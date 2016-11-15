ALTER TABLE `mrq_alt_checks` CHANGE `ticker_id` `ticker_id` BIGINT(20) NOT NULL, CHANGE `WorkingCapital` `WorkingCapital` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X1: Working Capital/Total Assets<br><br>Used with Total Assets to measure net liquid assets relative to total capitalization.', CHANGE `TotalAssets` `TotalAssets` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Total Assets from the balance sheet.<br><br>Used in X1: Working Capital/Total Assets<br><br>and X2: Retained Earnings/Total Assets<br><br>and X3: EBIT/Total Assets<br><br>and S5: Sales/Total Assets.', CHANGE `TotalLiabilities` `TotalLiabilities` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Total liabilities from the balance sheet.<br><br>X4: Market Value of Equity/Total Liabilities<br><br>This ratio shows how much the assets can decline in value before liabilities exceed assets and the company becomes insolvent.<br><br>For example, a company with a market value of its equity of $1,000 and debt of $500 could experience a two-thirds drop in asset value before insolvency.<br>However, the same firm with $250 equity will be insolvent if assets drop only one-third in value', CHANGE `RetainedEarnings` `RetainedEarnings` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Retained Earnings from the Income Statement.<br><br>X2: Retained Earnings/Total Assets<br><br>The total reinvested earnings or losses over the life of the company and is used with Total Assets to measure cumulative profitability over time.<br>The age of the company affects this ratio as a young company has not had time to build up its cumulative profits.<br><br>Also measures the leverage of the company. High RE/TA means the company uses its retained earnings for growth and not debt.', CHANGE `EBIT` `EBIT` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Earnings Before Interest and Tax (EBIT) from the Income Statement.<br><br>X3: EBIT/Total Assets<br><br>Measures the true productivity independent of tax or leverage. According to Altman, this ratio outperforms other profitability measures, including cash flow.', CHANGE `NetSales` `NetSales` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Sales from the income statement.<br><br>X5: Sales/Total Assets<br><br>This ratio shows the ability of the company to generate sales with its asset base. The ratio has a unique relation to other variables by contributing second most to the overal ability of the model. On its own, it is not as effective.', CHANGE `X1` `X1` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X1 = WC/TA<br><br>The working capital/total assets ratio, frequently found in studies of corporate problems, is a measure of the net liquid assets of the firm relative to the total capitalization.<br><br>Ordinarily, a firm experiencing consistent operating losses will have shrinking current assets in relation to total assets.<br><br>Of the three liquidity ratios evaluated, this one proved to be the most valuable.', CHANGE `X2` `X2` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X2 = RE/TA<br><br>Retained earnings is the account which reports the total amount of reinvested earnings and/or losses of a firm over its entire life. The account is also referred to as earned surplus.<br><br>This measure of cumulative profitability over time is what I referred to earlier as a new ratio. The age of a firm is implicitly considered in this ratio.<br><br>A relatively young firm will probably show a low RE/TA ratio because it has not had time to build up its cumulative profits.', CHANGE `X3` `X3` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X3 = EBIT/TA<br><br>This ratio is a measure of the true productivity of the firm''s assets, independent of any tax or leverage factors. Since the firm''s ultimate existence is based on the earning power of its assets, this ratio appears to be particularly appropriate for studies dealing with corporate failure', CHANGE `X5` `X5` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X5 = Sales/TA<br><br>The capital-turnover ratio is a standard financial ratio illustrating the sales generating ability of the firm''s assets.<br><br>It is one measure of management.s capacity in dealing with competitive conditions. This final ratio is quite important because it is the least significant ratio on an individual basis.<br><br>However, because of its unique relationship to other variables in the model,the sales/total assets ratio ranks second in its contribution to the overall discriminating ability of the model. ';

