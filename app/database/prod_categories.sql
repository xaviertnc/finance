-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `prod_categories`;
CREATE TABLE `prod_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `prod_categories` (`id`, `name`) VALUES
(1,	'ADSL'),
(2,	'Hosting'),
(3,	'Domain Registration'),
(4,	'Custom Realm'),
(5,	'Consultation'),
(6,	'Remote Support'),
(7,	'Callout and Labour'),
(8,	'Software Development'),
(9,	'Website Development'),
(10,	'Computer Hardware'),
(11,	'Computer Software');

-- 2018-08-18 05:25:20