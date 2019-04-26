<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Response;
use think\exception\HttpResponseException;

/**
 * 后台首页控制器
 *
 * @author Allen.liu
 */
//指定其他域名访问
header('Access-Control-Allow-Origin:*');
class Login extends Controller
{

    public function login(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $data = $request->only('email,password');
        $user = db('user')->where('email',$data['email'])->find();
        if ($user) {
                switch ($user['status']) {
                    case 0: {
                        $this->wrong(0, '用户被禁用');
                        break;
                    }
                    default: {
                        if ($user['password'] === think_admin_md5($data['password'], UC_AUTH_KEY)) {
                            //更换token绑定
                            $login_data = [
                                'last_login_ip' => $request->ip(),
                                'last_login_time' => $date,
                                'is_login' => 1,
                                ];
                            db('user')->where(['id'=>$user['id']])->update($login_data);
                            unset($data['password']);
                            $data['user_id'] = $user['id'];

                                $this->wrong(200, '登录成功', [], $data);
                        } else {
                            $this->wrong(0, '密码错误');
                        }
                        break;
                    }
            }
        } else {
            $this->wrong(0, '用户不存在');
        }

    }


    /* 退出登录 */
    public function logout($user_id)
    {
        $res = db('user')->where('id',$user_id)->update(['is_login'=>0]);
        if($res){
            $this->wrong(200, '退出登录成功');
        }else{
            $this->wrong(401, '退出登录失败');
        }

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
