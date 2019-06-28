<?php
namespace Liudian\Admin\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Facades\AdminAuth;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Admin\Model\AdminRbacRole;
use Liudian\Admin\Model\AdminUser;
use Liudian\Admin\Model\AdminUserLoginLog;
use Tcaptcha\Tcaptcha;

class AdminUserRepository
{

    use CommonReturn;

    protected $adminUser;

    protected $loginErrorKey = 'liudian_admin_login_error';

    public function __construct(AdminUser $adminUser){

        $this->adminUser = $adminUser;
    }

    /**
     * 登录操作
     * @return array
     */
    public function login($request){

        if(!$this->getErrorTime()){
            return self::returnErrorArr('登录失败次数太多！请休息10分钟再来！');
        }

        $username = Input::get('username', '');
        $password = Input::get('password', '');
        $captcha = Input::get('captcha', '');

        $validator = Validator::make([
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha
        ], [
            'username' => 'required',
            'password' => 'required|min:6',
            'captcha' => 'required|size:' . config('liudian_admin.captcha.captchaOptions.size')
        ], [
            'username.required' => '请填写用户名！',
            'password.required' => '请填写密码！',
            'password.min' => '密码最少需要6位！',
            'captcha.required' => '验证码必填！',
            'captcha.size' => '验证码错误！',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            if($errors){

                $errors = array_pop($errors);

                return self::returnErrorArr($errors[0]);
            }
        }

        if(!Tcaptcha::check($captcha)){

            return self::returnErrorArr('验证码错误！');
        }

        // 验证成功
        $adminUser = $this->adminUser->where([
            'username' => $username,
            'status' => 1
        ])->first();

        if(!$adminUser){

            // 记录失败次数

            $loginErrorTime = $this->writeErrorTime();

            if(!$loginErrorTime){

                return self::returnErrorArr('登录失败错误次数太多，请休息10分钟再来！');
            }

            return self::returnErrorArr('账号或密码错误！');
        }

        if(!Hash::check($password, $adminUser->password)){

            // 记录失败次数

            $loginErrorTime = $this->writeErrorTime();

            if(!$loginErrorTime){

                return self::returnErrorArr('登录失败错误次数太多，请休息10分钟再来！');
            }
            return self::returnErrorArr('账号或密码错误！');
        }

        // 登录成功！
        $rememberMe = Input::get('remember', 0);

        if($rememberMe){

            $rememberToken = $this->generateRememberToken();
            $adminUser->remember_token = $rememberToken;
            $adminUser->save();
        }

        // 写登录日志！
        $logObj = $this->writeLoginLog($request, $adminUser);

        if(!$logObj){

            return self::returnErrorArr('登录失败！');
        }

        AdminAuth::login($adminUser);

        $this->cleanErrorTime();

        // 更新权限信息
        foreach ($adminUser->roles as $role){
            (new AdminRbacRoleRepository(new AdminRbacRole()))->generatePermissionCache($role);
        }

        return self::returnOkArr($adminUser);
    }

    /**
     * 写登录错误次数信息
     * @return bool
     */
    protected function writeErrorTime(){

        $loginErrorInfo = Session::get(md5($this->loginErrorKey), []);

        if(empty($loginErrorInfo)){
            $loginErrorInfo = [
                'count' => 0,
                'time' => time()
            ];
        }

        $loginErrorInfo['count'] += 1;

        Session::put(md5($this->loginErrorKey), $loginErrorInfo);

        Session::save();

        if($loginErrorInfo['count'] >= config('liudian_admin.max_login_error_count', 5)){
            return false;
        }

        return true;
    }

    /**
     * 判断错误次数是否太多
     * @return bool
     */
    protected function getErrorTime(){
        $loginErrorInfo = Session::get(md5($this->loginErrorKey), []);

        if(empty($loginErrorInfo)){
            $loginErrorInfo = [
                'count' => 0,
                'time' => time()
            ];
        }

        if($loginErrorInfo['count'] >= config('liudian_admin.max_login_error_count', 5) && $loginErrorInfo['time'] > time() - 600){

            return false;
        }

        return true;
    }

    /**
     * 清空登录错误次数！
     */
    protected function cleanErrorTime(){
        Session::remove(md5($this->loginErrorKey));
        Session::save();
    }

    protected function writeLoginLog($request, $adminUser){

        return AdminUserLoginLog::create([
            'admin_id' => $adminUser->id,
            'login_ip' => $request->getClientIp()
        ]);
    }

    protected function generateRememberToken(){
        return md5(rand(0, 9999999) . '_' . microtime(true));
    }


    /*  CURD  */

    /**
     * 获取首页列表数据
     * @return mixed
     */
    public function getLists(){

        $limit = Input::get('limit', 10);

        $mode = $this->adminUser->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['username']) && trim($param['username'])){
            $mode->where(function ($query) use($param){
                $query->where('username', 'like', '%'.$param['username'].'%')->orWhere('phone', 'like', '%'.$param['username'].'%');
            });
        }
        if(isset($param['nickname']) && trim($param['nickname'])){
            $mode->where('nickname', 'like', '%'.$param['nickname'].'%');
        }

