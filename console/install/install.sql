/*
Navicat MySQL Data Transfer

Source Server         : company
Source Server Version : 50556
Source Host           : 192.168.0.11:3306
Source Database       : superweb

Target Server Type    : MYSQL
Target Server Version : 50556
File Encoding         : 65001

Date: 2018-11-05 17:54:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for apk_detail
-- ----------------------------
DROP TABLE IF EXISTS `apk_detail`;
CREATE TABLE `apk_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `apk_ID` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ver` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `md5` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `force_update` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `is_newest` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `save_position` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'oss',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=322 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for apk_list
-- ----------------------------
DROP TABLE IF EXISTS `apk_list`;
CREATE TABLE `apk_list` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img` text COLLATE utf8_unicode_ci,
  `sort` int(11) NOT NULL DEFAULT '0',
  `scheme_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'all',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for app_boot_picture
-- ----------------------------
DROP TABLE IF EXISTS `app_boot_picture`;
CREATE TABLE `app_boot_picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boot_pic` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '启动页面图片',
  `link` varchar(255) CHARACTER SET utf8 DEFAULT '#' COMMENT '链接',
  `during` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '时间范围',
  `status` varchar(255) CHARACTER SET utf8 DEFAULT '1' COMMENT '状态(0无效1有效)',
  `sort` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '排序',
  `created_at` int(11) DEFAULT NULL COMMENT '创建日期',
  `type` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT 'phone' COMMENT '终端类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for app_menu
-- ----------------------------
DROP TABLE IF EXISTS `app_menu`;
CREATE TABLE `app_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '名字',
  `zh_name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '图标',
  `icon` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '图标',
  `icon_hover` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '图标高亮',
  `icon_big` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '大图标',
  `icon_big_hover` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '图标大(高亮)',
  `restful_url` varchar(255) NOT NULL COMMENT 'restful地址',
  `auth` varchar(255) DEFAULT '0' COMMENT '是否需权限访问',
  `sort` char(3) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for dvb_order
-- ----------------------------
DROP TABLE IF EXISTS `dvb_order`;
CREATE TABLE `dvb_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '订单名称',
  `order_num` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '订单号',
  `order_date` date DEFAULT NULL COMMENT '订单日期',
  `order_count` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '订单数量',
  `client_id` int(11) DEFAULT NULL COMMENT '客户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for firmware_class
-- ----------------------------
DROP TABLE IF EXISTS `firmware_class`;
CREATE TABLE `firmware_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '产品名称',
  `is_use` char(1) DEFAULT '1',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for firmware_detail
-- ----------------------------
DROP TABLE IF EXISTS `firmware_detail`;
CREATE TABLE `firmware_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firmware_id` int(11) NOT NULL COMMENT '关联Id',
  `ver` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '版本号',
  `md5` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件md5',
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '资源地址',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '更新内容',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `force_update` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否强制更新',
  `is_use` char(1) CHARACTER SET utf8 DEFAULT '1' COMMENT '是否使用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for iptv_channel
-- ----------------------------
DROP TABLE IF EXISTS `iptv_channel`;
CREATE TABLE `iptv_channel` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ClassID` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  `EPG` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `use_flag` int(11) NOT NULL DEFAULT '1',
  `serial_id` int(11) NOT NULL DEFAULT '0',
  `en_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_id` int(11) NOT NULL,
  `client` set('11','12','13') COLLATE utf8_unicode_ci NOT NULL DEFAULT '11,12,13',
  `voole_id` int(11) NOT NULL DEFAULT '0',
  `eng_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'program',
  `channelPic` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alias_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '别名',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=19360 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for iptv_class
-- ----------------------------
DROP TABLE IF EXISTS `iptv_class`;
CREATE TABLE `iptv_class` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `use_flag` int(11) NOT NULL DEFAULT '1',
  `en_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client` set('11','12','13') COLLATE utf8_unicode_ci NOT NULL DEFAULT '11,12,13',
  `country_id` smallint(6) NOT NULL,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '导入识别关键字',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'common',
  `eng_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'program',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1541 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for iptv_list
-- ----------------------------
DROP TABLE IF EXISTS `iptv_list`;
CREATE TABLE `iptv_list` (
  `list_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `list_pid` smallint(3) NOT NULL DEFAULT '0' COMMENT '父id',
  `list_sid` tinyint(1) NOT NULL COMMENT '模型id',
  `list_name` char(20) NOT NULL COMMENT '分类名称',
  `list_dir` varchar(90) NOT NULL COMMENT '分类英文别名',
  `list_status` tinyint(1) NOT NULL DEFAULT '1',
  `list_keywords` varchar(255) NOT NULL COMMENT '分类SEO关键词',
  `list_title` varchar(50) NOT NULL COMMENT '分类SEO标题',
  `list_description` varchar(255) NOT NULL COMMENT '分类SEO描述',
  `list_ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '影片观看权限',
  `list_price` smallint(6) NOT NULL DEFAULT '0' COMMENT '影片单独付费',
  `list_trysee` smallint(6) NOT NULL DEFAULT '0' COMMENT '影片试看时间',
  `list_extend` text COMMENT '扩展配置',
  `list_icon` varchar(255) NOT NULL,
  `list_sort` smallint(6) NOT NULL,
  PRIMARY KEY (`list_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for iptv_middle_parade
-- ----------------------------
DROP TABLE IF EXISTS `iptv_middle_parade`;
CREATE TABLE `iptv_middle_parade` (
  `genre` varchar(30) DEFAULT NULL COMMENT '分类',
  `channel` varchar(50) DEFAULT NULL COMMENT '频道',
  `parade_content` text COMMENT '预告内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for iptv_nav
-- ----------------------------
DROP TABLE IF EXISTS `iptv_nav`;
CREATE TABLE `iptv_nav` (
  `nav_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nav_pid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `nav_oid` tinyint(3) NOT NULL DEFAULT '1',
  `nav_title` varchar(50) NOT NULL DEFAULT '',
  `nav_tips` varchar(50) NOT NULL DEFAULT '',
  `nav_link` varchar(255) NOT NULL DEFAULT '',
  `nav_status` tinyint(1) NOT NULL DEFAULT '1',
  `nav_target` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for iptv_order
-- ----------------------------
DROP TABLE IF EXISTS `iptv_order`;
CREATE TABLE `iptv_order` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_sign` varchar(32) NOT NULL COMMENT '订单日期',
  `order_status` char(1) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `order_uid` varchar(32) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_total` smallint(6) NOT NULL COMMENT '订单数量',
  `order_money` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `order_ispay` char(1) NOT NULL DEFAULT '0' COMMENT '是否支付',
  `order_addtime` int(11) NOT NULL COMMENT '下单时间',
  `order_paytime` int(11) NOT NULL COMMENT '支付时间',
  `order_confirmtime` int(11) NOT NULL COMMENT '订单确认时间',
  `order_info` tinytext NOT NULL COMMENT '订单信息',
  `order_paytype` varchar(64) NOT NULL COMMENT '支付类型',
  `order_type` varchar(30) DEFAULT NULL,
  `del_flag` char(2) NOT NULL DEFAULT '0',
  `transNo` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=737 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for iptv_parade
-- ----------------------------
DROP TABLE IF EXISTS `iptv_parade`;
CREATE TABLE `iptv_parade` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `channel_id` int(30) DEFAULT NULL COMMENT '频道id',
  `channel_name` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '频道名称',
  `parade_date` date DEFAULT NULL COMMENT '预告日期',
  `upload_date` date DEFAULT NULL COMMENT '采集日期',
  `parade_data` text CHARACTER SET utf8 COMMENT '预告数据',
  `source` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `parade_timestamp` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7564 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for iptv_play_group
-- ----------------------------
DROP TABLE IF EXISTS `iptv_play_group`;
CREATE TABLE `iptv_play_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vod_id` int(11) DEFAULT NULL,
  `group_name` varchar(32) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=346 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for iptv_renew
-- ----------------------------
DROP TABLE IF EXISTS `iptv_renew`;
CREATE TABLE `iptv_renew` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(36) CHARACTER SET utf8 NOT NULL,
  `date` int(10) NOT NULL,
  `renew_period` varchar(30) CHARACTER SET utf8 NOT NULL,
  `card_num` char(20) CHARACTER SET utf8 NOT NULL,
  `renew_operator` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '''操作用户''',
  `expire_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for iptv_tvlink
-- ----------------------------
DROP TABLE IF EXISTS `iptv_tvlink`;
CREATE TABLE `iptv_tvlink` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ChannelID` int(11) NOT NULL,
  `link` text NOT NULL,
  `source` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `use_flag` int(11) NOT NULL DEFAULT '1',
  `format` int(11) NOT NULL,
  `area_line` int(11) NOT NULL,
  `mass_level` int(11) NOT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `client` set('11','12','13') DEFAULT '11,12,13',
  `script_deal` char(1) NOT NULL DEFAULT '1',
  `scheme_id` varchar(255) NOT NULL DEFAULT 'all',
  `definition` char(1) NOT NULL DEFAULT '0',
  `method` varchar(20) NOT NULL DEFAULT 'null',
  `decode` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1100702 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for iptv_type
-- ----------------------------
DROP TABLE IF EXISTS `iptv_type`;
CREATE TABLE `iptv_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `field` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '字段',
  `vod_list_id` int(11) NOT NULL COMMENT '关联类型id',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for iptv_type_item
-- ----------------------------
DROP TABLE IF EXISTS `iptv_type_item`;
CREATE TABLE `iptv_type_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL COMMENT '关联分类id',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `zh_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '中文名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for iptv_upgrade_record
-- ----------------------------
DROP TABLE IF EXISTS `iptv_upgrade_record`;
CREATE TABLE `iptv_upgrade_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `expire_time` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `is_deal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for iptv_url_resolution
-- ----------------------------
DROP TABLE IF EXISTS `iptv_url_resolution`;
CREATE TABLE `iptv_url_resolution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '解析名称',
  `c` varchar(600) CHARACTER SET utf8 NOT NULL COMMENT 'c语言',
  `android` varchar(600) CHARACTER SET utf8 NOT NULL COMMENT '安卓',
  `referer` varchar(100) CHARACTER SET utf8 NOT NULL,
  `expire_time` smallint(6) NOT NULL COMMENT '过期时间',
  `url` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '目标url',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for iptv_vod
-- ----------------------------
DROP TABLE IF EXISTS `iptv_vod`;
CREATE TABLE `iptv_vod` (
  `vod_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '影片id',
  `vod_cid` smallint(6) NOT NULL COMMENT '影片分类',
  `vod_name` varchar(255) NOT NULL COMMENT '影片名称',
  `vod_ename` varchar(255) DEFAULT '' COMMENT '影片别名',
  `vod_title` varchar(255) DEFAULT '' COMMENT '影片副标',
  `vod_keywords` varchar(255) DEFAULT '' COMMENT '影片TAG',
  `vod_type` varchar(255) DEFAULT '' COMMENT '扩展分类',
  `vod_color` char(8) DEFAULT '',
  `vod_actor` varchar(255) DEFAULT '',
  `vod_director` varchar(255) DEFAULT '',
  `vod_content` text,
  `vod_pic` varchar(255) DEFAULT '',
  `vod_pic_bg` varchar(255) DEFAULT '',
  `vod_pic_slide` varchar(255) DEFAULT '',
  `vod_area` char(20) DEFAULT NULL,
  `vod_language` char(10) DEFAULT '',
  `vod_year` smallint(4) DEFAULT '0',
  `vod_continu` varchar(20) DEFAULT '0',
  `vod_total` mediumint(9) DEFAULT '0',
  `vod_isend` tinyint(1) DEFAULT '1',
  `vod_addtime` int(11) DEFAULT '0',
  `vod_filmtime` int(11) DEFAULT '0',
  `vod_hits` mediumint(8) DEFAULT '0',
  `vod_hits_day` mediumint(8) DEFAULT '0',
  `vod_hits_week` mediumint(8) DEFAULT '0',
  `vod_hits_month` mediumint(8) DEFAULT '0',
  `vod_hits_lasttime` int(11) DEFAULT '0',
  `vod_stars` tinyint(1) DEFAULT '1',
  `vod_status` tinyint(1) DEFAULT '1',
  `vod_up` mediumint(8) DEFAULT '0',
  `vod_down` mediumint(8) DEFAULT '0',
  `vod_ispay` tinyint(1) DEFAULT '0',
  `vod_price` smallint(6) DEFAULT '0',
  `vod_trysee` smallint(6) NOT NULL,
  `vod_play` varchar(255) DEFAULT '',
  `vod_server` varchar(255) DEFAULT '',
  `vod_url` longtext,
  `vod_inputer` varchar(30) DEFAULT '',
  `vod_reurl` varchar(255) DEFAULT '',
  `vod_jumpurl` varchar(150) DEFAULT '',
  `vod_letter` char(2) DEFAULT '',
  `vod_skin` varchar(30) DEFAULT '',
  `vod_gold` decimal(3,1) DEFAULT '0.0',
  `vod_golder` smallint(6) DEFAULT '0',
  `vod_length` varchar(10) DEFAULT NULL,
  `vod_weekday` varchar(60) DEFAULT '',
  `vod_series` varchar(120) DEFAULT '',
  `vod_copyright` smallint(3) DEFAULT '0',
  `vod_state` varchar(30) DEFAULT '',
  `vod_version` varchar(30) DEFAULT '',
  `vod_tv` varchar(30) DEFAULT '',
  `vod_douban_id` int(11) DEFAULT '0',
  `vod_douban_score` decimal(3,1) DEFAULT '0.0',
  `vod_scenario` longtext,
  `vod_extend` text,
  `vod_home` varchar(255) DEFAULT '0',
  `vod_multiple` char(1) NOT NULL DEFAULT '0',
  `vod_fill_flag` char(1) NOT NULL DEFAULT '0',
  `sort` int(8) NOT NULL DEFAULT '0',
  `vod_imdb_id` char(16) NOT NULL DEFAULT '',
  `vod_imdb_score` char(4) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`vod_id`),
  KEY `vod_cid` (`vod_cid`),
  KEY `vod_up` (`vod_up`),
  KEY `vod_down` (`vod_down`),
  KEY `vod_gold` (`vod_gold`),
  KEY `vod_addtime` (`vod_addtime`,`vod_cid`),
  KEY `vod_hits` (`vod_hits`,`vod_cid`)
) ENGINE=MyISAM AUTO_INCREMENT=346 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for iptv_vodlink
-- ----------------------------
DROP TABLE IF EXISTS `iptv_vodlink`;
CREATE TABLE `iptv_vodlink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联剧集',
  `url` varchar(255) NOT NULL,
  `hd_url` varchar(255) NOT NULL COMMENT '高清',
  `season` tinyint(255) NOT NULL DEFAULT '1' COMMENT '季数',
  `episode` smallint(4) NOT NULL DEFAULT '1' COMMENT '剧集',
  `plot` varchar(1000) DEFAULT NULL COMMENT '剧情',
  `link_trysee` tinyint(4) NOT NULL DEFAULT '5' COMMENT '免费试看',
  `group_id` int(11) DEFAULT NULL,
  `save_type` char(10) NOT NULL DEFAULT 'external',
  `pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vod_id` (`video_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5492 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for log_interface
-- ----------------------------
DROP TABLE IF EXISTS `log_interface`;
CREATE TABLE `log_interface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '2018' COMMENT '年份',
  `date` date NOT NULL COMMENT '日期',
  `total` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '请求总数',
  `watch` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '节目播放请求',
  `getClientToken` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT 'token请求',
  `getOttNewList` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT 'ott列表下载',
  `getIptvList` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '点播列表下载',
  `getAppMarket` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT 'APP市场',
  `renew` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '续费',
  `getNewApp` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT 'app更新',
  `ottCharge` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '下单接口',
  `register` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '注册请求',
  `getMajorEvent` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '获取主要赛事',
  `getOttRecommend` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '获取直播推荐',
  `getCountryList` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '获取国家列表',
  `notify` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '支付异步/同步通知',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=640 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for log_statics
-- ----------------------------
DROP TABLE IF EXISTS `log_statics`;
CREATE TABLE `log_statics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) DEFAULT NULL COMMENT '日期',
  `active_user` int(7) DEFAULT '0' COMMENT '活跃用户',
  `valid_user` int(7) DEFAULT '0' COMMENT '有效用户',
  `total` int(7) DEFAULT '0' COMMENT '请求总数',
  `token` int(7) DEFAULT '0' COMMENT 'token请求总数',
  `ott_list` int(7) DEFAULT '0' COMMENT 'ott节目列表请求总数',
  `iptv_list` int(7) DEFAULT '0' COMMENT 'iptv节目列表请求总数',
  `karaoke_list` int(7) DEFAULT '0' COMMENT '卡拉ok节目列表',
  `epg` int(7) DEFAULT '0' COMMENT '预告列表请求总数',
  `app_upgrade` int(7) DEFAULT '0' COMMENT 'APP升级',
  `firmware_upgrade` int(7) DEFAULT '0' COMMENT '固件升级',
  `renew` int(7) DEFAULT '0' COMMENT '会员续费',
  `dvb_register` int(7) DEFAULT '0' COMMENT 'dvb注册',
  `ott_charge` int(7) DEFAULT '0' COMMENT 'ott分类',
  `pay` int(7) DEFAULT '0' COMMENT '支付接口',
  `activateGenre` int(7) DEFAULT '0' COMMENT '激活分类使用',
  `paypal_callback` int(7) DEFAULT '0' COMMENT 'paypal 异步通知',
  `dokypay_callback` int(7) DEFAULT '0' COMMENT 'dokypay 异步通知',
  `getServerTime` int(7) DEFAULT '0' COMMENT '服务器时间',
  `play` int(7) DEFAULT '0' COMMENT '播放接口',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for log_tmp_interface
-- ----------------------------
DROP TABLE IF EXISTS `log_tmp_interface`;
CREATE TABLE `log_tmp_interface` (
  `header` varchar(20) DEFAULT NULL,
  `mac` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mac
-- ----------------------------
DROP TABLE IF EXISTS `mac`;
CREATE TABLE `mac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MAC` char(64) NOT NULL,
  `SN` varchar(64) NOT NULL DEFAULT '',
  `use_flag` int(11) NOT NULL DEFAULT '1',
  `ver` char(255) NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logintime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` int(11) NOT NULL DEFAULT '0',
  `duetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `contract_time` varchar(8) NOT NULL DEFAULT '0',
  `access_token` varchar(255) NOT NULL,
  `access_token_expire` int(11) NOT NULL,
  `identity_type` varchar(255) DEFAULT '0' COMMENT '0试用会员1付费会员',
  `client_id` smallint(6) NOT NULL DEFAULT '0',
  `is_online` char(1) NOT NULL DEFAULT '0',
  `is_hide` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mac_sn` (`MAC`,`SN`) USING BTREE COMMENT 'mac_sn索引',
  KEY `access_token` (`access_token`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=136026 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ott_access
-- ----------------------------
DROP TABLE IF EXISTS `ott_access`;
CREATE TABLE `ott_access` (
  `mac` varchar(50) DEFAULT NULL COMMENT 'mac地址',
  `genre` varchar(20) DEFAULT NULL COMMENT '列表名称',
  `is_valid` int(6) DEFAULT NULL COMMENT '是否有权限',
  `deny_msg` varchar(50) DEFAULT NULL COMMENT '拒绝原因',
  `expire_time` int(10) DEFAULT NULL COMMENT '过期时间',
  `access_key` char(32) NOT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ott_banner
-- ----------------------------
DROP TABLE IF EXISTS `ott_banner`;
CREATE TABLE `ott_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '标题',
  `desc` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '详情',
  `image` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '小图',
  `image_big` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '大图',
  `sort` varchar(255) DEFAULT '0' COMMENT '排序',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '频道id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '资源地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_channel
-- ----------------------------
DROP TABLE IF EXISTS `ott_channel`;
CREATE TABLE `ott_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_class_id` int(11) NOT NULL COMMENT '关联id',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `zh_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '中文名称',
  `keywords` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '关键字',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `use_flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `channel_number` int(11) NOT NULL DEFAULT '0' COMMENT '序列号',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alias_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `is_recommend` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否被推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9128 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for ott_event
-- ----------------------------
DROP TABLE IF EXISTS `ott_event`;
CREATE TABLE `ott_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `event_name_zh` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '中文名称',
  `event_introduce` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '介绍',
  `event_icon` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '图标',
  `event_icon_big` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '大图标',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_event_team
-- ----------------------------
DROP TABLE IF EXISTS `ott_event_team`;
CREATE TABLE `ott_event_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL COMMENT '所属赛事',
  `team_name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '队伍名称',
  `team_zh_name` varchar(50) CHARACTER SET utf8 DEFAULT '' COMMENT '队伍中文名',
  `team_introduce` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '队伍简介',
  `team_icon` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '队伍图标',
  `team_icon_big` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '队伍图标',
  `team_country` char(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '国家代码',
  `team_info` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '附加属性',
  `team_alias_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '别名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_genre_probation
-- ----------------------------
DROP TABLE IF EXISTS `ott_genre_probation`;
CREATE TABLE `ott_genre_probation` (
  `mac` varchar(32) NOT NULL,
  `genre` varchar(20) DEFAULT NULL COMMENT '列表名称',
  `day` date NOT NULL,
  `expire_time` int(10) DEFAULT NULL COMMENT '过期时间',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(10) DEFAULT NULL COMMENT '更新时间',
  KEY `mac_index` (`mac`),
  KEY `mac_genre` (`mac`,`genre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ott_link
-- ----------------------------
DROP TABLE IF EXISTS `ott_link`;
CREATE TABLE `ott_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL COMMENT '关联频道号',
  `link` text NOT NULL COMMENT '链接',
  `source` varchar(30) NOT NULL DEFAULT 'default' COMMENT '来源',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `use_flag` char(1) NOT NULL DEFAULT '1' COMMENT '可用',
  `format` int(11) NOT NULL,
  `script_deal` char(1) NOT NULL DEFAULT '1' COMMENT '脚本开关',
  `definition` char(1) NOT NULL DEFAULT '0' COMMENT '清晰度',
  `method` varchar(20) NOT NULL DEFAULT 'null' COMMENT '本地算法',
  `decode` char(1) NOT NULL DEFAULT '1' COMMENT '硬软解',
  `scheme_id` varchar(500) NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11888 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ott_main_class
-- ----------------------------
DROP TABLE IF EXISTS `ott_main_class`;
CREATE TABLE `ott_main_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '名字',
  `zh_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '中文名字',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '图标',
  `icon_hover` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `icon_bg` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `icon_bg_hover` varchar(255) DEFAULT NULL,
  `sort` char(3) CHARACTER SET utf8 DEFAULT '0' COMMENT '排序',
  `is_show` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '是否显示',
  `is_charge` char(1) NOT NULL DEFAULT '0' COMMENT '是否收费',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `use_flag` char(1) NOT NULL DEFAULT '1',
  `list_name` varchar(50) NOT NULL,
  `one_month_price` decimal(8,2) DEFAULT '0.00',
  `three_month_price` decimal(8,2) DEFAULT '0.00',
  `six_month_price` decimal(8,2) DEFAULT '0.00',
  `one_year_price` decimal(8,2) DEFAULT '0.00',
  `free_trail_days` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_major_event
-- ----------------------------
DROP TABLE IF EXISTS `ott_major_event`;
CREATE TABLE `ott_major_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL COMMENT '世界时间',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '标题',
  `live_match` text CHARACTER SET utf8 COMMENT '对阵信息',
  `base_time` int(10) DEFAULT NULL COMMENT '比赛时间',
  `match_data` text CHARACTER SET utf8 COMMENT '匹配预告列表',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `unique` char(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_order
-- ----------------------------
DROP TABLE IF EXISTS `ott_order`;
CREATE TABLE `ott_order` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '用户帐号',
  `genre` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '类别',
  `order_num` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '订单id',
  `expire_time` int(10) NOT NULL COMMENT '过期时间',
  `is_valid` char(3) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '是否有效',
  `access_key` char(32) NOT NULL,
  PRIMARY KEY (`oid`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=692 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_price_list
-- ----------------------------
DROP TABLE IF EXISTS `ott_price_list`;
CREATE TABLE `ott_price_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(8,2) NOT NULL DEFAULT '1.00' COMMENT '价格',
  `value` tinyint(2) NOT NULL COMMENT '值',
  `type` char(5) CHARACTER SET utf8 NOT NULL DEFAULT 'ott',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_probation
-- ----------------------------
DROP TABLE IF EXISTS `ott_probation`;
CREATE TABLE `ott_probation` (
  `mac` varchar(50) DEFAULT NULL COMMENT 'mac地址',
  `day` int(6) DEFAULT NULL COMMENT '免费体验天数',
  `expire_time` int(10) DEFAULT NULL COMMENT '过期时间',
  KEY `mac_index` (`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ott_recommend
-- ----------------------------
DROP TABLE IF EXISTS `ott_recommend`;
CREATE TABLE `ott_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `sort` char(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `url` varchar(255) NOT NULL COMMENT '资源地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ott_sub_class
-- ----------------------------
DROP TABLE IF EXISTS `ott_sub_class`;
CREATE TABLE `ott_sub_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_class_id` smallint(6) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zh_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `use_flag` int(11) NOT NULL DEFAULT '1',
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '导入识别关键字',
  `created_at` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=352 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sys__admin_scheme
-- ----------------------------
DROP TABLE IF EXISTS `sys__admin_scheme`;
CREATE TABLE `sys__admin_scheme` (
  `scheme_id` int(6) DEFAULT NULL,
  `admin_id` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sys__apk_scheme
-- ----------------------------
DROP TABLE IF EXISTS `sys__apk_scheme`;
CREATE TABLE `sys__apk_scheme` (
  `apk_id` int(6) DEFAULT NULL,
  `scheme_id` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sys__vod_scheme
-- ----------------------------
DROP TABLE IF EXISTS `sys__vod_scheme`;
CREATE TABLE `sys__vod_scheme` (
  `vod_id` int(11) DEFAULT NULL,
  `scheme_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sys_app_log
-- ----------------------------
DROP TABLE IF EXISTS `sys_app_log`;
CREATE TABLE `sys_app_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` char(4) CHARACTER SET utf8 NOT NULL DEFAULT '2018' COMMENT 'utf',
  `month` tinyint(4) NOT NULL COMMENT '月',
  `week` tinyint(2) NOT NULL COMMENT '第几周',
  `date` date NOT NULL COMMENT '日期',
  `mac` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT 'mac地址',
  `login_time` char(8) CHARACTER SET utf8 NOT NULL COMMENT '登录时间',
  `active_hour` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '活跃小时',
  `last_time` char(8) CHARACTER SET utf8 NOT NULL COMMENT '最后一次操作时间',
  `total_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '总的请求次数',
  `token_request` smallint(6) NOT NULL DEFAULT '0' COMMENT 'token请求次数',
  `token_success` smallint(6) NOT NULL DEFAULT '0' COMMENT 'token成功正确返回次数',
  `ott_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '直播列表请求次数',
  `iptv_request` smallint(6) NOT NULL DEFAULT '0' COMMENT 'iptv请求次数',
  `app_request` smallint(6) NOT NULL DEFAULT '0' COMMENT 'app升级请求次数',
  `firmware_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '固件升级次数',
  `renew_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '续费请求次数',
  `parade_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '预告请求',
  `time_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '时间请求',
  `register_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '注册',
  `auth_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '鉴权请求',
  `ip_change` smallint(6) NOT NULL DEFAULT '0' COMMENT 'IP变化次数',
  `request_rate` smallint(6) NOT NULL DEFAULT '0' COMMENT '请求频率',
  `exception` tinyint(4) DEFAULT '0' COMMENT '异常指数',
  `is_valid` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '1',
  `market_request` smallint(6) NOT NULL DEFAULT '0',
  `karaokelist_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '卡拉ok列表',
  `karaoke_request` smallint(6) NOT NULL DEFAULT '0' COMMENT '卡拉ok播放',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41776 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sys_banner
-- ----------------------------
DROP TABLE IF EXISTS `sys_banner`;
CREATE TABLE `sys_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vod_id` int(11) NOT NULL COMMENT '影片id',
  `sort` smallint(6) NOT NULL COMMENT '排序',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '标题',
  `description` tinytext CHARACTER SET utf8 NOT NULL COMMENT '描述',
  `pic` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pic_bg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sys_buy_record
-- ----------------------------
DROP TABLE IF EXISTS `sys_buy_record`;
CREATE TABLE `sys_buy_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vod_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_valid` tinyint(1) DEFAULT '0',
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sys_client
-- ----------------------------
DROP TABLE IF EXISTS `sys_client`;
CREATE TABLE `sys_client` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `phone` varchar(30) DEFAULT NULL COMMENT '手机',
  `admin_id` int(30) DEFAULT NULL,
  `client_age` int(30) DEFAULT NULL COMMENT '名称',
  `client_address` varchar(100) DEFAULT NULL COMMENT '地址',
  `client_email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `client_qq` varchar(30) DEFAULT NULL COMMENT 'qq',
  `client_engname` varchar(100) NOT NULL DEFAULT '' COMMENT '英文名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sys_karaoke
-- ----------------------------
DROP TABLE IF EXISTS `sys_karaoke`;
CREATE TABLE `sys_karaoke` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `albumName` text COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `albumImage` text COLLATE utf8_unicode_ci NOT NULL COMMENT '封面',
  `tid` int(11) NOT NULL,
  `mainActor` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '演员/歌手',
  `directors` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '导演',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '标签',
  `info` text COLLATE utf8_unicode_ci NOT NULL COMMENT '描述信息',
  `area` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '地区',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '关键字',
  `wflag` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ',
  `year` int(11) NOT NULL COMMENT '年份',
  `mod_version` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  `totalDuration` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '集数',
  `flag` int(11) NOT NULL DEFAULT '0',
  `hit_count` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `voole_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `is_finish` int(11) DEFAULT '0' COMMENT '是否完成',
  `yesterday_viewed` int(11) NOT NULL DEFAULT '0' COMMENT '昨日收看',
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `act_img` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '真实图片地址',
  `download_flag` int(11) NOT NULL DEFAULT '0' COMMENT '是否下载',
  `is_del` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `source` char(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'upload' COMMENT '来源',
  PRIMARY KEY (`ID`),
  KEY `year` (`year`),
  KEY `wflag` (`wflag`),
  KEY `tid` (`tid`),
  KEY `utime` (`utime`),
  FULLTEXT KEY `keywords` (`keywords`),
  FULLTEXT KEY `area` (`area`),
  FULLTEXT KEY `albumName` (`albumName`),
  FULLTEXT KEY `mainActor` (`mainActor`),
  FULLTEXT KEY `directors` (`directors`),
  FULLTEXT KEY `tags` (`tags`)
) ENGINE=MyISAM AUTO_INCREMENT=3047 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sys_program_log
-- ----------------------------
DROP TABLE IF EXISTS `sys_program_log`;
CREATE TABLE `sys_program_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `local_program` text CHARACTER SET utf8 NOT NULL COMMENT '本地解析前20个',
  `server_program` text CHARACTER SET utf8 NOT NULL COMMENT '服务器解析前二十个',
  `all_program` text CHARACTER SET utf8,
  `all_program_sum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=379 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sys_renewal_card
-- ----------------------------
DROP TABLE IF EXISTS `sys_renewal_card`;
CREATE TABLE `sys_renewal_card` (
  `card_num` varchar(16) NOT NULL COMMENT '卡号',
  `card_secret` varchar(16) NOT NULL COMMENT '卡密',
  `card_contracttime` varchar(10) DEFAULT '1 month' COMMENT '续费时长',
  `is_del` varchar(1) NOT NULL DEFAULT '0' COMMENT '是否被删除',
  `is_valid` varchar(1) NOT NULL DEFAULT '0' COMMENT '是否已使用',
  `created_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `batch` int(6) NOT NULL DEFAULT '0' COMMENT '批次',
  `who_use` varchar(30) NOT NULL DEFAULT '' COMMENT '使用的人',
  PRIMARY KEY (`card_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sys_scheme
-- ----------------------------
DROP TABLE IF EXISTS `sys_scheme`;
CREATE TABLE `sys_scheme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schemeName` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `cpu` varchar(50) CHARACTER SET utf8 NOT NULL,
  `flash` varchar(50) CHARACTER SET utf8 NOT NULL,
  `ddr` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sys_timeline_log
-- ----------------------------
DROP TABLE IF EXISTS `sys_timeline_log`;
CREATE TABLE `sys_timeline_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(255) CHARACTER SET utf8 DEFAULT '2018',
  `date` date NOT NULL,
  `total_line` varchar(500) CHARACTER SET utf8 NOT NULL,
  `watch_line` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `token_line` varchar(500) CHARACTER SET utf8 NOT NULL,
  `local_watch_line` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `server_watch_line` varchar(500) CHARACTER SET utf8 DEFAULT NULL COMMENT '服务器解析',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2426 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for yii2_migration
-- ----------------------------
DROP TABLE IF EXISTS `yii2_migration`;
CREATE TABLE `yii2_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yii2_session
-- ----------------------------
DROP TABLE IF EXISTS `yii2_session`;
CREATE TABLE `yii2_session` (
  `id` char(40) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yii2_user
-- ----------------------------
DROP TABLE IF EXISTS `yii2_user`;
CREATE TABLE `yii2_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `access_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_expire` int(11) DEFAULT NULL COMMENT 'API access_token 过期时间',
  `allowance` int(10) NOT NULL DEFAULT '0' COMMENT '剩余请求次数',
  `allowance_updated_at` int(10) NOT NULL COMMENT '最后一次请求更新时间',
  `is_vip` char(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `vip_expire_time` int(11) NOT NULL,
  `identity_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '0试用会员1付费会员',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------



/*
Navicat MySQL Data Transfer

Source Server         : company
Source Server Version : 50556
Source Host           : 192.168.0.11:3306
Source Database       : superweb

Target Server Type    : MYSQL
Target Server Version : 50556
File Encoding         : 65001

Date: 2018-11-05 17:55:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sys_country
-- ----------------------------
DROP TABLE IF EXISTS `sys_country`;
CREATE TABLE `sys_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL COMMENT '英文名称',
  `zh_name` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '中文名称',
  `code` char(2) CHARACTER SET utf8 NOT NULL COMMENT '代码',
  `icon` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '小图标',
  `icon_big` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '大图标',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of sys_country
-- ----------------------------
INSERT INTO `sys_country` VALUES ('1', 'America', '美国', 'US', '/images/meiguo.png', '');
INSERT INTO `sys_country` VALUES ('2', 'Andorra', '安道尔', 'AD', null, null);
INSERT INTO `sys_country` VALUES ('3', 'United Arab Emirates', '阿拉伯联合酋长国', 'AE', '/images/alabo.jpg', '');
INSERT INTO `sys_country` VALUES ('4', 'Afghanistan', '阿富汗', 'AF', null, null);
INSERT INTO `sys_country` VALUES ('5', 'Antigua and Barbuda', '安提瓜和巴布达', 'AG', null, null);
INSERT INTO `sys_country` VALUES ('6', 'Albania', '阿尔巴尼亚', 'AL', null, null);
INSERT INTO `sys_country` VALUES ('7', 'Armenia', '亚美尼亚', 'AM', null, null);
INSERT INTO `sys_country` VALUES ('8', 'Angola', '安哥拉', 'AO', null, null);
INSERT INTO `sys_country` VALUES ('9', 'Argentina', '阿根廷', 'AR', null, null);
INSERT INTO `sys_country` VALUES ('10', 'Austria', '奥地利', 'AT', null, null);
INSERT INTO `sys_country` VALUES ('11', 'Australia', '澳大利亚', 'AU', null, null);
INSERT INTO `sys_country` VALUES ('12', 'Aruba', '阿鲁巴', 'AW', null, null);
INSERT INTO `sys_country` VALUES ('13', 'Azerbaijan', '阿塞拜疆', 'AZ', null, null);
INSERT INTO `sys_country` VALUES ('14', 'Bosnia and Herzegovina', '波斯尼亚和黑塞哥维那', 'BA', null, null);
INSERT INTO `sys_country` VALUES ('15', 'Barbados', '巴巴多斯', 'BB', null, null);
INSERT INTO `sys_country` VALUES ('16', 'Bangladesh', '孟加拉国', 'BD', null, null);
INSERT INTO `sys_country` VALUES ('17', 'Belgium', '比利时', 'BE', null, null);
INSERT INTO `sys_country` VALUES ('18', 'Burkina Faso', '布基纳法索', 'BF', null, null);
INSERT INTO `sys_country` VALUES ('19', 'Bulgaria', '保加利亚', 'BG', null, null);
INSERT INTO `sys_country` VALUES ('20', 'Bahrain', '巴林', 'BH', null, null);
INSERT INTO `sys_country` VALUES ('21', 'Burundi', '布隆迪', 'BI', null, null);
INSERT INTO `sys_country` VALUES ('22', 'Benin', '贝宁', 'BJ', null, null);
INSERT INTO `sys_country` VALUES ('23', 'Bermuda', '百慕大', 'BM', null, null);
INSERT INTO `sys_country` VALUES ('24', 'Brunei', '文莱', 'BN', null, null);
INSERT INTO `sys_country` VALUES ('25', 'Bolivia', '玻利维亚', 'BO', null, null);
INSERT INTO `sys_country` VALUES ('26', 'Brazil', '巴西', 'BR', '/images/putaoya.jpg', '');
INSERT INTO `sys_country` VALUES ('27', 'Bahamas', '巴哈马', 'BS', null, null);
INSERT INTO `sys_country` VALUES ('28', 'Bhutan', '不丹', 'BT', null, null);
INSERT INTO `sys_country` VALUES ('29', 'Botswana', '博茨瓦纳', 'BW', null, null);
INSERT INTO `sys_country` VALUES ('30', 'Belarus', '白俄罗斯', 'BY', null, null);
INSERT INTO `sys_country` VALUES ('31', 'Belize', '伯利兹', 'BZ', null, null);
INSERT INTO `sys_country` VALUES ('32', 'Canada', '加拿大', 'CA', null, null);
INSERT INTO `sys_country` VALUES ('33', 'Democratic Republic of th', '刚果民主共和国', 'CD', null, null);
INSERT INTO `sys_country` VALUES ('34', 'Central African Republic', '中非共和国', 'CF', null, null);
INSERT INTO `sys_country` VALUES ('35', 'Democratic Republic of th', '刚果民主共和国', 'CG', null, null);
INSERT INTO `sys_country` VALUES ('36', 'Switzerland', '瑞士', 'CH', null, null);
INSERT INTO `sys_country` VALUES ('37', 'Chile', '智利', 'CL', null, null);
INSERT INTO `sys_country` VALUES ('38', 'Cameroon', '喀麦隆', 'CM', null, null);
INSERT INTO `sys_country` VALUES ('39', 'China', '中国', 'CN', '/images/china.jpg', '');
INSERT INTO `sys_country` VALUES ('40', 'Colombia', '哥伦比亚', 'CO', null, null);
INSERT INTO `sys_country` VALUES ('41', 'Costa Rica', '哥斯达黎加', 'CR', null, null);
INSERT INTO `sys_country` VALUES ('42', 'Cuba', '古巴', 'CU', null, null);
INSERT INTO `sys_country` VALUES ('43', 'Cape Verde', '佛得角', 'CV', null, null);
INSERT INTO `sys_country` VALUES ('44', 'Cyprus', '塞浦路斯', 'CY', null, null);
INSERT INTO `sys_country` VALUES ('45', 'Czech Republic', '捷克共和国', 'CZ', null, null);
INSERT INTO `sys_country` VALUES ('46', 'Germany', '德国', 'DE', '/images/德国.jpg', '');
INSERT INTO `sys_country` VALUES ('47', 'Djibouti', '吉布提', 'DJ', null, null);
INSERT INTO `sys_country` VALUES ('48', 'Denmark', '丹麦', 'DK', null, null);
INSERT INTO `sys_country` VALUES ('49', 'Dominica', '多米尼加', 'DM', null, null);
INSERT INTO `sys_country` VALUES ('50', 'Dominican Republic', '多明尼加共和国', 'DO', null, null);
INSERT INTO `sys_country` VALUES ('51', 'Algeria', '阿尔及利亚', 'DZ', null, null);
INSERT INTO `sys_country` VALUES ('52', 'Ecuador', '厄瓜多尔', 'EC', null, null);
INSERT INTO `sys_country` VALUES ('53', 'Estonia', '爱沙尼亚', 'EE', null, null);
INSERT INTO `sys_country` VALUES ('54', 'Egypt', '埃及', 'EG', '/images/alabo.jpg', '');
INSERT INTO `sys_country` VALUES ('55', 'Eritrea', '厄立特里亚', 'ER', null, null);
INSERT INTO `sys_country` VALUES ('56', 'Spain', '西班牙', 'ES', '/images/西班牙.jpg', '');
INSERT INTO `sys_country` VALUES ('57', 'Ethiopia', '埃塞俄比亚', 'ET', null, null);
INSERT INTO `sys_country` VALUES ('58', 'Finland', '芬兰', 'FI', null, null);
INSERT INTO `sys_country` VALUES ('59', 'Fiji', '斐', 'FJ', null, null);
INSERT INTO `sys_country` VALUES ('60', 'Falkland Islands', '福克兰群岛', 'FK', null, null);
INSERT INTO `sys_country` VALUES ('61', 'Micronesia', '密克罗尼西亚', 'FM', null, null);
INSERT INTO `sys_country` VALUES ('62', 'Faroe Islands', '法罗群岛', 'FO', null, null);
INSERT INTO `sys_country` VALUES ('63', 'France', '法国', 'FR', '/images/法国.jpg', '');
INSERT INTO `sys_country` VALUES ('64', 'Gabon', '加蓬', 'GA', null, null);
INSERT INTO `sys_country` VALUES ('65', 'United Kingdom', '英国', 'UK', '/images/英国.jpg', '');
INSERT INTO `sys_country` VALUES ('66', 'Grenada', '格林纳达', 'GD', null, null);
INSERT INTO `sys_country` VALUES ('67', 'Georgia', '格鲁吉亚', 'GE', null, null);
INSERT INTO `sys_country` VALUES ('68', 'Ghana', '加纳', 'GH', null, null);
INSERT INTO `sys_country` VALUES ('69', 'Gibraltar', '直布罗陀', 'GI', null, null);
INSERT INTO `sys_country` VALUES ('70', 'Gambia', '冈比亚', 'GM', null, null);
INSERT INTO `sys_country` VALUES ('71', 'Guinea', '几内亚', 'GN', null, null);
INSERT INTO `sys_country` VALUES ('72', 'Equatorial Guinea', '赤道几内亚', 'GQ', null, null);
INSERT INTO `sys_country` VALUES ('73', 'Greece', '希腊', 'GR', null, null);
INSERT INTO `sys_country` VALUES ('74', 'Guatemala', '危地马拉', 'GT', null, null);
INSERT INTO `sys_country` VALUES ('75', 'Guinea-Bissau', '几内亚比绍', 'GW', null, null);
INSERT INTO `sys_country` VALUES ('76', 'Guyana', '圭亚那', 'GY', null, null);
INSERT INTO `sys_country` VALUES ('77', 'Hong Kong', '香港', 'HK', null, null);
INSERT INTO `sys_country` VALUES ('78', 'Honduras', '洪都拉斯', 'HN', null, null);
INSERT INTO `sys_country` VALUES ('79', 'Croatia', '克罗地亚', 'HR', null, null);
INSERT INTO `sys_country` VALUES ('80', 'Haiti', '海地', 'HT', null, null);
INSERT INTO `sys_country` VALUES ('81', 'Hungary', '匈牙利', 'HU', null, null);
INSERT INTO `sys_country` VALUES ('82', 'Indonesia', '印度尼西亚', 'ID', null, null);
INSERT INTO `sys_country` VALUES ('83', 'Ireland', '爱尔兰', 'IE', null, null);
INSERT INTO `sys_country` VALUES ('84', 'Israel', '以色列', 'IL', null, null);
INSERT INTO `sys_country` VALUES ('85', 'India', '印度', 'IN', null, null);
INSERT INTO `sys_country` VALUES ('86', 'Iraq', '伊拉克', 'IQ', null, null);
INSERT INTO `sys_country` VALUES ('87', 'Iran', '伊朗', 'IR', null, null);
INSERT INTO `sys_country` VALUES ('88', 'Iceland', '冰岛', 'IS', null, null);
INSERT INTO `sys_country` VALUES ('89', 'Italy', '意大利', 'IT', '/images/意大利.jpg', '');
INSERT INTO `sys_country` VALUES ('90', 'Jamaica', '牙买加', 'JM', null, null);
INSERT INTO `sys_country` VALUES ('91', 'Jordan', '约旦', 'JO', null, null);
INSERT INTO `sys_country` VALUES ('92', 'Japan', '日本', 'JP', null, null);
INSERT INTO `sys_country` VALUES ('93', 'Kenya', '肯尼亚', 'KE', null, null);
INSERT INTO `sys_country` VALUES ('94', 'Kyrgyzstan', '吉尔吉斯斯坦', 'KG', null, null);
INSERT INTO `sys_country` VALUES ('95', 'Cambodia', '柬埔寨', 'KH', null, null);
INSERT INTO `sys_country` VALUES ('96', 'Kiribati', '基里巴斯', 'KI', null, null);
INSERT INTO `sys_country` VALUES ('97', 'Comoros', '科摩罗', 'KM', null, null);
INSERT INTO `sys_country` VALUES ('98', 'Saint Kitts and Nevis', '圣基茨和尼维斯', 'KN', null, null);
INSERT INTO `sys_country` VALUES ('99', 'North Korea', '北朝鲜', 'KP', null, null);
INSERT INTO `sys_country` VALUES ('100', 'South Korea', '韩国', 'KR', null, null);
INSERT INTO `sys_country` VALUES ('101', 'Kuwait', '科威特', 'KW', null, null);
INSERT INTO `sys_country` VALUES ('102', 'Cayman Islands', '开曼群岛', 'KY', null, null);
INSERT INTO `sys_country` VALUES ('103', 'Kazakhstan', '哈萨克斯坦', 'KZ', null, null);
INSERT INTO `sys_country` VALUES ('104', 'Laos', '老挝', 'LA', null, null);
INSERT INTO `sys_country` VALUES ('105', 'Lebanon', '黎巴嫩', 'LB', null, null);
INSERT INTO `sys_country` VALUES ('106', 'Saint Lucia', '圣卢西亚', 'LC', null, null);
INSERT INTO `sys_country` VALUES ('107', 'Liechtenstein', '列支敦士登', 'LI', null, null);
INSERT INTO `sys_country` VALUES ('108', 'Sri Lanka', '斯里兰卡', 'LK', null, null);
INSERT INTO `sys_country` VALUES ('109', 'Liberia', '利比里亚', 'LR', null, null);
INSERT INTO `sys_country` VALUES ('110', 'Lesotho', '莱索托', 'LS', null, null);
INSERT INTO `sys_country` VALUES ('111', 'Lithuania', '立陶宛', 'LT', null, null);
INSERT INTO `sys_country` VALUES ('112', 'Luxembourg', '卢森堡', 'LU', null, null);
INSERT INTO `sys_country` VALUES ('113', 'Latvia', '拉脱维亚', 'LV', null, null);
INSERT INTO `sys_country` VALUES ('114', 'Libya', '利比亚', 'LY', null, null);
INSERT INTO `sys_country` VALUES ('115', 'Morocco', '摩洛哥', 'MA', null, null);
INSERT INTO `sys_country` VALUES ('116', 'Monaco', '摩纳哥', 'MC', null, null);
INSERT INTO `sys_country` VALUES ('117', 'Moldova', '摩尔多瓦', 'MD', null, null);
INSERT INTO `sys_country` VALUES ('118', 'Montenegro', '黑山', 'ME', null, null);
INSERT INTO `sys_country` VALUES ('119', 'Madagascar', '马达加斯加', 'MG', null, null);
INSERT INTO `sys_country` VALUES ('120', 'Macedonia', '马其顿', 'MK', null, null);
INSERT INTO `sys_country` VALUES ('121', 'Mali', '马里', 'ML', null, null);
INSERT INTO `sys_country` VALUES ('122', 'Myanmar', '缅甸', 'MM', null, null);
INSERT INTO `sys_country` VALUES ('123', 'Mongolia', '蒙古', 'MN', null, null);
INSERT INTO `sys_country` VALUES ('124', 'Macao', '澳门', 'MO', null, null);
INSERT INTO `sys_country` VALUES ('125', 'Mauritania', '毛里塔尼亚', 'MR', null, null);
INSERT INTO `sys_country` VALUES ('126', 'Malta', '马耳他', 'MT', null, null);
INSERT INTO `sys_country` VALUES ('127', 'Mauritius', '毛里求斯', 'MU', null, null);
INSERT INTO `sys_country` VALUES ('128', 'Maldives', '马尔代夫', 'MV', null, null);
INSERT INTO `sys_country` VALUES ('129', 'Malawi', '马拉维', 'MW', null, null);
INSERT INTO `sys_country` VALUES ('130', 'Mexico', '墨西哥', 'MX', null, null);
INSERT INTO `sys_country` VALUES ('131', 'Malaysia', '马来西亚', 'MY', null, null);
INSERT INTO `sys_country` VALUES ('132', 'Mozambique', '莫桑比克', 'MZ', null, null);
INSERT INTO `sys_country` VALUES ('133', 'Namibia', '纳米比亚', 'NA', null, null);
INSERT INTO `sys_country` VALUES ('134', 'Niger', '尼日尔', 'NE', null, null);
INSERT INTO `sys_country` VALUES ('135', 'Nigeria', '尼日利亚', 'NG', null, null);
INSERT INTO `sys_country` VALUES ('136', 'Nicaragua', '尼加拉瓜', 'NI', null, null);
INSERT INTO `sys_country` VALUES ('137', 'Netherlands', '荷兰', 'NL', null, null);
INSERT INTO `sys_country` VALUES ('138', 'Norway', '挪威', 'NO', null, null);
INSERT INTO `sys_country` VALUES ('139', 'Nepal', '尼泊尔', 'NP', null, null);
INSERT INTO `sys_country` VALUES ('140', 'Nauru', '瑙鲁', 'NR', null, null);
INSERT INTO `sys_country` VALUES ('141', 'New Zealand', '新西兰', 'NZ', null, null);
INSERT INTO `sys_country` VALUES ('142', 'Oman', '阿曼', 'OM', null, null);
INSERT INTO `sys_country` VALUES ('143', 'Panama', '巴拿马', 'PA', null, null);
INSERT INTO `sys_country` VALUES ('144', 'Peru', '秘鲁', 'PE', null, null);
INSERT INTO `sys_country` VALUES ('145', 'Papua New Guinea', '巴布亚新几内亚', 'PG', null, null);
INSERT INTO `sys_country` VALUES ('146', 'Philippines', '菲律宾', 'PH', null, null);
INSERT INTO `sys_country` VALUES ('147', 'Pakistan', '巴基斯坦', 'PK', null, null);
INSERT INTO `sys_country` VALUES ('148', 'Poland', '波兰', 'PL', null, null);
INSERT INTO `sys_country` VALUES ('149', 'Puerto Rico', '波多黎各', 'PR', null, null);
INSERT INTO `sys_country` VALUES ('150', 'Palestine', '巴勒斯坦', 'PS', null, null);
INSERT INTO `sys_country` VALUES ('151', 'Portugal', '葡萄牙', 'PT', '/images/putaoya.jpg', '');
INSERT INTO `sys_country` VALUES ('152', 'Palau', '帕劳', 'PW', null, null);
INSERT INTO `sys_country` VALUES ('153', 'Paraguay', '巴拉圭', 'PY', null, null);
INSERT INTO `sys_country` VALUES ('154', 'Qatar', '卡塔尔', 'QA', null, null);
INSERT INTO `sys_country` VALUES ('155', 'Romania', '罗马尼亚', 'RO', null, null);
INSERT INTO `sys_country` VALUES ('156', 'Serbia', '塞尔维亚', 'RS', null, null);
INSERT INTO `sys_country` VALUES ('157', 'Russia', '俄罗斯', 'RU', null, null);
INSERT INTO `sys_country` VALUES ('158', 'Rwanda', '卢旺达', 'RW', null, null);
INSERT INTO `sys_country` VALUES ('159', 'Saudi Arabia', '沙特阿拉伯', 'SA', '/images/shatealabo.png', '');
INSERT INTO `sys_country` VALUES ('160', 'Solomon Islands', '所罗门群岛', 'SB', null, null);
INSERT INTO `sys_country` VALUES ('161', 'Seychelles', '塞舌尔', 'SC', null, null);
INSERT INTO `sys_country` VALUES ('162', 'Sudan', '苏丹', 'SD', null, null);
INSERT INTO `sys_country` VALUES ('163', 'Sweden', '瑞典', 'SE', null, null);
INSERT INTO `sys_country` VALUES ('164', 'Singapore', '新加坡', 'SG', null, null);
INSERT INTO `sys_country` VALUES ('165', 'Slovenia', '斯洛文尼亚', 'SI', null, null);
INSERT INTO `sys_country` VALUES ('166', 'Slovak Republic', '斯洛伐克共和国', 'SK', null, null);
INSERT INTO `sys_country` VALUES ('167', 'Sierra Leone', '塞拉利昂', 'SL', null, null);
INSERT INTO `sys_country` VALUES ('168', 'San Marino', '圣马力诺', 'SM', null, null);
INSERT INTO `sys_country` VALUES ('169', 'Senegal', '塞内加尔', 'SN', null, null);
INSERT INTO `sys_country` VALUES ('170', 'Somalia', '索马里', 'SO', null, null);
INSERT INTO `sys_country` VALUES ('171', 'Suriname', '苏里南', 'SR', null, null);
INSERT INTO `sys_country` VALUES ('172', 'Sao Tome and Principe', '圣多美和普林西比', 'ST', null, null);
INSERT INTO `sys_country` VALUES ('173', 'El Salvador', '萨尔瓦多', 'SV', null, null);
INSERT INTO `sys_country` VALUES ('174', 'Syria', '叙利亚', 'SY', null, null);
INSERT INTO `sys_country` VALUES ('175', 'Swaziland', '斯威士兰', 'SZ', null, null);
INSERT INTO `sys_country` VALUES ('176', 'Chad', '乍得', 'TD', null, null);
INSERT INTO `sys_country` VALUES ('177', 'Togo', '多哥', 'TG', null, null);
INSERT INTO `sys_country` VALUES ('178', 'Thailand', '泰国', 'TH', null, null);
INSERT INTO `sys_country` VALUES ('179', 'Tajikistan', '塔吉克斯坦', 'TJ', null, null);
INSERT INTO `sys_country` VALUES ('180', 'Turkmenistan', '土库曼斯坦', 'TM', null, null);
INSERT INTO `sys_country` VALUES ('181', 'Tunisia', '突尼斯', 'TN', null, null);
INSERT INTO `sys_country` VALUES ('182', 'Tonga', '汤加', 'TO', null, null);
INSERT INTO `sys_country` VALUES ('183', 'Turkey', '土耳其', 'TR', null, null);
INSERT INTO `sys_country` VALUES ('184', 'Trinidad and Tobago', '特立尼达和多巴哥', 'TT', null, null);
INSERT INTO `sys_country` VALUES ('185', 'Tuvalu', '图瓦卢', 'TV', null, null);
INSERT INTO `sys_country` VALUES ('186', 'Taiwan', '台湾', 'TW', null, null);
INSERT INTO `sys_country` VALUES ('187', 'Tanzania', '坦桑尼亚', 'TZ', null, null);
INSERT INTO `sys_country` VALUES ('188', 'Ukraine', '乌克兰', 'UA', null, null);
INSERT INTO `sys_country` VALUES ('189', 'Uganda', '乌干达', 'UG', null, null);
INSERT INTO `sys_country` VALUES ('190', 'Uruguay', '乌拉圭', 'UY', null, null);
INSERT INTO `sys_country` VALUES ('191', 'Uzbekistan', '乌兹别克斯坦', 'UZ', null, null);
INSERT INTO `sys_country` VALUES ('192', 'Saint Vincent And The Gre', '圣文森特和格林纳丁斯', 'VC', null, null);
INSERT INTO `sys_country` VALUES ('193', 'Venezuela', '委内瑞拉', 'VE', null, null);
INSERT INTO `sys_country` VALUES ('194', 'British Virgin Islands', '英属维尔京群岛', 'VG', null, null);
INSERT INTO `sys_country` VALUES ('195', 'Vietnam', '越南', 'VN', null, null);
INSERT INTO `sys_country` VALUES ('196', 'Vanuatu', '瓦努阿图', 'VU', null, null);
INSERT INTO `sys_country` VALUES ('197', 'Wallis and Futuna', '瓦利斯和富图纳', 'WF', null, null);
INSERT INTO `sys_country` VALUES ('198', 'Western Samoa', '西萨摩亚', 'WS', null, null);
INSERT INTO `sys_country` VALUES ('199', 'Yemen', '也门', 'YE', null, null);
INSERT INTO `sys_country` VALUES ('200', 'South Africa', '南非', 'ZA', null, null);
INSERT INTO `sys_country` VALUES ('201', 'Zambia', '赞比亚', 'ZM', null, null);
INSERT INTO `sys_country` VALUES ('202', 'Zimbabwe', '津巴布韦', 'ZW', null, null);

-- ----------------------------
-- Table structure for sys_crontab
-- ----------------------------
DROP TABLE IF EXISTS `sys_crontab`;
CREATE TABLE `sys_crontab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '定时任务名称',
  `route` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '任务路由',
  `crontab_str` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'crontab格式',
  `switch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '任务开关 0关闭 1开启',
  `status` tinyint(1) DEFAULT '0' COMMENT '任务运行状态 0正常 1任务报错',
  `last_rundate` datetime DEFAULT NULL COMMENT '任务上次运行时间',
  `next_rundate` datetime DEFAULT NULL COMMENT '任务下次运行时间',
  `execmemory` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '任务执行消耗内存(单位/byte)',
  `exectime` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '任务执行消耗时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of sys_crontab
-- ----------------------------
INSERT INTO `sys_crontab` VALUES ('3', '周天凌晨3点备份数据库', 'dump/create -db=db -gz -s', '0 3 * * 0', '1', '0', '2018-11-05 09:08:00', '2018-11-11 03:00:00', '0.00', '16.29');
INSERT INTO `sys_crontab` VALUES ('10', '维护订单表状态', 'order/update', '*/30 * * * *', '1', '0', '2018-11-05 17:30:00', '2018-11-05 18:00:00', '0.00', '0.55');
INSERT INTO `sys_crontab` VALUES ('12', '每个小时更新统计日志', 'log/daily-log', '0 * * * *', '1', '0', '2018-11-05 17:00:00', '2018-11-05 18:00:00', '0.00', '0.99');
INSERT INTO `sys_crontab` VALUES ('15', '同步用户在线状态', 'mac/sync', '*/15 * * * *', '1', '0', '2018-11-05 17:45:00', '2018-11-05 18:00:00', '0.00', '1.77');
INSERT INTO `sys_crontab` VALUES ('16', '离线统计昨天的日志', 'log/offline', '30 0 * * *', '1', '0', '2018-11-05 09:08:00', '2018-11-06 00:30:00', '0.00', '11.22');
INSERT INTO `sys_crontab` VALUES ('17', '更新预告中间表', 'parade/create-cache', '10 0 * * *', '1', '0', '2018-11-05 09:08:00', '2018-11-06 00:10:00', '0.00', '11.83');
INSERT INTO `sys_crontab` VALUES ('19', 'TEST', 'hello/test', '*/1 * * * *', '1', '0', '2018-11-05 17:55:00', '2018-11-05 17:56:00', '0.00', '0.80');

