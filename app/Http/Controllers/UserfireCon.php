<?php
namespace App\Http\Controllers;

// Import classes
use DB;

use Illuminate\Http\Request;
use App\Userfire;

class UserfireCon extends Controller
{
    //
    public function store(Request $request) {
        
        $uid = $request['uid'];
        $displayName = $request['displayName'];
        $email = $request['email'];
        $providerId = $request['providerId'];
        //$password = $request['passwordHash'];


        $userdata = array();
        //$userdata['id']=nextval()
        $userdata['uid']=$uid;
        $userdata['email']=$email;
        $userdata['providerid']=$providerId;
        $userdata['displayname']=$displayName;
        //$userdata['password']=$pasword;

        DB::table('user')->insert($userdata);

        
        return response()->json([
            "message" => "user fire record created"
        ], 201);
        
    }
    public function userlist(Request $request) {
        $filter_name = $request['filter_name'];
        $sort = $request['sort'];

        if($sort === '' || $sort === null){
            $code = "ORDER BY role ASC";
        }else{            
            $code = "ORDER BY $sort ASC";
        }

        try {
            if($filter_name == '' || $filter_name == null){            
            $dataset = DB::select("SELECT *
                            FROM public.user 
                            $code;
                        ");
            } else if($filter_name === 'search') {
                $keyword = $request['keyword'];
                $dataset = DB::select("
                        SELECT *
                            FROM public.user 
                            WHERE lower (displayname)  LIKE lower ('%' || '$keyword' || '%') OR lower(email) LIKE lower ('%' || '$keyword' || '%')    
                            $code;
                        ");
            }
        } catch (Exception $e) {
            $dataset = [];
        }
        return $dataset;
    }

    public function superuserlist(Request $request) {
        $filter_name = $request['filter_name'];
        $sort = $request['sort'];

        if($sort === '' || $sort === null){
            $code = "ORDER BY role ASC";
        }else{            
            $code = "ORDER BY $sort ASC";
        }

        try {
            if($filter_name == '' || $filter_name == null){            
                $dataset = DB::select("SELECT *
                                FROM public.user 
                                $code;
                            ");
            } else if($filter_name === 'search') {
                $keyword = $request['keyword'];
                $dataset = DB::select("
                        SELECT *
                            FROM public.user 
                            WHERE lower (displayname)  LIKE lower ('%' || '$keyword' || '%') OR lower(email) LIKE lower ('%' || '$keyword' || '%')   
                            $code;
                        ");
            }
        } catch (Exception $e) {
            $dataset = [];
        }
        return $dataset;
    }    
    public function changeRole($setRole, $uid) {        
        // $setRole = $request['setRole'];      
        // $uid = $request['uid'];
        DB::update('update public.user set role = ? where uid = ?',[$setRole,$uid]);
        echo "Record updated successfully.
        ";
        //  return response()->json([
        //     "message" => "user fire record created"
        // ], 201);
    }

    public function removeuser(Request $request) {
        $uid = $request['uid'];
        $users = DB::table('user')
                            ->where('uid',$uid)                            
                            ->delete();
        echo "user removed successfully.
        ";
    }
    public function checkrole($email) {
        $user='user';
        $role = DB::select("SELECT role
                        FROM public.user WHERE email='$email'");
        if ($role==[]){
            $role[0]['role']=$user;       
            }
        return $role;
    }
    public function updateAdmin(Request $request) {
        $data_admin = $request['data_admin'];  
        foreach($data_admin as $value){
            $dataset = DB::table('user')
                        ->where('uid', $value['uid'])
                        ->update(['role' => $value['role']]);
        }
    } 
    
    // public function userDetail($email) {
    //     $dataset = DB::table('user')
    //                     ->where('email', $email)
    //     }
    // }  
}
