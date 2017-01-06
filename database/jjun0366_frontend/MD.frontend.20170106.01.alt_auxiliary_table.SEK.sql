CREATE TABLE IF NOT EXISTS `tickers_alt_aux` (
  `ticker_id` bigint(20) NOT NULL,
  `mrq_MarketValueofEquity` decimal(30,15) DEFAULT NULL,
  `mrq_X4` decimal(30,15) DEFAULT NULL,
  `mrq_AltmanZNormal` decimal(30,15) DEFAULT NULL,
  `mrq_AltmanZRevised` decimal(30,15) DEFAULT NULL,
  `ttm_MarketValueofEquity` decimal(30,15) DEFAULT NULL,
  `ttm_X4` decimal(30,15) DEFAULT NULL,
  `ttm_AltmanZNormal` decimal(30,15) DEFAULT NULL,
  `ttm_AltmanZRevised` decimal(30,15) DEFAULT NULL,
  PRIMARY KEY (`ticker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

