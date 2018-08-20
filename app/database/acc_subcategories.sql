-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `acc_subcategories`;
CREATE TABLE `acc_subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `acc_subcategories` (`category_id`, `description`) VALUES
(1,	'Cash'),
(1,	'Short-term Investments'),
(1,	'Accounts Receivable'),
(1,	'Allowance for Doubtful Accounts'),
(1,	'Accrued Revenues/Receivables'),
(1,	'Prepaid Expenses'),
(1,	'Inventory'),
(1,	'Supplies'),
(1,	'Long-term Investments'),
(1,	'Land'),
(1,	'Buildings'),
(1,	'Equipment'),
(1,	'Vehicles'),
(1,	'Furniture and Fixtures'),
(1,	'Accumulated Depreciation'),
(2,	'Short-term Loans Payable'),
(2,	'Current Portion of Long-term Debt'),
(2,	'Accounts Payable'),
(2,	'Wages Payable'),
(2,	'Interest Payable'),
(2,	'Accrued Expenses'),
(2,	'Unearned or Deferred Revenues'),
(2,	'Installment Loans Payable'),
(2,	'Mortgage Loans Payable'),
(3,	'Personal Capital'),
(3,	'Personal Drawing'),
(3,	'Investor Equity'),
(3,	'Investor Dividents'),
(4,	'Sales'),
(4,	'Services'),
(5,	'Cost of Sales'),
(5,	'Supplies'),
(5,	'Salaries'),
(5,	'Wages'),
(5,	'Rent'),
(5,	'Utilities'),
(5,	'Telephone'),
(5,	'Advertising'),
(5,	'Depreciation'),
(6,	'Interest Revenues'),
(6,	'Gain on Sale of Assets'),
(6,	'Loss on Sale of Assets');


-- 2018-08-18 22:33:15