-- ----------------------------
-- Table structure for yii2_admin
-- ----------------------------
DROP TABLE IF EXISTS `yii2_admin`;
CREATE TABLE `yii2_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT '密码',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `reg_ip` int(11) NOT NULL DEFAULT '0' COMMENT '创建或注册IP',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态 1正常 0禁用',
  `created_at` int(11) NOT NULL COMMENT '创建或注册时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_admin
-- ----------------------------
INSERT INTO `yii2_admin` VALUES ('1', 'admin', 'SbSY36BLw3V2lU-GB7ZAzCVJKDFx82IJ', '$2y$13$8/lchlfwG1xrO.gd5MWHfuuBpqQeK/6aDtZvvMUliTmKbfUAkK.v6', '876505905@qq.com', '2130706433', '1541387411', '2147483647', '1', '1482305564', '1541398061');
INSERT INTO `yii2_admin` VALUES ('2', 'apk_manager', 'iv8Ck65kWUJzqPwxf9TQHIEWj8hGP98K', '$2y$13$q7Ck2t1N78JbneQkfgVjcuT6EvxSXOPLYfTGRIjldBw.SUENA8Kgm', 'newpoyang@163.com', '2004602048', '1539834796', '1934492162', '1', '1534213384', '1539834796');

-- ----------------------------
-- Table structure for yii2_auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_assignment`;
CREATE TABLE `yii2_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `yii2_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_assignment
-- ----------------------------
INSERT INTO `yii2_auth_assignment` VALUES ('app管理员', '2', '1534213387');
INSERT INTO `yii2_auth_assignment` VALUES ('超级管理员', '1', '1520270508');
INSERT INTO `yii2_auth_assignment` VALUES ('超级管理员', '9', '1519830838');

