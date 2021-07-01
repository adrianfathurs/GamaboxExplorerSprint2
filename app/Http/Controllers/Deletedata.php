<?php
namespace App\Http\Controllers;
use ZipArchive;

// Import classes
use DB;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class Deletedata extends Controller
{
    
    public function deletefile($path) {

        //$path = $request['path'];
        //$dataset_name = $request['dataset_name'];
        //$dataset_id =$request['dataset_id'];
        //DB::table('dataset')->where('dataset_name', $dataset_name)->update(['download_counter' => DB::raw('download_counter + 1')]);
        //DB::table('dataset')->where('$dataset_name')->increment('download_counter');
        $file_path = storage_path('app/public/'.$path);
        
        File::delete($filepath);
        DB::delete('delete from metadata where path = ?',[$path]);
        return response()->json([
            "message" => "Dataset deleted"
        ], 201);
    }

    public function deletedata($id, $username, $getDatasetName)
    {
        //$id = $request['id'];
        //$username = $request['created_by'];
        //$getDatasetName = $request['dataset_name'];
        /* $username = 'yusuf';
        $getDatasetName = 'hahaha'; */
        $folder = storage_path('app/public/').$username;
        $directory = storage_path('app/public/').$username.'/'.$getDatasetName;
        $fileName =  "{$getDatasetName}.zip";
        $filepath = $username.'/'.$fileName;
        // $deleteResponse = File::deleteDirectory($directory);
        // if(file_exists(storage_path("app/public/{$filepath}"))){
        //     File::delete(storage_path("app/public/{$filepath}"));
        // }
        DB::table('metadata')->where('id', $id)->delete();

        return response()->json([
            "message" => "Dataset deleted"
        ], 201);
    }

    public function removeDataset(Request $request) {        
        $username = $request['created_by'];         
        $dataset_name = $request['dataset_name'];      
        $dataset_id = $request['dataset_id'];
        $directory = storage_path('app/public/').$username.'/'.$dataset_name;

        $dataset_exist = DB::table('dataset')->where('id', $dataset_id)->exists();
        $metadata_exist = DB::table('metadata')->where('dataset_id', $dataset_id)->exists();
        $check_dataset = false;

        if($username === '' || $username === null || $dataset_name === '' || $dataset_name === null ){
            var_dump('request error');
        }else{
            if(is_dir($directory)) {
                $delete_file = File::deleteDirectory($directory);
                $check_dataset = true;
            }else{
                $check_dataset = true;
            }
        }

        if($dataset_exist || $metadata_exist || $check_dataset){
            $id = array();
            $dataset = DB::table('metadata')
            ->select('id')
            ->where('dataset_id', $dataset_id)
            ->get();
            // ->keyBy('id');
            foreach ($dataset as $key => $value) {
                array_push($id,$value->id);
            }

            $metadata_tag = DB::table('metadata_tag')
                            ->whereIn('metadata_id',$id)                            
                            ->delete();
            $metadata = DB::table('metadata')
                            ->where('dataset_id',$dataset_id)                            
                            ->delete();
            $dataset = DB::table('dataset')
                            ->where('id',$dataset_id)                            
                            ->delete();
            return 'success';
        }else{
            var_dump('db does not exist');            
        }
    }

    public function deleteImg(Request $request) {
        $path = $request['image'];
        File::delete($path);
    }
}