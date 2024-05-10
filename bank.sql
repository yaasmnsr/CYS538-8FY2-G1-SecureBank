-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2024 at 04:44 PM
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
-- Database: `bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_type` enum('Savings','Checking','Business','Individual Retirement') NOT NULL,
  `date_opened` date NOT NULL DEFAULT current_timestamp(),
  `account_status` enum('Active','Closed','Suspended','Pending Approval') NOT NULL DEFAULT 'Pending Approval',
  `balance` decimal(10,0) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_number`, `account_type`, `date_opened`, `account_status`, `balance`, `user_id`) VALUES
(1, 'S1WES94BO8SWXA2', 'Savings', '2024-05-08', 'Pending Approval', 0, 1000),
(2, 'MJV3TTF8WILMFW5', 'Checking', '2024-05-08', 'Active', 800, 1000),
(3, '0P0B957HN1FM91V', 'Savings', '2024-05-08', 'Active', 4000, 1001),
(4, 'A2JOJV5KCX2S5JW', 'Checking', '2024-05-08', 'Active', 2500, 1001),
(5, 'HULS31TA263CIMM', 'Business', '2024-05-08', 'Active', 5700, 1002),
(6, '7WQKNSZP7FNODBO', 'Checking', '2024-05-08', 'Active', 3000, 1002),
(7, 'NWJTV4JCAXIWTXW', 'Savings', '2024-05-08', 'Active', 7800, 1003),
(8, 'LK2OIF70XCUEF3I', 'Individual Retirement', '2024-05-08', 'Active', 4000, 1003),
(9, 'QJMO7UKV20LLX6M', 'Savings', '2024-05-08', 'Active', 3400, 1004),
(10, 'OARUV5142PDVSUI', 'Business', '2024-05-08', 'Active', 9800, 1004);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `first_name`, `last_name`, `password`) VALUES
(100, 'Yasmeen', 'Almansour', '$2y$10$sDK3ZDXiUMzk4DtLfwEHyOPncxc2Qg8GkEJiqsqhv.aLL/a/PRU/W'),
(101, 'Noor', 'Albarrak', '$2y$10$r1tCYjYZOsotjGJOJfFat.277E43LmxIOb/StMjRJ2pxi0oQVKaxG'),
(102, 'Hessa', 'Almegren', '$2y$10$swjVN6BtWjkq07orH9itP.BzK.j4VU6cR0POJRZPSbcevq12fgYum'),
(103, 'Norah', 'Alqahtani', '$2y$10$FZbJqurc8owgxW.4KJGHPeJX4tjD6SOyo7tJ0NQVu53PmrSEarcvK'),
(104, 'Nouf', 'Alsagour', '$2y$10$fa0k19sYOhl/Iowx/WEkiOkWOs5we/rnhZDu1/R3RMiWA3JreWCJy');

-- --------------------------------------------------------

--
-- Table structure for table `billpayments`
--

CREATE TABLE `billpayments` (
  `bill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bill_type` enum('Utility Bill','Subscription Bill','Financial Bill','Education Bill','Healthcare Bill','Fine Bill') NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `issued_date` date NOT NULL DEFAULT current_timestamp(),
  `account_id` int(11) DEFAULT NULL,
  `payment_time` datetime DEFAULT NULL,
  `status` enum('Pending','Completed','In Process') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billpayments`
--

INSERT INTO `billpayments` (`bill_id`, `user_id`, `bill_type`, `amount`, `issued_date`, `account_id`, `payment_time`, `status`) VALUES
(1, 1000, 'Subscription Bill', 25, '2024-05-08', 1, '2024-05-08 22:15:05', 'Completed'),
(2, 1000, 'Utility Bill', 150, '2024-05-08', NULL, NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `fundstransfer`
--

CREATE TABLE `fundstransfer` (
  `transfer_id` int(11) NOT NULL,
  `sender_accountId` int(11) NOT NULL,
  `recipient_accountId` int(11) NOT NULL,
  `transfer_amount` decimal(10,0) NOT NULL,
  `transfer_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fundstransfer`
--

INSERT INTO `fundstransfer` (`transfer_id`, `sender_accountId`, `recipient_accountId`, `transfer_amount`, `transfer_time`) VALUES
(1, 1, 2, 200, '2024-05-08 22:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` int(10) NOT NULL,
  `city` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `city`, `district`, `street`) VALUES
(1000, 'Yasmeen', 'Almansour', 'yasmeen@gmail.com', '$2y$10$ugv6eQylE8V.PLutkNTdvOadeIx.jj3ar4oDTzYJDXmgEDVk8CCZG', 512345678, 'Dammam', 'District 1', 'Street 1'),
(1001, 'Noor', 'Albarrak', 'noor@gmail.com', '$2y$10$5kjQz2x11jTHFWl5oz40KOyV5ijZrpBoScyDw/Iv4RapFS.nTgpJu', 505050505, 'Dammam', 'District 2', 'Street 1'),
(1002, 'Hessa', 'Almegren', 'hessa@gmail.com', '$2y$10$SgBOed7y2sLhJHyo.0C9R.NFKmnFxVAyH4VmWHESzFpbz8E9WgBKK', 534594753, 'Khobar', 'District 1', 'Street 2'),
(1003, 'Norah', 'Alqahtani', 'norah@gmail.com', '$2y$10$eoQq2a7xjDpxviSdVQj/7uKrZcMIdzP8HdNNcxaaVDB2LFOlABHP6', 505050503, 'Khobar', 'District 1', 'Street 2'),
(1004, 'Nouf', 'Alsagour', 'nouf@gmail.com', '$2y$10$3skFZsjYcceRdaJBFv44y.FfRmaUQwPLw84.betab5ixRy4Lrne7K', 534594753, 'Khobar', 'District 3', 'Street 1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `billpayments`
--
ALTER TABLE `billpayments`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `fk_userid` (`user_id`),
  ADD KEY `fk_accountid` (`account_id`);

--
-- Indexes for table `fundstransfer`
--
ALTER TABLE `fundstransfer`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `fk_sender_account` (`sender_accountId`),
  ADD KEY `fk_receiver_account` (`recipient_accountId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `billpayments`
--
ALTER TABLE `billpayments`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fundstransfer`
--
ALTER TABLE `fundstransfer`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `billpayments`
--
ALTER TABLE `billpayments`
  ADD CONSTRAINT `fk_accountid` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `fk_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `fundstransfer`
--
ALTER TABLE `fundstransfer`
  ADD CONSTRAINT `fk_receiver_account` FOREIGN KEY (`recipient_accountId`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `fk_sender_account` FOREIGN KEY (`sender_accountId`) REFERENCES `accounts` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
