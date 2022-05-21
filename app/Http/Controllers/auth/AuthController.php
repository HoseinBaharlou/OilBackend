<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Jobs\sendEmail;
use App\Mail\VerifyCode;
use App\Models\IpAddress;
use App\Models\User;
use App\Services\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // auth register
    public function register(Request $request){
        // validate
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:20',
            'last_name'=>'required|string|max:20',
            'email'=>'required|email|max:100|unique:users,email',
            'tag'=>'required|numeric|digits_between:1,10',
            'address'=>'required|string|max:200',
            'agreement'=>"required|boolean",
            'phone_number'=>'required|numeric|digits:11',
            'zip_code'=>'required|numeric|digits_between:10,10',
            'password'=>'required|string|min:8|max:100|confirmed'
        ]);
        // check validated
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        // check true agreement
        if($request->agreement != true){
            return response()->json([
                'errors'=>'برای ثبت نام در سایت باید توافقنامه را بپذیرید'
            ],401);
        }
        // email code
        $random = Str::random(6);
        // create user
        User::create([
            'name'=>$request->name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'agreement'=>$request->agreement,
            'tag'=>$request->tag,
            'zip_code'=>$request->zip_code,
            'email_code'=>$random,
            'expire_time'=>time()+(60*60*2),
            'password'=>Hash::make($request->password)
        ]);
        // send verify code
        $this->verify_code($request->email,$random);
        // response
        return response()->json([
            'success'=>'ثبت نام با موفقیت انجام شد',
        ],201);
    }
    //login page
    public function login(Request $request){
        // validate
        $validator = Validator::make($request->only(['email','password']),[
            'email'=>'required|email',
            'password'=>'required|string|max:100'
        ]);

        // check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        // attemp user
        auth()->attempt($request->only(['email','password']));

        //check auth user
        if (auth()->check()){
            $user = auth()->user();
            // get user ip
            $this->Get_ip($user->id,$request->ip());

            $token = Token::generate($user->id);

            return response()->json(['access_token'=>$token,'email'=>$user->email,'verify'=>$user->email_verified_at !== null ? true : false],201)->withCookie('token',$token,Token::$expire_time);
        }else{
            return response()->json([
                'errors'=>'ایمیل یا رمز عبور نادرست است.'
            ],401);
        }
    }
    // get ip
    private function Get_ip($id,$ipAddress){
        IpAddress::create([
            'user_id'=>$id,
            'ip_address'=>$ipAddress
        ]);
    }
    // send otp code
    public function verify_code($email,$random){
        sendEmail::dispatch($email,new VerifyCode($random));
    }

    // otp authentication
    public function otp(Request $request){
        $validator = validator::make($request->all(),[
            'email'=>'required|email|max:100',
            'code'=>'required|max:6'
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        // check expire time
        $code = User::where('email','=',$request->email)->first();
        $timestamp = time();
        $expire_time = (int) $code->expire_time;
        if($timestamp > $expire_time){
            return abort(404);
        }else if($code->email_code !== $request->code){
            return response()->json([
                'errors'=>'کد وارد شده صحیح نیست!'
            ],401);
        }
        // update column verify
        User::where('email','=',$request->email)->update([
            'email_verified_at'=>now()
        ]);

//        response
        return response()->json([
            'success'=>true
        ],201);
    }
    // create otp code
    public function createCode(Request $request){
        $validator = validator::make($request->all(),[
            'email'=>'required|email|max:100',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        // email code
        $random = Str::random(6);
        // check email
        $checkEmail = User::where('email','=',$request->email)->first();

        if($checkEmail->email_verified_at !== null){
            dd($checkEmail->email_verified_at);
            return abort(404);
        }

        // update column
        User::where('email','=',$request->email)->update([
            'email_code'=>$random,
            'expire_time'=>time()+(60*60*2),
        ]);
        // send verify code
        $this->verify_code($request->email,$random);
    }
    // check email verified
    public function email_verified(Request $request){
        // check verify email
        $verify = User::where('email','=',$request->email)->value('email_verified_at');

        if($verify === null){
            return false;
        }
        return true;
    }
}
