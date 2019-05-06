<?php
namespace Liudian\Admin\Http\Controllers;

use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Model\AdminRbacPermission;
use Liudian\Admin\Model\AdminRbacRolePermission;

class IndexController extends Controller
{

    use ControllerFoundation;

    public function index(){

        $configIndex = config('liudian_admin.admin_index_route');

        if($configIndex != 'admin_index'){
            dd($configIndex);
            return redirect(route($configIndex));
        }

        $this->data['pageTitle'] = '管理首页';

        return $this->render('admin::index.index');
    }
}