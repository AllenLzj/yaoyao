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
class Article extends Admin
{
    public function index()
    {
        $title = input('title');
        $where = [];
        if($title){
            $where['a.title|u.name'] = array('like', "%{$title}%");
        }

        $page_num = request()->param('page_num', 10);
        $data = db('article')->alias('a')
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
            $vo['content_num'] = db('article_comment')->where('article_id',$vo['id'])->count();
        }
        $this->assign('title', '论坛列表');
        return $this->fetch('', compact('list', 'title','page'));
    }

    //删除
    public function delete()
    {
        $prm = input('');
        $ids = $prm['id'];
        $res = db('article')->where('id','in',$ids)->delete();
        if($res){
            return ['status'=>1,'info'=>'删除成功！'];
        }else{
            return ['status'=>0,'info'=>'删除失败！'];

        }
    }


}
