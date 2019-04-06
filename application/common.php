<?php
/**
 * 系统公共库文件
 * 主要定义系统公共函数库
 */

/**
 * 检测是否含in.between
 */

// 防注入，字符串处理，禁止构造数组提交
// 字符过滤
function safe_replace($string)
{
    if (is_array($string)) {
        $string = implode('，', $string);
        $string = htmlspecialchars(str_shuffle($string));
    } else {
        $string = htmlspecialchars($string);
    }
    $string = str_replace('%20', '', $string);
    $string = str_replace('%27', '', $string);
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace("select", "", $string);
    $string = str_replace("join", "", $string);
    $string = str_replace("union", "", $string);
    $string = str_replace("where", "", $string);
    $string = str_replace("insert", "", $string);
    $string = str_replace("delete", "", $string);
    $string = str_replace("update", "", $string);
    $string = str_replace("like", "", $string);
    $string = str_replace("drop", "", $string);
    $string = str_replace("create", "", $string);
    $string = str_replace("modify", "", $string);
    $string = str_replace("rename", "", $string);
    $string = str_replace("alter", "", $string);
    $string = str_replace("cas", "", $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);

    return $string;
}

/**
 * 检测当前用户是否为管理员
 *
 * @return boolean true-管理员，false-非管理员
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_administrator($uid = null)
{
    $uid = is_null($uid) ? is_login() : $uid;
    return $uid && (intval($uid) === config('user_administrator'));
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 *
 * @param string $str
 *            要分割的字符串
 * @param string $glue
 *            分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ',')
{
    return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 *
 * @param array $arr
 *            要连接的数组
 * @param string $glue
 *            分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ',')
{
    return implode($glue, $arr);
}

/**
 * 字符串截取，支持中文和其他编码
 *
 * @static
 *
 * @access public
 * @param string $str
 *            需要转换的字符串
 * @param string $start
 *            开始位置
 * @param string $length
 *            截取长度
 * @param string $charset
 *            编码格式
 * @param string $suffix
 *            截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

/**
 * 系统加密方法
 *
 * @param string $data
 *            要加密的字符串
 * @param string $key
 *            加密密钥
 * @param int $expire
 *            过期时间 单位 秒
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_encrypt($data, $key = '', $expire = 0)
{
    $key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l)
            $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time() : 0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
    }
    return str_replace(array(
        '+',
        '/',
        '='
    ), array(
        '-',
        '_',
        ''
    ), base64_encode($str));
}

/**
 * 获取导航URL
 *
 * @param string $url
 *            导航URL
 * @return string 解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url)
{
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;
        default:
            $url = url($url);
            break;
    }
    return $url;
}

function get_index_url()
{
    $damain = $_SERVER['SERVER_NAME'];
    $url = "http://" . $damain . __ROOT__;
    return $url;
}

/**
 * 系统解密方法
 *
 * @param string $data
 *            要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param string $key
 *            加密密钥
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_decrypt($data, $key = '')
{
    $key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = str_replace(array(
        '-',
        '_'
    ), array(
        '+',
        '/'
    ), $data);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    $data = base64_decode($data);
    $expire = substr($data, 0, 10);
    $data = substr($data, 10);

    if ($expire > 0 && $expire < time()) {
        return '';
    }
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l)
            $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}

/**
 * 数据签名认证
 *
 * @param array $data
 *            被认证的数据
 * @return string 签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data)
{
    // 数据类型检测
    if (!is_array($data)) {
        $data = (array)$data;
    }
    ksort($data); // 排序
    $code = http_build_query($data); // url编码并生成query字符串
    $sign = sha1($code); // 生成签名
    return $sign;
}

/**
 * 对查询结果集进行排序
 *
 * @access public
 * @param array $list
 *            查询结果
 * @param string $field
 *            排序的字段名
 * @param array $sortby
 *            排序类型
 *            asc正向排序 desc逆向排序 nat自然排序
 * @return array
 *
 */
