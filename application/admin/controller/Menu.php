<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

/**
 * Class Menu
 * @package app\admin\controller
 * User: Allen.liu
 */
class Menu extends Admin
{

    /**
     * 后台菜单首页
     * @return none
     */
    public function index()
    {
        //菜单父ID
        $pid = request()->param('pid', 0);
        $page_num = request()->param('page_num', 10);
        $title = request()->param('title');
        $map['pid'] = $pid;
        if ($title) {
            $map['title'] = array('like', "%{$title}%");
        }
        $map['module_name'] = request()->module();
        $list = db('menu')->where($map)
            ->order('sort asc,id asc')
            ->paginate($page_num, false, [
                'query' => Request::instance()->param(),//不丢失已存在的url参数
            ])
            ->each(function ($item, $key) {
                int_to_string2($item, array('hide' => array(1 => '是', 0 => '否'), 'is_dev' => array(1 => '是', 0 => '否')));
                return $item;
            });
        $title = '菜单列表';
        return $this->fetch('', compact('list', 'title'));
    }

    /**
     * @return array
     * User: Allen.liu
     * 新增菜单页
     */
    public function create()
    {
        //获取所有菜单
        $menus = db('menu')->where(['pid' => 0])->field('id,title')->select();
        //删除没有权限的菜单
        foreach ($menus as &$menu) {
            //获取所有二级菜单
            $menu['child'] = db('menu')->where(['pid' => $menu['id']])->field('id,title')->select();
        }
        $title = '新增菜单';
        return $this->fetch('', compact('title', 'menus'));
    }

    /**
     * @param Request $request
     * @return array
     * User: Allen.liu
     * 保存菜单
     */
    public function save(Request $request)
    {
        $data = $request->param();
        //添加菜单模块名
        $data['module_name'] = request()->module();

        if ($data['pid'] == 0) {
            if (empty($data['title']) || empty($data['class_name']) || empty($data['sort']) || empty($data['tip'])) {
                $return_data = array('info' => '数据不能为空，请重新填写！', 'status' => 0);
                return $return_data;
            }
        } else {
            if (empty($data['title']) || empty($data['url']) || empty($data['tip']) || empty($data['sort'])) {
                $return_data = array('info' => '数据不能为空，请重新填写！', 'status' => 0);
                return $return_data;
            }
        }
        switch (input('hide')) {
            case 0:
                $data['descr'] = '侧边栏-' . $data['title'];
                break;
            default:
                $data['descr'] = '操作-' . $data['title'];
        }
        if ($data['pid'] == 0) {
            $data['descr'] = '一级菜单-' . $data['title'];
        }

        // 启动事务
        Db::startTrans();
        try {
            model('menu')->save($data);
            if(!empty($data['url'])){
                $this->autoAddChildMenus($data);
            }
            // 提交事务
            Db::commit();
            $return_data = array('info' => '新增成功！', 'status' => 1, 'target' => 'back');
            return $return_data;

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $return_data = array('info' => '新增失败！', 'status' => 0);
            return $return_data;
        }
    }

    public function edit()
    {
        //菜单ID
        $id = input('id', 0);
        //查询当前菜单
        $menu_present = db('menu')->find($id);


        //获取所有菜单
        $menus = db('menu')->where(['pid' => 0])->field('id,title')->select();
        //删除没有权限的菜单
        foreach ($menus as &$menu) {
            //获取所有二级菜单
            $menu['child'] = db('menu')->where(['pid' => $menu['id']])->field('id,title')->select();
        }
        $title = '编辑菜单页';
        return $this->fetch('', compact('title', 'menus', 'menu_present'));
    }

