<?php
//Get yahoo Sector and Industry

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/database.php');
include_once('../db/db.php');
require_once("../include/yahoo/common.inc.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();
$db = Database::GetInstance(); 

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

//$query = "SELECT value FROM system WHERE parameter = 'query_yahoo'";
//$res = mysql_query($query) or die(mysql_error());
try {
        $res = $db->query("SELECT value FROM system WHERE parameter = 'query_yahoo'");
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
}
//$row = mysql_fetch_assoc($res);
$row = $res->fetch(PDO::FETCH_ASSOC);
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
//$query = "SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id";
//$res = mysql_query($query) or die(mysql_error());
try {
        $res = $db->query("SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id");
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
}
//while ($row = mysql_fetch_assoc($res)) {
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
	$count2++;
	echo "Updating ".$row["ticker"]." Quote...";
	//Try to get yahoo data for the ticker

	$response = $yql->execute("select * from osv.finance.quotes where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");	
	if(isset($response->query) && isset($response->query->results)) {
		//Check if the symbol exists
		if(isset($response->query->results->quote)) {
			$eupdated ++;
			$rawdata = $response->query->results->quote;
                        //$query = "delete from tickers_yahoo_quotes_1 where ticker_id = " . $row["id"];
                        //mysql_query($query) or die (mysql_error());
                        try {
                                $db->exec("delete from tickers_yahoo_quotes_1 where ticker_id = " . $row["id"]);
                                $db->exec("delete from tickers_yahoo_quotes_2 where ticker_id = " . $row["id"]);
                        } catch(PDOException $ex) {
                                echo "\nDatabase Error"; //user message
                                die("Line: ".__LINE__." - ".$ex->getMessage());
                        }
                        //$query = "delete from tickers_yahoo_quotes_2 where ticker_id = " . $row["id"];
                        //mysql_query($query) or die (mysql_error());
                        //$query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id`, `Ask`, `AverageDailyVolume`, `Bid`, `AskRealTime`, `BidRealTime`, `BookValue`, `Change`, `Commision`, `Currency`, `ChangeRealTime`, `AfterHoursChangeRealTime`, `DividendShare`, `LastTradeDate`, `TradeDate`, `EarningsShare`, `EPSEstimateCurrentYear`, `EPSEstimateNextYear`, `EPSEstimateNextQuarter`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`, `HoldingsGainPercent`, `AnnualizedGain`, `HoldingsGain`, `HoldingsGainPercentRealTime`, `AnnualizedGainRealTime`, `MoreInfo`, `OrderBookRealTime`, `MarketCapitalization`, `MarketCapRealTime`, `EBITDA`, `ChangeFromYearLow`, `PercentChangeFromYearLow`, `LastTradeRealTimeWithTime`, `ChangePercentRealTime`, `ChangeFromYearHigh`, `PercentChangeFromYearHigh`) VALUES (";
                        $query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id`, `Ask`, `AverageDailyVolume`, `Bid`, `AskRealTime`, `BidRealTime`, `BookValue`, `Change`, `Commision`, `Currency`, `ChangeRealTime`, `AfterHoursChangeRealTime`, `DividendShare`, `LastTradeDate`, `TradeDate`, `EarningsShare`, `EPSEstimateCurrentYear`, `EPSEstimateNextYear`, `EPSEstimateNextQuarter`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`, `HoldingsGainPercent`, `AnnualizedGain`, `HoldingsGain`, `HoldingsGainPercentRealTime`, `AnnualizedGainRealTime`, `MoreInfo`, `OrderBookRealTime`, `MarketCapitalization`, `MarketCapRealTime`, `EBITDA`, `ChangeFromYearLow`, `PercentChangeFromYearLow`, `LastTradeRealTimeWithTime`, `ChangePercentRealTime`, `ChangeFromYearHigh`, `PercentChangeFromYearHigh`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $params = array();
                        //$query .= "'".$row["id"]."',";
                        $params[] = $row["id"];                        
                        //$query .= (!isset($rawdata->Ask)?"NULL":str_replace(',', '', $rawdata->Ask)).",";
                        $params[] = (!isset($rawdata->Ask)?NULL:$rawdata->Ask);
                        //$query .= (!isset($rawdata->AverageDailyVolume)?"NULL":str_replace(',', '', $rawdata->AverageDailyVolume)).",";
                        $params[] = (!isset($rawdata->AverageDailyVolume)?NULL:$rawdata->AverageDailyVolume);
                        //$query .= (!isset($rawdata->Bid)?"NULL":str_replace(',', '', $rawdata->Bid)).",";
                        $params[] = (!isset($rawdata->Bid)?NULL:$rawdata->Bid);
                        //$query .= (!isset($rawdata->AskRealtime)?"NULL":str_replace(',', '', $rawdata->AskRealtime)).",";
                        $params[] = (!isset($rawdata->AskRealtime)?NULL:$rawdata->AskRealtime);
                        //$query .= (!isset($rawdata->BidRealtime)?"NULL":str_replace(',', '', $rawdata->BidRealtime)).",";
                        $params[] = (!isset($rawdata->BidRealtime)?NULL:$rawdata->BidRealtime);
                        //$query .= (!isset($rawdata->BookValue)?"NULL":str_replace(',', '', $rawdata->BookValue)).",";
                        $params[] = (!isset($rawdata->BookValue)?NULL:$rawdata->BookValue);
                        //$query .= (!isset($rawdata->Change)?"NULL":str_replace(',', '', $rawdata->Change)).",";
                        $params[] = (!isset($rawdata->Change)?NULL:$rawdata->Change);
                        //$query .= (!isset($rawdata->Commision)?"NULL":str_replace(',', '', $rawdata->Commision)).",";
                        $params[] = (!isset($rawdata->Commision)?NULL:$rawdata->Commision);
                        //$query .= "'".$rawdata->Currency."',";
                        $params[] = $rawdata->Currency;
                        //$query .= (!isset($rawdata->ChangeRealtime)?"NULL":str_replace(',', '', $rawdata->ChangeRealtime)).",";
                        $params[] = (!isset($rawdata->ChangeRealtime)?NULL:$rawdata->ChangeRealtime);
                        //$query .= (!isset($rawdata->AfterHoursChangeRealtime)?"NULL":str_replace(',', '', $rawdata->AfterHoursChangeRealtime)).",";
                        $params[] = (!isset($rawdata->AfterHoursChangeRealtime)?NULL:$rawdata->AfterHoursChangeRealtime);
                        //$query .= (!isset($rawdata->DividendShare)?"NULL":str_replace(',', '', $rawdata->DividendShare)).",";
                        $params[] = (!isset($rawdata->DividendShare)?NULL:$rawdata->DividendShare);
                        //$query .= "'".date("Y-m-d", strtotime($rawdata->LastTradeDate))."',";
                        $params[] = date("Y-m-d", strtotime($rawdata->LastTradeDate));
                        //$query .= "'".date("Y-m-d", strtotime($rawdata->TradeDate))."',";
                        $params[] = date("Y-m-d", strtotime($rawdata->TradeDate));
                        //$query .= (!isset($rawdata->EarningsShare)?"NULL":str_replace(',', '', $rawdata->EarningsShare)).",";
                        $params[] = (!isset($rawdata->EarningsShare)?NULL:$rawdata->EarningsShare);
                        //$query .= (!isset($rawdata->EPSEstimateCurrentYear)?"NULL":str_replace(',', '', $rawdata->EPSEstimateCurrentYear)).",";
                        $params[] = (!isset($rawdata->EPSEstimateCurrentYear)?NULL:$rawdata->EPSEstimateCurrentYear);
                        //$query .= (!isset($rawdata->EPSEstimateNextYear)?"NULL":str_replace(',', '', $rawdata->EPSEstimateNextYear)).",";
                        $params[] = (!isset($rawdata->EPSEstimateNextYear)?NULL:$rawdata->EPSEstimateNextYear);
                        //$query .= (!isset($rawdata->EPSEstimateNextQuarter)?"NULL":str_replace(',', '', $rawdata->EPSEstimateNextQuarter)).",";
                        $params[] = (!isset($rawdata->EPSEstimateNextQuarter)?NULL:$rawdata->EPSEstimateNextQuarter);
                        //$query .= (!isset($rawdata->DaysLow)?"NULL":str_replace(',', '', $rawdata->DaysLow)).",";
                        $params[] = (!isset($rawdata->DaysLow)?NULL:$rawdata->DaysLow);
                        //$query .= (!isset($rawdata->DaysHigh)?"NULL":str_replace(',', '', $rawdata->DaysHigh)).",";
                        $params[] = (!isset($rawdata->DaysHigh)?NULL:$rawdata->DaysHigh);
                        //$query .= (!isset($rawdata->YearLow)?"NULL":str_replace(',', '', $rawdata->YearLow)).",";
                        $params[] = (!isset($rawdata->YearLow)?NULL:$rawdata->YearLow);
                        //$query .= (!isset($rawdata->YearHigh)?"NULL":str_replace(',', '', $rawdata->YearHigh)).",";
                        $params[] = (!isset($rawdata->YearHigh)?NULL:$rawdata->YearHigh);
                        //$query .= (!isset($rawdata->HoldingsGainPercent)?"NULL":str_replace(',', '', $rawdata->HoldingsGainPercent)).",";
                        $params[] = (!isset($rawdata->HoldingsGainPercent)?NULL:$rawdata->HoldingsGainPercent);
                        //$query .= (!isset($rawdata->AnnualizedGain)?"NULL":str_replace(',', '', $rawdata->AnnualizedGain)).",";
                        $params[] = (!isset($rawdata->AnnualizedGain)?NULL:$rawdata->AnnualizedGain);
                        //$query .= (!isset($rawdata->HoldingsGain)?"NULL":str_replace(',', '', $rawdata->HoldingsGain)).",";
                        $params[] = (!isset($rawdata->HoldingsGain)?NULL:$rawdata->HoldingsGain);
                        //$query .= (!isset($rawdata->HoldingsGainPercentRealtime)?"NULL":str_replace(',', '', $rawdata->HoldingsGainPercentRealtime)).",";
                        $params[] = (!isset($rawdata->HoldingsGainPercentRealtime)?NULL:$rawdata->HoldingsGainPercentRealtime);
                        //$query .= (!isset($rawdata->HoldingsGainRealtime)?"NULL":str_replace(',', '', $rawdata->HoldingsGainRealtime)).",";
                        $params[] = (!isset($rawdata->HoldingsGainRealtime)?NULL:$rawdata->HoldingsGainRealtime);
                        //$query .= "'".mysql_real_escape_string($rawdata->MoreInfo)."',";
                        $params[] = $rawdata->MoreInfo;
                        //$query .= (!isset($rawdata->OrderBookRealtime)?"NULL":str_replace(',', '', $rawdata->OrderBookRealtime)).",";
                        $params[] = (!isset($rawdata->OrderBookRealtime)?NULL:$rawdata->OrderBookRealtime);
                        //$query .= (!isset($rawdata->MarketCapitalization)?"NULL":str_replace(',', '', $rawdata->MarketCapitalization)).",";
                        $params[] = (!isset($rawdata->MarketCapitalization)?NULL:$rawdata->MarketCapitalization);
                        //$query .= (!isset($rawdata->MarketCapRealtime)?"NULL":str_replace(',', '', $rawdata->MarketCapRealtime)).",";
                        $params[] = (!isset($rawdata->MarketCapRealtime)?NULL:$rawdata->MarketCapRealtime);
                        //$query .= (!isset($rawdata->EBITDA)?"NULL":str_replace(',', '', $rawdata->EBITDA)).",";
                        $params[] = (!isset($rawdata->EBITDA)?NULL:$rawdata->EBITDA);
                        //$query .= (!isset($rawdata->ChangeFromYearLow)?"NULL":str_replace(',', '', $rawdata->ChangeFromYearLow)).",";
                        $params[] = (!isset($rawdata->ChangeFromYearLow)?NULL:$rawdata->ChangeFromYearLow);
                        //$query .= (!isset($rawdata->PercentChangeFromYearLow)?"NULL":str_replace(',', '', $rawdata->PercentChangeFromYearLow)).",";
                        $params[] = (!isset($rawdata->PercentChangeFromYearLow)?NULL:$rawdata->PercentChangeFromYearLow);
                        if(isset($rawdata->LastTradeRealTimeWithTime)) {
                                //$query .= "'".date("H:i",strtotime(substr($rawdata->LastTradeRealTimeWithTime, 0, strpos($rawdata->LastTradeRealTimeWithTime,"-")-1)))."',";
                                $params[] = date("H:i",strtotime(substr($rawdata->LastTradeRealTimeWithTime, 0, strpos($rawdata->LastTradeRealTimeWithTime,"-")-1)));
                        } else {
                                //$query .= "NULL,";
                                $params[] = NULL;
                        }                        
                        //$query .= (!isset($rawdata->ChangePercentRealtime)?"NULL":str_replace(',', '', $rawdata->ChangePercentRealtime)).",";
                        $params[] = (!isset($rawdata->ChangePercentRealtime)?NULL:$rawdata->ChangePercentRealtime);
                        //$query .= (!isset($rawdata->ChangeFromYearHigh)?"NULL":str_replace(',', '', $rawdata->ChangeFromYearHigh)).",";
                        $params[] = (!isset($rawdata->ChangeFromYearHigh)?NULL:$rawdata->ChangeFromYearHigh);
                        //$query .= (!isset($rawdata->PercebtChangeFromYearHigh)?"NULL":str_replace(',', '', $rawdata->PercebtChangeFromYearHigh));
                        $params[] = (!isset($rawdata->PercebtChangeFromYearHigh)?NULL:$rawdata->PercebtChangeFromYearHigh);
                        //$query .= ")";
                        //mysql_query($query) or die(mysql_error());
                        try {
                                $res1 = $db->prepare($query);
                                //$res->execute(array($rawdata->MoreInfo));
                                $res1->execute($params);
                        } catch(PDOException $ex) {
                                echo "\nDatabase Error"; //user message
                                die("Line: ".__LINE__." - ".$ex->getMessage());
                        }

                        //$query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradeWithTime`, `LastTradePriceOnly`, `HighLimit`, `LowLimit`, `FiftyDayMovingAverage`, `TwoHundredDayMovingAverage`, `ChangeFromTwoHundredDayMovingAverage`, `PercentageChangeFromTwoHundredDayMovingAverage`, `ChangeFromFiftyDayMovingAverage`, `PercentChangeFromFiftyDayMovingAverage`, `Name`, `Notes`, `Open`, `PreviousClose`, `PricePaid`, `ChangeInPercent`, `PriceSales`, `PriceBook`, `ExDividendDate`, `PERatio`, `DividendPayDate`, `PERatioRealTime`, `PEGRatio`, `PriceEPSEstimateCurrentYear`, `PriceEPSEstimateNextYear`, `SharesOwned`, `ShortRatio`, `LastTradeTime`, `TickerTrend`, `OneYrTargetPrice`, `Volume`, `HoldingsValue`, `HoldingsValueRealTime`, `DaysValueChange`, `DaysValueChangeRealTime`, `StockExchange`, `DividendYield`, `PercentChange`, `SharesOutstanding`) VALUES (";
                        $query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradeWithTime`, `LastTradePriceOnly`, `HighLimit`, `LowLimit`, `FiftyDayMovingAverage`, `TwoHundredDayMovingAverage`, `ChangeFromTwoHundredDayMovingAverage`, `PercentageChangeFromTwoHundredDayMovingAverage`, `ChangeFromFiftyDayMovingAverage`, `PercentChangeFromFiftyDayMovingAverage`, `Name`, `Notes`, `Open`, `PreviousClose`, `PricePaid`, `ChangeInPercent`, `PriceSales`, `PriceBook`, `ExDividendDate`, `PERatio`, `DividendPayDate`, `PERatioRealTime`, `PEGRatio`, `PriceEPSEstimateCurrentYear`, `PriceEPSEstimateNextYear`, `SharesOwned`, `ShortRatio`, `LastTradeTime`, `TickerTrend`, `OneYrTargetPrice`, `Volume`, `HoldingsValue`, `HoldingsValueRealTime`, `DaysValueChange`, `DaysValueChangeRealTime`, `StockExchange`, `DividendYield`, `PercentChange`, `SharesOutstanding`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //40par
                        $params = array();
                        //$query .= "'".$row["id"]."',";
                        $params[] = $row["id"];
                        if(isset($rawdata->LastTradeWithTime)) {
                                //$query .= "'".date("H:i",strtotime(substr($rawdata->LastTradeWithTime, 0, strpos($rawdata->LastTradeWithTime,"-")-1)))."',";
                                $params[] = date("H:i",strtotime(substr($rawdata->LastTradeWithTime, 0, strpos($rawdata->LastTradeWithTime,"-")-1)));
                        } else {
                                //$query .= "NULL,";
                                $params[] = NULL;
                        }
                        //$query .= (!isset($rawdata->LastTradePriceOnly)?"NULL":str_replace(',', '', $rawdata->LastTradePriceOnly)).",";
                        $params[] = (!isset($rawdata->LastTradePriceOnly)?NULL:$rawdata->LastTradePriceOnly);
                        //$query .= (!isset($rawdata->HighLimit)?"NULL":str_replace(',', '', $rawdata->HighLimit)).",";
                        $params[] = (!isset($rawdata->HighLimit)?NULL:$rawdata->HighLimit);
                        //$query .= (!isset($rawdata->LowLimit)?"NULL":str_replace(',', '', $rawdata->LowLimit)).",";
                        $params[] = (!isset($rawdata->LowLimit)?NULL:$rawdata->LowLimit);
                        //$query .= (!isset($rawdata->FiftydayMovingAverage)?"NULL":str_replace(',', '', $rawdata->FiftydayMovingAverage)).",";
                        $params[] = (!isset($rawdata->FiftydayMovingAverage)?NULL:$rawdata->FiftydayMovingAverage);
                        //$query .= (!isset($rawdata->TwoHundreddayMovingAverage)?"NULL":str_replace(',', '', $rawdata->TwoHundreddayMovingAverage)).",";
                        $params[] = (!isset($rawdata->TwoHundreddayMovingAverage)?NULL:$rawdata->TwoHundreddayMovingAverage);
                        //$query .= (!isset($rawdata->ChangeFromTwoHundreddayMovingAverage)?"NULL":str_replace(',', '', $rawdata->ChangeFromTwoHundreddayMovingAverage)).",";
                        $params[] = (!isset($rawdata->ChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->ChangeFromTwoHundreddayMovingAverage);
                        //$query .= (!isset($rawdata->PercentChangeFromTwoHundreddayMovingAverage)?"NULL":str_replace(',', '', $rawdata->PercentChangeFromTwoHundreddayMovingAverage)).",";
                        $params[] = (!isset($rawdata->PercentChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->PercentChangeFromTwoHundreddayMovingAverage);
                        //$query .= (!isset($rawdata->ChangeFromFiftydayMovingAverage)?"NULL":str_replace(',', '', $rawdata->ChangeFromFiftydayMovingAverage)).",";
                        $params[] = (!isset($rawdata->ChangeFromFiftydayMovingAverage)?NULL:$rawdata->ChangeFromFiftydayMovingAverage);
                        //$query .= (!isset($rawdata->PercentChangeFromFiftydayMovingAverage)?"NULL":str_replace(',', '', $rawdata->PercentChangeFromFiftydayMovingAverage)).",";
                        $params[] = (!isset($rawdata->PercentChangeFromFiftydayMovingAverage)?NULL:$rawdata->PercentChangeFromFiftydayMovingAverage);
                        //$query .= "'".mysql_real_escape_string($rawdata->Name)."',";
                        //$query .= "?,";
                        $params[] = $rawdata->Name;
                        //$query .= "'".mysql_real_escape_string($rawdata->Notes)."',";
                        //$query .= "?,";
                        $params[] = $rawdata->Notes;
                        //$query .= (!isset($rawdata->Open)?"NULL":str_replace(',', '', $rawdata->Open)).",";
                        $params[] = (!isset($rawdata->Open)?NULL:$rawdata->Open);
                        //$query .= (!isset($rawdata->PreviousClose)?"NULL":str_replace(',', '', $rawdata->PreviousClose)).",";
                        $params[] = (!isset($rawdata->PreviousClose)?NULL:$rawdata->PreviousClose);
                        //$query .= (!isset($rawdata->PricePaid)?"NULL":str_replace(',', '', $rawdata->PricePaid)).",";
                        $params[] = (!isset($rawdata->PricePaid)?NULL:$rawdata->PricePaid);
                        //$query .= (!isset($rawdata->ChangeinPercent)?"NULL":str_replace(',', '', $rawdata->ChangeinPercent)).",";
                        $params[] = (!isset($rawdata->ChangeinPercent)?NULL:$rawdata->ChangeinPercent);
                        //$query .= (!isset($rawdata->PriceSales)?"NULL":str_replace(',', '', $rawdata->PriceSales)).",";
                        $params[] = (!isset($rawdata->PriceSales)?NULL:$rawdata->PriceSales);
                        //$query .= (!isset($rawdata->PriceBook)?"NULL":str_replace(',', '', $rawdata->PriceBook)).",";
                        $params[] = (!isset($rawdata->PriceBook)?NULL:$rawdata->PriceBook);
                        //$query .= "'".date("Y-m-d", strtotime($rawdata->ExDividendDate))."',";
                        $params[] = date("Y-m-d", strtotime($rawdata->ExDividendDate));
                        //$query .= (!isset($rawdata->PERatio)?"NULL":str_replace(',', '', $rawdata->PERatio)).",";
                        $params[] = (!isset($rawdata->PERatio)?NULL:$rawdata->PERatio);
                        //$query .= "'".date("Y-m-d", strtotime($rawdata->DividendPayDate))."',";
                        $params[] = date("Y-m-d", strtotime($rawdata->DividendPayDate));
                        //$query .= (!isset($rawdata->PERatioRealtime)?"NULL":str_replace(',', '', $rawdata->PERatioRealtime)).",";
                        $params[] = (!isset($rawdata->PERatioRealtime)?NULL:$rawdata->PERatioRealtime);
                        //$query .= (!isset($rawdata->PEGRatio)?"NULL":str_replace(',', '', $rawdata->PEGRatio)).",";
                        $params[] = (!isset($rawdata->PEGRatio)?NULL:$rawdata->PEGRatio);
                        //$query .= (!isset($rawdata->PriceEPSEstimateCurrentYear)?"NULL":str_replace(',', '', $rawdata->PriceEPSEstimateCurrentYear)).",";
                        $params[] = (!isset($rawdata->PriceEPSEstimateCurrentYear)?NULL:$rawdata->PriceEPSEstimateCurrentYear);
                        //$query .= (!isset($rawdata->PriceEPSEstimateNextYear)?"NULL":str_replace(',', '', $rawdata->PriceEPSEstimateNextYear)).",";
                        $params[] = (!isset($rawdata->PriceEPSEstimateNextYear)?NULL:$rawdata->PriceEPSEstimateNextYear);
                        //$query .= (!isset($rawdata->SharesOwned)?"NULL":str_replace(',', '', $rawdata->SharesOwned)).",";
                        $params[] = (!isset($rawdata->SharesOwned)?NULL:$rawdata->SharesOwned);
                        //$query .= (!isset($rawdata->ShortRatio)?"NULL":str_replace(',', '', $rawdata->ShortRatio)).",";
                        $params[] = (!isset($rawdata->ShortRatio)?NULL:$rawdata->ShortRatio);
                        if(isset($rawdata->LastTradeTime)) {
                                //$query .= "'".date("H:i",strtotime($rawdata->LastTradeTime))."',";
                                $params[] = date("H:i",strtotime($rawdata->LastTradeTime));
                        } else {
                                //$query .= "NULL,";
                                $params[] = NULL;
                        }
                        //$query .= "'".$rawdata->TickerTrend."',";
                        $params[] = $rawdata->TickerTrend;
                        //$query .= (!isset($rawdata->OneyrTargetPrice)?"NULL":str_replace(',', '', $rawdata->OneyrTargetPrice)).",";
                        $params[] = (!isset($rawdata->OneyrTargetPrice)?NULL:$rawdata->OneyrTargetPrice);
                        //$query .= (!isset($rawdata->Volume)?"NULL":str_replace(',', '', $rawdata->Volume)).",";
                        $params[] = (!isset($rawdata->Volume)?NULL:$rawdata->Volume);
                        //$query .= (!isset($rawdata->HoldingsValue)?"NULL":str_replace(',', '', $rawdata->HoldingsValue)).",";
                        $params[] = (!isset($rawdata->HoldingsValue)?NULL:$rawdata->HoldingsValue);
                        //$query .= (!isset($rawdata->HoldingsValueRealtime)?"NULL":str_replace(',', '', $rawdata->HoldingsValueRealtime)).",";
                        $params[] = (!isset($rawdata->HoldingsValueRealtime)?NULL:$rawdata->HoldingsValueRealtime);
                        //$query .= (!isset($rawdata->DaysValueChange)?"NULL":str_replace(',', '', $rawdata->DaysValueChange)).",";
                        $params[] = (!isset($rawdata->DaysValueChange)?NULL:$rawdata->DaysValueChange);
                        //$query .= (!isset($rawdata->DaysValueChangeRealtime)?"NULL":str_replace(',', '', $rawdata->DaysValueChangeRealtime)).",";
                        $params[] = (!isset($rawdata->DaysValueChangeRealtime)?NULL:$rawdata->DaysValueChangeRealtime);
                        //$query .= "'".mysql_real_escape_string($rawdata->StockExchange)."',";
                        //$query .= "?,";
                        $params[] = $rawdata->StockExchange;
                        //$query .= (!isset($rawdata->DividendYield)?"NULL":str_replace(',', '', $rawdata->DividendYield)).",";
                        $params[] = (!isset($rawdata->DividendYield)?NULL:$rawdata->DividendYield);
                        //$query .= (!isset($rawdata->PercentChange)?"NULL":str_replace(',', '', $rawdata->Per
                        $params[] = (!isset($rawdata->PercentChange)?NULL:$rawdata->PercentChange);
                        //$query .= (!isset($rawdata->SharesOutstanding)?"NULL":str_replace(',', '', $rawdata->SharesOutstanding));
                        $params[] = (!isset($rawdata->SharesOutstanding)?NULL:$rawdata->SharesOutstanding);
                        //$query .= ")";                        
                        //mysql_query($query) or die(mysql_error());
                        try {
                                $res1 = $db->prepare($query);
                                $res1->execute($params);
                        } catch(PDOException $ex) {
                                echo "\nDatabase Error "; //user message
                                die("- Line: ".__LINE__." - ".$ex->getMessage());
                        }
			//$query = "UPDATE tickers_control SET last_volatile_date = NOW() WHERE ticker_id = " . $row["id"];
			//mysql_query($query) or die(mysql_error());
                        try {
                                $db->exec("UPDATE tickers_control SET last_volatile_date = NOW() WHERE ticker_id = " . $row["id"]);
                        } catch(PDOException $ex) {
                                echo "\nDatabase Error"; //user message
                                die("- Line: ".__LINE__." - ".$ex->getMessage());
                        }
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
