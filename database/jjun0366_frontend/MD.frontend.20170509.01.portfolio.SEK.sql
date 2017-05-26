DROP TABLE IF EXISTS `portfolio_persistent`;
CREATE TABLE IF NOT EXISTS `portfolio_persistent` (
        `id` bigint(20) NOT NULL AUTO_INCREMENT,
        `user_id` varchar(32) NOT NULL,
        `name` varchar(100) NOT NULL,
        `rank` smallint(6) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id` (`user_id`,`name`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portfolio_stocks`
--

DROP TABLE IF EXISTS `portfolio_stocks`;
CREATE TABLE IF NOT EXISTS `portfolio_stocks` (
        `pstock_id` bigint(20) NOT NULL AUTO_INCREMENT,
        `portfolio_id` bigint(20) NOT NULL,
        `ticker_id` bigint(20) NOT NULL,
        `date_added` date NOT NULL,
        `user_notes` text,
        `current_shares` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `current_costs` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `dividends_collected` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `realized_gain` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        PRIMARY KEY (`pstock_id`),
        UNIQUE KEY `portfolio_id` (`portfolio_id`,`ticker_id`),
        KEY `date_added` (`date_added`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portfolio_transactions`
--

DROP TABLE IF EXISTS `portfolio_transactions`;
CREATE TABLE IF NOT EXISTS `portfolio_transactions` (
        `transaction_id` bigint(20) NOT NULL AUTO_INCREMENT,
        `portfolio_id` bigint(20) NOT NULL,
        `ticker_id` bigint(20) NOT NULL,
        `transac_date` date NOT NULL,
        `transac_type` smallint(6) NOT NULL,
        `transac_price` decimal(30,15) NOT NULL,
        `transac_shares` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `transac_comission` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `transac_notes` text,
        `cash_linked` tinyint(1) NOT NULL DEFAULT '0',
        `transac_cash` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `transac_gain` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `transac_cost` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `transac_value` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `cost_per_share` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        `transac_gain_percent` decimal(30,15) NOT NULL DEFAULT '0.000000000000000',
        PRIMARY KEY (`transaction_id`),
        KEY `transac_date` (`transac_date`),
        KEY `transac_type` (`transac_type`),
        KEY `portfolio_id` (`portfolio_id`,`ticker_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

