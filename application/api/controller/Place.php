<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

//指定其他域名访问
header('Access-Control-Allow-Origin:*');

class Place extends ApiBase
{

    public function index()
    {
        $date = input('date',date('Y-m-d'));
        $type = input('type',1);
        $place_data = db('place')->alias('p')
            ->join('place_time pt','p.id=pt.place_id')
            ->where(['pt.date'=>$date,'p.type'=>$type])
            ->group('pt.place_id')
            ->field('p.id,p.name,group_concat(time,":",status) times')
            ->select();
        foreach ($place_data as $k => &$vo){
            $times = explode(',',$vo['times']);
            $vo['kx'] = '';
            $vo['zy'] = '';
            foreach ($times as $ti){
                $time = explode(':',$ti);
                if($time[1] == 0){
                    //空闲
                    if(empty($vo['kx'])){
                        $vo['kx'] = $time[0];
                    }else{
                        $vo['kx'] .= ','.$time[0];
                    }
                }else{
                    //占用
                    if(empty($vo['zy'])){
                        $vo['zy'] = $time[0];
                    }else{
                        $vo['zy'] .= ','.$time[0];
                    }
                }

            }
            $vo['kx'] = empty($vo['kx'])?'无':$vo['kx'].'节';
            $vo['zy'] = empty($vo['zy'])?'无':$vo['zy'].'节';
            unset($place_data[$k]['times']);

        }
        return json_encode($this->mergeData($place_data));
    }

    //选择场地
    public function details()
    {
        $id = input('id');
        $user_id = input('user_id');
        $date = input('date',date('Y-m-d'));
        $data = db('place')->where('id',$id)->field('id,name,type')->find();
        $data['time'] = db('place_time')->where(['place_id'=>$id,'date'=>$date,'status'=>0])->column('id,time');
        $data['user_name'] = db('user')->where('id',$user_id)->value('name');
        if($data['type'] == 1){
            $data['type_text'] = '自习室';
        }elseif ($data['type'] == 2){
            $data['type_text'] = '运动场馆';
        }else{
            $data['type_text'] = '会议室';
        }
        return json_encode($this->mergeData($data));
    }

    public function save()
    {
        $id = input('id');
        $times = input('time');
        $date = input('date',date('Y-m-d'));
        $data = db('place')->where('id',$id)->field('id,name,type')->find();
        $data['time_ids'] = db('place_time')->where(['place_id'=>$id,'date'=>$date,'time'=>['in',$times]])->column('id');
        $data['time_ids'] = implode(',',$data['time_ids']);
        $data['time_text'] = $date.'第'.$times.'节课';
        return json_encode($this->mergeData($data));

    }

}
