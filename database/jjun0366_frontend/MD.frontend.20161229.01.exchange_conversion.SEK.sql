CREATE TABLE IF NOT EXISTS `exchange_conversion` (
  `name_from` varchar(50) NOT NULL,
  `name_to` varchar(50) NOT NULL,
  PRIMARY KEY (`name_from`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `exchange_conversion` (`name_from`, `name_to`) VALUES
('', ''),
('Nasdaq Capital Market', 'Nasdaq'),
('Nasdaq Global Market', 'Nasdaq'),
('NYSE', 'NYSE'),
('NYSE MKT', 'NYSE'),
('OTC', 'OTC'),
('OTCBB', 'OTC'),
('Unknown', ''),
('WGAS', '');

