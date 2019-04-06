<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use think\Db;
/**
 * 数据库备份还原控制器
 * @author Allen.liu
 */
class Database extends Admin
{

    /**
     * 数据库备份/还原列表
     * @param  String $type import-还原，export-备份
     * @author Allen.liu
     */
    public function index($type = null)
    {
        switch ($type) {
            /* 数据还原 */
            case 'import':
                //列出备份文件列表
                $path = config('DATA_BACKUP_PATH');
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }
                $path = realpath($path);
                $flag = \FilesystemIterator::KEY_AS_FILENAME;
                $glob = new \FilesystemIterator($path, $flag);

                $list = array();
                foreach ($glob as $name => $file) {
                    if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                        $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                        $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                        $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                        $part = $name[6];

                        if (isset($list["{$date} {$time}"])) {
                            $info = $list["{$date} {$time}"];
                            $info['part'] = max($info['part'], $part);
                            $info['size'] = $info['size'] + $file->getSize();
                        } else {
                            $info['part'] = $part;
                            $info['size'] = $file->getSize();
                        }
                        $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                        $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                        $info['time'] = strtotime("{$date} {$time}");

                        $list["{$date} {$time}"] = $info;
                    }
                }
                $title = '数据还原';
                break;

            /* 数据备份 */
            case 'export':
                $Db = Db::connect();
                $list = $Db->query('SHOW TABLE STATUS');
                $list = array_map('array_change_key_case', $list);
                $title = '数据备份';
                break;

            default:
                $this->error('参数错误！');
        }

        //渲染模板
        $this->assign('title', $title);
        $this->assign('list', $list);
       return view($type);
    }

    /**
     * 优化表
     * @param  String $tables 表名
     * @return  array
     * @author Allen.liu
     */
    public function optimize($tables = null)
    {
        if ($tables) {
            $Db = Db::connect();
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if ($list) {
                    return array('info'=>'数据表优化完成！','status'=>1);
                } else {
                    return array('info'=>'数据表优化出错请重试！','status'=>-1);
                }
            } else {
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if ($list) {
                    return array('info'=>"数据表'{$tables}'优化完成！",'status'=>1);
                } else {
                    return array('info'=>"数据表'{$tables}'优化出错请重试！",'status'=>1);
                }
            }
        } else {
            return array('info'=>'请指定要优化的表！','status'=>-1);
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     * @author Allen.liu
     */
    public function repair($tables = null)
    {
        if ($tables) {
            $Db = Db::connect();
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = $Db->query("REPAIR TABLE `{$tables}`");

                if ($list) {
                    return array('info'=>'数据表修复完成！','status'=>1);
                } else {
                    return array('info'=>'数据表修复出错请重试！','status'=>-1);
                }
            } else {
                $list = $Db->query("REPAIR TABLE `{$tables}`");
                if ($list) {
                    return array('info'=>"数据表'{$tables}'修复完成！",'status'=>1);
                } else {
                    return array('info'=>"数据表'{$tables}'修复出错请重试！",'status'=>-1);
                }
            }
        } else {
            return array('info'=>'请指定要修复的表！','status'=>-1);
        }
    }

    //数据库备份
    public function bak(){
        $type=input("tp");
        $tables=input("tables/a");
        $name=input("name");
        $sql=new \org\Baksql(\think\Config::get("database"));
        $this->assign('title','数据库备份');
        $data=$sql->get_filelist();
        $this->assign('list',$data);
        switch ($type)
        {
            case "backup": //备份
                return $sql->backup($tables);
                break;
            case "dowonload": //下载
                $sql->downloadFile($name);
                break;
            case "restore": //还原
                return $sql->restore($name);
                break;
            case "del": //删除
                return $sql->delfilename($name);
                break;
            default: //获取备份文件列表
                return view();

        }

    }

}
