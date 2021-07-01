<?php

namespace App\Http\Controllers;

// Import classes
use DB;
use Exception;
use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;
use Shapefile\ShapefileWriter;
use Shapefile\Geometry\Point;

use App\Traits\GlobalFunction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;

class DataList extends Controller
{
    //
    use GlobalFunction;


    public function listDatasetFilter(Request $request) {
        try {
            $data = DB::table('dataset')->Join('metadata', 'metadata.dataset_id', '=', 'dataset.id')->select('dataset_id','dataset_name','created_date','created_by','category','subcategory','type','approval_ind','public_ind','description','image');
            if($request->input('category')){
                $data->whereIn('category',$request->input('category'));
            }
            if($request->input('subcategory')){
                $data->whereIn('subcategory',$request->input('subcategory'));
            }
            if($request->input('extensions')){
                $data->whereIn('type',$request->input('extensions'));
            }
            if($request->input('created_by')){
                $data->whereCreated_by($request->input('created_by'));
            }
            if($request->input('dataset_name')){
                $data->where('dataset_name', 'like', '%' . $request->input('dataset_name') . '%');
            }
            if($request->input('sort_by') != "created_date"){
                $data->orderBy($request->input('sort_by'), 'asc');
            }
            else{
                $data->orderBy($request->input('sort_by'), 'desc');
            }

            if($request->input('startyear') AND $request->input('endyear')){
                $data->whereBetween('created_date', [$request->input('startyear').'-01-01', $request->input('endyear').'-12-30']);
            }elseif($request->input('startyear')){
                $data->whereYear('created_date', '=', $request->input('startyear'));
                // $data->whereCreated_date($request->input('startdate'));
            }elseif($request->input('enddate')){
                $data->whereYear('created_date', '=', $request->input('endyear'));
            }

            $dataset = $data->get();

        } catch (Exception $e) {
            $dataset = 'error';
        }
        return $dataset;
    }
    public function update_metadata(Request $request)
    {
        $checkMetadata = DB::table('metadata')->whereId($request->id)->first();
        if (!empty($checkMetadata)) {
            $exportData = json_decode($checkMetadata->exportData, true);
            $exportData = $request->exportData;
            $metadata = DB::table('metadata')
            ->where('id', $request->id)
            ->update(['exportData' => json_encode($exportData)]);
            $response = DB::table('metadata')->whereId($request->id)->first();
            return $this->success($response);
        } else {
            return $this->error('Metadata is not found');
        }
    }

