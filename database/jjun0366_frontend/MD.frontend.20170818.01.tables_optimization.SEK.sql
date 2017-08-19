ALTER TABLE `tickers_yahoo_historical_data` ADD INDEX  (`adj_close`);
ALTER TABLE tickers_proedgard_updates ADD INDEX (`downloaded`);
ALTER TABLE tickers_proedgard_updates ADD INDEX (`ticker`);
ALTER TABLE tickers_split_parser ADD INDEX (`ticker`);

