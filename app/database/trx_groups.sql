-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `trx_groups`;
CREATE TABLE `trx_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `trx_groups` (`id`, `description`) VALUES
(1,	'Transfers'),
(2,	'Creditors'),
(3,	'Services'),
(4,	'Suppliers'),
(5,	'Clients');

-- 2018-08-16 00:53:55