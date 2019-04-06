<?php

namespace app\admin\model;

use think\Model;

class StaffDeputy extends Model
{
    const HIGH_RISE = 3;//职级高层
    /**
     * @param $value
     * @return mixed
     * User: Allen.liu
     * staff_rank获取器
     */
    public function getStaffRankAttr($value)
    {
        $status = [1 => '员工', 2 => '中层', 3 => '高层'];
        return $status[$value];
    }

    /**
     * @param $value
     * @return mixed
     * User: Allen.liu
     * staff_rank获取器
     */
    public function getGenderAttr($value)
    {
        $status = [1 => '男', 2 => '女'];
        return $status[$value];
    }


}
