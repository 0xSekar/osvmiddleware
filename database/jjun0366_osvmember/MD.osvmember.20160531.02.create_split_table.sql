CREATE TABLE IF NOT EXISTS `gf_split_parser` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ticker` varchar(15) DEFAULT NULL,
  `insdate` datetime DEFAULT NULL,
  `split_date` date DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `tested_for_today` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
