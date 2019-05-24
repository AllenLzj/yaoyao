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
class Announcement extends Admin
{
    public function index()
    {
        $title = input('title');
        $where = [];
        if($title){
            $where['a.title|u.username'] = array('like', "%{$title}%");
        }

        $page_num = request()->param('page_num', 10);
        $data = db('announcement')->alias('a')
            ->join('admin u','u.admin_id=a.admin_id')
            ->where($where)
            ->field('a.*,u.username user_name')
            ->order('a.create_time desc')
            ->paginate($page_num, false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $list = $data->toArray();
        $page = $data->render();
        $this->assign('title', '公告列表');
        return $this->fetch('', compact('list', 'title','page'));
    }


    //新增
    public function create()
    {
        $this->assign('title', '新增公告');
        return $this->fetch('', compact('title'));
    }

    //新增
    public function save()
    {
        $prm = input('');
        $data = [
            'title'=>$prm['title'],
            'info'=>$prm['info'],
            'admin_id'=>session('admin_info.admin_id'),
            'create_time'=>date('Y-m-d H:i:s')
        ];
        // 启动事务
        Db::startTrans();
        try {
            db('Announcement')->insert($data);
            // 提交事务
            Db::commit();
            $return_data = array('info' => '新增成功！', 'status' => 1, 'target' => 'back');
            return $return_data;

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $return_data = array('info' => '新增失败！', 'status' => 0);
            return $return_data;
        }
    }

    public function edit()
    {
        $id = input('id');
        $data = db('Announcement')->where('id',$id)->find();
        $this->assign('title', '编辑公告');
        return $this->fetch('', compact('title','data'));
    }

    public function update()
    {
        $prm = input('');
        $data = [
            'title'=>$prm['title'],
            'info'=>$prm['info'],
        ];
        // 启动事务
        Db::startTrans();
        try {
            db('Announcement')->where('id',$prm['id'])->update($data);
            // 提交事务
            Db::commit();
            $return_data = array('info' => '更新成功！', 'status' => 1, 'target' => 'back');
            return $return_data;

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $return_data = array('info' => '更新失败！', 'status' => 0);
            return $return_data;
        }
    }

    public function delete($id=null)
    {
        if (empty($id)) {
            $return_data = array('info' => '请选择要操作的数据！', 'status' => 0);
            return $return_data;
        }
        $id = array_unique((array)$id);
        // 启动事务
        Db::startTrans();
        try {
            db('Announcement')->where('id','in',$id)->delete();
            // 提交事务
            Db::commit();
            return array('info' => '删除成功！', 'status' => 1);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return array('info' => '删除失败！', 'status' => 0);
        }
    }
}
