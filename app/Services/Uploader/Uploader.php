<?php

namespace App\Services\Uploader;

use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class Uploader{

  private $files;
  private $storageManager;
  public function __construct(Request $request,StorageManager $storageManager)
  {
    $this->storageManager = $storageManager;
    $this->files = $request->file('file');
  }

  public function upload($isPrivate){
    return $this->putFileIntoStorage($isPrivate);
  }

  private function putFileIntoStorage($isPrivate){
    $method = $isPrivate ? 'PutFileAsPrivate' : 'putFileAsPublic';
    $fileName = [];
    if (gettype($this->files) == "array"){
        for ($i=0;$i<count($this->files);$i++){
            $file = $this->files[$i];
            $FileName = $this->fileName($file->getClientOriginalExtension());
            // save file name
            array_push($fileName,$FileName);
            $Type = $this->getType($file->getClientMimeType());
            $this->storageManager->$method($FileName,$file,$Type);
        }
        $fileName = implode(',',$fileName);
        return $fileName;
    }else if(gettype($this->files) == "object"){
        $file = $this->files;
        $FileName = $this->fileName($file->getClientOriginalExtension());
        $Type = $this->getType($file->getClientMimeType());
        $this->storageManager->$method($FileName,$file,$Type);
        return $fileName = $FileName;
    }
  }

  private function getType($MimeType){
    return [
      'image/jpeg'=>'image',
      'video/mp4'=>'video',
      'application/zip'=>'archive',
      'image/png'=>'image'
    ][
      $MimeType
    ];
  }

  private function fileName($Extension){
      return sha1(random_bytes(20)).'.'.$Extension;
  }
}
