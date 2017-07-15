CREATE TABLE IF NOT EXISTS `osv_blacklist` (
        `id` bigint(20) NOT NULL AUTO_INCREMENT,
        `ticker` varchar(45) DEFAULT NULL,
        `date_added` datetime DEFAULT NULL COMMENT 'date added to the table',
        `date_deleted_backend` datetime DEFAULT NULL COMMENT 'date when was deleted from backend',
        PRIMARY KEY (`id`),
        UNIQUE KEY `ticker_UNIQUE` (`ticker`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
