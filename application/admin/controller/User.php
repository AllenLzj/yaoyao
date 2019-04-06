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
use think\Request;
use think\Db;
/**
 * 后台首页控制器
 * @author Allen.liu
 */
class User extends Admin
{
    public function index()
    {
        $title = input('title');
        $where = [];
        if($title){
            $where['name'] = array('like', "%{$title}%");
        }
        $page_num = request()->param('page_num', 10);
        $data = db('user')
            ->where($where)
            ->paginate($page_num, false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $list = $data->toArray();
        $page = $data->render();
        foreach ($list['data'] as &$vo){
            $vo['sex_text'] = '';
            if(!empty($vo['sex'])) $vo['sex_text'] = $vo['sex'] == 1?'男':'女';
        }
        $this->assign('title', '用户列表');
        return $this->fetch('', compact('list', 'title','page'));
    }

    //禁用账号
    public function disabled()
    {
        $user_id = input('id');
        $status = db('user')->where('id',$user_id)->find();
        if(empty($status)) return ['status'=>0,'info'=>'用户不存在！'];
        $status = $status['status']==1?0:1;
        $res = db('user')->where('id',$user_id)->update(['status'=>$status]);
        if($res){
            return ['status'=>1,'info'=>'操作成功！'];
        }else{
            return ['status'=>0,'info'=>'操作失败！'];

        }
    }
}
