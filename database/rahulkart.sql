-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2019 at 08:23 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rahulkart`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currency`
--

CREATE TABLE `tbl_currency` (
  `currency_id` int(10) NOT NULL,
  `currency_code` varchar(50) NOT NULL,
  `currency_symbol` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE `tbl_language` (
  `lang_id` int(10) NOT NULL,
  `lang_code` varchar(10) NOT NULL,
  `lang_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_master`
--

CREATE TABLE `tbl_order_master` (
  `id` int(10) NOT NULL,
  `o_order_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_currency` varchar(10) NOT NULL,
  `order_amount` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_productdetail`
--

CREATE TABLE `tbl_order_productdetail` (
  `id` int(10) NOT NULL,
  `p_order_id` varchar(50) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_detail` varchar(50) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_userdetail`
--

CREATE TABLE `tbl_order_userdetail` (
  `id` int(10) NOT NULL,
  `u_order_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `email_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_currency`
--

CREATE TABLE `tbl_product_currency` (
  `id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `currency_id` int(10) NOT NULL,
  `currency_value` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_language`
--

CREATE TABLE `tbl_product_language` (
  `id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `language_id` int(10) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_details` varchar(500) NOT NULL,
  `product_add_info` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_master`
--

CREATE TABLE `tbl_product_master` (
  `product_id` int(10) NOT NULL,
  `product_status` tinyint(1) NOT NULL,
  `product_image` varchar(50) NOT NULL,
  `add_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `folder_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userdetail`
--

CREATE TABLE `tbl_userdetail` (
  `id` int(10) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `last_login` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `image_upload` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userlogin_history`
--

CREATE TABLE `tbl_userlogin_history` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `lastlogin` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`lang_id`);

--
-- Indexes for table `tbl_order_master`
--
ALTER TABLE `tbl_order_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_productdetail`
--
ALTER TABLE `tbl_order_productdetail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_userdetail`
--
ALTER TABLE `tbl_order_userdetail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_currency`
--
ALTER TABLE `tbl_product_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_language`
--
ALTER TABLE `tbl_product_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_master`
--
ALTER TABLE `tbl_product_master`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_userdetail`
--
ALTER TABLE `tbl_userdetail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_userlogin_history`
--
ALTER TABLE `tbl_userlogin_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  MODIFY `currency_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `lang_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_master`
--
ALTER TABLE `tbl_order_master`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_productdetail`
--
ALTER TABLE `tbl_order_productdetail`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_userdetail`
--
ALTER TABLE `tbl_order_userdetail`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_currency`
--
ALTER TABLE `tbl_product_currency`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_language`
--
ALTER TABLE `tbl_product_language`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_master`
--
ALTER TABLE `tbl_product_master`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_userdetail`
--
ALTER TABLE `tbl_userdetail`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_userlogin_history`
--
ALTER TABLE `tbl_userlogin_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
