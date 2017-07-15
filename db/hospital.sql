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
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `drugs`
--

CREATE TABLE `drugs` (
  `serial_number` varchar(25) NOT NULL,
  `drug_name` varchar(100) NOT NULL,
  `type` varchar(25) NOT NULL,
  `description` varchar(250) NOT NULL,
  `inventory_alert_level` int(100) NOT NULL,
  `dispensary_alert_level` int(100) NOT NULL,
  `inventory_stock_status` int(1) NOT NULL DEFAULT '0',
  `dispensary_stock_status` int(1) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drugs`
--

INSERT INTO `drugs` (`serial_number`, `drug_name`, `type`, `description`, `inventory_alert_level`, `dispensary_alert_level`, `inventory_stock_status`, `dispensary_stock_status`, `deleted`) VALUES
('00105901', 'NITROFURANTOIN TABLETS B.P. 50mg', 'Tablet', '', 0, 50, 0, 0, 0),
('00402901', 'ASCORBIC ACID TABLETS B.P. 100 mg', 'Tablet', 'Vitamin C Tablets', 100, 50, 1, 1, 0),
('01102802', 'Miconazole Cream B.P. 2% w/w 15g', 'Tube', 'Antifungal', 1000, 50, 1, 1, 0),
('59000403', 'Electrode Jelly 250g-260g', 'Tube', '', 200, 300, 0, 0, 0),
('pen12e5f5', 'penadole', 'Tablet', 'paracitamol type of drug', 0, 0, 0, 0, 0),
('s12nujnb4', 'insulin1', 'Spray', '', 0, 0, 0, 0, 0),
('tjf', 'jggfyu', 'Tablet', '', 0, 0, 0, 0, 0),
('vintagino', 'vinef3f123', 'Cream', '100ml', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `drug_batches`
--

CREATE TABLE `drug_batches` (
  `batch_number` varchar(25) NOT NULL,
  `serial_number` varchar(25) NOT NULL,
  `arrival` date NOT NULL,
  `expire` date NOT NULL,
  `arrival_amount` int(8) NOT NULL,
  `inventory_balance` int(11) NOT NULL,
  `dispensory_balance` int(11) NOT NULL,
  `other_departments_balance` int(11) NOT NULL,
  `total_balance` int(11) NOT NULL,
  `expiry_status` int(10) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `drug_batches`
--

INSERT INTO `drug_batches` (`batch_number`, `serial_number`, `arrival`, `expire`, `arrival_amount`, `inventory_balance`, `dispensory_balance`, `other_departments_balance`, `total_balance`, `expiry_status`, `deleted`) VALUES
('1602093', '00402901', '2017-06-11', '2017-07-07', 1000, 0, 65, 215, 280, 3, 0),
('1635014', '00105901', '2016-08-07', '2017-07-07', 5000, 1000, 15, 100, 1115, 3, 0),
('639J601', '01102802', '2016-07-07', '2017-07-07', 500, 500, 15, 0, 515, 3, 0),
('639K601', '01102802', '2016-08-07', '2018-08-01', 500, 50, 400, 75, 525, 0, 0),
('gtruh12hbyv', '01102802', '2017-07-03', '2017-10-04', 5000, 5000, 100, 0, 5100, 1, 0),
('jnhb1223njh', 'pen12e5f5', '2017-07-20', '2017-09-19', 6702, 4002, 1900, 600, 6502, 1, 0),
('juih12hv3', 's12nujnb4', '2017-07-28', '2018-10-20', 4702, 4702, 100, 0, 4802, 0, 0),
('kjnibu62cvsdc', '01102802', '2017-07-13', '2017-12-21', 480, 0, 480, 0, 480, 0, 0),
('m2hjb43kh', '59000403', '2017-07-02', '2017-07-20', 6000, 800, 900, 200, 1900, 2, 0),
('mjnub12', '01102802', '2017-07-03', '2017-07-27', 4502, 1982, 2520, 0, 4502, 2, 0),
('njomlk213nol', '59000403', '2017-07-05', '2017-10-26', 5000, 1700, 2222, 1078, 5000, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loggers`
--

CREATE TABLE `loggers` (
  `id` int(11) NOT NULL,
  `login_date` date NOT NULL,
  `user` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loggers`
--

INSERT INTO `loggers` (`id`, `login_date`, `user`) VALUES
(1, '2017-07-15', 2),
(2, '2017-07-15', 3),
(3, '2017-07-15', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` int(1) NOT NULL,
  `message` text NOT NULL,
  `description` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `date`, `type`, `message`, `description`) VALUES
(1, '2017-07-15', 0, 'Drug Expiry Warning', 'drug ASCORBIC ACID TABLETS B.P. 100 mg (serial number 00402901) batch no. 1602093 will expire in 90 days'),
(2, '2017-07-15', 0, 'Drug Expiry Warning', 'drug NITROFURANTOIN TABLETS B.P. 50mg (serial number 00105901) batch no. 1635014 will expire in 90 days'),
(3, '2017-07-15', 0, 'Drug Expiry Warning', 'drug Miconazole Cream B.P. 2% w/w 15g (serial number 01102802) batch no. gtruh12hbyv will expire in 90 days'),
(4, '2017-07-15', 0, 'Drug Expiry Warning', 'drug penadole (serial number pen12e5f5) batch no. jnhb1223njh will expire in 90 days'),
(5, '2017-07-15', 0, 'Drug Expiry Warning', 'drug Electrode Jelly 250g-260g (serial number 59000403) batch no. m2hjb43kh will expire in 90 days'),
(6, '2017-07-15', 0, 'Drug Expiry Warning', 'drug Miconazole Cream B.P. 2% w/w 15g (serial number 01102802) batch no. mjnub12 will expire in 90 days'),
(7, '2017-07-15', 0, 'Drug Expiry Warning', 'drug ASCORBIC ACID TABLETS B.P. 100 mg (serial number 00402901) batch no. 1602093 will expire in 45 days'),
(8, '2017-07-15', 0, 'Drug Expiry Warning', 'drug NITROFURANTOIN TABLETS B.P. 50mg (serial number 00105901) batch no. 1635014 will expire in 45 days'),
(9, '2017-07-15', 0, 'Drug Expiry Warning', 'drug Miconazole Cream B.P. 2% w/w 15g (serial number 01102802) batch no. 639J601 will expire in 45 days'),
(10, '2017-07-15', 0, 'Drug Expiry Warning', 'drug Electrode Jelly 250g-260g (serial number 59000403) batch no. m2hjb43kh will expire in 45 days'),
(11, '2017-07-15', 0, 'Drug Expiry Warning', 'drug Miconazole Cream B.P. 2% w/w 15g (serial number 01102802) batch no. mjnub12 will expire in 45 days'),
(12, '2017-07-15', 0, 'Drug Expiry Warning', 'drug ASCORBIC ACID TABLETS B.P. 100 mg (serial number 00402901) batch no. 1602093 will expire in 0 days'),
(13, '2017-07-15', 0, 'Drug Expiry Warning', 'drug NITROFURANTOIN TABLETS B.P. 50mg (serial number 00105901) batch no. 1635014 will expire in 0 days'),
(14, '2017-07-15', 0, 'Drug Expiry Warning', 'drug Miconazole Cream B.P. 2% w/w 15g (serial number 01102802) batch no. 639J601 will expire in 0 days');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(2) NOT NULL,
  `page_url` varchar(100) NOT NULL,
  `page_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `page_url`, `page_name`) VALUES
(2, 'createUser.php', 'Create A New User'),
(3, 'viewUser.php', 'View Users'),
(4, 'addANewDrug.php', 'Add A New Drug'),
(5, 'drugArrival.php', 'Drug Arrival'),
(6, 'dispensaryDrugRequest.php', 'Request Drugs'),
(7, 'inventoryDrugRequest.php', 'Request Drugs'),
(8, 'issueDrug.php', 'Issue Drugs'),
(9, 'showDrugDetails.php', 'Show Drugs'),
(10, 'issueDrugToOtherDepartment.php', 'Issue Drugs To Other Departments'),
(11, 'pastIssues.php', 'Past Issues');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `sending_dept` int(1) NOT NULL,
  `receiving_dept` int(1) NOT NULL,
  `u_id` int(4) NOT NULL,
  `state` int(1) NOT NULL,
  `description` varchar(100) NOT NULL,
  `issue_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `date`, `sending_dept`, `receiving_dept`, `u_id`, `state`, `description`, `issue_id`) VALUES
