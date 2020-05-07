-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2020 at 03:18 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pick_fix`
--

-- --------------------------------------------------------

--
-- Table structure for table `professionals`
--

CREATE TABLE `professionals` (
  `PID` int(11) NOT NULL,
  `FNAME` varchar(20) NOT NULL,
  `LNAME` varchar(20) NOT NULL,
  `EMAIL` varchar(25) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `AREA_CODE` int(3) NOT NULL,
  `PHONE_NUMBER` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UID` int(11) NOT NULL,
  `FNAME` varchar(20) NOT NULL,
  `LNAME` varchar(20) NOT NULL,
  `USERNAME` varchar(20) NOT NULL,
  `EMAIL` varchar(20) NOT NULL,
  `PASSWORD` varchar(20) NOT NULL,
  `AREA_CODE` int(3) NOT NULL,
  `PHONE_NUMBER` int(10) NOT NULL,
  `CITY` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UID`, `FNAME`, `LNAME`, `USERNAME`, `EMAIL`, `PASSWORD`, `AREA_CODE`, `PHONE_NUMBER`, `CITY`) VALUES
(1, 'Dzenita', 'Djulovic', 'Jenn', 'dzenita@gmail.com', '123456789', 387, 60778899, 'Zivinice');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `professionals`
--
ALTER TABLE `professionals`
  ADD PRIMARY KEY (`PID`),
  ADD UNIQUE KEY `PID` (`PID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `UID` (`UID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
