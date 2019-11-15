<?php
namespace Liudian\Admin\Http\Controllers;

use Liudian\Admin\Facades\AdminAuth;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Model\AdminRbacPermission;
use Liudian\Admin\Model\AdminRbacRolePermission;

class IndexController extends Controller
{

    use ControllerFoundation;

    public function index(){

        $this->data['pageTitle'] = '管理首页';

        return $this->render('admin::index.index');
    }
}