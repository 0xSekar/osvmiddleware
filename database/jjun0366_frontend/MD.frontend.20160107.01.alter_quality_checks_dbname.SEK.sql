CREATE TABLE  `jjun0366_frontend`.`reports_pio_checks` (
`report_id` BIGINT( 20 ) NOT NULL ,
 `pio1` SMALLINT( 6 ) NOT NULL DEFAULT  '0',
 `pio2` SMALLINT( 6 ) DEFAULT  '0',
 `pio3` SMALLINT( 6 ) DEFAULT  '0',
 `pio4` SMALLINT( 6 ) DEFAULT  '0',
 `pio5` SMALLINT( 6 ) DEFAULT  '0',
 `pio6` SMALLINT( 6 ) DEFAULT  '0',
 `pio7` SMALLINT( 6 ) DEFAULT  '0',
 `pio8` SMALLINT( 6 ) DEFAULT  '0',
 `pio9` SMALLINT( 6 ) DEFAULT  '0',
 `pioTotal` SMALLINT( 6 ) DEFAULT  '0',
PRIMARY KEY (  `report_id` ) ,
KEY  `pioTotal` (  `pioTotal` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO  `jjun0366_frontend`.`reports_pio_checks` 
SELECT * 
FROM  `jjun0366_frontend`.`reports_quality_checks` ;

DROP TABLE  `jjun0366_frontend`.`reports_quality_checks` ;

CREATE TABLE  `jjun0366_frontend`.`ttm_pio_checks` (
`ticker_id` BIGINT( 20 ) NOT NULL ,
 `pio1` SMALLINT( 6 ) DEFAULT  '0',
 `pio2` SMALLINT( 6 ) DEFAULT  '0',
 `pio3` SMALLINT( 6 ) DEFAULT  '0',
 `pio4` SMALLINT( 6 ) DEFAULT  '0',
 `pio5` SMALLINT( 6 ) DEFAULT  '0',
 `pio6` SMALLINT( 6 ) DEFAULT  '0',
 `pio7` SMALLINT( 6 ) DEFAULT  '0',
 `pio8` SMALLINT( 6 ) DEFAULT  '0',
 `pio9` SMALLINT( 6 ) DEFAULT  '0',
 `pioTotal` SMALLINT( 6 ) DEFAULT  '0',
PRIMARY KEY (  `ticker_id` ) ,
KEY  `pioTotal` (  `pioTotal` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO  `jjun0366_frontend`.`ttm_pio_checks` 
SELECT * 
FROM  `jjun0366_frontend`.`ttm_quality_checks` ;

DROP TABLE  `jjun0366_frontend`.`ttm_quality_checks` ;


