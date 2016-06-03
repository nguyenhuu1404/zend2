-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2015 at 04:05 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zf2online`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `img` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `price` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `info`, `img`, `author`, `price`) VALUES
(1, 'PHP Co Ban', 'Thong tin ve PHP co ban tai day', 'data/phpcoban.jpg', 'Bui Quoc Huy', 20),
(2, 'PHP nang cao', 'Thong tin ve sach PHP nang cao', 'data/phpnangcao.jpg', 'Bui Quoc Huy', 25),
(3, 'CodeIgniter', 'Thong tin ve sach Codeigniter', 'data/ci.jpg', 'Bui Quoc Huy', 40),
(4, 'Laravel 4', 'Thong tin ve sach Laravel', 'data/lar.jpg', 'Bui Quoc Huy', 60);

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `message` varchar(255) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `user_id`, `message`, `stamp`) VALUES
(1, 1, 'hello 123', '2014-10-29 09:42:27'),
(2, 1, 'xin chào', '2014-10-29 09:55:58'),
(3, 1, 'How are you ?', '2014-10-29 10:11:11'),
(4, 1, 'alo 123', '2014-10-29 10:45:19'),
(5, 1, 'bạn có nghe tôi không ?', '2014-10-29 10:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `filename`, `label`, `user_id`) VALUES
(2, 'design_layout_document.rar', 'Tai lieu Laravel', 1),
(3, 'qh-shoutbox.rar', 'Tài liệu Ajax Demo tập 01', 1),
(4, 'zend_07372193818372364889.rar', 'Tài liệu Zend 2.x Tập 2', 3),
(5, 'zend08_47382874829882745.rar', 'Tai lieu zend 2.x Tập 3', 3),
(6, 'oop_p02_736482.rar', 'Tài liệu OOP', 8);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE IF NOT EXISTS `level` (
  `lv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lv_name` varchar(50) NOT NULL,
  PRIMARY KEY (`lv_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`lv_id`, `lv_name`) VALUES
(1, 'Member'),
(2, 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` float(9,2) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `detail` text NOT NULL,
  `ship_name` varchar(255) DEFAULT NULL,
  `ship_address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `stamp`, `detail`, `ship_name`, `ship_address`) VALUES
(1, 1, 40.00, '2015-01-16 11:41:46', 'a:1:{i:1;a:3:{s:4:"name";s:10:"PHP Co Ban";s:3:"qty";s:1:"2";s:5:"price";s:5:"20.00";}}', 'Tran Hoang', '1 Main St, San Jose, United States'),
(2, 1, 40.00, '2015-01-16 12:21:47', 'a:1:{i:1;a:3:{s:4:"name";s:10:"PHP Co Ban";s:3:"qty";s:1:"2";s:5:"price";s:5:"20.00";}}', 'Tran Hoang', '1 Main St, San Jose, United States'),
(3, 1, 60.00, '2015-01-16 12:24:40', 'a:1:{i:1;a:3:{s:4:"name";s:10:"PHP Co Ban";s:3:"qty";i:3;s:5:"price";s:5:"20.00";}}', 'Tran Hoang', '1 Main St, San Jose, United States');

-- --------------------------------------------------------

--
-- Table structure for table `sharings`
--

CREATE TABLE IF NOT EXISTS `sharings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_id` (`file_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `sharings`
--

INSERT INTO `sharings` (`id`, `file_id`, `user_id`, `stamp`) VALUES
(3, 2, 8, '2014-11-13 09:51:01'),
(4, 2, 9, '2014-11-13 09:57:40'),
(5, 2, 10, '2014-11-13 09:57:46'),
(7, 4, 1, '2014-11-22 04:59:41'),
(8, 5, 1, '2014-11-22 05:00:06'),
(9, 6, 1, '2014-11-22 05:02:00'),
(10, 6, 3, '2014-11-22 05:02:05'),
(11, 3, 8, '2014-12-02 09:51:57'),
(12, 3, 9, '2014-12-02 09:52:04'),
(15, 3, 10, '2014-12-02 09:53:38'),
(16, 3, 11, '2014-12-02 09:59:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` char(70) NOT NULL,
  `email` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(1) NOT NULL DEFAULT '1',
  `access` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `level`, `access`) VALUES
(1, 'kenny', '827ccb0eea8a706c4c34a16891f84e7b', 'kenny@qhonline.info', 'Kenny Huy', 2, NULL),
(3, 'jacky', '827ccb0eea8a706c4c34a16891f84e7b', 'jacky@qhonline.info', 'Jack Nguyen', 1, 'a:1:{s:8:"training";a:3:{s:4:"user";a:2:{i:0;s:5:"index";i:1;s:3:"add";}s:4:"chat";a:2:{i:0;s:5:"index";i:1;s:11:"listMessage";}s:4:"book";a:4:{i:0;s:5:"index";i:1;s:7:"addItem";i:2;s:4:"cart";i:3;s:10:"updateItem";}}}'),
(8, 'Kaka', '827ccb0eea8a706c4c34a16891f84e7b', 'kaka@qhonline.info', 'Kaka Tran', 2, NULL),
(9, 'lili', '827ccb0eea8a706c4c34a16891f84e7b', 'lili@qhonline.info', 'Lili Nguyen', 1, NULL),
(10, 'kaka123', '12345', 'kaka@gmail.com', 'Kaka Hoang', 1, NULL),
(11, 'Misa', '827ccb0eea8a706c4c34a16891f84e7b', 'misa@gmail.com', 'Misa Nguyen', 1, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
