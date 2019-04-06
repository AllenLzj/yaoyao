<?php

namespace app\admin\validate;


use think\Validate;

class Department extends Validate
{
    protected $rule = [
        'department_name' => 'require|unique:department',
    ];

    protected $message = [
        'department_name.require' => '部门或子公司名称不能为空',
        'department_name.unique' => '部门或子公司名称已经存在',
    ];
}