-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2025 at 12:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beanappetit`
--
CREATE DATABASE IF NOT EXISTS `beanappetit` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `beanappetit`;

-- --------------------------------------------------------

--
-- Table structure for table `cartpage`
--

CREATE TABLE `cartpage` (
  `customer id` varchar(100) NOT NULL,
  `customer name` varchar(100) NOT NULL,
  `product id` varchar(100) NOT NULL,
  `product name` varchar(200) NOT NULL,
  `qty` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderspage`
--

CREATE TABLE `orderspage` (
  `order id` varchar(100) NOT NULL,
  `customer id` varchar(100) NOT NULL,
  `customer name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `product id` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `qty` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartpage`
--
ALTER TABLE `cartpage`
  ADD PRIMARY KEY (`customer id`,`product id`);

--
-- Indexes for table `orderspage`
--
ALTER TABLE `orderspage`
  ADD PRIMARY KEY (`order id`,`customer id`,`product id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
