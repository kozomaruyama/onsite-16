-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2023 年 6 月 05 日 20:33
-- サーバのバージョン： 10.3.28-MariaDB
-- PHP のバージョン: 8.0.20

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
-- ビュー用の構造 `v2_schedules`
--
DROP TABLE IF EXISTS `v2_schedules`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v2_schedules`  AS SELECT `a`.`id` AS `id`, `a`.`class` AS `class`, `a`.`subject_id` AS `subject_id`, `a`.`person_id` AS `person_id`, `a`.`start_date` AS `start_date`, `a`.`start_time` AS `start_time`, `a`.`start_time`- interval `a`.`alarm_interval` minute AS `alarm_time`, `a`.`start_timestamp`- `a`.`alarm_interval` * 60  AS `alarm_timestamp`, `a`.`end_date` AS `end_date`, `a`.`closing` AS `closing`, `a`.`percentage` AS `percentage`, date_format(`a`.`start_date`,'%d') AS `day`, `a`.`pay_ratio` AS `pay_ratio`, `a`.`status` AS `status`, '0' AS `subject_class`, `a`.`name` AS `name`, '' AS `client`, '' AS `main_nick`, '' AS `breakdown`, '0' AS `client_id`, `a`.`subcontract_id` AS `subcontract_id`, '' AS `site_name`, '' AS `site_address`, '' AS `sub_nick`, `a`.`color` AS `color`, '0' AS `expenses`, '0' AS `discount`, '0' AS `bill_closing`, '0' AS `bill_issue`, '0' AS `bill_amount`, '0' AS `tax_state`, '0' AS `amount_subcontract`, '0' AS `process`, '0' AS `pay_ratio2`, '0' AS `master_pay_ratio`, '0' AS `pay_status`, `c`.`schedule_class` AS `schedule_class`, `a`.`memo` AS `memo`, '0' AS `bill_payment_class`, `a`.`alarm_interval` AS `alarm_interval`, `a`.`alarm` AS `alarm` FROM ((`schedules` `a` left join `sys_items` `c` on(`a`.`class` = `c`.`no`)) left join `clients` `d` on(`a`.`subcontract_id` = `d`.`id`)) WHERE `a`.`subject_id` = 00  ;
-- --------------------------------------------------------

--
-- ビュー用の構造 `v_bills`
--
DROP TABLE IF EXISTS `v_bills`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_bills`  AS SELECT `a`.`id` AS `id`, `a`.`class` AS `class`, `a`.`invoice_id` AS `invoice_id`, `a`.`subject_id` AS `subject_id`, `a`.`schedule_id` AS `schedule_id`, `a`.`client_id` AS `client_id`, `a`.`start_date` AS `start_date`, `a`.`end_date` AS `end_date`, `a`.`closing_date` AS `closing_date`, `a`.`pay_date` AS `pay_date`, `a`.`name` AS `name`, `a`.`volume` AS `volume`, `a`.`unit` AS `unit`, `a`.`cost` AS `cost`, `a`.`amount` AS `amount`, `a`.`memo` AS `memo`, `b`.`name` AS `client` FROM (`bills` `a` join `clients` `b`) WHERE `a`.`client_id` = `b`.`id`  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_bill_zans`
--
DROP TABLE IF EXISTS `v_bill_zans`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_bill_zans`  AS SELECT `a`.`id` AS `id`, `a`.`client_id` AS `client_id`, `a`.`bill_amount` AS `bill_amount`, sum(`b`.`amount` + `b`.`tax` - `b`.`discount`) AS `pat_amount`, sum(`b`.`ratio`) AS `pay_ratio`, `a`.`bill_amount`- sum(`b`.`amount` + `b`.`tax` - `b`.`discount`) AS `amount_zan`, 100 - sum(`b`.`ratio`) AS `ratio_zan` FROM (`subjects` `a` join `invoice_masters` `b`) WHERE `a`.`id` = `b`.`subject_id` GROUP BY `a`.`id`, `a`.`client_id`, `a`.`bill_amount` ;


-- --------------------------------------------------------

--
-- ビュー用の構造 `v_pay_zans`
--
-- DROP TABLE IF EXISTS `v_pay_zans`;

-- CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pay_zans`  AS SELECT `schedules`.`subject_id` AS `id`, sum(`schedules`.`pay_ratio`) AS `bill_ratio` FROM `schedules` GROUP BY `schedules`.`subject_id``subject_id`  ;
-- --------------------------------------------------------

--
-- ビュー用の構造 `v_bill_clients`
--
-- DROP TABLE IF EXISTS `v_bill_clients`;

-- CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_bill_clients`  AS SELECT `a`.`id` AS `id`, `c`.`name` AS `name`, `a`.`client_id` AS `client_id`, `a`.`bill_amount` AS `bill_amount`, `a`.`pat_amount` AS `pat_amount`, `a`.`pay_ratio` AS `pay_ratio`, `a`.`amount_zan` AS `amount_zan`, `a`.`ratio_zan` AS `ratio_zan`, `b`.`bill_ratio` AS `bill_ratio` FROM ((`v_bill_zans` `a` join `v_pay_zans` `b`) join `subjects` `c`) WHERE `a`.`id` = `b`.`id` AND `a`.`pay_ratio` <> `b`.`bill_ratio` AND `a`.`id` = `c`.`id`  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_clients`
--
DROP TABLE IF EXISTS `v_clients`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_clients`  AS SELECT `a`.`id` AS `id`, `a`.`code` AS `code`, `a`.`class` AS `class`, `a`.`name` AS `name`, `a`.`kana` AS `kana`, `a`.`nickname` AS `nickname`, `a`.`delegate` AS `delegate`, `a`.`zip` AS `zip`, `a`.`address1` AS `address1`, `a`.`address2` AS `address2`, `a`.`address3` AS `address3`, `a`.`tel1` AS `tel1`, `a`.`tel2` AS `tel2`, `a`.`fax` AS `fax`, `a`.`mail` AS `mail`, `a`.`bill_closing` AS `bill_closing`, `a`.`bill_issue` AS `bill_issue`, `a`.`bill_payment` AS `bill_payment`, `a`.`bill_payment_class` AS `bill_payment_class`, `a`.`pay_ratio` AS `pay_ratio`, `a`.`pay_amount` AS `pay_amount`, `a`.`pay_status` AS `pay_status`, `a`.`tax_state` AS `tax_state`, `a`.`closing_date` AS `closing_date`, `a`.`message1_title` AS `message1_title`, `a`.`message2_title` AS `message2_title`, `a`.`message3_title` AS `message3_title`, `a`.`message4_title` AS `message4_title`, `a`.`message1` AS `message1`, `a`.`message2` AS `message2`, `a`.`message3` AS `message3`, `a`.`message4` AS `message4`, `a`.`color` AS `color`, `a`.`invoice_code` AS `invoice_code`, `a`.`print_style` AS `print_style`, `a`.`status` AS `status`, `b`.`client_class` AS `client_class`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `c`.`schedule_class` AS `schedule_class` FROM ((`clients` `a` left join `sys_items` `b` on(`a`.`class` = `b`.`no`)) left join `sys_items` `c` on(`a`.`pay_status` = `c`.`no`))  ;


-- --------------------------------------------------------

