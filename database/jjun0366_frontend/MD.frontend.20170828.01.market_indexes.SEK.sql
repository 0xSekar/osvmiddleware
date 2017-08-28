CREATE TABLE IF NOT EXISTS `market_indexes` (
        `index_name` varchar(10) NOT NULL,
        `report_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        `value` decimal(30,15) DEFAULT NULL,
        PRIMARY KEY (`index_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `market_indexes` (`index_name`, `report_date`, `value`) VALUES
('DIA', '2017-08-01 00:00:00', NULL),
    ('IWM', '2017-08-01 00:00:00', NULL),
    ('QQQ', '2017-08-01 00:00:00', NULL),
    ('VOO', '2017-08-01 00:00:00', NULL);