function list_sort_by($list, $field, $sortby = 'asc')
{
    if (is_array($list)) {
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 把返回的数据集转换成Tree
 *
 * @param array $list
 *            要转换的数据集
 * @param string $pid
 *            parent标记字段
 * @param string $level
 *            level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 *
 * @param array $tree
 *            原来的树
 * @param string $child
 *            孩子节点的键
 * @param string $order
 *            排序显示的键，一般是主键 升序排列
 * @param array $list
 *            过渡用的中间数组，
 * @return array 返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array())
{
    if (is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if (isset($reffer[$child])) {
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby = 'asc');
    }
    return $list;
}

/**
 * 格式化字节大小
 *
 * @param number $size
 *            字节数
 * @param string $delimiter
 *            数字和单位分隔符
 * @return string 格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '')
{
    $units = array(
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB'
    );
    for ($i = 0; $size >= 1024 && $i < 5; $i++)
        $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 设置跳转页面URL
 * 使用函数再次封装，方便以后选择不同的存储方式（目前使用cookie存储）
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function set_redirect_url($url)
{
    session('redirect_url', $url);
}

/**
 * 获取跳转页面URL
 *
 * @return string 跳转页URL
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_redirect_url()
{
    $url = session('redirect_url');
    return empty($url) ? __APP__ : $url;
}

/**
 * 处理插件钩子
 *
 * @param string $hook
 *            钩子名称
 * @param mixed $params
 *            传入参数
 * @return void
 */
function hook($hook, $params = array())
{
    \Think\Hook::listen($hook, $params);
}

/**
 * 获取插件类的类名
 *
 * @param strng $name
 *            插件名
 */
function get_addon_class($name)
{
    $class = "Addons\\{$name}\\{$name}Addon";
    return $class;
}

/**
 * 获取插件类的配置文件数组
 *
 * @param string $name
 *            插件名
 */
function get_addon_config($name)
{
    $class = get_addon_class($name);
    if (class_exists($class)) {
        $addon = new $class();
        return $addon->getConfig();
    } else {
        return array();
    }
}

/**
 * 插件显示内容里生成访问插件的url
 *
 * @param string $url
 *            url
 * @param array $param
 *            参数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function addons_url($url, $param = array())
{
    $url = parse_url($url);
    $case = C('URL_CASE_INSENSITIVE');
    $addons = $case ? parse_name($url['scheme']) : $url['scheme'];
    $controller = $case ? parse_name($url['host']) : $url['host'];
    $action = trim($case ? strtolower($url['path']) : $url['path'], '/');

    /* 解析URL带的参数 */
    if (isset($url['query'])) {
        parse_str($url['query'], $query);
        $param = array_merge($query, $param);
    }

    /* 基础参数 */
    $params = array(
        '_addons' => $addons,
        '_controller' => $controller,
        '_action' => $action
    );
    $params = array_merge($params, $param); // 添加额外参数

    return U('Addons/execute', $params);
}

/**
 * 时间戳格式化
 *
 * @param int $time
 * @return string 完整的时间显示
 * @author huajie <banhuajie@163.com>
 */
function time_format($time = NULL, $format = 'Y-m-d H:i')
{
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}

// //获取ip地址信息，返回操作对象
function get_ip_address()
{
    $ip = getip();
    $json = @file_get_contents("http://ip.taobao.com/service/1Info.php?ip=" . $ip); // 根据taobao ip
    $jsonarr = json_decode($json);
    if ($jsonarr->code == 0) {
        $data = $jsonarr->data;
        return $data;
    } else {
        return false;
    }
}

// //根据ip138获得本地真实IP
function get_onlineip()
{
    $mip = @file_get_contents("http://www.ip138.com/ip2city.asp");
    if ($mip) {
        preg_match("/\[.*\]/", $mip, $sip);
        $p = array(
            "/\[/",
            "/\]/"
        );
        $iipp = preg_replace($p, "", $sip[0]);
        return preg_replace($p, "", $sip[0]);
    } else {
        return "获取本地IP失败！";
    }
}