        $data = $mode->paginate($limit);

        return $data;
    }

    /**
     * 新增管理员
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

        // 执行写入数据！
        $adminUser = $this->adminUser->create($param);

        if(!$adminUser){
            return self::returnErrorArr('保存失败！');
        }

        // 写日志
        event(new AdminLog(AdminUser::class, $adminUser->id, '创建', '创建新管理员（'.$adminUser->username.'）', $param));

        return self::returnOkArr();
    }

    /**
     * 根据ID找到数据
     * @param $id
     * @return mixed
     */
    public function findById($id){
        return $this->adminUser->where('status', '>=', 0)->where([
            'id' => $id
        ])->first();
    }

    public function editPassword($id){

        $data = $this->findById($id);

        $rules = $this->rules();

        unset($rules['username']);

        unset($rules['nickname']);

        $messages = $this->messages();

        if($data->id == \AdminAuth::user()->id) {

            $rules['old_password'] = 'required';

            $messages['old_password.required'] = '旧密码必填！';
        }

        $param = Input::get('param', []);

        $validator = Validator::make($param, $rules, $messages);

        if($validator->fails()){
            // 有错
            $errors = $validator->errors()->getMessages();

            if($errors){

                $errors = array_pop($errors);

                return self::returnErrorArr($errors[0]);
            }
        }

        // 修改
        if($data->id == \AdminAuth::user()->id && !Hash::check($param['old_password'], $data->password)){
            return self::returnErrorArr('旧密码错误！');
        }

        $data->password = $param['password'];

        $data->save();

        event(new AdminLog(AdminUser::class, $data->id, '修改密码', '修改管理员（'.$data->username.'）的密码', $param));

        return self::returnOkArr();
    }

    /**
     * 编辑资料
     * @param $id
     * @return array
     */
    public function edit($id){

        $data = $this->findById($id);

        $rules = $this->rules();

        unset($rules['username']);

        unset($rules['password']);

        $param = Input::get('param', []);

        $validator = Validator::make($param, $rules, $this->messages());

        if($validator->fails()){
            // 有错
            $errors = $validator->errors()->getMessages();

            if($errors){

                $errors = array_pop($errors);

                return self::returnErrorArr($errors[0]);
            }
        }

        $data->update($param);

        event(new AdminLog(AdminUser::class, $data->id, '编辑', '修改管理员（'.$data->username.'）的资料', $param));

        return self::returnOkArr();
    }

    /**
     * 删除数据！
     * @param $id
     * @return array
     */
    public function delete($id){

        $adminUser = $this->adminUser->find($id);

        if(!$adminUser){
            return self::returnErrorArr('未找到要删除的数据！');
        }

        $adminUser->status = -1;

        $res = $adminUser->save();

        if(!$res){
            return self::returnErrorArr('删除失败！');
        }

        event(new AdminLog(AdminUser::class, $adminUser->id, '删除', '删除管理员（'.$adminUser->username.'）'));

        return self::returnOkArr();
    }

    /**
     * 生成管理员对应的分组
     * @param $data
     */
    public function generateRolesCache($data){

        $roles = $data->roleIds->pluck('role_id');

        $allAdminRoles = Cache::get('admin_user_roles', []);

        $allAdminRoles[$data->id] = $roles;

        Cache::forever('admin_user_roles', $allAdminRoles);
    }

    private function rules(){
        return [
            'username' => 'required|min:5|unique:admin_users',
            'nickname' => 'required',
            'password' => 'required|min:6|confirmed'
        ];
    }

    private function messages(){
        return [
            'username.required' => '登录账号必填！',
            'username.min' => '登录账号最少5个字符！',
            'username.unique' => '登录账号被占用！请更换！',
            'nickname.required' => '姓名/昵称必填！',
            'password.required' => '登录密码必填！',
            'password.min' => '登录密码最少6位！',
            'password.confirmed' => '两次密码输入不一致！'
        ];
    }
}