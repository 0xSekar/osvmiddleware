CREATE TABLE IF NOT EXISTS `screener_filter_top1` (
  `filter_id` int(11) NOT NULL,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `screener_filter_top2` (
  `filter_id` int(11) NOT NULL,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `screener_filter_top2` ADD  `count` INT NOT NULL ,
ADD INDEX (  `count` ) ;
