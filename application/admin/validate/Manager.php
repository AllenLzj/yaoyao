<?php

namespace app\admin\validate;


use think\Validate;

class Manager extends Validate
{
    protected $rule = [
        'phone' => 'require|checkPhone|unique:manager',
        'name' => 'require',
        'password' => 'require|confirm|min:6'
    ];

    protected $message = [
        'phone.require' => '手机号码格式不能为空',
        'phone.unique' => '手机号码已经存在',
        'name.require' => '管理员姓名不能为空',
        'password.require' => '密码不能为空',
        'password.confirm' => '两次密码输入不一致',
        'password.min' => '密码长度至少6位',
    ];

    protected $scene = [
        'edit' => ['name', 'phone'],
    ];

    protected function checkPhone($value)
    {
        $phoneMatch = "/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/";
        if (!preg_match($phoneMatch, $value)) {
            return '手机号格式错误';
        }else{
            return true;
        }
    }

}