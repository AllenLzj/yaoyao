<?php

namespace app\admin\model;

use think\Model;

class staffType extends Model
{
    /**
     * @param $value
     * @return mixed
     * User: Allen.liu
     * staff_rank获取器
     */
    public function getStaffRankAttr($value)
    {
        $status = [1 => '员工', 2 => '中层', 3 => '高层','4'=>'其他'];
        return $status[$value];
    }
}
