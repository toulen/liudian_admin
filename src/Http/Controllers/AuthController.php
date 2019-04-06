<?php
namespace Liudian\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Liudian\Admin\Facades\AdminAuth;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Admin\Repositories\AdminUserRepository;

class AuthController extends Controller
{

    use CommonReturn, ControllerFoundation;

    protected $adminUserRepository;


    public function __construct(AdminUserRepository $adminUserRepository){

        $this->adminUserRepository = $adminUserRepository;
    }

    /**
     * 后台账号登录
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function login(Request $request){

        // 已经登录，去首页
        if(AdminAuth::user()){

            return redirect()->route('admin_index');
        }

        if($request->method() == 'POST') {

            $loginRes = $this->adminUserRepository->login($request);

            if($loginRes['status'] == 0){
                return self::returnJson($loginRes);
            }

            // 成功，判断是否记住了密码
            $rememberMe = Input::get('remember', 0);

            $response = self::returnOkJson();

            if($rememberMe){
                $response = $response->withCookie(Cookie::make('liudian_admin_admin_user_remember_token', $loginRes['data']->remember_token, 60 * 24 * 7));
            }

            return $response;
        }

        $this->data['prev_url'] = \URL::previous();

        return $this->render('admin::auth.login');
    }

    /**
     * 退出登录状态！
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request){

        if(AdminAuth::user()){

            AdminAuth::logout();
        }

        $cookie = Cookie::forget('liudian_admin_admin_user_remember_token');

        return redirect()->route('admin_login')->withCookie($cookie);
    }
}