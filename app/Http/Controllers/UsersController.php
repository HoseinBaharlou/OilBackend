<?php

namespace App\Http\Controllers;

use App\Http\Requests\usersRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'users'=>User::with('ip_addresses','roles','permissions')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:20',
            'last_name'=>'required|string|max:20',
            'email'=>'required|email|max:100',
            'tag'=>'required|numeric|digits_between:1,10',
            'address'=>'required|string|max:200',
            'phone_number'=>'required|numeric|digits:11',
            'zip_code'=>'required|numeric|digits_between:10,10',
            'password'=>'min:0|max:100'
        ]);

        //check validator
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        $password = null;
        if (!$request->password) $password = $this->checkPassword($id); else $password = Hash::make($request->password);
        //update table
        User::where('id','=',$id)->update([
            'name'=>$request->name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'tag'=>$request->tag,
            'zip_code'=>$request->zip_code,
            'password'=>$password
        ]);

        return response()->json([
            'success'=>'کاربر با موفقیت ویرایش شد'
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    //check user for change password
    public function checkPassword($id){
        $user = User::findOrFail($id)->first();

        return $user->password;
    }

    //add role and permission to user
    public function add_role_permission(Request $request,User $user){
        //validate
        $validator = Validator::make($request->all(),[
            'roles'=>'array',
            'permissions'=>'array',
        ]);
        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        //refresh permission and roles
        $user->refreshRoles($request->roles);

        $user->RefreshPermission($request->permissions);

        return response()->json([
            'success'=>'سطح دسترسی کاربر ما موفقیت ویرایش شد.'
        ],201);
    }
}
