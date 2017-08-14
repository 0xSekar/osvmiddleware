ALTER TABLE  `tickers_proedgard_updates` ADD  `missing_gf_period` DATE NULL ;

ALTER TABLE  `tickers_proedgard_updates` CHANGE  `filed_date`  `filed_date` DATETIME NULL DEFAULT NULL COMMENT 'Filed date, taken from email subject alert',
CHANGE  `tested_for_today`  `tested_for_today` DATETIME NULL DEFAULT NULL COMMENT 'To check if today this record has been tested or not, if yes, then system should ignore',
CHANGE  `missing_gf_period`  `missing_gf_period` DATETIME NULL DEFAULT NULL ;
