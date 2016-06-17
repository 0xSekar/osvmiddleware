CREATE TABLE IF NOT EXISTS `reports_beneish_checks` (
  `report_id` bigint(20) NOT NULL,
  `DSRI` decimal(30,15) DEFAULT NULL,
  `GMI` decimal(30,15) DEFAULT NULL,
  `AQI` decimal(30,15) DEFAULT NULL,
  `SGI` decimal(30,15) DEFAULT NULL,
  `DEPI` decimal(30,15) DEFAULT NULL,
  `SGAI` decimal(30,15) DEFAULT NULL,
  `TATA` decimal(30,15) DEFAULT NULL,
  `LVGI` decimal(30,15) DEFAULT NULL,
  `BM5` decimal(30,15) DEFAULT NULL,
  `BM8` decimal(30,15) DEFAULT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ttm_beneish_checks` (
  `ticker_id` bigint(20) NOT NULL,
  `DSRI` decimal(30,15) DEFAULT NULL,
  `GMI` decimal(30,15) DEFAULT NULL,
  `AQI` decimal(30,15) DEFAULT NULL,
  `SGI` decimal(30,15) DEFAULT NULL,
  `DEPI` decimal(30,15) DEFAULT NULL,
  `SGAI` decimal(30,15) DEFAULT NULL,
  `TATA` decimal(30,15) DEFAULT NULL,
  `LVGI` decimal(30,15) DEFAULT NULL,
  `BM5` decimal(30,15) DEFAULT NULL,
  `BM8` decimal(30,15) DEFAULT NULL,
  PRIMARY KEY (`ticker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

