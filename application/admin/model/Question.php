<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2017/11/29
 * Time: 14:34
 */

namespace app\admin\model;

use think\Model;

class Question extends Model
{
    //类型
    const QUESTION_QUARTER = 1;//季度
    const QUESTION_TYPE_ONESELF = 2;//年度本级
    const QUESTION_TYPE_SON = 3;//年度子公司
    const QUESTION_TYPE_SON_PEOPLE = 4;//年度子公司成员
    //

}