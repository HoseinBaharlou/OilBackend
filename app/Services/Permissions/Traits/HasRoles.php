<?php
namespace App\Services\Permissions\Traits;

use App\Models\Role;
use Illuminate\Support\Arr;

trait HasRoles{
    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function giveRolesTo(...$roles){
        $roles = $this->getAllRoles($roles);
        if ($roles->isEmpty()){
            return response()->json([
                'errors'=>'این سطوح دسترسی متاسفانه وجود ندارد!'
            ],403);
        }
        //add roles
        $this->roles()->syncWithoutDetaching($roles);
    }

    //check roles in roles table
    public function getAllRoles(array $roles){
        return Role::whereIn('name',Arr::flatten($roles))->get();
    }

    //remove role of user
    public function withDrawRoles(array $roles){
        $roles = $this->getAllRoles($roles);
        if ($roles->isEmpty()){
            return response()->json([
                'errors'=>'این سطوح دسترسی متاسفانه وجود ندارد!'
            ],403);
        }

        //detach role
        $this->roles()->detach($roles);
    }

    //refresh roles
    public function refreshRoles(array $roles){
        $roles = $this->getAllRoles($roles);
        if ($roles->isEmpty()){
            return response()->json([
                'errors'=>'این سطوح دسترسی متاسفانه وجود ندارد!'
            ],403);
        }
        //async role
        $this->roles()->sync($roles);
    }

    //check role user
    public function HasRoles(string $roles){
        dd($this->roles->contains('name',$roles));
    }
}
