INSERT INTO fields_metadata (metadata_id, table_name, field_name, title, short_title, format, min, max, table_group, field_group, tooltip, field_type, good_increase, field_order)
VALUES
(NULL, 'ttm_accrual_checks', 'cash_flow_aggregate_accrual', 'Cash Flow Aggregate Accrual', 'CF Agg Accural', 'osvnumber:2:true', NULL, NULL, -1, NULL, 'Operations is composed of only two sides, the financing side and the operations side. Scale the cash flow-based aggregate accruals measure for comparative basis.<br> <katex>Accruals Ratio = (NI - (CFO + CFI))/(NOA_{t} + NOA_{t-1})/2</katex>', 'N', NULL, 0),
(NULL, 'ttm_accrual_checks', 'balance_sheet_aggregate_accrual', 'Balance Sheet Aggregate Accrual', 'BS. Agg Accrual', 'osvnumber:2:true', NULL, NULL, -1, NULL, 'Aggregate accruals are the change in Net Operating Assets (NOA) from one period to the next.<br> <katex>Aggregate Accruals = NOA_{t} - NOA_{t-1}</katex>', 'N', NULL, 0),
(NULL, 'ttm_accrual_checks', 'net_operating_assets', 'Net Operating Assets (NOA)', 'NOA', 'osvnumber:2:true', NULL, NULL, -1, NULL, 'Net operating assets (NOA) is the difference between operating assets and operating liabilities. NOA : (total assets – cash) – (total liabilities – total debt) ', 'N', 0, 0),
(NULL, 'ttm_dupont_checks', 'interest_burden', 'Interest Burden', 'Int. Burden', 'osvnumber:2:true', NULL, NULL, -1, NULL, 'EBT ÷ EBIT<br><br>Shows how interest is affecting profits. If a company has no debt, the ratio will be 1.', 'N', 0, 0),
(NULL, 'ttm_dupont_checks', 'equity_multiplier', 'Equity Multiplier', 'Eq. Multiplier', 'osvnumber:2:true', NULL, NULL, -1, NULL, 'Total Assets ÷ Shareholders Equity<br><br>Equity multiplier shows financial leverage.', 'N', 0, 0),
(NULL, 'ttm_dupont_checks', 'tax_burden', 'Tax Burden', 'Tax Burden', 'osvnumber:2:true', NULL, NULL, -1, NULL, 'Net Income ÷ EBT<br><br>Proportion of profits retained after paying taxes.', 'N', 0, 0),
(NULL, 'ttm_dupont_checks', 'operation_income_margin', 'Operating Income Margin', 'Op. Margin', 'osvpercent:2:true', NULL, NULL, -1, NULL, 'Net Income ÷ Sales<br><br>Net profit margin shows operating efficiency.', 'N', 1, 0)
;


UPDATE  `fields_metadata` SET  `format` =  'osvpercent:2:true',
`tooltip` = 'Sloan found that companies with low accrual ratios outperform companies with high accrual ratios.<br><br>Over a 40-year period between 1962 and 2001, buying the lowest accrual companies and shorting the highest accrual companies resulted in an average annual compounded return of 18% compared to the S&P 500''s 7.4% annual return over the same period.' WHERE  `fields_metadata`.`metadata_id` =1955;

UPDATE  `fields_metadata` SET  `format` =  'osvpercent:2:true',
`tooltip` = 'Sloan found that companies with low accrual ratios outperform companies with high accrual ratios.<br><br>Over a 40-year period between 1962 and 2001, buying the lowest accrual companies and shorting the highest accrual companies resulted in an average annual compounded return of 18% compared to the S&P 500''s 7.4% annual return over the same period.' WHERE  `fields_metadata`.`metadata_id` =1958;

UPDATE  `fields_metadata` SET  `short_title` =  'BS Accrual',
`format` =  'osvpercent:2:true',
`tooltip` = 'Balance sheet accrual can indicate whether capital is being used properly.<br><br>Accruals are accounting adjustments for revenues that have been earned but are not yet recorded in the accounts, and expenses that have been incurred but are not yet recorded in the accounts.' WHERE  `fields_metadata`.`metadata_id` =1953;

UPDATE  `fields_metadata` SET  `short_title` =  'BS Accrual',
`format` =  'osvpercent:2:true',
`tooltip` = 'Balance sheet accrual can indicate whether capital is being used properly.<br><br>Accruals are accounting adjustments for revenues that have been earned but are not yet recorded in the accounts, and expenses that have been incurred but are not yet recorded in the accounts.' WHERE  `fields_metadata`.`metadata_id` =1956;

UPDATE  `fields_metadata` SET  `short_title` =  'CF Accrual',
`format` =  'osvpercent:2:true',
`tooltip` = 'Accruals are accounting adjustments for revenues that have been earned but are not yet recorded in the accounts, and expenses that have been incurred but are not yet recorded in the accounts.' WHERE  `fields_metadata`.`metadata_id` =1954;

UPDATE  `fields_metadata` SET  `short_title` =  'CF Accrual',
`format` =  'osvpercent:2:true',
`tooltip` = 'Accruals are accounting adjustments for revenues that have been earned but are not yet recorded in the accounts, and expenses that have been incurred but are not yet recorded in the accounts.' WHERE  `fields_metadata`.`metadata_id` =1957;
