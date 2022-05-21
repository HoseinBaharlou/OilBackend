<?php
namespace App\Services\Token;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait Token{
    public static $expire_time = 30*24*60*60;

    //    generate token
    public static function generate($user_id){
        $token = sha1(Str::random(50));
        \App\Models\Token::create([
            'user_id'=>$user_id,
            'token'=>$token,
            'expire_time'=>self::$expire_time + time()
        ]);
        return $token;
    }

    // check token
    public static function checkToken(Request $request){
        $token = $request->header('token');
    }
}
