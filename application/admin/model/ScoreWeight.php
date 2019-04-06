<?php

namespace app\admin\model;

use think\Model;

class ScoreWeight extends Model
{
    public function getTypeTextAttr($value,$data){
        $status = [1=>'季度岗位职责履行考核',2=>'年度民主测评考核表',3=>'所属企业年度年度民主测评',4=>'所属企业经营班子成员年度民主测评'];
        return $status[$data['type']];
    }
}
