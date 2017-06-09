CREATE TABLE IF NOT EXISTS `reports_dupont_checks` (
        `report_id` bigint(20) NOT NULL,
        `net_profit_margin` decimal(30,15) DEFAULT NULL,
        `asset_turnover` decimal(30,15) DEFAULT NULL,
        `equity_multiplier` decimal(30,15) DEFAULT NULL,
        `roe_3` decimal(30,15) DEFAULT NULL,
        `tax_burden` decimal(30,15) DEFAULT NULL,
        `interest_burden` decimal(30,15) DEFAULT NULL,
        `operation_income_margin` decimal(30,15) DEFAULT NULL,
        `roe_5` decimal(30,15) DEFAULT NULL,
        PRIMARY KEY (`report_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ttm_dupont_checks` (
        `ticker_id` bigint(20) NOT NULL,
        `net_profit_margin` decimal(30,15) DEFAULT NULL,
        `asset_turnover` decimal(30,15) DEFAULT NULL,
        `equity_multiplier` decimal(30,15) DEFAULT NULL,
        `roe_3` decimal(30,15) DEFAULT NULL,
        `tax_burden` decimal(30,15) DEFAULT NULL,
        `interest_burden` decimal(30,15) DEFAULT NULL,
        `operation_income_margin` decimal(30,15) DEFAULT NULL,
        `roe_5` decimal(30,15) DEFAULT NULL,
        PRIMARY KEY (`ticker_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
