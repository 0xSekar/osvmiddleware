CREATE TABLE IF NOT EXISTS `tickers_eod_valuation` (
  `ticker_id` bigint(20) NOT NULL,
  `dcf_eps` decimal(30,15) DEFAULT NULL COMMENT 'DCF Fair Value using EPS|Earnings based multi-stage Discounted Cash Flow. CAUTION: Fair value calculation uses automatic numbers and does not include required human adjustments. Use at own risk in a screener.|osvcurrency:2|DCF EPS',
  `dcf_fcf` decimal(30,15) DEFAULT NULL COMMENT 'DCF Fair Value using FCF|Free Cash Flow based multi-stage Discounted Cash Flow. CAUTION: Fair value calculation uses automatic numbers and does not include required human adjustments. Use at own risk in a screener.|osvcurrency:2|DCF FCF',
  `dcf_oe` decimal(30,15) DEFAULT NULL COMMENT 'DCF Fair Value using Owner Earnings OE|Owner Earnings based multi-stage Discounted Cash Flow. CAUTION: Fair value calculation uses automatic numbers and does not include required human adjustments. Use at own risk in a screener.|osvcurrency:2|DCF OE',
  `graham` decimal(30,15) DEFAULT NULL COMMENT 'Graham Formula Fair Value|Fair value using Ben Graham''s Formula as defined in The Intelligent Investor. A high end estimate of fair value. Optimistic as it uses raw growth numbers. CAUTION: Fair value calculation uses automatic numbers and does not include required human adjustments. Use at own risk in a screener.|osvcurrency:2|Graham',
  `ebit` decimal(30,15) DEFAULT NULL COMMENT 'EBIT Multiples Fair Value Normal Case|The normal case using 3 year normalized EBIT margins. CAUTION: Fair value calculation uses automatic numbers and does not include required human adjustments. Use at own risk in a screener.|osvcurrency:2|EBIT FV',
  `p_dcf_eps` decimal(30,15) DEFAULT NULL COMMENT 'Price to DCF (EPS Based)|Price to Fair Value ratio for Earnings based DCF. Ratio < 1 means it is undervalued. Lower the cheaper. CAUTION: Valuations change with a single input. Use at own risk.|osvnumber:2:true|P/DCF-EPS',
  `p_dcf_fcf` decimal(30,15) DEFAULT NULL COMMENT 'Price to DCF (FCF Based)|Price to Fair Value ratio for FCF based DCF. Ratio < 1 means it is undervalued. Lower the cheaper. CAUTION: Valuations change with a single input. Use at own risk.|osvnumber:2:true|P/DCF-FCF',
  `p_dcf_oe` decimal(30,15) DEFAULT NULL COMMENT 'Price to DCF (Owner Earnings Based)|Price to Fair Value ratio for Owner Earnings based DCF. Ratio < 1 means it is undervalued. Lower the cheaper. CAUTION: Valuations change with a single input. Use at own risk.|osvnumber:2:true|P/DCF-OE',
  `p_graham` decimal(30,15) DEFAULT NULL COMMENT 'Price to Graham|Price to Fair Value ratio for Graham''s Formula. Ratio < 1 means it is undervalued. Lower the cheaper. CAUTION: Valuations change with a single input. Use at own risk.|osvnumber:2:true|P/Graham',
  `p_ebit` decimal(30,15) DEFAULT NULL COMMENT 'Price to EBIT Fair Value (Normal Case)|Price to Fair Value ratio for the 3 yr Normalized EBIT Multiples. Ratio < 1 means it is undervalued. Lower the cheaper. CAUTION: Valuations change with a single input. Use at own risk.|osvnumber:2:true|P/EBIT-FV',
  `mos_dcf_eps` decimal(30,15) DEFAULT NULL COMMENT 'DCF Margin of Safety (EPS Based MOS)|Margin of Safety % for Earnings based DCF|osvpercent:2:true|MOS DCF-EPS',
  `mos_dcf_fcf` decimal(30,15) DEFAULT NULL COMMENT 'DCF Margin of Safety (FCF Based MOS)|Margin of Safety % for FCF based DCF|osvpercent:2:true|MOS DCF-FCF',
  `mos_dcf_oe` decimal(30,15) DEFAULT NULL COMMENT 'DCF Margin of Safety (OE Based MOS)|Margin of Safety % for Owner Earnings based DCF|osvpercent:2:true|MOS DCF-OE',
  `mos_graham` decimal(30,15) DEFAULT NULL COMMENT 'Graham Margin of Safety|Margin of Safety % for Ben Graham fair value|osvpercent:2:true|MOS Graham',
  `mos_ebit` decimal(30,15) DEFAULT NULL COMMENT 'EBIT Fair Value Margin of Safety|Margin of Safety % for 3 yr Normalized EBIT Multiples fair value|osvpercent:2:true|MOS EBIT FV',
  PRIMARY KEY (`ticker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

