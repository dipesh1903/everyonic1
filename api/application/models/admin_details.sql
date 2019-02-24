-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 21, 2018 at 09:53 PM
-- Server version: 5.0.89-community-nt
-- PHP Version: 5.5.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `contact`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_details`
--

CREATE TABLE IF NOT EXISTS `admin_details` (
  `admin_id` int(11) NOT NULL auto_increment,
  `com_id` varchar(150) NOT NULL,
  `company_name` varchar(60) NOT NULL,
  `name` varchar(50) NOT NULL,
  `admin_email` varchar(60) NOT NULL,
  `image` text NOT NULL,
  `url_type` tinyint(2) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `bank_acc` varchar(50) NOT NULL,
  `ifsc_code` varchar(50) NOT NULL,
  `gst_no` varchar(50) NOT NULL,
  `google_map` text NOT NULL,
  `date` varchar(50) NOT NULL,
  `status` int(11) NOT NULL default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `admin_details`
--

INSERT INTO `admin_details` (`admin_id`, `com_id`, `company_name`, `name`, `admin_email`, `image`, `url_type`, `phone`, `address`, `bank_acc`, `ifsc_code`, `gst_no`, `google_map`, `date`, `status`, `timestamp`) VALUES
(1, 'cm101', 'sqsqsq', 'Kumarjeet', 'Kumarjeetdas58@rocketmail.com', '', 0, '8759866752', 'inccis', '2147483647', 'UTBI0DMCC46', '29ABCDEF1234F', 'xaxa', '2018-12-19', 1, '2018-12-17 17:07:17'),
(2, 'cm101', 'sqsqsq', 'Kumarjeet', 'Kumarjeetdas58@rocketmail.com', '', 0, '8759866752', 'inccis', '2147483647', 'UTBI0DMCC46', '29ABCDEF1234F', 'xaxa', '2018-12-19', 1, '2018-12-17 17:07:57'),
(3, 'cm101', 'sqsqsq', 'Kumarjeet', 'Kumarjeetdas58@rocketmail.com', '', 0, '8759866752', 'inccis', '2147483647', 'UTBI0DMCC46', '29ABCDEF1234F', 'xaxax', '2018-12-25', 1, '2018-12-17 17:08:57'),
(4, 'COM615221080540', 'Asdfasdfasdf', 'Asdf', 'asdf', '', 0, 'asdf', 'Baghajatin colony,45/8opp, Pradhan nagar', 'asdf', 'asdf', 'asdf', 'asdfsdaf', '2018-12-21', 1, '2018-12-19 14:35:40'),
(5, 'COM597398080658', 'Kumarjeet Software Pvt Ltd', 'Kumarjeet', 'Kumarjeetdas58@rocketmail.com', '', 0, '1236547890', 'Baghajatin colony,45/8opp, Pradhan nagar', '012365478921', '123654', '0236514789', 'iframe', '2018-12-21', 1, '2018-12-19 14:36:58');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
