<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Services\Permissions\Traits\HasPermission;
use App\Services\Permissions\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasPermission,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password','last_name','phone_number','agreement','tag','address','zip_code','email_code','expire_time','remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['permission','profile_src'];

    public function ip_addresses(){
        return $this->hasOne(IpAddress::class)->latest();
    }

    public function getPermissionAttribute()
    {

        return auth()->user()->permissions->pluck('name');

    }

    public function getProfileSrcAttribute()
    {

        if (auth()->user()->profile){
            return asset('profiles/'.auth()->user()->profile);
        }else{
            return null;
        }

    }
}
