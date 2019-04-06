<?php
//配置文件
define('UC_AUTH_KEY', '*&^sfohiwOIWE456*&%'); //加密KEY
define('HOST', 'http://hjkkh.haopingzhushou.com/weixin.php');
return [
    'user_administrator'    => 1, //管理员用户ID
    'develop_mode'          => 0,//开发者模式1=>是；0=>否
    'DATA_BACKUP_PATH'      =>PUBLIC_PATH.'backup/',
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_PATH . 'admin'.DS.'view' . DS . 'common'.DS.'error.html',
];