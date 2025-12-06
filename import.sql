-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期：2024-12-XX XX:XX:XX
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `loveway_database`
--

-- --------------------------------------------------------

--
-- 表的结构 `loveway_config`
--

CREATE TABLE `loveway_config` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `loveway_config`
--

INSERT INTO `loveway_config` (`id`, `name`, `value`) VALUES
(216039, 'title', '未知表白墙'),
(345445, 'about', '关于本站'),
(385031, 'more', '开发初衷'),
(393564, 'about_content', '欢迎来到表白墙！<br />本站使用现代Web技术构建<br /><br />服务条款：<br />1. 发言请遵守当地法律法规和学校规章制度<br />2. 如发现有消息对个人生活产生困扰，请联系网站管理员<br />3. 请文明表达，友善交流'),
(572965, 'submit', '去表白'),
(578760, 'keywords', '表白墙,Kagamine,表白'),
(782431, 'description', '一个现代化的表白墙系统'),
(878767, 'audio', ''),
(928519, 'more_content', '开发这个表白墙的初衷是为了创建一个温馨友善的表白环境<br>让每个人都能勇敢表达自己的情感<br><br>请合理使用本系统，遵守相关规定');

-- --------------------------------------------------------

--
-- 表的结构 `loveway_data` (更新版本，包含新字段)
--

CREATE TABLE `loveway_data` (
  `id` int(11) NOT NULL,
  `favorite` int(11) NOT NULL DEFAULT '0',
  `confessor` text NOT NULL COMMENT '表白者姓名',
  `contact` text NOT NULL COMMENT '联系方式',
  `class` varchar(10) DEFAULT '' COMMENT '表白者班级',
  `time` text NOT NULL COMMENT '提交时间',
  `to_who` text NOT NULL COMMENT '被表白者姓名',
  `ta_class` varchar(10) DEFAULT '' COMMENT '被表白者班级',
  `introduction` text NOT NULL COMMENT '介绍语',
  `content` text NOT NULL COMMENT '表白内容',
  `image` text DEFAULT '' COMMENT '单张图片',
  `images` text COMMENT '多张图片，逗号分隔',
  `video` text COMMENT '视频地址',
  `comment` text NOT NULL DEFAULT '[]' COMMENT '评论数据，JSON格式'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='表白数据表';

--
-- 转储表的索引
--

ALTER TABLE `loveway_config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `loveway_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `loveway_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;