// 从服务器获取访客ip
function getip()
{
    $onlineip = "";
    if (getenv(HTTP_CLIENT_IP) && strcasecmp(getenv(HTTP_CLIENT_IP), unknown)) {
        $onlineip = getenv(HTTP_CLIENT_IP);
    } elseif (getenv(HTTP_X_FORWARDED_FOR) && strcasecmp(getenv(HTTP_X_FORWARDED_FOR), unknown)) {
        $onlineip = getenv(HTTP_X_FORWARDED_FOR);
    } elseif (getenv(REMOTE_ADDR) && strcasecmp(getenv(REMOTE_ADDR), unknown)) {
        $onlineip = getenv(REMOTE_ADDR);
    } elseif (isset($_SERVER[REMOTE_ADDR]) && $_SERVER[REMOTE_ADDR] && strcasecmp($_SERVER[REMOTE_ADDR], unknown)) {
        $onlineip = $_SERVER[REMOTE_ADDR];
    }
    return safe_replace($onlineip);
}

/* 访问统计 */
function IpLookup($ip = '', $status, $id)
{
    $arr = get_ip_address();
    $ip = $arr->ip;
    $data["ip"] = $arr->ip;
    $data["country"] = $arr->country;
    $data["province"] = $arr->region;
    $data["city"] = $arr->city;
    $data["isp"] = $arr->isp;
    if (is_login()) {
        $member = D("member");
        $data["uid"] = $member->uid();
    }
    if (!empty($status)) {
        $data["status"] = $status;
    }
    if (!empty($id)) {
        $data["page"] = $id;
    }
    $data["time"] = NOW_TIME;
    $data["referer"] = $_SERVER['HTTP_REFERER'];
    $data["url"] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $record = M("records");
    if ($record->where("ip='$ip' and status='$status' and page='$id'")->select()) {
        // 有访问记录
        $now = NOW_TIME;
        $recordtime = date("YmdH", $now); // 当前时间点
        $time = $record->where("ip='$ip' and status='$status' and page='$id'")
            ->limit(1)
            ->order("id desc")
            ->getField("time");
        $visittime = date("YmdH", $time); // 获取最近一次访问点
        $chazhi = $recordtime - $visittime; // 小时差值
        if ($chazhi > C('LAG')) {
            $record->add($data);
        }  // 每隔5小时记录一次
        else {
        } // 不记录
    } else { // 没有访问记录
        $record->add($data);
    }

    return $status;
}

/**
 * 获取顶级模型信息
 */
function get_top_model($model_id = null)
{
    $map = array(
        'status' => 1,
        'extend' => 0
    );
    if (!is_null($model_id)) {
        $map['id'] = array(
            'neq',
            $model_id
        );
    }
    $model = M('Model')->where($map)
        ->field(true)
        ->select();
    foreach ($model as $value) {
        $list[$value['id']] = $value;
    }
    return $list;
}

/**
 * 获取所有文档模型信息
 *
 * @param integer $id
 *            模型ID
 * @param string $field
 *            模型字段
 * @return array
 */
function get_document_model($id = null, $field = null)
{
    static $list;

    /* 非法分类ID */
    if (!(is_numeric($id) || is_null($id))) {
        return '';
    }

    /* 读取缓存数据 */
    if (empty($list)) {
        $list = S('DOCUMENT_MODEL_LIST');
    }

    /* 获取模型名称 */
    if (empty($list)) {
        // $ismenu= array('elt', 1);
        $map = array(
            'status' => 1,
            'extend' => 1
        );
        $model = M('Model')->where($map)
            ->field(true)
            ->select();
        foreach ($model as $value) {
            $list[$value['id']] = $value;
        }
        S('DOCUMENT_MODEL_LIST', $list); // 更新缓存
    }

    /* 根据条件返回数据 */
    if (is_null($id)) {
        return $list;
    } elseif (is_null($field)) {
        return $list[$id];
    } else {
        return $list[$id][$field];
    }
}

