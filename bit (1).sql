-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2024 at 04:29 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

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
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(50) NOT NULL,
  `p_cat_id` int(20) DEFAULT NULL,
  `category_name` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `p_cat_id`, `category_name`, `status`) VALUES
(1, 1, 'AMD Processors', 1),
(2, 1, 'Intel Processors', 1),
(3, 2, 'ASUS Motherboards', 1),
(4, 2, 'MSI Motherboards', 1),
(5, 3, 'NVIDIA Graphics Cards', 1),
(6, 3, 'AMD Graphics Cards', 1),
(7, 4, 'Corsair RAM', 1);

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
  `mobile` int(50) NOT NULL,
  `nic` int(200) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grn`
--

INSERT INTO `grn` (`id`, `purchases`, `date`, `status`, `dlt`) VALUES
(1, 1, '2023-12-26 23:18:57', 1, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grn_details`
--

INSERT INTO `grn_details` (`id`, `grn_id`, `product`, `quantity`, `buy`, `status`, `dlt`) VALUES
(1, 1, '4', 8, 45000, 1, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category`, `name`, `price`, `reorder`, `description`, `status`) VALUES
(1, 2, 'Intel Core i9-9900K', 50000, 1, '|Intel         | Core i9-9900K     | 8 cores, 16 threads, 3.6 GHz base clock, 5.0 GHz max turbo frequency, LGA 1151, 16MB Intel Smart Cache', 1),
(2, 0, 'AMD Ryzen 7 5800X', 45000, 2, '|AMD           | Ryzen 7 5800X   | 8 cores, 16 threads, 3.8 GHz base clock, 4.7 GHz max boost clock, AM4 socket, 32MB L3 cache, 7nm Zen 3 architecture', 1),
(3, 0, 'ASUS ROG Strix Z590-E Gaming', 70000, 2, '| ASUS          | Z590-E Gaming   | ATX form factor, Intel Z590 chipset, PCIe 4.0, 14+2 power stages, Wi-Fi 6, Bluetooth 5.1, AI Noise-Canceling Microphone', 1),
(4, 0, 'MSI MPG B550 Gaming Plus', 80000, 2, '| MSI           | MPG B550 Gaming Plus| ATX form factor, AMD B550 chipset, PCIe 4.0, Mystic Light RGB, DDR4 Boost, HDMI/DP, 10+2+1 DUET Rail Power System', 1),
(5, 0, 'Intel® Core™ i7', 65000, 2, 'Total Cores\r\n4\r\n\r\nTotal Threads\r\n8\r\n\r\nMax Turbo Frequency\r\n3.90 GHz\r\n\r\nProcessor Base Frequency\r\n1.30 GHz\r\n\r\nCache\r\n8 MB Intel® Smart Cache\r\n\r\nBus Speed\r\n4 GT/s\r\n\r\nTDP\r\n15 W\r\n\r\nConfigurable TDP-up Base Frequency\r\n1.50 GHz\r\n\r\nConfigurable TDP-up\r\n25 W\r\n\r\nConfigurable TDP-down Base Frequency\r\n1.00 GHz\r\n\r\nConfigurable TDP-down\r\n12 W', 1),
(6, 0, 'AMD Radeon RX 6800 XT', 25000, 2, '| AMD           | RX 6800 XT  | 16GB GDDR6, 72 Compute Units, 2015 MHz base clock, 2250 MHz boost clock, Ray Tracing, AMD Infinity Cache, PCIe 4.0', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `date`, `supplier`, `status`, `dlt`) VALUES
(1, '2023-12-26', 3, 1, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchases_details`
--

INSERT INTO `purchases_details` (`id`, `purchases_id`, `product`, `quantity`, `status`, `dlt`) VALUES
(1, 1, 4, 8, 1, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `p_category`
--

CREATE TABLE `p_category` (
  `p_cat_id` int(20) NOT NULL,
  `parent_cat_name` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `p_category`
--

INSERT INTO `p_category` (`p_cat_id`, `parent_cat_name`, `status`) VALUES
(1, 'Processors', 1),
(2, 'Motherboards', 1),
(3, 'Graphics Cards', 1),
(4, 'Memory (RAM)', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns_details`
--

CREATE TABLE `sale_returns_details` (
  `id` int(11) NOT NULL,
  `sale_returns_id` int(11) NOT NULL,
  `product` varchar(100) CHARACTER SET latin1 NOT NULL,
  `quantity` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dlt` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `mobile` int(10) NOT NULL,
  `dob` date NOT NULL,
  `nic` bigint(50) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `title`, `firstname`, `lastname`, `gender`, `address`, `email`, `mobile`, `dob`, `nic`, `user_type`, `username`, `password`, `status`) VALUES
(1, 'Mrs', 'Upeksha', 'Wijerathne', 'female', '6, Thekkawaththa, Divulapitiya', 'Upeksha@gmail.com', 711459016, '2005-07-26', 123456789623, 'admin', 'Upeksha', '81dc9bdb52d04dc20036dbd8313ed055', 1),
(2, 'Mrs', 'Hasini', 'Wijerathne', 'female', 'No.10,Hapugahagama, Divulapitiya', 'hasini@gmail.com', 1234567896, '2004-11-03', 123456789623, 'user', 'Hasini', '7d5f8a044cd3d13bc40b1ad842e6650b', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(20) NOT NULL,
  `pid` int(20) NOT NULL,
  `quantity` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `pid`, `quantity`) VALUES
(1, 1, 8),
(2, 2, 0),
(3, 3, 0),
(4, 4, 0),
(5, 5, 0),
(6, 6, 0);

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
  `mobile` int(20) NOT NULL,
  `office_tel` int(20) NOT NULL,
  `nic` varchar(100) NOT NULL,
  `description` varchar(40) NOT NULL,
  `status` tinyint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `title`, `firstname`, `lastname`, `gender`, `address`, `email`, `mobile`, `office_tel`, `nic`, `description`, `status`) VALUES
(1, 'Mr', 'Sarath', 'Wijerathne', 'male', 'Gampaha', 'sarath@gmail.com', 1234569874, 1234569874, '1234569874', '-', 1),
(2, 'Mr', 'Sarath', 'Wijerathne', 'male', 'Gampaha', 'sarath@gmail.com', 1234569874, 1234569874, '1234569874', '-', 1),
(3, 'Mr', 'Sarath', 'Wijerathne', 'male', 'Gampaha', 'sarath@gmail.com', 1234569874, 1234569874, '1234569874', '-', 1);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grn`
--
ALTER TABLE `grn`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `grn_details`
--
ALTER TABLE `grn_details`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices_details`
--
ALTER TABLE `invoices_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchases_details`
--
ALTER TABLE `purchases_details`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_details`
--
ALTER TABLE `purchase_return_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p_category`
--
ALTER TABLE `p_category`
  MODIFY `p_cat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_returns_details`
--
ALTER TABLE `sale_returns_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
