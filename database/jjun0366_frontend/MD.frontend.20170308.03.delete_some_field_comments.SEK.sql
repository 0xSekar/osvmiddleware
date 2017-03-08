ALTER TABLE  `tickers_yahoo_quotes_2` CHANGE  `SharesOutstanding`  `SharesOutstanding` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_xignite_estimates` CHANGE  `EGR_SP500LongTermGrowthRate`  `EGR_SP500LongTermGrowthRate` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_xignite_estimates` CHANGE  `SA_MeanEstimateSP500LongTermGrowth` `SA_MeanEstimateSP500LongTermGrowth` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_xignite_estimates` CHANGE  `SA_CurrentFiscalYearPriceEarningsGrowthSP500` `SA_CurrentFiscalYearPriceEarningsGrowthSP500` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_yahoo_estimates_curr_qtr` CHANGE  `EPSRevDown90days`  `EPSRevDown90days` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_yahoo_estimates_curr_qtr` CHANGE  `GrowthEstIndustry`  `GrowthEstIndustry` DECIMAL( 30, 15 ) NULL DEFAULT NULL ,
CHANGE  `GrowthEstSector`  `GrowthEstSector` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_yahoo_estimates_curr_year` CHANGE  `EPSRevDown90days`  `EPSRevDown90days` DECIMAL( 30, 15 ) NULL DEFAULT NULL ,
CHANGE  `GrowthEstIndustry`  `GrowthEstIndustry` DECIMAL( 30, 15 ) NULL DEFAULT NULL ,
CHANGE  `GrowthEstSector`  `GrowthEstSector` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_yahoo_estimates_next_qtr` CHANGE  `EPSRevDown90days`  `EPSRevDown90days` DECIMAL( 30, 15 ) NULL DEFAULT NULL ,
CHANGE  `GrowthEstIndustry`  `GrowthEstIndustry` DECIMAL( 30, 15 ) NULL DEFAULT NULL ,
CHANGE  `GrowthEstSector`  `GrowthEstSector` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
ALTER TABLE  `tickers_yahoo_estimates_next_year` CHANGE  `EPSRevDown90days`  `EPSRevDown90days` DECIMAL( 30, 15 ) NULL DEFAULT NULL ,
CHANGE  `GrowthEstIndustry`  `GrowthEstIndustry` DECIMAL( 30, 15 ) NULL DEFAULT NULL ,
CHANGE  `GrowthEstSector`  `GrowthEstSector` DECIMAL( 30, 15 ) NULL DEFAULT NULL ;
