<?php
function update_raw_data_yahoo_keystats($ticker_id, $rawdata) {
	$tables = array("tickers_yahoo_keystats_1","tickers_yahoo_keystats_2");

        //Delete all reports before updating to be sure we do not miss any manual update
        //as this is a batch process, it will not impact on the UE
        foreach($tables as $table) {
                $query = "DELETE FROM $table WHERE ticker_id = ".$ticker_id;
                mysql_query($query) or die (mysql_error());
        }

        //Update yahoo keystats tables
        //tickers_yahoo_keystats_1
	$query = "INSERT INTO `tickers_yahoo_keystats_1` (`ticker_id` ,`MarketCapIntraday` ,`EnterpriseValueDate` ,`EnterpriseValue` ,`TrailingPETTMIntraday` ,`ForwardPEDate` ,`ForwardPE` ,`PEGRatio5Years` ,`PriceSalesTTM` ,`PriceBookMRQ` ,`EnterpriseValueRevenueTTM` ,`EnterpriseValueEBITDATTM` ,`FiscalYearEnds` ,`MostRecentQuarter` ,`ProfitMarginTTM` ,`OperatingMarginTTM` ,`ReturnOnAssetsTTM` ,`ReturnOnEquityTTM` ,`RevenueTTM` ,`RevenuePerShateTTM` ,`QtrlyRevenueGrowthYOY` ,`GrossProfitTTM` ,`EBITDATTM` ,`NetIncomeAvlToCommonTTM` ,`DilutedEPSTTM` ,`QtrlyEarningsGrowthYOY` ,`TotalCashMRQ` ,`TotalCashPerShareMRQ` ,`TotalDebtMRQ` ,`TotalDebtEquityMRQ` ,`CurrentRatioMRQ` ,`BookValuePerShareMRQ` ,`OperatingCashFlowTTM` ,`LeveredFreeCashFlowTTM`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->marketCap->raw) || !is_numeric($rawdata->marketCap->raw)?"NULL":$rawdata->marketCap->raw).",";
        $query .= "NULL,";
        $query .= (!isset($rawdata->enterpriseValue->raw) || !is_numeric($rawdata->enterpriseValue->raw)?"NULL":$rawdata->enterpriseValue->raw).",";
        $query .= (!isset($rawdata->trailingPE->raw) || !is_numeric($rawdata->trailingPE->raw)?"NULL":$rawdata->trailingPE->raw).",";
        $query .= "NULL,";
        $query .= (!isset($rawdata->forwardPE->raw) || !is_numeric($rawdata->forwardPE->raw)?"NULL":$rawdata->forwardPE->raw).",";
        $query .= (!isset($rawdata->pegRatio->raw) || !is_numeric($rawdata->pegRatio->raw)?"NULL":$rawdata->pegRatio->raw).",";
        $query .= (!isset($rawdata->priceToSalesTrailing12Months->raw) || !is_numeric($rawdata->priceToSalesTrailing12Months->raw)?"NULL":$rawdata->priceToSalesTrailing12Months->raw).",";
        $query .= (!isset($rawdata->priceToBook->raw) || !is_numeric($rawdata->priceToBook->raw)?"NULL":$rawdata->priceToBook->raw).",";
        $query .= (!isset($rawdata->enterpriseToRevenue->raw) || !is_numeric($rawdata->enterpriseToRevenue->raw)?"NULL":$rawdata->enterpriseToRevenue->raw).",";
        $query .= (!isset($rawdata->enterpriseToEbitda->raw) || !is_numeric($rawdata->enterpriseToEbitda->raw)?"NULL":$rawdata->enterpriseToEbitda->raw).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->lastFiscalYearEnd->fmt))."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->mostRecentQuarter->fmt))."',";
        $query .= (!isset($rawdata->profitMargins->raw) || !is_numeric($rawdata->profitMargins->raw)?"NULL":($rawdata->profitMargins->raw * 100)).",";
        $query .= (!isset($rawdata->operatingMargins->raw) || !is_numeric($rawdata->operatingMargins->raw)?"NULL":($rawdata->operatingMargins->raw * 100)).",";
        $query .= (!isset($rawdata->returnOnAssets->raw) || !is_numeric($rawdata->returnOnAssets->raw)?"NULL":($rawdata->returnOnAssets->raw * 100)).",";
        $query .= (!isset($rawdata->returnOnEquity->raw) || !is_numeric($rawdata->returnOnEquity->raw)?"NULL":($rawdata->returnOnEquity->raw * 100)).",";
        $query .= (!isset($rawdata->totalRevenue->raw) || !is_numeric($rawdata->totalRevenue->raw)?"NULL":$rawdata->totalRevenue->raw).",";
        $query .= (!isset($rawdata->revenuePerShare->raw) || !is_numeric($rawdata->revenuePerShare->raw)?"NULL":$rawdata->revenuePerShare->raw).",";
        $query .= (!isset($rawdata->revenueGrowth->raw) || !is_numeric($rawdata->revenueGrowth->raw)?"NULL":($rawdata->revenueGrowth->raw * 100)).",";
        $query .= (!isset($rawdata->grossProfits->raw) || !is_numeric($rawdata->grossProfits->raw)?"NULL":$rawdata->grossProfits->raw).",";
        $query .= (!isset($rawdata->ebitda->raw) || !is_numeric($rawdata->ebitda->raw)?"NULL":$rawdata->ebitda->raw).",";
        $query .= (!isset($rawdata->netIncomeToCommon->raw) || !is_numeric($rawdata->netIncomeToCommon->raw)?"NULL":$rawdata->netIncomeToCommon->raw).",";
        $query .= (!isset($rawdata->trailingEps->raw) || !is_numeric($rawdata->trailingEps->raw)?"NULL":$rawdata->trailingEps->raw).",";
        $query .= (!isset($rawdata->earningsQuarterlyGrowth->raw) || !is_numeric($rawdata->earningsQuarterlyGrowth->raw)?"NULL":($rawdata->earningsQuarterlyGrowth->raw * 100)).",";
        $query .= (!isset($rawdata->totalCash->raw) || !is_numeric($rawdata->totalCash->raw)?"NULL":$rawdata->totalCash->raw).",";
        $query .= (!isset($rawdata->totalCashPerShare->raw) || !is_numeric($rawdata->totalCashPerShare->raw)?"NULL":$rawdata->totalCashPerShare->raw).",";
        $query .= (!isset($rawdata->totalDebt->raw) || !is_numeric($rawdata->totalDebt->raw)?"NULL":$rawdata->totalDebt->raw).",";
        $query .= (!isset($rawdata->debtToEquity->raw) || !is_numeric($rawdata->debtToEquity->raw)?"NULL":$rawdata->debtToEquity->raw).",";
        $query .= (!isset($rawdata->currentRatio->raw) || !is_numeric($rawdata->currentRatio->raw)?"NULL":$rawdata->currentRatio->raw).",";
        $query .= (!isset($rawdata->bookValue->raw) || !is_numeric($rawdata->bookValue->raw)?"NULL":$rawdata->bookValue->raw).",";
        $query .= (!isset($rawdata->operatingCashflow->raw) || !is_numeric($rawdata->operatingCashflow->raw)?"NULL":$rawdata->operatingCashflow->raw).",";
        $query .= (!isset($rawdata->freeCashflow->raw) || !is_numeric($rawdata->freeCashflow->raw)?"NULL":$rawdata->freeCashflow->raw);
        $query .= ")";
        mysql_query($query) or die ($query . "\n". mysql_error());

	$query = "INSERT INTO `tickers_yahoo_keystats_2` (`ticker_id` ,`Beta` ,`52WeekChange` ,`52WeekChangeSPS500` ,`52WeekHighDate` ,`52WeekHighValue` ,`52WeekLowDate` ,`52WeekLowValue` ,`50DayMovingAverage` ,`200DayMovingAverage` ,`AvgVolume3Month` ,`AvgVolume10Days` ,`SharesOutstanding` ,`Float` ,`PercentageHeldByInsiders` ,`PercentageHeldByInstitutions` ,`SharesShortDate` ,`SharesShortValue` ,`SharesShortPriorMonth` ,`ShortRatioDate` ,`ShortRatio` ,`ShortPercentageOfFloatDate` ,`ShortPercentageOfFloat` ,`ForwardAnnualDividendRate` ,`ForwardAnnualDividendYield` ,`TrailingAnnualDividendRate` ,`TrailingAnnualDividendYield` ,`5YearAverageDividendYield` ,`PayoutRatio` ,`DividendDate` ,`ExDividendDate` ,`LastSplitFactorTerm` ,`LastSplitFactor` ,`LastSplitDate`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->Beta->raw) || !is_numeric($rawdata->Beta->raw)?"NULL":$rawdata->Beta->raw).",";
        $query .= (!isset($rawdata->_2WeekChange->raw) || !is_numeric($rawdata->_2WeekChange->raw)?"NULL":($rawdata->_2WeekChange->raw * 100)).",";
        $query .= (!isset($rawdata->SandP52WeekChange->raw) || !is_numeric($rawdata->SandP52WeekChange->raw)?"NULL":($rawdata->SandP52WeekChange->raw * 100)).",";
        $query .= "NULL,";
        $query .= (!isset($rawdata->fiftyTwoWeekHigh->raw) || !is_numeric($rawdata->fiftyTwoWeekHigh->raw)?"NULL":$rawdata->fiftyTwoWeekHigh->raw).",";
        $query .= "NULL,";
        $query .= (!isset($rawdata->fiftyTwoWeekLow->raw) || !is_numeric($rawdata->fiftyTwoWeekLow->raw)?"NULL":$rawdata->fiftyTwoWeekLow->raw).",";
        $query .= (!isset($rawdata->fiftyDayAverage->raw) || !is_numeric($rawdata->fiftyDayAverage->raw)?"NULL":$rawdata->fiftyDayAverage->raw).",";
        $query .= (!isset($rawdata->twoHundredDayAverage->raw) || !is_numeric($rawdata->twoHundredDayAverage->raw)?"NULL":$rawdata->twoHundredDayAverage->raw).",";
        $query .= (!isset($rawdata->averageVolume->raw) || !is_numeric($rawdata->averageVolume->raw)?"NULL":$rawdata->averageVolume->raw).",";
        $query .= (!isset($rawdata->averageDailyVolume10Day->raw) || !is_numeric($rawdata->averageDailyVolume10Day->raw)?"NULL":$rawdata->averageDailyVolume10Day->raw).",";
        $query .= (!isset($rawdata->sharesOutstanding->raw) || !is_numeric($rawdata->sharesOutstanding->raw)?"NULL":$rawdata->sharesOutstanding->raw).",";
        $query .= (!isset($rawdata->floatShares->raw) || !is_numeric($rawdata->floatShares->raw)?"NULL":$rawdata->floatShares->raw).",";
        $query .= (!isset($rawdata->heldPercentInsiders->raw) || !is_numeric($rawdata->heldPercentInsiders->raw)?"NULL":($rawdata->heldPercentInsiders->raw * 100)).",";
        $query .= (!isset($rawdata->heldPercentInstitutions->raw) || !is_numeric($rawdata->heldPercentInstitutions->raw)?"NULL":($rawdata->heldPercentInstitutions->raw * 100)).",";
        $query .= "NULL,";
        $query .= (!isset($rawdata->sharesShort->raw) || !is_numeric($rawdata->sharesShort->raw)?"NULL":$rawdata->sharesShort->raw).",";
        $query .= (!isset($rawdata->sharesShortPriorMonth->raw) || !is_numeric($rawdata->sharesShortPriorMonth->raw)?"NULL":$rawdata->sharesShortPriorMonth->raw).",";
        $query .= "NULL,";
        $query .= (!isset($rawdata->shortRatio->raw) || !is_numeric($rawdata->shortRatio->raw)?"NULL":$rawdata->shortRatio->raw).",";
        $query .= "NULL,";
        $query .= (!isset($rawdata->shortPercentOfFloat->raw) || !is_numeric($rawdata->shortPercentOfFloat->raw)?"NULL":$rawdata->shortPercentOfFloat->raw).",";
        $query .= (!isset($rawdata->dividendRate->raw) || !is_numeric($rawdata->dividendRate->raw)?"NULL":$rawdata->dividendRate->raw).",";
        $query .= (!isset($rawdata->dividendYield->raw) || !is_numeric($rawdata->dividendYield->raw)?"NULL":($rawdata->dividendYield->raw * 100)).",";
        $query .= (!isset($rawdata->trailingAnnualDividendRate->raw) || !is_numeric($rawdata->trailingAnnualDividendRate->raw)?"NULL":$rawdata->trailingAnnualDividendRate->raw).",";
        $query .= (!isset($rawdata->trailingAnnualDividendYield->raw) || !is_numeric($rawdata->trailingAnnualDividendYield->raw)?"NULL":($rawdata->trailingAnnualDividendYield->raw * 100)).",";
        $query .= (!isset($rawdata->fiveYearAvgDividendYield->raw) || !is_numeric($rawdata->fiveYearAvgDividendYield->raw)?"NULL":$rawdata->fiveYearAvgDividendYield->raw).",";
        $query .= (!isset($rawdata->payoutRatio->raw) || !is_numeric($rawdata->payoutRatio->raw)?"NULL":($rawdata->payoutRatio->raw * 100)).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->dividendDate->fmt))."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->exDividendDate->fmt))."',";
        $query .= "NULL,";
        $query .= "'".str_replace('/', ':', $rawdata->lastSplitFactor)."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->lastSplitDate->fmt))."'";
        $query .= ")";
        mysql_query($query) or die (mysql_error());
}
?>
