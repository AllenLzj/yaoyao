<?php

namespace app\api\controller;

use app\api\model\Users;
use think\Controller;
use think\Request;
use Predis\Client as Redis;
use think\Db;

//指定其他域名访问
header('Access-Control-Allow-Origin:*');

class Reg extends ApiBase
{
    public function sendEmail($email = null) {
        $user = db('user')->where(['email'=>$email])->find();
        if($user) $this->wrong(601, '该邮箱已经注册用户');
        $date = date('Y-m-d H:i:s');
        $expiration_time = date('Y-m-d H:i:s',strtotime('+10 minute'));
        $name='邀伴儿新用户注册';
        $subject='邀伴儿验证码';
        if (check_email($email)) {
            $content_num = rand_num(6);
            $content = '您的邀伴儿验证码为：' . $content_num . ",请在10分钟内完成操作";
            if (send_mail($email,$name,$subject,$content) == true) {
                $email_code = db('email_code')->where('email',$email)->count();
                if($email_code){
                    db('email_code')->where('email',$email)->update(['code'=>$content_num,'expiration_time'=>$expiration_time]);
                }else{
                    db('email_code')->insert(['code'=>$content_num,'email'=>$email,'expiration_time'=>$expiration_time,'create_time'=>$date]);
                }
                $this->wrong(200, '验证码发送成功');
            } else {
                $this->wrong(601, '验证码发送失败');
            }
        } else {
            $this->wrong(601, '邮箱格式错误');
        }
    }

    public function save(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $data = $request->only(['email', 'code']);
        $is_code = db('email_code')->where(['email'=>$data['email'],'expiration_time'=>['egt',$date]])->find();
        $user = db('user')->where('email',$data['email'])->find();
        if(!empty($user)) $this->wrong(601, '注册失败，账号已存在');
        if(empty($is_code) || $data['code'] == $is_code) $this->wrong(601, '验证码错误');
        $user = [
            'email' => $data['email'],
            'password' => think_admin_md5('123456', UC_AUTH_KEY),
            'last_login_ip' => $request->ip(),
            'last_login_time' => $date,
            'name'=>'yao_ban_001'
        ];
        // 启动事务
        Db::startTrans();
        try {
            db('user')->insert($user);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }


    //邮箱重置密码
    public function emailPassword(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $data = $request->only(['email','password','code']);
        if(!isset($data['email']) || empty($data['email'])) $this->wrong(401, '邮箱错误');
        $user_info = db('user')->where(['email'=>$data['email']])->find();
        if (empty($user_info)) {
            $this->wrong(401, '账号不存在');
        }
        $is_code = db('email_code')->where(['email'=>$data['email'],'expiration_time'=>['gt'=>$date]])->value('code');
        if(empty($is_code) || $data['code'] == $is_code) $this->wrong(601, '验证码错误');
        $password = think_admin_md5($data['password'], UC_AUTH_KEY);
        $res = db('user')->where(['email'=>$data['email']])->update(['password'=>$password]);
        if($res){
            return json_encode($this->mergeData());
        }else{
            $this->wrong(603, '重置密码失败');

        }
    }

}
