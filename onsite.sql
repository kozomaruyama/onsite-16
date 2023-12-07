-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2023 年 5 月 09 日 13:55
-- サーバのバージョン： 10.3.28-MariaDB
-- PHP のバージョン: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `onsite`
--

-- --------------------------------------------------------

--
-- ビュー用の代替構造 `v_invoice_masters`
-- (実際のビューを参照するには下にあります)
--
CREATE TABLE `v_invoice_masters` (
`id` bigint(20)
,`schedule_class` int(11)
,`invoice_id` bigint(20)
,`subject_id` bigint(20) unsigned
,`schedule_id` bigint(20) unsigned
,`client_id` bigint(20) unsigned
,`name` varchar(255)
,`breakdown` varchar(255)
,`start_date` date
,`end_date` date
,`site_address` varchar(100)
,`site_name` varchar(100)
,`expenses` int(11)
,`amount` int(11)
,`tax` int(11)
,`tax_state` tinyint(1)
,`discount_total` int(11)
,`discount` int(11)
,`ratio` decimal(7,4)
,`pay_ratio` tinyint(3)
,`adjust_amount` int(11)
,`fraction` decimal(5,2)
,`subject_bill_amount` int(11)
,`bill_amount` int(11)
,`unit` int(11)
,`unit_name` varchar(20)
,`closing_date` date
,`orderNo` int(11)
,`memo` varchar(255)
,`adjust_title` varchar(50)
,`code` varchar(20)
);

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_invoice_masters`
--
DROP TABLE IF EXISTS `v_invoice_masters`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_invoice_masters`  AS SELECT `a`.`id` AS `id`, `c`.`class` AS `schedule_class`, `a`.`invoice_id` AS `invoice_id`, `a`.`subject_id` AS `subject_id`, `a`.`schedule_id` AS `schedule_id`, `b`.`client_id` AS `client_id`, `b`.`name` AS `name`, `b`.`breakdown` AS `breakdown`, `b`.`start_date` AS `start_date`, `b`.`end_date` AS `end_date`, `b`.`site_address` AS `site_address`, `b`.`site_name` AS `site_name`, `b`.`expenses` AS `expenses`, `a`.`amount` AS `amount`, `a`.`tax` AS `tax`, `a`.`tax_state` AS `tax_state`, `b`.`discount` AS `discount_total`, `a`.`discount` AS `discount`, `a`.`ratio` AS `ratio`, `b`.`pay_ratio` AS `pay_ratio`, `a`.`adjust_amount` AS `adjust_amount`, `a`.`fraction` AS `fraction`, `b`.`bill_amount` AS `subject_bill_amount`, `a`.`bill_amount` AS `bill_amount`, `a`.`unit` AS `unit`, `d`.`unit_name` AS `unit_name`, `a`.`closing_date` AS `closing_date`, `a`.`orderNo` AS `orderNo`, `a`.`memo` AS `memo`, `a`.`adjust_title` AS `adjust_title`, `b`.`code` AS `code` FROM (((`invoice_masters` `a` join `subjects` `b`) join `schedules` `c`) join `sys_items` `d`) WHERE `a`.`subject_id` = `b`.`id` AND `a`.`schedule_id` = `c`.`id` AND `a`.`unit` = `d`.`no` ORDER BY `a`.`subject_id` ASC, `a`.`id` ASC  ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
