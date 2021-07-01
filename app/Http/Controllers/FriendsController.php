<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Support\Facades\Auth; 
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Friendship;
use App\User;

class FriendsController extends Controller
{
    //
    public function friend_requests(Request $request)
    {
        if (!$this->guard()->check())
        {
            return response()->json(array('status'=>'failed'), 403);
        }
        // $list = new User();
        // $value = $list->testing();
        $getUser = User::findOrFail(3);
        $user = $getUser->friends;

        return response()->json($user,201);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
