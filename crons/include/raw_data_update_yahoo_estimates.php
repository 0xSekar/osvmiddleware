<?php
function update_raw_data_yahoo_estimates($ticker_id, $dates, $rawdata) {
	$tables = array("tickers_yahoo_estimates_curr_qtr","tickers_yahoo_estimates_curr_year","tickers_yahoo_estimates_earn_hist", "tickers_yahoo_estimates_next_qtr", "tickers_yahoo_estimates_next_year", "tickers_yahoo_estimates_others");

        //Delete all reports before updating to be sure we do not miss any manual update
        //as this is a batch process, it will not impact on the UE
        foreach($tables as $table) {
                $query = "DELETE FROM $table WHERE ticker_id = ".$ticker_id;
                mysql_query($query) or die (mysql_error());
        }

        //Update yahoo estimates tables
        //tickers_yahoo_estimates_curr_qtr
	$query = "INSERT INTO `tickers_yahoo_estimates_curr_qtr` (`ticker_id`, `report_date`, `EarningsAvg`, `EarningsNoof`, `EarningsLow`, `EarningsHigh`, `EarningsYAEPS`, `RevenueAvg`, `RevenueNoof`, `RevenueLow`, `RevenueHigh`, `RevenueYASales`, `RevenueSalesGrowth`, `EPSTrendCurrentEst`, `EPSTrend7daysEst`, `EPSTrend30daysEst`, `EPSTrend60daysEst`, `EPSTrend90daysEst`, `EPSRevUp7days`, `EPSRevUp30days`, `EPSRevDown30days`, `EPSRevDown90days`, `GrowthEstTicker`, `GrowthEstIndustry`, `GrowthEstSector`, `GrowthEstSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= "'".$dates->currQtrDate."',";
        $query .= (is_null($rawdata->EarningsEst->AvgEstimate->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->AvgEstimate->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->NoofAnalysts->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->NoofAnalysts->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->LowEstimate->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->LowEstimate->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->HighEstimate->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->HighEstimate->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->YearAgoEPS->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->YearAgoEPS->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->AvgEstimate->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->AvgEstimate->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->NoofAnalysts->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->NoofAnalysts->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->LowEstimate->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->LowEstimate->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->HighEstimate->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->HighEstimate->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->YearAgoSales->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->YearAgoSales->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->SalesGrowth->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->SalesGrowth->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->CurrentEstimate->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->CurrentEstimate->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_7DaysAgo->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_7DaysAgo->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_30DaysAgo->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_30DaysAgo->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_60DaysAgo->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_60DaysAgo->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_90DaysAgo->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_90DaysAgo->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast7Days->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast7Days->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast30Days->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast30Days->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast30Days->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast30Days->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast90Days->{"CurrentQtr".$dates->currQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast90Days->{"CurrentQtr".$dates->currQtrDateText})).",";
        $query .= (is_null($rawdata->GrowthEst->CurrentQtr->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->CurrentQtr->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->CurrentQtr->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->CurrentQtr->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->CurrentQtr->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->CurrentQtr->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->CurrentQtr->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->CurrentQtr->SP500));
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_curr_qtr:" . $query ."\n" .mysql_error());

        //tickers_yahoo_estimates_next_qtr
	$query = "INSERT INTO `tickers_yahoo_estimates_next_qtr` (`ticker_id`, `report_date`, `EarningsAvg`, `EarningsNoof`, `EarningsLow`, `EarningsHigh`, `EarningsYAEPS`, `RevenueAvg`, `RevenueNoof`, `RevenueLow`, `RevenueHigh`, `RevenueYASales`, `RevenueSalesGrowth`, `EPSTrendCurrentEst`, `EPSTrend7daysEst`, `EPSTrend30daysEst`, `EPSTrend60daysEst`, `EPSTrend90daysEst`, `EPSRevUp7days`, `EPSRevUp30days`, `EPSRevDown30days`, `EPSRevDown90days`, `GrowthEstTicker`, `GrowthEstIndustry`, `GrowthEstSector`, `GrowthEstSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= "'".$dates->nextQtrDate."',";
        $query .= (is_null($rawdata->EarningsEst->AvgEstimate->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->AvgEstimate->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->NoofAnalysts->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->NoofAnalysts->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->LowEstimate->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->LowEstimate->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->HighEstimate->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->HighEstimate->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->YearAgoEPS->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->YearAgoEPS->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->AvgEstimate->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->AvgEstimate->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->NoofAnalysts->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->NoofAnalysts->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->LowEstimate->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->LowEstimate->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->HighEstimate->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->HighEstimate->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->YearAgoSales->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->YearAgoSales->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->SalesGrowth->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->SalesGrowth->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->CurrentEstimate->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->CurrentEstimate->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_7DaysAgo->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_7DaysAgo->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_30DaysAgo->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_30DaysAgo->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_60DaysAgo->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_60DaysAgo->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_90DaysAgo->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_90DaysAgo->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast7Days->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast7Days->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast30Days->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast30Days->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast30Days->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast30Days->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast90Days->{"NextQtr".$dates->nextQtrDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast90Days->{"NextQtr".$dates->nextQtrDateText})).",";
        $query .= (is_null($rawdata->GrowthEst->NextQtr->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextQtr->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->NextQtr->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextQtr->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->NextQtr->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextQtr->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->NextQtr->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextQtr->SP500));
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_next_qtr:" . $query ."\n" .mysql_error());

        //tickers_yahoo_estimates_curr_year
	$query = "INSERT INTO `tickers_yahoo_estimates_curr_year` (`ticker_id`, `report_date`, `EarningsAvg`, `EarningsNoof`, `EarningsLow`, `EarningsHigh`, `EarningsYAEPS`, `RevenueAvg`, `RevenueNoof`, `RevenueLow`, `RevenueHigh`, `RevenueYASales`, `RevenueSalesGrowth`, `EPSTrendCurrentEst`, `EPSTrend7daysEst`, `EPSTrend30daysEst`, `EPSTrend60daysEst`, `EPSTrend90daysEst`, `EPSRevUp7days`, `EPSRevUp30days`, `EPSRevDown30days`, `EPSRevDown90days`, `GrowthEstTicker`, `GrowthEstIndustry`, `GrowthEstSector`, `GrowthEstSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= "'".$dates->currYearDate."',";
        $query .= (is_null($rawdata->EarningsEst->AvgEstimate->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->AvgEstimate->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->NoofAnalysts->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->NoofAnalysts->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->LowEstimate->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->LowEstimate->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->HighEstimate->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->HighEstimate->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->YearAgoEPS->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->YearAgoEPS->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->AvgEstimate->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->AvgEstimate->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->NoofAnalysts->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->NoofAnalysts->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->LowEstimate->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->LowEstimate->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->HighEstimate->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->HighEstimate->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->YearAgoSales->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->YearAgoSales->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->SalesGrowth->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->SalesGrowth->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->CurrentEstimate->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->CurrentEstimate->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_7DaysAgo->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_7DaysAgo->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_30DaysAgo->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_30DaysAgo->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_60DaysAgo->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_60DaysAgo->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_90DaysAgo->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_90DaysAgo->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast7Days->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast7Days->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast30Days->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast30Days->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast30Days->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast30Days->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast90Days->{"CurrentYear".$dates->currYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast90Days->{"CurrentYear".$dates->currYearDateText})).",";
        $query .= (is_null($rawdata->GrowthEst->ThisYear->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->ThisYear->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->ThisYear->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->ThisYear->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->ThisYear->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->ThisYear->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->ThisYear->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->ThisYear->SP500));
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_curr_year:" . $query ."\n" .mysql_error());

        //tickers_yahoo_estimates_next_year
	$query = "INSERT INTO `tickers_yahoo_estimates_next_year` (`ticker_id`, `report_date`, `EarningsAvg`, `EarningsNoof`, `EarningsLow`, `EarningsHigh`, `EarningsYAEPS`, `RevenueAvg`, `RevenueNoof`, `RevenueLow`, `RevenueHigh`, `RevenueYASales`, `RevenueSalesGrowth`, `EPSTrendCurrentEst`, `EPSTrend7daysEst`, `EPSTrend30daysEst`, `EPSTrend60daysEst`, `EPSTrend90daysEst`, `EPSRevUp7days`, `EPSRevUp30days`, `EPSRevDown30days`, `EPSRevDown90days`, `GrowthEstTicker`, `GrowthEstIndustry`, `GrowthEstSector`, `GrowthEstSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= "'".$dates->nextYearDate."',";
        $query .= (is_null($rawdata->EarningsEst->AvgEstimate->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->AvgEstimate->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->NoofAnalysts->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->NoofAnalysts->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->LowEstimate->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->LowEstimate->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->HighEstimate->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->HighEstimate->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EarningsEst->YearAgoEPS->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EarningsEst->YearAgoEPS->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->AvgEstimate->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->AvgEstimate->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->NoofAnalysts->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->NoofAnalysts->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->LowEstimate->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->LowEstimate->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->HighEstimate->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->HighEstimate->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->YearAgoSales->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->YearAgoSales->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->RevenueEst->SalesGrowth->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->RevenueEst->SalesGrowth->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->CurrentEstimate->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->CurrentEstimate->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_7DaysAgo->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_7DaysAgo->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_30DaysAgo->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_30DaysAgo->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_60DaysAgo->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_60DaysAgo->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSTrends->_90DaysAgo->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSTrends->_90DaysAgo->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast7Days->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast7Days->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->UpLast30Days->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->UpLast30Days->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast30Days->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast30Days->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->EPSRevisions->DownLast90Days->{"NextYear".$dates->nextYearDateText})?"NULL":str_replace(',', '', $rawdata->EPSRevisions->DownLast90Days->{"NextYear".$dates->nextYearDateText})).",";
        $query .= (is_null($rawdata->GrowthEst->NextYear->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextYear->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->NextYear->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextYear->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->NextYear->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextYear->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->NextYear->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->NextYear->SP500));
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_next_year:" . $query ."\n" .mysql_error());

	//tickers_yahoo_estimates_earn_hist
	$query = "INSERT INTO `tickers_yahoo_estimates_earn_hist` (`ticker_id` ,`date1` ,`date2` ,`date3` ,`date4` ,`EarnHistEPSEst1` ,`EarnHistEPSEst2` ,`EarnHistEPSEst3` ,`EarnHistEPSEst4` ,`EarnHistEPSActual1` ,`EarnHistEPSActual2` ,`EarnHistEPSActual3` ,`EarnHistEPSActual4` ,`EarnHistDifference1` ,`EarnHistDifference2` ,`EarnHistDifference3` ,`EarnHistDifference4` ,`EarnHistSurprise1` ,`EarnHistSurprise2` ,`EarnHistSurprise3` ,`EarnHistSurprise4`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= ($dates->hDate[0]=="nodate1"?"NULL":"'".$dates->hDate[0]."'").",";
        $query .= ($dates->hDate[1]=="nodate2"?"NULL":"'".$dates->hDate[1]."'").",";
        $query .= ($dates->hDate[2]=="nodate3"?"NULL":"'".$dates->hDate[2]."'").",";
        $query .= ($dates->hDate[3]=="nodate4"?"NULL":"'".$dates->hDate[3]."'").",";
        $query .= (is_null($rawdata->EarningsHistory->EPSEst->{$dates->hDateText[0]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSEst->{$dates->hDateText[0]})).",";
        $query .= (is_null($rawdata->EarningsHistory->EPSEst->{$dates->hDateText[1]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSEst->{$dates->hDateText[1]})).",";
        $query .= (is_null($rawdata->EarningsHistory->EPSEst->{$dates->hDateText[2]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSEst->{$dates->hDateText[2]})).",";
        $query .= (is_null($rawdata->EarningsHistory->EPSEst->{$dates->hDateText[3]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSEst->{$dates->hDateText[3]})).",";
        $query .= (is_null($rawdata->EarningsHistory->EPSActual->{$dates->hDateText[0]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSActual->{$dates->hDateText[0]})).",";
        $query .= (is_null($rawdata->EarningsHistory->EPSActual->{$dates->hDateText[1]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSActual->{$dates->hDateText[1]})).",";
        $query .= (is_null($rawdata->EarningsHistory->EPSActual->{$dates->hDateText[2]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSActual->{$dates->hDateText[2]})).",";
        $query .= (is_null($rawdata->EarningsHistory->EPSActual->{$dates->hDateText[3]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->EPSActual->{$dates->hDateText[3]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Difference->{$dates->hDateText[0]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Difference->{$dates->hDateText[0]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Difference->{$dates->hDateText[1]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Difference->{$dates->hDateText[1]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Difference->{$dates->hDateText[2]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Difference->{$dates->hDateText[2]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Difference->{$dates->hDateText[3]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Difference->{$dates->hDateText[3]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Surprise->{$dates->hDateText[0]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Surprise->{$dates->hDateText[0]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Surprise->{$dates->hDateText[1]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Surprise->{$dates->hDateText[1]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Surprise->{$dates->hDateText[2]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Surprise->{$dates->hDateText[2]})).",";
        $query .= (is_null($rawdata->EarningsHistory->Surprise->{$dates->hDateText[3]})?"NULL":str_replace(',', '', $rawdata->EarningsHistory->Surprise->{$dates->hDateText[3]}));
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_earn_hist:" . $query ."\n" .mysql_error());

	//tickers_yahoo_estimates_others
	$query = "INSERT INTO `tickers_yahoo_estimates_others` (`ticker_id` ,`GrowthEstPast5YearTicker` ,`GrowthEstPast5YearIndustry` ,`GrowthEstPast5YearSector` ,`GrowthEstPast5YearSP500` ,`GrowthEstNext5YearTicker` ,`GrowthEstNext5YearIndustry` ,`GrowthEstNext5YearSector` ,`GrowthEstNext5YearSP500` ,`GrowthEstPriceEarnTicker` ,`GrowthEstPriceEarnIndustry` ,`GrowthEstPriceEarnSector` ,`GrowthEstPriceEarnSP500` ,`GrowthEstPEGRatioTicker` ,`GrowthEstPEGRatioIndustry` ,`GrowthEstPEGRatioSector` ,`GrowthEstPEGRatioSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (is_null($rawdata->GrowthEst->Past5Years->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->Past5Years->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->Past5Years->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->Past5Years->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->Past5Years->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->Past5Years->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->Past5Years->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->Past5Years->SP500)).",";
        $query .= (is_null($rawdata->GrowthEst->Next5Years->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->Next5Years->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->Next5Years->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->Next5Years->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->Next5Years->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->Next5Years->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->Next5Years->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->Next5Years->SP500)).",";
        $query .= (is_null($rawdata->GrowthEst->PriceEarnings->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->PriceEarnings->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->PriceEarnings->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->PriceEarnings->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->PriceEarnings->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->PriceEarnings->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->PriceEarnings->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->PriceEarnings->SP500)).",";
        $query .= (is_null($rawdata->GrowthEst->PEGRatio->{$rawdata->symbol})?"NULL":str_replace(',', '', $rawdata->GrowthEst->PEGRatio->{$rawdata->symbol})).",";
        $query .= (is_null($rawdata->GrowthEst->PEGRatio->Industry)?"NULL":str_replace(',', '', $rawdata->GrowthEst->PEGRatio->Industry)).",";
        $query .= (is_null($rawdata->GrowthEst->PEGRatio->Sector)?"NULL":str_replace(',', '', $rawdata->GrowthEst->PEGRatio->Sector)).",";
        $query .= (is_null($rawdata->GrowthEst->PEGRatio->SP500)?"NULL":str_replace(',', '', $rawdata->GrowthEst->PEGRatio->SP500));
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_others:" . $query ."\n" .mysql_error());
}
?>
