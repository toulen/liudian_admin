<?php

namespace Liudian\Admin\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Liudian\Admin\Model\AdminUser;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $currentRoute = Route::currentRouteName();

        $exceptCheckLogin = config('liudian_admin.except_check_login', []);

        // 不需要验证登录，直接跳过
        if(in_array($currentRoute, $exceptCheckLogin)){
            return $next($request);
        }

        // 未登录
        if(!\Liudian\Admin\Facades\AdminAuth::user()){

            // 检测COOKIE
            $rememberToken = Cookie::get('liudian_admin_admin_user_remember_token');

            if(!$rememberToken) {
                return redirect()->route('admin_login');
            }else{
                // 去寻找用户
                $adminUser = AdminUser::where([
                    'status' => 1,
                    'remember_token' => $rememberToken
                ])->first();

                if(!$adminUser){

                    return redirect()->route('admin_login');
                }

                // 登录
                \AdminAuth::login($adminUser);
            }
        }

        // RBAC权限验证
        $adminUser = \AdminAuth::user();

        if($adminUser->supper_admin){

            return $next($request);
        }

        $exceptCheckPerssion = config('liudian_admin.except_permission', []);

        if(in_array($currentRoute, $exceptCheckPerssion)){
            return $next($request);
        }

        // 判断是否是修改个人资料和密码
        $userInfoRoutes = ['admin_account_edit', 'admin_account_edit_password'];

        if(in_array($currentRoute, $userInfoRoutes)){
            $pathInfo = explode('/', $request->getPathInfo());

            if($pathInfo) {

                $id = $pathInfo[count($pathInfo) - 1];

                // 修改自己
                if($id == $adminUser->id){

                    return $next($request);
                }
            }

        }

        // 没有权限
        if(!$adminUser->can($currentRoute)){
            return redirect()->route('admin_public_no_permission');
        }

        return $next($request);
    }
}