(14, '2017-07-03', 2, 0, 4, 0, 'Inventory Drug Request', 0),
(15, '2017-07-03', 1, 0, 3, 5, 'Dispensary Drug Request', 14),
(16, '2017-07-03', 1, 0, 3, 4, 'Dispensary Drug Request', 13),
(17, '2017-07-03', 2, 0, 4, 0, 'Inventory Drug Request', 0),
(18, '2017-07-03', 1, 0, 3, 3, 'Dispensary Drug Request', 7),
(19, '2017-07-03', 2, 0, 4, 0, 'Inventory Drug Request', 0),
(20, '2017-07-13', 1, 0, 3, 1, 'Dispensary Drug Request', 0);

-- --------------------------------------------------------

--
-- Table structure for table `request_details`
--

CREATE TABLE `request_details` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `serial_number` varchar(25) NOT NULL,
  `batch_number` varchar(25) NOT NULL,
  `amount` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `request_details`
--

INSERT INTO `request_details` (`id`, `request_id`, `serial_number`, `batch_number`, `amount`) VALUES
(20, 14, '00105901', '', 500),
(21, 14, '00402901', '', 600),
(22, 15, '00105901', '', 1200),
(23, 15, '01102802', '', 3200),
(24, 16, '00105901', '', 5000),
(25, 16, '01102802', '', 3000),
(26, 17, '00402901', '', 3001),
(27, 17, '59000403', '', 2001),
(28, 18, '00402901', '', 1111),
(29, 18, '59000403', '', 2222),
(30, 18, '00105901', '', 3333),
(31, 19, '00105901', '', 5445),
(32, 19, '59000403', '', 5445),
(33, 20, '01102802', '', 500),
(34, 20, '00402901', '', 200);

