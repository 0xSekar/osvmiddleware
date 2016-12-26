DROP TABLE IF EXISTS `screener_field_current_group_persistent`;
CREATE TABLE IF NOT EXISTS `screener_field_current_group_persistent` (
  `user_id` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` char(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `screener_field_fields`;
CREATE TABLE IF NOT EXISTS `screener_field_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(50) NOT NULL,
  `field_group` int(11) NOT NULL,
  `field_order` char(4) NOT NULL DEFAULT 'NONE',
  `field_display_order` int(11) NOT NULL DEFAULT '0',
  `field_display_group` int(11) NOT NULL,
  PRIMARY KEY (`field_id`),
  KEY `field_group` (`field_group`,`field_display_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `screener_field_groups`;
CREATE TABLE IF NOT EXISTS `screener_field_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `group_display_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `group_order` (`group_display_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `screener_field_persistent`;
CREATE TABLE IF NOT EXISTS `screener_field_persistent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `config` blob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `screener_filter_criteria`;
CREATE TABLE IF NOT EXISTS `screener_filter_criteria` (
  `crit_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `crit_text` varchar(50) NOT NULL,
  `crit_cond` char(2) NOT NULL,
  `crit_value1` varchar(50) DEFAULT NULL,
  `crit_value2` varchar(50) DEFAULT NULL,
  `crit_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crit_id`),
  KEY `field_id` (`field_id`,`crit_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `screener_filter_fields`;
CREATE TABLE IF NOT EXISTS `screener_filter_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_table_name` varchar(50) NOT NULL,
  `field_table_field` varchar(50) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `field_desc` text,
  `field_type` char(1) NOT NULL,
  `field_group` int(11) NOT NULL,
  `field_order` int(11) NOT NULL DEFAULT '0',
  `report_type` char(3) DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `min` bigint(20) DEFAULT NULL,
  `max` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`field_id`),
  KEY `field_group` (`field_group`,`field_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `screener_filter_groups`;
CREATE TABLE IF NOT EXISTS `screener_filter_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `group_description` text,
  `group_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `group_order` (`group_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `screener_persistent`;
CREATE TABLE IF NOT EXISTS `screener_persistent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `criteria` blob,
  `params` blob,
  `fields` blob,
  `rank` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

