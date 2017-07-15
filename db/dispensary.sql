-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2017 at 06:24 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dispensary`
--

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `issue_id` int(11) NOT NULL,
  `reciever` int(1) NOT NULL,
  `patient_id` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `u_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`issue_id`, `reciever`, `patient_id`, `date`, `u_id`) VALUES
(1, 4, '48', '2017-07-03', 3),
(2, 4, '72', '2017-07-03', 3),
(3, 4, '45', '2017-07-03', 3),
(4, 4, '82', '2017-07-03', 3),
(5, 4, '45', '2017-07-05', 3),
(6, 4, '41', '2017-07-05', 3),
(7, 4, '12', '2017-07-05', 3),
(8, 4, '41', '2017-07-05', 3),
(9, 4, '16', '2017-07-05', 3),
(10, 4, '15', '2017-07-05', 3),
(11, 4, '14', '2017-07-05', 3),
(12, 4, '56', '2017-07-05', 3),
(13, 4, '216', '2017-07-05', 3),
(14, 4, '210', '2017-07-05', 3),
(15, 4, '59', '2017-07-06', 3),
(16, 4, '78', '2017-07-08', 5),
(17, 4, '45', '2017-07-10', 3),
(18, 4, '48', '2017-07-10', 3),
(19, 4, '102', '2017-07-15', 3);

-- --------------------------------------------------------

--
-- Table structure for table `issue_details`
--

CREATE TABLE `issue_details` (
  `id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `serial_number` varchar(25) NOT NULL,
  `batch_number` varchar(25) NOT NULL,
  `amount` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `issue_details`
--

INSERT INTO `issue_details` (`id`, `issue_id`, `serial_number`, `batch_number`, `amount`) VALUES
(1, 4, '01102802', '639K601', 15),
(2, 4, 'pen12e5f5', 'jnhb1223njh', 1200),
(3, 5, '01102802', '639K601', 10),
(4, 5, 'pen12e5f5', 'jnhb1223njh', 101),
(5, 8, '01102802', '639K601', 1),
(6, 8, 'pen12e5f5', 'jnhb1223njh', 2),
(7, 9, '01102802', '639K601', 12),
(8, 9, 'pen12e5f5', 'jnhb1223njh', 1),
(9, 10, '01102802', '639K601', 10),
(10, 11, 's12nujnb4', 'juih12hv3', 14),
(11, 12, '01102802', '639K601', 2),
(12, 12, 'pen12e5f5', 'jnhb1223njh', 120),
(13, 13, 's12nujnb4', 'juih12hv3', 46),
(14, 13, '01102802', '639K601', 1),
(15, 14, 'pen12e5f5', 'jnhb1223njh', 78),
(16, 14, '01102802', '639K601', 10),
(17, 15, '01102802', '639K601', 200),
(18, 16, '01102802', '639K601', 100),
(19, 16, '59000403', 'm2hjb43kh', 250),
(20, 17, '59000403', 'm2hjb43kh', 750),
(21, 17, 'pen12e5f5', 'jnhb1223njh', 200),
(22, 18, '59000403', 'm2hjb43kh', 100),
(23, 19, 's12nujnb4', 'juih12hv3', 100);

-- --------------------------------------------------------

--
-- Table structure for table `seen_notifications`
--

CREATE TABLE `seen_notifications` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `unseen_notifications`
--

CREATE TABLE `unseen_notifications` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `unseen_notifications`
--

INSERT INTO `unseen_notifications` (`id`, `notification_id`, `status`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`issue_id`);

--
-- Indexes for table `issue_details`
--
ALTER TABLE `issue_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_id` (`issue_id`);

--
-- Indexes for table `seen_notifications`
--
ALTER TABLE `seen_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unseen_notifications`
--
ALTER TABLE `unseen_notifications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `issue_details`
--
ALTER TABLE `issue_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `seen_notifications`
--
ALTER TABLE `seen_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unseen_notifications`
--
ALTER TABLE `unseen_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `issue_details`
--
ALTER TABLE `issue_details`
  ADD CONSTRAINT `issue_details_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`issue_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
