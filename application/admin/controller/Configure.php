<?php
/**
 * Created by PhpStorm.
 * User: VULCAN
 * Date: 2017/12/11
 * Time: 11:08
 */


namespace app\admin\controller;

use app\admin\model\Evaluation;
use think\Config;
use think\Db;
use think\Request;
use app\admin\model\Question;
use app\admin\controller\Excel;

class Configure extends Admin
{


    public function phone()
    {

        $list = db('config') -> where('name','phone') -> column('extra');
        $data = '';
        if(!empty($list))$data = json_decode($list[0],true);
        $title = '短信通道配置';
        return $this->fetch('', compact('title','data'));
    }



    public  function  doit()
    {
        $post = input('');
        if($post['type'] == 'wecha'){
            if(empty($post['appid']) || empty($post['appsecret'])){
                return ['status'=>0,'info'=>'输入内容不能为空'];
            }
            $extra['appid'] = $post['appid'];
            $extra['secret'] = $post['appsecret'];
            $extra = json_encode($extra);
            $status = db('config') -> where('name','wecha') -> find();
            if(empty($status)){
                $info = db('config') -> insert(['name'=>'wecha','extra'=>$extra,'create_time'=>time(),'title'=>'公众号绑定配置']);
            }else{
                $info = db('config') -> where('name','wecha') -> update(['extra'=>$extra,'update_time'=>time()]);
            }
        } elseif ($post['type'] == 'phone'){
            if(empty($post['phone']) || empty($post['key'])){
                return ['status'=>0,'info'=>'输入内容不能为空'];
            }
            $extra['phone'] = $post['phone'];
            $extra['key'] = $post['key'];
            $extra = json_encode($extra);
            $status = db('config') -> where('name','phone') -> find();
            if(empty($status)){
                $info = db('config') -> insert(['name'=>'phone','extra'=>$extra,'create_time'=>time(),'title'=>'短信通道配置']);
            }else{
                $info = db('config') -> where('name','phone') -> update(['extra'=>$extra,'update_time'=>time()]);
            }
        } else{
            $extra['remind'] = $post['example'];
            $extra = json_encode($extra);
            $status = db('config') -> where('name','remind') -> find();
            if(empty($status)){
                $info = db('config') -> insert(['name'=>'remind','extra'=>$extra,'create_time'=>time(),'title'=>'考核提醒消息设置']);
            }else{
                $info = db('config') -> where('name','remind') -> update(['extra'=>$extra,'update_time'=>time()]);
            }
        }
        if($info){
                return ['status'=>1,'info'=>'数据保存成功'];
            }else{
                return ['status'=>0,'info'=>'网络错误'];
            }   




    }







}
