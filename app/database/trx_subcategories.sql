-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `trx_subcategories`;
CREATE TABLE `trx_subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `trx_subcategories` (`id`, `category_id`, `description`) VALUES
(1,	1, 'Equipment'),
(2,	2, 'Loans'),
(3,	3, 'Sales'),
(4,	4, 'Cost of sales'),
(5,	4, 'Operations'),
(6,	4, 'Salaries');

-- 2018-08-16 00:54:30