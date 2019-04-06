<?php

namespace app\admin\model;

use think\Model;

class Department extends Model
{
    //部门类型
    const DEPARTMENT_TYPE_ONESELF = 1;//集团部门
    const DEPARTMENT_TYPE_SON = 2;//子公司
    public function getTypeAttr($value)
    {
        $status = [1=>'部门',2=>'子公司'];
        return $status[$value];
    }
}
