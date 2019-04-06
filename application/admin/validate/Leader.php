<?php
namespace app\admin\validate;


use think\Validate;

class Leader extends Validate
{
    protected $rule = [
        'staff_id' => 'require',
    ];

    protected $message = [
        'staff_id.require' => '请先选择员工',
    ];
}