--
-- ビュー用の構造 `v_client_classes`
--
DROP TABLE IF EXISTS `v_client_classes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_client_classes`  AS SELECT `sys_items`.`id` AS `id`, `sys_items`.`no` AS `no`, `sys_items`.`client_class` AS `client_class` FROM `sys_items` WHERE `sys_items`.`client_class` is not null ORDER BY `sys_items`.`no` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_closeingDateList`
--
DROP TABLE IF EXISTS `v_closeingDateList`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_closeingDateList`  AS SELECT `c`.`id` AS `client_id`, `b`.`id` AS `subject_id`, `a`.`id` AS `task_id`, `a`.`start_date` AS `start_date`, `a`.`amount` AS `amount`, CASE WHEN date_format(`a`.`start_date`,'%d') > `c`.`bill_closing` THEN concat(date_format(`a`.`start_date`,'%Y-%m-'),`c`.`bill_closing`) + interval 1 day ELSE concat(date_format(`a`.`start_date`,'%Y-%m-'),`c`.`bill_closing`) + interval -1 month + interval 1 day END AS `b.start_date`, CASE WHEN date_format(`a`.`start_date`,'%d') < `c`.`bill_closing` THEN concat(date_format(`a`.`start_date`,'%Y-%m-'),`c`.`bill_closing`) ELSE concat(date_format(`a`.`start_date`,'%Y-%m-'),`c`.`bill_closing`) + interval 1 month END AS `end_date`, `a`.`class` AS `class` FROM ((`tasks` `a` join `subjects` `b`) join `clients` `c`) WHERE `a`.`subject_id` = `b`.`id` AND `b`.`client_id` = `c`.`id` ORDER BY `c`.`id` ASC, `b`.`id` ASC, `a`.`start_date` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_closings`
--
DROP TABLE IF EXISTS `v_closings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_closings`  AS SELECT `v_closeingDateList`.`end_date` AS `end_date`, `v_closeingDateList`.`client_id` AS `v_client_id`, `v_closeingDateList`.`subject_id` AS `v_subject_id` FROM `v_closeingDateList` GROUP BY `v_closeingDateList`.`client_id`, `v_closeingDateList`.`end_date`, `v_closeingDateList`.`subject_id` ORDER BY `v_closeingDateList`.`client_id` ASC, `v_closeingDateList`.`end_date` ASC, `v_closeingDateList`.`subject_id` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_unit_name`
--
DROP TABLE IF EXISTS `v_unit_name`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_unit_name`  AS SELECT `sys_items`.`no` AS `no`, `sys_items`.`unit_name` AS `unit_name` FROM `sys_items` WHERE `sys_items`.`unit_name` is not null AND `sys_items`.`unit_name` <> '' ORDER BY `sys_items`.`no` ASC  ;
-- --------------------------------------------------------

--
-- ビュー用の構造 `v_invoices`
--
DROP TABLE IF EXISTS `v_invoices`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_invoices`  AS SELECT `a`.`id` AS `id`, `a`.`client_id` AS `client_id`, `a`.`code` AS `code`, `a`.`name` AS `name`, `a`.`closing_date` AS `closing_date`, `a`.`issue_date` AS `issue_date`, `a`.`payment_date` AS `payment_date`, `a`.`amount` AS `amount`, `a`.`amount`+ `a`.`adjust_amount` AS `amount_end`, `a`.`discount` AS `discount`, `a`.`adjust_amount` AS `adjust_amount`, `a`.`tax_state` AS `tax_state`, `a`.`tax_1` AS `tax_1`, `a`.`tax_2` AS `tax_2`, `a`.`tax_3` AS `tax_3`, `a`.`bill_amount` AS `bill_amount`, `a`.`message1_title` AS `message1_title`, `a`.`message2_title` AS `message2_title`, `a`.`message3_title` AS `message3_title`, `a`.`message1` AS `message1`, `a`.`message2` AS `message2`, `a`.`message3` AS `message3`, `a`.`unVisible` AS `unVisible`, `a`.`memo` AS `memo`, `b`.`name` AS `client_name`, `b`.`nickname` AS `client_nickname`, `b`.`invoice_code` AS `invoice_code`, `b`.`print_style` AS `print_style`, date_format(`a`.`closing_date`,'%Y-%m') AS `yymm` FROM (`invoices` `a` left join `clients` `b` on(`a`.`client_id` = `b`.`id`)) ORDER BY `a`.`closing_date` DESC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_invoice_details`
--
DROP TABLE IF EXISTS `v_invoice_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_invoice_details`  AS SELECT `a`.`id` AS `id`, `a`.`class` AS `details_class`, `a`.`invoice_master_id` AS `v_invoice_master_id`, `a`.`task_id` AS `task_id`, round(`a`.`yield_ratio`,2) AS `yield_ratio`, `a`.`yield_amount` AS `yield_amount`, `a`.`remain_ratio` AS `remain_ratio`, `a`.`fraction` AS `fraction`, `a`.`memo` AS `memo`, `b`.`class` AS `class`, `b`.`process` AS `process`, `b`.`product_class` AS `product_class`, `b`.`subject_id` AS `subject_id`, `b`.`name` AS `name`, `b`.`breakdown` AS `breakdown`, `b`.`volume` AS `volume`, `b`.`unit` AS `unit`, `b`.`cost` AS `cost`, `b`.`amount` AS `amount`, `b`.`status` AS `task_status`, `c`.`unit_name` AS `unit_name`, `b`.`isBreakdown` AS `isBreakdown`, `b`.`isLabel` AS `isLabel`, `b`.`orderNo` AS `orderNo`, `d`.`schedule_id` AS `schedule_id`, `d`.`invoice_id` AS `invoice_id` FROM (((`invoice_details` `a` join `tasks` `b`) join `invoice_masters` `d` on(`a`.`task_id` = `b`.`id` and `a`.`invoice_master_id` = `d`.`id`)) left join `v_unit_name` `c` on(`b`.`unit` = `c`.`no`)) ORDER BY `a`.`invoice_master_id` ASC, `b`.`subject_id` ASC, `b`.`orderNo` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_invoice_masters`
--
DROP TABLE IF EXISTS `v_invoice_masters`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_invoice_masters`  AS SELECT `a`.`id` AS `id`, `c`.`class` AS `schedule_class`, `a`.`invoice_id` AS `invoice_id`, `a`.`subject_id` AS `subject_id`, `a`.`schedule_id` AS `schedule_id`, `b`.`client_id` AS `client_id`, `b`.`name` AS `name`, `b`.`breakdown` AS `breakdown`, `b`.`start_date` AS `start_date`, `b`.`end_date` AS `end_date`, `b`.`site_address` AS `site_address`, `b`.`site_name` AS `site_name`, `b`.`expenses` AS `expenses`, `a`.`amount` AS `amount`, `a`.`tax` AS `tax`, `a`.`tax_state` AS `tax_state`, `b`.`discount` AS `discount_total`, `a`.`discount` AS `discount`, `a`.`ratio` AS `ratio`, `b`.`pay_ratio` AS `pay_ratio`, `a`.`adjust_amount` AS `adjust_amount`, `a`.`fraction` AS `fraction`, `b`.`bill_amount` AS `subject_bill_amount`, `a`.`bill_amount` AS `bill_amount`, `a`.`unit` AS `unit`, `d`.`unit_name` AS `unit_name`, `a`.`closing_date` AS `closing_date`, `a`.`orderNo` AS `orderNo`, `a`.`memo` AS `memo`, `a`.`adjust_title` AS `adjust_title`, `b`.`code` AS `code` FROM (((`invoice_masters` `a` join `subjects` `b`) join `schedules` `c`) join `sys_items` `d`) WHERE `a`.`subject_id` = `b`.`id` AND `a`.`schedule_id` = `c`.`id` AND `a`.`unit` = `d`.`no` ORDER BY `a`.`subject_id` ASC, `a`.`id` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_pays`
--
DROP TABLE IF EXISTS `v_pays`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pays`  AS SELECT `a`.`id` AS `id`, `a`.`class` AS `class`, `a`.`invoice_id` AS `invoice_id`, `a`.`subject_id` AS `subject_id`, `a`.`schedule_id` AS `schedule_id`, `a`.`client_id` AS `client_id`, `a`.`start_date` AS `start_date`, `a`.`end_date` AS `end_date`, `a`.`closing_date` AS `closing_date`, `a`.`pay_date` AS `pay_date`, `a`.`name` AS `name`, `a`.`volume` AS `volume`, `a`.`unit` AS `unit`, `a`.`cost` AS `cost`, `a`.`amount` AS `amount`, `a`.`memo` AS `memo`, `a`.`status` AS `status`, `b`.`name` AS `client`, `c`.`unit_name` AS `unit_name`, `b`.`nickname` AS `nickname` FROM ((`pays` `a` join `clients` `b`) join `sys_items` `c`) WHERE `a`.`client_id` = `b`.`id` AND `a`.`unit` = `c`.`no`;


-- --------------------------------------------------------

--
-- ビュー用の構造 `v_people`
--
DROP TABLE IF EXISTS `v_people`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_people`  AS SELECT `a`.`id` AS `id`, `a`.`code` AS `code`, `a`.`class` AS `class`, `a`.`client_id` AS `client_id`, `a`.`name` AS `name`, `a`.`kana` AS `kana`, `a`.`birthday` AS `birthday`, `a`.`sex` AS `sex`, `a`.`tel1` AS `tel1`, `a`.`tel2` AS `tel2`, `a`.`mail` AS `mail`, `a`.`address` AS `address`, `a`.`zip` AS `zip`, `a`.`color` AS `color`, `a`.`status` AS `status`, `b`.`client_class` AS `client_class`, `c`.`name` AS `belong`, ifnull(`d`.`role`,-1) AS `role`, `d`.`status` AS `account_status` FROM (((`people` `a` left join `sys_items` `b` on(`a`.`class` = `b`.`no`)) left join `clients` `c` on(`a`.`client_id` = `c`.`id`)) left join `users` `d` on(`a`.`id` = `d`.`person_id`)) ORDER BY `a`.`id` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_products`
--
DROP TABLE IF EXISTS `v_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_products`  AS SELECT `a`.`id` AS `id`, `a`.`code` AS `code`, `a`.`class` AS `class`, `a`.`name` AS `name`, `a`.`breakdown` AS `breakdown`, `a`.`kana` AS `kana`, `a`.`unit` AS `unit`, `a`.`cost` AS `cost`, `a`.`cost_1` AS `cost_1`, `a`.`cost_2` AS `cost_2`, `a`.`volume` AS `volume`, `a`.`items` AS `items`, `a`.`size` AS `size`, `a`.`weight` AS `weight`, `a`.`isLabel` AS `isLabel`, `b`.`product_class` AS `product_class`, `c`.`unit_name` AS `unit_name` FROM ((`products` `a` left join `sys_items` `b` on(`a`.`class` = `b`.`no`)) left join `sys_items` `c` on(`a`.`unit` = `c`.`no`)) ORDER BY `a`.`code` ASC, `a`.`id` ASC  ;
-- --------------------------------------------------------

--
-- ビュー用の構造 `v_subjects`
--
DROP TABLE IF EXISTS `v_subjects`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_subjects`  AS SELECT `a`.`id` AS `id`, `a`.`class` AS `class`, `a`.`code` AS `code`, `a`.`order_code` AS `order_code`, `a`.`name` AS `name`, `a`.`breakdown` AS `breakdown`, `a`.`client_id` AS `client_id`, `a`.`person_id` AS `person_id`, `a`.`subcontract_id` AS `subcontract_id`, `a`.`user_id` AS `user_id`, `a`.`percentage` AS `percentage`, `a`.`order_date` AS `order_date`, `a`.`start_date` AS `start_date`, `a`.`end_date` AS `end_date`, concat(ifnull(date_format(`a`.`start_date`,'%y-%m-%d'),''),'～',ifnull(date_format(`a`.`end_date`,'%y-%m-%d'),'')) AS `period`, `a`.`isDay` AS `isDay`, `a`.`site_address` AS `site_address`, `a`.`site_name` AS `site_name`, `a`.`bill_amount` AS `bill_amount`, `a`.`bill_arriving` AS `bill_arriving`, `a`.`expenses` AS `expenses`, `a`.`tax_state` AS `tax_state`, `a`.`tax` AS `tax`, `a`.`discount` AS `discount`, `a`.`pay_ratio` AS `pay_ratio`, `a`.`pay_amount` AS `pay_amount`, `a`.`pay_terms` AS `pay_terms`, `a`.`pay_status` AS `pay_status`, `a`.`amount_subcontract` AS `amount_subcontract`, `a`.`task_class` AS `task_class`, `a`.`process` AS `process`, `a`.`process_old` AS `process_old`, `a`.`file_path` AS `file_path`, `a`.`message1_title` AS `message1_title`, `a`.`message2_title` AS `message2_title`, `a`.`message3_title` AS `message3_title`, `a`.`message4_title` AS `message4_title`, `a`.`message1` AS `message1`, `a`.`message2` AS `message2`, `a`.`message3` AS `message3`, `a`.`message4` AS `message4`, `a`.`unVisible` AS `unVisible`, `a`.`memo` AS `memo`, `a`.`status` AS `status`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, concat(`a`.`name`,'(',`a`.`id`,')') AS `label`, `b`.`name` AS `client`, `b`.`nickname` AS `main_nick`, `b`.`zip` AS `client_zip`, `b`.`address1` AS `client_address1`, `b`.`address2` AS `client_address2`, `b`.`address3` AS `client_address3`, `b`.`tel1` AS `client_tel1`, `b`.`tel2` AS `client_tel2`, `b`.`fax` AS `client_fax`, `b`.`delegate` AS `delegate`, `b`.`bill_closing` AS `bill_closing`, `b`.`bill_issue` AS `bill_issue`, `b2`.`name` AS `subcontract`, `b2`.`nickname` AS `sub_nick`, `c`.`subject_class` AS `subject_class`, `d`.`task_class` AS `task_state`, `e`.`process_name` AS `process_name`, `f`.`name` AS `manager_name`, `g`.`name` AS `leader` FROM (((((((`subjects` `a` left join `clients` `b` on(`a`.`client_id` = `b`.`id`)) left join `clients` `b2` on(`a`.`subcontract_id` = `b2`.`id`)) left join `sys_items` `c` on(`a`.`class` = `c`.`no`)) left join `sys_items` `d` on(`a`.`task_class` = `d`.`no`)) left join `sys_items` `e` on(`a`.`process` = `e`.`no`)) left join `users` `f` on(`a`.`user_id` = `f`.`id`)) left join `people` `g` on(`a`.`person_id` = `g`.`id`)) ORDER BY `a`.`id` DESC ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_schedules`
--
DROP TABLE IF EXISTS `v_schedules`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_schedules`  AS SELECT `a`.`id` AS `id`, `a`.`class` AS `class`, `a`.`subject_id` AS `subject_id`, `a`.`person_id` AS `person_id`, `a`.`start_date` AS `start_date`, `a`.`start_time` AS `start_time`, `a`.`end_date` AS `end_date`, `a`.`closing` AS `closing`, `a`.`percentage` AS `percentage`, date_format(`a`.`start_date`,'%d') AS `day`, `a`.`pay_ratio` AS `pay_ratio`, `a`.`status` AS `status`,`a`.`alarm_interval` AS `alarm_interval`, `a`.`alarm` AS `alarm`, `b`.`class` AS `subject_class`, `b`.`name` AS `name`, `b`.`client` AS `client`, `b`.`main_nick` AS `main_nick`, `b`.`breakdown` AS `breakdown`, `b`.`client_id` AS `client_id`, `a`.`subcontract_id` AS `subcontract_id`, `b`.`site_name` AS `site_name`, `b`.`site_address` AS `site_address`, `b`.`sub_nick` AS `sub_nick`, CASE WHEN `a`.`subcontract_id` = 0 THEN 'grey' ELSE `d`.`color` END AS `color`, `b`.`expenses` AS `expenses`, `b`.`discount` AS `discount`, `b`.`bill_closing` AS `bill_closing`, `b`.`bill_issue` AS `bill_issue`, `b`.`bill_amount` AS `bill_amount`, `b`.`tax_state` AS `tax_state`, `b`.`amount_subcontract` AS `amount_subcontract`, `b`.`process` AS `process`, `b`.`pay_ratio` AS `pay_ratio2`, CASE `a`.`class` WHEN `b`.`pay_status` THEN `b`.`pay_ratio` ELSE 100 - `b`.`pay_ratio` END AS `master_pay_ratio`, `b`.`pay_status` AS `pay_status`, `c`.`schedule_class` AS `schedule_class`, `b`.`memo` AS `memo`, `d`.`bill_payment_class` AS `bill_payment_class` FROM (((`schedules` `a` left join `sys_items` `c` on(`a`.`class` = `c`.`no`)) left join `clients` `d` on(`a`.`subcontract_id` = `d`.`id`)) join `v_subjects` `b` on(`a`.`subject_id` = `b`.`id`))  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_schedule_details`
--
DROP TABLE IF EXISTS `v_schedule_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_schedule_details`  AS SELECT `a`.`id` AS `id`, `b`.`class` AS `class`, `a`.`schedule_id` AS `v_schedule_id`, `a`.`task_id` AS `task_id`, `a`.`start_date` AS `start_date`, `a`.`end_date` AS `end_date`, `a`.`percentage` AS `percentage`, `c`.`name` AS `name`, `c`.`breakdown` AS `breakdown`, `c`.`amount` AS `amount`, `c`.`remain_ratio` AS `remain_ratio`, `c`.`isLabel` AS `isLabel`, `c`.`memo` AS `memo`, `b`.`pay_ratio` AS `pay_ratio`, `b`.`subject_id` AS `subject_id`, `c`.`orderNo` AS `orderNo` FROM ((`schedule_details` `a` join `schedules` `b`) join `tasks` `c`) WHERE `a`.`schedule_id` = `b`.`id` AND `a`.`task_id` = `c`.`id` ORDER BY `a`.`schedule_id` ASC, `c`.`orderNo` ASC  ;


-- --------------------------------------------------------

--
-- ビュー用の構造 `v_subject_classes`
--
DROP TABLE IF EXISTS `v_subject_classes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_subject_classes`  AS SELECT `sys_items`.`no` AS `id`, `sys_items`.`subject_class` AS `subject_class` FROM `sys_items` WHERE `sys_items`.`subject_class` is not null ORDER BY `sys_items`.`no` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_subject_lists`
--
DROP TABLE IF EXISTS `v_subject_lists`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_subject_lists`  AS SELECT `t1`.`id` AS `subject_id`, `t2`.`id` AS `schedule_id`, dayofmonth(`t2`.`start_date`) AS `day`, `t2`.`start_date` AS `start_date`, `t1`.`name` AS `subject`, `t3`.`nickname` AS `client_nick`, CASE `t2`.`class` WHEN 10 THEN '架' ELSE '払' END AS `schedule_class`, CASE `t3`.`bill_issue` WHEN 31 THEN '末' ELSE `t3`.`bill_issue` END AS `bill_issue`, `t1`.`expenses`- `t1`.`discount` AS `amount`, `t2`.`pay_ratio` AS `pay_ratio`, round((`t1`.`expenses` - `t1`.`discount`) * `t2`.`pay_ratio` / 100,0) AS `bill_amount`, `t4`.`bill_closing` AS `pay_closing`, `t4`.`nickname` AS `sub_nick`, `t4`.`nickname` AS `subcontract_nick`, `t1`.`amount_subcontract` AS `amount_subcontract`, round(`t1`.`amount_subcontract` * `t2`.`pay_ratio` / 100,0) AS `pay_amount`, `t1`.`process` AS `process` FROM (((`subjects` `t1` join `schedules` `t2`) join `clients` `t3`) join `clients` `t4`) WHERE `t1`.`id` = `t2`.`subject_id` AND `t1`.`client_id` = `t3`.`id` AND `t2`.`subcontract_id` = `t4`.`id` ORDER BY `t2`.`start_date` ASC, `t1`.`client_id` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_tasks`
--
DROP TABLE IF EXISTS `v_tasks`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tasks`  AS SELECT `a`.`id` AS `id`, `a`.`class` AS `class`, `a`.`process` AS `process`, `a`.`product_class` AS `product_class`, `a`.`subject_id` AS `subject_id`, `a`.`name` AS `name`, `a`.`breakdown` AS `breakdown`, `a`.`user_id` AS `user_id`, `a`.`percentage` AS `percentage`, `a`.`volume` AS `volume`, concat(`a`.`volume`,`c`.`unit_name`) AS `volume_unit`, `a`.`unit` AS `unit`, `a`.`cost` AS `cost`, `a`.`amount` AS `amount`, `a`.`start_date` AS `start_date`, `a`.`end_date` AS `end_date`, `a`.`isDay` AS `isDay`, `a`.`days` AS `days`, `a`.`remain_ratio` AS `remain_ratio`, `a`.`isBreakdown` AS `isBreakdown`, `a`.`isLabel` AS `isLabel`, `a`.`orderNo` AS `orderNo`, `a`.`memo` AS `memo`, `a`.`status` AS `status`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `a`.`subject_id` AS `v_subject_id`, `b`.`task_class` AS `task_class`, `c`.`unit_name` AS `unit_name`, `d`.`product_class` AS `product_class_name`, `e`.`name` AS `manager_name` FROM ((((`tasks` `a` left join `sys_items` `b` on(`a`.`class` = `b`.`no`)) left join `sys_items` `c` on(`a`.`unit` = `c`.`no`)) left join `sys_items` `d` on(`a`.`product_class` = `d`.`no`)) left join `users` `e` on(`a`.`user_id` = `e`.`id`)) WHERE `b`.`task_class` is not null ORDER BY `a`.`subject_id` ASC, `a`.`orderNo` ASC  ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `v_task_classes`
--
DROP TABLE IF EXISTS `v_task_classes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_task_classes`  AS SELECT `sys_items`.`no` AS `no`, `sys_items`.`task_class` AS `task_class` FROM `sys_items` WHERE `sys_items`.`task_class` is not null AND `sys_items`.`task_class` <> '' ORDER BY `sys_items`.`no` ASC  ;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
