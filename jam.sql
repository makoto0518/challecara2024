-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1:3306
-- 生成日時: 2024-12-03 07:50:47
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
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `jam`
--

INSERT INTO `jam` (`jam_num`, `hackason_name`, `hackason_location`, `host_id`, `jam_start_time`, `jam_period_time`, `team_min`, `team_max`, `active`, `jam_password`) VALUES
(72, 'テスト2', 27, 'No.1', '2024-12-10 10:00:00', '2024-12-10 18:00:00', 4, 6, 1, '1111'),
(73, 'テスト3', 40, 'No.1', '2024-12-15 10:00:00', '2024-12-15 18:00:00', 2, 4, 1, '1111'),
(71, 'チャレンジキャラバン2024', 40, 'No.1', '2024-12-04 10:00:00', '2024-12-14 18:00:00', 3, 5, 1, '1111');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
