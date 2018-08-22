CREATE TABLE `bank_acc_wcit` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,4) NOT NULL,
  `balance` decimal(10,4) NOT NULL,
  `group_id` int NOT NULL,
  `type_id` int NOT NULL,
  `acc_id` int NOT NULL,
  `doc_id` int NOT NULL,
  `split_id` int NOT NULL
);


UPDATE `bank_acc_wcit` SET ledger_acc_id = 0, status_id = 0, entity_id = 0;