ALTER TABLE industry_codes ENGINE=InnoDB;
ALTER TABLE pttm_balanceconsolidated ENGINE=InnoDB;
ALTER TABLE pttm_balancefull ENGINE=InnoDB;
ALTER TABLE pttm_cashflowconsolidated ENGINE=InnoDB;
ALTER TABLE pttm_cashflowfull ENGINE=InnoDB;
ALTER TABLE pttm_financialscustom ENGINE=InnoDB;
ALTER TABLE pttm_gf_data ENGINE=InnoDB;
ALTER TABLE pttm_incomeconsolidated ENGINE=InnoDB;
ALTER TABLE pttm_incomefull ENGINE=InnoDB;
ALTER TABLE ratings_weight ENGINE=InnoDB;
ALTER TABLE reports_balanceconsolidated ENGINE=InnoDB;
ALTER TABLE reports_balancefull ENGINE=InnoDB;
ALTER TABLE reports_cashflowconsolidated ENGINE=InnoDB;
ALTER TABLE reports_cashflowfull ENGINE=InnoDB;
ALTER TABLE reports_financialheader ENGINE=InnoDB;
ALTER TABLE reports_financialscustom ENGINE=InnoDB;
ALTER TABLE reports_gf_data ENGINE=InnoDB;
ALTER TABLE reports_header ENGINE=InnoDB;
ALTER TABLE reports_incomeconsolidated ENGINE=InnoDB;
ALTER TABLE reports_incomefull ENGINE=InnoDB;

/*update reports_key_ratios 
set ReportDateAdjusted = null 
where DATE_FORMAT(ReportDateAdjusted,'%Y-%m-%d') = '0000-00-00';*/
ALTER TABLE reports_key_ratios ENGINE=InnoDB;
ALTER TABLE reports_metadata_eol ENGINE=InnoDB;
ALTER TABLE reports_pio_checks ENGINE=InnoDB;
ALTER TABLE reports_ratings ENGINE=InnoDB;
ALTER TABLE reports_variable_ratios ENGINE=InnoDB;
ALTER TABLE sic_codes ENGINE=InnoDB;
ALTER TABLE tickers ENGINE=InnoDB;
ALTER TABLE tickers_activity_daily_ratios ENGINE=InnoDB;
ALTER TABLE tickers_control ENGINE=InnoDB;
ALTER TABLE tickers_growth_ratios ENGINE=InnoDB;
ALTER TABLE tickers_leverage_ratios ENGINE=InnoDB;
ALTER TABLE tickers_metadata_eol ENGINE=InnoDB;
ALTER TABLE tickers_mini_ratios ENGINE=InnoDB;
ALTER TABLE tickers_profitability_ratios ENGINE=InnoDB;
ALTER TABLE tickers_valuation_ratios ENGINE=InnoDB;

/*update tickers_xignite_estimates 
set EGR_CompanyIndustryCurrentFiscalYearEnd = null 
where DATE_FORMAT(EGR_CompanyIndustryCurrentFiscalYearEnd,'%Y-%m-%d') = '0000-00-00';
update tickers_xignite_estimates 
set EGR_CompanyIndustryNextFiscalYearEnd = null 
where DATE_FORMAT(EGR_CompanyIndustryNextFiscalYearEnd,'%Y-%m-%d') = '0000-00-00';
update tickers_xignite_estimates 
set EGR_CompanyIndustrySecondFiscalYearEnd = null 
where DATE_FORMAT(EGR_CompanyIndustrySecondFiscalYearEnd,'%Y-%m-%d') = '0000-00-00';*/
ALTER TABLE tickers_xignite_estimates ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_dividend_history ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_estimates_curr_qtr ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_estimates_curr_year ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_estimates_earn_hist ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_estimates_next_qtr ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_estimates_next_year ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_estimates_others ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_historical_data ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_keystats_1 ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_keystats_2 ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_quotes_1 ENGINE=InnoDB;
ALTER TABLE tickers_yahoo_quotes_2 ENGINE=InnoDB;
ALTER TABLE ttm_balanceconsolidated ENGINE=InnoDB;
ALTER TABLE ttm_balancefull ENGINE=InnoDB;
ALTER TABLE ttm_cashflowconsolidated ENGINE=InnoDB;
ALTER TABLE ttm_cashflowfull ENGINE=InnoDB;
ALTER TABLE ttm_financialscustom ENGINE=InnoDB;
ALTER TABLE ttm_gf_data ENGINE=InnoDB;
ALTER TABLE ttm_incomeconsolidated ENGINE=InnoDB;
ALTER TABLE ttm_incomefull ENGINE=InnoDB;
ALTER TABLE ttm_key_ratios ENGINE=InnoDB;
ALTER TABLE ttm_pio_checks ENGINE=InnoDB;
ALTER TABLE ttm_ratings ENGINE=InnoDB;

