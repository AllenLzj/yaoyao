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
class Invitation extends Admin
{
    public function index()
    {
        $title = input('title');
        $where = [];
        if($title){
            $where['a.title|u.name'] = array('like', "%{$title}%");
        }

        $page_num = request()->param('page_num', 10);
        $data = db('invitation')->alias('a')
            ->join('user u','u.id=a.user_id')
            ->where($where)
            ->field('a.*,u.name user_name')
            ->order('a.create_time desc')
            ->paginate($page_num, false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $list = $data->toArray();
        $page = $data->render();
        foreach ($list['data'] as &$vo){
            $content = db('user_invitation')->alias('ui')
                ->join('user u','u.id=ui.user_id')
                ->join('picture p','p.picture_id=u.icon')
                ->where('invitation_id',$vo['id'])
                ->field('u.id,u.name,p.path')
                ->select();
            $vo['content_num'] = $content;

        }
//        print_r($list);die;
        $this->assign('title', '文章列表');
        return $this->fetch('', compact('list', 'title','page'));
    }

    //删除
    public function delete()
    {
        $prm = input('');
        $ids = $prm['id'];
        $res = db('invitation')->where('id','in',$ids)->delete();
        if($res){
            return ['status'=>1,'info'=>'删除成功！'];
        }else{
            return ['status'=>0,'info'=>'删除失败！'];

        }
    }


}