-- --------------------------------------------------------

--
-- Table structure for table `role2page`
--

CREATE TABLE `role2page` (
  `role_id` int(3) NOT NULL,
  `page_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role2page`
--

INSERT INTO `role2page` (`role_id`, `page_id`) VALUES
(0, 2),
(0, 3),
(0, 9),
(1, 6),
(1, 8),
(1, 9),
(1, 11),
(2, 4),
(2, 5),
(2, 7),
(2, 9),
(2, 10),
(2, 11);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(4) NOT NULL,
  `role_name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(0, 'Admin'),
(1, 'Dispenser'),
(3, 'Doctor'),
(2, 'Inventory Manager');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(4) NOT NULL,
  `u_name` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role_id` int(4) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `first_name`, `last_name`, `email`, `password`, `role_id`, `deleted`) VALUES
(2, 'admin', 'admin', 'lastadminkkn', 'admin@gmail.com', 'superadmin', 0, 0),
(3, 'Dis1', 'FirstDis12', 'LastDis12', 'Dis1@gmail.co', 'Dis1@hospital', 1, 0),
(4, 'Inv1', 'firstInv1ygnj', 'lastInv1jnj', 'Inv1@gmail.c', 'Inv1@hospital', 2, 0),
(5, 'DIs2', 'firstDis', 'LastDis', 'Dis2@gmail.com', 'Dis2@hospital', 1, 1),
(6, 'Inv2', 'firstInv', 'lastInv', 'Inv2@gmail.com', 'Inv2@hospital', 2, 0),
(7, 'DIs3', 'FirstDis', 'LastDis', 'Dis3@gmail.com', 'Dis3@hospital', 1, 0),
(8, 'Doc1', 'firstDoc', 'lastDoc', 'Doc1@gmail.com', 'Doc1@hospital', 3, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drugs`
--
ALTER TABLE `drugs`
  ADD PRIMARY KEY (`serial_number`);

--
-- Indexes for table `drug_batches`
--
ALTER TABLE `drug_batches`
  ADD PRIMARY KEY (`batch_number`),
  ADD KEY `serial_number` (`serial_number`);

--
-- Indexes for table `loggers`
--
ALTER TABLE `loggers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `request_details`
--
ALTER TABLE `request_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `serial_number` (`serial_number`),
  ADD KEY `batch_number` (`batch_number`),
  ADD KEY `batch_number_2` (`batch_number`);

--
-- Indexes for table `role2page`
--
ALTER TABLE `role2page`
  ADD PRIMARY KEY (`role_id`,`page_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `u_name` (`u_name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `loggers`
--
ALTER TABLE `loggers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `request_details`
--
ALTER TABLE `request_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `drug_batches`
--
ALTER TABLE `drug_batches`
  ADD CONSTRAINT `drug_batches_ibfk_1` FOREIGN KEY (`serial_number`) REFERENCES `drugs` (`serial_number`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`u_id`);

--
-- Constraints for table `request_details`
--
ALTER TABLE `request_details`
  ADD CONSTRAINT `request_details_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`),
  ADD CONSTRAINT `request_details_ibfk_2` FOREIGN KEY (`serial_number`) REFERENCES `drugs` (`serial_number`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
