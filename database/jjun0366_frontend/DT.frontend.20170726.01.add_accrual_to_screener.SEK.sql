TRUNCATE TABLE `screener_filter_groups`;

INSERT INTO `screener_filter_groups` (`group_id`, `group_name`, `group_description`, `group_order`) VALUES
(1, 'Altman', NULL, 2),
(2, 'Balance Sheet', NULL, 3),
(3, 'Balance Sheet Growth', NULL, 4),
(4, 'Beneish M Score', NULL, 5),
(5, 'Cash Flow Statement', NULL, 6),
(6, 'Cash Flow Statement Growth', NULL, 7),
(7, 'Company', NULL, 8),
(8, 'Estimates', NULL, 9),
(9, 'Income Statement', NULL, 11),
(10, 'Income Statement Growth', NULL, 12),
(12, 'Fundamentals Growth', NULL, 10),
(13, 'OSV Ratings', NULL, 14),
(14, 'Key Stats', NULL, 13),
(15, 'Stock Price', NULL, 18),
(16, 'Piotroski', NULL, 15),
(17, 'Ratios', NULL, 16),
(18, 'Ratios Growth', NULL, 17),
(19, 'Others', NULL, 21),
(20, 'Valuation', NULL, 19),
(21, 'Valuation Growth', NULL, 20),
(22, 'Accrual', NULL, 1);

INSERT INTO  `fields_metadata` (`metadata_id` ,`table_name` ,`field_name` ,`title`, `short_title` ,`format` ,`min` ,`max` ,`table_group` ,`field_group` ,`tooltip` ,`field_type` ,`good_increase` ,`field_order`) VALUES (
NULL ,  'reports_accrual_checks',  'balance_sheet_accrual_ratio',  'Balance Sheet Accrual Ratio',  'Balance Accrual', 'osvnumber:2:true', NULL , NULL ,  '1',  '22',  '',  'N', NULL ,  ''),
(NULL ,  'reports_accrual_checks',  'cash_flow_accrual_ratio',  'Cash Flow Accrual Ratio',  'Cash Accrual',  'osvnumber:2:true', NULL , NULL ,  '1',  '22',  '',  'N', NULL ,  ''),
(NULL ,  'reports_accrual_checks',  'sloan_accrual_ratio',  'Sloan Accrual Ratio',  'Sloan Accrual',  'osvnumber:2:true', NULL , NULL , '1',  '22',  '',  'N', NULL ,  '0'),
(NULL ,  'ttm_accrual_checks',  'balance_sheet_accrual_ratio',  'Balance Sheet Accrual Ratio',  'Balance Accrual',  'osvnumber:2:true', NULL , NULL ,  '3',  '22',  '',  'N', NULL ,  '0'),
(NULL ,  'ttm_accrual_checks',  'cash_flow_accrual_ratio',  'Cash Flow Accrual Ratio',  'Cash Accrual',  'osvnumber:2:true', NULL , NULL ,  '3',  '22',  '',  'N', NULL ,  '0'),
(NULL ,  'ttm_accrual_checks',  'sloan_accrual_ratio',  'Sloan Accrual Ratio',  'Sloan Accrual',  'osvnumber:2:true', NULL , NULL ,  '3', '22',  '',  'N', NULL ,  '0');
