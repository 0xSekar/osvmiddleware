<?php
//Get yahoo Sector and Industry

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/database.php');
require_once("../include/yahoo/common.inc.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$query = "SELECT value FROM system WHERE parameter = 'query_yahoo'";
$res = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc($res);
if($row["value"] == 0) {
        echo "Skip process as yahoo queries are currently dissabled.\n";
        exit;
}

//Using customized Yahoo Social SDK (The default version does not work)
$yql = new YahooYQLQuery();

$count2 = 0;
$eupdated = 0;
$enotfound = 0;
$eerrors = 0;
echo "Updating Tickers...\n";
//Analyst Estimates needs more frequent updates
$query = "SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id";
$res = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$count2++;
	echo "Updating ".$row["ticker"]." Quote...";
	//Try to get yahoo data for the ticker


	$response = $yql->execute("select * from osv.finance.quotes where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");	
	if(isset($response->query) && isset($response->query->results)) {
		//Check if the symbol exists
		if(isset($response->query->results->quote)) {
			$eupdated ++;
			$rawdata = $response->query->results->quote;

                        $query = "delete from tickers_yahoo_quotes_1 where ticker_id = " . $row["id"];
                        mysql_query($query) or die (mysql_error());
                        $query = "delete from tickers_yahoo_quotes_2 where ticker_id = " . $row["id"];
                        mysql_query($query) or die (mysql_error());
                        $query = "delete from tickers_alt_aux where ticker_id = " . $row["id"];
                        mysql_query($query) or die (mysql_error());
                        $query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id`, `Ask`, `AverageDailyVolume`, `Bid`, `AskRealTime`, `BidRealTime`, `BookValue`, `Change`, `Commision`, `Currency`, `ChangeRealTime`, `AfterHoursChangeRealTime`, `DividendShare`, `LastTradeDate`, `TradeDate`, `EarningsShare`, `EPSEstimateCurrentYear`, `EPSEstimateNextYear`, `EPSEstimateNextQuarter`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`, `HoldingsGainPercent`, `AnnualizedGain`, `HoldingsGain`, `HoldingsGainPercentRealTime`, `AnnualizedGainRealTime`, `MoreInfo`, `OrderBookRealTime`, `MarketCapitalization`, `MarketCapRealTime`, `EBITDA`, `ChangeFromYearLow`, `PercentChangeFromYearLow`, `LastTradeRealTimeWithTime`, `ChangePercentRealTime`, `ChangeFromYearHigh`, `PercentChangeFromYearHigh`) VALUES (";
                        $query .= "'".$row["id"]."',";
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
                        mysql_query($query) or die(mysql_error());

                        $query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradeWithTime`, `LastTradePriceOnly`, `HighLimit`, `LowLimit`, `FiftyDayMovingAverage`, `TwoHundredDayMovingAverage`, `ChangeFromTwoHundredDayMovingAverage`, `PercentageChangeFromTwoHundredDayMovingAverage`, `ChangeFromFiftyDayMovingAverage`, `PercentChangeFromFiftyDayMovingAverage`, `Name`, `Notes`, `Open`, `PreviousClose`, `PricePaid`, `ChangeInPercent`, `PriceSales`, `PriceBook`, `ExDividendDate`, `PERatio`, `DividendPayDate`, `PERatioRealTime`, `PEGRatio`, `PriceEPSEstimateCurrentYear`, `PriceEPSEstimateNextYear`, `SharesOwned`, `ShortRatio`, `LastTradeTime`, `TickerTrend`, `OneYrTargetPrice`, `Volume`, `HoldingsValue`, `HoldingsValueRealTime`, `DaysValueChange`, `DaysValueChangeRealTime`, `StockExchange`, `DividendYield`, `PercentChange`, `SharesOutstanding`) VALUES (";
                        $query .= "'".$row["id"]."',";
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
                        $query .= (!isset($rawdata->PercentChange)?"NULL":str_replace(',', '', $rawdata->PercentChange)).",";
                        $query .= (!isset($rawdata->SharesOutstanding)?"NULL":str_replace(',', '', $rawdata->SharesOutstanding));
                        $query .= ")";
                        mysql_query($query) or die(mysql_error());

			$query1 = "SELECT *,
                        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
                        THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
                        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
                        FROM (SELECT c.id,a.*, MarketCapitalization as MarketValueofEquity,
                        (CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE MarketCapitalization / TotalLiabilities END) AS X4
                        FROM tickers c, mrq_alt_checks a, tickers_yahoo_quotes_1 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
			$res1 = mysql_query($query1) or die(mysql_error());
			$row1 = mysql_fetch_assoc($res1);

			$query2 = "SELECT *,
                        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
                        THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
                        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
                        FROM (SELECT c.id,a.*, SharesOutstandingDiluted * LastTradePriceOnly as MarketValueofEquity,
                        (CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE SharesOutstandingDiluted * LastTradePriceOnly / TotalLiabilities END) AS X4
                        FROM tickers c, ttm_alt_checks a, tickers_yahoo_quotes_2 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
			$res2 = mysql_query($query2) or die(mysql_error());
			$row2 = mysql_fetch_assoc($res2);

			$query = "INSERT INTO  `jjun0366_frontend`.`tickers_alt_aux` (`ticker_id` ,`mrq_MarketValueofEquity` ,`mrq_X4` ,`mrq_AltmanZNormal` ,`mrq_AltmanZRevised` ,`ttm_MarketValueofEquity`, `ttm_X4` ,`ttm_AltmanZNormal` ,`ttm_AltmanZRevised`)VALUES (";
			$query .= "'".$row["id"]."',";
			if(is_null($row1)) {
				$query .= "null,null,null,null,";
			} else {
				$query .= (is_null($row1["MarketValueofEquity"])?'null':($row1["MarketValueofEquity"])).",";
				$query .= (is_null($row1["X4"])?'null':($row1["X4"])).",";
				$query .= (is_null($row1["AltmanZNormal"])?'null':($row1["AltmanZNormal"])).",";
				$query .= (is_null($row1["AltmanZRevised"])?'null':($row1["AltmanZRevised"])).",";
			}
			if(is_null($row2)) {
				$query .= "null,null,null,null";
			} else {
				$query .= (is_null($row2["MarketValueofEquity"])?'null':($row2["MarketValueofEquity"])).",";
				$query .= (is_null($row2["X4"])?'null':($row2["X4"])).",";
				$query .= (is_null($row2["AltmanZNormal"])?'null':($row2["AltmanZNormal"])).",";
				$query .= (is_null($row2["AltmanZRevised"])?'null':($row2["AltmanZRevised"]));
			}
			$query .= ")";
			mysql_query($query) or die(mysql_error());

			$query = "UPDATE tickers_control SET last_volatile_date = NOW() WHERE ticker_id = " . $row["id"];
			mysql_query($query) or die(mysql_error());
		} else {
			$enotfound ++;
		}
	} elseif(isset($response->error)) {
		$eerrors ++;
	} else {
		$eerrors ++;
	}
	echo " Done\n";
}

echo $count2 . " rows processed\n";
echo "Quotes:\n";
echo "\t".$eupdated." tickers updates\n";
echo "\t".$enotfound." tickers not found on yahoo\n";
echo "\t".$eerrors." errors updating tickers\n";
?>
