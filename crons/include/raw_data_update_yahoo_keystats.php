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
	$query = "INSERT INTO `tickers_yahoo_keystats_1` (`ticker_id` ,`MarketCapIntraday` ,`EnterpriceValueDate` ,`EnterpriceValue` ,`TrailingPETTMIntraday` ,`ForwardPEDate` ,`ForwardPE` ,`PEGRatio5Years` ,`PriceSalesTTM` ,`PriceBookMRQ` ,`EnterpriseValueRevenueTTM` ,`EnterpriseValueEBITDATTM` ,`FiscalYearEnds` ,`MostRecentQuarter` ,`ProfitMarginTTM` ,`OperatingMarginTTM` ,`ReturnOnAssetsTTM` ,`ReturnOnEquityTTM` ,`RevenueTTM` ,`RevenuePerShateTTM` ,`QtrlyRevenueGrowthYOY` ,`GrossProfitTTM` ,`EBITDATTM` ,`NetIncomeAvlToCommonTTM` ,`DilutedEPSTTM` ,`QtrlyEarningsGrowthYOY` ,`TotalCashMRQ` ,`TotalCashPerShareMRQ` ,`TotalDebtMRQ` ,`TotalDebtEquityMRQ` ,`CurrentRatioMRQ` ,`BookValuePerShareMRQ` ,`OperatingCashFlowTTM` ,`LeveredFreeCashFlowTTM`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->MarketCap->content)?"NULL":str_replace(',', '', $rawdata->MarketCap->content)).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->EnterpriseValue->term))."',";
        $query .= (!isset($rawdata->EnterpriseValue->content)?"NULL":str_replace(',', '', $rawdata->EnterpriseValue->content)).",";
        $query .= (!isset($rawdata->TrailingPE->content)?"NULL":str_replace(',', '', $rawdata->TrailingPE->content)).",";
        $query .= "'".date("Y-m-d", strtotime(substr($rawdata->ForwardPE->term, 4)))."',";
        $query .= (!isset($rawdata->ForwardPE->content)?"NULL":str_replace(',', '', $rawdata->ForwardPE->content)).",";
        $query .= (!isset($rawdata->PEGRatio->content)?"NULL":str_replace(',', '', $rawdata->PEGRatio->content)).",";
        $query .= (!isset($rawdata->PriceSales->content)?"NULL":str_replace(',', '', $rawdata->PriceSales->content)).",";
        $query .= (!isset($rawdata->PriceBook->content)?"NULL":str_replace(',', '', $rawdata->PriceBook->content)).",";
        $query .= (!isset($rawdata->EnterpriseValueRevenue->content)?"NULL":str_replace(',', '', $rawdata->EnterpriseValueRevenue->content)).",";
        $query .= (!isset($rawdata->EnterpriseValueEBITDA->content)?"NULL":str_replace(',', '', $rawdata->EnterpriseValueEBITDA->content)).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->FiscalYearEnds))."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->MostRecentQuarter->content))."',";
        $query .= (!isset($rawdata->ProfitMargin->content)?"NULL":str_replace(',', '', $rawdata->ProfitMargin->content)).",";
        $query .= (!isset($rawdata->OperatingMargin->content)?"NULL":str_replace(',', '', $rawdata->OperatingMargin->content)).",";
        $query .= (!isset($rawdata->ReturnonAssets->content)?"NULL":str_replace(',', '', $rawdata->ReturnonAssets->content)).",";
        $query .= (!isset($rawdata->ReturnonEquity->content)?"NULL":str_replace(',', '', $rawdata->ReturnonEquity->content)).",";
        $query .= (!isset($rawdata->Revenue->content)?"NULL":str_replace(',', '', $rawdata->Revenue->content)).",";
        $query .= (!isset($rawdata->RevenuePerShare->content)?"NULL":str_replace(',', '', $rawdata->RevenuePerShare->content)).",";
        $query .= (!isset($rawdata->QtrlyRevenueGrowth->content)?"NULL":str_replace(',', '', $rawdata->QtrlyRevenueGrowth->content)).",";
        $query .= (!isset($rawdata->GrossProfit->content)?"NULL":str_replace(',', '', $rawdata->GrossProfit->content)).",";
        $query .= (!isset($rawdata->EBITDA->content)?"NULL":str_replace(',', '', $rawdata->EBITDA->content)).",";
        $query .= (!isset($rawdata->NetIncomeAvltoCommon->content)?"NULL":str_replace(',', '', $rawdata->NetIncomeAvltoCommon->content)).",";
        $query .= (!isset($rawdata->DilutedEPS->content)?"NULL":str_replace(',', '', $rawdata->DilutedEPS->content)).",";
        $query .= (!isset($rawdata->QtrlyEarningsGrowth->content)?"NULL":str_replace(',', '', $rawdata->QtrlyEarningsGrowth->content)).",";
        $query .= (!isset($rawdata->TotalCash->content)?"NULL":str_replace(',', '', $rawdata->TotalCash->content)).",";
        $query .= (!isset($rawdata->TotalCashPerShare->content)?"NULL":str_replace(',', '', $rawdata->TotalCashPerShare->content)).",";
        $query .= (!isset($rawdata->TotalDebt->content)?"NULL":str_replace(',', '', $rawdata->TotalDebt->content)).",";
        $query .= (!isset($rawdata->TotalDebtEquity->content)?"NULL":str_replace(',', '', $rawdata->TotalDebtEquity->content)).",";
        $query .= (!isset($rawdata->CurrentRatio->content)?"NULL":str_replace(',', '', $rawdata->CurrentRatio->content)).",";
        $query .= (!isset($rawdata->BookValuePerShare->content)?"NULL":str_replace(',', '', $rawdata->BookValuePerShare->content)).",";
        $query .= (!isset($rawdata->OperatingCashFlow->content)?"NULL":str_replace(',', '', $rawdata->OperatingCashFlow->content)).",";
        $query .= (!isset($rawdata->LeveredFreeCashFlow->content)?"NULL":str_replace(',', '', $rawdata->LeveredFreeCashFlow->content));
        $query .= ")";
        mysql_query($query) or die ($query . "\n". mysql_error());

	$query = "INSERT INTO `tickers_yahoo_keystats_2` (`ticker_id` ,`Beta` ,`52WeekChange` ,`52WeekChangeSPS500` ,`52WeekHighDate` ,`52WeekHighValue` ,`52WeekLowDate` ,`52WeekLowValue` ,`50DayMovingAverage` ,`200DayMovingAverage` ,`AvgVolume3Month` ,`AvgVolume10Days` ,`SharesOutstanding` ,`Float` ,`PercentageHeldByInsiders` ,`PercentageHeldByInstitutions` ,`SharesShortDate` ,`SharesShortValue` ,`SharesShortPriorMonth` ,`ShortRatioDate` ,`ShortRatio` ,`ShortPercentageOfFloatDate` ,`ShortPercentageOfFloat` ,`ForwardAnnualDividendRate` ,`ForwardAnnualDividendYield` ,`TrailingAnnualDividendRate` ,`TrailingAnnualDividendYield` ,`5YearAverageDividendYield` ,`PayoutRatio` ,`DividendDate` ,`ExDividendDate` ,`LastSplitFactorTerm` ,`LastSplitFactor` ,`LastSplitDate`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->Beta)?"NULL":str_replace(',', '', $rawdata->Beta)).",";
        $query .= (!isset($rawdata->p_52_WeekChange)?"NULL":str_replace(',', '', $rawdata->p_52_WeekChange)).",";
        $query .= (!isset($rawdata->SP50052_WeekChange)?"NULL":str_replace(',', '', $rawdata->SP50052_WeekChange)).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->p_52_WeekHigh->term))."',";
        $query .= (!isset($rawdata->p_52_WeekHigh->content)?"NULL":str_replace(',', '', $rawdata->p_52_WeekHigh->content)).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->p_52_WeekLow->term))."',";
        $query .= (!isset($rawdata->p_52_WeekLow->content)?"NULL":str_replace(',', '', $rawdata->p_52_WeekLow->content)).",";
        $query .= (!isset($rawdata->p_50_DayMovingAverage)?"NULL":str_replace(',', '', $rawdata->p_50_DayMovingAverage)).",";
        $query .= (!isset($rawdata->p_200_DayMovingAverage)?"NULL":str_replace(',', '', $rawdata->p_200_DayMovingAverage)).",";
        $query .= (!isset($rawdata->AvgVol[0]->content)?"NULL":str_replace(',', '', $rawdata->AvgVol[0]->content)).",";
        $query .= (!isset($rawdata->AvgVol[1]->content)?"NULL":str_replace(',', '', $rawdata->AvgVol[1]->content)).",";
        $query .= (!isset($rawdata->SharesOutstanding)?"NULL":str_replace(',', '', $rawdata->SharesOutstanding)).",";
        $query .= (!isset($rawdata->Float)?"NULL":str_replace(',', '', $rawdata->Float)).",";
        $query .= (!isset($rawdata->PercentageHeldbyInsiders)?"NULL":str_replace(',', '', $rawdata->PercentageHeldbyInsiders)).",";
        $query .= (!isset($rawdata->PercentageHeldbyInstitutions)?"NULL":str_replace(',', '', $rawdata->PercentageHeldbyInstitutions)).",";
        $query .= "'".date("Y-m-d", strtotime(substr($rawdata->SharesShort[0]->term,6)))."',";
        $query .= (!isset($rawdata->SharesShort[0]->content)?"NULL":str_replace(',', '', $rawdata->SharesShort[0]->content)).",";
        $query .= (!isset($rawdata->SharesShort[1]->content)?"NULL":str_replace(',', '', $rawdata->SharesShort[1]->content)).",";
        $query .= "'".date("Y-m-d", strtotime(substr($rawdata->ShortRatio->term,6)))."',";
        $query .= (!isset($rawdata->ShortRatio->content)?"NULL":str_replace(',', '', $rawdata->ShortRatio->content)).",";
        $query .= "'".date("Y-m-d", strtotime(substr($rawdata->ShortPercentageofFloat->term,6)))."',";
        $query .= (!isset($rawdata->ShortPercentageofFloat->content)?"NULL":str_replace(',', '', $rawdata->ShortPercentageofFloat->content)).",";
        $query .= (!isset($rawdata->ForwardAnnualDividendRate)?"NULL":str_replace(',', '', $rawdata->ForwardAnnualDividendRate)).",";
        $query .= (!isset($rawdata->ForwardAnnualDividendYield)?"NULL":str_replace(',', '', $rawdata->ForwardAnnualDividendYield)).",";
        $query .= (!isset($rawdata->TrailingAnnualDividendYield[0])?"NULL":str_replace(',', '', $rawdata->TrailingAnnualDividendYield[0])).",";
        $query .= (!isset($rawdata->TrailingAnnualDividendYield[1])?"NULL":str_replace(',', '', $rawdata->TrailingAnnualDividendYield[1])).",";
        $query .= (!isset($rawdata->p_5YearAverageDividendYield)?"NULL":str_replace(',', '', $rawdata->p_5YearAverageDividendYield)).",";
        $query .= (!isset($rawdata->PayoutRatio)?"NULL":str_replace(',', '', $rawdata->PayoutRatio)).",";
        $query .= "'".date("Y-m-d", strtotime($rawdata->DividendDate))."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->Ex_DividendDate))."',";
        $query .= "'".$rawdata->LastSplitFactor->term."',";
        $query .= "'".$rawdata->LastSplitFactor->content."',";
        $query .= "'".date("Y-m-d", strtotime($rawdata->LastSplitDate))."'";
        $query .= ")";
        mysql_query($query) or die (mysql_error());
}
?>
