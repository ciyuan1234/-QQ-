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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- 
-- 导出表中的数据 `amazpot_seeds`
-- 

INSERT INTO `amazpot_seeds` (`id`, `type`, `type_cn`, `growduration`, `stepduration`, `quantity`, `rare`, `unitprice`) VALUES 
(1, 'pingguo', '苹果', 36000, 7200, 60, 0, 4),
(3, 'chengzi', '橙子', 36000, 7200, 50, 0, 5),
(4, 'li', '梨', 36000, 7200, 60, 0, 4),
(5, 'xiangjiao', '香蕉', 36000, 7200, 80, 0, 3),
(6, 'taozi', '桃子', 36000, 7200, 80, 0, 3),
(7, 'shiliu', '石榴', 36000, 7200, 35, 1, 18),
(8, 'shanzha', '山楂', 36000, 7200, 90, 1, 7),
(9, 'reqingguo', '热情果', 36000, 7200, 30, 1, 22),
(10, 'qiyiguo', '奇异果', 36000, 7200, 80, 1, 8),
(11, 'youzi', '柚子', 36000, 7200, 45, 1, 15),
(12, 'yingtao', '樱桃', 36000, 7200, 65, 1, 10),
(13, 'yangmei', '杨梅', 36000, 7200, 110, 1, 6),
(14, 'baiputao', '白葡萄', 36000, 7200, 110, 1, 6),
(15, 'ningmeng', '柠檬', 36000, 7200, 70, 1, 9),
(16, 'mangguo', '芒果', 36000, 7200, 70, 1, 9),
(17, 'luhui', '芦荟', 36000, 7200, 45, 1, 15),
(18, 'lanmei', '蓝莓', 36000, 7200, 130, 1, 5),
(19, 'jinju', '金桔', 36000, 7200, 70, 1, 9),
(20, 'hongputao', '红葡萄', 36000, 7200, 130, 1, 5),
(21, 'heijialun', '黑加仑', 36000, 7200, 60, 1, 11),
(22, 'hamigua', '哈密瓜', 36000, 7200, 35, 1, 18),
(23, 'fanshiliu', '番石榴', 36000, 7200, 35, 1, 18),
(24, 'caomei', '草莓', 36000, 7200, 55, 1, 12),
(25, 'boluo', '菠萝', 36000, 7200, 80, 1, 8),
(26, 'molihua', '茉莉花', 36000, 7200, 20, 2, 80),
(27, 'meiguihua', '玫瑰花', 36000, 7200, 20, 2, 100);
