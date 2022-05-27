<?php

namespace App\Http\Controllers;

use App\Models\Header;
use App\Services\convert_date\convert_date;
use App\Services\Uploader\StorageManager;
use App\Services\Uploader\Uploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Hekmatinasser\Verta\Verta;
class HeaderController extends Controller
{
    private static $uploader;
    public function __construct(Uploader $uploader)
    {
        self::$uploader = $uploader;
    }
    //image header
    public function image_header(Request $request){
        //validate
        $validator = Validator::make($request->only(['file','width','height']),[
            'file'=>'required|mimes:png,jpg,jpeg|file',
            'width'=>'required|numeric|min:2',
            'height'=>'required|numeric|min:2'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        // upload file
        $fileName = self::$uploader->upload(false,true);
        // get image size and file size
        $image = getimagesize($request->file);

        $size = $request->file('file')->getSize();
        //check database
        $header = Header::all();
        if (count($header) > 0){
            StorageManager::deleteFile($header->first()->file,'image',false);
            Header::where('id','=',1)->update([
                'file'=>$fileName,
                'size'=>number_format($size / 1048576, 2) . ' MB',
                'file_info'=>implode(',',$image),
                'slider'=>0
            ]);
        }else{
            Header::create([
                'name'=>$fileName,
                'size'=>number_format($size / 1048576, 2) . ' MB',
                'file_info'=>implode(',',$image),
                'slider'=>0
            ]);
        }
        //response
        return response()->json([
            'name'=>$fileName,
            'url'=>URL::to('/').'/storage/image/'.$fileName,
            'width'=>$image[0],
            'height'=>$image[1],
            'format'=>explode('.',$fileName)[1],
            'size'=>number_format($size / 1048576, 2) . ' MB',
            'created_at'=>convert_date::jalali($header->first()->created_at),
            'updated_at'=>convert_date::jalali($header->first()->updated_at)
        ]);
    }

    // slider header
    public function Slider_header(Request $request){
        //validate
        $validator = Validator::make($request->only('file','slider','width','height'),[
            'file'=>'required',
            'file.*'=>'mimes:png,jpeg,jpg',
            'slider'=>'required|boolean',
            'width'=>'required|numeric|min:2',
            'height'=>'required|numeric|min:2'
        ]);
        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        // upload file
        $FilesName = self::$uploader->upload(false,true);
        //add to table header
        $header = Header::all();
        if (count($header) > 0){
            //check file for add to table
            $Files = $header->where('id','=',1)->first();
            $Files = explode(',',$Files->file);
            $FilesName = explode(',',$FilesName);
            foreach ($Files as $item){
                if (file_exists(storage_path('app/public/image/'.$item))){
                    array_push($FilesName,$item);
                }
            }
            Header::where('id','=',1)->update([
                'file'=>implode(',',$FilesName),
                'slider'=>$request->slider,
                'width'=>$request->width,
                'height'=>$request->height,
                'size'=>null,
            ]);
        }else{

            Header::create([
                'name'=>implode(',',$FilesName),
                'slider'=>$request->slider,
                'size'=>null,

            ]);
        }

        //add url to files and check file exists
        if (gettype($FilesName) == 'string'){
            $FilesName = explode(',',$FilesName);
        }
        $New_File_Url = [];
        $New_File_Name = [];
        foreach ($FilesName as $item){
            $new_path = storage_path('app/public/image/'.$item);
            if (file_exists($new_path)){
                array_push($New_File_Url,URL::to('/').'/storage/image/'.$item);
                array_push($New_File_Name,$item);
            }
        }
        //response
        return response()->json([
            'name'=>$New_File_Name,
            'url'=>$New_File_Url,
            'updated_at'=>$header->first()->updated_at
        ]);
    }

    //show header
    public function show(){
        $header = Header::all();
        //check empty table
        if($header->isEmpty()){
            return response()->json(null,200);
        }
        $FilesName = explode(',',$header->first()->file);
        $New_File_Url = [];
        foreach ($FilesName as $item){
            $new_path = storage_path('app/public/image/'.$item);
            if (file_exists($new_path)){
                array_push($New_File_Url,URL::to('/').'/storage/image/'.$item);
            }
        }
        return response()->json([
            'header'=>$header->first(),
            'url'=>$New_File_Url,
            'name'=>$FilesName
        ]);
    }
}
