ALTER TABLE  `tickers_eod_valuation` ADD  `avg_mos` DECIMAL( 30, 15 ) NULL ;

INSERT INTO  `fields_metadata` (
`metadata_id` ,
`table_name` ,
`field_name` ,
`title` ,
`short_title` ,
`format` ,
`min` ,
`max` ,
`table_group` ,
`field_group` ,
`tooltip` ,
`field_type` ,
`good_increase` ,
`field_order`
)
VALUES (
NULL ,  'tickers_eod_valuation',  'avg_mos',  'Average Fair Value Margin of Safety',  'Avg FV MOS',  'osvpercent:2:true', NULL , NULL ,  '0',  '20', 'Average Fair Value Margin of Safety % for Owner Earnings based DCF, EPS based DCF, Free Cash Flow based DCF, Graham and EBIT', 'N', NULL ,  ''
);

