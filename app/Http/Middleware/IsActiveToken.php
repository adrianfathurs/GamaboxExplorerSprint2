<?php
/*
Costum middleware laravel-passport @sahala_ww
*/
namespace App\Http\Middleware;

use App\Models\OauthAccessTokens;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use \Firebase\JWT\JWT;
use Exception;
use Illuminate\Auth\GenericUser;

class IsActiveToken
{
    public function handle($request, Closure $next)
    {
        $header = $request->header('Authorization');
        $publicKey = file_get_contents(storage_path('oauth-public.key'));
        
        if (strpos($header, 'Bearer ') !== false)
        {
            $token = str_replace('Bearer ', '', $header);
            
            try {
                $res = JWT::decode($token, $publicKey, array('RS256'));               
            }catch(\Firebase\JWT\BeforeValidException $e){
                return response()->json(['status'=>$e->getMessage()], 401);
            }catch (\Firebase\JWT\ExpiredException $e) { 
                return response()->json(['status'=>$e->getMessage()], 401);
            }catch (\Firebase\JWT\SignatureInvalidException $e) {
                return response()->json(['status'=>$e->getMessage()], 401);
            }catch(Exception $e){
                return response()->json(['status'=>$e->getMessage()], 401);
            }
            
        }
        else
        {
            return response()->json(['status'=>'token not provided'], 401);
        }
        return $next($request);
       
    }

}