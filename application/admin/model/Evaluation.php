<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2017/11/29
 * Time: 14:34
 */

namespace app\admin\model;

use think\Model;
use think\Request;

class Evaluation extends Model
{
    //季度标识
    const QUARTER_ONE = 1;//第一季度
    const QUARTER_TWO = 2;//第二季度
    const QUARTER_THREE = 3;//第三季度
    const QUARTER_FOUR = 4;//第四季度

    //考核状态
    const ACCESS_STATUS_WAIT = 1;//待确认
    const ACCESS_STATUS_DOING = 2;//考核中
    const ACCESS_STATUS_FINISH = 3;//考核结束
    const ACCESS_STATUS_SELF = 4;//自评中

    //类型(只有部分引用，有些在代码写死了)
    const ACCESS_TYPE_QUARTER = 1;//季度
    const ACCESS_TYPE_ONESELF = 2;//年度本级
    const ACCESS_TYPE_SON = 3;//年度子公司
    const ACCESS_TYPE_SON_PEOPLE = 4;//年度子公司成员

    //获取考核记录
    public static function getQuarterLog($where, $page_num)
    {
        $data = db('evaluation')->group('year,season')
            ->where($where)
            ->order('year desc,season desc')
            ->paginate($page_num, false, [
                'query' => Request::instance()->param(),
    ]);
        return $data;
    }

}