    public function metadata(Request $request)
    {
        $checkMetadata = DB::table('metadata')->whereId($request->id)->first();
        if (!empty($checkMetadata)) {
            return $this->success($checkMetadata);
        } else {
            return $this->error('Metadata is not found');
        }
    }
    public function listDataset(Request $request) {
        $filter_name = $request['filter_name'];
        $sort = $request['sort'];

        if($sort === '' || $sort === null){
            $code = '';
        }else{            
            $code = "ORDER BY $sort ASC";
        }

        try {
            if($filter_name == '' || $filter_name == null){
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                FROM (
                                    SELECT *
                                    FROM public.metadata 
                                    WHERE  approval_ind = 'approved' AND public_ind = 'public' 
                                    )AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");   
            } else if($filter_name == 'all'){
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description, approval_ind
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                FROM (
                                    SELECT *
                                    FROM public.dataset 
                                    )AS merge
                                INNER JOIN public.metadata AS meta ON (merge.id = meta.dataset_id)
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        "); 
            } else if($filter_name === 'extensions') {
                $extension = $request['extension'];
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                FROM (
                                    SELECT *
                                    FROM public.metadata 
                                    WHERE type = '$extension' AND approval_ind = 'approved' AND public_ind = 'public' 
                                    )AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");   
            } else if($filter_name === 'date') {
                $year = $request['year'];
                $month = $request['month'];
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                        FROM(
                            SELECT
                            MG.*,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.* 
                                FROM (
                                    SELECT *
                                    FROM public.dataset 
                                    WHERE date_part('year', created_date) = $year AND
                                    date_part('month', created_date) = $month
                                    ORDER BY id ASC 
                                    )AS merge
                                INNER JOIN public.metadata AS meta ON (merge.id = meta.dataset_id)
                                WHERE meta.public_ind = 'public' AND meta.approval_ind = 'approved'                                 
                            ) as MG
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");   
            } else if($filter_name === 'category') {
                $category = $request['category'];
                $subcategory = $request['subcategory'];
                if($subcategory && $subcategory !== '') {
                    $dataset = DB::select("
                            SELECT 
                            dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                    FROM (
                                        SELECT *
                                        FROM public.dataset 
                                        WHERE category = '$category' AND subcategory = '$subcategory'
                                        )AS merge
                                    INNER JOIN public.metadata AS meta ON (merge.id = meta.dataset_id)
                                    WHERE meta.public_ind = 'public' AND meta.approval_ind = 'approved' 
                                ) as MERGE
                            ) as rank
                            WHERE rank=1
                            $code;
                            "); 
                }else{
                    $dataset = [];
                }  
            } else if($filter_name === 'onlycategory') {
                $category = $request['category'];
                if($category && $category !== '') {
                    $dataset = DB::select("
                            SELECT 
                            dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                    FROM (
                                        SELECT *
                                        FROM public.dataset 
                                        WHERE category = '$category'
                                        )AS merge
                                    INNER JOIN public.metadata AS meta ON (merge.id = meta.dataset_id)
                                    WHERE meta.public_ind = 'public' AND meta.approval_ind = 'approved' 
                                ) as MERGE
                            ) as rank
                            WHERE rank=1
                            $code;
                            "); 
                }else{
                    $dataset = [];
                }  
            } else if($filter_name === 'search') {
                $file_name = $request['file_name'];
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.* 
                                FROM public.metadata AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                WHERE (lower (meta.file_name)  LIKE lower ('%' || '$file_name' || '%') OR lower( merge.dataset_name) LIKE lower ('%' || '$file_name' || '%'))
                                AND meta.public_ind = 'public' AND meta.approval_ind = 'approved' 
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");
            } else if($filter_name === 'searchApp') {
                $file_name = $request['file_name'];
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.* 
                                FROM public.metadata AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                WHERE (lower (meta.file_name)  LIKE lower ('%' || '$file_name' || '%') OR lower( merge.dataset_name) LIKE lower ('%' || '$file_name' || '%')) AND meta.approval_ind = 'waiting' 
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");   
            } else if($filter_name === 'searchAppUser') {
                $username = $request['created_by'];
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.* 
                                FROM public.metadata AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                WHERE lower( merge.created_by) LIKE lower ('%' || '$username' || '%') AND meta.approval_ind = 'waiting' 
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");   
             } else if($filter_name === 'searchAppUserDataset') {
                $username = $request['created_by'];
                $dataset = DB::select("
                        SELECT 
                        dataset_name, created_date, created_by, category, subcategory, type, description, approval_ind
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.* 
                                FROM public.metadata AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                WHERE lower( merge.created_by) LIKE lower ('%' || '$username' || '%') 
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");   
            } else if($filter_name === 'searchAppDataset') {
                $file_name = $request['file_name'];
                $dataset = DB::select("
                        SELECT 
                        dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description, approval_ind
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.* 
                                FROM public.metadata AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                WHERE lower (meta.file_name)  LIKE lower ('%' || '$file_name' || '%') OR lower( merge.dataset_name) LIKE lower ('%' || '$file_name' || '%')  
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");            
            } else if($filter_name === 'advsearch') {
                $file_name = $request['file_name'];
                $category = $request['category'];
                $subcategory = $request['subcategory'];
                $tag = $request['tag'];
                if($category == ''){
                    $datasetValue = DB::select("
                            SELECT 
                            dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description, tag
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*, mt.tag
                                    FROM public.metadata AS meta
                                    INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                    INNER JOIN public.metadata_tag as mt on (meta.id = mt.metadata_id)
                                    WHERE (lower (meta.file_name)  LIKE lower ('%' || '$file_name' || '%') OR lower( merge.dataset_name) LIKE lower ('%' || '$file_name' || '%'))
                                    AND meta.approval_ind = 'approved' AND meta.public_ind = 'public'
                                ) as MERGE
                            ) as rank
                            WHERE rank=1
                            $code;
                            "); 
                }else if($subcategory == ''){
                    $datasetValue = DB::select("
                            SELECT 
                            dataset_name, created_date, created_by, category, subcategory, type, description
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*,mt.tag
                                    FROM public.metadata AS meta
                                    INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                    INNER JOIN public.metadata_tag as mt on (meta.id = mt.metadata_id)
                                    WHERE (lower (meta.file_name)  LIKE lower ('%' || '$file_name' || '%') OR lower( merge.dataset_name) LIKE lower ('%' || '$file_name' || '%'))
                                    AND category = '$category' AND meta.approval_ind = 'approved' AND meta.public_ind = 'public'
                                ) as MERGE
                            ) as rank
                            WHERE rank=1
                            $code;
                            ");   
                }else{
                    $datasetValue = DB::select("
                            SELECT 
                            dataset_name, created_date, created_by, category, subcategory, type, description
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*,mt.tag
                                    FROM public.metadata AS meta
                                    INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                    INNER JOIN public.metadata_tag as mt on (meta.id = mt.metadata_id)
                                    WHERE (lower (meta.file_name)  LIKE lower ('%' || '$file_name' || '%') OR lower( merge.dataset_name) LIKE lower ('%' || '$file_name' || '%'))
                                    AND category = '$category' AND subcategory = '$subcategory' AND meta.approval_ind = 'approved' AND meta.public_ind = 'public'
                                ) as MERGE
                            ) as rank
                            WHERE rank=1
                            $code;
                            ");   
                }
                $dataset = array();
                if($tag){
                    foreach($datasetValue as &$value){
                        $approval = true;
                        $listTag = explode(';',$value->tag);
                        foreach($tag as $key){                            
                            if(!in_array($key,$listTag)){
                                $approval = false;
                            }
                        }
                        if($approval){                            
                            array_push($dataset,$value);
                        }
                    }  
                    // var_dump($dataset);
                }else{
                    $dataset = $datasetValue;
                    // var_dump($dataset);
                }            
            } else if($filter_name == 'approval'){
                $dataset = DB::select("
                        SELECT 
                        dataset_id,dataset_name, created_date, created_by, category, subcategory, type, description
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                FROM (
                                    SELECT *
                                    FROM public.metadata 
                                    WHERE  approval_ind ='waiting' 
                                    )AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        $code;
                        ");                    
            }
        } catch (Exception $e) {
            $dataset = [];
        }

        return $dataset;
    }
    
    public function listMyDataset(Request $request) {
        $username = $request['username']; //nanti diganti user
        $filter_name = $request['filter_name'];
        try {
            if($filter_name == '' || $filter_name == null){
                $dataset = DB::select("
                        SELECT 
                        id, dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description, approval_ind, public_ind
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                FROM (
                                    SELECT *
                                    FROM public.dataset 
                                    WHERE created_by='$username' 
                                    )AS merge
                                INNER JOIN public.metadata AS meta ON (merge.id = meta.dataset_id)
                            ) as MERGE
                        ) as rank
                        WHERE rank=1
                        ");
            } else if($filter_name === 'extensions') {
                $extension = $request['extension'];
                $dataset = DB::select("
                        SELECT 
                        id, dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description, approval_ind, public_ind
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                FROM (
                                    SELECT *
                                    FROM public.metadata 
                                    WHERE updated_by='$username' AND type = '$extension'
                                    )AS meta
                                INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                            ) as MERGE
                        ) as rank
                        WHERE rank=1;
                        ");   
            } else if($filter_name === 'date') {
                $year = $request['year'];
                $month = $request['month'];
                $dataset = DB::select("
                        SELECT 
                        id, dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description, approval_ind, public_ind
                        FROM(
                            SELECT
                            *,
                            RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                            FROM(
                                SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                FROM (
                                    SELECT *
                                    FROM public.dataset 
                                    WHERE created_by='$username' AND
                                    date_part('year', created_date) = $year AND
                                    date_part('month', created_date) = $month
                                    ORDER BY id ASC 
                                    )AS merge
                                INNER JOIN public.metadata AS meta ON (merge.id = meta.dataset_id)
                            ) as MERGE
                        ) as rank
                        WHERE rank=1;
                        ");   
            } else if($filter_name === 'category') {
                $category = $request['category'];
                $subcategory = $request['subcategory'];
                if($subcategory && $subcategory !== '') {
                    $dataset = DB::select("
                            SELECT 
                            id, dataset_id, dataset_name, created_date, created_by, category, subcategory, type, description, approval_ind, public_ind
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                    FROM (
                                        SELECT *
                                        FROM public.dataset 
                                        WHERE created_by='$username' AND category = '$category' AND subcategory = '$subcategory'
                                        )AS merge
                                    INNER JOIN public.metadata AS meta ON (merge.id = meta.dataset_id)
                                ) as MERGE
                            ) as rank
                            WHERE rank=1;
                            "); 
                }else{
                    $dataset = [];
                }  
            }
        } catch (Exception $e) {
            $dataset = [];
        }

        return $dataset;
    } 

    public function perDataset(Request $request) {
        $dataset_name = $request['dataset_name'];
        $created_by = $request['created_by'];
        
        try {
            $dataset = DB::table('dataset')
                        ->where([
                            ['dataset_name', $dataset_name],
                            ['created_by', $created_by],
                        ])
                        ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')
                        ->join('user', 'dataset.created_by', '=', 'user.username')
                        ->join('metadata_tag', 'metadata_tag.metadata_id', '=', 'metadata.id')
                        ->select('dataset.dataset_name', 'dataset.view_counter', 'dataset.download_counter', 'dataset.created_by','user.job_title','user.company','user.phone', 'dataset.created_date', 'dataset.category', 'dataset.subcategory', 'metadata.type', 'metadata.description', 'metadata.file_name', 'metadata.size', 'metadata_tag.tag')
                        ->orderBy('dataset.created_date', 'desc')
                        ->get();
        } catch (Exception $e) {
            $dataset = [];
        }

        return $dataset;
    }
    public function filterShp(Request $request) {    
        try {              
            $user = $request['username'];
            $filter = $request['filter'];
            $extension="shp";
            if($filter === 'getCategory'){
                $category = DB::table('dataset')
                            ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where('type',$extension)
                            ->select('category as value')
                            ->distinct()
                            ->addSelect(DB::raw("category as text"))
                            ->orderBy('category')
                            ->get();               
                $migas = DB::table('dataset')
                            ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([

                                ['category', 'migas'],
                                ['type',$extension],
                                ]
                                )
                            ->select('subcategory as value')
                            ->distinct()
                            ->addSelect(DB::raw("subcategory as text"))
                            ->orderBy('subcategory')
                            ->get();               
                $batubara = DB::table('dataset')
                ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([

                                ['category', 'batubara'],
                                ['type',$extension],
                                ]
                                )
                ->select('subcategory as value')
                            ->distinct()
                            ->addSelect(DB::raw("subcategory as text"))
                            ->orderBy('subcategory')
                            ->get();              
                $panasbumi = DB::table('dataset')
                ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([

                                ['category', 'panasbumi'],
                                ['metadata.type',$extension],
                                ]
                                )
                            ->select('subcategory as value')
                            ->distinct()
                            ->addSelect(DB::raw("subcategory as text"))
                            ->orderBy('subcategory')
                            ->get();                
                $directory['category'] = $category;
                $directory['batubara'] = $batubara;
                $directory['panasbumi'] = $panasbumi;
                $directory['migas'] = $migas;
            }else if($user === '' || $user === null){
                $category = DB::table('dataset')
                            ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                                ['metadata.type', 'shp'],
                            ])  
                            ->select('category','subcategory')
                            ->distinct()
                            ->orderBy('category')
                            ->get(); 
                $date = DB::select("                            
                            SELECT 
                            date_part('year', created_date) year_date,
                            date_part('month', created_date) month_date
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                    FROM (
                                        SELECT *
                                        FROM public.metadata 
                                        WHERE  approval_ind = 'approved' AND public_ind = 'public' 
                                        )AS meta
                                    INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                ) as MERGE
                            ) as rank
                            WHERE rank=1
                            GROUP BY
                            year_date, month_date ORDER BY year_date,
                            month_date DESC;
                            ");
                $extension = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                            ])  
                            ->select('type')
                            ->distinct()
                            ->get();
                $directory = array();
                $directory['category'] = $category;
                $directory['date'] = $date;
                $directory['extension'] = $extension;
            }else{
                $category = DB::table('dataset')
                            ->select('category','subcategory')
                            ->where('created_by', $user)
                            ->distinct()
                            ->orderBy('category')
                            ->get(); 
                $date = DB::select("
                            SELECT date_part('year', created_date) year_date,
                            date_part('month', created_date) month_date 
                            FROM public.dataset                             
                            WHERE created_by='$user' 
                            GROUP BY year_date, month_date 
                            ORDER BY year_date, month_date DESC;
                            ");
                $extension = DB::table('metadata')
                            ->select('type')
                            ->where('updated_by', $user)
                            ->distinct()
                            ->get();
                $directory = array();
                $directory['category'] = $category;
                $directory['date'] = $date;
                $directory['extension'] = $extension;
            }            
        } catch (Exception $e) {
            $directory = [];
        }

        return $directory;
    }
    public function filterDataset(Request $request) {    
        try {              
            $user = $request['username'];
            $filter = $request['filter'];
            if($filter === 'getCategory'){
                $category = DB::table('dataset')
                            ->select('category as value')
                            ->distinct()
                            ->addSelect(DB::raw("category as text"))
                            ->orderBy('category')
                            ->get();               
                $migas = DB::table('dataset')
                            ->select('subcategory as value')
                            ->where('category', 'migas')
                            ->distinct()
                            ->addSelect(DB::raw("subcategory as text"))
                            ->orderBy('subcategory')
                            ->get();               
                $batubara = DB::table('dataset')
                            ->select('subcategory as value')
                            ->where('category', 'batubara')
                            ->distinct()
                            ->addSelect(DB::raw("subcategory as text"))
                            ->orderBy('subcategory')
                            ->get();              
                $panasbumi = DB::table('dataset')
                            ->select('subcategory as value')
                            ->where('category', 'panasbumi')
                            ->distinct()
                            ->addSelect(DB::raw("subcategory as text"))
                            ->orderBy('subcategory')
                            ->get();                
                $directory['category'] = $category;
                $directory['batubara'] = $batubara;
                $directory['panasbumi'] = $panasbumi;
                $directory['migas'] = $migas;
            }else if($user === '' || $user === null){
                $category = DB::table('dataset')
                            ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                            ])  
                            ->select('category','subcategory')
                            ->distinct()
                            ->orderBy('category')
                            ->get(); 
                $date = DB::select("                            
                            SELECT 
                            date_part('year', created_date) year_date,
                            date_part('month', created_date) month_date
                            FROM(
                                SELECT
                                *,
                                RANK() OVER (PARTITION BY dataset_id ORDER BY id DESC)
                                FROM(
                                    SELECT merge.dataset_name, merge.created_date, merge.created_by, merge.category, merge.subcategory, merge.view_counter, merge.download_counter, meta.*
                                    FROM (
                                        SELECT *
                                        FROM public.metadata 
                                        WHERE  approval_ind = 'approved' AND public_ind = 'public' 
                                        )AS meta
                                    INNER JOIN public.dataset AS merge ON (meta.dataset_id = merge.id)
                                ) as MERGE
                            ) as rank
                            WHERE rank=1
                            GROUP BY
                            year_date, month_date ORDER BY year_date,
                            month_date DESC;
                            ");
                $extension = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                            ])  
                            ->select('type')
                            ->distinct()
                            ->get();
                $directory = array();
                $directory['category'] = $category;
                $directory['date'] = $date;
                $directory['extension'] = $extension;
            }else{
                $category = DB::table('dataset')
                            ->select('category','subcategory')
                            ->where('created_by', $user)
                            ->distinct()
                            ->orderBy('category')
                            ->get(); 
                $date = DB::select("
                            SELECT date_part('year', created_date) year_date,
                            date_part('month', created_date) month_date 
                            FROM public.dataset                             
                            WHERE created_by='$user' 
                            GROUP BY year_date, month_date 
                            ORDER BY year_date, month_date DESC;
                            ");
                $extension = DB::table('metadata')
                            ->select('type')
                            ->where('updated_by', $user)
                            ->distinct()
                            ->get();
                $directory = array();
                $directory['category'] = $category;
                $directory['date'] = $date;
                $directory['extension'] = $extension;
            }            
        } catch (Exception $e) {
            $directory = [];
        }

        return $directory;
    }

    public function getShpFileListList(Request $request) {  
         /*  $user = 'admin';   */
         $user = $request['created_by'];    
          $category = $request['category'];    
         $subcategory = $request['subcategory'];     
         
        try {              
            $directory = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                                ['type', 'shp'],
                                ['category', $category],
                                ['subcategory', $subcategory], 
                            ])                               
                            ->orWhere([
                                    ['created_by', $user],
                                    ['category', $category],
                                    ['subcategory', $subcategory], 
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'private'],
                            ])
                            ->select('created_by as label')    
                            ->distinct()                 
                            ->addSelect(DB::raw("false as isDefaultExpanded"))
                            ->get(); 
            $i=1;
            foreach($directory as &$value){
                $value->id = $i;
                $i++;
                $dataset = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                                ['type', 'shp'],
                                ['created_by', $value->label],
                                ['category', $category],
                                ['subcategory', $subcategory], 
                            ])                               
                            ->orWhere([
                                    ['created_by', $user],
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'private'],
                                    ['category', $category],
                                    ['subcategory', $subcategory], 
                            ])
                            ->select('dataset_name as label','dataset.id')     
                            ->distinct()                 
                            ->addSelect(DB::raw("true as isDefaultExpanded"))
                            ->get(); 
                foreach($dataset as &$valueDataset){                    
                    $file = DB::table('metadata')
                                ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                                ->where([
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'public'],
                                    ['type', 'shp'],
                                    ['dataset_name', $valueDataset->label],
                                    ['created_by', $value->label],
                                    ['category', $category],
                                    ['subcategory', $subcategory], 
                                ])                               
                                ->orWhere([
                                        ['created_by', $user],
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'private'],
                                        ['category', $category],
                                        ['subcategory', $subcategory], 
                                ])
                                ->select('metadata.id','path','type as extension','file_name as label','created_by','dataset_name') 
                                ->get(); 
                    $valueDataset->selDisabled = true;         
                    $valueDataset->chkDisabled = true; 
                    $valueDataset->children = $file;
                }           
                $value->selDisabled = true;         
                $value->chkDisabled = true; 
                // $i=1;
                // foreach($dataset as &$key){
                //     $key->id =$i;
                //     $i++;
                // }   
                $value->children = $dataset;
            }
        } catch (Exception $e) {
            $directory = [];
        }

        return $directory;
    }
    public function getShpFileList(Request $request) {  
         /*  $user = 'admin';   */
         $user = $request['created_by'];    
         /* $category = $request['created_by'];    
         $subcategory = $request['subcategory'];     */
         
        try {              
            $directory = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                                ['type', 'shp'],
                                /* ['category', $category],
                                ['subcategory', $subcategory], */
                            ])                               
                            ->orWhere([
                                    ['created_by', $user],
                                    /* ['category', $category],
                                    ['subcategory', $subcategory], */
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'private'],
                            ])
                            ->select('created_by as title')    
                            ->distinct()                 
                            ->addSelect(DB::raw("false as expanded"))
                            ->get(); 
            foreach($directory as &$value){
                $dataset = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                                ['type', 'shp'],
                                ['created_by', $value->title],
                            ])                               
                            ->orWhere([
                                    ['created_by', $user],
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'private'],
                            ])
                            ->select('dataset_name as title')     
                            ->distinct()                 
                            ->addSelect(DB::raw("false as expanded"))
                            ->get(); 
                foreach($dataset as &$valueDataset){                    
                    $file = DB::table('metadata')
                                ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                                ->where([
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'public'],
                                    ['type', 'shp'],
                                    ['dataset_name', $valueDataset->title],
                                    ['created_by', $value->title],
                                ])                               
                                ->orWhere([
                                        ['created_by', $user],
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'private'],
                                ])
                                ->select('metadata.id','path','type as extension','file_name as title','created_by','dataset_name') 
                                ->get(); 
                    $valueDataset->selDisabled = true;         
                    $valueDataset->chkDisabled = true;  
                    $valueDataset->children = $file;
                }           
                $value->selDisabled = true;         
                $value->chkDisabled = true;   
                $value->children = $dataset;
            }
        } catch (Exception $e) {
            $directory = [];
        }

        return $directory;
    }

    public function getAllFileList(Request $request) {   
        $category = $request['category'];
        $subcategory = $request['subcategory']; 
        $user = $request['created_by']; 
        try {  
            if($category == '' || $category == 'all' || $category == null){
                $directory = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                            ])                              
                            ->orWhere([
                                    ['created_by', $user],
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'private'],
                            ])
                            ->select('created_by as title')    
                            ->distinct()                 
                            ->addSelect(DB::raw("false as expanded"))
                            ->get(); 
                foreach($directory as &$value){
                    $dataset = DB::table('metadata')
                                ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                                ->select('dataset_name as title')  
                                ->where([
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'public'],
                                    ['created_by', $value->title],
                                ])                             
                                ->orWhere([
                                        ['created_by', $user],
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'private'],
                                ]) 
                                ->distinct()                 
                                ->addSelect(DB::raw("false as expanded"))
                                ->get(); 
                    foreach($dataset as &$valueDataset){                    
                        $file = DB::table('metadata')
                                    ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                                    ->select('path','type as extension','file_name as title','exportData') 
                                    ->where([
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'public'],
                                        ['dataset_name', $valueDataset->title],
                                        ['created_by', $value->title],
                                    ])                             
                                    ->orWhere([
                                            ['created_by', $user],
                                            ['approval_ind', 'approved'],
                                            ['public_ind', 'private'],
                                    ])
                                    ->get(); 
                        $valueDataset->selDisabled = true;         
                        $valueDataset->chkDisabled = true;  
                        $valueDataset->children = $file;
                    }          
                    $value->selDisabled = true;         
                    $value->chkDisabled = true;        
                    $value->children = $dataset;
                }
            }else if($subcategory == 'all' || $subcategory == null || $subcategory == ''){
                $directory = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                                ['category', $category],
                            ])                               
                            ->orWhere([
                                    ['created_by', $user],
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'private'],
                            ])
                            ->select('created_by as title')   
                            ->distinct()                 
                            ->addSelect(DB::raw("false as expanded"))
                            ->get(); 
                foreach($directory as &$value){
                    $dataset = DB::table('metadata')
                                ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id') 
                                ->where([
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'public'],
                                    ['created_by', $value->title],
                                    ['category', $category],
                                ])                             
                                ->orWhere([
                                        ['created_by', $user],
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'private'],
                                ])
                                ->select('dataset_name as title')      
                                ->distinct()                 
                                ->addSelect(DB::raw("false as expanded"))
                                ->get(); 
                    foreach($dataset as &$valueDataset){                    
                        $file = DB::table('metadata')
                                    ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                                    ->where([
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'public'],
                                        ['dataset_name', $valueDataset->title],
                                        ['created_by', $value->title],
                                        ['category', $category],
                                    ])                            
                                    ->orWhere([
                                            ['created_by', $user],
                                            ['approval_ind', 'approved'],
                                            ['public_ind', 'private'],
                                    ])
                                    ->select('path','type as extension','file_name as title')  
                                    ->get(); 
                        $valueDataset->selDisabled = true;         
                        $valueDataset->chkDisabled = true;  
                        $valueDataset->children = $file;
                    }        
                    $value->selDisabled = true;         
                    $value->chkDisabled = true;     
                    $value->children = $dataset;
                }
            }else{
                $directory = DB::table('metadata')
                            ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id') 
                            ->where([
                                ['approval_ind', 'approved'],
                                ['public_ind', 'public'],
                                ['category', $category],
                                ['subcategory', $subcategory],
                            ])                             
                            ->orWhere([
                                    ['created_by', $user],
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'private'],
                            ])
                            ->select('created_by as title')  
                            ->distinct()                 
                            ->addSelect(DB::raw("false as expanded"))
                            ->get(); 
                foreach($directory as &$value){
                    $dataset = DB::table('metadata')
                                ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                                ->where([
                                    ['approval_ind', 'approved'],
                                    ['public_ind', 'public'],
                                    ['created_by', $value->title],
                                    ['category', $category],
                                    ['subcategory', $subcategory],
                                ])                              
                                ->orWhere([
                                        ['created_by', $user],
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'private'],
                                ]) 
                                ->select('dataset_name as title')   
                                ->distinct()                 
                                ->addSelect(DB::raw("false as expanded"))
                                ->get(); 
                    foreach($dataset as &$valueDataset){                    
                        $file = DB::table('metadata')
                                    ->join('dataset', 'dataset.id', '=', 'metadata.dataset_id')
                                    ->where([
                                        ['approval_ind', 'approved'],
                                        ['public_ind', 'public'],
                                        ['dataset_name', $valueDataset->title],
                                        ['created_by', $value->title],
                                        ['category', $category],
                                        ['subcategory', $subcategory],
                                    ])                            
                                    ->orWhere([
                                            ['created_by', $user],
                                            ['approval_ind', 'approved'],
                                            ['public_ind', 'private'],
                                    ])
                                    ->select('path','type as extension','file_name as title','exportData') 
                                    ->get(); 
                        $valueDataset->selDisabled = true;         
                        $valueDataset->chkDisabled = true;  
                        $valueDataset->children = $file;
                    }    
                    $value->selDisabled = true;         
                    $value->chkDisabled = true;       
                    $value->children = $dataset;
                }            
            }
        } catch (Exception $e) {
            $directory = [];
        }

        return $directory;
    }

    public function updatePublic(Request $request) {
        $data_public = $request['data_public'];  
        foreach($data_public as $value){
            $dataset = DB::table('metadata')
                        ->where('dataset_id', $value['dataset_id'])
                        ->update(['public_ind' => $value['public_ind']]);
        }
    }

    public function openPdf($user, $dataset, $file) {    
        // Store the file name into variable 
        $filePdf = storage_path('/app/public/').$user.'/'.$dataset.'/'.$file;
        $filename = $filePdf;
        
        // Header content type 
        header('Content-type: application/pdf'); 
        
        header('Content-Disposition: inline; filename="' . $filename . '"'); 
        
        header('Content-Transfer-Encoding: binary'); 
        
        header('Accept-Ranges: bytes'); 
        
        // Read the file 
        @readfile($filePdf); 
    }

    public function openImg($user, $dataset, $file) {    
        $remoteImage = storage_path('/app/public/').$user.'/'.$dataset.'/'.$file;
        $imginfo = getimagesize($remoteImage);
        header("Content-type: {$imginfo['mime']}");
        readfile($remoteImage);
    }

    public function listImgAboutUs() {
        $directory = storage_path('/app/aboutus/img/');
        $listFile = scandir($directory);
        $getFile = array();
        foreach($listFile as $list){
            if($list != '.' && $list != '..'){
                $table = array();
                $table['img'] = $list;
                $table['path'] = $directory.$list;
                array_push($getFile,$table);
            }
        }
        return $getFile;
    }

    public function openImgAboutUs($file) {    
        $remoteImage = storage_path('/app/aboutus/img/').$file;
        $imginfo = getimagesize($remoteImage);
        header("Content-type: {$imginfo['mime']}");
        readfile($remoteImage);
    }

    public function openTxt($user, $dataset, $file) {    
        $remoteImage = storage_path('/app/public/').$user.'/'.$dataset.'/'.$file;
        $file = fopen($remoteImage,"r") or die("Unable to open file!");
        while(! feof($file))
        {
            echo fgets($file). "<br />";
        }
        fclose($file);
    }

    public function saveTxtFile(Request $request) {
        $text = $request['text'];
        if($text != null){
            $path = storage_path('/app/aboutus/aboutus.txt');
            $myfile = fopen($path, "w");            
            fwrite($myfile, $text);
            fclose($myfile);
        }
    }

    public function getTxtFile() {
        $path = storage_path('/app/aboutus/aboutus.txt');
        if(file_exists($path)){
            $myfile = fopen($path, "r");    
            $getSize = filesize($path);
            if($getSize == 0){
                $dataText = '';
            }else{
                $dataText = fread($myfile,filesize($path));
            }       
            fclose($myfile);
        }else{
            $dataText = '';
        }
        return $dataText;
    }
    
    public function fetchData($user, $dataset, $file) {    
        $remoteImage = storage_path('/app/public/').$user.'/'.$dataset.'/'.$file;
        if (file_exists($remoteImage)) {
            //The URL that accepts the file upload.
            $url = env('NODE_URL').'upload-file';

            //The name of the field for the uploaded file.
            $uploadFieldName = 'filename';
            
            //The full path to the file that you want to upload
            $filePath = $remoteImage;
            
            
            //Initiate cURL
            $ch = curl_init();
            
            //Set the URL
            curl_setopt($ch, CURLOPT_URL, $url);
            
            //Set the HTTP request to POST
            curl_setopt($ch, CURLOPT_POST, true);
            
            //Tell cURL to return the output as a string.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            //If the function curl_file_create exists
            if(function_exists('curl_file_create')){
                //Use the recommended way, creating a CURLFile object.
                $filePath = curl_file_create($filePath);
            } else{
                //Otherwise, do it the old way.
                //Get the canonicalized pathname of our file and prepend
                //the @ character.
                $filePath = '@' . realpath($filePath);
                //Turn off SAFE UPLOAD so that it accepts files
                //starting with an @
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            }
            
            //Setup our POST fields
            $postFields = array(
                $uploadFieldName => $filePath,
                'blahblah' => 'Another POST FIELD'
            );
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            
            //Execute the request
            $result = curl_exec($ch);
            
            //If an error occured, throw an exception
            //with the error message.
            if(curl_errno($ch)){
                throw new Exception(curl_error($ch));
            }
            
            //Print out the response from the page
            echo $result; 

            // header('Content-Description: File Transfer');
            // header('Content-Type: application/octet-stream');
            // header('Content-Disposition: attachment; filename="'.basename($remoteImage).'"');
            // header('Expires: 0');
            // header('Cache-Control: must-revalidate');
            // header('Pragma: public');
            // header('Content-Length: ' . filesize($remoteImage));
            // readfile($remoteImage);
            exit;
        }else {            
            return $remoteImage;
        }
    }
    
    public function changePublic($setPublic,$dataset_id) {              
         DB::update('update metadata set public_ind = ? where dataset_id = ?',[$setPublic,$dataset_id]);
        echo "Record updated successfully.
        ";
     
    }
    public function publishData($verif,$id) {
         DB::update('update metadata set approval_ind = ? where dataset_id = ?',[$verif,$id]);
        echo "Record updated successfully.
        ";
    }
    public function getStats() {    
            $count = DB::table('dataset')            
                    ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')      
                    ->select(               
                        DB::raw("count(DISTINCT dataset_id) as cat_count"),
                        'category',         
                    )
                    ->where([
                        ['approval_ind', 'approved'],
                        ['public_ind', 'public'],
                    ])
                    ->groupBy('category')
                    ->get();
            // var_dump($count);
            $batubara = DB::table('dataset')            
                    ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')   
                    ->select(               
                        DB::raw("count(DISTINCT dataset_id) as subcat_count"),
                        'subcategory',         
                    )                 
                    ->where([
                        ['approval_ind', 'approved'],
                        ['public_ind', 'public'],
                        ['category', '=', 'batubara']
                    ])    
                    ->groupBy('subcategory')
                    ->orderBy('subcategory')
                    ->get(); 
            $migas = DB::table('dataset')            
                    ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')   
                    ->select(               
                        DB::raw("count(DISTINCT dataset_id) as subcat_count"),
                        'subcategory',         
                    )                    
                    ->where([
                        ['approval_ind', 'approved'],
                        ['public_ind', 'public'],
                        ['category', '=', 'migas']
                    ])    
                    ->groupBy('subcategory')
                    ->orderBy('subcategory')
                    ->get();
            $panasbumi = DB::table('dataset')            
                    ->join('metadata', 'dataset.id', '=', 'metadata.dataset_id')   
                    ->select(               
                        DB::raw("count(DISTINCT dataset_id) as subcat_count"),
                        'subcategory',         
                    )                    
                    ->where([
                        ['approval_ind', 'approved'],
                        ['public_ind', 'public'],
                        ['category', '=', 'panasbumi']
                    ])    
                    ->groupBy('subcategory')
                    ->orderBy('subcategory')
                    ->get();

            $stat = array();
            $stat['cat'] = $count;
            $stat['batubara'] = $batubara;
            $stat['migas'] = $migas;
            $stat['panasbumi'] = $panasbumi;  

        return $stat;
    }    
    
        public function coba(){
            $dataset = DB::select("
                        SELECT 
                        *
                        FROM dataset");   
                        dd($dataset);
             return $dataset;
        }
}