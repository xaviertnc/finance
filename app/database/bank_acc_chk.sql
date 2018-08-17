CREATE TABLE `bank_acc_chk` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,4) NOT NULL,
  `balance` decimal(10,4) NOT NULL,
  `acc_group_id` int NOT NULL,
  `trx_category_id` int NOT NULL,
  `trx_subcategory_id` int NOT NULL,
  `trx_type_id` int NOT NULL,
  `acc_id` int NOT NULL,
  `doc_id` int NOT NULL,
  `split_id` int NOT NULL
);