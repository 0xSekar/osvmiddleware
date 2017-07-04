CREATE TABLE `tickers_split_parser` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ticker` varchar(15) DEFAULT NULL,
  `insdate` datetime DEFAULT NULL,
  `split_date` date DEFAULT NULL,
  `old_eps` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
  `updated_date` datetime DEFAULT NULL,
  `tested_for_today` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tickers_proedgard_updates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `ticker` varchar(15) CHARACTER SET latin1 DEFAULT NULL COMMENT 'ticker extracted from email contents',
  `insdate` datetime DEFAULT NULL,
  `downloaded` varchar(1) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Bool to mark ticker as downloaded',
  `filed_date` date DEFAULT NULL COMMENT 'Filed date, taken from email subject alert',
  `updated_date` datetime DEFAULT NULL COMMENT 'To compare when the report data was actually updated by EOL',
  `tested_for_today` date DEFAULT NULL COMMENT 'To check if today this record has been tested or not, if yes, then system should ignore',
  `otc` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='To store EOL Edgar notifications of company filings';


