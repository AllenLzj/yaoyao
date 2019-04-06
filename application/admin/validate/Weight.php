<?php
namespace app\admin\validate;


use think\Validate;

class Weight extends Validate
{
    protected $rule = [
        'staff_type_id' => 'require',
        'evaluation_subject' => 'require',
        'score' => 'number|between:1,100',
        'type' => 'require',
    ];

    protected $message = [
        'staff_type_id.require' => '考核对象不能为空',
        'evaluation_subject.require' => '评价主体不能为空',
        'score.number' => '考核分只能是数字',
        'score.between' => '权重必须在1-100之间',
        'type.require' => '考核类型不能为空',
    ];
}