    public function update(Request $request)
    {
        $data = $request->param();
        //添加菜单模块名
        $data['module_name'] = request()->module();

        if ($data['pid'] == 0) {
            if (empty($data['title']) || empty($data['class_name']) || empty($data['sort']) || empty($data['tip'])) {
                $return_data = array('info' => '数据不能为空，请重新填写！', 'status' => 0);
                return $return_data;
            }
        } else {
            if (empty($data['title']) || empty($data['url']) || empty($data['tip']) || empty($data['sort'])) {
                $return_data = array('info' => '数据不能为空，请重新填写！', 'status' => 0);
                return $return_data;
            }
        }
        switch (input('hide')) {
            case 0:
                $data['descr'] = '侧边栏-' . $data['title'];
                break;
            default:
                $data['descr'] = '操作-' . $data['title'];
        }
        if ($data['pid'] == 0) {
            $data['descr'] = '一级菜单-' . $data['title'];
        }

        // 启动事务
        Db::startTrans();
        try {
//            $Menu = new \app\admin\model\Menu();
            model('menu')->save($data, ['id' => $data['id']]);
            // 提交事务
            Db::commit();

            $return_data = array('info' => '编辑成功！', 'status' => 1, 'target' => 'back');
            return $return_data;

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $return_data = array('info' => '编辑失败！', 'status' => 0);
            return $return_data;
        }
    }

    /**
     * @param $id
     * @return array
     * User: Allen.liu
     * 删除菜单
     */
    public function delete($id=null)
    {
        if (empty($id)) {
            $return_data = array('info' => '请选择要操作的数据！', 'status' => 0);
            return $return_data;
        }
        $id = array_unique((array)$id);
        // 启动事务
        Db::startTrans();
        try {
            $Menu = new \app\admin\model\Menu();
            $Menu::destroy($id);
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
     * @param $controller
     * User: Allen.liu
     * 传入controller/index自动添加余下的二级菜单
     */
    public function autoAddChildMenus($menu)
    {
        $url = explode('/', strtolower($menu['url']));
        $controller = $url[0];
        //是列表页才自动添加二级菜单
        if ($url[1] == 'index') {
            $data = [
                [
                    'title' => '新增' . $menu['title'],
                    'url' => $controller . '/create',
                    'hide' => 1,
                    'is_dev' => 0,
                    'tip' => '新增' . $menu['title'] . '页',
                    'descr' => '操作-' . '新增' . $menu['title'],
                    'pid' => $menu['pid'],
                    'module_name' => $menu['module_name']
                ],
                [
                    'title' => '新增' . $menu['title'] . '提交',
                    'url' => $controller . '/save',
                    'hide' => 1,
                    'is_dev' => 0,
                    'tip' => '新增' . $menu['title'] . '提交',
                    'descr' => '操作-' . '新增' . $menu['title'] . '提交',
                    'pid' => $menu['pid'],
                    'module_name' => $menu['module_name']
                ],
                [
                    'title' => '编辑' . $menu['title'],
                    'url' => $controller . '/edit',
                    'hide' => 1,
                    'is_dev' => 0,
                    'tip' => '编辑' . $menu['title'] . '页',
                    'descr' => '操作-' . '编辑' . $menu['title'],
                    'pid' => $menu['pid'],
                    'module_name' => $menu['module_name']
                ],
                [
                    'title' => '编辑' . $menu['title'] . '提交',
                    'url' => $controller . '/update',
                    'hide' => 1,
                    'is_dev' => 0,
                    'tip' => '编辑' . $menu['title'] . '提交',
                    'descr' => '操作-' . '编辑' . $menu['title'] . '提交',
                    'pid' => $menu['pid'],
                    'module_name' => $menu['module_name']
                ],
                [
                    'title' => $menu['title'] . '详情',
                    'url' => $controller . '/read',
                    'hide' => 1,
                    'is_dev' => 0,
                    'tip' => $menu['title'] . '详情' . '页',
                    'descr' => '操作-' . $menu['title'] . '详情',
                    'pid' => $menu['pid'],
                    'module_name' => $menu['module_name']
                ],
                [
                    'title' => '删除' . $menu['title'],
                    'url' => $controller . '/delete',
                    'hide' => 1,
                    'is_dev' => 0,
                    'tip' => '删除' . $menu['title'] . '页',
                    'descr' => '操作-' . '删除' . $menu['title'],
                    'pid' => $menu['pid'],
                    'module_name' => $menu['module_name']
                ]
            ];
            $Menu = new \app\admin\model\Menu();
            $Menu->saveAll($data);
        }
    }
    public function navbar(){
        $title = '导航';
        return $this->fetch('common/navbar',compact('title'));
    }

    public function sidebar()
    {
        $title = '导航';
        return $this->fetch('common/sidebar', compact('title'));
    }
}