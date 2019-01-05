-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2018 at 04:27 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.0.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_berl`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payslip`
--

CREATE TABLE `tbl_payslip` (
  `payslip_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Untitled.txt',
  `mime` varchar(50) NOT NULL DEFAULT 'text/plain',
  `size` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `data` mediumblob NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reseller`
--

CREATE TABLE `tbl_reseller` (
  `reseller_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL DEFAULT '',
  `postcode` varchar(5) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `phone_wa` varchar(20) NOT NULL DEFAULT '',
  `courier` varchar(50) NOT NULL DEFAULT '',
  `payslip_id` int(11) NOT NULL DEFAULT '0',
  `register_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `surename` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `password`, `surename`, `email`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Rend Backlaysh', 'hai.stuw@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_payslip`
--
ALTER TABLE `tbl_payslip`
  ADD PRIMARY KEY (`payslip_id`);

--
-- Indexes for table `tbl_reseller`
--
ALTER TABLE `tbl_reseller`
  ADD PRIMARY KEY (`reseller_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_payslip`
--
ALTER TABLE `tbl_payslip`
  MODIFY `payslip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_reseller`
--
ALTER TABLE `tbl_reseller`
  MODIFY `reseller_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
