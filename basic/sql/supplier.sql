-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2022-04-29 15:23:12
-- 服务器版本： 5.7.29-log
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supplier_demo_yo`
--

-- --------------------------------------------------------

--
-- 表的结构 `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT 'Supplier Name',
  `code` char(3) DEFAULT NULL COMMENT 'Supplier Code',
  `t_status` enum('ok','hold') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'ok' COMMENT 'Supplier Status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `code`, `t_status`) VALUES
(1, 'DJI', '001', 'ok'),
(2, 'Xiaomi', '002', 'ok'),
(3, 'TENCENT', '003', 'ok'),
(4, 'GOOGLE', '004', 'ok'),
(5, 'TESLA', '005', 'ok'),
(6, 'SPACEX', '006', 'ok'),
(7, 'TWITTER', '007', 'ok'),
(8, 'ALIYUN', '008', 'ok'),
(9, 'SINA', '009', 'ok');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
