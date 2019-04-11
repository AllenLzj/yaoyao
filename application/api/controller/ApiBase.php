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
        if(empty($is_login)) return ['info'=>'请先登录！','status'=>0,'is_login'=>0];

    }

    //发送验证码
    public function verifyCode($phone)
    {
        if (check_phone($phone)) {
            $content_num = rand_num(6);
            $content = lang('your SMS verification code is:') . $content_num . lang('you are logged in to AirSnow and this verification code is valid for 10 minutes.');
            if (smsend($phone, $content)) {
                $redis = new Redis();
                $redis->set($phone, $content_num, 'EX', 600);
                $this->wrong(200, lang('the verification code was sent successfully'));
            } else {
                $this->wrong(601, lang('the verification code failed to be sent. Please retrieve it'));
            }
        } else {
            $this->wrong(602, lang('wrong mobile phone number, please re-enter'));
        }
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

    /**验证手机号和验证码是否是一致的
     * @param $phone
     * @param $code
     * @param $type
     * 不为空需要验证是否是用户的手机
     * User: WangMingxue
     * Email cumt_wangmingxue@126.com
     */
    public function checkCodePhone($phone, $code)
    {
        $redis = new Redis();
        if (!$redis->exists($phone)) {
            return lang('illegal cell phone number');
        }
        if ($redis->get($phone) != $code) {
            return lang('verification code error');
        }
        return false;
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
