<?php

namespace app\admin\validate;


use think\Validate;

class Staff extends Validate
{
    protected $rule = [
        'name' => 'require',
        'phone' => 'require|checkPhone',
        'staff_type_id' => 'require',
        'staff_rank' => 'require',
        'staff_position' => 'require',
        'department_id' => 'require',
        'gender' => 'require',
    ];

    protected $message = [
        'name.require' => '员工名不能为空',
        'phone.require' => '手机不能为空',
        'staff_type_id.require' => '类型不能为空',
        'staff_rank.require' => '职级不能为空',
        'staff_position.require' => '职称不能为空',
        'department_id.require' => '部门不能为空',
        'gender.require' => '请选择性别',
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