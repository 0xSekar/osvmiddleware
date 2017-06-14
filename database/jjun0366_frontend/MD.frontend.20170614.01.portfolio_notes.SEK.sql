CREATE TABLE `portfolio_notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pstock_id` bigint(20) NOT NULL,
  `note` varchar(500) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
