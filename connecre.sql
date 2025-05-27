-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1:3306
-- 生成日時: 2025-04-17 03:56:07
-- サーバのバージョン： 8.2.0
-- PHP のバージョン: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `connecre`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `apply`
--

DROP TABLE IF EXISTS `apply`;
CREATE TABLE IF NOT EXISTS `apply` (
  `apply_num` int NOT NULL AUTO_INCREMENT,
  `recruit_num` int DEFAULT NULL,
  `apply_datetime` datetime NOT NULL,
  `applicant_id` varchar(20) DEFAULT NULL,
  `apply_content` varchar(1000) DEFAULT NULL,
  `apply_status` tinyint NOT NULL DEFAULT '1' COMMENT '0:拒否   1:未承認  2:承認済み ',
  PRIMARY KEY (`apply_num`),
  KEY `fk_recruit_num` (`recruit_num`),
  KEY `fk_applicant_id` (`applicant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `apply`
--

INSERT INTO `apply` (`apply_num`, `recruit_num`, `apply_datetime`, `applicant_id`, `apply_content`, `apply_status`) VALUES
(30, 82, '2024-12-06 10:46:59', 'No.2', 'No.2で応募', 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `history_num` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(30) DEFAULT NULL,
  `history_title` varchar(50) DEFAULT NULL,
  `history_discription` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`history_num`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `jam`
--

DROP TABLE IF EXISTS `jam`;
CREATE TABLE IF NOT EXISTS `jam` (
  `jam_num` int NOT NULL AUTO_INCREMENT,
  `hackason_name` varchar(40) NOT NULL,
  `hackason_location` tinyint NOT NULL,
  `host_id` varchar(30) DEFAULT NULL,
  `jam_start_time` datetime NOT NULL,
  `jam_period_time` datetime NOT NULL,
  `team_min` tinyint NOT NULL,
  `team_max` tinyint NOT NULL,
  `active` tinyint NOT NULL DEFAULT '1',
  `jam_password` varchar(8) NOT NULL,
  PRIMARY KEY (`jam_num`),
  KEY `fk_host_id` (`host_id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `jam`
--

INSERT INTO `jam` (`jam_num`, `hackason_name`, `hackason_location`, `host_id`, `jam_start_time`, `jam_period_time`, `team_min`, `team_max`, `active`, `jam_password`) VALUES
(71, 'チャレンジキャラバン2024', 40, 'No.1', '2024-12-04 10:00:00', '2024-12-14 18:00:00', 3, 5, 0, '1111'),
(72, 'テスト2', 27, 'No.1', '2024-12-10 10:00:00', '2024-12-10 18:00:00', 4, 6, 0, '1111'),
(73, 'テスト3', 40, 'No.1', '2024-12-15 10:00:00', '2024-12-15 18:00:00', 2, 4, 0, '1111'),
(78, 'ジャムてすと', 0, 'No.1', '2024-12-06 17:46:12', '2025-01-11 17:46:00', 2, 4, 0, '1111');

-- --------------------------------------------------------

--
-- テーブルの構造 `jam_apply`
--

DROP TABLE IF EXISTS `jam_apply`;
CREATE TABLE IF NOT EXISTS `jam_apply` (
  `apply_num` int NOT NULL AUTO_INCREMENT,
  `jam_num` int DEFAULT NULL,
  `applicant_id` varchar(20) DEFAULT NULL,
  `applicant_role` tinyint NOT NULL,
  PRIMARY KEY (`apply_num`),
  KEY `fk_apply_jam_num` (`jam_num`),
  KEY `fk_jam_applicant_id` (`applicant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `jam_apply`
--

INSERT INTO `jam_apply` (`apply_num`, `jam_num`, `applicant_id`, `applicant_role`) VALUES
(40, 72, 'No.2', 0),
(24, 71, 'No.10', 2),
(23, 71, 'No.9', 2),
(22, 71, 'No.8', 2),
(21, 71, 'No.7', 1),
(20, 71, 'No.6', 1),
(19, 71, 'No.5', 1),
(18, 71, 'No.4', 0),
(17, 71, 'No.3', 0),
(16, 71, 'No.2', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `jam_chat`
--

DROP TABLE IF EXISTS `jam_chat`;
CREATE TABLE IF NOT EXISTS `jam_chat` (
  `chat_num` int NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(20) DEFAULT NULL,
  `chat_datetime` datetime NOT NULL,
  `chat_content` varchar(1000) DEFAULT NULL,
  `room_num` int DEFAULT NULL,
  PRIMARY KEY (`chat_num`),
  KEY `fk_jam_sender_id` (`sender_id`),
  KEY `fk_jam_room_num` (`room_num`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `jam_chat`
--

INSERT INTO `jam_chat` (`chat_num`, `sender_id`, `chat_datetime`, `chat_content`, `room_num`) VALUES
(42, 'No.3', '2024-12-06 17:58:00', 'aa', 47);

-- --------------------------------------------------------

--
-- テーブルの構造 `jam_chat_room`
--

DROP TABLE IF EXISTS `jam_chat_room`;
CREATE TABLE IF NOT EXISTS `jam_chat_room` (
  `room_num` int NOT NULL AUTO_INCREMENT,
  `jam_num` int DEFAULT NULL,
  `active` tinyint NOT NULL,
  PRIMARY KEY (`room_num`),
  KEY `fk_jam_num` (`jam_num`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `jam_chat_room`
--

INSERT INTO `jam_chat_room` (`room_num`, `jam_num`, `active`) VALUES
(47, 71, 1),
(46, 71, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `jam_member`
--

DROP TABLE IF EXISTS `jam_member`;
CREATE TABLE IF NOT EXISTS `jam_member` (
  `num` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) NOT NULL,
  `room_num` int NOT NULL,
  PRIMARY KEY (`num`),
  KEY `fk_member_id` (`member_id`),
  KEY `fk_member_room_num` (`room_num`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `jam_member`
--

INSERT INTO `jam_member` (`num`, `member_id`, `room_num`) VALUES
(93, 'No.2', 46),
(92, 'No.3', 47),
(91, 'No.4', 46),
(90, 'No.8', 47),
(89, 'No.9', 46),
(88, 'No.10', 47),
(87, 'No.5', 46),
(86, 'No.6', 47),
(85, 'No.7', 46);

-- --------------------------------------------------------

--
-- テーブルの構造 `recruit`
--

DROP TABLE IF EXISTS `recruit`;
CREATE TABLE IF NOT EXISTS `recruit` (
  `recruit_num` int NOT NULL AUTO_INCREMENT,
  `recruit_title` varchar(50) NOT NULL,
  `recruit_datetime` datetime NOT NULL,
  `recruiter_id` varchar(30) NOT NULL,
  `recruit_role` tinyint DEFAULT NULL,
  `recruit_location` tinyint NOT NULL,
  `recruit_capacity` tinyint NOT NULL,
  `recruit_content` varchar(1000) DEFAULT NULL,
  `recruit_status` tinyint NOT NULL,
  `edited` tinyint NOT NULL,
  PRIMARY KEY (`recruit_num`),
  KEY `fk_recruiter_id` (`recruiter_id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `recruit`
--

INSERT INTO `recruit` (`recruit_num`, `recruit_title`, `recruit_datetime`, `recruiter_id`, `recruit_role`, `recruit_location`, `recruit_capacity`, `recruit_content`, `recruit_status`, `edited`) VALUES
(82, '募集てすとfromNo.1', '2024-12-06 10:46:45', 'No.1', 1, 40, 3, '1111', 1, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `recruit_chat`
--

DROP TABLE IF EXISTS `recruit_chat`;
CREATE TABLE IF NOT EXISTS `recruit_chat` (
  `chat_num` int NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(30) DEFAULT NULL,
  `chat_datetime` datetime DEFAULT NULL,
  `chat_content` varchar(1000) DEFAULT NULL,
  `room_num` int DEFAULT NULL,
  PRIMARY KEY (`chat_num`),
  KEY `fk_sender_id` (`sender_id`),
  KEY `fk_room_num` (`room_num`)
) ENGINE=MyISAM AUTO_INCREMENT=327 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `recruit_chat`
--

INSERT INTO `recruit_chat` (`chat_num`, `sender_id`, `chat_datetime`, `chat_content`, `room_num`) VALUES
(323, 'No.1', '2024-12-06 10:47:53', 'おはようございます', 51),
(324, 'No.1', '2025-01-25 02:10:36', 'aaa', 51),
(325, 'No.1', '2025-04-10 12:35:18', 'あああ', 51),
(326, 'No.1', '2025-04-10 12:35:24', 'fromNo.2', 51),
(138, 'No.1', '2024-11-29 21:34:29', 'ああああああああaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(139, 'No.1', '2024-11-29 21:36:01', 'ああああああああaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(140, 'No.1', '2024-11-29 21:36:16', 'ああああああああaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(141, 'No.1', '2024-11-29 21:36:28', 'お世話になっております。', 36),
(142, 'No.1', '2024-11-29 21:36:40', 'お世話になっております。', 36),
(143, 'No.1', '2024-11-29 21:36:54', 'お世話になっております。', 36),
(144, 'No.1', '2024-11-29 21:37:02', 'お世話になっております。', 36),
(145, 'No.1', '2024-11-29 21:37:56', 'お世話になっております。', 36),
(146, 'No.1', '2024-11-29 21:38:22', 'お世話になっております。', 36),
(147, 'No.1', '2024-11-29 21:39:58', 'お世話になっております。', 36),
(148, 'No.1', '2024-11-29 21:42:03', 'お世話になっております。', 36),
(149, 'No.1', '2024-11-29 21:43:03', 'a', 33),
(150, 'No.1', '2024-11-29 21:43:05', 'a', 33),
(151, 'No.1', '2024-11-29 21:43:05', '', 33),
(152, 'No.1', '2024-11-29 21:43:06', '', 33),
(153, 'No.1', '2024-11-29 21:43:07', '', 33),
(154, 'No.1', '2024-11-29 21:43:07', '', 33),
(155, 'No.1', '2024-11-29 21:43:07', '', 33),
(156, 'No.1', '2024-11-29 21:43:07', '', 33),
(157, 'No.1', '2024-11-29 21:43:07', '', 33),
(158, 'No.1', '2024-11-29 21:43:08', '', 33),
(159, 'No.1', '2024-11-29 21:43:08', '', 33),
(160, 'No.1', '2024-11-29 21:43:09', '', 33),
(161, 'No.1', '2024-11-29 21:43:09', '', 33),
(162, 'No.1', '2024-11-29 21:43:09', '', 33),
(163, 'No.1', '2024-11-29 21:43:09', '', 33),
(164, 'No.1', '2024-11-29 21:43:09', '', 33),
(165, 'No.1', '2024-11-29 21:43:10', '', 33),
(166, 'No.1', '2024-11-29 21:43:10', '', 33),
(167, 'No.1', '2024-11-29 21:43:10', '', 33),
(168, 'No.1', '2024-11-29 21:43:10', '', 33),
(169, 'No.1', '2024-11-29 21:43:10', '', 33),
(170, 'No.1', '2024-11-29 21:55:03', 'あ', 36),
(171, 'No.1', '2024-11-29 21:56:12', 'あ', 36),
(172, 'No.1', '2024-11-29 21:57:44', 'あ', 36),
(173, 'No.1', '2024-11-29 21:58:05', 'あ', 36),
(174, 'No.1', '2024-11-29 21:59:49', 'あ', 36),
(175, 'No.1', '2024-11-29 22:00:15', 'あ', 36),
(176, 'No.1', '2024-11-29 22:00:41', 'あ', 36),
(177, 'No.1', '2024-11-29 22:01:13', 'あ', 36),
(178, 'No.1', '2024-11-29 22:01:53', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(179, 'No.1', '2024-11-29 22:05:21', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(180, 'No.1', '2024-11-29 22:10:45', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(181, 'No.1', '2024-11-29 22:11:06', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(182, 'No.1', '2024-11-29 22:12:14', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(183, 'No.1', '2024-11-29 22:12:42', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(184, 'No.1', '2024-11-29 22:20:45', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(185, 'No.1', '2024-11-29 22:21:07', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(186, 'No.1', '2024-11-29 22:21:22', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(187, 'No.1', '2024-11-29 22:22:01', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(188, 'No.1', '2024-11-29 22:22:21', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(189, 'No.1', '2024-11-29 22:22:49', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(190, 'No.1', '2024-11-29 22:23:42', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(191, 'No.1', '2024-11-29 22:25:38', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(192, 'No.1', '2024-11-29 22:26:10', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(193, 'No.1', '2024-11-29 22:26:31', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(194, 'No.1', '2024-11-29 22:38:44', 'あああああああああああああああああああああああああああああああああああああああああ', 36),
(195, 'No.1', '2024-11-29 22:42:33', 'あああああああああああああああああああああああああああああああああああああああああ', 36),
(196, 'No.1', '2024-11-29 22:43:01', 'あああああああああああああああああああああああああああああああああああああああああ', 36),
(197, 'No.1', '2024-11-29 22:44:10', 'あああああああああああああああああああああああああああああああああああああああああ', 36),
(198, 'No.1', '2024-11-29 22:45:03', 'あああああああああああああああああああああああああああああああああああああああああ', 36),
(199, 'No.2', '2024-11-29 22:46:42', 'iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', 36),
(200, 'No.2', '2024-11-29 22:46:47', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(201, 'No.2', '2024-11-29 22:51:34', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(202, 'No.2', '2024-11-29 22:51:42', 'あああああああああああああああああ', 36),
(203, 'No.1', '2024-11-29 22:51:56', 'ああああああああ', 36),
(204, 'No.1', '2024-11-29 22:52:00', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 36),
(205, 'No.1', '2024-11-29 22:56:51', '', 33),
(206, 'No.1', '2024-11-29 23:00:07', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaあ', 36),
(207, 'No.1', '2024-11-29 23:00:15', 'あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ', 36),
(208, 'No.1', '2024-11-30 01:07:42', 'a', 36),
(209, 'No.1', '2024-11-30 01:07:53', 'aaaaaa', 36),
(210, 'No.1', '2024-11-30 01:07:55', 'aaaaaaaaaaaaaa', 36),
(211, 'No.1', '2024-11-30 01:07:58', 'aaaaaaa', 36),
(212, 'No.1', '2024-11-30 01:08:01', 'aaaaaaaaaaaaaaaaaaaaaa', 36),
(213, 'No.1', '2024-11-30 01:08:03', 'aaaaaaaaaaaaaaaaaaaaaaaa', 36),
(214, 'No.1', '2024-11-30 13:13:50', 'aaaaaaa', 33),
(215, 'No.1', '2024-11-30 13:13:52', 'aaaaaa', 33),
(216, 'No.1', '2024-11-30 13:14:01', 'aaaaaaa', 33),
(217, 'No.2', '2024-11-30 13:15:46', 'a', 36),
(218, 'No.2', '2024-11-30 13:15:50', 'qqq', 36),
(219, 'No.2', '2024-11-30 13:21:28', 'a', 36),
(220, 'No.2', '2024-11-30 21:46:03', 'ああ', 33),
(221, 'No.2', '2024-11-30 21:46:35', 'ああ', 33),
(222, 'No.2', '2024-11-30 21:46:56', 'ああ', 33),
(223, 'No.2', '2024-11-30 21:46:58', 'ああ', 33),
(224, 'No.2', '2024-11-30 21:48:36', 'ああ', 33),
(225, 'No.2', '2024-11-30 21:52:43', 'ああ', 33),
(226, 'No.1', '2024-12-02 14:41:32', 'aa', 36),
(227, 'No.1', '2024-12-02 14:41:33', 'aa', 36),
(228, 'No.1', '2024-12-02 14:44:46', 'aaa', 0),
(229, 'No.1', '2024-12-02 14:45:59', 'fromNo.1', 0),
(230, 'No.1', '2024-12-02 14:46:58', 'fromNo.1', 0),
(231, 'No.1', '2024-12-02 14:47:02', 'fromNo.2', 0),
(232, 'No.1', '2024-12-02 14:48:00', 'fromNo.2', 0),
(233, 'No.1', '2024-12-02 14:49:05', 'aaa', 36),
(234, 'No.1', '2024-12-02 16:37:53', 'aaaaaaaaaaa', 0),
(235, 'No.1', '2024-12-02 16:38:03', 'aaaaaaaa', 0),
(236, 'No.1', '2024-12-02 16:38:13', 'aaaaaaa', 37),
(237, 'No.1', '2024-12-02 16:38:15', 'aaaaaaaaa', 37),
(238, 'No.1', '2024-12-02 16:38:17', 'aaaaaaaa', 37),
(239, 'No.1', '2024-12-02 16:38:18', 'aaaaaaaaaa', 37),
(240, 'No.1', '2024-12-02 16:38:19', 'aaaaaaa', 37),
(241, 'No.1', '2024-12-02 16:38:21', 'aaaaaaaaa', 37),
(242, 'No.1', '2024-12-02 16:38:22', 'aaaaaaaa', 37),
(243, 'No.1', '2024-12-02 16:38:23', 'aaaaaaaa', 37),
(244, 'No.1', '2024-12-02 16:38:29', 'aaaa', 0),
(245, 'No.1', '2024-12-02 16:38:47', 'aa', 0),
(246, 'No.1', '2024-12-02 16:39:49', 'aaaaaaaaa', 37),
(247, 'No.1', '2024-12-02 16:39:51', 'aaaaaaa', 37),
(248, 'No.1', '2024-12-02 18:01:19', 'おはようございますfromNo.1', 38),
(249, 'No.3', '2024-12-02 18:01:34', 'おはようございますfromNo.3', 38),
(250, 'No.3', '2024-12-02 18:01:37', 'a', 38),
(251, 'No.3', '2024-12-02 18:01:40', 'aa', 38);

-- --------------------------------------------------------

--
-- テーブルの構造 `recruit_chat_room`
--

DROP TABLE IF EXISTS `recruit_chat_room`;
CREATE TABLE IF NOT EXISTS `recruit_chat_room` (
  `room_num` int NOT NULL AUTO_INCREMENT,
  `recruit_num` int DEFAULT NULL,
  `active` tinyint NOT NULL,
  PRIMARY KEY (`room_num`),
  KEY `fk_cr_recruit_num` (`recruit_num`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `recruit_chat_room`
--

INSERT INTO `recruit_chat_room` (`room_num`, `recruit_num`, `active`) VALUES
(51, 82, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `recruit_tech`
--

DROP TABLE IF EXISTS `recruit_tech`;
CREATE TABLE IF NOT EXISTS `recruit_tech` (
  `recruit_num` int DEFAULT NULL,
  `recruit_tech` varchar(20) NOT NULL,
  KEY `fk_tech_recruit_num` (`recruit_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
CREATE TABLE IF NOT EXISTS `userinfo` (
  `user_num` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(30) NOT NULL,
  `user_pw` varchar(20) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_role` tinyint DEFAULT NULL,
  `user_profile` varchar(1000) DEFAULT NULL,
  `user_icon` varchar(50) DEFAULT 'img/default.png',
  PRIMARY KEY (`user_num`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `userinfo`
--

INSERT INTO `userinfo` (`user_num`, `user_id`, `user_pw`, `user_name`, `user_role`, `user_profile`, `user_icon`) VALUES
(1, 'No.1', '1111', 'No.1', 0, 'テスト用アカウントNo.1', 'uploads/cb9640da5fbb8a546e4c38a88b2cc72f.png'),
(2, 'No.2', '2222', 'No.2', 1, 'テスト用アカウントNo.2', 'img/default.png'),
(3, 'No.3', '3333', 'アカウント3', 1, 'がんばります', 'img/default.png'),
(4, 'No.4', '4444', 'アカウント4', 1, 'テスト用アカウントNo.4', 'img/default.png'),
(5, 'No.5', '5555', 'アカウント5', 2, 'テスト用アカウントNo.5', 'img/default.png'),
(6, 'No.6', '6666', 'No.6', 2, 'てすと', 'img/default.png'),
(7, '7', '7777', '7777', 2, '1', 'img/default.png'),
(8, 'No.8', '8888', 'No.8', 2, '8888', 'img/default.png'),
(9, 'No.9', '9999', 'No.9', 0, '9999', 'img/default.png'),
(10, 'No.10', '1010', 'No.10', 1, '1010', 'img/default.png'),
(11, 'No.7', '7777', 'No.7', 0, '7777', 'img/default.png');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
