-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-05-14 23:01:49
-- 服务器版本： 5.7.22-log
-- PHP Version: 5.6.36

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
-- 表的结构 `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `tid` tinyint(1) DEFAULT '1' COMMENT '1支出类型，2收入类型',
  `cname` varchar(20) DEFAULT NULL COMMENT '类别名称'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `tid`, `cname`) VALUES
(1, 1, '餐饮（吃）'),
(2, 1, '购物（穿）'),
(3, 1, '住房（住）'),
(4, 1, '交通（行）'),
(5, 1, '其他（备）'),
(6, 2, '工资'),
(7, 2, '红包'),
(8, 2, '其他（备）');

-- --------------------------------------------------------

--
-- 表的结构 `finance`
--

CREATE TABLE `finance` (
  `id` int(11) NOT NULL,
  `cid` tinyint(3) DEFAULT '1' COMMENT '类别ID',
  `type` tinyint(1) DEFAULT '1' COMMENT '1支出，2收入',
  `money` decimal(8,2) DEFAULT '0.00' COMMENT '金额',
  `addtime` int(10) DEFAULT '0' COMMENT '费用产生时间',
  `mark` varchar(100) CHARACTER SET utf8 DEFAULT '0' COMMENT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `finance`
--

INSERT INTO `finance` (`id`, `cid`, `type`, `money`, `addtime`, `mark`) VALUES
(1, 1, 1, '300.00', 1514903876, '1月餐费'),
(2, 4, 1, '100.00', 1514990276, '公交卡充值'),
(3, 1, 1, '22.21', 1515076676, '早餐总计截止21日'),
(4, 2, 1, '372.70', 1516545476, '沃尔玛四次和截止21日'),
(5, 7, 2, '9.65', 1516545476, '微信红包总计'),
(6, 1, 1, '70.00', 1516545476, '早餐来自微信'),
(7, 1, 1, '5.00', 1516667341, '早餐肠粉'),
(8, 1, 1, '7.00', 1516800812, '昨天跟今天早餐'),
(9, 1, 1, '3.00', 1516886570, '早餐'),
(10, 3, 1, '12.15', 1516886570, '12月电费'),
(11, 1, 1, '0.01', 1516886580, '测试'),
(12, 4, 1, '50.00', 1516960881, '公交卡充值'),
(13, 5, 1, '37.00', 1517049641, '南澳游玩'),
(14, 1, 1, '5.00', 1517218089, '早餐'),
(15, 1, 1, '2.00', 1517287121, '早餐'),
(16, 1, 1, '88.54', 1517322328, '沃尔玛'),
(17, 4, 1, '120.00', 1517637676, '公交卡与门禁卡');

-- --------------------------------------------------------

--
-- 表的结构 `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `item`
--

INSERT INTO `item` (`id`, `item_name`) VALUES
(1, 'Hello World.666'),
(2, 'Lets go!'),
(3, '77777');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finance`
--
ALTER TABLE `finance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `finance`
--
ALTER TABLE `finance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
