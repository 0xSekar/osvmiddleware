ALTER TABLE  `reports_ratings` CHANGE  `Grade`  `AS_grade` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE  `reports_ratings` ADD  `Q_grade` CHAR( 1 ) NOT NULL , ADD  `V_grade` CHAR( 1 ) NOT NULL , ADD  `G_grade` CHAR( 1 ) NOT NULL;
ALTER TABLE  `ttm_ratings` CHANGE  `Grade`  `AS_grade` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE  `ttm_ratings` ADD  `Q_grade` CHAR( 1 ) NOT NULL , ADD  `V_grade` CHAR( 1 ) NOT NULL , ADD  `G_grade` CHAR( 1 ) NOT NULL;
