/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : 127.0.0.1:3306
 Source Schema         : wj_yaoyao

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 17/04/2019 17:29:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yy_academy
-- ----------------------------
DROP TABLE IF EXISTS `yy_academy`;
CREATE TABLE `yy_academy`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学院名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '学院表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yy_academy
-- ----------------------------
INSERT INTO `yy_academy` VALUES (1, '机械工程学院');
INSERT INTO `yy_academy` VALUES (2, '电子信息学院');
INSERT INTO `yy_academy` VALUES (3, '通信工程学院');
INSERT INTO `yy_academy` VALUES (4, '自动化学院');
INSERT INTO `yy_academy` VALUES (5, '计算机学院');
INSERT INTO `yy_academy` VALUES (6, '材料与环境工程学院');
INSERT INTO `yy_academy` VALUES (7, '生命信息与仪器工程学院');
INSERT INTO `yy_academy` VALUES (8, '软件工程学院');
INSERT INTO `yy_academy` VALUES (9, '理学院');
INSERT INTO `yy_academy` VALUES (10, '经济学院');
INSERT INTO `yy_academy` VALUES (11, '管理学院');
INSERT INTO `yy_academy` VALUES (12, '数字媒体和艺术设计学院');
INSERT INTO `yy_academy` VALUES (13, '人文与法学院');
INSERT INTO `yy_academy` VALUES (14, '会计学院');
INSERT INTO `yy_academy` VALUES (15, '外国语学院');
INSERT INTO `yy_academy` VALUES (16, '卓越学院');
INSERT INTO `yy_academy` VALUES (17, '网络空间安全学院');
INSERT INTO `yy_academy` VALUES (18, '马克思主义学院');
INSERT INTO `yy_academy` VALUES (19, '信息工程学院');
INSERT INTO `yy_academy` VALUES (20, '浙江保密学院');
INSERT INTO `yy_academy` VALUES (21, '体育与艺术教学部');
INSERT INTO `yy_academy` VALUES (22, '继续教育学院');
INSERT INTO `yy_academy` VALUES (23, '国际教育学院');

