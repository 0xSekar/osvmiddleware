CREATE TABLE IF NOT EXISTS `tooltips` (
  `tooltip_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `short_title` varchar(200) DEFAULT NULL,
  `format` varchar(200) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  `table_group` int(11) NOT NULL DEFAULT '0',
  `field_group` int(11) DEFAULT NULL,
  `comment` blob,
  PRIMARY KEY (`tooltip_id`),
  KEY `field_group` (`field_group`),
  KEY `table_group` (`table_group`),
  KEY `table` (`table_name`,`field_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

ALTER TABLE  `screener_filter_fields` ADD  `tooltip_id` INT NOT NULL AFTER  `field_id` ,
ADD INDEX (  `tooltip_id` ) ;

ALTER TABLE  `screener_filter_fields2` ADD  `tooltip_id` INT NOT NULL AFTER  `field_id` ,
ADD INDEX (  `tooltip_id` ) ;

