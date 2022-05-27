<?php

namespace App\Services\Uploader;

use App\Services\Resize\Resize;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class Uploader{

  private $files;
  private $storageManager;
  private $request;
  public function __construct(Request $request,StorageManager $storageManager)
  {
    $this->storageManager = $storageManager;
    $this->files = $request->file('file');
    $this->request = $request;
  }

  public function upload($isPrivate,$Resize){
    return $this->putFileIntoStorage($isPrivate,$Resize);
  }
  // put file public or private (according to isPrivate parameter) into storage
  private function putFileIntoStorage($isPrivate,$Resize){
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
            //get image name and type file and public or private file for resize
            $FileForResize = [$FileName,$Type,$isPrivate];
            //Resize File(if was image)
            if ($Resize){
                Resize::ResizeImage($this->request->width,$this->request->height,$FileForResize);
            }
        }
        $fileName = implode(',',$fileName);
        return $fileName;
    }else if(gettype($this->files) == "object"){
        $file = $this->files;
        $FileName = $this->fileName($file->getClientOriginalExtension());
        $Type = $this->getType($file->getClientMimeType());
        $this->storageManager->$method($FileName,$file,$Type);
        //get image name and type file and public or private file for resize
        $FileForResize = [$FileName,$Type,$isPrivate];
        //Resize File(if was image)
        if ($Resize){
            Resize::ResizeImage($this->request->width,$this->request->height,$FileForResize);
        }
        return $fileName = $FileName;
    }
  }
  // get type file
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
 //generate file name
  private function fileName($Extension){
      return sha1(random_bytes(20)).'.'.$Extension;
  }
}
