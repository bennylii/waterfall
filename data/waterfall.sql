-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost:3306
-- 生成日期: 2013 年 03 月 29 日 14:24
-- 服务器版本: 5.1.41
-- PHP 版本: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `waterfall`
--
DROP DATABASE `waterfall`;
CREATE DATABASE `waterfall` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `waterfall`;

-- --------------------------------------------------------

--
-- 表的结构 `wf_picture`
--

CREATE TABLE IF NOT EXISTS `wf_picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(512) NOT NULL COMMENT '照片备注',
  `path` varchar(128) NOT NULL COMMENT '照片路径',
  `tag` varchar(128) NOT NULL COMMENT '照片标签',
  `width` int(11) NOT NULL COMMENT '照片的宽度值，单位像素',
  `height` int(11) NOT NULL COMMENT '照片的高度值，单位像素',
  `top_show` tinyint(4) NOT NULL DEFAULT '-1' COMMENT '是否置顶幻灯片展示',
  `createtime` int(11) NOT NULL COMMENT '记录创建时间',
  `updatetime` int(11) NOT NULL COMMENT '记录更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='照片表' AUTO_INCREMENT=109 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
