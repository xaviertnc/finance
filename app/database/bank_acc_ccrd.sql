CREATE TABLE `bank_acc_ccrd` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `group_id` int NOT NULL,
  `type_id` int NOT NULL,
  `acc_id` int NOT NULL,
  `doc_id` int NOT NULL,
  `split_id` int NOT NULL
);