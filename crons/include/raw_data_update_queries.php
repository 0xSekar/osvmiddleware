<?php
function update_raw_data_tickers($dates, $rawdata) {
	$areports = AREPORTS;
	$qreports = QREPORTS;
	$treports = $areports+$qreports;

	$report_tables = array("reports_balanceconsolidated","reports_balanceconsolidated_3cagr","reports_balanceconsolidated_5cagr","reports_balanceconsolidated_7cagr","reports_balanceconsolidated_10cagr","reports_balancefull","reports_balancefull_3cagr","reports_balancefull_5cagr","reports_balancefull_7cagr","reports_balancefull_10cagr","reports_cashflowconsolidated","reports_cashflowconsolidated_3cagr","reports_cashflowconsolidated_5cagr","reports_cashflowconsolidated_7cagr","reports_cashflowconsolidated_10cagr","reports_cashflowfull","reports_cashflowfull_3cagr","reports_cashflowfull_5cagr","reports_cashflowfull_7cagr","reports_cashflowfull_10cagr","reports_financialheader","reports_gf_data","reports_gf_data_3cagr","reports_gf_data_5cagr","reports_gf_data_7cagr","reports_gf_data_10cagr","reports_incomeconsolidated","reports_incomeconsolidated_3cagr","reports_incomeconsolidated_5cagr","reports_incomeconsolidated_7cagr","reports_incomeconsolidated_10cagr","reports_incomefull","reports_incomefull_3cagr","reports_incomefull_5cagr","reports_incomefull_7cagr","reports_incomefull_10cagr","reports_metadata_eol","reports_variable_ratios","reports_variable_ratios_3cagr","reports_variable_ratios_5cagr","reports_variable_ratios_7cagr","reports_variable_ratios_10cagr","reports_financialscustom","reports_financialscustom_3cagr","reports_financialscustom_5cagr","reports_financialscustom_7cagr","reports_financialscustom_10cagr","reports_key_ratios","reports_key_ratios_3cagr","reports_key_ratios_5cagr","reports_key_ratios_7cagr","reports_key_ratios_10cagr");
	$ticker_tables = array("tickers_activity_daily_ratios", "tickers_growth_ratios", "tickers_leverage_ratios", "tickers_metadata_eol", "tickers_mini_ratios", "tickers_profitability_ratios", "tickers_valuation_ratios");
	$ttm_tables = array("ttm_balanceconsolidated","ttm_balancefull","ttm_cashflowconsolidated","ttm_cashflowfull","ttm_incomeconsolidated","ttm_incomefull","ttm_financialscustom", "ttm_gf_data");
	$pttm_tables = array("pttm_balanceconsolidated","pttm_balancefull","pttm_cashflowconsolidated","pttm_cashflowfull","pttm_incomeconsolidated","pttm_incomefull","pttm_financialscustom", "pttm_gf_data");

        //Delete all reports before updating to be sure we do not miss any manual update
        //as this is a batch process, it will not impact on the UE
        foreach($ticker_tables as $table) {
                $query = "DELETE FROM $table WHERE ticker_id = ".$dates->ticker_id;
                mysql_query($query) or die (mysql_error());
        }

        //Update tickers_* tables (tables that hold only 1 data point per symbol)
        //tickers_activity_daily_ratios
        $query = "INSERT INTO `tickers_activity_daily_ratios` (`ticker_id`, `AccountsPayableTurnoverDaysFY`, `TradeCycleDaysFY`, `TradeCycleDaysTTM`, `AccountsPayableTurnoverDaysTTM`, `InventoryTurnoverDaysFY`, `InventoryTurnoverDaysTTM`, `NetOperatingProfitafterTaxFQ`, `NetOperatingProfitafterTaxFY`, `NetOperatingProfitafterTaxTTM`, `ReceivablesCollectionPeriodDaysFY`, `ReceivablesCollectionPeriodDaysTTM`, `TaxRatePctFQ`, `TaxRatePctFY`, `TaxRatePctTTM`, `Volume`, `AverageVolume`, `Beta1Year`, `Beta3Year`, `Beta5Year`, `Date52WeekHigh`, `Date52WeekLow`, `DatePreviousClose`, `DatePriceClose`, `PreviousVolume`, `Price52WeekHigh`, `Price52WeekLow`, `PriceClose`, `PricePctChange13Week`, `PricePctChange1Day`, `PricePctChange1Week`, `PricePctChange26Week`, `PricePctChange4Week`, `PricePctChange52Week`, `PricePctChangeYTD`, `PricePreviousClose`) VALUES (";
        $query .= "'".$dates->ticker_id."',";
        $query .= $rawdata["AccountsPayableTurnoverDaysFY"][$treports].",";
        $query .= $rawdata["TradeCycleDaysFY"][$treports].",";
        $query .= $rawdata["TradeCycleDaysTTM"][$treports].",";
        $query .= $rawdata["AccountsPayableTurnoverDaysTTM"][$treports].",";
        $query .= $rawdata["InventoryTurnoverDaysFY"][$treports].",";
        $query .= $rawdata["InventoryTurnoverDaysTTM"][$treports].",";
        $query .= $rawdata["NetOperatingProfitafterTaxFQ"][$treports].",";
        $query .= $rawdata["NetOperatingProfitafterTaxFY"][$treports].",";
        $query .= $rawdata["NetOperatingProfitafterTaxTTM"][$treports].",";
        $query .= $rawdata["ReceivablesCollectionPeriodDaysFY"][$treports].",";
        $query .= $rawdata["ReceivablesCollectionPeriodDaysTTM"][$treports].",";
        $query .= $rawdata["TaxRatePctFQ"][$treports].",";
        $query .= $rawdata["TaxRatePctFY"][$treports].",";
        $query .= $rawdata["TaxRatePctTTM"][$treports].",";
        $query .= $rawdata["Volume"][$treports].",";
        $query .= $rawdata["AverageVolume"][$treports].",";
        $query .= $rawdata["Beta1Year"][$treports].",";
        $query .= $rawdata["Beta3Year"][$treports].",";
        $query .= $rawdata["Beta5Year"][$treports].",";
        $query .= "'".date("Y-m-d",strtotime($rawdata["Date52WeekHigh"][$treports]))."',";
        $query .= "'".date("Y-m-d",strtotime($rawdata["Date52WeekLow"][$treports]))."',";
        $query .= "'".date("Y-m-d",strtotime($rawdata["DatePreviousClose"][$treports]))."',";
        $query .= "'".date("Y-m-d",strtotime($rawdata["DatePriceClose"][$treports]))."',";
        $query .= $rawdata["PreviousVolume"][$treports].",";
        $query .= $rawdata["Price52WeekHigh"][$treports].",";
        $query .= $rawdata["Price52WeekLow"][$treports].",";
        $query .= $rawdata["PriceClose"][$treports].",";
        $query .= $rawdata["PricePctChange13Week"][$treports].",";
        $query .= $rawdata["PricePctChange1Day"][$treports].",";
        $query .= $rawdata["PricePctChange1Week"][$treports].",";
        $query .= $rawdata["PricePctChange26Week"][$treports].",";
        $query .= $rawdata["PricePctChange4Week"][$treports].",";
        $query .= $rawdata["PricePctChange52Week"][$treports].",";
        $query .= $rawdata["PricePctChangeYTD"][$treports].",";
        $query .= $rawdata["PricePreviousClose"][$treports];
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
        //tickers_growth_ratios
        $query = "INSERT INTO `tickers_growth_ratios` (`ticker_id`, `AdjustedEBITDAPctGrowth3YearCAGRFY`, `AdjustedEBITDAPctGrowth5YearCAGRFY`, `AdjustedEBITDAPctGrowthFY`, `AdjustedEBITDAPctGrowthTTM`, `EBITDAPctGrowth3YearCAGRFY`, `EBITDAPctGrowth5YearCAGRFY`, `EBITDAPctGrowthFY`, `EBITDAPctGrowthTTM`, `EBITPctGrowth3YearCAGRFY`, `EBITPctGrowth5YearCAGRFY`, `EBITPctGrowthFY`, `EBITPctGrowthTTM`, `FreeCashFlowPctGrowth3YearCAGRFY`, `FreeCashFlowPctGrowth5YearCAGRFY`, `FreeCashFlowPctGrowthFY`, `FreeCashFlowPctGrowthTTM`, `NetIncomePctGrowth3YearCAGRFY`, `NetIncomePctGrowth5YearCAGRFY`, `NetIncomePctGrowthFY`, `NetIncomePctGrowthTTM`, `OperatingCashFlowPctGrowth3YearCAGRFY`, `OperatingCashFlowPctGrowth5YearCAGRFY`, `OperatingCashFlowPctGrowthFY`, `OperatingCashFlowPctGrowthTTM`, `OperatingProfitPctGrowth3YearCAGRFY`, `OperatingProfitPctGrowth5YearCAGRFY`, `OperatingProfitPctGrowthFY`, `OperatingProfitPctGrowthTTM`, `PriceEarningstoGrowthFY`, `PriceEarningstoGrowthTTM`, `RevenuePctGrowth3YearCAGRFY`, `RevenuePctGrowth5YearCAGRFY`, `RevenuePctGrowthFY`, `RevenuePctGrowthTTM`) VALUES (";
        $query .= "'".$dates->ticker_id."',";
        $query .= $rawdata["AdjustedEBITDAPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITDAPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITDAPctGrowthFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITDAPctGrowthTTM"][$treports].",";
        $query .= $rawdata["EBITDAPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["EBITDAPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["EBITDAPctGrowthFY"][$treports].",";
        $query .= $rawdata["EBITDAPctGrowthTTM"][$treports].",";
        $query .= $rawdata["EBITPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["EBITPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["EBITPctGrowthFY"][$treports].",";
        $query .= $rawdata["EBITPctGrowthTTM"][$treports].",";
        $query .= $rawdata["FreeCashFlowPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["FreeCashFlowPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["FreeCashFlowPctGrowthFY"][$treports].",";
        $query .= $rawdata["FreeCashFlowPctGrowthTTM"][$treports].",";
        $query .= $rawdata["NetIncomePctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["NetIncomePctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["NetIncomePctGrowthFY"][$treports].",";
        $query .= $rawdata["NetIncomePctGrowthTTM"][$treports].",";
        $query .= $rawdata["OperatingCashFlowPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["OperatingCashFlowPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["OperatingCashFlowPctGrowthFY"][$treports].",";
        $query .= $rawdata["OperatingCashFlowPctGrowthTTM"][$treports].",";
        $query .= $rawdata["OperatingProfitPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["OperatingProfitPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["OperatingProfitPctGrowthFY"][$treports].",";
        $query .= $rawdata["OperatingProfitPctGrowthTTM"][$treports].",";
        $query .= $rawdata["PriceEarningstoGrowthFY"][$treports].",";
        $query .= $rawdata["PriceEarningstoGrowthTTM"][$treports].",";
        $query .= $rawdata["RevenuePctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["RevenuePctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["RevenuePctGrowthFY"][$treports].",";
        $query .= $rawdata["RevenuePctGrowthTTM"][$treports];
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
        //tickers_leverage_ratios
	$query = "INSERT INTO `tickers_leverage_ratios` (`ticker_id`, `TotalCapitalFY`, `TotalDebtFQ`, `TotalDebtFY`, `AltmanZscoreFY`, `AltmanZscoreTTM`, `BookEquityFQ`, `BookEquityFY`, `DebttoAssetsFQ`, `DebttoAssetsFY`, `DegreeofCombinedLeverageFY`, `DegreeofCombinedLeverageTTM`, `DegreeofFinancialLeverageFY`, `DegreeofFinancialLeverageTTM`, `DegreeofOperationalLeverageFY`, `DegreeofOperationalLeverageTTM`, `FreeCashFlowFQ`, `FreeCashFlowFY`, `FreeCashFlowtoEquityPctFY`, `FreeCashFlowtoEquityPctTTM`, `FreeCashFlowTTM`, `LongTermCapitalFQ`, `LongTermCapitalFY`, `LongTermDebttoLongTermCapitalFQ`, `LongTermDebttoLongTermCapitalFY`, `LongTermDebttoTotalCapitalFQ`, `LongTermDebttoTotalCapitalFY`, `NetDebtFQ`, `NetDebtFY`, `OperatingCashFlowFQ`, `OperatingCashFlowFY`, `OperatingCashFlowTTM`, `TotalCapitalFQ`) VALUES (";
        $query .= "'".$dates->ticker_id."',";
        $query .= $rawdata["TotalCapitalFY"][$treports].",";
        $query .= $rawdata["TotalDebtFQ"][$treports].",";
        $query .= $rawdata["TotalDebtFY"][$treports].",";
        $query .= $rawdata["AltmanZscoreFY"][$treports].",";
        $query .= $rawdata["AltmanZscoreTTM"][$treports].",";
        $query .= $rawdata["BookEquityFQ"][$treports].",";
        $query .= $rawdata["BookEquityFY"][$treports].",";
        $query .= $rawdata["DebttoAssetsFQ"][$treports].",";
        $query .= $rawdata["DebttoAssetsFY"][$treports].",";
        $query .= $rawdata["DegreeofCombinedLeverageFY"][$treports].",";
        $query .= $rawdata["DegreeofCombinedLeverageTTM"][$treports].",";
        $query .= $rawdata["DegreeofFinancialLeverageFY"][$treports].",";
        $query .= $rawdata["DegreeofFinancialLeverageTTM"][$treports].",";
        $query .= $rawdata["DegreeofOperationalLeverageFY"][$treports].",";
        $query .= $rawdata["DegreeofOperationalLeverageTTM"][$treports].",";
        $query .= $rawdata["FreeCashFlowFQ"][$treports].",";
        $query .= $rawdata["FreeCashFlowFY"][$treports].",";
        $query .= $rawdata["FreeCashFlowtoEquityPctFY"][$treports].",";
        $query .= $rawdata["FreeCashFlowtoEquityPctTTM"][$treports].",";
        $query .= $rawdata["FreeCashFlowTTM"][$treports].",";
        $query .= $rawdata["LongTermCapitalFQ"][$treports].",";
        $query .= $rawdata["LongTermCapitalFY"][$treports].",";
        $query .= $rawdata["LongTermDebttoLongTermCapitalFQ"][$treports].",";
        $query .= $rawdata["LongTermDebttoLongTermCapitalFY"][$treports].",";
        $query .= $rawdata["LongTermDebttoTotalCapitalFQ"][$treports].",";
        $query .= $rawdata["LongTermDebttoTotalCapitalFY"][$treports].",";
        $query .= $rawdata["NetDebtFQ"][$treports].",";
        $query .= $rawdata["NetDebtFY"][$treports].",";
        $query .= $rawdata["OperatingCashFlowFQ"][$treports].",";
        $query .= $rawdata["OperatingCashFlowFY"][$treports].",";
        $query .= $rawdata["OperatingCashFlowTTM"][$treports].",";
        $query .= $rawdata["TotalCapitalFQ"][$treports];
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
	//tickers_mini_ratios
	$query = "INSERT INTO `tickers_mini_ratios` (`ticker_id`, `DebttoEquityFQ`, `DebttoEquityFY`, `MarketCapBasic`, `MarketCapDiluted`, `MarketCapTSO`, `PriceBookFQ`, `PriceBookFY`, `PriceEarningsFY`, `PriceEarningsTTM`, `GrossMarginPctFQ`, `GrossMarginPctFY`, `GrossMarginPctTTM`, `OperatingMarginPctFQ`, `OperatingMarginPctFY`, `OperatingMarginPctTTM`, `CashRatioFQ`, `CashRatioFY`, `NetWorkingCapitalFQ`, `NetWorkingCapitalFY`, `CurrentRatioFQ`, `CurrentRatioFY`, `QuickRatioFQ`, `QuickRatioFY`) VALUES (";
        $query .= "'".$dates->ticker_id."',";
        $query .= $rawdata["DebttoEquityFQ"][$treports].",";
        $query .= $rawdata["DebttoEquityFY"][$treports].",";
        $query .= $rawdata["MarketCapBasic"][$treports].",";
        $query .= $rawdata["MarketCapDiluted"][$treports].",";
        $query .= $rawdata["MarketCapTSO"][$treports].",";
        $query .= $rawdata["PriceBookFQ"][$treports].",";
        $query .= $rawdata["PriceBookFY"][$treports].",";
        $query .= $rawdata["PriceEarningsFY"][$treports].",";
        $query .= $rawdata["PriceEarningsTTM"][$treports].",";
        $query .= $rawdata["GrossMarginPctFQ"][$treports].",";
        $query .= $rawdata["GrossMarginPctFY"][$treports].",";
        $query .= $rawdata["GrossMarginPctTTM"][$treports].",";
        $query .= $rawdata["OperatingMarginPctFQ"][$treports].",";
        $query .= $rawdata["OperatingMarginPctFY"][$treports].",";
        $query .= $rawdata["OperatingMarginPctTTM"][$treports].",";
        $query .= $rawdata["CashRatioFQ"][$treports].",";
        $query .= $rawdata["CashRatioFY"][$treports].",";
        $query .= $rawdata["NetWorkingCapitalFQ"][$treports].",";
        $query .= $rawdata["NetWorkingCapitalFY"][$treports].",";
        $query .= $rawdata["CurrentRatioFQ"][$treports].",";
        $query .= $rawdata["CurrentRatioFY"][$treports].",";
        $query .= $rawdata["QuickRatioFQ"][$treports].",";
        $query .= $rawdata["QuickRatioFY"][$treports];
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
	//tickers_profitability_ratios
	$query = "INSERT INTO `tickers_profitability_ratios` (`ticker_id`, `AdjustedEBITDAFQ`, `AdjustedEBITDAFY`, `AdjustedEBITDATTM`, `AdjustedEBITFQ`, `AdjustedEBITFY`, `AdjustedEBITPctGrowth3YearCAGRFY`, `AdjustedEBITPctGrowth5YearCAGRFY`, `AdjustedEBITPctGrowthFY`, `AdjustedEBITPctGrowthTTM`, `AdjustedEBITTTM`, `AdjustedNetIncomeFQ`, `AdjustedNetIncomeFY`, `AdjustedNetIncomePctGrowth3YearCAGRFY`, `AdjustedNetIncomePctGrowth5YearCAGRFY`, `AdjustedNetIncomePctGrowthFY`, `AdjustedNetIncomePctGrowthTTM`, `AdjustedNetIncomeTTM`, `AftertaxMarginPctFQ`, `AftertaxMarginPctFY`, `AftertaxMarginPctTTM`, `EBITDAFQ`, `EBITDAFY`, `EBITDATTM`, `EBITFQ`, `EBITFY`, `EBITTTM`, `FreeCashFlowMarginPctFQ`, `FreeCashFlowMarginPctFY`, `FreeCashFlowMarginPctTTM`, `FreeCashFlowReturnonAssetsPctFY`, `FreeCashFlowReturnonAssetsPctTTM`, `NetIncomeperEmployeeFY`, `NetIncomeperEmployeeTTM`, `PretaxMarginPctFQ`, `PretaxMarginPctFY`, `PretaxMarginPctTTM`, `ReturnonAssetsPctFY`, `ReturnonAssetsPctTTM`, `ReturnonEquityPctFY`, `ReturnonEquityPctTTM`, `ReturnonInvestedCapitalPctFY`, `ReturnonInvestedCapitalPctTTM`, `RevenueperEmployeeFY`, `RevenueperEmployeeTTM`) VALUES (";
        $query .= "'".$dates->ticker_id."',";
        $query .= $rawdata["AdjustedEBITDAFQ"][$treports].",";
        $query .= $rawdata["AdjustedEBITDAFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITDATTM"][$treports].",";
        $query .= $rawdata["AdjustedEBITFQ"][$treports].",";
        $query .= $rawdata["AdjustedEBITFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITPctGrowthFY"][$treports].",";
        $query .= $rawdata["AdjustedEBITPctGrowthTTM"][$treports].",";
        $query .= $rawdata["AdjustedEBITTTM"][$treports].",";
        $query .= $rawdata["AdjustedNetIncomeFQ"][$treports].",";
        $query .= $rawdata["AdjustedNetIncomeFY"][$treports].",";
        $query .= $rawdata["AdjustedNetIncomePctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedNetIncomePctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedNetIncomePctGrowthFY"][$treports].",";
        $query .= $rawdata["AdjustedNetIncomePctGrowthTTM"][$treports].",";
        $query .= $rawdata["AdjustedNetIncomeTTM"][$treports].",";
        $query .= $rawdata["AftertaxMarginPctFQ"][$treports].",";
        $query .= $rawdata["AftertaxMarginPctFY"][$treports].",";
        $query .= $rawdata["AftertaxMarginPctTTM"][$treports].",";
        $query .= $rawdata["EBITDAFQ"][$treports].",";
        $query .= $rawdata["EBITDAFY"][$treports].",";
        $query .= $rawdata["EBITDATTM"][$treports].",";
        $query .= $rawdata["EBITFQ"][$treports].",";
        $query .= $rawdata["EBITFY"][$treports].",";
        $query .= $rawdata["EBITTTM"][$treports].",";
        $query .= $rawdata["FreeCashFlowMarginPctFQ"][$treports].",";
        $query .= $rawdata["FreeCashFlowMarginPctFY"][$treports].",";
        $query .= $rawdata["FreeCashFlowMarginPctTTM"][$treports].",";
        $query .= $rawdata["FreeCashFlowReturnonAssetsPctFY"][$treports].",";
        $query .= $rawdata["FreeCashFlowReturnonAssetsPctTTM"][$treports].",";
        $query .= $rawdata["NetIncomeperEmployeeFY"][$treports].",";
        $query .= $rawdata["NetIncomeperEmployeeTTM"][$treports].",";
        $query .= $rawdata["PretaxMarginPctFQ"][$treports].",";
        $query .= $rawdata["PretaxMarginPctFY"][$treports].",";
        $query .= $rawdata["PretaxMarginPctTTM"][$treports].",";
        $query .= $rawdata["ReturnonAssetsPctFY"][$treports].",";
        $query .= $rawdata["ReturnonAssetsPctTTM"][$treports].",";
        $query .= $rawdata["ReturnonEquityPctFY"][$treports].",";
        $query .= $rawdata["ReturnonEquityPctTTM"][$treports].",";
        $query .= $rawdata["ReturnonInvestedCapitalPctFY"][$treports].",";
        $query .= $rawdata["ReturnonInvestedCapitalPctTTM"][$treports].",";
        $query .= $rawdata["RevenueperEmployeeFY"][$treports].",";
        $query .= $rawdata["RevenueperEmployeeTTM"][$treports];
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
	//tickers_valuation_ratios
	$query = "INSERT INTO `tickers_valuation_ratios` (`ticker_id`, `TotalEquityFQ`, `TotalEquityFY`, `EarningsperShareNormalizedDilutedFQ`, `EarningsperShareNormalizedDilutedFY`, `EarningsperShareNormalizedDilutedTTM`, `AdjustedEPSDilutedPctGrowth3YearCAGRFY`, `AdjustedEPSDilutedPctGrowth5YearCAGRFY`, `AdjustedEPSDilutedPctGrowthFY`, `AdjustedEPSDilutedPctGrowthTTM`, `BasicAverageShares`, `DilutedAverageShares`, `DividendsperShareFQ`, `DividendsperShareFY`, `DividendsperShareTTM`, `EarningsperShareBasicFQ`, `EarningsperShareBasicFY`, `EarningsperShareBasicTTM`, `EarningsperShareDilutedFQ`, `EarningsperShareDilutedFY`, `EarningsperShareDilutedTTM`, `EnterpriseValueEBITDAFY`, `EnterpriseValueEBITDATTM`, `EnterpriseValueEBITFY`, `EnterpriseValueEBITTTM`, `EnterpriseValueFQ`, `EnterpriseValueFY`, `ExpectedAnnualDividends`, `PriceBookExclIntangiblesFQ`, `PriceBookExclIntangiblesFY`, `PriceEarningsNormalizedFY`, `PriceEarningsNormalizedTTM`, `PriceFreeCashFlowFY`, `PriceFreeCashFlowTTM`, `PriceRevenueFY`, `PriceRevenueTTM`) VALUES (";
        $query .= "'".$dates->ticker_id."',";
        $query .= $rawdata["TotalEquityFQ"][$treports].",";
        $query .= $rawdata["TotalEquityFY"][$treports].",";
        $query .= $rawdata["EarningsperShareNormalizedDilutedFQ"][$treports].",";
        $query .= $rawdata["EarningsperShareNormalizedDilutedFY"][$treports].",";
        $query .= $rawdata["EarningsperShareNormalizedDilutedTTM"][$treports].",";
        $query .= $rawdata["AdjustedEPSDilutedPctGrowth3YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedEPSDilutedPctGrowth5YearCAGRFY"][$treports].",";
        $query .= $rawdata["AdjustedEPSDilutedPctGrowthFY"][$treports].",";
        $query .= $rawdata["AdjustedEPSDilutedPctGrowthTTM"][$treports].",";
        $query .= $rawdata["BasicAverageShares"][$treports].",";
        $query .= $rawdata["DilutedAverageShares"][$treports].",";
        $query .= $rawdata["DividendsperShareFQ"][$treports].",";
        $query .= $rawdata["DividendsperShareFY"][$treports].",";
        $query .= $rawdata["DividendsperShareTTM"][$treports].",";
        $query .= $rawdata["EarningsperShareBasicFQ"][$treports].",";
        $query .= $rawdata["EarningsperShareBasicFY"][$treports].",";
        $query .= $rawdata["EarningsperShareBasicTTM"][$treports].",";
        $query .= $rawdata["EarningsperShareDilutedFQ"][$treports].",";
        $query .= $rawdata["EarningsperShareDilutedFY"][$treports].",";
        $query .= $rawdata["EarningsperShareDilutedTTM"][$treports].",";
        $query .= $rawdata["EnterpriseValueEBITDAFY"][$treports].",";
        $query .= $rawdata["EnterpriseValueEBITDATTM"][$treports].",";
        $query .= $rawdata["EnterpriseValueEBITFY"][$treports].",";
        $query .= $rawdata["EnterpriseValueEBITTTM"][$treports].",";
        $query .= $rawdata["EnterpriseValueFQ"][$treports].",";
        $query .= $rawdata["EnterpriseValueFY"][$treports].",";
        $query .= $rawdata["ExpectedAnnualDividends"][$treports].",";
        $query .= $rawdata["PriceBookExclIntangiblesFQ"][$treports].",";
        $query .= $rawdata["PriceBookExclIntangiblesFY"][$treports].",";
        $query .= $rawdata["PriceEarningsNormalizedFY"][$treports].",";
        $query .= $rawdata["PriceEarningsNormalizedTTM"][$treports].",";
        $query .= $rawdata["PriceFreeCashFlowFY"][$treports].",";
        $query .= $rawdata["PriceFreeCashFlowTTM"][$treports].",";
        $query .= $rawdata["PriceRevenueFY"][$treports].",";
        $query .= $rawdata["PriceRevenueTTM"][$treports];
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
	//tickers_metadata_eol
	$query = "INSERT INTO `tickers_metadata_eol` (`ticker_id`, `TotalSharesOutstandingDate`, `BusinessDescription`, `CITY`, `Country`, `Formername`, `Industry`, `InvRelationsEmail`, `LastAnnualEPS`, `LastAnnualNetIncome`, `LastAnnualRevenue`, `LastAnnualTotalAssets`, `PhoneAreaCode`, `PhoneCountryCode`, `PhoneNumber`, `PublicFloat`, `PublicFloatDate`, `Sector`, `State`, `StateofIncorporation`, `StreetAddress1`, `StreetAddress2`, `TaxID`, `WebSiteURL`, `ZipCode`) VALUES (";
        $query .= "'".$dates->ticker_id."',";
        $query .= "'".date("Y-m-d",strtotime($rawdata["TotalSharesOutstandingDate"][$treports]))."',";
        $query .= ($rawdata["BusinessDescription"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["BusinessDescription"][$treports])."',");
        $query .= ($rawdata["CITY"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["CITY"][$treports])."',");
        $query .= ($rawdata["Country"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["Country"][$treports])."',");
        $query .= ($rawdata["Formername"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["Formername"][$treports])."',");
        $query .= ($rawdata["Industry"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["Industry"][$treports])."',");
        $query .= ($rawdata["InvRelationsEmail"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["InvRelationsEmail"][$treports])."',");
        $query .= $rawdata["LastAnnualEPS"][$treports].",";
        $query .= $rawdata["LastAnnualNetIncome"][$treports].",";
        $query .= $rawdata["LastAnnualRevenue"][$treports].",";
        $query .= $rawdata["LastAnnualTotalAssets"][$treports].",";
        $query .= ($rawdata["PhoneAreaCode"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["PhoneAreaCode"][$treports])."',");
        $query .= ($rawdata["PhoneCountryCode"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["PhoneCountryCode"][$treports])."',");
        $query .= ($rawdata["PhoneNumber"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["PhoneNumber"][$treports])."',");
        $query .= $rawdata["PublicFloat"][$treports].",";
        $query .= "'".date("Y-m-d",strtotime($rawdata["PublicFloatDate"][$treports]))."',";
        $query .= ($rawdata["Sector"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["Sector"][$treports])."',");
        $query .= ($rawdata["State"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["State"][$treports])."',");
        $query .= ($rawdata["StateofIncorporation"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["StateofIncorporation"][$treports])."',");
        $query .= ($rawdata["StreetAddress1"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["StreetAddress1"][$treports])."',");
        $query .= ($rawdata["StreetAddress2"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["StreetAddress2"][$treports])."',");
        $query .= ($rawdata["TaxID"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["TaxID"][$treports])."',");
        $query .= ($rawdata["WebSiteURL"][$treports]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["WebSiteURL"][$treports])."',");
        $query .= ($rawdata["ZipCode"][$treports]=='null' ? 'null':"'".mysql_real_escape_string($rawdata["ZipCode"][$treports])."'");
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());

        //Update reports_* tables
        foreach($report_tables as $table) {
                $query = "DELETE FROM $table WHERE report_id IN (SELECT id FROM reports_header WHERE ticker_id = ".$dates->ticker_id.")";
                mysql_query($query) or die (mysql_error());
        }
        $query = "DELETE FROM reports_header WHERE ticker_id = ".$dates->ticker_id;
        mysql_query($query) or die (mysql_error());

	for($i=1; $i<=$treports; $i++) {
	    if (!is_numeric($rawdata["duration"][$i])) {
        	//reports_header
		$query = "INSERT IGNORE INTO `reports_header` (`report_type`, `report_date`, `ticker_id`, `fiscal_year`, `fiscal_quarter`) VALUES (";
        	$query .= "'".$rawdata["duration"][$i]."',";
        	$query .= "'".date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]))."',";
        	$query .= "'".$dates->ticker_id."',";
        	$query .= "'".$rawdata["fiscalYear"][$i]."',";
        	$query .= "'".$rawdata["FiscalQuarter"][$i]."'";
        	$query .= ")";
	        mysql_query($query) or die ($query."\n".mysql_error());
		if (mysql_affected_rows()>0) {
			$report_id = mysql_insert_id();
			//reports_balanceconsolidated
			$query = "INSERT INTO `reports_balanceconsolidated` (`report_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["CommitmentsContingencies"][$i].",";
        		$query .= $rawdata["CommonStock"][$i].",";
	        	$query .= $rawdata["DeferredCharges"][$i].",";
        		$query .= $rawdata["DeferredIncomeTaxesCurrent"][$i].",";
        		$query .= $rawdata["DeferredIncomeTaxesLongterm"][$i].",";
	        	$query .= $rawdata["AccountsPayableandAccruedExpenses"][$i].",";
        		$query .= $rawdata["AccruedInterest"][$i].",";
        		$query .= $rawdata["AdditionalPaidinCapital"][$i].",";
	        	$query .= $rawdata["AdditionalPaidinCapitalPreferredStock"][$i].",";
        		$query .= $rawdata["CashandCashEquivalents"][$i].",";
        		$query .= $rawdata["CashCashEquivalentsandShorttermInvestments"][$i].",";
	        	$query .= $rawdata["Goodwill"][$i].",";
        		$query .= $rawdata["IntangibleAssets"][$i].",";
        		$query .= $rawdata["InventoriesNet"][$i].",";
	        	$query .= $rawdata["LongtermDeferredIncomeTaxLiabilities"][$i].",";
        		$query .= $rawdata["LongtermDeferredLiabilityCharges"][$i].",";
        		$query .= $rawdata["LongtermInvestments"][$i].",";
	        	$query .= $rawdata["MinorityInterest"][$i].",";
        		$query .= $rawdata["OtherAccumulatedComprehensiveIncome"][$i].",";
        		$query .= $rawdata["OtherAssets"][$i].",";
	        	$query .= $rawdata["OtherCurrentAssets"][$i].",";
        		$query .= $rawdata["OtherCurrentLiabilities"][$i].",";
        		$query .= $rawdata["OtherEquity"][$i].",";
	        	$query .= $rawdata["OtherInvestments"][$i].",";
        		$query .= $rawdata["OtherLiabilities"][$i].",";
        		$query .= $rawdata["PartnersCapital"][$i].",";
	        	$query .= $rawdata["PensionPostretirementObligation"][$i].",";
        		$query .= $rawdata["PreferredStock"][$i].",";
        		$query .= $rawdata["PrepaidExpenses"][$i].",";
	        	$query .= $rawdata["PropertyPlantEquipmentNet"][$i].",";
        		$query .= $rawdata["RestrictedCash"][$i].",";
        		$query .= $rawdata["RetainedEarnings"][$i].",";
	        	$query .= $rawdata["TemporaryEquity"][$i].",";
        		$query .= $rawdata["TotalAssets"][$i].",";
        		$query .= $rawdata["TotalCurrentAssets"][$i].",";
	        	$query .= $rawdata["TotalCurrentLiabilities"][$i].",";
        		$query .= $rawdata["TotalLiabilities"][$i].",";
        		$query .= $rawdata["TotalLongtermDebt"][$i].",";
	        	$query .= $rawdata["TotalReceivablesNet"][$i].",";
        		$query .= $rawdata["TotalShorttermDebt"][$i].",";
        		$query .= $rawdata["TotalStockholdersEquity"][$i].",";
	        	$query .= $rawdata["TreasuryStock"][$i]."";
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_balanceconsolidated CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("CommitmentsContingencies", "CommonStock", "DeferredCharges", "DeferredIncomeTaxesCurrent", "DeferredIncomeTaxesLongterm", "AccountsPayableandAccruedExpenses", "AccruedInterest", "AdditionalPaidinCapital", "AdditionalPaidinCapitalPreferredStock", "CashandCashEquivalents", "CashCashEquivalentsandShorttermInvestments", "Goodwill", "IntangibleAssets", "InventoriesNet", "LongtermDeferredIncomeTaxLiabilities", "LongtermDeferredLiabilityCharges", "LongtermInvestments", "MinorityInterest", "OtherAccumulatedComprehensiveIncome", "OtherAssets", "OtherCurrentAssets", "OtherCurrentLiabilities", "OtherEquity", "OtherInvestments", "OtherLiabilities", "PartnersCapital", "PensionPostretirementObligation", "PreferredStock", "PrepaidExpenses", "PropertyPlantEquipmentNet", "RestrictedCash", "RetainedEarnings", "TemporaryEquity", "TotalAssets", "TotalCurrentAssets", "TotalCurrentLiabilities", "TotalLiabilities", "TotalLongtermDebt", "TotalReceivablesNet", "TotalShorttermDebt", "TotalStockholdersEquity", "TreasuryStock");
					updateCAGR("reports_balanceconsolidated_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
					if ($i > 5) {
						updateCAGR("reports_balanceconsolidated_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
					}
					if ($i > 7) {
						updateCAGR("reports_balanceconsolidated_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
					}
					if ($i > 10) {
						updateCAGR("reports_balanceconsolidated_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
					}
                                }
                        }

			//reports_balancefull
			$query = "INSERT INTO `reports_balancefull` (`report_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["TotalDebt"][$i].",";
        		$query .= $rawdata["TotalAssetsFQ"][$i].",";
        		$query .= $rawdata["TotalAssetsFY"][$i].",";
        		$query .= $rawdata["CurrentPortionofLongtermDebt"][$i].",";
        		$query .= $rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$i].",";
        		$query .= $rawdata["DeferredLiabilityCharges"][$i].",";
        		$query .= $rawdata["AccountsNotesReceivableNet"][$i].",";
        		$query .= $rawdata["AccountsPayable"][$i].",";
        		$query .= $rawdata["AccountsReceivableTradeNet"][$i].",";
        		$query .= $rawdata["AccruedExpenses"][$i].",";
        		$query .= $rawdata["AccumulatedDepreciation"][$i].",";
        		$query .= $rawdata["AmountsDuetoRelatedPartiesShortterm"][$i].",";
        		$query .= $rawdata["GoodwillIntangibleAssetsNet"][$i].",";
        		$query .= $rawdata["IncomeTaxesPayable"][$i].",";
        		$query .= $rawdata["LiabilitiesStockholdersEquity"][$i].",";
        		$query .= $rawdata["LongtermDebt"][$i].",";
        		$query .= $rawdata["NotesPayable"][$i].",";
        		$query .= $rawdata["OperatingLeases"][$i].",";
        		$query .= $rawdata["OtherAccountsNotesReceivable"][$i].",";
        		$query .= $rawdata["OtherAccountsPayableandAccruedExpenses"][$i].",";
        		$query .= $rawdata["OtherBorrowings"][$i].",";
        		$query .= $rawdata["OtherReceivables"][$i].",";
        		$query .= $rawdata["PropertyandEquipmentGross"][$i].",";
        		$query .= $rawdata["TotalLongtermAssets"][$i].",";
        		$query .= $rawdata["TotalLongtermLiabilities"][$i].",";
        		$query .= $rawdata["TotalSharesOutstanding"][$i].",";
			$query .= $rawdata["ShorttermInvestments"][$i];
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_balancefull CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("TotalDebt", "TotalAssetsFQ", "TotalAssetsFY", "CurrentPortionofLongtermDebt", "DeferredIncomeTaxLiabilitiesShortterm", "DeferredLiabilityCharges", "AccountsNotesReceivableNet", "AccountsPayable", "AccountsReceivableTradeNet", "AccruedExpenses", "AccumulatedDepreciation", "AmountsDuetoRelatedPartiesShortterm", "GoodwillIntangibleAssetsNet", "IncomeTaxesPayable", "LiabilitiesStockholdersEquity", "LongtermDebt", "NotesPayable", "OperatingLeases", "OtherAccountsNotesReceivable", "OtherAccountsPayableandAccruedExpenses", "OtherBorrowings", "OtherReceivables", "PropertyandEquipmentGross", "TotalLongtermAssets", "TotalLongtermLiabilities", "TotalSharesOutstanding", "ShorttermInvestments");
					updateCAGR("reports_balancefull_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
					if ($i > 5) {
						updateCAGR("reports_balancefull_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
					}
					if ($i > 7) {
						updateCAGR("reports_balancefull_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
					}
					if ($i > 10) {
						updateCAGR("reports_balancefull_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
					}
                                }
                        }

			//reports_cashflowconsolidated
			$query = "INSERT INTO `reports_cashflowconsolidated` (`report_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["ChangeinCurrentAssets"][$i].",";
        		$query .= $rawdata["ChangeinCurrentLiabilities"][$i].",";
        		$query .= $rawdata["ChangeinDebtNet"][$i].",";
        		$query .= $rawdata["ChangeinDeferredRevenue"][$i].",";
        		$query .= $rawdata["ChangeinEquityNet"][$i].",";
        		$query .= $rawdata["ChangeinIncomeTaxesPayable"][$i].",";
        		$query .= $rawdata["ChangeinInventories"][$i].",";
        		$query .= $rawdata["ChangeinOperatingAssetsLiabilities"][$i].",";
        		$query .= $rawdata["ChangeinOtherAssets"][$i].",";
        		$query .= $rawdata["ChangeinOtherCurrentAssets"][$i].",";
        		$query .= $rawdata["ChangeinOtherCurrentLiabilities"][$i].",";
        		$query .= $rawdata["ChangeinOtherLiabilities"][$i].",";
        		$query .= $rawdata["ChangeinPrepaidExpenses"][$i].",";
        		$query .= $rawdata["DividendsPaid"][$i].",";
        		$query .= $rawdata["EffectofExchangeRateonCash"][$i].",";
        		$query .= $rawdata["EmployeeCompensation"][$i].",";
        		$query .= $rawdata["AcquisitionSaleofBusinessNet"][$i].",";
        		$query .= $rawdata["AdjustmentforEquityEarnings"][$i].",";
        		$query .= $rawdata["AdjustmentforMinorityInterest"][$i].",";
        		$query .= $rawdata["AdjustmentforSpecialCharges"][$i].",";
        		$query .= $rawdata["CapitalExpenditures"][$i].",";
        		$query .= $rawdata["CashfromDiscontinuedOperations"][$i].",";
        		$query .= $rawdata["CashfromFinancingActivities"][$i].",";
        		$query .= $rawdata["CashfromInvestingActivities"][$i].",";
        		$query .= $rawdata["CashfromOperatingActivities"][$i].",";
        		$query .= $rawdata["CFDepreciationAmortization"][$i].",";
        		$query .= $rawdata["DeferredIncomeTaxes"][$i].",";
        		$query .= $rawdata["ChangeinAccountsPayableAccruedExpenses"][$i].",";
        		$query .= $rawdata["ChangeinAccountsReceivable"][$i].",";
        		$query .= $rawdata["InvestmentChangesNet"][$i].",";
        		$query .= $rawdata["NetChangeinCash"][$i].",";
        		$query .= $rawdata["OtherAdjustments"][$i].",";
        		$query .= $rawdata["OtherAssetLiabilityChangesNet"][$i].",";
        		$query .= $rawdata["OtherFinancingActivitiesNet"][$i].",";
        		$query .= $rawdata["OtherInvestingActivities"][$i].",";
        		$query .= $rawdata["RealizedGainsLosses"][$i].",";
        		$query .= $rawdata["SaleofPropertyPlantEquipment"][$i].",";
        		$query .= $rawdata["StockOptionTaxBenefits"][$i].",";
        		$query .= $rawdata["TotalAdjustments"][$i];
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_cashflowconsolidated CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("ChangeinCurrentAssets", "ChangeinCurrentLiabilities", "ChangeinDebtNet", "ChangeinDeferredRevenue", "ChangeinEquityNet", "ChangeinIncomeTaxesPayable", "ChangeinInventories", "ChangeinOperatingAssetsLiabilities", "ChangeinOtherAssets", "ChangeinOtherCurrentAssets", "ChangeinOtherCurrentLiabilities", "ChangeinOtherLiabilities", "ChangeinPrepaidExpenses", "DividendsPaid", "EffectofExchangeRateonCash", "EmployeeCompensation", "AcquisitionSaleofBusinessNet", "AdjustmentforEquityEarnings", "AdjustmentforMinorityInterest", "AdjustmentforSpecialCharges", "CapitalExpenditures", "CashfromDiscontinuedOperations", "CashfromFinancingActivities", "CashfromInvestingActivities", "CashfromOperatingActivities", "CFDepreciationAmortization", "DeferredIncomeTaxes", "ChangeinAccountsPayableAccruedExpenses", "ChangeinAccountsReceivable", "InvestmentChangesNet", "NetChangeinCash", "OtherAdjustments", "OtherAssetLiabilityChangesNet", "OtherFinancingActivitiesNet", "OtherInvestingActivities", "RealizedGainsLosses", "SaleofPropertyPlantEquipment", "StockOptionTaxBenefits", "TotalAdjustments");
					updateCAGR("reports_cashflowconsolidated_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
					if ($i > 5) {
						updateCAGR("reports_cashflowconsolidated_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
					}
					if ($i > 7) {
						updateCAGR("reports_cashflowconsolidated_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
					}
					if ($i > 10) {
						updateCAGR("reports_cashflowconsolidated_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
					}
                                }
                        }

			//reports_cashflowfull
			$query = "INSERT INTO `reports_cashflowfull` (`report_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["ChangeinLongtermDebtNet"][$i].",";
        		$query .= $rawdata["ChangeinShorttermBorrowingsNet"][$i].",";
        		$query .= $rawdata["CashandCashEquivalentsBeginningofYear"][$i].",";
        		$query .= $rawdata["CashandCashEquivalentsEndofYear"][$i].",";
        		$query .= $rawdata["CashPaidforIncomeTaxes"][$i].",";
        		$query .= $rawdata["CashPaidforInterestExpense"][$i].",";
        		$query .= $rawdata["CFNetIncome"][$i].",";
        		$query .= $rawdata["IssuanceofEquity"][$i].",";
        		$query .= $rawdata["LongtermDebtPayments"][$i].",";
        		$query .= $rawdata["LongtermDebtProceeds"][$i].",";
        		$query .= $rawdata["OtherDebtNet"][$i].",";
        		$query .= $rawdata["OtherEquityTransactionsNet"][$i].",";
        		$query .= $rawdata["OtherInvestmentChangesNet"][$i].",";
        		$query .= $rawdata["PurchaseofInvestments"][$i].",";
        		$query .= $rawdata["RepurchaseofEquity"][$i].",";
        		$query .= $rawdata["SaleofInvestments"][$i].",";
        		$query .= $rawdata["ShorttermBorrowings"][$i].",";
        		$query .= $rawdata["TotalNoncashAdjustments"][$i];
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_cashflowfull CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("ChangeinLongtermDebtNet", "ChangeinShorttermBorrowingsNet", "CashandCashEquivalentsBeginningofYear", "CashandCashEquivalentsEndofYear", "CashPaidforIncomeTaxes", "CashPaidforInterestExpense", "CFNetIncome", "IssuanceofEquity", "LongtermDebtPayments", "LongtermDebtProceeds", "OtherDebtNet", "OtherEquityTransactionsNet", "OtherInvestmentChangesNet", "PurchaseofInvestments", "RepurchaseofEquity", "SaleofInvestments", "ShorttermBorrowings", "TotalNoncashAdjustments");
					updateCAGR("reports_cashflowfull_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
					if ($i > 5) {
						updateCAGR("reports_cashflowfull_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
					}
					if ($i > 7) {
						updateCAGR("reports_cashflowfull_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
					}
					if ($i > 10) {
						updateCAGR("reports_cashflowfull_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
					}
                                }
                        }

			//reports_financialheader
			$query = "INSERT INTO `reports_financialheader` (`report_id`, `USDConversionRate`, `Restated`, `ReceivedDate`, `Preliminary`, `PeriodLengthCode`, `PeriodLength`, `Original`, `FormType`, `FiledDate`, `DCN`, `CurrencyCode`, `CrossCalculated`, `Audited`, `Amended`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["USDConversionRate"][$i].",";
        		$query .= "'".($rawdata["Restated"][$i] == "false" ? 0 : 1)."',";
        		$query .= "'".date("Y-m-d",strtotime($rawdata["ReceivedDate"][$i]))."',";
        		$query .= "'".($rawdata["Preliminary"][$i] == "false" ? 0 : 1)."',";
        		$query .= ($rawdata["PeriodLengthCode"][$i]=='null' ? 'null,':"'".$rawdata["PeriodLengthCode"][$i]."',");
        		$query .= $rawdata["PeriodLength"][$i].",";
        		$query .= "'".($rawdata["Original"][$i] == "false" ? 0 : 1)."',";
        		$query .= ($rawdata["FormType"][$i]=='null' ? 'null,':"'".$rawdata["FormType"][$i]."',");
        		$query .= "'".date("Y-m-d",strtotime($rawdata["FiledDate"][$i]))."',";
        		$query .= ($rawdata["DCN"][$i]=='null' ? 'null,':"'".$rawdata["DCN"][$i]."',");
        		$query .= ($rawdata["CurrencyCode"][$i]=='null' ? 'null,':"'".$rawdata["CurrencyCode"][$i]."',");
        		$query .= "'".($rawdata["CrossCalculated"][$i] == "false" ? 0 : 1)."',";
        		$query .= "'".($rawdata["Audited"][$i] == "false" ? 0 : 1)."',";
        		$query .= "'".($rawdata["Amended"][$i] == "false" ? 0 : 1)."'";
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_gf_data
			$query = "INSERT INTO `reports_gf_data` (`report_id`, `fiscalPeriod_eol`, `fiscalPeriod_gf`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= ($rawdata["fiscalPeriod"][$i]=='null' ? 'null,':"'".$rawdata["fiscalPeriod"][$i]."',");
        		$query .= ($rawdata["FiscalPeriod"][$i]=='null' ? 'null,':"'".$rawdata["FiscalPeriod"][$i]."',");
        		$query .= toFloat($rawdata["InterestIncome"][$i]).",";
        		$query .= toFloat($rawdata["InterestExpense"][$i]).",";
        		$query .= toFloat($rawdata["EPSBasic"][$i]).",";
        		$query .= toFloat($rawdata["EPSDiluted"][$i]).",";
        		$query .= toFloat($rawdata["SharesOutstandingDiluted"][$i]).",";
        		$query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$i]).",";
        		$query .= toFloat($rawdata["InventoriesWorkInProcess"][$i]).",";
        		$query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$i]).",";
        		$query .= toFloat($rawdata["InventoriesFinishedGoods"][$i]).",";
        		$query .= toFloat($rawdata["InventoriesOther"][$i]).",";
        		$query .= toFloat($rawdata["TotalInventories"][$i]).",";
        		$query .= toFloat($rawdata["LandAndImprovements"][$i]).",";
        		$query .= toFloat($rawdata["BuildingsAndImprovements"][$i]).",";
        		$query .= toFloat($rawdata["MachineryFurnitureEquipment"][$i]).",";
        		$query .= toFloat($rawdata["ConstructionInProgress"][$i]).",";
        		$query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$i]).",";
        		$query .= toFloat($rawdata["SharesOutstandingBasic"][$i]);
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_gf_data CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("InterestIncome", "InterestExpense", "EPSBasic", "EPSDiluted", "SharesOutstandingDiluted", "InventoriesRawMaterialsComponents", "InventoriesWorkInProcess", "InventoriesInventoriesAdjustments", "InventoriesFinishedGoods", "InventoriesOther", "TotalInventories", "LandAndImprovements", "BuildingsAndImprovements", "MachineryFurnitureEquipment", "ConstructionInProgress", "GrossPropertyPlantandEquipment", "SharesOutstandingBasic");
					updateCAGR("reports_gf_data_3cagr", $fieldArray, 3, $i, $report_id, $rawdata, true);
					if ($i > 5) {
						updateCAGR("reports_gf_data_5cagr", $fieldArray, 5, $i, $report_id, $rawdata, true);
					}
					if ($i > 7) {
						updateCAGR("reports_gf_data_7cagr", $fieldArray, 7, $i, $report_id, $rawdata, true);
					}
					if ($i > 10) {
						updateCAGR("reports_gf_data_10cagr", $fieldArray, 10, $i, $report_id, $rawdata, true);
					}
                                }
                        }

			//reports_incomeconsolidated
			$query = "INSERT INTO `reports_incomeconsolidated` (`report_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
	        	$query .= "'".$report_id."',";
			if ($rawdata["EBIT"][$i] === "null" && $rawdata["OperatingProfit"][$i] !== "null") {
				$rawdata["EBIT"][$i] = $rawdata["OperatingProfit"][$i];
			}
	        	$query .= $rawdata["EBIT"][$i].",";
        		$query .= $rawdata["CostofRevenue"][$i].",";
        		$query .= $rawdata["DepreciationAmortizationExpense"][$i].",";
        		$query .= $rawdata["DilutedEPSNetIncome"][$i].",";
        		$query .= $rawdata["DiscontinuedOperations"][$i].",";
        		$query .= $rawdata["EquityEarnings"][$i].",";
        		$query .= $rawdata["AccountingChange"][$i].",";
        		$query .= $rawdata["BasicEPSNetIncome"][$i].",";
        		$query .= $rawdata["ExtraordinaryItems"][$i].",";
        		$query .= $rawdata["GrossProfit"][$i].",";
        		$query .= $rawdata["IncomebeforeExtraordinaryItems"][$i].",";
        		$query .= $rawdata["IncomeBeforeTaxes"][$i].",";
        		$query .= $rawdata["IncomeTaxes"][$i].",";
        		$query .= toFloat($rawdata["InterestExpense"][$i]).",";
        		$query .= toFloat($rawdata["InterestIncome"][$i]).",";
        		$query .= $rawdata["MinorityInterestEquityEarnings"][$i].",";
        		$query .= $rawdata["NetIncome"][$i].",";
        		$query .= $rawdata["NetIncomeApplicabletoCommon"][$i].",";
        		$query .= $rawdata["OperatingProfit"][$i].",";
        		$query .= $rawdata["OtherNonoperatingIncomeExpense"][$i].",";
        		$query .= $rawdata["OtherOperatingExpenses"][$i].",";
        		$query .= $rawdata["ResearchDevelopmentExpense"][$i].",";
        		$query .= $rawdata["RestructuringRemediationImpairmentProvisions"][$i].",";
        		$query .= $rawdata["TotalRevenue"][$i].",";
        		$query .= $rawdata["SellingGeneralAdministrativeExpenses"][$i];
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_incomeconsolidated CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("EBIT", "CostofRevenue", "DepreciationAmortizationExpense", "DilutedEPSNetIncome", "DiscontinuedOperations", "EquityEarnings", "AccountingChange", "BasicEPSNetIncome", "ExtraordinaryItems", "GrossProfit", "IncomebeforeExtraordinaryItems", "IncomeBeforeTaxes", "IncomeTaxes", "InterestExpense", "InterestIncome", "MinorityInterestEquityEarnings", "NetIncome", "NetIncomeApplicabletoCommon", "OperatingProfit", "OtherNonoperatingIncomeExpense", "OtherOperatingExpenses", "ResearchDevelopmentExpense", "RestructuringRemediationImpairmentProvisions", "TotalRevenue", "SellingGeneralAdministrativeExpenses");
					updateCAGR("reports_incomeconsolidated_3cagr", $fieldArray, 3, $i, $report_id, $rawdata, true);
					if ($i > 5) {
						updateCAGR("reports_incomeconsolidated_5cagr", $fieldArray, 5, $i, $report_id, $rawdata, true);
					}
					if ($i > 7) {
						updateCAGR("reports_incomeconsolidated_7cagr", $fieldArray, 7, $i, $report_id, $rawdata, true);
					}
					if ($i > 10) {
						updateCAGR("reports_incomeconsolidated_10cagr", $fieldArray, 10, $i, $report_id, $rawdata, true);
					}
                                }
                        }

			//reports_incomefull
			$query = "INSERT INTO `reports_incomefull` (`report_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["AdjustedEBIT"][$i].",";
        		$query .= $rawdata["AdjustedEBITDA"][$i].",";
        		$query .= $rawdata["AdjustedNetIncome"][$i].",";
        		$query .= $rawdata["AftertaxMargin"][$i].",";
			if ($rawdata["EBITDA"][$i] === "null" && $rawdata["OperatingProfit"][$i] !== "null") {
                                $rawdata["EBITDA"][$i] = $rawdata["OperatingProfit"][$i] + $rawdata["DepreciationAmortizationExpense"][$i];
                        }
        		$query .= $rawdata["EBITDA"][$i].",";
        		$query .= $rawdata["GrossMargin"][$i].",";
        		$query .= $rawdata["NetOperatingProfitafterTax"][$i].",";
        		$query .= $rawdata["OperatingMargin"][$i].",";
        		$query .= $rawdata["RevenueFQ"][$i].",";
        		$query .= $rawdata["RevenueFY"][$i].",";
        		$query .= $rawdata["RevenueTTM"][$i].",";
        		$query .= $rawdata["CostOperatingExpenses"][$i].",";
        		$query .= $rawdata["DepreciationExpense"][$i].",";
        		$query .= $rawdata["DilutedEPSNetIncomefromContinuingOperations"][$i].",";
        		$query .= $rawdata["DilutedWeightedAverageShares"][$i].",";
        		$query .= $rawdata["AmortizationExpense"][$i].",";
        		$query .= $rawdata["BasicEPSNetIncomefromContinuingOperations"][$i].",";
        		$query .= $rawdata["BasicWeightedAverageShares"][$i].",";
        		$query .= $rawdata["GeneralAdministrativeExpense"][$i].",";
        		$query .= $rawdata["IncomeAfterTaxes"][$i].",";
        		$query .= $rawdata["LaborExpense"][$i].",";
        		$query .= $rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$i].",";
        		$query .= $rawdata["InterestIncomeExpenseNet"][$i].",";
        		$query .= $rawdata["NoncontrollingInterest"][$i].",";
        		$query .= $rawdata["NonoperatingGainsLosses"][$i].",";
        		$query .= $rawdata["OperatingExpenses"][$i].",";
        		$query .= $rawdata["OtherGeneralAdministrativeExpense"][$i].",";
        		$query .= $rawdata["OtherInterestIncomeExpenseNet"][$i].",";
        		$query .= $rawdata["OtherRevenue"][$i].",";
        		$query .= $rawdata["OtherSellingGeneralAdministrativeExpenses"][$i].",";
        		$query .= $rawdata["PreferredDividends"][$i].",";
        		$query .= $rawdata["SalesMarketingExpense"][$i].",";
        		$query .= $rawdata["TotalNonoperatingIncomeExpense"][$i].",";
        		$query .= $rawdata["TotalOperatingExpenses"][$i].",";
        		$query .= $rawdata["OperatingRevenue"][$i];
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_incomefull CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("AdjustedEBIT", "AdjustedEBITDA", "AdjustedNetIncome", "AftertaxMargin", "EBITDA", "GrossMargin", "NetOperatingProfitafterTax", "OperatingMargin", "RevenueFQ", "RevenueFY", "RevenueTTM", "CostOperatingExpenses", "DepreciationExpense", "DilutedEPSNetIncomefromContinuingOperations", "DilutedWeightedAverageShares", "AmortizationExpense", "BasicEPSNetIncomefromContinuingOperations", "BasicWeightedAverageShares", "GeneralAdministrativeExpense", "IncomeAfterTaxes", "LaborExpense", "NetIncomefromContinuingOperationsApplicabletoCommon", "InterestIncomeExpenseNet", "NoncontrollingInterest", "NonoperatingGainsLosses", "OperatingExpenses", "OtherGeneralAdministrativeExpense", "OtherInterestIncomeExpenseNet", "OtherRevenue", "OtherSellingGeneralAdministrativeExpenses", "PreferredDividends", "SalesMarketingExpense", "TotalNonoperatingIncomeExpense", "TotalOperatingExpenses", "OperatingRevenue");
					updateCAGR("reports_incomefull_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
					if ($i > 5) {
						updateCAGR("reports_incomefull_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
					}
					if ($i > 7) {
						updateCAGR("reports_incomefull_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
					}
					if ($i > 10) {
						updateCAGR("reports_incomefull_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
					}
                                }
                        }

			//reports_metadata_eol
			$query = "INSERT INTO `reports_metadata_eol` (`report_id`, `CoverSheetTSO`, `CoverSheetTSODate`, `AuditorCode`, `AuditorOpinion`, `InventoryPolicy`, `NumberofShareholders`, `NumberofEmployees`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["CoverSheetTSO"][$i].",";
        		$query .= "'".date("Y-m-d",strtotime($rawdata["CoverSheetTSODate"][$i]))."',";
        		$query .= ($rawdata["AuditorCode"][$i]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["AuditorCode"][$i])."',");
        		$query .= ($rawdata["AuditorOpinion"][$i]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["AuditorOpinion"][$i])."',");
        		$query .= ($rawdata["InventoryPolicy"][$i]=='null' ? 'null,':"'".mysql_real_escape_string($rawdata["InventoryPolicy"][$i])."',");
        		$query .= $rawdata["NumberofShareholders"][$i].",";
        		$query .= $rawdata["NumberofEmployees"][$i];
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_variable_ratios
			$query = "INSERT INTO `reports_variable_ratios` (`report_id`, `BookEquity`, `DebttoAssets`, `DegreeofCombinedLeverage`, `DegreeofFinancialLeverage`, `DegreeofOperationalLeverage`, `FreeCashFlow`, `DebttoEquity`, `AdjustedEPSBasic`, `AdjustedEPSDiluted`, `FreeCashFlowReturnonAssets`, `ReturnonAssets`, `ReturnonEquity`, `ReturnonInvestedCapital`, `RevenueperEmployee`, `CashRatio`, `CurrentRatio`, `FreeCashFlowMargin`, `LongTermCapital`, `LongTermDebttoLongTermCapital`, `LongTermDebttoTotalCapital`, `NetDebt`, `NetIncomeperEmployee`, `NetWorkingCapital`, `PretaxMargin`, `QuickRatio`, `TaxRate`, `TotalCapital`) VALUES (";
	        	$query .= "'".$report_id."',";
        		$query .= $rawdata["BookEquity"][$i].",";
        		$query .= $rawdata["DebttoAssets"][$i].",";
        		$query .= $rawdata["DegreeofCombinedLeverage"][$i].",";
        		$query .= $rawdata["DegreeofFinancialLeverage"][$i].",";
        		$query .= $rawdata["DegreeofOperationalLeverage"][$i].",";
        		$query .= $rawdata["FreeCashFlow"][$i].",";
        		$query .= $rawdata["DebttoEquity"][$i].",";
        		$query .= $rawdata["AdjustedEPSBasic"][$i].",";
        		$query .= $rawdata["AdjustedEPSDiluted"][$i].",";
        		$query .= $rawdata["FreeCashFlowReturnonAssets"][$i].",";
        		$query .= $rawdata["ReturnonAssets"][$i].",";
        		$query .= $rawdata["ReturnonEquity"][$i].",";
        		$query .= $rawdata["ReturnonInvestedCapital"][$i].",";
        		$query .= $rawdata["RevenueperEmployee"][$i].",";
        		$query .= $rawdata["CashRatio"][$i].",";
        		$query .= $rawdata["CurrentRatio"][$i].",";
        		$query .= $rawdata["FreeCashFlowMargin"][$i].",";
        		$query .= $rawdata["LongTermCapital"][$i].",";
        		$query .= $rawdata["LongTermDebttoLongTermCapital"][$i].",";
        		$query .= $rawdata["LongTermDebttoTotalCapital"][$i].",";
        		$query .= $rawdata["NetDebt"][$i].",";
        		$query .= $rawdata["NetIncomeperEmployee"][$i].",";
        		$query .= $rawdata["NetWorkingCapital"][$i].",";
        		$query .= $rawdata["PretaxMargin"][$i].",";
        		$query .= $rawdata["QuickRatio"][$i].",";
        		$query .= $rawdata["TaxRate"][$i].",";
        		$query .= $rawdata["TotalCapital"][$i];
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_variable_ratios CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					$fieldArray = Array("BookEquity", "DebttoAssets", "DegreeofCombinedLeverage", "DegreeofFinancialLeverage", "DegreeofOperationalLeverage", "FreeCashFlow", "DebttoEquity", "AdjustedEPSBasic", "AdjustedEPSDiluted", "FreeCashFlowReturnonAssets", "ReturnonAssets", "ReturnonEquity", "ReturnonInvestedCapital", "RevenueperEmployee", "CashRatio", "CurrentRatio", "FreeCashFlowMargin", "LongTermCapital", "LongTermDebttoLongTermCapital", "LongTermDebttoTotalCapital", "NetDebt", "NetIncomeperEmployee", "NetWorkingCapital", "PretaxMargin", "QuickRatio", "TaxRate", "TotalCapital");
					updateCAGR("reports_variable_ratios_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
					if ($i > 5) {
						updateCAGR("reports_variable_ratios_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
					}
					if ($i > 7) {
						updateCAGR("reports_variable_ratios_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
					}
					if ($i > 10) {
						updateCAGR("reports_variable_ratios_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
					}
                                }
                        }

			//reports_financialscustom (computed data)
                        $query = "INSERT INTO `reports_financialscustom` (`report_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `SalesPercChange`, `Sales5YYCGrPerc`) VALUES (";
                        $query .= "'".$report_id."',";
                        $query .= (($rawdata["CostofRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CostofRevenue"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["GrossProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["GrossProfit"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null' ||  $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["ResearchDevelopmentExpense"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["CFDepreciationAmortization"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CFDepreciationAmortization"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["EBITDA"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["EBITDA"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["OperatingProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["OperatingProfit"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["EBIT"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["EBIT"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["IncomeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]==0)?'null':($rawdata["IncomeTaxes"][$i]/$rawdata["IncomeBeforeTaxes"][$i])).",";
                        $query .= (($rawdata["IncomeBeforeTaxes"][$i]=='null' && $rawdata["IncomeTaxes"][$i]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$i]-$rawdata["IncomeTaxes"][$i])).",";
                        $query .= (($rawdata["NetIncome"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["NetIncome"][$i]/$rawdata["TotalRevenue"][$i])).",";
                        $query .= (($rawdata["DividendsPaid"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]==0)?'null':(-($rawdata["DividendsPaid"][$i])/(toFloat($rawdata["SharesOutstandingBasic"][$i])*1000000))).",";
                        $query .= (($rawdata["CurrentPortionofLongtermDebt"][$i]=='null' && $rawdata["ShorttermBorrowings"][$i]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["ShorttermBorrowings"][$i])).",";
                        $query .= (($rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["NotesPayable"][$i]=='null')?'null':($rawdata["TotalLongtermDebt"][$i]+$rawdata["NotesPayable"][$i])).",";
                        $query .= (($rawdata["LongtermDebtProceeds"][$i]=='null' && $rawdata["LongtermDebtPayments"][$i] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$i]+$rawdata["LongtermDebtPayments"][$i])).",";
                        $query .= (($rawdata["CapitalExpenditures"][$i]=='null')?'null':(-$rawdata["CapitalExpenditures"][$i])).",";
                        $query .= (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?'null':($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i])).",";
                        $query .= (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?'null':($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i]))).",";
			if ($i <= $areports) {
				if ($i == 1) {
					$query .= "null,null";
				} else {
					$query .= ((($rawdata["TotalRevenue"][$i]=='null' && $rawdata["TotalRevenue"][$i-1]=='null') || $rawdata["TotalRevenue"][$i-1]=='null' || $rawdata["TotalRevenue"][$i-1]==0)?'null':(($rawdata["TotalRevenue"][$i]-$rawdata["TotalRevenue"][$i-1])/$rawdata["TotalRevenue"][$i-1])).",";
					if ($i > 5) {
						if ($rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i-5]=='null' || $rawdata["TotalRevenue"][$i-5]<=0 || $rawdata["TotalRevenue"][$i] < 0) {
							$query .= "null";
						} else {
							$query .= (pow($rawdata["TotalRevenue"][$i]/$rawdata["TotalRevenue"][$i-5], 1/5) - 1); 
						}
					} else {
						$query .= "null";
					}
				}
			} else {
				$query .= "null,null";
			}
        		$query .= ")";
	        	mysql_query($query) or die ($query."\n".mysql_error());
			//reports_financialscustom CAGR
                        if ($i <= $areports) {
                                if ($i > 3) {
					updateCAGR_FC("reports_financialscustom_3cagr", 3, $i, $report_id, $rawdata);
					if ($i > 5) {
						updateCAGR_FC("reports_financialscustom_5cagr", 5, $i, $report_id, $rawdata);
					}
					if ($i > 7) {
						updateCAGR_FC("reports_financialscustom_7cagr", 7, $i, $report_id, $rawdata);
					}
					if ($i > 10) {
						updateCAGR_FC("reports_financialscustom_10cagr", 10, $i, $report_id, $rawdata);
					}
                                }
                        }


			//Populate Key Ratios only for annual reports
			if($i <= $areports) {
				$CapEx = (($rawdata["CapitalExpenditures"][$i]=='null')?null:(-$rawdata["CapitalExpenditures"][$i]));
				$FreeCashFlow = (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?null:($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i]));
				$OwnerEarningsFCF = (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?null:($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i])));
				if($i == 1) {
					$arpy = $inpy = 0;
				} else {
		                        $arpy = $rawdata["AccountsReceivableTradeNet"][$i-1]=='null'?null:$rawdata["AccountsReceivableTradeNet"][$i-1];
        		                $inpy = $rawdata["InventoriesNet"][$i-1]=='null'?null:$rawdata["InventoriesNet"][$i-1];
				}
				$rdate = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]));
				$qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$dates->ticker_id."' and report_date <= '".$rdate."' order by report_date desc limit 1";
				$price = null;
		                $rquote = mysql_query($qquote) or die (mysql_error());
                		if(mysql_num_rows($rquote) > 0) {
		                      	$price = mysql_fetch_assoc($rquote);
              				$rdate = $price["report_date"];
		                        $price = $price["adj_close"];
		                }
		                $entValue = (($rawdata["SharesOutstandingDiluted"][$i]=='null' && is_null($price) && $rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["TotalShorttermDebt"][$i]=='null' && $rawdata["PreferredStock"][$i]=='null' && $rawdata["MinorityInterestEquityEarnings"][$i]=='null' && $rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null')?null:((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price)+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalShorttermDebt"][$i]+$rawdata["PreferredStock"][$i]+$rawdata["MinorityInterestEquityEarnings"][$i]-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]));
                		$query = "INSERT INTO `reports_key_ratios` (`report_id`, `ReportYear`, `ReportDate`, `ReportDateAdjusted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (";
				$query .= "'".$report_id."',";
		                $query .= $rawdata["fiscalYear"][$i].",";
                		$query .= "'".date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]))."',";
		                $query .= ($rdate == '0000-00-00'?'null':"'".$rdate."'").",";
		                $query .= "'".$price."',";
                		$query .= ((($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null' && is_null($CapEx)) || $rawdata["TaxRatePercent"][$i]=='null')?'null':(($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx)*(1-$rawdata["TaxRatePercent"][$i]))).",";
		                $query .= (($rawdata["SharesOutstandingDiluted"][$i]=='null'||is_null($price))?'null':(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price)).",";
                		$query .= "'".$entValue."',";
		                $query .= $rawdata["GoodwillIntangibleAssetsNet"][$i].",";
                		$query .= (($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')?'null':($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i])).",";
		                $query .= (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null' ||($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null'&&$rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["LongtermInvestments"][$i]=='null'))?'null':(($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] + $rawdata["LongtermInvestments"][$i]) - max(0, ($rawdata["TotalCurrentLiabilities"][$i]-$rawdata["TotalCurrentAssets"][$i]+$rawdata["CashCashEquivalentsandShorttermInvestments"][$i])))).",";
                		$query .= (($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')?'null':($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])).",";
		                $query .= (($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')?'null':($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i])).",";
                		$query .= ((is_null($price)||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?'null':($price / toFloat($rawdata["EPSDiluted"][$i]))).",";
		                $query .= (($rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?'null':((((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price)-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/toFloat($rawdata["EPSDiluted"][$i]))).",";
                		$query .= ((is_null($entValue)||$rawdata["EBITDA"][$i]=='null'||$rawdata["EBITDA"][$i]==0)?'null':($entValue / $rawdata["EBITDA"][$i])).",";
		                $query .= ((is_null($entValue)||$rawdata["EBIT"][$i]=='null'||$rawdata["EBIT"][$i]==0)?'null':($entValue / $rawdata["EBIT"][$i])).",";
                		$query .= ((is_null($price)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price / ($rawdata["TotalRevenue"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))).",";
		                $query .= ((is_null($price)||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))).",";
                		$query .= ((is_null($price)||($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i]==0))?'null':($price / (($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))).",";
		                $query .= ((is_null($price)||($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null'&&is_null($CapEx))||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx==0)||$rawdata["TaxRatePercent"][$i]==1)?'null':($price / ((($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx)*(1-$rawdata["TaxRatePercent"][$i]))/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))).",";
                		$query .= ((is_null($price)||is_null($FreeCashFlow)||$FreeCashFlow==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price / ($FreeCashFlow/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))).",";
		                $query .= ((is_null($price)||is_null($OwnerEarningsFCF)||$OwnerEarningsFCF==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price / ($OwnerEarningsFCF/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))).",";
                		$query .= ((is_null($FreeCashFlow)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($FreeCashFlow / $rawdata["TotalRevenue"][$i])).",";
                		$query .= ((is_null($price)||$price==0||is_null($FreeCashFlow)||$FreeCashFlow==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':(1 / ($price / ($FreeCashFlow/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))))).",";
                		$query .= (($rawdata["EBIT"][$i]=='null'||is_null($entValue)||$entValue==0)?'null':($rawdata["EBIT"][$i] / $entValue)).",";
		                $query .= (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["NetIncome"][$i] / $rawdata["TotalStockholdersEquity"][$i])).",";
                		$query .= (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["NetIncome"][$i] / $rawdata["TotalAssets"][$i])).",";
		                $query .= (($rawdata["EBIT"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':(($rawdata["EBIT"][$i]*(1-$rawdata["TaxRatePercent"][$i])) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]))).",";
                		$query .= ((is_null($FreeCashFlow)||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':($FreeCashFlow / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]))).",";
		                $query .= (($rawdata["GrossProfit"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["GrossProfit"][$i] / $rawdata["TotalAssets"][$i])).",";
		                $query .= ((is_null($price)||$price==0||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':(1 / ($price / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))))).",";
		                $query .= ((($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["InventoriesNet"][$i]=='null')||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?'null':(($rawdata["TotalCurrentAssets"][$i] - $rawdata["InventoriesNet"][$i]) / $rawdata["TotalCurrentLiabilities"][$i])).",";
                		$query .= (($rawdata["TotalCurrentAssets"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?'null':($rawdata["TotalCurrentAssets"][$i] / $rawdata["TotalCurrentLiabilities"][$i])).",";
		                $query .= ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i])).",";
                		$query .= ((($rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':(($rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i])).",";
		                $query .= (($rawdata["TotalShorttermDebt"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["TotalShorttermDebt"][$i] / $rawdata["TotalStockholdersEquity"][$i])).",";
                		$query .= (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["TotalRevenue"][$i] / $rawdata["TotalAssets"][$i])).",";
		                $query .= (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] / $rawdata["TotalRevenue"][$i])).",";
                		$query .= (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i])).",";
		                $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i] / $rawdata["TotalRevenue"][$i])).",";
                		$query .= (($rawdata["ResearchDevelopmentExpense"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i] / $rawdata["TotalRevenue"][$i])).",";
		                $query .= (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365)).",";
                		$query .= (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365)).",";
		                $query .= (($rawdata["AccountsPayable"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365)).",";
                		$query .= (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':(($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365)+($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365)-($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365))).",";
		                if($i==1) {
                		        $query .= (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["AccountsReceivableTradeNet"][$i]=='null'||$rawdata["AccountsReceivableTradeNet"][$i]==0)?'null':($rawdata["TotalRevenue"][$i] / ($rawdata["AccountsReceivableTradeNet"][$i]))).",";
		                        $query .= (($rawdata["CostofRevenue"][$i]=='null'||$rawdata["InventoriesNet"][$i]=='null'||$rawdata["InventoriesNet"][$i]==0)?'null':($rawdata["CostofRevenue"][$i] / ($rawdata["InventoriesNet"][$i]))).",";
                			$query .= (($rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0||$rawdata["InventoriesNet"][$i]=='null'||$rawdata["InventoriesNet"][$i]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i] / ($rawdata["InventoriesNet"][$i])))).",";
		                } else {
                		        $query .= (($rawdata["TotalRevenue"][$i]=='null'||($rawdata["AccountsReceivableTradeNet"][$i]=='null'&&is_null($arpy))||($rawdata["AccountsReceivableTradeNet"][$i]+$arpy==0))?'null':($rawdata["TotalRevenue"][$i] / (($arpy + $rawdata["AccountsReceivableTradeNet"][$i])/2))).",";
		                        $query .= (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy))||($rawdata["InventoriesNet"][$i]+$inpy==0))?'null':($rawdata["CostofRevenue"][$i] / (($inpy + $rawdata["InventoriesNet"][$i])/2))).",";
                			$query .= (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy))||($rawdata["InventoriesNet"][$i]+$inpy==0)||$rawdata["CostofRevenue"][$i]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i] / (($inpy + $rawdata["InventoriesNet"][$i])/2)))).",";
		                }
                		$query .= (($rawdata["GoodwillIntangibleAssetsNet"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["GoodwillIntangibleAssetsNet"][$i] / $rawdata["TotalStockholdersEquity"][$i])).",";
		                $query .= (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["InventoriesNet"][$i] / $rawdata["TotalRevenue"][$i])).",";
                		$query .= ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]))).",";
		                $query .= (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]))).",";
                		$query .= ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalLongtermDebt"][$i]=='null' &&$rawdata["TotalShorttermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?'null':(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]))).",";
		                $query .= (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?'null':($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]))).",";
                		$query .= ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalAssets"][$i])).",";
		                $query .= ((($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||is_null($price)||$price==0)?'null':((($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i]) / (toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/$price));
                		$query .= ")";
				mysql_query($query) or die ($query."\n".mysql_error());
	                        //reports_key_ratios CAGR
                                if ($i > 3) {
                                        updateCAGR_KR("reports_key_ratios_3cagr", 3, $i, $report_id, $rawdata, $dates->ticker_id);
                                        if ($i > 5) {
                                                updateCAGR_KR("reports_key_ratios_5cagr", 5, $i, $report_id, $rawdata, $dates->ticker_id);
                                        }
                                        if ($i > 7) {
                                                updateCAGR_KR("reports_key_ratios_7cagr", 7, $i, $report_id, $rawdata, $dates->ticker_id);
                                        }
                                        if ($i > 10) {
                                                updateCAGR_KR("reports_key_ratios_10cagr", 10, $i, $report_id, $rawdata, $dates->ticker_id);
                                        }
                                }
			}
		}
	    }
	}

	//Update TTM and PTTM data
        foreach($ttm_tables as $table) {
                $query = "DELETE FROM $table WHERE ticker_id = ".$dates->ticker_id;
                mysql_query($query) or die (mysql_error());
        }
        foreach($pttm_tables as $table) {
                $query = "DELETE FROM $table WHERE ticker_id = ".$dates->ticker_id;
                mysql_query($query) or die (mysql_error());
        }
	//Determine if USA stock or ADR
	$stock_type = "ADR";
	$MRQRow = $areports;
	$PMRQRow = $areports - 1;
	if($rawdata["Country"][$areports] == "UNITED STATES OF AMERICA" || $rawdata["Country"][$treports] == "UNITED STATES OF AMERICA" || strpos($rawdata["FormType"][$areports], "10-K") !== false || strpos($rawdata["FormType"][$treports], "10-K") !== false || strpos($rawdata["FormType"][$areports], "10-Q") !== false || strpos($rawdata["FormType"][$treports], "10-Q") !== false || strpos($rawdata["FormType"][$areports], "8-K") !== false || strpos($rawdata["FormType"][$treports], "8-K") !== false) {
		$stock_type = "USA";
		$MRQRow = $treports;
		$PMRQRow = $treports - 4;
	}

	//Load Balance MRQ data
	$query = "INSERT INTO `ttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
	$query .= $rawdata["CommitmentsContingencies"][$MRQRow].",";
      	$query .= $rawdata["CommonStock"][$MRQRow].",";
       	$query .= $rawdata["DeferredCharges"][$MRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesCurrent"][$MRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesLongterm"][$MRQRow].",";
       	$query .= $rawdata["AccountsPayableandAccruedExpenses"][$MRQRow].",";
       	$query .= $rawdata["AccruedInterest"][$MRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapital"][$MRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapitalPreferredStock"][$MRQRow].",";
       	$query .= $rawdata["CashandCashEquivalents"][$MRQRow].",";
       	$query .= $rawdata["CashCashEquivalentsandShorttermInvestments"][$MRQRow].",";
       	$query .= $rawdata["Goodwill"][$MRQRow].",";
       	$query .= $rawdata["IntangibleAssets"][$MRQRow].",";
       	$query .= $rawdata["InventoriesNet"][$MRQRow].",";
       	$query .= $rawdata["LongtermDeferredIncomeTaxLiabilities"][$MRQRow].",";
       	$query .= $rawdata["LongtermDeferredLiabilityCharges"][$MRQRow].",";
       	$query .= $rawdata["LongtermInvestments"][$MRQRow].",";
       	$query .= $rawdata["MinorityInterest"][$MRQRow].",";
       	$query .= $rawdata["OtherAccumulatedComprehensiveIncome"][$MRQRow].",";
       	$query .= $rawdata["OtherAssets"][$MRQRow].",";
       	$query .= $rawdata["OtherCurrentAssets"][$MRQRow].",";
       	$query .= $rawdata["OtherCurrentLiabilities"][$MRQRow].",";
       	$query .= $rawdata["OtherEquity"][$MRQRow].",";
       	$query .= $rawdata["OtherInvestments"][$MRQRow].",";
       	$query .= $rawdata["OtherLiabilities"][$MRQRow].",";
       	$query .= $rawdata["PartnersCapital"][$MRQRow].",";
       	$query .= $rawdata["PensionPostretirementObligation"][$MRQRow].",";
       	$query .= $rawdata["PreferredStock"][$MRQRow].",";
       	$query .= $rawdata["PrepaidExpenses"][$MRQRow].",";
       	$query .= $rawdata["PropertyPlantEquipmentNet"][$MRQRow].",";
       	$query .= $rawdata["RestrictedCash"][$MRQRow].",";
       	$query .= $rawdata["RetainedEarnings"][$MRQRow].",";
       	$query .= $rawdata["TemporaryEquity"][$MRQRow].",";
       	$query .= $rawdata["TotalAssets"][$MRQRow].",";
       	$query .= $rawdata["TotalCurrentAssets"][$MRQRow].",";
       	$query .= $rawdata["TotalCurrentLiabilities"][$MRQRow].",";
       	$query .= $rawdata["TotalLiabilities"][$MRQRow].",";
       	$query .= $rawdata["TotalLongtermDebt"][$MRQRow].",";
       	$query .= $rawdata["TotalReceivablesNet"][$MRQRow].",";
       	$query .= $rawdata["TotalShorttermDebt"][$MRQRow].",";
       	$query .= $rawdata["TotalStockholdersEquity"][$MRQRow].",";
       	$query .= $rawdata["TreasuryStock"][$MRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query."\n".mysql_error());

	$query = "INSERT INTO `pttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
	$query .= $rawdata["CommitmentsContingencies"][$PMRQRow].",";
      	$query .= $rawdata["CommonStock"][$PMRQRow].",";
       	$query .= $rawdata["DeferredCharges"][$PMRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesCurrent"][$PMRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesLongterm"][$PMRQRow].",";
       	$query .= $rawdata["AccountsPayableandAccruedExpenses"][$PMRQRow].",";
       	$query .= $rawdata["AccruedInterest"][$PMRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapital"][$PMRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapitalPreferredStock"][$PMRQRow].",";
       	$query .= $rawdata["CashandCashEquivalents"][$PMRQRow].",";
       	$query .= $rawdata["CashCashEquivalentsandShorttermInvestments"][$PMRQRow].",";
       	$query .= $rawdata["Goodwill"][$PMRQRow].",";
       	$query .= $rawdata["IntangibleAssets"][$PMRQRow].",";
       	$query .= $rawdata["InventoriesNet"][$PMRQRow].",";
       	$query .= $rawdata["LongtermDeferredIncomeTaxLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["LongtermDeferredLiabilityCharges"][$PMRQRow].",";
       	$query .= $rawdata["LongtermInvestments"][$PMRQRow].",";
       	$query .= $rawdata["MinorityInterest"][$PMRQRow].",";
       	$query .= $rawdata["OtherAccumulatedComprehensiveIncome"][$PMRQRow].",";
       	$query .= $rawdata["OtherAssets"][$PMRQRow].",";
       	$query .= $rawdata["OtherCurrentAssets"][$PMRQRow].",";
       	$query .= $rawdata["OtherCurrentLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["OtherEquity"][$PMRQRow].",";
       	$query .= $rawdata["OtherInvestments"][$PMRQRow].",";
       	$query .= $rawdata["OtherLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["PartnersCapital"][$PMRQRow].",";
       	$query .= $rawdata["PensionPostretirementObligation"][$PMRQRow].",";
       	$query .= $rawdata["PreferredStock"][$PMRQRow].",";
       	$query .= $rawdata["PrepaidExpenses"][$PMRQRow].",";
       	$query .= $rawdata["PropertyPlantEquipmentNet"][$PMRQRow].",";
       	$query .= $rawdata["RestrictedCash"][$PMRQRow].",";
       	$query .= $rawdata["RetainedEarnings"][$PMRQRow].",";
       	$query .= $rawdata["TemporaryEquity"][$PMRQRow].",";
       	$query .= $rawdata["TotalAssets"][$PMRQRow].",";
       	$query .= $rawdata["TotalCurrentAssets"][$PMRQRow].",";
       	$query .= $rawdata["TotalCurrentLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["TotalLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["TotalLongtermDebt"][$PMRQRow].",";
       	$query .= $rawdata["TotalReceivablesNet"][$PMRQRow].",";
       	$query .= $rawdata["TotalShorttermDebt"][$PMRQRow].",";
       	$query .= $rawdata["TotalStockholdersEquity"][$PMRQRow].",";
       	$query .= $rawdata["TreasuryStock"][$PMRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query."\n".mysql_error());

	$query = "INSERT INTO `ttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
       	$query .= $rawdata["TotalDebt"][$MRQRow].",";
       	$query .= $rawdata["TotalAssetsFQ"][$MRQRow].",";
       	$query .= $rawdata["TotalAssetsFY"][$MRQRow].",";
       	$query .= $rawdata["CurrentPortionofLongtermDebt"][$MRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$MRQRow].",";
       	$query .= $rawdata["DeferredLiabilityCharges"][$MRQRow].",";
       	$query .= $rawdata["AccountsNotesReceivableNet"][$MRQRow].",";
       	$query .= $rawdata["AccountsPayable"][$MRQRow].",";
       	$query .= $rawdata["AccountsReceivableTradeNet"][$MRQRow].",";
       	$query .= $rawdata["AccruedExpenses"][$MRQRow].",";
       	$query .= $rawdata["AccumulatedDepreciation"][$MRQRow].",";
       	$query .= $rawdata["AmountsDuetoRelatedPartiesShortterm"][$MRQRow].",";
       	$query .= $rawdata["GoodwillIntangibleAssetsNet"][$MRQRow].",";
       	$query .= $rawdata["IncomeTaxesPayable"][$MRQRow].",";
       	$query .= $rawdata["LiabilitiesStockholdersEquity"][$MRQRow].",";
       	$query .= $rawdata["LongtermDebt"][$MRQRow].",";
       	$query .= $rawdata["NotesPayable"][$MRQRow].",";
       	$query .= $rawdata["OperatingLeases"][$MRQRow].",";
       	$query .= $rawdata["OtherAccountsNotesReceivable"][$MRQRow].",";
       	$query .= $rawdata["OtherAccountsPayableandAccruedExpenses"][$MRQRow].",";
       	$query .= $rawdata["OtherBorrowings"][$MRQRow].",";
       	$query .= $rawdata["OtherReceivables"][$MRQRow].",";
       	$query .= $rawdata["PropertyandEquipmentGross"][$MRQRow].",";
       	$query .= $rawdata["TotalLongtermAssets"][$MRQRow].",";
       	$query .= $rawdata["TotalLongtermLiabilities"][$MRQRow].",";
       	$query .= $rawdata["TotalSharesOutstanding"][$MRQRow].",";
	$query .= $rawdata["ShorttermInvestments"][$MRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query."\n".mysql_error());

	$query = "INSERT INTO `pttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
       	$query .= $rawdata["TotalDebt"][$PMRQRow].",";
       	$query .= $rawdata["TotalAssetsFQ"][$PMRQRow].",";
       	$query .= $rawdata["TotalAssetsFY"][$PMRQRow].",";
       	$query .= $rawdata["CurrentPortionofLongtermDebt"][$PMRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$PMRQRow].",";
       	$query .= $rawdata["DeferredLiabilityCharges"][$PMRQRow].",";
       	$query .= $rawdata["AccountsNotesReceivableNet"][$PMRQRow].",";
       	$query .= $rawdata["AccountsPayable"][$PMRQRow].",";
       	$query .= $rawdata["AccountsReceivableTradeNet"][$PMRQRow].",";
       	$query .= $rawdata["AccruedExpenses"][$PMRQRow].",";
       	$query .= $rawdata["AccumulatedDepreciation"][$PMRQRow].",";
       	$query .= $rawdata["AmountsDuetoRelatedPartiesShortterm"][$PMRQRow].",";
       	$query .= $rawdata["GoodwillIntangibleAssetsNet"][$PMRQRow].",";
       	$query .= $rawdata["IncomeTaxesPayable"][$PMRQRow].",";
       	$query .= $rawdata["LiabilitiesStockholdersEquity"][$PMRQRow].",";
       	$query .= $rawdata["LongtermDebt"][$PMRQRow].",";
       	$query .= $rawdata["NotesPayable"][$PMRQRow].",";
       	$query .= $rawdata["OperatingLeases"][$PMRQRow].",";
       	$query .= $rawdata["OtherAccountsNotesReceivable"][$PMRQRow].",";
       	$query .= $rawdata["OtherAccountsPayableandAccruedExpenses"][$PMRQRow].",";
       	$query .= $rawdata["OtherBorrowings"][$PMRQRow].",";
       	$query .= $rawdata["OtherReceivables"][$PMRQRow].",";
       	$query .= $rawdata["PropertyandEquipmentGross"][$PMRQRow].",";
       	$query .= $rawdata["TotalLongtermAssets"][$PMRQRow].",";
       	$query .= $rawdata["TotalLongtermLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["TotalSharesOutstanding"][$PMRQRow].",";
	$query .= $rawdata["ShorttermInvestments"][$PMRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query."\n".mysql_error());

	//Cashflow and Financial
	if($stock_type == "ADR") {
                $query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= toFloat($rawdata["InterestIncome"][$MRQRow]).",";
                $query .= toFloat($rawdata["InterestExpense"][$MRQRow]).",";
                $query .= toFloat($rawdata["EPSBasic"][$MRQRow]).",";
                $query .= toFloat($rawdata["EPSDiluted"][$MRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$MRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$MRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$MRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query."\n".mysql_error());

                $query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= toFloat($rawdata["InterestIncome"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InterestExpense"][$PMRQRow]).",";
                $query .= toFloat($rawdata["EPSBasic"][$PMRQRow]).",";
                $query .= toFloat($rawdata["EPSDiluted"][$PMRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$PMRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$PMRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$PMRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= $rawdata["ChangeinCurrentAssets"][$MRQRow].",";
        	$query .= $rawdata["ChangeinCurrentLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinDebtNet"][$MRQRow].",";
        	$query .= $rawdata["ChangeinDeferredRevenue"][$MRQRow].",";
        	$query .= $rawdata["ChangeinEquityNet"][$MRQRow].",";
        	$query .= $rawdata["ChangeinIncomeTaxesPayable"][$MRQRow].",";
        	$query .= $rawdata["ChangeinInventories"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOperatingAssetsLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherAssets"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentAssets"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinPrepaidExpenses"][$MRQRow].",";
        	$query .= $rawdata["DividendsPaid"][$MRQRow].",";
        	$query .= $rawdata["EffectofExchangeRateonCash"][$MRQRow].",";
        	$query .= $rawdata["EmployeeCompensation"][$MRQRow].",";
        	$query .= $rawdata["AcquisitionSaleofBusinessNet"][$MRQRow].",";
        	$query .= $rawdata["AdjustmentforEquityEarnings"][$MRQRow].",";
        	$query .= $rawdata["AdjustmentforMinorityInterest"][$MRQRow].",";
        	$query .= $rawdata["AdjustmentforSpecialCharges"][$MRQRow].",";
        	$query .= $rawdata["CapitalExpenditures"][$MRQRow].",";
        	$query .= $rawdata["CashfromDiscontinuedOperations"][$MRQRow].",";
        	$query .= $rawdata["CashfromFinancingActivities"][$MRQRow].",";
        	$query .= $rawdata["CashfromInvestingActivities"][$MRQRow].",";
        	$query .= $rawdata["CashfromOperatingActivities"][$MRQRow].",";
        	$query .= $rawdata["CFDepreciationAmortization"][$MRQRow].",";
        	$query .= $rawdata["DeferredIncomeTaxes"][$MRQRow].",";
        	$query .= $rawdata["ChangeinAccountsPayableAccruedExpenses"][$MRQRow].",";
        	$query .= $rawdata["ChangeinAccountsReceivable"][$MRQRow].",";
        	$query .= $rawdata["InvestmentChangesNet"][$MRQRow].",";
        	$query .= $rawdata["NetChangeinCash"][$MRQRow].",";
        	$query .= $rawdata["OtherAdjustments"][$MRQRow].",";
        	$query .= $rawdata["OtherAssetLiabilityChangesNet"][$MRQRow].",";
        	$query .= $rawdata["OtherFinancingActivitiesNet"][$MRQRow].",";
        	$query .= $rawdata["OtherInvestingActivities"][$MRQRow].",";
        	$query .= $rawdata["RealizedGainsLosses"][$MRQRow].",";
        	$query .= $rawdata["SaleofPropertyPlantEquipment"][$MRQRow].",";
        	$query .= $rawdata["StockOptionTaxBenefits"][$MRQRow].",";
        	$query .= $rawdata["TotalAdjustments"][$MRQRow];
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());
		
		$query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= $rawdata["ChangeinCurrentAssets"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinCurrentLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinDebtNet"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinDeferredRevenue"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinEquityNet"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinIncomeTaxesPayable"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinInventories"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOperatingAssetsLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherAssets"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentAssets"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinPrepaidExpenses"][$PMRQRow].",";
        	$query .= $rawdata["DividendsPaid"][$PMRQRow].",";
        	$query .= $rawdata["EffectofExchangeRateonCash"][$PMRQRow].",";
        	$query .= $rawdata["EmployeeCompensation"][$PMRQRow].",";
        	$query .= $rawdata["AcquisitionSaleofBusinessNet"][$PMRQRow].",";
        	$query .= $rawdata["AdjustmentforEquityEarnings"][$PMRQRow].",";
        	$query .= $rawdata["AdjustmentforMinorityInterest"][$PMRQRow].",";
        	$query .= $rawdata["AdjustmentforSpecialCharges"][$PMRQRow].",";
        	$query .= $rawdata["CapitalExpenditures"][$PMRQRow].",";
        	$query .= $rawdata["CashfromDiscontinuedOperations"][$PMRQRow].",";
        	$query .= $rawdata["CashfromFinancingActivities"][$PMRQRow].",";
        	$query .= $rawdata["CashfromInvestingActivities"][$PMRQRow].",";
        	$query .= $rawdata["CashfromOperatingActivities"][$PMRQRow].",";
        	$query .= $rawdata["CFDepreciationAmortization"][$PMRQRow].",";
        	$query .= $rawdata["DeferredIncomeTaxes"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinAccountsPayableAccruedExpenses"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinAccountsReceivable"][$PMRQRow].",";
        	$query .= $rawdata["InvestmentChangesNet"][$PMRQRow].",";
        	$query .= $rawdata["NetChangeinCash"][$PMRQRow].",";
        	$query .= $rawdata["OtherAdjustments"][$PMRQRow].",";
        	$query .= $rawdata["OtherAssetLiabilityChangesNet"][$PMRQRow].",";
        	$query .= $rawdata["OtherFinancingActivitiesNet"][$PMRQRow].",";
        	$query .= $rawdata["OtherInvestingActivities"][$PMRQRow].",";
        	$query .= $rawdata["RealizedGainsLosses"][$PMRQRow].",";
        	$query .= $rawdata["SaleofPropertyPlantEquipment"][$PMRQRow].",";
        	$query .= $rawdata["StockOptionTaxBenefits"][$PMRQRow].",";
        	$query .= $rawdata["TotalAdjustments"][$PMRQRow];
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["ChangeinLongtermDebtNet"][$MRQRow].",";
       		$query .= $rawdata["ChangeinShorttermBorrowingsNet"][$MRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsBeginningofYear"][$MRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsEndofYear"][$MRQRow].",";
       		$query .= $rawdata["CashPaidforIncomeTaxes"][$MRQRow].",";
      		$query .= $rawdata["CashPaidforInterestExpense"][$MRQRow].",";
       		$query .= $rawdata["CFNetIncome"][$MRQRow].",";
       		$query .= $rawdata["IssuanceofEquity"][$MRQRow].",";
       		$query .= $rawdata["LongtermDebtPayments"][$MRQRow].",";
       		$query .= $rawdata["LongtermDebtProceeds"][$MRQRow].",";
      		$query .= $rawdata["OtherDebtNet"][$MRQRow].",";
       		$query .= $rawdata["OtherEquityTransactionsNet"][$MRQRow].",";
       		$query .= $rawdata["OtherInvestmentChangesNet"][$MRQRow].",";
       		$query .= $rawdata["PurchaseofInvestments"][$MRQRow].",";
       		$query .= $rawdata["RepurchaseofEquity"][$MRQRow].",";
       		$query .= $rawdata["SaleofInvestments"][$MRQRow].",";
       		$query .= $rawdata["ShorttermBorrowings"][$MRQRow].",";
       		$query .= $rawdata["TotalNoncashAdjustments"][$MRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["ChangeinLongtermDebtNet"][$PMRQRow].",";
       		$query .= $rawdata["ChangeinShorttermBorrowingsNet"][$PMRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsBeginningofYear"][$PMRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow].",";
       		$query .= $rawdata["CashPaidforIncomeTaxes"][$PMRQRow].",";
      		$query .= $rawdata["CashPaidforInterestExpense"][$PMRQRow].",";
       		$query .= $rawdata["CFNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["IssuanceofEquity"][$PMRQRow].",";
       		$query .= $rawdata["LongtermDebtPayments"][$PMRQRow].",";
       		$query .= $rawdata["LongtermDebtProceeds"][$PMRQRow].",";
      		$query .= $rawdata["OtherDebtNet"][$PMRQRow].",";
       		$query .= $rawdata["OtherEquityTransactionsNet"][$PMRQRow].",";
       		$query .= $rawdata["OtherInvestmentChangesNet"][$PMRQRow].",";
       		$query .= $rawdata["PurchaseofInvestments"][$PMRQRow].",";
       		$query .= $rawdata["RepurchaseofEquity"][$PMRQRow].",";
       		$query .= $rawdata["SaleofInvestments"][$PMRQRow].",";
       		$query .= $rawdata["ShorttermBorrowings"][$PMRQRow].",";
       		$query .= $rawdata["TotalNoncashAdjustments"][$PMRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["EBIT"][$MRQRow].",";
       		$query .= $rawdata["CostofRevenue"][$MRQRow].",";
       		$query .= $rawdata["DepreciationAmortizationExpense"][$MRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncome"][$MRQRow].",";
       		$query .= $rawdata["DiscontinuedOperations"][$MRQRow].",";
       		$query .= $rawdata["EquityEarnings"][$MRQRow].",";
      		$query .= $rawdata["AccountingChange"][$MRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncome"][$MRQRow].",";
       		$query .= $rawdata["ExtraordinaryItems"][$MRQRow].",";
       		$query .= $rawdata["GrossProfit"][$MRQRow].",";
       		$query .= $rawdata["IncomebeforeExtraordinaryItems"][$MRQRow].",";
       		$query .= $rawdata["IncomeBeforeTaxes"][$MRQRow].",";
       		$query .= $rawdata["IncomeTaxes"][$MRQRow].",";
       		$query .= toFloat($rawdata["InterestExpense"][$MRQRow]).",";
       		$query .= toFloat($rawdata["InterestIncome"][$MRQRow]).",";
       		$query .= $rawdata["MinorityInterestEquityEarnings"][$MRQRow].",";
       		$query .= $rawdata["NetIncome"][$MRQRow].",";
       		$query .= $rawdata["NetIncomeApplicabletoCommon"][$MRQRow].",";
       		$query .= $rawdata["OperatingProfit"][$MRQRow].",";
       		$query .= $rawdata["OtherNonoperatingIncomeExpense"][$MRQRow].",";
      		$query .= $rawdata["OtherOperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["ResearchDevelopmentExpense"][$MRQRow].",";
       		$query .= $rawdata["RestructuringRemediationImpairmentProvisions"][$MRQRow].",";
       		$query .= $rawdata["TotalRevenue"][$MRQRow].",";
       		$query .= $rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["EBIT"][$PMRQRow].",";
       		$query .= $rawdata["CostofRevenue"][$PMRQRow].",";
       		$query .= $rawdata["DepreciationAmortizationExpense"][$PMRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["DiscontinuedOperations"][$PMRQRow].",";
       		$query .= $rawdata["EquityEarnings"][$PMRQRow].",";
      		$query .= $rawdata["AccountingChange"][$PMRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["ExtraordinaryItems"][$PMRQRow].",";
       		$query .= $rawdata["GrossProfit"][$PMRQRow].",";
       		$query .= $rawdata["IncomebeforeExtraordinaryItems"][$PMRQRow].",";
       		$query .= $rawdata["IncomeBeforeTaxes"][$PMRQRow].",";
       		$query .= $rawdata["IncomeTaxes"][$PMRQRow].",";
       		$query .= toFloat($rawdata["InterestExpense"][$PMRQRow]).",";
       		$query .= toFloat($rawdata["InterestIncome"][$PMRQRow]).",";
       		$query .= $rawdata["MinorityInterestEquityEarnings"][$PMRQRow].",";
       		$query .= $rawdata["NetIncome"][$PMRQRow].",";
       		$query .= $rawdata["NetIncomeApplicabletoCommon"][$PMRQRow].",";
       		$query .= $rawdata["OperatingProfit"][$PMRQRow].",";
       		$query .= $rawdata["OtherNonoperatingIncomeExpense"][$PMRQRow].",";
      		$query .= $rawdata["OtherOperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["ResearchDevelopmentExpense"][$PMRQRow].",";
       		$query .= $rawdata["RestructuringRemediationImpairmentProvisions"][$PMRQRow].",";
       		$query .= $rawdata["TotalRevenue"][$PMRQRow].",";
       		$query .= $rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["AdjustedEBIT"][$MRQRow].",";
       		$query .= $rawdata["AdjustedEBITDA"][$MRQRow].",";
      		$query .= $rawdata["AdjustedNetIncome"][$MRQRow].",";
       		$query .= $rawdata["AftertaxMargin"][$MRQRow].",";
       		$query .= $rawdata["EBITDA"][$MRQRow].",";
      		$query .= $rawdata["GrossMargin"][$MRQRow].",";
       		$query .= $rawdata["NetOperatingProfitafterTax"][$MRQRow].",";
       		$query .= $rawdata["OperatingMargin"][$MRQRow].",";
       		$query .= $rawdata["RevenueFQ"][$MRQRow].",";
      		$query .= $rawdata["RevenueFY"][$MRQRow].",";
       		$query .= $rawdata["RevenueTTM"][$MRQRow].",";
       		$query .= $rawdata["CostOperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["DepreciationExpense"][$MRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncomefromContinuingOperations"][$MRQRow].",";
       		$query .= $rawdata["DilutedWeightedAverageShares"][$MRQRow].",";
       		$query .= $rawdata["AmortizationExpense"][$MRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncomefromContinuingOperations"][$MRQRow].",";
       		$query .= $rawdata["BasicWeightedAverageShares"][$MRQRow].",";
      		$query .= $rawdata["GeneralAdministrativeExpense"][$MRQRow].",";
       		$query .= $rawdata["IncomeAfterTaxes"][$MRQRow].",";
       		$query .= $rawdata["LaborExpense"][$MRQRow].",";
       		$query .= $rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$MRQRow].",";
       		$query .= $rawdata["InterestIncomeExpenseNet"][$MRQRow].",";
       		$query .= $rawdata["NoncontrollingInterest"][$MRQRow].",";
       		$query .= $rawdata["NonoperatingGainsLosses"][$MRQRow].",";
       		$query .= $rawdata["OperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["OtherGeneralAdministrativeExpense"][$MRQRow].",";
       		$query .= $rawdata["OtherInterestIncomeExpenseNet"][$MRQRow].",";
       		$query .= $rawdata["OtherRevenue"][$MRQRow].",";
       		$query .= $rawdata["OtherSellingGeneralAdministrativeExpenses"][$MRQRow].",";
      		$query .= $rawdata["PreferredDividends"][$MRQRow].",";
       		$query .= $rawdata["SalesMarketingExpense"][$MRQRow].",";
       		$query .= $rawdata["TotalNonoperatingIncomeExpense"][$MRQRow].",";
       		$query .= $rawdata["TotalOperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["OperatingRevenue"][$MRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["AdjustedEBIT"][$PMRQRow].",";
       		$query .= $rawdata["AdjustedEBITDA"][$PMRQRow].",";
      		$query .= $rawdata["AdjustedNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["AftertaxMargin"][$PMRQRow].",";
       		$query .= $rawdata["EBITDA"][$PMRQRow].",";
      		$query .= $rawdata["GrossMargin"][$PMRQRow].",";
       		$query .= $rawdata["NetOperatingProfitafterTax"][$PMRQRow].",";
       		$query .= $rawdata["OperatingMargin"][$PMRQRow].",";
       		$query .= $rawdata["RevenueFQ"][$PMRQRow].",";
      		$query .= $rawdata["RevenueFY"][$PMRQRow].",";
       		$query .= $rawdata["RevenueTTM"][$PMRQRow].",";
       		$query .= $rawdata["CostOperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["DepreciationExpense"][$PMRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncomefromContinuingOperations"][$PMRQRow].",";
       		$query .= $rawdata["DilutedWeightedAverageShares"][$PMRQRow].",";
       		$query .= $rawdata["AmortizationExpense"][$PMRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncomefromContinuingOperations"][$PMRQRow].",";
       		$query .= $rawdata["BasicWeightedAverageShares"][$PMRQRow].",";
      		$query .= $rawdata["GeneralAdministrativeExpense"][$PMRQRow].",";
       		$query .= $rawdata["IncomeAfterTaxes"][$PMRQRow].",";
       		$query .= $rawdata["LaborExpense"][$PMRQRow].",";
       		$query .= $rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$PMRQRow].",";
       		$query .= $rawdata["InterestIncomeExpenseNet"][$PMRQRow].",";
       		$query .= $rawdata["NoncontrollingInterest"][$PMRQRow].",";
       		$query .= $rawdata["NonoperatingGainsLosses"][$PMRQRow].",";
       		$query .= $rawdata["OperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["OtherGeneralAdministrativeExpense"][$PMRQRow].",";
       		$query .= $rawdata["OtherInterestIncomeExpenseNet"][$PMRQRow].",";
       		$query .= $rawdata["OtherRevenue"][$PMRQRow].",";
       		$query .= $rawdata["OtherSellingGeneralAdministrativeExpenses"][$PMRQRow].",";
      		$query .= $rawdata["PreferredDividends"][$PMRQRow].",";
       		$query .= $rawdata["SalesMarketingExpense"][$PMRQRow].",";
       		$query .= $rawdata["TotalNonoperatingIncomeExpense"][$PMRQRow].",";
       		$query .= $rawdata["TotalOperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["OperatingRevenue"][$PMRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

                $query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["CostofRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["CostofRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["GrossProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["GrossProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]=='null' ||  $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["ResearchDevelopmentExpense"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["CFDepreciationAmortization"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["CFDepreciationAmortization"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["EBITDA"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["EBITDA"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["OperatingProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["OperatingProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["EBIT"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["EBIT"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["IncomeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]==0)?'null':($rawdata["IncomeTaxes"][$MRQRow]/$rawdata["IncomeBeforeTaxes"][$MRQRow])).",";
                $query .= (($rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' && $rawdata["IncomeTaxes"][$MRQRow]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$MRQRow]-$rawdata["IncomeTaxes"][$MRQRow])).",";
                $query .= (($rawdata["NetIncome"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["NetIncome"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
		$query .= (($rawdata["DividendsPaid"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]==0)?'null':(-($rawdata["DividendsPaid"][$MRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$MRQRow])*1000000))).",";
                $query .= (($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]=='null' && $rawdata["ShorttermBorrowings"][$MRQRow]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow])).",";
                $query .= (($rawdata["TotalLongtermDebt"][$MRQRow]=='null' && $rawdata["NotesPayable"][$MRQRow]=='null')?'null':($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow])).",";
                $query .= (($rawdata["LongtermDebtProceeds"][$MRQRow]=='null' && $rawdata["LongtermDebtPayments"][$MRQRow] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$MRQRow]+$rawdata["LongtermDebtPayments"][$MRQRow])).",";
                $query .= (($rawdata["CapitalExpenditures"][$MRQRow]=='null')?'null':(-$rawdata["CapitalExpenditures"][$MRQRow])).",";
                $query .= (($rawdata["CashfromOperatingActivities"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null')?'null':($rawdata["CashfromOperatingActivities"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow])).",";
                $query .= (($rawdata["CFNetIncome"][$MRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$MRQRow]=='null' && $rawdata["EmployeeCompensation"][$MRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$MRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$MRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$MRQRow]=='null')?'null':($rawdata["CFNetIncome"][$MRQRow]+$rawdata["CFDepreciationAmortization"][$MRQRow]+$rawdata["EmployeeCompensation"][$MRQRow]+$rawdata["AdjustmentforSpecialCharges"][$MRQRow]+$rawdata["DeferredIncomeTaxes"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow]+($rawdata["ChangeinCurrentAssets"][$MRQRow]+$rawdata["ChangeinCurrentLiabilities"][$MRQRow]))).",";
		$query .= (($rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]<=0 || $rawdata["TotalRevenue"][$MRQRow] < 0)?'null':(pow($rawdata["TotalRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow-5], 1/5) - 1));
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());

                $query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["CostofRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["CostofRevenue"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["GrossProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["GrossProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]=='null' ||  $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["ResearchDevelopmentExpense"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["CFDepreciationAmortization"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["EBITDA"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["EBITDA"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["OperatingProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["OperatingProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["EBIT"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["EBIT"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["IncomeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]==0)?'null':($rawdata["IncomeTaxes"][$PMRQRow]/$rawdata["IncomeBeforeTaxes"][$PMRQRow])).",";
                $query .= (($rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' && $rawdata["IncomeTaxes"][$PMRQRow]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$PMRQRow]-$rawdata["IncomeTaxes"][$PMRQRow])).",";
                $query .= (($rawdata["NetIncome"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["NetIncome"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
		$query .= (($rawdata["DividendsPaid"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]==0)?'null':(-($rawdata["DividendsPaid"][$PMRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow])*1000000))).",";
                $query .= (($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]=='null' && $rawdata["ShorttermBorrowings"][$PMRQRow]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]+$rawdata["ShorttermBorrowings"][$PMRQRow])).",";
                $query .= (($rawdata["TotalLongtermDebt"][$PMRQRow]=='null' && $rawdata["NotesPayable"][$PMRQRow]=='null')?'null':($rawdata["TotalLongtermDebt"][$PMRQRow]+$rawdata["NotesPayable"][$PMRQRow])).",";
                $query .= (($rawdata["LongtermDebtProceeds"][$PMRQRow]=='null' && $rawdata["LongtermDebtPayments"][$PMRQRow] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$PMRQRow]+$rawdata["LongtermDebtPayments"][$PMRQRow])).",";
                $query .= (($rawdata["CapitalExpenditures"][$PMRQRow]=='null')?'null':(-$rawdata["CapitalExpenditures"][$PMRQRow])).",";
                $query .= (($rawdata["CashfromOperatingActivities"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null')?'null':($rawdata["CashfromOperatingActivities"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow])).",";
                $query .= (($rawdata["CFNetIncome"][$PMRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' && $rawdata["EmployeeCompensation"][$PMRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$PMRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$PMRQRow]=='null')?'null':($rawdata["CFNetIncome"][$PMRQRow]+$rawdata["CFDepreciationAmortization"][$PMRQRow]+$rawdata["EmployeeCompensation"][$PMRQRow]+$rawdata["AdjustmentforSpecialCharges"][$PMRQRow]+$rawdata["DeferredIncomeTaxes"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow]+($rawdata["ChangeinCurrentAssets"][$PMRQRow]+$rawdata["ChangeinCurrentLiabilities"][$PMRQRow])));
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());
	} else {
                $query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["InterestIncome"][$treports-3]=='null'&&$rawdata["InterestIncome"][$treports-2]=='null'&&$rawdata["InterestIncome"][$treports-1]=='null'&&$rawdata["InterestIncome"][$treports]=='null')?'null':(toFloat($rawdata["InterestIncome"][$treports-3])+toFloat($rawdata["InterestIncome"][$treports-2])+toFloat($rawdata["InterestIncome"][$treports-1])+toFloat($rawdata["InterestIncome"][$treports]))).",";
                $query .= (($rawdata["InterestExpense"][$treports-3]=='null'&&$rawdata["InterestExpense"][$treports-2]=='null'&&$rawdata["InterestExpense"][$treports-1]=='null'&&$rawdata["InterestExpense"][$treports]=='null')?'null':(toFloat($rawdata["InterestExpense"][$treports-3])+toFloat($rawdata["InterestExpense"][$treports-2])+toFloat($rawdata["InterestExpense"][$treports-1])+toFloat($rawdata["InterestExpense"][$treports]))).",";
                $query .= (($rawdata["EPSBasic"][$treports-3]=='null'&&$rawdata["EPSBasic"][$treports-2]=='null'&&$rawdata["EPSBasic"][$treports-1]=='null'&&$rawdata["EPSBasic"][$treports]=='null')?'null':(toFloat($rawdata["EPSBasic"][$treports-3])+toFloat($rawdata["EPSBasic"][$treports-2])+toFloat($rawdata["EPSBasic"][$treports-1])+toFloat($rawdata["EPSBasic"][$treports]))).",";
                $query .= (($rawdata["EPSDiluted"][$treports-3]=='null'&&$rawdata["EPSDiluted"][$treports-2]=='null'&&$rawdata["EPSDiluted"][$treports-1]=='null'&&$rawdata["EPSDiluted"][$treports]=='null')?'null':(toFloat($rawdata["EPSDiluted"][$treports-3])+toFloat($rawdata["EPSDiluted"][$treports-2])+toFloat($rawdata["EPSDiluted"][$treports-1])+toFloat($rawdata["EPSDiluted"][$treports]))).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$MRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$MRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$MRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query."\n".mysql_error());

                $query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["InterestIncome"][$treports-7]=='null'&&$rawdata["InterestIncome"][$treports-6]=='null'&&$rawdata["InterestIncome"][$treports-5]=='null'&&$rawdata["InterestIncome"][$treports-4]=='null')?'null':(toFloat($rawdata["InterestIncome"][$treports-7])+toFloat($rawdata["InterestIncome"][$treports-6])+toFloat($rawdata["InterestIncome"][$treports-5])+toFloat($rawdata["InterestIncome"][$treports-4]))).",";
                $query .= (($rawdata["InterestExpense"][$treports-7]=='null'&&$rawdata["InterestExpense"][$treports-6]=='null'&&$rawdata["InterestExpense"][$treports-5]=='null'&&$rawdata["InterestExpense"][$treports-4]=='null')?'null':(toFloat($rawdata["InterestExpense"][$treports-7])+toFloat($rawdata["InterestExpense"][$treports-6])+toFloat($rawdata["InterestExpense"][$treports-5])+toFloat($rawdata["InterestExpense"][$treports-4]))).",";
                $query .= (($rawdata["EPSBasic"][$treports-7]=='null'&&$rawdata["EPSBasic"][$treports-6]=='null'&&$rawdata["EPSBasic"][$treports-5]=='null'&&$rawdata["EPSBasic"][$treports-4]=='null')?'null':(toFloat($rawdata["EPSBasic"][$treports-7])+toFloat($rawdata["EPSBasic"][$treports-6])+toFloat($rawdata["EPSBasic"][$treports-5])+toFloat($rawdata["EPSBasic"][$treports-4]))).",";
                $query .= (($rawdata["EPSDiluted"][$treports-7]=='null'&&$rawdata["EPSDiluted"][$treports-6]=='null'&&$rawdata["EPSDiluted"][$treports-5]=='null'&&$rawdata["EPSDiluted"][$treports-4]=='null')?'null':(toFloat($rawdata["EPSDiluted"][$treports-7])+toFloat($rawdata["EPSDiluted"][$treports-6])+toFloat($rawdata["EPSDiluted"][$treports-5])+toFloat($rawdata["EPSDiluted"][$treports-4]))).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$PMRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$PMRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$PMRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= (($rawdata["ChangeinCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports]=='null')?'null':($rawdata["ChangeinCurrentAssets"][$treports-3]+$rawdata["ChangeinCurrentAssets"][$treports-2]+$rawdata["ChangeinCurrentAssets"][$treports-1]+$rawdata["ChangeinCurrentAssets"][$treports])).",";
        	$query .= (($rawdata["ChangeinCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports]=='null')?'null':($rawdata["ChangeinCurrentLiabilities"][$treports-3]+$rawdata["ChangeinCurrentLiabilities"][$treports-2]+$rawdata["ChangeinCurrentLiabilities"][$treports-1]+$rawdata["ChangeinCurrentLiabilities"][$treports])).",";
        	$query .= (($rawdata["ChangeinDebtNet"][$treports-3]=='null'&&$rawdata["ChangeinDebtNet"][$treports-2]=='null'&&$rawdata["ChangeinDebtNet"][$treports-1]=='null'&&$rawdata["ChangeinDebtNet"][$treports]=='null')?'null':($rawdata["ChangeinDebtNet"][$treports-3]+$rawdata["ChangeinDebtNet"][$treports-2]+$rawdata["ChangeinDebtNet"][$treports-1]+$rawdata["ChangeinDebtNet"][$treports])).",";
        	$query .= (($rawdata["ChangeinDeferredRevenue"][$treports-3]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-2]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-1]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports]=='null')?'null':($rawdata["ChangeinDeferredRevenue"][$treports-3]+$rawdata["ChangeinDeferredRevenue"][$treports-2]+$rawdata["ChangeinDeferredRevenue"][$treports-1]+$rawdata["ChangeinDeferredRevenue"][$treports])).",";
        	$query .= (($rawdata["ChangeinEquityNet"][$treports-3]=='null'&&$rawdata["ChangeinEquityNet"][$treports-2]=='null'&&$rawdata["ChangeinEquityNet"][$treports-1]=='null'&&$rawdata["ChangeinEquityNet"][$treports]=='null')?'null':($rawdata["ChangeinEquityNet"][$treports-3]+$rawdata["ChangeinEquityNet"][$treports-2]+$rawdata["ChangeinEquityNet"][$treports-1]+$rawdata["ChangeinEquityNet"][$treports])).",";
        	$query .= (($rawdata["ChangeinIncomeTaxesPayable"][$treports-3]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-2]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-1]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports]=='null')?'null':($rawdata["ChangeinIncomeTaxesPayable"][$treports-3]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-2]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-1]+$rawdata["ChangeinIncomeTaxesPayable"][$treports])).",";
        	$query .= (($rawdata["ChangeinInventories"][$treports-3]=='null'&&$rawdata["ChangeinInventories"][$treports-2]=='null'&&$rawdata["ChangeinInventories"][$treports-1]=='null'&&$rawdata["ChangeinInventories"][$treports]=='null')?'null':($rawdata["ChangeinInventories"][$treports-3]+$rawdata["ChangeinInventories"][$treports-2]+$rawdata["ChangeinInventories"][$treports-1]+$rawdata["ChangeinInventories"][$treports])).",";
        	$query .= (($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports]=='null')?'null':($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-3]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-2]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-1]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports])).",";
        	$query .= (($rawdata["ChangeinOtherAssets"][$treports-3]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-2]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-1]=='null'&&$rawdata["ChangeinOtherAssets"][$treports]=='null')?'null':($rawdata["ChangeinOtherAssets"][$treports-3]+$rawdata["ChangeinOtherAssets"][$treports-2]+$rawdata["ChangeinOtherAssets"][$treports-1]+$rawdata["ChangeinOtherAssets"][$treports])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports]=='null')?'null':($rawdata["ChangeinOtherCurrentAssets"][$treports-3]+$rawdata["ChangeinOtherCurrentAssets"][$treports-2]+$rawdata["ChangeinOtherCurrentAssets"][$treports-1]+$rawdata["ChangeinOtherCurrentAssets"][$treports])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports]=='null')?'null':($rawdata["ChangeinOtherCurrentLiabilities"][$treports-3]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-2]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-1]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports])).",";
        	$query .= (($rawdata["ChangeinOtherLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports]=='null')?'null':($rawdata["ChangeinOtherLiabilities"][$treports-3]+$rawdata["ChangeinOtherLiabilities"][$treports-2]+$rawdata["ChangeinOtherLiabilities"][$treports-1]+$rawdata["ChangeinOtherLiabilities"][$treports])).",";
        	$query .= (($rawdata["ChangeinPrepaidExpenses"][$treports-3]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-2]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-1]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports]=='null')?'null':($rawdata["ChangeinPrepaidExpenses"][$treports-3]+$rawdata["ChangeinPrepaidExpenses"][$treports-2]+$rawdata["ChangeinPrepaidExpenses"][$treports-1]+$rawdata["ChangeinPrepaidExpenses"][$treports])).",";
        	$query .= (($rawdata["DividendsPaid"][$treports-3]=='null'&&$rawdata["DividendsPaid"][$treports-2]=='null'&&$rawdata["DividendsPaid"][$treports-1]=='null'&&$rawdata["DividendsPaid"][$treports]=='null')?'null':($rawdata["DividendsPaid"][$treports-3]+$rawdata["DividendsPaid"][$treports-2]+$rawdata["DividendsPaid"][$treports-1]+$rawdata["DividendsPaid"][$treports])).",";
        	$query .= (($rawdata["EffectofExchangeRateonCash"][$treports-3]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-2]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-1]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports]=='null')?'null':($rawdata["EffectofExchangeRateonCash"][$treports-3]+$rawdata["EffectofExchangeRateonCash"][$treports-2]+$rawdata["EffectofExchangeRateonCash"][$treports-1]+$rawdata["EffectofExchangeRateonCash"][$treports])).",";
        	$query .= (($rawdata["EmployeeCompensation"][$treports-3]=='null'&&$rawdata["EmployeeCompensation"][$treports-2]=='null'&&$rawdata["EmployeeCompensation"][$treports-1]=='null'&&$rawdata["EmployeeCompensation"][$treports]=='null')?'null':($rawdata["EmployeeCompensation"][$treports-3]+$rawdata["EmployeeCompensation"][$treports-2]+$rawdata["EmployeeCompensation"][$treports-1]+$rawdata["EmployeeCompensation"][$treports])).",";
        	$query .= (($rawdata["AcquisitionSaleofBusinessNet"][$treports-3]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-2]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-1]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports]=='null')?'null':($rawdata["AcquisitionSaleofBusinessNet"][$treports-3]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-2]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-1]+$rawdata["AcquisitionSaleofBusinessNet"][$treports])).",";
        	$query .= (($rawdata["AdjustmentforEquityEarnings"][$treports-3]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-2]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-1]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports]=='null')?'null':($rawdata["AdjustmentforEquityEarnings"][$treports-3]+$rawdata["AdjustmentforEquityEarnings"][$treports-2]+$rawdata["AdjustmentforEquityEarnings"][$treports-1]+$rawdata["AdjustmentforEquityEarnings"][$treports])).",";
        	$query .= (($rawdata["AdjustmentforMinorityInterest"][$treports-3]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-2]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-1]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports]=='null')?'null':($rawdata["AdjustmentforMinorityInterest"][$treports-3]+$rawdata["AdjustmentforMinorityInterest"][$treports-2]+$rawdata["AdjustmentforMinorityInterest"][$treports-1]+$rawdata["AdjustmentforMinorityInterest"][$treports])).",";
        	$query .= (($rawdata["AdjustmentforSpecialCharges"][$treports-3]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-2]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-1]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports]=='null')?'null':($rawdata["AdjustmentforSpecialCharges"][$treports-3]+$rawdata["AdjustmentforSpecialCharges"][$treports-2]+$rawdata["AdjustmentforSpecialCharges"][$treports-1]+$rawdata["AdjustmentforSpecialCharges"][$treports])).",";
        	$query .= (($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')?'null':($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])).",";
        	$query .= (($rawdata["CashfromDiscontinuedOperations"][$treports-3]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-2]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-1]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports]=='null')?'null':($rawdata["CashfromDiscontinuedOperations"][$treports-3]+$rawdata["CashfromDiscontinuedOperations"][$treports-2]+$rawdata["CashfromDiscontinuedOperations"][$treports-1]+$rawdata["CashfromDiscontinuedOperations"][$treports])).",";
        	$query .= (($rawdata["CashfromFinancingActivities"][$treports-3]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-2]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-1]=='null'&&$rawdata["CashfromFinancingActivities"][$treports]=='null')?'null':($rawdata["CashfromFinancingActivities"][$treports-3]+$rawdata["CashfromFinancingActivities"][$treports-2]+$rawdata["CashfromFinancingActivities"][$treports-1]+$rawdata["CashfromFinancingActivities"][$treports])).",";
        	$query .= (($rawdata["CashfromInvestingActivities"][$treports-3]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-2]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-1]=='null'&&$rawdata["CashfromInvestingActivities"][$treports]=='null')?'null':($rawdata["CashfromInvestingActivities"][$treports-3]+$rawdata["CashfromInvestingActivities"][$treports-2]+$rawdata["CashfromInvestingActivities"][$treports-1]+$rawdata["CashfromInvestingActivities"][$treports])).",";
        	$query .= (($rawdata["CashfromOperatingActivities"][$treports-3]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-2]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-1]=='null'&&$rawdata["CashfromOperatingActivities"][$treports]=='null')?'null':($rawdata["CashfromOperatingActivities"][$treports-3]+$rawdata["CashfromOperatingActivities"][$treports-2]+$rawdata["CashfromOperatingActivities"][$treports-1]+$rawdata["CashfromOperatingActivities"][$treports])).",";
        	$query .= (($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')?'null':($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports])).",";
        	$query .= (($rawdata["DeferredIncomeTaxes"][$treports-3]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-2]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-1]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports]=='null')?'null':($rawdata["DeferredIncomeTaxes"][$treports-3]+$rawdata["DeferredIncomeTaxes"][$treports-2]+$rawdata["DeferredIncomeTaxes"][$treports-1]+$rawdata["DeferredIncomeTaxes"][$treports])).",";
        	$query .= (($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-3]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-2]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-1]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports]=='null')?'null':($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-3]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-2]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-1]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports])).",";
        	$query .= (($rawdata["ChangeinAccountsReceivable"][$treports-3]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-2]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-1]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports]=='null')?'null':($rawdata["ChangeinAccountsReceivable"][$treports-3]+$rawdata["ChangeinAccountsReceivable"][$treports-2]+$rawdata["ChangeinAccountsReceivable"][$treports-1]+$rawdata["ChangeinAccountsReceivable"][$treports])).",";
        	$query .= (($rawdata["InvestmentChangesNet"][$treports-3]=='null'&&$rawdata["InvestmentChangesNet"][$treports-2]=='null'&&$rawdata["InvestmentChangesNet"][$treports-1]=='null'&&$rawdata["InvestmentChangesNet"][$treports]=='null')?'null':($rawdata["InvestmentChangesNet"][$treports-3]+$rawdata["InvestmentChangesNet"][$treports-2]+$rawdata["InvestmentChangesNet"][$treports-1]+$rawdata["InvestmentChangesNet"][$treports])).",";
        	$query .= (($rawdata["NetChangeinCash"][$treports-3]=='null'&&$rawdata["NetChangeinCash"][$treports-2]=='null'&&$rawdata["NetChangeinCash"][$treports-1]=='null'&&$rawdata["NetChangeinCash"][$treports]=='null')?'null':($rawdata["NetChangeinCash"][$treports-3]+$rawdata["NetChangeinCash"][$treports-2]+$rawdata["NetChangeinCash"][$treports-1]+$rawdata["NetChangeinCash"][$treports])).",";
        	$query .= (($rawdata["OtherAdjustments"][$treports-3]=='null'&&$rawdata["OtherAdjustments"][$treports-2]=='null'&&$rawdata["OtherAdjustments"][$treports-1]=='null'&&$rawdata["OtherAdjustments"][$treports]=='null')?'null':($rawdata["OtherAdjustments"][$treports-3]+$rawdata["OtherAdjustments"][$treports-2]+$rawdata["OtherAdjustments"][$treports-1]+$rawdata["OtherAdjustments"][$treports])).",";
        	$query .= (($rawdata["OtherAssetLiabilityChangesNet"][$treports-3]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-2]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-1]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports]=='null')?'null':($rawdata["OtherAssetLiabilityChangesNet"][$treports-3]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-2]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-1]+$rawdata["OtherAssetLiabilityChangesNet"][$treports])).",";
        	$query .= (($rawdata["OtherFinancingActivitiesNet"][$treports-3]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-2]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-1]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports]=='null')?'null':($rawdata["OtherFinancingActivitiesNet"][$treports-3]+$rawdata["OtherFinancingActivitiesNet"][$treports-2]+$rawdata["OtherFinancingActivitiesNet"][$treports-1]+$rawdata["OtherFinancingActivitiesNet"][$treports])).",";
        	$query .= (($rawdata["OtherInvestingActivities"][$treports-3]=='null'&&$rawdata["OtherInvestingActivities"][$treports-2]=='null'&&$rawdata["OtherInvestingActivities"][$treports-1]=='null'&&$rawdata["OtherInvestingActivities"][$treports]=='null')?'null':($rawdata["OtherInvestingActivities"][$treports-3]+$rawdata["OtherInvestingActivities"][$treports-2]+$rawdata["OtherInvestingActivities"][$treports-1]+$rawdata["OtherInvestingActivities"][$treports])).",";
        	$query .= (($rawdata["RealizedGainsLosses"][$treports-3]=='null'&&$rawdata["RealizedGainsLosses"][$treports-2]=='null'&&$rawdata["RealizedGainsLosses"][$treports-1]=='null'&&$rawdata["RealizedGainsLosses"][$treports]=='null')?'null':($rawdata["RealizedGainsLosses"][$treports-3]+$rawdata["RealizedGainsLosses"][$treports-2]+$rawdata["RealizedGainsLosses"][$treports-1]+$rawdata["RealizedGainsLosses"][$treports])).",";
        	$query .= (($rawdata["SaleofPropertyPlantEquipment"][$treports-3]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-2]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-1]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports]=='null')?'null':($rawdata["SaleofPropertyPlantEquipment"][$treports-3]+$rawdata["SaleofPropertyPlantEquipment"][$treports-2]+$rawdata["SaleofPropertyPlantEquipment"][$treports-1]+$rawdata["SaleofPropertyPlantEquipment"][$treports])).",";
        	$query .= (($rawdata["StockOptionTaxBenefits"][$treports-3]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-2]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-1]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports]=='null')?'null':($rawdata["StockOptionTaxBenefits"][$treports-3]+$rawdata["StockOptionTaxBenefits"][$treports-2]+$rawdata["StockOptionTaxBenefits"][$treports-1]+$rawdata["StockOptionTaxBenefits"][$treports])).",";
        	$query .= (($rawdata["TotalAdjustments"][$treports-3]=='null'&&$rawdata["TotalAdjustments"][$treports-2]=='null'&&$rawdata["TotalAdjustments"][$treports-1]=='null'&&$rawdata["TotalAdjustments"][$treports]=='null')?'null':($rawdata["TotalAdjustments"][$treports-3]+$rawdata["TotalAdjustments"][$treports-2]+$rawdata["TotalAdjustments"][$treports-1]+$rawdata["TotalAdjustments"][$treports]));
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());
		
		$query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= (($rawdata["ChangeinCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-4]=='null')?'null':($rawdata["ChangeinCurrentAssets"][$treports-7]+$rawdata["ChangeinCurrentAssets"][$treports-6]+$rawdata["ChangeinCurrentAssets"][$treports-5]+$rawdata["ChangeinCurrentAssets"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-4]=='null')?'null':($rawdata["ChangeinCurrentLiabilities"][$treports-7]+$rawdata["ChangeinCurrentLiabilities"][$treports-6]+$rawdata["ChangeinCurrentLiabilities"][$treports-5]+$rawdata["ChangeinCurrentLiabilities"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinDebtNet"][$treports-7]=='null'&&$rawdata["ChangeinDebtNet"][$treports-6]=='null'&&$rawdata["ChangeinDebtNet"][$treports-5]=='null'&&$rawdata["ChangeinDebtNet"][$treports-4]=='null')?'null':($rawdata["ChangeinDebtNet"][$treports-7]+$rawdata["ChangeinDebtNet"][$treports-6]+$rawdata["ChangeinDebtNet"][$treports-5]+$rawdata["ChangeinDebtNet"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinDeferredRevenue"][$treports-7]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-6]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-5]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-4]=='null')?'null':($rawdata["ChangeinDeferredRevenue"][$treports-7]+$rawdata["ChangeinDeferredRevenue"][$treports-6]+$rawdata["ChangeinDeferredRevenue"][$treports-5]+$rawdata["ChangeinDeferredRevenue"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinEquityNet"][$treports-7]=='null'&&$rawdata["ChangeinEquityNet"][$treports-6]=='null'&&$rawdata["ChangeinEquityNet"][$treports-5]=='null'&&$rawdata["ChangeinEquityNet"][$treports-4]=='null')?'null':($rawdata["ChangeinEquityNet"][$treports-7]+$rawdata["ChangeinEquityNet"][$treports-6]+$rawdata["ChangeinEquityNet"][$treports-5]+$rawdata["ChangeinEquityNet"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinIncomeTaxesPayable"][$treports-7]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-6]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-5]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-4]=='null')?'null':($rawdata["ChangeinIncomeTaxesPayable"][$treports-7]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-6]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-5]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinInventories"][$treports-7]=='null'&&$rawdata["ChangeinInventories"][$treports-6]=='null'&&$rawdata["ChangeinInventories"][$treports-5]=='null'&&$rawdata["ChangeinInventories"][$treports-4]=='null')?'null':($rawdata["ChangeinInventories"][$treports-7]+$rawdata["ChangeinInventories"][$treports-6]+$rawdata["ChangeinInventories"][$treports-5]+$rawdata["ChangeinInventories"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-4]=='null')?'null':($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-7]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-6]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-5]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinOtherAssets"][$treports-7]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-6]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-5]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-4]=='null')?'null':($rawdata["ChangeinOtherAssets"][$treports-7]+$rawdata["ChangeinOtherAssets"][$treports-6]+$rawdata["ChangeinOtherAssets"][$treports-5]+$rawdata["ChangeinOtherAssets"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-4]=='null')?'null':($rawdata["ChangeinOtherCurrentAssets"][$treports-7]+$rawdata["ChangeinOtherCurrentAssets"][$treports-6]+$rawdata["ChangeinOtherCurrentAssets"][$treports-5]+$rawdata["ChangeinOtherCurrentAssets"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-4]=='null')?'null':($rawdata["ChangeinOtherCurrentLiabilities"][$treports-7]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-6]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-5]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinOtherLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-4]=='null')?'null':($rawdata["ChangeinOtherLiabilities"][$treports-7]+$rawdata["ChangeinOtherLiabilities"][$treports-6]+$rawdata["ChangeinOtherLiabilities"][$treports-5]+$rawdata["ChangeinOtherLiabilities"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinPrepaidExpenses"][$treports-7]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-6]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-5]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-4]=='null')?'null':($rawdata["ChangeinPrepaidExpenses"][$treports-7]+$rawdata["ChangeinPrepaidExpenses"][$treports-6]+$rawdata["ChangeinPrepaidExpenses"][$treports-5]+$rawdata["ChangeinPrepaidExpenses"][$treports-4])).",";
        	$query .= (($rawdata["DividendsPaid"][$treports-7]=='null'&&$rawdata["DividendsPaid"][$treports-6]=='null'&&$rawdata["DividendsPaid"][$treports-5]=='null'&&$rawdata["DividendsPaid"][$treports-4]=='null')?'null':($rawdata["DividendsPaid"][$treports-7]+$rawdata["DividendsPaid"][$treports-6]+$rawdata["DividendsPaid"][$treports-5]+$rawdata["DividendsPaid"][$treports-4])).",";
        	$query .= (($rawdata["EffectofExchangeRateonCash"][$treports-7]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-6]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-5]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-4]=='null')?'null':($rawdata["EffectofExchangeRateonCash"][$treports-7]+$rawdata["EffectofExchangeRateonCash"][$treports-6]+$rawdata["EffectofExchangeRateonCash"][$treports-5]+$rawdata["EffectofExchangeRateonCash"][$treports-4])).",";
        	$query .= (($rawdata["EmployeeCompensation"][$treports-7]=='null'&&$rawdata["EmployeeCompensation"][$treports-6]=='null'&&$rawdata["EmployeeCompensation"][$treports-5]=='null'&&$rawdata["EmployeeCompensation"][$treports-4]=='null')?'null':($rawdata["EmployeeCompensation"][$treports-7]+$rawdata["EmployeeCompensation"][$treports-6]+$rawdata["EmployeeCompensation"][$treports-5]+$rawdata["EmployeeCompensation"][$treports-4])).",";
        	$query .= (($rawdata["AcquisitionSaleofBusinessNet"][$treports-7]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-6]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-5]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-4]='null')?'null':($rawdata["AcquisitionSaleofBusinessNet"][$treports-7]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-6]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-5]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-4])).",";
        	$query .= (($rawdata["AdjustmentforEquityEarnings"][$treports-7]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-6]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-5]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-4]=='null')?'null':($rawdata["AdjustmentforEquityEarnings"][$treports-7]+$rawdata["AdjustmentforEquityEarnings"][$treports-6]+$rawdata["AdjustmentforEquityEarnings"][$treports-5]+$rawdata["AdjustmentforEquityEarnings"][$treports-4])).",";
        	$query .= (($rawdata["AdjustmentforMinorityInterest"][$treports-7]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-6]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-5]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-4]=='null')?'null':($rawdata["AdjustmentforMinorityInterest"][$treports-7]+$rawdata["AdjustmentforMinorityInterest"][$treports-6]+$rawdata["AdjustmentforMinorityInterest"][$treports-5]+$rawdata["AdjustmentforMinorityInterest"][$treports-4])).",";
        	$query .= (($rawdata["AdjustmentforSpecialCharges"][$treports-7]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-6]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-5]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-4]=='null')?'null':($rawdata["AdjustmentforSpecialCharges"][$treports-7]+$rawdata["AdjustmentforSpecialCharges"][$treports-6]+$rawdata["AdjustmentforSpecialCharges"][$treports-5]+$rawdata["AdjustmentforSpecialCharges"][$treports-4])).",";
        	$query .= (($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')?'null':($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])).",";
        	$query .= (($rawdata["CashfromDiscontinuedOperations"][$treports-7]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-6]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-5]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-4]=='null')?'null':($rawdata["CashfromDiscontinuedOperations"][$treports-7]+$rawdata["CashfromDiscontinuedOperations"][$treports-6]+$rawdata["CashfromDiscontinuedOperations"][$treports-5]+$rawdata["CashfromDiscontinuedOperations"][$treports-4])).",";
        	$query .= (($rawdata["CashfromFinancingActivities"][$treports-7]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-6]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-5]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-4]=='null')?'null':($rawdata["CashfromFinancingActivities"][$treports-7]+$rawdata["CashfromFinancingActivities"][$treports-6]+$rawdata["CashfromFinancingActivities"][$treports-5]+$rawdata["CashfromFinancingActivities"][$treports-4])).",";
        	$query .= (($rawdata["CashfromInvestingActivities"][$treports-7]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-6]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-5]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-4]=='null')?'null':($rawdata["CashfromInvestingActivities"][$treports-7]+$rawdata["CashfromInvestingActivities"][$treports-6]+$rawdata["CashfromInvestingActivities"][$treports-5]+$rawdata["CashfromInvestingActivities"][$treports-4])).",";
        	$query .= (($rawdata["CashfromOperatingActivities"][$treports-7]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-6]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-5]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-4]=='null')?'null':($rawdata["CashfromOperatingActivities"][$treports-7]+$rawdata["CashfromOperatingActivities"][$treports-6]+$rawdata["CashfromOperatingActivities"][$treports-5]+$rawdata["CashfromOperatingActivities"][$treports-4])).",";
        	$query .= (($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')?'null':($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4])).",";
        	$query .= (($rawdata["DeferredIncomeTaxes"][$treports-7]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-6]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-5]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-4]=='null')?'null':($rawdata["DeferredIncomeTaxes"][$treports-7]+$rawdata["DeferredIncomeTaxes"][$treports-6]+$rawdata["DeferredIncomeTaxes"][$treports-5]+$rawdata["DeferredIncomeTaxes"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-7]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-6]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-5]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-4]=='null')?'null':($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-7]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-6]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-5]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-4])).",";
        	$query .= (($rawdata["ChangeinAccountsReceivable"][$treports-7]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-6]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-5]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-4]=='null')?'null':($rawdata["ChangeinAccountsReceivable"][$treports-7]+$rawdata["ChangeinAccountsReceivable"][$treports-6]+$rawdata["ChangeinAccountsReceivable"][$treports-5]+$rawdata["ChangeinAccountsReceivable"][$treports-4])).",";
        	$query .= (($rawdata["InvestmentChangesNet"][$treports-7]=='null'&&$rawdata["InvestmentChangesNet"][$treports-6]=='null'&&$rawdata["InvestmentChangesNet"][$treports-5]=='null'&&$rawdata["InvestmentChangesNet"][$treports-4]=='null')?'null':($rawdata["InvestmentChangesNet"][$treports-7]+$rawdata["InvestmentChangesNet"][$treports-6]+$rawdata["InvestmentChangesNet"][$treports-5]+$rawdata["InvestmentChangesNet"][$treports-4])).",";
        	$query .= (($rawdata["NetChangeinCash"][$treports-7]=='null'&&$rawdata["NetChangeinCash"][$treports-6]=='null'&&$rawdata["NetChangeinCash"][$treports-5]=='null'&&$rawdata["NetChangeinCash"][$treports-4]=='null')?'null':($rawdata["NetChangeinCash"][$treports-7]+$rawdata["NetChangeinCash"][$treports-6]+$rawdata["NetChangeinCash"][$treports-5]+$rawdata["NetChangeinCash"][$treports-4])).",";
        	$query .= (($rawdata["OtherAdjustments"][$treports-7]=='null'&&$rawdata["OtherAdjustments"][$treports-6]=='null'&&$rawdata["OtherAdjustments"][$treports-5]=='null'&&$rawdata["OtherAdjustments"][$treports-4]=='null')?'null':($rawdata["OtherAdjustments"][$treports-7]+$rawdata["OtherAdjustments"][$treports-6]+$rawdata["OtherAdjustments"][$treports-5]+$rawdata["OtherAdjustments"][$treports-4])).",";
        	$query .= (($rawdata["OtherAssetLiabilityChangesNet"][$treports-7]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-6]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-5]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-4]=='null')?'null':($rawdata["OtherAssetLiabilityChangesNet"][$treports-7]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-6]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-5]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-4])).",";
        	$query .= (($rawdata["OtherFinancingActivitiesNet"][$treports-7]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-6]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-5]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-4]=='null')?'null':($rawdata["OtherFinancingActivitiesNet"][$treports-7]+$rawdata["OtherFinancingActivitiesNet"][$treports-6]+$rawdata["OtherFinancingActivitiesNet"][$treports-5]+$rawdata["OtherFinancingActivitiesNet"][$treports-4])).",";
        	$query .= (($rawdata["OtherInvestingActivities"][$treports-7]=='null'&&$rawdata["OtherInvestingActivities"][$treports-6]=='null'&&$rawdata["OtherInvestingActivities"][$treports-5]=='null'&&$rawdata["OtherInvestingActivities"][$treports-4]=='null')?'null':($rawdata["OtherInvestingActivities"][$treports-7]+$rawdata["OtherInvestingActivities"][$treports-6]+$rawdata["OtherInvestingActivities"][$treports-5]+$rawdata["OtherInvestingActivities"][$treports-4])).",";
        	$query .= (($rawdata["RealizedGainsLosses"][$treports-7]=='null'&&$rawdata["RealizedGainsLosses"][$treports-6]=='null'&&$rawdata["RealizedGainsLosses"][$treports-5]=='null'&&$rawdata["RealizedGainsLosses"][$treports-4]=='null')?'null':($rawdata["RealizedGainsLosses"][$treports-7]+$rawdata["RealizedGainsLosses"][$treports-6]+$rawdata["RealizedGainsLosses"][$treports-5]+$rawdata["RealizedGainsLosses"][$treports-4])).",";
        	$query .= (($rawdata["SaleofPropertyPlantEquipment"][$treports-7]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-6]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-5]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-4]=='null')?'null':($rawdata["SaleofPropertyPlantEquipment"][$treports-7]+$rawdata["SaleofPropertyPlantEquipment"][$treports-6]+$rawdata["SaleofPropertyPlantEquipment"][$treports-5]+$rawdata["SaleofPropertyPlantEquipment"][$treports-4])).",";
        	$query .= (($rawdata["StockOptionTaxBenefits"][$treports-7]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-6]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-5]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-4]=='null')?'null':($rawdata["StockOptionTaxBenefits"][$treports-7]+$rawdata["StockOptionTaxBenefits"][$treports-6]+$rawdata["StockOptionTaxBenefits"][$treports-5]+$rawdata["StockOptionTaxBenefits"][$treports-4])).",";
        	$query .= (($rawdata["TotalAdjustments"][$treports-7]=='null'&&$rawdata["TotalAdjustments"][$treports-6]=='null'&&$rawdata["TotalAdjustments"][$treports-5]=='null'&&$rawdata["TotalAdjustments"][$treports-4]=='null')?'null':($rawdata["TotalAdjustments"][$treports-7]+$rawdata["TotalAdjustments"][$treports-6]+$rawdata["TotalAdjustments"][$treports-5]+$rawdata["TotalAdjustments"][$treports-4]));
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= (($rawdata["ChangeinLongtermDebtNet"][$treports-3]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-2]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-1]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports]=='null')?'null':($rawdata["ChangeinLongtermDebtNet"][$treports-3]+$rawdata["ChangeinLongtermDebtNet"][$treports-2]+$rawdata["ChangeinLongtermDebtNet"][$treports-1]+$rawdata["ChangeinLongtermDebtNet"][$treports])).",";
       		$query .= (($rawdata["ChangeinShorttermBorrowingsNet"][$treports-3]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-2]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-1]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports]=='null')?'null':($rawdata["ChangeinShorttermBorrowingsNet"][$treports-3]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-2]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-1]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports])).",";
       		$query .= (($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-3]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-2]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-1]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports]=='null')?'null':($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-3]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-2]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-1]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports])).",";
       		$query .= (($rawdata["CashandCashEquivalentsEndofYear"][$treports-3]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][$treports-2]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][$treports-1]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][$treports]=='null')?'null':($rawdata["CashandCashEquivalentsEndofYear"][$treports-3]+$rawdata["CashandCashEquivalentsEndofYear"][$treports-2]+$rawdata["CashandCashEquivalentsEndofYear"][$treports-1]+$rawdata["CashandCashEquivalentsEndofYear"][$treports])).",";
       		$query .= (($rawdata["CashPaidforIncomeTaxes"][$treports-3]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-2]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-1]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports]=='null')?'null':($rawdata["CashPaidforIncomeTaxes"][$treports-3]+$rawdata["CashPaidforIncomeTaxes"][$treports-2]+$rawdata["CashPaidforIncomeTaxes"][$treports-1]+$rawdata["CashPaidforIncomeTaxes"][$treports])).",";
      		$query .= (($rawdata["CashPaidforInterestExpense"][$treports-3]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-2]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-1]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports]=='null')?'null':($rawdata["CashPaidforInterestExpense"][$treports-3]+$rawdata["CashPaidforInterestExpense"][$treports-2]+$rawdata["CashPaidforInterestExpense"][$treports-1]+$rawdata["CashPaidforInterestExpense"][$treports])).",";
       		$query .= (($rawdata["CFNetIncome"][$treports-3]=='null'&&$rawdata["CFNetIncome"][$treports-2]=='null'&&$rawdata["CFNetIncome"][$treports-1]=='null'&&$rawdata["CFNetIncome"][$treports]=='null')?'null':($rawdata["CFNetIncome"][$treports-3]+$rawdata["CFNetIncome"][$treports-2]+$rawdata["CFNetIncome"][$treports-1]+$rawdata["CFNetIncome"][$treports])).",";
       		$query .= (($rawdata["IssuanceofEquity"][$treports-3]=='null'&&$rawdata["IssuanceofEquity"][$treports-2]=='null'&&$rawdata["IssuanceofEquity"][$treports-1]=='null'&&$rawdata["IssuanceofEquity"][$treports]=='null')?'null':($rawdata["IssuanceofEquity"][$treports-3]+$rawdata["IssuanceofEquity"][$treports-2]+$rawdata["IssuanceofEquity"][$treports-1]+$rawdata["IssuanceofEquity"][$treports])).",";
       		$query .= (($rawdata["LongtermDebtPayments"][$treports-3]=='null'&&$rawdata["LongtermDebtPayments"][$treports-2]=='null'&&$rawdata["LongtermDebtPayments"][$treports-1]=='null'&&$rawdata["LongtermDebtPayments"][$treports]=='null')?'null':($rawdata["LongtermDebtPayments"][$treports-3]+$rawdata["LongtermDebtPayments"][$treports-2]+$rawdata["LongtermDebtPayments"][$treports-1]+$rawdata["LongtermDebtPayments"][$treports])).",";
       		$query .= (($rawdata["LongtermDebtProceeds"][$treports-3]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-2]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-1]=='null'&&$rawdata["LongtermDebtProceeds"][$treports]=='null')?'null':($rawdata["LongtermDebtProceeds"][$treports-3]+$rawdata["LongtermDebtProceeds"][$treports-2]+$rawdata["LongtermDebtProceeds"][$treports-1]+$rawdata["LongtermDebtProceeds"][$treports])).",";
      		$query .= (($rawdata["OtherDebtNet"][$treports-3]=='null'&&$rawdata["OtherDebtNet"][$treports-2]=='null'&&$rawdata["OtherDebtNet"][$treports-1]=='null'&&$rawdata["OtherDebtNet"][$treports]=='null')?'null':($rawdata["OtherDebtNet"][$treports-3]+$rawdata["OtherDebtNet"][$treports-2]+$rawdata["OtherDebtNet"][$treports-1]+$rawdata["OtherDebtNet"][$treports])).",";
       		$query .= (($rawdata["OtherEquityTransactionsNet"][$treports-3]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-2]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-1]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports]=='null')?'null':($rawdata["OtherEquityTransactionsNet"][$treports-3]+$rawdata["OtherEquityTransactionsNet"][$treports-2]+$rawdata["OtherEquityTransactionsNet"][$treports-1]+$rawdata["OtherEquityTransactionsNet"][$treports])).",";
       		$query .= (($rawdata["OtherInvestmentChangesNet"][$treports-3]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-2]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-1]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports]=='null')?'null':($rawdata["OtherInvestmentChangesNet"][$treports-3]+$rawdata["OtherInvestmentChangesNet"][$treports-2]+$rawdata["OtherInvestmentChangesNet"][$treports-1]+$rawdata["OtherInvestmentChangesNet"][$treports])).",";
       		$query .= (($rawdata["PurchaseofInvestments"][$treports-3]=='null'&&$rawdata["PurchaseofInvestments"][$treports-2]=='null'&&$rawdata["PurchaseofInvestments"][$treports-1]=='null'&&$rawdata["PurchaseofInvestments"][$treports]=='null')?'null':($rawdata["PurchaseofInvestments"][$treports-3]+$rawdata["PurchaseofInvestments"][$treports-2]+$rawdata["PurchaseofInvestments"][$treports-1]+$rawdata["PurchaseofInvestments"][$treports])).",";
       		$query .= (($rawdata["RepurchaseofEquity"][$treports-3]=='null'&&$rawdata["RepurchaseofEquity"][$treports-2]=='null'&&$rawdata["RepurchaseofEquity"][$treports-1]=='null'&&$rawdata["RepurchaseofEquity"][$treports]=='null')?'null':($rawdata["RepurchaseofEquity"][$treports-3]+$rawdata["RepurchaseofEquity"][$treports-2]+$rawdata["RepurchaseofEquity"][$treports-1]+$rawdata["RepurchaseofEquity"][$treports])).",";
       		$query .= (($rawdata["SaleofInvestments"][$treports-3]=='null'&&$rawdata["SaleofInvestments"][$treports-2]=='null'&&$rawdata["SaleofInvestments"][$treports-1]=='null'&&$rawdata["SaleofInvestments"][$treports]=='null')?'null':($rawdata["SaleofInvestments"][$treports-3]+$rawdata["SaleofInvestments"][$treports-2]+$rawdata["SaleofInvestments"][$treports-1]+$rawdata["SaleofInvestments"][$treports])).",";
       		$query .= (($rawdata["ShorttermBorrowings"][$treports-3]=='null'&&$rawdata["ShorttermBorrowings"][$treports-2]=='null'&&$rawdata["ShorttermBorrowings"][$treports-1]=='null'&&$rawdata["ShorttermBorrowings"][$treports]=='null')?'null':($rawdata["ShorttermBorrowings"][$treports-3]+$rawdata["ShorttermBorrowings"][$treports-2]+$rawdata["ShorttermBorrowings"][$treports-1]+$rawdata["ShorttermBorrowings"][$treports])).",";
       		$query .= (($rawdata["TotalNoncashAdjustments"][$treports-3]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-2]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-1]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports]=='null')?'null':($rawdata["TotalNoncashAdjustments"][$treports-3]+$rawdata["TotalNoncashAdjustments"][$treports-2]+$rawdata["TotalNoncashAdjustments"][$treports-1]+$rawdata["TotalNoncashAdjustments"][$treports]));
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= (($rawdata["ChangeinLongtermDebtNet"][$treports-7]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-6]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-5]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-4]=='null')?'null':($rawdata["ChangeinLongtermDebtNet"][$treports-7]+$rawdata["ChangeinLongtermDebtNet"][$treports-6]+$rawdata["ChangeinLongtermDebtNet"][$treports-5]+$rawdata["ChangeinLongtermDebtNet"][$treports-4])).",";
       		$query .= (($rawdata["ChangeinShorttermBorrowingsNet"][$treports-7]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-6]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-5]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-4]=='null')?'null':($rawdata["ChangeinShorttermBorrowingsNet"][$treports-7]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-6]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-5]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-4])).",";
       		$query .= (($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-7]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-6]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-5]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-4]=='null')?'null':($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-7]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-6]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-5]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-4])).",";
       		$query .= (($rawdata["CashandCashEquivalentsEndofYear"][$treports-7]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][$treports-6]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][$treports-5]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][$treports-4]=='null')?'null':($rawdata["CashandCashEquivalentsEndofYear"][$treports-7]+$rawdata["CashandCashEquivalentsEndofYear"][$treports-6]+$rawdata["CashandCashEquivalentsEndofYear"][$treports-5]+$rawdata["CashandCashEquivalentsEndofYear"][$treports-4])).",";
       		$query .= (($rawdata["CashPaidforIncomeTaxes"][$treports-7]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-6]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-5]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-4]=='null')?'null':($rawdata["CashPaidforIncomeTaxes"][$treports-7]+$rawdata["CashPaidforIncomeTaxes"][$treports-6]+$rawdata["CashPaidforIncomeTaxes"][$treports-5]+$rawdata["CashPaidforIncomeTaxes"][$treports-4])).",";
      		$query .= (($rawdata["CashPaidforInterestExpense"][$treports-7]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-6]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-5]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-4]=='null')?'null':($rawdata["CashPaidforInterestExpense"][$treports-7]+$rawdata["CashPaidforInterestExpense"][$treports-6]+$rawdata["CashPaidforInterestExpense"][$treports-5]+$rawdata["CashPaidforInterestExpense"][$treports-4])).",";
       		$query .= (($rawdata["CFNetIncome"][$treports-7]=='null'&&$rawdata["CFNetIncome"][$treports-6]=='null'&&$rawdata["CFNetIncome"][$treports-5]=='null'&&$rawdata["CFNetIncome"][$treports-4]=='null')?'null':($rawdata["CFNetIncome"][$treports-7]+$rawdata["CFNetIncome"][$treports-6]+$rawdata["CFNetIncome"][$treports-5]+$rawdata["CFNetIncome"][$treports-4])).",";
       		$query .= (($rawdata["IssuanceofEquity"][$treports-7]=='null'&&$rawdata["IssuanceofEquity"][$treports-6]=='null'&&$rawdata["IssuanceofEquity"][$treports-5]=='null'&&$rawdata["IssuanceofEquity"][$treports-4]=='null')?'null':($rawdata["IssuanceofEquity"][$treports-7]+$rawdata["IssuanceofEquity"][$treports-6]+$rawdata["IssuanceofEquity"][$treports-5]+$rawdata["IssuanceofEquity"][$treports-4])).",";
       		$query .= (($rawdata["LongtermDebtPayments"][$treports-7]=='null'&&$rawdata["LongtermDebtPayments"][$treports-6]=='null'&&$rawdata["LongtermDebtPayments"][$treports-5]=='null'&&$rawdata["LongtermDebtPayments"][$treports-4]=='null')?'null':($rawdata["LongtermDebtPayments"][$treports-7]+$rawdata["LongtermDebtPayments"][$treports-6]+$rawdata["LongtermDebtPayments"][$treports-5]+$rawdata["LongtermDebtPayments"][$treports-4])).",";
       		$query .= (($rawdata["LongtermDebtProceeds"][$treports-7]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-6]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-5]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-4]=='null')?'null':($rawdata["LongtermDebtProceeds"][$treports-7]+$rawdata["LongtermDebtProceeds"][$treports-6]+$rawdata["LongtermDebtProceeds"][$treports-5]+$rawdata["LongtermDebtProceeds"][$treports-4])).",";
      		$query .= (($rawdata["OtherDebtNet"][$treports-7]=='null'&&$rawdata["OtherDebtNet"][$treports-6]=='null'&&$rawdata["OtherDebtNet"][$treports-5]=='null'&&$rawdata["OtherDebtNet"][$treports-4]=='null')?'null':($rawdata["OtherDebtNet"][$treports-7]+$rawdata["OtherDebtNet"][$treports-6]+$rawdata["OtherDebtNet"][$treports-5]+$rawdata["OtherDebtNet"][$treports-4])).",";
       		$query .= (($rawdata["OtherEquityTransactionsNet"][$treports-7]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-6]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-5]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-4]=='null')?'null':($rawdata["OtherEquityTransactionsNet"][$treports-7]+$rawdata["OtherEquityTransactionsNet"][$treports-6]+$rawdata["OtherEquityTransactionsNet"][$treports-5]+$rawdata["OtherEquityTransactionsNet"][$treports-4])).",";
       		$query .= (($rawdata["OtherInvestmentChangesNet"][$treports-7]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-6]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-5]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-4]=='null')?'null':($rawdata["OtherInvestmentChangesNet"][$treports-7]+$rawdata["OtherInvestmentChangesNet"][$treports-6]+$rawdata["OtherInvestmentChangesNet"][$treports-5]+$rawdata["OtherInvestmentChangesNet"][$treports-4])).",";
       		$query .= (($rawdata["PurchaseofInvestments"][$treports-7]=='null'&&$rawdata["PurchaseofInvestments"][$treports-6]=='null'&&$rawdata["PurchaseofInvestments"][$treports-5]=='null'&&$rawdata["PurchaseofInvestments"][$treports-4]=='null')?'null':($rawdata["PurchaseofInvestments"][$treports-7]+$rawdata["PurchaseofInvestments"][$treports-6]+$rawdata["PurchaseofInvestments"][$treports-5]+$rawdata["PurchaseofInvestments"][$treports-4])).",";
       		$query .= (($rawdata["RepurchaseofEquity"][$treports-7]=='null'&&$rawdata["RepurchaseofEquity"][$treports-6]=='null'&&$rawdata["RepurchaseofEquity"][$treports-5]=='null'&&$rawdata["RepurchaseofEquity"][$treports-4]=='null')?'null':($rawdata["RepurchaseofEquity"][$treports-7]+$rawdata["RepurchaseofEquity"][$treports-6]+$rawdata["RepurchaseofEquity"][$treports-5]+$rawdata["RepurchaseofEquity"][$treports-4])).",";
       		$query .= (($rawdata["SaleofInvestments"][$treports-7]=='null'&&$rawdata["SaleofInvestments"][$treports-6]=='null'&&$rawdata["SaleofInvestments"][$treports-5]=='null'&&$rawdata["SaleofInvestments"][$treports-4]=='null')?'null':($rawdata["SaleofInvestments"][$treports-7]+$rawdata["SaleofInvestments"][$treports-6]+$rawdata["SaleofInvestments"][$treports-5]+$rawdata["SaleofInvestments"][$treports-4])).",";
       		$query .= (($rawdata["ShorttermBorrowings"][$treports-7]=='null'&&$rawdata["ShorttermBorrowings"][$treports-6]=='null'&&$rawdata["ShorttermBorrowings"][$treports-5]=='null'&&$rawdata["ShorttermBorrowings"][$treports-4]=='null')?'null':($rawdata["ShorttermBorrowings"][$treports-7]+$rawdata["ShorttermBorrowings"][$treports-6]+$rawdata["ShorttermBorrowings"][$treports-5]+$rawdata["ShorttermBorrowings"][$treports-4])).",";
       		$query .= (($rawdata["TotalNoncashAdjustments"][$treports-7]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-6]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-5]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-4]=='null')?'null':($rawdata["TotalNoncashAdjustments"][$treports-7]+$rawdata["TotalNoncashAdjustments"][$treports-6]+$rawdata["TotalNoncashAdjustments"][$treports-5]+$rawdata["TotalNoncashAdjustments"][$treports-4]));
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= (($rawdata["EBIT"][$treports-3]=='null'&&$rawdata["EBIT"][$treports-2]=='null'&&$rawdata["EBIT"][$treports-1]=='null'&&$rawdata["EBIT"][$treports]=='null')?'null':($rawdata["EBIT"][$treports-3]+$rawdata["EBIT"][$treports-2]+$rawdata["EBIT"][$treports-1]+$rawdata["EBIT"][$treports])).",";
       		$query .= (($rawdata["CostofRevenue"][$treports-3]=='null'&&$rawdata["CostofRevenue"][$treports-2]=='null'&&$rawdata["CostofRevenue"][$treports-1]=='null'&&$rawdata["CostofRevenue"][$treports]=='null')?'null':($rawdata["CostofRevenue"][$treports-3]+$rawdata["CostofRevenue"][$treports-2]+$rawdata["CostofRevenue"][$treports-1]+$rawdata["CostofRevenue"][$treports])).",";
       		$query .= (($rawdata["DepreciationAmortizationExpense"][$treports-3]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-2]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-1]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports]=='null')?'null':($rawdata["DepreciationAmortizationExpense"][$treports-3]+$rawdata["DepreciationAmortizationExpense"][$treports-2]+$rawdata["DepreciationAmortizationExpense"][$treports-1]+$rawdata["DepreciationAmortizationExpense"][$treports])).",";
      		$query .= (($rawdata["DilutedEPSNetIncome"][$treports-3]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-2]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-1]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports]=='null')?'null':($rawdata["DilutedEPSNetIncome"][$treports-3]+$rawdata["DilutedEPSNetIncome"][$treports-2]+$rawdata["DilutedEPSNetIncome"][$treports-1]+$rawdata["DilutedEPSNetIncome"][$treports])).",";
       		$query .= (($rawdata["DiscontinuedOperations"][$treports-3]=='null'&&$rawdata["DiscontinuedOperations"][$treports-2]=='null'&&$rawdata["DiscontinuedOperations"][$treports-1]=='null'&&$rawdata["DiscontinuedOperations"][$treports]=='null')?'null':($rawdata["DiscontinuedOperations"][$treports-3]+$rawdata["DiscontinuedOperations"][$treports-2]+$rawdata["DiscontinuedOperations"][$treports-1]+$rawdata["DiscontinuedOperations"][$treports-3])).",";
       		$query .= (($rawdata["EquityEarnings"][$treports-3]=='null'&&$rawdata["EquityEarnings"][$treports-2]=='null'&&$rawdata["EquityEarnings"][$treports-1]=='null'&&$rawdata["EquityEarnings"][$treports]=='null')?'null':($rawdata["EquityEarnings"][$treports-3]+$rawdata["EquityEarnings"][$treports-2]+$rawdata["EquityEarnings"][$treports-1]+$rawdata["EquityEarnings"][$treports])).",";
      		$query .= (($rawdata["AccountingChange"][$treports-3]=='null'&&$rawdata["AccountingChange"][$treports-2]=='null'&&$rawdata["AccountingChange"][$treports-1]=='null'&&$rawdata["AccountingChange"][$treports]=='null')?'null':($rawdata["AccountingChange"][$treports-3]+$rawdata["AccountingChange"][$treports-2]+$rawdata["AccountingChange"][$treports-1]+$rawdata["AccountingChange"][$treports])).",";
       		$query .= (($rawdata["BasicEPSNetIncome"][$treports-3]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-2]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-1]=='null'&&$rawdata["BasicEPSNetIncome"][$treports]=='null')?'null':($rawdata["BasicEPSNetIncome"][$treports-3]+$rawdata["BasicEPSNetIncome"][$treports-2]+$rawdata["BasicEPSNetIncome"][$treports-1]+$rawdata["BasicEPSNetIncome"][$treports])).",";
       		$query .= (($rawdata["ExtraordinaryItems"][$treports-3]=='null'&&$rawdata["ExtraordinaryItems"][$treports-2]=='null'&&$rawdata["ExtraordinaryItems"][$treports-1]=='null'&&$rawdata["ExtraordinaryItems"][$treports]=='null')?'null':($rawdata["ExtraordinaryItems"][$treports-3]+$rawdata["ExtraordinaryItems"][$treports-2]+$rawdata["ExtraordinaryItems"][$treports-1]+$rawdata["ExtraordinaryItems"][$treports])).",";
       		$query .= (($rawdata["GrossProfit"][$treports-3]=='null'&&$rawdata["GrossProfit"][$treports-2]=='null'&&$rawdata["GrossProfit"][$treports-1]=='null'&&$rawdata["GrossProfit"][$treports]=='null')?'null':($rawdata["GrossProfit"][$treports-3]+$rawdata["GrossProfit"][$treports-2]+$rawdata["GrossProfit"][$treports-1]+$rawdata["GrossProfit"][$treports])).",";
       		$query .= (($rawdata["IncomebeforeExtraordinaryItems"][$treports-3]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-2]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-1]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports]=='null')?'null':($rawdata["IncomebeforeExtraordinaryItems"][$treports-3]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-2]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-1]+$rawdata["IncomebeforeExtraordinaryItems"][$treports])).",";
       		$query .= (($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports])).",";
       		$query .= (($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')?'null':($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports])).",";
       		$query .= (($rawdata["InterestExpense"][$treports-3]=='null'&&$rawdata["InterestExpense"][$treports-2]=='null'&&$rawdata["InterestExpense"][$treports-1]=='null'&&$rawdata["InterestExpense"][$treports]=='null')?'null':(toFloat($rawdata["InterestExpense"][$treports-3])+toFloat($rawdata["InterestExpense"][$treports-2])+toFloat($rawdata["InterestExpense"][$treports-1])+toFloat($rawdata["InterestExpense"][$treports]))).",";
       		$query .= (($rawdata["InterestIncome"][$treports-3]=='null'&&$rawdata["InterestIncome"][$treports-2]=='null'&&$rawdata["InterestIncome"][$treports-1]=='null'&&$rawdata["InterestIncome"][$treports]=='null')?'null':(toFloat($rawdata["InterestIncome"][$treports-3])+toFloat($rawdata["InterestIncome"][$treports-2])+toFloat($rawdata["InterestIncome"][$treports-1])+toFloat($rawdata["InterestIncome"][$treports]))).",";
       		$query .= (($rawdata["MinorityInterestEquityEarnings"][$treports-3]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-2]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-1]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports]=='null')?'null':($rawdata["MinorityInterestEquityEarnings"][$treports-3]+$rawdata["MinorityInterestEquityEarnings"][$treports-2]+$rawdata["MinorityInterestEquityEarnings"][$treports-1]+$rawdata["MinorityInterestEquityEarnings"][$treports])).",";
       		$query .= (($rawdata["NetIncome"][$treports-3]=='null'&&$rawdata["NetIncome"][$treports-2]=='null'&&$rawdata["NetIncome"][$treports-1]=='null'&&$rawdata["NetIncome"][$treports]=='null')?'null':($rawdata["NetIncome"][$treports-3]+$rawdata["NetIncome"][$treports-2]+$rawdata["NetIncome"][$treports-1]+$rawdata["NetIncome"][$treports])).",";
       		$query .= (($rawdata["NetIncomeApplicabletoCommon"][$treports-3]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-2]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-1]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports]=='null')?'null':($rawdata["NetIncomeApplicabletoCommon"][$treports-3]+$rawdata["NetIncomeApplicabletoCommon"][$treports-2]+$rawdata["NetIncomeApplicabletoCommon"][$treports-1]+$rawdata["NetIncomeApplicabletoCommon"][$treports])).",";
       		$query .= (($rawdata["OperatingProfit"][$treports-3]=='null'&&$rawdata["OperatingProfit"][$treports-2]=='null'&&$rawdata["OperatingProfit"][$treports-1]=='null'&&$rawdata["OperatingProfit"][$treports]=='null')?'null':($rawdata["OperatingProfit"][$treports-3]+$rawdata["OperatingProfit"][$treports-2]+$rawdata["OperatingProfit"][$treports-1]+$rawdata["OperatingProfit"][$treports])).",";
       		$query .= (($rawdata["OtherNonoperatingIncomeExpense"][$treports-3]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-2]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-1]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports]=='null')?'null':($rawdata["OtherNonoperatingIncomeExpense"][$treports-3]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-2]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-1]+$rawdata["OtherNonoperatingIncomeExpense"][$treports])).",";
      		$query .= (($rawdata["OtherOperatingExpenses"][$treports-3]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-2]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-1]=='null'&&$rawdata["OtherOperatingExpenses"][$treports]=='null')?'null':($rawdata["OtherOperatingExpenses"][$treports-3]+$rawdata["OtherOperatingExpenses"][$treports-2]+$rawdata["OtherOperatingExpenses"][$treports-1]+$rawdata["OtherOperatingExpenses"][$treports])).",";
       		$query .= (($rawdata["ResearchDevelopmentExpense"][$treports-3]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-2]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-1]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports]=='null')?'null':($rawdata["ResearchDevelopmentExpense"][$treports-3]+$rawdata["ResearchDevelopmentExpense"][$treports-2]+$rawdata["ResearchDevelopmentExpense"][$treports-1]+$rawdata["ResearchDevelopmentExpense"][$treports])).",";
       		$query .= (($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-3]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-2]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-1]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports]=='null')?'null':($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-3]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-2]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-1]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports])).",";
       		$query .= (($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')?'null':($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])).",";
       		$query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports]=='null')?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports]));
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= (($rawdata["EBIT"][$treports-7]=='null'&&$rawdata["EBIT"][$treports-6]=='null'&&$rawdata["EBIT"][$treports-5]=='null'&&$rawdata["EBIT"][$treports-4]=='null')?'null':($rawdata["EBIT"][$treports-7]+$rawdata["EBIT"][$treports-6]+$rawdata["EBIT"][$treports-5]+$rawdata["EBIT"][$treports-4])).",";
       		$query .= (($rawdata["CostofRevenue"][$treports-7]=='null'&&$rawdata["CostofRevenue"][$treports-6]=='null'&&$rawdata["CostofRevenue"][$treports-5]=='null'&&$rawdata["CostofRevenue"][$treports-4]=='null')?'null':($rawdata["CostofRevenue"][$treports-7]+$rawdata["CostofRevenue"][$treports-6]+$rawdata["CostofRevenue"][$treports-5]+$rawdata["CostofRevenue"][$treports-4])).",";
       		$query .= (($rawdata["DepreciationAmortizationExpense"][$treports-7]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-6]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-5]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-4]=='null')?'null':($rawdata["DepreciationAmortizationExpense"][$treports-7]+$rawdata["DepreciationAmortizationExpense"][$treports-6]+$rawdata["DepreciationAmortizationExpense"][$treports-5]+$rawdata["DepreciationAmortizationExpense"][$treports-4])).",";
      		$query .= (($rawdata["DilutedEPSNetIncome"][$treports-7]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-6]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-5]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-4]=='null')?'null':($rawdata["DilutedEPSNetIncome"][$treports-7]+$rawdata["DilutedEPSNetIncome"][$treports-6]+$rawdata["DilutedEPSNetIncome"][$treports-5]+$rawdata["DilutedEPSNetIncome"][$treports-4])).",";
       		$query .= (($rawdata["DiscontinuedOperations"][$treports-7]=='null'&&$rawdata["DiscontinuedOperations"][$treports-6]=='null'&&$rawdata["DiscontinuedOperations"][$treports-5]=='null'&&$rawdata["DiscontinuedOperations"][$treports-4]=='null')?'null':($rawdata["DiscontinuedOperations"][$treports-7]+$rawdata["DiscontinuedOperations"][$treports-6]+$rawdata["DiscontinuedOperations"][$treports-5]+$rawdata["DiscontinuedOperations"][$treports-7])).",";
       		$query .= (($rawdata["EquityEarnings"][$treports-7]=='null'&&$rawdata["EquityEarnings"][$treports-6]=='null'&&$rawdata["EquityEarnings"][$treports-5]=='null'&&$rawdata["EquityEarnings"][$treports-4]=='null')?'null':($rawdata["EquityEarnings"][$treports-7]+$rawdata["EquityEarnings"][$treports-6]+$rawdata["EquityEarnings"][$treports-5]+$rawdata["EquityEarnings"][$treports-4])).",";
      		$query .= (($rawdata["AccountingChange"][$treports-7]=='null'&&$rawdata["AccountingChange"][$treports-6]=='null'&&$rawdata["AccountingChange"][$treports-5]=='null'&&$rawdata["AccountingChange"][$treports-4]=='null')?'null':($rawdata["AccountingChange"][$treports-7]+$rawdata["AccountingChange"][$treports-6]+$rawdata["AccountingChange"][$treports-5]+$rawdata["AccountingChange"][$treports-4])).",";
       		$query .= (($rawdata["BasicEPSNetIncome"][$treports-7]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-6]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-5]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-4]=='null')?'null':($rawdata["BasicEPSNetIncome"][$treports-7]+$rawdata["BasicEPSNetIncome"][$treports-6]+$rawdata["BasicEPSNetIncome"][$treports-5]+$rawdata["BasicEPSNetIncome"][$treports-4])).",";
       		$query .= (($rawdata["ExtraordinaryItems"][$treports-7]=='null'&&$rawdata["ExtraordinaryItems"][$treports-6]=='null'&&$rawdata["ExtraordinaryItems"][$treports-5]=='null'&&$rawdata["ExtraordinaryItems"][$treports-4]=='null')?'null':($rawdata["ExtraordinaryItems"][$treports-7]+$rawdata["ExtraordinaryItems"][$treports-6]+$rawdata["ExtraordinaryItems"][$treports-5]+$rawdata["ExtraordinaryItems"][$treports-4])).",";
       		$query .= (($rawdata["GrossProfit"][$treports-7]=='null'&&$rawdata["GrossProfit"][$treports-6]=='null'&&$rawdata["GrossProfit"][$treports-5]=='null'&&$rawdata["GrossProfit"][$treports-4]=='null')?'null':($rawdata["GrossProfit"][$treports-7]+$rawdata["GrossProfit"][$treports-6]+$rawdata["GrossProfit"][$treports-5]+$rawdata["GrossProfit"][$treports-4])).",";
       		$query .= (($rawdata["IncomebeforeExtraordinaryItems"][$treports-7]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-6]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-5]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-4]=='null')?'null':($rawdata["IncomebeforeExtraordinaryItems"][$treports-7]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-6]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-5]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-4])).",";
       		$query .= (($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4])).",";
       		$query .= (($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')?'null':($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4])).",";
       		$query .= (($rawdata["InterestExpense"][$treports-7]=='null'&&$rawdata["InterestExpense"][$treports-6]=='null'&&$rawdata["InterestExpense"][$treports-5]=='null'&&$rawdata["InterestExpense"][$treports-4]=='null')?'null':(toFloat($rawdata["InterestExpense"][$treports-7])+toFloat($rawdata["InterestExpense"][$treports-6])+toFloat($rawdata["InterestExpense"][$treports-5])+toFloat($rawdata["InterestExpense"][$treports-4]))).",";
       		$query .= (($rawdata["InterestIncome"][$treports-7]=='null'&&$rawdata["InterestIncome"][$treports-6]=='null'&&$rawdata["InterestIncome"][$treports-5]=='null'&&$rawdata["InterestIncome"][$treports-4]=='null')?'null':(toFloat($rawdata["InterestIncome"][$treports-7])+toFloat($rawdata["InterestIncome"][$treports-6])+toFloat($rawdata["InterestIncome"][$treports-5])+toFloat($rawdata["InterestIncome"][$treports-4]))).",";
       		$query .= (($rawdata["MinorityInterestEquityEarnings"][$treports-7]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-6]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-5]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-4]=='null')?'null':($rawdata["MinorityInterestEquityEarnings"][$treports-7]+$rawdata["MinorityInterestEquityEarnings"][$treports-6]+$rawdata["MinorityInterestEquityEarnings"][$treports-5]+$rawdata["MinorityInterestEquityEarnings"][$treports-4])).",";
       		$query .= (($rawdata["NetIncome"][$treports-7]=='null'&&$rawdata["NetIncome"][$treports-6]=='null'&&$rawdata["NetIncome"][$treports-5]=='null'&&$rawdata["NetIncome"][$treports-4]=='null')?'null':($rawdata["NetIncome"][$treports-7]+$rawdata["NetIncome"][$treports-6]+$rawdata["NetIncome"][$treports-5]+$rawdata["NetIncome"][$treports-4])).",";
       		$query .= (($rawdata["NetIncomeApplicabletoCommon"][$treports-7]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-6]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-5]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-4]=='null')?'null':($rawdata["NetIncomeApplicabletoCommon"][$treports-7]+$rawdata["NetIncomeApplicabletoCommon"][$treports-6]+$rawdata["NetIncomeApplicabletoCommon"][$treports-5]+$rawdata["NetIncomeApplicabletoCommon"][$treports-4])).",";
       		$query .= (($rawdata["OperatingProfit"][$treports-7]=='null'&&$rawdata["OperatingProfit"][$treports-6]=='null'&&$rawdata["OperatingProfit"][$treports-5]=='null'&&$rawdata["OperatingProfit"][$treports-4]=='null')?'null':($rawdata["OperatingProfit"][$treports-7]+$rawdata["OperatingProfit"][$treports-6]+$rawdata["OperatingProfit"][$treports-5]+$rawdata["OperatingProfit"][$treports-4])).",";
       		$query .= (($rawdata["OtherNonoperatingIncomeExpense"][$treports-7]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-6]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-5]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-4]=='null')?'null':($rawdata["OtherNonoperatingIncomeExpense"][$treports-7]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-6]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-5]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-4])).",";
      		$query .= (($rawdata["OtherOperatingExpenses"][$treports-7]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-6]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-5]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-4]=='null')?'null':($rawdata["OtherOperatingExpenses"][$treports-7]+$rawdata["OtherOperatingExpenses"][$treports-6]+$rawdata["OtherOperatingExpenses"][$treports-5]+$rawdata["OtherOperatingExpenses"][$treports-4])).",";
       		$query .= (($rawdata["ResearchDevelopmentExpense"][$treports-7]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-6]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-5]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-4]=='null')?'null':($rawdata["ResearchDevelopmentExpense"][$treports-7]+$rawdata["ResearchDevelopmentExpense"][$treports-6]+$rawdata["ResearchDevelopmentExpense"][$treports-5]+$rawdata["ResearchDevelopmentExpense"][$treports-4])).",";
       		$query .= (($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-7]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-6]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-5]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-4]=='null')?'null':($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-7]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-6]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-5]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-4])).",";
       		$query .= (($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')?'null':($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])).",";
       		$query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]=='null')?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]));
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= (($rawdata["AdjustedEBIT"][$treports-3]=='null'&&$rawdata["AdjustedEBIT"][$treports-2]=='null'&&$rawdata["AdjustedEBIT"][$treports-1]=='null'&&$rawdata["AdjustedEBIT"][$treports]=='null')?'null':($rawdata["AdjustedEBIT"][$treports-3]+$rawdata["AdjustedEBIT"][$treports-2]+$rawdata["AdjustedEBIT"][$treports-1]+$rawdata["AdjustedEBIT"][$treports])).",";
       		$query .= (($rawdata["AdjustedEBITDA"][$treports-3]=='null'&&$rawdata["AdjustedEBITDA"][$treports-2]=='null'&&$rawdata["AdjustedEBITDA"][$treports-1]=='null'&&$rawdata["AdjustedEBITDA"][$treports]=='null')?'null':($rawdata["AdjustedEBITDA"][$treports-3]+$rawdata["AdjustedEBITDA"][$treports-2]+$rawdata["AdjustedEBITDA"][$treports-1]+$rawdata["AdjustedEBITDA"][$treports])).",";
      		$query .= (($rawdata["AdjustedNetIncome"][$treports-3]=='null'&&$rawdata["AdjustedNetIncome"][$treports-3]=='null'&&$rawdata["AdjustedNetIncome"][$treports-1]=='null'&&$rawdata["AdjustedNetIncome"][$treports]=='null')?'null':($rawdata["AdjustedNetIncome"][$treports-3]+$rawdata["AdjustedNetIncome"][$treports-2]+$rawdata["AdjustedNetIncome"][$treports-1]+$rawdata["AdjustedNetIncome"][$treports])).",";
		$divisor = 4;
		if($rawdata["AftertaxMargin"][$treports-3]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports-2]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports-1]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports]=='null') {$divisor--;}
       		$query .= (($divisor==0)?'null':(($rawdata["AftertaxMargin"][$treports-3]+$rawdata["AftertaxMargin"][$treports-2]+$rawdata["AftertaxMargin"][$treports-1]+$rawdata["AftertaxMargin"][$treports])/$divisor)).",";
       		$query .= (($rawdata["EBITDA"][$treports-3]=='null'&&$rawdata["EBITDA"][$treports-2]=='null'&&$rawdata["EBITDA"][$treports-1]=='null'&&$rawdata["EBITDA"][$treports]=='null')?'null':($rawdata["EBITDA"][$treports-3]+$rawdata["EBITDA"][$treports-2]+$rawdata["EBITDA"][$treports-1]+$rawdata["EBITDA"][$treports])).",";
		$divisor = 4;
		if($rawdata["GrossMargin"][$treports-3]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-2]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-1]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports]=='null') {$divisor--;}
      		$query .= (($divisor==0)?'null':(($rawdata["GrossMargin"][$treports-3]+$rawdata["GrossMargin"][$treports-2]+$rawdata["GrossMargin"][$treports-1]+$rawdata["GrossMargin"][$treports])/$divisor)).",";
       		$query .= (($rawdata["NetOperatingProfitafterTax"][$treports-3]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-2]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-1]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports]=='null')?'null':($rawdata["NetOperatingProfitafterTax"][$treports-3]+$rawdata["NetOperatingProfitafterTax"][$treports-2]+$rawdata["NetOperatingProfitafterTax"][$treports-1]+$rawdata["NetOperatingProfitafterTax"][$treports])).",";
		$divisor = 4;
		if($rawdata["OperatingMargin"][$treports-3]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-2]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-1]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports]=='null') {$divisor--;}
       		$query .= (($divisor==0)?'null':(($rawdata["OperatingMargin"][$treports-3]+$rawdata["OperatingMargin"][$treports-2]+$rawdata["OperatingMargin"][$treports-1]+$rawdata["OperatingMargin"][$treports])/$divisor)).",";
       		$query .= (($rawdata["RevenueFQ"][$treports-3]=='null'&&$rawdata["RevenueFQ"][$treports-2]=='null'&&$rawdata["RevenueFQ"][$treports-1]=='null'&&$rawdata["RevenueFQ"][$treports]=='null')?'null':($rawdata["RevenueFQ"][$treports-3]+$rawdata["RevenueFQ"][$treports-2]+$rawdata["RevenueFQ"][$treports-1]+$rawdata["RevenueFQ"][$treports])).",";
      		$query .= (($rawdata["RevenueFY"][$treports-3]=='null'&&$rawdata["RevenueFY"][$treports-2]=='null'&&$rawdata["RevenueFY"][$treports-1]=='null'&&$rawdata["RevenueFY"][$treports]=='null')?'null':($rawdata["RevenueFY"][$treports-3]+$rawdata["RevenueFY"][$treports-2]+$rawdata["RevenueFY"][$treports-1]+$rawdata["RevenueFY"][$treports])).",";
       		$query .= (($rawdata["RevenueTTM"][$treports-3]=='null'&&$rawdata["RevenueTTM"][$treports-2]=='null'&&$rawdata["RevenueTTM"][$treports-1]=='null'&&$rawdata["RevenueTTM"][$treports]=='null')?'null':($rawdata["RevenueTTM"][$treports-3]+$rawdata["RevenueTTM"][$treports-2]+$rawdata["RevenueTTM"][$treports-1]+$rawdata["RevenueTTM"][$treports])).",";
       		$query .= (($rawdata["CostOperatingExpenses"][$treports-3]=='null'&&$rawdata["CostOperatingExpenses"][$treports-2]=='null'&&$rawdata["CostOperatingExpenses"][$treports-1]=='null'&&$rawdata["CostOperatingExpenses"][$treports]=='null')?'null':($rawdata["CostOperatingExpenses"][$treports-3]+$rawdata["CostOperatingExpenses"][$treports-2]+$rawdata["CostOperatingExpenses"][$treports-1]+$rawdata["CostOperatingExpenses"][$treports])).",";
       		$query .= (($rawdata["DepreciationExpense"][$treports-3]=='null'&&$rawdata["DepreciationExpense"][$treports-2]=='null'&&$rawdata["DepreciationExpense"][$treports-1]=='null'&&$rawdata["DepreciationExpense"][$treports]=='null')?'null':($rawdata["DepreciationExpense"][$treports-3]+$rawdata["DepreciationExpense"][$treports-2]+$rawdata["DepreciationExpense"][$treports-1]+$rawdata["DepreciationExpense"][$treports])).",";
      		$query .= (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-3]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-2]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-1]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports]=='null')?'null':($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-3]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-2]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-1]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports])).",";
                $query .= $rawdata["DilutedWeightedAverageShares"][$MRQRow].",";
       		$query .= (($rawdata["AmortizationExpense"][$treports-3]=='null'&&$rawdata["AmortizationExpense"][$treports-2]=='null'&&$rawdata["AmortizationExpense"][$treports-1]=='null'&&$rawdata["AmortizationExpense"][$treports]=='null')?'null':($rawdata["AmortizationExpense"][$treports-3]+$rawdata["AmortizationExpense"][$treports-2]+$rawdata["AmortizationExpense"][$treports-1]+$rawdata["AmortizationExpense"][$treports])).",";
       		$query .= (($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-3]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-2]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-1]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports]=='null')?'null':($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-3]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-2]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-1]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports])).",";
                $query .= $rawdata["BasicWeightedAverageShares"][$MRQRow].",";
      		$query .= (($rawdata["GeneralAdministrativeExpense"][$treports-3]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-2]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-1]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports]=='null')?'null':($rawdata["GeneralAdministrativeExpense"][$treports-3]+$rawdata["GeneralAdministrativeExpense"][$treports-2]+$rawdata["GeneralAdministrativeExpense"][$treports-1]+$rawdata["GeneralAdministrativeExpense"][$treports])).",";
       		$query .= (($rawdata["IncomeAfterTaxes"][$treports-3]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-2]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-1]=='null'&&$rawdata["IncomeAfterTaxes"][$treports]=='null')?'null':($rawdata["IncomeAfterTaxes"][$treports-3]+$rawdata["IncomeAfterTaxes"][$treports-2]+$rawdata["IncomeAfterTaxes"][$treports-1]+$rawdata["IncomeAfterTaxes"][$treports])).",";
       		$query .= (($rawdata["LaborExpense"][$treports-3]=='null'&&$rawdata["LaborExpense"][$treports-2]=='null'&&$rawdata["LaborExpense"][$treports-1]=='null'&&$rawdata["LaborExpense"][$treports]=='null')?'null':($rawdata["LaborExpense"][$treports-3]+$rawdata["LaborExpense"][$treports-2]+$rawdata["LaborExpense"][$treports-1]+$rawdata["LaborExpense"][$treports])).",";
       		$query .= (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-3]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-2]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-1]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports]=='null')?'null':($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-3]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-2]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-1]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports])).",";
       		$query .= (($rawdata["InterestIncomeExpenseNet"][$treports-3]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-2]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-1]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports]=='null')?'null':($rawdata["InterestIncomeExpenseNet"][$treports-3]+$rawdata["InterestIncomeExpenseNet"][$treports-2]+$rawdata["InterestIncomeExpenseNet"][$treports-1]+$rawdata["InterestIncomeExpenseNet"][$treports])).",";
       		$query .= (($rawdata["NoncontrollingInterest"][$treports-3]=='null'&&$rawdata["NoncontrollingInterest"][$treports-2]=='null'&&$rawdata["NoncontrollingInterest"][$treports-1]=='null'&&$rawdata["NoncontrollingInterest"][$treports]=='null')?'null':($rawdata["NoncontrollingInterest"][$treports-3]+$rawdata["NoncontrollingInterest"][$treports-2]+$rawdata["NoncontrollingInterest"][$treports-1]+$rawdata["NoncontrollingInterest"][$treports])).",";
       		$query .= (($rawdata["NonoperatingGainsLosses"][$treports-3]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-2]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-1]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports]=='null')?'null':($rawdata["NonoperatingGainsLosses"][$treports-3]+$rawdata["NonoperatingGainsLosses"][$treports-2]+$rawdata["NonoperatingGainsLosses"][$treports-1]+$rawdata["NonoperatingGainsLosses"][$treports])).",";
       		$query .= (($rawdata["OperatingExpenses"][$treports-3]=='null'&&$rawdata["OperatingExpenses"][$treports-2]=='null'&&$rawdata["OperatingExpenses"][$treports-1]=='null'&&$rawdata["OperatingExpenses"][$treports]=='null')?'null':($rawdata["OperatingExpenses"][$treports-3]+$rawdata["OperatingExpenses"][$treports-2]+$rawdata["OperatingExpenses"][$treports-1]+$rawdata["OperatingExpenses"][$treports])).",";
       		$query .= (($rawdata["OtherGeneralAdministrativeExpense"][$treports-3]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-2]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-1]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports]=='null')?'null':($rawdata["OtherGeneralAdministrativeExpense"][$treports-3]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-2]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-1]+$rawdata["OtherGeneralAdministrativeExpense"][$treports])).",";
       		$query .= (($rawdata["OtherInterestIncomeExpenseNet"][$treports-3]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-2]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-1]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports]=='null')?'null':($rawdata["OtherInterestIncomeExpenseNet"][$treports-3]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-2]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-1]+$rawdata["OtherInterestIncomeExpenseNet"][$treports])).",";
       		$query .= (($rawdata["OtherRevenue"][$treports-3]=='null'&&$rawdata["OtherRevenue"][$treports-2]=='null'&&$rawdata["OtherRevenue"][$treports-1]=='null'&&$rawdata["OtherRevenue"][$treports]=='null')?'null':($rawdata["OtherRevenue"][$treports-3]+$rawdata["OtherRevenue"][$treports-2]+$rawdata["OtherRevenue"][$treports-1]+$rawdata["OtherRevenue"][$treports])).",";
       		$query .= (($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports]=='null')?'null':($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports])).",";
      		$query .= (($rawdata["PreferredDividends"][$treports-3]=='null'&&$rawdata["PreferredDividends"][$treports-2]=='null'&&$rawdata["PreferredDividends"][$treports-1]=='null'&&$rawdata["PreferredDividends"][$treports]=='null')?'null':($rawdata["PreferredDividends"][$treports-3]+$rawdata["PreferredDividends"][$treports-2]+$rawdata["PreferredDividends"][$treports-1]+$rawdata["PreferredDividends"][$treports])).",";
       		$query .= (($rawdata["SalesMarketingExpense"][$treports-3]=='null'&&$rawdata["SalesMarketingExpense"][$treports-2]=='null'&&$rawdata["SalesMarketingExpense"][$treports-1]=='null'&&$rawdata["SalesMarketingExpense"][$treports]=='null')?'null':($rawdata["SalesMarketingExpense"][$treports-3]+$rawdata["SalesMarketingExpense"][$treports-2]+$rawdata["SalesMarketingExpense"][$treports-1]+$rawdata["SalesMarketingExpense"][$treports])).",";
       		$query .= (($rawdata["TotalNonoperatingIncomeExpense"][$treports-3]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-2]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-1]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports]=='null')?'null':($rawdata["TotalNonoperatingIncomeExpense"][$treports-3]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-2]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-1]+$rawdata["TotalNonoperatingIncomeExpense"][$treports])).",";
       		$query .= (($rawdata["TotalOperatingExpenses"][$treports-3]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-2]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-1]=='null'&&$rawdata["TotalOperatingExpenses"][$treports]=='null')?'null':($rawdata["TotalOperatingExpenses"][$treports-3]+$rawdata["TotalOperatingExpenses"][$treports-2]+$rawdata["TotalOperatingExpenses"][$treports-1]+$rawdata["TotalOperatingExpenses"][$treports])).",";
       		$query .= (($rawdata["OperatingRevenue"][$treports-3]=='null'&&$rawdata["OperatingRevenue"][$treports-2]=='null'&&$rawdata["OperatingRevenue"][$treports-1]=='null'&&$rawdata["OperatingRevenue"][$treports]=='null')?'null':($rawdata["OperatingRevenue"][$treports-3]+$rawdata["OperatingRevenue"][$treports-2]+$rawdata["OperatingRevenue"][$treports-1]+$rawdata["OperatingRevenue"][$treports]));
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= (($rawdata["AdjustedEBIT"][$treports-7]=='null'&&$rawdata["AdjustedEBIT"][$treports-6]=='null'&&$rawdata["AdjustedEBIT"][$treports-5]=='null'&&$rawdata["AdjustedEBIT"][$treports-4]=='null')?'null':($rawdata["AdjustedEBIT"][$treports-7]+$rawdata["AdjustedEBIT"][$treports-6]+$rawdata["AdjustedEBIT"][$treports-5]+$rawdata["AdjustedEBIT"][$treports-4])).",";
       		$query .= (($rawdata["AdjustedEBITDA"][$treports-7]=='null'&&$rawdata["AdjustedEBITDA"][$treports-6]=='null'&&$rawdata["AdjustedEBITDA"][$treports-5]=='null'&&$rawdata["AdjustedEBITDA"][$treports-4]=='null')?'null':($rawdata["AdjustedEBITDA"][$treports-7]+$rawdata["AdjustedEBITDA"][$treports-6]+$rawdata["AdjustedEBITDA"][$treports-5]+$rawdata["AdjustedEBITDA"][$treports-4])).",";
      		$query .= (($rawdata["AdjustedNetIncome"][$treports-7]=='null'&&$rawdata["AdjustedNetIncome"][$treports-6]=='null'&&$rawdata["AdjustedNetIncome"][$treports-5]=='null'&&$rawdata["AdjustedNetIncome"][$treports-4]=='null')?'null':($rawdata["AdjustedNetIncome"][$treports-7]+$rawdata["AdjustedNetIncome"][$treports-6]+$rawdata["AdjustedNetIncome"][$treports-5]+$rawdata["AdjustedNetIncome"][$treports-4])).",";
                $divisor = 4;
                if($rawdata["AftertaxMargin"][$treports-7]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][$treports-6]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][$treports-5]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][$treports-4]=='null') {$divisor--;}
       		$query .= (($divisor==0)?'null':(($rawdata["AftertaxMargin"][$treports-7]+$rawdata["AftertaxMargin"][$treports-6]+$rawdata["AftertaxMargin"][$treports-5]+$rawdata["AftertaxMargin"][$treports-4])/$divisor)).",";
       		$query .= (($rawdata["EBITDA"][$treports-7]=='null'&&$rawdata["EBITDA"][$treports-6]=='null'&&$rawdata["EBITDA"][$treports-5]=='null'&&$rawdata["EBITDA"][$treports-4]=='null')?'null':($rawdata["EBITDA"][$treports-7]+$rawdata["EBITDA"][$treports-6]+$rawdata["EBITDA"][$treports-5]+$rawdata["EBITDA"][$treports-4])).",";
		$divisor = 4;
		if($rawdata["GrossMargin"][$treports-7]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-6]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-5]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-4]=='null') {$divisor--;}
      		$query .= (($divisor==0)?'null':(($rawdata["GrossMargin"][$treports-7]+$rawdata["GrossMargin"][$treports-6]+$rawdata["GrossMargin"][$treports-5]+$rawdata["GrossMargin"][$treports-4])/$divisor)).",";
       		$query .= (($rawdata["NetOperatingProfitafterTax"][$treports-7]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-6]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-5]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-4]=='null')?'null':($rawdata["NetOperatingProfitafterTax"][$treports-7]+$rawdata["NetOperatingProfitafterTax"][$treports-6]+$rawdata["NetOperatingProfitafterTax"][$treports-5]+$rawdata["NetOperatingProfitafterTax"][$treports-4])).",";
		$divisor = 4;
		if($rawdata["OperatingMargin"][$treports-7]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-6]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-5]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-4]=='null') {$divisor--;}
       		$query .= (($divisor==0)?'null':(($rawdata["OperatingMargin"][$treports-7]+$rawdata["OperatingMargin"][$treports-6]+$rawdata["OperatingMargin"][$treports-5]+$rawdata["OperatingMargin"][$treports-4])/$divisor)).",";
       		$query .= (($rawdata["RevenueFQ"][$treports-7]=='null'&&$rawdata["RevenueFQ"][$treports-6]=='null'&&$rawdata["RevenueFQ"][$treports-5]=='null'&&$rawdata["RevenueFQ"][$treports-4]=='null')?'null':($rawdata["RevenueFQ"][$treports-7]+$rawdata["RevenueFQ"][$treports-6]+$rawdata["RevenueFQ"][$treports-5]+$rawdata["RevenueFQ"][$treports-4])).",";
      		$query .= (($rawdata["RevenueFY"][$treports-7]=='null'&&$rawdata["RevenueFY"][$treports-6]=='null'&&$rawdata["RevenueFY"][$treports-5]=='null'&&$rawdata["RevenueFY"][$treports-4]=='null')?'null':($rawdata["RevenueFY"][$treports-7]+$rawdata["RevenueFY"][$treports-6]+$rawdata["RevenueFY"][$treports-5]+$rawdata["RevenueFY"][$treports-4])).",";
       		$query .= (($rawdata["RevenueTTM"][$treports-7]=='null'&&$rawdata["RevenueTTM"][$treports-6]=='null'&&$rawdata["RevenueTTM"][$treports-5]=='null'&&$rawdata["RevenueTTM"][$treports-4]=='null')?'null':($rawdata["RevenueTTM"][$treports-7]+$rawdata["RevenueTTM"][$treports-6]+$rawdata["RevenueTTM"][$treports-5]+$rawdata["RevenueTTM"][$treports-4])).",";
       		$query .= (($rawdata["CostOperatingExpenses"][$treports-7]=='null'&&$rawdata["CostOperatingExpenses"][$treports-6]=='null'&&$rawdata["CostOperatingExpenses"][$treports-5]=='null'&&$rawdata["CostOperatingExpenses"][$treports-4]=='null')?'null':($rawdata["CostOperatingExpenses"][$treports-7]+$rawdata["CostOperatingExpenses"][$treports-6]+$rawdata["CostOperatingExpenses"][$treports-5]+$rawdata["CostOperatingExpenses"][$treports-4])).",";
       		$query .= (($rawdata["DepreciationExpense"][$treports-7]=='null'&&$rawdata["DepreciationExpense"][$treports-6]=='null'&&$rawdata["DepreciationExpense"][$treports-5]=='null'&&$rawdata["DepreciationExpense"][$treports-4]=='null')?'null':($rawdata["DepreciationExpense"][$treports-7]+$rawdata["DepreciationExpense"][$treports-6]+$rawdata["DepreciationExpense"][$treports-5]+$rawdata["DepreciationExpense"][$treports-4])).",";
      		$query .= (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-7]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-6]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-5]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-4]=='null')?'null':($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-7]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-6]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-5]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-4])).",";
                $query .= $rawdata["DilutedWeightedAverageShares"][$PMRQRow].",";
       		$query .= (($rawdata["AmortizationExpense"][$treports-7]=='null'&&$rawdata["AmortizationExpense"][$treports-6]=='null'&&$rawdata["AmortizationExpense"][$treports-5]=='null'&&$rawdata["AmortizationExpense"][$treports-4]=='null')?'null':($rawdata["AmortizationExpense"][$treports-7]+$rawdata["AmortizationExpense"][$treports-6]+$rawdata["AmortizationExpense"][$treports-5]+$rawdata["AmortizationExpense"][$treports-4])).",";
       		$query .= (($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-7]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-6]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-5]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-4]=='null')?'null':($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-7]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-6]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-5]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-4])).",";
                $query .= $rawdata["BasicWeightedAverageShares"][$PMRQRow].",";
      		$query .= (($rawdata["GeneralAdministrativeExpense"][$treports-7]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-6]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-5]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-4]=='null')?'null':($rawdata["GeneralAdministrativeExpense"][$treports-7]+$rawdata["GeneralAdministrativeExpense"][$treports-6]+$rawdata["GeneralAdministrativeExpense"][$treports-5]+$rawdata["GeneralAdministrativeExpense"][$treports-4])).",";
       		$query .= (($rawdata["IncomeAfterTaxes"][$treports-7]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-6]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-5]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-4]=='null')?'null':($rawdata["IncomeAfterTaxes"][$treports-7]+$rawdata["IncomeAfterTaxes"][$treports-6]+$rawdata["IncomeAfterTaxes"][$treports-5]+$rawdata["IncomeAfterTaxes"][$treports-4])).",";
       		$query .= (($rawdata["LaborExpense"][$treports-7]=='null'&&$rawdata["LaborExpense"][$treports-6]=='null'&&$rawdata["LaborExpense"][$treports-5]=='null'&&$rawdata["LaborExpense"][$treports-4]=='null')?'null':($rawdata["LaborExpense"][$treports-7]+$rawdata["LaborExpense"][$treports-6]+$rawdata["LaborExpense"][$treports-5]+$rawdata["LaborExpense"][$treports-4])).",";
       		$query .= (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-7]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-6]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-5]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-4]=='null')?'null':($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-7]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-6]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-5]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-4])).",";
       		$query .= (($rawdata["InterestIncomeExpenseNet"][$treports-7]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-6]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-5]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-4]=='null')?'null':($rawdata["InterestIncomeExpenseNet"][$treports-7]+$rawdata["InterestIncomeExpenseNet"][$treports-6]+$rawdata["InterestIncomeExpenseNet"][$treports-5]+$rawdata["InterestIncomeExpenseNet"][$treports-4])).",";
       		$query .= (($rawdata["NoncontrollingInterest"][$treports-7]=='null'&&$rawdata["NoncontrollingInterest"][$treports-6]=='null'&&$rawdata["NoncontrollingInterest"][$treports-5]=='null'&&$rawdata["NoncontrollingInterest"][$treports-4]=='null')?'null':($rawdata["NoncontrollingInterest"][$treports-7]+$rawdata["NoncontrollingInterest"][$treports-6]+$rawdata["NoncontrollingInterest"][$treports-5]+$rawdata["NoncontrollingInterest"][$treports-4])).",";
       		$query .= (($rawdata["NonoperatingGainsLosses"][$treports-7]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-6]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-5]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-4]=='null')?'null':($rawdata["NonoperatingGainsLosses"][$treports-7]+$rawdata["NonoperatingGainsLosses"][$treports-6]+$rawdata["NonoperatingGainsLosses"][$treports-5]+$rawdata["NonoperatingGainsLosses"][$treports-4])).",";
       		$query .= (($rawdata["OperatingExpenses"][$treports-7]=='null'&&$rawdata["OperatingExpenses"][$treports-6]=='null'&&$rawdata["OperatingExpenses"][$treports-5]=='null'&&$rawdata["OperatingExpenses"][$treports-4]=='null')?'null':($rawdata["OperatingExpenses"][$treports-7]+$rawdata["OperatingExpenses"][$treports-6]+$rawdata["OperatingExpenses"][$treports-5]+$rawdata["OperatingExpenses"][$treports-4])).",";
       		$query .= (($rawdata["OtherGeneralAdministrativeExpense"][$treports-7]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-6]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-5]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-4]=='null')?'null':($rawdata["OtherGeneralAdministrativeExpense"][$treports-7]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-6]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-5]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-4])).",";
       		$query .= (($rawdata["OtherInterestIncomeExpenseNet"][$treports-7]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-6]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-5]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-4]=='null')?'null':($rawdata["OtherInterestIncomeExpenseNet"][$treports-7]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-6]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-5]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-4])).",";
       		$query .= (($rawdata["OtherRevenue"][$treports-7]=='null'&&$rawdata["OtherRevenue"][$treports-6]=='null'&&$rawdata["OtherRevenue"][$treports-5]=='null'&&$rawdata["OtherRevenue"][$treports-4]=='null')?'null':($rawdata["OtherRevenue"][$treports-7]+$rawdata["OtherRevenue"][$treports-6]+$rawdata["OtherRevenue"][$treports-5]+$rawdata["OtherRevenue"][$treports-4])).",";
       		$query .= (($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-4]=='null')?'null':($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-4])).",";
      		$query .= (($rawdata["PreferredDividends"][$treports-7]=='null'&&$rawdata["PreferredDividends"][$treports-6]=='null'&&$rawdata["PreferredDividends"][$treports-5]=='null'&&$rawdata["PreferredDividends"][$treports-4]=='null')?'null':($rawdata["PreferredDividends"][$treports-7]+$rawdata["PreferredDividends"][$treports-6]+$rawdata["PreferredDividends"][$treports-5]+$rawdata["PreferredDividends"][$treports-4])).",";
       		$query .= (($rawdata["SalesMarketingExpense"][$treports-7]=='null'&&$rawdata["SalesMarketingExpense"][$treports-6]=='null'&&$rawdata["SalesMarketingExpense"][$treports-5]=='null'&&$rawdata["SalesMarketingExpense"][$treports-4]=='null')?'null':($rawdata["SalesMarketingExpense"][$treports-7]+$rawdata["SalesMarketingExpense"][$treports-6]+$rawdata["SalesMarketingExpense"][$treports-5]+$rawdata["SalesMarketingExpense"][$treports-4])).",";
       		$query .= (($rawdata["TotalNonoperatingIncomeExpense"][$treports-7]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-6]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-5]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-4]=='null')?'null':($rawdata["TotalNonoperatingIncomeExpense"][$treports-7]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-6]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-5]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-4])).",";
       		$query .= (($rawdata["TotalOperatingExpenses"][$treports-7]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-6]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-5]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-4]=='null')?'null':($rawdata["TotalOperatingExpenses"][$treports-7]+$rawdata["TotalOperatingExpenses"][$treports-6]+$rawdata["TotalOperatingExpenses"][$treports-5]+$rawdata["TotalOperatingExpenses"][$treports-4])).",";
       		$query .= (($rawdata["OperatingRevenue"][$treports-7]=='null'&&$rawdata["OperatingRevenue"][$treports-6]=='null'&&$rawdata["OperatingRevenue"][$treports-5]=='null'&&$rawdata["OperatingRevenue"][$treports-4]=='null')?'null':($rawdata["OperatingRevenue"][$treports-7]+$rawdata["OperatingRevenue"][$treports-6]+$rawdata["OperatingRevenue"][$treports-5]+$rawdata["OperatingRevenue"][$treports-4]));
       		$query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

                $query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= ((($rawdata["CostofRevenue"][$treports-3]=='null'&&$rawdata["CostofRevenue"][$treports-2]=='null'&&$rawdata["CostofRevenue"][$treports-1]=='null'&&$rawdata["CostofRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["CostofRevenue"][$treports-3]+$rawdata["CostofRevenue"][$treports-2]+$rawdata["CostofRevenue"][$treports-1]+$rawdata["CostofRevenue"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["GrossProfit"][$treports-3]=='null'&&$rawdata["GrossProfit"][$treports-2]=='null'&&$rawdata["GrossProfit"][$treports-1]=='null'&&$rawdata["GrossProfit"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["GrossProfit"][$treports-3]+$rawdata["GrossProfit"][$treports-2]+$rawdata["GrossProfit"][$treports-1]+$rawdata["GrossProfit"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["ResearchDevelopmentExpense"][$treports-3]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-2]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-1]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["ResearchDevelopmentExpense"][$treports-3]+$rawdata["ResearchDevelopmentExpense"][$treports-2]+$rawdata["ResearchDevelopmentExpense"][$treports-1]+$rawdata["ResearchDevelopmentExpense"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["EBITDA"][$treports-3]=='null'&&$rawdata["EBITDA"][$treports-2]=='null'&&$rawdata["EBITDA"][$treports-1]=='null'&&$rawdata["EBITDA"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["EBITDA"][$treports-3]+$rawdata["EBITDA"][$treports-2]+$rawdata["EBITDA"][$treports-1]+$rawdata["EBITDA"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["OperatingProfit"][$treports-3]=='null'&&$rawdata["OperatingProfit"][$treports-2]=='null'&&$rawdata["OperatingProfit"][$treports-1]=='null'&&$rawdata["OperatingProfit"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["OperatingProfit"][$treports-3]+$rawdata["OperatingProfit"][$treports-2]+$rawdata["OperatingProfit"][$treports-1]+$rawdata["OperatingProfit"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["EBIT"][$treports-3]=='null'&&$rawdata["EBIT"][$treports-2]=='null'&&$rawdata["EBIT"][$treports-1]=='null'&&$rawdata["EBIT"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["EBIT"][$treports-3]+$rawdata["EBIT"][$treports-2]+$rawdata["EBIT"][$treports-1]+$rawdata["EBIT"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
                $query .= ((($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports]==0))?'null':(($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports])/($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports]))).",";
                $query .= ((($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')&&($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null'))?'null':(($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports])-($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports]))).",";
                $query .= ((($rawdata["NetIncome"][$treports-3]=='null'&&$rawdata["NetIncome"][$treports-2]=='null'&&$rawdata["NetIncome"][$treports-1]=='null'&&$rawdata["NetIncome"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?'null':(($rawdata["NetIncome"][$treports-3]+$rawdata["NetIncome"][$treports-2]+$rawdata["NetIncome"][$treports-1]+$rawdata["NetIncome"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]))).",";
		$value = 0;
		if(($rawdata["DividendsPaid"][$treports-3]=='null'&&$rawdata["DividendsPaid"][$treports-2]=='null'&&$rawdata["DividendsPaid"][$treports-1]=='null'&&$rawdata["DividendsPaid"][$treports]=='null')||($rawdata["SharesOutstandingBasic"][$treports-3]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-2]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-1]=='null'&&$rawdata["SharesOutstandingBasic"][$treports]=='null')||($rawdata["SharesOutstandingBasic"][$treports-3]+$rawdata["SharesOutstandingBasic"][$treports-2]+$rawdata["SharesOutstandingBasic"][$treports-1]+$rawdata["SharesOutstandingBasic"][$treports]==0)) {
			$value = "'null'";
		} else {
			if($rawdata["DividendsPaid"][$treports-3]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-3]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-3]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-3]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-3])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-2]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-2]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-2]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-2]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-2])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-1]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-1]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-1]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-1]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-1])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports]!='null'&&$rawdata["SharesOutstandingBasic"][$treports]!='null'&&$rawdata["SharesOutstandingBasic"][$treports]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports]/(toFloat($rawdata["SharesOutstandingBasic"][$treports])*1000000));
			}
		}
		$query .= $value.",";
		$query .= ((($rawdata["CurrentPortionofLongtermDebt"][$treports-3]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-2]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-1]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports]=='null')&&($rawdata["ShorttermBorrowings"][$treports-3]=='null'&&$rawdata["ShorttermBorrowings"][$treports-2]=='null'&&$rawdata["ShorttermBorrowings"][$treports-1]=='null'&&$rawdata["ShorttermBorrowings"][$treports]=='null'))?'null':($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow])).",";
		$query .= ((($rawdata["TotalLongtermDebt"][$treports-3]=='null'&&$rawdata["TotalLongtermDebt"][$treports-2]=='null'&&$rawdata["TotalLongtermDebt"][$treports-1]=='null'&&$rawdata["TotalLongtermDebt"][$treports]=='null')&&($rawdata["NotesPayable"][$treports-3]=='null'&&$rawdata["NotesPayable"][$treports-2]=='null'&&$rawdata["NotesPayable"][$treports-1]=='null'&&$rawdata["NotesPayable"][$treports]=='null'))?'null':($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow])).",";
                $query .= ((($rawdata["LongtermDebtProceeds"][$treports-3]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-2]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-1]=='null'&&$rawdata["LongtermDebtProceeds"][$treports]=='null')&&($rawdata["LongtermDebtPayments"][$treports-3]=='null'&&$rawdata["LongtermDebtPayments"][$treports-2]=='null'&&$rawdata["LongtermDebtPayments"][$treports-1]=='null'&&$rawdata["LongtermDebtPayments"][$treports]=='null'))?'null':(($rawdata["LongtermDebtProceeds"][$treports-3]+$rawdata["LongtermDebtProceeds"][$treports-2]+$rawdata["LongtermDebtProceeds"][$treports-1]+$rawdata["LongtermDebtProceeds"][$treports])+($rawdata["LongtermDebtPayments"][$treports-3]+$rawdata["LongtermDebtPayments"][$treports-2]+$rawdata["LongtermDebtPayments"][$treports-1]+$rawdata["LongtermDebtPayments"][$treports]))).",";
                $query .= (($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')?'null':(-($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports]))).",";
                $query .= ((($rawdata["CashfromOperatingActivities"][$treports-3]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-2]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-1]=='null'&&$rawdata["CashfromOperatingActivities"][$treports]=='null')&&($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null'))?'null':(($rawdata["CashfromOperatingActivities"][$treports-3]+$rawdata["CashfromOperatingActivities"][$treports-2]+$rawdata["CashfromOperatingActivities"][$treports-1]+$rawdata["CashfromOperatingActivities"][$treports])+($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports]))).",";
                $query .= ((($rawdata["CFNetIncome"][$treports-3]=='null'&&$rawdata["CFNetIncome"][$treports-2]=='null'&&$rawdata["CFNetIncome"][$treports-1]=='null'&&$rawdata["CFNetIncome"][$treports]=='null')&&($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')&&($rawdata["EmployeeCompensation"][$treports-3]=='null'&&$rawdata["EmployeeCompensation"][$treports-2]=='null'&&$rawdata["EmployeeCompensation"][$treports-1]=='null'&&$rawdata["EmployeeCompensation"][$treports]=='null')&&($rawdata["AdjustmentforSpecialCharges"][$treports-3]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-2]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-1]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports]=='null')&&($rawdata["DeferredIncomeTaxes"][$treports-3]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-2]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-1]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports]=='null')&&($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')&&($rawdata["ChangeinCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports]=='null')&&($rawdata["ChangeinCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports]=='null'))?'null':
			(($rawdata["CFNetIncome"][$treports-3]+$rawdata["CFNetIncome"][$treports-2]+$rawdata["CFNetIncome"][$treports-1]+$rawdata["CFNetIncome"][$treports])+($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports])+($rawdata["EmployeeCompensation"][$treports-3]+$rawdata["EmployeeCompensation"][$treports-2]+$rawdata["EmployeeCompensation"][$treports-1]+$rawdata["EmployeeCompensation"][$treports])+($rawdata["AdjustmentforSpecialCharges"][$treports-3]+$rawdata["AdjustmentforSpecialCharges"][$treports-2]+$rawdata["AdjustmentforSpecialCharges"][$treports-1]+$rawdata["AdjustmentforSpecialCharges"][$treports])+($rawdata["DeferredIncomeTaxes"][$treports-3]+$rawdata["DeferredIncomeTaxes"][$treports-2]+$rawdata["DeferredIncomeTaxes"][$treports-1]+$rawdata["DeferredIncomeTaxes"][$treports])+($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])+(($rawdata["ChangeinCurrentAssets"][$treports-3]+$rawdata["ChangeinCurrentAssets"][$treports-2]+$rawdata["ChangeinCurrentAssets"][$treports-1]+$rawdata["ChangeinCurrentAssets"][$treports])+($rawdata["ChangeinCurrentLiabilities"][$treports-3]+$rawdata["ChangeinCurrentLiabilities"][$treports-2]+$rawdata["ChangeinCurrentLiabilities"][$treports-1]+$rawdata["ChangeinCurrentLiabilities"][$treports])))).",";
		$query .= ((($rawdata["TotalRevenue"][$treports-3]=='null' && $rawdata["TotalRevenue"][$treports-2]=='null' && $rawdata["TotalRevenue"][$treports-1]=='null' && $rawdata["TotalRevenue"][$treports]=='null') || $rawdata["TotalRevenue"][$areports-5]=='null' || $rawdata["TotalRevenue"][$areports-5]<=0 || ($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports] < 0))?'null':(pow(($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])/$rawdata["TotalRevenue"][$areports-5], 1/5) - 1));
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());

                $query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= ((($rawdata["CostofRevenue"][$treports-7]=='null'&&$rawdata["CostofRevenue"][$treports-6]=='null'&&$rawdata["CostofRevenue"][$treports-5]=='null'&&$rawdata["CostofRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["CostofRevenue"][$treports-7]+$rawdata["CostofRevenue"][$treports-6]+$rawdata["CostofRevenue"][$treports-5]+$rawdata["CostofRevenue"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["GrossProfit"][$treports-7]=='null'&&$rawdata["GrossProfit"][$treports-6]=='null'&&$rawdata["GrossProfit"][$treports-5]=='null'&&$rawdata["GrossProfit"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["GrossProfit"][$treports-7]+$rawdata["GrossProfit"][$treports-6]+$rawdata["GrossProfit"][$treports-5]+$rawdata["GrossProfit"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["ResearchDevelopmentExpense"][$treports-7]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-6]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-5]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["ResearchDevelopmentExpense"][$treports-7]+$rawdata["ResearchDevelopmentExpense"][$treports-6]+$rawdata["ResearchDevelopmentExpense"][$treports-5]+$rawdata["ResearchDevelopmentExpense"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["EBITDA"][$treports-7]=='null'&&$rawdata["EBITDA"][$treports-6]=='null'&&$rawdata["EBITDA"][$treports-5]=='null'&&$rawdata["EBITDA"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["EBITDA"][$treports-7]+$rawdata["EBITDA"][$treports-6]+$rawdata["EBITDA"][$treports-5]+$rawdata["EBITDA"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["OperatingProfit"][$treports-7]=='null'&&$rawdata["OperatingProfit"][$treports-6]=='null'&&$rawdata["OperatingProfit"][$treports-5]=='null'&&$rawdata["OperatingProfit"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["OperatingProfit"][$treports-7]+$rawdata["OperatingProfit"][$treports-6]+$rawdata["OperatingProfit"][$treports-5]+$rawdata["OperatingProfit"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["EBIT"][$treports-7]=='null'&&$rawdata["EBIT"][$treports-6]=='null'&&$rawdata["EBIT"][$treports-5]=='null'&&$rawdata["EBIT"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["EBIT"][$treports-7]+$rawdata["EBIT"][$treports-6]+$rawdata["EBIT"][$treports-5]+$rawdata["EBIT"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
                $query .= ((($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4]==0))?'null':(($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4])/($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4]))).",";
                $query .= ((($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')&&($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null'))?'null':(($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4])-($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4]))).",";
                $query .= ((($rawdata["NetIncome"][$treports-7]=='null'&&$rawdata["NetIncome"][$treports-6]=='null'&&$rawdata["NetIncome"][$treports-5]=='null'&&$rawdata["NetIncome"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?'null':(($rawdata["NetIncome"][$treports-7]+$rawdata["NetIncome"][$treports-6]+$rawdata["NetIncome"][$treports-5]+$rawdata["NetIncome"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]))).",";
		$value = 0;
		if(($rawdata["DividendsPaid"][$treports-7]=='null'&&$rawdata["DividendsPaid"][$treports-6]=='null'&&$rawdata["DividendsPaid"][$treports-5]=='null'&&$rawdata["DividendsPaid"][$treports-4]=='null')||($rawdata["SharesOutstandingBasic"][$treports-7]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-6]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-5]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-4]=='null')||($rawdata["SharesOutstandingBasic"][$treports-7]+$rawdata["SharesOutstandingBasic"][$treports-6]+$rawdata["SharesOutstandingBasic"][$treports-5]+$rawdata["SharesOutstandingBasic"][$treports-4]==0)) {
			$value = "'null'";
		} else {
			if($rawdata["DividendsPaid"][$treports-7]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-7]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-7]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-7]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-7])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-6]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-6]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-6]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-6]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-6])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-5]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-5]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-5]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-5]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-5])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-4]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-4]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-4]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-4]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-4])*1000000));
			}
		}
		$query .= $value.",";
		$query .= ((($rawdata["CurrentPortionofLongtermDebt"][$treports-7]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-6]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-5]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-4]=='null')&&($rawdata["ShorttermBorrowings"][$treports-7]=='null'&&$rawdata["ShorttermBorrowings"][$treports-6]=='null'&&$rawdata["ShorttermBorrowings"][$treports-5]=='null'&&$rawdata["ShorttermBorrowings"][$treports-4]=='null'))?'null':($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow])).",";
		$query .= ((($rawdata["TotalLongtermDebt"][$treports-7]=='null'&&$rawdata["TotalLongtermDebt"][$treports-6]=='null'&&$rawdata["TotalLongtermDebt"][$treports-5]=='null'&&$rawdata["TotalLongtermDebt"][$treports-4]=='null')&&($rawdata["NotesPayable"][$treports-7]=='null'&&$rawdata["NotesPayable"][$treports-6]=='null'&&$rawdata["NotesPayable"][$treports-5]=='null'&&$rawdata["NotesPayable"][$treports-4]=='null'))?'null':($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow])).",";
                $query .= ((($rawdata["LongtermDebtProceeds"][$treports-7]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-6]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-5]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-4]=='null')&&($rawdata["LongtermDebtPayments"][$treports-7]=='null'&&$rawdata["LongtermDebtPayments"][$treports-6]=='null'&&$rawdata["LongtermDebtPayments"][$treports-5]=='null'&&$rawdata["LongtermDebtPayments"][$treports-4]=='null'))?'null':(($rawdata["LongtermDebtProceeds"][$treports-7]+$rawdata["LongtermDebtProceeds"][$treports-6]+$rawdata["LongtermDebtProceeds"][$treports-5]+$rawdata["LongtermDebtProceeds"][$treports-4])+($rawdata["LongtermDebtPayments"][$treports-7]+$rawdata["LongtermDebtPayments"][$treports-6]+$rawdata["LongtermDebtPayments"][$treports-5]+$rawdata["LongtermDebtPayments"][$treports-4]))).",";
                $query .= (($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')?'null':(-($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4]))).",";
                $query .= ((($rawdata["CashfromOperatingActivities"][$treports-7]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-6]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-5]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-4]=='null')&&($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null'))?'null':(($rawdata["CashfromOperatingActivities"][$treports-7]+$rawdata["CashfromOperatingActivities"][$treports-6]+$rawdata["CashfromOperatingActivities"][$treports-5]+$rawdata["CashfromOperatingActivities"][$treports-4])+($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4]))).",";
                $query .= ((($rawdata["CFNetIncome"][$treports-7]=='null'&&$rawdata["CFNetIncome"][$treports-6]=='null'&&$rawdata["CFNetIncome"][$treports-5]=='null'&&$rawdata["CFNetIncome"][$treports-4]=='null')&&($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')&&($rawdata["EmployeeCompensation"][$treports-7]=='null'&&$rawdata["EmployeeCompensation"][$treports-6]=='null'&&$rawdata["EmployeeCompensation"][$treports-5]=='null'&&$rawdata["EmployeeCompensation"][$treports-4]=='null')&&($rawdata["AdjustmentforSpecialCharges"][$treports-7]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-6]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-5]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-4]=='null')&&($rawdata["DeferredIncomeTaxes"][$treports-7]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-6]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-5]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-4]=='null')&&($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')&&($rawdata["ChangeinCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-4]=='null')&&($rawdata["ChangeinCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-4]=='null'))?'null':
			(($rawdata["CFNetIncome"][$treports-7]+$rawdata["CFNetIncome"][$treports-6]+$rawdata["CFNetIncome"][$treports-5]+$rawdata["CFNetIncome"][$treports-4])+($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4])+($rawdata["EmployeeCompensation"][$treports-7]+$rawdata["EmployeeCompensation"][$treports-6]+$rawdata["EmployeeCompensation"][$treports-5]+$rawdata["EmployeeCompensation"][$treports-4])+($rawdata["AdjustmentforSpecialCharges"][$treports-7]+$rawdata["AdjustmentforSpecialCharges"][$treports-6]+$rawdata["AdjustmentforSpecialCharges"][$treports-5]+$rawdata["AdjustmentforSpecialCharges"][$treports-4])+($rawdata["DeferredIncomeTaxes"][$treports-7]+$rawdata["DeferredIncomeTaxes"][$treports-6]+$rawdata["DeferredIncomeTaxes"][$treports-5]+$rawdata["DeferredIncomeTaxes"][$treports-4])+($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])+(($rawdata["ChangeinCurrentAssets"][$treports-7]+$rawdata["ChangeinCurrentAssets"][$treports-6]+$rawdata["ChangeinCurrentAssets"][$treports-5]+$rawdata["ChangeinCurrentAssets"][$treports-4])+($rawdata["ChangeinCurrentLiabilities"][$treports-7]+$rawdata["ChangeinCurrentLiabilities"][$treports-6]+$rawdata["ChangeinCurrentLiabilities"][$treports-5]+$rawdata["ChangeinCurrentLiabilities"][$treports-4]))));
        	$query .= ")";
	       	mysql_query($query) or die ($query."\n".mysql_error());
	}
}

