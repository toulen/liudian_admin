<?php
namespace Liudian\Admin\Helper;

trait CommonReturn
{

    /**
     * @param array $data
     * @param string $msg
     * @return array
     */
    public static function returnOkArr($data = [], $msg = ''){
        return [
            'status' => 1,
            'msg' => $msg,
            'data' => $data
        ];
    }

    /**
     * @param string $msg
     * @param array $data
     * @return array
     */
    public static function returnErrorArr($msg = '', $data = []){
        return [
            'status' => 0,
            'msg' => $msg,
            'data' => $data
        ];
    }

    /**
     * @param array $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public static function returnOkJson($data = [], $msg = ''){
        return response()->json([
            'status' => 1,
            'msg' => $msg ? : 'success',
            'data' => $data
        ]);
    }

    /**
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function returnErrorJson($msg = '', $data = []){
        return response()->json([
            'status' => 0,
            'msg' => $msg ? : 'error',
            'data' => $data
        ]);
    }

    /**
     * @param array $arr
     * @return \Illuminate\Http\JsonResponse
     */
    public static function returnJson($arr = []){
        return response()->json($arr);
    }

    public static function returnTableData($data){

        $returnData = [];
        $returnData['code'] = 0;
        $returnData['count'] = $data->total();
        $returnData['msg'] = '';
        $returnData['data'] = $data->all();

        return response()->json($returnData);
    }

    /**
     * 根据请求方式返回错误
     * @param $request
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public static function returnErrorByMethod($request, $msg = '', $data = []){
        if($request->ajax){
            return self::returnErrorJson($msg, $data);
        }

        return redirect()->back()->withErrors([
            'error' => $msg
        ]);
    }

    /**
     * 根据请求方式返回成功信息
     * @param $request
     * @param array $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public static function returnOkByMethod($request, $data = [], $msg = ''){
        if($request->ajax){
            return self::returnOkJson($data, $msg);
        }

        return redirect()->back()->withErrors([
            'success' => $msg
        ]);
    }
}