ALTER TABLE  `ttm_ratings` ADD  `Grade` CHAR( 1 ) NULL;
ALTER TABLE  `jjun0366_frontend`.`ttm_ratings` ADD INDEX (  `Grade` );
ALTER TABLE  `reports_ratings` ADD  `Grade` CHAR( 1 ) NULL;
ALTER TABLE  `jjun0366_frontend`.`reports_ratings` ADD INDEX (  `Grade` );

