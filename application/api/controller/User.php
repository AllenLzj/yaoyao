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
