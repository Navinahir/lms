-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 25, 2022 at 04:40 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `system_size` varchar(45) DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `warranties` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `mounting` int(11) DEFAULT NULL,
  `electric_kit` int(1) DEFAULT 1,
  `power_phase` int(11) DEFAULT NULL,
  `export_limit` int(1) DEFAULT NULL,
  `house_type` int(11) DEFAULT NULL,
  `roof_type` int(11) DEFAULT NULL,
  `roof_angle` int(11) DEFAULT NULL,
  `freebies` varchar(100) DEFAULT NULL,
  `special_note` text DEFAULT NULL,
  `basic_system_cost` float DEFAULT NULL,
  `special_discount` float DEFAULT NULL,
  `other_price` float DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `deposit_required` float DEFAULT NULL,
  `balance_due` float DEFAULT NULL,
  `project_note` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `start_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
