-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `accronym`;
CREATE TABLE `accronym` (
  `code` varchar(10) NOT NULL,
  `full_form` text NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `accronym` (`code`, `full_form`) VALUES
('col01',	'General Computing Lab'),
('CSE',	'Computer Science and Engineering');

DROP TABLE IF EXISTS `dues`;
CREATE TABLE `dues` (
  `dueID` bigint(20) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL,
  `description` text NOT NULL,
  `generated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('P','C','R','A') NOT NULL,
  `entry_number` varchar(11) NOT NULL,
  `employee_uID` varchar(20) NOT NULL,
  `lab_code` varchar(10) NOT NULL,
  PRIMARY KEY (`dueID`),
  KEY `entry_number` (`entry_number`),
  KEY `employee_uID` (`employee_uID`),
  KEY `lab_code` (`lab_code`),
  CONSTRAINT `dues_ibfk_1` FOREIGN KEY (`entry_number`) REFERENCES `student_table` (`entry_number`),
  CONSTRAINT `dues_ibfk_2` FOREIGN KEY (`employee_uID`) REFERENCES `user_table` (`uID`),
  CONSTRAINT `dues_ibfk_3` FOREIGN KEY (`lab_code`) REFERENCES `lab_info` (`lab_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `dues` (`dueID`, `amount`, `description`, `generated_time`, `modified_time`, `status`, `entry_number`, `employee_uID`, `lab_code`) VALUES
(1,	10,	'Lan Port brokege',	'2017-02-27 12:34:39',	'2017-02-27 09:58:19',	'A',	'2014CS10258',	'emp01',	'col01'),
(2,	11,	'Mouse removal',	'2017-02-27 12:37:06',	'2017-02-27 10:59:06',	'A',	'2014CS10258',	'emp01',	'col01'),
(3,	20,	'slept in the chair',	'2017-02-27 14:55:03',	'2017-02-27 14:55:03',	'P',	'2014CS10258',	'emp01',	'col01');

DROP TABLE IF EXISTS `duesra`;
CREATE TABLE `duesra` (
  `dueID` bigint(20) NOT NULL,
  `requested_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `requested_comment` text NOT NULL,
  `approved_comment` text NOT NULL,
  `approved_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `dueID` (`dueID`),
  CONSTRAINT `duesra_ibfk_1` FOREIGN KEY (`dueID`) REFERENCES `dues` (`dueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `duesra` (`dueID`, `requested_time`, `requested_comment`, `approved_comment`, `approved_time`) VALUES
(1,	'2017-02-27 12:36:32',	'new request',	'new comment',	'2017-02-27 12:36:32'),
(2,	'2017-02-27 12:37:06',	'new request',	'new comment',	'2017-02-27 12:37:06');

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `fileID` varchar(50) NOT NULL,
  `content_link` text NOT NULL,
  PRIMARY KEY (`fileID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `file_dues`;
CREATE TABLE `file_dues` (
  `fileID` varchar(30) NOT NULL,
  `dueID` bigint(20) NOT NULL,
  PRIMARY KEY (`fileID`),
  KEY `dueID` (`dueID`),
  CONSTRAINT `file_dues_ibfk_1` FOREIGN KEY (`dueID`) REFERENCES `dues` (`dueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `incharge`;
CREATE TABLE `incharge` (
  `lab_code` varchar(10) NOT NULL,
  `incharge_uID` varchar(20) NOT NULL,
  PRIMARY KEY (`lab_code`,`incharge_uID`),
  KEY `incharge_uID` (`incharge_uID`),
  CONSTRAINT `incharge_ibfk_1` FOREIGN KEY (`lab_code`) REFERENCES `lab_info` (`lab_code`),
  CONSTRAINT `incharge_ibfk_2` FOREIGN KEY (`incharge_uID`) REFERENCES `user_table` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `incharge` (`lab_code`, `incharge_uID`) VALUES
('col01',	'emp01');

DROP TABLE IF EXISTS `lab_info`;
CREATE TABLE `lab_info` (
  `lab_code` varchar(10) NOT NULL,
  `hod_uID` varchar(10) NOT NULL,
  `department_code` varchar(3) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`lab_code`),
  KEY `hod_uID` (`hod_uID`),
  KEY `department_code` (`department_code`),
  CONSTRAINT `lab_info_ibfk_1` FOREIGN KEY (`hod_uID`) REFERENCES `user_table` (`uID`),
  CONSTRAINT `lab_info_ibfk_2` FOREIGN KEY (`lab_code`) REFERENCES `accronym` (`code`),
  CONSTRAINT `lab_info_ibfk_3` FOREIGN KEY (`department_code`) REFERENCES `accronym` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lab_info` (`lab_code`, `hod_uID`, `department_code`, `address`) VALUES
('col01',	'hodCS',	'CSE',	'Room no 401\r\nBharti Building\r\n');

DROP TABLE IF EXISTS `student_table`;
CREATE TABLE `student_table` (
  `entry_number` varchar(11) NOT NULL,
  `programme` text NOT NULL,
  `department_code` varchar(3) NOT NULL,
  PRIMARY KEY (`entry_number`),
  CONSTRAINT `student_table_ibfk_1` FOREIGN KEY (`entry_number`) REFERENCES `user_table` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `student_table` (`entry_number`, `programme`, `department_code`) VALUES
('2014CS10258',	'B. Tech',	'CSE');

DROP TABLE IF EXISTS `user_table`;
CREATE TABLE `user_table` (
  `uID` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `uType` enum('A','H','I','S') NOT NULL,
  PRIMARY KEY (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user_table` (`uID`, `name`, `uType`) VALUES
('2014CS10202',	'Abhijeet Anand',	'S'),
('2014CS10258',	'Sourav Das',	'S'),
('arUGS',	'Alan V sinate',	'A'),
('emp01',	'Prasad Kumar',	'I'),
('hodCS',	'S arun kumar',	'H');

-- 2017-02-27 18:04:00
