<?php

namespace App\Http\Controllers;

use App\Models\Tellme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TellmeController extends Controller
{
    public function store(Request $request){
        //validate
        $validator = Validator::make($request->all(),[
            'fullName'=>'required|string|min:5|max:50',
            'email'=>'required|email|max:250',
            'phoneNumber'=>'required|digits:11,numeric',
            'content'=>'required|min:5|max:1000|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        //insert
        Tellme::create($request->only(['fullName','email','phoneNumber','content']));

        //res
        return response()->json([
            'success'=>'درخواست شما با موفقیت ارسال گردید.'
        ],201);
    }
}
