CREATE TABLE IF NOT EXISTS `tickers_id_history` (
        `id` bigint(20) NOT NULL,
        `ticker` varchar(20) NOT NULL,
        PRIMARY KEY (`ticker`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

