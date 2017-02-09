TRUNCATE TABLE `screener_filter_groups`;

INSERT INTO `screener_filter_groups` (`group_id`, `group_name`, `group_description`, `group_order`) VALUES
(1, 'Altman', NULL, 1),
(2, 'Balance Sheet', NULL, 2),
(3, 'Balance Sheet Growth', NULL, 3),
(4, 'Beneish M Score', NULL, 4),
(5, 'Cash Flow Statement', NULL, 5),
(6, 'Cash Flow Statement Growth', NULL, 6),
(7, 'Company', NULL, 7),
(8, 'Estimates', NULL, 8),
(9, 'Income Statement', NULL, 10),
(10, 'Income Statement Growth', NULL, 11),
(12, 'Fundamentals Growth', NULL, 9),
(13, 'OSV Ratings', NULL, 13),
(14, 'Key Stats', NULL, 12),
(15, 'Stock Price', NULL, 17),
(16, 'Piotroski', NULL, 14),
(17, 'Ratios', NULL, 15),
(18, 'Ratios Growth', NULL, 16),
(19, 'Others', NULL, 20),
(20, 'Valuation', NULL, 18);

