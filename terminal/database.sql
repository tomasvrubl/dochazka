-- Adminer 4.8.1 MySQL 8.0.32-0ubuntu0.20.04.2 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `dochazka` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dochazka`;

DROP TABLE IF EXISTS `dochazka`;
CREATE TABLE `dochazka` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cas` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `osoba_id` int NOT NULL,
  `udalost_id` int NOT NULL,
  `sent` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `udalost_id` (`udalost_id`),
  KEY `osoba_id` (`osoba_id`),
  CONSTRAINT `dochazka_ibfk_2` FOREIGN KEY (`osoba_id`) REFERENCES `osoby` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `osoby`;
CREATE TABLE `osoby` (
  `id` int NOT NULL AUTO_INCREMENT,
  `personid` int NOT NULL,
  `prijmeni` varchar(60) NOT NULL,
  `jmeno` varchar(60) NOT NULL,
  `personalnum` varchar(20) NOT NULL,
  `code_lo` int NOT NULL,
  `code_hi` int NOT NULL,
  `old_chip` varchar(14) NOT NULL,
  `new_chip` varchar(14) NOT NULL,
  `md5` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `udalost`;
CREATE TABLE `udalost` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pwk_id` int NOT NULL,
  `io` int NOT NULL,
  `sensortype` int NOT NULL,
  `lbl` varchar(60) NOT NULL,
  `css` varchar(120) NOT NULL,
  `css_icon` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `udalost` (`id`, `pwk_id`, `io`, `sensortype`, `lbl`, `css`, `css_icon`) VALUES
(1,	143,	1,	1,	'Příchod',	'css-prichod',	'fas fa-sign-in-alt'),
(2,	145,	2,	2,	'Odchod',	'css-odchod',	'fas fa-sign-out-alt'),
(3,	147,	2,	2,	'Dovolená',	'css-dovol',	'fas fa-umbrella-beach'),
(6,	152,	2,	2,	'Náhradní volno',	'css-nahvol',	'fas fa-sign-out-alt'),
(7,	158,	2,	2,	'Lékař',	'css-lekar',	'fas fa-user-md');

DROP VIEW IF EXISTS `view_dochazka`;
CREATE TABLE `view_dochazka` (`id` int, `cas` timestamp, `osoba_id` int, `udalost_id` int, `sent` tinyint, `personid` int, `prijmeni` varchar(60), `jmeno` varchar(60), `personalnum` varchar(20), `code_hi` int, `code_lo` int, `old_chip` varchar(14), `new_chip` varchar(14), `pwk_id` int, `io` int, `sensortype` int, `lbl` varchar(60));


DROP TABLE IF EXISTS `view_dochazka`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_dochazka` AS select `d`.`id` AS `id`,`d`.`cas` AS `cas`,`d`.`osoba_id` AS `osoba_id`,`d`.`udalost_id` AS `udalost_id`,`d`.`sent` AS `sent`,`o`.`personid` AS `personid`,`o`.`prijmeni` AS `prijmeni`,`o`.`jmeno` AS `jmeno`,`o`.`personalnum` AS `personalnum`,`o`.`code_hi` AS `code_hi`,`o`.`code_lo` AS `code_lo`,`o`.`old_chip` AS `old_chip`,`o`.`new_chip` AS `new_chip`,`u`.`pwk_id` AS `pwk_id`,`u`.`io` AS `io`,`u`.`sensortype` AS `sensortype`,`u`.`lbl` AS `lbl` from ((`dochazka` `d` join `osoby` `o` on((`d`.`osoba_id` = `o`.`id`))) join `udalost` `u` on((`d`.`udalost_id` = `u`.`id`))) order by `d`.`cas`;

-- 2023-05-02 20:15:50
