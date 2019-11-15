<?php
namespace Liudian\Admin\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Admin\Model\AdminRbacRole;

class AdminRbacRoleRepository
{
    use CommonReturn;

    protected $adminRbacRole;

    public function __construct(AdminRbacRole $adminRbacRole){

        $this->adminRbacRole = $adminRbacRole;
    }

    /**
     * 获取管理员分组列表
     * @return mixed
     */
    public function getLists(){

        $limit = Input::get('limit', 10);

        $model = $this->adminRbacRole->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['name']) && trim($param['name'])){

            $model->where('name', 'like', '%'. trim($param['name']) .'%');
        }

        $lists = $model->paginate($limit);

        return $lists;
    }

    /**
     * 新增分组
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

        // 新增数据！
        $adminRole = $this->adminRbacRole->create($param);

        if(!$adminRole){
            return self::returnErrorArr('保存失败！');
        }

        event(new AdminLog(AdminRbacRole::class, $adminRole->id, '新增', '新增管理员分组（'.$adminRole->name.'）', $param));

        return self::returnOkArr();
    }

    /**
     * 通过ID找
     * @param $id
     * @return mixed
     */
    public function findById($id){
        return $this->adminRbacRole->where('status', '>=', 0)->where([
            'id' => $id
        ])->first();
    }

    /**
     * 编辑
     * @param $id
     * @return array
     */
    public function edit($id){

        $data = $this->findById($id);

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

        $data->update($param);

        event(new AdminLog(AdminRbacRole::class, $data->id, '编辑', '编辑管理员分组（'.$data->name.'）', $param));

        return self::returnOkArr();
    }

    /**
     * 删除
     * @param $id
     * @return array
     */
    public function delete($id){

        $data = $this->findById($id);

        if(!$data){
            return self::returnErrorArr('未找到要删除的数据！');
        }

        $data->status = -1;

        $res = $data->save();

        if(!$res){
            return self::returnErrorArr('删除失败！');
        }

        event(new AdminLog(AdminRbacRole::class, $data->id, '删除', '删除管理员分组（'.$data->name.'）'));

        return self::returnOkArr();
    }

    /**
     * 生成当前角色拥有的权限列表
     * @param $role
     */
    public function generatePermissionCache($role){

        $rolePermissions = $role->permissions->pluck('route_name', 'id')->toArray();

        foreach ($rolePermissions as &$permission){

            $permission = explode(',', $permission);

            $permission = $permission[0];
        }

        $allRolePermissions = Cache::get('admin_user_role_permissions', []);

        $allRolePermissions[$role->id] = collect($rolePermissions);

        Cache::forever('admin_user_role_permissions', $allRolePermissions);
    }

    public function rules(){
        return [
            'name' => 'required'
        ];
    }

    public function messages(){
        return [
            'name.required' => '分组名称必填！'
        ];
    }
}