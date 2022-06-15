<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tellme extends Model
{
    use HasFactory;

    protected $fillable = ['fullName','email','phoneNumber','content'];
}
