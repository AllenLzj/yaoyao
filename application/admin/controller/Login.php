<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\Controller;


/**
 * 后台首页控制器
 *
 * @author Allen.liu
 */
class Login extends Controller
{
    /**
     * 后台用户登录页
     *
     * @author Allen.liu
     */
    public function index()
    {
        return $this->fetch('');
    }

    /**
     * @param null $username
     * @param null $password
     * @return array
     * User: Allen.liu
     * 登录处理
     */
    public function login($phone = null, $password = null)
    {
        $Member = model('admin')->where(array('phone' => $phone))->find();
        if (!empty($Member)) {
            switch ($Member['status']) {
                case 0: {
                    $return_data = array('info' => '用户已被禁用！', 'status' => 0);
                    return $return_data;
                    break;
                }
                default: {
                    if ($Member['password'] === think_admin_md5($password, UC_AUTH_KEY)) {
                        model('admin')->login($Member['admin_id']);
                        $return_data = array('info' => '登录成功！', 'status' => 1, 'url' => url('admin/Role/index'));
                        return $return_data;

                    } else {
                        $return_data = array('info' => '密码错误！', 'status' => 0);
                        return $return_data;
                    }
                    break;
                }
            }
        } else { // 登录失败
            $return_data = array('info' => '用户不存在！', 'status' => 0);
            return $return_data;
        }

    }

    /* 退出登录 */
    public function logout()
    {
        if (session('?admin_info')) {
            model('Admin')->logout();
            $this->redirect('admin/Login/login');

        } else {
            $this->redirect('admin/Login/login');
        }
    }


}
