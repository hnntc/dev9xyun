-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 12 月 04 日 05:37
-- 服务器版本: 5.0.96-community-nt
-- PHP 版本: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- 表的结构 `wp_access_list`
--

CREATE TABLE IF NOT EXISTS `wp_access_list` (
  `aid` int(32) NOT NULL auto_increment COMMENT '授权id',
  `ownerid` int(32) NOT NULL COMMENT '拥有者id  ->member(uid)',
  `access_token` varchar(64) NOT NULL COMMENT '令牌',
  `screen_name` varchar(32) default NULL,
  `get_time` datetime default NULL COMMENT '申请时间',
  `expires_in` int(11) default NULL COMMENT '有效时间',
  `uid` varchar(32) NOT NULL COMMENT '授权用户',
  PRIMARY KEY  (`aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_available_token`
--

CREATE TABLE IF NOT EXISTS `wp_available_token` (
  `id` int(32) NOT NULL auto_increment,
  `ownerid` int(32) NOT NULL,
  `available_list` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_weibo_history`
--

CREATE TABLE IF NOT EXISTS `wp_weibo_history` (
  `wid` int(32) NOT NULL auto_increment,
  `ownerid` int(32) NOT NULL,
  `data` text NOT NULL,
  `pic_urls` text COMMENT '微博图片路径',
  `date` datetime NOT NULL,
  `show` tinyint(1) NOT NULL default '1' COMMENT '当成功发送的数量为0时不显示 值为0',
  PRIMARY KEY  (`wid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_weibo_report`
--

CREATE TABLE IF NOT EXISTS `wp_weibo_report` (
  `cid` int(32) NOT NULL auto_increment COMMENT '主键',
  `wid` int(32) NOT NULL COMMENT '关联weibo_historywid',
  `comments` int(32) NOT NULL COMMENT '评论数量',
  `reposts` int(32) NOT NULL COMMENT '转发数量',
  `attitudes` int(32) NOT NULL COMMENT '赞',
  `last_update_time` datetime NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY  (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=88 ;

--
-- 转存表中的数据 `wp_hooks`
--

REPLACE INTO `wp_hooks` (`name`, `description`, `type`, `update_time`, `addons`) VALUES
( 'weiboEditor', '微博编辑器的钩子', 1, 0, 'WeiboEditor');


--
-- 转存表中的数据 `wp_addons`
--
REPLACE INTO `wp_addons` (`name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`, `type`, `cate_id`) VALUES
( 'WeiboEditor', '微博编辑器', '微博编辑器', 1, '{"random":"1"}', 'han', '0.1', 0, 0, 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
