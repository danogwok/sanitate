-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 25, 2013 at 07:43 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sanitate`
--

-- --------------------------------------------------------

--
-- Table structure for table `case`
--

CREATE TABLE IF NOT EXISTS `case` (
  `id` int(11) NOT NULL auto_increment,
  `location_attribute` text NOT NULL,
  `location_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `category_id` int(255) NOT NULL,
  `status` enum('Resolved','Pending') NOT NULL default 'Pending',
  `title` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `dated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `location_id` (`location_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `case`
--

INSERT INTO `case` (`id`, `location_attribute`, `location_id`, `user_id`, `category_id`, `status`, `title`, `description`, `dated`) VALUES
(2, 'in the slum, next to the clinic', 1, 3, 1, 'Pending', 'over flowing rubbish container', 'there is an over flowing rubbist container right in the middle of the city, at city center', '2012-12-14 12:22:00'),
(3, 'adfasf', 2, 6, 2, 'Pending', 'asf', 'asdf', '2012-12-16 13:31:50');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(255) NOT NULL auto_increment,
  `description` text NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `description`, `name`) VALUES
(1, 'issues related to poor waste management', 'waste management'),
(2, 'issues related to poor hygiene.', 'hygiene'),
(3, 'Burst pipes, ', 'Water leaks');

-- --------------------------------------------------------

--
-- Table structure for table `incoming_sms`
--

CREATE TABLE IF NOT EXISTS `incoming_sms` (
  `id` int(255) NOT NULL auto_increment,
  `time_received` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL,
  `case_id` int(255) default NULL,
  `status` varchar(12) NOT NULL default 'Unread',
  PRIMARY KEY  (`id`),
  KEY `case_id` (`case_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `incoming_sms`
--

INSERT INTO `incoming_sms` (`id`, `time_received`, `number`, `message`, `case_id`, `status`) VALUES
(1, '2012-12-16 06:19:20', '256771325146', 'burst sewag pipe in katanga next to the catholic church', NULL, 'Unread'),
(2, '2012-12-16 04:16:00', '2147483647', 'the church is called st.cleo, next to it,burst sewage pipe', NULL, 'Unread'),
(3, '2012-12-17 06:00:53', '2147483647', 'burst water pipe in katanga', NULL, 'Unread'),
(4, '2012-12-19 06:00:34', '2147483647', 'rubbish desposed off in garden and it''s stinking, large heep of rubbish', NULL, 'Unread'),
(5, '2012-12-16 12:59:57', '256772076530', 'rubbish container over flowing at city square', 2, 'Unread');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(255) NOT NULL auto_increment,
  `district` text NOT NULL,
  `parish` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `district`, `parish`) VALUES
(1, 'kampala', 'kamokya'),
(2, 'city square', 'wandegeya'),
(3, 'kampala', 'katanga'),
(4, 'kampala', 'kivulu'),
(5, 'kampala', 'city square');

-- --------------------------------------------------------

--
-- Table structure for table `outgoing_sms`
--

CREATE TABLE IF NOT EXISTS `outgoing_sms` (
  `id` int(255) NOT NULL auto_increment,
  `time_received` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `number` varchar(14) NOT NULL,
  `message` varchar(300) NOT NULL,
  `case_id` int(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `case_id` (`case_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `outgoing_sms`
--

INSERT INTO `outgoing_sms` (`id`, `time_received`, `number`, `message`, `case_id`) VALUES
(1, '2012-12-16 06:24:26', '2147483647', 'where exactly n katanga, or whats the name of the church?', NULL),
(2, '2012-12-16 06:24:26', '2147483647', 'ok, you''re complaint has been sent to NWSA', NULL),
(3, '2012-12-16 06:24:26', '2147483647', 'problem of rubbish at city square has been forwarded', NULL),
(4, '2012-12-16 06:24:26', '2147483647', 'problem of sewage at kikoni has been forwaded to NATIONAL water and sewage authorities', NULL),
(5, '2012-12-16 06:24:26', '2147483647', 'tell us more about you''re location', NULL),
(11, '2012-12-16 14:43:44', '256772076530', 'hello', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(255) NOT NULL auto_increment,
  `username` text NOT NULL,
  `password` varchar(25) NOT NULL,
  `account_type` enum('Admin','User') NOT NULL default 'User',
  `date_registered` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `logout_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `login_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `email` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `account_type`, `date_registered`, `logout_time`, `login_time`, `email`, `name`, `phone`) VALUES
(2, 'nwsa', 'nwsa', 'User', '2012-12-15 16:12:58', '2012-12-15 21:00:00', '2012-12-14 21:00:00', 'nssf@gmail.com', 'NWSC', ''),
(3, 'kcca', 'kcca', 'User', '2012-12-15 16:13:14', '2012-12-14 21:00:00', '2012-12-14 21:00:00', 'kcca@gmail.com', 'KCCA', ''),
(6, 'admin', 'admin', 'Admin', '2012-12-15 16:14:10', '2012-12-14 21:00:00', '2012-12-14 21:00:00', 'admin@gmail.com', 'ADMIN', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `case`
--
ALTER TABLE `case`
  ADD CONSTRAINT `case_ibfk_11` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `case_ibfk_12` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `case_ibfk_13` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `case_ibfk_6` FOREIGN KEY (`id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `case_ibfk_7` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `incoming_sms`
--
ALTER TABLE `incoming_sms`
  ADD CONSTRAINT `incoming_sms_ibfk_2` FOREIGN KEY (`case_id`) REFERENCES `case` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `outgoing_sms`
--
ALTER TABLE `outgoing_sms`
  ADD CONSTRAINT `outgoing_sms_ibfk_2` FOREIGN KEY (`case_id`) REFERENCES `case` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `outgoing_sms_ibfk_3` FOREIGN KEY (`case_id`) REFERENCES `case` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
