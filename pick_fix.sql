-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2020 at 10:38 PM
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

--
-- Dumping data for table `professionals`
--

INSERT INTO `professionals` (`PID`, `FNAME`, `LNAME`, `EMAIL`, `PASSWORD`, `AREA_CODE`, `PHONE_NUMBER`) VALUES
(1, 'Auto', 'Increment', 'works@fine.com', 'hello', 123, 123),
(3, 'Arr', 'arr', 'armin.salihovic@live.com', '123', 1321, 123),
(4, 'asd', '123', 'aa@aa.com', '32', 123, 123);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UID` int(11) NOT NULL,
  `FNAME` varchar(20) NOT NULL,
  `LNAME` varchar(20) NOT NULL,
  `EMAIL` varchar(20) NOT NULL,
  `PASSWORD` varchar(20) NOT NULL,
  `AREA_CODE` int(3) DEFAULT NULL,
  `PHONE_NUMBER` int(10) DEFAULT NULL,
  `CITY` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UID`, `FNAME`, `LNAME`, `EMAIL`, `PASSWORD`, `AREA_CODE`, `PHONE_NUMBER`, `CITY`) VALUES
(1, 'Dzenita', 'Djulovic', 'dzenita@gmail.com', '123456789', 387, 60778899, 'Zivinice'),
(2, 'Armin', 'Salihovic', 'armin.salihovic@live', '123', 123, 123, 'tuzla'),
(3, 'Armin', 'Salihovic', 'armin.salihovic@live', '123', 123, 123, 'tuzla'),
(4, 'Armin', 'Salihovic', 'armin.salihovic@live', '123', 123, 123, 'tuzla'),
(5, 'Aa', 'Aa', 'lexie@live.com', '123', 123, 123, 'tuzla');

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
  ADD UNIQUE KEY `UID` (`UID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `professionals`
--
ALTER TABLE `professionals`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;