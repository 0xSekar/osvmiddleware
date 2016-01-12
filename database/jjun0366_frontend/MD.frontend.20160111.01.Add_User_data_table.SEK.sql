CREATE TABLE  `jjun0366_frontend`.`user_persistent_data` (
`user_id` VARCHAR( 32 ) NOT NULL ,
`name` VARCHAR( 32 ) NOT NULL ,
`value` BLOB NULL ,
PRIMARY KEY (  `user_id` ,  `name` )
) ENGINE = InnoDB ;
