-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1:3306
-- 生成日時: 2024-12-03 07:50:11
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
(7, 'No.7', '7777', '7777', 2, '1', 'img/default.png'),
(8, 'No.8', '8888', 'No.8', 2, '8888', 'img/default.png'),
(9, 'No.9', '9999', 'No.9', 0, '9999', 'img/default.png'),
(10, 'No.10', '1010', 'No.10', 1, '1010', 'img/default.png'),
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
