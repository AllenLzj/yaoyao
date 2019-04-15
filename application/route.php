<?php
/**
 * Created by PhpStorm.
 * User: Allen.liu
 * Date: 2017/8/7
 * Time: 14:00
 */

use think\Route;

//菜单相关
Route::get('/admin/navbar', 'admin/Menu/navbar');
Route::get('/', 'admin/Role/index');
Route::get('/admin/sidebar', 'admin/Menu/sidebar');
Route::get('/admin/password', 'admin/Admin/pass');//修改密码
Route::post('/admin/password', 'admin/Admin/passSave');//修改密码

Route::get('/admin/menu/[:pid]', 'admin/Menu/index');
//Route::get('/admin/menu/:id', 'admin/Menu/read');
Route::get('/admin/menu/create/:pid', 'admin/Menu/create');
Route::post('/admin/menu', 'admin/Menu/save');
Route::get('/admin/menu/:id/edit', 'admin/Menu/edit');
Route::put('/admin/menu/:id', 'admin/Menu/update');
Route::delete('/admin/menu', 'admin/Menu/delete');
//角色相关
Route::get('/admin/role', 'admin/Role/index');
Route::get('/admin/role/:id', 'admin/Role/read');
Route::get('/admin/role/create', 'admin/Role/create');
Route::post('/admin/role', 'admin/Role/save');
Route::get('/admin/role/:id/edit', 'admin/Role/edit');
Route::put('/admin/role/:id', 'admin/Role/update');
Route::delete('/admin/role', 'admin/Role/delete');
//授权相关
Route::get('/admin/access/[:role_id]', 'admin/Role/access');
Route::post('/admin/access', 'admin/Role/accessHandle');
//登录相关
Route::get('/admin/login', 'admin/Login/index');
Route::get('/admin/logout', 'admin/Login/logout');
Route::post('/admin/login', 'admin/Login/login');
//管理员相关
Route::get('/admin/manager', 'admin/Manager/index');
Route::get('/admin/manager/:id', 'admin/Manager/read');
Route::get('/admin/manager/create', 'admin/Manager/create');
Route::post('/admin/manager', 'admin/Manager/save');
Route::get('/admin/manager/:id/edit', 'admin/Manager/edit');
Route::put('/admin/manager/:id', 'admin/Manager/update');
Route::delete('/admin/manager', 'admin/Manager/delete');

//用户管理
Route::get('/admin/user', 'admin/user/index');//用户列表
Route::get('/admin/user_add', 'admin/user/create');//新增用户
Route::post('/admin/user_save', 'admin/user/save');//新增用户
Route::get('/admin/user_edit/:id', 'admin/user/edit');//编辑用户
Route::post('/admin/user_update', 'admin/user/update');//更新用户
Route::get('/admin/user_disabled', 'admin/user/disabled');//用户禁用

/**
 * app部分路由
 */
Route::get('/V1/sendEmail', 'api/Reg/sendEmail');//邮箱发送验证码
Route::get('/V1/reg_save', 'api/Reg/save');//注册账号
Route::get('/V1/email_password', 'api/Reg/emailPassword');//重置密码

Route::post('/V1/avatar_edit', 'api/User/avatarEdit');//修改头像
Route::get('/V1/user_save', 'api/User/save');//编辑资料







