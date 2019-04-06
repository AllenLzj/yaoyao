<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

/**
 * 用户组模型类
 * Class AuthGroupModel
 * @author Allen.liu
 */
class AdminGroup extends Model
{
    const TYPE_ADMIN = 1;                   // 管理员用户组类型标识
    const MEMBER = 'member';
    const UCENTER_MEMBER = 'admin';
    const AUTH_GROUP_ACCESS = 'admin_rule'; // 权限表表名
    const AUTH_GROUP = 'admin_group';        // 用户组表名


    /**
     * 把用户添加到用户组,支持批量添加用户到用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     *
     * 示例: 把uid=1的用户添加到group_id为1,2的组 `AuthGroupModel->addToGroup(1,'1,2');`
     */
    public function addToGroup($uid, $gid)
    {
        $uid = is_array($uid) ? implode(',', $uid) : trim($uid, ',');
        $gid = is_array($gid) ? $gid : explode(',', trim($gid, ','));

        $Access = M(self::AUTH_GROUP_ACCESS);
        if (isset($_REQUEST['batch'])) {
            //为单个用户批量添加用户组时,先删除旧数据
            $del = $Access->where(array('admin_id' => array('in', $uid)))->delete();
        }

        $uid_arr = explode(',', $uid);
        $uid_arr = array_diff($uid_arr, array(C('USER_ADMINISTRATOR')));
        $add = array();
        if ($del !== false) {
            foreach ($uid_arr as $u) {
                //判断用户id是否合法
                if (D('Admin')->where(array('admin_id' => (int)$u))->getField('admin_id') == false) {
                    $this->error = "编号为{$u}的用户不存在！";
                    return false;
                }
                foreach ($gid as $g) {
                    if (is_numeric($u) && is_numeric($g)) {
                        $add[] = array('admin_group_id' => $g, 'admin_id' => $u);
                    }
                }
            }
            $Access->addAll($add);
        }
        if ($Access->getDbError()) {
            if (count($uid_arr) == 1 && count($gid) == 1) {
                //单个添加时定制错误提示
                $this->error = "不能重复添加";
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * 返回用户所属用户组信息
     * @param  int $uid 用户id
     * @return array  用户所属的用户组 array(
     * array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     * ...)
     */
    static public function getUserGroup($uid)
    {
        static $groups = array();

        $prefix = config("database.prefix");
        $user_groups = M()
            ->field('admin_id,g.admin_group_id,admin_group_name,admin_group_description,admin_rules')
            ->table($prefix . self::AUTH_GROUP_ACCESS . ' a')
            ->join($prefix . self::AUTH_GROUP . " g on a.admin_group_id=g.admin_group_id")
            ->where("a.admin_id='$uid' and g.admin_group_status='1'")
            ->select();
        $groups[$uid] = $user_groups ? $user_groups : array();
        return $groups[$uid];
    }

    /**
     * 返回用户拥有管理权限的分类id列表
     *
     * @param int $uid 用户id
     * @return array
     *
     *  array(2,4,8,13)
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function getAuthCategories($uid)
    {
        return self::getAuthExtend($uid, self::AUTH_EXTEND_CATEGORY_TYPE, 'AUTH_CATEGORY');
    }


    /**
     * 获取用户组授权的扩展信息数据
     *
     * @param int $gid 用户组id
     * @return array
     *
     *  array(2,4,8,13)
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    static public function getExtendOfGroup($gid, $type)
    {
        if (!is_numeric($type)) {
            return false;
        }
        return M(self::AUTH_EXTEND)->where(array('group_id' => $gid, 'type' => $type))->getfield('extend_id', true);
    }

    /**
     * 获取用户组授权的分类id列表
     *
     * @param int $gid 用户组id
     * @return array
     *
     *  array(2,4,8,13)
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function getCategoryOfGroup($gid)
    {
        return self::getExtendOfGroup($gid, self::AUTH_EXTEND_CATEGORY_TYPE);
    }


    /**
     * 批量设置用户组可管理的扩展权限数据
     *
     * @param int|string|array $gid 用户组id
     * @param int|string|array $cid 分类id
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    static public function addToExtend($gid, $cid, $type)
    {
        $gid = is_array($gid) ? implode(',', $gid) : trim($gid, ',');
        $cid = is_array($cid) ? $cid : explode(',', trim($cid, ','));

        $Access = M(self::AUTH_EXTEND);
        $del = $Access->where(array('group_id' => array('in', $gid), 'type' => $type))->delete();

        $gid = explode(',', $gid);
        $add = array();
        if ($del !== false) {
            foreach ($gid as $g) {
                foreach ($cid as $c) {
                    if (is_numeric($g) && is_numeric($c)) {
                        $add[] = array('group_id' => $g, 'extend_id' => $c, 'type' => $type);
                    }
                }
            }
            $Access->addAll($add);
        }
        if ($Access->getDbError()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 批量设置用户组可管理的分类
     *
     * @param int|string|array $gid 用户组id
     * @param int|string|array $cid 分类id
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function addToCategory($gid, $cid)
    {
        return self::addToExtend($gid, $cid, self::AUTH_EXTEND_CATEGORY_TYPE);
    }


    /**
     * 将用户从用户组中移除
     * @param int|string|array $gid 用户组id
     * @param int|string|array $cid 分类id
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    public function removeFromGroup($uid, $gid)
    {
        return M(self::AUTH_GROUP_ACCESS)->where(array('admin_id' => $uid, 'admin_group_id' => $gid))->delete();
    }

    /**
     * 获取某个用户组的用户列表
     *
     * @param int $group_id 用户组id
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function memberInGroup($group_id)
    {
        $prefix = C('DB_PREFIX');
        $l_table = $prefix . self::MEMBER;
        $r_table = $prefix . self::AUTH_GROUP_ACCESS;
        $r_table2 = $prefix . self::UCENTER_MEMBER;
        $list = M()->field('m.admin_id,u.username,m.last_login_time,m.last_login_ip,m.admin_status')
            ->table($l_table . ' m')
            ->join($r_table . ' a ON m.admin_id=a.admin_id')
            ->join($r_table2 . ' u ON m.admin_id=u.admin_id')
            ->where(array('a.admin_group_id' => $group_id))
            ->select();
        return $list;
    }

    /**
     * 检查id是否全部存在
     * @param array|string $gid 用户组id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkId($modelname, $mid, $msg = '以下id不存在:')
    {
        if (is_array($mid)) {
            $count = count($mid);
            $ids = implode(',', $mid);
        } else {
            $mid = explode(',', $mid);
            $count = count($mid);
            $ids = $mid;
        }

        $s = M($modelname)->where(array('admin_group_id' => array('IN', $ids)))->getField('admin_group_id', true);
        if (count($s) === $count) {
            return true;
        } else {
            $diff = implode(',', array_diff($mid, $s));
            $this->error = $msg . $diff;
            return false;
        }
    }

    /**
     * 检查用户组是否全部存在
     * @param array|string $gid 用户组id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkGroupId($gid)
    {
        return $this->checkId('AdminGroup', $gid, '以下用户组id不存在:');
    }

}

