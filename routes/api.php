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
    'prefix' => 'auth',
    'namespace'=> 'App\Http\Controllers\auth'

], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('otp','AuthController@otp');
    Route::post('sendEmailVerify','AuthController@createCode');
});


//category
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::post('/category','CategoryController@store')->middleware(['auth:sanctum','can:create_category']);
    Route::get('/category','CategoryController@show');
    Route::get('/categories','CategoryController@index');
});

//show category
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::get('/categories/shop/{slug}','showCategoryController@shop');
    Route::get('/categories/post/{slug}','showCategoryController@post');
});

//post
Route::group([
    'namespace'=> 'App\Http\Controllers\Post'
],function (){
    Route::get('/posts','showPostController@index');
    Route::get('/posts/{id}','showPostController@show');
    Route::get('/post','PostController@index')->middleware(['auth:sanctum','can:post_list']);
    Route::post('/post','PostController@store')->middleware(['auth:sanctum,can:create_post']);
    Route::get('/post/{id}/edit','PostController@edit')->middleware(['auth:sanctum,can:edit_post']);
    Route::post('/post/{post}/update','PostController@update')->middleware(['auth:sanctum,can:edit_post']);
    Route::get('post/{id}/softDelete','PostController@softDelete')->middleware(['auth:sanctum','can:post_trash_manager']);
    Route::get('post/trash','PostController@trash')->middleware(['auth:sanctum','can:post_trash_manager']);
    Route::get('post/{id}/restore','PostController@restore')->middleware(['auth:sanctum','can:post_trash_manager']);
    Route::get('post/{id}/destroy','PostController@destroy')->middleware(['auth:sanctum','can:post_trash_manager']);
});
//route likes post
Route::group([
    'namespace'=> 'App\Http\Controllers\Post'
],function (){
    Route::post('likes/{post}','LikesController@store');
    Route::delete('likes/{post}','LikesController@destroy');
});

//route comments
Route::group([
    'namespace'=> 'App\Http\Controllers\Comment'
],function (){
    Route::post('comments/{post}','CommentController@store')->middleware('auth:sanctum');
    Route::post('comments/replies/{post}','ReplyController@store')->middleware('auth:sanctum');
    Route::delete('comments/{comment}','CommentController@destroy')->middleware(['auth:sanctum','can:delete_comment']);
});
// home page
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::post('/content-header','HeaderController@content_header')->middleware(['auth:sanctum','can:header_manager']);
    Route::post('/image-header','HeaderController@image_header')->middleware(['auth:sanctum','can:header_manager']);
    Route::post('/slider-header','HeaderController@Slider_header')->middleware(['auth:sanctum','can:header_manager']);
    Route::get('/show-header','HeaderController@show');
});


//removeFile
Route::post('/delete-file',function (Request $request){
    \App\Services\Uploader\StorageManager::deleteFile($request->name,$request->type,$request->isPrivate);
});

//users manager
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::get('users-list','UsersController@index')->middleware(['auth:sanctum','can:users_list']);
    Route::post('update-user/{id}','UsersController@update')->middleware(['auth:sanctum','can:edit_users']);
    Route::post('users/{user}/update-role','UsersController@add_role_permission')->middleware(['auth:sanctum','can:edit_role_user']);
    Route::get('user_roles','RoleController@index')->middleware(['auth:sanctum','can:users_list']);
});
//profile
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::post('profile','updateProfileController@update')->middleware('auth:sanctum');
    Route::delete('profile','updateProfileController@delete')->middleware('auth:sanctum');
});
//roles and permissions
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::get('roles','RoleController@index')->middleware(['auth:sanctum','can:role_list']);
    Route::post('roles','RoleController@store')->middleware(['auth:sanctum','can:create_role']);
    Route::get('roles/{role}','RoleController@edit')->middleware(['auth:sanctum','can:edit_role']);
    Route::post('roles/{role}/update','RoleController@update')->middleware(['auth:sanctum','can:edit_role']);
});

//product
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::get('product','ProductController@index')->middleware(['auth:sanctum','can:product_list']);
    Route::post('product','ProductController@store')->middleware(['auth:sanctum','can:product_create']);
    Route::get('product/{product}/edit','ProductController@edit')->middleware(['auth:sanctum','can:edit_product']);
    Route::post('product/{product}/update','ProductController@update')->middleware(['auth:sanctum','can:edit_product']);
    Route::get('product/{id}/softDelete','ProductController@softDelete')->middleware(['auth:sanctum','can:product_trash_manager']);
    Route::get('product/trash','ProductController@trash')->middleware(['auth:sanctum','can:product_trash_manager']);
    Route::get('product/{id}/restore','ProductController@restore')->middleware(['auth:sanctum','can:product_trash_manager']);
    Route::get('product/{id}/destroy','ProductController@destroy')->middleware(['auth:sanctum','can:product_trash_manager']);
});

//analyze file
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::get('analyze','AnalyzeController@index')->middleware(['auth:sanctum','can:file_manager']);
    Route::delete('analyze/{analyze}','AnalyzeController@destroy')->middleware(['auth:sanctum','can:file_manager']);
    Route::get('analyze/{analyze}','AnalyzeController@show');
    Route::post('analyze','AnalyzeController@store')->middleware('auth:sanctum');
});
//tell me
Route::group([
    'namespace'=> 'App\Http\Controllers'
],function (){
    Route::get('/tellMe','TellmeController@index')->middleware('auth:sanctum','can:tellMe_manager');
    Route::post('/tellMe','TellmeController@store');
});
Route::get('/content',[\App\Http\Controllers\ContentController::class,'index']);