ALTER TABLE `ttm_alt_checks` CHANGE `ticker_id` `ticker_id` BIGINT(20) NOT NULL, CHANGE `WorkingCapital` `WorkingCapital` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X1: Working Capital/Total Assets<br><br>Used with Total Assets to measure net liquid assets relative to total capitalization.', CHANGE `TotalAssets` `TotalAssets` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Total Assets from the balance sheet.<br><br>Used in X1: Working Capital/Total Assets<br><br>and X2: Retained Earnings/Total Assets<br><br>and X3: EBIT/Total Assets<br><br>and S5: Sales/Total Assets.', CHANGE `TotalLiabilities` `TotalLiabilities` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Total liabilities from the balance sheet.<br><br>X4: Market Value of Equity/Total Liabilities<br><br>This ratio shows how much the assets can decline in value before liabilities exceed assets and the company becomes insolvent.<br><br>For example, a company with a market value of its equity of $1,000 and debt of $500 could experience a two-thirds drop in asset value before insolvency.<br>However, the same firm with $250 equity will be insolvent if assets drop only one-third in value', CHANGE `RetainedEarnings` `RetainedEarnings` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Retained Earnings from the Income Statement.<br><br>X2: Retained Earnings/Total Assets<br><br>The total reinvested earnings or losses over the life of the company and is used with Total Assets to measure cumulative profitability over time.<br>The age of the company affects this ratio as a young company has not had time to build up its cumulative profits.<br><br>Also measures the leverage of the company. High RE/TA means the company uses its retained earnings for growth and not debt.', CHANGE `EBIT` `EBIT` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Earnings Before Interest and Tax (EBIT) from the Income Statement.<br><br>X3: EBIT/Total Assets<br><br>Measures the true productivity independent of tax or leverage. According to Altman, this ratio outperforms other profitability measures, including cash flow.', CHANGE `NetSales` `NetSales` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Sales from the income statement.<br><br>X5: Sales/Total Assets<br><br>This ratio shows the ability of the company to generate sales with its asset base. The ratio has a unique relation to other variables by contributing second most to the overal ability of the model. On its own, it is not as effective.', CHANGE `X1` `X1` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X1 = WC/TA<br><br>The working capital/total assets ratio, frequently found in studies of corporate problems, is a measure of the net liquid assets of the firm relative to the total capitalization.<br><br>Ordinarily, a firm experiencing consistent operating losses will have shrinking current assets in relation to total assets.<br><br>Of the three liquidity ratios evaluated, this one proved to be the most valuable.', CHANGE `X2` `X2` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X2 = RE/TA<br><br>Retained earnings is the account which reports the total amount of reinvested earnings and/or losses of a firm over its entire life. The account is also referred to as earned surplus.<br><br>This measure of cumulative profitability over time is what I referred to earlier as a new ratio. The age of a firm is implicitly considered in this ratio.<br><br>A relatively young firm will probably show a low RE/TA ratio because it has not had time to build up its cumulative profits.', CHANGE `X3` `X3` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X3 = EBIT/TA<br><br>This ratio is a measure of the true productivity of the firm''s assets, independent of any tax or leverage factors. Since the firm''s ultimate existence is based on the earning power of its assets, this ratio appears to be particularly appropriate for studies dealing with corporate failure', CHANGE `X5` `X5` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X5 = Sales/TA<br><br>The capital-turnover ratio is a standard financial ratio illustrating the sales generating ability of the firm''s assets.<br><br>It is one measure of management.s capacity in dealing with competitive conditions. This final ratio is quite important because it is the least significant ratio on an individual basis.<br><br>However, because of its unique relationship to other variables in the model,the sales/total assets ratio ranks second in its contribution to the overall discriminating ability of the model. ';

