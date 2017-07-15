-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2017 at 06:25 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `issue_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `u_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`issue_id`, `department_id`, `date`, `u_id`) VALUES
(7, 1, '2017-07-06', 4),
(9, 0, '2017-07-10', 4),
(10, 0, '2017-07-10', 4),
(11, 4, '2017-07-10', 4),
(12, 5, '2017-07-10', 4),
(13, 1, '2017-07-10', 4),
(14, 1, '2017-07-10', 4);

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
(13, 7, '00402901', '1602093', 14),
(14, 7, '59000403', 'm2hjb43kh', 2000),
(15, 7, '59000403', 'njomlk213nol', 222),
(18, 9, '01102802', '639K601', 50),
(19, 9, '59000403', 'm2hjb43kh', 88),
(20, 10, '59000403', 'm2hjb43kh', 100),
(21, 11, 'pen12e5f5', 'jnhb1223njh', 600),
(22, 12, '59000403', 'njomlk213nol', 78),
(23, 13, '01102802', 'kjnibu62cvsdc', 480),
(24, 13, '01102802', 'mjnub12', 2520),
(25, 14, '01102802', 'gtruh12hbyv', 3000),
(26, 14, '01102802', 'mjnub12', 200);

-- --------------------------------------------------------

--
-- Table structure for table `seen_notifications`
--

CREATE TABLE `seen_notifications` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL
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
(1, 2, 1),
(2, 3, 1),
(3, 4, 1),
(4, 5, 1),
(5, 6, 1),
(6, 8, 1),
(7, 9, 1),
(8, 10, 1),
(9, 11, 1),
(10, 13, 1),
(11, 14, 1);

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
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `issue_details`
--
ALTER TABLE `issue_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `seen_notifications`
--
ALTER TABLE `seen_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unseen_notifications`
--
ALTER TABLE `unseen_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
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
