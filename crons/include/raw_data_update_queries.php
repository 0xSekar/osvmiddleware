<?php
include ("update_cagr_tables_functions.php");
function update_raw_data_tickers($dates, $rawdata) {
    $db = Database::GetInstance(); 
    $areports = AREPORTS;
    $qreports = QREPORTS;
    $treports = $areports+$qreports;

    $report_tables = array("reports_balanceconsolidated","reports_balanceconsolidated_3cagr","reports_balanceconsolidated_5cagr","reports_balanceconsolidated_7cagr","reports_balanceconsolidated_10cagr","reports_balancefull","reports_balancefull_3cagr","reports_balancefull_5cagr","reports_balancefull_7cagr","reports_balancefull_10cagr","reports_cashflowconsolidated","reports_cashflowconsolidated_3cagr","reports_cashflowconsolidated_5cagr","reports_cashflowconsolidated_7cagr","reports_cashflowconsolidated_10cagr","reports_cashflowfull","reports_cashflowfull_3cagr","reports_cashflowfull_5cagr","reports_cashflowfull_7cagr","reports_cashflowfull_10cagr","reports_financialheader","reports_gf_data","reports_gf_data_3cagr","reports_gf_data_5cagr","reports_gf_data_7cagr","reports_gf_data_10cagr","reports_incomeconsolidated","reports_incomeconsolidated_3cagr","reports_incomeconsolidated_5cagr","reports_incomeconsolidated_7cagr","reports_incomeconsolidated_10cagr","reports_incomefull","reports_incomefull_3cagr","reports_incomefull_5cagr","reports_incomefull_7cagr","reports_incomefull_10cagr","reports_metadata_eol","reports_variable_ratios","reports_variable_ratios_3cagr","reports_variable_ratios_5cagr","reports_variable_ratios_7cagr","reports_variable_ratios_10cagr","reports_financialscustom","reports_financialscustom_3cagr","reports_financialscustom_5cagr","reports_financialscustom_7cagr","reports_financialscustom_10cagr","reports_key_ratios","reports_key_ratios_3cagr","reports_key_ratios_5cagr","reports_key_ratios_7cagr","reports_key_ratios_10cagr","reports_valuation","reports_valuation_3cagr","reports_valuation_5cagr","reports_valuation_7cagr","reports_valuation_10cagr");

    //Update tickers_* tables (tables that hold only 1 data point per symbol)
    $query = "INSERT INTO `tickers_activity_daily_ratios` (`ticker_id`, `AccountsPayableTurnoverDaysFY`, `TradeCycleDaysFY`, `TradeCycleDaysTTM`, `AccountsPayableTurnoverDaysTTM`, `InventoryTurnoverDaysFY`, `InventoryTurnoverDaysTTM`, `NetOperatingProfitafterTaxFQ`, `NetOperatingProfitafterTaxFY`, `NetOperatingProfitafterTaxTTM`, `ReceivablesCollectionPeriodDaysFY`, `ReceivablesCollectionPeriodDaysTTM`, `TaxRatePctFQ`, `TaxRatePctFY`, `TaxRatePctTTM`, `Volume`, `AverageVolume`, `Beta1Year`, `Beta3Year`, `Beta5Year`, `Date52WeekHigh`, `Date52WeekLow`, `DatePreviousClose`, `DatePriceClose`, `PreviousVolume`, `Price52WeekHigh`, `Price52WeekLow`, `PriceClose`, `PricePctChange13Week`, `PricePctChange1Day`, `PricePctChange1Week`, `PricePctChange26Week`, `PricePctChange4Week`, `PricePctChange52Week`, `PricePctChangeYTD`, `PricePreviousClose`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AccountsPayableTurnoverDaysFY`=?, `TradeCycleDaysFY`=?, `TradeCycleDaysTTM`=?, `AccountsPayableTurnoverDaysTTM`=?, `InventoryTurnoverDaysFY`=?, `InventoryTurnoverDaysTTM`=?, `NetOperatingProfitafterTaxFQ`=?, `NetOperatingProfitafterTaxFY`=?, `NetOperatingProfitafterTaxTTM`=?, `ReceivablesCollectionPeriodDaysFY`=?, `ReceivablesCollectionPeriodDaysTTM`=?, `TaxRatePctFQ`=?, `TaxRatePctFY`=?, `TaxRatePctTTM`=?, `Volume`=?, `AverageVolume`=?, `Beta1Year`=?, `Beta3Year`=?, `Beta5Year`=?, `Date52WeekHigh`=?, `Date52WeekLow`=?, `DatePreviousClose`=?, `DatePriceClose`=?, `PreviousVolume`=?, `Price52WeekHigh`=?, `Price52WeekLow`=?, `PriceClose`=?, `PricePctChange13Week`=?, `PricePctChange1Day`=?, `PricePctChange1Week`=?, `PricePctChange26Week`=?, `PricePctChange4Week`=?, `PricePctChange52Week`=?, `PricePctChangeYTD`=?, `PricePreviousClose`=?";
    $params = array();
    $params[] = ($rawdata["AccountsPayableTurnoverDaysFY"][$treports] == 'null' ? null: $rawdata["AccountsPayableTurnoverDaysFY"][$treports]);
    $params[] = ($rawdata["TradeCycleDaysFY"][$treports] == 'null' ? null: $rawdata["TradeCycleDaysFY"][$treports]);
    $params[] = ($rawdata["TradeCycleDaysTTM"][$treports] == 'null' ? null: $rawdata["TradeCycleDaysTTM"][$treports]);
    $params[] = ($rawdata["AccountsPayableTurnoverDaysTTM"][$treports] == 'null' ? null: $rawdata["AccountsPayableTurnoverDaysTTM"][$treports]);
    $params[] = ($rawdata["InventoryTurnoverDaysFY"][$treports] == 'null' ? null: $rawdata["InventoryTurnoverDaysFY"][$treports]);
    $params[] = ($rawdata["InventoryTurnoverDaysTTM"][$treports] == 'null' ? null: $rawdata["InventoryTurnoverDaysTTM"][$treports]);
    $params[] = ($rawdata["NetOperatingProfitafterTaxFQ"][$treports] == 'null' ? null: $rawdata["NetOperatingProfitafterTaxFQ"][$treports]);
    $params[] = ($rawdata["NetOperatingProfitafterTaxFY"][$treports] == 'null' ? null: $rawdata["NetOperatingProfitafterTaxFY"][$treports]);
    $params[] = ($rawdata["NetOperatingProfitafterTaxTTM"][$treports] == 'null' ? null: $rawdata["NetOperatingProfitafterTaxTTM"][$treports]);
    $params[] = ($rawdata["ReceivablesCollectionPeriodDaysFY"][$treports] == 'null' ? null: $rawdata["ReceivablesCollectionPeriodDaysFY"][$treports]);
    $params[] = ($rawdata["ReceivablesCollectionPeriodDaysTTM"][$treports] == 'null' ? null: $rawdata["ReceivablesCollectionPeriodDaysTTM"][$treports]);
    $params[] = ($rawdata["TaxRatePctFQ"][$treports] == 'null' ? null: $rawdata["TaxRatePctFQ"][$treports]);
    $params[] = ($rawdata["TaxRatePctFY"][$treports] == 'null' ? null: $rawdata["TaxRatePctFY"][$treports]);
    $params[] = ($rawdata["TaxRatePctTTM"][$treports] == 'null' ? null: $rawdata["TaxRatePctTTM"][$treports]);
    $params[] = ($rawdata["Volume"][$treports] == 'null' ? null: $rawdata["Volume"][$treports]);
    $params[] = ($rawdata["AverageVolume"][$treports] == 'null' ? null: $rawdata["AverageVolume"][$treports]);
    $params[] = ($rawdata["Beta1Year"][$treports] == 'null' ? null: $rawdata["Beta1Year"][$treports]);
    $params[] = ($rawdata["Beta3Year"][$treports] == 'null' ? null: $rawdata["Beta3Year"][$treports]);
    $params[] = ($rawdata["Beta5Year"][$treports] == 'null' ? null:$rawdata["Beta5Year"][$treports]);
    $params[] = date("Y-m-d",strtotime($rawdata["Date52WeekHigh"][$treports]));
    $params[] = date("Y-m-d",strtotime($rawdata["Date52WeekLow"][$treports]));
    $params[] = date("Y-m-d",strtotime($rawdata["DatePreviousClose"][$treports]));
    $params[] = date("Y-m-d",strtotime($rawdata["DatePriceClose"][$treports]));
    $params[] = ($rawdata["PreviousVolume"][$treports] == 'null' ? null:$rawdata["PreviousVolume"][$treports]);
    $params[] = ($rawdata["Price52WeekHigh"][$treports] == 'null' ? null:$rawdata["Price52WeekHigh"][$treports]);
    $params[] = ($rawdata["Price52WeekLow"][$treports] == 'null' ? null:$rawdata["Price52WeekLow"][$treports]);
    $params[] = ($rawdata["PriceClose"][$treports] == 'null' ? null:$rawdata["PriceClose"][$treports]);
    $params[] = ($rawdata["PricePctChange13Week"][$treports] == 'null' ? null:$rawdata["PricePctChange13Week"][$treports]);
    $params[] = ($rawdata["PricePctChange1Day"][$treports] == 'null' ? null:$rawdata["PricePctChange1Day"][$treports]);
    $params[] = ($rawdata["PricePctChange1Week"][$treports] == 'null' ? null:$rawdata["PricePctChange1Week"][$treports]);
    $params[] = ($rawdata["PricePctChange26Week"][$treports] == 'null' ? null:$rawdata["PricePctChange26Week"][$treports]);
    $params[] = ($rawdata["PricePctChange4Week"][$treports] == 'null' ? null:$rawdata["PricePctChange4Week"][$treports]);
    $params[] = ($rawdata["PricePctChange52Week"][$treports] == 'null' ? null:$rawdata["PricePctChange52Week"][$treports]);
    $params[] = ($rawdata["PricePctChangeYTD"][$treports] == 'null' ? null:$rawdata["PricePctChangeYTD"][$treports]);
    $params[] = ($rawdata["PricePreviousClose"][$treports] == 'null' ? null:$rawdata["PricePreviousClose"][$treports]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);
    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    //tickers_growth_ratios
    $query = "INSERT INTO `tickers_growth_ratios` (`ticker_id`, `AdjustedEBITDAPctGrowth3YearCAGRFY`, `AdjustedEBITDAPctGrowth5YearCAGRFY`, `AdjustedEBITDAPctGrowthFY`, `AdjustedEBITDAPctGrowthTTM`, `EBITDAPctGrowth3YearCAGRFY`, `EBITDAPctGrowth5YearCAGRFY`, `EBITDAPctGrowthFY`, `EBITDAPctGrowthTTM`, `EBITPctGrowth3YearCAGRFY`, `EBITPctGrowth5YearCAGRFY`, `EBITPctGrowthFY`, `EBITPctGrowthTTM`, `FreeCashFlowPctGrowth3YearCAGRFY`, `FreeCashFlowPctGrowth5YearCAGRFY`, `FreeCashFlowPctGrowthFY`, `FreeCashFlowPctGrowthTTM`, `NetIncomePctGrowth3YearCAGRFY`, `NetIncomePctGrowth5YearCAGRFY`, `NetIncomePctGrowthFY`, `NetIncomePctGrowthTTM`, `OperatingCashFlowPctGrowth3YearCAGRFY`, `OperatingCashFlowPctGrowth5YearCAGRFY`, `OperatingCashFlowPctGrowthFY`, `OperatingCashFlowPctGrowthTTM`, `OperatingProfitPctGrowth3YearCAGRFY`, `OperatingProfitPctGrowth5YearCAGRFY`, `OperatingProfitPctGrowthFY`, `OperatingProfitPctGrowthTTM`, `PriceEarningstoGrowthFY`, `PriceEarningstoGrowthTTM`, `RevenuePctGrowth3YearCAGRFY`, `RevenuePctGrowth5YearCAGRFY`, `RevenuePctGrowthFY`, `RevenuePctGrowthTTM`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AdjustedEBITDAPctGrowth3YearCAGRFY`=?, `AdjustedEBITDAPctGrowth5YearCAGRFY`=?, `AdjustedEBITDAPctGrowthFY`=?, `AdjustedEBITDAPctGrowthTTM`=?, `EBITDAPctGrowth3YearCAGRFY`=?, `EBITDAPctGrowth5YearCAGRFY`=?, `EBITDAPctGrowthFY`=?, `EBITDAPctGrowthTTM`=?, `EBITPctGrowth3YearCAGRFY`=?, `EBITPctGrowth5YearCAGRFY`=?, `EBITPctGrowthFY`=?, `EBITPctGrowthTTM`=?, `FreeCashFlowPctGrowth3YearCAGRFY`=?, `FreeCashFlowPctGrowth5YearCAGRFY`=?, `FreeCashFlowPctGrowthFY`=?, `FreeCashFlowPctGrowthTTM`=?, `NetIncomePctGrowth3YearCAGRFY`=?, `NetIncomePctGrowth5YearCAGRFY`=?, `NetIncomePctGrowthFY`=?, `NetIncomePctGrowthTTM`=?, `OperatingCashFlowPctGrowth3YearCAGRFY`=?, `OperatingCashFlowPctGrowth5YearCAGRFY`=?, `OperatingCashFlowPctGrowthFY`=?, `OperatingCashFlowPctGrowthTTM`=?, `OperatingProfitPctGrowth3YearCAGRFY`=?, `OperatingProfitPctGrowth5YearCAGRFY`=?, `OperatingProfitPctGrowthFY`=?, `OperatingProfitPctGrowthTTM`=?, `PriceEarningstoGrowthFY`=?, `PriceEarningstoGrowthTTM`=?, `RevenuePctGrowth3YearCAGRFY`=?, `RevenuePctGrowth5YearCAGRFY`=?, `RevenuePctGrowthFY`=?, `RevenuePctGrowthTTM`=?";
    $params = array();
    $params[] = ($rawdata["AdjustedEBITDAPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITDAPctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITDAPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITDAPctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITDAPctGrowthFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITDAPctGrowthFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITDAPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["AdjustedEBITDAPctGrowthTTM"][$treports]);
    $params[] = ($rawdata["EBITDAPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["EBITDAPctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["EBITDAPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["EBITDAPctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["EBITDAPctGrowthFY"][$treports] == 'null' ? null:$rawdata["EBITDAPctGrowthFY"][$treports]);
    $params[] = ($rawdata["EBITDAPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["EBITDAPctGrowthTTM"][$treports]);
    $params[] = ($rawdata["EBITPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["EBITPctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["EBITPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["EBITPctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["EBITPctGrowthFY"][$treports] == 'null' ? null:$rawdata["EBITPctGrowthFY"][$treports]);
    $params[] = ($rawdata["EBITPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["EBITPctGrowthTTM"][$treports]);
    $params[] = ($rawdata["FreeCashFlowPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["FreeCashFlowPctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["FreeCashFlowPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["FreeCashFlowPctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["FreeCashFlowPctGrowthFY"][$treports] == 'null' ? null:$rawdata["FreeCashFlowPctGrowthFY"][$treports]);
    $params[] = ($rawdata["FreeCashFlowPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["FreeCashFlowPctGrowthTTM"][$treports]);
    $params[] = ($rawdata["NetIncomePctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["NetIncomePctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["NetIncomePctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["NetIncomePctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["NetIncomePctGrowthFY"][$treports] == 'null' ? null:$rawdata["NetIncomePctGrowthFY"][$treports]);
    $params[] = ($rawdata["NetIncomePctGrowthTTM"][$treports] == 'null' ? null:$rawdata["NetIncomePctGrowthTTM"][$treports]);
    $params[] = ($rawdata["OperatingCashFlowPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["OperatingCashFlowPctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["OperatingCashFlowPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["OperatingCashFlowPctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["OperatingCashFlowPctGrowthFY"][$treports] == 'null' ? null:$rawdata["OperatingCashFlowPctGrowthFY"][$treports]);
    $params[] = ($rawdata["OperatingCashFlowPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["OperatingCashFlowPctGrowthTTM"][$treports]);
    $params[] = ($rawdata["OperatingProfitPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["OperatingProfitPctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["OperatingProfitPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["OperatingProfitPctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["OperatingProfitPctGrowthFY"][$treports] == 'null' ? null:$rawdata["OperatingProfitPctGrowthFY"][$treports]);
    $params[] = ($rawdata["OperatingProfitPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["OperatingProfitPctGrowthTTM"][$treports]);
    $params[] = ($rawdata["PriceEarningstoGrowthFY"][$treports] == 'null' ? null:$rawdata["PriceEarningstoGrowthFY"][$treports]);
    $params[] = ($rawdata["PriceEarningstoGrowthTTM"][$treports] == 'null' ? null:$rawdata["PriceEarningstoGrowthTTM"][$treports]);
    $params[] = ($rawdata["RevenuePctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["RevenuePctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["RevenuePctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["RevenuePctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["RevenuePctGrowthFY"][$treports] == 'null' ? null:$rawdata["RevenuePctGrowthFY"][$treports]);
    $params[] = ($rawdata["RevenuePctGrowthTTM"][$treports] == 'null' ? null:$rawdata["RevenuePctGrowthTTM"][$treports]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);
    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    //tickers_leverage_ratios
    $query = "INSERT INTO `tickers_leverage_ratios` (`ticker_id`, `TotalCapitalFY`, `TotalDebtFQ`, `TotalDebtFY`, `AltmanZscoreFY`, `AltmanZscoreTTM`, `BookEquityFQ`, `BookEquityFY`, `DebttoAssetsFQ`, `DebttoAssetsFY`, `DegreeofCombinedLeverageFY`, `DegreeofCombinedLeverageTTM`, `DegreeofFinancialLeverageFY`, `DegreeofFinancialLeverageTTM`, `DegreeofOperationalLeverageFY`, `DegreeofOperationalLeverageTTM`, `FreeCashFlowFQ`, `FreeCashFlowFY`, `FreeCashFlowtoEquityPctFY`, `FreeCashFlowtoEquityPctTTM`, `FreeCashFlowTTM`, `LongTermCapitalFQ`, `LongTermCapitalFY`, `LongTermDebttoLongTermCapitalFQ`, `LongTermDebttoLongTermCapitalFY`, `LongTermDebttoTotalCapitalFQ`, `LongTermDebttoTotalCapitalFY`, `NetDebtFQ`, `NetDebtFY`, `OperatingCashFlowFQ`, `OperatingCashFlowFY`, `OperatingCashFlowTTM`, `TotalCapitalFQ`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `TotalCapitalFY`=?, `TotalDebtFQ`=?, `TotalDebtFY`=?, `AltmanZscoreFY`=?, `AltmanZscoreTTM`=?, `BookEquityFQ`=?, `BookEquityFY`=?, `DebttoAssetsFQ`=?, `DebttoAssetsFY`=?, `DegreeofCombinedLeverageFY`=?, `DegreeofCombinedLeverageTTM`=?, `DegreeofFinancialLeverageFY`=?, `DegreeofFinancialLeverageTTM`=?, `DegreeofOperationalLeverageFY`=?, `DegreeofOperationalLeverageTTM`=?, `FreeCashFlowFQ`=?, `FreeCashFlowFY`=?, `FreeCashFlowtoEquityPctFY`=?, `FreeCashFlowtoEquityPctTTM`=?, `FreeCashFlowTTM`=?, `LongTermCapitalFQ`=?, `LongTermCapitalFY`=?, `LongTermDebttoLongTermCapitalFQ`=?, `LongTermDebttoLongTermCapitalFY`=?, `LongTermDebttoTotalCapitalFQ`=?, `LongTermDebttoTotalCapitalFY`=?, `NetDebtFQ`=?, `NetDebtFY`=?, `OperatingCashFlowFQ`=?, `OperatingCashFlowFY`=?, `OperatingCashFlowTTM`=?, `TotalCapitalFQ`=?";    
    $params = array();
    $params[] = ($rawdata["TotalCapitalFY"][$treports] == 'null' ? null:$rawdata["TotalCapitalFY"][$treports]);
    $params[] = ($rawdata["TotalDebtFQ"][$treports] == 'null' ? null:$rawdata["TotalDebtFQ"][$treports]);
    $params[] = ($rawdata["TotalDebtFY"][$treports] == 'null' ? null:$rawdata["TotalDebtFY"][$treports]);
    $params[] = ($rawdata["AltmanZscoreFY"][$treports] == 'null' ? null:$rawdata["AltmanZscoreFY"][$treports]);
    $params[] = ($rawdata["AltmanZscoreTTM"][$treports] == 'null' ? null:$rawdata["AltmanZscoreTTM"][$treports]);
    $params[] = ($rawdata["BookEquityFQ"][$treports] == 'null' ? null:$rawdata["BookEquityFQ"][$treports]);
    $params[] = ($rawdata["BookEquityFY"][$treports] == 'null' ? null:$rawdata["BookEquityFY"][$treports]);
    $params[] = ($rawdata["DebttoAssetsFQ"][$treports] == 'null' ? null:$rawdata["DebttoAssetsFQ"][$treports]);
    $params[] = ($rawdata["DebttoAssetsFY"][$treports] == 'null' ? null:$rawdata["DebttoAssetsFY"][$treports]);
    $params[] = ($rawdata["DegreeofCombinedLeverageFY"][$treports] == 'null' ? null:$rawdata["DegreeofCombinedLeverageFY"][$treports]);
    $params[] = ($rawdata["DegreeofCombinedLeverageTTM"][$treports] == 'null' ? null:$rawdata["DegreeofCombinedLeverageTTM"][$treports]);
    $params[] = ($rawdata["DegreeofFinancialLeverageFY"][$treports] == 'null' ? null:$rawdata["DegreeofFinancialLeverageFY"][$treports]);
    $params[] = ($rawdata["DegreeofFinancialLeverageTTM"][$treports] == 'null' ? null:$rawdata["DegreeofFinancialLeverageTTM"][$treports]);
    $params[] = ($rawdata["DegreeofOperationalLeverageFY"][$treports] == 'null' ? null:$rawdata["DegreeofOperationalLeverageFY"][$treports]);
    $params[] = ($rawdata["DegreeofOperationalLeverageTTM"][$treports] == 'null' ? null:$rawdata["DegreeofOperationalLeverageTTM"][$treports]);
    $params[] = ($rawdata["FreeCashFlowFQ"][$treports] == 'null' ? null:$rawdata["FreeCashFlowFQ"][$treports]);
    $params[] = ($rawdata["FreeCashFlowFY"][$treports] == 'null' ? null:$rawdata["FreeCashFlowFY"][$treports]);
    $params[] = ($rawdata["FreeCashFlowtoEquityPctFY"][$treports] == 'null' ? null:$rawdata["FreeCashFlowtoEquityPctFY"][$treports]);
    $params[] = ($rawdata["FreeCashFlowtoEquityPctTTM"][$treports] == 'null' ? null:$rawdata["FreeCashFlowtoEquityPctTTM"][$treports]);
    $params[] = ($rawdata["FreeCashFlowTTM"][$treports] == 'null' ? null:$rawdata["FreeCashFlowTTM"][$treports]);
    $params[] = ($rawdata["LongTermCapitalFQ"][$treports] == 'null' ? null:$rawdata["LongTermCapitalFQ"][$treports]);
    $params[] = ($rawdata["LongTermCapitalFY"][$treports] == 'null' ? null:$rawdata["LongTermCapitalFY"][$treports]);
    $params[] = ($rawdata["LongTermDebttoLongTermCapitalFQ"][$treports] == 'null' ? null:$rawdata["LongTermDebttoLongTermCapitalFQ"][$treports]);
    $params[] = ($rawdata["LongTermDebttoLongTermCapitalFY"][$treports] == 'null' ? null:$rawdata["LongTermDebttoLongTermCapitalFY"][$treports]);
    $params[] = ($rawdata["LongTermDebttoTotalCapitalFQ"][$treports] == 'null' ? null:$rawdata["LongTermDebttoTotalCapitalFQ"][$treports]);
    $params[] = ($rawdata["LongTermDebttoTotalCapitalFY"][$treports] == 'null' ? null:$rawdata["LongTermDebttoTotalCapitalFY"][$treports]);
    $params[] = ($rawdata["NetDebtFQ"][$treports] == 'null' ? null:$rawdata["NetDebtFQ"][$treports]);
    $params[] = ($rawdata["NetDebtFY"][$treports] == 'null' ? null:$rawdata["NetDebtFY"][$treports]);
    $params[] = ($rawdata["OperatingCashFlowFQ"][$treports] == 'null' ? null:$rawdata["OperatingCashFlowFQ"][$treports]);
    $params[] = ($rawdata["OperatingCashFlowFY"][$treports] == 'null' ? null:$rawdata["OperatingCashFlowFY"][$treports]);
    $params[] = ($rawdata["OperatingCashFlowTTM"][$treports] == 'null' ? null:$rawdata["OperatingCashFlowTTM"][$treports]);
    $params[] = ($rawdata["TotalCapitalFQ"][$treports] == 'null' ? null:$rawdata["TotalCapitalFQ"][$treports]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    //tickers_mini_ratios
    $query = "INSERT INTO `tickers_mini_ratios` (`ticker_id`, `DebttoEquityFQ`, `DebttoEquityFY`, `MarketCapBasic`, `MarketCapDiluted`, `MarketCapTSO`, `PriceBookFQ`, `PriceBookFY`, `PriceEarningsFY`, `PriceEarningsTTM`, `GrossMarginPctFQ`, `GrossMarginPctFY`, `GrossMarginPctTTM`, `OperatingMarginPctFQ`, `OperatingMarginPctFY`, `OperatingMarginPctTTM`, `CashRatioFQ`, `CashRatioFY`, `NetWorkingCapitalFQ`, `NetWorkingCapitalFY`, `CurrentRatioFQ`, `CurrentRatioFY`, `QuickRatioFQ`, `QuickRatioFY`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?) ON DUPLICATE KEY UPDATE `DebttoEquityFQ`=?, `DebttoEquityFY`=?, `MarketCapBasic`=?, `MarketCapDiluted`=?, `MarketCapTSO`=?, `PriceBookFQ`=?, `PriceBookFY`=?, `PriceEarningsFY`=?, `PriceEarningsTTM`=?, `GrossMarginPctFQ`=?, `GrossMarginPctFY`=?, `GrossMarginPctTTM`=?, `OperatingMarginPctFQ`=?, `OperatingMarginPctFY`=?, `OperatingMarginPctTTM`=?, `CashRatioFQ`=?, `CashRatioFY`=?, `NetWorkingCapitalFQ`=?, `NetWorkingCapitalFY`=?, `CurrentRatioFQ`=?, `CurrentRatioFY`=?, `QuickRatioFQ`=?, `QuickRatioFY`=?";
    $params = array();
    $params[] = ($rawdata["DebttoEquityFQ"][$treports] == 'null' ? null:$rawdata["DebttoEquityFQ"][$treports]);
    $params[] = ($rawdata["DebttoEquityFY"][$treports] == 'null' ? null:$rawdata["DebttoEquityFY"][$treports]);
    $params[] = ($rawdata["MarketCapBasic"][$treports] == 'null' ? null:$rawdata["MarketCapBasic"][$treports]);
    $params[] = ($rawdata["MarketCapDiluted"][$treports] == 'null' ? null:$rawdata["MarketCapDiluted"][$treports]);
    $params[] = ($rawdata["MarketCapTSO"][$treports] == 'null' ? null:$rawdata["MarketCapTSO"][$treports]);
    $params[] = ($rawdata["PriceBookFQ"][$treports] == 'null' ? null:$rawdata["PriceBookFQ"][$treports]);
    $params[] = ($rawdata["PriceBookFY"][$treports] == 'null' ? null:$rawdata["PriceBookFY"][$treports]);
    $params[] = ($rawdata["PriceEarningsFY"][$treports] == 'null' ? null:$rawdata["PriceEarningsFY"][$treports]);
    $params[] = ($rawdata["PriceEarningsTTM"][$treports] == 'null' ? null:$rawdata["PriceEarningsTTM"][$treports]);
    $params[] = ($rawdata["GrossMarginPctFQ"][$treports] == 'null' ? null:$rawdata["GrossMarginPctFQ"][$treports]);
    $params[] = ($rawdata["GrossMarginPctFY"][$treports] == 'null' ? null:$rawdata["GrossMarginPctFY"][$treports]);
    $params[] = ($rawdata["GrossMarginPctTTM"][$treports] == 'null' ? null:$rawdata["GrossMarginPctTTM"][$treports]);
    $params[] = ($rawdata["OperatingMarginPctFQ"][$treports] == 'null' ? null:$rawdata["OperatingMarginPctFQ"][$treports]);
    $params[] = ($rawdata["OperatingMarginPctFY"][$treports] == 'null' ? null:$rawdata["OperatingMarginPctFY"][$treports]);
    $params[] = ($rawdata["OperatingMarginPctTTM"][$treports] == 'null' ? null:$rawdata["OperatingMarginPctTTM"][$treports]);
    $params[] = ($rawdata["CashRatioFQ"][$treports] == 'null' ? null:$rawdata["CashRatioFQ"][$treports]);
    $params[] = ($rawdata["CashRatioFY"][$treports] == 'null' ? null:$rawdata["CashRatioFY"][$treports]);
    $params[] = ($rawdata["NetWorkingCapitalFQ"][$treports] == 'null' ? null:$rawdata["NetWorkingCapitalFQ"][$treports]);
    $params[] = ($rawdata["NetWorkingCapitalFY"][$treports] == 'null' ? null:$rawdata["NetWorkingCapitalFY"][$treports]);
    $params[] = ($rawdata["CurrentRatioFQ"][$treports] == 'null' ? null:$rawdata["CurrentRatioFQ"][$treports]);
    $params[] = ($rawdata["CurrentRatioFY"][$treports] == 'null' ? null:$rawdata["CurrentRatioFY"][$treports]);
    $params[] = ($rawdata["QuickRatioFQ"][$treports] == 'null' ? null:$rawdata["QuickRatioFQ"][$treports]);
    $params[] = ($rawdata["QuickRatioFY"][$treports] == 'null' ? null:$rawdata["QuickRatioFY"][$treports]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    //tickers_profitability_ratios	
    $query = "INSERT INTO `tickers_profitability_ratios` (`ticker_id`, `AdjustedEBITDAFQ`, `AdjustedEBITDAFY`, `AdjustedEBITDATTM`, `AdjustedEBITFQ`, `AdjustedEBITFY`, `AdjustedEBITPctGrowth3YearCAGRFY`, `AdjustedEBITPctGrowth5YearCAGRFY`, `AdjustedEBITPctGrowthFY`, `AdjustedEBITPctGrowthTTM`, `AdjustedEBITTTM`, `AdjustedNetIncomeFQ`, `AdjustedNetIncomeFY`, `AdjustedNetIncomePctGrowth3YearCAGRFY`, `AdjustedNetIncomePctGrowth5YearCAGRFY`, `AdjustedNetIncomePctGrowthFY`, `AdjustedNetIncomePctGrowthTTM`, `AdjustedNetIncomeTTM`, `AftertaxMarginPctFQ`, `AftertaxMarginPctFY`, `AftertaxMarginPctTTM`, `EBITDAFQ`, `EBITDAFY`, `EBITDATTM`, `EBITFQ`, `EBITFY`, `EBITTTM`, `FreeCashFlowMarginPctFQ`, `FreeCashFlowMarginPctFY`, `FreeCashFlowMarginPctTTM`, `FreeCashFlowReturnonAssetsPctFY`, `FreeCashFlowReturnonAssetsPctTTM`, `NetIncomeperEmployeeFY`, `NetIncomeperEmployeeTTM`, `PretaxMarginPctFQ`, `PretaxMarginPctFY`, `PretaxMarginPctTTM`, `ReturnonAssetsPctFY`, `ReturnonAssetsPctTTM`, `ReturnonEquityPctFY`, `ReturnonEquityPctTTM`, `ReturnonInvestedCapitalPctFY`, `ReturnonInvestedCapitalPctTTM`, `RevenueperEmployeeFY`, `RevenueperEmployeeTTM`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AdjustedEBITDAFQ`=?, `AdjustedEBITDAFY`=?, `AdjustedEBITDATTM`=?, `AdjustedEBITFQ`=?, `AdjustedEBITFY`=?, `AdjustedEBITPctGrowth3YearCAGRFY`=?, `AdjustedEBITPctGrowth5YearCAGRFY`=?, `AdjustedEBITPctGrowthFY`=?, `AdjustedEBITPctGrowthTTM`=?, `AdjustedEBITTTM`=?, `AdjustedNetIncomeFQ`=?, `AdjustedNetIncomeFY`=?, `AdjustedNetIncomePctGrowth3YearCAGRFY`=?, `AdjustedNetIncomePctGrowth5YearCAGRFY`=?, `AdjustedNetIncomePctGrowthFY`=?, `AdjustedNetIncomePctGrowthTTM`=?, `AdjustedNetIncomeTTM`=?, `AftertaxMarginPctFQ`=?, `AftertaxMarginPctFY`=?, `AftertaxMarginPctTTM`=?, `EBITDAFQ`=?, `EBITDAFY`=?, `EBITDATTM`=?, `EBITFQ`=?, `EBITFY`=?, `EBITTTM`=?, `FreeCashFlowMarginPctFQ`=?, `FreeCashFlowMarginPctFY`=?, `FreeCashFlowMarginPctTTM`=?, `FreeCashFlowReturnonAssetsPctFY`=?, `FreeCashFlowReturnonAssetsPctTTM`=?, `NetIncomeperEmployeeFY`=?, `NetIncomeperEmployeeTTM`=?, `PretaxMarginPctFQ`=?, `PretaxMarginPctFY`=?, `PretaxMarginPctTTM`=?, `ReturnonAssetsPctFY`=?, `ReturnonAssetsPctTTM`=?, `ReturnonEquityPctFY`=?, `ReturnonEquityPctTTM`=?, `ReturnonInvestedCapitalPctFY`=?, `ReturnonInvestedCapitalPctTTM`=?, `RevenueperEmployeeFY`=?, `RevenueperEmployeeTTM`=?";        
    $params = array();
    $params[] = ($rawdata["AdjustedEBITDAFQ"][$treports] == 'null' ? null:$rawdata["AdjustedEBITDAFQ"][$treports]);
    $params[] = ($rawdata["AdjustedEBITDAFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITDAFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITDATTM"][$treports] == 'null' ? null:$rawdata["AdjustedEBITDATTM"][$treports]);
    $params[] = ($rawdata["AdjustedEBITFQ"][$treports] == 'null' ? null:$rawdata["AdjustedEBITFQ"][$treports]);
    $params[] = ($rawdata["AdjustedEBITFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITPctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITPctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITPctGrowthFY"][$treports] == 'null' ? null:$rawdata["AdjustedEBITPctGrowthFY"][$treports]);
    $params[] = ($rawdata["AdjustedEBITPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["AdjustedEBITPctGrowthTTM"][$treports]);
    $params[] = ($rawdata["AdjustedEBITTTM"][$treports] == 'null' ? null:$rawdata["AdjustedEBITTTM"][$treports]);
    $params[] = ($rawdata["AdjustedNetIncomeFQ"][$treports] == 'null' ? null:$rawdata["AdjustedNetIncomeFQ"][$treports]);
    $params[] = ($rawdata["AdjustedNetIncomeFY"][$treports] == 'null' ? null:$rawdata["AdjustedNetIncomeFY"][$treports]);
    $params[] = ($rawdata["AdjustedNetIncomePctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedNetIncomePctGrowth3YearCAGRFY"][$treports]);
    $params[] = ($rawdata["AdjustedNetIncomePctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedNetIncomePctGrowth5YearCAGRFY"][$treports]);
    $params[] = ($rawdata["AdjustedNetIncomePctGrowthFY"][$treports] == 'null' ? null:$rawdata["AdjustedNetIncomePctGrowthFY"][$treports]);
    $params[] = ($rawdata["AdjustedNetIncomePctGrowthTTM"][$treports] == 'null' ? null:$rawdata["AdjustedNetIncomePctGrowthTTM"][$treports]);
    $params[] = ($rawdata["AdjustedNetIncomeTTM"][$treports] == 'null' ? null:$rawdata["AdjustedNetIncomeTTM"][$treports]);
    $params[] = ($rawdata["AftertaxMarginPctFQ"][$treports] == 'null' ? null:$rawdata["AftertaxMarginPctFQ"][$treports]);
    $params[] = ($rawdata["AftertaxMarginPctFY"][$treports] == 'null' ? null:$rawdata["AftertaxMarginPctFY"][$treports]);
    $params[] = ($rawdata["AftertaxMarginPctTTM"][$treports] == 'null' ? null:$rawdata["AftertaxMarginPctTTM"][$treports]);
    $params[] = ($rawdata["EBITDAFQ"][$treports] == 'null' ? null:$rawdata["EBITDAFQ"][$treports]);
    $params[] = ($rawdata["EBITDAFY"][$treports] == 'null' ? null:$rawdata["EBITDAFY"][$treports]);
    $params[] = ($rawdata["EBITDATTM"][$treports] == 'null' ? null:$rawdata["EBITDATTM"][$treports]);
    $params[] = ($rawdata["EBITFQ"][$treports] == 'null' ? null:$rawdata["EBITFQ"][$treports]);
    $params[] = ($rawdata["EBITFY"][$treports] == 'null' ? null:$rawdata["EBITFY"][$treports]);
    $params[] = ($rawdata["EBITTTM"][$treports] == 'null' ? null:$rawdata["EBITTTM"][$treports]);
    $params[] = ($rawdata["FreeCashFlowMarginPctFQ"][$treports] == 'null' ? null:$rawdata["FreeCashFlowMarginPctFQ"][$treports]);
    $params[] = ($rawdata["FreeCashFlowMarginPctFY"][$treports] == 'null' ? null:$rawdata["FreeCashFlowMarginPctFY"][$treports]);
    $params[] = ($rawdata["FreeCashFlowMarginPctTTM"][$treports] == 'null' ? null:$rawdata["FreeCashFlowMarginPctTTM"][$treports]);
    $params[] = ($rawdata["FreeCashFlowReturnonAssetsPctFY"][$treports] == 'null' ? null:$rawdata["FreeCashFlowReturnonAssetsPctFY"][$treports]);
    $params[] = ($rawdata["FreeCashFlowReturnonAssetsPctTTM"][$treports] == 'null' ? null:$rawdata["FreeCashFlowReturnonAssetsPctTTM"][$treports]);
    $params[] = ($rawdata["NetIncomeperEmployeeFY"][$treports] == 'null' ? null:$rawdata["NetIncomeperEmployeeFY"][$treports]);
    $params[] = ($rawdata["NetIncomeperEmployeeTTM"][$treports] == 'null' ? null:$rawdata["NetIncomeperEmployeeTTM"][$treports]);
    $params[] = ($rawdata["PretaxMarginPctFQ"][$treports] == 'null' ? null:$rawdata["PretaxMarginPctFQ"][$treports]);
    $params[] = ($rawdata["PretaxMarginPctFY"][$treports] == 'null' ? null:$rawdata["PretaxMarginPctFY"][$treports]);
    $params[] = ($rawdata["PretaxMarginPctTTM"][$treports] == 'null' ? null:$rawdata["PretaxMarginPctTTM"][$treports]);
    $params[] = ($rawdata["ReturnonAssetsPctFY"][$treports] == 'null' ? null:$rawdata["ReturnonAssetsPctFY"][$treports]);
    $params[] = ($rawdata["ReturnonAssetsPctTTM"][$treports] == 'null' ? null:$rawdata["ReturnonAssetsPctTTM"][$treports]);
    $params[] = ($rawdata["ReturnonEquityPctFY"][$treports] == 'null' ? null:$rawdata["ReturnonEquityPctFY"][$treports]);
    $params[] = ($rawdata["ReturnonEquityPctTTM"][$treports] == 'null' ? null:$rawdata["ReturnonEquityPctTTM"][$treports]);
    $params[] = ($rawdata["ReturnonInvestedCapitalPctFY"][$treports] == 'null' ? null:$rawdata["ReturnonInvestedCapitalPctFY"][$treports]);
    $params[] = ($rawdata["ReturnonInvestedCapitalPctTTM"][$treports] == 'null' ? null:$rawdata["ReturnonInvestedCapitalPctTTM"][$treports]);
    $params[] = ($rawdata["RevenueperEmployeeFY"][$treports] == 'null' ? null:$rawdata["RevenueperEmployeeFY"][$treports]);
    $params[] = ($rawdata["RevenueperEmployeeTTM"][$treports] == 'null' ? null:$rawdata["RevenueperEmployeeTTM"][$treports]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    //tickers_valuation_ratios
    $query = "INSERT INTO `tickers_valuation_ratios` (`ticker_id`, `TotalEquityFQ`, `TotalEquityFY`, `EarningsperShareNormalizedDilutedFQ`, `EarningsperShareNormalizedDilutedFY`, `EarningsperShareNormalizedDilutedTTM`, `AdjustedEPSDilutedPctGrowth3YearCAGRFY`, `AdjustedEPSDilutedPctGrowth5YearCAGRFY`, `AdjustedEPSDilutedPctGrowthFY`, `AdjustedEPSDilutedPctGrowthTTM`, `BasicAverageShares`, `DilutedAverageShares`, `DividendsperShareFQ`, `DividendsperShareFY`, `DividendsperShareTTM`, `EarningsperShareBasicFQ`, `EarningsperShareBasicFY`, `EarningsperShareBasicTTM`, `EarningsperShareDilutedFQ`, `EarningsperShareDilutedFY`, `EarningsperShareDilutedTTM`, `EnterpriseValueEBITDAFY`, `EnterpriseValueEBITDATTM`, `EnterpriseValueEBITFY`, `EnterpriseValueEBITTTM`, `EnterpriseValueFQ`, `EnterpriseValueFY`, `ExpectedAnnualDividends`, `PriceBookExclIntangiblesFQ`, `PriceBookExclIntangiblesFY`, `PriceEarningsNormalizedFY`, `PriceEarningsNormalizedTTM`, `PriceFreeCashFlowFY`, `PriceFreeCashFlowTTM`, `PriceRevenueFY`, `PriceRevenueTTM`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `TotalEquityFQ`=?, `TotalEquityFY`=?, `EarningsperShareNormalizedDilutedFQ`=?, `EarningsperShareNormalizedDilutedFY`=?, `EarningsperShareNormalizedDilutedTTM`=?, `AdjustedEPSDilutedPctGrowth3YearCAGRFY`=?, `AdjustedEPSDilutedPctGrowth5YearCAGRFY`=?, `AdjustedEPSDilutedPctGrowthFY`=?, `AdjustedEPSDilutedPctGrowthTTM`=?, `BasicAverageShares`=?, `DilutedAverageShares`=?, `DividendsperShareFQ`=?, `DividendsperShareFY`=?, `DividendsperShareTTM`=?, `EarningsperShareBasicFQ`=?, `EarningsperShareBasicFY`=?, `EarningsperShareBasicTTM`=?, `EarningsperShareDilutedFQ`=?, `EarningsperShareDilutedFY`=?, `EarningsperShareDilutedTTM`=?, `EnterpriseValueEBITDAFY`=?, `EnterpriseValueEBITDATTM`=?, `EnterpriseValueEBITFY`=?, `EnterpriseValueEBITTTM`=?, `EnterpriseValueFQ`=?, `EnterpriseValueFY`=?, `ExpectedAnnualDividends`=?, `PriceBookExclIntangiblesFQ`=?, `PriceBookExclIntangiblesFY`=?, `PriceEarningsNormalizedFY`=?, `PriceEarningsNormalizedTTM`=?, `PriceFreeCashFlowFY`=?, `PriceFreeCashFlowTTM`=?, `PriceRevenueFY`=?, `PriceRevenueTTM`=?";        
    $params = array();
    try {
        $params[] = ($rawdata["TotalEquityFQ"][$treports] == 'null' ? null:$rawdata["TotalEquityFQ"][$treports]);
        $params[] = ($rawdata["TotalEquityFY"][$treports] == 'null' ? null:$rawdata["TotalEquityFY"][$treports]);
        $params[] = ($rawdata["EarningsperShareNormalizedDilutedFQ"][$treports] == 'null' ? null:$rawdata["EarningsperShareNormalizedDilutedFQ"][$treports]);
        $params[] = ($rawdata["EarningsperShareNormalizedDilutedFY"][$treports] == 'null' ? null:$rawdata["EarningsperShareNormalizedDilutedFY"][$treports]);
        $params[] = ($rawdata["EarningsperShareNormalizedDilutedTTM"][$treports] == 'null' ? null:$rawdata["EarningsperShareNormalizedDilutedTTM"][$treports]);
        $params[] = ($rawdata["AdjustedEPSDilutedPctGrowth3YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedEPSDilutedPctGrowth3YearCAGRFY"][$treports]);
        $params[] = ($rawdata["AdjustedEPSDilutedPctGrowth5YearCAGRFY"][$treports] == 'null' ? null:$rawdata["AdjustedEPSDilutedPctGrowth5YearCAGRFY"][$treports]);
        $params[] = ($rawdata["AdjustedEPSDilutedPctGrowthFY"][$treports] == 'null' ? null:$rawdata["AdjustedEPSDilutedPctGrowthFY"][$treports]);
        $params[] = ($rawdata["AdjustedEPSDilutedPctGrowthTTM"][$treports] == 'null' ? null:$rawdata["AdjustedEPSDilutedPctGrowthTTM"][$treports]);
        $params[] = ($rawdata["BasicAverageShares"][$treports] == 'null' ? null:$rawdata["BasicAverageShares"][$treports]);
        $params[] = ($rawdata["DilutedAverageShares"][$treports] == 'null' ? null:$rawdata["DilutedAverageShares"][$treports]);
        $params[] = ($rawdata["DividendsperShareFQ"][$treports] == 'null' ? null:$rawdata["DividendsperShareFQ"][$treports]);
        $params[] = ($rawdata["DividendsperShareFY"][$treports] == 'null' ? null:$rawdata["DividendsperShareFY"][$treports]);
        $params[] = ($rawdata["DividendsperShareTTM"][$treports] == 'null' ? null:$rawdata["DividendsperShareTTM"][$treports]);
        $params[] = ($rawdata["EarningsperShareBasicFQ"][$treports] == 'null' ? null:$rawdata["EarningsperShareBasicFQ"][$treports]);
        $params[] = ($rawdata["EarningsperShareBasicFY"][$treports] == 'null' ? null:$rawdata["EarningsperShareBasicFY"][$treports]);
        $params[] = ($rawdata["EarningsperShareBasicTTM"][$treports] == 'null' ? null:$rawdata["EarningsperShareBasicTTM"][$treports]);
        $params[] = ($rawdata["EarningsperShareDilutedFQ"][$treports] == 'null' ? null:$rawdata["EarningsperShareDilutedFQ"][$treports]);
        $params[] = ($rawdata["EarningsperShareDilutedFY"][$treports] == 'null' ? null:$rawdata["EarningsperShareDilutedFY"][$treports]);
        $params[] = ($rawdata["EarningsperShareDilutedTTM"][$treports] == 'null' ? null:$rawdata["EarningsperShareDilutedTTM"][$treports]);
        $params[] = ($rawdata["EnterpriseValueEBITDAFY"][$treports] == 'null' ? null:$rawdata["EnterpriseValueEBITDAFY"][$treports]);
        $params[] = ($rawdata["EnterpriseValueEBITDATTM"][$treports] == 'null' ? null:$rawdata["EnterpriseValueEBITDATTM"][$treports]);
        $params[] = ($rawdata["EnterpriseValueEBITFY"][$treports] == 'null' ? null:$rawdata["EnterpriseValueEBITFY"][$treports]);
        $params[] = ($rawdata["EnterpriseValueEBITTTM"][$treports] == 'null' ? null:$rawdata["EnterpriseValueEBITTTM"][$treports]);
        $params[] = ($rawdata["EnterpriseValueFQ"][$treports] == 'null' ? null:$rawdata["EnterpriseValueFQ"][$treports]);
        $params[] = ($rawdata["EnterpriseValueFY"][$treports] == 'null' ? null:$rawdata["EnterpriseValueFY"][$treports]);
        $params[] = ($rawdata["ExpectedAnnualDividends"][$treports] == 'null' ? null:$rawdata["ExpectedAnnualDividends"][$treports]);
        $params[] = ($rawdata["PriceBookExclIntangiblesFQ"][$treports] == 'null' ? null:$rawdata["PriceBookExclIntangiblesFQ"][$treports]);
        $params[] = ($rawdata["PriceBookExclIntangiblesFY"][$treports] == 'null' ? null:$rawdata["PriceBookExclIntangiblesFY"][$treports]);
        $params[] = ($rawdata["PriceEarningsNormalizedFY"][$treports] == 'null' ? null:$rawdata["PriceEarningsNormalizedFY"][$treports]);
        $params[] = ($rawdata["PriceEarningsNormalizedTTM"][$treports] == 'null' ? null:$rawdata["PriceEarningsNormalizedTTM"][$treports]);
        $params[] = ($rawdata["PriceFreeCashFlowFY"][$treports] == 'null' ? null:$rawdata["PriceFreeCashFlowFY"][$treports]);
        $params[] = ($rawdata["PriceFreeCashFlowTTM"][$treports] == 'null' ? null:$rawdata["PriceFreeCashFlowTTM"][$treports]);
        $params[] = ($rawdata["PriceRevenueFY"][$treports] == 'null' ? null:$rawdata["PriceRevenueFY"][$treports]);
        $params[] = ($rawdata["PriceRevenueTTM"][$treports] == 'null' ? null:$rawdata["PriceRevenueTTM"][$treports]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    //tickers_metadata_eol
    $query = "INSERT INTO `tickers_metadata_eol` (`ticker_id`, `TotalSharesOutstandingDate`, `BusinessDescription`, `CITY`, `Country`, `Formername`, `Industry`, `InvRelationsEmail`, `LastAnnualEPS`, `LastAnnualNetIncome`, `LastAnnualRevenue`, `LastAnnualTotalAssets`, `PhoneAreaCode`, `PhoneCountryCode`, `PhoneNumber`, `PublicFloat`, `PublicFloatDate`, `Sector`, `State`, `StateofIncorporation`, `StreetAddress1`, `StreetAddress2`, `TaxID`, `WebSiteURL`, `ZipCode`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ON DUPLICATE KEY UPDATE `TotalSharesOutstandingDate`=?, `BusinessDescription`=?, `CITY`=?, `Country`=?, `Formername`=?, `Industry`=?, `InvRelationsEmail`=?, `LastAnnualEPS`=?, `LastAnnualNetIncome`=?, `LastAnnualRevenue`=?, `LastAnnualTotalAssets`=?, `PhoneAreaCode`=?, `PhoneCountryCode`=?, `PhoneNumber`=?, `PublicFloat`=?, `PublicFloatDate`=?, `Sector`=?, `State`=?, `StateofIncorporation`=?, `StreetAddress1`=?, `StreetAddress2`=?, `TaxID`=?, `WebSiteURL`=?, `ZipCode`=?";
    try {
        $params = array();
        $params[] = (date("Y-m-d",strtotime($rawdata["TotalSharesOutstandingDate"][$treports])));
        $params[] = ($rawdata["BusinessDescription"][$treports] =='null' ? null:$rawdata["BusinessDescription"][$treports]);
        $params[] = ($rawdata["CITY"][$treports] =='null' ? null:$rawdata["CITY"][$treports]);
        $params[] = ($rawdata["Country"][$treports] =='null' ? null:$rawdata["Country"][$treports]);
        $params[] = ($rawdata["Formername"][$treports] =='null' ? null:$rawdata["Formername"][$treports]);
        $params[] = ($rawdata["Industry"][$treports] =='null' ? null:$rawdata["Industry"][$treports]);
        $params[] = ($rawdata["InvRelationsEmail"][$treports]=='null' ? null:$rawdata["InvRelationsEmail"][$treports]);
        $params[] = ($rawdata["LastAnnualEPS"][$treports] =='null' ? null:$rawdata["LastAnnualEPS"][$treports]);
        $params[] = ($rawdata["LastAnnualNetIncome"][$treports] =='null' ? null:$rawdata["LastAnnualNetIncome"][$treports]);
        $params[] = ($rawdata["LastAnnualRevenue"][$treports] =='null' ? null:$rawdata["LastAnnualRevenue"][$treports]);
        $params[] = ($rawdata["LastAnnualTotalAssets"][$treports] =='null' ? null:$rawdata["LastAnnualTotalAssets"][$treports]);
        $params[] = ($rawdata["PhoneAreaCode"][$treports] =='null' ? null:$rawdata["PhoneAreaCode"][$treports]);
        $params[] = ($rawdata["PhoneCountryCode"][$treports] =='null' ? null:$rawdata["PhoneCountryCode"][$treports]);
        $params[] = ($rawdata["PhoneNumber"][$treports] =='null' ? null:$rawdata["PhoneNumber"][$treports]);
        $params[] = ($rawdata["PublicFloat"][$treports] =='null' ? null:$rawdata["PublicFloat"][$treports]);
        $params[] = (date("Y-m-d",strtotime($rawdata["PublicFloatDate"][$treports])));
        $params[] = ($rawdata["Sector"][$treports] =='null' ? null:$rawdata["Sector"][$treports]);
        $params[] = ($rawdata["State"][$treports] =='null' ? null:$rawdata["State"][$treports]);
        $params[] = ($rawdata["StateofIncorporation"][$treports] =='null' ? null:$rawdata["StateofIncorporation"][$treports]);
        $params[] = ($rawdata["StreetAddress1"][$treports] =='null' ? null:$rawdata["StreetAddress1"][$treports]);
        $params[] = ($rawdata["StreetAddress2"][$treports] =='null' ? null:$rawdata["StreetAddress2"][$treports]);
        $params[] = ($rawdata["TaxID"][$treports] =='null' ? null:$rawdata["TaxID"][$treports]);
        $params[] = ($rawdata["WebSiteURL"][$treports] =='null' ? null:$rawdata["WebSiteURL"][$treports]);
        $params[] = ($rawdata["ZipCode"][$treports] =='null' ? null:$rawdata["ZipCode"][$treports]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }

    //Update reports_* tables
    foreach($report_tables as $table) {
        $query = "DELETE FROM $table WHERE report_id IN (SELECT id FROM reports_header WHERE ticker_id = ".$dates->ticker_id.")";
        try {
            $db->exec($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    }
    $query = "DELETE FROM reports_header WHERE ticker_id = ".$dates->ticker_id;
    try {
        $db->exec($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }

    for($i=1; $i<=$treports; $i++) {
        if (!is_numeric($rawdata["duration"][$i])) {
            //reports_header
            $query = "INSERT IGNORE INTO `reports_header` (`report_type`, `report_date`, `ticker_id`, `fiscal_year`, `fiscal_quarter`) VALUES (?, ?, ?, ?, ?)";        	
            $params = array();
            $params[] = $rawdata["duration"][$i];
            $params[] = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]));
            $params[] = $dates->ticker_id;
            $params[] = $rawdata["fiscalYear"][$i];
            $params[] = $rawdata["FiscalQuarter"][$i];
            try {
                $res = $db->prepare($query);
                $res->execute($params);
                $affected_rows = $res->rowCount();
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }
            if ($affected_rows>0) {
                $report_id = $db->lastInsertId();

                //Get price and SO data for the report date
                $rdate = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]));
                $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$dates->ticker_id."' and report_date <= '".$rdate."' order by report_date desc limit 1";
                $price = null;
                try {
                    $rquote = $db->query($qquote);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
                $row_count = $rquote->rowCount();
                if($row_count > 0) {
                    $pricerow = $rquote->fetch(PDO::FETCH_ASSOC);
                    $rdate = $pricerow["report_date"];
                    $price = $pricerow["adj_close"];
                    $rawdata["SharesOutstandingDiluted"][$i] = max($rawdata["SharesOutstandingDiluted"][$i], $pricerow["SharesOutstandingY"]/1000000, $pricerow["SharesOutstandingBC"]/1000000);
                    $rawdata["SharesOutstandingBasic"][$i] = max($rawdata["SharesOutstandingBasic"][$i], $pricerow["SharesOutstandingY"]/1000000, $pricerow["SharesOutstandingBC"]/1000000);
                }

                //reports_balanceconsolidated
                $query = "INSERT INTO `reports_balanceconsolidated` (`report_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["CommitmentsContingencies"][$i] =='null' ? null:$rawdata["CommitmentsContingencies"][$i]);
                $params[] = ($rawdata["CommonStock"][$i] =='null' ? null:$rawdata["CommonStock"][$i] );
                $params[] = ($rawdata["DeferredCharges"][$i] =='null' ? null:$rawdata["DeferredCharges"][$i]);
                $params[] = ($rawdata["DeferredIncomeTaxesCurrent"][$i] =='null' ? null:$rawdata["DeferredIncomeTaxesCurrent"][$i]);
                $params[] = ($rawdata["DeferredIncomeTaxesLongterm"][$i] =='null' ? null:$rawdata["DeferredIncomeTaxesLongterm"][$i]);
                $params[] = ($rawdata["AccountsPayableandAccruedExpenses"][$i] =='null' ? null:$rawdata["AccountsPayableandAccruedExpenses"][$i]);
                $params[] = ($rawdata["AccruedInterest"][$i] =='null' ? null:$rawdata["AccruedInterest"][$i]);
                $params[] = ($rawdata["AdditionalPaidinCapital"][$i] =='null' ? null:$rawdata["AdditionalPaidinCapital"][$i]);
                $params[] = ($rawdata["AdditionalPaidinCapitalPreferredStock"][$i] =='null' ? null:$rawdata["AdditionalPaidinCapitalPreferredStock"][$i]);
                $params[] = ($rawdata["CashandCashEquivalents"][$i] =='null' ? null:$rawdata["CashandCashEquivalents"][$i]);
                $params[] = ($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] =='null' ? null:$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]);
                $params[] = ($rawdata["Goodwill"][$i] =='null' ? null:$rawdata["Goodwill"][$i]);
                $params[] = ($rawdata["IntangibleAssets"][$i] =='null' ? null:$rawdata["IntangibleAssets"][$i]);
                $params[] = ($rawdata["InventoriesNet"][$i] =='null' ? null:$rawdata["InventoriesNet"][$i]);
                $params[] = ($rawdata["LongtermDeferredIncomeTaxLiabilities"][$i] =='null' ? null:$rawdata["LongtermDeferredIncomeTaxLiabilities"][$i]);
                $params[] = ($rawdata["LongtermDeferredLiabilityCharges"][$i] =='null' ? null:$rawdata["LongtermDeferredLiabilityCharges"][$i]);
                $params[] = ($rawdata["LongtermInvestments"][$i] =='null' ? null:$rawdata["LongtermInvestments"][$i]);
                $params[] = ($rawdata["MinorityInterest"][$i] =='null' ? null:$rawdata["MinorityInterest"][$i]);
                $params[] = ($rawdata["OtherAccumulatedComprehensiveIncome"][$i] =='null' ? null:$rawdata["OtherAccumulatedComprehensiveIncome"][$i]);
                $params[] = ($rawdata["OtherAssets"][$i] =='null' ? null:$rawdata["OtherAssets"][$i]);
                $params[] = ($rawdata["OtherCurrentAssets"][$i] =='null' ? null:$rawdata["OtherCurrentAssets"][$i]);
                $params[] = ($rawdata["OtherCurrentLiabilities"][$i] =='null' ? null:$rawdata["OtherCurrentLiabilities"][$i]);
                $params[] = ($rawdata["OtherEquity"][$i] =='null' ? null:$rawdata["OtherEquity"][$i]);
                $params[] = ($rawdata["OtherInvestments"][$i] =='null' ? null:$rawdata["OtherInvestments"][$i]);
                $params[] = ($rawdata["OtherLiabilities"][$i] =='null' ? null:$rawdata["OtherLiabilities"][$i]);
                $params[] = ($rawdata["PartnersCapital"][$i] =='null' ? null:$rawdata["PartnersCapital"][$i]);
                $params[] = ($rawdata["PensionPostretirementObligation"][$i] =='null' ? null:$rawdata["PensionPostretirementObligation"][$i]);
                $params[] = ($rawdata["PreferredStock"][$i] =='null' ? null:$rawdata["PreferredStock"][$i]);
                $params[] = ($rawdata["PrepaidExpenses"][$i] =='null' ? null:$rawdata["PrepaidExpenses"][$i]);
                $params[] = ($rawdata["PropertyPlantEquipmentNet"][$i] =='null' ? null:$rawdata["PropertyPlantEquipmentNet"][$i]);
                $params[] = ($rawdata["RestrictedCash"][$i] =='null' ? null:$rawdata["RestrictedCash"][$i]);
                $params[] = ($rawdata["RetainedEarnings"][$i] =='null' ? null:$rawdata["RetainedEarnings"][$i]);
                $params[] = ($rawdata["TemporaryEquity"][$i] =='null' ? null:$rawdata["TemporaryEquity"][$i]);
                $params[] = ($rawdata["TotalAssets"][$i] =='null' ? null:$rawdata["TotalAssets"][$i]);
                $params[] = ($rawdata["TotalCurrentAssets"][$i] =='null' ? null:$rawdata["TotalCurrentAssets"][$i]);
                $params[] = ($rawdata["TotalCurrentLiabilities"][$i] =='null' ? null:$rawdata["TotalCurrentLiabilities"][$i]);
                $params[] = ($rawdata["TotalLiabilities"][$i] =='null' ? null:$rawdata["TotalLiabilities"][$i]);
                $params[] = ($rawdata["TotalLongtermDebt"][$i] =='null' ? null:$rawdata["TotalLongtermDebt"][$i]);
                $params[] = ($rawdata["TotalReceivablesNet"][$i] =='null' ? null:$rawdata["TotalReceivablesNet"][$i]);
                $params[] = ($rawdata["TotalShorttermDebt"][$i] =='null' ? null:$rawdata["TotalShorttermDebt"][$i]);
                $params[] = ($rawdata["TotalStockholdersEquity"][$i] =='null' ? null:$rawdata["TotalStockholdersEquity"][$i]);
                $params[] = ($rawdata["TreasuryStock"][$i] =='null' ? null:$rawdata["TreasuryStock"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_balancefull` (`report_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["TotalDebt"][$i] =='null' ? null:$rawdata["TotalDebt"][$i]);
                $params[] = ($rawdata["TotalAssetsFQ"][$i] =='null' ? null:$rawdata["TotalAssetsFQ"][$i]);
                $params[] = ($rawdata["TotalAssetsFY"][$i] =='null' ? null:$rawdata["TotalAssetsFY"][$i]);
                $params[] = ($rawdata["CurrentPortionofLongtermDebt"][$i] =='null' ? null:$rawdata["CurrentPortionofLongtermDebt"][$i]);
                $params[] = ($rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$i] =='null' ? null:$rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$i]);
                $params[] = ($rawdata["DeferredLiabilityCharges"][$i] =='null' ? null:$rawdata["DeferredLiabilityCharges"][$i]);
                $params[] = ($rawdata["AccountsNotesReceivableNet"][$i] =='null' ? null:$rawdata["AccountsNotesReceivableNet"][$i]);
                $params[] = ($rawdata["AccountsPayable"][$i] =='null' ? null:$rawdata["AccountsPayable"][$i]);
                $params[] = ($rawdata["AccountsReceivableTradeNet"][$i] =='null' ? null:$rawdata["AccountsReceivableTradeNet"][$i]);
                $params[] = ($rawdata["AccruedExpenses"][$i] =='null' ? null:$rawdata["AccruedExpenses"][$i]);
                $params[] = ($rawdata["AccumulatedDepreciation"][$i] =='null' ? null:$rawdata["AccumulatedDepreciation"][$i]);
                $params[] = ($rawdata["AmountsDuetoRelatedPartiesShortterm"][$i] =='null' ? null:$rawdata["AmountsDuetoRelatedPartiesShortterm"][$i]);
                $params[] = ($rawdata["GoodwillIntangibleAssetsNet"][$i] =='null' ? null:$rawdata["GoodwillIntangibleAssetsNet"][$i]);
                $params[] = ($rawdata["IncomeTaxesPayable"][$i] =='null' ? null:$rawdata["IncomeTaxesPayable"][$i]);
                $params[] = ($rawdata["LiabilitiesStockholdersEquity"][$i] =='null' ? null:$rawdata["LiabilitiesStockholdersEquity"][$i]);
                $params[] = ($rawdata["LongtermDebt"][$i] =='null' ? null:$rawdata["LongtermDebt"][$i]);
                $params[] = ($rawdata["NotesPayable"][$i] =='null' ? null:$rawdata["NotesPayable"][$i]);
                $params[] = ($rawdata["OperatingLeases"][$i] =='null' ? null:$rawdata["OperatingLeases"][$i]);
                $params[] = ($rawdata["OtherAccountsNotesReceivable"][$i] =='null' ? null:$rawdata["OtherAccountsNotesReceivable"][$i]);
                $params[] = ($rawdata["OtherAccountsPayableandAccruedExpenses"][$i] =='null' ? null:$rawdata["OtherAccountsPayableandAccruedExpenses"][$i]);
                $params[] = ($rawdata["OtherBorrowings"][$i] =='null' ? null:$rawdata["OtherBorrowings"][$i]);
                $params[] = ($rawdata["OtherReceivables"][$i] =='null' ? null:$rawdata["OtherReceivables"][$i]);
                $params[] = ($rawdata["PropertyandEquipmentGross"][$i] =='null' ? null:$rawdata["PropertyandEquipmentGross"][$i]);
                $params[] = ($rawdata["TotalLongtermAssets"][$i] =='null' ? null:$rawdata["TotalLongtermAssets"][$i]);
                $params[] = ($rawdata["TotalLongtermLiabilities"][$i] =='null' ? null:$rawdata["TotalLongtermLiabilities"][$i]);
                $params[] = ($rawdata["TotalSharesOutstanding"][$i] =='null' ? null:$rawdata["TotalSharesOutstanding"][$i]);
                $params[] = ($rawdata["ShorttermInvestments"][$i] =='null' ? null:$rawdata["ShorttermInvestments"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_cashflowconsolidated` (`report_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["ChangeinCurrentAssets"][$i] =='null' ? null:$rawdata["ChangeinCurrentAssets"][$i]);
                $params[] = ($rawdata["ChangeinCurrentLiabilities"][$i] =='null' ? null:$rawdata["ChangeinCurrentLiabilities"][$i]);
                $params[] = ($rawdata["ChangeinDebtNet"][$i] =='null' ? null:$rawdata["ChangeinDebtNet"][$i]);
                $params[] = ($rawdata["ChangeinDeferredRevenue"][$i] =='null' ? null:$rawdata["ChangeinDeferredRevenue"][$i]);
                $params[] = ($rawdata["ChangeinEquityNet"][$i] =='null' ? null:$rawdata["ChangeinEquityNet"][$i]);
                $params[] = ($rawdata["ChangeinIncomeTaxesPayable"][$i] =='null' ? null:$rawdata["ChangeinIncomeTaxesPayable"][$i]);
                $params[] = ($rawdata["ChangeinInventories"][$i] =='null' ? null:$rawdata["ChangeinInventories"][$i]);
                $params[] = ($rawdata["ChangeinOperatingAssetsLiabilities"][$i] =='null' ? null:$rawdata["ChangeinOperatingAssetsLiabilities"][$i]);
                $params[] = ($rawdata["ChangeinOtherAssets"][$i] =='null' ? null:$rawdata["ChangeinOtherAssets"][$i]);
                $params[] = ($rawdata["ChangeinOtherCurrentAssets"][$i] =='null' ? null:$rawdata["ChangeinOtherCurrentAssets"][$i]);
                $params[] = ($rawdata["ChangeinOtherCurrentLiabilities"][$i] =='null' ? null:$rawdata["ChangeinOtherCurrentLiabilities"][$i] );
                $params[] = ($rawdata["ChangeinOtherLiabilities"][$i] =='null' ? null:$rawdata["ChangeinOtherLiabilities"][$i]);
                $params[] = ($rawdata["ChangeinPrepaidExpenses"][$i] =='null' ? null:$rawdata["ChangeinPrepaidExpenses"][$i]);
                $params[] = ($rawdata["DividendsPaid"][$i] =='null' ? null:$rawdata["DividendsPaid"][$i]);
                $params[] = ($rawdata["EffectofExchangeRateonCash"][$i] =='null' ? null:$rawdata["EffectofExchangeRateonCash"][$i]);
                $params[] = ($rawdata["EmployeeCompensation"][$i] =='null' ? null:$rawdata["EmployeeCompensation"][$i]);
                $params[] = ($rawdata["AcquisitionSaleofBusinessNet"][$i] =='null' ? null:$rawdata["AcquisitionSaleofBusinessNet"][$i]);
                $params[] = ($rawdata["AdjustmentforEquityEarnings"][$i] =='null' ? null:$rawdata["AdjustmentforEquityEarnings"][$i]);
                $params[] = ($rawdata["AdjustmentforMinorityInterest"][$i] =='null' ? null:$rawdata["AdjustmentforMinorityInterest"][$i]);
                $params[] = ($rawdata["AdjustmentforSpecialCharges"][$i] =='null' ? null:$rawdata["AdjustmentforSpecialCharges"][$i]);
                $params[] = ($rawdata["CapitalExpenditures"][$i] =='null' ? null:$rawdata["CapitalExpenditures"][$i]);
                $params[] = ($rawdata["CashfromDiscontinuedOperations"][$i] =='null' ? null:$rawdata["CashfromDiscontinuedOperations"][$i]);
                $params[] = ($rawdata["CashfromFinancingActivities"][$i] =='null' ? null:$rawdata["CashfromFinancingActivities"][$i]);
                $params[] = ($rawdata["CashfromInvestingActivities"][$i] =='null' ? null:$rawdata["CashfromInvestingActivities"][$i]);
                $params[] = ($rawdata["CashfromOperatingActivities"][$i] =='null' ? null:$rawdata["CashfromOperatingActivities"][$i]);
                $params[] = ($rawdata["CFDepreciationAmortization"][$i] =='null' ? null:$rawdata["CFDepreciationAmortization"][$i]);
                $params[] = ($rawdata["DeferredIncomeTaxes"][$i] =='null' ? null:$rawdata["DeferredIncomeTaxes"][$i]);
                $params[] = ($rawdata["ChangeinAccountsPayableAccruedExpenses"][$i] =='null' ? null:$rawdata["ChangeinAccountsPayableAccruedExpenses"][$i]);
                $params[] = ($rawdata["ChangeinAccountsReceivable"][$i] =='null' ? null:$rawdata["ChangeinAccountsReceivable"][$i]);
                $params[] = ($rawdata["InvestmentChangesNet"][$i] =='null' ? null:$rawdata["InvestmentChangesNet"][$i]);
                $params[] = ($rawdata["NetChangeinCash"][$i] =='null' ? null:$rawdata["NetChangeinCash"][$i]);
                $params[] = ($rawdata["OtherAdjustments"][$i] =='null' ? null:$rawdata["OtherAdjustments"][$i] );
                $params[] = ($rawdata["OtherAssetLiabilityChangesNet"][$i] =='null' ? null:$rawdata["OtherAssetLiabilityChangesNet"][$i]);
                $params[] = ($rawdata["OtherFinancingActivitiesNet"][$i] =='null' ? null:$rawdata["OtherFinancingActivitiesNet"][$i]);
                $params[] = ($rawdata["OtherInvestingActivities"][$i] =='null' ? null:$rawdata["OtherInvestingActivities"][$i]);
                $params[] = ($rawdata["RealizedGainsLosses"][$i] =='null' ? null:$rawdata["RealizedGainsLosses"][$i]);
                $params[] = ($rawdata["SaleofPropertyPlantEquipment"][$i] =='null' ? null:$rawdata["SaleofPropertyPlantEquipment"][$i]);
                $params[] = ($rawdata["StockOptionTaxBenefits"][$i] =='null' ? null:$rawdata["StockOptionTaxBenefits"][$i]);
                $params[] = ($rawdata["TotalAdjustments"][$i] =='null' ? null:$rawdata["TotalAdjustments"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_cashflowfull` (`report_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["ChangeinLongtermDebtNet"][$i] =='null' ? null:$rawdata["ChangeinLongtermDebtNet"][$i]);
                $params[] = ($rawdata["ChangeinShorttermBorrowingsNet"][$i] =='null' ? null:$rawdata["ChangeinShorttermBorrowingsNet"][$i]);
                $params[] = ($rawdata["CashandCashEquivalentsBeginningofYear"][$i] =='null' ? null:$rawdata["CashandCashEquivalentsBeginningofYear"][$i]);
                $params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$i] =='null' ? null:$rawdata["CashandCashEquivalentsEndofYear"][$i]);
                $params[] = ($rawdata["CashPaidforIncomeTaxes"][$i] =='null' ? null:$rawdata["CashPaidforIncomeTaxes"][$i]);
                $params[] = ($rawdata["CashPaidforInterestExpense"][$i] =='null' ? null:$rawdata["CashPaidforInterestExpense"][$i]);
                $params[] = ($rawdata["CFNetIncome"][$i] =='null' ? null:$rawdata["CFNetIncome"][$i]);
                $params[] = ($rawdata["IssuanceofEquity"][$i] =='null' ? null:$rawdata["IssuanceofEquity"][$i]);
                $params[] = ($rawdata["LongtermDebtPayments"][$i] =='null' ? null:$rawdata["LongtermDebtPayments"][$i]);
                $params[] = ($rawdata["LongtermDebtProceeds"][$i] =='null' ? null:$rawdata["LongtermDebtProceeds"][$i]);
                $params[] = ($rawdata["OtherDebtNet"][$i] =='null' ? null:$rawdata["OtherDebtNet"][$i]);
                $params[] = ($rawdata["OtherEquityTransactionsNet"][$i] =='null' ? null:$rawdata["OtherEquityTransactionsNet"][$i]);
                $params[] = ($rawdata["OtherInvestmentChangesNet"][$i] =='null' ? null:$rawdata["OtherInvestmentChangesNet"][$i]);
                $params[] = ($rawdata["PurchaseofInvestments"][$i] =='null' ? null:$rawdata["PurchaseofInvestments"][$i]);
                $params[] = ($rawdata["RepurchaseofEquity"][$i] =='null' ? null:$rawdata["RepurchaseofEquity"][$i]);
                $params[] = ($rawdata["SaleofInvestments"][$i] =='null' ? null:$rawdata["SaleofInvestments"][$i]);
                $params[] = ($rawdata["ShorttermBorrowings"][$i] =='null' ? null:$rawdata["ShorttermBorrowings"][$i]);
                $params[] = ($rawdata["TotalNoncashAdjustments"][$i] =='null' ? null:$rawdata["TotalNoncashAdjustments"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_financialheader` (`report_id`, `USDConversionRate`, `Restated`, `ReceivedDate`, `Preliminary`, `PeriodLengthCode`, `PeriodLength`, `Original`, `FormType`, `FiledDate`, `DCN`, `CurrencyCode`, `CrossCalculated`, `Audited`, `Amended`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["USDConversionRate"][$i] =='null' ? null:$rawdata["USDConversionRate"][$i]);
                $params[] = ($rawdata["Restated"][$i] == "false" ? 0 : 1);
                $params[] = date("Y-m-d",strtotime($rawdata["ReceivedDate"][$i]));
                $params[] = ($rawdata["Preliminary"][$i] == "false" ? 0 : 1);
                $params[] = ($rawdata["PeriodLengthCode"][$i]=='null' ? null:$rawdata["PeriodLengthCode"][$i]);
                $params[] = $rawdata["PeriodLength"][$i];
                $params[] = ($rawdata["Original"][$i] == "false" ? 0 : 1);
                $params[] = ($rawdata["FormType"][$i]=='null' ? null:$rawdata["FormType"][$i]);
                $params[] = date("Y-m-d",strtotime($rawdata["FiledDate"][$i]));
                $params[] = ($rawdata["DCN"][$i]=='null' ? null:$rawdata["DCN"][$i]);
                $params[] = ($rawdata["CurrencyCode"][$i]=='null' ? null:$rawdata["CurrencyCode"][$i]);
                $params[] = ($rawdata["CrossCalculated"][$i] == "false" ? 0 : 1);
                $params[] = ($rawdata["Audited"][$i] == "false" ? 0 : 1);
                $params[] = ($rawdata["Amended"][$i] == "false" ? 0 : 1);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
                //reports_gf_data
                $query = "INSERT INTO `reports_gf_data` (`report_id`, `fiscalPeriod_eol`, `fiscalPeriod_gf`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ((!isset($rawdata["fiscalPeriod"]) || $rawdata["fiscalPeriod"][$i] =='null') ? null:$rawdata["fiscalPeriod"][$i]);
                $params[] = ((!isset($rawdata["FiscalPeriod"]) || $rawdata["FiscalPeriod"][$i] =='null') ? null:$rawdata["FiscalPeriod"][$i]);
                $params[] = ((!isset($rawdata["InterestIncome"]) || $rawdata["InterestIncome"][$i] =='null') ? null:toFloat($rawdata["InterestIncome"][$i]));
                $params[] = ((!isset($rawdata["InterestExpense"]) || $rawdata["InterestExpense"][$i] =='null') ? null:toFloat($rawdata["InterestExpense"][$i]));
                $params[] = ((!isset($rawdata["EPSBasic"]) || $rawdata["EPSBasic"][$i] =='null') ? null:toFloat($rawdata["EPSBasic"][$i]));
                $params[] = ((!isset($rawdata["EPSDiluted"]) || $rawdata["EPSDiluted"][$i] =='null') ? null:toFloat($rawdata["EPSDiluted"][$i]));
                $params[] = ((!isset($rawdata["SharesOutstandingDiluted"]) || $rawdata["SharesOutstandingDiluted"][$i] =='null') ? null:toFloat($rawdata["SharesOutstandingDiluted"][$i]));
                $params[] = ((!isset($rawdata["InventoriesRawMaterialsComponents"]) || $rawdata["InventoriesRawMaterialsComponents"][$i] =='null') ? null:toFloat($rawdata["InventoriesRawMaterialsComponents"][$i]));
                $params[] = ((!isset($rawdata["InventoriesWorkInProcess"]) || $rawdata["InventoriesWorkInProcess"][$i] =='null') ? null:toFloat($rawdata["InventoriesWorkInProcess"][$i]));
                $params[] = ((!isset($rawdata["InventoriesInventoriesAdjustments"]) || $rawdata["InventoriesInventoriesAdjustments"][$i] =='null') ? null:toFloat($rawdata["InventoriesInventoriesAdjustments"][$i]));
                $params[] = ((!isset($rawdata["InventoriesFinishedGoods"]) || $rawdata["InventoriesFinishedGoods"][$i] =='null') ? null:toFloat($rawdata["InventoriesFinishedGoods"][$i]));
                $params[] = ((!isset($rawdata["InventoriesOther"]) || $rawdata["InventoriesOther"][$i] =='null') ? null:toFloat($rawdata["InventoriesOther"][$i]));
                $params[] = ((!isset($rawdata["TotalInventories"]) || $rawdata["TotalInventories"][$i] =='null') ? null:toFloat($rawdata["TotalInventories"][$i]));
                $params[] = ((!isset($rawdata["LandAndImprovements"]) || $rawdata["LandAndImprovements"][$i] =='null') ? null:toFloat($rawdata["LandAndImprovements"][$i]));
                $params[] = ((!isset($rawdata["BuildingsAndImprovements"]) || $rawdata["BuildingsAndImprovements"][$i] =='null') ? null:toFloat($rawdata["BuildingsAndImprovements"][$i]));
                $params[] = ((!isset($rawdata["MachineryFurnitureEquipment"]) || $rawdata["MachineryFurnitureEquipment"][$i] =='null') ? null:toFloat($rawdata["MachineryFurnitureEquipment"][$i]));
                $params[] = ((!isset($rawdata["ConstructionInProgress"]) || $rawdata["ConstructionInProgress"][$i] =='null') ? null:toFloat($rawdata["ConstructionInProgress"][$i]));
                $params[] = ((!isset($rawdata["GrossPropertyPlantandEquipment"]) || $rawdata["GrossPropertyPlantandEquipment"][$i] =='null') ? null:toFloat($rawdata["GrossPropertyPlantandEquipment"][$i]));
                $params[] = ((!isset($rawdata["SharesOutstandingBasic"]) || $rawdata["SharesOutstandingBasic"][$i] =='null') ? null:toFloat($rawdata["SharesOutstandingBasic"][$i]));
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_incomeconsolidated` (`report_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                if ($rawdata["EBIT"][$i] === "null" && $rawdata["OperatingProfit"][$i] !== "null") {
                    $rawdata["EBIT"][$i] = $rawdata["OperatingProfit"][$i];
                }
                $params[] = ($rawdata["EBIT"][$i] =='null' ? null:$rawdata["EBIT"][$i]);
                $params[] = ($rawdata["CostofRevenue"][$i] =='null' ? null:$rawdata["CostofRevenue"][$i]);
                $params[] = ($rawdata["DepreciationAmortizationExpense"][$i] =='null' ? null:$rawdata["DepreciationAmortizationExpense"][$i]);
                $params[] = ($rawdata["DilutedEPSNetIncome"][$i] =='null' ? null:$rawdata["DilutedEPSNetIncome"][$i]);
                $params[] = ($rawdata["DiscontinuedOperations"][$i] =='null' ? null:$rawdata["DiscontinuedOperations"][$i]);
                $params[] = ($rawdata["EquityEarnings"][$i] =='null' ? null:$rawdata["EquityEarnings"][$i]);
                $params[] = ($rawdata["AccountingChange"][$i] =='null' ? null:$rawdata["AccountingChange"][$i]);
                $params[] = ($rawdata["BasicEPSNetIncome"][$i] =='null' ? null:$rawdata["BasicEPSNetIncome"][$i]);
                $params[] = ($rawdata["ExtraordinaryItems"][$i] =='null' ? null:$rawdata["ExtraordinaryItems"][$i]);
                $params[] = ($rawdata["GrossProfit"][$i] =='null' ? null:$rawdata["GrossProfit"][$i]);
                $params[] = ($rawdata["IncomebeforeExtraordinaryItems"][$i] =='null' ? null:$rawdata["IncomebeforeExtraordinaryItems"][$i]);
                $params[] = ($rawdata["IncomeBeforeTaxes"][$i] =='null' ? null:$rawdata["IncomeBeforeTaxes"][$i]);
                $params[] = ($rawdata["IncomeTaxes"][$i] =='null' ? null:$rawdata["IncomeTaxes"][$i]);
                $params[] = ($rawdata["InterestExpense"][$i] =='null' ? null:toFloat($rawdata["InterestExpense"][$i]));
                $params[] = ($rawdata["InterestIncome"][$i] =='null' ? null:toFloat($rawdata["InterestIncome"][$i]));
                $params[] = ($rawdata["MinorityInterestEquityEarnings"][$i] =='null' ? null:$rawdata["MinorityInterestEquityEarnings"][$i]);
                $params[] = ($rawdata["NetIncome"][$i] =='null' ? null:$rawdata["NetIncome"][$i]);
                $params[] = ($rawdata["NetIncomeApplicabletoCommon"][$i] =='null' ? null:$rawdata["NetIncomeApplicabletoCommon"][$i]);
                $params[] = ($rawdata["OperatingProfit"][$i] =='null' ? null:$rawdata["OperatingProfit"][$i]);
                $params[] = ($rawdata["OtherNonoperatingIncomeExpense"][$i] =='null' ? null:$rawdata["OtherNonoperatingIncomeExpense"][$i]);
                $params[] = ($rawdata["OtherOperatingExpenses"][$i] =='null' ? null:$rawdata["OtherOperatingExpenses"][$i]);
                $params[] = ($rawdata["ResearchDevelopmentExpense"][$i] =='null' ? null:$rawdata["ResearchDevelopmentExpense"][$i]);
                $params[] = ($rawdata["RestructuringRemediationImpairmentProvisions"][$i] =='null' ? null:$rawdata["RestructuringRemediationImpairmentProvisions"][$i]);
                $params[] = ($rawdata["TotalRevenue"][$i] =='null' ? null:$rawdata["TotalRevenue"][$i]);
                $params[] = ($rawdata["SellingGeneralAdministrativeExpenses"][$i] =='null' ? null:$rawdata["SellingGeneralAdministrativeExpenses"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_incomefull` (`report_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["AdjustedEBIT"][$i] =='null' ? null:$rawdata["AdjustedEBIT"][$i]);
                $params[] = ($rawdata["AdjustedEBITDA"][$i] =='null' ? null:$rawdata["AdjustedEBITDA"][$i]);
                $params[] = ($rawdata["AdjustedNetIncome"][$i] =='null' ? null:$rawdata["AdjustedNetIncome"][$i]);
                $params[] = ($rawdata["AftertaxMargin"][$i] =='null' ? null:$rawdata["AftertaxMargin"][$i]);
                if ($rawdata["EBITDA"][$i] === "null" && $rawdata["OperatingProfit"][$i] !== "null") {
                    $rawdata["EBITDA"][$i] = $rawdata["OperatingProfit"][$i] + $rawdata["DepreciationAmortizationExpense"][$i];
                }
                $params[] = ($rawdata["EBITDA"][$i] =='null' ? null:$rawdata["EBITDA"][$i]);
                $params[] = ($rawdata["GrossMargin"][$i] =='null' ? null:$rawdata["GrossMargin"][$i]);
                $params[] = ($rawdata["NetOperatingProfitafterTax"][$i] =='null' ? null:$rawdata["NetOperatingProfitafterTax"][$i]);
                $params[] = ($rawdata["OperatingMargin"][$i] =='null' ? null:$rawdata["OperatingMargin"][$i]);
                $params[] = ($rawdata["RevenueFQ"][$i] =='null' ? null:$rawdata["RevenueFQ"][$i]);
                $params[] = ($rawdata["RevenueFY"][$i] =='null' ? null:$rawdata["RevenueFY"][$i]);
                $params[] = ($rawdata["RevenueTTM"][$i] =='null' ? null:$rawdata["RevenueTTM"][$i]);
                $params[] = ($rawdata["CostOperatingExpenses"][$i] =='null' ? null:$rawdata["CostOperatingExpenses"][$i]);
                $params[] = ($rawdata["DepreciationExpense"][$i] =='null' ? null:$rawdata["DepreciationExpense"][$i]);
                $params[] = ($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$i] =='null' ? null:$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$i]);
                $params[] = ($rawdata["DilutedWeightedAverageShares"][$i] =='null' ? null:$rawdata["DilutedWeightedAverageShares"][$i]);
                $params[] = ($rawdata["AmortizationExpense"][$i] =='null' ? null:$rawdata["AmortizationExpense"][$i]);
                $params[] = ($rawdata["BasicEPSNetIncomefromContinuingOperations"][$i] =='null' ? null:$rawdata["BasicEPSNetIncomefromContinuingOperations"][$i]);
                $params[] = ($rawdata["BasicWeightedAverageShares"][$i] =='null' ? null:$rawdata["BasicWeightedAverageShares"][$i]);
                $params[] = ($rawdata["GeneralAdministrativeExpense"][$i] =='null' ? null:$rawdata["GeneralAdministrativeExpense"][$i]);
                $params[] = ($rawdata["IncomeAfterTaxes"][$i] =='null' ? null:$rawdata["IncomeAfterTaxes"][$i]);
                $params[] = ($rawdata["LaborExpense"][$i] =='null' ? null:$rawdata["LaborExpense"][$i]);
                $params[] = ($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$i] =='null' ? null:$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$i]);
                $params[] = ($rawdata["InterestIncomeExpenseNet"][$i] =='null' ? null:$rawdata["InterestIncomeExpenseNet"][$i]);
                $params[] = ($rawdata["NoncontrollingInterest"][$i] =='null' ? null:$rawdata["NoncontrollingInterest"][$i]);
                $params[] = ($rawdata["NonoperatingGainsLosses"][$i] =='null' ? null:$rawdata["NonoperatingGainsLosses"][$i]);
                $params[] = ($rawdata["OperatingExpenses"][$i] =='null' ? null:$rawdata["OperatingExpenses"][$i]);
                $params[] = ($rawdata["OtherGeneralAdministrativeExpense"][$i] =='null' ? null:$rawdata["OtherGeneralAdministrativeExpense"][$i]);
                $params[] = ($rawdata["OtherInterestIncomeExpenseNet"][$i] =='null' ? null:$rawdata["OtherInterestIncomeExpenseNet"][$i]);
                $params[] = ($rawdata["OtherRevenue"][$i] =='null' ? null:$rawdata["OtherRevenue"][$i]);
                $params[] = ($rawdata["OtherSellingGeneralAdministrativeExpenses"][$i] =='null' ? null:$rawdata["OtherSellingGeneralAdministrativeExpenses"][$i]);
                $params[] = ($rawdata["PreferredDividends"][$i] =='null' ? null:$rawdata["PreferredDividends"][$i]);
                $params[] = ($rawdata["SalesMarketingExpense"][$i] =='null' ? null:$rawdata["SalesMarketingExpense"][$i]);
                $params[] = ($rawdata["TotalNonoperatingIncomeExpense"][$i] =='null' ? null:$rawdata["TotalNonoperatingIncomeExpense"][$i]);
                $params[] = ($rawdata["TotalOperatingExpenses"][$i] =='null' ? null:$rawdata["TotalOperatingExpenses"][$i]);
                $params[] = ($rawdata["OperatingRevenue"][$i] =='null' ? null:$rawdata["OperatingRevenue"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_metadata_eol` (`report_id`, `CoverSheetTSO`, `CoverSheetTSODate`, `AuditorCode`, `AuditorOpinion`, `InventoryPolicy`, `NumberofShareholders`, `NumberofEmployees`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["CoverSheetTSO"][$i] =='null' ? null:$rawdata["CoverSheetTSO"][$i]);
                $params[] = date("Y-m-d",strtotime($rawdata["CoverSheetTSODate"][$i]));
                $params[] = ($rawdata["AuditorCode"][$i] =='null' ? null:$rawdata["AuditorCode"][$i]);
                $params[] = ($rawdata["AuditorOpinion"][$i] =='null' ? null:$rawdata["AuditorOpinion"][$i]);
                $params[] = ($rawdata["InventoryPolicy"][$i] =='null' ? null:$rawdata["InventoryPolicy"][$i]);
                $params[] = ($rawdata["NumberofShareholders"][$i] =='null' ? null:$rawdata["NumberofShareholders"][$i]);
                $params[] = ($rawdata["NumberofEmployees"][$i] =='null' ? null:$rawdata["NumberofEmployees"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
                //reports_variable_ratios
                $query = "INSERT INTO `reports_variable_ratios` (`report_id`, `BookEquity`, `DebttoAssets`, `DegreeofCombinedLeverage`, `DegreeofFinancialLeverage`, `DegreeofOperationalLeverage`, `FreeCashFlow`, `DebttoEquity`, `AdjustedEPSBasic`, `AdjustedEPSDiluted`, `FreeCashFlowReturnonAssets`, `ReturnonAssets`, `ReturnonEquity`, `ReturnonInvestedCapital`, `RevenueperEmployee`, `CashRatio`, `CurrentRatio`, `FreeCashFlowMargin`, `LongTermCapital`, `LongTermDebttoLongTermCapital`, `LongTermDebttoTotalCapital`, `NetDebt`, `NetIncomeperEmployee`, `NetWorkingCapital`, `PretaxMargin`, `QuickRatio`, `TaxRate`, `TotalCapital`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	        	
                $params = array();
                $params[] = $report_id;
                $params[] = ($rawdata["BookEquity"][$i] =='null' ? null:$rawdata["BookEquity"][$i]);
                $params[] = ($rawdata["DebttoAssets"][$i] =='null' ? null:$rawdata["DebttoAssets"][$i]);
                $params[] = ($rawdata["DegreeofCombinedLeverage"][$i] =='null' ? null:$rawdata["DegreeofCombinedLeverage"][$i]);
                $params[] = ($rawdata["DegreeofFinancialLeverage"][$i] =='null' ? null:$rawdata["DegreeofFinancialLeverage"][$i]);
                $params[] = ($rawdata["DegreeofOperationalLeverage"][$i] =='null' ? null:$rawdata["DegreeofOperationalLeverage"][$i]);
                $params[] = ($rawdata["FreeCashFlow"][$i] =='null' ? null:$rawdata["FreeCashFlow"][$i]);
                $params[] = ($rawdata["DebttoEquity"][$i] =='null' ? null:$rawdata["DebttoEquity"][$i]);
                $params[] = ($rawdata["AdjustedEPSBasic"][$i] =='null' ? null:$rawdata["AdjustedEPSBasic"][$i]);
                $params[] = ($rawdata["AdjustedEPSDiluted"][$i] =='null' ? null:$rawdata["AdjustedEPSDiluted"][$i]);
                $params[] = ($rawdata["FreeCashFlowReturnonAssets"][$i] =='null' ? null:$rawdata["FreeCashFlowReturnonAssets"][$i]);
                $params[] = ($rawdata["ReturnonAssets"][$i] =='null' ? null:$rawdata["ReturnonAssets"][$i]);
                $params[] = ($rawdata["ReturnonEquity"][$i] =='null' ? null:$rawdata["ReturnonEquity"][$i]);
                $params[] = ($rawdata["ReturnonInvestedCapital"][$i] =='null' ? null:$rawdata["ReturnonInvestedCapital"][$i]);
                $params[] = ($rawdata["RevenueperEmployee"][$i] =='null' ? null:$rawdata["RevenueperEmployee"][$i]);
                $params[] = ($rawdata["CashRatio"][$i] =='null' ? null:$rawdata["CashRatio"][$i]);
                $params[] = ($rawdata["CurrentRatio"][$i] =='null' ? null:$rawdata["CurrentRatio"][$i]);
                $params[] = ($rawdata["FreeCashFlowMargin"][$i] =='null' ? null:$rawdata["FreeCashFlowMargin"][$i]);
                $params[] = ($rawdata["LongTermCapital"][$i] =='null' ? null:$rawdata["LongTermCapital"][$i]);
                $params[] = ($rawdata["LongTermDebttoLongTermCapital"][$i] =='null' ? null:$rawdata["LongTermDebttoLongTermCapital"][$i]);
                $params[] = ($rawdata["LongTermDebttoTotalCapital"][$i] =='null' ? null:$rawdata["LongTermDebttoTotalCapital"][$i]);
                $params[] = ($rawdata["NetDebt"][$i] =='null' ? null:$rawdata["NetDebt"][$i]);
                $params[] = ($rawdata["NetIncomeperEmployee"][$i] =='null' ? null:$rawdata["NetIncomeperEmployee"][$i]);
                $params[] = ($rawdata["NetWorkingCapital"][$i] =='null' ? null:$rawdata["NetWorkingCapital"][$i]);
                $params[] = ($rawdata["PretaxMargin"][$i] =='null' ? null:$rawdata["PretaxMargin"][$i]);
                $params[] = ($rawdata["QuickRatio"][$i] =='null' ? null:$rawdata["QuickRatio"][$i]);
                $params[] = ($rawdata["TaxRate"][$i] =='null' ? null:$rawdata["TaxRate"][$i]);
                $params[] = ($rawdata["TotalCapital"][$i] =='null' ? null:$rawdata["TotalCapital"][$i]);
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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
                $query = "INSERT INTO `reports_financialscustom` (`report_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `SalesPercChange`, `Sales5YYCGrPerc`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?)";        
                $params = array();
                $params[] = $report_id;
                $params[] = (($rawdata["CostofRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["CostofRevenue"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["GrossProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["GrossProfit"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null' ||  $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["ResearchDevelopmentExpense"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["ResearchDevelopmentExpense"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["CFDepreciationAmortization"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["CFDepreciationAmortization"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["EBITDA"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["EBITDA"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["OperatingProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["OperatingProfit"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["EBIT"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["EBIT"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = $rawdata["TaxRatePercent"][$i] = (($rawdata["IncomeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]==0)?null:($rawdata["IncomeTaxes"][$i]/$rawdata["IncomeBeforeTaxes"][$i]));
                $params[] = (($rawdata["IncomeBeforeTaxes"][$i]=='null' && $rawdata["IncomeTaxes"][$i]=='null')?null:($rawdata["IncomeBeforeTaxes"][$i]-$rawdata["IncomeTaxes"][$i]));
                $params[] = (($rawdata["NetIncome"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?null:($rawdata["NetIncome"][$i]/$rawdata["TotalRevenue"][$i]));
                $params[] = (($rawdata["DividendsPaid"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]==0)?null:(-($rawdata["DividendsPaid"][$i])/(toFloat($rawdata["SharesOutstandingBasic"][$i])*1000000)));
                $params[] = (($rawdata["CurrentPortionofLongtermDebt"][$i]=='null' && $rawdata["ShorttermBorrowings"][$i]=='null')?null:($rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["ShorttermBorrowings"][$i]));
                $params[] = (($rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["NotesPayable"][$i]=='null')?null:($rawdata["TotalLongtermDebt"][$i]+$rawdata["NotesPayable"][$i]));
                $params[] = (($rawdata["LongtermDebtProceeds"][$i]=='null' && $rawdata["LongtermDebtPayments"][$i] == 'null')?null:($rawdata["LongtermDebtProceeds"][$i]+$rawdata["LongtermDebtPayments"][$i]));
                $params[] = (($rawdata["CapitalExpenditures"][$i]=='null')?null:(-$rawdata["CapitalExpenditures"][$i]));
                $params[] = (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?null:($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i]));
                $params[] = (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?null:($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i])));
                if ($i <= $areports) {
                    if ($i == 1) {
                        $params[] = null;
                        $params[] = null;
                    } else {
                        $params[] = ((($rawdata["TotalRevenue"][$i]=='null' && $rawdata["TotalRevenue"][$i-1]=='null') || $rawdata["TotalRevenue"][$i-1]=='null' || $rawdata["TotalRevenue"][$i-1]==0)?null:(($rawdata["TotalRevenue"][$i]-$rawdata["TotalRevenue"][$i-1])/$rawdata["TotalRevenue"][$i-1]));
                        if ($i > 5) {
                            if ($rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i-5]=='null' || $rawdata["TotalRevenue"][$i-5]<=0 || $rawdata["TotalRevenue"][$i] < 0) {
                                $params[] = "null";
                            } else {
                                $params[] = (pow($rawdata["TotalRevenue"][$i]/$rawdata["TotalRevenue"][$i-5], 1/5) - 1); 
                            }
                        } else {
                            $params[] = null;
                        }
                    }
                } else {
                    $params[] = null;
                    $params[] = null;
                }
                try {
                    $res = $db->prepare($query);
                    $res->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
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


                //Populate Key Ratios and valuation only for annual reports
                if($i <= $areports) {
                    $CapEx = (($rawdata["CapitalExpenditures"][$i]=='null')?null:(-$rawdata["CapitalExpenditures"][$i]));
                    $FreeCashFlow = (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?null:($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i]));
                    $OwnerEarningsFCF = (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?null:($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i])));
                    if($i == 1) {
                        $arpy = $inpy = 0;
                    } else {
                        $arpy = $rawdata["TotalReceivablesNet"][$i-1]=='null'?null:$rawdata["TotalReceivablesNet"][$i-1];
                        $inpy = $rawdata["InventoriesNet"][$i-1]=='null'?null:$rawdata["InventoriesNet"][$i-1];
                    }
                    $entValue = (($rawdata["SharesOutstandingDiluted"][$i]=='null' && is_null($price) && $rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["TotalShorttermDebt"][$i]=='null' && $rawdata["PreferredStock"][$i]=='null' && $rawdata["MinorityInterestEquityEarnings"][$i]=='null' && $rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null')?null:((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price)+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalShorttermDebt"][$i]+$rawdata["PreferredStock"][$i]+$rawdata["MinorityInterestEquityEarnings"][$i]-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]));
                    $query = "INSERT INTO `reports_key_ratios` (`report_id`, `ReportYear`, `ReportDate`, `ReportDateAdjusted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?)"; //57par				
                    $params = array();
                    $params[] = $report_id;
                    $params[] = $rawdata["fiscalYear"][$i];
                    $params[] = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]));
                    $params[] = ($rdate == '0000-00-00'?null:$rdate);
                    $params[] = $price;
                    $params[] = ((($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null' && is_null($CapEx)) || $rawdata["TaxRatePercent"][$i]=='null')?null:(($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx)*(1-$rawdata["TaxRatePercent"][$i])));
                    $params[] = (($rawdata["SharesOutstandingDiluted"][$i]=='null'||is_null($price))? null:(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price));
                    $params[] = $entValue;
                    $params[] = $rawdata["GoodwillIntangibleAssetsNet"][$i];
                    $params[] = (($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')?null:($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i]));
                    $params[] = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null' ||($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null'&&$rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["LongtermInvestments"][$i]=='null'))?null:(($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] + $rawdata["LongtermInvestments"][$i]) - max(0, ($rawdata["TotalCurrentLiabilities"][$i]-$rawdata["TotalCurrentAssets"][$i]+$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]))));
                    $params[] = (($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')?null:($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]));
                    $params[] = (($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')?null:($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i]));
                    $params[] = ((is_null($price)||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?null:($price / toFloat($rawdata["EPSDiluted"][$i])));
                    $params[] = (($rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?null:((((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price)-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/toFloat($rawdata["EPSDiluted"][$i])));
                    $params[] = ((is_null($entValue)||$rawdata["EBITDA"][$i]=='null'||$rawdata["EBITDA"][$i]==0)?null:($entValue / $rawdata["EBITDA"][$i]));
                    $params[] = ((is_null($entValue)||$rawdata["EBIT"][$i]=='null'||$rawdata["EBIT"][$i]==0)?null:($entValue / $rawdata["EBIT"][$i]));
                    $params[] = ((is_null($price)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?null:($price / ($rawdata["TotalRevenue"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
                    $params[] = ((is_null($price)||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
                    $params[] = ((is_null($price)||($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i]==0))?null:($price / (($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
                    $params[] = ((is_null($price)||($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null'&&is_null($CapEx))||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx==0)||$rawdata["TaxRatePercent"][$i]==1)?null:($price / ((($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx)*(1-$rawdata["TaxRatePercent"][$i]))/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
                    $params[] = ((is_null($price)||is_null($FreeCashFlow)||$FreeCashFlow==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?null:($price / ($FreeCashFlow/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
                    $params[] = ((is_null($price)||is_null($OwnerEarningsFCF)||$OwnerEarningsFCF==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?null:($price / ($OwnerEarningsFCF/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
                    $params[] = ((is_null($FreeCashFlow)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?null:($FreeCashFlow / $rawdata["TotalRevenue"][$i]));
                    $params[] = ((is_null($price)||$price==0||is_null($FreeCashFlow)||$FreeCashFlow==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?null:(1 / ($price / ($FreeCashFlow/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))));
                    $params[] = (($rawdata["EBIT"][$i]=='null'||is_null($entValue)||$entValue==0)?null:($rawdata["EBIT"][$i] / $entValue));
                    $params[] = (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?null:($rawdata["NetIncome"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
                    $params[] = (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?null:($rawdata["NetIncome"][$i] / $rawdata["TotalAssets"][$i])).",";
                    $params[] = (($rawdata["EBIT"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?null:(($rawdata["EBIT"][$i]*(1-$rawdata["TaxRatePercent"][$i])) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
                    $params[] = ((is_null($FreeCashFlow)||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?null:($FreeCashFlow / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
                    $params[] = (($rawdata["GrossProfit"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?null:($rawdata["GrossProfit"][$i] / $rawdata["TotalAssets"][$i]));
                    $params[] = ((is_null($price)||$price==0||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?null:(1 / ($price / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))));
                    $params[] = ((($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["InventoriesNet"][$i]=='null')||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?null:(($rawdata["TotalCurrentAssets"][$i] - $rawdata["InventoriesNet"][$i]) / $rawdata["TotalCurrentLiabilities"][$i]));
                    $params[] = (($rawdata["TotalCurrentAssets"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?null:($rawdata["TotalCurrentAssets"][$i] / $rawdata["TotalCurrentLiabilities"][$i]));
                    $params[] = ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?null:(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i]));
                    $params[] = ((($rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?null:(($rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i]));
                    $params[] = (($rawdata["TotalShorttermDebt"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?null:($rawdata["TotalShorttermDebt"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
                    $params[] = (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?null:($rawdata["TotalRevenue"][$i] / $rawdata["TotalAssets"][$i]));
                    $params[] = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?null:($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] / $rawdata["TotalRevenue"][$i]));
                    $params[] = (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?null:($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i]));
                    $params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"][$i] / $rawdata["TotalRevenue"][$i]));
                    $params[] = (($rawdata["ResearchDevelopmentExpense"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?null:($rawdata["ResearchDevelopmentExpense"][$i] / $rawdata["TotalRevenue"][$i]));
                    $params[] = (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?null:($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365));
                    $params[] = (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?null:($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365));
                    $params[] = (($rawdata["AccountsPayable"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?null:($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365));
                    $params[] = (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?null:(($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365)+($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365)-($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365)));
                    if($i==1) {
                        $params[] = (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalReceivablesNet"][$i]==0)?null:($rawdata["TotalRevenue"][$i] / ($rawdata["TotalReceivablesNet"][$i])));
                        $params[] = (($rawdata["CostofRevenue"][$i]=='null'||$rawdata["InventoriesNet"][$i]=='null'||$rawdata["InventoriesNet"][$i]==0)?null:($rawdata["CostofRevenue"][$i] / ($rawdata["InventoriesNet"][$i])));
                        $params[] = (($rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0||$rawdata["InventoriesNet"][$i]=='null'||$rawdata["InventoriesNet"][$i]==0)?null:(365 / ($rawdata["CostofRevenue"][$i] / ($rawdata["InventoriesNet"][$i]))));
                    } else {
                        $params[] = (($rawdata["TotalRevenue"][$i]=='null'||($rawdata["TotalReceivablesNet"][$i]=='null'&&is_null($arpy))||($rawdata["TotalReceivablesNet"][$i]+$arpy==0))?null:($rawdata["TotalRevenue"][$i] / (($arpy + $rawdata["TotalReceivablesNet"][$i])/2)));
                        $params[] = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy))||($rawdata["InventoriesNet"][$i]+$inpy==0))?null:($rawdata["CostofRevenue"][$i] / (($inpy + $rawdata["InventoriesNet"][$i])/2)));
                        $params[] = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy))||($rawdata["InventoriesNet"][$i]+$inpy==0)||$rawdata["CostofRevenue"][$i]==0)?null:(365 / ($rawdata["CostofRevenue"][$i] / (($inpy + $rawdata["InventoriesNet"][$i])/2))));
                    }
                    $params[] = (($rawdata["GoodwillIntangibleAssetsNet"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?null:($rawdata["GoodwillIntangibleAssetsNet"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
                    $params[] = (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?null:($rawdata["InventoriesNet"][$i] / $rawdata["TotalRevenue"][$i]));
                    $params[] = ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?null:(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
                    $params[] = (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?null:($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
                    $params[] = ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalLongtermDebt"][$i]=='null' &&$rawdata["TotalShorttermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?null:(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i])));
                    $params[] = (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?null:($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i])));
                    $params[] = ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?null:(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalAssets"][$i]));
                    $params[] = (($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||is_null($price)||$price==0)?null:((($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i]) / (toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/$price);                                
                    try {
                        $res = $db->prepare($query);
                        $res->execute($params);
                    } catch(PDOException $ex) {
                        echo "\nDatabase Error"; //user message
                        die("Line: ".__LINE__." - ".$ex->getMessage());
                    }
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

                    //reports_valuation
                    $query = "INSERT INTO `reports_valuation` (`report_id`, `nnwc`, `p_nnwc`, `mos_nnwc`, `ncav`, `p_ncav`, `mos_ncav`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $params = array();
                    if(isset($rawdata["CashCashEquivalentsandShorttermInvestments"])) {
                        $cce = $rawdata["CashCashEquivalentsandShorttermInvestments"][$i];
                    } else {
                        $cce = 0;
                    }
                    if(isset($rawdata["TotalReceivablesNet"])) {
                        $trn = $rawdata["TotalReceivablesNet"][$i];
                    } else {
                        $trn = 0;
                    }
                    if(isset($rawdata["TotalInventories"])) {
                        $tin = $rawdata["TotalInventories"][$i];
                    } else {
                        $tin = 0;
                    }
                    if(isset($rawdata["TotalLiabilities"])) {
                        $tli = $rawdata["TotalLiabilities"][$i];
                    } else {
                        $tli = 0;
                    }
                    if(isset($rawdata["TotalCurrentAssets"])) {
                        $tca = $rawdata["TotalCurrentAssets"][$i];
                    } else {
                        $tca = 0;
                    }
                    if(isset($rawdata["SharesOutstandingDiluted"])) {
                        $sod = $rawdata["SharesOutstandingDiluted"][$i];
                    } else {
                        $sod = 'null';
                    }

                    $nnwc = $cce + $trn * 0.75 + $tin * 0.5 * 1000000 - $tli;
                    $ncav = $tca - $tli;
                    $p_nnwc = (($sod=='null'||is_null($price)||$nnwc==0)? null:(toFloat($sod)*1000000*$price/$nnwc));
                    $p_nvac = (($sod=='null'||is_null($price)||$ncav==0)? null:(toFloat($sod)*1000000*$price/$ncav));
                    $params[] = $report_id;
                    $params[] = $nnwc;
                    $params[] = $p_nnwc;
                    $params[] = ((is_null($p_nnwc) || (1-$p_nnwc)*100 < 0 || $nnwc < 0) ? 0:((1-$p_nnwc)*100));
                    $params[] = $ncav;
                    $params[] = $p_nvac;
                    $params[] = ((is_null($p_nvac) || (1-$p_nvac)*100 < 0 || $ncav < 0) ? 0:((1-$p_nvac)*100));
                    try {
                        $res = $db->prepare($query);
                        $res->execute($params);
                    } catch(PDOException $ex) {
                        echo "\nDatabase Error"; //user message
                        die("Line: ".__LINE__." - ".$ex->getMessage());
                    }

                    //reports_valuation CAGR
                    if ($i > 3) {
                        updateCAGR_V("reports_valuation_3cagr", 3, $i, $report_id, $rawdata, $dates->ticker_id);
                        if ($i > 5) {
                            updateCAGR_V("reports_valuation_5cagr", 5, $i, $report_id, $rawdata, $dates->ticker_id);
                        }
                        if ($i > 7) {
                            updateCAGR_V("reports_valuation_7cagr", 7, $i, $report_id, $rawdata, $dates->ticker_id);
                        }
                        if ($i > 10) {
                            updateCAGR_V("reports_valuation_10cagr", 10, $i, $report_id, $rawdata, $dates->ticker_id);
                        }
                    }

                }
            }
        }
    }

    //Update TTM and PTTM data
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
    $query = "INSERT INTO `ttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?) ON DUPLICATE KEY UPDATE `CommitmentsContingencies`=?, `CommonStock`=?, `DeferredCharges`=?, `DeferredIncomeTaxesCurrent`=?, `DeferredIncomeTaxesLongterm`=?, `AccountsPayableandAccruedExpenses`=?, `AccruedInterest`=?, `AdditionalPaidinCapital`=?, `AdditionalPaidinCapitalPreferredStock`=?, `CashandCashEquivalents`=?, `CashCashEquivalentsandShorttermInvestments`=?, `Goodwill`=?, `IntangibleAssets`=?, `InventoriesNet`=?, `LongtermDeferredIncomeTaxLiabilities`=?, `LongtermDeferredLiabilityCharges`=?, `LongtermInvestments`=?, `MinorityInterest`=?, `OtherAccumulatedComprehensiveIncome`=?, `OtherAssets`=?, `OtherCurrentAssets`=?, `OtherCurrentLiabilities`=?, `OtherEquity`=?, `OtherInvestments`=?, `OtherLiabilities`=?, `PartnersCapital`=?, `PensionPostretirementObligation`=?, `PreferredStock`=?, `PrepaidExpenses`=?, `PropertyPlantEquipmentNet`=?, `RestrictedCash`=?, `RetainedEarnings`=?, `TemporaryEquity`=?, `TotalAssets`=?, `TotalCurrentAssets`=?, `TotalCurrentLiabilities`=?, `TotalLiabilities`=?, `TotalLongtermDebt`=?, `TotalReceivablesNet`=?, `TotalShorttermDebt`=?, `TotalStockholdersEquity`=?, `TreasuryStock`=?";
    $params = array();
    $params[] = ($rawdata["CommitmentsContingencies"][$MRQRow] =='null' ? null:$rawdata["CommitmentsContingencies"][$MRQRow]);
    $params[] = ($rawdata["CommonStock"][$MRQRow] =='null' ? null:$rawdata["CommonStock"][$MRQRow]);
    $params[] = ($rawdata["DeferredCharges"][$MRQRow] =='null' ? null:$rawdata["DeferredCharges"][$MRQRow]);
    $params[] = ($rawdata["DeferredIncomeTaxesCurrent"][$MRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxesCurrent"][$MRQRow]);
    $params[] = ($rawdata["DeferredIncomeTaxesLongterm"][$MRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxesLongterm"][$MRQRow]);
    $params[] = ($rawdata["AccountsPayableandAccruedExpenses"][$MRQRow] =='null' ? null:$rawdata["AccountsPayableandAccruedExpenses"][$MRQRow]);
    $params[] = ($rawdata["AccruedInterest"][$MRQRow] =='null' ? null:$rawdata["AccruedInterest"][$MRQRow]);
    $params[] = ($rawdata["AdditionalPaidinCapital"][$MRQRow] =='null' ? null:$rawdata["AdditionalPaidinCapital"][$MRQRow]);
    $params[] = ($rawdata["AdditionalPaidinCapitalPreferredStock"][$MRQRow] =='null' ? null:$rawdata["AdditionalPaidinCapitalPreferredStock"][$MRQRow]);
    $params[] = ($rawdata["CashandCashEquivalents"][$MRQRow] =='null' ? null:$rawdata["CashandCashEquivalents"][$MRQRow]);
    $params[] = ($rawdata["CashCashEquivalentsandShorttermInvestments"][$MRQRow] =='null' ? null:$rawdata["CashCashEquivalentsandShorttermInvestments"][$MRQRow]);
    $params[] = ($rawdata["Goodwill"][$MRQRow] =='null' ? null:$rawdata["Goodwill"][$MRQRow]);
    $params[] = ($rawdata["IntangibleAssets"][$MRQRow] =='null' ? null:$rawdata["IntangibleAssets"][$MRQRow]);
    $params[] = ($rawdata["InventoriesNet"][$MRQRow] =='null' ? null:$rawdata["InventoriesNet"][$MRQRow]);
    $params[] = ($rawdata["LongtermDeferredIncomeTaxLiabilities"][$MRQRow] =='null' ? null:$rawdata["LongtermDeferredIncomeTaxLiabilities"][$MRQRow]);
    $params[] = ($rawdata["LongtermDeferredLiabilityCharges"][$MRQRow] =='null' ? null:$rawdata["LongtermDeferredLiabilityCharges"][$MRQRow]);
    $params[] = ($rawdata["LongtermInvestments"][$MRQRow] =='null' ? null:$rawdata["LongtermInvestments"][$MRQRow]);
    $params[] = ($rawdata["MinorityInterest"][$MRQRow] =='null' ? null:$rawdata["MinorityInterest"][$MRQRow]);
    $params[] = ($rawdata["OtherAccumulatedComprehensiveIncome"][$MRQRow] =='null' ? null:$rawdata["OtherAccumulatedComprehensiveIncome"][$MRQRow]);
    $params[] = ($rawdata["OtherAssets"][$MRQRow] =='null' ? null:$rawdata["OtherAssets"][$MRQRow]);
    $params[] = ($rawdata["OtherCurrentAssets"][$MRQRow] =='null' ? null:$rawdata["OtherCurrentAssets"][$MRQRow]);
    $params[] = ($rawdata["OtherCurrentLiabilities"][$MRQRow] =='null' ? null:$rawdata["OtherCurrentLiabilities"][$MRQRow]);
    $params[] = ($rawdata["OtherEquity"][$MRQRow] =='null' ? null:$rawdata["OtherEquity"][$MRQRow]);
    $params[] = ($rawdata["OtherInvestments"][$MRQRow] =='null' ? null:$rawdata["OtherInvestments"][$MRQRow]);
    $params[] = ($rawdata["OtherLiabilities"][$MRQRow] =='null' ? null:$rawdata["OtherLiabilities"][$MRQRow]);
    $params[] = ($rawdata["PartnersCapital"][$MRQRow] =='null' ? null:$rawdata["PartnersCapital"][$MRQRow]);
    $params[] = ($rawdata["PensionPostretirementObligation"][$MRQRow] =='null' ? null:$rawdata["PensionPostretirementObligation"][$MRQRow]);
    $params[] = ($rawdata["PreferredStock"][$MRQRow] =='null' ? null:$rawdata["PreferredStock"][$MRQRow]);
    $params[] = ($rawdata["PrepaidExpenses"][$MRQRow] =='null' ? null:$rawdata["PrepaidExpenses"][$MRQRow]);
    $params[] = ($rawdata["PropertyPlantEquipmentNet"][$MRQRow] =='null' ? null:$rawdata["PropertyPlantEquipmentNet"][$MRQRow]);
    $params[] = ($rawdata["RestrictedCash"][$MRQRow] =='null' ? null:$rawdata["RestrictedCash"][$MRQRow]);
    $params[] = ($rawdata["RetainedEarnings"][$MRQRow] =='null' ? null:$rawdata["RetainedEarnings"][$MRQRow]);
    $params[] = ($rawdata["TemporaryEquity"][$MRQRow] =='null' ? null:$rawdata["TemporaryEquity"][$MRQRow]);
    $params[] = ($rawdata["TotalAssets"][$MRQRow] =='null' ? null:$rawdata["TotalAssets"][$MRQRow]);
    $params[] = ($rawdata["TotalCurrentAssets"][$MRQRow] =='null' ? null:$rawdata["TotalCurrentAssets"][$MRQRow]);
    $params[] = ($rawdata["TotalCurrentLiabilities"][$MRQRow] =='null' ? null:$rawdata["TotalCurrentLiabilities"][$MRQRow]);
    $params[] = ($rawdata["TotalLiabilities"][$MRQRow] =='null' ? null:$rawdata["TotalLiabilities"][$MRQRow]);
    $params[] = ($rawdata["TotalLongtermDebt"][$MRQRow] =='null' ? null:$rawdata["TotalLongtermDebt"][$MRQRow]);
    $params[] = ($rawdata["TotalReceivablesNet"][$MRQRow] =='null' ? null:$rawdata["TotalReceivablesNet"][$MRQRow]);
    $params[] = ($rawdata["TotalShorttermDebt"][$MRQRow] =='null' ? null:$rawdata["TotalShorttermDebt"][$MRQRow]);
    $params[] = ($rawdata["TotalStockholdersEquity"][$MRQRow] =='null' ? null:$rawdata["TotalStockholdersEquity"][$MRQRow]);
    $params[] = ($rawdata["TreasuryStock"][$MRQRow] =='null' ? null:$rawdata["TreasuryStock"][$MRQRow]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }

    $query = "INSERT INTO `pttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?) ON DUPLICATE KEY UPDATE `CommitmentsContingencies`=?, `CommonStock`=?, `DeferredCharges`=?, `DeferredIncomeTaxesCurrent`=?, `DeferredIncomeTaxesLongterm`=?, `AccountsPayableandAccruedExpenses`=?, `AccruedInterest`=?, `AdditionalPaidinCapital`=?, `AdditionalPaidinCapitalPreferredStock`=?, `CashandCashEquivalents`=?, `CashCashEquivalentsandShorttermInvestments`=?, `Goodwill`=?, `IntangibleAssets`=?, `InventoriesNet`=?, `LongtermDeferredIncomeTaxLiabilities`=?, `LongtermDeferredLiabilityCharges`=?, `LongtermInvestments`=?, `MinorityInterest`=?, `OtherAccumulatedComprehensiveIncome`=?, `OtherAssets`=?, `OtherCurrentAssets`=?, `OtherCurrentLiabilities`=?, `OtherEquity`=?, `OtherInvestments`=?, `OtherLiabilities`=?, `PartnersCapital`=?, `PensionPostretirementObligation`=?, `PreferredStock`=?, `PrepaidExpenses`=?, `PropertyPlantEquipmentNet`=?, `RestrictedCash`=?, `RetainedEarnings`=?, `TemporaryEquity`=?, `TotalAssets`=?, `TotalCurrentAssets`=?, `TotalCurrentLiabilities`=?, `TotalLiabilities`=?, `TotalLongtermDebt`=?, `TotalReceivablesNet`=?, `TotalShorttermDebt`=?, `TotalStockholdersEquity`=?, `TreasuryStock`=?";
    $params = array();
    $params[] = ($rawdata["CommitmentsContingencies"][$PMRQRow] =='null' ? null:$rawdata["CommitmentsContingencies"][$PMRQRow]);
    $params[] = ($rawdata["CommonStock"][$PMRQRow] =='null' ? null:$rawdata["CommonStock"][$PMRQRow]);
    $params[] = ($rawdata["DeferredCharges"][$PMRQRow] =='null' ? null:$rawdata["DeferredCharges"][$PMRQRow]);
    $params[] = ($rawdata["DeferredIncomeTaxesCurrent"][$PMRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxesCurrent"][$PMRQRow]);
    $params[] = ($rawdata["DeferredIncomeTaxesLongterm"][$PMRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxesLongterm"][$PMRQRow]);
    $params[] = ($rawdata["AccountsPayableandAccruedExpenses"][$PMRQRow] =='null' ? null:$rawdata["AccountsPayableandAccruedExpenses"][$PMRQRow]);
    $params[] = ($rawdata["AccruedInterest"][$PMRQRow] =='null' ? null:$rawdata["AccruedInterest"][$PMRQRow]);
    $params[] = ($rawdata["AdditionalPaidinCapital"][$PMRQRow] =='null' ? null:$rawdata["AdditionalPaidinCapital"][$PMRQRow]);
    $params[] = ($rawdata["AdditionalPaidinCapitalPreferredStock"][$PMRQRow] =='null' ? null:$rawdata["AdditionalPaidinCapitalPreferredStock"][$PMRQRow]);
    $params[] = ($rawdata["CashandCashEquivalents"][$PMRQRow] =='null' ? null:$rawdata["CashandCashEquivalents"][$PMRQRow]);
    $params[] = ($rawdata["CashCashEquivalentsandShorttermInvestments"][$PMRQRow] =='null' ? null:$rawdata["CashCashEquivalentsandShorttermInvestments"][$PMRQRow]);
    $params[] = ($rawdata["Goodwill"][$PMRQRow] =='null' ? null:$rawdata["Goodwill"][$PMRQRow]);
    $params[] = ($rawdata["IntangibleAssets"][$PMRQRow] =='null' ? null:$rawdata["IntangibleAssets"][$PMRQRow]);
    $params[] = ($rawdata["InventoriesNet"][$PMRQRow] =='null' ? null:$rawdata["InventoriesNet"][$PMRQRow]);
    $params[] = ($rawdata["LongtermDeferredIncomeTaxLiabilities"][$PMRQRow] =='null' ? null:$rawdata["LongtermDeferredIncomeTaxLiabilities"][$PMRQRow]);
    $params[] = ($rawdata["LongtermDeferredLiabilityCharges"][$PMRQRow] =='null' ? null:$rawdata["LongtermDeferredLiabilityCharges"][$PMRQRow]);
    $params[] = ($rawdata["LongtermInvestments"][$PMRQRow] =='null' ? null:$rawdata["LongtermInvestments"][$PMRQRow]);
    $params[] = ($rawdata["MinorityInterest"][$PMRQRow] =='null' ? null:$rawdata["MinorityInterest"][$PMRQRow]);
    $params[] = ($rawdata["OtherAccumulatedComprehensiveIncome"][$PMRQRow] =='null' ? null:$rawdata["OtherAccumulatedComprehensiveIncome"][$PMRQRow]);
    $params[] = ($rawdata["OtherAssets"][$PMRQRow] =='null' ? null:$rawdata["OtherAssets"][$PMRQRow]);
    $params[] = ($rawdata["OtherCurrentAssets"][$PMRQRow] =='null' ? null:$rawdata["OtherCurrentAssets"][$PMRQRow]);
    $params[] = ($rawdata["OtherCurrentLiabilities"][$PMRQRow] =='null' ? null:$rawdata["OtherCurrentLiabilities"][$PMRQRow]);
    $params[] = ($rawdata["OtherEquity"][$PMRQRow] =='null' ? null:$rawdata["OtherEquity"][$PMRQRow]);
    $params[] = ($rawdata["OtherInvestments"][$PMRQRow] =='null' ? null:$rawdata["OtherInvestments"][$PMRQRow]);
    $params[] = ($rawdata["OtherLiabilities"][$PMRQRow] =='null' ? null:$rawdata["OtherLiabilities"][$PMRQRow]);
    $params[] = ($rawdata["PartnersCapital"][$PMRQRow] =='null' ? null:$rawdata["PartnersCapital"][$PMRQRow]);
    $params[] = ($rawdata["PensionPostretirementObligation"][$PMRQRow] =='null' ? null:$rawdata["PensionPostretirementObligation"][$PMRQRow]);
    $params[] = ($rawdata["PreferredStock"][$PMRQRow] =='null' ? null:$rawdata["PreferredStock"][$PMRQRow]);
    $params[] = ($rawdata["PrepaidExpenses"][$PMRQRow] =='null' ? null:$rawdata["PrepaidExpenses"][$PMRQRow]);
    $params[] = ($rawdata["PropertyPlantEquipmentNet"][$PMRQRow] =='null' ? null:$rawdata["PropertyPlantEquipmentNet"][$PMRQRow]);
    $params[] = ($rawdata["RestrictedCash"][$PMRQRow] =='null' ? null:$rawdata["RestrictedCash"][$PMRQRow]);
    $params[] = ($rawdata["RetainedEarnings"][$PMRQRow] =='null' ? null:$rawdata["RetainedEarnings"][$PMRQRow]);
    $params[] = ($rawdata["TemporaryEquity"][$PMRQRow] =='null' ? null:$rawdata["TemporaryEquity"][$PMRQRow]);
    $params[] = ($rawdata["TotalAssets"][$PMRQRow] =='null' ? null:$rawdata["TotalAssets"][$PMRQRow]);
    $params[] = ($rawdata["TotalCurrentAssets"][$PMRQRow] =='null' ? null:$rawdata["TotalCurrentAssets"][$PMRQRow]);
    $params[] = ($rawdata["TotalCurrentLiabilities"][$PMRQRow] =='null' ? null:$rawdata["TotalCurrentLiabilities"][$PMRQRow]);
    $params[] = ($rawdata["TotalLiabilities"][$PMRQRow] =='null' ? null:$rawdata["TotalLiabilities"][$PMRQRow]);
    $params[] = ($rawdata["TotalLongtermDebt"][$PMRQRow] =='null' ? null:$rawdata["TotalLongtermDebt"][$PMRQRow]);
    $params[] = ($rawdata["TotalReceivablesNet"][$PMRQRow] =='null' ? null:$rawdata["TotalReceivablesNet"][$PMRQRow]);
    $params[] = ($rawdata["TotalShorttermDebt"][$PMRQRow] =='null' ? null:$rawdata["TotalShorttermDebt"][$PMRQRow]);
    $params[] = ($rawdata["TotalStockholdersEquity"][$PMRQRow] =='null' ? null:$rawdata["TotalStockholdersEquity"][$PMRQRow]);
    $params[] = ($rawdata["TreasuryStock"][$PMRQRow] =='null' ? null:$rawdata["TreasuryStock"][$PMRQRow]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }

    $query = "INSERT INTO `ttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?) ON DUPLICATE KEY UPDATE `TotalDebt`=?, `TotalAssetsFQ`=?, `TotalAssetsFY`=?, `CurrentPortionofLongtermDebt`=?, `DeferredIncomeTaxLiabilitiesShortterm`=?, `DeferredLiabilityCharges`=?, `AccountsNotesReceivableNet`=?, `AccountsPayable`=?, `AccountsReceivableTradeNet`=?, `AccruedExpenses`=?, `AccumulatedDepreciation`=?, `AmountsDuetoRelatedPartiesShortterm`=?, `GoodwillIntangibleAssetsNet`=?, `IncomeTaxesPayable`=?, `LiabilitiesStockholdersEquity`=?, `LongtermDebt`=?, `NotesPayable`=?, `OperatingLeases`=?, `OtherAccountsNotesReceivable`=?, `OtherAccountsPayableandAccruedExpenses`=?, `OtherBorrowings`=?, `OtherReceivables`=?, `PropertyandEquipmentGross`=?, `TotalLongtermAssets`=?, `TotalLongtermLiabilities`=?, `TotalSharesOutstanding`=?, `ShorttermInvestments`=?";
    $params = array();
    $params[] = ($rawdata["TotalDebt"][$MRQRow] =='null' ? null:$rawdata["TotalDebt"][$MRQRow]);
    $params[] = ($rawdata["TotalAssetsFQ"][$MRQRow] =='null' ? null:$rawdata["TotalAssetsFQ"][$MRQRow]);
    $params[] = ($rawdata["TotalAssetsFY"][$MRQRow] =='null' ? null:$rawdata["TotalAssetsFY"][$MRQRow]);
    $params[] = ($rawdata["CurrentPortionofLongtermDebt"][$MRQRow] =='null' ? null:$rawdata["CurrentPortionofLongtermDebt"][$MRQRow]);
    $params[] = ($rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$MRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$MRQRow]);
    $params[] = ($rawdata["DeferredLiabilityCharges"][$MRQRow] =='null' ? null:$rawdata["DeferredLiabilityCharges"][$MRQRow]);
    $params[] = ($rawdata["AccountsNotesReceivableNet"][$MRQRow] =='null' ? null:$rawdata["AccountsNotesReceivableNet"][$MRQRow]);
    $params[] = ($rawdata["AccountsPayable"][$MRQRow] =='null' ? null:$rawdata["AccountsPayable"][$MRQRow]);
    $params[] = ($rawdata["AccountsReceivableTradeNet"][$MRQRow] =='null' ? null:$rawdata["AccountsReceivableTradeNet"][$MRQRow]);
    $params[] = ($rawdata["AccruedExpenses"][$MRQRow] =='null' ? null:$rawdata["AccruedExpenses"][$MRQRow]);
    $params[] = ($rawdata["AccumulatedDepreciation"][$MRQRow] =='null' ? null:$rawdata["AccumulatedDepreciation"][$MRQRow]);
    $params[] = ($rawdata["AmountsDuetoRelatedPartiesShortterm"][$MRQRow] =='null' ? null:$rawdata["AmountsDuetoRelatedPartiesShortterm"][$MRQRow]);
    $params[] = ($rawdata["GoodwillIntangibleAssetsNet"][$MRQRow] =='null' ? null:$rawdata["GoodwillIntangibleAssetsNet"][$MRQRow]);
    $params[] = ($rawdata["IncomeTaxesPayable"][$MRQRow] =='null' ? null:$rawdata["IncomeTaxesPayable"][$MRQRow]);
    $params[] = ($rawdata["LiabilitiesStockholdersEquity"][$MRQRow] =='null' ? null:$rawdata["LiabilitiesStockholdersEquity"][$MRQRow]);
    $params[] = ($rawdata["LongtermDebt"][$MRQRow] =='null' ? null:$rawdata["LongtermDebt"][$MRQRow]);
    $params[] = ($rawdata["NotesPayable"][$MRQRow] =='null' ? null:$rawdata["NotesPayable"][$MRQRow]);
    $params[] = ($rawdata["OperatingLeases"][$MRQRow] =='null' ? null:$rawdata["OperatingLeases"][$MRQRow]);
    $params[] = ($rawdata["OtherAccountsNotesReceivable"][$MRQRow] =='null' ? null:$rawdata["OtherAccountsNotesReceivable"][$MRQRow]);
    $params[] = ($rawdata["OtherAccountsPayableandAccruedExpenses"][$MRQRow] =='null' ? null:$rawdata["OtherAccountsPayableandAccruedExpenses"][$MRQRow]);
    $params[] = ($rawdata["OtherBorrowings"][$MRQRow] =='null' ? null:$rawdata["OtherBorrowings"][$MRQRow]);
    $params[] = ($rawdata["OtherReceivables"][$MRQRow] =='null' ? null:$rawdata["OtherReceivables"][$MRQRow]);
    $params[] = ($rawdata["PropertyandEquipmentGross"][$MRQRow] =='null' ? null:$rawdata["PropertyandEquipmentGross"][$MRQRow]);
    $params[] = ($rawdata["TotalLongtermAssets"][$MRQRow] =='null' ? null:$rawdata["TotalLongtermAssets"][$MRQRow]);
    $params[] = ($rawdata["TotalLongtermLiabilities"][$MRQRow] =='null' ? null:$rawdata["TotalLongtermLiabilities"][$MRQRow]);
    $params[] = ($rawdata["TotalSharesOutstanding"][$MRQRow] =='null' ? null:$rawdata["TotalSharesOutstanding"][$MRQRow]);
    $params[] = ($rawdata["ShorttermInvestments"][$MRQRow] =='null' ? null:$rawdata["ShorttermInvestments"][$MRQRow]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }

    $query = "INSERT INTO `pttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?) ON DUPLICATE KEY UPDATE `TotalDebt`=?, `TotalAssetsFQ`=?, `TotalAssetsFY`=?, `CurrentPortionofLongtermDebt`=?, `DeferredIncomeTaxLiabilitiesShortterm`=?, `DeferredLiabilityCharges`=?, `AccountsNotesReceivableNet`=?, `AccountsPayable`=?, `AccountsReceivableTradeNet`=?, `AccruedExpenses`=?, `AccumulatedDepreciation`=?, `AmountsDuetoRelatedPartiesShortterm`=?, `GoodwillIntangibleAssetsNet`=?, `IncomeTaxesPayable`=?, `LiabilitiesStockholdersEquity`=?, `LongtermDebt`=?, `NotesPayable`=?, `OperatingLeases`=?, `OtherAccountsNotesReceivable`=?, `OtherAccountsPayableandAccruedExpenses`=?, `OtherBorrowings`=?, `OtherReceivables`=?, `PropertyandEquipmentGross`=?, `TotalLongtermAssets`=?, `TotalLongtermLiabilities`=?, `TotalSharesOutstanding`=?, `ShorttermInvestments`=?";
    $params = array();
    $params[] = ($rawdata["TotalDebt"][$PMRQRow] =='null' ? null:$rawdata["TotalDebt"][$PMRQRow]);
    $params[] = ($rawdata["TotalAssetsFQ"][$PMRQRow] =='null' ? null:$rawdata["TotalAssetsFQ"][$PMRQRow]);
    $params[] = ($rawdata["TotalAssetsFY"][$PMRQRow] =='null' ? null:$rawdata["TotalAssetsFY"][$PMRQRow]);
    $params[] = ($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow] =='null' ? null:$rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]);
    $params[] = ($rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$PMRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$PMRQRow]);
    $params[] = ($rawdata["DeferredLiabilityCharges"][$PMRQRow] =='null' ? null:$rawdata["DeferredLiabilityCharges"][$PMRQRow]);
    $params[] = ($rawdata["AccountsNotesReceivableNet"][$PMRQRow] =='null' ? null:$rawdata["AccountsNotesReceivableNet"][$PMRQRow]);
    $params[] = ($rawdata["AccountsPayable"][$PMRQRow] =='null' ? null:$rawdata["AccountsPayable"][$PMRQRow]);
    $params[] = ($rawdata["AccountsReceivableTradeNet"][$PMRQRow] =='null' ? null:$rawdata["AccountsReceivableTradeNet"][$PMRQRow]);
    $params[] = ($rawdata["AccruedExpenses"][$PMRQRow] =='null' ? null:$rawdata["AccruedExpenses"][$PMRQRow]);
    $params[] = ($rawdata["AccumulatedDepreciation"][$PMRQRow] =='null' ? null:$rawdata["AccumulatedDepreciation"][$PMRQRow]);
    $params[] = ($rawdata["AmountsDuetoRelatedPartiesShortterm"][$PMRQRow] =='null' ? null:$rawdata["AmountsDuetoRelatedPartiesShortterm"][$PMRQRow]);
    $params[] = ($rawdata["GoodwillIntangibleAssetsNet"][$PMRQRow] =='null' ? null:$rawdata["GoodwillIntangibleAssetsNet"][$PMRQRow]);
    $params[] = ($rawdata["IncomeTaxesPayable"][$PMRQRow] =='null' ? null:$rawdata["IncomeTaxesPayable"][$PMRQRow]);
    $params[] = ($rawdata["LiabilitiesStockholdersEquity"][$PMRQRow] =='null' ? null:$rawdata["LiabilitiesStockholdersEquity"][$PMRQRow]);
    $params[] = ($rawdata["LongtermDebt"][$PMRQRow] =='null' ? null:$rawdata["LongtermDebt"][$PMRQRow]);
    $params[] = ($rawdata["NotesPayable"][$PMRQRow] =='null' ? null:$rawdata["NotesPayable"][$PMRQRow]);
    $params[] = ($rawdata["OperatingLeases"][$PMRQRow] =='null' ? null:$rawdata["OperatingLeases"][$PMRQRow]);
    $params[] = ($rawdata["OtherAccountsNotesReceivable"][$PMRQRow] =='null' ? null:$rawdata["OtherAccountsNotesReceivable"][$PMRQRow]);
    $params[] = ($rawdata["OtherAccountsPayableandAccruedExpenses"][$PMRQRow] =='null' ? null:$rawdata["OtherAccountsPayableandAccruedExpenses"][$PMRQRow]);
    $params[] = ($rawdata["OtherBorrowings"][$PMRQRow] =='null' ? null:$rawdata["OtherBorrowings"][$PMRQRow]);
    $params[] = ($rawdata["OtherReceivables"][$PMRQRow] =='null' ? null:$rawdata["OtherReceivables"][$PMRQRow]);
    $params[] = ($rawdata["PropertyandEquipmentGross"][$PMRQRow] =='null' ? null:$rawdata["PropertyandEquipmentGross"][$PMRQRow]);
    $params[] = ($rawdata["TotalLongtermAssets"][$PMRQRow] =='null' ? null:$rawdata["TotalLongtermAssets"][$PMRQRow]);
    $params[] = ($rawdata["TotalLongtermLiabilities"][$PMRQRow] =='null' ? null:$rawdata["TotalLongtermLiabilities"][$PMRQRow]);
    $params[] = ($rawdata["TotalSharesOutstanding"][$PMRQRow] =='null' ? null:$rawdata["TotalSharesOutstanding"][$PMRQRow]);
    $params[] = ($rawdata["ShorttermInvestments"][$PMRQRow] =='null' ? null:$rawdata["ShorttermInvestments"][$PMRQRow]);
    $params = array_merge($params,$params);
    array_unshift($params,$dates->ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }

    //Cashflow and Financial
    if($stock_type == "ADR") {
        $query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?) ON DUPLICATE KEY UPDATE `InterestIncome`=?, `InterestExpense`=?, `EPSBasic`=?, `EPSDiluted`=?, `SharesOutstandingDiluted`=?, `InventoriesRawMaterialsComponents`=?, `InventoriesWorkInProcess`=?, `InventoriesInventoriesAdjustments`=?, `InventoriesFinishedGoods`=?, `InventoriesOther`=?, `TotalInventories`=?, `LandAndImprovements`=?, `BuildingsAndImprovements`=?, `MachineryFurnitureEquipment`=?, `ConstructionInProgress`=?, `GrossPropertyPlantandEquipment`=?, `SharesOutstandingBasic`=?"; 
        $params = array();
        $params[] = ((!isset($rawdata["InterestIncome"]) || $rawdata["InterestIncome"][$MRQRow] =='null') ? null:toFloat($rawdata["InterestIncome"][$MRQRow]));
        $params[] = ((!isset($rawdata["InterestExpense"]) || $rawdata["InterestExpense"][$MRQRow] =='null') ? null:toFloat($rawdata["InterestExpense"][$MRQRow]));
        $params[] = ((!isset($rawdata["EPSBasic"]) || $rawdata["EPSBasic"][$MRQRow] =='null') ? null:toFloat($rawdata["EPSBasic"][$MRQRow]));
        $params[] = ((!isset($rawdata["EPSDiluted"]) || $rawdata["EPSDiluted"][$MRQRow] =='null') ? null:toFloat($rawdata["EPSDiluted"][$MRQRow]));
        $params[] = ((!isset($rawdata["SharesOutstandingDiluted"]) || $rawdata["SharesOutstandingDiluted"][$MRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesRawMaterialsComponents"]) || $rawdata["InventoriesRawMaterialsComponents"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesWorkInProcess"]) || $rawdata["InventoriesWorkInProcess"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesInventoriesAdjustments"]) || $rawdata["InventoriesInventoriesAdjustments"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesFinishedGoods"]) || $rawdata["InventoriesFinishedGoods"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesOther"]) || $rawdata["InventoriesOther"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesOther"][$MRQRow]));
        $params[] = ((!isset($rawdata["TotalInventories"]) || $rawdata["TotalInventories"][$MRQRow] =='null') ? null:toFloat($rawdata["TotalInventories"][$MRQRow]));
        $params[] = ((!isset($rawdata["LandAndImprovements"]) || $rawdata["LandAndImprovements"][$MRQRow] =='null') ? null:toFloat($rawdata["LandAndImprovements"][$MRQRow]));
        $params[] = ((!isset($rawdata["BuildingsAndImprovements"]) || $rawdata["BuildingsAndImprovements"][$MRQRow] =='null') ? null:toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]));
        $params[] = ((!isset($rawdata["MachineryFurnitureEquipment"]) || $rawdata["MachineryFurnitureEquipment"][$MRQRow] =='null') ? null:toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]));
        $params[] = ((!isset($rawdata["ConstructionInProgress"]) || $rawdata["ConstructionInProgress"][$MRQRow] =='null') ? null:toFloat($rawdata["ConstructionInProgress"][$MRQRow]));
        $params[] = ((!isset($rawdata["GrossPropertyPlantandEquipment"]) || $rawdata["GrossPropertyPlantandEquipment"][$MRQRow] =='null') ? null:toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]));
        $params[] = ((!isset($rawdata["SharesOutstandingBasic"]) || $rawdata["SharesOutstandingBasic"][$MRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?) ON DUPLICATE KEY UPDATE `InterestIncome`=?, `InterestExpense`=?, `EPSBasic`=?, `EPSDiluted`=?, `SharesOutstandingDiluted`=?, `InventoriesRawMaterialsComponents`=?, `InventoriesWorkInProcess`=?, `InventoriesInventoriesAdjustments`=?, `InventoriesFinishedGoods`=?, `InventoriesOther`=?, `TotalInventories`=?, `LandAndImprovements`=?, `BuildingsAndImprovements`=?, `MachineryFurnitureEquipment`=?, `ConstructionInProgress`=?, `GrossPropertyPlantandEquipment`=?, `SharesOutstandingBasic`=?";
        $params = array();
        $params[] = ((!isset($rawdata["InterestIncome"]) || $rawdata["InterestIncome"][$PMRQRow] =='null') ? null:toFloat($rawdata["InterestIncome"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InterestExpense"]) || $rawdata["InterestExpense"][$PMRQRow] =='null') ? null:toFloat($rawdata["InterestExpense"][$PMRQRow]));
        $params[] = ((!isset($rawdata["EPSBasic"]) || $rawdata["EPSBasic"][$PMRQRow] =='null') ? null:toFloat($rawdata["EPSBasic"][$PMRQRow]));
        $params[] = ((!isset($rawdata["EPSDiluted"]) || $rawdata["EPSDiluted"][$PMRQRow] =='null') ? null:toFloat($rawdata["EPSDiluted"][$PMRQRow]));
        $params[] = ((!isset($rawdata["SharesOutstandingDiluted"]) || $rawdata["SharesOutstandingDiluted"][$PMRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesRawMaterialsComponents"]) || $rawdata["InventoriesRawMaterialsComponents"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesWorkInProcess"]) || $rawdata["InventoriesWorkInProcess"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesInventoriesAdjustments"]) || $rawdata["InventoriesInventoriesAdjustments"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesFinishedGoods"]) || $rawdata["InventoriesFinishedGoods"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesOther"]) || $rawdata["InventoriesOther"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesOther"][$PMRQRow]));
        $params[] = ((!isset($rawdata["TotalInventories"]) || $rawdata["TotalInventories"][$PMRQRow] =='null') ? null:toFloat($rawdata["TotalInventories"][$PMRQRow]));
        $params[] = ((!isset($rawdata["LandAndImprovements"]) || $rawdata["LandAndImprovements"][$PMRQRow] =='null') ? null:toFloat($rawdata["LandAndImprovements"][$PMRQRow]));
        $params[] = ((!isset($rawdata["BuildingsAndImprovements"]) || $rawdata["BuildingsAndImprovements"][$PMRQRow] =='null') ? null:toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]));
        $params[] = ((!isset($rawdata["MachineryFurnitureEquipment"]) || $rawdata["MachineryFurnitureEquipment"][$PMRQRow] =='null') ? null:toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]));
        $params[] = ((!isset($rawdata["ConstructionInProgress"]) || $rawdata["ConstructionInProgress"][$PMRQRow] =='null') ? null:toFloat($rawdata["ConstructionInProgress"][$PMRQRow]));
        $params[] = ((!isset($rawdata["GrossPropertyPlantandEquipment"]) || $rawdata["GrossPropertyPlantandEquipment"][$PMRQRow] =='null') ? null:toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]));
        $params[] = ((!isset($rawdata["SharesOutstandingBasic"]) || $rawdata["SharesOutstandingBasic"][$PMRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinCurrentAssets`=?, `ChangeinCurrentLiabilities`=?, `ChangeinDebtNet`=?, `ChangeinDeferredRevenue`=?, `ChangeinEquityNet`=?, `ChangeinIncomeTaxesPayable`=?, `ChangeinInventories`=?, `ChangeinOperatingAssetsLiabilities`=?, `ChangeinOtherAssets`=?, `ChangeinOtherCurrentAssets`=?, `ChangeinOtherCurrentLiabilities`=?, `ChangeinOtherLiabilities`=?, `ChangeinPrepaidExpenses`=?, `DividendsPaid`=?, `EffectofExchangeRateonCash`=?, `EmployeeCompensation`=?, `AcquisitionSaleofBusinessNet`=?, `AdjustmentforEquityEarnings`=?, `AdjustmentforMinorityInterest`=?, `AdjustmentforSpecialCharges`=?, `CapitalExpenditures`=?, `CashfromDiscontinuedOperations`=?, `CashfromFinancingActivities`=?, `CashfromInvestingActivities`=?, `CashfromOperatingActivities`=?, `CFDepreciationAmortization`=?, `DeferredIncomeTaxes`=?, `ChangeinAccountsPayableAccruedExpenses`=?, `ChangeinAccountsReceivable`=?, `InvestmentChangesNet`=?, `NetChangeinCash`=?, `OtherAdjustments`=?, `OtherAssetLiabilityChangesNet`=?, `OtherFinancingActivitiesNet`=?, `OtherInvestingActivities`=?, `RealizedGainsLosses`=?, `SaleofPropertyPlantEquipment`=?, `StockOptionTaxBenefits`=?, `TotalAdjustments`=?";
        $params = array();
        $params[] = ($rawdata["ChangeinCurrentAssets"][$MRQRow] =='null' ? null:$rawdata["ChangeinCurrentAssets"][$MRQRow]);
        $params[] = ($rawdata["ChangeinCurrentLiabilities"][$MRQRow] =='null' ? null:$rawdata["ChangeinCurrentLiabilities"][$MRQRow]);
        $params[] = ($rawdata["ChangeinDebtNet"][$MRQRow] =='null' ? null:$rawdata["ChangeinDebtNet"][$MRQRow]);
        $params[] = ($rawdata["ChangeinDeferredRevenue"][$MRQRow] =='null' ? null:$rawdata["ChangeinDeferredRevenue"][$MRQRow]);
        $params[] = ($rawdata["ChangeinEquityNet"][$MRQRow] =='null' ? null:$rawdata["ChangeinEquityNet"][$MRQRow]);
        $params[] = ($rawdata["ChangeinIncomeTaxesPayable"][$MRQRow] =='null' ? null:$rawdata["ChangeinIncomeTaxesPayable"][$MRQRow]);
        $params[] = ($rawdata["ChangeinInventories"][$MRQRow] =='null' ? null:$rawdata["ChangeinInventories"][$MRQRow]);
        $params[] = ($rawdata["ChangeinOperatingAssetsLiabilities"][$MRQRow] =='null' ? null:$rawdata["ChangeinOperatingAssetsLiabilities"][$MRQRow]);
        $params[] = ($rawdata["ChangeinOtherAssets"][$MRQRow] =='null' ? null:$rawdata["ChangeinOtherAssets"][$MRQRow]);
        $params[] = ($rawdata["ChangeinOtherCurrentAssets"][$MRQRow] =='null' ? null:$rawdata["ChangeinOtherCurrentAssets"][$MRQRow]);
        $params[] = ($rawdata["ChangeinOtherCurrentLiabilities"][$MRQRow] =='null' ? null:$rawdata["ChangeinOtherCurrentLiabilities"][$MRQRow]);
        $params[] = ($rawdata["ChangeinOtherLiabilities"][$MRQRow] =='null' ? null:$rawdata["ChangeinOtherLiabilities"][$MRQRow]);
        $params[] = ($rawdata["ChangeinPrepaidExpenses"][$MRQRow] =='null' ? null:$rawdata["ChangeinPrepaidExpenses"][$MRQRow]);
        $params[] = ($rawdata["DividendsPaid"][$MRQRow] =='null' ? null:$rawdata["DividendsPaid"][$MRQRow]);
        $params[] = ($rawdata["EffectofExchangeRateonCash"][$MRQRow] =='null' ? null:$rawdata["EffectofExchangeRateonCash"][$MRQRow]);
        $params[] = ($rawdata["EmployeeCompensation"][$MRQRow] =='null' ? null:$rawdata["EmployeeCompensation"][$MRQRow]);
        $params[] = ($rawdata["AcquisitionSaleofBusinessNet"][$MRQRow] =='null' ? null:$rawdata["AcquisitionSaleofBusinessNet"][$MRQRow]);
        $params[] = ($rawdata["AdjustmentforEquityEarnings"][$MRQRow] =='null' ? null:$rawdata["AdjustmentforEquityEarnings"][$MRQRow]);
        $params[] = ($rawdata["AdjustmentforMinorityInterest"][$MRQRow] =='null' ? null:$rawdata["AdjustmentforMinorityInterest"][$MRQRow]);
        $params[] = ($rawdata["AdjustmentforSpecialCharges"][$MRQRow] =='null' ? null:$rawdata["AdjustmentforSpecialCharges"][$MRQRow]);
        $params[] = ($rawdata["CapitalExpenditures"][$MRQRow] =='null' ? null:$rawdata["CapitalExpenditures"][$MRQRow]);
        $params[] = ($rawdata["CashfromDiscontinuedOperations"][$MRQRow] =='null' ? null:$rawdata["CashfromDiscontinuedOperations"][$MRQRow]);
        $params[] = ($rawdata["CashfromFinancingActivities"][$MRQRow] =='null' ? null:$rawdata["CashfromFinancingActivities"][$MRQRow]);
        $params[] = ($rawdata["CashfromInvestingActivities"][$MRQRow] =='null' ? null:$rawdata["CashfromInvestingActivities"][$MRQRow]);
        $params[] = ($rawdata["CashfromOperatingActivities"][$MRQRow] =='null' ? null:$rawdata["CashfromOperatingActivities"][$MRQRow]);
        $params[] = ($rawdata["CFDepreciationAmortization"][$MRQRow] =='null' ? null:$rawdata["CFDepreciationAmortization"][$MRQRow]);
        $params[] = ($rawdata["DeferredIncomeTaxes"][$MRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxes"][$MRQRow]);
        $params[] = ($rawdata["ChangeinAccountsPayableAccruedExpenses"][$MRQRow] =='null' ? null:$rawdata["ChangeinAccountsPayableAccruedExpenses"][$MRQRow]);
        $params[] = ($rawdata["ChangeinAccountsReceivable"][$MRQRow] =='null' ? null:$rawdata["ChangeinAccountsReceivable"][$MRQRow]);
        $params[] = ($rawdata["InvestmentChangesNet"][$MRQRow] =='null' ? null:$rawdata["InvestmentChangesNet"][$MRQRow]);
        $params[] = ($rawdata["NetChangeinCash"][$MRQRow] =='null' ? null:$rawdata["NetChangeinCash"][$MRQRow]);
        $params[] = ($rawdata["OtherAdjustments"][$MRQRow] =='null' ? null:$rawdata["OtherAdjustments"][$MRQRow]);
        $params[] = ($rawdata["OtherAssetLiabilityChangesNet"][$MRQRow] =='null' ? null:$rawdata["OtherAssetLiabilityChangesNet"][$MRQRow]);
        $params[] = ($rawdata["OtherFinancingActivitiesNet"][$MRQRow] =='null' ? null:$rawdata["OtherFinancingActivitiesNet"][$MRQRow]);
        $params[] = ($rawdata["OtherInvestingActivities"][$MRQRow] =='null' ? null:$rawdata["OtherInvestingActivities"][$MRQRow]);
        $params[] = ($rawdata["RealizedGainsLosses"][$MRQRow] =='null' ? null:$rawdata["RealizedGainsLosses"][$MRQRow]);
        $params[] = ($rawdata["SaleofPropertyPlantEquipment"][$MRQRow] =='null' ? null:$rawdata["SaleofPropertyPlantEquipment"][$MRQRow]);
        $params[] = ($rawdata["StockOptionTaxBenefits"][$MRQRow] =='null' ? null:$rawdata["StockOptionTaxBenefits"][$MRQRow]);
        $params[] = ($rawdata["TotalAdjustments"][$MRQRow] =='null' ? null:$rawdata["TotalAdjustments"][$MRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinCurrentAssets`=?, `ChangeinCurrentLiabilities`=?, `ChangeinDebtNet`=?, `ChangeinDeferredRevenue`=?, `ChangeinEquityNet`=?, `ChangeinIncomeTaxesPayable`=?, `ChangeinInventories`=?, `ChangeinOperatingAssetsLiabilities`=?, `ChangeinOtherAssets`=?, `ChangeinOtherCurrentAssets`=?, `ChangeinOtherCurrentLiabilities`=?, `ChangeinOtherLiabilities`=?, `ChangeinPrepaidExpenses`=?, `DividendsPaid`=?, `EffectofExchangeRateonCash`=?, `EmployeeCompensation`=?, `AcquisitionSaleofBusinessNet`=?, `AdjustmentforEquityEarnings`=?, `AdjustmentforMinorityInterest`=?, `AdjustmentforSpecialCharges`=?, `CapitalExpenditures`=?, `CashfromDiscontinuedOperations`=?, `CashfromFinancingActivities`=?, `CashfromInvestingActivities`=?, `CashfromOperatingActivities`=?, `CFDepreciationAmortization`=?, `DeferredIncomeTaxes`=?, `ChangeinAccountsPayableAccruedExpenses`=?, `ChangeinAccountsReceivable`=?, `InvestmentChangesNet`=?, `NetChangeinCash`=?, `OtherAdjustments`=?, `OtherAssetLiabilityChangesNet`=?, `OtherFinancingActivitiesNet`=?, `OtherInvestingActivities`=?, `RealizedGainsLosses`=?, `SaleofPropertyPlantEquipment`=?, `StockOptionTaxBenefits`=?, `TotalAdjustments`=?";
        $params = array();
        $params[] = ($rawdata["ChangeinCurrentAssets"][$PMRQRow] =='null' ? null:$rawdata["ChangeinCurrentAssets"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinCurrentLiabilities"][$PMRQRow] =='null' ? null:$rawdata["ChangeinCurrentLiabilities"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinDebtNet"][$PMRQRow] =='null' ? null:$rawdata["ChangeinDebtNet"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinDeferredRevenue"][$PMRQRow] =='null' ? null:$rawdata["ChangeinDeferredRevenue"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinEquityNet"][$PMRQRow] =='null' ? null:$rawdata["ChangeinEquityNet"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinIncomeTaxesPayable"][$PMRQRow] =='null' ? null:$rawdata["ChangeinIncomeTaxesPayable"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinInventories"][$PMRQRow] =='null' ? null:$rawdata["ChangeinInventories"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinOperatingAssetsLiabilities"][$PMRQRow] =='null' ? null:$rawdata["ChangeinOperatingAssetsLiabilities"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinOtherAssets"][$PMRQRow] =='null' ? null:$rawdata["ChangeinOtherAssets"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinOtherCurrentAssets"][$PMRQRow] =='null' ? null:$rawdata["ChangeinOtherCurrentAssets"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinOtherCurrentLiabilities"][$PMRQRow] =='null' ? null:$rawdata["ChangeinOtherCurrentLiabilities"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinOtherLiabilities"][$PMRQRow] =='null' ? null:$rawdata["ChangeinOtherLiabilities"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinPrepaidExpenses"][$PMRQRow] =='null' ? null:$rawdata["ChangeinPrepaidExpenses"][$PMRQRow]);
        $params[] = ($rawdata["DividendsPaid"][$PMRQRow] =='null' ? null:$rawdata["DividendsPaid"][$PMRQRow]);
        $params[] = ($rawdata["EffectofExchangeRateonCash"][$PMRQRow] =='null' ? null:$rawdata["EffectofExchangeRateonCash"][$PMRQRow]);
        $params[] = ($rawdata["EmployeeCompensation"][$PMRQRow] =='null' ? null:$rawdata["EmployeeCompensation"][$PMRQRow]);
        $params[] = ($rawdata["AcquisitionSaleofBusinessNet"][$PMRQRow] =='null' ? null:$rawdata["AcquisitionSaleofBusinessNet"][$PMRQRow]);
        $params[] = ($rawdata["AdjustmentforEquityEarnings"][$PMRQRow] =='null' ? null:$rawdata["AdjustmentforEquityEarnings"][$PMRQRow]);
        $params[] = ($rawdata["AdjustmentforMinorityInterest"][$PMRQRow] =='null' ? null:$rawdata["AdjustmentforMinorityInterest"][$PMRQRow]);
        $params[] = ($rawdata["AdjustmentforSpecialCharges"][$PMRQRow] =='null' ? null:$rawdata["AdjustmentforSpecialCharges"][$PMRQRow]);
        $params[] = ($rawdata["CapitalExpenditures"][$PMRQRow] =='null' ? null:$rawdata["CapitalExpenditures"][$PMRQRow]);
        $params[] = ($rawdata["CashfromDiscontinuedOperations"][$PMRQRow] =='null' ? null:$rawdata["CashfromDiscontinuedOperations"][$PMRQRow]);
        $params[] = ($rawdata["CashfromFinancingActivities"][$PMRQRow] =='null' ? null:$rawdata["CashfromFinancingActivities"][$PMRQRow]);
        $params[] = ($rawdata["CashfromInvestingActivities"][$PMRQRow] =='null' ? null:$rawdata["CashfromInvestingActivities"][$PMRQRow]);
        $params[] = ($rawdata["CashfromOperatingActivities"][$PMRQRow] =='null' ? null:$rawdata["CashfromOperatingActivities"][$PMRQRow]);
        $params[] = ($rawdata["CFDepreciationAmortization"][$PMRQRow] =='null' ? null:$rawdata["CFDepreciationAmortization"][$PMRQRow]);
        $params[] = ($rawdata["DeferredIncomeTaxes"][$PMRQRow] =='null' ? null:$rawdata["DeferredIncomeTaxes"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinAccountsPayableAccruedExpenses"][$PMRQRow] =='null' ? null:$rawdata["ChangeinAccountsPayableAccruedExpenses"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinAccountsReceivable"][$PMRQRow] =='null' ? null:$rawdata["ChangeinAccountsReceivable"][$PMRQRow]);
        $params[] = ($rawdata["InvestmentChangesNet"][$PMRQRow] =='null' ? null:$rawdata["InvestmentChangesNet"][$PMRQRow]);
        $params[] = ($rawdata["NetChangeinCash"][$PMRQRow] =='null' ? null:$rawdata["NetChangeinCash"][$PMRQRow]);
        $params[] = ($rawdata["OtherAdjustments"][$PMRQRow] =='null' ? null:$rawdata["OtherAdjustments"][$PMRQRow]);
        $params[] = ($rawdata["OtherAssetLiabilityChangesNet"][$PMRQRow] =='null' ? null:$rawdata["OtherAssetLiabilityChangesNet"][$PMRQRow]);
        $params[] = ($rawdata["OtherFinancingActivitiesNet"][$PMRQRow] =='null' ? null:$rawdata["OtherFinancingActivitiesNet"][$PMRQRow]);
        $params[] = ($rawdata["OtherInvestingActivities"][$PMRQRow] =='null' ? null:$rawdata["OtherInvestingActivities"][$PMRQRow]);
        $params[] = ($rawdata["RealizedGainsLosses"][$PMRQRow] =='null' ? null:$rawdata["RealizedGainsLosses"][$PMRQRow]);
        $params[] = ($rawdata["SaleofPropertyPlantEquipment"][$PMRQRow] =='null' ? null:$rawdata["SaleofPropertyPlantEquipment"][$PMRQRow]);
        $params[] = ($rawdata["StockOptionTaxBenefits"][$PMRQRow] =='null' ? null:$rawdata["StockOptionTaxBenefits"][$PMRQRow]);
        $params[] = ($rawdata["TotalAdjustments"][$PMRQRow] =='null' ? null:$rawdata["TotalAdjustments"][$PMRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinLongtermDebtNet`=?, `ChangeinShorttermBorrowingsNet`=?, `CashandCashEquivalentsBeginningofYear`=?, `CashandCashEquivalentsEndofYear`=?, `CashPaidforIncomeTaxes`=?, `CashPaidforInterestExpense`=?, `CFNetIncome`=?, `IssuanceofEquity`=?, `LongtermDebtPayments`=?, `LongtermDebtProceeds`=?, `OtherDebtNet`=?, `OtherEquityTransactionsNet`=?, `OtherInvestmentChangesNet`=?, `PurchaseofInvestments`=?, `RepurchaseofEquity`=?, `SaleofInvestments`=?, `ShorttermBorrowings`=?, `TotalNoncashAdjustments`=?";
        $params = array();
        $params[] = ($rawdata["ChangeinLongtermDebtNet"][$MRQRow] =='null' ? null:$rawdata["ChangeinLongtermDebtNet"][$MRQRow]);
        $params[] = ($rawdata["ChangeinShorttermBorrowingsNet"][$MRQRow] =='null' ? null:$rawdata["ChangeinShorttermBorrowingsNet"][$MRQRow]);
        $params[] = ($rawdata["CashandCashEquivalentsBeginningofYear"][$MRQRow] =='null' ? null:$rawdata["CashandCashEquivalentsBeginningofYear"][$MRQRow]);
        $params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$MRQRow] =='null' ? null:$rawdata["CashandCashEquivalentsEndofYear"][$MRQRow]);
        $params[] = ($rawdata["CashPaidforIncomeTaxes"][$MRQRow] =='null' ? null:$rawdata["CashPaidforIncomeTaxes"][$MRQRow]);
        $params[] = ($rawdata["CashPaidforInterestExpense"][$MRQRow] =='null' ? null:$rawdata["CashPaidforInterestExpense"][$MRQRow]);
        $params[] = ($rawdata["CFNetIncome"][$MRQRow] =='null' ? null:$rawdata["CFNetIncome"][$MRQRow]);
        $params[] = ($rawdata["IssuanceofEquity"][$MRQRow] =='null' ? null:$rawdata["IssuanceofEquity"][$MRQRow]);
        $params[] = ($rawdata["LongtermDebtPayments"][$MRQRow] =='null' ? null:$rawdata["LongtermDebtPayments"][$MRQRow]);
        $params[] = ($rawdata["LongtermDebtProceeds"][$MRQRow] =='null' ? null:$rawdata["LongtermDebtProceeds"][$MRQRow]);
        $params[] = ($rawdata["OtherDebtNet"][$MRQRow] =='null' ? null:$rawdata["OtherDebtNet"][$MRQRow]);
        $params[] = ($rawdata["OtherEquityTransactionsNet"][$MRQRow] =='null' ? null:$rawdata["OtherEquityTransactionsNet"][$MRQRow]);
        $params[] = ($rawdata["OtherInvestmentChangesNet"][$MRQRow] =='null' ? null:$rawdata["OtherInvestmentChangesNet"][$MRQRow]);
        $params[] = ($rawdata["PurchaseofInvestments"][$MRQRow] =='null' ? null:$rawdata["PurchaseofInvestments"][$MRQRow]);
        $params[] = ($rawdata["RepurchaseofEquity"][$MRQRow] =='null' ? null:$rawdata["RepurchaseofEquity"][$MRQRow]);
        $params[] = ($rawdata["SaleofInvestments"][$MRQRow] =='null' ? null:$rawdata["SaleofInvestments"][$MRQRow]);
        $params[] = ($rawdata["ShorttermBorrowings"][$MRQRow] =='null' ? null:$rawdata["ShorttermBorrowings"][$MRQRow]);
        $params[] = ($rawdata["TotalNoncashAdjustments"][$MRQRow] =='null' ? null:$rawdata["TotalNoncashAdjustments"][$MRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinLongtermDebtNet`=?, `ChangeinShorttermBorrowingsNet`=?, `CashandCashEquivalentsBeginningofYear`=?, `CashandCashEquivalentsEndofYear`=?, `CashPaidforIncomeTaxes`=?, `CashPaidforInterestExpense`=?, `CFNetIncome`=?, `IssuanceofEquity`=?, `LongtermDebtPayments`=?, `LongtermDebtProceeds`=?, `OtherDebtNet`=?, `OtherEquityTransactionsNet`=?, `OtherInvestmentChangesNet`=?, `PurchaseofInvestments`=?, `RepurchaseofEquity`=?, `SaleofInvestments`=?, `ShorttermBorrowings`=?, `TotalNoncashAdjustments`=?";
        $params = array();
        $params[] = ($rawdata["ChangeinLongtermDebtNet"][$PMRQRow] =='null' ? null:$rawdata["ChangeinLongtermDebtNet"][$PMRQRow]);
        $params[] = ($rawdata["ChangeinShorttermBorrowingsNet"][$PMRQRow] =='null' ? null:$rawdata["ChangeinShorttermBorrowingsNet"][$PMRQRow]);
        $params[] = ($rawdata["CashandCashEquivalentsBeginningofYear"][$PMRQRow] =='null' ? null:$rawdata["CashandCashEquivalentsBeginningofYear"][$PMRQRow]);
        $params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow] =='null' ? null:$rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow]);
        $params[] = ($rawdata["CashPaidforIncomeTaxes"][$PMRQRow] =='null' ? null:$rawdata["CashPaidforIncomeTaxes"][$PMRQRow]);
        $params[] = ($rawdata["CashPaidforInterestExpense"][$PMRQRow] =='null' ? null:$rawdata["CashPaidforInterestExpense"][$PMRQRow]);
        $params[] = ($rawdata["CFNetIncome"][$PMRQRow] =='null' ? null:$rawdata["CFNetIncome"][$PMRQRow]);
        $params[] = ($rawdata["IssuanceofEquity"][$PMRQRow] =='null' ? null:$rawdata["IssuanceofEquity"][$PMRQRow]);
        $params[] = ($rawdata["LongtermDebtPayments"][$PMRQRow] =='null' ? null:$rawdata["LongtermDebtPayments"][$PMRQRow]);
        $params[] = ($rawdata["LongtermDebtProceeds"][$PMRQRow] =='null' ? null:$rawdata["LongtermDebtProceeds"][$PMRQRow]);
        $params[] = ($rawdata["OtherDebtNet"][$PMRQRow] =='null' ? null:$rawdata["OtherDebtNet"][$PMRQRow]);
        $params[] = ($rawdata["OtherEquityTransactionsNet"][$PMRQRow] =='null' ? null:$rawdata["OtherEquityTransactionsNet"][$PMRQRow]);
        $params[] = ($rawdata["OtherInvestmentChangesNet"][$PMRQRow] =='null' ? null:$rawdata["OtherInvestmentChangesNet"][$PMRQRow]);
        $params[] = ($rawdata["PurchaseofInvestments"][$PMRQRow] =='null' ? null:$rawdata["PurchaseofInvestments"][$PMRQRow]);
        $params[] = ($rawdata["RepurchaseofEquity"][$PMRQRow] =='null' ? null:$rawdata["RepurchaseofEquity"][$PMRQRow]);
        $params[] = ($rawdata["SaleofInvestments"][$PMRQRow] =='null' ? null:$rawdata["SaleofInvestments"][$PMRQRow]);
        $params[] = ($rawdata["ShorttermBorrowings"][$PMRQRow] =='null' ? null:$rawdata["ShorttermBorrowings"][$PMRQRow]);
        $params[] = ($rawdata["TotalNoncashAdjustments"][$PMRQRow] =='null' ? null:$rawdata["TotalNoncashAdjustments"][$PMRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `EBIT`=?, `CostofRevenue`=?, `DepreciationAmortizationExpense`=?, `DilutedEPSNetIncome`=?, `DiscontinuedOperations`=?, `EquityEarnings`=?, `AccountingChange`=?, `BasicEPSNetIncome`=?, `ExtraordinaryItems`=?, `GrossProfit`=?, `IncomebeforeExtraordinaryItems`=?, `IncomeBeforeTaxes`=?, `IncomeTaxes`=?, `InterestExpense`=?, `InterestIncome`=?, `MinorityInterestEquityEarnings`=?, `NetIncome`=?, `NetIncomeApplicabletoCommon`=?, `OperatingProfit`=?, `OtherNonoperatingIncomeExpense`=?, `OtherOperatingExpenses`=?, `ResearchDevelopmentExpense`=?, `RestructuringRemediationImpairmentProvisions`=?, `TotalRevenue`=?, `SellingGeneralAdministrativeExpenses`=?";
        $params = array();
        $params[] = ($rawdata["EBIT"][$MRQRow] =='null' ? null:$rawdata["EBIT"][$MRQRow]);
        $params[] = ($rawdata["CostofRevenue"][$MRQRow] =='null' ? null:$rawdata["CostofRevenue"][$MRQRow]);
        $params[] = ($rawdata["DepreciationAmortizationExpense"][$MRQRow] =='null' ? null:$rawdata["DepreciationAmortizationExpense"][$MRQRow]);
        $params[] = ($rawdata["DilutedEPSNetIncome"][$MRQRow] =='null' ? null:$rawdata["DilutedEPSNetIncome"][$MRQRow]);
        $params[] = ($rawdata["DiscontinuedOperations"][$MRQRow] =='null' ? null:$rawdata["DiscontinuedOperations"][$MRQRow]);
        $params[] = ($rawdata["EquityEarnings"][$MRQRow] =='null' ? null:$rawdata["EquityEarnings"][$MRQRow]);
        $params[] = ($rawdata["AccountingChange"][$MRQRow] =='null' ? null:$rawdata["AccountingChange"][$MRQRow]);
        $params[] = ($rawdata["BasicEPSNetIncome"][$MRQRow] =='null' ? null:$rawdata["BasicEPSNetIncome"][$MRQRow]);
        $params[] = ($rawdata["ExtraordinaryItems"][$MRQRow] =='null' ? null:$rawdata["ExtraordinaryItems"][$MRQRow]);
        $params[] = ($rawdata["GrossProfit"][$MRQRow] =='null' ? null:$rawdata["GrossProfit"][$MRQRow]);
        $params[] = ($rawdata["IncomebeforeExtraordinaryItems"][$MRQRow] =='null' ? null:$rawdata["IncomebeforeExtraordinaryItems"][$MRQRow]);
        $params[] = ($rawdata["IncomeBeforeTaxes"][$MRQRow] =='null' ? null:$rawdata["IncomeBeforeTaxes"][$MRQRow]);
        $params[] = ($rawdata["IncomeTaxes"][$MRQRow] =='null' ? null:$rawdata["IncomeTaxes"][$MRQRow]);
        $params[] = ($rawdata["InterestExpense"][$MRQRow] =='null' ? null:toFloat($rawdata["InterestExpense"][$MRQRow]));
        $params[] = ($rawdata["InterestIncome"][$MRQRow] =='null' ? null:toFloat($rawdata["InterestIncome"][$MRQRow]));
        $params[] = ($rawdata["MinorityInterestEquityEarnings"][$MRQRow] =='null' ? null:$rawdata["MinorityInterestEquityEarnings"][$MRQRow]);
        $params[] = ($rawdata["NetIncome"][$MRQRow] =='null' ? null:$rawdata["NetIncome"][$MRQRow]);
        $params[] = ($rawdata["NetIncomeApplicabletoCommon"][$MRQRow] =='null' ? null:$rawdata["NetIncomeApplicabletoCommon"][$MRQRow]);
        $params[] = ($rawdata["OperatingProfit"][$MRQRow] =='null' ? null:$rawdata["OperatingProfit"][$MRQRow]);
        $params[] = ($rawdata["OtherNonoperatingIncomeExpense"][$MRQRow] =='null' ? null:$rawdata["OtherNonoperatingIncomeExpense"][$MRQRow]);
        $params[] = ($rawdata["OtherOperatingExpenses"][$MRQRow] =='null' ? null:$rawdata["OtherOperatingExpenses"][$MRQRow]);
        $params[] = ($rawdata["ResearchDevelopmentExpense"][$MRQRow] =='null' ? null:$rawdata["ResearchDevelopmentExpense"][$MRQRow]);
        $params[] = ($rawdata["RestructuringRemediationImpairmentProvisions"][$MRQRow] =='null' ? null:$rawdata["RestructuringRemediationImpairmentProvisions"][$MRQRow]);
        $params[] = ($rawdata["TotalRevenue"][$MRQRow] =='null' ? null:$rawdata["TotalRevenue"][$MRQRow]);
        $params[] = ($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow] =='null' ? null:$rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `EBIT`=?, `CostofRevenue`=?, `DepreciationAmortizationExpense`=?, `DilutedEPSNetIncome`=?, `DiscontinuedOperations`=?, `EquityEarnings`=?, `AccountingChange`=?, `BasicEPSNetIncome`=?, `ExtraordinaryItems`=?, `GrossProfit`=?, `IncomebeforeExtraordinaryItems`=?, `IncomeBeforeTaxes`=?, `IncomeTaxes`=?, `InterestExpense`=?, `InterestIncome`=?, `MinorityInterestEquityEarnings`=?, `NetIncome`=?, `NetIncomeApplicabletoCommon`=?, `OperatingProfit`=?, `OtherNonoperatingIncomeExpense`=?, `OtherOperatingExpenses`=?, `ResearchDevelopmentExpense`=?, `RestructuringRemediationImpairmentProvisions`=?, `TotalRevenue`=?, `SellingGeneralAdministrativeExpenses`=?";
        $params = array();
        $params[] = ($rawdata["EBIT"][$PMRQRow] =='null' ? null:$rawdata["EBIT"][$PMRQRow]);
        $params[] = ($rawdata["CostofRevenue"][$PMRQRow] =='null' ? null:$rawdata["CostofRevenue"][$PMRQRow]);
        $params[] = ($rawdata["DepreciationAmortizationExpense"][$PMRQRow] =='null' ? null:$rawdata["DepreciationAmortizationExpense"][$PMRQRow]);
        $params[] = ($rawdata["DilutedEPSNetIncome"][$PMRQRow] =='null' ? null:$rawdata["DilutedEPSNetIncome"][$PMRQRow]);
        $params[] = ($rawdata["DiscontinuedOperations"][$PMRQRow] =='null' ? null:$rawdata["DiscontinuedOperations"][$PMRQRow]);
        $params[] = ($rawdata["EquityEarnings"][$PMRQRow] =='null' ? null:$rawdata["EquityEarnings"][$PMRQRow]);
        $params[] = ($rawdata["AccountingChange"][$PMRQRow] =='null' ? null:$rawdata["AccountingChange"][$PMRQRow]);
        $params[] = ($rawdata["BasicEPSNetIncome"][$PMRQRow] =='null' ? null:$rawdata["BasicEPSNetIncome"][$PMRQRow]);
        $params[] = ($rawdata["ExtraordinaryItems"][$PMRQRow] =='null' ? null:$rawdata["ExtraordinaryItems"][$PMRQRow]);
        $params[] = ($rawdata["GrossProfit"][$PMRQRow] =='null' ? null:$rawdata["GrossProfit"][$PMRQRow]);
        $params[] = ($rawdata["IncomebeforeExtraordinaryItems"][$PMRQRow] =='null' ? null:$rawdata["IncomebeforeExtraordinaryItems"][$PMRQRow]);
        $params[] = ($rawdata["IncomeBeforeTaxes"][$PMRQRow] =='null' ? null:$rawdata["IncomeBeforeTaxes"][$PMRQRow]);
        $params[] = ($rawdata["IncomeTaxes"][$PMRQRow] =='null' ? null:$rawdata["IncomeTaxes"][$PMRQRow]);
        $params[] = ($rawdata["InterestExpense"][$PMRQRow] =='null' ? null:toFloat($rawdata["InterestExpense"][$PMRQRow]));
        $params[] = ($rawdata["InterestIncome"][$PMRQRow] =='null' ? null:toFloat($rawdata["InterestIncome"][$PMRQRow]));
        $params[] = ($rawdata["MinorityInterestEquityEarnings"][$PMRQRow] =='null' ? null:$rawdata["MinorityInterestEquityEarnings"][$PMRQRow]);
        $params[] = ($rawdata["NetIncome"][$PMRQRow] =='null' ? null:$rawdata["NetIncome"][$PMRQRow]);
        $params[] = ($rawdata["NetIncomeApplicabletoCommon"][$PMRQRow] =='null' ? null:$rawdata["NetIncomeApplicabletoCommon"][$PMRQRow]);
        $params[] = ($rawdata["OperatingProfit"][$PMRQRow] =='null' ? null:$rawdata["OperatingProfit"][$PMRQRow]);
        $params[] = ($rawdata["OtherNonoperatingIncomeExpense"][$PMRQRow] =='null' ? null:$rawdata["OtherNonoperatingIncomeExpense"][$PMRQRow]);
        $params[] = ($rawdata["OtherOperatingExpenses"][$PMRQRow] =='null' ? null:$rawdata["OtherOperatingExpenses"][$PMRQRow]);
        $params[] = ($rawdata["ResearchDevelopmentExpense"][$PMRQRow] =='null' ? null:$rawdata["ResearchDevelopmentExpense"][$PMRQRow]);
        $params[] = ($rawdata["RestructuringRemediationImpairmentProvisions"][$PMRQRow] =='null' ? null:$rawdata["RestructuringRemediationImpairmentProvisions"][$PMRQRow]);
        $params[] = ($rawdata["TotalRevenue"][$PMRQRow] =='null' ? null:$rawdata["TotalRevenue"][$PMRQRow]);
        $params[] = ($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow] =='null' ? null:$rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AdjustedEBIT`=?, `AdjustedEBITDA`=?, `AdjustedNetIncome`=?, `AftertaxMargin`=?, `EBITDA`=?, `GrossMargin`=?, `NetOperatingProfitafterTax`=?, `OperatingMargin`=?, `RevenueFQ`=?, `RevenueFY`=?, `RevenueTTM`=?, `CostOperatingExpenses`=?, `DepreciationExpense`=?, `DilutedEPSNetIncomefromContinuingOperations`=?, `DilutedWeightedAverageShares`=?, `AmortizationExpense`=?, `BasicEPSNetIncomefromContinuingOperations`=?, `BasicWeightedAverageShares`=?, `GeneralAdministrativeExpense`=?, `IncomeAfterTaxes`=?, `LaborExpense`=?, `NetIncomefromContinuingOperationsApplicabletoCommon`=?, `InterestIncomeExpenseNet`=?, `NoncontrollingInterest`=?, `NonoperatingGainsLosses`=?, `OperatingExpenses`=?, `OtherGeneralAdministrativeExpense`=?, `OtherInterestIncomeExpenseNet`=?, `OtherRevenue`=?, `OtherSellingGeneralAdministrativeExpenses`=?, `PreferredDividends`=?, `SalesMarketingExpense`=?, `TotalNonoperatingIncomeExpense`=?, `TotalOperatingExpenses`=?, `OperatingRevenue`=?";
        $params = array();
        $params[] = ($rawdata["AdjustedEBIT"][$MRQRow] =='null' ? null:$rawdata["AdjustedEBIT"][$MRQRow]);
        $params[] = ($rawdata["AdjustedEBITDA"][$MRQRow] =='null' ? null:$rawdata["AdjustedEBITDA"][$MRQRow]);
        $params[] = ($rawdata["AdjustedNetIncome"][$MRQRow] =='null' ? null:$rawdata["AdjustedNetIncome"][$MRQRow]);
        $params[] = ($rawdata["AftertaxMargin"][$MRQRow] =='null' ? null:$rawdata["AftertaxMargin"][$MRQRow]);
        $params[] = ($rawdata["EBITDA"][$MRQRow] =='null' ? null:$rawdata["EBITDA"][$MRQRow]);
        $params[] = ($rawdata["GrossMargin"][$MRQRow] =='null' ? null:$rawdata["GrossMargin"][$MRQRow]);
        $params[] = ($rawdata["NetOperatingProfitafterTax"][$MRQRow] =='null' ? null:$rawdata["NetOperatingProfitafterTax"][$MRQRow]);
        $params[] = ($rawdata["OperatingMargin"][$MRQRow] =='null' ? null:$rawdata["OperatingMargin"][$MRQRow]);
        $params[] = ($rawdata["RevenueFQ"][$MRQRow] =='null' ? null:$rawdata["RevenueFQ"][$MRQRow]);
        $params[] = ($rawdata["RevenueFY"][$MRQRow] =='null' ? null:$rawdata["RevenueFY"][$MRQRow]);
        $params[] = ($rawdata["RevenueTTM"][$MRQRow] =='null' ? null:$rawdata["RevenueTTM"][$MRQRow]);
        $params[] = ($rawdata["CostOperatingExpenses"][$MRQRow] =='null' ? null:$rawdata["CostOperatingExpenses"][$MRQRow]);
        $params[] = ($rawdata["DepreciationExpense"][$MRQRow] =='null' ? null:$rawdata["DepreciationExpense"][$MRQRow]);
        $params[] = ($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$MRQRow] =='null' ? null:$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$MRQRow]);
        $params[] = ($rawdata["DilutedWeightedAverageShares"][$MRQRow] =='null' ? null:$rawdata["DilutedWeightedAverageShares"][$MRQRow]);
        $params[] = ($rawdata["AmortizationExpense"][$MRQRow] =='null' ? null:$rawdata["AmortizationExpense"][$MRQRow]);
        $params[] = ($rawdata["BasicEPSNetIncomefromContinuingOperations"][$MRQRow] =='null' ? null:$rawdata["BasicEPSNetIncomefromContinuingOperations"][$MRQRow]);
        $params[] = ($rawdata["BasicWeightedAverageShares"][$MRQRow] =='null' ? null:$rawdata["BasicWeightedAverageShares"][$MRQRow]);
        $params[] = ($rawdata["GeneralAdministrativeExpense"][$MRQRow] =='null' ? null:$rawdata["GeneralAdministrativeExpense"][$MRQRow]);
        $params[] = ($rawdata["IncomeAfterTaxes"][$MRQRow] =='null' ? null:$rawdata["IncomeAfterTaxes"][$MRQRow]);
        $params[] = ($rawdata["LaborExpense"][$MRQRow] =='null' ? null:$rawdata["LaborExpense"][$MRQRow]);
        $params[] = ($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$MRQRow] =='null' ? null:$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$MRQRow]);
        $params[] = ($rawdata["InterestIncomeExpenseNet"][$MRQRow] =='null' ? null:$rawdata["InterestIncomeExpenseNet"][$MRQRow]);
        $params[] = ($rawdata["NoncontrollingInterest"][$MRQRow] =='null' ? null:$rawdata["NoncontrollingInterest"][$MRQRow]);
        $params[] = ($rawdata["NonoperatingGainsLosses"][$MRQRow] =='null' ? null:$rawdata["NonoperatingGainsLosses"][$MRQRow]);
        $params[] = ($rawdata["OperatingExpenses"][$MRQRow] =='null' ? null:$rawdata["OperatingExpenses"][$MRQRow]);
        $params[] = ($rawdata["OtherGeneralAdministrativeExpense"][$MRQRow] =='null' ? null:$rawdata["OtherGeneralAdministrativeExpense"][$MRQRow]);
        $params[] = ($rawdata["OtherInterestIncomeExpenseNet"][$MRQRow] =='null' ? null:$rawdata["OtherInterestIncomeExpenseNet"][$MRQRow]);
        $params[] = ($rawdata["OtherRevenue"][$MRQRow] =='null' ? null:$rawdata["OtherRevenue"][$MRQRow]);
        $params[] = ($rawdata["OtherSellingGeneralAdministrativeExpenses"][$MRQRow] =='null' ? null:$rawdata["OtherSellingGeneralAdministrativeExpenses"][$MRQRow]);
        $params[] = ($rawdata["PreferredDividends"][$MRQRow] =='null' ? null:$rawdata["PreferredDividends"][$MRQRow]);
        $params[] = ($rawdata["SalesMarketingExpense"][$MRQRow] =='null' ? null:$rawdata["SalesMarketingExpense"][$MRQRow]);
        $params[] = ($rawdata["TotalNonoperatingIncomeExpense"][$MRQRow] =='null' ? null:$rawdata["TotalNonoperatingIncomeExpense"][$MRQRow]);
        $params[] = ($rawdata["TotalOperatingExpenses"][$MRQRow] =='null' ? null:$rawdata["TotalOperatingExpenses"][$MRQRow]);
        $params[] = ($rawdata["OperatingRevenue"][$MRQRow] =='null' ? null:$rawdata["OperatingRevenue"][$MRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AdjustedEBIT`=?, `AdjustedEBITDA`=?, `AdjustedNetIncome`=?, `AftertaxMargin`=?, `EBITDA`=?, `GrossMargin`=?, `NetOperatingProfitafterTax`=?, `OperatingMargin`=?, `RevenueFQ`=?, `RevenueFY`=?, `RevenueTTM`=?, `CostOperatingExpenses`=?, `DepreciationExpense`=?, `DilutedEPSNetIncomefromContinuingOperations`=?, `DilutedWeightedAverageShares`=?, `AmortizationExpense`=?, `BasicEPSNetIncomefromContinuingOperations`=?, `BasicWeightedAverageShares`=?, `GeneralAdministrativeExpense`=?, `IncomeAfterTaxes`=?, `LaborExpense`=?, `NetIncomefromContinuingOperationsApplicabletoCommon`=?, `InterestIncomeExpenseNet`=?, `NoncontrollingInterest`=?, `NonoperatingGainsLosses`=?, `OperatingExpenses`=?, `OtherGeneralAdministrativeExpense`=?, `OtherInterestIncomeExpenseNet`=?, `OtherRevenue`=?, `OtherSellingGeneralAdministrativeExpenses`=?, `PreferredDividends`=?, `SalesMarketingExpense`=?, `TotalNonoperatingIncomeExpense`=?, `TotalOperatingExpenses`=?, `OperatingRevenue`=?";
        $params = array();
        $params[] = ($rawdata["AdjustedEBIT"][$PMRQRow] =='null' ? null:$rawdata["AdjustedEBIT"][$PMRQRow]);
        $params[] = ($rawdata["AdjustedEBITDA"][$PMRQRow] =='null' ? null:$rawdata["AdjustedEBITDA"][$PMRQRow]);
        $params[] = ($rawdata["AdjustedNetIncome"][$PMRQRow] =='null' ? null:$rawdata["AdjustedNetIncome"][$PMRQRow]);
        $params[] = ($rawdata["AftertaxMargin"][$PMRQRow] =='null' ? null:$rawdata["AftertaxMargin"][$PMRQRow]);
        $params[] = ($rawdata["EBITDA"][$PMRQRow] =='null' ? null:$rawdata["EBITDA"][$PMRQRow]);
        $params[] = ($rawdata["GrossMargin"][$PMRQRow] =='null' ? null:$rawdata["GrossMargin"][$PMRQRow]);
        $params[] = ($rawdata["NetOperatingProfitafterTax"][$PMRQRow] =='null' ? null:$rawdata["NetOperatingProfitafterTax"][$PMRQRow]);
        $params[] = ($rawdata["OperatingMargin"][$PMRQRow] =='null' ? null:$rawdata["OperatingMargin"][$PMRQRow]);
        $params[] = ($rawdata["RevenueFQ"][$PMRQRow] =='null' ? null:$rawdata["RevenueFQ"][$PMRQRow]);
        $params[] = ($rawdata["RevenueFY"][$PMRQRow] =='null' ? null:$rawdata["RevenueFY"][$PMRQRow]);
        $params[] = ($rawdata["RevenueTTM"][$PMRQRow] =='null' ? null:$rawdata["RevenueTTM"][$PMRQRow]);
        $params[] = ($rawdata["CostOperatingExpenses"][$PMRQRow] =='null' ? null:$rawdata["CostOperatingExpenses"][$PMRQRow]);
        $params[] = ($rawdata["DepreciationExpense"][$PMRQRow] =='null' ? null:$rawdata["DepreciationExpense"][$PMRQRow]);
        $params[] = ($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$PMRQRow] =='null' ? null:$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$PMRQRow]);
        $params[] = ($rawdata["DilutedWeightedAverageShares"][$PMRQRow] =='null' ? null:$rawdata["DilutedWeightedAverageShares"][$PMRQRow]);
        $params[] = ($rawdata["AmortizationExpense"][$PMRQRow] =='null' ? null:$rawdata["AmortizationExpense"][$PMRQRow]);
        $params[] = ($rawdata["BasicEPSNetIncomefromContinuingOperations"][$PMRQRow] =='null' ? null:$rawdata["BasicEPSNetIncomefromContinuingOperations"][$PMRQRow]);
        $params[] = ($rawdata["BasicWeightedAverageShares"][$PMRQRow] =='null' ? null:$rawdata["BasicWeightedAverageShares"][$PMRQRow]);
        $params[] = ($rawdata["GeneralAdministrativeExpense"][$PMRQRow] =='null' ? null:$rawdata["GeneralAdministrativeExpense"][$PMRQRow]);
        $params[] = ($rawdata["IncomeAfterTaxes"][$PMRQRow] =='null' ? null:$rawdata["IncomeAfterTaxes"][$PMRQRow]);
        $params[] = ($rawdata["LaborExpense"][$PMRQRow] =='null' ? null:$rawdata["LaborExpense"][$PMRQRow]);
        $params[] = ($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$PMRQRow] =='null' ? null:$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$PMRQRow]);
        $params[] = ($rawdata["InterestIncomeExpenseNet"][$PMRQRow] =='null' ? null:$rawdata["InterestIncomeExpenseNet"][$PMRQRow]);
        $params[] = ($rawdata["NoncontrollingInterest"][$PMRQRow] =='null' ? null:$rawdata["NoncontrollingInterest"][$PMRQRow]);
        $params[] = ($rawdata["NonoperatingGainsLosses"][$PMRQRow] =='null' ? null:$rawdata["NonoperatingGainsLosses"][$PMRQRow]);
        $params[] = ($rawdata["OperatingExpenses"][$PMRQRow] =='null' ? null:$rawdata["OperatingExpenses"][$PMRQRow]);
        $params[] = ($rawdata["OtherGeneralAdministrativeExpense"][$PMRQRow] =='null' ? null:$rawdata["OtherGeneralAdministrativeExpense"][$PMRQRow]);
        $params[] = ($rawdata["OtherInterestIncomeExpenseNet"][$PMRQRow] =='null' ? null:$rawdata["OtherInterestIncomeExpenseNet"][$PMRQRow]);
        $params[] = ($rawdata["OtherRevenue"][$PMRQRow] =='null' ? null:$rawdata["OtherRevenue"][$PMRQRow]);
        $params[] = ($rawdata["OtherSellingGeneralAdministrativeExpenses"][$PMRQRow] =='null' ? null:$rawdata["OtherSellingGeneralAdministrativeExpenses"][$PMRQRow]);
        $params[] = ($rawdata["PreferredDividends"][$PMRQRow] =='null' ? null:$rawdata["PreferredDividends"][$PMRQRow]);
        $params[] = ($rawdata["SalesMarketingExpense"][$PMRQRow] =='null' ? null:$rawdata["SalesMarketingExpense"][$PMRQRow]);
        $params[] = ($rawdata["TotalNonoperatingIncomeExpense"][$PMRQRow] =='null' ? null:$rawdata["TotalNonoperatingIncomeExpense"][$PMRQRow]);
        $params[] = ($rawdata["TotalOperatingExpenses"][$PMRQRow] =='null' ? null:$rawdata["TotalOperatingExpenses"][$PMRQRow]);
        $params[] = ($rawdata["OperatingRevenue"][$PMRQRow] =='null' ? null:$rawdata["OperatingRevenue"][$PMRQRow]);
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `COGSPercent`=?, `GrossMarginPercent`=?, `SGAPercent`=?, `RDPercent`=?, `DepreciationAmortizationPercent`=?, `EBITDAPercent`=?, `OperatingMarginPercent`=?, `EBITPercent`=?, `TaxRatePercent`=?, `IncomeAfterTaxes`=?, `NetMarginPercent`=?, `DividendsPerShare`=?, `ShortTermDebtAndCurrentPortion`=?, `TotalLongTermDebtAndNotesPayable`=?, `NetChangeLongTermDebt`=?, `CapEx`=?, `FreeCashFlow`=?, `OwnerEarningsFCF`=?, `Sales5YYCGrPerc`=?";
        $params = array();
        $params[] = (($rawdata["CostofRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["CostofRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["GrossProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["GrossProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]=='null' ||  $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["ResearchDevelopmentExpense"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["ResearchDevelopmentExpense"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["CFDepreciationAmortization"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["CFDepreciationAmortization"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["EBITDA"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["EBITDA"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["OperatingProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["OperatingProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["EBIT"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["EBIT"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["IncomeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]==0)?null:($rawdata["IncomeTaxes"][$MRQRow]/$rawdata["IncomeBeforeTaxes"][$MRQRow]));
        $params[] = (($rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' && $rawdata["IncomeTaxes"][$MRQRow]=='null')?null:($rawdata["IncomeBeforeTaxes"][$MRQRow]-$rawdata["IncomeTaxes"][$MRQRow]));
        $params[] = (($rawdata["NetIncome"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["NetIncome"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
        $params[] = (($rawdata["DividendsPaid"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]==0)?null:(-($rawdata["DividendsPaid"][$MRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$MRQRow])*1000000)));
        $params[] = (($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]=='null' && $rawdata["ShorttermBorrowings"][$MRQRow]=='null')?null:($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow]));
        $params[] = (($rawdata["TotalLongtermDebt"][$MRQRow]=='null' && $rawdata["NotesPayable"][$MRQRow]=='null')?null:($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow]));
        $params[] = (($rawdata["LongtermDebtProceeds"][$MRQRow]=='null' && $rawdata["LongtermDebtPayments"][$MRQRow] == 'null')?null:($rawdata["LongtermDebtProceeds"][$MRQRow]+$rawdata["LongtermDebtPayments"][$MRQRow]));
        $params[] = (($rawdata["CapitalExpenditures"][$MRQRow]=='null')?null:(-$rawdata["CapitalExpenditures"][$MRQRow]));
        $params[] = (($rawdata["CashfromOperatingActivities"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null')?null:($rawdata["CashfromOperatingActivities"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow]));
        $params[] = (($rawdata["CFNetIncome"][$MRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$MRQRow]=='null' && $rawdata["EmployeeCompensation"][$MRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$MRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$MRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$MRQRow]=='null')?null:($rawdata["CFNetIncome"][$MRQRow]+$rawdata["CFDepreciationAmortization"][$MRQRow]+$rawdata["EmployeeCompensation"][$MRQRow]+$rawdata["AdjustmentforSpecialCharges"][$MRQRow]+$rawdata["DeferredIncomeTaxes"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow]+($rawdata["ChangeinCurrentAssets"][$MRQRow]+$rawdata["ChangeinCurrentLiabilities"][$MRQRow])));
        $params[] = (($rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]<=0 || $rawdata["TotalRevenue"][$MRQRow] < 0)?null:(pow($rawdata["TotalRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow-5], 1/5) - 1));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `COGSPercent`=?, `GrossMarginPercent`=?, `SGAPercent`=?, `RDPercent`=?, `DepreciationAmortizationPercent`=?, `EBITDAPercent`=?, `OperatingMarginPercent`=?, `EBITPercent`=?, `TaxRatePercent`=?, `IncomeAfterTaxes`=?, `NetMarginPercent`=?, `DividendsPerShare`=?, `ShortTermDebtAndCurrentPortion`=?, `TotalLongTermDebtAndNotesPayable`=?, `NetChangeLongTermDebt`=?, `CapEx`=?, `FreeCashFlow`=?, `OwnerEarningsFCF`=?";
        $params = array();
        $params[] = (($rawdata["CostofRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["CostofRevenue"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["GrossProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["GrossProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]=='null' ||  $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["ResearchDevelopmentExpense"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["ResearchDevelopmentExpense"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["CFDepreciationAmortization"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["EBITDA"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["EBITDA"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["OperatingProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["OperatingProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["EBIT"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["EBIT"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["IncomeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]==0)?null:($rawdata["IncomeTaxes"][$PMRQRow]/$rawdata["IncomeBeforeTaxes"][$PMRQRow]));
        $params[] = (($rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' && $rawdata["IncomeTaxes"][$PMRQRow]=='null')?null:($rawdata["IncomeBeforeTaxes"][$PMRQRow]-$rawdata["IncomeTaxes"][$PMRQRow]));
        $params[] = (($rawdata["NetIncome"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["NetIncome"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
        $params[] = (($rawdata["DividendsPaid"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]==0)?null:(-($rawdata["DividendsPaid"][$PMRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow])*1000000)));
        $params[] = (($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]=='null' && $rawdata["ShorttermBorrowings"][$PMRQRow]=='null')?null:($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]+$rawdata["ShorttermBorrowings"][$PMRQRow]));
        $params[] = (($rawdata["TotalLongtermDebt"][$PMRQRow]=='null' && $rawdata["NotesPayable"][$PMRQRow]=='null')?null:($rawdata["TotalLongtermDebt"][$PMRQRow]+$rawdata["NotesPayable"][$PMRQRow]));
        $params[] = (($rawdata["LongtermDebtProceeds"][$PMRQRow]=='null' && $rawdata["LongtermDebtPayments"][$PMRQRow] == 'null')?null:($rawdata["LongtermDebtProceeds"][$PMRQRow]+$rawdata["LongtermDebtPayments"][$PMRQRow]));
        $params[] = (($rawdata["CapitalExpenditures"][$PMRQRow]=='null')?null:(-$rawdata["CapitalExpenditures"][$PMRQRow]));
        $params[] = (($rawdata["CashfromOperatingActivities"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null')?null:($rawdata["CashfromOperatingActivities"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow]));
        $params[] = (($rawdata["CFNetIncome"][$PMRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' && $rawdata["EmployeeCompensation"][$PMRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$PMRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$PMRQRow]=='null')?null:($rawdata["CFNetIncome"][$PMRQRow]+$rawdata["CFDepreciationAmortization"][$PMRQRow]+$rawdata["EmployeeCompensation"][$PMRQRow]+$rawdata["AdjustmentforSpecialCharges"][$PMRQRow]+$rawdata["DeferredIncomeTaxes"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow]+($rawdata["ChangeinCurrentAssets"][$PMRQRow]+$rawdata["ChangeinCurrentLiabilities"][$PMRQRow])));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    } else {
        $query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `InterestIncome`=?, `InterestExpense`=?, `EPSBasic`=?, `EPSDiluted`=?, `SharesOutstandingDiluted`=?, `InventoriesRawMaterialsComponents`=?, `InventoriesWorkInProcess`=?, `InventoriesInventoriesAdjustments`=?, `InventoriesFinishedGoods`=?, `InventoriesOther`=?, `TotalInventories`=?, `LandAndImprovements`=?, `BuildingsAndImprovements`=?, `MachineryFurnitureEquipment`=?, `ConstructionInProgress`=?, `GrossPropertyPlantandEquipment`=?, `SharesOutstandingBasic`=?";
        $params = array();
        $params[] = ((!isset($rawdata["InterestIncome"]) || ($rawdata["InterestIncome"][$treports-3]=='null'&&$rawdata["InterestIncome"][$treports-2]=='null'&&$rawdata["InterestIncome"][$treports-1]=='null'&&$rawdata["InterestIncome"][$treports]=='null'))?null:(toFloat($rawdata["InterestIncome"][$treports-3])+toFloat($rawdata["InterestIncome"][$treports-2])+toFloat($rawdata["InterestIncome"][$treports-1])+toFloat($rawdata["InterestIncome"][$treports])));
        $params[] = ((!isset($rawdata["InterestExpense"]) || ($rawdata["InterestExpense"][$treports-3]=='null'&&$rawdata["InterestExpense"][$treports-2]=='null'&&$rawdata["InterestExpense"][$treports-1]=='null'&&$rawdata["InterestExpense"][$treports]=='null'))?null:(toFloat($rawdata["InterestExpense"][$treports-3])+toFloat($rawdata["InterestExpense"][$treports-2])+toFloat($rawdata["InterestExpense"][$treports-1])+toFloat($rawdata["InterestExpense"][$treports])));
        $params[] = ((!isset($rawdata["EPSBasic"]) || ($rawdata["EPSBasic"][$treports-3]=='null'&&$rawdata["EPSBasic"][$treports-2]=='null'&&$rawdata["EPSBasic"][$treports-1]=='null'&&$rawdata["EPSBasic"][$treports]=='null'))?null:(toFloat($rawdata["EPSBasic"][$treports-3])+toFloat($rawdata["EPSBasic"][$treports-2])+toFloat($rawdata["EPSBasic"][$treports-1])+toFloat($rawdata["EPSBasic"][$treports])));
        $params[] = ((!isset($rawdata["EPSDiluted"]) || ($rawdata["EPSDiluted"][$treports-3]=='null'&&$rawdata["EPSDiluted"][$treports-2]=='null'&&$rawdata["EPSDiluted"][$treports-1]=='null'&&$rawdata["EPSDiluted"][$treports]=='null'))?null:(toFloat($rawdata["EPSDiluted"][$treports-3])+toFloat($rawdata["EPSDiluted"][$treports-2])+toFloat($rawdata["EPSDiluted"][$treports-1])+toFloat($rawdata["EPSDiluted"][$treports])));
        $params[] = ((!isset($rawdata["SharesOutstandingDiluted"]) || $rawdata["SharesOutstandingDiluted"][$MRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesRawMaterialsComponents"]) || $rawdata["InventoriesRawMaterialsComponents"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesWorkInProcess"]) || $rawdata["InventoriesWorkInProcess"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesInventoriesAdjustments"]) || $rawdata["InventoriesInventoriesAdjustments"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesFinishedGoods"]) || $rawdata["InventoriesFinishedGoods"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]));
        $params[] = ((!isset($rawdata["InventoriesOther"]) || $rawdata["InventoriesOther"][$MRQRow] =='null') ? null:toFloat($rawdata["InventoriesOther"][$MRQRow]));
        $params[] = ((!isset($rawdata["TotalInventories"]) || $rawdata["TotalInventories"][$MRQRow] =='null') ? null:toFloat($rawdata["TotalInventories"][$MRQRow]));
        $params[] = ((!isset($rawdata["LandAndImprovements"]) || $rawdata["LandAndImprovements"][$MRQRow] =='null') ? null:toFloat($rawdata["LandAndImprovements"][$MRQRow]));
        $params[] = ((!isset($rawdata["BuildingsAndImprovements"]) || $rawdata["BuildingsAndImprovements"][$MRQRow] =='null') ? null:toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]));
        $params[] = ((!isset($rawdata["MachineryFurnitureEquipment"]) || $rawdata["MachineryFurnitureEquipment"][$MRQRow] =='null') ? null:toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]));
        $params[] = ((!isset($rawdata["ConstructionInProgress"]) || $rawdata["ConstructionInProgress"][$MRQRow] =='null') ? null:toFloat($rawdata["ConstructionInProgress"][$MRQRow]));
        $params[] = ((!isset($rawdata["GrossPropertyPlantandEquipment"]) || $rawdata["GrossPropertyPlantandEquipment"][$MRQRow] =='null') ? null:toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]));
        $params[] = ((!isset($rawdata["SharesOutstandingBasic"]) || $rawdata["SharesOutstandingBasic"][$MRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `InterestIncome`=?, `InterestExpense`=?, `EPSBasic`=?, `EPSDiluted`=?, `SharesOutstandingDiluted`=?, `InventoriesRawMaterialsComponents`=?, `InventoriesWorkInProcess`=?, `InventoriesInventoriesAdjustments`=?, `InventoriesFinishedGoods`=?, `InventoriesOther`=?, `TotalInventories`=?, `LandAndImprovements`=?, `BuildingsAndImprovements`=?, `MachineryFurnitureEquipment`=?, `ConstructionInProgress`=?, `GrossPropertyPlantandEquipment`=?, `SharesOutstandingBasic`=?";
        $params = array();
        $params[] = ((!isset($rawdata["InterestIncome"]) || ($rawdata["InterestIncome"][$treports-7]=='null'&&$rawdata["InterestIncome"][$treports-6]=='null'&&$rawdata["InterestIncome"][$treports-5]=='null'&&$rawdata["InterestIncome"][$treports-4]=='null'))?null:(toFloat($rawdata["InterestIncome"][$treports-7])+toFloat($rawdata["InterestIncome"][$treports-6])+toFloat($rawdata["InterestIncome"][$treports-5])+toFloat($rawdata["InterestIncome"][$treports-4])));
        $params[] = ((!isset($rawdata["InterestExpense"]) || ($rawdata["InterestExpense"][$treports-7]=='null'&&$rawdata["InterestExpense"][$treports-6]=='null'&&$rawdata["InterestExpense"][$treports-5]=='null'&&$rawdata["InterestExpense"][$treports-4]=='null'))?null:(toFloat($rawdata["InterestExpense"][$treports-7])+toFloat($rawdata["InterestExpense"][$treports-6])+toFloat($rawdata["InterestExpense"][$treports-5])+toFloat($rawdata["InterestExpense"][$treports-4])));
        $params[] = ((!isset($rawdata["EPSBasic"]) || ($rawdata["EPSBasic"][$treports-7]=='null'&&$rawdata["EPSBasic"][$treports-6]=='null'&&$rawdata["EPSBasic"][$treports-5]=='null'&&$rawdata["EPSBasic"][$treports-4]=='null'))?null:(toFloat($rawdata["EPSBasic"][$treports-7])+toFloat($rawdata["EPSBasic"][$treports-6])+toFloat($rawdata["EPSBasic"][$treports-5])+toFloat($rawdata["EPSBasic"][$treports-4])));
        $params[] = ((!isset($rawdata["EPSDiluted"]) || ($rawdata["EPSDiluted"][$treports-7]=='null'&&$rawdata["EPSDiluted"][$treports-6]=='null'&&$rawdata["EPSDiluted"][$treports-5]=='null'&&$rawdata["EPSDiluted"][$treports-4]=='null'))?null:(toFloat($rawdata["EPSDiluted"][$treports-7])+toFloat($rawdata["EPSDiluted"][$treports-6])+toFloat($rawdata["EPSDiluted"][$treports-5])+toFloat($rawdata["EPSDiluted"][$treports-4])));
        $params[] = ((!isset($rawdata["SharesOutstandingDiluted"]) || $rawdata["SharesOutstandingDiluted"][$PMRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesRawMaterialsComponents"]) || $rawdata["InventoriesRawMaterialsComponents"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesWorkInProcess"]) || $rawdata["InventoriesWorkInProcess"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesInventoriesAdjustments"]) || $rawdata["InventoriesInventoriesAdjustments"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesFinishedGoods"]) || $rawdata["InventoriesFinishedGoods"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]));
        $params[] = ((!isset($rawdata["InventoriesOther"]) || $rawdata["InventoriesOther"][$PMRQRow] =='null') ? null:toFloat($rawdata["InventoriesOther"][$PMRQRow]));
        $params[] = ((!isset($rawdata["TotalInventories"]) || $rawdata["TotalInventories"][$PMRQRow] =='null') ? null:toFloat($rawdata["TotalInventories"][$PMRQRow]));
        $params[] = ((!isset($rawdata["LandAndImprovements"]) || $rawdata["LandAndImprovements"][$PMRQRow] =='null') ? null:toFloat($rawdata["LandAndImprovements"][$PMRQRow]));
        $params[] = ((!isset($rawdata["BuildingsAndImprovements"]) || $rawdata["BuildingsAndImprovements"][$PMRQRow] =='null') ? null:toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]));
        $params[] = ((!isset($rawdata["MachineryFurnitureEquipment"]) || $rawdata["MachineryFurnitureEquipment"][$PMRQRow] =='null') ? null:toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]));
        $params[] = ((!isset($rawdata["ConstructionInProgress"]) || $rawdata["ConstructionInProgress"][$PMRQRow] =='null') ? null:toFloat($rawdata["ConstructionInProgress"][$PMRQRow]));
        $params[] = ((!isset($rawdata["GrossPropertyPlantandEquipment"]) || $rawdata["GrossPropertyPlantandEquipment"][$PMRQRow] =='null') ? null:toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]));
        $params[] = ((!isset($rawdata["SharesOutstandingBasic"]) || $rawdata["SharesOutstandingBasic"][$PMRQRow] =='null') ? null:toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinCurrentAssets`=?, `ChangeinCurrentLiabilities`=?, `ChangeinDebtNet`=?, `ChangeinDeferredRevenue`=?, `ChangeinEquityNet`=?, `ChangeinIncomeTaxesPayable`=?, `ChangeinInventories`=?, `ChangeinOperatingAssetsLiabilities`=?, `ChangeinOtherAssets`=?, `ChangeinOtherCurrentAssets`=?, `ChangeinOtherCurrentLiabilities`=?, `ChangeinOtherLiabilities`=?, `ChangeinPrepaidExpenses`=?, `DividendsPaid`=?, `EffectofExchangeRateonCash`=?, `EmployeeCompensation`=?, `AcquisitionSaleofBusinessNet`=?, `AdjustmentforEquityEarnings`=?, `AdjustmentforMinorityInterest`=?, `AdjustmentforSpecialCharges`=?, `CapitalExpenditures`=?, `CashfromDiscontinuedOperations`=?, `CashfromFinancingActivities`=?, `CashfromInvestingActivities`=?, `CashfromOperatingActivities`=?, `CFDepreciationAmortization`=?, `DeferredIncomeTaxes`=?, `ChangeinAccountsPayableAccruedExpenses`=?, `ChangeinAccountsReceivable`=?, `InvestmentChangesNet`=?, `NetChangeinCash`=?, `OtherAdjustments`=?, `OtherAssetLiabilityChangesNet`=?, `OtherFinancingActivitiesNet`=?, `OtherInvestingActivities`=?, `RealizedGainsLosses`=?, `SaleofPropertyPlantEquipment`=?, `StockOptionTaxBenefits`=?, `TotalAdjustments`=?";
        $params = array();
        $params[] = (($rawdata["ChangeinCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports]=='null')?null:($rawdata["ChangeinCurrentAssets"][$treports-3]+$rawdata["ChangeinCurrentAssets"][$treports-2]+$rawdata["ChangeinCurrentAssets"][$treports-1]+$rawdata["ChangeinCurrentAssets"][$treports]));
        $params[] = (($rawdata["ChangeinCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports]=='null')?null:($rawdata["ChangeinCurrentLiabilities"][$treports-3]+$rawdata["ChangeinCurrentLiabilities"][$treports-2]+$rawdata["ChangeinCurrentLiabilities"][$treports-1]+$rawdata["ChangeinCurrentLiabilities"][$treports]));
        $params[] = (($rawdata["ChangeinDebtNet"][$treports-3]=='null'&&$rawdata["ChangeinDebtNet"][$treports-2]=='null'&&$rawdata["ChangeinDebtNet"][$treports-1]=='null'&&$rawdata["ChangeinDebtNet"][$treports]=='null')?null:($rawdata["ChangeinDebtNet"][$treports-3]+$rawdata["ChangeinDebtNet"][$treports-2]+$rawdata["ChangeinDebtNet"][$treports-1]+$rawdata["ChangeinDebtNet"][$treports]));
        $params[] = (($rawdata["ChangeinDeferredRevenue"][$treports-3]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-2]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-1]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports]=='null')?null:($rawdata["ChangeinDeferredRevenue"][$treports-3]+$rawdata["ChangeinDeferredRevenue"][$treports-2]+$rawdata["ChangeinDeferredRevenue"][$treports-1]+$rawdata["ChangeinDeferredRevenue"][$treports]));
        $params[] = (($rawdata["ChangeinEquityNet"][$treports-3]=='null'&&$rawdata["ChangeinEquityNet"][$treports-2]=='null'&&$rawdata["ChangeinEquityNet"][$treports-1]=='null'&&$rawdata["ChangeinEquityNet"][$treports]=='null')?null:($rawdata["ChangeinEquityNet"][$treports-3]+$rawdata["ChangeinEquityNet"][$treports-2]+$rawdata["ChangeinEquityNet"][$treports-1]+$rawdata["ChangeinEquityNet"][$treports]));
        $params[] = (($rawdata["ChangeinIncomeTaxesPayable"][$treports-3]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-2]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-1]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports]=='null')?null:($rawdata["ChangeinIncomeTaxesPayable"][$treports-3]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-2]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-1]+$rawdata["ChangeinIncomeTaxesPayable"][$treports]));
        $params[] = (($rawdata["ChangeinInventories"][$treports-3]=='null'&&$rawdata["ChangeinInventories"][$treports-2]=='null'&&$rawdata["ChangeinInventories"][$treports-1]=='null'&&$rawdata["ChangeinInventories"][$treports]=='null')?null:($rawdata["ChangeinInventories"][$treports-3]+$rawdata["ChangeinInventories"][$treports-2]+$rawdata["ChangeinInventories"][$treports-1]+$rawdata["ChangeinInventories"][$treports]));
        $params[] = (($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports]=='null')?null:($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-3]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-2]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-1]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports]));
        $params[] = (($rawdata["ChangeinOtherAssets"][$treports-3]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-2]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-1]=='null'&&$rawdata["ChangeinOtherAssets"][$treports]=='null')?null:($rawdata["ChangeinOtherAssets"][$treports-3]+$rawdata["ChangeinOtherAssets"][$treports-2]+$rawdata["ChangeinOtherAssets"][$treports-1]+$rawdata["ChangeinOtherAssets"][$treports]));
        $params[] = (($rawdata["ChangeinOtherCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports]=='null')?null:($rawdata["ChangeinOtherCurrentAssets"][$treports-3]+$rawdata["ChangeinOtherCurrentAssets"][$treports-2]+$rawdata["ChangeinOtherCurrentAssets"][$treports-1]+$rawdata["ChangeinOtherCurrentAssets"][$treports]));
        $params[] = (($rawdata["ChangeinOtherCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports]=='null')?null:($rawdata["ChangeinOtherCurrentLiabilities"][$treports-3]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-2]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-1]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports]));
        $params[] = (($rawdata["ChangeinOtherLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports]=='null')?null:($rawdata["ChangeinOtherLiabilities"][$treports-3]+$rawdata["ChangeinOtherLiabilities"][$treports-2]+$rawdata["ChangeinOtherLiabilities"][$treports-1]+$rawdata["ChangeinOtherLiabilities"][$treports]));
        $params[] = (($rawdata["ChangeinPrepaidExpenses"][$treports-3]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-2]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-1]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports]=='null')?null:($rawdata["ChangeinPrepaidExpenses"][$treports-3]+$rawdata["ChangeinPrepaidExpenses"][$treports-2]+$rawdata["ChangeinPrepaidExpenses"][$treports-1]+$rawdata["ChangeinPrepaidExpenses"][$treports]));
        $params[] = (($rawdata["DividendsPaid"][$treports-3]=='null'&&$rawdata["DividendsPaid"][$treports-2]=='null'&&$rawdata["DividendsPaid"][$treports-1]=='null'&&$rawdata["DividendsPaid"][$treports]=='null')?null:($rawdata["DividendsPaid"][$treports-3]+$rawdata["DividendsPaid"][$treports-2]+$rawdata["DividendsPaid"][$treports-1]+$rawdata["DividendsPaid"][$treports]));
        $params[] = (($rawdata["EffectofExchangeRateonCash"][$treports-3]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-2]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-1]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports]=='null')?null:($rawdata["EffectofExchangeRateonCash"][$treports-3]+$rawdata["EffectofExchangeRateonCash"][$treports-2]+$rawdata["EffectofExchangeRateonCash"][$treports-1]+$rawdata["EffectofExchangeRateonCash"][$treports]));
        $params[] = (($rawdata["EmployeeCompensation"][$treports-3]=='null'&&$rawdata["EmployeeCompensation"][$treports-2]=='null'&&$rawdata["EmployeeCompensation"][$treports-1]=='null'&&$rawdata["EmployeeCompensation"][$treports]=='null')?null:($rawdata["EmployeeCompensation"][$treports-3]+$rawdata["EmployeeCompensation"][$treports-2]+$rawdata["EmployeeCompensation"][$treports-1]+$rawdata["EmployeeCompensation"][$treports]));
        $params[] = (($rawdata["AcquisitionSaleofBusinessNet"][$treports-3]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-2]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-1]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports]=='null')?null:($rawdata["AcquisitionSaleofBusinessNet"][$treports-3]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-2]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-1]+$rawdata["AcquisitionSaleofBusinessNet"][$treports]));
        $params[] = (($rawdata["AdjustmentforEquityEarnings"][$treports-3]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-2]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-1]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports]=='null')?null:($rawdata["AdjustmentforEquityEarnings"][$treports-3]+$rawdata["AdjustmentforEquityEarnings"][$treports-2]+$rawdata["AdjustmentforEquityEarnings"][$treports-1]+$rawdata["AdjustmentforEquityEarnings"][$treports]));
        $params[] = (($rawdata["AdjustmentforMinorityInterest"][$treports-3]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-2]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-1]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports]=='null')?null:($rawdata["AdjustmentforMinorityInterest"][$treports-3]+$rawdata["AdjustmentforMinorityInterest"][$treports-2]+$rawdata["AdjustmentforMinorityInterest"][$treports-1]+$rawdata["AdjustmentforMinorityInterest"][$treports]));
        $params[] = (($rawdata["AdjustmentforSpecialCharges"][$treports-3]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-2]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-1]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports]=='null')?null:($rawdata["AdjustmentforSpecialCharges"][$treports-3]+$rawdata["AdjustmentforSpecialCharges"][$treports-2]+$rawdata["AdjustmentforSpecialCharges"][$treports-1]+$rawdata["AdjustmentforSpecialCharges"][$treports]));
        $params[] = (($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')?null:($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports]));
        $params[] = (($rawdata["CashfromDiscontinuedOperations"][$treports-3]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-2]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-1]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports]=='null')?null:($rawdata["CashfromDiscontinuedOperations"][$treports-3]+$rawdata["CashfromDiscontinuedOperations"][$treports-2]+$rawdata["CashfromDiscontinuedOperations"][$treports-1]+$rawdata["CashfromDiscontinuedOperations"][$treports]));
        $params[] = (($rawdata["CashfromFinancingActivities"][$treports-3]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-2]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-1]=='null'&&$rawdata["CashfromFinancingActivities"][$treports]=='null')?null:($rawdata["CashfromFinancingActivities"][$treports-3]+$rawdata["CashfromFinancingActivities"][$treports-2]+$rawdata["CashfromFinancingActivities"][$treports-1]+$rawdata["CashfromFinancingActivities"][$treports]));
        $params[] = (($rawdata["CashfromInvestingActivities"][$treports-3]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-2]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-1]=='null'&&$rawdata["CashfromInvestingActivities"][$treports]=='null')?null:($rawdata["CashfromInvestingActivities"][$treports-3]+$rawdata["CashfromInvestingActivities"][$treports-2]+$rawdata["CashfromInvestingActivities"][$treports-1]+$rawdata["CashfromInvestingActivities"][$treports]));
        $params[] = (($rawdata["CashfromOperatingActivities"][$treports-3]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-2]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-1]=='null'&&$rawdata["CashfromOperatingActivities"][$treports]=='null')?null:($rawdata["CashfromOperatingActivities"][$treports-3]+$rawdata["CashfromOperatingActivities"][$treports-2]+$rawdata["CashfromOperatingActivities"][$treports-1]+$rawdata["CashfromOperatingActivities"][$treports]));
        $params[] = (($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')?null:($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports]));
        $params[] = (($rawdata["DeferredIncomeTaxes"][$treports-3]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-2]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-1]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports]=='null')?null:($rawdata["DeferredIncomeTaxes"][$treports-3]+$rawdata["DeferredIncomeTaxes"][$treports-2]+$rawdata["DeferredIncomeTaxes"][$treports-1]+$rawdata["DeferredIncomeTaxes"][$treports]));
        $params[] = (($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-3]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-2]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-1]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports]=='null')?null:($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-3]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-2]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-1]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports]));
        $params[] = (($rawdata["ChangeinAccountsReceivable"][$treports-3]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-2]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-1]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports]=='null')?null:($rawdata["ChangeinAccountsReceivable"][$treports-3]+$rawdata["ChangeinAccountsReceivable"][$treports-2]+$rawdata["ChangeinAccountsReceivable"][$treports-1]+$rawdata["ChangeinAccountsReceivable"][$treports]));
        $params[] = (($rawdata["InvestmentChangesNet"][$treports-3]=='null'&&$rawdata["InvestmentChangesNet"][$treports-2]=='null'&&$rawdata["InvestmentChangesNet"][$treports-1]=='null'&&$rawdata["InvestmentChangesNet"][$treports]=='null')?null:($rawdata["InvestmentChangesNet"][$treports-3]+$rawdata["InvestmentChangesNet"][$treports-2]+$rawdata["InvestmentChangesNet"][$treports-1]+$rawdata["InvestmentChangesNet"][$treports]));
        $params[] = ($rawdata["NetChangeinCash"][$MRQRow] == 'null'?null:$rawdata["NetChangeinCash"][$MRQRow]);
        $params[] = (($rawdata["OtherAdjustments"][$treports-3]=='null'&&$rawdata["OtherAdjustments"][$treports-2]=='null'&&$rawdata["OtherAdjustments"][$treports-1]=='null'&&$rawdata["OtherAdjustments"][$treports]=='null')?null:($rawdata["OtherAdjustments"][$treports-3]+$rawdata["OtherAdjustments"][$treports-2]+$rawdata["OtherAdjustments"][$treports-1]+$rawdata["OtherAdjustments"][$treports]));
        $params[] = (($rawdata["OtherAssetLiabilityChangesNet"][$treports-3]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-2]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-1]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports]=='null')?null:($rawdata["OtherAssetLiabilityChangesNet"][$treports-3]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-2]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-1]+$rawdata["OtherAssetLiabilityChangesNet"][$treports]));
        $params[] = (($rawdata["OtherFinancingActivitiesNet"][$treports-3]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-2]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-1]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports]=='null')?null:($rawdata["OtherFinancingActivitiesNet"][$treports-3]+$rawdata["OtherFinancingActivitiesNet"][$treports-2]+$rawdata["OtherFinancingActivitiesNet"][$treports-1]+$rawdata["OtherFinancingActivitiesNet"][$treports]));
        $params[] = (($rawdata["OtherInvestingActivities"][$treports-3]=='null'&&$rawdata["OtherInvestingActivities"][$treports-2]=='null'&&$rawdata["OtherInvestingActivities"][$treports-1]=='null'&&$rawdata["OtherInvestingActivities"][$treports]=='null')?null:($rawdata["OtherInvestingActivities"][$treports-3]+$rawdata["OtherInvestingActivities"][$treports-2]+$rawdata["OtherInvestingActivities"][$treports-1]+$rawdata["OtherInvestingActivities"][$treports]));
        $params[] = (($rawdata["RealizedGainsLosses"][$treports-3]=='null'&&$rawdata["RealizedGainsLosses"][$treports-2]=='null'&&$rawdata["RealizedGainsLosses"][$treports-1]=='null'&&$rawdata["RealizedGainsLosses"][$treports]=='null')?null:($rawdata["RealizedGainsLosses"][$treports-3]+$rawdata["RealizedGainsLosses"][$treports-2]+$rawdata["RealizedGainsLosses"][$treports-1]+$rawdata["RealizedGainsLosses"][$treports]));
        $params[] = (($rawdata["SaleofPropertyPlantEquipment"][$treports-3]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-2]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-1]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports]=='null')?null:($rawdata["SaleofPropertyPlantEquipment"][$treports-3]+$rawdata["SaleofPropertyPlantEquipment"][$treports-2]+$rawdata["SaleofPropertyPlantEquipment"][$treports-1]+$rawdata["SaleofPropertyPlantEquipment"][$treports]));
        $params[] = (($rawdata["StockOptionTaxBenefits"][$treports-3]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-2]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-1]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports]=='null')?null:($rawdata["StockOptionTaxBenefits"][$treports-3]+$rawdata["StockOptionTaxBenefits"][$treports-2]+$rawdata["StockOptionTaxBenefits"][$treports-1]+$rawdata["StockOptionTaxBenefits"][$treports]));
        $params[] = (($rawdata["TotalAdjustments"][$treports-3]=='null'&&$rawdata["TotalAdjustments"][$treports-2]=='null'&&$rawdata["TotalAdjustments"][$treports-1]=='null'&&$rawdata["TotalAdjustments"][$treports]=='null')?null:($rawdata["TotalAdjustments"][$treports-3]+$rawdata["TotalAdjustments"][$treports-2]+$rawdata["TotalAdjustments"][$treports-1]+$rawdata["TotalAdjustments"][$treports]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinCurrentAssets`=?, `ChangeinCurrentLiabilities`=?, `ChangeinDebtNet`=?, `ChangeinDeferredRevenue`=?, `ChangeinEquityNet`=?, `ChangeinIncomeTaxesPayable`=?, `ChangeinInventories`=?, `ChangeinOperatingAssetsLiabilities`=?, `ChangeinOtherAssets`=?, `ChangeinOtherCurrentAssets`=?, `ChangeinOtherCurrentLiabilities`=?, `ChangeinOtherLiabilities`=?, `ChangeinPrepaidExpenses`=?, `DividendsPaid`=?, `EffectofExchangeRateonCash`=?, `EmployeeCompensation`=?, `AcquisitionSaleofBusinessNet`=?, `AdjustmentforEquityEarnings`=?, `AdjustmentforMinorityInterest`=?, `AdjustmentforSpecialCharges`=?, `CapitalExpenditures`=?, `CashfromDiscontinuedOperations`=?, `CashfromFinancingActivities`=?, `CashfromInvestingActivities`=?, `CashfromOperatingActivities`=?, `CFDepreciationAmortization`=?, `DeferredIncomeTaxes`=?, `ChangeinAccountsPayableAccruedExpenses`=?, `ChangeinAccountsReceivable`=?, `InvestmentChangesNet`=?, `NetChangeinCash`=?, `OtherAdjustments`=?, `OtherAssetLiabilityChangesNet`=?, `OtherFinancingActivitiesNet`=?, `OtherInvestingActivities`=?, `RealizedGainsLosses`=?, `SaleofPropertyPlantEquipment`=?, `StockOptionTaxBenefits`=?, `TotalAdjustments`=?";
        $params = array();
        $params[] = (($rawdata["ChangeinCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-4]=='null')?null:($rawdata["ChangeinCurrentAssets"][$treports-7]+$rawdata["ChangeinCurrentAssets"][$treports-6]+$rawdata["ChangeinCurrentAssets"][$treports-5]+$rawdata["ChangeinCurrentAssets"][$treports-4]));
        $params[] = (($rawdata["ChangeinCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinCurrentLiabilities"][$treports-7]+$rawdata["ChangeinCurrentLiabilities"][$treports-6]+$rawdata["ChangeinCurrentLiabilities"][$treports-5]+$rawdata["ChangeinCurrentLiabilities"][$treports-4]));
        $params[] = (($rawdata["ChangeinDebtNet"][$treports-7]=='null'&&$rawdata["ChangeinDebtNet"][$treports-6]=='null'&&$rawdata["ChangeinDebtNet"][$treports-5]=='null'&&$rawdata["ChangeinDebtNet"][$treports-4]=='null')?null:($rawdata["ChangeinDebtNet"][$treports-7]+$rawdata["ChangeinDebtNet"][$treports-6]+$rawdata["ChangeinDebtNet"][$treports-5]+$rawdata["ChangeinDebtNet"][$treports-4]));
        $params[] = (($rawdata["ChangeinDeferredRevenue"][$treports-7]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-6]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-5]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-4]=='null')?null:($rawdata["ChangeinDeferredRevenue"][$treports-7]+$rawdata["ChangeinDeferredRevenue"][$treports-6]+$rawdata["ChangeinDeferredRevenue"][$treports-5]+$rawdata["ChangeinDeferredRevenue"][$treports-4]));
        $params[] = (($rawdata["ChangeinEquityNet"][$treports-7]=='null'&&$rawdata["ChangeinEquityNet"][$treports-6]=='null'&&$rawdata["ChangeinEquityNet"][$treports-5]=='null'&&$rawdata["ChangeinEquityNet"][$treports-4]=='null')?null:($rawdata["ChangeinEquityNet"][$treports-7]+$rawdata["ChangeinEquityNet"][$treports-6]+$rawdata["ChangeinEquityNet"][$treports-5]+$rawdata["ChangeinEquityNet"][$treports-4]));
        $params[] = (($rawdata["ChangeinIncomeTaxesPayable"][$treports-7]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-6]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-5]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-4]=='null')?null:($rawdata["ChangeinIncomeTaxesPayable"][$treports-7]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-6]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-5]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-4]));
        $params[] = (($rawdata["ChangeinInventories"][$treports-7]=='null'&&$rawdata["ChangeinInventories"][$treports-6]=='null'&&$rawdata["ChangeinInventories"][$treports-5]=='null'&&$rawdata["ChangeinInventories"][$treports-4]=='null')?null:($rawdata["ChangeinInventories"][$treports-7]+$rawdata["ChangeinInventories"][$treports-6]+$rawdata["ChangeinInventories"][$treports-5]+$rawdata["ChangeinInventories"][$treports-4]));
        $params[] = (($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-7]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-6]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-5]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-4]));
        $params[] = (($rawdata["ChangeinOtherAssets"][$treports-7]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-6]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-5]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-4]=='null')?null:($rawdata["ChangeinOtherAssets"][$treports-7]+$rawdata["ChangeinOtherAssets"][$treports-6]+$rawdata["ChangeinOtherAssets"][$treports-5]+$rawdata["ChangeinOtherAssets"][$treports-4]));
        $params[] = (($rawdata["ChangeinOtherCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-4]=='null')?null:($rawdata["ChangeinOtherCurrentAssets"][$treports-7]+$rawdata["ChangeinOtherCurrentAssets"][$treports-6]+$rawdata["ChangeinOtherCurrentAssets"][$treports-5]+$rawdata["ChangeinOtherCurrentAssets"][$treports-4]));
        $params[] = (($rawdata["ChangeinOtherCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinOtherCurrentLiabilities"][$treports-7]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-6]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-5]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-4]));
        $params[] = (($rawdata["ChangeinOtherLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinOtherLiabilities"][$treports-7]+$rawdata["ChangeinOtherLiabilities"][$treports-6]+$rawdata["ChangeinOtherLiabilities"][$treports-5]+$rawdata["ChangeinOtherLiabilities"][$treports-4]));
        $params[] = (($rawdata["ChangeinPrepaidExpenses"][$treports-7]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-6]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-5]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-4]=='null')?null:($rawdata["ChangeinPrepaidExpenses"][$treports-7]+$rawdata["ChangeinPrepaidExpenses"][$treports-6]+$rawdata["ChangeinPrepaidExpenses"][$treports-5]+$rawdata["ChangeinPrepaidExpenses"][$treports-4]));
        $params[] = (($rawdata["DividendsPaid"][$treports-7]=='null'&&$rawdata["DividendsPaid"][$treports-6]=='null'&&$rawdata["DividendsPaid"][$treports-5]=='null'&&$rawdata["DividendsPaid"][$treports-4]=='null')?null:($rawdata["DividendsPaid"][$treports-7]+$rawdata["DividendsPaid"][$treports-6]+$rawdata["DividendsPaid"][$treports-5]+$rawdata["DividendsPaid"][$treports-4]));
        $params[] = (($rawdata["EffectofExchangeRateonCash"][$treports-7]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-6]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-5]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-4]=='null')?null:($rawdata["EffectofExchangeRateonCash"][$treports-7]+$rawdata["EffectofExchangeRateonCash"][$treports-6]+$rawdata["EffectofExchangeRateonCash"][$treports-5]+$rawdata["EffectofExchangeRateonCash"][$treports-4]));
        $params[] = (($rawdata["EmployeeCompensation"][$treports-7]=='null'&&$rawdata["EmployeeCompensation"][$treports-6]=='null'&&$rawdata["EmployeeCompensation"][$treports-5]=='null'&&$rawdata["EmployeeCompensation"][$treports-4]=='null')?null:($rawdata["EmployeeCompensation"][$treports-7]+$rawdata["EmployeeCompensation"][$treports-6]+$rawdata["EmployeeCompensation"][$treports-5]+$rawdata["EmployeeCompensation"][$treports-4]));
        $params[] = (($rawdata["AcquisitionSaleofBusinessNet"][$treports-7]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-6]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-5]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-4]='null')?null:($rawdata["AcquisitionSaleofBusinessNet"][$treports-7]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-6]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-5]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-4]));
        $params[] = (($rawdata["AdjustmentforEquityEarnings"][$treports-7]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-6]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-5]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-4]=='null')?null:($rawdata["AdjustmentforEquityEarnings"][$treports-7]+$rawdata["AdjustmentforEquityEarnings"][$treports-6]+$rawdata["AdjustmentforEquityEarnings"][$treports-5]+$rawdata["AdjustmentforEquityEarnings"][$treports-4]));
        $params[] = (($rawdata["AdjustmentforMinorityInterest"][$treports-7]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-6]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-5]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-4]=='null')?null:($rawdata["AdjustmentforMinorityInterest"][$treports-7]+$rawdata["AdjustmentforMinorityInterest"][$treports-6]+$rawdata["AdjustmentforMinorityInterest"][$treports-5]+$rawdata["AdjustmentforMinorityInterest"][$treports-4]));
        $params[] = (($rawdata["AdjustmentforSpecialCharges"][$treports-7]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-6]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-5]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-4]=='null')?null:($rawdata["AdjustmentforSpecialCharges"][$treports-7]+$rawdata["AdjustmentforSpecialCharges"][$treports-6]+$rawdata["AdjustmentforSpecialCharges"][$treports-5]+$rawdata["AdjustmentforSpecialCharges"][$treports-4]));
        $params[] = (($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')?null:($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4]));
        $params[] = (($rawdata["CashfromDiscontinuedOperations"][$treports-7]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-6]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-5]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-4]=='null')?null:($rawdata["CashfromDiscontinuedOperations"][$treports-7]+$rawdata["CashfromDiscontinuedOperations"][$treports-6]+$rawdata["CashfromDiscontinuedOperations"][$treports-5]+$rawdata["CashfromDiscontinuedOperations"][$treports-4]));
        $params[] = (($rawdata["CashfromFinancingActivities"][$treports-7]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-6]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-5]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-4]=='null')?null:($rawdata["CashfromFinancingActivities"][$treports-7]+$rawdata["CashfromFinancingActivities"][$treports-6]+$rawdata["CashfromFinancingActivities"][$treports-5]+$rawdata["CashfromFinancingActivities"][$treports-4]));
        $params[] = (($rawdata["CashfromInvestingActivities"][$treports-7]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-6]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-5]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-4]=='null')?null:($rawdata["CashfromInvestingActivities"][$treports-7]+$rawdata["CashfromInvestingActivities"][$treports-6]+$rawdata["CashfromInvestingActivities"][$treports-5]+$rawdata["CashfromInvestingActivities"][$treports-4]));
        $params[] = (($rawdata["CashfromOperatingActivities"][$treports-7]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-6]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-5]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-4]=='null')?null:($rawdata["CashfromOperatingActivities"][$treports-7]+$rawdata["CashfromOperatingActivities"][$treports-6]+$rawdata["CashfromOperatingActivities"][$treports-5]+$rawdata["CashfromOperatingActivities"][$treports-4]));
        $params[] = (($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')?null:($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4]));
        $params[] = (($rawdata["DeferredIncomeTaxes"][$treports-7]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-6]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-5]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-4]=='null')?null:($rawdata["DeferredIncomeTaxes"][$treports-7]+$rawdata["DeferredIncomeTaxes"][$treports-6]+$rawdata["DeferredIncomeTaxes"][$treports-5]+$rawdata["DeferredIncomeTaxes"][$treports-4]));
        $params[] = (($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-7]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-6]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-5]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-4]=='null')?null:($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-7]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-6]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-5]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-4]));
        $params[] = (($rawdata["ChangeinAccountsReceivable"][$treports-7]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-6]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-5]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-4]=='null')?null:($rawdata["ChangeinAccountsReceivable"][$treports-7]+$rawdata["ChangeinAccountsReceivable"][$treports-6]+$rawdata["ChangeinAccountsReceivable"][$treports-5]+$rawdata["ChangeinAccountsReceivable"][$treports-4]));
        $params[] = (($rawdata["InvestmentChangesNet"][$treports-7]=='null'&&$rawdata["InvestmentChangesNet"][$treports-6]=='null'&&$rawdata["InvestmentChangesNet"][$treports-5]=='null'&&$rawdata["InvestmentChangesNet"][$treports-4]=='null')?null:($rawdata["InvestmentChangesNet"][$treports-7]+$rawdata["InvestmentChangesNet"][$treports-6]+$rawdata["InvestmentChangesNet"][$treports-5]+$rawdata["InvestmentChangesNet"][$treports-4]));
        $params[] = ($rawdata["NetChangeinCash"][$PMRQRow] == 'null'?null:$rawdata["NetChangeinCash"][$PMRQRow]);
        $params[] = (($rawdata["OtherAdjustments"][$treports-7]=='null'&&$rawdata["OtherAdjustments"][$treports-6]=='null'&&$rawdata["OtherAdjustments"][$treports-5]=='null'&&$rawdata["OtherAdjustments"][$treports-4]=='null')?null:($rawdata["OtherAdjustments"][$treports-7]+$rawdata["OtherAdjustments"][$treports-6]+$rawdata["OtherAdjustments"][$treports-5]+$rawdata["OtherAdjustments"][$treports-4]));
        $params[] = (($rawdata["OtherAssetLiabilityChangesNet"][$treports-7]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-6]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-5]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-4]=='null')?null:($rawdata["OtherAssetLiabilityChangesNet"][$treports-7]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-6]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-5]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-4]));
        $params[] = (($rawdata["OtherFinancingActivitiesNet"][$treports-7]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-6]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-5]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-4]=='null')?null:($rawdata["OtherFinancingActivitiesNet"][$treports-7]+$rawdata["OtherFinancingActivitiesNet"][$treports-6]+$rawdata["OtherFinancingActivitiesNet"][$treports-5]+$rawdata["OtherFinancingActivitiesNet"][$treports-4]));
        $params[] = (($rawdata["OtherInvestingActivities"][$treports-7]=='null'&&$rawdata["OtherInvestingActivities"][$treports-6]=='null'&&$rawdata["OtherInvestingActivities"][$treports-5]=='null'&&$rawdata["OtherInvestingActivities"][$treports-4]=='null')?null:($rawdata["OtherInvestingActivities"][$treports-7]+$rawdata["OtherInvestingActivities"][$treports-6]+$rawdata["OtherInvestingActivities"][$treports-5]+$rawdata["OtherInvestingActivities"][$treports-4]));
        $params[] = (($rawdata["RealizedGainsLosses"][$treports-7]=='null'&&$rawdata["RealizedGainsLosses"][$treports-6]=='null'&&$rawdata["RealizedGainsLosses"][$treports-5]=='null'&&$rawdata["RealizedGainsLosses"][$treports-4]=='null')?null:($rawdata["RealizedGainsLosses"][$treports-7]+$rawdata["RealizedGainsLosses"][$treports-6]+$rawdata["RealizedGainsLosses"][$treports-5]+$rawdata["RealizedGainsLosses"][$treports-4]));
        $params[] = (($rawdata["SaleofPropertyPlantEquipment"][$treports-7]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-6]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-5]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-4]=='null')?null:($rawdata["SaleofPropertyPlantEquipment"][$treports-7]+$rawdata["SaleofPropertyPlantEquipment"][$treports-6]+$rawdata["SaleofPropertyPlantEquipment"][$treports-5]+$rawdata["SaleofPropertyPlantEquipment"][$treports-4]));
        $params[] = (($rawdata["StockOptionTaxBenefits"][$treports-7]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-6]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-5]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-4]=='null')?null:($rawdata["StockOptionTaxBenefits"][$treports-7]+$rawdata["StockOptionTaxBenefits"][$treports-6]+$rawdata["StockOptionTaxBenefits"][$treports-5]+$rawdata["StockOptionTaxBenefits"][$treports-4]));
        $params[] = (($rawdata["TotalAdjustments"][$treports-7]=='null'&&$rawdata["TotalAdjustments"][$treports-6]=='null'&&$rawdata["TotalAdjustments"][$treports-5]=='null'&&$rawdata["TotalAdjustments"][$treports-4]=='null')?null:($rawdata["TotalAdjustments"][$treports-7]+$rawdata["TotalAdjustments"][$treports-6]+$rawdata["TotalAdjustments"][$treports-5]+$rawdata["TotalAdjustments"][$treports-4]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinLongtermDebtNet`=?, `ChangeinShorttermBorrowingsNet`=?, `CashandCashEquivalentsBeginningofYear`=?, `CashandCashEquivalentsEndofYear`=?, `CashPaidforIncomeTaxes`=?, `CashPaidforInterestExpense`=?, `CFNetIncome`=?, `IssuanceofEquity`=?, `LongtermDebtPayments`=?, `LongtermDebtProceeds`=?, `OtherDebtNet`=?, `OtherEquityTransactionsNet`=?, `OtherInvestmentChangesNet`=?, `PurchaseofInvestments`=?, `RepurchaseofEquity`=?, `SaleofInvestments`=?, `ShorttermBorrowings`=?, `TotalNoncashAdjustments`=?";
        $params = array();
        $params[] = (($rawdata["ChangeinLongtermDebtNet"][$treports-3]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-2]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-1]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports]=='null')?null:($rawdata["ChangeinLongtermDebtNet"][$treports-3]+$rawdata["ChangeinLongtermDebtNet"][$treports-2]+$rawdata["ChangeinLongtermDebtNet"][$treports-1]+$rawdata["ChangeinLongtermDebtNet"][$treports]));
        $params[] = (($rawdata["ChangeinShorttermBorrowingsNet"][$treports-3]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-2]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-1]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports]=='null')?null:($rawdata["ChangeinShorttermBorrowingsNet"][$treports-3]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-2]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-1]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports]));
        $params[] = (($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-3]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-2]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-1]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports]=='null')?null:($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-3]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-2]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-1]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports]));
        $params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$MRQRow] =='null' ? null:$rawdata["CashandCashEquivalentsEndofYear"][$MRQRow]);
        $params[] = (($rawdata["CashPaidforIncomeTaxes"][$treports-3]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-2]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-1]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports]=='null')?null:($rawdata["CashPaidforIncomeTaxes"][$treports-3]+$rawdata["CashPaidforIncomeTaxes"][$treports-2]+$rawdata["CashPaidforIncomeTaxes"][$treports-1]+$rawdata["CashPaidforIncomeTaxes"][$treports]));
        $params[] = (($rawdata["CashPaidforInterestExpense"][$treports-3]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-2]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-1]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports]=='null')?null:($rawdata["CashPaidforInterestExpense"][$treports-3]+$rawdata["CashPaidforInterestExpense"][$treports-2]+$rawdata["CashPaidforInterestExpense"][$treports-1]+$rawdata["CashPaidforInterestExpense"][$treports]));
        $params[] = (($rawdata["CFNetIncome"][$treports-3]=='null'&&$rawdata["CFNetIncome"][$treports-2]=='null'&&$rawdata["CFNetIncome"][$treports-1]=='null'&&$rawdata["CFNetIncome"][$treports]=='null')?null:($rawdata["CFNetIncome"][$treports-3]+$rawdata["CFNetIncome"][$treports-2]+$rawdata["CFNetIncome"][$treports-1]+$rawdata["CFNetIncome"][$treports]));
        $params[] = (($rawdata["IssuanceofEquity"][$treports-3]=='null'&&$rawdata["IssuanceofEquity"][$treports-2]=='null'&&$rawdata["IssuanceofEquity"][$treports-1]=='null'&&$rawdata["IssuanceofEquity"][$treports]=='null')?null:($rawdata["IssuanceofEquity"][$treports-3]+$rawdata["IssuanceofEquity"][$treports-2]+$rawdata["IssuanceofEquity"][$treports-1]+$rawdata["IssuanceofEquity"][$treports]));
        $params[] = (($rawdata["LongtermDebtPayments"][$treports-3]=='null'&&$rawdata["LongtermDebtPayments"][$treports-2]=='null'&&$rawdata["LongtermDebtPayments"][$treports-1]=='null'&&$rawdata["LongtermDebtPayments"][$treports]=='null')?null:($rawdata["LongtermDebtPayments"][$treports-3]+$rawdata["LongtermDebtPayments"][$treports-2]+$rawdata["LongtermDebtPayments"][$treports-1]+$rawdata["LongtermDebtPayments"][$treports]));
        $params[] = (($rawdata["LongtermDebtProceeds"][$treports-3]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-2]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-1]=='null'&&$rawdata["LongtermDebtProceeds"][$treports]=='null')?null:($rawdata["LongtermDebtProceeds"][$treports-3]+$rawdata["LongtermDebtProceeds"][$treports-2]+$rawdata["LongtermDebtProceeds"][$treports-1]+$rawdata["LongtermDebtProceeds"][$treports]));
        $params[] = (($rawdata["OtherDebtNet"][$treports-3]=='null'&&$rawdata["OtherDebtNet"][$treports-2]=='null'&&$rawdata["OtherDebtNet"][$treports-1]=='null'&&$rawdata["OtherDebtNet"][$treports]=='null')?null:($rawdata["OtherDebtNet"][$treports-3]+$rawdata["OtherDebtNet"][$treports-2]+$rawdata["OtherDebtNet"][$treports-1]+$rawdata["OtherDebtNet"][$treports]));
        $params[] = (($rawdata["OtherEquityTransactionsNet"][$treports-3]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-2]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-1]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports]=='null')?null:($rawdata["OtherEquityTransactionsNet"][$treports-3]+$rawdata["OtherEquityTransactionsNet"][$treports-2]+$rawdata["OtherEquityTransactionsNet"][$treports-1]+$rawdata["OtherEquityTransactionsNet"][$treports]));
        $params[] = (($rawdata["OtherInvestmentChangesNet"][$treports-3]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-2]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-1]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports]=='null')?null:($rawdata["OtherInvestmentChangesNet"][$treports-3]+$rawdata["OtherInvestmentChangesNet"][$treports-2]+$rawdata["OtherInvestmentChangesNet"][$treports-1]+$rawdata["OtherInvestmentChangesNet"][$treports]));
        $params[] = (($rawdata["PurchaseofInvestments"][$treports-3]=='null'&&$rawdata["PurchaseofInvestments"][$treports-2]=='null'&&$rawdata["PurchaseofInvestments"][$treports-1]=='null'&&$rawdata["PurchaseofInvestments"][$treports]=='null')?null:($rawdata["PurchaseofInvestments"][$treports-3]+$rawdata["PurchaseofInvestments"][$treports-2]+$rawdata["PurchaseofInvestments"][$treports-1]+$rawdata["PurchaseofInvestments"][$treports]));
        $params[] = (($rawdata["RepurchaseofEquity"][$treports-3]=='null'&&$rawdata["RepurchaseofEquity"][$treports-2]=='null'&&$rawdata["RepurchaseofEquity"][$treports-1]=='null'&&$rawdata["RepurchaseofEquity"][$treports]=='null')?null:($rawdata["RepurchaseofEquity"][$treports-3]+$rawdata["RepurchaseofEquity"][$treports-2]+$rawdata["RepurchaseofEquity"][$treports-1]+$rawdata["RepurchaseofEquity"][$treports]));
        $params[] = (($rawdata["SaleofInvestments"][$treports-3]=='null'&&$rawdata["SaleofInvestments"][$treports-2]=='null'&&$rawdata["SaleofInvestments"][$treports-1]=='null'&&$rawdata["SaleofInvestments"][$treports]=='null')?null:($rawdata["SaleofInvestments"][$treports-3]+$rawdata["SaleofInvestments"][$treports-2]+$rawdata["SaleofInvestments"][$treports-1]+$rawdata["SaleofInvestments"][$treports]));
        $params[] = (($rawdata["ShorttermBorrowings"][$treports-3]=='null'&&$rawdata["ShorttermBorrowings"][$treports-2]=='null'&&$rawdata["ShorttermBorrowings"][$treports-1]=='null'&&$rawdata["ShorttermBorrowings"][$treports]=='null')?null:($rawdata["ShorttermBorrowings"][$treports-3]+$rawdata["ShorttermBorrowings"][$treports-2]+$rawdata["ShorttermBorrowings"][$treports-1]+$rawdata["ShorttermBorrowings"][$treports]));
        $params[] = (($rawdata["TotalNoncashAdjustments"][$treports-3]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-2]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-1]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports]=='null')?null:($rawdata["TotalNoncashAdjustments"][$treports-3]+$rawdata["TotalNoncashAdjustments"][$treports-2]+$rawdata["TotalNoncashAdjustments"][$treports-1]+$rawdata["TotalNoncashAdjustments"][$treports]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ChangeinLongtermDebtNet`=?, `ChangeinShorttermBorrowingsNet`=?, `CashandCashEquivalentsBeginningofYear`=?, `CashandCashEquivalentsEndofYear`=?, `CashPaidforIncomeTaxes`=?, `CashPaidforInterestExpense`=?, `CFNetIncome`=?, `IssuanceofEquity`=?, `LongtermDebtPayments`=?, `LongtermDebtProceeds`=?, `OtherDebtNet`=?, `OtherEquityTransactionsNet`=?, `OtherInvestmentChangesNet`=?, `PurchaseofInvestments`=?, `RepurchaseofEquity`=?, `SaleofInvestments`=?, `ShorttermBorrowings`=?, `TotalNoncashAdjustments`=?";
        $params = array();
        $params[] = (($rawdata["ChangeinLongtermDebtNet"][$treports-7]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-6]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-5]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-4]=='null')?null:($rawdata["ChangeinLongtermDebtNet"][$treports-7]+$rawdata["ChangeinLongtermDebtNet"][$treports-6]+$rawdata["ChangeinLongtermDebtNet"][$treports-5]+$rawdata["ChangeinLongtermDebtNet"][$treports-4]));
        $params[] = (($rawdata["ChangeinShorttermBorrowingsNet"][$treports-7]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-6]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-5]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-4]=='null')?null:($rawdata["ChangeinShorttermBorrowingsNet"][$treports-7]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-6]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-5]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-4]));
        $params[] = (($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-7]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-6]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-5]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-4]=='null')?null:($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-7]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-6]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-5]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-4]));
        $params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow] =='null' ? null:$rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow]);
        $params[] = (($rawdata["CashPaidforIncomeTaxes"][$treports-7]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-6]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-5]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-4]=='null')?null:($rawdata["CashPaidforIncomeTaxes"][$treports-7]+$rawdata["CashPaidforIncomeTaxes"][$treports-6]+$rawdata["CashPaidforIncomeTaxes"][$treports-5]+$rawdata["CashPaidforIncomeTaxes"][$treports-4]));
        $params[] = (($rawdata["CashPaidforInterestExpense"][$treports-7]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-6]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-5]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-4]=='null')?null:($rawdata["CashPaidforInterestExpense"][$treports-7]+$rawdata["CashPaidforInterestExpense"][$treports-6]+$rawdata["CashPaidforInterestExpense"][$treports-5]+$rawdata["CashPaidforInterestExpense"][$treports-4]));
        $params[] = (($rawdata["CFNetIncome"][$treports-7]=='null'&&$rawdata["CFNetIncome"][$treports-6]=='null'&&$rawdata["CFNetIncome"][$treports-5]=='null'&&$rawdata["CFNetIncome"][$treports-4]=='null')?null:($rawdata["CFNetIncome"][$treports-7]+$rawdata["CFNetIncome"][$treports-6]+$rawdata["CFNetIncome"][$treports-5]+$rawdata["CFNetIncome"][$treports-4]));
        $params[] = (($rawdata["IssuanceofEquity"][$treports-7]=='null'&&$rawdata["IssuanceofEquity"][$treports-6]=='null'&&$rawdata["IssuanceofEquity"][$treports-5]=='null'&&$rawdata["IssuanceofEquity"][$treports-4]=='null')?null:($rawdata["IssuanceofEquity"][$treports-7]+$rawdata["IssuanceofEquity"][$treports-6]+$rawdata["IssuanceofEquity"][$treports-5]+$rawdata["IssuanceofEquity"][$treports-4]));
        $params[] = (($rawdata["LongtermDebtPayments"][$treports-7]=='null'&&$rawdata["LongtermDebtPayments"][$treports-6]=='null'&&$rawdata["LongtermDebtPayments"][$treports-5]=='null'&&$rawdata["LongtermDebtPayments"][$treports-4]=='null')?null:($rawdata["LongtermDebtPayments"][$treports-7]+$rawdata["LongtermDebtPayments"][$treports-6]+$rawdata["LongtermDebtPayments"][$treports-5]+$rawdata["LongtermDebtPayments"][$treports-4]));
        $params[] = (($rawdata["LongtermDebtProceeds"][$treports-7]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-6]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-5]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-4]=='null')?null:($rawdata["LongtermDebtProceeds"][$treports-7]+$rawdata["LongtermDebtProceeds"][$treports-6]+$rawdata["LongtermDebtProceeds"][$treports-5]+$rawdata["LongtermDebtProceeds"][$treports-4]));
        $params[] = (($rawdata["OtherDebtNet"][$treports-7]=='null'&&$rawdata["OtherDebtNet"][$treports-6]=='null'&&$rawdata["OtherDebtNet"][$treports-5]=='null'&&$rawdata["OtherDebtNet"][$treports-4]=='null')?null:($rawdata["OtherDebtNet"][$treports-7]+$rawdata["OtherDebtNet"][$treports-6]+$rawdata["OtherDebtNet"][$treports-5]+$rawdata["OtherDebtNet"][$treports-4]));
        $params[] = (($rawdata["OtherEquityTransactionsNet"][$treports-7]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-6]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-5]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-4]=='null')?null:($rawdata["OtherEquityTransactionsNet"][$treports-7]+$rawdata["OtherEquityTransactionsNet"][$treports-6]+$rawdata["OtherEquityTransactionsNet"][$treports-5]+$rawdata["OtherEquityTransactionsNet"][$treports-4]));
        $params[] = (($rawdata["OtherInvestmentChangesNet"][$treports-7]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-6]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-5]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-4]=='null')?null:($rawdata["OtherInvestmentChangesNet"][$treports-7]+$rawdata["OtherInvestmentChangesNet"][$treports-6]+$rawdata["OtherInvestmentChangesNet"][$treports-5]+$rawdata["OtherInvestmentChangesNet"][$treports-4]));
        $params[] = (($rawdata["PurchaseofInvestments"][$treports-7]=='null'&&$rawdata["PurchaseofInvestments"][$treports-6]=='null'&&$rawdata["PurchaseofInvestments"][$treports-5]=='null'&&$rawdata["PurchaseofInvestments"][$treports-4]=='null')?null:($rawdata["PurchaseofInvestments"][$treports-7]+$rawdata["PurchaseofInvestments"][$treports-6]+$rawdata["PurchaseofInvestments"][$treports-5]+$rawdata["PurchaseofInvestments"][$treports-4]));
        $params[] = (($rawdata["RepurchaseofEquity"][$treports-7]=='null'&&$rawdata["RepurchaseofEquity"][$treports-6]=='null'&&$rawdata["RepurchaseofEquity"][$treports-5]=='null'&&$rawdata["RepurchaseofEquity"][$treports-4]=='null')?null:($rawdata["RepurchaseofEquity"][$treports-7]+$rawdata["RepurchaseofEquity"][$treports-6]+$rawdata["RepurchaseofEquity"][$treports-5]+$rawdata["RepurchaseofEquity"][$treports-4]));
        $params[] = (($rawdata["SaleofInvestments"][$treports-7]=='null'&&$rawdata["SaleofInvestments"][$treports-6]=='null'&&$rawdata["SaleofInvestments"][$treports-5]=='null'&&$rawdata["SaleofInvestments"][$treports-4]=='null')?null:($rawdata["SaleofInvestments"][$treports-7]+$rawdata["SaleofInvestments"][$treports-6]+$rawdata["SaleofInvestments"][$treports-5]+$rawdata["SaleofInvestments"][$treports-4]));
        $params[] = (($rawdata["ShorttermBorrowings"][$treports-7]=='null'&&$rawdata["ShorttermBorrowings"][$treports-6]=='null'&&$rawdata["ShorttermBorrowings"][$treports-5]=='null'&&$rawdata["ShorttermBorrowings"][$treports-4]=='null')?null:($rawdata["ShorttermBorrowings"][$treports-7]+$rawdata["ShorttermBorrowings"][$treports-6]+$rawdata["ShorttermBorrowings"][$treports-5]+$rawdata["ShorttermBorrowings"][$treports-4]));
        $params[] = (($rawdata["TotalNoncashAdjustments"][$treports-7]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-6]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-5]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-4]=='null')?null:($rawdata["TotalNoncashAdjustments"][$treports-7]+$rawdata["TotalNoncashAdjustments"][$treports-6]+$rawdata["TotalNoncashAdjustments"][$treports-5]+$rawdata["TotalNoncashAdjustments"][$treports-4]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `EBIT`=?, `CostofRevenue`=?, `DepreciationAmortizationExpense`=?, `DilutedEPSNetIncome`=?, `DiscontinuedOperations`=?, `EquityEarnings`=?, `AccountingChange`=?, `BasicEPSNetIncome`=?, `ExtraordinaryItems`=?, `GrossProfit`=?, `IncomebeforeExtraordinaryItems`=?, `IncomeBeforeTaxes`=?, `IncomeTaxes`=?, `InterestExpense`=?, `InterestIncome`=?, `MinorityInterestEquityEarnings`=?, `NetIncome`=?, `NetIncomeApplicabletoCommon`=?, `OperatingProfit`=?, `OtherNonoperatingIncomeExpense`=?, `OtherOperatingExpenses`=?, `ResearchDevelopmentExpense`=?, `RestructuringRemediationImpairmentProvisions`=?, `TotalRevenue`=?, `SellingGeneralAdministrativeExpenses`=?";
        $params = array();
        $params[] = (($rawdata["EBIT"][$treports-3]=='null'&&$rawdata["EBIT"][$treports-2]=='null'&&$rawdata["EBIT"][$treports-1]=='null'&&$rawdata["EBIT"][$treports]=='null')?null:($rawdata["EBIT"][$treports-3]+$rawdata["EBIT"][$treports-2]+$rawdata["EBIT"][$treports-1]+$rawdata["EBIT"][$treports]));
        $params[] = (($rawdata["CostofRevenue"][$treports-3]=='null'&&$rawdata["CostofRevenue"][$treports-2]=='null'&&$rawdata["CostofRevenue"][$treports-1]=='null'&&$rawdata["CostofRevenue"][$treports]=='null')?null:($rawdata["CostofRevenue"][$treports-3]+$rawdata["CostofRevenue"][$treports-2]+$rawdata["CostofRevenue"][$treports-1]+$rawdata["CostofRevenue"][$treports]));
        $params[] = (($rawdata["DepreciationAmortizationExpense"][$treports-3]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-2]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-1]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports]=='null')?null:($rawdata["DepreciationAmortizationExpense"][$treports-3]+$rawdata["DepreciationAmortizationExpense"][$treports-2]+$rawdata["DepreciationAmortizationExpense"][$treports-1]+$rawdata["DepreciationAmortizationExpense"][$treports]));
        $params[] = (($rawdata["DilutedEPSNetIncome"][$treports-3]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-2]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-1]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports]=='null')?null:($rawdata["DilutedEPSNetIncome"][$treports-3]+$rawdata["DilutedEPSNetIncome"][$treports-2]+$rawdata["DilutedEPSNetIncome"][$treports-1]+$rawdata["DilutedEPSNetIncome"][$treports]));
        $params[] = (($rawdata["DiscontinuedOperations"][$treports-3]=='null'&&$rawdata["DiscontinuedOperations"][$treports-2]=='null'&&$rawdata["DiscontinuedOperations"][$treports-1]=='null'&&$rawdata["DiscontinuedOperations"][$treports]=='null')?null:($rawdata["DiscontinuedOperations"][$treports-3]+$rawdata["DiscontinuedOperations"][$treports-2]+$rawdata["DiscontinuedOperations"][$treports-1]+$rawdata["DiscontinuedOperations"][$treports-3]));
        $params[] = (($rawdata["EquityEarnings"][$treports-3]=='null'&&$rawdata["EquityEarnings"][$treports-2]=='null'&&$rawdata["EquityEarnings"][$treports-1]=='null'&&$rawdata["EquityEarnings"][$treports]=='null')?null:($rawdata["EquityEarnings"][$treports-3]+$rawdata["EquityEarnings"][$treports-2]+$rawdata["EquityEarnings"][$treports-1]+$rawdata["EquityEarnings"][$treports]));
        $params[] = (($rawdata["AccountingChange"][$treports-3]=='null'&&$rawdata["AccountingChange"][$treports-2]=='null'&&$rawdata["AccountingChange"][$treports-1]=='null'&&$rawdata["AccountingChange"][$treports]=='null')?null:($rawdata["AccountingChange"][$treports-3]+$rawdata["AccountingChange"][$treports-2]+$rawdata["AccountingChange"][$treports-1]+$rawdata["AccountingChange"][$treports]));
        $params[] = (($rawdata["BasicEPSNetIncome"][$treports-3]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-2]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-1]=='null'&&$rawdata["BasicEPSNetIncome"][$treports]=='null')?null:($rawdata["BasicEPSNetIncome"][$treports-3]+$rawdata["BasicEPSNetIncome"][$treports-2]+$rawdata["BasicEPSNetIncome"][$treports-1]+$rawdata["BasicEPSNetIncome"][$treports]));
        $params[] = (($rawdata["ExtraordinaryItems"][$treports-3]=='null'&&$rawdata["ExtraordinaryItems"][$treports-2]=='null'&&$rawdata["ExtraordinaryItems"][$treports-1]=='null'&&$rawdata["ExtraordinaryItems"][$treports]=='null')?null:($rawdata["ExtraordinaryItems"][$treports-3]+$rawdata["ExtraordinaryItems"][$treports-2]+$rawdata["ExtraordinaryItems"][$treports-1]+$rawdata["ExtraordinaryItems"][$treports]));
        $params[] = (($rawdata["GrossProfit"][$treports-3]=='null'&&$rawdata["GrossProfit"][$treports-2]=='null'&&$rawdata["GrossProfit"][$treports-1]=='null'&&$rawdata["GrossProfit"][$treports]=='null')?null:($rawdata["GrossProfit"][$treports-3]+$rawdata["GrossProfit"][$treports-2]+$rawdata["GrossProfit"][$treports-1]+$rawdata["GrossProfit"][$treports]));
        $params[] = (($rawdata["IncomebeforeExtraordinaryItems"][$treports-3]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-2]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-1]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports]=='null')?null:($rawdata["IncomebeforeExtraordinaryItems"][$treports-3]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-2]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-1]+$rawdata["IncomebeforeExtraordinaryItems"][$treports]));
        $params[] = (($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null')?null:($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports]));
        $params[] = (($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')?null:($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports]));
        $params[] = (($rawdata["InterestExpense"][$treports-3]=='null'&&$rawdata["InterestExpense"][$treports-2]=='null'&&$rawdata["InterestExpense"][$treports-1]=='null'&&$rawdata["InterestExpense"][$treports]=='null')?null:(toFloat($rawdata["InterestExpense"][$treports-3])+toFloat($rawdata["InterestExpense"][$treports-2])+toFloat($rawdata["InterestExpense"][$treports-1])+toFloat($rawdata["InterestExpense"][$treports])));
        $params[] = (($rawdata["InterestIncome"][$treports-3]=='null'&&$rawdata["InterestIncome"][$treports-2]=='null'&&$rawdata["InterestIncome"][$treports-1]=='null'&&$rawdata["InterestIncome"][$treports]=='null')?null:(toFloat($rawdata["InterestIncome"][$treports-3])+toFloat($rawdata["InterestIncome"][$treports-2])+toFloat($rawdata["InterestIncome"][$treports-1])+toFloat($rawdata["InterestIncome"][$treports])));
        $params[] = (($rawdata["MinorityInterestEquityEarnings"][$treports-3]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-2]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-1]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports]=='null')?null:($rawdata["MinorityInterestEquityEarnings"][$treports-3]+$rawdata["MinorityInterestEquityEarnings"][$treports-2]+$rawdata["MinorityInterestEquityEarnings"][$treports-1]+$rawdata["MinorityInterestEquityEarnings"][$treports]));
        $params[] = (($rawdata["NetIncome"][$treports-3]=='null'&&$rawdata["NetIncome"][$treports-2]=='null'&&$rawdata["NetIncome"][$treports-1]=='null'&&$rawdata["NetIncome"][$treports]=='null')?null:($rawdata["NetIncome"][$treports-3]+$rawdata["NetIncome"][$treports-2]+$rawdata["NetIncome"][$treports-1]+$rawdata["NetIncome"][$treports]));
        $params[] = (($rawdata["NetIncomeApplicabletoCommon"][$treports-3]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-2]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-1]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports]=='null')?null:($rawdata["NetIncomeApplicabletoCommon"][$treports-3]+$rawdata["NetIncomeApplicabletoCommon"][$treports-2]+$rawdata["NetIncomeApplicabletoCommon"][$treports-1]+$rawdata["NetIncomeApplicabletoCommon"][$treports]));
        $params[] = (($rawdata["OperatingProfit"][$treports-3]=='null'&&$rawdata["OperatingProfit"][$treports-2]=='null'&&$rawdata["OperatingProfit"][$treports-1]=='null'&&$rawdata["OperatingProfit"][$treports]=='null')?null:($rawdata["OperatingProfit"][$treports-3]+$rawdata["OperatingProfit"][$treports-2]+$rawdata["OperatingProfit"][$treports-1]+$rawdata["OperatingProfit"][$treports]));
        $params[] = (($rawdata["OtherNonoperatingIncomeExpense"][$treports-3]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-2]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-1]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports]=='null')?null:($rawdata["OtherNonoperatingIncomeExpense"][$treports-3]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-2]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-1]+$rawdata["OtherNonoperatingIncomeExpense"][$treports]));
        $params[] = (($rawdata["OtherOperatingExpenses"][$treports-3]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-2]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-1]=='null'&&$rawdata["OtherOperatingExpenses"][$treports]=='null')?null:($rawdata["OtherOperatingExpenses"][$treports-3]+$rawdata["OtherOperatingExpenses"][$treports-2]+$rawdata["OtherOperatingExpenses"][$treports-1]+$rawdata["OtherOperatingExpenses"][$treports]));
        $params[] = (($rawdata["ResearchDevelopmentExpense"][$treports-3]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-2]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-1]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports]=='null')?null:($rawdata["ResearchDevelopmentExpense"][$treports-3]+$rawdata["ResearchDevelopmentExpense"][$treports-2]+$rawdata["ResearchDevelopmentExpense"][$treports-1]+$rawdata["ResearchDevelopmentExpense"][$treports]));
        $params[] = (($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-3]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-2]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-1]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports]=='null')?null:($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-3]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-2]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-1]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports]));
        $params[] = (($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')?null:($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]));
        $params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports]=='null')?null:($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `EBIT`=?, `CostofRevenue`=?, `DepreciationAmortizationExpense`=?, `DilutedEPSNetIncome`=?, `DiscontinuedOperations`=?, `EquityEarnings`=?, `AccountingChange`=?, `BasicEPSNetIncome`=?, `ExtraordinaryItems`=?, `GrossProfit`=?, `IncomebeforeExtraordinaryItems`=?, `IncomeBeforeTaxes`=?, `IncomeTaxes`=?, `InterestExpense`=?, `InterestIncome`=?, `MinorityInterestEquityEarnings`=?, `NetIncome`=?, `NetIncomeApplicabletoCommon`=?, `OperatingProfit`=?, `OtherNonoperatingIncomeExpense`=?, `OtherOperatingExpenses`=?, `ResearchDevelopmentExpense`=?, `RestructuringRemediationImpairmentProvisions`=?, `TotalRevenue`=?, `SellingGeneralAdministrativeExpenses`=?";
        $params = array();
        $params[] = (($rawdata["EBIT"][$treports-7]=='null'&&$rawdata["EBIT"][$treports-6]=='null'&&$rawdata["EBIT"][$treports-5]=='null'&&$rawdata["EBIT"][$treports-4]=='null')?null:($rawdata["EBIT"][$treports-7]+$rawdata["EBIT"][$treports-6]+$rawdata["EBIT"][$treports-5]+$rawdata["EBIT"][$treports-4]));
        $params[] = (($rawdata["CostofRevenue"][$treports-7]=='null'&&$rawdata["CostofRevenue"][$treports-6]=='null'&&$rawdata["CostofRevenue"][$treports-5]=='null'&&$rawdata["CostofRevenue"][$treports-4]=='null')?null:($rawdata["CostofRevenue"][$treports-7]+$rawdata["CostofRevenue"][$treports-6]+$rawdata["CostofRevenue"][$treports-5]+$rawdata["CostofRevenue"][$treports-4]));
        $params[] = (($rawdata["DepreciationAmortizationExpense"][$treports-7]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-6]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-5]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-4]=='null')?null:($rawdata["DepreciationAmortizationExpense"][$treports-7]+$rawdata["DepreciationAmortizationExpense"][$treports-6]+$rawdata["DepreciationAmortizationExpense"][$treports-5]+$rawdata["DepreciationAmortizationExpense"][$treports-4]));
        $params[] = (($rawdata["DilutedEPSNetIncome"][$treports-7]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-6]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-5]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-4]=='null')?null:($rawdata["DilutedEPSNetIncome"][$treports-7]+$rawdata["DilutedEPSNetIncome"][$treports-6]+$rawdata["DilutedEPSNetIncome"][$treports-5]+$rawdata["DilutedEPSNetIncome"][$treports-4]));
        $params[] = (($rawdata["DiscontinuedOperations"][$treports-7]=='null'&&$rawdata["DiscontinuedOperations"][$treports-6]=='null'&&$rawdata["DiscontinuedOperations"][$treports-5]=='null'&&$rawdata["DiscontinuedOperations"][$treports-4]=='null')?null:($rawdata["DiscontinuedOperations"][$treports-7]+$rawdata["DiscontinuedOperations"][$treports-6]+$rawdata["DiscontinuedOperations"][$treports-5]+$rawdata["DiscontinuedOperations"][$treports-7]));
        $params[] = (($rawdata["EquityEarnings"][$treports-7]=='null'&&$rawdata["EquityEarnings"][$treports-6]=='null'&&$rawdata["EquityEarnings"][$treports-5]=='null'&&$rawdata["EquityEarnings"][$treports-4]=='null')?null:($rawdata["EquityEarnings"][$treports-7]+$rawdata["EquityEarnings"][$treports-6]+$rawdata["EquityEarnings"][$treports-5]+$rawdata["EquityEarnings"][$treports-4]));
        $params[] = (($rawdata["AccountingChange"][$treports-7]=='null'&&$rawdata["AccountingChange"][$treports-6]=='null'&&$rawdata["AccountingChange"][$treports-5]=='null'&&$rawdata["AccountingChange"][$treports-4]=='null')?null:($rawdata["AccountingChange"][$treports-7]+$rawdata["AccountingChange"][$treports-6]+$rawdata["AccountingChange"][$treports-5]+$rawdata["AccountingChange"][$treports-4]));
        $params[] = (($rawdata["BasicEPSNetIncome"][$treports-7]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-6]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-5]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-4]=='null')?null:($rawdata["BasicEPSNetIncome"][$treports-7]+$rawdata["BasicEPSNetIncome"][$treports-6]+$rawdata["BasicEPSNetIncome"][$treports-5]+$rawdata["BasicEPSNetIncome"][$treports-4]));
        $params[] = (($rawdata["ExtraordinaryItems"][$treports-7]=='null'&&$rawdata["ExtraordinaryItems"][$treports-6]=='null'&&$rawdata["ExtraordinaryItems"][$treports-5]=='null'&&$rawdata["ExtraordinaryItems"][$treports-4]=='null')?null:($rawdata["ExtraordinaryItems"][$treports-7]+$rawdata["ExtraordinaryItems"][$treports-6]+$rawdata["ExtraordinaryItems"][$treports-5]+$rawdata["ExtraordinaryItems"][$treports-4]));
        $params[] = (($rawdata["GrossProfit"][$treports-7]=='null'&&$rawdata["GrossProfit"][$treports-6]=='null'&&$rawdata["GrossProfit"][$treports-5]=='null'&&$rawdata["GrossProfit"][$treports-4]=='null')?null:($rawdata["GrossProfit"][$treports-7]+$rawdata["GrossProfit"][$treports-6]+$rawdata["GrossProfit"][$treports-5]+$rawdata["GrossProfit"][$treports-4]));
        $params[] = (($rawdata["IncomebeforeExtraordinaryItems"][$treports-7]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-6]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-5]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-4]=='null')?null:($rawdata["IncomebeforeExtraordinaryItems"][$treports-7]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-6]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-5]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-4]));
        $params[] = (($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null')?null:($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4]));
        $params[] = (($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')?null:($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4]));
        $params[] = (($rawdata["InterestExpense"][$treports-7]=='null'&&$rawdata["InterestExpense"][$treports-6]=='null'&&$rawdata["InterestExpense"][$treports-5]=='null'&&$rawdata["InterestExpense"][$treports-4]=='null')?null:(toFloat($rawdata["InterestExpense"][$treports-7])+toFloat($rawdata["InterestExpense"][$treports-6])+toFloat($rawdata["InterestExpense"][$treports-5])+toFloat($rawdata["InterestExpense"][$treports-4])));
        $params[] = (($rawdata["InterestIncome"][$treports-7]=='null'&&$rawdata["InterestIncome"][$treports-6]=='null'&&$rawdata["InterestIncome"][$treports-5]=='null'&&$rawdata["InterestIncome"][$treports-4]=='null')?null:(toFloat($rawdata["InterestIncome"][$treports-7])+toFloat($rawdata["InterestIncome"][$treports-6])+toFloat($rawdata["InterestIncome"][$treports-5])+toFloat($rawdata["InterestIncome"][$treports-4])));
        $params[] = (($rawdata["MinorityInterestEquityEarnings"][$treports-7]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-6]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-5]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-4]=='null')?null:($rawdata["MinorityInterestEquityEarnings"][$treports-7]+$rawdata["MinorityInterestEquityEarnings"][$treports-6]+$rawdata["MinorityInterestEquityEarnings"][$treports-5]+$rawdata["MinorityInterestEquityEarnings"][$treports-4]));
        $params[] = (($rawdata["NetIncome"][$treports-7]=='null'&&$rawdata["NetIncome"][$treports-6]=='null'&&$rawdata["NetIncome"][$treports-5]=='null'&&$rawdata["NetIncome"][$treports-4]=='null')?null:($rawdata["NetIncome"][$treports-7]+$rawdata["NetIncome"][$treports-6]+$rawdata["NetIncome"][$treports-5]+$rawdata["NetIncome"][$treports-4]));
        $params[] = (($rawdata["NetIncomeApplicabletoCommon"][$treports-7]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-6]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-5]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-4]=='null')?null:($rawdata["NetIncomeApplicabletoCommon"][$treports-7]+$rawdata["NetIncomeApplicabletoCommon"][$treports-6]+$rawdata["NetIncomeApplicabletoCommon"][$treports-5]+$rawdata["NetIncomeApplicabletoCommon"][$treports-4]));
        $params[] = (($rawdata["OperatingProfit"][$treports-7]=='null'&&$rawdata["OperatingProfit"][$treports-6]=='null'&&$rawdata["OperatingProfit"][$treports-5]=='null'&&$rawdata["OperatingProfit"][$treports-4]=='null')?null:($rawdata["OperatingProfit"][$treports-7]+$rawdata["OperatingProfit"][$treports-6]+$rawdata["OperatingProfit"][$treports-5]+$rawdata["OperatingProfit"][$treports-4]));
        $params[] = (($rawdata["OtherNonoperatingIncomeExpense"][$treports-7]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-6]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-5]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-4]=='null')?null:($rawdata["OtherNonoperatingIncomeExpense"][$treports-7]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-6]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-5]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-4]));
        $params[] = (($rawdata["OtherOperatingExpenses"][$treports-7]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-6]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-5]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-4]=='null')?null:($rawdata["OtherOperatingExpenses"][$treports-7]+$rawdata["OtherOperatingExpenses"][$treports-6]+$rawdata["OtherOperatingExpenses"][$treports-5]+$rawdata["OtherOperatingExpenses"][$treports-4]));
        $params[] = (($rawdata["ResearchDevelopmentExpense"][$treports-7]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-6]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-5]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-4]=='null')?null:($rawdata["ResearchDevelopmentExpense"][$treports-7]+$rawdata["ResearchDevelopmentExpense"][$treports-6]+$rawdata["ResearchDevelopmentExpense"][$treports-5]+$rawdata["ResearchDevelopmentExpense"][$treports-4]));
        $params[] = (($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-7]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-6]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-5]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-4]=='null')?null:($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-7]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-6]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-5]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-4]));
        $params[] = (($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')?null:($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]));
        $params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]=='null')?null:($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AdjustedEBIT`=?, `AdjustedEBITDA`=?, `AdjustedNetIncome`=?, `AftertaxMargin`=?, `EBITDA`=?, `GrossMargin`=?, `NetOperatingProfitafterTax`=?, `OperatingMargin`=?, `RevenueFQ`=?, `RevenueFY`=?, `RevenueTTM`=?, `CostOperatingExpenses`=?, `DepreciationExpense`=?, `DilutedEPSNetIncomefromContinuingOperations`=?, `DilutedWeightedAverageShares`=?, `AmortizationExpense`=?, `BasicEPSNetIncomefromContinuingOperations`=?, `BasicWeightedAverageShares`=?, `GeneralAdministrativeExpense`=?, `IncomeAfterTaxes`=?, `LaborExpense`=?, `NetIncomefromContinuingOperationsApplicabletoCommon`=?, `InterestIncomeExpenseNet`=?, `NoncontrollingInterest`=?, `NonoperatingGainsLosses`=?, `OperatingExpenses`=?, `OtherGeneralAdministrativeExpense`=?, `OtherInterestIncomeExpenseNet`=?, `OtherRevenue`=?, `OtherSellingGeneralAdministrativeExpenses`=?, `PreferredDividends`=?, `SalesMarketingExpense`=?, `TotalNonoperatingIncomeExpense`=?, `TotalOperatingExpenses`=?, `OperatingRevenue`=?";
        $params = array();
        $params[] = (($rawdata["AdjustedEBIT"][$treports-3]=='null'&&$rawdata["AdjustedEBIT"][$treports-2]=='null'&&$rawdata["AdjustedEBIT"][$treports-1]=='null'&&$rawdata["AdjustedEBIT"][$treports]=='null')?null:($rawdata["AdjustedEBIT"][$treports-3]+$rawdata["AdjustedEBIT"][$treports-2]+$rawdata["AdjustedEBIT"][$treports-1]+$rawdata["AdjustedEBIT"][$treports]));
        $params[] = (($rawdata["AdjustedEBITDA"][$treports-3]=='null'&&$rawdata["AdjustedEBITDA"][$treports-2]=='null'&&$rawdata["AdjustedEBITDA"][$treports-1]=='null'&&$rawdata["AdjustedEBITDA"][$treports]=='null')?null:($rawdata["AdjustedEBITDA"][$treports-3]+$rawdata["AdjustedEBITDA"][$treports-2]+$rawdata["AdjustedEBITDA"][$treports-1]+$rawdata["AdjustedEBITDA"][$treports]));
        $params[] = (($rawdata["AdjustedNetIncome"][$treports-3]=='null'&&$rawdata["AdjustedNetIncome"][$treports-3]=='null'&&$rawdata["AdjustedNetIncome"][$treports-1]=='null'&&$rawdata["AdjustedNetIncome"][$treports]=='null')?null:($rawdata["AdjustedNetIncome"][$treports-3]+$rawdata["AdjustedNetIncome"][$treports-2]+$rawdata["AdjustedNetIncome"][$treports-1]+$rawdata["AdjustedNetIncome"][$treports]));
        $divisor = 4;
        if($rawdata["AftertaxMargin"][$treports-3]=='null') {$divisor--;}
        if($rawdata["AftertaxMargin"][$treports-2]=='null') {$divisor--;}
        if($rawdata["AftertaxMargin"][$treports-1]=='null') {$divisor--;}
        if($rawdata["AftertaxMargin"][$treports]=='null') {$divisor--;}
        $params[] = (($divisor==0)?null:(($rawdata["AftertaxMargin"][$treports-3]+$rawdata["AftertaxMargin"][$treports-2]+$rawdata["AftertaxMargin"][$treports-1]+$rawdata["AftertaxMargin"][$treports])/$divisor));
        $params[] = (($rawdata["EBITDA"][$treports-3]=='null'&&$rawdata["EBITDA"][$treports-2]=='null'&&$rawdata["EBITDA"][$treports-1]=='null'&&$rawdata["EBITDA"][$treports]=='null')?null:($rawdata["EBITDA"][$treports-3]+$rawdata["EBITDA"][$treports-2]+$rawdata["EBITDA"][$treports-1]+$rawdata["EBITDA"][$treports]));
        $divisor = 4;
        if($rawdata["GrossMargin"][$treports-3]=='null') {$divisor--;}
        if($rawdata["GrossMargin"][$treports-2]=='null') {$divisor--;}
        if($rawdata["GrossMargin"][$treports-1]=='null') {$divisor--;}
        if($rawdata["GrossMargin"][$treports]=='null') {$divisor--;}
        $params[] = (($divisor==0)?null:(($rawdata["GrossMargin"][$treports-3]+$rawdata["GrossMargin"][$treports-2]+$rawdata["GrossMargin"][$treports-1]+$rawdata["GrossMargin"][$treports])/$divisor));
        $params[] = (($rawdata["NetOperatingProfitafterTax"][$treports-3]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-2]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-1]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports]=='null')?null:($rawdata["NetOperatingProfitafterTax"][$treports-3]+$rawdata["NetOperatingProfitafterTax"][$treports-2]+$rawdata["NetOperatingProfitafterTax"][$treports-1]+$rawdata["NetOperatingProfitafterTax"][$treports]));
        $divisor = 4;
        if($rawdata["OperatingMargin"][$treports-3]=='null') {$divisor--;}
        if($rawdata["OperatingMargin"][$treports-2]=='null') {$divisor--;}
        if($rawdata["OperatingMargin"][$treports-1]=='null') {$divisor--;}
        if($rawdata["OperatingMargin"][$treports]=='null') {$divisor--;}
        $params[] = (($divisor==0)?null:(($rawdata["OperatingMargin"][$treports-3]+$rawdata["OperatingMargin"][$treports-2]+$rawdata["OperatingMargin"][$treports-1]+$rawdata["OperatingMargin"][$treports])/$divisor));
        $params[] = (($rawdata["RevenueFQ"][$treports-3]=='null'&&$rawdata["RevenueFQ"][$treports-2]=='null'&&$rawdata["RevenueFQ"][$treports-1]=='null'&&$rawdata["RevenueFQ"][$treports]=='null')?null:($rawdata["RevenueFQ"][$treports-3]+$rawdata["RevenueFQ"][$treports-2]+$rawdata["RevenueFQ"][$treports-1]+$rawdata["RevenueFQ"][$treports]));
        $params[] = (($rawdata["RevenueFY"][$treports-3]=='null'&&$rawdata["RevenueFY"][$treports-2]=='null'&&$rawdata["RevenueFY"][$treports-1]=='null'&&$rawdata["RevenueFY"][$treports]=='null')?null:($rawdata["RevenueFY"][$treports-3]+$rawdata["RevenueFY"][$treports-2]+$rawdata["RevenueFY"][$treports-1]+$rawdata["RevenueFY"][$treports]));
        $params[] = (($rawdata["RevenueTTM"][$treports-3]=='null'&&$rawdata["RevenueTTM"][$treports-2]=='null'&&$rawdata["RevenueTTM"][$treports-1]=='null'&&$rawdata["RevenueTTM"][$treports]=='null')?null:($rawdata["RevenueTTM"][$treports-3]+$rawdata["RevenueTTM"][$treports-2]+$rawdata["RevenueTTM"][$treports-1]+$rawdata["RevenueTTM"][$treports]));
        $params[] = (($rawdata["CostOperatingExpenses"][$treports-3]=='null'&&$rawdata["CostOperatingExpenses"][$treports-2]=='null'&&$rawdata["CostOperatingExpenses"][$treports-1]=='null'&&$rawdata["CostOperatingExpenses"][$treports]=='null')?null:($rawdata["CostOperatingExpenses"][$treports-3]+$rawdata["CostOperatingExpenses"][$treports-2]+$rawdata["CostOperatingExpenses"][$treports-1]+$rawdata["CostOperatingExpenses"][$treports]));
        $params[] = (($rawdata["DepreciationExpense"][$treports-3]=='null'&&$rawdata["DepreciationExpense"][$treports-2]=='null'&&$rawdata["DepreciationExpense"][$treports-1]=='null'&&$rawdata["DepreciationExpense"][$treports]=='null')?null:($rawdata["DepreciationExpense"][$treports-3]+$rawdata["DepreciationExpense"][$treports-2]+$rawdata["DepreciationExpense"][$treports-1]+$rawdata["DepreciationExpense"][$treports]));
        $params[] = (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-3]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-2]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-1]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports]=='null')?null:($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-3]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-2]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-1]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports]));
        $params[] = $rawdata["DilutedWeightedAverageShares"][$MRQRow];
        $params[] = (($rawdata["AmortizationExpense"][$treports-3]=='null'&&$rawdata["AmortizationExpense"][$treports-2]=='null'&&$rawdata["AmortizationExpense"][$treports-1]=='null'&&$rawdata["AmortizationExpense"][$treports]=='null')?null:($rawdata["AmortizationExpense"][$treports-3]+$rawdata["AmortizationExpense"][$treports-2]+$rawdata["AmortizationExpense"][$treports-1]+$rawdata["AmortizationExpense"][$treports]));
        $params[] = (($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-3]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-2]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-1]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports]=='null')?null:($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-3]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-2]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-1]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports]));
        $params[] = $rawdata["BasicWeightedAverageShares"][$MRQRow];
        $params[] = (($rawdata["GeneralAdministrativeExpense"][$treports-3]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-2]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-1]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports]=='null')?null:($rawdata["GeneralAdministrativeExpense"][$treports-3]+$rawdata["GeneralAdministrativeExpense"][$treports-2]+$rawdata["GeneralAdministrativeExpense"][$treports-1]+$rawdata["GeneralAdministrativeExpense"][$treports]));
        $params[] = (($rawdata["IncomeAfterTaxes"][$treports-3]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-2]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-1]=='null'&&$rawdata["IncomeAfterTaxes"][$treports]=='null')?null:($rawdata["IncomeAfterTaxes"][$treports-3]+$rawdata["IncomeAfterTaxes"][$treports-2]+$rawdata["IncomeAfterTaxes"][$treports-1]+$rawdata["IncomeAfterTaxes"][$treports]));
        $params[] = (($rawdata["LaborExpense"][$treports-3]=='null'&&$rawdata["LaborExpense"][$treports-2]=='null'&&$rawdata["LaborExpense"][$treports-1]=='null'&&$rawdata["LaborExpense"][$treports]=='null')?null:($rawdata["LaborExpense"][$treports-3]+$rawdata["LaborExpense"][$treports-2]+$rawdata["LaborExpense"][$treports-1]+$rawdata["LaborExpense"][$treports]));
        $params[] = (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-3]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-2]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-1]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports]=='null')?null:($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-3]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-2]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-1]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports]));
        $params[] = (($rawdata["InterestIncomeExpenseNet"][$treports-3]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-2]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-1]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports]=='null')?null:($rawdata["InterestIncomeExpenseNet"][$treports-3]+$rawdata["InterestIncomeExpenseNet"][$treports-2]+$rawdata["InterestIncomeExpenseNet"][$treports-1]+$rawdata["InterestIncomeExpenseNet"][$treports]));
        $params[] = (($rawdata["NoncontrollingInterest"][$treports-3]=='null'&&$rawdata["NoncontrollingInterest"][$treports-2]=='null'&&$rawdata["NoncontrollingInterest"][$treports-1]=='null'&&$rawdata["NoncontrollingInterest"][$treports]=='null')?null:($rawdata["NoncontrollingInterest"][$treports-3]+$rawdata["NoncontrollingInterest"][$treports-2]+$rawdata["NoncontrollingInterest"][$treports-1]+$rawdata["NoncontrollingInterest"][$treports]));
        $params[] = (($rawdata["NonoperatingGainsLosses"][$treports-3]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-2]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-1]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports]=='null')?null:($rawdata["NonoperatingGainsLosses"][$treports-3]+$rawdata["NonoperatingGainsLosses"][$treports-2]+$rawdata["NonoperatingGainsLosses"][$treports-1]+$rawdata["NonoperatingGainsLosses"][$treports]));
        $params[] = (($rawdata["OperatingExpenses"][$treports-3]=='null'&&$rawdata["OperatingExpenses"][$treports-2]=='null'&&$rawdata["OperatingExpenses"][$treports-1]=='null'&&$rawdata["OperatingExpenses"][$treports]=='null')?null:($rawdata["OperatingExpenses"][$treports-3]+$rawdata["OperatingExpenses"][$treports-2]+$rawdata["OperatingExpenses"][$treports-1]+$rawdata["OperatingExpenses"][$treports]));
        $params[] = (($rawdata["OtherGeneralAdministrativeExpense"][$treports-3]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-2]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-1]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports]=='null')?null:($rawdata["OtherGeneralAdministrativeExpense"][$treports-3]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-2]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-1]+$rawdata["OtherGeneralAdministrativeExpense"][$treports]));
        $params[] = (($rawdata["OtherInterestIncomeExpenseNet"][$treports-3]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-2]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-1]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports]=='null')?null:($rawdata["OtherInterestIncomeExpenseNet"][$treports-3]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-2]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-1]+$rawdata["OtherInterestIncomeExpenseNet"][$treports]));
        $params[] = (($rawdata["OtherRevenue"][$treports-3]=='null'&&$rawdata["OtherRevenue"][$treports-2]=='null'&&$rawdata["OtherRevenue"][$treports-1]=='null'&&$rawdata["OtherRevenue"][$treports]=='null')?null:($rawdata["OtherRevenue"][$treports-3]+$rawdata["OtherRevenue"][$treports-2]+$rawdata["OtherRevenue"][$treports-1]+$rawdata["OtherRevenue"][$treports]));
        $params[] = (($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports]=='null')?null:($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports]));
        $params[] = (($rawdata["PreferredDividends"][$treports-3]=='null'&&$rawdata["PreferredDividends"][$treports-2]=='null'&&$rawdata["PreferredDividends"][$treports-1]=='null'&&$rawdata["PreferredDividends"][$treports]=='null')?null:($rawdata["PreferredDividends"][$treports-3]+$rawdata["PreferredDividends"][$treports-2]+$rawdata["PreferredDividends"][$treports-1]+$rawdata["PreferredDividends"][$treports]));
        $params[] = (($rawdata["SalesMarketingExpense"][$treports-3]=='null'&&$rawdata["SalesMarketingExpense"][$treports-2]=='null'&&$rawdata["SalesMarketingExpense"][$treports-1]=='null'&&$rawdata["SalesMarketingExpense"][$treports]=='null')?null:($rawdata["SalesMarketingExpense"][$treports-3]+$rawdata["SalesMarketingExpense"][$treports-2]+$rawdata["SalesMarketingExpense"][$treports-1]+$rawdata["SalesMarketingExpense"][$treports]));
        $params[] = (($rawdata["TotalNonoperatingIncomeExpense"][$treports-3]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-2]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-1]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports]=='null')?null:($rawdata["TotalNonoperatingIncomeExpense"][$treports-3]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-2]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-1]+$rawdata["TotalNonoperatingIncomeExpense"][$treports]));
        $params[] = (($rawdata["TotalOperatingExpenses"][$treports-3]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-2]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-1]=='null'&&$rawdata["TotalOperatingExpenses"][$treports]=='null')?null:($rawdata["TotalOperatingExpenses"][$treports-3]+$rawdata["TotalOperatingExpenses"][$treports-2]+$rawdata["TotalOperatingExpenses"][$treports-1]+$rawdata["TotalOperatingExpenses"][$treports]));
        $params[] = (($rawdata["OperatingRevenue"][$treports-3]=='null'&&$rawdata["OperatingRevenue"][$treports-2]=='null'&&$rawdata["OperatingRevenue"][$treports-1]=='null'&&$rawdata["OperatingRevenue"][$treports]=='null')?null:($rawdata["OperatingRevenue"][$treports-3]+$rawdata["OperatingRevenue"][$treports-2]+$rawdata["OperatingRevenue"][$treports-1]+$rawdata["OperatingRevenue"][$treports]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AdjustedEBIT`=?, `AdjustedEBITDA`=?, `AdjustedNetIncome`=?, `AftertaxMargin`=?, `EBITDA`=?, `GrossMargin`=?, `NetOperatingProfitafterTax`=?, `OperatingMargin`=?, `RevenueFQ`=?, `RevenueFY`=?, `RevenueTTM`=?, `CostOperatingExpenses`=?, `DepreciationExpense`=?, `DilutedEPSNetIncomefromContinuingOperations`=?, `DilutedWeightedAverageShares`=?, `AmortizationExpense`=?, `BasicEPSNetIncomefromContinuingOperations`=?, `BasicWeightedAverageShares`=?, `GeneralAdministrativeExpense`=?, `IncomeAfterTaxes`=?, `LaborExpense`=?, `NetIncomefromContinuingOperationsApplicabletoCommon`=?, `InterestIncomeExpenseNet`=?, `NoncontrollingInterest`=?, `NonoperatingGainsLosses`=?, `OperatingExpenses`=?, `OtherGeneralAdministrativeExpense`=?, `OtherInterestIncomeExpenseNet`=?, `OtherRevenue`=?, `OtherSellingGeneralAdministrativeExpenses`=?, `PreferredDividends`=?, `SalesMarketingExpense`=?, `TotalNonoperatingIncomeExpense`=?, `TotalOperatingExpenses`=?, `OperatingRevenue`=?";
        $params = array();
        $params[] = (($rawdata["AdjustedEBIT"][$treports-7]=='null'&&$rawdata["AdjustedEBIT"][$treports-6]=='null'&&$rawdata["AdjustedEBIT"][$treports-5]=='null'&&$rawdata["AdjustedEBIT"][$treports-4]=='null')?null:($rawdata["AdjustedEBIT"][$treports-7]+$rawdata["AdjustedEBIT"][$treports-6]+$rawdata["AdjustedEBIT"][$treports-5]+$rawdata["AdjustedEBIT"][$treports-4]));
        $params[] = (($rawdata["AdjustedEBITDA"][$treports-7]=='null'&&$rawdata["AdjustedEBITDA"][$treports-6]=='null'&&$rawdata["AdjustedEBITDA"][$treports-5]=='null'&&$rawdata["AdjustedEBITDA"][$treports-4]=='null')?null:($rawdata["AdjustedEBITDA"][$treports-7]+$rawdata["AdjustedEBITDA"][$treports-6]+$rawdata["AdjustedEBITDA"][$treports-5]+$rawdata["AdjustedEBITDA"][$treports-4]));
        $params[] = (($rawdata["AdjustedNetIncome"][$treports-7]=='null'&&$rawdata["AdjustedNetIncome"][$treports-6]=='null'&&$rawdata["AdjustedNetIncome"][$treports-5]=='null'&&$rawdata["AdjustedNetIncome"][$treports-4]=='null')?null:($rawdata["AdjustedNetIncome"][$treports-7]+$rawdata["AdjustedNetIncome"][$treports-6]+$rawdata["AdjustedNetIncome"][$treports-5]+$rawdata["AdjustedNetIncome"][$treports-4]));
        $divisor = 4;
        if($rawdata["AftertaxMargin"][$treports-7]=='null') {$divisor--;}
        if($rawdata["AftertaxMargin"][$treports-6]=='null') {$divisor--;}
        if($rawdata["AftertaxMargin"][$treports-5]=='null') {$divisor--;}
        if($rawdata["AftertaxMargin"][$treports-4]=='null') {$divisor--;}
        $params[] = (($divisor==0)?null:(($rawdata["AftertaxMargin"][$treports-7]+$rawdata["AftertaxMargin"][$treports-6]+$rawdata["AftertaxMargin"][$treports-5]+$rawdata["AftertaxMargin"][$treports-4])/$divisor));
        $params[] = (($rawdata["EBITDA"][$treports-7]=='null'&&$rawdata["EBITDA"][$treports-6]=='null'&&$rawdata["EBITDA"][$treports-5]=='null'&&$rawdata["EBITDA"][$treports-4]=='null')?null:($rawdata["EBITDA"][$treports-7]+$rawdata["EBITDA"][$treports-6]+$rawdata["EBITDA"][$treports-5]+$rawdata["EBITDA"][$treports-4]));
        $divisor = 4;
        if($rawdata["GrossMargin"][$treports-7]=='null') {$divisor--;}
        if($rawdata["GrossMargin"][$treports-6]=='null') {$divisor--;}
        if($rawdata["GrossMargin"][$treports-5]=='null') {$divisor--;}
        if($rawdata["GrossMargin"][$treports-4]=='null') {$divisor--;}
        $params[] = (($divisor==0)?null:(($rawdata["GrossMargin"][$treports-7]+$rawdata["GrossMargin"][$treports-6]+$rawdata["GrossMargin"][$treports-5]+$rawdata["GrossMargin"][$treports-4])/$divisor));
        $params[] = (($rawdata["NetOperatingProfitafterTax"][$treports-7]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-6]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-5]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-4]=='null')?null:($rawdata["NetOperatingProfitafterTax"][$treports-7]+$rawdata["NetOperatingProfitafterTax"][$treports-6]+$rawdata["NetOperatingProfitafterTax"][$treports-5]+$rawdata["NetOperatingProfitafterTax"][$treports-4]));
        $divisor = 4;
        if($rawdata["OperatingMargin"][$treports-7]=='null') {$divisor--;}
        if($rawdata["OperatingMargin"][$treports-6]=='null') {$divisor--;}
        if($rawdata["OperatingMargin"][$treports-5]=='null') {$divisor--;}
        if($rawdata["OperatingMargin"][$treports-4]=='null') {$divisor--;}
        $params[] = (($divisor==0)?null:(($rawdata["OperatingMargin"][$treports-7]+$rawdata["OperatingMargin"][$treports-6]+$rawdata["OperatingMargin"][$treports-5]+$rawdata["OperatingMargin"][$treports-4])/$divisor));
        $params[] = (($rawdata["RevenueFQ"][$treports-7]=='null'&&$rawdata["RevenueFQ"][$treports-6]=='null'&&$rawdata["RevenueFQ"][$treports-5]=='null'&&$rawdata["RevenueFQ"][$treports-4]=='null')?null:($rawdata["RevenueFQ"][$treports-7]+$rawdata["RevenueFQ"][$treports-6]+$rawdata["RevenueFQ"][$treports-5]+$rawdata["RevenueFQ"][$treports-4]));
        $params[] = (($rawdata["RevenueFY"][$treports-7]=='null'&&$rawdata["RevenueFY"][$treports-6]=='null'&&$rawdata["RevenueFY"][$treports-5]=='null'&&$rawdata["RevenueFY"][$treports-4]=='null')?null:($rawdata["RevenueFY"][$treports-7]+$rawdata["RevenueFY"][$treports-6]+$rawdata["RevenueFY"][$treports-5]+$rawdata["RevenueFY"][$treports-4]));
        $params[] = (($rawdata["RevenueTTM"][$treports-7]=='null'&&$rawdata["RevenueTTM"][$treports-6]=='null'&&$rawdata["RevenueTTM"][$treports-5]=='null'&&$rawdata["RevenueTTM"][$treports-4]=='null')?null:($rawdata["RevenueTTM"][$treports-7]+$rawdata["RevenueTTM"][$treports-6]+$rawdata["RevenueTTM"][$treports-5]+$rawdata["RevenueTTM"][$treports-4]));
        $params[] = (($rawdata["CostOperatingExpenses"][$treports-7]=='null'&&$rawdata["CostOperatingExpenses"][$treports-6]=='null'&&$rawdata["CostOperatingExpenses"][$treports-5]=='null'&&$rawdata["CostOperatingExpenses"][$treports-4]=='null')?null:($rawdata["CostOperatingExpenses"][$treports-7]+$rawdata["CostOperatingExpenses"][$treports-6]+$rawdata["CostOperatingExpenses"][$treports-5]+$rawdata["CostOperatingExpenses"][$treports-4]));
        $params[] = (($rawdata["DepreciationExpense"][$treports-7]=='null'&&$rawdata["DepreciationExpense"][$treports-6]=='null'&&$rawdata["DepreciationExpense"][$treports-5]=='null'&&$rawdata["DepreciationExpense"][$treports-4]=='null')?null:($rawdata["DepreciationExpense"][$treports-7]+$rawdata["DepreciationExpense"][$treports-6]+$rawdata["DepreciationExpense"][$treports-5]+$rawdata["DepreciationExpense"][$treports-4]));
        $params[] = (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-7]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-6]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-5]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-4]=='null')?null:($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-7]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-6]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-5]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-4]));
        $params[] = $rawdata["DilutedWeightedAverageShares"][$PMRQRow];
        $params[] = (($rawdata["AmortizationExpense"][$treports-7]=='null'&&$rawdata["AmortizationExpense"][$treports-6]=='null'&&$rawdata["AmortizationExpense"][$treports-5]=='null'&&$rawdata["AmortizationExpense"][$treports-4]=='null')?null:($rawdata["AmortizationExpense"][$treports-7]+$rawdata["AmortizationExpense"][$treports-6]+$rawdata["AmortizationExpense"][$treports-5]+$rawdata["AmortizationExpense"][$treports-4]));
        $params[] = (($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-7]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-6]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-5]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-4]=='null')?null:($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-7]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-6]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-5]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-4]));
        $params[] = $rawdata["BasicWeightedAverageShares"][$PMRQRow];
        $params[] = (($rawdata["GeneralAdministrativeExpense"][$treports-7]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-6]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-5]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-4]=='null')?null:($rawdata["GeneralAdministrativeExpense"][$treports-7]+$rawdata["GeneralAdministrativeExpense"][$treports-6]+$rawdata["GeneralAdministrativeExpense"][$treports-5]+$rawdata["GeneralAdministrativeExpense"][$treports-4]));
        $params[] = (($rawdata["IncomeAfterTaxes"][$treports-7]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-6]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-5]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-4]=='null')?null:($rawdata["IncomeAfterTaxes"][$treports-7]+$rawdata["IncomeAfterTaxes"][$treports-6]+$rawdata["IncomeAfterTaxes"][$treports-5]+$rawdata["IncomeAfterTaxes"][$treports-4]));
        $params[] = (($rawdata["LaborExpense"][$treports-7]=='null'&&$rawdata["LaborExpense"][$treports-6]=='null'&&$rawdata["LaborExpense"][$treports-5]=='null'&&$rawdata["LaborExpense"][$treports-4]=='null')?null:($rawdata["LaborExpense"][$treports-7]+$rawdata["LaborExpense"][$treports-6]+$rawdata["LaborExpense"][$treports-5]+$rawdata["LaborExpense"][$treports-4]));
        $params[] = (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-7]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-6]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-5]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-4]=='null')?null:($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-7]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-6]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-5]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-4]));
        $params[] = (($rawdata["InterestIncomeExpenseNet"][$treports-7]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-6]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-5]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-4]=='null')?null:($rawdata["InterestIncomeExpenseNet"][$treports-7]+$rawdata["InterestIncomeExpenseNet"][$treports-6]+$rawdata["InterestIncomeExpenseNet"][$treports-5]+$rawdata["InterestIncomeExpenseNet"][$treports-4]));
        $params[] = (($rawdata["NoncontrollingInterest"][$treports-7]=='null'&&$rawdata["NoncontrollingInterest"][$treports-6]=='null'&&$rawdata["NoncontrollingInterest"][$treports-5]=='null'&&$rawdata["NoncontrollingInterest"][$treports-4]=='null')?null:($rawdata["NoncontrollingInterest"][$treports-7]+$rawdata["NoncontrollingInterest"][$treports-6]+$rawdata["NoncontrollingInterest"][$treports-5]+$rawdata["NoncontrollingInterest"][$treports-4]));
        $params[] = (($rawdata["NonoperatingGainsLosses"][$treports-7]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-6]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-5]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-4]=='null')?null:($rawdata["NonoperatingGainsLosses"][$treports-7]+$rawdata["NonoperatingGainsLosses"][$treports-6]+$rawdata["NonoperatingGainsLosses"][$treports-5]+$rawdata["NonoperatingGainsLosses"][$treports-4]));
        $params[] = (($rawdata["OperatingExpenses"][$treports-7]=='null'&&$rawdata["OperatingExpenses"][$treports-6]=='null'&&$rawdata["OperatingExpenses"][$treports-5]=='null'&&$rawdata["OperatingExpenses"][$treports-4]=='null')?null:($rawdata["OperatingExpenses"][$treports-7]+$rawdata["OperatingExpenses"][$treports-6]+$rawdata["OperatingExpenses"][$treports-5]+$rawdata["OperatingExpenses"][$treports-4]));
        $params[] = (($rawdata["OtherGeneralAdministrativeExpense"][$treports-7]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-6]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-5]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-4]=='null')?null:($rawdata["OtherGeneralAdministrativeExpense"][$treports-7]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-6]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-5]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-4]));
        $params[] = (($rawdata["OtherInterestIncomeExpenseNet"][$treports-7]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-6]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-5]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-4]=='null')?null:($rawdata["OtherInterestIncomeExpenseNet"][$treports-7]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-6]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-5]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-4]));
        $params[] = (($rawdata["OtherRevenue"][$treports-7]=='null'&&$rawdata["OtherRevenue"][$treports-6]=='null'&&$rawdata["OtherRevenue"][$treports-5]=='null'&&$rawdata["OtherRevenue"][$treports-4]=='null')?null:($rawdata["OtherRevenue"][$treports-7]+$rawdata["OtherRevenue"][$treports-6]+$rawdata["OtherRevenue"][$treports-5]+$rawdata["OtherRevenue"][$treports-4]));
        $params[] = (($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-4]=='null')?null:($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-4]));
        $params[] = (($rawdata["PreferredDividends"][$treports-7]=='null'&&$rawdata["PreferredDividends"][$treports-6]=='null'&&$rawdata["PreferredDividends"][$treports-5]=='null'&&$rawdata["PreferredDividends"][$treports-4]=='null')?null:($rawdata["PreferredDividends"][$treports-7]+$rawdata["PreferredDividends"][$treports-6]+$rawdata["PreferredDividends"][$treports-5]+$rawdata["PreferredDividends"][$treports-4]));
        $params[] = (($rawdata["SalesMarketingExpense"][$treports-7]=='null'&&$rawdata["SalesMarketingExpense"][$treports-6]=='null'&&$rawdata["SalesMarketingExpense"][$treports-5]=='null'&&$rawdata["SalesMarketingExpense"][$treports-4]=='null')?null:($rawdata["SalesMarketingExpense"][$treports-7]+$rawdata["SalesMarketingExpense"][$treports-6]+$rawdata["SalesMarketingExpense"][$treports-5]+$rawdata["SalesMarketingExpense"][$treports-4]));
        $params[] = (($rawdata["TotalNonoperatingIncomeExpense"][$treports-7]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-6]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-5]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-4]=='null')?null:($rawdata["TotalNonoperatingIncomeExpense"][$treports-7]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-6]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-5]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-4]));
        $params[] = (($rawdata["TotalOperatingExpenses"][$treports-7]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-6]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-5]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-4]=='null')?null:($rawdata["TotalOperatingExpenses"][$treports-7]+$rawdata["TotalOperatingExpenses"][$treports-6]+$rawdata["TotalOperatingExpenses"][$treports-5]+$rawdata["TotalOperatingExpenses"][$treports-4]));
        $params[] = (($rawdata["OperatingRevenue"][$treports-7]=='null'&&$rawdata["OperatingRevenue"][$treports-6]=='null'&&$rawdata["OperatingRevenue"][$treports-5]=='null'&&$rawdata["OperatingRevenue"][$treports-4]=='null')?null:($rawdata["OperatingRevenue"][$treports-7]+$rawdata["OperatingRevenue"][$treports-6]+$rawdata["OperatingRevenue"][$treports-5]+$rawdata["OperatingRevenue"][$treports-4]));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `COGSPercent`=?, `GrossMarginPercent`=?, `SGAPercent`=?, `RDPercent`=?, `DepreciationAmortizationPercent`=?, `EBITDAPercent`=?, `OperatingMarginPercent`=?, `EBITPercent`=?, `TaxRatePercent`=?, `IncomeAfterTaxes`=?, `NetMarginPercent`=?, `DividendsPerShare`=?, `ShortTermDebtAndCurrentPortion`=?, `TotalLongTermDebtAndNotesPayable`=?, `NetChangeLongTermDebt`=?, `CapEx`=?, `FreeCashFlow`=?, `OwnerEarningsFCF`=?, `Sales5YYCGrPerc`=?";
        $params = array();
        $params[] = ((($rawdata["CostofRevenue"][$treports-3]=='null'&&$rawdata["CostofRevenue"][$treports-2]=='null'&&$rawdata["CostofRevenue"][$treports-1]=='null'&&$rawdata["CostofRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["CostofRevenue"][$treports-3]+$rawdata["CostofRevenue"][$treports-2]+$rawdata["CostofRevenue"][$treports-1]+$rawdata["CostofRevenue"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["GrossProfit"][$treports-3]=='null'&&$rawdata["GrossProfit"][$treports-2]=='null'&&$rawdata["GrossProfit"][$treports-1]=='null'&&$rawdata["GrossProfit"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["GrossProfit"][$treports-3]+$rawdata["GrossProfit"][$treports-2]+$rawdata["GrossProfit"][$treports-1]+$rawdata["GrossProfit"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["ResearchDevelopmentExpense"][$treports-3]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-2]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-1]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["ResearchDevelopmentExpense"][$treports-3]+$rawdata["ResearchDevelopmentExpense"][$treports-2]+$rawdata["ResearchDevelopmentExpense"][$treports-1]+$rawdata["ResearchDevelopmentExpense"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["EBITDA"][$treports-3]=='null'&&$rawdata["EBITDA"][$treports-2]=='null'&&$rawdata["EBITDA"][$treports-1]=='null'&&$rawdata["EBITDA"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["EBITDA"][$treports-3]+$rawdata["EBITDA"][$treports-2]+$rawdata["EBITDA"][$treports-1]+$rawdata["EBITDA"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["OperatingProfit"][$treports-3]=='null'&&$rawdata["OperatingProfit"][$treports-2]=='null'&&$rawdata["OperatingProfit"][$treports-1]=='null'&&$rawdata["OperatingProfit"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["OperatingProfit"][$treports-3]+$rawdata["OperatingProfit"][$treports-2]+$rawdata["OperatingProfit"][$treports-1]+$rawdata["OperatingProfit"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["EBIT"][$treports-3]=='null'&&$rawdata["EBIT"][$treports-2]=='null'&&$rawdata["EBIT"][$treports-1]=='null'&&$rawdata["EBIT"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["EBIT"][$treports-3]+$rawdata["EBIT"][$treports-2]+$rawdata["EBIT"][$treports-1]+$rawdata["EBIT"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
        $params[] = ((($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports]==0))?null:(($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports])/($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports])));
        $params[] = ((($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')&&($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null'))?null:(($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports])-($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports])));
        $params[] = ((($rawdata["NetIncome"][$treports-3]=='null'&&$rawdata["NetIncome"][$treports-2]=='null'&&$rawdata["NetIncome"][$treports-1]=='null'&&$rawdata["NetIncome"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["NetIncome"][$treports-3]+$rawdata["NetIncome"][$treports-2]+$rawdata["NetIncome"][$treports-1]+$rawdata["NetIncome"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
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
        $params[] = $value;
        $params[] = ((($rawdata["CurrentPortionofLongtermDebt"][$treports-3]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-2]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-1]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports]=='null')&&($rawdata["ShorttermBorrowings"][$treports-3]=='null'&&$rawdata["ShorttermBorrowings"][$treports-2]=='null'&&$rawdata["ShorttermBorrowings"][$treports-1]=='null'&&$rawdata["ShorttermBorrowings"][$treports]=='null'))?null:($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow]));
        $params[] = ((($rawdata["TotalLongtermDebt"][$treports-3]=='null'&&$rawdata["TotalLongtermDebt"][$treports-2]=='null'&&$rawdata["TotalLongtermDebt"][$treports-1]=='null'&&$rawdata["TotalLongtermDebt"][$treports]=='null')&&($rawdata["NotesPayable"][$treports-3]=='null'&&$rawdata["NotesPayable"][$treports-2]=='null'&&$rawdata["NotesPayable"][$treports-1]=='null'&&$rawdata["NotesPayable"][$treports]=='null'))?null:($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow]));
        $params[] = ((($rawdata["LongtermDebtProceeds"][$treports-3]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-2]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-1]=='null'&&$rawdata["LongtermDebtProceeds"][$treports]=='null')&&($rawdata["LongtermDebtPayments"][$treports-3]=='null'&&$rawdata["LongtermDebtPayments"][$treports-2]=='null'&&$rawdata["LongtermDebtPayments"][$treports-1]=='null'&&$rawdata["LongtermDebtPayments"][$treports]=='null'))?null:(($rawdata["LongtermDebtProceeds"][$treports-3]+$rawdata["LongtermDebtProceeds"][$treports-2]+$rawdata["LongtermDebtProceeds"][$treports-1]+$rawdata["LongtermDebtProceeds"][$treports])+($rawdata["LongtermDebtPayments"][$treports-3]+$rawdata["LongtermDebtPayments"][$treports-2]+$rawdata["LongtermDebtPayments"][$treports-1]+$rawdata["LongtermDebtPayments"][$treports])));
        $params[] = (($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')?null:(-($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])));
        $params[] = ((($rawdata["CashfromOperatingActivities"][$treports-3]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-2]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-1]=='null'&&$rawdata["CashfromOperatingActivities"][$treports]=='null')&&($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null'))?null:(($rawdata["CashfromOperatingActivities"][$treports-3]+$rawdata["CashfromOperatingActivities"][$treports-2]+$rawdata["CashfromOperatingActivities"][$treports-1]+$rawdata["CashfromOperatingActivities"][$treports])+($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])));
        $params[] = ((($rawdata["CFNetIncome"][$treports-3]=='null'&&$rawdata["CFNetIncome"][$treports-2]=='null'&&$rawdata["CFNetIncome"][$treports-1]=='null'&&$rawdata["CFNetIncome"][$treports]=='null')&&($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')&&($rawdata["EmployeeCompensation"][$treports-3]=='null'&&$rawdata["EmployeeCompensation"][$treports-2]=='null'&&$rawdata["EmployeeCompensation"][$treports-1]=='null'&&$rawdata["EmployeeCompensation"][$treports]=='null')&&($rawdata["AdjustmentforSpecialCharges"][$treports-3]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-2]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-1]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports]=='null')&&($rawdata["DeferredIncomeTaxes"][$treports-3]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-2]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-1]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports]=='null')&&($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')&&($rawdata["ChangeinCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports]=='null')&&($rawdata["ChangeinCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports]=='null'))?null:
                (($rawdata["CFNetIncome"][$treports-3]+$rawdata["CFNetIncome"][$treports-2]+$rawdata["CFNetIncome"][$treports-1]+$rawdata["CFNetIncome"][$treports])+($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports])+($rawdata["EmployeeCompensation"][$treports-3]+$rawdata["EmployeeCompensation"][$treports-2]+$rawdata["EmployeeCompensation"][$treports-1]+$rawdata["EmployeeCompensation"][$treports])+($rawdata["AdjustmentforSpecialCharges"][$treports-3]+$rawdata["AdjustmentforSpecialCharges"][$treports-2]+$rawdata["AdjustmentforSpecialCharges"][$treports-1]+$rawdata["AdjustmentforSpecialCharges"][$treports])+($rawdata["DeferredIncomeTaxes"][$treports-3]+$rawdata["DeferredIncomeTaxes"][$treports-2]+$rawdata["DeferredIncomeTaxes"][$treports-1]+$rawdata["DeferredIncomeTaxes"][$treports])+($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])+(($rawdata["ChangeinCurrentAssets"][$treports-3]+$rawdata["ChangeinCurrentAssets"][$treports-2]+$rawdata["ChangeinCurrentAssets"][$treports-1]+$rawdata["ChangeinCurrentAssets"][$treports])+($rawdata["ChangeinCurrentLiabilities"][$treports-3]+$rawdata["ChangeinCurrentLiabilities"][$treports-2]+$rawdata["ChangeinCurrentLiabilities"][$treports-1]+$rawdata["ChangeinCurrentLiabilities"][$treports]))));
        $params[] = ((($rawdata["TotalRevenue"][$treports-3]=='null' && $rawdata["TotalRevenue"][$treports-2]=='null' && $rawdata["TotalRevenue"][$treports-1]=='null' && $rawdata["TotalRevenue"][$treports]=='null') || $rawdata["TotalRevenue"][$areports-5]=='null' || $rawdata["TotalRevenue"][$areports-5]<=0 || ($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports] < 0))?null:(pow(($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])/$rawdata["TotalRevenue"][$areports-5], 1/5) - 1));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `COGSPercent`=?, `GrossMarginPercent`=?, `SGAPercent`=?, `RDPercent`=?, `DepreciationAmortizationPercent`=?, `EBITDAPercent`=?, `OperatingMarginPercent`=?, `EBITPercent`=?, `TaxRatePercent`=?, `IncomeAfterTaxes`=?, `NetMarginPercent`=?, `DividendsPerShare`=?, `ShortTermDebtAndCurrentPortion`=?, `TotalLongTermDebtAndNotesPayable`=?, `NetChangeLongTermDebt`=?, `CapEx`=?, `FreeCashFlow`=?, `OwnerEarningsFCF`=?";
        $params = array();
        $params[] = ((($rawdata["CostofRevenue"][$treports-7]=='null'&&$rawdata["CostofRevenue"][$treports-6]=='null'&&$rawdata["CostofRevenue"][$treports-5]=='null'&&$rawdata["CostofRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["CostofRevenue"][$treports-7]+$rawdata["CostofRevenue"][$treports-6]+$rawdata["CostofRevenue"][$treports-5]+$rawdata["CostofRevenue"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["GrossProfit"][$treports-7]=='null'&&$rawdata["GrossProfit"][$treports-6]=='null'&&$rawdata["GrossProfit"][$treports-5]=='null'&&$rawdata["GrossProfit"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["GrossProfit"][$treports-7]+$rawdata["GrossProfit"][$treports-6]+$rawdata["GrossProfit"][$treports-5]+$rawdata["GrossProfit"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["ResearchDevelopmentExpense"][$treports-7]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-6]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-5]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["ResearchDevelopmentExpense"][$treports-7]+$rawdata["ResearchDevelopmentExpense"][$treports-6]+$rawdata["ResearchDevelopmentExpense"][$treports-5]+$rawdata["ResearchDevelopmentExpense"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["EBITDA"][$treports-7]=='null'&&$rawdata["EBITDA"][$treports-6]=='null'&&$rawdata["EBITDA"][$treports-5]=='null'&&$rawdata["EBITDA"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["EBITDA"][$treports-7]+$rawdata["EBITDA"][$treports-6]+$rawdata["EBITDA"][$treports-5]+$rawdata["EBITDA"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["OperatingProfit"][$treports-7]=='null'&&$rawdata["OperatingProfit"][$treports-6]=='null'&&$rawdata["OperatingProfit"][$treports-5]=='null'&&$rawdata["OperatingProfit"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["OperatingProfit"][$treports-7]+$rawdata["OperatingProfit"][$treports-6]+$rawdata["OperatingProfit"][$treports-5]+$rawdata["OperatingProfit"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["EBIT"][$treports-7]=='null'&&$rawdata["EBIT"][$treports-6]=='null'&&$rawdata["EBIT"][$treports-5]=='null'&&$rawdata["EBIT"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["EBIT"][$treports-7]+$rawdata["EBIT"][$treports-6]+$rawdata["EBIT"][$treports-5]+$rawdata["EBIT"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
        $params[] = ((($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4]==0))?null:(($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4])/($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4])));
        $params[] = ((($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')&&($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null'))?null:(($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4])-($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4])));
        $params[] = ((($rawdata["NetIncome"][$treports-7]=='null'&&$rawdata["NetIncome"][$treports-6]=='null'&&$rawdata["NetIncome"][$treports-5]=='null'&&$rawdata["NetIncome"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["NetIncome"][$treports-7]+$rawdata["NetIncome"][$treports-6]+$rawdata["NetIncome"][$treports-5]+$rawdata["NetIncome"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
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
        $params[] = $value;
        $params[] = ((($rawdata["CurrentPortionofLongtermDebt"][$treports-7]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-6]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-5]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-4]=='null')&&($rawdata["ShorttermBorrowings"][$treports-7]=='null'&&$rawdata["ShorttermBorrowings"][$treports-6]=='null'&&$rawdata["ShorttermBorrowings"][$treports-5]=='null'&&$rawdata["ShorttermBorrowings"][$treports-4]=='null'))?null:($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow]));
        $params[] = ((($rawdata["TotalLongtermDebt"][$treports-7]=='null'&&$rawdata["TotalLongtermDebt"][$treports-6]=='null'&&$rawdata["TotalLongtermDebt"][$treports-5]=='null'&&$rawdata["TotalLongtermDebt"][$treports-4]=='null')&&($rawdata["NotesPayable"][$treports-7]=='null'&&$rawdata["NotesPayable"][$treports-6]=='null'&&$rawdata["NotesPayable"][$treports-5]=='null'&&$rawdata["NotesPayable"][$treports-4]=='null'))?null:($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow]));
        $params[] = ((($rawdata["LongtermDebtProceeds"][$treports-7]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-6]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-5]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-4]=='null')&&($rawdata["LongtermDebtPayments"][$treports-7]=='null'&&$rawdata["LongtermDebtPayments"][$treports-6]=='null'&&$rawdata["LongtermDebtPayments"][$treports-5]=='null'&&$rawdata["LongtermDebtPayments"][$treports-4]=='null'))?null:(($rawdata["LongtermDebtProceeds"][$treports-7]+$rawdata["LongtermDebtProceeds"][$treports-6]+$rawdata["LongtermDebtProceeds"][$treports-5]+$rawdata["LongtermDebtProceeds"][$treports-4])+($rawdata["LongtermDebtPayments"][$treports-7]+$rawdata["LongtermDebtPayments"][$treports-6]+$rawdata["LongtermDebtPayments"][$treports-5]+$rawdata["LongtermDebtPayments"][$treports-4])));
        $params[] = (($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')?null:(-($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])));
        $params[] = ((($rawdata["CashfromOperatingActivities"][$treports-7]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-6]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-5]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-4]=='null')&&($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null'))?null:(($rawdata["CashfromOperatingActivities"][$treports-7]+$rawdata["CashfromOperatingActivities"][$treports-6]+$rawdata["CashfromOperatingActivities"][$treports-5]+$rawdata["CashfromOperatingActivities"][$treports-4])+($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])));
        $params[] = ((($rawdata["CFNetIncome"][$treports-7]=='null'&&$rawdata["CFNetIncome"][$treports-6]=='null'&&$rawdata["CFNetIncome"][$treports-5]=='null'&&$rawdata["CFNetIncome"][$treports-4]=='null')&&($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')&&($rawdata["EmployeeCompensation"][$treports-7]=='null'&&$rawdata["EmployeeCompensation"][$treports-6]=='null'&&$rawdata["EmployeeCompensation"][$treports-5]=='null'&&$rawdata["EmployeeCompensation"][$treports-4]=='null')&&($rawdata["AdjustmentforSpecialCharges"][$treports-7]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-6]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-5]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-4]=='null')&&($rawdata["DeferredIncomeTaxes"][$treports-7]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-6]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-5]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-4]=='null')&&($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')&&($rawdata["ChangeinCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-4]=='null')&&($rawdata["ChangeinCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-4]=='null'))?null:
                (($rawdata["CFNetIncome"][$treports-7]+$rawdata["CFNetIncome"][$treports-6]+$rawdata["CFNetIncome"][$treports-5]+$rawdata["CFNetIncome"][$treports-4])+($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4])+($rawdata["EmployeeCompensation"][$treports-7]+$rawdata["EmployeeCompensation"][$treports-6]+$rawdata["EmployeeCompensation"][$treports-5]+$rawdata["EmployeeCompensation"][$treports-4])+($rawdata["AdjustmentforSpecialCharges"][$treports-7]+$rawdata["AdjustmentforSpecialCharges"][$treports-6]+$rawdata["AdjustmentforSpecialCharges"][$treports-5]+$rawdata["AdjustmentforSpecialCharges"][$treports-4])+($rawdata["DeferredIncomeTaxes"][$treports-7]+$rawdata["DeferredIncomeTaxes"][$treports-6]+$rawdata["DeferredIncomeTaxes"][$treports-5]+$rawdata["DeferredIncomeTaxes"][$treports-4])+($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])+(($rawdata["ChangeinCurrentAssets"][$treports-7]+$rawdata["ChangeinCurrentAssets"][$treports-6]+$rawdata["ChangeinCurrentAssets"][$treports-5]+$rawdata["ChangeinCurrentAssets"][$treports-4])+($rawdata["ChangeinCurrentLiabilities"][$treports-7]+$rawdata["ChangeinCurrentLiabilities"][$treports-6]+$rawdata["ChangeinCurrentLiabilities"][$treports-5]+$rawdata["ChangeinCurrentLiabilities"][$treports-4]))));
        $params = array_merge($params,$params);
        array_unshift($params,$dates->ticker_id);

        try {
            $res = $db->prepare($query);
            $res->execute($params); 
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
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
?>
