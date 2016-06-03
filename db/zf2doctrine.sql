-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2015 at 04:07 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zf2doctrine`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'PHP Basic'),
(2, 'Codeigniter 2.x'),
(3, 'Laravel Framework 4.x'),
(4, 'Zend Framework 2.x');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8 NOT NULL,
  `email` varchar(200) NOT NULL,
  `post_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content`, `email`, `post_id`, `date_created`) VALUES
(2, 'Bài viết này tạm được', 'kenny@qhonline.info', 1, '2015-04-22 07:29:56'),
(3, 'Bài viết rất tốt, phát huy nhé.', 'kenny@qhonline.info', 1, '2015-04-22 07:43:07'),
(4, 'Bài viết rất hay', 'kenny@qhonline.info', 10, '2015-04-27 07:29:31'),
(5, 'Phát huy nữa bạn nhé.', 'kaka345@gmail.com', 10, '2015-04-27 07:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 NOT NULL,
  `info` text CHARACTER SET utf8 NOT NULL,
  `content` longtext CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `info`, `content`, `status`, `cate_id`, `date_created`) VALUES
(1, 'Cài đặt PHP', '<p>M&ocirc; tả c&agrave;i đặt PHP</p>', '<p>Nội dung chi tiết c&agrave;i đặt PHP</p>', 2, 1, '2015-04-19 04:55:40'),
(2, 'Câu lệnh điều kiện trong PHP', 'mô tả về câu lệnh điều kiện', 'Chi tiết về câu lệnh điều kiện', 2, 1, '2015-04-15 11:05:38'),
(3, 'Khái niệm controller trong CI', 'Mô tả khái niệm controller', 'Chi tiết khái niệm controller', 2, 2, '2015-04-15 11:05:38'),
(4, 'Route trong Laravel 4.x', 'Mô tả route trong laravel 4.x', 'Chi tiết Route trong laravel', 2, 3, '2015-04-15 11:06:37'),
(6, 'Làm việc với doctrine trong Zend 2.x', '<p>M&ocirc; tả về c&aacute;ch l&agrave;m việc doctrine trong Zend 2.x</p>', '<p>Chi tiết về c&aacute;ch l&agrave;m việc trong Zend 2.x</p>', 2, 4, '2015-04-28 12:23:29'),
(7, 'Khái niệm về model', '<p>M&ocirc; tả về model</p>', '<p>chi tiết về model</p>', 2, 1, '2015-04-28 13:15:41'),
(8, 'Lập trình hướng đối tượng', '<p><strong>M&ocirc; tả về lập tr&igrave;nh hướng đối tượng&nbsp;</strong></p>', '<p><em><strong>Chi tiết về lập tr&igrave;nh hướng đối tượng</strong></em></p>', 2, 1, '2015-04-18 05:58:46'),
(10, 'Doctrine trong Zend 2.3', '<p>M&ocirc; tả phần doctrine trong zend 2</p>', '<p>Chi tiết doctrine trong zend 2</p>', 2, 4, '2015-04-19 04:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `post_tag`
--

CREATE TABLE IF NOT EXISTS `post_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `post_tag`
--

INSERT INTO `post_tag` (`id`, `post_id`, `tag_id`) VALUES
(15, 10, 4),
(16, 10, 5),
(17, 10, 6),
(18, 1, 8),
(19, 1, 9),
(20, 1, 10),
(21, 6, 5),
(22, 6, 4),
(23, 7, 4),
(24, 8, 4),
(29, 11, 5),
(30, 11, 11),
(31, 11, 12);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'php'),
(2, 'framework'),
(3, 'laravel'),
(4, 'doctrine'),
(5, 'zend 2'),
(6, 'php framework'),
(7, 'zend model'),
(8, 'cai dat php'),
(9, 'php basic'),
(10, 'php setup'),
(11, 'tableGateway'),
(12, 'model'),
(13, 'database');

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
