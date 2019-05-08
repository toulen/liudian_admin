<?php
namespace Liudian\Admin\Foundation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

trait ControllerCURD
{

    public function beforeIndex(){}


    public function beforeCreate($request){}


    public function beforeEdit($id, $request){}


    /**
     * 首页数据
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request){

        if($request->isMethod('POST')){

            $lists = $this->modelRepository->getLists();

            return $this->returnTableData($lists);
        }

        $this->data['pageTitle'] = $this->pageConfig['pageTitle'] . '列表';

        $this->data['pageBreadcrumb'][] = [
            'name' => $this->pageConfig['pageTitle'] . '列表',
            'link' => ''
        ];

        $this->beforeIndex();

        return $this->render($this->pageConfig['viewPrefix'] . '.index');
    }

    /**
     * 新建数据
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request){

        if($request->isMethod('POST')){

            $res = $this->modelRepository->create();

            return self::returnJson($res);
        }

        $this->data['pageTitle'] = '新增' . $this->pageConfig['pageTitle'];

        $this->data['pageBreadcrumb'][] = [
            'name' => $this->pageConfig['pageTitle'] . '列表',
            'link' => route($this->pageConfig['indexRoute'])
        ];
        $this->data['pageBreadcrumb'][] = [
            'name' => '新增' . $this->pageConfig['pageTitle'],
            'link' =>''
        ];


        $this->beforeCreate($request);

        return $this->render($this->pageConfig['viewPrefix'] . '.create');
    }

    /**
     * 编辑
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id, Request $request){

        if(!$data = $this->modelRepository->findById($id)){
            return self::returnErrorByMethod($request, '未找到数据！');
        }

        if($request->method() == 'POST') {

            $res = $this->modelRepository->edit($id);

            return self::returnJson($res);
        }

        $this->data['pageTitle'] = '编辑' . $this->pageConfig['pageTitle'];

        $this->data['pageBreadcrumb'][] = [
            'name' => $this->pageConfig['pageTitle'] . '列表',
            'link' => route($this->pageConfig['indexRoute'])
        ];
        $this->data['pageBreadcrumb'][] = [
            'name' => '编辑' . $this->pageConfig['pageTitle'],
            'link' =>''
        ];

        $this->data['data'] = $data;

        $this->beforeEdit($id, $request);

        return $this->render($this->pageConfig['viewPrefix'] . '.edit');
    }

    /**
     * 查看数据
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function show($id, Request $request){

        if(!$data = $this->modelRepository->findById($id)){
            return self::returnErrorByMethod($request, '未找到数据！');
        }

        $this->data['data'] = $data;


        $this->data['pageTitle'] = '查看' . $this->pageConfig['pageTitle'];

        $this->data['pageBreadcrumb'][] = [
            'name' => $this->pageConfig['pageTitle'] . '列表',
            'link' => route($this->pageConfig['indexRoute'])
        ];
        $this->data['pageBreadcrumb'][] = [
            'name' => '查看' . $this->pageConfig['pageTitle'],
            'link' =>''
        ];

        return $this->render($this->pageConfig['viewPrefix'] . '.show');
    }

    /**
     * 删除
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request){

        $res = $this->modelRepository->delete($id);

        return self::returnJson($res);
    }
}