<?php
namespace app\admin\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'name' => 'require|unique:user',
        'phone' => 'require|isMobile|unique:user',
        'email' => 'require|email|unique:user',
        'password' => 'require',

    ];

    protected $message = [
        'phone.require' => '手机号不能为空',
        'phone.isMobile' => '手机号格式错误',
        'phone.unique' => '手机号以经存在',
        'name.require' => '用户名不能为空',
        'name.unique' => '用户名已经存在',
        'password.require' => '密码不能为空',
        'email.require' => '邮箱不能为空',
        'email.email' => '邮箱格式不正确',
        'email.unique' => '邮箱已经存在',

    ];

    protected function isMobile($value)
    {
        $rule = '/^0?(13|14|15|17|18)[0-9]{9}$/';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}