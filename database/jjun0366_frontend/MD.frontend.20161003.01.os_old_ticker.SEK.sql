ALTER TABLE  `tickers` ADD  `is_old` BOOLEAN NOT NULL DEFAULT FALSE ,
ADD INDEX (  `is_old` ) ;

