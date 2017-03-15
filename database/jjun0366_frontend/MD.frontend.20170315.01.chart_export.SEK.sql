CREATE TABLE `chart_export` (
  `id` char(32) NOT NULL,
  `chart_options` longtext NOT NULL,
  `exported_date` varchar(45) NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `checksum_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
