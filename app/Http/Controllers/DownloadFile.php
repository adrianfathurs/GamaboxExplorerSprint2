<?php
namespace App\Http\Controllers;
use ZipArchive;

// Import classes
use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DownloadFile extends Controller
{
    
    public function downloadfilename(Request $request) {

        $path = $request['path'];
        //$dataset_name = $request['dataset_name'];
        //$dataset_id =$request['dataset_id'];
        //DB::table('dataset')->where('dataset_name', $dataset_name)->update(['download_counter' => DB::raw('download_counter + 1')]);
        //DB::table('dataset')->where('$dataset_name')->increment('download_counter');
        $file_path = storage_path('app/public/'.$path);
        return response()->download($file_path);
        //return response()->download(storage_path("app/public/{$path}"));
    }

    public function downloadZip($username, $getDatasetName)
    {
        
        // $username = $request['created_by'];
        // $getDatasetName = $request['dataset_name'];
        /* $username = 'yusuf';
        $getDatasetName = 'hahaha'; */
        $folder = storage_path('app/public/').$username;
        $directory = storage_path('app/public/').$username.'/'.$getDatasetName;
        $fileName =  "{$getDatasetName}.zip";
        $zipPath = $username.'/'.$fileName;
        $filepath = storage_path("app/public/{$zipPath}");
        
        $zip = new ZipArchive;

        if ($zip->open($filepath, ZipArchive::CREATE) === TRUE)
        {
            $files = File::files($directory);
    
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
                
            $zip->close();
        }
        
        //DB::table('dataset')->where('dataset_name')->increment('download_counter');

        // return response()->download(storage_path("app/public/{$filepath}"));
        if(file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=$fileName");
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            unlink($filepath);
            die();
        } else {
            http_response_code(404);
            die();
        }
    }

    public function downloadperfile($getuser, $getdataset, $getfile) {
        // $getuser =  $request['getuser'];
        // $getdataset =  $request['getdataset'];
        // $getfile =  $request['getfile'];
        $filepath = storage_path('app/public/')."$getuser/$getdataset/$getfile";
        // Process download
        if(file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=$getfile");
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            die();
        } else {
            http_response_code(404);
	        die();
        }
    }
}