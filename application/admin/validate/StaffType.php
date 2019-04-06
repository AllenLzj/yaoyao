<?php
namespace app\admin\validate;


use think\Validate;

class StaffType extends Validate
{
    protected $rule = [
        'staff_type_name' => 'require|unique:staff_type',
    ];

    protected $message = [
        'staff_type_name.require' => '员工类型不能为空',
        'staff_type_name.unique' => '员工类型名已存在',
    ];
}