-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2024 at 09:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bit`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `parent_cat` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(50) NOT NULL,
  `p_cat_id` int(20) DEFAULT NULL,
  `category_name` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `p_cat_id`, `category_name`, `status`) VALUES
(1, 1, 'AMD Processors', 1),
(2, 1, 'Intel Processors 2', 0),
(3, 2, 'ASUS Motherboards', 1),
(4, 2, 'MSI Motherboards', 1),
(5, 3, 'NVIDIA Graphics Cards', 1),
(6, 3, 'AMD Graphics Cards', 1),
(7, 4, 'Corsair RAM', 1),
(8, 1, 'Samsung Processors', 1),
(9, 1, 'Intel i5 Processor', 1),
(10, 4, 'DDR 3', 1),
(11, 5, 'SSD', 1),
(12, 6, 'MSI', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `address` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `title`, `firstname`, `lastname`, `address`, `email`, `mobile`, `nic`, `description`, `status`) VALUES
(1, 'Mr', 'Sarath', 'Wijerathne', 'Hapugahagama,Divulapituya', 'sarath@gmail.com', '0312246066', '2147483647', 'Test Description', 1),
(2, 'Mr', 'Nadun', 'Pabasara', 'Divulapitiya', 'nadun@gmail.com', '0714563295', '2147483647', '3t', 1),
(3, 'Mr', 'Nilantha', 'Perera', 'Gampaha', 'nilantha@gmail.com', '0714523016', '214748364V', 'No', 1),
(4, 'Ms', 'Thilini', 'Perera', 'Gampaha', 'thilini@gmail.com', '0714563289', '1236547878V', 'No', 0);

-- --------------------------------------------------------

--
-- Table structure for table `grn`
--

CREATE TABLE `grn` (
  `id` int(30) NOT NULL,
  `purchases` int(30) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) NOT NULL,
  `dlt` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grn`
--

