-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- 主机: 10.165.35.203:3306
-- 生成日期: 2020 年 06 月 26 日 08:11
-- 服务器版本: 1.0.12
-- PHP 版本: 5.5.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `wpoy`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_bank`
-- 

CREATE TABLE `amazpot_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerid` varchar(32) NOT NULL,
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `capacity` int(10) unsigned NOT NULL DEFAULT '200',
  `coin` int(10) unsigned NOT NULL DEFAULT '0',
  `growcoin` tinyint(1) unsigned NOT NULL DEFAULT '5',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ownerid` (`ownerid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_coinhistory`
-- 

CREATE TABLE `amazpot_coinhistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerid` varchar(32) CHARACTER SET utf8 NOT NULL,
  `value` int(10) NOT NULL,
  `createdate` int(10) unsigned NOT NULL,
  `msg` varchar(50) CHARACTER SET utf8 NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ownerid` (`ownerid`,`createdate`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_days`
-- 

CREATE TABLE `amazpot_days` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) unsigned NOT NULL,
  `date_fmt` varchar(10) NOT NULL,
  `type` varchar(10) NOT NULL,
  `type_cn` varchar(10) NOT NULL,
  `vgain` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vrare` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vsale` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `eventid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_formids`
-- 

CREATE TABLE `amazpot_formids` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerid` varchar(32) CHARACTER SET utf8 NOT NULL,
  `formid` varchar(32) CHARACTER SET utf8 NOT NULL,
  `createdate` int(10) unsigned NOT NULL,
  `used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ownerid` (`ownerid`,`createdate`),
  KEY `used` (`used`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_members`
-- 

CREATE TABLE `amazpot_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(32) NOT NULL,
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `headimg` varchar(150) NOT NULL,
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1男2女',
  `area` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `city` varchar(20) NOT NULL,
  `province` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  `age` tinyint(2) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '10' COMMENT '普通10，101高级用户，管理员251',
  `joindate` int(10) unsigned NOT NULL,
  `lastlogin` int(10) unsigned NOT NULL COMMENT '最后登录',
  `baned` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '封禁',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分/代币',
  `prestige` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '声望',
  `cupinfo` varchar(100) NOT NULL,
  `pottype` varchar(4) NOT NULL DEFAULT '01',
  `cartype` varchar(4) NOT NULL COMMENT '汽车类型',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`),
  KEY `baned` (`baned`),
  KEY `coin` (`coin`),
  KEY `prestige` (`prestige`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_plantlog`
-- 

CREATE TABLE `amazpot_plantlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plantid` int(10) unsigned NOT NULL,
  `ownerid` varchar(32) NOT NULL,
  `actiontype` tinyint(1) unsigned NOT NULL COMMENT '1除虫2浇水3施肥4采摘',
  `msg` varchar(100) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `plantid` (`plantid`,`ownerid`,`createtime`),
  KEY `actiontype` (`actiontype`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_plants`
-- 

CREATE TABLE `amazpot_plants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerid` varchar(32) NOT NULL,
  `type` varchar(10) NOT NULL,
  `type_cn` varchar(6) NOT NULL COMMENT '类型中文',
  `rare` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vworm` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '虫害度',
  `vgain` int(3) unsigned NOT NULL DEFAULT '100' COMMENT '丰收度',
  `vthirsty` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '干旱度',
  `vrare` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '稀有度',
  `seedtime` int(10) unsigned NOT NULL COMMENT '播种时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `growduration` int(10) unsigned NOT NULL COMMENT '成熟时长',
  `stepduration` int(10) unsigned NOT NULL,
  `quantity` int(5) unsigned NOT NULL DEFAULT '0',
  `ori_quantity` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '原结果数量',
  `unitprice` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerid` (`ownerid`,`type`),
  KEY `quantity` (`quantity`),
  KEY `seedtime` (`seedtime`,`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_seeds`
-- 

CREATE TABLE `amazpot_seeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `type_cn` varchar(10) NOT NULL COMMENT '类型中文',
  `growduration` int(10) unsigned NOT NULL COMMENT '成熟时长',
  `stepduration` int(10) unsigned NOT NULL COMMENT '级别时长',
  `quantity` int(10) unsigned NOT NULL,
  `rare` tinyint(1) unsigned NOT NULL,
  `unitprice` int(10) unsigned NOT NULL COMMENT '果实出售单价',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_shop`
-- 

CREATE TABLE `amazpot_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `type` varchar(50) NOT NULL,
  `effect` varchar(50) NOT NULL COMMENT '特殊效果',
  `category` tinyint(1) NOT NULL COMMENT '0其他，1花盆，2汽车',
  `price` int(10) unsigned NOT NULL COMMENT '售价',
  `unit_type` varchar(10) NOT NULL DEFAULT '0' COMMENT '单位类型，0金币，植物type',
  `onlyonce` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '一次性',
  PRIMARY KEY (`id`),
  KEY `type` (`category`,`price`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_shoppinglog`
-- 

CREATE TABLE `amazpot_shoppinglog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerid` varchar(32) NOT NULL,
  `shopid` int(10) unsigned NOT NULL,
  `category` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerid` (`ownerid`,`shopid`,`createtime`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_visitlog`
-- 

CREATE TABLE `amazpot_visitlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(32) NOT NULL,
  `ownerid` varchar(32) NOT NULL,
  `headimg` varchar(150) NOT NULL,
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `actiontype` tinyint(1) unsigned NOT NULL COMMENT '1除虫2浇水3施肥4采摘',
  `msg` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`ownerid`,`createtime`),
  KEY `actiontype` (`actiontype`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `amazpot_warehouse`
-- 

CREATE TABLE `amazpot_warehouse` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerid` varchar(32) NOT NULL,
  `type` varchar(10) NOT NULL,
  `type_cn` varchar(10) NOT NULL COMMENT '类型中文',
  `quantity` int(10) unsigned NOT NULL,
  `unitprice` int(10) unsigned NOT NULL COMMENT '果实出售单价',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `ownerid` (`ownerid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
