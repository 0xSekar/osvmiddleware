CREATE TABLE `chart_export` (
  `id` char(32) NOT NULL,
  `ticker` varchar(20) NOT NULL,
  `chart_options` longtext NOT NULL,
  `exported_date` varchar(45) NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
