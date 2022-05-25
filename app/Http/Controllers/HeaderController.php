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
    public function image_header(Request $request){
        //validate
        $validator = Validator::make($request->only(['file']),[
            'file'=>'required|mimes:png,jpg,jpeg|file'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        // upload file
        $fileName = self::$uploader->upload(false);
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
}
