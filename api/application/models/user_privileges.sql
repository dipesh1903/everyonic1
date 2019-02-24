-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 23, 2018 at 01:38 PM
-- Server version: 5.0.89-community-nt
-- PHP Version: 5.5.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `everyionic_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_privileges`
--

CREATE TABLE IF NOT EXISTS `user_privileges` (
  `auto_id` int(11) NOT NULL auto_increment,
  `usertype` varchar(30) NOT NULL,
  `module` varchar(100) NOT NULL,
  `add` tinyint(1) NOT NULL default '0',
  `view` tinyint(1) NOT NULL default '0',
  `update` tinyint(1) NOT NULL default '0',
  `delete` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`auto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=161 ;

--
-- Dumping data for table `user_privileges`
--

INSERT INTO `user_privileges` (`auto_id`, `usertype`, `module`, `add`, `view`, `update`, `delete`) VALUES
(113, 'admin', 'admin_back', 1, 1, 1, 1),
(114, 'admin', 'create_password_back', 1, 1, 1, 1),
(115, 'admin', 'distributor_management_back', 1, 1, 1, 1),
(116, 'admin', 'master_management_back', 1, 1, 1, 1),
(117, 'admin', 'retailer_management_back', 1, 1, 1, 1),
(118, 'admin', 'package_assigner_back', 1, 1, 1, 1),
(119, 'admin', 'package_back', 1, 1, 1, 1),
(120, 'admin', 'packagecommission_back', 1, 1, 1, 1),
(121, 'admin', 'provider_back', 1, 1, 1, 1),
(122, 'admin', 'service_back', 1, 1, 1, 1),
(123, 'admin', 'wallet_lock', 1, 1, 1, 1),
(124, 'admin', 'wallet_back', 1, 1, 1, 1),
(125, 'ms', 'admin_back', 0, 0, 0, 0),
(126, 'ms', 'create_password_back', 1, 1, 1, 1),
(127, 'ms', 'distributor_management_back', 1, 1, 1, 1),
(128, 'ms', 'master_management_back', 1, 1, 1, 1),
(129, 'ms', 'retailer_management_back', 1, 1, 1, 1),
(130, 'ms', 'package_assigner_back', 1, 1, 1, 1),
(131, 'ms', 'package_back', 1, 1, 1, 1),
(132, 'ms', 'packagecommission_back', 1, 1, 1, 1),
(133, 'ms', 'provider_back', 1, 1, 1, 1),
(134, 'ms', 'service_back', 0, 0, 0, 0),
(135, 'ms', 'wallet_lock', 0, 0, 0, 0),
(136, 'ms', 'wallet_back', 0, 0, 0, 0),
(137, 'ds', 'admin_back', 0, 0, 0, 0),
(138, 'ds', 'create_password_back', 0, 0, 0, 0),
(139, 'ds', 'distributor_management_back', 0, 0, 0, 0),
(140, 'ds', 'master_management_back', 0, 0, 0, 0),
(141, 'ds', 'retailer_management_back', 0, 0, 0, 0),
(142, 'ds', 'package_assigner_back', 0, 0, 0, 0),
(143, 'ds', 'package_back', 0, 0, 0, 0),
(144, 'ds', 'packagecommission_back', 0, 0, 0, 0),
(145, 'ds', 'provider_back', 0, 0, 0, 0),
(146, 'ds', 'service_back', 0, 0, 0, 0),
(147, 'ds', 'wallet_lock', 0, 0, 0, 0),
(148, 'ds', 'wallet_back', 0, 0, 0, 0),
(149, 'rt', 'admin_back', 0, 0, 0, 0),
(150, 'rt', 'create_password_back', 0, 0, 0, 0),
(151, 'rt', 'distributor_management_back', 0, 0, 0, 0),
(152, 'rt', 'master_management_back', 0, 0, 0, 0),
(153, 'rt', 'retailer_management_back', 0, 0, 0, 0),
(154, 'rt', 'package_assigner_back', 0, 0, 0, 0),
(155, 'rt', 'package_back', 0, 0, 0, 0),
(156, 'rt', 'packagecommission_back', 0, 0, 0, 0),
(157, 'rt', 'provider_back', 0, 0, 0, 0),
(158, 'rt', 'service_back', 0, 0, 0, 0),
(159, 'rt', 'wallet_lock', 0, 0, 0, 0),
(160, 'rt', 'wallet_back', 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
