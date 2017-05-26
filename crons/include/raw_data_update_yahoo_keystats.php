<?php
function update_raw_data_yahoo_keystats($ticker_id, $rawdata) {
    $db = Database::GetInstance();

    //Update yahoo keystats tables
    //tickers_yahoo_keystats_1
    $query = "INSERT INTO `tickers_yahoo_keystats_1` (`ticker_id` ,`MarketCapIntraday` ,`EnterpriseValueDate` ,`EnterpriseValue` ,`TrailingPETTMIntraday` ,`ForwardPEDate` ,`ForwardPE` ,`PEGRatio5Years` ,`PriceSalesTTM` ,`PriceBookMRQ` ,`EnterpriseValueRevenueTTM` ,`EnterpriseValueEBITDATTM` ,`FiscalYearEnds` ,`MostRecentQuarter` ,`ProfitMarginTTM` ,`OperatingMarginTTM` ,`ReturnOnAssetsTTM` ,`ReturnOnEquityTTM` ,`RevenueTTM` ,`RevenuePerShateTTM` ,`QtrlyRevenueGrowthYOY` ,`GrossProfitTTM` ,`EBITDATTM` ,`NetIncomeAvlToCommonTTM` ,`DilutedEPSTTM` ,`QtrlyEarningsGrowthYOY` ,`TotalCashMRQ` ,`TotalCashPerShareMRQ` ,`TotalDebtMRQ` ,`TotalDebtEquityMRQ` ,`CurrentRatioMRQ` ,`BookValuePerShareMRQ` ,`OperatingCashFlowTTM` ,`LeveredFreeCashFlowTTM`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `MarketCapIntraday`=? ,`EnterpriseValueDate`=? ,`EnterpriseValue`=? ,`TrailingPETTMIntraday`=? ,`ForwardPEDate`=? ,`ForwardPE`=? ,`PEGRatio5Years` =?,`PriceSalesTTM`=? ,`PriceBookMRQ`=? ,`EnterpriseValueRevenueTTM`=? ,`EnterpriseValueEBITDATTM`=? ,`FiscalYearEnds`=? ,`MostRecentQuarter`=? ,`ProfitMarginTTM`=? ,`OperatingMarginTTM`=? ,`ReturnOnAssetsTTM`=? ,`ReturnOnEquityTTM`=? ,`RevenueTTM`=? ,`RevenuePerShateTTM`=? ,`QtrlyRevenueGrowthYOY`=? ,`GrossProfitTTM`=? ,`EBITDATTM`=? ,`NetIncomeAvlToCommonTTM`=? ,`DilutedEPSTTM`=? ,`QtrlyEarningsGrowthYOY`=? ,`TotalCashMRQ`=? ,`TotalCashPerShareMRQ`=? ,`TotalDebtMRQ`=? ,`TotalDebtEquityMRQ`=? ,`CurrentRatioMRQ`=? ,`BookValuePerShareMRQ`=? ,`OperatingCashFlowTTM`=? ,`LeveredFreeCashFlowTTM`=?";
    $params = array();
    $params[] = (!isset($rawdata->marketCap->raw) || !is_numeric($rawdata->marketCap->raw)?NULL:$rawdata->marketCap->raw);
    $params[] = NULL;
    $params[] = (!isset($rawdata->enterpriseValue->raw) || !is_numeric($rawdata->enterpriseValue->raw)?NULL:$rawdata->enterpriseValue->raw);
    $params[] = (!isset($rawdata->trailingPE->raw) || !is_numeric($rawdata->trailingPE->raw)?NULL:$rawdata->trailingPE->raw);
    $params[] = NULL;
    $params[] = (!isset($rawdata->forwardPE->raw) || !is_numeric($rawdata->forwardPE->raw)?NULL:$rawdata->forwardPE->raw);
    $params[] = (!isset($rawdata->pegRatio->raw) || !is_numeric($rawdata->pegRatio->raw)?NULL:$rawdata->pegRatio->raw);
    $params[] = (!isset($rawdata->priceToSalesTrailing12Months->raw) || !is_numeric($rawdata->priceToSalesTrailing12Months->raw)?NULL:$rawdata->priceToSalesTrailing12Months->raw);
    $params[] = (!isset($rawdata->priceToBook->raw) || !is_numeric($rawdata->priceToBook->raw)?NULL:$rawdata->priceToBook->raw);
    $params[] = (!isset($rawdata->enterpriseToRevenue->raw) || !is_numeric($rawdata->enterpriseToRevenue->raw)?NULL:$rawdata->enterpriseToRevenue->raw);
    $params[] = (!isset($rawdata->enterpriseToEbitda->raw) || !is_numeric($rawdata->enterpriseToEbitda->raw)?NULL:$rawdata->enterpriseToEbitda->raw);
    $params[] = (!isset($rawdata->lastFiscalYearEnd->fmt)?NULL:date("Y-m-d", strtotime($rawdata->lastFiscalYearEnd->fmt)));
    $params[] = (!isset($rawdata->mostRecentQuarter->fmt)?NULL:date("Y-m-d", strtotime($rawdata->mostRecentQuarter->fmt)));
    $params[] = (!isset($rawdata->profitMargins->raw) || !is_numeric($rawdata->profitMargins->raw)?NULL:($rawdata->profitMargins->raw * 100));
    $params[] = (!isset($rawdata->operatingMargins->raw) || !is_numeric($rawdata->operatingMargins->raw)?NULL:($rawdata->operatingMargins->raw * 100));
    $params[] = (!isset($rawdata->returnOnAssets->raw) || !is_numeric($rawdata->returnOnAssets->raw)?NULL:($rawdata->returnOnAssets->raw * 100));
    $params[] = (!isset($rawdata->returnOnEquity->raw) || !is_numeric($rawdata->returnOnEquity->raw)?NULL:($rawdata->returnOnEquity->raw * 100));
    $params[] = (!isset($rawdata->totalRevenue->raw) || !is_numeric($rawdata->totalRevenue->raw)?NULL:$rawdata->totalRevenue->raw);
    $params[] = (!isset($rawdata->revenuePerShare->raw) || !is_numeric($rawdata->revenuePerShare->raw)?NULL:$rawdata->revenuePerShare->raw);
    $params[] = (!isset($rawdata->revenueGrowth->raw) || !is_numeric($rawdata->revenueGrowth->raw)?NULL:($rawdata->revenueGrowth->raw * 100));
    $params[] = (!isset($rawdata->grossProfits->raw) || !is_numeric($rawdata->grossProfits->raw)?NULL:$rawdata->grossProfits->raw);
    $params[] = (!isset($rawdata->ebitda->raw) || !is_numeric($rawdata->ebitda->raw)?NULL:$rawdata->ebitda->raw);
    $params[] = (!isset($rawdata->netIncomeToCommon->raw) || !is_numeric($rawdata->netIncomeToCommon->raw)?NULL:$rawdata->netIncomeToCommon->raw);
    $params[] = (!isset($rawdata->trailingEps->raw) || !is_numeric($rawdata->trailingEps->raw)?NULL:$rawdata->trailingEps->raw);
    $params[] = (!isset($rawdata->earningsQuarterlyGrowth->raw) || !is_numeric($rawdata->earningsQuarterlyGrowth->raw)?NULL:($rawdata->earningsQuarterlyGrowth->raw * 100));
    $params[] = (!isset($rawdata->totalCash->raw) || !is_numeric($rawdata->totalCash->raw)?NULL:$rawdata->totalCash->raw);
    $params[] = (!isset($rawdata->totalCashPerShare->raw) || !is_numeric($rawdata->totalCashPerShare->raw)?NULL:$rawdata->totalCashPerShare->raw);
    $params[] = (!isset($rawdata->totalDebt->raw) || !is_numeric($rawdata->totalDebt->raw)?NULL:$rawdata->totalDebt->raw);
    $params[] = (!isset($rawdata->debtToEquity->raw) || !is_numeric($rawdata->debtToEquity->raw)?NULL:$rawdata->debtToEquity->raw);
    $params[] = (!isset($rawdata->currentRatio->raw) || !is_numeric($rawdata->currentRatio->raw)?NULL:$rawdata->currentRatio->raw);
    $params[] = (!isset($rawdata->bookValue->raw) || !is_numeric($rawdata->bookValue->raw)?NULL:$rawdata->bookValue->raw);
    $params[] = (!isset($rawdata->operatingCashflow->raw) || !is_numeric($rawdata->operatingCashflow->raw)?NULL:$rawdata->operatingCashflow->raw);
    $params[] = (!isset($rawdata->freeCashflow->raw) || !is_numeric($rawdata->freeCashflow->raw)?NULL:$rawdata->freeCashflow->raw);
    $params = array_merge($params,$params);
    array_unshift($params,$ticker_id);

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }

    $query = "INSERT INTO `tickers_yahoo_keystats_2` (`ticker_id` ,`Beta` ,`52WeekChange` ,`52WeekChangeSPS500` ,`52WeekHighDate` ,`52WeekHighValue` ,`52WeekLowDate` ,`52WeekLowValue` ,`50DayMovingAverage` ,`200DayMovingAverage` ,`AvgVolume3Month` ,`AvgVolume10Days` ,`SharesOutstanding` ,`Float` ,`PercentageHeldByInsiders` ,`PercentageHeldByInstitutions` ,`SharesShortDate` ,`SharesShortValue` ,`SharesShortPriorMonth` ,`ShortRatioDate` ,`ShortRatio` ,`ShortPercentageOfFloatDate` ,`ShortPercentageOfFloat` ,`ForwardAnnualDividendRate` ,`ForwardAnnualDividendYield` ,`TrailingAnnualDividendRate` ,`TrailingAnnualDividendYield` ,`5YearAverageDividendYield` ,`PayoutRatio` ,`DividendDate` ,`ExDividendDate` ,`LastSplitFactorTerm` ,`LastSplitFactor` ,`LastSplitDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `Beta` = ? ,`52WeekChange` = ? ,`52WeekChangeSPS500` = ? ,`50DayMovingAverage` = ? ,`200DayMovingAverage` = ? ,`SharesOutstanding` = ? ,`Float` = ? ,`PercentageHeldByInsiders` = ? ,`PercentageHeldByInstitutions` = ? ,`SharesShortDate` = ? ,`SharesShortValue` = ? ,`SharesShortPriorMonth` = ? ,`ShortRatioDate` = ? ,`ShortRatio` = ? ,`ShortPercentageOfFloatDate` = ? ,`ShortPercentageOfFloat` = ? ,`TrailingAnnualDividendRate` = ? ,`TrailingAnnualDividendYield` = ? ,`5YearAverageDividendYield` = ? ,`PayoutRatio` = ? ,`DividendDate` = ? ,`LastSplitFactorTerm` = ? ,`LastSplitFactor` = ? ,`LastSplitDate` = ?, `ForwardAnnualDividendRate` = ?,`ForwardAnnualDividendYield` =?";
    $params = array();
    $params[] = $ticker_id;
    $params[] = (!isset($rawdata->beta->raw) || !is_numeric($rawdata->beta->raw)?NULL:$rawdata->beta->raw);
    $params[] = (!isset($rawdata->_2WeekChange->raw) || !is_numeric($rawdata->_2WeekChange->raw)?NULL:($rawdata->_2WeekChange->raw * 100));
    $params[] = (!isset($rawdata->SandP52WeekChange->raw) || !is_numeric($rawdata->SandP52WeekChange->raw)?NULL:($rawdata->SandP52WeekChange->raw * 100));
    $params[] = NULL;
    $params[] = (!isset($rawdata->fiftyTwoWeekHigh->raw) || !is_numeric($rawdata->fiftyTwoWeekHigh->raw)?NULL:$rawdata->fiftyTwoWeekHigh->raw);
    $params[] = NULL;
    $params[] = (!isset($rawdata->fiftyTwoWeekLow->raw) || !is_numeric($rawdata->fiftyTwoWeekLow->raw)?NULL:$rawdata->fiftyTwoWeekLow->raw);
    $params[] = (!isset($rawdata->fiftyDayAverage->raw) || !is_numeric($rawdata->fiftyDayAverage->raw)?NULL:$rawdata->fiftyDayAverage->raw);
    $params[] = (!isset($rawdata->twoHundredDayAverage->raw) || !is_numeric($rawdata->twoHundredDayAverage->raw)?NULL:$rawdata->twoHundredDayAverage->raw);
    $params[] = (!isset($rawdata->averageVolume->raw) || !is_numeric($rawdata->averageVolume->raw)?NULL:$rawdata->averageVolume->raw);
    $params[] = (!isset($rawdata->averageDailyVolume10Day->raw) || !is_numeric($rawdata->averageDailyVolume10Day->raw)?NULL:$rawdata->averageDailyVolume10Day->raw);
    $params[] = (!isset($rawdata->sharesOutstanding->raw) || !is_numeric($rawdata->sharesOutstanding->raw)?NULL:$rawdata->sharesOutstanding->raw);
    $params[] = (!isset($rawdata->floatShares->raw) || !is_numeric($rawdata->floatShares->raw)?NULL:$rawdata->floatShares->raw);
    $params[] = (!isset($rawdata->heldPercentInsiders->raw) || !is_numeric($rawdata->heldPercentInsiders->raw)?NULL:($rawdata->heldPercentInsiders->raw * 100));
    $params[] = (!isset($rawdata->heldPercentInstitutions->raw) || !is_numeric($rawdata->heldPercentInstitutions->raw)?NULL:($rawdata->heldPercentInstitutions->raw * 100));
    $params[] = NULL;
    $params[] = (!isset($rawdata->sharesShort->raw) || !is_numeric($rawdata->sharesShort->raw)?NULL:$rawdata->sharesShort->raw);
    $params[] = (!isset($rawdata->sharesShortPriorMonth->raw) || !is_numeric($rawdata->sharesShortPriorMonth->raw)?NULL:$rawdata->sharesShortPriorMonth->raw);
    $params[] = NULL;
    $params[] = (!isset($rawdata->shortRatio->raw) || !is_numeric($rawdata->shortRatio->raw)?NULL:$rawdata->shortRatio->raw);
    $params[] = NULL;
    $params[] = (!isset($rawdata->shortPercentOfFloat->raw) || !is_numeric($rawdata->shortPercentOfFloat->raw)?NULL:$rawdata->shortPercentOfFloat->raw);
    $params[] = (!isset($rawdata->dividendRate->raw) || !is_numeric($rawdata->dividendRate->raw)?NULL:$rawdata->dividendRate->raw);
    $params[] = (!isset($rawdata->dividendYield->raw) || !is_numeric($rawdata->dividendYield->raw)?NULL:($rawdata->dividendYield->raw * 100));
    $params[] = (!isset($rawdata->trailingAnnualDividendRate->raw) || !is_numeric($rawdata->trailingAnnualDividendRate->raw)?NULL:$rawdata->trailingAnnualDividendRate->raw);
    $params[] = (!isset($rawdata->trailingAnnualDividendYield->raw) || !is_numeric($rawdata->trailingAnnualDividendYield->raw)?NULL:($rawdata->trailingAnnualDividendYield->raw * 100));
    $params[] = (!isset($rawdata->fiveYearAvgDividendYield->raw) || !is_numeric($rawdata->fiveYearAvgDividendYield->raw)?NULL:$rawdata->fiveYearAvgDividendYield->raw);
    $params[] = (!isset($rawdata->payoutRatio->raw) || !is_numeric($rawdata->payoutRatio->raw)?NULL:($rawdata->payoutRatio->raw * 100));
    $params[] = (!isset($rawdata->dividendDate->fmt))?NULL:date("Y-m-d", strtotime($rawdata->dividendDate->fmt));
    $params[] = (!isset($rawdata->exDividendDate->fmt))?NULL:date("Y-m-d", strtotime($rawdata->exDividendDate->fmt));
    $params[] = NULL;
    $params[] = (!isset($rawdata->lastSplitFactor)?NULL:str_replace('/', ':', $rawdata->lastSplitFactor));
    $params[] = (!isset($rawdata->lastSplitDate->fmt))?NULL:date("Y-m-d", strtotime($rawdata->lastSplitDate->fmt));

    $params[] = (!isset($rawdata->beta->raw) || !is_numeric($rawdata->beta->raw)?NULL:$rawdata->beta->raw);
    $params[] = (!isset($rawdata->_2WeekChange->raw) || !is_numeric($rawdata->_2WeekChange->raw)?NULL:($rawdata->_2WeekChange->raw * 100));
    $params[] = (!isset($rawdata->SandP52WeekChange->raw) || !is_numeric($rawdata->SandP52WeekChange->raw)?NULL:($rawdata->SandP52WeekChange->raw * 100));
    $params[] = (!isset($rawdata->fiftyDayAverage->raw) || !is_numeric($rawdata->fiftyDayAverage->raw)?NULL:$rawdata->fiftyDayAverage->raw);
    $params[] = (!isset($rawdata->twoHundredDayAverage->raw) || !is_numeric($rawdata->twoHundredDayAverage->raw)?NULL:$rawdata->twoHundredDayAverage->raw);
    $params[] = (!isset($rawdata->sharesOutstanding->raw) || !is_numeric($rawdata->sharesOutstanding->raw)?NULL:$rawdata->sharesOutstanding->raw);
    $params[] = (!isset($rawdata->floatShares->raw) || !is_numeric($rawdata->floatShares->raw)?NULL:$rawdata->floatShares->raw);
    $params[] = (!isset($rawdata->heldPercentInsiders->raw) || !is_numeric($rawdata->heldPercentInsiders->raw)?NULL:($rawdata->heldPercentInsiders->raw * 100));
    $params[] = (!isset($rawdata->heldPercentInstitutions->raw) || !is_numeric($rawdata->heldPercentInstitutions->raw)?NULL:($rawdata->heldPercentInstitutions->raw * 100));
    $params[] = NULL;
    $params[] = (!isset($rawdata->sharesShort->raw) || !is_numeric($rawdata->sharesShort->raw)?NULL:$rawdata->sharesShort->raw);
    $params[] = (!isset($rawdata->sharesShortPriorMonth->raw) || !is_numeric($rawdata->sharesShortPriorMonth->raw)?NULL:$rawdata->sharesShortPriorMonth->raw);
    $params[] = NULL;
    $params[] = (!isset($rawdata->shortRatio->raw) || !is_numeric($rawdata->shortRatio->raw)?NULL:$rawdata->shortRatio->raw);
    $params[] = NULL;
    $params[] = (!isset($rawdata->shortPercentOfFloat->raw) || !is_numeric($rawdata->shortPercentOfFloat->raw)?NULL:$rawdata->shortPercentOfFloat->raw);
    $params[] = (!isset($rawdata->trailingAnnualDividendRate->raw) || !is_numeric($rawdata->trailingAnnualDividendRate->raw)?NULL:$rawdata->trailingAnnualDividendRate->raw);
    $params[] = (!isset($rawdata->trailingAnnualDividendYield->raw) || !is_numeric($rawdata->trailingAnnualDividendYield->raw)?NULL:($rawdata->trailingAnnualDividendYield->raw * 100));
    $params[] = (!isset($rawdata->fiveYearAvgDividendYield->raw) || !is_numeric($rawdata->fiveYearAvgDividendYield->raw)?NULL:$rawdata->fiveYearAvgDividendYield->raw);
    $params[] = (!isset($rawdata->payoutRatio->raw) || !is_numeric($rawdata->payoutRatio->raw)?NULL:($rawdata->payoutRatio->raw * 100));
    $params[] = (!isset($rawdata->dividendDate->fmt))?NULL:date("Y-m-d", strtotime($rawdata->dividendDate->fmt));
    $params[] = NULL;
    $params[] = (!isset($rawdata->lastSplitFactor)?NULL:str_replace('/', ':', $rawdata->lastSplitFactor));
    $params[] = (!isset($rawdata->lastSplitDate->fmt))?NULL:date("Y-m-d", strtotime($rawdata->lastSplitDate->fmt));
    $params[] = (!isset($rawdata->dividendRate->raw) || !is_numeric($rawdata->dividendRate->raw)?NULL:$rawdata->dividendRate->raw);
    $params[] = (!isset($rawdata->dividendYield->raw) || !is_numeric($rawdata->dividendYield->raw)?NULL:($rawdata->dividendYield->raw * 100));

    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
}