/**
 * 解析UBB数据
 *
 * @param string $data
 *            UBB字符串
 * @return string 解析为HTML的数据
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function ubb($data)
{
    // TODO: 待完善，目前返回原始数据
    return $data;
}

/**
 * 记录行为日志，并执行该行为的规则
 *
 * @param string $action
 *            行为标识
 * @param string $model
 *            触发行为的模型名
 * @param int $record_id
 *            触发行为的记录id
 * @param int $user_id
 *            执行行为的用户id
 * @return boolean
 * @author huajie <banhuajie@163.com>
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null)
{

    // 参数检查
    if (empty($action) || empty($model) || empty($record_id)) {
        return '参数不能为空';
    }
    if (empty($user_id)) {
        $user_id = is_login();
    }

    // 查询行为,判断是否执行
    $action_info = M('Action')->getByName($action);
    if ($action_info['status'] != 1) {
        return '该行为被禁用或删除';
    }

    // 插入行为日志
    $data['action_id'] = $action_info['id'];
    $data['user_id'] = $user_id;
    $data['action_ip'] = ip2long(get_client_ip());
    $data['model'] = $model;
    $data['record_id'] = $record_id;
    $data['create_time'] = NOW_TIME;

    // 解析日志规则,生成日志备注
    if (!empty($action_info['log'])) {
        if (preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)) {
            $log['user'] = $user_id;
            $log['record'] = $record_id;
            $log['model'] = $model;
            $log['time'] = NOW_TIME;
            $log['data'] = array(
                'user' => $user_id,
                'model' => $model,
                'record' => $record_id,
                'time' => NOW_TIME
            );
            foreach ($match[1] as $value) {
                $param = explode('|', $value);
                if (isset($param[1])) {
                    $replace[] = call_user_func($param[1], $log[$param[0]]);
                } else {
                    $replace[] = $log[$param[0]];
                }
            }
            $data['remark'] = str_replace($match[0], $replace, $action_info['log']);
        } else {
            $data['remark'] = $action_info['log'];
        }
    } else {
        // 未定义日志规则，记录操作url
        $data['remark'] = '操作url：' . $_SERVER['REQUEST_URI'];
    }

    M('ActionLog')->add($data);

    if (!empty($action_info['rule'])) {
        // 解析行为
        $rules = parse_action($action, $user_id);

        // 执行行为
        $res = execute_action($rules, $action_info['id'], $user_id);
    }
}

/**
 * 解析行为规则
 * 规则定义 table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 * field->要操作的字段；
 * condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 * rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 * cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 * max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 *
 * @param string $action
 *            行为id或者name
 * @param int $self
 *            替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author huajie <banhuajie@163.com>
 */
function parse_action($action = null, $self)
{
    if (empty($action)) {
        return false;
    }

    // 参数支持id或者name
    if (is_numeric($action)) {
        $map = array(
            'id' => $action
        );
    } else {
        $map = array(
            'name' => $action
        );
    }

    // 查询行为信息
    $info = M('Action')->where($map)->find();
    if (!$info || $info['status'] != 1) {
        return false;
    }

    // 解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
    $rules = $info['rule'];
    $rules = str_replace('{$self}', $self, $rules);
    $rules = explode(';', $rules);
    $return = array();
    foreach ($rules as $key => &$rule) {
        $rule = explode('|', $rule);
        foreach ($rule as $k => $fields) {
            $field = empty($fields) ? array() : explode(':', $fields);
            if (!empty($field)) {
                $return[$key][$field[0]] = $field[1];
            }
        }
        // cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
        if (!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])) {
            unset($return[$key]['cycle'], $return[$key]['max']);
        }
    }

    return $return;
}

/**
 * 执行行为
 *
 * @param array $rules
 *            解析后的规则数组
 * @param int $action_id
 *            行为id
 * @param array $user_id
 *            执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author huajie <banhuajie@163.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null)
{
    if (!$rules || empty($action_id) || empty($user_id)) {
        return false;
    }

    $return = true;
    foreach ($rules as $rule) {

        // 检查执行周期
        $map = array(
            'action_id' => $action_id,
            'user_id' => $user_id
        );
        $map['create_time'] = array(
            'gt',
            NOW_TIME - intval($rule['cycle']) * 3600
        );
        $exec_count = M('ActionLog')->where($map)->count();
        if ($exec_count > $rule['max']) {
            continue;
        }

        // 执行数据库操作
        $Model = M(ucfirst($rule['table']));
        $field = $rule['field'];
        $res = $Model->where($rule['condition'])->setField($field, array(
            'exp',
            $rule['rule']
        ));

        if (!$res) {
            $return = false;
        }
    }
    return $return;
}

// 根据订单编码，获取会员邮箱
function get_email($uid)
{
    $email = M('ucenter_member')->where("id='$uid'")->getField("email");
    return $email;
}

// 基于数组创建目录和文件
function create_dir_or_files($files)
{
    foreach ($files as $key => $value) {
        if (substr($value, -1) == '/') {
            mkdir($value);
        } else {
            @file_put_contents($value, '');
        }
    }
}

if (!function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $result = array();
        if (null === $indexKey) {
            if (null === $columnKey) {
                $result = array_values($input);
            } else {
                foreach ($input as $row) {
                    $result[] = $row[$columnKey];
                }
            }
        } else {
            if (null === $columnKey) {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row;
                }
            } else {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
        return $result;
    }
}

/**
 * 获取表名（不含表前缀）
 *
 * @param string $model_id
 * @return string 表名
 * @author huajie <banhuajie@163.com>
 */
