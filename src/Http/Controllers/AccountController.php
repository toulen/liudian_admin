<?php
namespace Liudian\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Admin\Model\AdminRbacPermission;
use Liudian\Admin\Model\AdminRbacRole;
use Liudian\Admin\Model\AdminRbacUserRole;
use Liudian\Admin\Model\AdminUser;
use Liudian\Admin\Repositories\AdminUserRepository;

class AccountController extends Controller
{

    use ControllerFoundation, CommonReturn, ControllerCURD;

    protected $modelRepository;

    protected $pageConfig = [
        'pageTitle' => '管理员',
        'viewPrefix' => 'admin::account',
        'indexRoute' => 'admin_account_index'
    ];

    public function __construct(AdminUserRepository $adminUserRepository){

        $this->modelRepository = $adminUserRepository;
    }

    /**
     * 修改管理员密码
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editPassword($id, Request $request){

        if(!$data = $this->modelRepository->findById($id)){
            return self::returnErrorByMethod($request, '未找到管理员！');
        }

        if($request->isMethod('POST')){

            $res = $this->modelRepository->editPassword($id);

            return self::returnJson($res);
        }

        $this->data['pageTitle'] = '修改管理员密码';

        $this->data['pageBreadcrumb'][] = [
            'name' => '管路员列表',
            'link' => route('admin_account_index')
        ];
        $this->data['pageBreadcrumb'][] = [
            'name' => '修改管理员密码',
            'link' =>''
        ];

        $this->data['data'] = $data;

        return $this->render('admin::account.edit_password');

    }


    public function roles($id, Request $request){

        if(!$data = $this->modelRepository->findById($id)){
            return self::returnErrorByMethod($request, '未找到管理员！');
        }

        if($request->isMethod('POST')){

            $params = Input::get('param', []);

            // 删除以前的
            AdminRbacUserRole::where([
                'user_id' => $data->id
            ])->delete();

            // 新增
            $roles = isset($params['role']) ? $params['role'] : [];

            if($roles){

                $insertData = [];

                foreach ($roles as $role){

                    $insertData[] = [
                        'user_id' => $data->id,
                        'role_id' => $role
                    ];
                }

                \DB::table((new AdminRbacUserRole())->getTable())->insert($insertData);

                // 日志
                event(new AdminLog(AdminUser::class, $data->id, '编辑管路员分组', '编辑（'.$data->username.'）的分组'), $insertData);

                $this->modelRepository->generateRolesCache($data);

                return self::returnOkJson();
            }
        }

        $this->data['pageTitle'] = '编辑' . $this->pageConfig['pageTitle'] . '所属分组';

        $this->data['pageBreadcrumb'][] = [
            'name' => $this->pageConfig['pageTitle'] . '列表',
            'link' => route($this->pageConfig['indexRoute'])
        ];
        $this->data['pageBreadcrumb'][] = [
            'name' => '编辑' . $this->pageConfig['pageTitle'] . '所属分组',
            'link' =>''
        ];

        $this->data['data'] = $data;

        $roles = AdminRbacRole::where(['status' => 1])->get(['name', 'id']);

        $this->data['roles'] = $roles;

        return $this->render($this->pageConfig['viewPrefix'] . '.roles');
    }
}