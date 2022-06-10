<?php

namespace App\Http\Controllers;

use App\Models\Analyze;
use App\Services\Uploader\StorageManager;
use App\Services\Uploader\Uploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AnalyzeController extends Controller
{
    private static $uploader;
    public function __construct(Uploader $uploader)
    {
        self::$uploader = $uploader;
    }

    public function index(){
        $analyze = Analyze::all();

        return response()->json([
            'analyze'=>$analyze
        ],200);
    }
    //download analyze
    public function show(Analyze $analyze){
        return $analyze->download();
    }
    //destroy analyze
    public function destroy(Analyze $analyze){
        StorageManager::deleteFile($analyze->file,$analyze->type,true);

        $analyze->delete();

        return response()->json([
            'success'=>'عملیات با موفقیت انجام شد.'
        ],200);
    }
    //store
    public function store(Request $request){
        //validate
        $validator = Validator::make($request->all(),[
            'file'=>'required|file|mimes:png,jpeg,jpg,pdf,zip,rar,docx,svg,doc|max:20000',
            'level'=>'required|numeric|digits:1'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        //upload file
        $fileName = self::$uploader->upload(true,false);

        Analyze::create([
           'user_name'=>$request->user()->name,
            'level'=>$request->level,
            'user_id'=>$request->user()->id,
            'type'=>self::$uploader->getType($request->file('file')->getClientMimeType()),
            'file'=>$fileName
        ]);

        //response
        return response()->json([
            'success'=>'فایل آنالیز با موفقیت ارسال شد.'
        ],201);
    }
}
