-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2016 at 11:15 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simplyhealth`
--

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(32) NOT NULL,
  `access` int(10) UNSIGNED DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE IF NOT EXISTS `staffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(25) NOT NULL,
  `state` varchar(20) NOT NULL,
  `zipcode` varchar(12) NOT NULL,
  `phone` varchar(30) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY userid (userid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(10) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(512) NOT NULL,
  `roleid` int(11) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS patient (
  id int(11) NOT NULL AUTO_INCREMENT,
  userid int(11) NOT NULL,
  firstname varchar(50) NOT NULL,
  lastname varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  address1 varchar(100) NOT NULL,
  address2 varchar(100) DEFAULT NULL,
  city varchar(25) NOT NULL,
  state varchar(20) NOT NULL,
  zipcode varchar(12) NOT NULL,
  phone varchar(30) NOT NULL,
  emergency_contact_name varchar(60) DEFAULT NULL,
  emergency_contact_phone varchar(30),
  PRIMARY KEY (id),
  UNIQUE KEY userid (userid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table 'patient_history'
--

CREATE TABLE IF NOT EXISTS patient_history (
  patient_id int(11) NOT NULL DEFAULT '0',
  eczema_self_ind varchar(1) DEFAULT NULL,
  highchol_self_ind varchar(1) DEFAULT NULL,
  highbp_self_ind varchar(1) DEFAULT NULL,
  mental_self_ind varchar(1) DEFAULT NULL,
  obesity_self_ind varchar(1) DEFAULT NULL,
  PRIMARY KEY (patient_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table 'appts'
--

CREATE TABLE `appts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `reason` varchar(120) DEFAULT NULL,
  `appt_date` datetime DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
  

--
-- Constraints for table patient_history
--
ALTER TABLE patient_history
  ADD CONSTRAINT patient_history_ibfk_1 FOREIGN KEY (patient_id) REFERENCES patient (id);
  
--
-- Constraints for table `staffs`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `roles` (`id`);

--
-- Constraints for table `staffs`
--
ALTER TABLE `staffs`
  ADD CONSTRAINT `staffs_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table appts
--
ALTER TABLE appts
  ADD CONSTRAINT appts_ibfk_1 FOREIGN KEY (patient_id) REFERENCES patient (id),
  ADD CONSTRAINT appts_ibfk_2 FOREIGN KEY (doctor_id) REFERENCES staffs (id);

--
-- Constraints for dumped tables
--
INSERT INTO `roles` (`id`, `rolename`) VALUES (NULL, 'Admin');
INSERT INTO `roles` (`id`, `rolename`) VALUES (NULL, 'Nurse');
INSERT INTO `roles` (`id`, `rolename`) VALUES (NULL, 'Doctor');
INSERT INTO `roles` (`id`, `rolename`) VALUES (NULL, 'Patient');
--
-- Indexes for dumped tables
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
