<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Menu extends Model
{
//    use SoftDelete;
//    protected $deleteTime = 'delete_time';
    public function setUrlAttr($value)
    {
        return strtolower($value);
    }
}
