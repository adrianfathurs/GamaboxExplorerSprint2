<?php
namespace App\Http\Controllers;

// Import classes
use DB;
use Ramsey\Uuid\Uuid;
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
class UploadFile extends Controller
{
    
    use GlobalFunction;
    public function upload(Request $request) {
       
        ini_set('memory_limit', '-1'); 
        // Read file contents...
        $files = $request->file('file');
        $imageFile = $request->file('imageFile');
        $getImageName = $request['imageFileName'];
        $tagging = $request['tagging'];
        $username = $request['username']; // Nanti diganti pake get username, ini buat dummy
        $category = $request['category'];
        $subcategory = $request['subcategory'];
        $description = $request['description'];
        $getDatasetName = $request['file_name'];
        $imageFileName = $request['imageFileName'];
        $setPublic = 'public';
        
        if($request->hasFile('file'))
        {


            // $directory = storage_path('/app/public/').$extension_path;
            //$directory untuk path dataset
            $directory = storage_path('/app/public/').$username;
            //Perlu penambahan $diretoryiImageFile untuk image thumbnail dataset
            $full_directory = $directory.'/'.$getDatasetName;

            if (!file_exists($directory)){
                mkdir($directory, 0700);
                // dump('New Folder Created : '.$username);
                if (!file_exists($full_directory)){
                    mkdir($full_directory, 0700);
                    // dump('New Folder Created : '.$getDatasetName);
                }else{
                    // dump('Folder Already Exist : '.$getDatasetName);
                }
            }else{
                // $list = scandir($directory);
                // dump('Folder Already Exist : '.$username);
                if (!file_exists($full_directory)){
                    mkdir($full_directory, 0700);
                    // dump('New Folder Created : '.$getDatasetName);
                }else{
                    // dump('Folder Already Exist : '.$getDatasetName);
                }
            }


            // Insert Data to DB //////////////////////////////////////////////////////
            date_default_timezone_set("Asia/Bangkok");

            $current_date = date("Y-m-d H:i:s");

            $dataset_db = array();
            $metadata_db = array();
            $metadata_tag_db = array();

            $count = 0;
            
            $dataset_exist = DB::table('dataset')
                ->where([
                    ['created_by', $username],
                    ['dataset_name', $getDatasetName]
                ])
                ->exists();

            if($dataset_exist){
                // abort(400, 'This dataset name is already used');
                return response('This dataset name is already used', 400);
            }else{

                $dataset_id = Uuid::uuid1()->toString();
                $dataset_db[$count]['id'] = $dataset_id;
                $dataset_db[$count]['dataset_name'] = $getDatasetName;
                $dataset_db[$count]['created_date'] = $current_date;
                $dataset_db[$count]['created_by'] = $username;
                $dataset_db[$count]['category'] = $category;
                $dataset_db[$count]['subcategory'] = $subcategory;

                $type = str_replace('image/','',$request->type);
                if(empty($type)) $type='png';
                if ($request->hasFile('imageFile')) {
                    $image = $request->file('imageFile');
                    $name = time().'.'.$type; 
                    // $name = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/dataset');
                    $image->move($destinationPath, $name);
                    $dataset_db[$count]['image'] = 'images/dataset/'.$name;
                }
            }
            foreach ($files as $file) {
                // If you would like to get original name of the uploaded file
                $file_name = $file->getClientOriginalName();

                // If you would like to get size of the uploaded file
                $size = $file->getSize();

                // The extension method may be used to get the file extension of the uploaded file
                // $extension = $file->extension();
                $info = pathinfo($file_name);
                $extension = $info['extension'];

                // Name from user
                $name = $username.'/'.$getDatasetName.'/'.$file_name;

                if (!file_exists($name)){
                    
                    // Insert Data to DB //////////////////////////////////////////////////////
                    $metadata_id = Uuid::uuid1()->toString();
                    //$dataset_db[$count]['file_path'] = $name;  tunggu colom di DB
                    
                    $metadata_db[$count]['id'] = $metadata_id;
                    $metadata_db[$count]['dataset_id'] = $dataset_id;
                    $metadata_db[$count]['file_name'] = $file_name;        
                    $metadata_db[$count]['uploaded_date'] = $current_date;
                    $metadata_db[$count]['uploaded_by'] = $username;
                    $metadata_db[$count]['updated_date'] = $current_date;
                    $metadata_db[$count]['updated_by'] = $username;
                    $metadata_db[$count]['size'] = $size;
                    $metadata_db[$count]['type'] = $extension;
                    $metadata_db[$count]['path'] = $name;
                    $metadata_db[$count]['description'] = $description;
                    $metadata_db[$count]['public_ind'] = $setPublic;
                    $metadata_db[$count]['exportData'] = '';
                    $metadata_db[$count]['approval_ind'] = 'approved';
                    
                    $metadata_tag_db[$count]['metadata_id'] = $metadata_id;
                    $metadata_tag_db[$count]['tag'] = $tagging;

                    // Insert Data to DB //////////////////////////////////////////////////////

                    // putFileAs( path, file, name_file )
                    $path = Storage::putFileAs(
                        'public', $file, $name
                    );
                    if($extension=='las'){
                        $remoteImage = storage_path('/app/').$path;
                        

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
                            
                            $metadata_db[$count]['exportData'] = $result;

                            //If an error occured, throw an exception
                            //with the error message.
                            if(curl_errno($ch)){
                                        return  response('Failed upload file', 201);
                                // throw new Exception(curl_error($ch));
                            }else{
                                // return  response('sukses', 200);
                            }
                        }
                    }elseif($extension=='shp'){
                        $pathShp[$count]['path'] = storage_path('/app/').$path;
                        $pathShp[$count]['metadata_id'] = $metadata_id;
                        $pathShp[$count]['count'] = $count;
                    }
              
                    $count += 1;
                }
            }

        if(!empty($pathShp)){
            foreach($pathShp as $row){
                $directory = $row['path'];
                if (file_exists($directory)) {
                    // Open Shapefile
                    $Shapefile = new ShapefileReader($directory);
                    
                    // return Shapefile;
                    $dataMap = array();  
                    $GeoData = array();  
                    $GeoJson = array();  
                    $counts = 0; 
                    
                    // Read all the records
                    while ($Geometry= $Shapefile->fetchRecord() ) {
                        // Skip the record if marked as "deleted"
                        if ($Geometry->isDeleted()) {
                            continue;
                        }
                        
                        $listData = $Geometry->getDataArray();
    
                        if($counts === 0) {
                            foreach($listData as $x => $x_value) {
                                if (!ctype_space($x_value) && !empty($x_value)) {
                                    $GeoData[$x] = array($x_value);
                                }
                            }  
                        } else {
                            foreach($listData as $x => $x_value) {
                                if (array_key_exists($x,$GeoData)){
                                    if (!in_array($x_value, $GeoData[$x])) {
                                        array_push($GeoData[$x],$x_value);
                                    }
                                }
                            }                        
                        }
                        $counts += 1;

                        $convert = json_decode($Geometry->getGeoJSON(),true);                    
                        $convertData = $Geometry->getDataArray();
                        $convert['properties'] = $convertData;
                        array_push($GeoJson,$convert);
                    }
    
                    $i=0;
                    foreach($GeoJson as $key){
                        $metashp['id'] =  Uuid::uuid1()->toString();
                        $metashp['fk_metadata']= $row['metadata_id'];
                        $metashp['extenddata']= json_encode($GeoJson[$i]);
                        DB::table('metashp')->insert($metashp);
                        $i++;
                    }
                    $metadata_db[$row['count']]['exportData'] = json_encode($GeoData);
                }
            }
        }

            if($count==0){
                return  response('Dataset already exist', 400);
            }else{

                if(!$dataset_exist){
                    DB::table('dataset')->insert($dataset_db);
                }
                DB::table('metadata')->insert($metadata_db);
                DB::table('metadata_tag')->insert($metadata_tag_db);
                return  response('Dataset has been saved', 201);
            }
        }
    }
    
    public function uploadImg(Request $request) {
        // Read file contents...
        $files = $request->file('file');

        if($request->hasFile('file'))
        {
            
            // $directory = storage_path('/app/public/').$extension_path;
            $directory = storage_path('/app/aboutus/img/');
            $countFile = count(scandir($directory))-2;

            foreach ($files as $file) {                
                // If you would like to get original name of the uploaded file
                $file_name = $file->getClientOriginalName();

                // If you would like to get size of the uploaded file
                $size = $file->getSize();

                // The extension method may be used to get the file extension of the uploaded file
                // $extension = $file->extension();
                $info = pathinfo($file_name);
                $extension = $info['extension'];

                $countFile = uniqid();
                $createName = $countFile.'.'.$extension;

                // Name from user
                $name = $directory.$createName;

                // var_dump($name);

                if (!file_exists($name)){
                    // putFileAs( path, file, name_file )
                    $file->move($directory,$createName);
                    
                }

                // return 'success';
            }
        }
    }
    
    public function opendata() {
        // Path
        $directory = storage_path('/app/public');

        // To retrieve all directories
        $listDir = scandir($directory);
        $directories = File::directories($directory);
        $list_folder = array();
        foreach ($directories as &$value) {   
            $list_array = array();  
            $list_file = array();         
            if (is_dir($value)) {
                if ($dh = opendir($value)) {
                    while (($file = readdir($dh)) !== false) {
                        if($file !== '.' && $file !== '..'){
                            $info = array();
                            $info['title'] = $file;

                            array_push($list_file,$info);
                        }
                    }
                    closedir($dh);
                }
            }
            $list_array['title']=pathinfo($value)['basename'];                        
            $list_array['path']=$value;
            $list_array['selDisabled']=true;
            $list_array['chkDisabled']=true;
            $list_array['expanded']=false;
            $list_array['children']=$list_file;
            // $listFiles = Storage::allDirectories($value);
            array_push($list_folder,$list_array);
        }
        


        return $list_folder;
    }
    
    public function shapefile(Request $request) {
        $path = $request['path'];
        /* $extension = $request['extension']; */
        $extension ='shp';

        // Starting clock time in seconds 
        // $start_time = microtime(true); 

        // $info = pathinfo($file);
        // $extension = $info['extension'];
        ini_set('memory_limit', '-1'); 
        // Path
        $directory = storage_path('app/public/'.$path);
        if (file_exists($directory)) {
            try {
                if($extension !== 'shp'){
                    $jsonData = [];
                    return $jsonData;                
                }
                // Open Shapefile
                $Shapefile = new ShapefileReader($directory);
               
                
                // return Shapefile;
                $GeoJson = array();  
                
                // print_r($Shapefile->fetchRecord());
                // Read all the records
                while ($Geometry = $Shapefile->fetchRecord()) {
                    // Skip the record if marked as "deleted"
                    if ($Geometry->isDeleted()) {
                        continue;
                    }

                    $convert = json_decode($Geometry->getGeoJSON(),true);                    
                    $convertData = $Geometry->getDataArray();
                    $convert['properties'] = $convertData;
                    
                    array_push($GeoJson,$convert);
                    
                    //  // Print Geometry as an Array
                    // print_r($Geometry->getArray());
                    
                    // // Print Geometry as WKT
                    // print_r($Geometry->getWKT());
                    
                    // // Print Geometry as GeoJSON
                    // print_r($Geometry->getGeoJSON());
                    
                    // // Print DBF data
                    // print_r($Geometry->getDataArray());

                    // return $dataMap;
                }
                // End clock time in seconds 
                // $end_time = microtime(true); 
                
                // Calculate script execution time 
                // $execution_time = ($end_time - $start_time); 
                // echo " Execution time of script = ".$execution_time." sec <br>"; 

                return $GeoJson;
                exit;
            
            } catch (ShapefileException $e) {
                // Print detailed error information
                echo 'Error Type: ' . $e->getErrorType()
                    . '\nMessage: ' . $e->getMessage()
                    . '\nDetails: ' . $e->getDetails();
            }
            $file_exist = 'The file $directory exists';

            // End clock time in seconds 
            // $end_time = microtime(true); 
            
            // Calculate script execution time 
            // $execution_time = ($end_time - $start_time); 
            // echo " Execution time of script = ".$execution_time." sec <br>"; 

            return $file_exist;
        } else {
            $file_exist = "The file $directory does not exist";

            // End clock time in seconds 
            // $end_time = microtime(true); 
            
            // Calculate script execution time 
            // $execution_time = ($end_time - $start_time); 
            // echo " Execution time of script = ".$execution_time." sec <br>"; 

            return $file_exist;
        }   
        // End clock time in seconds 
        // $end_time = microtime(true); 
        
        // Calculate script execution time 
        // $execution_time = ($end_time - $start_time); 
        // echo " Execution time of script = ".$execution_time." sec <br>";     
        
        return $directory;
    }
    //ini percobaan
     public function shapefileFilter(Request $request) {
        //keys tu kayak NAME_1
        ini_set('memory_limit', '-1'); 
        $id=$request['id'];
        $key=$request['key'];
        $req=$request['contentSelected'];
        $geo = array();
        $checkMetadata = DB::table('metashp')->where('fk_metadata',$id)->get();
        if (!empty($checkMetadata)) {
            // return $this->success($checkMetadata);
              for ($i=0; $i < COUNT($checkMetadata); $i++) { 
                $convert=json_decode($checkMetadata);
                $convert2=$convert[$i]->extenddata;
                $convert3=json_decode($convert2);
               
                    if (in_array($convert3->properties->$key, $req )) {
                        
                        array_push($geo,$convert3);
                        continue;
                    }
                
            }   
            
            return $geo;
            exit;
        } else {
            return $this->error('Metadata is not found');
        }

        
        
                
            
        
       
    } 
    //=================================================
    //Ini udah Bener
    // public function shapefileFilter(Request $request) {
    //     // Starting clock time in seconds 
    //     // $start_time = microtime(true); 

    //     // $file = $request['file'];
    //     // $info = pathinfo($request['file']);
    //     // $extension = $info['extension'];

    //     $path = $request['path'];
    //     $extension = $request['extension'];
    //     $keys = $request['key'];
        
    //     // Path
    //     $directory = storage_path('app/public/'.$path);
    //     if (file_exists($directory)) {
    //         try {
    //             if($extension !== 'shp'){
    //                 $jsonData = [];
    //                 return $jsonData;                
    //             }
    //             // Open Shapefile
    //             $Shapefile = new ShapefileReader($directory);
                
    //             // return Shapefile;
    //             $dataMap = array();  
    //             $GeoJson = array();  
    //             $geo=array();
                
    //             // print_r($Shapefile->fetchRecord());
    //             // Read all the records
    //             while ($Geometry = $Shapefile->fetchRecord()) {
    //                 // Skip the record if marked as "deleted"
    //                 if ($Geometry->isDeleted()) {
    //                     continue;
    //                 }
                    
    //                 $geoData = $Geometry->getDataArray();
    //             //filtering Proses 
    //                 if (in_array($geoData[$keys], $request[$keys])) {
                        
    //                     $convert = json_decode($Geometry->getGeoJSON(),true);                    
    //                     $convertData = $Geometry->getDataArray();
    //                     $convert['properties'] = $convertData;
    //                     array_push($GeoJson,$convert);
                        
    //                 }
                    
    //                 //  // Print Geometry as an Array
    //                 // print_r($Geometry->getArray());
                    
    //                 // // Print Geometry as WKT
    //                 // print_r($Geometry->getWKT());
                    
    //                 // // Print Geometry as GeoJSON
    //                 // print_r($Geometry->getGeoJSON());
                    
    //                 // // Print DBF data
    //                 // print_r($Geometry->getDataArray());

    //                 // return $dataMap;
    //             }
    //             // End clock time in seconds 
    //             // $end_time = microtime(true); 
                
    //             // Calculate script execution time 
    //             // $execution_time = ($end_time - $start_time); 
    //             // echo " Execution time of script = ".$execution_time." sec <br>"; 
                
    //             /* return $GeoJson; */
    //              return $GeoJson; 
    //             exit;
            
    //         } catch (ShapefileException $e) {
    //             // Print detailed error information
    //             echo 'Error Type: ' . $e->getErrorType()
    //                 . '\nMessage: ' . $e->getMessage()
    //                 . '\nDetails: ' . $e->getDetails();
    //         }
    //         $file_exist = 'The file $directory exists';
            
    //         // End clock time in seconds 
    //         // $end_time = microtime(true); 
            
    //         // Calculate script execution time 
    //         // $execution_time = ($end_time - $start_time); 
    //         // echo " Execution time of script = ".$execution_time." sec <br>"; 
            
    //         return $file_exist;
    //     } else {
    //         $file_exist = "The file $directory does not exist";
            
    //         // End clock time in seconds 
    //         // $end_time = microtime(true); 
            
    //         // Calculate script execution time 
    //         // $execution_time = ($end_time - $start_time); 
    //         // echo " Execution time of script = ".$execution_time." sec <br>"; 
            
    //         return $file_exist;
    //     }  
    //     // End clock time in seconds 
    //     // $end_time = microtime(true); 
        
    //     // Calculate script execution time 
    //     // $execution_time = ($end_time - $start_time); 
    //     // echo " Execution time of script = ".$execution_time." sec <br>"; 
        
    //     return $directory;
    // }

    public function shapeData(Request $request) {
        ini_set('memory_limit', '-1'); 
        $path = $request['path'];
        $extension = $request['extension'];
        // Starting clock time in seconds 
        // $start_time = microtime(true); 

        // $info = pathinfo($file);
        // $extension = $info['extension'];
        
        // Path
        $directory = storage_path('/app/public/'.$path);
        if (file_exists($directory)) {
            try {
                if($extension !== 'shp'){
                    $jsonData = [];
                    return $jsonData;                
                }
                // Open Shapefile
                $Shapefile = new ShapefileReader($directory);
                
                // return Shapefile;
                $dataMap = array();  
                $GeoData = array();  
                $count = 0; 
                
                // print_r($Shapefile->fetchRecord());
                // Read all the records
                while ($Geometry= $Shapefile->fetchRecord() ) {
                    // Skip the record if marked as "deleted"
                    if ($Geometry->isDeleted()) {
                        continue;
                    }
                    
                    $listData = $Geometry->getDataArray();

                    if($count === 0) {
                        foreach($listData as $x => $x_value) {
                            if (!ctype_space($x_value) && !empty($x_value)) {
                                $GeoData[$x] = array($x_value);
                            }
                        }  
                    } else {
                        foreach($listData as $x => $x_value) {
                            if (array_key_exists($x,$GeoData)){
                                if (!in_array($x_value, $GeoData[$x])) {
                                    array_push($GeoData[$x],$x_value);
                                }
                            }
                        }                        
                    }
                    
                    $count += 1;
                    

                    // $perData = json_decode($Geometry->getGeoJSON(),true);
                    // $perData['properties'] = $Geometry->getDataArray();
                    // array_push($dataMap,$perData);

                    // return $Geometry->getGeoJSON();
                    
                    //  // Print Geometry as an Array
                    // print_r($Geometry->getArray());
                    
                    // // Print Geometry as WKT
                    // print_r($Geometry->getWKT());
                    
                    // // Print Geometry as GeoJSON
                    // print_r($Geometry->getGeoJSON());
                    
                    // // Print DBF data
                    // print_r($Geometry->getDataArray());

                    // return $dataMap;
                }
                // End clock time in seconds 
                // $end_time = microtime(true); 
                
                // Calculate script execution time 
                // $execution_time = ($end_time - $start_time); 
                // echo " Execution time of script = ".$execution_time." sec <br>";
                
                return $GeoData;
                exit;
            
            } catch (ShapefileException $e) {
                // Print detailed error information
                echo 'Error Type: ' . $e->getErrorType()
                    . '\nMessage: ' . $e->getMessage()
                    . '\nDetails: ' . $e->getDetails();
            }
            $file_exist = 'The file $directory exists';
            
            // End clock time in seconds 
            // $end_time = microtime(true); 
            
            // Calculate script execution time 
            // $execution_time = ($end_time - $start_time); 
            // echo " Execution time of script = ".$execution_time." sec <br>";

            return $file_exist;
        } else {
            $file_exist = "The file $directory does not exist";

            // End clock time in seconds 
            // $end_time = microtime(true); 
            
            // Calculate script execution time 
            // $execution_time = ($end_time - $start_time); 
            // echo " Execution time of script = ".$execution_time." sec <br>";

            return $file_exist;
        }        
        
        // End clock time in seconds 
        // $end_time = microtime(true); 
        
        // Calculate script execution time 
        // $execution_time = ($end_time - $start_time); 
        // echo " Execution time of script = ".$execution_time." sec <br>";

        return $directory;
    }

    public function getdata(Request $request) {
        ini_set('memory_limit', '-1'); 
        $path = $request['path'];
        $extension = $request['extension'];

        // Path
        $directory = storage_path('/app/public/'.$path);

        try{
            if($extension === 'xlsx' || $extension === 'xls'){
                $inputFileType = ucfirst($extension);
                
                // $inputFileType = 'Xls';
                // $inputFileType = 'Xlsx';
                // $inputFileType = 'Xml';
                // $inputFileType = 'Ods';
                // $inputFileType = 'Slk';
                // $inputFileType = 'Gnumeric';
                // $inputFileType = 'Csv';

                /**  Create a new Reader of the type defined in $inputFileType  **/
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                /**  Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = $reader->load($directory);

                // data worksheet yg akan dibaca ada di active sheet
                $worksheet = $spreadsheet->getActiveSheet();
                
                // mendapatkan maks nomor baris data
                $highestRow = $worksheet->getHighestRow();
                // mendapatkan maks kolom data
                $highestColumn = $worksheet->getHighestColumn();
                
                // mendapatkan nama-nama kolom data 
                // membaca value yang ada di cell: A1, B1, ..., E1
                // dan simpan ke dalam array $colsName
                $colsName = array();
                for($col='A'; $col<=$highestColumn; $col++){
                    $colsName[] =  $worksheet->getCell($col . 1)->getValue();
                }
                
                // inisialisasi array untuk menampung semua data
                $dataAll = array();
                
                // proses membaca data baris-perbaris 
                // dimulai dari baris ke-2, karena baris ke-1 berisi nama kolom tabel
                
                for($row=2; $row<=$highestRow; $row++){
                    // inisialisasi array untuk data perbaris
                    $dataRow = array();
                
                    $i = 0;
                    // untuk setiap baris data, baca value tiap kolom cell
                        // misal untuk baris ke-2, cell yang dibaca: A2, B2, ..., E2
                        // misal untuk baris ke-3, cell yang dibaca: A3, B3, ..., E3
                        // dst ...
                    for($col='A'; $col<=$highestColumn; $col++){
                        // setiap value digabung menjadi satu
                        // dan tambahkan ke array $dataRow
                        $dataRow += array($colsName[$i] => $worksheet->getCell($col . $row)->getValue());
                        $i++;
                    }
                    // setelah didapat data array perbaris
                    // tambahkan ke $dataAll 
                    $dataAll[] = $dataRow;
                }
                
                // convert ke json lalu tampilkan
                $jsonData = json_encode($dataAll);
                
                return $jsonData;
            }else if($extension === 'csv'){
                // Set your CSV feed
                $feed = $directory;

                // Arrays we'll use later
                $keys = array();
                $newArray = array();
                
                // Function to convert CSV into associative array
                function csvToArray($file, $delimiter) { 
                    if (($handle = fopen($file, 'r')) !== FALSE) { 
                        $i = 0; 
                        while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
                        for ($j = 0; $j < count($lineArray); $j++) { 
                            $arr[$i][$j] = $lineArray[$j]; 
                        } 
                        $i++; 
                        } 
                        fclose($handle); 
                    } 
                    return $arr; 
                } 

                // Do it
                $data = csvToArray($feed, ',');

                // Set number of elements (minus 1 because we shift off the first row)
                $count = count($data) - 1;
                
                //Use first row for names  
                $labels = array_shift($data);  

                foreach ($labels as $label) {
                $keys[] = $label;
                }

                // Add Ids, just in case we want them later
                $keys[] = 'id';

                for ($i = 0; $i < $count; $i++) {
                $data[$i][] = $i;
                }
                
                // Bring it all together
                for ($j = 0; $j < $count; $j++) {
                $d = array_combine($keys, $data[$j]);
                $newArray[$j] = $d;
                }

                // Print it out as JSON
                return json_encode($newArray);
            }else{
                $jsonData = [];
                return $jsonData;                
            }
        }catch(Exception $e){
            $jsonData = [];
            return $jsonData;
        }

        
    }

    public function csvdata($file,$delimit) {
        ini_set('memory_limit', '-1'); 
        $info = pathinfo($file);
        $extension = $info['extension'];

        // Path
        $directory = storage_path('/app/public/'.$extension.'/'.$file);
        
        // Set your CSV feed
        $feed = $directory;

        // Arrays we'll use later
        $keys = array();
        $newArray = array();
        
        // Function to convert CSV into associative array
        function csvToArray($file, $delimiter) { 
            if (($handle = fopen($file, 'r')) !== FALSE) { 
                $i = 0; 
                while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
                for ($j = 0; $j < count($lineArray); $j++) { 
                    $arr[$i][$j] = $lineArray[$j]; 
                } 
                $i++; 
                } 
                fclose($handle); 
            } 
            return $arr; 
        } 

        // Do it
        $data = csvToArray($feed, $delimit);

        // Set number of elements (minus 1 because we shift off the first row)
        $count = count($data) - 1;
        
        //Use first row for names  
        $labels = array_shift($data);  

        foreach ($labels as $label) {
        $keys[] = $label;
        }

        // Add Ids, just in case we want them later
        $keys[] = 'id';

        for ($i = 0; $i < $count; $i++) {
        $data[$i][] = $i;
        }
        
        // Bring it all together
        for ($j = 0; $j < $count; $j++) {
        $d = array_combine($keys, $data[$j]);
        $newArray[$j] = $d;
        }

        // Print it out as JSON
        return json_encode($newArray);
    }
    
    // const REQUIRED_FILES = [
    //     'thumbnail.jpg',
    //     'assets/style.css',
    // ];

    public function zipdata($file){
        ini_set('memory_limit', '-1'); 
        $info = pathinfo($file);
        $extension = $info['extension'];

        // Path
        $directory = storage_path('/app/public/'.$extension.'/'.$file);

        $zip = zip_open($directory);

        if ($zip) {
            while ($zip_entry = zip_read($zip)) {
                // Open directory entry for reading
                if (zip_entry_open($zip, $zip_entry)) {
                    // Read open directory entry
                    $contents = zip_entry_read($zip_entry);
                    return $contents;
                    // zip_entry_close($zip_entry);
                }
                // echo "</p>";
            }
            zip_close($zip);
        }

        return $filesInside;

        // $intersection = array_intersect(self::REQUIRED_FILES, $filesInside);

        // if (count($intersection) !== count(self:: REQUIRED_FILES)) {
        //     abort(422);
        // }

        // ZIP contains all required files, continue
    }
}
