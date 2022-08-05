<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\register;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\passwordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request){
$request->validate([
    'fullname' => 'required|string',
    'username' => 'required|string|unique:users',
    'email' => 'required|string|unique:users',
    'password' => 'required|string|min:6'
]);

$user = new User([
    'fullname' => $request->fullname,
    'username' => $request->username,
    'email' => $request->email,
    'password' => Hash::make($request->password)
]);
$user->save();
return response()->json([
    'status' => true,
    'message' => "User Registered Successfully!"], 200);

    }

    public function login(Request $request){
$request->validate([
    'email' => 'required|string',
    'password' => 'required|string',
]);

 $credentials = request(['email', 'password']);

if(!Auth::attempt($credentials))
{
    return response()->json(["status" => false , 'message' => 'Unauthorized user!'], 401);
}

$user = $request->user();
$tokenresult = $user->createToken('Personal Access Token');
$token = $tokenresult->plainTextToken;
$expires_at = Carbon::now()->addweeks(1);

return response()->json(["status" => true, 'data' => [
    'user' => Auth::user(),
    'access_token' => $token,
    'token_type' => 'Bearer',
    'expires_at' => $expires_at
    ]]);

    }

    public function forgetpassword(Request $request){
       
    try{
    $user = User::where('email', $request->email)->get();

if (count($user) > 0) {
  $token = Str::random(40);
$domain = URL::to('/');
$url = $domain.'/reset-password?token='.$token;


$data['url'] = $url;
$data['email'] = $request->email;
$data['title'] = 'Password Reset';
$data['body'] = 'Please click on below link to Reset your password!';

Mail::send('resetpassmail', ['data' => $data], function($message) use($data){
$message->to($data['email'])->subject($data['title']);

});

$datetime = Carbon::now()->format('Y-m-d H:i:s');

passwordReset::updateOrCreate(
    ['email' => $request->email],

    [
    'email' => $request->email,
    'token' => $token,
    'created_at' => $datetime,
    
    ]
);

return response()->json([
    'status' =>true,
    'msg' => 'Please check your mail to Resent your Password!'
]);
    }
    else{

    return response()->json([
        'status' =>False,
        'msg' => 'User not Found!'
    ]);
    }

    }catch (\Exception $e){
    return response()->json([
        'status' =>False,
        'msg' => $e->getMessage()
    ]);
    }

    }

    public function resetpass(Request $request){
$resetd  = passwordReset::where('token', $request->token)->get();

if(isset($request->token) && count($resetd) > 0 ){

$user = User::where('email', $resetd[0]['email'])->get();
return view('reset-password', compact('user'));

}else
    {
return 'No';
}
    }

public function updpass(Request $request){

    $request->validate([
        'password' => 'required|string|min:6|confirmed',
        'confirm_pass' => 'required|string|min:6|same:password'
    ]);

$user = User::find($request->id);
$user->password = Hash::make($request->password);
$user->save();

passwordReset::where('email', $user->email)->delete();

return "<h1>Your Password Reset was Successful!</h1>";


}

public function changepass(Request $request){
$validator = Validator::make($request->all(),[
    'old_password' => 'required|min:6',
    'password' => 'required|min:6',
    'confirm_pass' => 'required|same:password'

]);

if($validator->fails()){
    return response()->json([
        'status' => false,
        'message' => 'validation fails',
        'errors' => $validator->errors(),
    ], 422);
}
$user = $request->user();
if(Hash::check($request->old_password, $user->password))
    {
$user->update([
'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'status' =>true,
            'message' => 'Password Updated Successfully'
        ], 200);

    }else{
    return response()->json([
        'status' =>false,
        'message' => 'Old Password does not match'
    ], 400);
    }

        }
}
