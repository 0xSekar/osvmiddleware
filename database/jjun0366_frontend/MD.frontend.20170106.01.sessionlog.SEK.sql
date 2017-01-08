CREATE TABLE `sessionlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(64) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `requesttype` varchar(50) DEFAULT NULL,
  `requesttime` datetime DEFAULT NULL,
  `version` varchar(500) DEFAULT NULL,
  `ticker` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sessionlog_requesttime_IDX` (`requesttime`),
  KEY `sessionlog_login_IDX` (`login`),
  KEY `sessionlog_login_requesttime_IDX` (`login`,`requesttime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
