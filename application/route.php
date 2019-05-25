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

//论坛管理
Route::get('/admin/article_index', 'admin/article/index');//论坛列表
Route::delete('/admin/article_delete', 'admin/article/delete');//论坛列表

//公告
Route::get('/admin/Announcement/index', 'admin/Announcement/index');
Route::get('/admin/Announcement/create', 'admin/Announcement/create');
Route::post('/admin/Announcement/save', 'admin/Announcement/save');
Route::get('/admin/Announcement/edit', 'admin/Announcement/edit');
Route::post('/admin/Announcement/update', 'admin/Announcement/update');

//场地资源设置
Route::get('/admin/Place/index', 'admin/Place/index');
Route::get('/admin/Place/create', 'admin/Place/create');
Route::post('/admin/Place/save', 'admin/Place/save');
Route::get('/admin/Place/details', 'admin/Place/details');
Route::post('/admin/Place/details_save', 'admin/Place/detailsSave');

/**
 * app部分路由
 */
//用户部分
Route::get('/V1/sendEmail', 'api/Reg/sendEmail');//邮箱发送验证码
Route::get('/V1/reg_save', 'api/Reg/save');//注册账号
Route::get('/V1/email_password', 'api/Reg/emailPassword');//重置密码
Route::get('/V1/getPersonalData', 'api/User/getPersonalData');//获取个人资料
Route::post('/V1/avatar_edit', 'api/User/avatarEdit');//修改头像
Route::get('/V1/user_save', 'api/User/save');//编辑资料
Route::get('/V1/authentication', 'api/User/authentication');//认证
Route::get('/V1/login', 'api/Login/login');//登录
Route::get('/V1/logout', 'api/Login/logout');//退出登录

//邀请帖子
Route::post('/V1/add_invitation', 'api/Invitation/addInvitation');//发布邀请
Route::get('/V1/del_invitation', 'api/Invitation/delInvitation');//删除邀请
Route::get('/V1/join_invitation', 'api/Invitation/joinInvitation');//加入邀请
Route::get('/V1/exit_invitation', 'api/Invitation/exitInvitation');//退出邀请
Route::get('/V1/get_academy', 'api/Invitation/getAcademy');//获取学院
Route::get('/V1/invitation_list', 'api/Invitation/invitationList');//邀请列表
Route::get('/V1/my_invitation', 'api/Invitation/myInvitation');//我参与的邀请
Route::get('/V1/my_push_invitation', 'api/Invitation/myPushInvitation');//我发布的邀请

//论坛
Route::get('/V1/article_add', 'api/article/addArticle');//论坛列表
Route::get('/V1/article_index', 'api/article/index');//论坛列表
Route::get('/V1/article_like/:article_id', 'api/article/like');//点赞
Route::get('/V1/article_comment/:article_id', 'api/article/comment');//评论
Route::get('/V1/del_article/:article_id', 'api/article/delArticle');//删除
Route::get('/V1/upload_pictures', 'api/article/uploadPictures');//上传图片






