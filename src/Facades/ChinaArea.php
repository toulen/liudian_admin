<?php
namespace Liudian\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class ChinaArea extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'china_area';
    }
}