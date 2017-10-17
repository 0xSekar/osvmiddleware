DROP TABLE IF EXISTS ratings_pen;

DROP TABLE IF EXISTS ratings_weight;

CREATE TABLE `ratings_weight` (
  `variable` char(2) NOT NULL,
  `weight` float NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('G1',0.1,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('G2',0.1,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('G3',0.55,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('G4',0.25,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('Q1',0.275,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('Q2',0.45,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('Q3',0.275,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('V1',0.275,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('V2',0.375,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('V3',0.075,NULL);
INSERT INTO `ratings_weight` (`variable`,`weight`,`name`) VALUES ('V4',0.275,NULL);

DROP TABLE IF EXISTS ratings_filters;
CREATE TABLE `ratings_filters` (
  `variable` VARCHAR(20) NOT NULL,
  `field_order` SMALLINT NOT NULL,
  `value1` DECIMAL(4,2) NOT NULL,
  `value2` DECIMAL(4,2) NULL,
  PRIMARY KEY (`variable`, `field_order`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`, `value2`) VALUES ('FCF_S', '1', '0', '0.30');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('FCF_S', '2', '0.30');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('FCF_S', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`, `value2`) VALUES ('CROIC', '1', '0', '0.40');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('CROIC', '2', '0.40');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('CROIC', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`, `value2`) VALUES ('SalesPercChange', '1', '0', '0.60');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('SalesPercChange', '2', '0.60');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('SalesPercChange', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`, `value2`) VALUES ('Sales5YYCGrPerc', '1', '0', '0.40');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('Sales5YYCGrPerc', '2', '0.40');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('Sales5YYCGrPerc', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`, `value2`) VALUES ('GPA', '1', '0', '1');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('GPA', '2', '1');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('GPA', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('EV_EBIT', '2', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('EV_EBIT', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('P_FCF', '2', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('P_FCF', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('P_BV', '2', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('P_BV', '3', '0');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`, `value2`) VALUES ('RevenuePctGrowthTTM', '1', '0', '0.60');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('RevenuePctGrowthTTM', '2', '0.60');
INSERT INTO `ratings_filters` (`variable`, `field_order`, `value1`) VALUES ('RevenuePctGrowthTTM', '3', '0');