ALTER TABLE `reports_alt_checks` CHANGE `WorkingCapital` `WorkingCapital` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X1: Working Capital/Total Assets<br><br>Used with Total Assets to measure net liquid assets relative to total capitalization.', CHANGE `TotalAssets` `TotalAssets` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Total Assets from the balance sheet.<br><br>Used in X1: Working Capital/Total Assets<br><br>and X2: Retained Earnings/Total Assets<br><br>and X3: EBIT/Total Assets<br><br>and S5: Sales/Total Assets.', CHANGE `TotalLiabilities` `TotalLiabilities` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Total liabilities from the balance sheet.<br><br>X4: Market Value of Equity/Total Liabilities<br><br>This ratio shows how much the assets can decline in value before liabilities exceed assets and the company becomes insolvent.<br><br>For example, a company with a market value of its equity of $1,000 and debt of $500 could experience a two-thirds drop in asset value before insolvency.<br>However, the same firm with $250 equity will be insolvent if assets drop only one-third in value', CHANGE `RetainedEarnings` `RetainedEarnings` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Retained Earnings from the Income Statement.<br><br>X2: Retained Earnings/Total Assets<br><br>The total reinvested earnings or losses over the life of the company and is used with Total Assets to measure cumulative profitability over time.<br>The age of the company affects this ratio as a young company has not had time to build up its cumulative profits.<br><br>Also measures the leverage of the company. High RE/TA means the company uses its retained earnings for growth and not debt.', CHANGE `EBIT` `EBIT` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Earnings Before Interest and Tax (EBIT) from the Income Statement.<br><br>X3: EBIT/Total Assets<br><br>Measures the true productivity independent of tax or leverage. According to Altman, this ratio outperforms other profitability measures, including cash flow.', CHANGE `MarketValueofEquity` `MarketValueofEquity` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'The stock market value of the equity only.<br><br>The equity market value serves as a proxy for the company asset values.', CHANGE `NetSales` `NetSales` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Sales from the income statement.<br><br>X5: Sales/Total Assets<br><br>This ratio shows the ability of the company to generate sales with its asset base. The ratio has a unique relation to other variables by contributing second most to the overal ability of the model. On its own, it is not as effective.', CHANGE `X1` `X1` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X1 = WC/TA<br><br>The working capital/total assets ratio, frequently found in studies of corporate problems, is a measure of the net liquid assets of the firm relative to the total capitalization.<br><br>Ordinarily, a firm experiencing consistent operating losses will have shrinking current assets in relation to total assets.<br><br>Of the three liquidity ratios evaluated, this one proved to be the most valuable.', CHANGE `X2` `X2` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X2 = RE/TA<br><br>Retained earnings is the account which reports the total amount of reinvested earnings and/or losses of a firm over its entire life. The account is also referred to as earned surplus.<br><br>This measure of cumulative profitability over time is what I referred to earlier as a new ratio. The age of a firm is implicitly considered in this ratio.<br><br>A relatively young firm will probably show a low RE/TA ratio because it has not had time to build up its cumulative profits.', CHANGE `X3` `X3` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X3 = EBIT/TA<br><br>This ratio is a measure of the true productivity of the firm''s assets, independent of any tax or leverage factors. Since the firm''s ultimate existence is based on the earning power of its assets, this ratio appears to be particularly appropriate for studies dealing with corporate failure', CHANGE `X4` `X4` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X4 = MVoE/TL<br><br>The measure shows how much the firm''s assets can decline in value (measured by market value of equity plus debt) before the liabilities exceed the assets and the firm becomes insolvent.<br><br>E.g. a company with a market value of its equity of $1,000 and debt of $500 could experience a two-thirds drop in asset value before insolvency.<br><br>However, the same firm with $250 equity will be insolvent if assets drop only one-third in value.', CHANGE `X5` `X5` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'X5 = Sales/TA<br><br>The capital-turnover ratio is a standard financial ratio illustrating the sales generating ability of the firm''s assets.<br><br>It is one measure of management.s capacity in dealing with competitive conditions. This final ratio is quite important because it is the least significant ratio on an individual basis.<br><br>However, because of its unique relationship to other variables in the model,the sales/total assets ratio ranks second in its contribution to the overall discriminating ability of the model. ', CHANGE `AltmanZNormal` `AltmanZNormal` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Original Altman Z score used for manufacturing companies.<br><br>When Z is below 1.8, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.', CHANGE `AltmanZRevised` `AltmanZRevised` DECIMAL(30,15) NULL DEFAULT NULL COMMENT 'Revised Altman Z score used for non-manufacturing companies.<br><br>When Z is below 1.1, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.';
