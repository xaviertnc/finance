-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `prod_subcategories`;
CREATE TABLE `prod_subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `prod_subcategories` (`id`, `category_id`, `name`) VALUES
(1,	1,	'ADSL Business Uncapped'),
(2,	1,	'ADSL Premium Uncapped'),
(3,	1,	'ADSL Home Uncapped'),
(4,	1,	'ADSL Capped'),
(5,	2,	'Linux Hosting'),
(6,	2,	'Windows Hosting');

-- 2018-08-18 05:28:40