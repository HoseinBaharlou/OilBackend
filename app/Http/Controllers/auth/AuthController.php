<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Jobs\sendEmail;
use App\Mail\VerifyCode;
use App\Models\User;
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

    // send otp code
    public function verify_code($email,$random){
        sendEmail::dispatch($email,new VerifyCode($random));
    }
}
