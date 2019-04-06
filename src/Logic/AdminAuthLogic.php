<?php
namespace Liudian\Admin\Logic;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Liudian\Admin\Model\AdminUser;

class AdminAuthLogic
{

    private $sessionKey = 'liudian_admin_user';

    private $mode = 'safe';

    private $user;

    public function __construct(){

        $this->mode = config('liudian_admin.auth_mode', 'safe');
    }

    /**
     * 将登录信息写入SESSION
     * @param $admin
     */
    public function login($admin){

        $value = $this->mode == 'safe' ? $admin->id : $admin;

        Session::put(md5($this->sessionKey), $value);

        Session::save();
    }

    /**
     * @return mixed
     */
    private function getSessionInfo(){
        return Session::get(md5($this->sessionKey), null);
    }

    /**
     * 获取当前管理员用户
     * @return mixed|null
     */
    public function user(){

        if($this->user){
            return $this->user;
        }

        $sessionInfo = $this->getSessionInfo();

        if(!$sessionInfo){
            return null;
        }

        if($this->mode == 'normal'){

            return $sessionInfo;
        }

        $user = AdminUser::where([
            'status' => 1,
            'id' => $sessionInfo
        ])->first();

        return $user;
    }

    /**
     * 退出登录
     */
    public function logout(){

        $this->user = null;

        Session::remove(md5($this->sessionKey));

        Session::save();
    }
}