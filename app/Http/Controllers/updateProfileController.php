<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class updateProfileController extends Controller
{
    public function update(Request $request){
        //validate
        $validator = Validator::make($request->all(),[
           'name'=>'required|string|min:3',
           'last_name'=>'required|string|min:3',
           'address'=>'required|string|min:10',
           'phone_number'=>'required|numeric|digits:11',
            'profiles'=>'nullable',
            'profile_name'=>'nullable|string'
        ]);

        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        //data
        $data = $request->only(['name','last_name','address','phone_number']);
        //upload profile
        if ($request->profile){
            $file_name = sha1(random_bytes(55)).'.'.Str::afterLast($request->profile_name,'.');
            $path = public_path('profiles/').$file_name;
            Image::make($request->profile)->fit(70)->save($path);
            //save file name to data variable
            auth()->user()->profile = $file_name;
            auth()->user()->save();
        }

        auth()->user()->update($data);
        auth()->user()->save();

        return response()->json([
           'success'=>'پروفایل کاربری با موفقیت ویرایش شد.',
            'test'=>$data
        ],201);
    }

    //delete profile
    public function delete(Request $request){
        $validator = Validator::make($request->only(['file_name']),[
            'file_name'=>'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        //delete profile
        $path = public_path('profiles/'.auth()->user()->profile);

        File::delete($path);

        auth()->user()->profile = null;
        auth()->user()->save();
    }
}
