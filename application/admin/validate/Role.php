<?php
namespace app\admin\validate;


use think\Validate;

class Role extends Validate
{
    protected $rule = [
        'description' => 'require',
        'name' => 'require',
    ];

    protected $message = [
        'description.require' => '角色描述不能为空',
        'name.require' => '角色名不能为空',
    ];
}