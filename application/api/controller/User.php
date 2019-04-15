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


   //编辑头像
    public function avatarEdit()
    {
        $data = request()->only('id');
        $avatar = request()->file('avatar');
        $info = $avatar->move(PUBLIC_PATH . DS . 'uploads');
        if ($info) {
            $return['path'] = '/uploads/' . $info->getSaveName();
            // 启动事务
            Db::startTrans();
            try {
                //获取picture_id
                $picture_id = Db::name('picture')->insertGetId(array('path' => $return['path']));
                //更新教练头像
                model('User')->save(['avatar' => $picture_id], ['user_id' => $data['id']]);
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
