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
        if ($name) {
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
        if (empty($prm['name'])) return ['status' => 0, 'info' => '场地名称不能为空'];
        if (empty($prm['type'])) return ['status' => 0, 'info' => '场地类型不能为空'];
        $now_date = date('Y-m-d H:i:s');
        $data = [
            'name' => $prm['name'],
            'info' => $prm['info'],
            'type' => $prm['type'],
            'create_time' => $now_date
        ];
        foreach ($prm['times'] as $w => $ti) {
            $time_str = implode(',', $ti);
            switch ($w) {
                case 1:
                    $data['monday'] = $time_str;
                    break;
                case 2:
                    $data['tuesday'] = $time_str;
                    break;
                case 3:
                    $data['wednesday'] = $time_str;
                    break;
                case 4:
                    $data['thursday'] = $time_str;
                    break;
                case 5:
                    $data['friday'] = $time_str;
                    break;
                case 6:
                    $data['saturday'] = $time_str;
                    break;
                case 0:
                    $data['sunday'] = $time_str;
                    break;
            }
        }
        //获取当年所有日期
        $end_date = strtotime("2019-12-31");
        $d = time();
        $date = [];
        while ($d < $end_date) {
            $d += 86400;
            $date[] = date('Y-m-d', $d);
        }
        $place_time_data = [];
        // 启动事务
        Db::startTrans();
        try {
            $place_id = db('place')->insertGetId($data);
            foreach ($date as $vo) {
                $w = date('w', strtotime($vo));
                if (isset($prm['times'][$w])) {
                    $time_arr = $prm['times'][$w];
                    foreach ($time_arr as $i) {
                        $place_time_data[] = [
                            'date' => $vo,
                            'week' => $w,
                            'time' => $i,
                            'place_id' => $place_id,
                            'create_time' => $now_date
                        ];
                    }
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

    //新增
    public function details()
    {
        $prm_date = input('date', null);
        $date = date('Y-m-d');
        if (!empty($prm_date)) $date = $prm_date;
        $week = date('w',strtotime($date));
        switch ($week) {
            case 1:
                $week_text = '周一';
                break;
            case 2:
                $week_text = '周二';
                break;
            case 3:
                $week_text = '周三';
                break;
            case 4:
                $week_text = '周四';
                break;
            case 5:
                $week_text = '周五';
                break;
            case 6:
                $week_text = '周六';
                break;
            case 0:
                $week_text = '周日';
                break;
        }
        $place_data = db('place_time')->where('date', $date)->column('time,status');
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            if (isset($place_data[$i])) {
                $data[$i] = [
                    'time' => $i,
                    'is_select' => 1,
                    'status' => $place_data[$i],
                ];
            } else {
                $data[$i] = [
                    'time' => $i,
                    'is_select' => 0,
                    'status' => 0,
                ];
            }
        }
        $time_arr = [
            '1'=>'第一节',
            '2'=>'第二节',
            '3'=>'第三节',
            '4'=>'第四节',
            '5'=>'第五节',
            '6'=>'第六节',
            '7'=>'第七节',
            '8'=>'第八节',
            '9'=>'第九节',
            '10'=>'第十节',
            '11'=>'第十一节',
            '12'=>'第十二节',
        ];
        $this->assign('title', '场地资源详情');
        return $this->fetch('', compact('title', 'data','week_text','time_arr','date'));
    }

    //编辑当天空余时间
    public function detailsSave()
    {
        $prm = input('');
        $create_date = date('Y-m-d H:i:s');
        if(empty($prm['date'])) $prm['date'] = date('Y-m-d');
        $week = date('w',strtotime($prm['date']));
        $place_time_data = db('place_time')->where(['date'=>$prm['date'],'place_id'=>$prm['id']])->column('time,id');
        $add_data = [];//待添加数据
        foreach ($prm['times'] as $ti){
            if(!isset($place_time_data[$ti])){
                $add_data[] = [
                    'time'=>$ti,
                    'date'=>$prm['date'],
                    'week'=>$week,
                    'place_id'=>$prm['id'],
                    'create_time'=>$create_date,
                ];
            }
            unset($place_time_data[$ti]);
        }

        // 启动事务
        Db::startTrans();
        try {

            db('place_time')->insertAll($add_data);
            db('place_time')->where('id','in',$place_time_data)->delete();
            // 提交事务
            Db::commit();
            $return_data = array('info' => '保存成功！', 'status' => 1, 'target' => 'back');
            return $return_data;

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $return_data = array('info' => '保存失败！', 'status' => 0);
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
