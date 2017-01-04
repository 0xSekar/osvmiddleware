<?php
//Get yahoo Sector and Industry

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/db.php');
require_once("../include/yahoo/common.inc.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$db = Database::GetInstance(); 

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

try {
        $res = $db->query("SELECT value FROM system WHERE parameter = 'query_yahoo'");
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
}
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
try {
        $res = $db->query("SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id");
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
}
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
                        try {
                                $db->exec("delete from tickers_yahoo_quotes_1 where ticker_id = " . $row["id"]);
                                $db->exec("delete from tickers_yahoo_quotes_2 where ticker_id = " . $row["id"]);
                        } catch(PDOException $ex) {
                                echo "\nDatabase Error"; //user message
                                die("Line: ".__LINE__." - ".$ex->getMessage());
                        }
                        $query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id`, `Ask`, `AverageDailyVolume`, `Bid`, `AskRealTime`, `BidRealTime`, `BookValue`, `Change`, `Commision`, `Currency`, `ChangeRealTime`, `AfterHoursChangeRealTime`, `DividendShare`, `LastTradeDate`, `TradeDate`, `EarningsShare`, `EPSEstimateCurrentYear`, `EPSEstimateNextYear`, `EPSEstimateNextQuarter`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`, `HoldingsGainPercent`, `AnnualizedGain`, `HoldingsGain`, `HoldingsGainPercentRealTime`, `AnnualizedGainRealTime`, `MoreInfo`, `OrderBookRealTime`, `MarketCapitalization`, `MarketCapRealTime`, `EBITDA`, `ChangeFromYearLow`, `PercentChangeFromYearLow`, `LastTradeRealTimeWithTime`, `ChangePercentRealTime`, `ChangeFromYearHigh`, `PercentChangeFromYearHigh`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $params = array();
                        $params[] = $row["id"];                        
                        $params[] = (!isset($rawdata->Ask)?NULL:$rawdata->Ask);
                        $params[] = (!isset($rawdata->AverageDailyVolume)?NULL:$rawdata->AverageDailyVolume);
                        $params[] = (!isset($rawdata->Bid)?NULL:$rawdata->Bid);
                        $params[] = (!isset($rawdata->AskRealtime)?NULL:$rawdata->AskRealtime);
                        $params[] = (!isset($rawdata->BidRealtime)?NULL:$rawdata->BidRealtime);
                        $params[] = (!isset($rawdata->BookValue)?NULL:$rawdata->BookValue);
                        $params[] = (!isset($rawdata->Change)?NULL:$rawdata->Change);
                        $params[] = (!isset($rawdata->Commision)?NULL:$rawdata->Commision);
                        $params[] = $rawdata->Currency;
                        $params[] = (!isset($rawdata->ChangeRealtime)?NULL:$rawdata->ChangeRealtime);
                        $params[] = (!isset($rawdata->AfterHoursChangeRealtime)?NULL:$rawdata->AfterHoursChangeRealtime);
                        $params[] = (!isset($rawdata->DividendShare)?NULL:$rawdata->DividendShare);
                        $params[] = date("Y-m-d", strtotime($rawdata->LastTradeDate));
                        $params[] = date("Y-m-d", strtotime($rawdata->TradeDate));
                        $params[] = (!isset($rawdata->EarningsShare)?NULL:$rawdata->EarningsShare);
                        $params[] = (!isset($rawdata->EPSEstimateCurrentYear)?NULL:$rawdata->EPSEstimateCurrentYear);
                        $params[] = (!isset($rawdata->EPSEstimateNextYear)?NULL:$rawdata->EPSEstimateNextYear);
                        $params[] = (!isset($rawdata->EPSEstimateNextQuarter)?NULL:$rawdata->EPSEstimateNextQuarter);
                        $params[] = (!isset($rawdata->DaysLow)?NULL:$rawdata->DaysLow);
                        $params[] = (!isset($rawdata->DaysHigh)?NULL:$rawdata->DaysHigh);
                        $params[] = (!isset($rawdata->YearLow)?NULL:$rawdata->YearLow);
                        $params[] = (!isset($rawdata->YearHigh)?NULL:$rawdata->YearHigh);
                        $params[] = (!isset($rawdata->HoldingsGainPercent)?NULL:$rawdata->HoldingsGainPercent);
                        $params[] = (!isset($rawdata->AnnualizedGain)?NULL:$rawdata->AnnualizedGain);
                        $params[] = (!isset($rawdata->HoldingsGain)?NULL:$rawdata->HoldingsGain);
                        $params[] = (!isset($rawdata->HoldingsGainPercentRealtime)?NULL:$rawdata->HoldingsGainPercentRealtime);
                        $params[] = (!isset($rawdata->HoldingsGainRealtime)?NULL:$rawdata->HoldingsGainRealtime);
                        $params[] = $rawdata->MoreInfo;
                        $params[] = (!isset($rawdata->OrderBookRealtime)?NULL:$rawdata->OrderBookRealtime);
                        $params[] = (!isset($rawdata->MarketCapitalization)?NULL:$rawdata->MarketCapitalization);
                        $params[] = (!isset($rawdata->MarketCapRealtime)?NULL:$rawdata->MarketCapRealtime);
                        $params[] = (!isset($rawdata->EBITDA)?NULL:$rawdata->EBITDA);
                        $params[] = (!isset($rawdata->ChangeFromYearLow)?NULL:$rawdata->ChangeFromYearLow);
                        $params[] = (!isset($rawdata->PercentChangeFromYearLow)?NULL:$rawdata->PercentChangeFromYearLow);
                        if(isset($rawdata->LastTradeRealTimeWithTime)) {
                                $params[] = date("H:i",strtotime(substr($rawdata->LastTradeRealTimeWithTime, 0, strpos($rawdata->LastTradeRealTimeWithTime,"-")-1)));
                        } else {
                                $params[] = NULL;
                        }                        
                        $params[] = (!isset($rawdata->ChangePercentRealtime)?NULL:$rawdata->ChangePercentRealtime);
                        $params[] = (!isset($rawdata->ChangeFromYearHigh)?NULL:$rawdata->ChangeFromYearHigh);
                        $params[] = (!isset($rawdata->PercebtChangeFromYearHigh)?NULL:$rawdata->PercebtChangeFromYearHigh);
                        try {
                                $res1 = $db->prepare($query);
                                $res1->execute($params);
                        } catch(PDOException $ex) {
                                echo "\nDatabase Error"; //user message
                                die("Line: ".__LINE__." - ".$ex->getMessage());
                        }

                        $query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradeWithTime`, `LastTradePriceOnly`, `HighLimit`, `LowLimit`, `FiftyDayMovingAverage`, `TwoHundredDayMovingAverage`, `ChangeFromTwoHundredDayMovingAverage`, `PercentageChangeFromTwoHundredDayMovingAverage`, `ChangeFromFiftyDayMovingAverage`, `PercentChangeFromFiftyDayMovingAverage`, `Name`, `Notes`, `Open`, `PreviousClose`, `PricePaid`, `ChangeInPercent`, `PriceSales`, `PriceBook`, `ExDividendDate`, `PERatio`, `DividendPayDate`, `PERatioRealTime`, `PEGRatio`, `PriceEPSEstimateCurrentYear`, `PriceEPSEstimateNextYear`, `SharesOwned`, `ShortRatio`, `LastTradeTime`, `TickerTrend`, `OneYrTargetPrice`, `Volume`, `HoldingsValue`, `HoldingsValueRealTime`, `DaysValueChange`, `DaysValueChangeRealTime`, `StockExchange`, `DividendYield`, `PercentChange`, `SharesOutstanding`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //40par
                        $params = array();
                        $params[] = $row["id"];
                        if(isset($rawdata->LastTradeWithTime)) {
                                $params[] = date("H:i",strtotime(substr($rawdata->LastTradeWithTime, 0, strpos($rawdata->LastTradeWithTime,"-")-1)));
                        } else {
                                $params[] = NULL;
                        }
                        $params[] = (!isset($rawdata->LastTradePriceOnly)?NULL:$rawdata->LastTradePriceOnly);
                        $params[] = (!isset($rawdata->HighLimit)?NULL:$rawdata->HighLimit);
                        $params[] = (!isset($rawdata->LowLimit)?NULL:$rawdata->LowLimit);
                        $params[] = (!isset($rawdata->FiftydayMovingAverage)?NULL:$rawdata->FiftydayMovingAverage);
                        $params[] = (!isset($rawdata->TwoHundreddayMovingAverage)?NULL:$rawdata->TwoHundreddayMovingAverage);
                        $params[] = (!isset($rawdata->ChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->ChangeFromTwoHundreddayMovingAverage);
                        $params[] = (!isset($rawdata->PercentChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->PercentChangeFromTwoHundreddayMovingAverage);
                        $params[] = (!isset($rawdata->ChangeFromFiftydayMovingAverage)?NULL:$rawdata->ChangeFromFiftydayMovingAverage);
                        $params[] = (!isset($rawdata->PercentChangeFromFiftydayMovingAverage)?NULL:$rawdata->PercentChangeFromFiftydayMovingAverage);
                        $params[] = $rawdata->Name;
                        $params[] = $rawdata->Notes;
                        $params[] = (!isset($rawdata->Open)?NULL:$rawdata->Open);
                        $params[] = (!isset($rawdata->PreviousClose)?NULL:$rawdata->PreviousClose);
                        $params[] = (!isset($rawdata->PricePaid)?NULL:$rawdata->PricePaid);
                        $params[] = (!isset($rawdata->ChangeinPercent)?NULL:$rawdata->ChangeinPercent);
                        $params[] = (!isset($rawdata->PriceSales)?NULL:$rawdata->PriceSales);
                        $params[] = (!isset($rawdata->PriceBook)?NULL:$rawdata->PriceBook);
                        $params[] = date("Y-m-d", strtotime($rawdata->ExDividendDate));
                        $params[] = (!isset($rawdata->PERatio)?NULL:$rawdata->PERatio);
                        $params[] = date("Y-m-d", strtotime($rawdata->DividendPayDate));
                        $params[] = (!isset($rawdata->PERatioRealtime)?NULL:$rawdata->PERatioRealtime);
                        $params[] = (!isset($rawdata->PEGRatio)?NULL:$rawdata->PEGRatio);
                        $params[] = (!isset($rawdata->PriceEPSEstimateCurrentYear)?NULL:$rawdata->PriceEPSEstimateCurrentYear);
                        $params[] = (!isset($rawdata->PriceEPSEstimateNextYear)?NULL:$rawdata->PriceEPSEstimateNextYear);
                        $params[] = (!isset($rawdata->SharesOwned)?NULL:$rawdata->SharesOwned);
                        $params[] = (!isset($rawdata->ShortRatio)?NULL:$rawdata->ShortRatio);
                        if(isset($rawdata->LastTradeTime)) {
                                $params[] = date("H:i",strtotime($rawdata->LastTradeTime));
                        } else {
                                $params[] = NULL;
                        }
                        $params[] = $rawdata->TickerTrend;
                        $params[] = (!isset($rawdata->OneyrTargetPrice)?NULL:$rawdata->OneyrTargetPrice);
                        $params[] = (!isset($rawdata->Volume)?NULL:$rawdata->Volume);
                        $params[] = (!isset($rawdata->HoldingsValue)?NULL:$rawdata->HoldingsValue);
                        $params[] = (!isset($rawdata->HoldingsValueRealtime)?NULL:$rawdata->HoldingsValueRealtime);
                        $params[] = (!isset($rawdata->DaysValueChange)?NULL:$rawdata->DaysValueChange);
                        $params[] = (!isset($rawdata->DaysValueChangeRealtime)?NULL:$rawdata->DaysValueChangeRealtime);
                        $params[] = $rawdata->StockExchange;
                        $params[] = (!isset($rawdata->DividendYield)?NULL:$rawdata->DividendYield);
                        $params[] = (!isset($rawdata->PercentChange)?NULL:$rawdata->PercentChange);
                        $params[] = (!isset($rawdata->SharesOutstanding)?NULL:$rawdata->SharesOutstanding);
                        try {
                                $res1 = $db->prepare($query);
                                $res1->execute($params);
                        } catch(PDOException $ex) {
                                echo "\nDatabase Error "; //user message
                                die("- Line: ".__LINE__." - ".$ex->getMessage());
                        }
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