-- ----------------------------
-- Table structure for yii2_auth_item
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_item`;
CREATE TABLE `yii2_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `yii2_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `yii2_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_item
-- ----------------------------
INSERT INTO `yii2_auth_item` VALUES ('#', '2', '', '#', null, '1519808376', '1534397550');
INSERT INTO `yii2_auth_item` VALUES ('admin/auth', '2', '', 'admin/auth', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('admin/create', '2', '', 'admin/create', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('admin/delete', '2', '', 'admin/delete', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('admin/index', '2', '', 'admin/index', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('admin/update', '2', '', 'admin/update', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('apk-detail/create', '2', '', 'apk-detail/create', null, '1534323523', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-detail/delete', '2', '', 'apk-detail/delete', null, '1534323801', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-detail/index', '2', '', 'apk-detail/index', null, '1534324049', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-detail/update', '2', '', 'apk-detail/update', null, '1534323523', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-detail/view', '2', '', 'apk-detail/view', null, '1534323801', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-list/create', '2', '', 'apk-list/create', null, '1534397461', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-list/delete', '2', '', 'apk-list/delete', null, '1534304609', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-list/index', '2', '', 'apk-list/index', null, '1534213343', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('apk-list/set-scheme', '2', '', 'apk-list/set-scheme', null, '1534401437', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-list/update', '2', '', 'apk-list/update', null, '1534304535', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('apk-list/view', '2', '', 'apk-list/view', null, '1534304609', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('app-boot-picture/index', '2', '', 'app-boot-picture/index', null, '1534213911', '1534213911');
INSERT INTO `yii2_auth_item` VALUES ('app-list/set-scheme', '2', '', 'app-list/set-scheme', null, '1534401297', '1534401297');
INSERT INTO `yii2_auth_item` VALUES ('app-list/update', '2', '', 'app-list/update', null, '1534304535', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('app-menu/index', '2', '', 'app-menu/index', null, '1534213911', '1534213911');
INSERT INTO `yii2_auth_item` VALUES ('app管理员', '1', 'app管理员', null, null, '1534213325', '1534213325');
INSERT INTO `yii2_auth_item` VALUES ('backup/default/index', '2', '', 'backup/default/index', null, '1519808877', '1519811523');
INSERT INTO `yii2_auth_item` VALUES ('combo/create', '2', '', 'combo/create', null, '1519808877', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('combo/delete', '2', '', 'combo/delete', null, '1519808877', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('combo/index', '2', '', 'combo/index', null, '1517996186', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('combo/update', '2', '', 'combo/update', null, '1519808877', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('combo/view', '2', '', 'combo/view', null, '1519378925', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('config/attachment', '2', '', 'config/attachment', null, '1484734191', '1534401435');
INSERT INTO `yii2_auth_item` VALUES ('config/basic', '2', '', 'config/basic', null, '1484734191', '1534401435');
INSERT INTO `yii2_auth_item` VALUES ('config/send-mail', '2', '', 'config/send-mail', null, '1484734191', '1534401435');
INSERT INTO `yii2_auth_item` VALUES ('database/export', '2', '', 'database/export', null, '1484734305', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('excel-setting/update', '2', '', 'excel-setting/update', null, '1519451875', '1519451875');
INSERT INTO `yii2_auth_item` VALUES ('excel/import', '2', '', 'excel/import', null, '1519436284', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('excel/index', '2', '', 'excel/index', null, '1519436031', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('index/frame', '2', '', 'index/frame', null, '1518057962', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('index/index', '2', '', 'index/index', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('log/index', '2', '', 'log/index', null, '1534213984', '1534397550');
INSERT INTO `yii2_auth_item` VALUES ('menu/create', '2', '', 'menu/create', null, '1484734191', '1534401435');
INSERT INTO `yii2_auth_item` VALUES ('menu/delete', '2', '', 'menu/delete', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('menu/index', '2', '', 'menu/index', null, '1484734191', '1534401435');
INSERT INTO `yii2_auth_item` VALUES ('menu/update', '2', '', 'menu/update', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('note/index', '2', '', 'note/index', null, '1534213984', '1534397550');
INSERT INTO `yii2_auth_item` VALUES ('order/create', '2', '', 'order/create', null, '1518074401', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('order/delete', '2', '', 'order/delete', null, '1519450583', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('order/index', '2', '', 'order/index', null, '1517996186', '1534401437');
INSERT INTO `yii2_auth_item` VALUES ('order/update', '2', '', 'order/update', null, '1519450583', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('order/view', '2', '', 'order/view', null, '1519377930', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('product/delete', '2', '', 'product/delete', null, '1519808877', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('product/index', '2', '', 'product/index', null, '1517996186', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('product/update', '2', '', 'product/update', null, '1519808877', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('product/view', '2', '', 'product/view', null, '1519378499', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('role/auth', '2', '', 'role/auth', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('role/create', '2', '', 'role/create', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('role/delete', '2', '', 'role/delete', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('role/export-setting', '2', '', 'role/export-setting', null, '1519438814', '1519451875');
INSERT INTO `yii2_auth_item` VALUES ('role/index', '2', '', 'role/index', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('role/update', '2', '', 'role/update', null, '1484734191', '1534401436');
INSERT INTO `yii2_auth_item` VALUES ('servicer/create', '2', '', 'servicer/create', null, '1519810140', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('servicer/index', '2', '', 'servicer/index', null, '1517996187', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('servicer/view', '2', '', 'servicer/view', null, '1519884478', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('transator/delete', '2', '', 'transator/delete', null, '1519810140', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('transator/index', '2', '', 'transator/index', null, '1517996187', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('transator/update', '2', '', 'transator/update', null, '1519810140', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('transator/view', '2', '', 'transator/view', null, '1519884478', '1520059861');
INSERT INTO `yii2_auth_item` VALUES ('超级管理员', '1', '授权所有权限', null, null, '1484712662', '1519884549');

-- ----------------------------
-- Table structure for yii2_auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_item_child`;
CREATE TABLE `yii2_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `yii2_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `yii2_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `yii2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_item_child
-- ----------------------------
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', '#');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-detail/create');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-detail/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-detail/index');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-detail/update');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-detail/view');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-list/create');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-list/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-list/index');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-list/update');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'apk-list/view');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'app-list/update');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'index/frame');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'index/index');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'log/index');
INSERT INTO `yii2_auth_item_child` VALUES ('app管理员', 'note/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'admin/auth');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'admin/create');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'admin/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'admin/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'admin/update');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-detail/create');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-detail/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-detail/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-detail/update');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-detail/view');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-list/create');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-list/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-list/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-list/set-scheme');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-list/update');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'apk-list/view');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'app-list/update');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'config/attachment');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'config/basic');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'config/send-mail');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'index/frame');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'index/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'menu/create');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'menu/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'menu/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'menu/update');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'order/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'role/auth');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'role/create');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'role/delete');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'role/index');
INSERT INTO `yii2_auth_item_child` VALUES ('超级管理员', 'role/update');

-- ----------------------------
-- Table structure for yii2_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `yii2_auth_rule`;
CREATE TABLE `yii2_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_auth_rule
-- ----------------------------
INSERT INTO `yii2_auth_rule` VALUES ('', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:0:\"\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1518057980;}', '1484734191', '1518057980');
INSERT INTO `yii2_auth_rule` VALUES ('#', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:1:\"#\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519808376;s:9:\"updatedAt\";i:1534397550;}', '1519808376', '1534397550');
INSERT INTO `yii2_auth_rule` VALUES ('admin/auth', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:10:\"admin/auth\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('admin/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"admin/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('admin/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"admin/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('admin/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"admin/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('admin/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"admin/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('apk-detail/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:17:\"apk-detail/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534323523;s:9:\"updatedAt\";i:1534401436;}', '1534323523', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('apk-detail/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:17:\"apk-detail/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534323801;s:9:\"updatedAt\";i:1534401437;}', '1534323801', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-detail/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:16:\"apk-detail/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534324048;s:9:\"updatedAt\";i:1534401437;}', '1534324048', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-detail/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:17:\"apk-detail/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534323523;s:9:\"updatedAt\";i:1534401437;}', '1534323523', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-detail/view', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"apk-detail/view\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534323801;s:9:\"updatedAt\";i:1534401437;}', '1534323801', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-list/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"apk-list/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534397461;s:9:\"updatedAt\";i:1534401437;}', '1534397461', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-list/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"apk-list/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534304609;s:9:\"updatedAt\";i:1534401437;}', '1534304609', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-list/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:14:\"apk-list/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534213343;s:9:\"updatedAt\";i:1534401436;}', '1534213343', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('apk-list/set-scheme', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:19:\"apk-list/set-scheme\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534401437;s:9:\"updatedAt\";i:1534401437;}', '1534401437', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-list/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"apk-list/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534304535;s:9:\"updatedAt\";i:1534401437;}', '1534304535', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('apk-list/view', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:13:\"apk-list/view\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534304609;s:9:\"updatedAt\";i:1534401436;}', '1534304609', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('app-boot-picture/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:22:\"app-boot-picture/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534213911;s:9:\"updatedAt\";i:1534213911;}', '1534213911', '1534213911');
INSERT INTO `yii2_auth_rule` VALUES ('app-list/set-scheme', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:19:\"app-list/set-scheme\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534401297;s:9:\"updatedAt\";i:1534401297;}', '1534401297', '1534401297');
INSERT INTO `yii2_auth_rule` VALUES ('app-list/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"app-list/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534304535;s:9:\"updatedAt\";i:1534401436;}', '1534304535', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('app-menu/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:14:\"app-menu/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534213911;s:9:\"updatedAt\";i:1534213911;}', '1534213911', '1534213911');
INSERT INTO `yii2_auth_rule` VALUES ('backup/default/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:20:\"backup/default/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519808877;s:9:\"updatedAt\";i:1519811523;}', '1519808877', '1519811523');
INSERT INTO `yii2_auth_rule` VALUES ('combo/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"combo/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519808877;s:9:\"updatedAt\";i:1520059861;}', '1519808877', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('combo/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"combo/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519808877;s:9:\"updatedAt\";i:1520059861;}', '1519808877', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('combo/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"combo/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1517996186;s:9:\"updatedAt\";i:1520059861;}', '1517996186', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('combo/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"combo/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519808877;s:9:\"updatedAt\";i:1520059861;}', '1519808877', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('combo/view', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:10:\"combo/view\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519378925;s:9:\"updatedAt\";i:1520059861;}', '1519378925', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('config/attachment', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:17:\"config/attachment\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401435;}', '1484734191', '1534401435');
INSERT INTO `yii2_auth_rule` VALUES ('config/basic', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"config/basic\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401435;}', '1484734191', '1534401435');
INSERT INTO `yii2_auth_rule` VALUES ('config/send-mail', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:16:\"config/send-mail\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401435;}', '1484734191', '1534401435');
INSERT INTO `yii2_auth_rule` VALUES ('database/export', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"database/export\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734305;s:9:\"updatedAt\";i:1520059861;}', '1484734305', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('excel-setting/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:20:\"excel-setting/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519451875;s:9:\"updatedAt\";i:1519451875;}', '1519451875', '1519451875');
INSERT INTO `yii2_auth_rule` VALUES ('excel/import', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"excel/import\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519436284;s:9:\"updatedAt\";i:1520059861;}', '1519436284', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('excel/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"excel/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519436031;s:9:\"updatedAt\";i:1520059861;}', '1519436031', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('index/frame', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"index/frame\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1518057962;s:9:\"updatedAt\";i:1534401436;}', '1518057962', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('index/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"index/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('log/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:9:\"log/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534213984;s:9:\"updatedAt\";i:1534397550;}', '1534213984', '1534397550');
INSERT INTO `yii2_auth_rule` VALUES ('menu/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"menu/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401435;}', '1484734191', '1534401435');
INSERT INTO `yii2_auth_rule` VALUES ('menu/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"menu/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('menu/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:10:\"menu/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401435;}', '1484734191', '1534401435');
INSERT INTO `yii2_auth_rule` VALUES ('menu/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"menu/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('note/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:10:\"note/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1534213984;s:9:\"updatedAt\";i:1534397550;}', '1534213984', '1534397550');
INSERT INTO `yii2_auth_rule` VALUES ('order/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"order/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1518074401;s:9:\"updatedAt\";i:1520059861;}', '1518074401', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('order/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"order/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519450583;s:9:\"updatedAt\";i:1520059861;}', '1519450583', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('order/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"order/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1517996186;s:9:\"updatedAt\";i:1534401437;}', '1517996186', '1534401437');
INSERT INTO `yii2_auth_rule` VALUES ('order/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"order/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519450583;s:9:\"updatedAt\";i:1520059861;}', '1519450583', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('order/view', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:10:\"order/view\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519377930;s:9:\"updatedAt\";i:1520059861;}', '1519377930', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('product/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:14:\"product/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519808877;s:9:\"updatedAt\";i:1520059861;}', '1519808877', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('product/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:13:\"product/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1517996186;s:9:\"updatedAt\";i:1520059861;}', '1517996186', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('product/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:14:\"product/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519808877;s:9:\"updatedAt\";i:1520059861;}', '1519808877', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('product/view', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:12:\"product/view\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519378499;s:9:\"updatedAt\";i:1520059861;}', '1519378499', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('role/auth', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:9:\"role/auth\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('role/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"role/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('role/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"role/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('role/export-setting', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:19:\"role/export-setting\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519438814;s:9:\"updatedAt\";i:1519451875;}', '1519438814', '1519451875');
INSERT INTO `yii2_auth_rule` VALUES ('role/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:10:\"role/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('role/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:11:\"role/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1484734191;s:9:\"updatedAt\";i:1534401436;}', '1484734191', '1534401436');
INSERT INTO `yii2_auth_rule` VALUES ('servicer/create', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"servicer/create\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519810140;s:9:\"updatedAt\";i:1520059861;}', '1519810140', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('servicer/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:14:\"servicer/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1517996187;s:9:\"updatedAt\";i:1520059861;}', '1517996187', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('servicer/view', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:13:\"servicer/view\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519884478;s:9:\"updatedAt\";i:1520059861;}', '1519884478', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('transator/delete', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:16:\"transator/delete\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519810140;s:9:\"updatedAt\";i:1520059861;}', '1519810140', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('transator/index', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:15:\"transator/index\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1517996187;s:9:\"updatedAt\";i:1520059861;}', '1517996187', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('transator/update', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:16:\"transator/update\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519810140;s:9:\"updatedAt\";i:1520059861;}', '1519810140', '1520059861');
INSERT INTO `yii2_auth_rule` VALUES ('transator/view', 'O:23:\"backend\\models\\AuthRule\":4:{s:4:\"name\";s:14:\"transator/view\";s:30:\"\0backend\\models\\AuthRule\0_rule\";r:1;s:9:\"createdAt\";i:1519884478;s:9:\"updatedAt\";i:1520059861;}', '1519884478', '1520059861');

-- ----------------------------
-- Table structure for yii2_config
-- ----------------------------
DROP TABLE IF EXISTS `yii2_config`;
CREATE TABLE `yii2_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyid` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `keyid` (`keyid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_config
-- ----------------------------
INSERT INTO `yii2_config` VALUES ('1', 'basic', '', '{\"sitename\":\"IPTV Management System\",\"url\":\"\",\"logo\":\"\\/statics\\/themes\\/admin\\/images\\/logo.png\",\"seo_keywords\":\"\",\"seo_description\":\"\",\"copyright\":\"@Yii2 CMS\",\"statcode\":\"\",\"close\":\"0\",\"close_reason\":\"\\u7ad9\\u70b9\\u5347\\u7ea7\\u4e2d, \\u8bf7\\u7a0d\\u540e\\u8bbf\\u95ee!\"}');
INSERT INTO `yii2_config` VALUES ('2', 'sendmail', '', '{\"mail_type\":\"0\",\"smtp_server\":\"smtp.qq.com\",\"smtp_port\":\"25\",\"auth\":\"1\",\"openssl\":\"1\",\"smtp_user\":\"771405950\",\"smtp_pwd\":\"qiaoBo1989122\",\"send_email\":\"771405950@qq.com\",\"nickname\":\"\\u8fb9\\u8d70\\u8fb9\\u4e54\",\"sign\":\"<hr \\/>\\r\\n\\u90ae\\u4ef6\\u7b7e\\u540d\\uff1a\\u6b22\\u8fce\\u8bbf\\u95ee <a href=\\\"http:\\/\\/www.test-yii2cms.com\\\" target=\\\"_blank\\\">Yii2 CMS<\\/a>\"}');
INSERT INTO `yii2_config` VALUES ('3', 'attachment', '', '{\"attachment_size\":\"2048\",\"attachment_suffix\":\"jpg|jpeg|gif|bmp|png\",\"watermark_enable\":\"1\",\"watermark_pos\":\"0\",\"watermark_text\":\"Yii2 CMS\"}');
INSERT INTO `yii2_config` VALUES ('4', 'ottcharge', '', '{\"mode\":\"2\",\"mode_desc\":\"模式一:会员开通后可观看所有直播分类\",\"free_day\":\"7\"}');

-- ----------------------------
-- Table structure for yii2_menu
-- ----------------------------
DROP TABLE IF EXISTS `yii2_menu`;
CREATE TABLE `yii2_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `icon_style` varchar(50) NOT NULL DEFAULT '',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yii2_menu
-- ----------------------------
INSERT INTO `yii2_menu` VALUES ('1', '0', 'My panel', '#', 'fa-home', '0', '0');
INSERT INTO `yii2_menu` VALUES ('2', '0', 'System', 'config/basic', 'fa-cogs', '1', '94');
INSERT INTO `yii2_menu` VALUES ('3', '0', 'Admin', 'admin/index', 'fa-user', '1', '80');
INSERT INTO `yii2_menu` VALUES ('8', '1', 'System info', '#', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('9', '2', 'Site configuration', 'config/basic', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('10', '2', 'Menu List', 'menu/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('11', '3', 'Admin', 'admin/index', '', '1', '1');
INSERT INTO `yii2_menu` VALUES ('12', '3', 'Role', 'role/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('16', '5', '用户管理', '', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('17', '2', 'Database', 'db-manager/default', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('20', '9', 'Basic configuration', 'config/basic', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('21', '9', 'Mailbox configuration', 'config/send-mail', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('22', '9', 'Accessory configuration', 'config/attachment', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('23', '10', 'Create', 'menu/create', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('24', '10', 'Update', 'menu/update', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('25', '10', 'Delete', 'menu/delete', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('26', '11', 'Create', 'admin/create', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('27', '11', 'Update', 'admin/update', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('28', '11', 'Auth', 'admin/auth', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('29', '11', 'Delete', 'admin/delete', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('30', '12', 'Create', 'role/create', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('31', '12', 'Update', 'role/update', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('32', '12', 'Auth', 'role/auth', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('33', '12', 'Delete', 'role/delete', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('41', '40', '国家列表', 'country/index', 'fa-flag', '1', '1');
INSERT INTO `yii2_menu` VALUES ('48', '0', 'System Information', 'index/index', 'fa-home', '1', '0');
INSERT INTO `yii2_menu` VALUES ('49', '48', 'System Information', 'index/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('51', '48', 'Left Menu', 'index/frame', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('72', '0', 'Karaoke', 'karaoke/index', ' fa-microphone', '0', '98');
INSERT INTO `yii2_menu` VALUES ('73', '72', 'Karaoke List', 'karaoke/index', '', '1', '1');
INSERT INTO `yii2_menu` VALUES ('74', '0', 'User', 'mac/index', 'fa-group', '1', '97');
INSERT INTO `yii2_menu` VALUES ('75', '74', 'TV User', 'mac/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('76', '48', 'Interface log', 'log/index', '', '1', '4');
INSERT INTO `yii2_menu` VALUES ('78', '2', 'Timed task', 'crontab/index', '', '1', '5');
INSERT INTO `yii2_menu` VALUES ('79', '0', 'App', 'apk-list/index', 'fa-android', '1', '96');
INSERT INTO `yii2_menu` VALUES ('80', '79', 'APP List', 'apk-list/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('81', '48', 'Update Information', 'note/index', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('82', '0', 'Order', '/', 'fa-list', '1', '97');
INSERT INTO `yii2_menu` VALUES ('83', '82', 'Order List', 'order/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('84', '0', 'IPTV', '/', 'fa-tv', '1', '98');
INSERT INTO `yii2_menu` VALUES ('85', '84', 'IPTV Genre', 'vod-list/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('86', '74', 'Mobile User', 'user/index', '', '1', '2');
INSERT INTO `yii2_menu` VALUES ('87', '84', 'Banner List', 'banner/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('88', '0', 'OTT', '/', 'fa-globe', '1', '99');
INSERT INTO `yii2_menu` VALUES ('89', '88', 'OTT Genre', 'main-class/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('91', '88', 'Banner List', 'ott-banner/index', '', '1', '2');
INSERT INTO `yii2_menu` VALUES ('92', '88', 'Recommend List', 'ott-channel/recommend', '', '1', '4');
INSERT INTO `yii2_menu` VALUES ('93', '88', 'EPG', 'parade/index', '', '1', '99');
INSERT INTO `yii2_menu` VALUES ('94', '88', 'Event Genre', 'ott-event/index', '', '1', '5');
INSERT INTO `yii2_menu` VALUES ('95', '88', 'Main Event', 'major-event/index', '', '1', '6');
INSERT INTO `yii2_menu` VALUES ('98', '2', 'Country List', 'country/index', '', '0', '1');
INSERT INTO `yii2_menu` VALUES ('99', '0', 'Firmware', '/', 'fa-cloud-upload', '1', '95');
INSERT INTO `yii2_menu` VALUES ('100', '99', 'Firmware List', 'firmware-class/index', '', '1', '1');
INSERT INTO `yii2_menu` VALUES ('101', '99', 'Associated order', 'dvb-order/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('102', '74', 'Client', 'client/index', '', '0', '3');
INSERT INTO `yii2_menu` VALUES ('103', '48', 'Real-time statistics', 'log/now', '', '1', '5');
INSERT INTO `yii2_menu` VALUES ('104', '88', 'OTT Orders', 'ott-order/index', '', '1', '9');
INSERT INTO `yii2_menu` VALUES ('105', '88', 'Price List', 'ott-price-list/index', '', '1', '8');
INSERT INTO `yii2_menu` VALUES ('106', '79', 'Boot Picture', 'app-boot-picture/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('107', '82', 'Recharge card', 'renewal-card/index', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('108', '88', 'Scheme', 'scheme/index', '', '1', '12');
INSERT INTO `yii2_menu` VALUES ('109', '88', 'Global Search', 'ott-channel/global-search', '', '1', '2');
INSERT INTO `yii2_menu` VALUES ('110', '80', 'Update', 'app-list/update', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('111', '80', 'Create', 'apk-list/update', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('112', '80', 'Delete', 'apk-list/delete', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('113', '80', 'View', 'apk-list/view', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('114', '80', 'Edit apk', 'apk-detail/create', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('115', '80', 'Update app', 'apk-detail/update', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('116', '80', 'Delete Apk', 'apk-detail/delete', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('117', '80', 'View Apk', 'apk-detail/view', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('118', '80', 'Apk List', 'apk-detail/index', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('119', '80', 'create App', 'apk-list/create', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('120', '80', 'update app', 'apk-list/update', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('121', '80', 'Delete App ', 'apk-list/delete', '', '0', '0');
INSERT INTO `yii2_menu` VALUES ('122', '80', 'set scheme', 'apk-list/set-scheme', '', '1', '0');
INSERT INTO `yii2_menu` VALUES ('123', '82', '分类权限', 'ott-access/index', '', '1', '3');
