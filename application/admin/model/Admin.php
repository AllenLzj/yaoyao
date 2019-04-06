<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

/**
 * 管理员模型
 * @author Allen.liu
 */
class Admin extends Model
{

    protected $pk = 'admin_id';

    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid)
    {
        /* 检测是否在当前应用注册 */
        $user = $this->field('*')->where(array('admin_id' => $uid))->find();
        if (!$user || 1 != $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }
        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout()
    {
        cookie(null);
        session(null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user)
    {
        /* 更新登录信息 */
        $data = array(
            'admin_id' => $user['admin_id'],
            'last_login_time' => date('Y-m-d H:i:s'),
            'last_login_ip' => request()->ip(1)
        );
        $this->isUpdate(true)->save($data);

        /* 记录登录SESSION */
        $admin_info = db('admin')
            ->alias('a')
            ->join('manager b', 'a.admin_id=b.admin_id', 'left')
            ->field('b.*,a.username')
            ->where(array('a.admin_id' => $user['admin_id']))
            ->find();
        session('admin_info', $admin_info);
    }
}
