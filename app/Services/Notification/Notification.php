<?php
namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class Notification{
    public function __call($method, $arguments)
    {
        $ProviderPath = __NAMESPACE__.'\Providers\\'.substr($method,4).'Provider';
        if(!class_exists($ProviderPath)){
            throw new \Exception("class is not exists");
        }
        $providerInstance = new $ProviderPath;
        $providerInstance->send(...$arguments);
    }
}
?>