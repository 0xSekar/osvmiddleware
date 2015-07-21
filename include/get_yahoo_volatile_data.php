<?php
function get_yahoo_volatile_data($ticker_id, $symbol) {
	$query = "select *, TIMESTAMPDIFF(MINUTE , last_volatile_date, NOW( )) as tdiff from tickers_control where ticker_id = " . $ticker_id;
	$res = mysql_query($query) or die (mysql_error());
	$row = mysql_fetch_assoc($res);
	if (isset($row) && isset($row["last_volatile_date"])) {
		//record exists, check date
		if($row["tdiff"] > 15) {
			//Old data, update
			update_yahoo_volatile_data($ticker_id, $symbol);
		}
	} else {
		//Missing data, update
		update_yahoo_volatile_data($ticker_id, $symbol);
	}
	$query = "SELECT * FROM `tickers_yahoo_quotes_1` a, `tickers_yahoo_quotes_2` b WHERE a.ticker_id=b.ticker_id and a.ticker_id= " . $ticker_id;
	$res = mysql_query($query) or die (mysql_error());
	$row = mysql_fetch_assoc($res);
	return $row;
}

function update_yahoo_volatile_data($ticker_id, $symbol) {
	$yql = new YahooYQLQuery();
	$response = $yql->execute("select * from osv.finance.quotes where symbol='".str_replace(".", ",", $symbol)."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
        if(isset($response->query) && isset($response->query->results)) {
		$rawdata = $response->query->results->quote;
		$query = "delete from tickers_yahoo_quotes_1 where ticker_id = " . $ticker_id;
		$res = mysql_query($query) or die (mysql_error());
		$query = "delete from tickers_yahoo_quotes_2 where ticker_id = " . $ticker_id;
		$res = mysql_query($query) or die (mysql_error());
		$query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id`, `Ask`, `AverageDailyVolume`, `Bid`, `AskRealTime`, `BidRealTime`, `BookValue`, `Change`, `Commision`, `Currency`, `ChangeRealTime`, `AfterHoursChangeRealTime`, `DividendShare`, `LastTradeDate`, `TradeDate`, `EarningsShare`, `EPSEstimateCurrentYear`, `EPSEstimateNextYear`, `EPSEstimateNextQuarter`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`, `HoldingsGainPercent`, `AnnualizedGain`, `HoldingsGain`, `HoldingsGainPercentRealTime`, `AnnualizedGainRealTime`, `MoreInfo`, `OrderBookRealTime`, `MarketCapitalization`, `MarketCapRealTime`, `EBITDA`, `ChangeFromYearLow`, `PercentChangeFromYearLow`, `LastTradeRealTimeWithTime`, `ChangePercentRealTime`, `ChangeFromYearHigh`, `PercentChangeFromYearHigh`) VALUES (";
		$query .= "'".$ticker_id."',";
		$query .= (!isset($rawdata->Ask)?"NULL":str_replace(',', '', $rawdata->Ask)).",";
		$query .= (!isset($rawdata->AverageDailyVolume)?"NULL":str_replace(',', '', $rawdata->AverageDailyVolume)).",";
		$query .= (!isset($rawdata->Bid)?"NULL":str_replace(',', '', $rawdata->Bid)).",";
		$query .= (!isset($rawdata->AskRealtime)?"NULL":str_replace(',', '', $rawdata->AskRealtime)).",";
		$query .= (!isset($rawdata->BidRealtime)?"NULL":str_replace(',', '', $rawdata->BidRealtime)).",";
		$query .= (!isset($rawdata->BookValue)?"NULL":str_replace(',', '', $rawdata->BookValue)).",";
		$query .= (!isset($rawdata->Change)?"NULL":str_replace(',', '', $rawdata->Change)).",";
		$query .= (!isset($rawdata->Commision)?"NULL":str_replace(',', '', $rawdata->Commision)).",";
        	$query .= "'".$rawdata->Currency."',";
		$query .= (!isset($rawdata->ChangeRealtime)?"NULL":str_replace(',', '', $rawdata->ChangeRealtime)).",";
		$query .= (!isset($rawdata->AfterHoursChangeRealtime)?"NULL":str_replace(',', '', $rawdata->AfterHoursChangeRealtime)).",";
		$query .= (!isset($rawdata->DividendShare)?"NULL":str_replace(',', '', $rawdata->DividendShare)).",";
        	$query .= "'".date("Y-m-d", strtotime($rawdata->LastTradeDate))."',";
        	$query .= "'".date("Y-m-d", strtotime($rawdata->TradeDate))."',";
		$query .= (!isset($rawdata->EarningsShare)?"NULL":str_replace(',', '', $rawdata->EarningsShare)).",";
		$query .= (!isset($rawdata->EPSEstimateCurrentYear)?"NULL":str_replace(',', '', $rawdata->EPSEstimateCurrentYear)).",";
		$query .= (!isset($rawdata->EPSEstimateNextYear)?"NULL":str_replace(',', '', $rawdata->EPSEstimateNextYear)).",";
		$query .= (!isset($rawdata->EPSEstimateNextQuarter)?"NULL":str_replace(',', '', $rawdata->EPSEstimateNextQuarter)).",";
		$query .= (!isset($rawdata->DaysLow)?"NULL":str_replace(',', '', $rawdata->DaysLow)).",";
		$query .= (!isset($rawdata->DaysHigh)?"NULL":str_replace(',', '', $rawdata->DaysHigh)).",";
		$query .= (!isset($rawdata->YearLow)?"NULL":str_replace(',', '', $rawdata->YearLow)).",";
		$query .= (!isset($rawdata->YearHigh)?"NULL":str_replace(',', '', $rawdata->YearHigh)).",";
		$query .= (!isset($rawdata->HoldingsGainPercent)?"NULL":str_replace(',', '', $rawdata->HoldingsGainPercent)).",";
		$query .= (!isset($rawdata->AnnualizedGain)?"NULL":str_replace(',', '', $rawdata->AnnualizedGain)).",";
		$query .= (!isset($rawdata->HoldingsGain)?"NULL":str_replace(',', '', $rawdata->HoldingsGain)).",";
		$query .= (!isset($rawdata->HoldingsGainPercentRealtime)?"NULL":str_replace(',', '', $rawdata->HoldingsGainPercentRealtime)).",";
		$query .= (!isset($rawdata->HoldingsGainRealtime)?"NULL":str_replace(',', '', $rawdata->HoldingsGainRealtime)).",";
        	$query .= "'".mysql_real_escape_string($rawdata->MoreInfo)."',";
		$query .= (!isset($rawdata->OrderBookRealtime)?"NULL":str_replace(',', '', $rawdata->OrderBookRealtime)).",";
		$query .= (!isset($rawdata->MarketCapitalization)?"NULL":str_replace(',', '', $rawdata->MarketCapitalization)).",";
		$query .= (!isset($rawdata->MarketCapRealtime)?"NULL":str_replace(',', '', $rawdata->MarketCapRealtime)).",";
		$query .= (!isset($rawdata->EBITDA)?"NULL":str_replace(',', '', $rawdata->EBITDA)).",";
		$query .= (!isset($rawdata->ChangeFromYearLow)?"NULL":str_replace(',', '', $rawdata->ChangeFromYearLow)).",";
		$query .= (!isset($rawdata->PercentChangeFromYearLow)?"NULL":str_replace(',', '', $rawdata->PercentChangeFromYearLow)).",";
		if(isset($rawdata->LastTradeRealTimeWithTime)) {
			$query .= "'".date("H:i",strtotime(substr($rawdata->LastTradeRealTimeWithTime, 0, strpos($rawdata->LastTradeRealTimeWithTime,"-")-1)))."',";
		} else {
			$query .= "NULL,";
		}
		$query .= (!isset($rawdata->ChangePercentRealtime)?"NULL":str_replace(',', '', $rawdata->ChangePercentRealtime)).",";
		$query .= (!isset($rawdata->ChangeFromYearHigh)?"NULL":str_replace(',', '', $rawdata->ChangeFromYearHigh)).",";
		$query .= (!isset($rawdata->PercebtChangeFromYearHigh)?"NULL":str_replace(',', '', $rawdata->PercebtChangeFromYearHigh));
	        $query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());

		$query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradeWithTime`, `LastTradePriceOnly`, `HighLimit`, `LowLimit`, `FiftyDayMovingAverage`, `TwoHundredDayMovingAverage`, `ChangeFromTwoHundredDayMovingAverage`, `PercentageChangeFromTwoHundredDayMovingAverage`, `ChangeFromFiftyDayMovingAverage`, `PercentChangeFromFiftyDayMovingAverage`, `Name`, `Notes`, `Open`, `PreviousClose`, `PricePaid`, `ChangeInPercent`, `PriceSales`, `PriceBook`, `ExDividendDate`, `PERatio`, `DividendPayDate`, `PERatioRealTime`, `PEGRatio`, `PriceEPSEstimateCurrentYear`, `PriceEPSEstimateNextYear`, `SharesOwned`, `ShortRatio`, `LastTradeTime`, `TickerTrend`, `OneYrTargetPrice`, `Volume`, `HoldingsValue`, `HoldingsValueRealTime`, `DaysValueChange`, `DaysValueChangeRealTime`, `StockExchange`, `DividendYield`, `PercentChange`) VALUES (";
		$query .= "'".$ticker_id."',";
		if(isset($rawdata->LastTradeWithTime)) {
			$query .= "'".date("H:i",strtotime(substr($rawdata->LastTradeWithTime, 0, strpos($rawdata->LastTradeWithTime,"-")-1)))."',";
		} else {
			$query .= "NULL,";
		}
		$query .= (!isset($rawdata->LastTradePriceOnly)?"NULL":str_replace(',', '', $rawdata->LastTradePriceOnly)).",";
		$query .= (!isset($rawdata->HighLimit)?"NULL":str_replace(',', '', $rawdata->HighLimit)).",";
		$query .= (!isset($rawdata->LowLimit)?"NULL":str_replace(',', '', $rawdata->LowLimit)).",";
		$query .= (!isset($rawdata->FiftydayMovingAverage)?"NULL":str_replace(',', '', $rawdata->FiftydayMovingAverage)).",";
		$query .= (!isset($rawdata->TwoHundreddayMovingAverage)?"NULL":str_replace(',', '', $rawdata->TwoHundreddayMovingAverage)).",";
		$query .= (!isset($rawdata->ChangeFromTwoHundreddayMovingAverage)?"NULL":str_replace(',', '', $rawdata->ChangeFromTwoHundreddayMovingAverage)).",";
		$query .= (!isset($rawdata->PercentChangeFromTwoHundreddayMovingAverage)?"NULL":str_replace(',', '', $rawdata->PercentChangeFromTwoHundreddayMovingAverage)).",";
		$query .= (!isset($rawdata->ChangeFromFiftydayMovingAverage)?"NULL":str_replace(',', '', $rawdata->ChangeFromFiftydayMovingAverage)).",";
		$query .= (!isset($rawdata->PercentChangeFromFiftydayMovingAverage)?"NULL":str_replace(',', '', $rawdata->PercentChangeFromFiftydayMovingAverage)).",";
        	$query .= "'".mysql_real_escape_string($rawdata->Name)."',";
        	$query .= "'".mysql_real_escape_string($rawdata->Notes)."',";
		$query .= (!isset($rawdata->Open)?"NULL":str_replace(',', '', $rawdata->Open)).",";
		$query .= (!isset($rawdata->PreviousClose)?"NULL":str_replace(',', '', $rawdata->PreviousClose)).",";
		$query .= (!isset($rawdata->PricePaid)?"NULL":str_replace(',', '', $rawdata->PricePaid)).",";
		$query .= (!isset($rawdata->ChangeinPercent)?"NULL":str_replace(',', '', $rawdata->ChangeinPercent)).",";
		$query .= (!isset($rawdata->PriceSales)?"NULL":str_replace(',', '', $rawdata->PriceSales)).",";
		$query .= (!isset($rawdata->PriceBook)?"NULL":str_replace(',', '', $rawdata->PriceBook)).",";
        	$query .= "'".date("Y-m-d", strtotime($rawdata->ExDividendDate))."',";
		$query .= (!isset($rawdata->PERatio)?"NULL":str_replace(',', '', $rawdata->PERatio)).",";
        	$query .= "'".date("Y-m-d", strtotime($rawdata->DividendPayDate))."',";
		$query .= (!isset($rawdata->PERatioRealtime)?"NULL":str_replace(',', '', $rawdata->PERatioRealtime)).",";
		$query .= (!isset($rawdata->PEGRatio)?"NULL":str_replace(',', '', $rawdata->PEGRatio)).",";
		$query .= (!isset($rawdata->PriceEPSEstimateCurrentYear)?"NULL":str_replace(',', '', $rawdata->PriceEPSEstimateCurrentYear)).",";
		$query .= (!isset($rawdata->PriceEPSEstimateNextYear)?"NULL":str_replace(',', '', $rawdata->PriceEPSEstimateNextYear)).",";
		$query .= (!isset($rawdata->SharesOwned)?"NULL":str_replace(',', '', $rawdata->SharesOwned)).",";
		$query .= (!isset($rawdata->ShortRatio)?"NULL":str_replace(',', '', $rawdata->ShortRatio)).",";
		if(isset($rawdata->LastTradeTime)) {
			$query .= "'".date("H:i",strtotime($rawdata->LastTradeTime))."',";
		} else {
			$query .= "NULL,";
		}
        	$query .= "'".$rawdata->TickerTrend."',";
		$query .= (!isset($rawdata->OneyrTargetPrice)?"NULL":str_replace(',', '', $rawdata->OneyrTargetPrice)).",";
		$query .= (!isset($rawdata->Volume)?"NULL":str_replace(',', '', $rawdata->Volume)).",";
		$query .= (!isset($rawdata->HoldingsValue)?"NULL":str_replace(',', '', $rawdata->HoldingsValue)).",";
		$query .= (!isset($rawdata->HoldingsValueRealtime)?"NULL":str_replace(',', '', $rawdata->HoldingsValueRealtime)).",";
		$query .= (!isset($rawdata->DaysValueChange)?"NULL":str_replace(',', '', $rawdata->DaysValueChange)).",";
		$query .= (!isset($rawdata->DaysValueChangeRealtime)?"NULL":str_replace(',', '', $rawdata->DaysValueChangeRealtime)).",";
        	$query .= "'".mysql_real_escape_string($rawdata->StockExchange)."',";
		$query .= (!isset($rawdata->DividendYield)?"NULL":str_replace(',', '', $rawdata->DividendYield)).",";
		$query .= (!isset($rawdata->PercentChange)?"NULL":str_replace(',', '', $rawdata->PercentChange));
	        $query .= ")";
        	mysql_query($query) or die ($query."\n".mysql_error());
	        $query_up = "UPDATE tickers_control SET last_volatile_date = NOW() WHERE ticker_id = " . $ticker_id;
        	mysql_query($query_up) or die(mysql_error());
        }
}
?>
