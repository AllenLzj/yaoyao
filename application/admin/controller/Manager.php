<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

/**
 * Class Manager
 * @package app\admin\controller
 * User: Allen.liu
 */
class Manager extends Admin
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //每页数量
        $page_num = request()->param('page_num', 10);
        $name = request()->param('name');
        //查询条件
        $map = [];
        if ($name) {
            $map['a.name'] = array('like', "%{$name}%");
        }
        $managers = db('manager')
            ->alias('a')
            ->join('role_admin b', 'a.admin_id=b.admin_id')
            ->join('role c', 'b.role_id=c.id')
            ->where($map)
            ->field('a.*,c.name as role_name')
            ->paginate($page_num, false, [
                'query' => Request::instance()->param(),//不丢失已存在的url参数
            ]);
        $title = '管理员列表';
        return $this->fetch('', compact('title', 'managers'));

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $roles = db('role')->select();
        $title = '新增管理员';
        return $this->fetch('', compact('title', 'roles'));
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->param();
        $validate_result = $this->validate($data, 'Manager');
        if (true !== $validate_result) {
            return array('info' => $validate_result, 'status' => 0);
        }
        // 启动事务
        Db::startTrans();
        try {
            //数据库操作
            //关联admin表
            $password = think_admin_md5($data['password'], UC_AUTH_KEY);
            //如果使用model方法返回的是操作结果不是对象因此获取不到新增的ID
            $Admin = new \app\admin\model\Admin();
            $Admin->save(['phone' => $data['phone'], 'password' => $password]);
            $admin_id = $Admin->admin_id;
            //插入管理员表
            $Manager = new \app\admin\model\Manager();
            $data['admin_id'] = $admin_id;
            $Manager->save($data);
            //在role_admin表中插入记录
            db('role_admin')->insert(['role_id' => $data['role_id'], 'admin_id' => $admin_id]);
            // 提交事务
            Db::commit();
            //返回结果
            return array('info' => '新增成功', 'status' => 1, 'target' => 'back');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return array('info' => '新增失败', 'status' => 0);

        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $manager = db('manager')
            ->alias('a')
            ->join('role_admin b', 'a.admin_id=b.admin_id')
            ->join('role c', 'b.role_id=c.id')
            ->where('a.id', $id)
            ->field('a.*,c.name as role_name')
            ->find();
        $title = '管理员详情';
        return $this->fetch('', compact('title', 'manager'));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $manager = db('manager')
            ->alias('a')
            ->join('role_admin b', 'a.admin_id=b.admin_id')
            ->join('role c', 'b.role_id=c.id')
            ->where('a.id', $id)
            ->field('a.*,c.name as role_name,b.role_id')
            ->find();
        $roles = db('role')->select();
        $title = '管理员编辑';
        return $this->fetch('', compact('title', 'manager', 'roles'));
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->param();
        $validate_result = $this->validate($data, 'Manager.edit');
        if (true !== $validate_result) {
            return array('info' => $validate_result, 'status' => 0);
        }
        // 启动事务
        Db::startTrans();
        try {
            //数据库操作
            $Manager = new \app\admin\model\Manager();
            $Manager->save($data, ['id' => $id]);
            $admin_id =session('admin_info.admin_id');
            //更新用户表
            $Admin = new \app\admin\model\Admin();
            $Admin->save(['phone' => $data['phone']], ['admin_id' => $admin_id]);
            //更新role_admin
            db('role_admin')->where('admin_id', $admin_id)->update(['role_id' => $data['role_id']]);

            // 提交事务
            Db::commit();
            //返回结果
            return array('info' => '更新成功', 'status' => 1, 'target' => 'back');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return array('info' => '更新失败', 'status' => 0);

        }

    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id=null)
    {
        if (empty($id)) {
            $return_data = array('info' => '请选择要操作的数据！', 'status' => -1);
            return $return_data;
        }
        $id = array_unique((array)$id);
        // 启动事务
        Db::startTrans();
        try {
            $Manager = new \app\admin\model\Manager();
            $Manager::destroy($id);
            // 提交事务
            Db::commit();
            return array('info' => '删除成功！', 'status' => 1);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return array('info' => '删除失败！', 'status' => 0);

        }
    }
}