function update_raw_data_barchart_keystats($ticker_id, $resOD) {
    //Updating Keystats 2 from Barchart
    $db = Database::GetInstance();

    $query = "INSERT INTO `tickers_yahoo_keystats_2` (`ticker_id`, `52WeekHighDate` ,`52WeekHighValue` ,`52WeekLowDate` ,`52WeekLowValue` ,`AvgVolume10Days` , `ExDividendDate`, `AvgVolume3Month`) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `52WeekHighDate`= ? ,`52WeekHighValue` = ? ,`52WeekLowDate` = ? ,`52WeekLowValue` = ? ,`AvgVolume10Days` = ? , `ExDividendDate` = ?, `AvgVolume3Month` = ?";
    $params = array();
    $params[] = $ticker_id;
    $params[] = $resOD['results'][0]['fiftyTwoWkHighDate'];
    $params[] = $resOD['results'][0]['fiftyTwoWkHigh'];
    $params[] = $resOD['results'][0]['fiftyTwoWkLowDate'];
    $params[] = $resOD['results'][0]['fiftyTwoWkLow'];
    $params[] = $resOD['results'][0]['twentyDayAvgVol'];
    $params[] = $resOD['results'][0]['exDividendDate'];
    $params[] = $resOD['results'][0]['averageQuarterlyVolume'];

    $params[] = $resOD['results'][0]['fiftyTwoWkHighDate'];
    $params[] = $resOD['results'][0]['fiftyTwoWkHigh'];
    $params[] = $resOD['results'][0]['fiftyTwoWkLowDate'];
    $params[] = $resOD['results'][0]['fiftyTwoWkLow'];
    $params[] = $resOD['results'][0]['twentyDayAvgVol'];
    $params[] = $resOD['results'][0]['exDividendDate'];
    $params[] = $resOD['results'][0]['averageQuarterlyVolume'];
    try {
        $res = $db->prepare($query);
        $res->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
}
?>
