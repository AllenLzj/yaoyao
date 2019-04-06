<?php

namespace app\admin\controller;

use think\Controller;
use PHPExcel;
use PHPExcel_IOFactory;

class Excel extends Controller
{
    /**
     * @param $title   导出excel文件名 chat
     * @param $data    导出的数据列－select结果 array
     * @param $title   导出数据第一列 array
     */
    public function exportExcel($data, $name, $pvalue)
    {
        $data = $this::toIndexArr($data);
//        array_unshift($data,$title);
        //找到当前脚本所在路径
        //$path = PUBLIC_PATH;
        //实例化PHPExcel类，类似于在桌面上新建一个Excel表格
        $PHPExcel = new PHPExcel();
        //获得当前活动sheet的操作对象
        $PHPSheet = $PHPExcel->getActiveSheet();
        //给当前活动sheet设置名称
        $PHPSheet->setTitle($pvalue);
        //给当前活动sheet填充数据，数据填充是按顺序一行一行填充的，假如想给A1留空，可以直接setCellValue(‘A1’,’’);
//        $PHPSheet->setCellValue('A1', '姓名')
//            ->setCellValue('B1', '分数');
//        $PHPSheet->setCellValue('A2', '张三')
//            ->setCellValue('B2', '50');
        //直接加载数组数据来填充数据，但是假如数据量大的时候会很占内存，同时因为是批量插入数据，所以不利于我们控制样式以及编辑
        $PHPSheet->fromArray($data);
        //按照指定格式生成Excel文件，‘Excel2007’表示生成2007版本的xlsx，
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        //表示在$path路径下面生成demo.xlsx文件
        //$PHPWriter->save($path . DS . 'excel/demo.xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
        //header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $PHPWriter->save("php://output");
    }

    /**
     * @param $data
     * @return mixed
     * User: Allen.liu
     */
    static function toIndexArr($data)
    {
        foreach ($data as $vol => $item) {
            $i = 0;
            foreach ($item as $key => $value) {
                $arr_item[$i] = $value;
                $arr_key[$i] = $key;
                $i++;
            }
            $arr[$vol] = $arr_item;
        }
        array_unshift($arr, $arr_key);
        return $arr;
    }

    /**
     * @param $file
     * @return array
     * User: Allen.liu
     * 上传文件处理
     */
    public function importExcel($file)
    {
        // 判断文件是什么格式
        $type = pathinfo($file);
        $type = strtolower($type["extension"]);
        switch ($type){
            case 'xlsx':
                $type = "Excel2007";
                break;
            case 'xls':
                $type = "Excel5";
                break;
            case 'csv':
                $type = "CSV";
                break;
        }

        ini_set("max_execution_time", "0");
        // 判断使用哪种格式
        $objReader = PHPExcel_IOFactory::createReader($type);
        $objPHPExcel = $objReader->load($file);
        $sheet = $objPHPExcel->getSheet(0);
        // 取得总行数
        $highestRow = $sheet->getHighestRow();
        // 取得总列数
        $highestColumn = $sheet->getHighestColumn();
        //循环读取excel文件,读取一条,插入一条
        $data = array();
        //从第一行开始读取数据
        for ($j = 1; $j <= $highestRow; $j++) {
            //从A列读取数据
            for ($k = "A"; $k <= $highestColumn; $k++) {
                // 读取单元格
                $data[$j][] = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
            }
        }
//        $data索引值从1开始
        array_splice($data, 0, 1);
        return $data;
    }


}
