<?php
namespace Liudian\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Admin\Model\AdminRbacPermission;
use Liudian\Admin\Model\AdminRbacRolePermission;
use Liudian\Admin\Repositories\AdminRbacRoleRepository;

class AdminRoleController extends Controller
{

    use ControllerFoundation, CommonReturn, ControllerCURD;

    protected $modelRepository;

    protected $pageConfig = [
        'pageTitle' => '管理员分组',
        'viewPrefix' => 'admin::role',
        'indexRoute' => 'admin_role_index'
    ];

    public function __construct(AdminRbacRoleRepository $adminRbacRoleRepository){

        $this->modelRepository = $adminRbacRoleRepository;
    }

    public function permissions($id, Request $request){

        if(!$data = $this->modelRepository->findById($id)){
            return self::returnErrorByMethod($request, '未找到分组！');
        }

        if($request->isMethod('POST')){
            $param = Input::get('param', []);

            $permissions = isset($param['permissions']) ? $param['permissions'] : [];

            // 删除以前的
            AdminRbacRolePermission::where([
                'role_id' => $id
            ])->delete();

            $insertData = [];

            foreach ($permissions as $permission){
                $insertData[] = [
                    'role_id' => $id,
                    'permission_id' => $permission
                ];
            }

            \DB::table((new AdminRbacRolePermission())->getTable())->insert($insertData);

            event(new AdminLog(AdminRbacRolePermission::class, $id, '设置权限', '设置（'.$data->name.'）权限'), $insertData);

            // 生成角色的权限
            $this->modelRepository->generatePermissionCache($data);

            return self::returnOkArr();
        }


        $this->data['data'] = $data;

        $this->data['pageTitle'] = '管理员分组的权限管理';

        $this->data['pageBreadcrumb'][] = [
            'name' => '管理员分组列表',
            'link' => route($this->pageConfig['indexRoute'])
        ];

        $this->data['pageBreadcrumb'][] = [
            'name' => '分组权限管理',
            'link' => ''
        ];

        $this->data['permissions'] = AdminRbacPermission::where([
            'status' => 1
        ])->orderBy('left_key')->get();

        return $this->render($this->pageConfig['viewPrefix'] . '.permissions');
    }
}