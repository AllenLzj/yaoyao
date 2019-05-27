<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

//指定其他域名访问
header('Access-Control-Allow-Origin:*');

class Invitation extends ApiBase
{


   public function addInvitation(Request $request)
   {
       $prm = $request->only('type,title,info,site,time,user_num,academy_id,identity,name');
       $time_ids = input('time_ids',null);
       $prm['user_id'] = input('user_id');
       $prm['create_time'] = date('Y-m-d H:i:s');
       // 启动事务
       Db::startTrans();
       try {
           $invitation_id = db('invitation')->insertGetId($prm);
           if(!empty($time_ids)){
               db('place_time')->where('id','in',$time_ids)->update(['status'=>1,'invitation_id'=>$invitation_id]);
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

    public function getAcademy()
    {
        $data = db('academy')->column('id,name');
        return json_encode($this->mergeData($data));

    }

    public function delInvitation(Request $request)
    {
        $prm = $request->only('user_id,invitation_id');
        // 启动事务
        Db::startTrans();
        try {
            db('invitation')->where(['user_id'=>$prm['user_id'],'id'=>$prm['invitation_id']])->delete();
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

    //加入邀请
    public function joinInvitation(Request $request)
    {
        $prm = $request->only('user_id,invitation_id');
        $is_join = db('user_invitation')->where(['user_id'=>$prm['user_id'],'invitation_id'=>$prm['invitation_id']])->count();
        if($is_join) $this->wrong(601, '已参与，无需重复参与');
        $data = db('invitation')->where('id',$prm['invitation_id'])->find();
        if($data['sign_up_num'] >= $data['user_num']) $this->wrong(603, '人数已满');
        if($data['user_id'] == $data['user_id']) $this->wrong(603, '请勿加入本人发布的邀请');

// 启动事务
        Db::startTrans();
        try {
            db('invitation')->where('id',$prm['invitation_id'])->setInc('sign_up_num');
            db('user_invitation')->insert(['user_id'=>$prm['user_id'],'invitation_id'=>$prm['invitation_id']]);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

    //退出邀请
    public function exitInvitation(Request $request)
    {
        $prm = $request->only('user_id,invitation_id');
        $is_join = db('user_invitation')->where(['user_id'=>$prm['user_id'],'invitation_id'=>$prm['invitation_id']])->count();
        if(empty($is_join)) $this->wrong(601, '您未参与改邀请');
// 启动事务
        Db::startTrans();
        try {
            db('invitation')->where('id',$prm['invitation_id'])->setDec('sign_up_num');
            db('user_invitation')->where(['user_id'=>$prm['user_id'],'invitation_id'=>$prm['invitation_id']])->delete();
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

    //主页邀请列表
    public function invitationList(Request $request)
    {
        $type = input('type',1);
        $search = input('search',null);
        $where = ['i.type'=>$type];
        if(!empty($search)) $where['i.title|i.info'] = ['like', "%{$search}%"];
        $data = db('invitation')->alias('i')
            ->join('academy a','a.id=i.academy_id')
            ->join('user u','u.id=i.user_id')
            ->join('picture p','p.picture_id=u.icon')
            ->where($where)
            ->order('i.id desc')
            ->field('i.*,a.name academy_name,p.path')
            ->select();
        foreach ($data as &$vo){
            $vo['num_data'] = $vo['sign_up_num'].'/'.$vo['user_num'];
            $vo['identity'] = $vo['identity'] == 2?'教师':'学生';
        }
        return json_encode($this->mergeData($data));

    }

    //我的邀请
    public function myInvitation(Request $request)
    {
        $user_id = input('user_id');
        $data = db('user_invitation')->alias('ui')
            ->join('invitation i','i.id=ui.invitation_id')
            ->join('academy a','a.id=i.academy_id')
            ->join('user u','u.id=i.user_id')
            ->join('picture p','p.picture_id=u.icon')
            ->where(['ui.user_id'=>$user_id])
            ->order('id desc')
            ->field('i.*,a.name academy_name,p.path')
            ->select();
        foreach ($data as &$vo){
            $vo['num_data'] = $vo['sign_up_num'].'/'.$vo['user_num'];
            $vo['identity'] = $vo['identity'] == 2?'教师':'学生';
        }
        return json_encode($this->mergeData($data));

    }

    //我发布的邀请
    public function myPushInvitation($user_id)
    {
        $user_id = input('user_id');
        $data = db('invitation')->alias('i')
            ->join('academy a','a.id=i.academy_id')
            ->join('user u','u.id=i.user_id')
            ->join('picture p','p.picture_id=u.icon')
            ->where(['i.user_id'=>$user_id])
            ->order('id desc')
            ->field('i.*,a.name academy_name,p.path')
            ->select();
        foreach ($data as &$vo){
            $vo['num_data'] = $vo['sign_up_num'].'/'.$vo['user_num'];
            $vo['identity'] = $vo['identity'] == 2?'教师':'学生';
        }
        return json_encode($this->mergeData($data));
    }
}
