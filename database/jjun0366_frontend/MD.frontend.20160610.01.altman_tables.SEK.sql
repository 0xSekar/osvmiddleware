CREATE TABLE IF NOT EXISTS `mrq_alt_checks` (
  `ticker_id` bigint(20) NOT NULL,
  `WorkingCapital` decimal(30,15) DEFAULT NULL,
  `TotalAssets` decimal(30,15) DEFAULT NULL,
  `TotalLiabilities` decimal(30,15) DEFAULT NULL,
  `RetainedEarnings` decimal(30,15) DEFAULT NULL,
  `EBIT` decimal(30,15) DEFAULT NULL,
  `NetSales` decimal(30,15) DEFAULT NULL,
  `X1` decimal(30,15) DEFAULT NULL,
  `X2` decimal(30,15) DEFAULT NULL,
  `X3` decimal(30,15) DEFAULT NULL,
  `X5` decimal(30,15) DEFAULT NULL,
  PRIMARY KEY (`ticker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reports_alt_checks` (
  `report_id` bigint(20) NOT NULL,
  `reportYear` char(4) NOT NULL,
  `WorkingCapital` decimal(30,15) DEFAULT NULL,
  `TotalAssets` decimal(30,15) DEFAULT NULL,
  `TotalLiabilities` decimal(30,15) DEFAULT NULL,
  `RetainedEarnings` decimal(30,15) DEFAULT NULL,
  `EBIT` decimal(30,15) DEFAULT NULL,
  `MarquetValueofEquity` decimal(30,15) DEFAULT NULL,
  `NetSales` decimal(30,15) DEFAULT NULL,
  `X1` decimal(30,15) DEFAULT NULL,
  `X2` decimal(30,15) DEFAULT NULL,
  `X3` decimal(30,15) DEFAULT NULL,
  `X4` decimal(30,15) DEFAULT NULL,
  `X5` decimal(30,15) DEFAULT NULL,
  `AltmanZNormal` decimal(30,15) DEFAULT NULL,
  `AltmanZRevised` decimal(30,15) DEFAULT NULL,
  PRIMARY KEY (`report_id`),
  KEY `reportYear` (`reportYear`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ttm_alt_checks` (
  `ticker_id` bigint(20) NOT NULL,
  `WorkingCapital` decimal(30,15) DEFAULT NULL,
  `TotalAssets` decimal(30,15) DEFAULT NULL,
  `TotalLiabilities` decimal(30,15) DEFAULT NULL,
  `RetainedEarnings` decimal(30,15) DEFAULT NULL,
  `EBIT` decimal(30,15) DEFAULT NULL,
  `SharesOutstandingDiluted` decimal(30,15) DEFAULT NULL,
  `NetSales` decimal(30,15) DEFAULT NULL,
  `X1` decimal(30,15) DEFAULT NULL,
  `X2` decimal(30,15) DEFAULT NULL,
  `X3` decimal(30,15) DEFAULT NULL,
  `X5` decimal(30,15) DEFAULT NULL,
  PRIMARY KEY (`ticker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