-- ----------------------------
-- Table structure for yy_admin
-- ----------------------------
DROP TABLE IF EXISTS `yy_admin`;
CREATE TABLE `yy_admin`  (
  `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机',
  `last_login_time` datetime NULL DEFAULT NULL,
  `last_login_ip` bigint(20) NULL DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '管理员状态,1启用2禁用',
  `create_time` datetime NULL DEFAULT NULL COMMENT '添加时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`admin_id`) USING BTREE,
  UNIQUE INDEX `phone`(`phone`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yy_admin
-- ----------------------------
INSERT INTO `yy_admin` VALUES (61, 'admin', '4e3988009121972e1b919354e26a1f10', '13758248144', '2019-04-10 21:50:39', 2130706433, 1, '2017-11-27 17:29:39', '2019-04-10 21:50:39');

-- ----------------------------
-- Table structure for yy_config
-- ----------------------------
DROP TABLE IF EXISTS `yy_config`;
CREATE TABLE `yy_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置类型',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置分组',
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置值',
  `sort` smallint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_name`(`name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `group`(`group`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 87 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yy_config
-- ----------------------------
INSERT INTO `yy_config` VALUES (1, 'WEB_SITE_TITLE', 1, '网站标题', 1, '', '网站标题前台显示标题', 1378898976, 1379235274, 1, '杭电Umis', 0);
INSERT INTO `yy_config` VALUES (2, 'WEB_SITE_DESCRIPTION', 2, '网站描述', 1, '', '网站搜索引擎描述', 1378898976, 1379235841, 1, '', 1);
INSERT INTO `yy_config` VALUES (3, 'WEB_SITE_KEYWORD', 2, '网站关键字', 1, '', '网站搜索引擎关键字', 1378898976, 1381390100, 1, '', 8);
INSERT INTO `yy_config` VALUES (82, 'AUTO_SEND', 4, '是否自动发货', 6, '0:关闭自动发货\r\n1:开启自动发货\r\n', '是否开启自动发货', 1443118977, 1443119078, 1, '1', 0);
INSERT INTO `yy_config` VALUES (4, 'WEB_SITE_CLOSE', 4, '关闭站点', 1, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1379235296, 1, '1', 1);
INSERT INTO `yy_config` VALUES (9, 'CONFIG_TYPE_LIST', 3, '配置类型列表', 4, '', '主要用于数据解析和页面表单的生成', 1378898976, 1379235348, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', 2);
INSERT INTO `yy_config` VALUES (10, 'WEB_SITE_ICP', 1, '网站备案号', 1, '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '', 9);
INSERT INTO `yy_config` VALUES (11, 'DOCUMENT_POSITION', 3, '文档推荐位', 2, '', '文档推荐位，推荐到多个位置KEY值相加即可', 1379053380, 1379235329, 1, '1:列表推荐\r\n2:频道推荐\r\n4:首页推荐', 3);
INSERT INTO `yy_config` VALUES (12, 'DOCUMENT_DISPLAY', 3, '文档可见性', 2, '', '文章可见性仅影响前台显示，后台不收影响', 1379056370, 1379235322, 1, '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', 4);
INSERT INTO `yy_config` VALUES (13, 'COLOR_STYLE', 4, '后台色系', 1, 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', 1379122533, 1379235904, 1, 'default_color', 10);
INSERT INTO `yy_config` VALUES (20, 'CONFIG_GROUP_LIST', 3, '配置分组', 4, '', '配置分组', 1379228036, 1481275778, 1, '1:基本\r\n2:内容\r\n3:用户\r\n4:系统\r\n5:费用\r\n6:商城\r\n7:接口\r\n9:短信', 4);
INSERT INTO `yy_config` VALUES (21, 'HOOKS_TYPE', 3, '钩子的类型', 4, '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', 1379313397, 1379313407, 1, '1:视图\r\n2:控制器', 6);
INSERT INTO `yy_config` VALUES (22, 'AUTH_CONFIG', 3, 'Auth配置', 4, '', '自定义Auth.class.php类配置', 1379409310, 1379409564, 1, 'AUTH_ON:1\r\nAUTH_TYPE:2', 8);
INSERT INTO `yy_config` VALUES (23, 'OPEN_DRAFTBOX', 4, '是否开启草稿功能', 2, '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', 1379484332, 1379484591, 1, '0', 1);
INSERT INTO `yy_config` VALUES (24, 'DRAFT_AOTOSAVE_INTERVAL', 0, '自动保存草稿时间', 2, '', '自动保存草稿的时间间隔，单位：秒', 1379484574, 1386143323, 1, '60', 2);
INSERT INTO `yy_config` VALUES (25, 'LIST_ROWS', 0, '后台每页记录数', 2, '', '后台数据每页显示记录数', 1379503896, 1380427745, 1, '10', 10);
INSERT INTO `yy_config` VALUES (26, 'USER_ALLOW_REGISTER', 4, '是否允许用户注册', 3, '0:关闭注册\r\n1:允许注册', '是否开放用户注册', 1379504487, 1379504580, 1, '1', 3);
INSERT INTO `yy_config` VALUES (27, 'CODEMIRROR_THEME', 4, '预览插件的CodeMirror主题', 4, '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', 1379814385, 1384740813, 1, 'ambiance', 3);
INSERT INTO `yy_config` VALUES (28, 'DATA_BACKUP_PATH', 1, '数据库备份根路径', 4, '', '路径必须以 / 结尾', 1381482411, 1381482411, 1, './Data/', 5);
INSERT INTO `yy_config` VALUES (29, 'DATA_BACKUP_PART_SIZE', 0, '数据库备份卷大小', 4, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1381729564, 1, '20971520', 7);
INSERT INTO `yy_config` VALUES (30, 'DATA_BACKUP_COMPRESS', 4, '数据库备份文件是否启用压缩', 4, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1381729544, 1, '1', 9);
INSERT INTO `yy_config` VALUES (31, 'DATA_BACKUP_COMPRESS_LEVEL', 4, '数据库备份文件压缩级别', 4, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1381713408, 1, '9', 10);
INSERT INTO `yy_config` VALUES (32, 'DEVELOP_MODE', 4, '开启开发者模式', 4, '0:关闭\r\n1:开启', '是否开启开发者模式', 1383105995, 1383291877, 1, '1', 11);
INSERT INTO `yy_config` VALUES (33, 'ALLOW_VISIT', 3, '不受限控制器方法', 0, '', '', 1386644047, 1386644741, 1, 'AuthManager/updatePassword;AuthManager/submitPassword;Department/getinfo;Sample/addtype;Sample/deltype;Sample/addSampleTag;Sample/delTag;Sample/addProp;Sample/delProp;Store/addStore;Store/editStore;Store/delStore;Store/addArea;Sample/editArea;Store/delArea;Store/addShelf;Store/editShelf;Store/delShelf;Store/addShelfLayer;Store/editShelfLayer;Store/delShelfLayer;Store/printCode', 0);
INSERT INTO `yy_config` VALUES (34, 'DENY_VISIT', 3, '超管专限控制器方法', 0, '', '仅超级管理员可访问的控制器方法', 1386644141, 1386644659, 1, '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', 0);
INSERT INTO `yy_config` VALUES (35, 'REPLY_LIST_ROWS', 0, '回复列表每页条数', 2, '', '', 1386645376, 1387178083, 1, '10', 0);
INSERT INTO `yy_config` VALUES (36, 'ADMIN_ALLOW_IP', 2, '后台允许访问IP', 4, '', '多个用逗号分隔，如果不配置表示不限制IP访问', 1387165454, 1387165553, 1, '', 12);
INSERT INTO `yy_config` VALUES (37, 'SHOW_PAGE_TRACE', 4, '是否显示页面Trace', 4, '0:关闭\r\n1:开启', '是否显示页面Trace信息', 1387165685, 1387165685, 1, '0', 1);
INSERT INTO `yy_config` VALUES (40, 'HOTSEARCH', 1, '热词', 1, '', '热门搜索词，必须逗号隔开', 1413221018, 1414964609, 1, '', 0);
INSERT INTO `yy_config` VALUES (41, 'SHIPMONEY', 0, '运费', 5, '', '低于一定金额的运费', 1414001070, 1414001482, 1, '10', 0);
INSERT INTO `yy_config` VALUES (42, 'LOWWEST', 0, '最低消费金额', 5, '', '用户最低消费的金额，低于该金额，则增加运费', 1414001165, 1414001495, 1, '50', 0);
INSERT INTO `yy_config` VALUES (43, 'RATIO', 0, '积分现金兑换比', 5, '', '1000表示1000积分可兑换成1元', 1414153401, 1414153401, 1, '1000', 0);
INSERT INTO `yy_config` VALUES (44, 'DEADTIME', 0, '退货有效期', 5, '', '从订单签收完成多少天内可以退货', 1414164561, 1414164642, 1, '7', 0);
INSERT INTO `yy_config` VALUES (45, 'CHANGETIME', 1, '换货期', 5, '', '订单签收多少天内后可以换货', 1414164627, 1414164654, 1, '15', 0);
INSERT INTO `yy_config` VALUES (46, 'QQ', 1, 'QQ客服', 6, '', '网站客服的qq代码', 1414183635, 1414664781, 1, '', 0);
INSERT INTO `yy_config` VALUES (47, 'ALWW', 0, '阿里旺旺号', 6, '', '网站阿里旺旺客服', 1414183716, 1414664772, 1, '', 0);
INSERT INTO `yy_config` VALUES (48, 'IP_TONGJI', 4, '开启ip访问统计', 3, '0:关闭,1:开启', '开启后追踪用户的访问页面，访问明细，访问地域', 1414244159, 1414244270, 1, '1', 0);
INSERT INTO `yy_config` VALUES (49, 'LAG', 0, '统计时间间隔（小时）', 3, '', '重复访问的会员每隔多少时间统计，小于这一时间，不统计', 1414258358, 1414407504, 1, '6', 21);
INSERT INTO `yy_config` VALUES (50, 'DOMAIN', 1, '网站域名', 1, '', '网站域名', 1414504643, 1414504839, 1, 'http://localhost/jiehuolou/wg', 0);
INSERT INTO `yy_config` VALUES (51, '100KEY', 1, '0-快递100查询key', 7, '', '申请地址：http://www.kuaidi100.com/openapi/applyapi.shtml，用于查询运单号', 1414664721, 1415473565, 1, '', 0);
INSERT INTO `yy_config` VALUES (52, 'ADDRESS', 1, '发货地址', 6, '', '卖家的发货地址', 1414664871, 1414664871, 1, '', 0);
INSERT INTO `yy_config` VALUES (53, 'CONTACT', 1, '联系电话', 6, '', '卖家的联系方式', 1414664911, 1414664911, 1, '', 0);
INSERT INTO `yy_config` VALUES (54, 'SHOPNAME', 1, '卖家名称', 6, '', '卖家发货时填写的昵称', 1414665008, 1414665008, 1, '', 0);
INSERT INTO `yy_config` VALUES (55, 'SITENAME', 1, '网站名称', 1, '', '用于网站支付时显示，如土豆网', 1414761363, 1414761398, 1, '杭电Umis', 0);
INSERT INTO `yy_config` VALUES (56, 'ALIPAYPARTNER', 1, '1-支付宝商户号', 7, '', '这里是你在成功申请支付宝接口后获取到的PID', 1414769351, 1415137270, 1, '', 0);
INSERT INTO `yy_config` VALUES (57, 'ALIPAYKEY', 1, '支付宝密钥', 7, '', '这里是你在成功申请支付宝接口后获取到的Key', 1414769402, 1414769402, 1, '', 0);
INSERT INTO `yy_config` VALUES (67, 'TENPAYPARTNER', 1, '2-财付通合作者ID', 7, '', '合作者ID，财付通有该配置，开通财付通账户后给予', 1415472468, 1415473488, 1, '', 1);
INSERT INTO `yy_config` VALUES (66, 'TENPAYKEY', 1, '财付通加密key', 7, '', '加密key，开通财付通账户后给予', 1415472288, 1415473499, 1, '', 2);
INSERT INTO `yy_config` VALUES (68, 'PALPAYPARTNER', 1, '3-贝宝账号', 7, '', '合作者ID，贝宝有该配置，开通贝宝账户后给予不需密码', 1415472655, 1415473914, 1, '', 3);
INSERT INTO `yy_config` VALUES (69, 'YEEPAYPARTNER', 1, '4-易付宝合作者id', 7, '', '易付宝给予的合作者id', 1415472817, 1415473522, 1, '', 4);
INSERT INTO `yy_config` VALUES (64, 'ALIPAYEMAIL', 1, '支付宝收款账号', 7, '', '支付宝收款账号邮箱', 1415472043, 1415472347, 1, '', 0);
INSERT INTO `yy_config` VALUES (70, 'YEEPAYKEY', 1, '易付宝密钥', 7, '', '易付宝合作者的密钥', 1415473084, 1415473533, 1, '', 5);
INSERT INTO `yy_config` VALUES (71, 'KUAIQIANPARTNER', 1, '5-快钱合作者id', 7, '', '合作者ID，快钱有该配置，开通财付通账户后给予', 1415473241, 1415473540, 1, '', 5);
INSERT INTO `yy_config` VALUES (72, 'KUAIQIANKEY', 1, '快钱key', 7, '', '加密key，开通快钱账户后给予', 1415473293, 1415473553, 1, '', 5);
INSERT INTO `yy_config` VALUES (73, 'UNIONPARTNER', 1, '6-银联合作者账号', 7, '', '合作者ID，银联有该配置，开通银联账户后给予', 1415473364, 1415473424, 1, '', 6);
INSERT INTO `yy_config` VALUES (74, 'UNIONKEY', 1, '银联key', 7, '', ' 加密key，开通银联账户后给予', 1415473475, 1415473475, 1, '', 6);
INSERT INTO `yy_config` VALUES (75, 'SCODE', 1, '授权码', 1, '', ' 商城的授权码', 1415473475, 1415473475, 1, '', 6);
INSERT INTO `yy_config` VALUES (76, 'MAIL_HOST', 1, 'smtp服务器的名称', 8, '', ' smtp服务器的名称，默认QQ', 1415473475, 1415473475, 1, '', 6);
INSERT INTO `yy_config` VALUES (77, 'MAIL_USERNAME', 1, '邮箱用户名', 8, '', ' 邮箱用户名', 1415473475, 1415473475, 1, '', 6);
INSERT INTO `yy_config` VALUES (78, 'MAIL_FROMNAME', 1, '发件人姓名', 8, '', ' 商城网站名称，如yershop商城，默认QQ', 1415473475, 1415473475, 1, '', 6);
INSERT INTO `yy_config` VALUES (79, 'MAIL_PASSWORD', 1, '邮箱密码', 8, '', ' 配置的邮箱密码', 1415473475, 1415473475, 1, '', 6);
INSERT INTO `yy_config` VALUES (80, 'SMSACCOUNT', 1, '1-互亿账号', 9, '', '申请地址：http://www.ihuyi.com/product.php', 1426339726, 1426340118, 1, '', 15);
INSERT INTO `yy_config` VALUES (81, 'SMSPASSWORD', 1, '互亿密码', 9, '', '互亿提供的密码', 1426339942, 1426340130, 1, '', 16);
INSERT INTO `yy_config` VALUES (83, 'remind', 0, '考核提醒消息设置', 0, '[{\"status\":4,\"question\":\"1\"}]', '', 1513233710, 1517972461, 0, '{\"remind\":[\"1\",\"4\"]}', 0);
INSERT INTO `yy_config` VALUES (84, 'wecha', 0, '公众号绑定配置', 0, '', '微信appid和appsecret', 1513315191, 1513853573, 0, '{\"appid\":\"wx1dc559ccb231600d\",\"secret\":\"c12293bf6cfb1d0ad60e6b0b67e10c75\"}', 0);
INSERT INTO `yy_config` VALUES (86, 'phone', 0, '短信通道配置', 0, '', '短信接口用户名和密码', 1513850461, 1513852882, 0, '{\"phone\":\"wltest\",\"key\":\"12345678\"}', 0);

-- ----------------------------
-- Table structure for yy_email_code
-- ----------------------------
DROP TABLE IF EXISTS `yy_email_code`;
CREATE TABLE `yy_email_code`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` int(6) NOT NULL,
  `expiration_time` datetime NOT NULL COMMENT '过期时间',
  `create_time` datetime NOT NULL,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '邮件验证码表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yy_email_code
-- ----------------------------
INSERT INTO `yy_email_code` VALUES (1, '693021325@qq.com', 847840, '2019-04-15 23:13:20', '2019-04-14 18:33:26', '2019-04-15 23:03:22');

-- ----------------------------
-- Table structure for yy_invitation
-- ----------------------------
DROP TABLE IF EXISTS `yy_invitation`;
CREATE TABLE `yy_invitation`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '发布人',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态1.正常发布 2.删除',
  `type` tinyint(1) NOT NULL COMMENT '1.考研考公 2.体育运动 3.社团活动 4.课外休闲',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `info` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `site` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '地址',
  `time` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '时间',
  `user_num` int(5) NULL DEFAULT NULL COMMENT '要求人数',
  `sign_up_num` int(5) NOT NULL DEFAULT 0 COMMENT '报名人数',
  `create_time` datetime NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邀请人名称',
  `identity` tinyint(1) NULL DEFAULT NULL COMMENT '身份(1学生2教师)',
  `academy_id` int(11) NULL DEFAULT NULL COMMENT '所属学院',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '邀请帖子表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yy_invitation
-- ----------------------------
INSERT INTO `yy_invitation` VALUES (2, 1, 1, 1, '测试邀请', '约图书馆', '杭电图书馆', '4.19上午6点', 2, 1, '2019-04-17 16:47:07', 'Allen', 1, 1);
INSERT INTO `yy_invitation` VALUES (3, 1, 1, 1, '测试邀请1', '约图书馆', '杭电图书馆', '4.19上午6点', 2, 1, '2019-04-17 17:19:22', 'Allen', 1, 1);

-- ----------------------------
-- Table structure for yy_manager
-- ----------------------------
DROP TABLE IF EXISTS `yy_manager`;
CREATE TABLE `yy_manager`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '管理员姓名',
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '管理员手机',
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '辅导员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yy_manager
-- ----------------------------
INSERT INTO `yy_manager` VALUES (4, 61, '超级管理员', '13706710032', '2017-11-27 17:29:39', '2017-11-27 18:05:36');
INSERT INTO `yy_manager` VALUES (5, 223, '章天星', '15957132335', '2017-12-12 19:25:10', '2017-12-12 19:25:10');
INSERT INTO `yy_manager` VALUES (8, 226, '章天星', '15936065305', '2017-12-13 15:30:44', '2017-12-13 15:30:44');
INSERT INTO `yy_manager` VALUES (10, 228, '测试管理员1', '13436065305', '2017-12-14 14:29:59', '2017-12-14 14:29:59');
INSERT INTO `yy_manager` VALUES (11, 232, '测试管理员2', '13557132335', '2017-12-14 14:58:42', '2017-12-14 14:58:42');
INSERT INTO `yy_manager` VALUES (13, 234, '测试管理员3', '18712345678', '2017-12-18 10:17:35', '2017-12-18 10:17:35');

-- ----------------------------
-- Table structure for yy_menu
-- ----------------------------
DROP TABLE IF EXISTS `yy_menu`;
CREATE TABLE `yy_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分类ID',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序（同级有效）',
  `url` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否隐藏',
  `tip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态',
  `class_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'css类名称',
  `module_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'admin' COMMENT '模块名',
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 204 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '菜单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yy_menu
-- ----------------------------
INSERT INTO `yy_menu` VALUES (1, '系统', '一级菜单-系统', 0, 2, '', 0, '系统', '', 0, 0, 'sun outline icon', 'admin', '2019-04-07 00:49:02', '2019-04-10 21:48:56', NULL);
INSERT INTO `yy_menu` VALUES (5, '菜单', '侧边栏-菜单', 1, 1, 'menu/index', 0, '菜单列表', '', 0, 0, '', 'admin', '2017-11-27 14:52:58', '2017-12-21 11:29:13', NULL);
INSERT INTO `yy_menu` VALUES (6, '新增菜单页', '操作-新增菜单页', 1, 2, 'menu/create', 1, '新增菜单页', '', 0, 0, NULL, 'admin', '2017-11-27 15:00:27', '2017-11-27 15:11:21', NULL);
INSERT INTO `yy_menu` VALUES (7, '新增菜单确认', '操作-新增菜单确认', 1, 3, 'menu/save', 1, '新增菜单确认', '', 0, 0, NULL, 'admin', '2017-11-27 15:01:24', '2017-11-27 15:11:24', NULL);
INSERT INTO `yy_menu` VALUES (8, '编辑菜单确认', '操作-编辑菜单确认', 1, 4, 'menu/update', 1, '编辑菜单确认', '', 0, 0, NULL, 'admin', '2017-11-27 15:01:59', '2017-11-27 15:11:29', NULL);
INSERT INTO `yy_menu` VALUES (9, '编辑菜单', '操作-编辑菜单', 1, 5, 'menu/edit', 1, '编辑菜单页', '', 0, 0, NULL, 'admin', '2017-11-27 15:02:19', '2017-11-27 15:11:32', NULL);
INSERT INTO `yy_menu` VALUES (11, '删除菜单', '操作-删除菜单', 1, 6, 'menu/delete', 1, '删除菜单按钮', '', 0, 0, NULL, 'admin', '2017-11-27 15:11:01', '2017-11-27 15:11:01', NULL);
INSERT INTO `yy_menu` VALUES (12, '角色', '侧边栏-角色', 1, 2, 'role/index', 0, '角色列表', '', 0, 0, NULL, 'admin', '2017-11-27 15:12:45', '2017-11-27 15:12:45', NULL);
INSERT INTO `yy_menu` VALUES (13, '新增角色', '操作-新增角色', 1, 2, 'role/create', 1, '新增角色页', '', 0, 0, NULL, 'admin', '2017-11-27 15:13:23', '2017-11-27 15:13:23', NULL);
INSERT INTO `yy_menu` VALUES (14, '新增角色处理', '操作-新增角色处理', 1, 2, 'role/save', 1, '新增角色处理', '', 0, 0, NULL, 'admin', '2017-11-27 15:13:42', '2017-11-27 15:13:42', NULL);
INSERT INTO `yy_menu` VALUES (15, '编辑角色处理', '操作-编辑角色处理', 1, 2, 'role/update', 1, '编辑角色处理', '', 0, 0, NULL, 'admin', '2017-11-27 15:14:05', '2017-11-27 15:14:05', NULL);
INSERT INTO `yy_menu` VALUES (16, '编辑角色', '操作-编辑角色', 1, 2, 'role/edit', 1, '编辑角色页', '', 0, 0, NULL, 'admin', '2017-11-27 15:14:25', '2017-11-27 15:14:25', NULL);
INSERT INTO `yy_menu` VALUES (17, '管理员', '侧边栏-管理员', 1, 3, 'manager/index', 0, '管理员列表', '', 0, 0, NULL, 'admin', '2017-11-27 15:15:49', '2017-11-27 15:15:49', NULL);
INSERT INTO `yy_menu` VALUES (18, '编辑管理员', '操作-编辑管理员', 1, 3, 'manager/edit', 1, '编辑管理员', '', 0, 0, NULL, 'admin', '2017-11-27 15:16:13', '2017-11-27 15:16:13', NULL);
INSERT INTO `yy_menu` VALUES (19, '编辑管理员处理', '操作-编辑管理员处理', 1, 3, 'manager/update', 1, '编辑管理员处理', '', 0, 0, NULL, 'admin', '2017-11-27 15:16:33', '2017-11-27 15:16:33', NULL);
INSERT INTO `yy_menu` VALUES (20, '新增管理员处理', '操作-新增管理员处理', 1, 3, 'manager/save', 1, '新增管理员处理', '', 0, 0, NULL, 'admin', '2017-11-27 15:17:12', '2017-11-27 15:17:12', NULL);
INSERT INTO `yy_menu` VALUES (21, '新增管理员', '操作-新增管理员', 1, 3, 'manager/create', 1, '新增管理员', '', 0, 0, NULL, 'admin', '2017-11-27 15:17:35', '2017-11-27 15:17:35', NULL);
INSERT INTO `yy_menu` VALUES (22, '角色', '操作-角色', 1, 3, 'role/access', 1, '角色授权页', '', 0, 0, NULL, 'admin', '2017-11-28 14:59:04', '2017-11-28 14:59:04', NULL);
INSERT INTO `yy_menu` VALUES (171, '首页', '一级菜单-首页', 0, 1, '', 0, '首页', '', 0, 0, 'home icon', 'admin', '2017-12-21 11:30:37', '2017-12-21 11:30:37', NULL);
INSERT INTO `yy_menu` VALUES (169, '修改密码', '侧边栏-修改密码', 1, 4, 'admin/pass', 0, '修改密码页', '', 0, 0, '', 'admin', '2017-12-19 14:03:02', '2017-12-19 14:03:02', NULL);
INSERT INTO `yy_menu` VALUES (170, '修改密码确认操作', '操作-修改密码确认操作', 1, 5, 'admin/passsave', 1, '修改密码确认操作', '', 0, 0, '', 'admin', '2017-12-19 14:03:51', '2017-12-19 14:03:51', NULL);
INSERT INTO `yy_menu` VALUES (151, '公众号绑定配置', '侧边栏-公众号绑定配置', 109, 3, 'configure/index', 0, '公众号微信配置', '', 0, 0, '', 'admin', '2017-12-11 14:50:57', '2017-12-15 18:02:04', NULL);
INSERT INTO `yy_menu` VALUES (152, '新增公众号绑定配置', '操作-新增公众号绑定配置', 109, 0, 'configure/create', 1, '新增公众号绑定配置页', '', 0, 0, NULL, 'admin', '2017-12-11 14:50:57', '2017-12-11 14:50:57', NULL);
INSERT INTO `yy_menu` VALUES (153, '新增公众号绑定配置提交', '操作-新增公众号绑定配置提交', 109, 0, 'configure/save', 1, '新增公众号绑定配置提交', '', 0, 0, NULL, 'admin', '2017-12-11 14:50:57', '2017-12-11 14:50:57', NULL);
INSERT INTO `yy_menu` VALUES (154, '编辑公众号绑定配置', '操作-编辑公众号绑定配置', 109, 0, 'configure/edit', 1, '编辑公众号绑定配置页', '', 0, 0, NULL, 'admin', '2017-12-11 14:50:57', '2017-12-11 14:50:57', NULL);
INSERT INTO `yy_menu` VALUES (155, '编辑公众号绑定配置提交', '操作-编辑公众号绑定配置提交', 109, 0, 'configure/update', 1, '编辑公众号绑定配置提交', '', 0, 0, NULL, 'admin', '2017-12-11 14:50:57', '2017-12-11 14:50:57', NULL);
INSERT INTO `yy_menu` VALUES (156, '公众号绑定配置详情', '操作-公众号绑定配置详情', 109, 0, 'configure/read', 1, '公众号绑定配置详情页', '', 0, 0, NULL, 'admin', '2017-12-11 14:50:57', '2017-12-11 14:50:57', NULL);
INSERT INTO `yy_menu` VALUES (157, '删除公众号绑定配置', '操作-删除公众号绑定配置', 109, 0, 'configure/delete', 1, '删除公众号绑定配置页', '', 0, 0, NULL, 'admin', '2017-12-11 14:50:57', '2017-12-11 14:50:57', NULL);
INSERT INTO `yy_menu` VALUES (158, '短信通道配置', '侧边栏-短信通道配置', 109, 4, 'configure/phone', 0, '短信通道配置', '', 0, 0, '', 'admin', '2017-12-11 16:47:30', '2017-12-15 18:02:08', NULL);
INSERT INTO `yy_menu` VALUES (166, '考核提醒消息设置', '侧边栏-考核提醒消息设置', 109, 5, 'configure/remind', 0, '考核提醒消息设置', '', 0, 0, '', 'admin', '2017-12-11 17:17:14', '2017-12-15 18:02:15', NULL);
INSERT INTO `yy_menu` VALUES (195, '用户管理', '一级菜单-用户管理', 0, 3, '', 0, '用户管理', '', 0, 0, 'user icon', 'admin', '2019-04-06 23:06:55', '2019-04-10 21:50:28', NULL);
INSERT INTO `yy_menu` VALUES (196, '用户列表', '侧边栏-用户列表', 195, 1, 'user/index', 0, '用户列表', '', 0, 0, '', 'admin', '2019-04-06 23:18:47', '2019-04-06 23:18:47', NULL);
INSERT INTO `yy_menu` VALUES (197, '新增用户列表', '操作-新增用户列表', 195, 0, 'user/create', 1, '新增用户列表页', '', 0, 0, NULL, 'admin', '2019-04-06 23:18:47', '2019-04-06 23:18:47', NULL);
INSERT INTO `yy_menu` VALUES (198, '新增用户列表提交', '操作-新增用户列表提交', 195, 0, 'user/save', 1, '新增用户列表提交', '', 0, 0, NULL, 'admin', '2019-04-06 23:18:47', '2019-04-06 23:18:47', NULL);
INSERT INTO `yy_menu` VALUES (199, '编辑用户列表', '操作-编辑用户列表', 195, 0, 'user/edit', 1, '编辑用户列表页', '', 0, 0, NULL, 'admin', '2019-04-06 23:18:47', '2019-04-06 23:18:47', NULL);
INSERT INTO `yy_menu` VALUES (200, '编辑用户列表提交', '操作-编辑用户列表提交', 195, 0, 'user/update', 1, '编辑用户列表提交', '', 0, 0, NULL, 'admin', '2019-04-06 23:18:47', '2019-04-06 23:18:47', NULL);
INSERT INTO `yy_menu` VALUES (201, '用户列表详情', '操作-用户列表详情', 195, 0, 'user/read', 1, '用户列表详情页', '', 0, 0, NULL, 'admin', '2019-04-06 23:18:47', '2019-04-06 23:18:47', NULL);
INSERT INTO `yy_menu` VALUES (203, '用户禁用', '操作-用户禁用', 195, 2, 'user/disabled', 1, '用户禁用', '', 0, 0, '', 'admin', '2019-04-07 00:46:01', '2019-04-07 00:46:01', NULL);

-- ----------------------------
-- Table structure for yy_menu_role
-- ----------------------------
DROP TABLE IF EXISTS `yy_menu_role`;
CREATE TABLE `yy_menu_role`  (
  `menu_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yy_menu_role
-- ----------------------------
INSERT INTO `yy_menu_role` VALUES (1, 17);
INSERT INTO `yy_menu_role` VALUES (5, 17);
INSERT INTO `yy_menu_role` VALUES (6, 17);
INSERT INTO `yy_menu_role` VALUES (7, 17);
INSERT INTO `yy_menu_role` VALUES (8, 17);
INSERT INTO `yy_menu_role` VALUES (9, 17);
INSERT INTO `yy_menu_role` VALUES (11, 17);
INSERT INTO `yy_menu_role` VALUES (12, 17);
INSERT INTO `yy_menu_role` VALUES (13, 17);
INSERT INTO `yy_menu_role` VALUES (14, 17);
INSERT INTO `yy_menu_role` VALUES (15, 17);
INSERT INTO `yy_menu_role` VALUES (16, 17);
INSERT INTO `yy_menu_role` VALUES (17, 17);
INSERT INTO `yy_menu_role` VALUES (18, 17);
INSERT INTO `yy_menu_role` VALUES (19, 17);
INSERT INTO `yy_menu_role` VALUES (20, 17);
INSERT INTO `yy_menu_role` VALUES (21, 17);
INSERT INTO `yy_menu_role` VALUES (22, 17);
INSERT INTO `yy_menu_role` VALUES (54, 17);
INSERT INTO `yy_menu_role` VALUES (61, 17);
INSERT INTO `yy_menu_role` VALUES (62, 17);
INSERT INTO `yy_menu_role` VALUES (60, 17);
INSERT INTO `yy_menu_role` VALUES (64, 17);
INSERT INTO `yy_menu_role` VALUES (58, 17);
INSERT INTO `yy_menu_role` VALUES (59, 17);
INSERT INTO `yy_menu_role` VALUES (56, 17);
INSERT INTO `yy_menu_role` VALUES (63, 17);
INSERT INTO `yy_menu_role` VALUES (65, 17);
INSERT INTO `yy_menu_role` VALUES (66, 17);
INSERT INTO `yy_menu_role` VALUES (67, 17);
INSERT INTO `yy_menu_role` VALUES (68, 17);
INSERT INTO `yy_menu_role` VALUES (69, 17);
INSERT INTO `yy_menu_role` VALUES (70, 17);
INSERT INTO `yy_menu_role` VALUES (71, 17);
INSERT INTO `yy_menu_role` VALUES (72, 17);
INSERT INTO `yy_menu_role` VALUES (73, 17);
INSERT INTO `yy_menu_role` VALUES (74, 17);
INSERT INTO `yy_menu_role` VALUES (75, 17);
INSERT INTO `yy_menu_role` VALUES (76, 17);
INSERT INTO `yy_menu_role` VALUES (77, 17);
INSERT INTO `yy_menu_role` VALUES (78, 17);
INSERT INTO `yy_menu_role` VALUES (79, 17);
INSERT INTO `yy_menu_role` VALUES (80, 17);
INSERT INTO `yy_menu_role` VALUES (81, 17);
INSERT INTO `yy_menu_role` VALUES (82, 17);
INSERT INTO `yy_menu_role` VALUES (83, 17);
INSERT INTO `yy_menu_role` VALUES (84, 17);
INSERT INTO `yy_menu_role` VALUES (98, 17);
INSERT INTO `yy_menu_role` VALUES (131, 17);
INSERT INTO `yy_menu_role` VALUES (132, 17);
INSERT INTO `yy_menu_role` VALUES (133, 17);
INSERT INTO `yy_menu_role` VALUES (134, 17);
INSERT INTO `yy_menu_role` VALUES (135, 17);
INSERT INTO `yy_menu_role` VALUES (136, 17);
INSERT INTO `yy_menu_role` VALUES (137, 17);
INSERT INTO `yy_menu_role` VALUES (85, 17);
INSERT INTO `yy_menu_role` VALUES (86, 17);
INSERT INTO `yy_menu_role` VALUES (96, 17);
INSERT INTO `yy_menu_role` VALUES (97, 17);
INSERT INTO `yy_menu_role` VALUES (99, 17);
INSERT INTO `yy_menu_role` VALUES (95, 17);
INSERT INTO `yy_menu_role` VALUES (150, 17);
INSERT INTO `yy_menu_role` VALUES (87, 17);
INSERT INTO `yy_menu_role` VALUES (88, 17);
INSERT INTO `yy_menu_role` VALUES (129, 17);
INSERT INTO `yy_menu_role` VALUES (138, 17);
INSERT INTO `yy_menu_role` VALUES (139, 17);
INSERT INTO `yy_menu_role` VALUES (140, 17);
INSERT INTO `yy_menu_role` VALUES (141, 17);
INSERT INTO `yy_menu_role` VALUES (142, 17);
INSERT INTO `yy_menu_role` VALUES (143, 17);
INSERT INTO `yy_menu_role` VALUES (144, 17);
INSERT INTO `yy_menu_role` VALUES (145, 17);
INSERT INTO `yy_menu_role` VALUES (146, 17);
INSERT INTO `yy_menu_role` VALUES (147, 17);
INSERT INTO `yy_menu_role` VALUES (148, 17);
INSERT INTO `yy_menu_role` VALUES (100, 17);
INSERT INTO `yy_menu_role` VALUES (107, 17);
INSERT INTO `yy_menu_role` VALUES (108, 17);
INSERT INTO `yy_menu_role` VALUES (120, 17);
INSERT INTO `yy_menu_role` VALUES (109, 17);
INSERT INTO `yy_menu_role` VALUES (114, 17);
INSERT INTO `yy_menu_role` VALUES (113, 17);
INSERT INTO `yy_menu_role` VALUES (115, 17);
INSERT INTO `yy_menu_role` VALUES (116, 17);
INSERT INTO `yy_menu_role` VALUES (117, 17);
INSERT INTO `yy_menu_role` VALUES (118, 17);
INSERT INTO `yy_menu_role` VALUES (119, 17);
INSERT INTO `yy_menu_role` VALUES (121, 17);
INSERT INTO `yy_menu_role` VALUES (122, 17);
INSERT INTO `yy_menu_role` VALUES (123, 17);
INSERT INTO `yy_menu_role` VALUES (124, 17);
INSERT INTO `yy_menu_role` VALUES (125, 17);
INSERT INTO `yy_menu_role` VALUES (126, 17);
INSERT INTO `yy_menu_role` VALUES (127, 17);
INSERT INTO `yy_menu_role` VALUES (151, 17);
INSERT INTO `yy_menu_role` VALUES (152, 17);
INSERT INTO `yy_menu_role` VALUES (153, 17);
INSERT INTO `yy_menu_role` VALUES (154, 17);
INSERT INTO `yy_menu_role` VALUES (155, 17);
INSERT INTO `yy_menu_role` VALUES (156, 17);
INSERT INTO `yy_menu_role` VALUES (157, 17);
INSERT INTO `yy_menu_role` VALUES (158, 17);
INSERT INTO `yy_menu_role` VALUES (166, 17);
INSERT INTO `yy_menu_role` VALUES (1, 28);
INSERT INTO `yy_menu_role` VALUES (5, 28);
INSERT INTO `yy_menu_role` VALUES (6, 28);
INSERT INTO `yy_menu_role` VALUES (7, 28);
INSERT INTO `yy_menu_role` VALUES (8, 28);
INSERT INTO `yy_menu_role` VALUES (9, 28);
INSERT INTO `yy_menu_role` VALUES (11, 28);
INSERT INTO `yy_menu_role` VALUES (12, 28);
INSERT INTO `yy_menu_role` VALUES (13, 28);
INSERT INTO `yy_menu_role` VALUES (14, 28);
INSERT INTO `yy_menu_role` VALUES (15, 28);
INSERT INTO `yy_menu_role` VALUES (16, 28);
INSERT INTO `yy_menu_role` VALUES (17, 28);
INSERT INTO `yy_menu_role` VALUES (18, 28);
INSERT INTO `yy_menu_role` VALUES (19, 28);
INSERT INTO `yy_menu_role` VALUES (20, 28);
INSERT INTO `yy_menu_role` VALUES (21, 28);
INSERT INTO `yy_menu_role` VALUES (22, 28);
INSERT INTO `yy_menu_role` VALUES (54, 28);
INSERT INTO `yy_menu_role` VALUES (61, 28);
INSERT INTO `yy_menu_role` VALUES (62, 28);
INSERT INTO `yy_menu_role` VALUES (60, 28);
INSERT INTO `yy_menu_role` VALUES (64, 28);
INSERT INTO `yy_menu_role` VALUES (58, 28);
INSERT INTO `yy_menu_role` VALUES (59, 28);
INSERT INTO `yy_menu_role` VALUES (56, 28);
INSERT INTO `yy_menu_role` VALUES (63, 28);
INSERT INTO `yy_menu_role` VALUES (65, 28);
INSERT INTO `yy_menu_role` VALUES (66, 28);
INSERT INTO `yy_menu_role` VALUES (67, 28);
INSERT INTO `yy_menu_role` VALUES (68, 28);
INSERT INTO `yy_menu_role` VALUES (69, 28);
INSERT INTO `yy_menu_role` VALUES (70, 28);
INSERT INTO `yy_menu_role` VALUES (71, 28);
INSERT INTO `yy_menu_role` VALUES (72, 28);
INSERT INTO `yy_menu_role` VALUES (73, 28);
INSERT INTO `yy_menu_role` VALUES (74, 28);
INSERT INTO `yy_menu_role` VALUES (75, 28);
INSERT INTO `yy_menu_role` VALUES (76, 28);
INSERT INTO `yy_menu_role` VALUES (77, 28);
INSERT INTO `yy_menu_role` VALUES (78, 28);
INSERT INTO `yy_menu_role` VALUES (79, 28);
INSERT INTO `yy_menu_role` VALUES (80, 28);
INSERT INTO `yy_menu_role` VALUES (81, 28);
INSERT INTO `yy_menu_role` VALUES (82, 28);
INSERT INTO `yy_menu_role` VALUES (83, 28);
INSERT INTO `yy_menu_role` VALUES (84, 28);
INSERT INTO `yy_menu_role` VALUES (98, 28);
INSERT INTO `yy_menu_role` VALUES (131, 28);
INSERT INTO `yy_menu_role` VALUES (132, 28);
INSERT INTO `yy_menu_role` VALUES (133, 28);
INSERT INTO `yy_menu_role` VALUES (134, 28);
INSERT INTO `yy_menu_role` VALUES (135, 28);
INSERT INTO `yy_menu_role` VALUES (136, 28);
INSERT INTO `yy_menu_role` VALUES (137, 28);
INSERT INTO `yy_menu_role` VALUES (85, 28);
INSERT INTO `yy_menu_role` VALUES (86, 28);
INSERT INTO `yy_menu_role` VALUES (96, 28);
INSERT INTO `yy_menu_role` VALUES (97, 28);
INSERT INTO `yy_menu_role` VALUES (99, 28);
INSERT INTO `yy_menu_role` VALUES (95, 28);
INSERT INTO `yy_menu_role` VALUES (150, 28);
INSERT INTO `yy_menu_role` VALUES (1, 18);
INSERT INTO `yy_menu_role` VALUES (5, 18);
INSERT INTO `yy_menu_role` VALUES (6, 18);
INSERT INTO `yy_menu_role` VALUES (7, 18);
INSERT INTO `yy_menu_role` VALUES (8, 18);
INSERT INTO `yy_menu_role` VALUES (9, 18);
INSERT INTO `yy_menu_role` VALUES (11, 18);
INSERT INTO `yy_menu_role` VALUES (12, 18);
INSERT INTO `yy_menu_role` VALUES (13, 18);
INSERT INTO `yy_menu_role` VALUES (14, 18);
INSERT INTO `yy_menu_role` VALUES (15, 18);
INSERT INTO `yy_menu_role` VALUES (16, 18);
INSERT INTO `yy_menu_role` VALUES (17, 18);
INSERT INTO `yy_menu_role` VALUES (18, 18);
INSERT INTO `yy_menu_role` VALUES (19, 18);
INSERT INTO `yy_menu_role` VALUES (20, 18);
INSERT INTO `yy_menu_role` VALUES (21, 18);
INSERT INTO `yy_menu_role` VALUES (22, 18);
INSERT INTO `yy_menu_role` VALUES (54, 18);
INSERT INTO `yy_menu_role` VALUES (61, 18);
INSERT INTO `yy_menu_role` VALUES (62, 18);
INSERT INTO `yy_menu_role` VALUES (60, 18);
INSERT INTO `yy_menu_role` VALUES (64, 18);
INSERT INTO `yy_menu_role` VALUES (58, 18);
INSERT INTO `yy_menu_role` VALUES (59, 18);
INSERT INTO `yy_menu_role` VALUES (56, 18);
INSERT INTO `yy_menu_role` VALUES (63, 18);
INSERT INTO `yy_menu_role` VALUES (65, 18);
INSERT INTO `yy_menu_role` VALUES (66, 18);
INSERT INTO `yy_menu_role` VALUES (67, 18);
INSERT INTO `yy_menu_role` VALUES (68, 18);
INSERT INTO `yy_menu_role` VALUES (69, 18);
INSERT INTO `yy_menu_role` VALUES (70, 18);
INSERT INTO `yy_menu_role` VALUES (71, 18);
INSERT INTO `yy_menu_role` VALUES (72, 18);
INSERT INTO `yy_menu_role` VALUES (73, 18);
INSERT INTO `yy_menu_role` VALUES (74, 18);
INSERT INTO `yy_menu_role` VALUES (75, 18);
INSERT INTO `yy_menu_role` VALUES (76, 18);
INSERT INTO `yy_menu_role` VALUES (77, 18);
INSERT INTO `yy_menu_role` VALUES (78, 18);
INSERT INTO `yy_menu_role` VALUES (79, 18);
INSERT INTO `yy_menu_role` VALUES (80, 18);
INSERT INTO `yy_menu_role` VALUES (81, 18);
INSERT INTO `yy_menu_role` VALUES (82, 18);
INSERT INTO `yy_menu_role` VALUES (83, 18);
INSERT INTO `yy_menu_role` VALUES (84, 18);
INSERT INTO `yy_menu_role` VALUES (98, 18);
INSERT INTO `yy_menu_role` VALUES (131, 18);
INSERT INTO `yy_menu_role` VALUES (132, 18);
INSERT INTO `yy_menu_role` VALUES (133, 18);
INSERT INTO `yy_menu_role` VALUES (134, 18);
INSERT INTO `yy_menu_role` VALUES (135, 18);
INSERT INTO `yy_menu_role` VALUES (136, 18);
INSERT INTO `yy_menu_role` VALUES (137, 18);
INSERT INTO `yy_menu_role` VALUES (85, 18);
INSERT INTO `yy_menu_role` VALUES (86, 18);
INSERT INTO `yy_menu_role` VALUES (96, 18);
INSERT INTO `yy_menu_role` VALUES (97, 18);
INSERT INTO `yy_menu_role` VALUES (99, 18);
INSERT INTO `yy_menu_role` VALUES (95, 18);
INSERT INTO `yy_menu_role` VALUES (150, 18);
INSERT INTO `yy_menu_role` VALUES (87, 18);
INSERT INTO `yy_menu_role` VALUES (88, 18);
INSERT INTO `yy_menu_role` VALUES (129, 18);
INSERT INTO `yy_menu_role` VALUES (138, 18);
INSERT INTO `yy_menu_role` VALUES (139, 18);
INSERT INTO `yy_menu_role` VALUES (140, 18);
INSERT INTO `yy_menu_role` VALUES (141, 18);
INSERT INTO `yy_menu_role` VALUES (142, 18);
INSERT INTO `yy_menu_role` VALUES (143, 18);
INSERT INTO `yy_menu_role` VALUES (144, 18);
INSERT INTO `yy_menu_role` VALUES (145, 18);
INSERT INTO `yy_menu_role` VALUES (146, 18);
INSERT INTO `yy_menu_role` VALUES (147, 18);
INSERT INTO `yy_menu_role` VALUES (148, 18);
INSERT INTO `yy_menu_role` VALUES (100, 18);
INSERT INTO `yy_menu_role` VALUES (107, 18);
INSERT INTO `yy_menu_role` VALUES (108, 18);
INSERT INTO `yy_menu_role` VALUES (120, 18);
INSERT INTO `yy_menu_role` VALUES (109, 18);
INSERT INTO `yy_menu_role` VALUES (114, 18);
INSERT INTO `yy_menu_role` VALUES (113, 18);
INSERT INTO `yy_menu_role` VALUES (115, 18);
INSERT INTO `yy_menu_role` VALUES (116, 18);
INSERT INTO `yy_menu_role` VALUES (117, 18);
INSERT INTO `yy_menu_role` VALUES (118, 18);
INSERT INTO `yy_menu_role` VALUES (119, 18);
INSERT INTO `yy_menu_role` VALUES (121, 18);
INSERT INTO `yy_menu_role` VALUES (122, 18);
INSERT INTO `yy_menu_role` VALUES (123, 18);
INSERT INTO `yy_menu_role` VALUES (124, 18);
INSERT INTO `yy_menu_role` VALUES (125, 18);
INSERT INTO `yy_menu_role` VALUES (126, 18);
INSERT INTO `yy_menu_role` VALUES (127, 18);
INSERT INTO `yy_menu_role` VALUES (151, 18);
INSERT INTO `yy_menu_role` VALUES (152, 18);
INSERT INTO `yy_menu_role` VALUES (153, 18);
INSERT INTO `yy_menu_role` VALUES (154, 18);
INSERT INTO `yy_menu_role` VALUES (155, 18);
INSERT INTO `yy_menu_role` VALUES (156, 18);
INSERT INTO `yy_menu_role` VALUES (157, 18);
INSERT INTO `yy_menu_role` VALUES (158, 18);
INSERT INTO `yy_menu_role` VALUES (166, 18);

-- ----------------------------
-- Table structure for yy_picture
-- ----------------------------
DROP TABLE IF EXISTS `yy_picture`;
CREATE TABLE `yy_picture`  (
  `picture_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `path` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '路径',
  `md5` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`picture_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 48 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '图片表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yy_picture
-- ----------------------------
INSERT INTO `yy_picture` VALUES (1, '__static_/admin/images/default_user.jpg', NULL, NULL, 1, '2019-04-10 22:52:37');

-- ----------------------------
-- Table structure for yy_role
-- ----------------------------
DROP TABLE IF EXISTS `yy_role`;
CREATE TABLE `yy_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_unique`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Rbac角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yy_role
-- ----------------------------
INSERT INTO `yy_role` VALUES (17, '超级管理员', '超级管理员', '2017-11-26 18:24:42', '2017-12-14 16:25:01');
INSERT INTO `yy_role` VALUES (18, '管理员', '管理员', '2017-11-27 12:58:45', '2017-11-27 16:45:50');

-- ----------------------------
-- Table structure for yy_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `yy_role_admin`;
CREATE TABLE `yy_role_admin`  (
  `admin_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`admin_id`, `role_id`) USING BTREE,
  INDEX `role_user_role_id_foreign`(`role_id`) USING BTREE,
  CONSTRAINT `role_admin_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `yy_admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `yy_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for yy_user
-- ----------------------------
DROP TABLE IF EXISTS `yy_user`;
CREATE TABLE `yy_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '邮箱',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sex` tinyint(1) NULL DEFAULT NULL COMMENT '性别',
  `icon` int(11) NOT NULL DEFAULT 1 COMMENT '头像',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1启用 2禁用',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_login` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否登陆',
  `last_login_time` datetime NULL DEFAULT NULL,
  `last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yy_user
-- ----------------------------
INSERT INTO `yy_user` VALUES (1, NULL, '6930213251@qq.com', '4e3988009121972e1b919354e26a1f10', '从心。', 1, 1, 1, '2019-04-06 23:27:40', '2019-04-14 18:33:20', 0, NULL, NULL);
INSERT INTO `yy_user` VALUES (2, '13758248144', '6930213258@qq.com', '4e3988009121972e1b919354e26a1f10', '嘿嘿嘿', NULL, 1, 1, NULL, NULL, 0, NULL, NULL);
INSERT INTO `yy_user` VALUES (3, NULL, '693021325@qq.com', '4e3988009121972e1b919354e26a1f10', '瑶瑶', 2, 1, 1, NULL, '2019-04-16 15:21:57', 0, '2019-04-16 15:08:08', '127.0.0.1');
INSERT INTO `yy_user` VALUES (4, NULL, '693021325@qq.com', '4e3988009121972e1b919354e26a1f10', 'yao_ban_001', NULL, 1, 1, NULL, NULL, 0, '2019-04-15 23:08:06', '127.0.0.1');

-- ----------------------------
-- Table structure for yy_user_invitation
-- ----------------------------
DROP TABLE IF EXISTS `yy_user_invitation`;
CREATE TABLE `yy_user_invitation`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `invitation_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '帖子参与表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of yy_user_invitation
-- ----------------------------
INSERT INTO `yy_user_invitation` VALUES (7, 2, 3);
INSERT INTO `yy_user_invitation` VALUES (6, 2, 2);

SET FOREIGN_KEY_CHECKS = 1;
