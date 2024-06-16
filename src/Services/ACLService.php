<?php

namespace EasyPanel\Services;

use Arr;
use EasyPanel\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class ACLService
{
    /**
     * @param Collection<Role> $roles
     * @param $permission
     * @return void
     */
    public static function hasPermission(Collection $roles, $permission): bool
    {

        foreach ($roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;

    }


}