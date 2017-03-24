CREATE TABLE IF NOT EXISTS `reports_valuation` (
  `report_id` bigint(20) NOT NULL,
  `nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Net Net Working Capital|NNWC can be considered as the absolute floor value or liquidation fire sale. NNWC = Cash & Equivalents + (Accounts Receivables x 0.75) + (Inventory x 0.5) - Total Liabilities|osvnumber:2:true|NNWC',
  `p_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'Price to NNWC|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NNWC value.|osvnumber:2:true|P/NNWC',
  `mos_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Margin of Safety|Margin of Safety % based on NNWC value|osvpercent:2:false|MOS NNWC',
  `ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Net Current Asset Value|Companies where their market cap is less than NCAV are considered extremely cheap. NCAV = Current Assets - Total Liabilities|osvnumber:2:true|NCAV',
  `p_ncav` decimal(30,15) DEFAULT NULL COMMENT 'Price to NCAV|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NCAV value.|osvnumber:2:true|P/NCAV',
  `mos_ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Margin of Safety|Margin of Safety % based on NCAV value|osvpercent:2:false|MOS NCAV',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reports_valuation_3cagr` (
  `report_id` bigint(20) NOT NULL,
  `nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Net Net Working Capital|NNWC can be considered as the absolute floor value or liquidation fire sale. NNWC = Cash & Equivalents + (Accounts Receivables x 0.75) + (Inventory x 0.5) - Total Liabilities|osvpercent:2:true|NNWC',
  `p_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'Price to NNWC|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NNWC value.|osvpercent:2:true|P/NNWC',
  `mos_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Margin of Safety|Margin of Safety % based on NNWC value|osvpercent:2:true|MOS NNWC',
  `ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Net Current Asset Value|Companies where their market cap is less than NCAV are considered extremely cheap. NCAV = Current Assets - Total Liabilities|osvpercent:2:true|NCAV',
  `p_ncav` decimal(30,15) DEFAULT NULL COMMENT 'Price to NCAV|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NCAV value.|osvpercent:2:true|P/NCAV',
  `mos_ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Margin of Safety|Margin of Safety % based on NCAV value|osvpercent:2:true|MOS NCAV',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reports_valuation_5cagr` (
  `report_id` bigint(20) NOT NULL,
  `nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Net Net Working Capital|NNWC can be considered as the absolute floor value or liquidation fire sale. NNWC = Cash & Equivalents + (Accounts Receivables x 0.75) + (Inventory x 0.5) - Total Liabilities|osvpercent:2:true|NNWC',
  `p_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'Price to NNWC|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NNWC value.|osvpercent:2:true|P/NNWC',
  `mos_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Margin of Safety|Margin of Safety % based on NNWC value|osvpercent:2:true|MOS NNWC',
  `ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Net Current Asset Value|Companies where their market cap is less than NCAV are considered extremely cheap. NCAV = Current Assets - Total Liabilities|osvpercent:2:true|NCAV',
  `p_ncav` decimal(30,15) DEFAULT NULL COMMENT 'Price to NCAV|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NCAV value.|osvpercent:2:true|P/NCAV',
  `mos_ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Margin of Safety|Margin of Safety % based on NCAV value|osvpercent:2:true|MOS NCAV',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reports_valuation_7cagr` (
  `report_id` bigint(20) NOT NULL,
  `nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Net Net Working Capital|NNWC can be considered as the absolute floor value or liquidation fire sale. NNWC = Cash & Equivalents + (Accounts Receivables x 0.75) + (Inventory x 0.5) - Total Liabilities|osvpercent:2:true|NNWC',
  `p_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'Price to NNWC|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NNWC value.|osvpercent:2:true|P/NNWC',
  `mos_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Margin of Safety|Margin of Safety % based on NNWC value|osvpercent:2:true|MOS NNWC',
  `ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Net Current Asset Value|Companies where their market cap is less than NCAV are considered extremely cheap. NCAV = Current Assets - Total Liabilities|osvpercent:2:true|NCAV',
  `p_ncav` decimal(30,15) DEFAULT NULL COMMENT 'Price to NCAV|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NCAV value.|osvpercent:2:true|P/NCAV',
  `mos_ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Margin of Safety|Margin of Safety % based on NCAV value|osvpercent:2:true|MOS NCAV',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reports_valuation_10cagr` (
  `report_id` bigint(20) NOT NULL,
  `nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Net Net Working Capital|NNWC can be considered as the absolute floor value or liquidation fire sale. NNWC = Cash & Equivalents + (Accounts Receivables x 0.75) + (Inventory x 0.5) - Total Liabilities|osvpercent:2:true|NNWC',
  `p_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'Price to NNWC|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NNWC value.|osvpercent:2:true|P/NNWC',
  `mos_nnwc` decimal(30,15) DEFAULT NULL COMMENT 'NNWC Margin of Safety|Margin of Safety % based on NNWC value|osvpercent:2:true|MOS NNWC',
  `ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Net Current Asset Value|Companies where their market cap is less than NCAV are considered extremely cheap. NCAV = Current Assets - Total Liabilities|osvpercent:2:true|NCAV',
  `p_ncav` decimal(30,15) DEFAULT NULL COMMENT 'Price to NCAV|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NCAV value.|osvpercent:2:true|P/NCAV',
  `mos_ncav` decimal(30,15) DEFAULT NULL COMMENT 'NCAV Margin of Safety|Margin of Safety % based on NCAV value|osvpercent:2:true|MOS NCAV',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `tickers_eod_valuation` ADD  `nnwc` DECIMAL( 30, 15 ) NULL COMMENT 'NNWC Net Net Working Capital|NNWC can be considered as the absolute floor value or liquidation fire sale. NNWC = Cash & Equivalents + (Accounts Receivables x 0.75) + (Inventory x 0.5) - Total Liabilities|osvnumber:2:true|NNWC' AFTER  `ebit` ,
ADD  `ncav` DECIMAL( 30, 15 ) NULL COMMENT  'NCAV Net Current Asset Value|Companies where their market cap is less than NCAV are considered extremely cheap. NCAV = Current Assets - Total Liabilities|osvnumber:2:true|NCAV' AFTER `nnwc` ;

ALTER TABLE  `tickers_eod_valuation` ADD  `p_nnwc` DECIMAL( 30, 15 ) NULL COMMENT 'Price to NNWC|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NNWC value.|osvnumber:2:true|P/NNWC' AFTER  `p_ebit` ,
ADD  `p_ncav` DECIMAL( 30, 15 ) NULL COMMENT 'Price to NCAV|Price to NNWC based on the latest quarterly data. Ratio < 1 means that the market cap is less than the NCAV value.|osvnumber:2:true|P/NCAV' AFTER  `p_nnwc` ;

ALTER TABLE  `tickers_eod_valuation` ADD  `mos_nnwc` DECIMAL( 30, 15 ) NULL COMMENT 'NNWC Margin of Safety|Margin of Safety % based on NNWC value|osvpercent:2:false|MOS NNWC',
ADD  `mos_ncav` DECIMAL( 30, 15 ) NULL COMMENT  'NCAV Margin of Safety|Margin of Safety % based on NCAV value|osvpercent:2:false|MOS NCAV';

