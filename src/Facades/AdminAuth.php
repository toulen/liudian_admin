<?php
namespace Liudian\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class AdminAuth extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'admin_auth';
    }
}