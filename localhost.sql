-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2025-12-06 17:24:04
-- 服务器版本： 5.7.44-log
-- PHP 版本： 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `fish1234_cn`
--
CREATE DATABASE IF NOT EXISTS `fish1234_cn` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fish1234_cn`;

-- --------------------------------------------------------

--
-- 表的结构 `loveway_config`
--

CREATE TABLE `loveway_config` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `loveway_config`
--

INSERT INTO `loveway_config` (`id`, `name`, `value`) VALUES
(216039, 'title', '未知表白墙'),
(345445, 'about', '关于本站'),
(385031, 'more', '开发初衷'),
(393564, 'about_content', '欢迎来到本表白墙！<br />本站基于 <a href=\"https://github.com/unknown-o/loveway-php\" target=\"_blank\">loveway-php</a> 项目搭建，广东LittleFish进行性能优化，二次部署等。本站使用MDUI开发。<br><br>原作者留下的<strong>服务条款</strong>：<br>1.发言请遵守当地法律法规和学校规章制度，本站保留对于发布不良信息和人身攻击的自然人追究法律责任的权利。<br>2.如发现有消息对个人生活产生困扰，请联系网站管理员。<br>邮箱：abnermod@163.com<br><br>然后就是我自己加的<br><del>3.PHP是世界上最好的语言。</del>'),
(572965, 'submit', '去表白'),
(578760, 'keywords', '未知表白墙,Kagamine'),
(782431, 'description', '本表白墙献给最可爱的你们！'),
(878767, 'audio', ''),
(928519, 'more_content', '找项目做着玩的');

-- --------------------------------------------------------

--
-- 表的结构 `loveway_data`
--

CREATE TABLE `loveway_data` (
  `id` int(11) NOT NULL,
  `favorite` int(11) NOT NULL,
  `confessor` text NOT NULL,
  `contact` text NOT NULL,
  `class` varchar(10) DEFAULT '',
  `time` text NOT NULL,
  `to_who` text NOT NULL,
  `introduction` text NOT NULL,
  `content` text NOT NULL,
  `image` text NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转储表的索引
--

--
-- 表的索引 `loveway_config`
--
ALTER TABLE `loveway_config`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `loveway_data`
--
ALTER TABLE `loveway_data`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
