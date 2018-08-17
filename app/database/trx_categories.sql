-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `trx_categories`;
CREATE TABLE `trx_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `trx_categories` (`id`, `description`) VALUES
(1,	'Asset'),
(2,	'Equity'),
(3,	'Income'),
(4,	'Expense');

-- 2018-08-16 00:54:30