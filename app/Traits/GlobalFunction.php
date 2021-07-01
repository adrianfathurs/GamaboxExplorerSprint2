<?php

namespace App\Traits;


use \DateTime;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait GlobalFunction
{
   /**
    * Return a success JSON response.
    *
    * @param  array|string  $data
    * @param  string  $message
    * @param  int|null  $code
    * @return \Illuminate\Http\JsonResponse
    */
   protected function success($data, string $message = "", int $code = 200)
   {
      return response()->json([
         'code' => 200,
         'status' => 'success',
         'result' => $data,
         'message' => $message
      ], $code);
   }

   /**
    * Return an error JSON response.
    *
    * @param  string  $message
    * @param  int  $code
    * @param  array|string|null  $data
    * @return \Illuminate\Http\JsonResponse
    */
   protected function error(string $message = null, int $code = 200)
   {
      return response()->json([
         'code' => 500,
         'status' => 'failed',
         'message' => $message
      ], $code);
   }

   protected  function isJson(string $string)
   {
      json_decode($string);
      return (json_last_error() == JSON_ERROR_NONE);
   }

}
