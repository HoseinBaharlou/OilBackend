<?php

namespace App\Services\Uploader;

use Illuminate\Http\Request;

class Uploader{

  private $storageManager;
  private $file;

  public function __construct(Request $request,StorageManager $storageManager)
  {
    $this->file = $request->file('file');
    $this->storageManager = $storageManager;
  }
  // upload file in public folder
  public function upload_public(){
    // create file name
    $file_name = $this->File_Name($this->file->getClientOriginalExtension());
    // upload 
    $this->storageManager->putFileAsPublic($this->File_Name($this->file->getClientOriginalExtension()),$this->file,$this->getType());
    // return file name
    return $file_name;
  }
  // upload file in private folder
  public function upload_private(){
    // create file name
    $file_name = $this->File_Name($this->file->getClientOriginalExtension());
    // upload 
    $this->storageManager->putFileAsPrivate($file_name,$this->file,$this->getType());
    // return file name
    return $file_name;
  }
  // GET Mime type
  private function getType(){
    return[
      'image/jpeg'=>'image',
      'image/png'=>'image',
      'application/pdf'=>'pdf'
    ][$this->file->getClientMimeType()];
  }

  // change file name
  private function File_Name(string $Extension){
    return sha1(time()).'.'.$Extension;
  }
}