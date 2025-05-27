-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1:3306
-- 生成日時: 2024-12-03 07:53:50
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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `jam_apply`
--

INSERT INTO `jam_apply` (`apply_num`, `jam_num`, `applicant_id`, `applicant_role`) VALUES
(16, 71, 'No.2', 0),
(17, 71, 'No.3', 0),
(18, 71, 'No.4', 0),
(19, 71, 'No.5', 1),
(20, 71, 'No.6', 1),
(21, 71, 'No.7', 1),
(22, 71, 'No.8', 2),
(23, 71, 'No.9', 2),
(24, 71, 'No.10', 2),


(25, 72, 'No.2', 0),
(26, 72, 'No.3', 0),
(27, 72, 'No.4', 0),
(28, 72, 'No.5', 1),
(29, 72, 'No.6', 1),
(30, 72, 'No.7', 1),
(31, 72, 'No.8', 2),
(32, 72, 'No.9', 2),
(33, 72, 'No.10', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