function toFloat($num) {
    if (is_null($num)) {
	return 'null';
    }
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
   
    if (!$sep) {
        return floatval(preg_replace("/[^\-0-9]/", "", $num));
    } 

    return floatval(
        preg_replace("/[^\-0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^\-0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}

function updateCAGR($table, $fieldArray, $years, $period, $report_id, $rawdata, $toFloat = false) {
	$query = "INSERT INTO `$table` (`report_id`";
	foreach ($fieldArray as $value) {
		$query .= ",`$value`";
	}
	$query .= ") VALUES (";
	$query .= "'".$report_id."'";
	foreach ($fieldArray as $value) {
        	if ($rawdata[$value][$period]=='null' || $rawdata[$value][$period-$years]=='null' || $rawdata[$value][$period-$years]<=0 || $rawdata[$value][$period] < 0) {
	        	$query .= ",null";
	        } else {
			if ($toFloat) {
        	        	$query .= ",".(pow(toFloat($rawdata[$value][$period])/toFloat($rawdata[$value][$period-$years]), 1/$years) - 1);
			} else {
        	        	$query .= ",".(pow($rawdata[$value][$period]/$rawdata[$value][$period-$years], 1/$years) - 1);
			}
	        }
	}
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
}

function updateCAGR_concat($vv, $va, $years) {
        if ($va=='null' || $vv=='null' || $vv<=0 || $va < 0) {
                return ",null";
        } else {
                return ",".(pow($va/$vv, 1/$years) - 1);
        }
}

function updateCAGR_FC($table, $years, $i, $report_id, $rawdata) {
        $query = "INSERT INTO `$table` (`report_id`";
	$query .= ", `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `SalesPercChange`) VALUES (";
        $query .= "'".$report_id."'";

        $va = (($rawdata["CostofRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CostofRevenue"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["CostofRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["CostofRevenue"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["GrossProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["GrossProfit"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["GrossProfit"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["GrossProfit"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null' ||  $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years]=='null' ||  $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["ResearchDevelopmentExpense"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["ResearchDevelopmentExpense"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CFDepreciationAmortization"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CFDepreciationAmortization"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["CFDepreciationAmortization"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["CFDepreciationAmortization"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["EBITDA"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["EBITDA"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["EBITDA"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["EBITDA"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["OperatingProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["OperatingProfit"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["OperatingProfit"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["OperatingProfit"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["EBIT"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["EBIT"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["EBIT"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["EBIT"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["IncomeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]==0)?'null':($rawdata["IncomeTaxes"][$i]/$rawdata["IncomeBeforeTaxes"][$i]));
        $vv = (($rawdata["IncomeTaxes"][$i-$years]=='null' || $rawdata["IncomeBeforeTaxes"][$i-$years]=='null' || $rawdata["IncomeBeforeTaxes"][$i-$years]==0)?'null':($rawdata["IncomeTaxes"][$i-$years]/$rawdata["IncomeBeforeTaxes"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["IncomeBeforeTaxes"][$i]=='null' && $rawdata["IncomeTaxes"][$i]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$i]-$rawdata["IncomeTaxes"][$i]));
        $vv = (($rawdata["IncomeBeforeTaxes"][$i-$years]=='null' && $rawdata["IncomeTaxes"][$i-$years]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$i-$years]-$rawdata["IncomeTaxes"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["NetIncome"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["NetIncome"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["NetIncome"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["NetIncome"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["DividendsPaid"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]==0)?'null':(-($rawdata["DividendsPaid"][$i])/(toFloat($rawdata["SharesOutstandingBasic"][$i])*1000000)));
        $vv = (($rawdata["DividendsPaid"][$i-$years]=='null' || $rawdata["SharesOutstandingBasic"][$i-$years]=='null' || $rawdata["SharesOutstandingBasic"][$i-$years]==0)?'null':(-($rawdata["DividendsPaid"][$i-$years])/(toFloat($rawdata["SharesOutstandingBasic"][$i-$years])*1000000)));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CurrentPortionofLongtermDebt"][$i]=='null' && $rawdata["ShorttermBorrowings"][$i]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["ShorttermBorrowings"][$i]));
        $vv = (($rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null' && $rawdata["ShorttermBorrowings"][$i-$years]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["ShorttermBorrowings"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["NotesPayable"][$i]=='null')?'null':($rawdata["TotalLongtermDebt"][$i]+$rawdata["NotesPayable"][$i]));
        $vv = (($rawdata["TotalLongtermDebt"][$i-$years]=='null' && $rawdata["NotesPayable"][$i-$years]=='null')?'null':($rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["NotesPayable"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["LongtermDebtProceeds"][$i]=='null' && $rawdata["LongtermDebtPayments"][$i] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$i]+$rawdata["LongtermDebtPayments"][$i]));
        $vv = (($rawdata["LongtermDebtProceeds"][$i-$years]=='null' && $rawdata["LongtermDebtPayments"][$i-$years] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$i-$years]+$rawdata["LongtermDebtPayments"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CapitalExpenditures"][$i]=='null')?'null':(-$rawdata["CapitalExpenditures"][$i]));
        $vv = (($rawdata["CapitalExpenditures"][$i-$years]=='null')?'null':(-$rawdata["CapitalExpenditures"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?'null':($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i]));
        $vv = (($rawdata["CashfromOperatingActivities"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null')?'null':($rawdata["CashfromOperatingActivities"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?'null':($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i])));
        $vv = (($rawdata["CFNetIncome"][$i-$years]=='null' && $rawdata["CFDepreciationAmortization"][$i-$years]=='null' && $rawdata["EmployeeCompensation"][$i-$years]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i-$years]=='null' && $rawdata["DeferredIncomeTaxes"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null' && $rawdata["ChangeinCurrentAssets"][$i-$years]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i-$years]=='null')?'null':($rawdata["CFNetIncome"][$i-$years]+$rawdata["CFDepreciationAmortization"][$i-$years]+$rawdata["EmployeeCompensation"][$i-$years]+$rawdata["AdjustmentforSpecialCharges"][$i-$years]+$rawdata["DeferredIncomeTaxes"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]+($rawdata["ChangeinCurrentAssets"][$i-$years]+$rawdata["ChangeinCurrentLiabilities"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        if ($i - $years > 1) {
                $va = ((($rawdata["TotalRevenue"][$i]=='null' && $rawdata["TotalRevenue"][$i-1]=='null') || $rawdata["TotalRevenue"][$i-1]=='null' || $rawdata["TotalRevenue"][$i-1]==0)?'null':(($rawdata["TotalRevenue"][$i]-$rawdata["TotalRevenue"][$i-1])/$rawdata["TotalRevenue"][$i-1]));
                $vv = ((($rawdata["TotalRevenue"][$i-$years]=='null' && $rawdata["TotalRevenue"][$i-$years-1]=='null') || $rawdata["TotalRevenue"][$i-$years-1]=='null' || $rawdata["TotalRevenue"][$i-$years-1]==0)?'null':(($rawdata["TotalRevenue"][$i-$years]-$rawdata["TotalRevenue"][$i-$years-1])/$rawdata["TotalRevenue"][$i-$years-1]));
		$query .= updateCAGR_concat($vv, $va, $years);
        } else {
                $query .= ",null";
        }

        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
}

function updateCAGR_KR($table, $years, $i, $report_id, $rawdata, $ticker_id) {
	$CapEx_a = (($rawdata["CapitalExpenditures"][$i]=='null')?null:(-$rawdata["CapitalExpenditures"][$i]));
	$CapEx_v = (($rawdata["CapitalExpenditures"][$i-$years]=='null')?null:(-$rawdata["CapitalExpenditures"][$i-$years]));
	$FreeCashFlow_a = (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?null:($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i]));
	$FreeCashFlow_v = (($rawdata["CashfromOperatingActivities"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null')?null:($rawdata["CashfromOperatingActivities"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]));
	$OwnerEarningsFCF_a = (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?null:($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i])));
	$OwnerEarningsFCF_v = (($rawdata["CFNetIncome"][$i-$years]=='null' && $rawdata["CFDepreciationAmortization"][$i-$years]=='null' && $rawdata["EmployeeCompensation"][$i-$years]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i-$years]=='null' && $rawdata["DeferredIncomeTaxes"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null' && $rawdata["ChangeinCurrentAssets"][$i-$years]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i-$years]=='null')?null:($rawdata["CFNetIncome"][$i-$years]+$rawdata["CFDepreciationAmortization"][$i-$years]+$rawdata["EmployeeCompensation"][$i-$years]+$rawdata["AdjustmentforSpecialCharges"][$i-$years]+$rawdata["DeferredIncomeTaxes"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]+($rawdata["ChangeinCurrentAssets"][$i-$years]+$rawdata["ChangeinCurrentLiabilities"][$i-$years])));
        $arpy_a = $rawdata["AccountsReceivableTradeNet"][$i-1]=='null'?null:$rawdata["AccountsReceivableTradeNet"][$i-1];
	$inpy_a = $rawdata["InventoriesNet"][$i-1]=='null'?null:$rawdata["InventoriesNet"][$i-1];
	if($i - $years == 1) {
		$arpy_v = $inpy_v = 0;
	} else {
                $arpy_v = $rawdata["AccountsReceivableTradeNet"][$i-$years-1]=='null'?null:$rawdata["AccountsReceivableTradeNet"][$i-$years-1];
	        $inpy_v = $rawdata["InventoriesNet"][$i-$years-1]=='null'?null:$rawdata["InventoriesNet"][$i-$years-1];
	}
	$rdate_a = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]));
	$qquote_a = "Select * from tickers_yahoo_historical_data where ticker_id = '".$ticker_id."' and report_date <= '".$rdate_a."' order by report_date desc limit 1";
	$price_a = null;
        $rquote_a = mysql_query($qquote_a) or die (mysql_error());
	if(mysql_num_rows($rquote_a) > 0) {
             	$price_a = mysql_fetch_assoc($rquote_a);
	        $price_a = $price_a["adj_close"];
	}
	$rdate_v = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i-$years]));
	$qquote_v = "Select * from tickers_yahoo_historical_data where ticker_id = '".$ticker_id."' and report_date <= '".$rdate_v."' order by report_date desc limit 1";
	$price_v = null;
        $rquote_v = mysql_query($qquote_v) or die (mysql_error());
	if(mysql_num_rows($rquote_v) > 0) {
             	$price_v = mysql_fetch_assoc($rquote_v);
	        $price_v = $price_v["adj_close"];
	}
        $entValue_a = (($rawdata["SharesOutstandingDiluted"][$i]=='null' && is_null($price_a) && $rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["TotalShorttermDebt"][$i]=='null' && $rawdata["PreferredStock"][$i]=='null' && $rawdata["MinorityInterestEquityEarnings"][$i]=='null' && $rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null')?null:((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price_a)+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalShorttermDebt"][$i]+$rawdata["PreferredStock"][$i]+$rawdata["MinorityInterestEquityEarnings"][$i]-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]));
        $entValue_v = (($rawdata["SharesOutstandingDiluted"][$i-$years]=='null' && is_null($price_v) && $rawdata["TotalLongtermDebt"][$i-$years]=='null' && $rawdata["TotalShorttermDebt"][$i-$years]=='null' && $rawdata["PreferredStock"][$i-$years]=='null' && $rawdata["MinorityInterestEquityEarnings"][$i-$years]=='null' && $rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null')?null:((toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000*$price_v)+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["PreferredStock"][$i-$years]+$rawdata["MinorityInterestEquityEarnings"][$i-$years]-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]));

	$query = "INSERT INTO $table (`report_id`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (";
	$query .= "'".$report_id."'";
        $va = $price_a;
        $vv = $price_v;
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null' && is_null($CapEx_a)) || $rawdata["TaxRatePercent"][$i]=='null')?'null':(($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx_a)*(1-$rawdata["TaxRatePercent"][$i])));
        $vv = ((($rawdata["GrossProfit"][$i-$years]=='null'&&$rawdata["OperatingExpenses"][$i-$years]=='null' && is_null($CapEx_v)) || $rawdata["TaxRatePercent"][$i-$years]=='null')?'null':(($rawdata["GrossProfit"][$i-$years]-$rawdata["OperatingExpenses"][$i-$years]-$CapEx_v)*(1-$rawdata["TaxRatePercent"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["SharesOutstandingDiluted"][$i]=='null'||is_null($price_a))?'null':(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price_a));
	$vv = (($rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||is_null($price_v))?'null':(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000*$price_v));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = $entValue_a;
        $vv = $entValue_v;
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = $rawdata["GoodwillIntangibleAssetsNet"][$i];
	$vv = $rawdata["GoodwillIntangibleAssetsNet"][$i-$years];
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')?'null':($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i]));
        $vv = (($rawdata["TotalStockholdersEquity"][$i-$years]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i-$years]=='null')?'null':($rawdata["TotalStockholdersEquity"][$i-$years] - $rawdata["GoodwillIntangibleAssetsNet"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null' ||($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null'&&$rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["LongtermInvestments"][$i]=='null'))?'null':(($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] + $rawdata["LongtermInvestments"][$i]) - max(0, ($rawdata["TotalCurrentLiabilities"][$i]-$rawdata["TotalCurrentAssets"][$i]+$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]))));
	$vv = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null' ||($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null'&&$rawdata["TotalCurrentLiabilities"][$i-$years]=='null'&&$rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["LongtermInvestments"][$i-$years]=='null'))?'null':(($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years] + $rawdata["LongtermInvestments"][$i-$years]) - max(0, ($rawdata["TotalCurrentLiabilities"][$i-$years]-$rawdata["TotalCurrentAssets"][$i-$years]+$rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')?'null':($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]));
        $vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')?'null':($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')?'null':($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i]));
	$vv = (($rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["TotalCurrentLiabilities"][$i-$years]=='null')?'null':($rawdata["TotalCurrentAssets"][$i-$years] - $rawdata["TotalCurrentLiabilities"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?'null':($price_a / toFloat($rawdata["EPSDiluted"][$i])));
        $vv = ((is_null($price_v)||$rawdata["EPSDiluted"][$i-$years]=='null'||$rawdata["EPSDiluted"][$i-$years]==0)?'null':($price_v / toFloat($rawdata["EPSDiluted"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?'null':((((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price_a)-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/toFloat($rawdata["EPSDiluted"][$i])));
	$vv = (($rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||$rawdata["EPSDiluted"][$i-$years]=='null'||$rawdata["EPSDiluted"][$i-$years]==0)?'null':((((toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000*$price_v)-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years])/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))/toFloat($rawdata["EPSDiluted"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($entValue_a)||$rawdata["EBITDA"][$i]=='null'||$rawdata["EBITDA"][$i]==0)?'null':($entValue_a / $rawdata["EBITDA"][$i]));
        $vv = ((is_null($entValue_v)||$rawdata["EBITDA"][$i-$years]=='null'||$rawdata["EBITDA"][$i-$years]==0)?'null':($entValue_v / $rawdata["EBITDA"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($entValue_a)||$rawdata["EBIT"][$i]=='null'||$rawdata["EBIT"][$i]==0)?'null':($entValue_a / $rawdata["EBIT"][$i]));
	$vv = ((is_null($entValue_v)||$rawdata["EBIT"][$i-$years]=='null'||$rawdata["EBIT"][$i-$years]==0)?'null':($entValue_v / $rawdata["EBIT"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($rawdata["TotalRevenue"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($rawdata["TotalRevenue"][$i-$years]/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($price_a)||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
	$vv = ((is_null($price_v)||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($rawdata["TotalStockholdersEquity"][$i-$years]/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i]==0))?'null':($price_a / (($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||($rawdata["TotalStockholdersEquity"][$i-$years]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i-$years]=='null')||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||($rawdata["TotalStockholdersEquity"][$i-$years] - $rawdata["GoodwillIntangibleAssetsNet"][$i-$years]==0))?'null':($price_v / (($rawdata["TotalStockholdersEquity"][$i-$years] - $rawdata["GoodwillIntangibleAssetsNet"][$i-$years])/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($price_a)||($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null'&&is_null($CapEx_a))||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx_a==0)||$rawdata["TaxRatePercent"][$i]==1)?'null':($price_a / ((($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx_a)*(1-$rawdata["TaxRatePercent"][$i]))/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
	$vv = ((is_null($price_v)||($rawdata["GrossProfit"][$i-$years]=='null'&&$rawdata["OperatingExpenses"][$i-$years]=='null'&&is_null($CapEx_v))||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||($rawdata["GrossProfit"][$i-$years]-$rawdata["OperatingExpenses"][$i-$years]-$CapEx_v==0)||$rawdata["TaxRatePercent"][$i-$years]==1)?'null':($price_v / ((($rawdata["GrossProfit"][$i-$years]-$rawdata["OperatingExpenses"][$i-$years]-$CapEx_v)*(1-$rawdata["TaxRatePercent"][$i-$years]))/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||is_null($FreeCashFlow_a)||$FreeCashFlow_a==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($FreeCashFlow_a/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||is_null($FreeCashFlow_v)||$FreeCashFlow_v==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($FreeCashFlow_v/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||is_null($OwnerEarningsFCF_a)||$OwnerEarningsFCF_a==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($OwnerEarningsFCF_a/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||is_null($OwnerEarningsFCF_v)||$OwnerEarningsFCF_v==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($OwnerEarningsFCF_v/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($FreeCashFlow_a)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($FreeCashFlow_a / $rawdata["TotalRevenue"][$i]));
	$vv = ((is_null($FreeCashFlow_v)||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($FreeCashFlow_v / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
       	$va = ((is_null($price_a)||$price_a==0||is_null($FreeCashFlow_a)||$FreeCashFlow_a==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':(1 / ($price_a / ($FreeCashFlow_a/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))));
       	$vv = ((is_null($price_v)||$price_v==0||is_null($FreeCashFlow_v)||$FreeCashFlow_v==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':(1 / ($price_v / ($FreeCashFlow_v/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000)))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["EBIT"][$i]=='null'||is_null($entValue_a)||$entValue_a==0)?'null':($rawdata["EBIT"][$i] / $entValue_a));
        $vv = (($rawdata["EBIT"][$i-$years]=='null'||is_null($entValue_v)||$entValue_v==0)?'null':($rawdata["EBIT"][$i-$years] / $entValue_v));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["NetIncome"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
	$vv = (($rawdata["NetIncome"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':($rawdata["NetIncome"][$i-$years] / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["NetIncome"][$i] / $rawdata["TotalAssets"][$i]));
        $vv = (($rawdata["NetIncome"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':($rawdata["NetIncome"][$i-$years] / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["EBIT"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':(($rawdata["EBIT"][$i]*(1-$rawdata["TaxRatePercent"][$i])) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
	$vv = (($rawdata["EBIT"][$i-$years]=='null'||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':(($rawdata["EBIT"][$i-$years]*(1-$rawdata["TaxRatePercent"][$i-$years])) / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($FreeCashFlow_a)||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':($FreeCashFlow_a / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
        $vv = ((is_null($FreeCashFlow_v)||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':($FreeCashFlow_v / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["GrossProfit"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["GrossProfit"][$i] / $rawdata["TotalAssets"][$i]));
	$vv = (($rawdata["GrossProfit"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':($rawdata["GrossProfit"][$i-$years] / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($price_a)||$price_a==0||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':(1 / ($price_a / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))));
	$vv = ((is_null($price_v)||$price_v==0||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':(1 / ($price_v / ($rawdata["TotalStockholdersEquity"][$i-$years]/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000)))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["InventoriesNet"][$i]=='null')||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?'null':(($rawdata["TotalCurrentAssets"][$i] - $rawdata["InventoriesNet"][$i]) / $rawdata["TotalCurrentLiabilities"][$i]));
	$vv = ((($rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["InventoriesNet"][$i-$years]=='null')||$rawdata["TotalCurrentLiabilities"][$i-$years]=='null'||$rawdata["TotalCurrentLiabilities"][$i-$years]==0)?'null':(($rawdata["TotalCurrentAssets"][$i-$years] - $rawdata["InventoriesNet"][$i-$years]) / $rawdata["TotalCurrentLiabilities"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalCurrentAssets"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?'null':($rawdata["TotalCurrentAssets"][$i] / $rawdata["TotalCurrentLiabilities"][$i]));
        $vv = (($rawdata["TotalCurrentAssets"][$i-$years]=='null'||$rawdata["TotalCurrentLiabilities"][$i-$years]=='null'||$rawdata["TotalCurrentLiabilities"][$i-$years]==0)?'null':($rawdata["TotalCurrentAssets"][$i-$years] / $rawdata["TotalCurrentLiabilities"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i]));
	$vv = ((($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null')||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':(($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]) / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':(($rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i]));
        $vv = ((($rawdata["TotalLongtermDebt"][$i-$years]=='null')||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':(($rawdata["TotalLongtermDebt"][$i-$years]) / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalShorttermDebt"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["TotalShorttermDebt"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
	$vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':($rawdata["TotalShorttermDebt"][$i-$years] / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["TotalRevenue"][$i] / $rawdata["TotalAssets"][$i]));
        $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':($rawdata["TotalRevenue"][$i-$years] / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] / $rawdata["TotalRevenue"][$i]));
	$vv = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["TotalReceivablesNet"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["TotalReceivablesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i] / $rawdata["TotalRevenue"][$i]));
	$vv = (($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["ResearchDevelopmentExpense"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i] / $rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["ResearchDevelopmentExpense"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365));
	$vv = (($rawdata["TotalReceivablesNet"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["TotalReceivablesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years] * 365));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365));
        $vv = (($rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0)?'null':($rawdata["InventoriesNet"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["AccountsPayable"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365));
	$vv = (($rawdata["AccountsPayable"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0)?'null':($rawdata["AccountsPayable"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':(($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365)+($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365)-($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365)));
        $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0||$rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0)?'null':(($rawdata["TotalReceivablesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years] * 365)+($rawdata["InventoriesNet"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365)-($rawdata["AccountsPayable"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365)));
	$query .= updateCAGR_concat($vv, $va, $years);
	if($i - $years == 1) {
                $va = (($rawdata["TotalRevenue"][$i]=='null'||($rawdata["AccountsReceivableTradeNet"][$i]=='null'&&is_null($arpy_a))||($rawdata["AccountsReceivableTradeNet"][$i]+$arpy_a==0))?'null':($rawdata["TotalRevenue"][$i] / (($arpy_a + $rawdata["AccountsReceivableTradeNet"][$i])/2)));
                $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["AccountsReceivableTradeNet"][$i-$years]=='null'||$rawdata["AccountsReceivableTradeNet"][$i-$years]==0)?'null':($rawdata["TotalRevenue"][$i-$years] / ($rawdata["AccountsReceivableTradeNet"][$i-$years])));
		$query .= updateCAGR_concat($vv, $va, $years);
	        $va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0))?'null':($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2)));
	        $vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["InventoriesNet"][$i-$years]==0)?'null':($rawdata["CostofRevenue"][$i-$years] / ($rawdata["InventoriesNet"][$i-$years])));
		$query .= updateCAGR_concat($vv, $va, $years);
        	$va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0)||$rawdata["CostofRevenue"][$i]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2))));
        	$vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0||$rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["InventoriesNet"][$i-$years]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i-$years] / ($rawdata["InventoriesNet"][$i-$years]))));
		$query .= updateCAGR_concat($vv, $va, $years);
	} else {
                $va = (($rawdata["TotalRevenue"][$i]=='null'||($rawdata["AccountsReceivableTradeNet"][$i]=='null'&&is_null($arpy_a))||($rawdata["AccountsReceivableTradeNet"][$i]+$arpy_a==0))?'null':($rawdata["TotalRevenue"][$i] / (($arpy_a + $rawdata["AccountsReceivableTradeNet"][$i])/2)));
                $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||($rawdata["AccountsReceivableTradeNet"][$i-$years]=='null'&&is_null($arpy_v))||($rawdata["AccountsReceivableTradeNet"][$i-$years]+$arpy_v==0))?'null':($rawdata["TotalRevenue"][$i-$years] / (($arpy_v + $rawdata["AccountsReceivableTradeNet"][$i-$years])/2)));
		$query .= updateCAGR_concat($vv, $va, $years);
	        $va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0))?'null':($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2)));
	        $vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||($rawdata["InventoriesNet"][$i-$years]=='null'&&is_null($inpy_v))||($rawdata["InventoriesNet"][$i-$years]+$inpy_v==0))?'null':($rawdata["CostofRevenue"][$i-$years] / (($inpy_v + $rawdata["InventoriesNet"][$i-$years])/2)));
		$query .= updateCAGR_concat($vv, $va, $years);
        	$va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0)||$rawdata["CostofRevenue"][$i]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2))));
        	$vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||($rawdata["InventoriesNet"][$i-$years]=='null'&&is_null($inpy_v))||($rawdata["InventoriesNet"][$i-$years]+$inpy_v==0)||$rawdata["CostofRevenue"][$i-$years]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i-$years] / (($inpy_v + $rawdata["InventoriesNet"][$i-$years])/2))));
		$query .= updateCAGR_concat($vv, $va, $years);
	}
        $va = (($rawdata["GoodwillIntangibleAssetsNet"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["GoodwillIntangibleAssetsNet"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
        $vv = (($rawdata["GoodwillIntangibleAssetsNet"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':($rawdata["GoodwillIntangibleAssetsNet"][$i-$years] / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["InventoriesNet"][$i] / $rawdata["TotalRevenue"][$i]));
	$vv = (($rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["InventoriesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
        $vv = ((($rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':(($rawdata["TotalLongtermDebt"][$i-$years]) / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
	$vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'||($rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':($rawdata["TotalShorttermDebt"][$i-$years] / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalLongtermDebt"][$i]=='null' &&$rawdata["TotalShorttermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?'null':(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i])));
        $vv = ((($rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalLongtermDebt"][$i-$years]=='null' &&$rawdata["TotalShorttermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]==0))?'null':(($rawdata["TotalLongtermDebt"][$i-$years]) / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?'null':($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i])));
	$vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]==0))?'null':($rawdata["TotalShorttermDebt"][$i-$years] / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalAssets"][$i]));
        $vv = ((($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null')||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':(($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]) / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||is_null($price_a)||$price_a==0)?'null':((($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i]) / (toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/$price_a));
	$vv = ((($rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["TotalCurrentLiabilities"][$i-$years]=='null')||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||is_null($price_v)||$price_v==0)?'null':((($rawdata["TotalCurrentAssets"][$i-$years] - $rawdata["TotalCurrentLiabilities"][$i-$years]) / (toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))/$price_v));
	$query .= updateCAGR_concat($vv, $va, $years);
        $query .= ")";
	mysql_query($query) or die ($query."\n".mysql_error());
}
?>
