CREATE TABLE IF NOT EXISTS `screener_criteria` (
  `crit_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `crit_text` varchar(50) NOT NULL,
  `crit_cond` char(2) NOT NULL,
  `crit_value1` varchar(50) DEFAULT NULL,
  `crit_value2` varchar(50) DEFAULT NULL,
  `crit_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crit_id`),
  KEY `field_id` (`field_id`,`crit_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `screener_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_table_name` varchar(50) NOT NULL,
  `field_table_field` varchar(50) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `field_desc` text,
  `field_type` char(1) NOT NULL,
  `field_group` int(11) NOT NULL,
  `field_order` int(11) NOT NULL DEFAULT '0',
  `report_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`field_id`),
  KEY `field_group` (`field_group`,`field_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `screener_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `group_description` text,
  `group_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `group_order` (`group_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `screener_persistent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `criteria` blob,
  `params` blob,
  `fields` blob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

