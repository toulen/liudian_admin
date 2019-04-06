<?php
namespace Liudian\Admin\Foundation;

use Liudian\Admin\Facades\RbacPermission;
use Liudian\Admin\Model\AdminRbacPermission;
use Liudian\Admin\Repositories\AdminRbacPermissionRepository;

trait ControllerFoundation
{

    protected $data = [
        'layout' => true,
        'pageTitle' => '',
        'pageBreadcrumb' => []
    ];

    /**
     * 渲染视图
     * @param $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render($view, $data = []){

        $this->commonData();

        $data = array_merge($this->data, $data);

        return view($view, $data);
    }

    /**
     * 页面公用数据
     */
    public function commonData(){

        // 获取所有的导航（页面权限作为导航）
        $this->data['firstDepthNav'] = RbacPermission::getPermissionFirstDepthNav();

        // 获取哪些菜单应该被选中
        $this->data['pageActive'] = RbacPermission::getPageActive();

        // 获取左侧菜单列表
        $this->data['leftNavs'] = RbacPermission::getLeftNavs();
    }
}