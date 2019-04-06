<?php
/**
 * Created by PhpStorm.
 * User: Allen.liu
 * Date: 2017/8/7
 * Time: 14:00
 */

use think\Route;

//菜单相关
Route::get('/navbar', 'admin/Menu/navbar');
Route::get('/sidebar', 'admin/Menu/sidebar');
Route::get('/password', 'admin/Admin/pass');//修改密码
Route::post('/password', 'admin/Admin/passSave');//修改密码

Route::get('/menu/[:pid]', 'admin/Menu/index');
Route::get('/menu/:id', 'admin/Menu/read');
Route::get('/menu/create/:pid', 'admin/Menu/create');
Route::post('/menu', 'admin/Menu/save');
Route::get('/menu/:id/edit', 'admin/Menu/edit');
Route::put('/menu/:id', 'admin/Menu/update');
Route::delete('/menu', 'admin/Menu/delete');
//角色相关
Route::get('/role', 'admin/Role/index');
Route::get('/role/:id', 'admin/Role/read');
Route::get('/role/create', 'admin/Role/create');
Route::post('/role', 'admin/Role/save');
Route::get('/role/:id/edit', 'admin/Role/edit');
Route::put('/role/:id', 'admin/Role/update');
Route::delete('/role', 'admin/Role/delete');
//授权相关
Route::get('/access/[:role_id]', 'admin/Role/access');
Route::post('/access', 'admin/Role/accessHandle');
//登录相关
Route::get('/login', 'admin/Login/index');
Route::get('/logout', 'admin/Login/logout');
Route::post('/login', 'admin/Login/login');
//管理员相关
Route::get('/manager', 'admin/Manager/index');
Route::get('/manager/:id', 'admin/Manager/read');
Route::get('/manager/create', 'admin/Manager/create');
Route::post('/manager', 'admin/Manager/save');
Route::get('/manager/:id/edit', 'admin/Manager/edit');
Route::put('/manager/:id', 'admin/Manager/update');
Route::delete('/manager', 'admin/Manager/delete');

/**
 * 微信模块路由定义
 */






