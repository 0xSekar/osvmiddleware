ALTER TABLE  `tickers_control` ADD  `last_barchart_date` DATETIME NOT NULL AFTER  `last_yahoo_date` ;
UPDATE tickers_control SET  `last_barchart_date` =  "2000-01-01";
