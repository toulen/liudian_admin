<?php
namespace Liudian\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class RbacPermission extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rbac_permission';
    }
}