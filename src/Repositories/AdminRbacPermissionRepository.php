<?php
namespace Liudian\Admin\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Admin\Model\AdminRbacPermission;
use Liudian\Admin\Model\AdminRbacRolePermission;

class AdminRbacPermissionRepository
{

    use CommonReturn;

    protected $adminRbacPermission;

    public function __construct(AdminRbacPermission $adminRbacPermission){

        $this->adminRbacPermission = $adminRbacPermission;
    }

    /**
     * 获取所有的
     * @return mixed
     */
    public function getPermissions(){

        if(!$permissions = Cache::get('admin_rbac_permissions', [])){

            $permissions = $this->adminRbacPermission->orderBy('left_key')->get();

            Cache::put('admin_rbac_permissions', $permissions, 60);
        }

        return $permissions;
    }

    /**
     * 获取一级菜单
     * @return mixed
     */
    public function getPermissionFirstDepthNav(){

        $permissions = $this->getPermissions()->where('nav_show', '=', 1);

        $permissions = $permissions->groupBy('depth');

        return isset($permissions[0]) ? $permissions[0] : [];
    }

    /**
     * 根据PERMISSION获取到当前路由的路径
     * @return array
     */
    public function getPageActive(){

        $permissions = $this->getPermissions();

        if(!$permissions || !$permissions->count()){

            return [];
        }

        $currentRoute = Route::currentRouteName();

        $nowPermission = $permissions->where('route_name', '=', $currentRoute);

        $nowPermission = $nowPermission->pop();

        if(!$nowPermission){

            return [];
        }

        $pageActives = [];

        $treePermissions = $permissions->where('left_key', '<=', $nowPermission->left_key)->where('right_key', '>=', $nowPermission->right_key);

        return $treePermissions->pluck('id')->toArray();
    }

    /**
     * 获取左侧菜单列表
     * @return \Illuminate\Support\Collection
     */
    public function getLeftNavs(){

        $permissions = $this->getPermissions();

        if(!$permissions || !$permissions->count()){

            return [];
        }

        $currentRoute = Route::currentRouteName();

        $nowPermission = $permissions->where('route_name', '=', $currentRoute);

        $nowPermission = $nowPermission->pop();

        if(!$nowPermission){

            return [];
        }

        $firstDepth = $permissions->where('left_key', '<=', $nowPermission->left_key)->where('right_key', '>=', $nowPermission->left_key)->where('depth', '=', 0);

        $firstDepth = $firstDepth->first();

        $treePermissions = $permissions->where('left_key', '>', $firstDepth->left_key)->where('right_key', '<', $firstDepth->right_key)->where('nav_show', '=', 1)->where('depth', '!=', 0);

        return $treePermissions;
    }

    // CURD

    /**
     * 获取菜单列表
     * @return mixed
     */
    public function getLists(){

        $model = $this->adminRbacPermission->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['name']) && trim($param['name'])){

            $model->where('name', 'like', '%'.trim($param['name']).'%');
        }

        $model->orderBy('left_key');

        $lists = $model->paginate(999999);

        return $lists;
    }

    /**
     * 新增菜单
     * @return array
     */
    public function create(){

        $param = Input::get('param', []);

        $validator = Validator::make($param, $this->rules(), $this->messages());

        if($validator->fails()){
            // 有错
            $errors = $validator->errors()->getMessages();

            if($errors){

                $errors = array_pop($errors);

                return self::returnErrorArr($errors[0]);
            }
        }

        \DB::beginTransaction();

        $adminPermission = $this->adminRbacPermission->create($param);

        if(!$adminPermission){

            \DB::rollBack();
            return self::returnErrorArr('保存失败！');
        }

        if($param['parent_id']){

            $parent = $this->findById($param['parent_id']);

            if(!$param){

                \DB::rollBack();
                return self::returnErrorArr('保存失败！');
            }

            try {
                $adminPermission->makeChildOf($parent);
            }catch (\Exception $e){}
        }

        event(new AdminLog(AdminRbacPermission::class, $adminPermission->id, '创建', '创建新菜单（'.$adminPermission->name.'）', $param));

        \DB::commit();

        Cache::forget('admin_rbac_permissions');

        return self::returnOkArr();
    }

    public function findById($id){

        return $this->adminRbacPermission->where('status', '>=', 0)->where([
            'id' => $id
        ])->first();
    }

    /**
     * 编辑
     * @param $id
     * @return array
     */
    public function edit($id){

        $param = Input::get('param', []);

        $validator = Validator::make($param, $this->rules(), $this->messages());

        if($validator->fails()){
            // 有错
            $errors = $validator->errors()->getMessages();

            if($errors){

                $errors = array_pop($errors);

                return self::returnErrorArr($errors[0]);
            }
        }

        $data = $this->findById($id);

        // 修改
        $oldParentId = $data->parent_id;

        $data->update($param);

        if($oldParentId != $param['parent_id']){
            // 更换
            if($param['parent_id'] == 0){
                $data->makeRoot();
            }else{
                $parent = $this->adminRbacPermission->where([
                    'status' => 1,
                    'id' => $param['parent_id']
                ])->first();

                $data->makeChildOf($parent);
            }
        }

        event(new AdminLog(AdminRbacPermission::class, $data->id, '编辑', '编辑菜单（'.$data->name.'）', $param));

        Cache::forget('admin_rbac_permissions');

        return self::returnOkArr();

    }


    /**
     * 删除
     * @param $id
     * @return array
     */
    public function delete($id){

        $data = $this->findById($id);

        AdminRbacRolePermission::where([
            'permission_id' => $id
        ])->delete();

        $name = $data->name;

        $data->delete();

        event(new AdminLog(AdminRbacPermission::class, $id, '删除', '删除菜单（'.$name.'）'));

        Cache::forget('admin_rbac_permissions');

        return self::returnOkArr();
    }

    /**
     * 移动
     * @param $data
     * @param $type
     * @return array
     */
    public function move($data, $type){

        $typeName = [
            'left' => '上',
            'right' => '下'
        ];

        if(!isset($typeName[$type])){

            return self::returnErrorArr('移动失败！');
        }

        $method = 'move' . ucfirst($type);

        $data->$method();


        event(new AdminLog(AdminRbacPermission::class, $data->id, '移动', $typeName[$type] . '移动菜单（'.$data->name.'）'));

        Cache::forget('admin_rbac_permissions');

        return self::returnOkArr();
    }

    public function rules(){

        return [
            'name' => 'required',
        ];
    }

    public function messages(){

        return [
            'name.required' => '菜单名称必填！'
        ];
    }
}