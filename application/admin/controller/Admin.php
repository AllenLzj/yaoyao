<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
class Admin extends Controller
{
    /**
     * User: Allen.liu
     * 进行权限判断
     * 根据url获取contoller/action验证
     */
    protected function _initialize()
    {

        //判断是否登录
        if (!$this->isLogin()) {
            return $this->redirect('admin/Login/login');
        }
        //判断是否是超级管理员
        if (!$this->isAdmin()) {
            // 检测访问权限
            $access = $this->checkAccess();
            if (!$access) {
                if (request()->isAjax()) {
                    $return_data = array('info' => '未授权的访问！', 'status' => 0);
                    return $return_data;
                } else {
                    //获取菜单列表
                    $menus = $this->getMenus();
                    $this->assign('MENU_LIST', $menus);
                    //面包屑导航
                    $this->assign('BREAD_CRUMB', $this->breadCrumb());

                    $this->assign('title', '未授权的访问');

                    $this->error('未授权访问!');
                }
            }
        }
        //获取菜单列表
        $menus = $this->getMenus();
        $this->assign('MENU_LIST', $menus);
        //面包屑导航


      
       
        $this->assign('BREAD_CRUMB', $this->breadCrumb());
    }

    /**
     * @return bool
     * User: Allen.liu
     * 检查当前用户是否具有当前权限
     */
    public function checkAccess()
    {
        $url = strtolower(request()->controller() . '/' . request()->action());
        $module_name = strtolower(request()->module());
        //判断是否存在菜单不存在跳转到创建页
        $menu = db('menu')->where(['url' => $url, 'module_name' => $module_name])->find();
        if (!$menu) {
            $this->redirect('admin/Menu/create', ['pid' => 1]);
        }

        $result = db('menu a')
            ->join('menu_role b', 'a.id=b.menu_id', 'left')
            ->join('role_admin c', 'b.role_id=c.role_id', 'left')
            ->where(['a.url' => $url, 'a.module_name' => $module_name, 'c.admin_id' => session('admin_info.admin_id')])
            ->value('a.id');
        if ($result) {
            return true;
        } else {
            return false;

        }

    }

    public function getMenus()
    {
        if (!session('menu_list')) {
            $menus = db('menu')
                ->where(['pid' => 0, 'hide' => 0, 'is_dev' => 0])
                ->field('id,title,url,module_name,class_name')
                ->order('sort asc')
                ->select();
            //删除没有权限的菜单
            if (!$this->isAdmin()) {
                foreach ($menus as $key => $value) {
                    if (!$this->checkMenuAccess($value['id'])) {
                        unset($menus[$key]);
                    }
                }
            }

            foreach ($menus as &$menu) {
                //获取所有二级菜单
                $menu['child'] = db('menu')
                    ->where(['pid' => $menu['id'], 'hide' => 0, 'is_dev' => 0])
                    ->field('id,title,url,module_name')
                    ->order('sort asc')
                    ->select();
                //删除没有权限的二级菜单
                if (!$this->isAdmin()) {
                    foreach ($menu['child'] as $key1 => $value1) {
                        if (!$this->checkMenuAccess($value1['id'])) {
                            unset($menu['child'][$key1]);
                        }
                    }
                }
            }
            session('menu_list', $menus);
        } else {
            $menus = session('menu_list');
        }

        return $menus;
    }

    /**
     * @param $url
     * @return bool
     * User: Allen.liu
     * 判断当前菜单是否具有权限
     */
    public function checkMenuAccess($id)
    {
        $module_name = strtolower(request()->module());
        $result = db('menu a')
            ->join('menu_role b', 'a.id=b.menu_id', 'left')
            ->join('role_admin c', 'b.role_id=c.role_id', 'left')
            ->where(['a.id' => $id, 'a.module_name' => $module_name, 'c.admin_id' => session('admin_info.admin_id')])
            ->find();
        if ($result) {
            return true;
        } else {
            return false;

        }
    }

    //面包屑导航
    public function breadCrumb()
    {
        $url = strtolower(request()->controller() . '/' . request()->action());
        $module_name = strtolower(request()->module());
        $menu = db('menu')->where(['url' => $url, 'module_name' => $module_name])->find();
        $parent_name = db('menu')->where('id', $menu['pid'])->value('title');
        return ['menu' => $menu, 'parent_name' => $parent_name];

    }

    //超级管理员验证
    public function isAdmin()
    {
        if (session('admin_info.username') == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * User: Allen.liu
     * 判断是否已经登录
     */
    public function isLogin()
    {
        if (session('?admin_info')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     * User: Allen.liu
     * Email cumt_wangmingxue@126.com
     * 修改密码
     */
    public function pass()
    {
        $title = '修改密码';
        return $this->fetch('', compact('title'));
    }

    public function passSave()
    {
        //获取参数
        $password = input('post.old');
        if (empty($password)) {
            return array('info' => '请输入原密码', 'status' => 0);
        }
        $data['password'] = input('post.password');
        if (empty($data['password'])) {
            return array('info' => '请输入新密码', 'status' => 0);
        }
        $repassword = input('post.repassword');
        if (empty($repassword)) {
            return array('info' => '请输入确认密码', 'status' => 0);
        }

        if ($data['password'] !== $repassword) {
            return array('info' => '您输入的新密码与确认密码不一致', 'status' => 0);
        }

        $old_password = db('admin')->where(array('admin_id' => session('admin_info.admin_id')))->value('password');
        if (think_admin_md5($password, UC_AUTH_KEY) != $old_password) {
            return array('info' => '您输入的旧密码不对', 'status' => 0);
        }

        if (think_admin_md5($data['password'], UC_AUTH_KEY) == $old_password) {
            return array('info' => '您输入的新密码和旧密码不能一样', 'status' => 0);
        }

        if (strlen($data['password']) > 30 || strlen($data['password']) < 6) {
            return array('info' => '密码长度必须在6-30个字符之间', 'status' => 0);
        }
        $model = db('admin')->where(array('admin_id' => session('admin_info.admin_id')))->data(array('password' => think_admin_md5($data['password'], UC_AUTH_KEY)))->update();
        if ($model) {
            session(null);
            return array('info' => '密码修改成功', 'status' => 1, 'url' => url('admin/Login/index'));
        } else {
            return array('info' => '密码修改失败', 'status' => 0);
        }
    }
}
