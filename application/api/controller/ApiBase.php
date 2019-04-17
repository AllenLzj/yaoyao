<?php

namespace app\api\controller;

use think\Controller;
use Hashids\Hashids;
use think\Request;
use think\Db;
use Predis\Client as Redis;
use think\Response;
use think\exception\HttpResponseException;

//指定其他域名访问
header('Access-Control-Allow-Origin:*');

class ApiBase extends Controller
{

    protected static $access_token, $timestamp, $sign, $sign_data;
    protected static $return_data = '';

    public function __construct(Request $request = null)
    {
        self::$return_data = ['code' => 200, 'message' => lang('data return successful')];
        parent::__construct($request);
        $user_id = $request->param('user_id');
        $is_login = db('user')->where(['id'=>$user_id,'is_login'=>1])->count();
//        if(empty($is_login)) $this->wrong(666, '请重新登录');

    }



    //组合数据
    public function mergeData($data = null)
    {
        $return_data = self::$return_data;
        if ($data) {
            $return_data['data'] = $data;
        }
        return $return_data;
    }


    /**
     * @param int $code
     * @param string $message
     * @param array $header
     */
    protected function wrong($code = 500, $message = '', $header = [], $data=[])
    {
        $result = [
            'code' => $code,
            'message' => $message,
        ];
        if($data){
            $result['data'] = $data;
        }
        $response = Response::create($result, 'json')->header($header);
        throw new HttpResponseException($response);
    }
}
