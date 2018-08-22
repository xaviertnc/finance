DROP TABLE IF EXISTS `trx_statuses`;
CREATE TABLE `trx_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `trx_statuses` (`description`) VALUES
('TRF Assign'),
('INxxxx Assign'),
('Dxxxx Assign'),
('Regex Assign'),
('Manual Entity'),
('Manually Assigned'),
('Manually Assigned Split');