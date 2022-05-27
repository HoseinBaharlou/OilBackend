<?php
namespace App\Services\Resize;

use App\Services\Uploader\StorageManager;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;

class Resize{
    public static function ResizeImage($width,$height,$file){
        //file path
        $path = storage_path(self::DirectoryPrefix($file[0],$file[1],$file[2]));
//        check file exists

        //get format file
        $mimes = explode('.',$file[0])[1];
        // load image
        $image = ImageManagerStatic::make($path);
        //get original width and height
        $original_width = $image->width();
        $original_height = $image->height();

        //get image ratio
        $original_ratio = $original_width/$original_height;
        $requests_ratio = $width/$height;

        //image cropping
        if ($requests_ratio<$original_ratio){
            //cropping image of width
            $new_width = (int)($original_width*$requests_ratio);
            $image->resizeCanvas($new_width,null);
        }else{
            //cropping image of height
            $new_height = (int)($original_width*$requests_ratio);
            $image->resizeCanvas(null,$new_height);
        }
        // resize request image
        $image->resize($width,$height,function ($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // delete origin image and save new image
        $image->save();


    }
    private static function DirectoryPrefix($name,$type,$isPrivate){
        $isPrivate = $isPrivate ? 'private' : 'public';
        return 'app'.DIRECTORY_SEPARATOR.$isPrivate.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$name;
    }
}
