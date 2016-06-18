CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `username` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `last_activity` int(10) unsigned DEFAULT NULL,
  `expiration` int(10) unsigned DEFAULT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
