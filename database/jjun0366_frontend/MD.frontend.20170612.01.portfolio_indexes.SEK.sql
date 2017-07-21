CREATE TABLE IF NOT EXISTS `market_indexes_history` (
        `index_name` varchar(10) NOT NULL,
        `report_date` date NOT NULL DEFAULT '0000-00-00',
        `close` decimal(30,15) DEFAULT NULL,
        PRIMARY KEY (`index_name`,`report_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
