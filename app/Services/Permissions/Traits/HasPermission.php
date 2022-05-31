<?php
namespace App\Services\Permissions\Traits;


use App\Models\Permission;
use Illuminate\Support\Arr;

trait HasPermission{
    //create relashonship
    public function permissions(){
        return $this->belongsToMany(\App\Models\Permission::class);
    }
    //check permissions and sync permission
    public function givePermission(...$permissions){
        $permissions = $this->getAllPermission($permissions);
        if ($permissions->isEmpty()){
            return response()->json([
                'errors'=>'این سطوح دسترسی متاسفانه وجود ندارد!'
            ],403);
        }
        $this->permissions()->syncWithoutDetaching($permissions);
    }
    // check permission on permission table
    protected function getAllPermission(array $permission){
        return Permission::whereIn('name',Arr::flatten($permission))->get();
    }

    //remove permission
    public function withDrawPermissions(...$permissions){
        $permissions = $this->getAllPermission($permissions); //check permission
        //detach permission
        $this->permissions()->detach($permissions);
    }

    //refresh permission
    public function RefreshPermission(array $permissions){
        $permissions = $this->getAllPermission($permissions); //check permission

        //sync permission
        $this->permissions()->sync($permissions);
    }

    //check permission user
    public function HasPermission(Permission $permissions){
        return $this->hasPermissionThroughRoles($permissions) || $this->permissions->contains($permissions);
    }

    protected function hasPermissionThroughRoles(Permission $permission){
        foreach ($permission->roles as $role){
            if ($this->roles->contains($role)) return true;
        }
        return false;
    }
}
