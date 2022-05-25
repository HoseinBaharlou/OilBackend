<?php

namespace App\Services\Uploader;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StorageManager{
  public function PutFileAsPrivate(String $name,UploadedFile $file,String $type){
    return Storage::disk('private')->putFileAs($type,$file,$name);
  }

  public function putFileAsPublic(string $name,UploadedFile $file,string $type){
    return Storage::disk('public')->putFileAs($type,$file,$name);
  }

  public static function deleteFile(string $name,string $type,bool $isPrivate){
      $isPrivate = $isPrivate ? 'private' : 'public';
      return Storage::disk($isPrivate)->delete(self::DirectoryPrefix($type,$name));
  }
//  directory private or public address
  public static function DirectoryPrefix($type,$name){
      return $type.DIRECTORY_SEPARATOR.$name;
  }
}