function get_table_name($model_id = null)
{
    if (empty($model_id)) {
        return false;
    }
    $Model = M('Model');
    $name = '';
    $info = $Model->getById($model_id);
    if ($info['extend'] != 0) {
        $name = $Model->getFieldById($info['extend'], 'name') . '_';
    }
    $name .= $info['name'];
    return $name;
}

/**
 * 获取属性信息并缓存
 *
 * @param integer $id
 *            属性ID
 * @param string $field
 *            要获取的字段名
 * @return string 属性信息
 */
function get_model_attribute($model_id, $group = true, $fields = true)
{
    static $list;

    /* 非法ID */
    if (empty($model_id) || !is_numeric($model_id)) {
        return '';
    }

    /* 获取属性 */
    if (!isset($list[$model_id])) {
        $map = array(
            'model_id' => $model_id
        );
        $extend = M('Model')->getFieldById($model_id, 'extend');

        if ($extend) {
            $map = array(
                'model_id' => array(
                    "in",
                    array(
                        $model_id,
                        $extend
                    )
                )
            );
        }
        $info = M('Attribute')->where($map)
            ->field($fields)
            ->select();
        $list[$model_id] = $info;
    }

    $attr = array();
    if ($group) {
        foreach ($list[$model_id] as $value) {
            $attr[$value['id']] = $value;
        }
        $sort = M('Model')->getFieldById($model_id, 'field_sort');

        if (empty($sort)) { // 未排序
            $group = array(
                1 => array_merge($attr)
            );
        } else {
            $group = json_decode($sort, true);

            $keys = array_keys($group);
            foreach ($group as &$value) {
                foreach ($value as $key => $val) {
                    $value[$key] = $attr[$val];
                    unset($attr[$val]);
                }
            }

            if (!empty($attr)) {
                $group[$keys[0]] = array_merge($group[$keys[0]], $attr);
            }
        }
        $attr = $group;
    } else {
        foreach ($list[$model_id] as $value) {
            $attr[$value['name']] = $value;
        }
    }
    return $attr;
}

/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5'); 调用Admin模块的User接口
 *
 * @param string $name
 *            格式 [模块名]/接口名/方法名
 * @param array|string $vars
 *            参数
 */
function api($name, $vars = array())
{
    $array = explode('/', $name);
    $method = array_pop($array);
    $classname = array_pop($array);
    $module = $array ? array_pop($array) : 'Common';
    $callback = $module . '\\Api\\' . $classname . 'Api::' . $method;
    if (is_string($vars)) {
        parse_str($vars, $vars);
    }
    return call_user_func_array($callback, $vars);
}

/**
 * 根据条件字段获取指定表的数据
 *
 * @param mixed $value
 *            条件，可用常量或者数组
 * @param string $condition
 *            条件字段
 * @param string $field
 *            需要返回的字段，不传则返回整个数据
 * @param string $table
 *            需要查询的表
 * @author huajie <banhuajie@163.com>
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null)
{
    if (empty($value) || empty($table)) {
        return false;
    }

    // 拼接参数
    $map[$condition] = $value;
    $info = M(ucfirst($table))->where($map);
    if (empty($field)) {
        $info = $info->field(true)->find();
    } else {
        $info = $info->getField($field);
    }
    return $info;
}

/**
 * 获取链接信息
 *
 * @param int $link_id
 * @param string $field
 * @return 完整的链接信息或者某一字段
 * @author huajie <banhuajie@163.com>
 */
