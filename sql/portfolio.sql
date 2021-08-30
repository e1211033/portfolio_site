-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql
-- 生成日時: 2021 年 8 月 26 日 01:01
-- サーバのバージョン： 5.7.34
-- PHP のバージョン: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `portfolio`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `ec_cart`
--

CREATE TABLE `ec_cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `create_datetime` datetime DEFAULT '0000-00-00 00:00:00',
  `update_datetime` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `ec_item_master`
--

CREATE TABLE `ec_item_master` (
  `item_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `img` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `area_detail` int(11) NOT NULL,
  `create_datetime` datetime DEFAULT '0000-00-00 00:00:00',
  `update_datetime` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `ec_item_stock`
--

CREATE TABLE `ec_item_stock` (
  `item_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `create_datetime` datetime DEFAULT '0000-00-00 00:00:00',
  `update_datetime` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `ec_item_stock`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `ec_user`
--

CREATE TABLE `ec_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `create_datetime` datetime DEFAULT '0000-00-00 00:00:00',
  `update_datetime` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `ec_cart`
--
ALTER TABLE `ec_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- テーブルのインデックス `ec_item_master`
--
ALTER TABLE `ec_item_master`
  ADD PRIMARY KEY (`item_id`);

--
-- テーブルのインデックス `ec_item_stock`
--
ALTER TABLE `ec_item_stock`
  ADD PRIMARY KEY (`item_id`);

--
-- テーブルのインデックス `ec_user`
--
ALTER TABLE `ec_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `ec_cart`
--
ALTER TABLE `ec_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- テーブルの AUTO_INCREMENT `ec_item_master`
--
ALTER TABLE `ec_item_master`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルの AUTO_INCREMENT `ec_user`
--
ALTER TABLE `ec_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `ec_cart`
--
ALTER TABLE `ec_cart`
  ADD CONSTRAINT `ec_cart_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ec_item_master` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ec_cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `ec_user` (`user_id`) ON DELETE CASCADE;

--
-- テーブルの制約 `ec_item_stock`
--
ALTER TABLE `ec_item_stock`
  ADD CONSTRAINT `ec_item_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ec_item_master` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
