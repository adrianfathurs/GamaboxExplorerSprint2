<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\GlobalFunction;
use JWTAuth;
use Illuminate\Support\Facades\Auth; 
use Tymon\JWTAuth\Exceptions\JWTException;
use Laravel\Passport\Client as OClient; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use DB;
use App\Mail\Regist;
use App\Mail\ForgotPassword;
use Socialite;
class UserController extends Controller
{
    use GlobalFunction;

    public function login(Request $request)
    {
        if (!empty($request->email) and !empty($request->password)) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $auth = Auth::user();
                return $this->success($auth,'Logged in success');
            } else {
                return $this->error('The password is invalid');
            }
        } else {
            return $this->error('Credentials doesnt match');
        }
    }


    public function register(Request $request)
    {
        $input['username'] = $request->username;
        $input['displayName'] = $input['username'];
        $input['email'] = $request->email;
        $input['uid'] = $input['email'];
        $checkEmail = User::whereEmail($input['email'])->first();
        if (!empty($checkEmail)) {
            return $this->error('Email is already exist');
        }
        $checkUsername = User::whereUsername($input['username'])->first();
        if (!empty($checkUsername)) {
            return $this->error('Username is already exist');
        }
        $input['password'] = Hash::make($request->password);
        $input['role'] = 'user';
        $input['account_status'] = 'active';
        $input['providerid'] = 'password';
        $input['emailVerified'] = 0;
        $input['registered_via'] = 'email';
        $additional_data['email_verification_code']= sha1(uniqid());
        $input['additional_data']=json_encode($additional_data);
        $user = User::create($input);
        $input['url'] = env('APP_URL');
        Mail::to($input['email'])->send(new Regist($input));
        $input['id'] =$user->id;
        return $this->success($input,'Success registration, please check your email');
    }


    public function verify_email(Request $request)
    {
        $checkUser = User::whereEmail($request->email)->first();
        if (!empty($checkUser)) {
            $additional_data=json_decode($checkUser->additional_data);
            if($request->verification_code==$additional_data->email_verification_code){
                $input['emailVerified'] = TRUE;
                $form_additional_data['email_verification_code']= sha1(uniqid());
                $input['additional_data']=json_encode($form_additional_data);
                $update = User::find($checkUser->id)->update($input);
                $dataUser = User::find($checkUser->id);
                return $this->success($dataUser,'Your email has been verified');
            }else{
                return $this->error('Verification code doesnt match');
            }
        }else{
            return $this->error('User is not found');
        }
    }


    public function login_old(Request $request)
    {
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) 
        { 
            $o_client = OClient::where('password_client', 1)->first();
            //dd($timai_token);
            $token = $this->getTokenAndRefreshToken($o_client, $request->get('email'), $request->get('password'));
            $userdata = Auth::user();
            
            return array('token_type'=>$token['token_type'],
                         'token'=>$token['access_token'],
                         'refresh_token'=>$token['refresh_token'],
                         'ttl'=>$token['expires_in'],
                         'userData'=>$userdata);
        } 
        else 
        { 
            return false;
        } 
    }

    public function register_old(Request $request)
    {
        date_default_timezone_set("Asia/Bangkok");

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create([
            'id' => uniqid(),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // $token = JWTAuth::fromUser($user);
        // return response()->json(compact('user','token'),201);

        return response()->json(['status' => 'ok'],201);
    }

    public function refreshJwt(Request $request)
    {
        $refresh_token = $request->get('refresh_token');
        $oClient = OClient::where('password_client', 1)->first();
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('POST',  env('OAUTH_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'scope' => '*',
                ],
            ]);
            return json_decode((string) $response->getBody(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e){
            return response()->json(['status'=>'Token invalid'], 401); 
        
        } catch (Exception $e) {
            return response()->json(['status'=>'Unathorized'], 401); 
        }
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password)
    { 
        $oClient = OClient::where('password_client', 1)->first();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', env('OAUTH_URL') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        
        return $result;
    }

    public function logout(Request $request)
    {
        if (!$this->guard()->check())
        {
            return response()->json(array('status'=>'failed'), 403);
        }

        $this->guard()->user()->token()->revoke();
        
        return response()->json(['status' => 'ok'],200);
    }


    public function send_forgot_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email"
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        } else {
            //Create Password Reset Token
            $token =sha1(uniqid());
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => date("Y-m-d H:i:s")
            ]);
            $mail['url'] = env('APP_URL').'/reset-password/'.$token.'?email='.$request->email;
            $mail['token'] = $token;
            $mail['email'] = $request->email;
            Mail::to($mail['email'])->send(new ForgotPassword($mail));
                return $this->success($request->all(),'Forgot password link has been sent');
            // try {
            //     $response = Password::sendResetLink($request->only('email'));
            //     switch ($response) {
            //         case Password::RESET_LINK_SENT:
            //             return $this->success($request->all(),'Forgot password link has been sent');
            //         case Password::INVALID_USER:
            //             return $this->error(trans($response));
            //     }
            // } catch (\Swift_TransportException $ex) {
            //     return $this->error($ex->getMessage());
            // } catch (Exception $ex) {
            //     return $this->error($ex->getMessage());
            // }
        }
        return $this->error('Undefined');
      
    }

    public function create_new_password(Request $request)
    {
        $passwordReset = DB::table('password_resets')->whereEmail($request->email)->first();
        if($passwordReset){
            $hashed = Hash::make($request->token);
            if ($request->token==$passwordReset->token) {
                $checkUser = User::whereEmail($passwordReset->email)->first();
                $input['password'] = Hash::make($request->password);
                $update = User::find($checkUser->id)->update($input);
                return $this->success($update,'Success Reset Password');
            }else{
                return $this->error('Invalid Token');
            }
        }else{
            return $this->error('Invalid email address');
        }
    }

    public function check_forgot_password(Request $request)
    {
       

    }
    public function guard()
    {
        return Auth::guard('api');
    }


    public function SocialSignup($provider)
    {
        // Socialite will pick response data automatic
        $user = Socialite::driver($provider)->stateless()->user();
        $checkAccount = User::whereEmail($user->email)->first();
        if($user){
            if($checkAccount){
                $dataUser = $checkAccount;
            }else{
                $input['username'] = str_replace(' ','',$user->name);
                $input['email'] = $user->email;
                $input['password'] = Hash::make($user->id);
                $input['role'] = 'user';
                $input['displayName'] = $input['username'];
                if(!empty($user->avatar)){
                    $input['photo'] = $user->avatar;
                }
                $input['providerid'] = 'password';
                $input['emailVerified'] =   1;
                $input['account_status'] = 'active';
                $input['registered_via'] = 'gmail';
                $additional_data['social_id']= $user->id;
                $input['additional_data']=json_encode($additional_data);
                $user = User::create($input);
                $input['id'] =$user->id;
                $dataUser = User::whereEmail($user->email)->first();
            }
            return $this->success($dataUser,'Success Registration with google');
        }else{
            return $this->error('Registration failed, please try again');
        }
    }


    public function edit(Request $request)
    {
        $checkUser = User::find($request->id);

        if($checkUser){
            $input['job_title'] = $request->job_title;
            $input['company'] = $request->company;
            $input['phone'] = $request->phone;
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/profile_picture');
                $image->move($destinationPath, $name);
                $input['photo'] = 'images/profile_picture/'.$name;
            }

            $update = User::find($checkUser->id)->update($input);
            $input['id'] =$request->id;
            return $this->success($input,'Success Updated Data');
        }else{
            return $this->error('User is not found');
        }
    }


    public function change_password(Request $request)
    {
        $checkUser = User::find($request->id);
        if($checkUser){
            if (Auth::attempt(['email' => $checkUser->email, 'password' => $request->old_password])) {
                $input['password'] = Hash::make($request->new_password);
                $update = User::find($checkUser->id)->update($input);
                return $this->success($checkUser,'Success Change Password');
            }else{
                return $this->error('Wrong old password');
            }
        }else{
            return $this->error('User is not found');
        }
    }
}