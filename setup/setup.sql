-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 10, 2017 at 05:36 PM
-- Server version: 5.5.57-0ubuntu0.14.04.1
-- PHP Version: 7.0.19-1+deb.sury.org~trusty+2

SET GLOBAL SQL_MODE = "TRADITIONAL";
SET GLOBAL time_zone = "+02:00";

--
-- Database: `tagntreat`
--
CREATE DATABASE IF NOT EXISTS `tagntreat` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `tagntreat`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `ID` int(11) NOT NULL,
  `Username` varchar(32) COLLATE utf8_bin NOT NULL,
  `Name` varchar(64) COLLATE utf8_bin NOT NULL,
  `Email` varchar(64) COLLATE utf8_bin NOT NULL,
  `Classroom` varchar(8) COLLATE utf8_bin NOT NULL,
  `Password` char(128) COLLATE utf8_bin NOT NULL,
  `Salt` char(128) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
