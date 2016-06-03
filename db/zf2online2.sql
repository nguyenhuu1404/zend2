-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2016 at 05:17 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zf2online`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `img` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `price` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `chats` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `message` varchar(255) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `level` (
  `lv_id` int(10) UNSIGNED NOT NULL,
  `lv_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` float(9,2) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `detail` text NOT NULL,
  `ship_name` varchar(255) DEFAULT NULL,
  `ship_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `sharings` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` char(70) NOT NULL,
  `email` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(1) NOT NULL DEFAULT '1',
  `access` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `level`, `access`) VALUES
(1, 'kenny', '827ccb0eea8a706c4c34a16891f84e7b', 'kenny@qhonline.info', 'Kenny Huy', 2, 'a:1:{s:8:"training";a:4:{s:4:"user";a:4:{i:0;s:5:"index";i:1;s:3:"add";i:2;s:4:"edit";i:3;s:3:"del";}s:4:"file";a:2:{i:0;s:5:"index";i:1;s:3:"add";}s:4:"chat";a:1:{i:0;s:5:"index";}s:4:"book";a:2:{i:0;s:5:"index";i:1;s:7:"addItem";}}}'),
(3, 'jacky', '827ccb0eea8a706c4c34a16891f84e7b', 'jacky@qhonline.info', 'Jack Nguyen', 1, 'a:1:{s:8:"training";a:2:{s:4:"user";a:1:{i:0;s:5:"index";}s:4:"file";a:1:{i:0;s:5:"index";}}}'),
(8, 'Kaka', '827ccb0eea8a706c4c34a16891f84e7b', 'kaka@qhonline.info', 'Kaka Tran', 2, NULL),
(9, 'lili', '827ccb0eea8a706c4c34a16891f84e7b', 'lili@qhonline.info', 'Lili Nguyen', 1, NULL),
(10, 'kaka123', '12345', 'kaka@gmail.com', 'Kaka Hoang', 1, NULL),
(11, 'Misa', '827ccb0eea8a706c4c34a16891f84e7b', 'misa@gmail.com', 'Misa Nguyen', 1, NULL),
(12, 'huunv90', 'e10adc3949ba59abbe56e057f20f883e', 'nguyen@gmail.com', 'huu', 2, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`lv_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sharings`
--
ALTER TABLE `sharings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `file_id` (`file_id`,`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `lv_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sharings`
--
ALTER TABLE `sharings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
