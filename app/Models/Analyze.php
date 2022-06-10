<?php

namespace App\Models;

use App\Services\Uploader\StorageManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyze extends Model
{
    use HasFactory;

    protected $fillable = ['level','file','type','user_id','user_name'];

    protected $hidden = ['email','phone_number'];

    public function users(){
        return $this->belongsTo(User::class);
    }


    //download file
    public function download(){
        return resolve(StorageManager::class)->getFile($this->file,$this->type,true);
    }
}
