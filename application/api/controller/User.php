<?php

namespace app\api\controller;

use app\api\model\Users;
use think\Controller;
use think\Request;
use think\Db;

//指定其他域名访问
header('Access-Control-Allow-Origin:*');

class User extends ApiBase
{

    //获取个人资料
    public function getPersonalData($user_id)
    {
        $data = db('user')->where('id',$user_id)->find();
        $data['icon_path'] = db('picture')->where('picture_id',$data['icon'])->value('path');
//        $data['academy'] = db('academy')->where('id',$data['academy_id'])->value('name');
        return json_encode($this->mergeData($data));
    }

   //编辑头像
    public function avatarEdit(Request $request)
    {
        $data = $request->only('user_id');
        $avatar = request()->file('avatar');
        $info = $avatar->move(PUBLIC_PATH . DS . 'uploads');
        if ($info) {
            $return['path'] = '/uploads/' . $info->getSaveName();
            // 启动事务
            Db::startTrans();
            try {
                //获取picture_id
                $picture_id = db('picture')->insertGetId(['path' => $return['path']]);
                //更新教练头像
                db('user')->where('id',$data['user_id'])->update(['icon' => $picture_id]);
                // 提交事务
                Db::commit();
                return json_encode($this->mergeData($return));
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $this->wrong('401', $e->getMessage());
            }
        } else {
            // 上传失败获取错误信息
            $this->wrong('401', $avatar->getError());
        }
    }


    //修改资料
    public function save(Request $request){
        $data = $request->only('name,sex,id');
        // 启动事务
        Db::startTrans();
        try {
            db('User')->where('id',$data['id'])->update($data);
            db('invitation')->where('user_id',$data['id'])->update(['name'=>$data['name']]);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong('401', $e->getMessage());
        }
    }

    //认证
    public function authentication($user_id){
        // 启动事务
        Db::startTrans();
        try {
            db('User')->where('id',$user_id)->update(['is_authentication'=>1]);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong('401', $e->getMessage());
        }
    }

    public function friendList($user_id)
    {
        $where['user_id|friend_user_id'] = $user_id;
        $data = db('friend_user')->alias('fu')
            ->where($where)
            ->field('user_id,friend_user_id')
            ->select();
        foreach ($data as &$vo){
            $where_a = [];
            if($vo['user_id'] == $user_id){
                $where_a['u.id'] = $vo['friend_user_id'];
            }else{
                $where_a['u.id'] = $vo['user_id'];
            }
            $data_u = db('user')->alias('u')
            ->join('picture p','p.picture_id=u.icon')
                ->where($where_a)
                ->field('u.id,u.name,u.sex,p.path')
                ->find();
            $vo['name'] = $data_u['name'];
            $vo['sex'] = $data_u['sex'];
            $vo['path'] = $data_u['path'];
        }

        return json_encode($this->mergeData($data));
    }

    public function addFriend($user_id,$friend_user_id)
    {
        $is_friend = db('friend_user')->where(['user_id'=>$user_id,'friend_user_id'=>$friend_user_id])->find();
        $is_friend1 = db('friend_user')->where(['user_id'=>$friend_user_id,'friend_user_id'=>$user_id])->find();
        $friend_user_name = db('user')->where('id',$user_id)->value('name');
        if($is_friend || $is_friend1) $this->wrong('401', '添加好友失败，'.$friend_user_name.'和您已经是好友关系');
        if($user_id == $friend_user_id) $this->wrong('401', '添加好友失败');
        // 启动事务
        Db::startTrans();
        try {
            db('friend_user')->insert(['user_id'=>$user_id,'friend_user_id'=>$friend_user_id,'create_time'=>date('Y-m-d H:i:s')]);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong('401', $e->getMessage());
        }
    }

    public function delFriend($user_id,$friend_user_id)
    {
        $is_friend = db('friend_user')->where(['user_id'=>$user_id,'friend_user_id'=>$friend_user_id])->find();
        $is_friend1 = db('friend_user')->where(['user_id'=>$friend_user_id,'friend_user_id'=>$user_id])->find();
        $friend_user_name = db('user')->where('id',$user_id)->value('name');
        if(empty($is_friend) && empty($is_friend1)) $this->wrong('401', '删除好友失败，'.$friend_user_name.'和您不是好友关系');
        // 启动事务
        Db::startTrans();
        try {
            db('friend_user')->where(['user_id'=>$user_id,'friend_user_id'=>$friend_user_id])->delete();
            db('friend_user')->where(['friend_user_id'=>$user_id,'user_id'=>$friend_user_id])->delete();
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong('401', $e->getMessage());
        }
    }

    public function isFriend($user_id,$friend_user_id)
    {
        $data = db('friend_user')->where(['user_id' => $user_id, 'friend_user_id' => $friend_user_id])->find();
        $data1 = db('friend_user')->where(['user_id' => $friend_user_id, 'friend_user_id' => $user_id])->find();
        $is_friend['is_friend'] = 0;
        if ($data || $data1) $is_friend['is_friend'] = 1;
        return json_encode($this->mergeData($is_friend));

        // 启动事务
        Db::startTrans();
        try {
            db('friend_user')->insert(['user_id' => $user_id, 'friend_user_id' => $friend_user_id, 'create_time' => date('Y-m-d H:i:s')]);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong('401', $e->getMessage());
        }
    }
}
