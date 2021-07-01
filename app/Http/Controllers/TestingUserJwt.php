<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class TestingUserJwt extends Controller
{
    public function text() {
        $data = "Data Show";
        return response()->json($data, 200);
    }

    public function textAuth() {
        if (!$this->guard()->check())
        {
            return response()->json(array('status'=>'failed'), 403);
        }
        $data = "Welcome " . $this->guard()->user()->name;
        return response()->json($data, 200);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}