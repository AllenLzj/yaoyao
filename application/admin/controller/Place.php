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
class Place extends Admin
{
    public function index()
    {
        $type = input('type');
        $where = [];
        if ($type) {
            $where['type'] = $type;
        }
        $name = input('title');
        if($name){
            $where['name'] = array('like', "%{$name}%");
        }
        $page_num = request()->param('page_num', 10);
        $data = db('place')
            ->where($where)
            ->order('create_time desc')
            ->paginate($page_num, false, [
                'query' => Request::instance()->param(),//不丢失已存在的url参数
            ]);
        $list = $data->toArray();
        $page = $data->render();
        foreach ($list['data'] as &$vo) {
            if ($vo['type'] == 1) {
                $vo['type'] = '自习室';
            } elseif ($vo['type'] == 2) {
                $vo['type'] = '运动场馆';
            } else {
                $vo['type'] = '会议室';
            }
        }
        $this->assign('title', '场地资源列表');
        return $this->fetch('', compact('list', 'title', 'page'));
    }


    //新增
    public function create()
    {
        $this->assign('title', '新增场地');
        return $this->fetch('', compact('title'));
    }

    //新增
    public function save()
    {
        $prm = input('');
        $now_date = date('Y-m-d H:i:s');
        $data = [
            'name' => $prm['name'],
            'info' => $prm['info'],
            'type' => $prm['type'],
            'create_time' => $now_date
        ];
        //获取当年所有日期
        $now_date = strtotime("2019-12-31");
        $d = time();
        $date = [];
        while ($d < $now_date) {
            $d += 86400;
            $date[] = date('Y-m-d', $d);
        }
        $place_time_data = [];
        // 启动事务
        Db::startTrans();
        try {
            $place_id = db('place')->insert($data);
            foreach ($date as $vo) {
                for ($i = 1; $i <= 12; $i++) {
                    $place_time_data[] = [
                        'date' => $vo,
                        'time' => $i,
                        'place_id' => $place_id,
                        'create_time' => $now_date
                    ];
                }
            }
            db('place_time')->insertAll($place_time_data);
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
        $data = db('Announcement')->where('id', $id)->find();
        $this->assign('title', '编辑公告');
        return $this->fetch('', compact('title', 'data'));
    }

    public function update()
    {
        $prm = input('');
        $data = [
            'title' => $prm['title'],
            'info' => $prm['info'],
        ];
        // 启动事务
        Db::startTrans();
        try {
            db('Announcement')->where('id', $prm['id'])->update($data);
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

    public function delete($id = null)
    {
        if (empty($id)) {
            $return_data = array('info' => '请选择要操作的数据！', 'status' => 0);
            return $return_data;
        }
        $id = array_unique((array)$id);
        // 启动事务
        Db::startTrans();
        try {
            db('Announcement')->where('id', 'in', $id)->delete();
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
