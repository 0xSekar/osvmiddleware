CREATE TABLE `ticker_view_log` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` varchar(45) NOT NULL,
 `ticker` varchar(45) NOT NULL,
 `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;