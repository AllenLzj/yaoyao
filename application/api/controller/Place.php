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

    //点赞
    public function like($user_id, $article_id)
    {

        $like = db('article_like')->where(['article_id'=>$article_id,'user_id'=>$user_id])->find();
        // 启动事务
        Db::startTrans();
        try {
            if(empty($like)){
                db('article_like')->insert(['article_id'=>$article_id,'user_id'=>$user_id,'create_time'=>date('Y-m-d H:i:s')]);
                db('article')->where('id',$article_id)->setInc('like_num',1);
            }else{
                db('article_like')->where(['article_id'=>$article_id,'user_id'=>$user_id])->delete();
                db('article')->where('id',$article_id)->setDec('like_num',1);
            }
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }


}
