-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- 主机: 10.165.35.203:3306
-- 生成日期: 2020 年 06 月 29 日 16:31
-- 服务器版本: 1.0.12
-- PHP 版本: 5.5.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `wpoy`
-- 

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- 
-- 导出表中的数据 `amazpot_shop`
-- 

INSERT INTO `amazpot_shop` (`id`, `name`, `type`, `effect`, `category`, `price`, `unit_type`, `onlyonce`) VALUES 
(1, '红陶花盆', '01', '无', 1, 0, '0', 1),
(2, '红陶边纹花盆', '02', '无', 1, 1000, '0', 1),
(3, '赤陶剑纹花盆', '03', '无', 1, 3000, '0', 1),
(4, '赤陶云纹花盆', '04', '无', 1, 5000, '0', 1),
(5, '赤陶灰边花盆', '05', '无', 1, 1000, '0', 1),
(6, '红陶白纹花盆', '06', '无', 1, 3000, '0', 1),
(7, '蛋壳花盆', '07', '收成+5%', 1, 10000, '0', 1),
(8, '小鲸鱼花盆', '08', '收成+10%', 1, 20000, '0', 1),
(9, '北欧铁艺花盆', '09', '收成+15%', 1, 30000, '0', 1),
(10, '司母戊鼎', '10', '无', 1, 500000, '0', 1),
(11, '笑眯眯马克杯', '11', '收成+10%', 1, 20000, '0', 1),
(12, '土豪金盆', '99', '无', 1, 1000000, '0', 1),
(13, 'Swart', '01', '无', 2, 160000, '0', 1),
(14, 'Meni', '02', '无', 2, 240000, '0', 1),
(15, '小甲虫', '03', '无', 2, 200000, '0', 1),
(16, '宝驴跑车', '04', '无', 2, 350000, '0', 1),
(17, '笨驰四驱', '05', '无', 2, 450000, '0', 1),
(18, '笨驰跑车', '06', '无', 2, 600000, '0', 1),
(19, '宝驴7系', '07', '无', 2, 700000, '0', 1),
(20, '鹰非Q80', '08', '无', 2, 1000000, '0', 1),
(21, '法拉尼', '09', '无', 2, 3000000, '0', 1),
(22, 'GWC总裁', '10', '无', 2, 5000000, '0', 1);
