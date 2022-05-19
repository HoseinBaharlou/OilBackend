<?php

namespace App\Services\Notification\Providers;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class VerifyCodeProvider{
    public function send($email,Mailable $mailable){
        return Mail::to($email)->send($mailable);
    }
}