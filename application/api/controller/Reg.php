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

    /**
     * User: WangMingxue
     * Email cumt_wangmingxue@126.com
     * tp5发送邮件
     */
    public function sendEmail() {
        $email = input('email',null);
        $user = db('user')->where(['email'=>$email])->find();
        if(empty($user)) return ['info'=>'用户不存在！','status'=>0];
        $name='邀伴儿新用户注册';
        $subject='邀伴儿验证码';
        if (check_email($email)) {
            $content_num = rand_num(6);
            $content = '您的邀伴儿验证码为：' . $content_num . ",请在10分钟内完成注册";
            if (send_mail($email,$name,$subject,$content) == true) {
                $redis = new Redis();
                $redis->set($email, $content_num, 'EX', 600);
                return ['info'=>'验证码发送成功！','status'=>1];
            } else {
                return ['info'=>'用户不存在！','status'=>0];
            }
        } else {
            return ['info'=>'邮箱格式错误！','status'=>0];
        }
    }
    public function save(Request $request)
    {
        $data = $request->except(['sign', 'timestamp', 'access_token']);
        $token = input('access_token','');
        $validate_result = $this->validate($data, 'Reg');
        if ($validate_result !== true) {
            $this->wrong(401, $validate_result);
        }
        //验证验证码
        $redis = new Redis();
        if (!$redis->exists($data['phone'])) {
            $this->wrong(601, lang('illegal cell phone number'));
        }
        if ($redis->get($data['phone']) != $data['code']) {
            $this->wrong(602, lang('verification code error'));
        }
        $user_model = new Users();
        // 启动事务
        Db::startTrans();
        try {
            $user = [
                'phone' => $data['phone'],
                'country_code' => $data['country_code'],
                'password' => think_admin_md5($data['password'], UC_AUTH_KEY),
                'last_login_ip' => $request->ip(),
                'last_login_time' => date('Y-m-d H:i:s'),
            ];
            $user_model->save($user);
            $user_id = $user_model->id;
            $student = [
                'user_id' => $user_id,
            ];
            //token 关联user
            db('token')->where(['access_token'=>$token])->update(['user_id'=>$user_id]);
            model('students')->save($student);
            // 提交事务
            Db::commit();
            $redis->del($data['phone']);
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        }


    }

    //短信重置密码
    public function updatePassword(Request $request)
    {
        $data = $request->except(['sign', 'timestamp', 'access_token']);
        $user_info = db('user')->where(['phone'=>$data['phone']])->find();
        if (empty($user_info)) {
            $this->wrong(401, lang('Cell phone Numbers don not exist'));
        }
        //验证验证码
        $redis = new Redis();
        if (!$redis->exists($data['phone'])) {
            $this->wrong(601, lang('illegal cell phone number'));
        }
        if ($redis->get($data['phone']) != $data['code']) {
            $this->wrong(602, lang('verification code error'));
        }
        $password = think_admin_md5($data['password'], UC_AUTH_KEY);
        $res = db('user')->where(['phone'=>$data['phone']])->update(['password'=>$password]);
        if($res){
            return json_encode($this->mergeData());
        }else{
            $this->wrong(603, lang('modify the failure'));

        }
    }

    //邮箱重置密码
    public function emailPassword(Request $request)
    {
        $data = $request->except(['sign', 'timestamp', 'access_token']);
        if(!isset($data['email']) || empty($data['email'])) $this->wrong(401, lang('email error'));
        $user_info = db('user')->where(['email'=>$data['email']])->find();
        if (empty($user_info)) {
            $this->wrong(401, lang('this mailbox is not bound to an account'));
        }
        //验证验证码
        $redis = new Redis();
        if (!$redis->exists($data['email'])) {
            $this->wrong(601, lang('illegal mail'));
        }
        if ($redis->get($data['email']) != $data['code']) {
            $this->wrong(602, lang('verification code error'));
        }
        $password = think_admin_md5($data['password'], UC_AUTH_KEY);
        $res = db('user')->where(['email'=>$data['email']])->update(['password'=>$password]);
        if($res){
            return json_encode($this->mergeData());
        }else{
            $this->wrong(603, lang('modify the failure'));

        }
    }

}
