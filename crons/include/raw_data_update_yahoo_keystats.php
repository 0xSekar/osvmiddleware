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
        $query .= (!is_numeric($rawdata->marketCap->raw) || !isset($rawdata->marketCap->raw)?"NULL":$rawdata->marketCap->raw).",";
        $query .= "NULL,";
        $query .= (!is_numeric($rawdata->enterpriseValue->raw) || !isset($rawdata->enterpriseValue->raw)?"NULL":$rawdata->enterpriseValue->raw).",";
        $query .= (!is_numeric($rawdata->trailingPE->raw) || !isset($rawdata->trailingPE->raw)?"NULL":$rawdata->trailingPE->raw).",";
        $query .= "NULL,";
        $query .= (!is_numeric($rawdata->forwardPE->raw) || !isset($rawdata->forwardPE->raw)?"NULL":$rawdata->forwardPE->raw).",";
        $query .= (!is_numeric($rawdata->pegRatio->raw) || !isset($rawdata->pegRatio->raw)?"NULL":$rawdata->pegRatio->raw).",";
        $query .= (!is_numeric($rawdata->priceToSalesTrailing12Months->raw) || !isset($rawdata->priceToSalesTrailing12Months->raw)?"NULL":$rawdata->priceToSalesTrailing12Months->raw).",";
        $query .= (!is_numeric($rawdata->priceToBook->raw) || !isset($rawdata->priceToBook->raw)?"NULL":$rawdata->priceToBook->raw).",";
        $query .= (!is_numeric($rawdata->enterpriseToRevenue->raw) || !isset($rawdata->enterpriseToRevenue->raw)?"NULL":$rawdata->enterpriseToRevenue->raw).",";
        $query .= (!is_numeric($rawdata->enterpriseToEbitda->raw) || !isset($rawdata->enterpriseToEbitda->raw)?"NULL":$rawdata->enterpriseToEbitda->raw).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->lastFiscalYearEnd->fmt))."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->mostRecentQuarter->fmt))."',";
        $query .= (!is_numeric($rawdata->profitMargins->raw) || !isset($rawdata->profitMargins->raw)?"NULL":($rawdata->profitMargins->raw * 100)).",";
        $query .= (!is_numeric($rawdata->operatingMargins->raw) || !isset($rawdata->operatingMargins->raw)?"NULL":($rawdata->operatingMargins->raw * 100)).",";
        $query .= (!is_numeric($rawdata->returnOnAssets->raw) || !isset($rawdata->returnOnAssets->raw)?"NULL":($rawdata->returnOnAssets->raw * 100)).",";
        $query .= (!is_numeric($rawdata->returnOnEquity->raw) || !isset($rawdata->returnOnEquity->raw)?"NULL":($rawdata->returnOnEquity->raw * 100)).",";
        $query .= (!is_numeric($rawdata->totalRevenue->raw) || !isset($rawdata->totalRevenue->raw)?"NULL":$rawdata->totalRevenue->raw).",";
        $query .= (!is_numeric($rawdata->revenuePerShare->raw) || !isset($rawdata->revenuePerShare->raw)?"NULL":$rawdata->revenuePerShare->raw).",";
        $query .= (!is_numeric($rawdata->revenueGrowth->raw) || !isset($rawdata->revenueGrowth->raw)?"NULL":($rawdata->revenueGrowth->raw * 100)).",";
        $query .= (!is_numeric($rawdata->grossProfits->raw) || !isset($rawdata->grossProfits->raw)?"NULL":$rawdata->grossProfits->raw).",";
        $query .= (!is_numeric($rawdata->ebitda->raw) || !isset($rawdata->ebitda->raw)?"NULL":$rawdata->ebitda->raw).",";
        $query .= (!is_numeric($rawdata->netIncomeToCommon->raw) || !isset($rawdata->netIncomeToCommon->raw)?"NULL":$rawdata->netIncomeToCommon->raw).",";
        $query .= (!is_numeric($rawdata->trailingEps->raw) || !isset($rawdata->trailingEps->raw)?"NULL":$rawdata->trailingEps->raw).",";
        $query .= (!is_numeric($rawdata->earningsQuarterlyGrowth->raw) || !isset($rawdata->earningsQuarterlyGrowth->raw)?"NULL":($rawdata->earningsQuarterlyGrowth->raw * 100)).",";
        $query .= (!is_numeric($rawdata->totalCash->raw) || !isset($rawdata->totalCash->raw)?"NULL":$rawdata->totalCash->raw).",";
        $query .= (!is_numeric($rawdata->totalCashPerShare->raw) || !isset($rawdata->totalCashPerShare->raw)?"NULL":$rawdata->totalCashPerShare->raw).",";
        $query .= (!is_numeric($rawdata->totalDebt->raw) || !isset($rawdata->totalDebt->raw)?"NULL":$rawdata->totalDebt->raw).",";
        $query .= (!is_numeric($rawdata->debtToEquity->raw) || !isset($rawdata->debtToEquity->raw)?"NULL":$rawdata->debtToEquity->raw).",";
        $query .= (!is_numeric($rawdata->currentRatio->raw) || !isset($rawdata->currentRatio->raw)?"NULL":$rawdata->currentRatio->raw).",";
        $query .= (!is_numeric($rawdata->bookValue->raw) || !isset($rawdata->bookValue->raw)?"NULL":$rawdata->bookValue->raw).",";
        $query .= (!is_numeric($rawdata->operatingCashflow->raw) || !isset($rawdata->operatingCashflow->raw)?"NULL":$rawdata->operatingCashflow->raw).",";
        $query .= (!is_numeric($rawdata->freeCashflow->raw) || !isset($rawdata->freeCashflow->raw)?"NULL":$rawdata->freeCashflow->raw);
        $query .= ")";
        mysql_query($query) or die ($query . "\n". mysql_error());

	$query = "INSERT INTO `tickers_yahoo_keystats_2` (`ticker_id` ,`Beta` ,`52WeekChange` ,`52WeekChangeSPS500` ,`52WeekHighDate` ,`52WeekHighValue` ,`52WeekLowDate` ,`52WeekLowValue` ,`50DayMovingAverage` ,`200DayMovingAverage` ,`AvgVolume3Month` ,`AvgVolume10Days` ,`SharesOutstanding` ,`Float` ,`PercentageHeldByInsiders` ,`PercentageHeldByInstitutions` ,`SharesShortDate` ,`SharesShortValue` ,`SharesShortPriorMonth` ,`ShortRatioDate` ,`ShortRatio` ,`ShortPercentageOfFloatDate` ,`ShortPercentageOfFloat` ,`ForwardAnnualDividendRate` ,`ForwardAnnualDividendYield` ,`TrailingAnnualDividendRate` ,`TrailingAnnualDividendYield` ,`5YearAverageDividendYield` ,`PayoutRatio` ,`DividendDate` ,`ExDividendDate` ,`LastSplitFactorTerm` ,`LastSplitFactor` ,`LastSplitDate`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!is_numeric($rawdata->Beta->raw) || !isset($rawdata->Beta->raw)?"NULL":$rawdata->Beta->raw).",";
        $query .= (!is_numeric($rawdata->_2WeekChange->raw) || !isset($rawdata->_2WeekChange->raw)?"NULL":($rawdata->_2WeekChange->raw * 100)).",";
        $query .= (!is_numeric($rawdata->SandP52WeekChange->raw) || !isset($rawdata->SandP52WeekChange->raw)?"NULL":($rawdata->SandP52WeekChange->raw * 100)).",";
        $query .= "NULL,";
        $query .= (!is_numeric($rawdata->fiftyTwoWeekHigh->raw) || !isset($rawdata->fiftyTwoWeekHigh->raw)?"NULL":$rawdata->fiftyTwoWeekHigh->raw).",";
        $query .= "NULL,";
        $query .= (!is_numeric($rawdata->fiftyTwoWeekLow->raw) || !isset($rawdata->fiftyTwoWeekLow->raw)?"NULL":$rawdata->fiftyTwoWeekLow->raw).",";
        $query .= (!is_numeric($rawdata->fiftyDayAverage->raw) || !isset($rawdata->fiftyDayAverage->raw)?"NULL":$rawdata->fiftyDayAverage->raw).",";
        $query .= (!is_numeric($rawdata->twoHundredDayAverage->raw) || !isset($rawdata->twoHundredDayAverage->raw)?"NULL":$rawdata->twoHundredDayAverage->raw).",";
        $query .= (!is_numeric($rawdata->averageVolume->raw) || !isset($rawdata->averageVolume->raw)?"NULL":$rawdata->averageVolume->raw).",";
        $query .= (!is_numeric($rawdata->averageDailyVolume10Day->raw) || !isset($rawdata->averageDailyVolume10Day->raw)?"NULL":$rawdata->averageDailyVolume10Day->raw).",";
        $query .= (!is_numeric($rawdata->sharesOutstanding->raw) || !isset($rawdata->sharesOutstanding->raw)?"NULL":$rawdata->sharesOutstanding->raw).",";
        $query .= (!is_numeric($rawdata->floatShares->raw) || !isset($rawdata->floatShares->raw)?"NULL":$rawdata->floatShares->raw).",";
        $query .= (!is_numeric($rawdata->heldPercentInsiders->raw) || !isset($rawdata->heldPercentInsiders->raw)?"NULL":($rawdata->heldPercentInsiders->raw * 100)).",";
        $query .= (!is_numeric($rawdata->heldPercentInstitutions->raw) || !isset($rawdata->heldPercentInstitutions->raw)?"NULL":($rawdata->heldPercentInstitutions->raw * 100)).",";
        $query .= "NULL,";
        $query .= (!is_numeric($rawdata->sharesShort->raw) || !isset($rawdata->sharesShort->raw)?"NULL":$rawdata->sharesShort->raw).",";
        $query .= (!is_numeric($rawdata->sharesShortPriorMonth->raw) || !isset($rawdata->sharesShortPriorMonth->raw)?"NULL":$rawdata->sharesShortPriorMonth->raw).",";
        $query .= "NULL,";
        $query .= (!is_numeric($rawdata->shortRatio->raw) || !isset($rawdata->shortRatio->raw)?"NULL":$rawdata->shortRatio->raw).",";
        $query .= "NULL,";
        $query .= (!is_numeric($rawdata->shortPercentOfFloat->raw) || !isset($rawdata->shortPercentOfFloat->raw)?"NULL":$rawdata->shortPercentOfFloat->raw).",";
        $query .= (!is_numeric($rawdata->dividendRate->raw) || !isset($rawdata->dividendRate->raw)?"NULL":$rawdata->dividendRate->raw).",";
        $query .= (!is_numeric($rawdata->dividendYield->raw) || !isset($rawdata->dividendYield->raw)?"NULL":($rawdata->dividendYield->raw * 100)).",";
        $query .= (!is_numeric($rawdata->trailingAnnualDividendRate->raw) || !isset($rawdata->trailingAnnualDividendRate->raw)?"NULL":$rawdata->trailingAnnualDividendRate->raw).",";
        $query .= (!is_numeric($rawdata->trailingAnnualDividendYield->raw) || !isset($rawdata->trailingAnnualDividendYield->raw)?"NULL":($rawdata->trailingAnnualDividendYield->raw * 100)).",";
        $query .= (!is_numeric($rawdata->fiveYearAvgDividendYield->raw) || !isset($rawdata->fiveYearAvgDividendYield->raw)?"NULL":$rawdata->fiveYearAvgDividendYield->raw).",";
        $query .= (!is_numeric($rawdata->payoutRatio->raw) || !isset($rawdata->payoutRatio->raw)?"NULL":($rawdata->payoutRatio->raw * 100)).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->dividendDate->fmt))."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->exDividendDate->fmt))."',";
        $query .= "NULL,";
        $query .= "'".str_replace('/', ':', $rawdata->lastSplitFactor)."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->lastSplitDate->fmt))."'";
        $query .= ")";
        mysql_query($query) or die (mysql_error());
}
?>
