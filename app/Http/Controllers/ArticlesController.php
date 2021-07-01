<?php

namespace App\Http\Controllers;

// Import classes
use DB;
use Exception;
use App\Traits\GlobalFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;
use App\Articles;

class ArticlesController extends Controller
{
    //
    use GlobalFunction;


    public function index(Request $request) {
        $limit = 100;
        $offset = 0;
        if (!empty($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        if (!empty($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        $data = Articles::with('user:id,username')->orderBy('id','desc');
        if($request->input('status')){
            $data->whereStatus($request->input('status'));
        }
        if($request->input('user_id')){
            $data->whereUser_id($request->input('user_id'));
        }
        if($request->input('id')){
            $articles = $data->find($request->input('id'));
        }else{
            $articles = $data->offset($offset)->limit($limit)->get();
        }
        if($articles){
            return $this->success($articles);
        } else {
            return $this->error('Article is empty');
        }
    }
    

    public function add(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta'); 
		$created_at= date("Y-m-d H:i:s");
        if (!empty($request->user_id)) {
            $input['title'] = $request->title;
            $input['description'] = $request->description;
            $input['user_id'] = $request->user_id;
            $input['created_at'] = $created_at;
            $type = str_replace('image/','',$request->type);
            if(empty($type)) $type='png';
            $input['status'] = 'publish';
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time().'.'.$type; 
                $destinationPath = public_path('/images/articles');
                $image->move($destinationPath, $name);
                $input['image'] = 'images/articles/'.$name;
            }
            Articles::insert($input);
            return $this->success($input,'Success add article');
        } else {
            return $this->error('Failed is add article');
        }
    }

    public function edit(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta'); 
		$updated_at= date("Y-m-d H:i:s");
        $checkArticle = Articles::whereId($request->id)->first();
        if (!empty($checkArticle)) {
            $input['title'] = $request->title;
            $input['description'] = $request->description;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                /* $name = time().'.'.$image->getClientOriginalExtension(); */
                $name = time().'.png';
                $destinationPath = public_path('/images/articles');
                $image->move($destinationPath, $name);
                $input['image'] = 'images/articles/'.$name;

                //remove old image
            $old_file_path = public_path($checkArticle->image);
            File::delete($old_file_path);
            }
            $input['updated_at'] = $updated_at;
            Articles::whereId($request->id)->update($input);
            return $this->success($input,'Success update article');
        } else {
            return $this->error('Article is not found');
        }
    }

    public function delete(Request $request)
    {
        $checkArticle = Articles::whereId($request->id)->first();
        if (!empty($checkArticle)) {
            if($checkArticle->image){
            $old_file_path = public_path($checkArticle->image);
            File::delete($old_file_path);
            }
            Articles::whereId($request->id)->delete();
            return $this->success($checkArticle,'Success deleted article');
        } else {
            return $this->error('Article is not found');
        }
    }
   
}