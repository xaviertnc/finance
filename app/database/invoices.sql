DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_ref` varchar(65) DEFAULT NULL,
  `invoice_no` varchar(16) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `salesperson_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `trashed_at` datetime DEFAULT NULL,
  `trashed_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `invoices` (type_id,client_id,client_ref,invoice_no,`date`,
  `title`,amount,salesperson_id,job_id,sent_at,paid_at,paid_amount,status_id)
(SELECT
invtypeid as type_id,
clientid as client_id,
clientref as client_ref,
id as invoice_no,
invdate as `date`,
`title`,
amount,
salespersonid as salesperson_id,
jobid as job_id,
datesent as sent_at,
paymentdate as paid_at,
payamount as paid_amount,
invstatusid as status_id
FROM `invoices_wc`
ORDER BY invoice_no);

INSERT INTO `invoices` (type_id,client_id,client_ref,invoice_no,`date`,
  `title`,amount,salesperson_id,job_id,sent_at,paid_at,paid_amount,status_id)
(SELECT
invtypeid as type_id,
clientid as client_id,
clientref as client_ref,
id as invoice_no,
invdate as `date`,
`title`,
amount,
salespersonid as salesperson_id,
jobid as job_id,
datesent as sent_at,
paymentdate as paid_at,
payamount as paid_amount,
invstatusid as status_id
FROM `invoices_wcit`
ORDER BY invoice_no);