<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\admin\controller;

/**
 * 后台首页控制器
 * @author Allen.liu
 */
class Index extends Admin
{
    public function index()
    {
        $this->assign('title', '首页');
        return $this->fetch();
    }
}
