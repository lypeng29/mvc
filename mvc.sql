-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-08-02 08:35:51
-- 服务器版本： 5.5.53
-- PHP Version: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvc`
--

-- --------------------------------------------------------

--
-- 表的结构 `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `item_name` varchar(200) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `item`
--

INSERT INTO `item` (`id`, `item_name`) VALUES
(3, '内容太多'),
(4, '都是'),
(5, '你们好');

-- --------------------------------------------------------

--
-- 表的结构 `queue`
--

CREATE TABLE `queue` (
  `id` bigint(16) UNSIGNED NOT NULL,
  `main_type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型',
  `sub_type` varchar(128) NOT NULL DEFAULT '' COMMENT '子类型',
  `request_url` varchar(300) DEFAULT NULL COMMENT '请求URL',
  `params` text COMMENT '参数(需序列化，邮件格式必要字段“mailAddress,subject,content”，短信格式必要字段“mobile,code”，可选参数“user_name,consignee”)',
  `max_times` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '重试最大次数',
  `exe_times` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '被执行次数',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0:未执行,1:重试中,2:执行成功,3:执行失败',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `run_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '运行时间',
  `is_lock` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0:未锁定1已经锁定'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='队列消息任务表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queue_status` (`status`),
  ADD KEY `is_lock` (`is_lock`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `queue`
--
ALTER TABLE `queue`
  MODIFY `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
