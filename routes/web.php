<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Auth;

// Upload File
Route::post('/store_file', 'UploadFile@upload')->name('upload');
Route::get('/download/{file}', 'DownloadFile@download')->name('download');
Route::get('/{any}', 'ApplicationController')->where('any', '.*');
require __DIR__ . '/auth.php';
