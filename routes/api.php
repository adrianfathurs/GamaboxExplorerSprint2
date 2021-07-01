<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::post('send_forgot_password', 'UserController@send_forgot_password');
Route::post('create_new_password', 'UserController@create_new_password');
Route::post('refresh', 'UserController@refreshJwt');
Route::post('verify_email', 'UserController@verify_email');
Route::post('logout', 'UserController@logout')->middleware('IsActiveToken');

// For Testing
Route::get('text', 'TestingUserJwt@text');
Route::get('textauth', 'TestingUserJwt@textauth')->middleware('IsActiveToken');
Route::get('friend', 'FriendsController@friend_requests')->middleware('IsActiveToken');

// For Upload
Route::post('upload', 'UploadFile@upload');
Route::post('uploadimg', 'UploadFile@uploadImg');
Route::post('getdata', 'UploadFile@getdata');
Route::post('mapshapefile', 'UploadFile@shapefile');
Route::post('shapefile', 'UploadFile@shapefileFilter');
Route::post('geodata', 'UploadFile@shapeData');

Route::get('csvdata/{file}/{delimit}', 'UploadFile@csvdata');
Route::get('opendata', 'UploadFile@opendata');
Route::get('zipdata/{file}', 'UploadFile@zipdata');
// Route::get('shapefile/{file}', 'UploadFile@shapefile');

// For Dataset
Route::post('perdataset', 'DataList@perDataset');
Route::post('dataset', 'DataList@listDataset');
Route::post('dataset/filter', 'DataList@listDatasetFilter');
Route::post('mydataset', 'DataList@listMyDataset');
Route::post('filter', 'DataList@filterDataset');
Route::post('filtershp', 'DataList@filterShp');
Route::post('update', 'DataList@updatePublic');
Route::post('datalist/update_metadata', 'DataList@update_metadata');
Route::get('datalist/metadata', 'DataList@metadata');
Route::get('changePublic/{id}/{setPublic}', 'DataList@changePublic');
Route::get('publish/{id}/{verif}', 'DataList@publishData');
Route::get('stats', 'DataList@getStats');

// For AboutUs
Route::post('savetext', 'DataList@saveTxtFile');
Route::get('gettext', 'DataList@getTxtFile');

// List Map SHP
Route::post('shp', 'DataList@getShpFileList');
Route::post('getsshp', 'DataList@getShpFileListList');

// List File
Route::post('all', 'DataList@getAllFileList');
Route::get('pdf/{user}/{dataset}/{file}', 'DataList@openPdf');
Route::get('img/{user}/{dataset}/{file}', 'DataList@openImg');
Route::get('imgaboutus/{file}', 'DataList@openImgAboutUs');
Route::get('listimg', 'DataList@listImgAboutUs');
Route::get('txt/{user}/{dataset}/{file}', 'DataList@openTxt');
Route::get('las/{user}/{dataset}/{file}', 'DataList@fetchData');

// // For delete
// Route::post('deletedata', 'Deletedata@deletedata');
// Route::post('deletefile', 'Deletedata@deletefile');

// For download
// Route::post('downloadzip', 'DownloadFile@downloadzip');
Route::post('download', 'DownloadFile@downloadfilename');
Route::get('downloadzip/{username}/{getDatasetName}', 'DownloadFile@downloadzip');
Route::get('downloadfile/{getuser}/{getdataset}/{getfile}', 'DownloadFile@downloadperfile');

// send email
Route::post('sendemail', 'MailController@basic_email');

//push firebase user
Route::post('store', 'UserfireCon@store');

//for delete
Route::post('deleteimage', 'Deletedata@deleteImg');
Route::post('deletedataset', 'Deletedata@removeDataset');
Route::get('deletedata/{id}/{username}/{getDatasetName}', 'Deletedata@deletedata');
Route::get('deletefile/{path}', 'Deletedata@deletefile');

//for user
Route::post('userlist', 'UserfireCon@userlist');
Route::post('removeuser', 'UserfireCon@removeuser');
Route::post('updateAdmin', 'UserfireCon@updateAdmin');
Route::post('superuserlist', 'UserfireCon@superuserlist');
Route::get('changeRole/{changeRole}/{uid}', 'UserfireCon@changeRole');
Route::get('checkrole/{email}', 'UserfireCon@checkrole');


Route::post('sociallogin/{provider}', 'UserController@SocialSignup');
Route::get('auth/{provider}/callback', 'UserController@SocialSignupCallback')->where('provider', '.*');

// For Article
Route::post('articles/add', 'ArticlesController@add');
Route::post('articles/edit', 'ArticlesController@edit');
Route::post('articles/delete', 'ArticlesController@delete');
Route::get('articles', 'ArticlesController@index');


// For User
Route::post('user/edit', 'UserController@edit');
Route::post('user/change_password', 'UserController@change_password');