INSERT INTO `grn` (`id`, `purchases`, `date`, `status`, `dlt`) VALUES
(1, 1, '2023-12-26 23:18:57', 1, 0),
(2, 3, '2024-08-08 21:36:52', 1, 0),
(3, 5, '2024-08-09 07:59:24', 1, 0),
(4, 13, '2024-08-09 12:02:36', 1, 0),
(5, 11, '2024-08-09 12:10:08', 1, 0),
(6, 13, '2024-08-09 12:10:51', 1, 0),
(7, 1, '2024-08-09 12:11:09', 1, 0),
(8, 15, '2024-08-09 16:24:21', 1, 0),
(9, 16, '2024-08-09 21:22:29', 1, 0),
(10, 17, '2024-08-10 12:55:24', 0, 1),
(11, 17, '2024-08-10 12:55:56', 1, 0),
(12, 17, '2024-08-10 13:00:18', 1, 0),
(13, 3, '2024-08-10 13:12:57', 1, 0),
(14, 18, '2024-08-10 13:15:53', 1, 0),
(15, 18, '2024-08-10 13:19:10', 1, 0),
(16, 15, '2024-08-10 13:19:25', 1, 0),
(17, 3, '2024-08-10 13:19:36', 1, 0),
(18, 19, '2024-08-10 13:20:18', 0, 1),
(19, 19, '2024-08-10 13:24:46', 1, 0),
(20, 20, '2024-08-10 13:25:40', 1, 0),
(21, 22, '2024-08-10 16:46:21', 1, 0),
(22, 22, '2024-08-10 17:12:18', 1, 0),
(23, 11, '2024-08-10 17:12:29', 1, 0),
(24, 22, '2024-08-10 17:26:49', 1, 0),
(25, 14, '2024-08-10 17:27:04', 1, 0),
(26, 22, '2024-08-10 17:27:41', 1, 0),
(27, 10, '2024-08-10 17:27:49', 1, 0),
(28, 22, '2024-08-11 08:22:33', 1, 0),
(29, 23, '2024-08-11 08:23:14', 1, 0),
(30, 23, '2024-08-11 08:23:35', 1, 0),
(31, 23, '2024-08-11 11:36:17', 1, 0),
(32, 23, '2024-08-11 12:36:51', 1, 0),
(33, 25, '2024-08-12 11:21:23', 1, 0),
(34, 25, '2024-08-12 11:25:16', 1, 0),
(35, 26, '2024-08-12 11:31:26', 1, 0),
(36, 26, '2024-08-12 21:59:23', 1, 0),
(37, 25, '2024-08-12 22:13:05', 1, 0),
(38, 26, '2024-08-12 22:13:15', 1, 0),
(39, 27, '2024-08-12 22:14:13', 1, 0),
(40, 29, '2024-08-12 22:16:30', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `grn_details`
--

CREATE TABLE `grn_details` (
  `id` int(20) NOT NULL,
  `grn_id` int(20) NOT NULL,
  `product` varchar(100) NOT NULL,
  `quantity` int(20) NOT NULL,
  `buy` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dlt` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grn_details`
--

INSERT INTO `grn_details` (`id`, `grn_id`, `product`, `quantity`, `buy`, `status`, `dlt`) VALUES
(1, 1, '4', 8, 45000, 1, 0),
(2, 8, '11', 10, 15000, 1, 0),
(3, 9, '12', 10, 6000, 1, 0),
(4, 12, '2', 10, 2000, 1, 0),
(5, 12, '12', 10, 10000, 1, 0),
(6, 14, '7', 5, 8000, 1, 0),
(7, 14, '4', 10, 12000, 1, 0),
(8, 18, '1', 8, 10000, 0, 0),
(9, 20, '11', 5, 6000, 1, 0),
(10, 20, '12', 6, 10000, 1, 0),
(11, 21, '7', 10, 20000, 1, 0),
(12, 23, '7', 1, 10000, 1, 0),
(13, 25, '11', 1, 10000, 1, 0),
(14, 27, '7', 1, 10000, 1, 0),
(15, 30, '12', 10, 10000, 1, 0),
(16, 31, '12', 8, 15000, 1, 0),
(17, 32, '12', 2, 12000, 1, 0),
(18, 33, '7', 7, 2000, 1, 0),
(19, 33, '11', 5, 2000, 1, 0),
(20, 33, '12', 3, 3000, 1, 0),
(21, 34, '7', 1, 5000, 1, 0),
(22, 35, '10', 5, 6000, 1, 0),
(23, 35, '5', 4, 5000, 1, 0),
(24, 39, '4', 10, 20000, 1, 0),
(25, 40, '10', 10, 20000, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `customer` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dlt` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `date`, `customer`, `status`, `dlt`) VALUES
(1, '2024-08-08 19:21:07', '2', 1, 0),
(2, '2024-08-09 12:12:06', '4', 1, 0),
(3, '2024-08-09 12:12:48', '4', 1, 0),
(4, '2024-08-10 16:52:16', '3', 1, 0),
(5, '2024-08-11 15:37:34', '2', 1, 0),
(6, '2024-08-12 11:33:25', '1', 1, 0),
(7, '2024-08-12 11:34:30', '2', 1, 0),
(8, '2024-08-12 19:12:25', '3', 1, 0),
(9, '2024-08-12 21:15:04', '2', 1, 0),
(10, '2024-08-13 11:10:58', '3', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoices_details`
--

CREATE TABLE `invoices_details` (
  `id` int(11) NOT NULL,
  `invoices_id` int(11) NOT NULL,
  `product` varchar(100) NOT NULL,
  `quantity` int(10) NOT NULL,
  `sell` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dlt` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices_details`
--

INSERT INTO `invoices_details` (`id`, `invoices_id`, `product`, `quantity`, `sell`, `status`, `dlt`) VALUES
(1, 1, '6', 1, 27000, 1, 0),
(2, 1, '1', 2, 55000, 1, 0),
(3, 1, '5', 1, 67000, 1, 0),
(4, 3, '5', 1, 66000, 1, 0),
(5, 4, '1', 2, 55000, 1, 0),
(6, 5, '5', 2, 65000, 1, 0),
(7, 6, '7', 5, 50000, 1, 0),
(8, 8, '7', 2, 55000, 1, 0),
(9, 9, '10', 1, 71000, 1, 0),
(10, 9, '4', 2, 81000, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category` int(10) NOT NULL,
  `name` varchar(250) NOT NULL,
  `price` int(11) NOT NULL,
  `reorder` int(11) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category`, `name`, `price`, `reorder`, `description`, `status`) VALUES
(1, 2, 'Intel Core i9-9900K', 50000, 1, '|Intel         | Core i9-9900K     | 8 cores, 16 threads, 3.6 GHz base clock, 5.0 GHz max turbo frequency, LGA 1151, 16MB Intel Smart Cache', 1),
(2, 0, 'AMD Ryzen 7 5800X', 45000, 2, '|AMD           | Ryzen 7 5800X   | 8 cores, 16 threads, 3.8 GHz base clock, 4.7 GHz max boost clock, AM4 socket, 32MB L3 cache, 7nm Zen 3 architecture', 1),
(3, 0, 'ASUS ROG Strix Z590-E Gaming', 70000, 2, '| ASUS          | Z590-E Gaming   | ATX form factor, Intel Z590 chipset, PCIe 4.0, 14+2 power stages, Wi-Fi 6, Bluetooth 5.1, AI Noise-Canceling Microphone', 1),
(4, 0, 'MSI MPG B550 Gaming Plus', 80000, 2, '| MSI           | MPG B550 Gaming Plus| ATX form factor, AMD B550 chipset, PCIe 4.0, Mystic Light RGB, DDR4 Boost, HDMI/DP, 10+2+1 DUET Rail Power System', 1),
(5, 0, 'Intel® Core™ i7', 65000, 2, 'Total Cores\r\n4\r\n\r\nTotal Threads\r\n8\r\n\r\nMax Turbo Frequency\r\n3.90 GHz\r\n\r\nProcessor Base Frequency\r\n1.30 GHz\r\n\r\nCache\r\n8 MB Intel® Smart Cache\r\n\r\nBus Speed\r\n4 GT/s\r\n\r\nTDP\r\n15 W\r\n\r\nConfigurable TDP-up Base Frequency\r\n1.50 GHz\r\n\r\nConfigurable TDP-up\r\n25 W\r\n\r\nConfigurable TDP-down Base Frequency\r\n1.00 GHz\r\n\r\nConfigurable TDP-down\r\n12 W', 1),
(6, 0, 'AMD Radeon RX 6800 XT', 25000, 2, '| AMD           | RX 6800 XT  | 16GB GDDR6, 72 Compute Units, 2015 MHz base clock, 2250 MHz boost clock, Ray Tracing, AMD Infinity Cache, PCIe 4.0', 0),
(7, 0, 'MSI Motherboard 2', 40000, 5, '-', 1),
(8, 0, '12th Generation Intel® Core™ i5 Processors', 50000, 3, '12th Generation Intel® Core™ i5 Processors ; Intel® Core™ i5-12600K Processor. Q4&#039;21, 10, 4.90 GHz, 20 MB Intel® Smart Cache ; Intel® Core™ i5-12600KF Processor.', 1),
(9, 0, 'Intel i3 Processor', 55000, 2, '-', 0),
(10, 2, 'Intel i7 Processor', 70000, 4, '-', 1),
(11, 10, 'DDR3 SDRAM', 2000, 5, 'No Description', 1),
(12, 2, 'Intel SSD 128 GB', 10000, 4, 'No', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(20) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `supplier` int(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dlt` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `date`, `supplier`, `status`, `dlt`) VALUES
(1, '2023-12-26', 3, 0, 1),
(2, '2024-08-08', 4, 0, 1),
(3, '2024-08-08', 4, 1, 0),
(4, '2024-08-08', 4, 0, 1),
(5, '2024-08-08', 4, 1, 0),
(6, '2024-08-09', 4, 0, 1),
(7, '2024-08-09', 5, 1, 0),
(8, '2024-08-09', 5, 1, 0),
(9, '2024-08-09', 5, 0, 1),
(10, '2024-08-09', 4, 1, 0),
(11, '2024-08-09', 5, 1, 0),
(12, '2024-08-09', 6, 0, 1),
(13, '2024-08-09', 6, 0, 1),
(14, '2024-08-09', 6, 1, 0),
(15, '2024-08-09', 6, 1, 0),
(16, '2024-08-09', 6, 0, 1),
(17, '2024-08-10', 2, 1, 0),
(18, '2024-08-10', 1, 1, 0),
(19, '2024-08-10', 5, 1, 0),
(20, '2024-08-10', 6, 1, 0),
(21, '2024-08-10', 5, 1, 0),
(22, '2024-08-10', 5, 1, 0),
(23, '2024-08-11', 6, 1, 0),
(24, '2024-08-12', 6, 1, 0),
(25, '2024-08-12', 5, 1, 0),
(26, '2024-08-12', 6, 1, 0),
(27, '2024-08-12', 6, 1, 0),
(28, '2024-08-12', 6, 1, 0),
(29, '2024-08-12', 3, 1, 0),
(30, '2024-08-12', 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchases_details`
--

CREATE TABLE `purchases_details` (
  `id` int(30) NOT NULL,
  `purchases_id` int(30) NOT NULL,
  `product` int(30) NOT NULL,
  `quantity` int(40) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dlt` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases_details`
--

INSERT INTO `purchases_details` (`id`, `purchases_id`, `product`, `quantity`, `status`, `dlt`) VALUES
(1, 1, 4, 8, 0, 0),
(2, 10, 7, 2, 1, 0),
(3, 10, 7, 2, 1, 0),
(4, 10, 7, 1, 1, 0),
(5, 11, 7, 1, 1, 0),
(6, 12, 11, 1, 0, 0),
(7, 13, 11, 1, 0, 0),
(8, 13, 11, 2, 0, 0),
(9, 14, 11, 1, 1, 0),
(10, 15, 11, 10, 1, 0),
(11, 16, 12, 10, 0, 0),
(12, 17, 12, 10, 1, 0),
(13, 17, 2, 10, 1, 0),
(14, 18, 4, 10, 1, 0),
(15, 18, 7, 5, 1, 0),
(16, 19, 1, 8, 1, 0),
(17, 20, 12, 6, 1, 0),
(18, 20, 11, 10, 1, 0),
(19, 22, 7, 10, 1, 0),
(20, 22, 12, 10, 0, 1),
(21, 23, 12, 10, 1, 0),
(22, 23, 12, 10, 1, 0),
(23, 24, 12, 2, 1, 0),
(24, 25, 12, 3, 1, 0),
(25, 25, 11, 5, 1, 0),
(26, 25, 7, 8, 1, 0),
(27, 26, 5, 5, 1, 0),
(28, 26, 10, 5, 1, 0),
(29, 27, 4, 11, 1, 0),
(30, 29, 10, 10, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `grn` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dlt` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_return`
--

INSERT INTO `purchase_return` (`id`, `date`, `grn`, `status`, `dlt`) VALUES
(1, '2024-08-08 17:22:05', 1, 1, 0),
(2, '2024-08-08 18:21:45', 1, 1, 0),
(3, '2024-08-09 12:11:49', 7, 1, 0),
(4, '2024-08-10 13:33:15', 20, 1, 0),
(5, '2024-08-10 16:32:07', 20, 1, 0),
(6, '2024-08-10 16:43:14', 1, 0, 0),
(7, '2024-08-10 16:43:39', 3, 1, 0),
(8, '2024-08-10 16:44:41', 11, 1, 0),
(9, '2024-08-10 16:47:35', 21, 1, 0),
(10, '2024-08-11 08:21:41', 27, 1, 0),
(11, '2024-08-11 08:24:00', 30, 1, 0),
(12, '2024-08-11 09:48:21', 30, 1, 0),
(13, '2024-08-11 09:48:48', 30, 1, 0),
(14, '2024-08-11 12:42:26', 32, 1, 0),
(15, '2024-08-12 11:27:19', 34, 1, 0),
(16, '2024-08-12 11:29:47', 34, 1, 0),
(17, '2024-08-12 11:32:12', 35, 1, 0),
(18, '2024-08-12 21:54:42', 35, 1, 0),
(19, '2024-08-12 21:58:21', 35, 1, 0),
(20, '2024-08-13 10:49:28', 40, 1, 0),
(21, '2024-08-13 10:54:41', 40, 1, 0),
(22, '2024-08-13 10:56:26', 40, 1, 0),
(23, '2024-08-13 11:10:24', 40, 1, 0),
(24, '2024-08-13 11:41:23', 40, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_details`
--

CREATE TABLE `purchase_return_details` (
  `id` int(11) NOT NULL,
  `purchase_return_id` int(11) NOT NULL,
  `product` varchar(110) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dlt` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_return_details`
--

INSERT INTO `purchase_return_details` (`id`, `purchase_return_id`, `product`, `quantity`, `status`, `dlt`) VALUES
(1, 1, '4', 2, 1, 0),
(2, 2, '4', 1, 1, 0),
(3, 6, '4', 1, 0, 0),
(4, 11, '12', 2, 1, 0),
(5, 12, '12', 1, 1, 0),
(6, 13, '12', 2, 1, 0),
(7, 14, '12', 1, 1, 0),
(8, 15, '7', 1, 1, 0),
(9, 17, '5', 2, 1, 0),
(10, 17, '10', 1, 1, 0),
(11, 19, '5', 2, 1, 0),
(12, 20, '10', 1, 1, 0),
(13, 21, '10', 1, 1, 0),
(14, 22, '10', 1, 1, 0),
(15, 24, '10', 5, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `p_category`
--

CREATE TABLE `p_category` (
  `p_cat_id` int(20) NOT NULL,
  `parent_cat_name` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_category`
--

INSERT INTO `p_category` (`p_cat_id`, `parent_cat_name`, `status`) VALUES
(1, 'Processors', 1),
(2, 'Motherboards', 1),
(3, 'Graphics Cards', 1),
(4, 'Memory (RAM)', 1),
(5, 'Hard Disk', 1),
(6, 'Power Supply', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `invoice` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dlt` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_returns`
--

INSERT INTO `sale_returns` (`id`, `date`, `invoice`, `status`, `dlt`) VALUES
(1, '2024-08-09 12:17:12', 3, 0, 1),
(2, '2024-08-10 17:08:38', 4, 1, 0),
(3, '2024-08-11 11:37:18', 4, 1, 0),
(4, '2024-08-11 12:42:51', 4, 1, 0),
(5, '2024-08-11 12:47:23', 4, 1, 0),
(6, '2024-08-11 12:47:29', 3, 1, 0),
(7, '2024-08-11 12:47:54', 1, 1, 0),
(8, '2024-08-11 15:39:57', 5, 1, 0),
(9, '2024-08-12 11:35:19', 7, 1, 0),
(10, '2024-08-12 11:35:31', 5, 0, 1),
(11, '2024-08-12 11:38:18', 7, 0, 1),
(12, '2024-08-12 19:11:51', 7, 1, 0),
(13, '2024-08-12 21:47:46', 9, 1, 0),
(14, '2024-08-13 11:11:27', 10, 1, 0),
(15, '2024-08-13 11:11:39', 8, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns_details`
--

CREATE TABLE `sale_returns_details` (
  `id` int(11) NOT NULL,
  `sale_returns_id` int(11) NOT NULL,
  `product` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quantity` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dlt` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_returns_details`
--

INSERT INTO `sale_returns_details` (`id`, `sale_returns_id`, `product`, `quantity`, `status`, `dlt`) VALUES
(1, 4, '1', 2, 1, 0),
(2, 6, '5', 1, 1, 0),
(3, 7, '5', 1, 1, 0),
(4, 7, '1', 1, 1, 0),
(5, 7, '6', 1, 1, 0),
(6, 8, '5', 1, 1, 0),
(7, 10, '5', 1, 0, 0),
(8, 13, '4', 2, 1, 0),
(9, 15, '7', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(100) NOT NULL,
  `title` varchar(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(30) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `nic` bigint(50) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `title`, `firstname`, `lastname`, `gender`, `address`, `email`, `mobile`, `dob`, `nic`, `user_type`, `username`, `password`, `status`) VALUES
(0, 'Ms', 'admin', 'admin', 'female', 'Gampaha', 'admin@gmail.com', '0312246066', '2006-08-11', 123654789652, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1),
(1, 'Mrs', 'Upeksha', 'Wijerathne', 'female', '6, Thekkawaththa, Divulapitiya', 'Upeksha@gmail.com', '0711459016', '2005-07-26', 123456789623, 'admin', 'Upeksha', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(2, 'Mrs', 'Hasini', 'Wijerathne', 'female', 'No.10,Hapugahagama, Divulapitiya', 'hasini@gmail.com', '0711459875', '2004-11-03', 123456789623, 'user', 'Hasini', '202cb962ac59075b964b07152d234b70', 1),
(5, 'Mr', 'Naduni', 'Wijerathne', 'male', 'Colombo', 'naduni@gmail.com', '0711459011', '2006-08-02', 123456789512, 'user', 'naduni', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(6, 'Mr', 'Kamal', 'Fernando', 'male', 'Colombo', 'kamal@gmail.com', '0312245896', '2006-08-10', 123654789652, 'user', 'Kamal', '202cb962ac59075b964b07152d234b70', 1),
(7, 'Ms', 'Sanduni', 'Upeksha', 'female', 'Athurugiriya', 'sanduni@gmail.com', '0714569823', '2006-08-04', 123654789632, 'admin', 'upeksha', '202cb962ac59075b964b07152d234b70', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(20) NOT NULL,
  `pid` int(20) NOT NULL,
  `quantity` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `pid`, `quantity`) VALUES
(1, 1, 73),
(2, 2, 10),
(3, 3, 10),
(4, 4, 20),
(5, 5, 13),
(6, 6, 8),
(7, 7, 3),
(8, 8, 0),
(9, 9, 0),
(10, 10, 4),
(11, 11, 10),
(12, 12, 18);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) NOT NULL,
  `title` varchar(10) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` varchar(40) NOT NULL,
  `email` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `office_tel` varchar(20) NOT NULL,
  `nic` varchar(100) NOT NULL,
  `description` varchar(40) NOT NULL,
  `status` tinyint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `title`, `firstname`, `lastname`, `gender`, `address`, `email`, `mobile`, `office_tel`, `nic`, `description`, `status`) VALUES
(1, 'Mr', 'Sarath', 'Wijerathne', 'male', 'Gampaha', 'sarath@gmail.com', '1234569874', '1234569874', '1234569874', '-', 1),
(2, 'Mr', 'Lakmal', 'Jayasinghe', '', 'Gampaha', 'lakmal@gmail.com', '1234569874', '1234569874', '123456987423', '-', 1),
(3, 'Mr', 'Susil', 'Kumara', '', 'Gampaha', 'susil@gmail.com', '0776925456', '0312246066', '123456987423', '-', 1),
(4, 'Mr', 'Nalin', 'Bandara', '', 'Gampaha', 'nalin@gmail.com', '714563295', '714563280', '123654789623', '', 1),
(5, 'Mr', 'Aathiq', 'Ahamed', 'male', 'Colombo', 'aathiq@gmail.com', '712365984', '312245622', '123654789632', '-', 1),
(6, 'Mr', 'Saman', 'Peiris', '', 'Gampaha', 'saman@gmail.com', '0711256013', '0312245699', '123456789632', 'No Description 1', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grn`
--
ALTER TABLE `grn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grn_details`
--
ALTER TABLE `grn_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices_details`
--
ALTER TABLE `invoices_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases_details`
--
ALTER TABLE `purchases_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return_details`
--
ALTER TABLE `purchase_return_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p_category`
--
ALTER TABLE `p_category`
  ADD PRIMARY KEY (`p_cat_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_returns_details`
--
ALTER TABLE `sale_returns_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grn`
--
ALTER TABLE `grn`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `grn_details`
--
ALTER TABLE `grn_details`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invoices_details`
--
ALTER TABLE `invoices_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `purchases_details`
--
ALTER TABLE `purchases_details`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `purchase_return_details`
--
ALTER TABLE `purchase_return_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `p_category`
--
ALTER TABLE `p_category`
  MODIFY `p_cat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sale_returns_details`
--
ALTER TABLE `sale_returns_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
