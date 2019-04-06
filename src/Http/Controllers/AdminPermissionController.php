<?php
namespace Liudian\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Admin\Repositories\AdminRbacPermissionRepository;

class AdminPermissionController extends Controller
{

    use ControllerFoundation, CommonReturn, ControllerCURD;

    protected $modelRepository;

    protected $pageConfig = [
        'pageTitle' => '菜单',
        'viewPrefix' => 'admin::permission',
        'indexRoute' => 'admin_permission_index'
    ];

    public function __construct(AdminRbacPermissionRepository $adminRbacPermissionRepository){

        $this->modelRepository = $adminRbacPermissionRepository;
    }

    public function beforeCreate($request){

        $permissions = $this->modelRepository->getPermissions();

        $this->data['permissions'] = $permissions;
    }

    public function beforeEdit($id, $request){

        $permissions = $this->modelRepository->getPermissions();

        $this->data['permissions'] = $permissions;
    }

    public function move($id, Request $request){

        $type = Input::get('type', 'left');

        if(!$data = $this->modelRepository->findById($id)){
            return self::returnErrorByMethod($request, '未找到菜单！');
        }

        $res = $this->modelRepository->move($data, $type);

        return self::returnJson($res);
    }
}