function get_link($link_id = null, $field = 'url')
{
    $link = '';
    if (empty($link_id)) {
        return $link;
    }
    $link = M('Url')->getById($link_id);
    if (empty($field)) {
        return $link;
    } else {
        return $link[$field];
    }
}

/**
 * 获取文档封面图片
 *
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据 或者 指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = null)
{
    if (empty($cover_id)) {
        return false;
    }
    $picture = M('Picture')->where(array(
        'status' => 1
    ))->getById($cover_id);
    if ($field == 'path') {
        if (!empty($picture['url'])) {
            $picture['path'] = $picture['url'];
        } else {
            $picture['path'] = __ROOT__ . $picture['path'];
        }
    }
    return empty($field) ? $picture : $picture[$field];
}

/**
 * 可使用优惠券最低消费金额
 */
function get_fcoupon_lowpayment($code)
{
    $info = M('fcoupon')->where("code='$code'")->find();
    return $info['lowpayment'];
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 *
 * @param number $pos
 *            推荐位的值
 * @param number $contain
 *            指定推荐位
 * @return boolean true 包含 ， false 不包含
 * @author huajie <banhuajie@163.com>
 */
function check_document_position($pos = 0, $contain = 0)
{
    if (empty($pos) || empty($contain)) {
        return false;
    }

    // 将两个参数进行按位与运算，不为0则表示$contain属于$pos
    $res = $pos & $contain;
    if ($res !== 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取数据的所有子孙数据的id值
 *
 * @author 朱亚杰 <xcoolcc@gmail.com>
 */
function get_stemma($pids, Model &$model, $field = 'id')
{
    $collection = array();

    // 非空判断
    if (empty($pids)) {
        return $collection;
    }

    if (is_array($pids)) {
        $pids = trim(implode(',', $pids), ',');
    }
    $result = $model->field($field)
        ->where(array(
            'pid' => array(
                'IN',
                (string)$pids
            )
        ))
        ->select();
    $child_ids = array_column((array)$result, 'id');

    while (!empty($child_ids)) {
        $collection = array_merge($collection, $result);
        $result = $model->field($field)
            ->where(array(
                'pid' => array(
                    'IN',
                    $child_ids
                )
            ))
            ->select();
        $child_ids = array_column((array)$result, 'id');
    }
    return $collection;
}

/**
 * 验证分类是否允许发布内容
 *
 * @param integer $id
 *            分类ID
 * @return boolean true-允许发布内容，false-不允许发布内容
 */
function check_category($id)
{
    if (is_array($id)) {
        $type = get_category($id['category_id'], 'type');
        $type = explode(",", $type);
        return in_array($id['type'], $type);
    } else {
        $publish = get_category($id, 'allow_publish');
        return $publish ? true : false;
    }
}

/**
 * 检测分类是否绑定了指定模型
 *
 * @param array $info
 *            模型ID和分类ID数组
 * @return boolean true-绑定了模型，false-未绑定模型
 */
function check_category_model($info)
{
    $cate = get_category($info['category_id']);
    $array = explode(',', $info['pid'] ? $cate['model_sub'] : $cate['model']);
    return in_array($info['model_id'], $array);
}

/**
 * 邮件发送函数
 */
function sendMail($to, $title, $content)
{
    Vendor('PHPMailer.PHPMailer');
    $mail = new \vendor\PHPMailer\PHPMailer(); // 实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host = C('MAIL_HOST'); // smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = true; // 启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); // 你的邮箱名
    $mail->Password = C('MAIL_PASSWORD'); // 邮箱密码
    $mail->From = C('MAIL_FROM'); // 发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); // 发件人姓名
    $mail->AddAddress($to, "尊敬的客户");
    $mail->WordWrap = 50; // 设置每行字符长度
    $mail->IsHTML(TRUE); // 是否HTML格式邮件
    $mail->CharSet = 'utf-8'; // 设置邮件编码
    $mail->Subject = $title; // 邮件主题
    $mail->Body = $content; // 邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; // 邮件正文不支持HTML的备用显示
    return ($mail->Send());
}

/* 登录购物车处理函数 ,会员模型函数 */
function addintocart($uid)
{
    $table = M("shopcart");
    $cart = $_SESSION["cart"];
    foreach ($cart as $k => $val) {
        $id = $val["id"];
        $parameters = $val["parameters"];
        $sort = $val["sort"];
        $num = M("shopcart")->where("goodid='$id' and uid='$uid 'and parameters='$parameters'")->getField("num");
        if ($num) {
            $table->num = $val["num"] + $num;
            $table->where("goodid='$id' and uid='$uid 'and parameters='$parameters'")->save();
        } else {
            $table->goodid = $id;
            $table->price = $val["price"];
            $table->parameters = $parameters;
            $table->sort = $sort;
            $table->uid = $uid;
            $table->num = $val["num"];
            $table->add();
        }
    }
}

/* 记录登录历史信息 ,会员模型函数 */
function history($uid)
{
    $arr = get_ip_address();
    $data["uid"] = $uid;
    $data["login_ip"] = $arr->ip;
    $data["login_country"] = $arr->country;
    $data["login_province"] = $arr->region;
    $data["login_city"] = $arr->city;
    $data["login_isp"] = $arr->isp;
    $data["login_time"] = NOW_TIME;
    /* 登录方式 */
    $data["login_way"] = isMobil();
    $history = M("history");
    $history->create();
    $history->add($data);
}

/* 判断是电脑还是手机访问 */
function isMobil()
{
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';
    $mobile_os_list = array(
        'Google Wireless Transcoder',
        'Windows CE',
        'WindowsCE',
        'Symbian',
        'Android',
        'armv6l',
        'armv5',
        'Mobile',
        'CentOS',
        'mowser',
        'AvantGo',
        'Opera Mobi',
        'J2ME/MIDP',
        'Smartphone',
        'Go.Web',
        'Palm',
        'iPAQ'
    );
    $mobile_token_list = array(
        'Profile/MIDP',
        'Configuration/CLDC-',
        '160×160',
        '176×220',
        '240×240',
        '240×320',
        '320×240',
        'UP.Browser',
        'UP.Link',
        'SymbianOS',
        'PalmOS',
        'PocketPC',
        'SonyEricsson',
        'Nokia',
        'BlackBerry',
        'Vodafone',
        'BenQ',
        'Novarra-Vision',
        'Iris',
        'NetFront',
        'HTC_',
        'Xda_',
        'SAMSUNG-SGH',
        'Wapaka',
        'DoCoMo',
        'iPhone',
        'iPod'
    );
    $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) || CheckSubstrs($mobile_token_list, $useragent);
    if ($found_mobile) {
        $way = '手机登录'; // '手机登录'
    } else {
        $way = '电脑登录'; // '电脑登录'
    }
    return $way;
}

// 加密解密函数，函数encrypt($string,$operation,$key)
// 中$string：需要加密解密的字符串；$operation：判断是加密还是解密，E表示加密，D表示解密；$key：密匙。
// echo '加密:'.encrypt($str, 'E', $key); echo '解密：'.encrypt($str, 'D', $key);
function encrypt($string, $operation, $key = '')
{
    $key = C('DATA_AUTH_KEY');
    $key = md5($key);
    $key_length = strlen($key);
    $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'D') {
        if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
            return substr($result, 8);
        } else {
            return '';
        }
    } else {
        return str_replace('=', '', base64_encode($result));
    }
}

