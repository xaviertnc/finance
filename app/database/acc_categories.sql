-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `acc_categories`;
CREATE TABLE `acc_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `acc_categories` (`id`, `description`) VALUES
(1,	'Asset'),
(2,	'Liability'),
(3,	'Equity'),
(4,	'Income'),
(5,	'Expense'),
(6,	'Other');

-- 2018-08-18 22:37:36