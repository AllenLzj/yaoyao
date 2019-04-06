<?php
//数据库字段配置
return [
    //仓库状态
    'store_status' => [
        '0'    => '禁用',
        '1'    => '正常',
        '-1'   => '删除'
    ],

    //考核状态
    'review_status' => [
        '1'    => '待确认',
        '2'    => '考核中',
        '3'    => '考核结束',
        '4'    => '自评中',
    ],

    //评价标准
    'evaluate_criterion' => [
        '1'    => [
            'name' => '好',
            'score' => '100'
        ],
        '2'    => [
            'name' => '较好',
            'score' => '80'
        ],
        '3'    => [
            'name' => '一般',
            'score' => '60'
        ],
        '4'    => [
            'name' => '差',
            'score' => '0'
        ],

    ],
];