function CheckSubstrs($substrs, $text)
{
    foreach ($substrs as $substr) {

        if (false !== strpos($text, $substr)) {

            return true;
        }

        return false;
    }
}

//get获取返回值
function get($url, $param = array())
{
    if (!is_array($param)) {
        throw new Exception("参数必须为array");
    }
    $p = '';
    foreach ($param as $key => $value) {
        $p = $p . $key . '=' . $value . '&';
    }
    if (preg_match('/\?[\d\D]+/', $url)) {//matched ?c
        $p = '&' . $p;
    } else if (preg_match('/\?$/', $url)) {//matched ?$
        $p = $p;
    } else {
        $p = '?' . $p;
    }
    $p = preg_replace('/&$/', '', $p);
    $url = $url . $p;
    //echo $url;
    $httph = curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");

    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_HEADER, 1);
    $rst = curl_exec($httph);
    curl_close($httph);
    return $rst;
}

//批量发送短信接口
//$type=base 表示必须要用短信接口
//$type=senior 表示可用可不用短信接口
function smsend($tels, $content, $type = 'base')
{
    $param = array(
        "account" => config('sms_account'),
        "password" => config('sms_password'),
        "tels" => $tels,
        "content" => $content
    );
    if (empty($tels) || empty($content)) {
        return false;
    } else {
        $return_msg = get('http://sms.woliucloud.com/sms/Msg/send', $param);
        return $return_msg;
    }
}

