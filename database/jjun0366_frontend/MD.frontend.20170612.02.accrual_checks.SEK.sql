CREATE TABLE IF NOT EXISTS `reports_accrual_checks` (
        `report_id` bigint(20) NOT NULL,
        `net_operating_assets` decimal(30,15) DEFAULT NULL,
        `balance_sheet_aggregate_accrual` decimal(30,15) DEFAULT NULL,
        `cash_flow_aggregate_accrual` decimal(30,15) DEFAULT NULL,
        `balance_sheet_accrual_ratio` decimal(30,15) DEFAULT NULL,
        `cash_flow_accrual_ratio` decimal(30,15) DEFAULT NULL,
        `sloan_accrual_ratio` decimal(30,15) DEFAULT NULL,
        `stock_price` decimal(30,15) DEFAULT NULL,
        PRIMARY KEY (`report_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ttm_accrual_checks` (
        `ticker_id` bigint(20) NOT NULL,
        `net_operating_assets` decimal(30,15) DEFAULT NULL,
        `balance_sheet_aggregate_accrual` decimal(30,15) DEFAULT NULL,
        `cash_flow_aggregate_accrual` decimal(30,15) DEFAULT NULL,
        `balance_sheet_accrual_ratio` decimal(30,15) DEFAULT NULL,
        `cash_flow_accrual_ratio` decimal(30,15) DEFAULT NULL,
        `sloan_accrual_ratio` decimal(30,15) DEFAULT NULL,
        `stock_price` decimal(30,15) DEFAULT NULL,
        PRIMARY KEY (`ticker_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
