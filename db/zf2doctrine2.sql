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
-- Database: `zf2doctrine`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Php co ban'),
(2, 'CI');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `post_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content`, `email`, `post_id`, `date_created`) VALUES
(1, 'tot tot tot', 'nguyenhuu140490@gmail.com', 1, '2016-05-31 22:10:40'),
(2, 'tét', 'loinguyencauanhdanhchoem_2006@yahoo.com', 1, '2016-05-31 22:11:20');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `info` text NOT NULL,
  `content` longtext NOT NULL,
  `status` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `info`, `content`, `status`, `cate_id`, `date_created`) VALUES
(1, 'cai dat php', '<p>cai dat php</p>', '<p>dfdf df</p>', 2, 1, '2016-05-27 04:42:29'),
(2, 'ci', '<p>ci</p>', '<p>ci</p>', 2, 2, '2016-05-27 04:42:42'),
(4, 'phan trang2 + 3', '<p>fd</p>', '<p>df</p>', 2, 1, '2016-06-01 07:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `post_tag`
--

CREATE TABLE `post_tag` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_tag`
--

INSERT INTO `post_tag` (`id`, `post_id`, `tag_id`) VALUES
(9, 1, 4),
(10, 1, 5),
(11, 2, 2),
(16, 4, 4),
(17, 4, 7),
(18, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'validator'),
(2, 'Ci'),
(3, 'Ci2'),
(4, 'php'),
(5, 'Cai dat'),
(6, 'doctrine'),
(7, 'co ban'),
(8, 'zend 2');

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
(1, 'kenny', '827ccb0eea8a706c4c34a16891f84e7b', 'kenny@qhonline.info', 'Kenny Huy', 2, NULL),
(3, 'jacky', '827ccb0eea8a706c4c34a16891f84e7b', 'jacky@qhonline.info', 'Jack Nguyen', 1, 'a:1:{s:8:"training";a:3:{s:4:"user";a:2:{i:0;s:5:"index";i:1;s:3:"add";}s:4:"chat";a:2:{i:0;s:5:"index";i:1;s:11:"listMessage";}s:4:"book";a:4:{i:0;s:5:"index";i:1;s:7:"addItem";i:2;s:4:"cart";i:3;s:10:"updateItem";}}}'),
(8, 'Kaka', '827ccb0eea8a706c4c34a16891f84e7b', 'kaka@qhonline.info', 'Kaka Tran', 2, NULL),
(9, 'lili', '827ccb0eea8a706c4c34a16891f84e7b', 'lili@qhonline.info', 'Lili Nguyen', 1, NULL),
(10, 'kaka123', '12345', 'kaka@gmail.com', 'Kaka Hoang', 1, NULL),
(11, 'Misa', '827ccb0eea8a706c4c34a16891f84e7b', 'misa@gmail.com', 'Misa Nguyen', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `post_tag`
--
ALTER TABLE `post_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
