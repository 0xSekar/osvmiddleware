INSERT INTO `fields_metadata` (`metadata_id` ,`table_name`, `field_name`, `title`, `short_title`, `format`, `min`, `max`, `table_group`, `field_group`, `tooltip`, `field_type`, `good_increase`, `field_order`) VALUES
(NULL, NULL, 'SharesHeld', 'Quantity of Shares Held', 'Quantity', 'osvnumber:2:false', NULL, NULL, -10, NULL, 'Quantity of Shares Held', 'N', NULL, 100000),
(NULL, NULL, 'CostPerShare', 'Cost Per Share', 'Cost/Sh', 'osvcurrency:2', NULL, NULL, -10, NULL, 'Cost Per Share', 'N', NULL, 90001),
(NULL, NULL, 'DividendsReceived', 'Total Dividends Received', 'Div Received', 'osvcurrency:2', NULL, NULL, -10, NULL, 'Total Dividends Received', 'N', NULL, 90002),
(NULL, NULL, 'MarketValue', 'Current Market Value', 'Mkt Value', 'osvcurrency:0', NULL, NULL, -10, NULL, 'Current Market Value', 'N', NULL, 90003),
(NULL, NULL, 'UnrealizedGain', 'Unrealized P&L', 'Unrlz P&L', 'osvcurrency:2', NULL, NULL, -10, NULL, 'Unrealized P&L', 'N', NULL, 90004),
(NULL, NULL, 'UnrealizedGainPercent', 'Unrealized P&L Change', 'Unrlz P&L%', 'osvpercent:2:true', NULL, NULL, -10, NULL, 'Unrealized P&L Change', 'N', NULL, 90005),
(NULL, NULL, 'DateAdded', 'Date Added', 'Date Added', 'osvdate', NULL, NULL, -10, NULL, 'Date Added', 'D', NULL, 90006),
(NULL, NULL, 'DaysHeld', 'Number of Days Held', 'Days Held', 'osvnumber:0', NULL, NULL, -10, NULL, 'Days Held', 'N', NULL, 90007),
(NULL, NULL, 'StockNotes', 'Your Notes on the Stock', 'Note', 'osvtext', NULL, NULL, -10, NULL, 'Your Notes on the Stock', 'S', NULL, 90008);

INSERT INTO  `fields_metadata` (`metadata_id` ,`table_name` ,`field_name` ,`title` ,`short_title` ,`format` ,`min` ,`max` ,`table_group` ,`field_group` ,`tooltip` ,`field_type` ,`good_increase` ,`field_order`) VALUES (
NULL ,  'tickers_yahoo_quotes_1',  'DaysLow',  'Lowest Day Price',  'Day Low',  'osvcurrency:2', NULL , NULL ,  '0',  '14', 'Lowest Day Price',  'N', NULL ,  '');

INSERT INTO  `fields_metadata` (`metadata_id` ,`table_name` ,`field_name` ,`title` ,`short_title` ,`format` ,`min` ,`max` ,`table_group` ,`field_group` ,`tooltip` ,`field_type` ,`good_increase` ,`field_order`) VALUES (
NULL ,  'tickers_yahoo_quotes_1',  'DaysHigh',  'Highest Day Price',  'Day High',  'osvcurrency:2', NULL , NULL ,  '0',  '14', 'Highest Day Price',  'N', NULL ,  '');

INSERT INTO  `fields_metadata` (`metadata_id` ,`table_name` ,`field_name` ,`title` ,`short_title` ,`format` ,`min` ,`max` ,`table_group` ,`field_group` ,`tooltip` ,`field_type` ,`good_increase` ,`field_order`) VALUES (
NULL , NULL ,  'RealizedGain',  'Realized P&L',  'Realized P&L',  'osvcurrency:1', NULL , NULL ,  '-10', NULL ,  'Realized P&L',  'N', NULL ,  '90009'
);

INSERT INTO  `fields_metadata` (`metadata_id` ,`table_name` ,`field_name` ,`title` ,`short_title` ,`format` ,`min` ,`max` ,`table_group` ,`field_group` ,`tooltip` ,`field_type` ,`good_increase` ,`field_order`) VALUES (
NULL , NULL ,  'TotalGain',  'Total P&L',  'Total P&L',  'osvcurrency:1', NULL , NULL ,  '-10', NULL ,  'Total P&L',  'N', NULL ,  '90010'
);

INSERT INTO  `fields_metadata` (`metadata_id` ,`table_name` ,`field_name` ,`title` ,`short_title` ,`format` ,`min` ,`max` ,`table_group` ,`field_group` ,`tooltip` ,`field_type` ,`good_increase` ,`field_order`) VALUES (
NULL , NULL ,  'OverallReturn',  'Overall Return',  'Overall Return',  'osvpercent:2:true', NULL , NULL ,  '-10', NULL ,  'Overall Return', 'N', NULL ,  '90011'
);
