<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Roles = Role::all();
        $Permissions = Permission::all();

        //response
        return response()->json([
            'roles'=>$Roles,
            'permissions'=>$Permissions
        ],200);
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
        //validator
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|min:3',
            'persian_name'=>'required|string|min:3'
        ]);
        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        //create role
        Role::create([
           'name'=>$request->name,
           'persian_name'=>$request->persian_name
        ]);

        //response
        return response()->json([
            'success'=>'نقش با موفقیت ایجاد شد.'
        ],201);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');

        return response()->json([
            'permissions'=>$permissions,
            'roles'=>$role,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //validate
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|min:3',
            'persian_name'=>'required|string|min:3',
            'permissions'=>'required|array|min:1',
        ]);
        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        //update role
        $role->update($request->only(['name','persian_name']));
        //refresh permission
        $role->RefreshPermission($request->permissions);

        return response()->json([
            'success'=>'نقش با موفقیت ویرایش شد.'
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
}
