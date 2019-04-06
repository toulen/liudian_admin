<?php
namespace Liudian\Admin\Foundation;

use Illuminate\Support\Facades\Cache;

trait AuthPermission
{

    public function can($permission){

        if(\AdminAuth::user()->supper_admin){
            return true;
        }

        $isId = is_numeric($permission) ? true : false;

        $roleCache = Cache::get('admin_user_roles', []);

        $userId = \AdminAuth::user()->id;

        if(!isset($roleCache[$userId])){
            // 没有角色，
            return false;
        }

        // 有角色
        $roles = $roleCache[$userId];

        $permissions = [];

        $permissionCache = Cache::get('admin_user_role_permissions', []);

        foreach ($roles as $role){
            if(isset($permissionCache[$role])){
                $ps = $permissionCache[$role]->toArray();
                $permissions = array_merge($permissions, $isId ? array_keys($ps) : array_values($ps));
            }
        }

        if(!in_array($permission, $permissions)){
            return false;
        }

        return true;
    }
}