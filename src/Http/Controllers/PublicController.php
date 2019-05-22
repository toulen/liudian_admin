<?php
namespace Liudian\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Liudian\Admin\Facades\ChinaArea;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Tcaptcha\Tcaptcha;

class PublicController extends Controller
{

    use ControllerFoundation, CommonReturn;

    /**
     * 生成图形验证码
     * @throws \Exception
     */
    public function captcha(){

        $tcaptcha = new Tcaptcha(config('liudian_admin.captcha'));

        $tcaptcha->generate();
    }


    public function ajaxUpload(Request $request){
        try {

            $nowUser = \AdminAuth::user();

            $path = 'public/uploads/' . $nowUser->id;

            $type = Input::get('type', 'image');

            if($type == 'image'){
                $path .= '/image/' . date('Y-m-d');
            }elseif($type == 'video'){
                $path .= '/video/' . date('Y-m-d');
            }

            $path = $request->file('upload')->store($path);

            return self::returnOkJson([
                'url' => Storage::disk('local')->url($path)
            ]);
        }catch(\Exception $e){
            return self::returnErrorJson($e->getMessage());
        }
    }

    public function noPermission(){

        $this->data['pageTitle'] = '暂无权限访问！';

        return $this->render('admin::error.no_permission');
    }

    public function getAreaChildren(){
        $id = Input::get('id', 0);

        if(!$id){
            return self::returnOkJson();
        }

        $lists = ChinaArea::getChildren($id);

        return self::returnOkJson($lists);
    }
}