<?php
function update_raw_data_yahoo_estimates($ticker_id, $rawdata) {
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
        $query .= (!isset($rawdata->currQtr->endDate)?"NULL":"'".date("Y-m-d", strtotime($rawdata->currQtr->endDate))."'").",";
        $query .= (!isset($rawdata->currQtr->earningsEstimate->avg->raw) || !is_numeric($rawdata->currQtr->earningsEstimate->avg->raw)?"NULL":$rawdata->currQtr->earningsEstimate->avg->raw).",";
        $query .= (!isset($rawdata->currQtr->earningsEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->currQtr->earningsEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->currQtr->earningsEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->currQtr->earningsEstimate->low->raw) || !is_numeric($rawdata->currQtr->earningsEstimate->low->raw)?"NULL":$rawdata->currQtr->earningsEstimate->low->raw).",";
        $query .= (!isset($rawdata->currQtr->earningsEstimate->high->raw) || !is_numeric($rawdata->currQtr->earningsEstimate->high->raw)?"NULL":$rawdata->currQtr->earningsEstimate->high->raw).",";
        $query .= (!isset($rawdata->currQtr->earningsEstimate->yearAgoEps->raw) || !is_numeric($rawdata->currQtr->earningsEstimate->yearAgoEps->raw)?"NULL":$rawdata->currQtr->earningsEstimate->yearAgoEps->raw).",";
        $query .= (!isset($rawdata->currQtr->revenueEstimate->avg->raw) || !is_numeric($rawdata->currQtr->revenueEstimate->avg->raw)?"NULL":$rawdata->currQtr->revenueEstimate->avg->raw).",";
        $query .= (!isset($rawdata->currQtr->revenueEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->currQtr->revenueEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->currQtr->revenueEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->currQtr->revenueEstimate->low->raw) || !is_numeric($rawdata->currQtr->revenueEstimate->low->raw)?"NULL":$rawdata->currQtr->revenueEstimate->low->raw).",";
        $query .= (!isset($rawdata->currQtr->revenueEstimate->high->raw) || !is_numeric($rawdata->currQtr->revenueEstimate->high->raw)?"NULL":$rawdata->currQtr->revenueEstimate->high->raw).",";
        $query .= (!isset($rawdata->currQtr->revenueEstimate->yearAgoRevenue->raw) || !is_numeric($rawdata->currQtr->revenueEstimate->yearAgoRevenue->raw)?"NULL":$rawdata->currQtr->revenueEstimate->yearAgoRevenue->raw).",";
        $query .= (!isset($rawdata->currQtr->revenueEstimate->growth->raw) || !is_numeric($rawdata->currQtr->revenueEstimate->growth->raw)?"NULL":($rawdata->currQtr->revenueEstimate->growth->raw * 100)).",";
        $query .= (!isset($rawdata->currQtr->epsTrend->current->raw) || !is_numeric($rawdata->currQtr->epsTrend->current->raw)?"NULL":$rawdata->currQtr->epsTrend->current->raw).",";
        $query .= (!isset($rawdata->currQtr->epsTrend->_daysAgo->raw) || !is_numeric($rawdata->currQtr->epsTrend->_daysAgo->raw)?"NULL":$rawdata->currQtr->epsTrend->_daysAgo->raw).",";
        $query .= (!isset($rawdata->currQtr->epsTrend->_0daysAgo[0]->raw) || !is_numeric($rawdata->currQtr->epsTrend->_0daysAgo[0]->raw)?"NULL":$rawdata->currQtr->epsTrend->_0daysAgo[0]->raw).",";
        $query .= (!isset($rawdata->currQtr->epsTrend->_0daysAgo[1]->raw) || !is_numeric($rawdata->currQtr->epsTrend->_0daysAgo[1]->raw)?"NULL":$rawdata->currQtr->epsTrend->_0daysAgo[1]->raw).",";
        $query .= (!isset($rawdata->currQtr->epsTrend->_0daysAgo[2]->raw) || !is_numeric($rawdata->currQtr->epsTrend->_0daysAgo[2]->raw)?"NULL":$rawdata->currQtr->epsTrend->_0daysAgo[2]->raw).",";
        $query .= (!isset($rawdata->currQtr->epsRevisions->upLast7days->raw) || !is_numeric($rawdata->currQtr->epsRevisions->upLast7days->raw)?"NULL":$rawdata->currQtr->epsRevisions->upLast7days->raw).",";
        $query .= (!isset($rawdata->currQtr->epsRevisions->upLast30days->raw) || !is_numeric($rawdata->currQtr->epsRevisions->upLast30days->raw)?"NULL":$rawdata->currQtr->epsRevisions->upLast30days->raw).",";
        $query .= (!isset($rawdata->currQtr->epsRevisions->downLast30days->raw) || !is_numeric($rawdata->currQtr->epsRevisions->downLast30days->raw)?"NULL":$rawdata->currQtr->epsRevisions->downLast30days->raw).",";
        $query .= (!isset($rawdata->currQtr->epsRevisions->downLast90days->raw) || !is_numeric($rawdata->currQtr->epsRevisions->downLast90days->raw)?"NULL":$rawdata->currQtr->epsRevisions->downLast90days->raw).",";
        $query .= (!isset($rawdata->currQtr->growth->raw) || !is_numeric($rawdata->currQtr->growth->raw)?"NULL":($rawdata->currQtr->growth->raw * 100)).",";
        $query .= (!isset($rawdata->currQtr->industryTrend->growth->raw) || !is_numeric($rawdata->currQtr->industryTrend->growth->raw)?"NULL":($rawdata->currQtr->industryTrend->growth->raw * 100)).",";
        $query .= (!isset($rawdata->currQtr->sectorTrend->growth->raw) || !is_numeric($rawdata->currQtr->sectorTrend->growth->raw)?"NULL":($rawdata->currQtr->sectorTrend->growth->raw * 100)).",";
        $query .= "NULL";
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_curr_qtr:" . $query ."\n" .mysql_error());

        //tickers_yahoo_estimates_next_qtr
	$query = "INSERT INTO `tickers_yahoo_estimates_next_qtr` (`ticker_id`, `report_date`, `EarningsAvg`, `EarningsNoof`, `EarningsLow`, `EarningsHigh`, `EarningsYAEPS`, `RevenueAvg`, `RevenueNoof`, `RevenueLow`, `RevenueHigh`, `RevenueYASales`, `RevenueSalesGrowth`, `EPSTrendCurrentEst`, `EPSTrend7daysEst`, `EPSTrend30daysEst`, `EPSTrend60daysEst`, `EPSTrend90daysEst`, `EPSRevUp7days`, `EPSRevUp30days`, `EPSRevDown30days`, `EPSRevDown90days`, `GrowthEstTicker`, `GrowthEstIndustry`, `GrowthEstSector`, `GrowthEstSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->nextQtr->endDate)?"NULL":"'".date("Y-m-d", strtotime($rawdata->nextQtr->endDate))."'").",";
        $query .= (!isset($rawdata->nextQtr->earningsEstimate->avg->raw) || !is_numeric($rawdata->nextQtr->earningsEstimate->avg->raw)?"NULL":$rawdata->nextQtr->earningsEstimate->avg->raw).",";
        $query .= (!isset($rawdata->nextQtr->earningsEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->nextQtr->earningsEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->nextQtr->earningsEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->nextQtr->earningsEstimate->low->raw) || !is_numeric($rawdata->nextQtr->earningsEstimate->low->raw)?"NULL":$rawdata->nextQtr->earningsEstimate->low->raw).",";
        $query .= (!isset($rawdata->nextQtr->earningsEstimate->high->raw) || !is_numeric($rawdata->nextQtr->earningsEstimate->high->raw)?"NULL":$rawdata->nextQtr->earningsEstimate->high->raw).",";
        $query .= (!isset($rawdata->nextQtr->earningsEstimate->yearAgoEps->raw) || !is_numeric($rawdata->nextQtr->earningsEstimate->yearAgoEps->raw)?"NULL":$rawdata->nextQtr->earningsEstimate->yearAgoEps->raw).",";
        $query .= (!isset($rawdata->nextQtr->revenueEstimate->avg->raw) || !is_numeric($rawdata->nextQtr->revenueEstimate->avg->raw)?"NULL":$rawdata->nextQtr->revenueEstimate->avg->raw).",";
        $query .= (!isset($rawdata->nextQtr->revenueEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->nextQtr->revenueEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->nextQtr->revenueEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->nextQtr->revenueEstimate->low->raw) || !is_numeric($rawdata->nextQtr->revenueEstimate->low->raw)?"NULL":$rawdata->nextQtr->revenueEstimate->low->raw).",";
        $query .= (!isset($rawdata->nextQtr->revenueEstimate->high->raw) || !is_numeric($rawdata->nextQtr->revenueEstimate->high->raw)?"NULL":$rawdata->nextQtr->revenueEstimate->high->raw).",";
        $query .= (!isset($rawdata->nextQtr->revenueEstimate->yearAgoRevenue->raw) || !is_numeric($rawdata->nextQtr->revenueEstimate->yearAgoRevenue->raw)?"NULL":$rawdata->nextQtr->revenueEstimate->yearAgoRevenue->raw).",";
        $query .= (!isset($rawdata->nextQtr->revenueEstimate->growth->raw) || !is_numeric($rawdata->nextQtr->revenueEstimate->growth->raw)?"NULL":($rawdata->nextQtr->revenueEstimate->growth->raw * 100)).",";
        $query .= (!isset($rawdata->nextQtr->epsTrend->current->raw) || !is_numeric($rawdata->nextQtr->epsTrend->current->raw)?"NULL":$rawdata->nextQtr->epsTrend->current->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsTrend->_daysAgo->raw) || !is_numeric($rawdata->nextQtr->epsTrend->_daysAgo->raw)?"NULL":$rawdata->nextQtr->epsTrend->_daysAgo->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsTrend->_0daysAgo[0]->raw) || !is_numeric($rawdata->nextQtr->epsTrend->_0daysAgo[0]->raw)?"NULL":$rawdata->nextQtr->epsTrend->_0daysAgo[0]->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsTrend->_0daysAgo[1]->raw) || !is_numeric($rawdata->nextQtr->epsTrend->_0daysAgo[1]->raw)?"NULL":$rawdata->nextQtr->epsTrend->_0daysAgo[1]->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsTrend->_0daysAgo[2]->raw) || !is_numeric($rawdata->nextQtr->epsTrend->_0daysAgo[2]->raw)?"NULL":$rawdata->nextQtr->epsTrend->_0daysAgo[2]->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsRevisions->upLast7days->raw) || !is_numeric($rawdata->nextQtr->epsRevisions->upLast7days->raw)?"NULL":$rawdata->nextQtr->epsRevisions->upLast7days->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsRevisions->upLast30days->raw) || !is_numeric($rawdata->nextQtr->epsRevisions->upLast30days->raw)?"NULL":$rawdata->nextQtr->epsRevisions->upLast30days->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsRevisions->downLast30days->raw) || !is_numeric($rawdata->nextQtr->epsRevisions->downLast30days->raw)?"NULL":$rawdata->nextQtr->epsRevisions->downLast30days->raw).",";
        $query .= (!isset($rawdata->nextQtr->epsRevisions->downLast90days->raw) || !is_numeric($rawdata->nextQtr->epsRevisions->downLast90days->raw)?"NULL":$rawdata->nextQtr->epsRevisions->downLast90days->raw).",";
        $query .= (!isset($rawdata->nextQtr->growth->raw) || !is_numeric($rawdata->nextQtr->growth->raw)?"NULL":($rawdata->nextQtr->growth->raw * 100)).",";
        $query .= (!isset($rawdata->nextQtr->industryTrend->growth->raw) || !is_numeric($rawdata->nextQtr->industryTrend->growth->raw)?"NULL":($rawdata->nextQtr->industryTrend->growth->raw * 100)).",";
        $query .= (!isset($rawdata->nextQtr->sectorTrend->growth->raw) || !is_numeric($rawdata->nextQtr->sectorTrend->growth->raw)?"NULL":($rawdata->nextQtr->sectorTrend->growth->raw * 100)).",";
        $query .= "NULL";
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_next_qtr:" . $query ."\n" .mysql_error());

        //tickers_yahoo_estimates_curr_year
	$query = "INSERT INTO `tickers_yahoo_estimates_curr_year` (`ticker_id`, `report_date`, `EarningsAvg`, `EarningsNoof`, `EarningsLow`, `EarningsHigh`, `EarningsYAEPS`, `RevenueAvg`, `RevenueNoof`, `RevenueLow`, `RevenueHigh`, `RevenueYASales`, `RevenueSalesGrowth`, `EPSTrendCurrentEst`, `EPSTrend7daysEst`, `EPSTrend30daysEst`, `EPSTrend60daysEst`, `EPSTrend90daysEst`, `EPSRevUp7days`, `EPSRevUp30days`, `EPSRevDown30days`, `EPSRevDown90days`, `GrowthEstTicker`, `GrowthEstIndustry`, `GrowthEstSector`, `GrowthEstSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->currYear->endDate)?"NULL":"'".date("Y-m-d", strtotime($rawdata->currYear->endDate))."'").",";
        $query .= (!isset($rawdata->currYear->earningsEstimate->avg->raw) || !is_numeric($rawdata->currYear->earningsEstimate->avg->raw)?"NULL":$rawdata->currYear->earningsEstimate->avg->raw).",";
        $query .= (!isset($rawdata->currYear->earningsEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->currYear->earningsEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->currYear->earningsEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->currYear->earningsEstimate->low->raw) || !is_numeric($rawdata->currYear->earningsEstimate->low->raw)?"NULL":$rawdata->currYear->earningsEstimate->low->raw).",";
        $query .= (!isset($rawdata->currYear->earningsEstimate->high->raw) || !is_numeric($rawdata->currYear->earningsEstimate->high->raw)?"NULL":$rawdata->currYear->earningsEstimate->high->raw).",";
        $query .= (!isset($rawdata->currYear->earningsEstimate->yearAgoEps->raw) || !is_numeric($rawdata->currYear->earningsEstimate->yearAgoEps->raw)?"NULL":$rawdata->currYear->earningsEstimate->yearAgoEps->raw).",";
        $query .= (!isset($rawdata->currYear->revenueEstimate->avg->raw) || !is_numeric($rawdata->currYear->revenueEstimate->avg->raw)?"NULL":$rawdata->currYear->revenueEstimate->avg->raw).",";
        $query .= (!isset($rawdata->currYear->revenueEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->currYear->revenueEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->currYear->revenueEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->currYear->revenueEstimate->low->raw) || !is_numeric($rawdata->currYear->revenueEstimate->low->raw)?"NULL":$rawdata->currYear->revenueEstimate->low->raw).",";
        $query .= (!isset($rawdata->currYear->revenueEstimate->high->raw) || !is_numeric($rawdata->currYear->revenueEstimate->high->raw)?"NULL":$rawdata->currYear->revenueEstimate->high->raw).",";
        $query .= (!isset($rawdata->currYear->revenueEstimate->yearAgoRevenue->raw) || !is_numeric($rawdata->currYear->revenueEstimate->yearAgoRevenue->raw)?"NULL":$rawdata->currYear->revenueEstimate->yearAgoRevenue->raw).",";
        $query .= (!isset($rawdata->currYear->revenueEstimate->growth->raw) || !is_numeric($rawdata->currYear->revenueEstimate->growth->raw)?"NULL":($rawdata->currYear->revenueEstimate->growth->raw * 100)).",";
        $query .= (!isset($rawdata->currYear->epsTrend->current->raw) || !is_numeric($rawdata->currYear->epsTrend->current->raw)?"NULL":$rawdata->currYear->epsTrend->current->raw).",";
        $query .= (!isset($rawdata->currYear->epsTrend->_daysAgo->raw) || !is_numeric($rawdata->currYear->epsTrend->_daysAgo->raw)?"NULL":$rawdata->currYear->epsTrend->_daysAgo->raw).",";
        $query .= (!isset($rawdata->currYear->epsTrend->_0daysAgo[0]->raw) || !is_numeric($rawdata->currYear->epsTrend->_0daysAgo[0]->raw)?"NULL":$rawdata->currYear->epsTrend->_0daysAgo[0]->raw).",";
        $query .= (!isset($rawdata->currYear->epsTrend->_0daysAgo[1]->raw) || !is_numeric($rawdata->currYear->epsTrend->_0daysAgo[1]->raw)?"NULL":$rawdata->currYear->epsTrend->_0daysAgo[1]->raw).",";
        $query .= (!isset($rawdata->currYear->epsTrend->_0daysAgo[2]->raw) || !is_numeric($rawdata->currYear->epsTrend->_0daysAgo[2]->raw)?"NULL":$rawdata->currYear->epsTrend->_0daysAgo[2]->raw).",";
        $query .= (!isset($rawdata->currYear->epsRevisions->upLast7days->raw) || !is_numeric($rawdata->currYear->epsRevisions->upLast7days->raw)?"NULL":$rawdata->currYear->epsRevisions->upLast7days->raw).",";
        $query .= (!isset($rawdata->currYear->epsRevisions->upLast30days->raw) || !is_numeric($rawdata->currYear->epsRevisions->upLast30days->raw)?"NULL":$rawdata->currYear->epsRevisions->upLast30days->raw).",";
        $query .= (!isset($rawdata->currYear->epsRevisions->downLast30days->raw) || !is_numeric($rawdata->currYear->epsRevisions->downLast30days->raw)?"NULL":$rawdata->currYear->epsRevisions->downLast30days->raw).",";
        $query .= (!isset($rawdata->currYear->epsRevisions->downLast90days->raw) || !is_numeric($rawdata->currYear->epsRevisions->downLast90days->raw)?"NULL":$rawdata->currYear->epsRevisions->downLast90days->raw).",";
        $query .= (!isset($rawdata->currYear->growth->raw) || !is_numeric($rawdata->currYear->growth->raw)?"NULL":($rawdata->currYear->growth->raw * 100)).",";
        $query .= (!isset($rawdata->currYear->industryTrend->growth->raw) || !is_numeric($rawdata->currYear->industryTrend->growth->raw)?"NULL":($rawdata->currYear->industryTrend->growth->raw * 100)).",";
        $query .= (!isset($rawdata->currYear->sectorTrend->growth->raw) || !is_numeric($rawdata->currYear->sectorTrend->growth->raw)?"NULL":($rawdata->currYear->sectorTrend->growth->raw * 100)).",";
        $query .= "NULL";
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_curr_year:" . $query ."\n" .mysql_error());

        //tickers_yahoo_estimates_next_year
	$query = "INSERT INTO `tickers_yahoo_estimates_next_year` (`ticker_id`, `report_date`, `EarningsAvg`, `EarningsNoof`, `EarningsLow`, `EarningsHigh`, `EarningsYAEPS`, `RevenueAvg`, `RevenueNoof`, `RevenueLow`, `RevenueHigh`, `RevenueYASales`, `RevenueSalesGrowth`, `EPSTrendCurrentEst`, `EPSTrend7daysEst`, `EPSTrend30daysEst`, `EPSTrend60daysEst`, `EPSTrend90daysEst`, `EPSRevUp7days`, `EPSRevUp30days`, `EPSRevDown30days`, `EPSRevDown90days`, `GrowthEstTicker`, `GrowthEstIndustry`, `GrowthEstSector`, `GrowthEstSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->nextYear->endDate)?"NULL":"'".date("Y-m-d", strtotime($rawdata->nextYear->endDate))."'").",";
        $query .= (!isset($rawdata->nextYear->earningsEstimate->avg->raw) || !is_numeric($rawdata->nextYear->earningsEstimate->avg->raw)?"NULL":$rawdata->nextYear->earningsEstimate->avg->raw).",";
        $query .= (!isset($rawdata->nextYear->earningsEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->nextYear->earningsEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->nextYear->earningsEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->nextYear->earningsEstimate->low->raw) || !is_numeric($rawdata->nextYear->earningsEstimate->low->raw)?"NULL":$rawdata->nextYear->earningsEstimate->low->raw).",";
        $query .= (!isset($rawdata->nextYear->earningsEstimate->high->raw) || !is_numeric($rawdata->nextYear->earningsEstimate->high->raw)?"NULL":$rawdata->nextYear->earningsEstimate->high->raw).",";
        $query .= (!isset($rawdata->nextYear->earningsEstimate->yearAgoEps->raw) || !is_numeric($rawdata->nextYear->earningsEstimate->yearAgoEps->raw)?"NULL":$rawdata->nextYear->earningsEstimate->yearAgoEps->raw).",";
        $query .= (!isset($rawdata->nextYear->revenueEstimate->avg->raw) || !is_numeric($rawdata->nextYear->revenueEstimate->avg->raw)?"NULL":$rawdata->nextYear->revenueEstimate->avg->raw).",";
        $query .= (!isset($rawdata->nextYear->revenueEstimate->numberOfAnalysts->raw) || !is_numeric($rawdata->nextYear->revenueEstimate->numberOfAnalysts->raw)?"NULL":$rawdata->nextYear->revenueEstimate->numberOfAnalysts->raw).",";
        $query .= (!isset($rawdata->nextYear->revenueEstimate->low->raw) || !is_numeric($rawdata->nextYear->revenueEstimate->low->raw)?"NULL":$rawdata->nextYear->revenueEstimate->low->raw).",";
        $query .= (!isset($rawdata->nextYear->revenueEstimate->high->raw) || !is_numeric($rawdata->nextYear->revenueEstimate->high->raw)?"NULL":$rawdata->nextYear->revenueEstimate->high->raw).",";
        $query .= (!isset($rawdata->nextYear->revenueEstimate->yearAgoRevenue->raw) || !is_numeric($rawdata->nextYear->revenueEstimate->yearAgoRevenue->raw)?"NULL":$rawdata->nextYear->revenueEstimate->yearAgoRevenue->raw).",";
        $query .= (!isset($rawdata->nextYear->revenueEstimate->growth->raw) || !is_numeric($rawdata->nextYear->revenueEstimate->growth->raw)?"NULL":($rawdata->nextYear->revenueEstimate->growth->raw * 100)).",";
        $query .= (!isset($rawdata->nextYear->epsTrend->current->raw) || !is_numeric($rawdata->nextYear->epsTrend->current->raw)?"NULL":$rawdata->nextYear->epsTrend->current->raw).",";
        $query .= (!isset($rawdata->nextYear->epsTrend->_daysAgo->raw) || !is_numeric($rawdata->nextYear->epsTrend->_daysAgo->raw)?"NULL":$rawdata->nextYear->epsTrend->_daysAgo->raw).",";
        $query .= (!isset($rawdata->nextYear->epsTrend->_0daysAgo[0]->raw) || !is_numeric($rawdata->nextYear->epsTrend->_0daysAgo[0]->raw)?"NULL":$rawdata->nextYear->epsTrend->_0daysAgo[0]->raw).",";
        $query .= (!isset($rawdata->nextYear->epsTrend->_0daysAgo[1]->raw) || !is_numeric($rawdata->nextYear->epsTrend->_0daysAgo[1]->raw)?"NULL":$rawdata->nextYear->epsTrend->_0daysAgo[1]->raw).",";
        $query .= (!isset($rawdata->nextYear->epsTrend->_0daysAgo[2]->raw) || !is_numeric($rawdata->nextYear->epsTrend->_0daysAgo[2]->raw)?"NULL":$rawdata->nextYear->epsTrend->_0daysAgo[2]->raw).",";
        $query .= (!isset($rawdata->nextYear->epsRevisions->upLast7days->raw) || !is_numeric($rawdata->nextYear->epsRevisions->upLast7days->raw)?"NULL":$rawdata->nextYear->epsRevisions->upLast7days->raw).",";
        $query .= (!isset($rawdata->nextYear->epsRevisions->upLast30days->raw) || !is_numeric($rawdata->nextYear->epsRevisions->upLast30days->raw)?"NULL":$rawdata->nextYear->epsRevisions->upLast30days->raw).",";
        $query .= (!isset($rawdata->nextYear->epsRevisions->downLast30days->raw) || !is_numeric($rawdata->nextYear->epsRevisions->downLast30days->raw)?"NULL":$rawdata->nextYear->epsRevisions->downLast30days->raw).",";
        $query .= (!isset($rawdata->nextYear->epsRevisions->downLast90days->raw) || !is_numeric($rawdata->nextYear->epsRevisions->downLast90days->raw)?"NULL":$rawdata->nextYear->epsRevisions->downLast90days->raw).",";
        $query .= (!isset($rawdata->nextYear->growth->raw) || !is_numeric($rawdata->nextYear->growth->raw)?"NULL":($rawdata->nextYear->growth->raw * 100)).",";
        $query .= (!isset($rawdata->nextYear->industryTrend->growth->raw) || !is_numeric($rawdata->nextYear->industryTrend->growth->raw)?"NULL":($rawdata->nextYear->industryTrend->growth->raw * 100)).",";
        $query .= (!isset($rawdata->nextYear->sectorTrend->growth->raw) || !is_numeric($rawdata->nextYear->sectorTrend->growth->raw)?"NULL":($rawdata->nextYear->sectorTrend->growth->raw * 100)).",";
        $query .= "NULL";
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_next_year:" . $query ."\n" .mysql_error());

	//tickers_yahoo_estimates_earn_hist
	$query = "INSERT INTO `tickers_yahoo_estimates_earn_hist` (`ticker_id` ,`date1` ,`date2` ,`date3` ,`date4` ,`EarnHistEPSEst1` ,`EarnHistEPSEst2` ,`EarnHistEPSEst3` ,`EarnHistEPSEst4` ,`EarnHistEPSActual1` ,`EarnHistEPSActual2` ,`EarnHistEPSActual3` ,`EarnHistEPSActual4` ,`EarnHistDifference1` ,`EarnHistDifference2` ,`EarnHistDifference3` ,`EarnHistDifference4` ,`EarnHistSurprise1` ,`EarnHistSurprise2` ,`EarnHistSurprise3` ,`EarnHistSurprise4`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->earningsHistory->minus4q->quarter->fmt)?"NULL":"'".$rawdata->earningsHistory->minus4q->quarter->fmt ."'").",";
        $query .= (!isset($rawdata->earningsHistory->minus3q->quarter->fmt)?"NULL":"'".$rawdata->earningsHistory->minus3q->quarter->fmt ."'").",";
        $query .= (!isset($rawdata->earningsHistory->minus2q->quarter->fmt)?"NULL":"'".$rawdata->earningsHistory->minus2q->quarter->fmt ."'").",";
        $query .= (!isset($rawdata->earningsHistory->minus1q->quarter->fmt)?"NULL":"'".$rawdata->earningsHistory->minus1q->quarter->fmt ."'").",";
        $query .= (!isset($rawdata->earningsHistory->minus4q->epsEstimate->raw) || !is_numeric($rawdata->earningsHistory->minus4q->epsEstimate->raw)?"NULL":$rawdata->earningsHistory->minus4q->epsEstimate->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus3q->epsEstimate->raw) || !is_numeric($rawdata->earningsHistory->minus3q->epsEstimate->raw)?"NULL":$rawdata->earningsHistory->minus3q->epsEstimate->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus3q->epsEstimate->raw) || !is_numeric($rawdata->earningsHistory->minus2q->epsEstimate->raw)?"NULL":$rawdata->earningsHistory->minus2q->epsEstimate->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus1q->epsEstimate->raw) || !is_numeric($rawdata->earningsHistory->minus1q->epsEstimate->raw)?"NULL":$rawdata->earningsHistory->minus1q->epsEstimate->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus4q->epsActual->raw) || !is_numeric($rawdata->earningsHistory->minus4q->epsActual->raw)?"NULL":$rawdata->earningsHistory->minus4q->epsActual->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus3q->epsActual->raw) || !is_numeric($rawdata->earningsHistory->minus3q->epsActual->raw)?"NULL":$rawdata->earningsHistory->minus3q->epsActual->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus2q->epsActual->raw) || !is_numeric($rawdata->earningsHistory->minus2q->epsActual->raw)?"NULL":$rawdata->earningsHistory->minus2q->epsActual->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus1q->epsActual->raw) || !is_numeric($rawdata->earningsHistory->minus1q->epsActual->raw)?"NULL":$rawdata->earningsHistory->minus1q->epsActual->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus4q->epsDifference->raw) || !is_numeric($rawdata->earningsHistory->minus4q->epsDifference->raw)?"NULL":$rawdata->earningsHistory->minus4q->epsDifference->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus3q->epsDifference->raw) || !is_numeric($rawdata->earningsHistory->minus3q->epsDifference->raw)?"NULL":$rawdata->earningsHistory->minus3q->epsDifference->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus2q->epsDifference->raw) || !is_numeric($rawdata->earningsHistory->minus2q->epsDifference->raw)?"NULL":$rawdata->earningsHistory->minus2q->epsDifference->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus1q->epsDifference->raw) || !is_numeric($rawdata->earningsHistory->minus1q->epsDifference->raw)?"NULL":$rawdata->earningsHistory->minus1q->epsDifference->raw).",";
        $query .= (!isset($rawdata->earningsHistory->minus4q->surprisePercent->raw) || !is_numeric($rawdata->earningsHistory->minus4q->surprisePercent->raw)?"NULL":($rawdata->earningsHistory->minus4q->surprisePercent->raw * 100)).",";
        $query .= (!isset($rawdata->earningsHistory->minus3q->surprisePercent->raw) || !is_numeric($rawdata->earningsHistory->minus3q->surprisePercent->raw)?"NULL":($rawdata->earningsHistory->minus3q->surprisePercent->raw * 100)).",";
        $query .= (!isset($rawdata->earningsHistory->minus2q->surprisePercent->raw) || !is_numeric($rawdata->earningsHistory->minus2q->surprisePercent->raw)?"NULL":($rawdata->earningsHistory->minus2q->surprisePercent->raw * 100)).",";
        $query .= (!isset($rawdata->earningsHistory->minus1q->surprisePercent->raw) || !is_numeric($rawdata->earningsHistory->minus1q->surprisePercent->raw)?"NULL":($rawdata->earningsHistory->minus1q->surprisePercent->raw * 100));
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_earn_hist:" . $query ."\n" .mysql_error());

	//tickers_yahoo_estimates_others
	$query = "INSERT INTO `tickers_yahoo_estimates_others` (`ticker_id` ,`GrowthEstPast5YearTicker` ,`GrowthEstPast5YearIndustry` ,`GrowthEstPast5YearSector` ,`GrowthEstPast5YearSP500` ,`GrowthEstNext5YearTicker` ,`GrowthEstNext5YearIndustry` ,`GrowthEstNext5YearSector` ,`GrowthEstNext5YearSP500` ,`GrowthEstPriceEarnTicker` ,`GrowthEstPriceEarnIndustry` ,`GrowthEstPriceEarnSector` ,`GrowthEstPriceEarnSP500` ,`GrowthEstPEGRatioTicker` ,`GrowthEstPEGRatioIndustry` ,`GrowthEstPEGRatioSector` ,`GrowthEstPEGRatioSP500`) VALUES (";
        $query .= "'".$ticker_id."',";
        $query .= (!isset($rawdata->minus5Year->growth->raw) || !is_numeric($rawdata->minus5Year->growth->raw)?"NULL":($rawdata->minus5Year->growth->raw * 100)).",";
        $query .= (!isset($rawdata->minus5Year->industryTrend->growth->raw) || !is_numeric($rawdata->minus5Year->industryTrend->growth->raw)?"NULL":($rawdata->minus5Year->industryTrend->growth->raw * 100)).",";
        $query .= (!isset($rawdata->minus5Year->sectorTrend->growth->raw) || !is_numeric($rawdata->minus5Year->sectorTrend->growth->raw)?"NULL":($rawdata->minus5Year->sectorTrend->growth->raw * 100)).",";
	$query .= "NULL,";
        $query .= (!isset($rawdata->plus5Year->growth->raw) || !is_numeric($rawdata->plus5Year->growth->raw)?"NULL":($rawdata->plus5Year->growth->raw * 100)).",";
        $query .= (!isset($rawdata->plus5Year->industryTrend->growth->raw) || !is_numeric($rawdata->plus5Year->industryTrend->growth->raw)?"NULL":($rawdata->plus5Year->industryTrend->growth->raw * 100)).",";
        $query .= (!isset($rawdata->plus5Year->sectorTrend->growth->raw) || !is_numeric($rawdata->plus5Year->sectorTrend->growth->raw)?"NULL":($rawdata->plus5Year->sectorTrend->growth->raw * 100)).",";
	$query .= "NULL,";
	$query .= "NULL,";
	$query .= "NULL,";
	$query .= "NULL,";
	$query .= "NULL,";
	$query .= "NULL,";
        $query .= (!isset($rawdata->industryPegRatio->raw) || !is_numeric($rawdata->industryPegRatio->raw)?"NULL":$rawdata->industryPegRatio->raw).",";
        $query .= (!isset($rawdata->sectorPegRatio->raw) || !is_numeric($rawdata->sectorPegRatio->raw)?"NULL":$rawdata->sectorPegRatio->raw).",";
	$query .= "NULL";
        $query .= ")";
        mysql_query($query) or die ("tickers_yahoo_estimates_others:" . $query ."\n" .mysql_error());
}
?>
