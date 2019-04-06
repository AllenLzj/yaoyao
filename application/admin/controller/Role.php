<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

/**
 * Class Role
 * @package app\admin\controller
 * User: Allen.liu
 */
class Role extends Admin
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
        //实例化模型
        $Role = new \app\admin\model\Role();
        //查询条件
        $map = [];
        if ($name) {
            $map['name'] = array('like', "%{$name}%");
        }
        $roles = $Role->where($map)->paginate($page_num, false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $title = '角色列表';
        return $this->fetch('', compact('title', 'roles'));

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $title = '新增角色';
        return $this->fetch('', compact('title'));
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
        $validate_result = $this->validate($data, 'Role');
        if (true !== $validate_result) {
            return array('info' => $validate_result, 'status' => 0);
        }
        // 启动事务
        Db::startTrans();
        try {
            //数据库操作
            $Role = new \app\admin\model\Role();
            $Role->save($data);
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
        $Role = new \app\admin\model\Role();
        $role = $Role->find($id);
        $title = '角色详情';
        return $this->fetch('', compact('title', 'role'));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $Role = new \app\admin\model\Role();
        $role = $Role->find($id);
        $title = '角色编辑';
        return $this->fetch('', compact('title', 'role'));
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
        $validate_result = $this->validate($data, 'Role');
        if (true !== $validate_result) {
            return array('info' => $validate_result, 'status' => 0);
        }
        // 启动事务
        Db::startTrans();
        try {
            //数据库操作
            $Role = new \app\admin\model\Role();
            $Role->save($data, ['id' => $id]);
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
            $Role = new \app\admin\model\Role();
            $Role::destroy($id);
            // 提交事务
            Db::commit();
            return array('info' => '删除成功！', 'status' => 1);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return array('info' => '删除失败！', 'status' => 0);

        }
    }

    /**
     * @return mixed
     * User: Allen.liu
     * 角色授权页
     */
    public function access($role_id)
    {
        //获取所有一级菜单
        $menus = db('menu_')->where('pid', 0)->field('id,descr')->select();
        foreach ($menus as &$menu) {
            //当前权限存在添加标识符
            if (db('menu_role')->where(['role_id' => $role_id, 'menu_id' => $menu['id']])->find()) {
                $menu['check_status'] = 1;
            } else {
                $menu['check_status'] = 0;
            }
        }
        foreach ($menus as &$menu) {
            //获取所有二级菜单
            $menu['child'] = db('menu')->where('pid', $menu['id'])->field('id,descr')->select();
            if (!empty($menu['child'])) {
                foreach ($menu['child'] as &$item) {
                    //当前权限存在添加标识符
                    if (db('menu_role')->where(['role_id' => $role_id, 'menu_id' => $item['id']])->find()) {
                        $item['check_status'] = 1;
                    } else {
                        $item['check_status'] = 0;
                    }
                }
            }
        }

        $title = '角色授权';
        return $this->fetch('', compact('title', 'menus'));
    }

    /**
     * @param Request $request
     * @param $role_id
     * @return array
     * User: Allen.liu
     * 角色授权处理
     */
    public function accessHandle(Request $request, $role_id)
    {
        //获取节点列表
        $ids = $request->param('admin_rules/a');
        if (empty($ids)) {
            return ['info' => '请选择具体权限后进行提交！', 'status' => 0];
        } else {
            foreach ($ids as $item) {
                $arr['role_id'] = $role_id;
                $arr['menu_id'] = $item;
                $node_list[] = $arr;
            }
        }
        // 启动事务
        Db::startTrans();
        try {
            Db::name('menu_role')->where('role_id', $role_id)->delete();
            $result = Db::name('menu_role')->insertAll($node_list);
            // 提交事务
            Db::commit();
            if ($result) {
                return ['info' => '权限配置成功！', 'status' => 1, 'target' => 'back'];

            } else {
                return ['info' => '权限配置失败！', 'status' => 0];

            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['info' => '权限配置失败！', 'status' => 0];
        }

    }
}
