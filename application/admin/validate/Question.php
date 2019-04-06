<?php

namespace app\admin\validate;


use think\Validate;

class Question extends Validate
{
    protected $rule = [
//        'title' => 'require',
        'content' => 'require',
        'score' => 'number|between:1,100',
        'type' => 'require',
    ];

    protected $message = [
//        'title.require' => '考核项目名不能为空',
        'content.require' => '考核内容不能为空',
        'score.number' => '考核分只能是数字',
        'score.between' => '考核分必须在1-100之间',
        'type.require' => '考核类型不能为空',
    ];
}