CREATE TABLE  `jjun0366_frontend`.`ratings_pen` (
`variable` VARCHAR( 20 ) NOT NULL ,
`aplic_order` INT NOT NULL ,
`comp1` CHAR( 2 ) NOT NULL ,
`value1` FLOAT NOT NULL ,
`comp2` CHAR( 2 ) NULL ,
`value2` FLOAT NULL ,
`result` FLOAT NULL ,
PRIMARY KEY (  `variable` ,  `aplic_order` )
) ENGINE = InnoDB ;