/**
 * 根据数组key删除元素
 *
 * @param array $arr
 * @param key $key
 * @author 王琨
 */
function array_remove($arr, $key)
{
    if (!array_key_exists($key, $arr)) {
        return $arr;
    }
    $keys = array_keys($arr);
    $index = array_search($key, $keys);
    if ($index !== FALSE) {
        array_splice($arr, $index, 1);
    }
    return $arr;
}

// 不区分大小写的in_array实现
function in_array_case($value, $array)
{
    return in_array(strtolower($value), array_map('strtolower', $array));
}

//删除函数
function del($model, $id)
{
    if (!empty($model) && !empty($id)) {
        $result = db($model)->delete($id);
        if ($result) {
            return array('info' => '删除成功', 'status' => 1, 'target' => 'reload');
        } else {
            return array('info' => '删除失败', 'status' => -1);
        }
    }
}

function int_to_string(&$data, $map = array('status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')))
{
    if ($data === false || $data === null) {
        return $data;
    }
    $data = (array)$data;
    foreach ($data as $key => $row) {
        foreach ($map as $col => $pair) {
            if (isset($row[$col]) && isset($pair[$row[$col]])) {
                $data[$key][$col . '_text'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 */
function think_admin_md5($str, $key = 'ThinkUCenter')
{
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/*
 * 发送模板消息
 */
function templatesend($touser, $template_id, $url2, $data2)
{
    $accessToken = getAccessToken();
    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $accessToken;
    $data = array(
        'touser' => $touser,
        'template_id' => $template_id,
        'url' => $url2,
        'data' => $data2
    );
    $data = json_encode($data);
    $res = curlHelp($url, urldecode($data));

    $res = json_decode($res, true);
    return $res;
}

//本地读取token
function getAccessToken()
{
    $webroot = $_SERVER['DOCUMENT_ROOT'];

    //去微信获取，然后保存
    $token_path = $webroot . '/WxToken';

    if (!is_dir($token_path)) {
        mkdir($token_path, 0777, 1);
    }

    $token_save_file = $token_path . '/token.json';

    if (file_exists($token_save_file)) {     //如果Token文件存在
        $get_local_token = file_get_contents($token_save_file);
        $token_array = json_decode($get_local_token, true);
        //判断本地的weixin_token是否存在
        if (!is_array($token_array) || !isset($token_array['get_token_time'])) {
            //去微信获取，然后保存
            $token_array = getATRemote($token_save_file);
        } else {
            //判断 当前时间 减去 本地获取微信token的时间 大于7000秒 ,就要重新获取
            if (time() - $token_array['get_token_time'] > 7000) {
                $token_array = getATRemote($token_save_file);
            }
        }
    } else {
        $token_array = getATRemote($token_save_file);
    }

    return $token_array['access_token'];
}

//远程读取token
function getATRemote($token_save_file)
{
    $APPID = config('WEIXIN_APPID');
    $SECRET = config('WEIXIN_APPSECRET');

    $url = sprintf("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s", $APPID, $SECRET);

    $res = curlHelp($url);

    $TOKEN_json = json_decode($res, true);
    $TOKEN_json['get_token_time'] = time();

    file_put_contents($token_save_file, json_encode($TOKEN_json));
    return $TOKEN_json;
}

//远程抓取文件
function curlHelp($url, $post_data = '')
{
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if (isset($post_data)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
/**
 * @param $len需要生成的指定长度
 * @return int
 */
function rand_num($len)
{
    $min = pow(10, $len - 1);
    $max = pow(10, $len);
    $num = rand($min, $max);
    return $num;
}

function check_phone($phone)
{
    //验证手机号
    $phoneMatch = "/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/";
    if (!preg_match($phoneMatch, $phone)) {
        return false;
    } else {
        return true;
    }
}