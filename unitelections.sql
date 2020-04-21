-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: mysql.lodge104.net
-- Generation Time: Apr 19, 2020 at 06:04 PM
-- Server version: 5.7.28-log
-- PHP Version: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elections`
--
CREATE DATABASE IF NOT EXISTS `elections` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `elections`;

-- --------------------------------------------------------

--
-- Table structure for table `eligibleScouts`
--

CREATE TABLE `eligibleScouts` (
  `id` int(11) NOT NULL,
  `unitId` int(11) NOT NULL,
  `lastName` text,
  `firstName` text,
  `rank` text,
  `isElected` tinyint(1) NOT NULL DEFAULT '0',
  `dob` date DEFAULT NULL,
  `address_line1` text,
  `address_line2` text,
  `city` text,
  `state` text,
  `zip` text,
  `phone` text,
  `email` text,
  `bsa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `unitId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `unitElections`
--

CREATE TABLE `unitElections` (
  `id` int(11) NOT NULL,
  `unitNumber` int(11) DEFAULT NULL,
  `unitCommunity` text,
  `chapter` text,
  `exported` text,
  `open` text,
  `sm_name` text,
  `sm_address_line1` text,
  `sm_address_line2` text,
  `sm_city` text,
  `sm_state` text,
  `sm_zip` text,
  `sm_email` text,
  `sm_phone` text,
  `numRegisteredYouth` int(11) DEFAULT NULL,
  `dateOfElection` date DEFAULT NULL,
  `accessKey` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `unitElections`
--
DELIMITER $$
CREATE TRIGGER `unitAccessKey` BEFORE INSERT ON `unitElections` FOR EACH ROW BEGIN
  IF new.accessKey IS NULL THEN
    SET new.accessKey = uuid();
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `unitId` int(11) NOT NULL,
  `submissionId` int(11) NOT NULL,
  `scoutId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eligibleScouts`
--
ALTER TABLE `eligibleScouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unitElections`
--
ALTER TABLE `unitElections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eligibleScouts`
--
ALTER TABLE `eligibleScouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unitElections`
--
ALTER TABLE `unitElections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
