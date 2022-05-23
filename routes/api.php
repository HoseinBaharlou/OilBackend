<?php

use App\Jobs\sendEmail;
use App\Mail\VerifyCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth routes
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace'=> 'App\Http\Controllers\auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('profile', 'AuthController@profile');
    Route::post('otp','AuthController@otp');
    Route::post('sendEmailVerify','AuthController@createCode');

});


//category
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::post('/category','CategoryController@store');
    Route::get('/category','CategoryController@show');
});

//article
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::post('/article','ArticleController@store');
});

//news
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::post('/news','NewsController@store');
});
