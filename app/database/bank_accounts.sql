-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `bank_accounts`;
CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `short` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `bank_accounts` (`id`, `description`, `short`) VALUES
(1,	'Webchamp', 'wc'),
(2,	'Webchamp IT', 'wcit'),
(3,	'C. Moller Chk', 'chk'),
(4,	'C. Moller CCrd', 'ccrd');

-- 2018-08-16 00:53:55