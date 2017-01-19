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
				$db->exec("delete from tickers_alt_aux where ticker_id = " . $row["id"]);
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

			$query1 = "SELECT *,
				(CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
				 THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
				(CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
					FROM (SELECT c.id,a.*, MarketCapitalization as MarketValueofEquity,
							(CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE MarketCapitalization / TotalLiabilities END) AS X4
							FROM tickers c, mrq_alt_checks a, tickers_yahoo_quotes_1 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
			try {
				$res1 = $db->query($query1);
				$row1 = $res1->fetch(PDO::FETCH_ASSOC);
			} catch(PDOException $ex) {
				echo "\nDatabase Error"; //user message
				die("- Line: ".__LINE__." - ".$ex->getMessage());
			}

			$query2 = "SELECT *,
				(CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
				 THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
				(CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
					FROM (SELECT c.id,a.*, SharesOutstandingDiluted * LastTradePriceOnly as MarketValueofEquity,
							(CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE SharesOutstandingDiluted * LastTradePriceOnly / TotalLiabilities END) AS X4
							FROM tickers c, ttm_alt_checks a, tickers_yahoo_quotes_2 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
			try {
				$res2 = $db->query($query2);
				$row2 = $res2->fetch(PDO::FETCH_ASSOC);
			} catch(PDOException $ex) {
				echo "\nDatabase Error"; //user message
				die("- Line: ".__LINE__." - ".$ex->getMessage());
			}

			$query = "INSERT INTO  `tickers_alt_aux` (`ticker_id` ,`mrq_MarketValueofEquity` ,`mrq_X4` ,`mrq_AltmanZNormal` ,`mrq_AltmanZRevised` ,`ttm_MarketValueofEquity`, `ttm_X4` ,`ttm_AltmanZNormal` ,`ttm_AltmanZRevised`) VALUES (?,?,?,?,?,?,?,?,?)";
			$params = array();
			$params[] = $row["id"];

			if(is_null($row1)) {
				$params[] = null;
				$params[] = null;
				$params[] = null;
				$params[] = null;
			} else {
				$params[] = $row1["MarketValueofEquity"];
				$params[] = $row1["X4"];
				$params[] = $row1["AltmanZNormal"];
				$params[] = $row1["AltmanZRevised"];
			}
			if(is_null($row2)) {
				$params[] = null;
				$params[] = null;
				$params[] = null;
				$params[] = null;
			} else {
				$params[] = $row2["MarketValueofEquity"];
				$params[] = $row2["X4"];
				$params[] = $row2["AltmanZNormal"];
				$params[] = $row2["AltmanZRevised"];
			}
			try {
				$resf = $db->prepare($query);
				$resf->execute($params);
			} catch(PDOException $ex) {
				echo "\nDatabase Error"; //user message
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

	//Update from Barchart
			$sym = $row["ticker"]; //get symbol from yahoo rawdata
			echo "\nUpdating from Barchart:".$sym."\n";
		
			$queryOD = "http://ondemand.websol.barchart.com/getQuote.json?apikey=fbb10c94f13efa7fccbe641643f7901f&symbols=".$sym."&mode=I&fields=ask,avgVolume,bid,netChange,low,high,fiftyTwoWkLow,fiftyTwoWkHigh,lastPrice,percentChange,name,open,previousClose,exDividendDate,tradeTimestamp,volume,dividendYieldAnnual,sharesOutstanding";
			$resOD = file_get_contents($queryOD);
			$resJS = json_decode($resOD, true);
			//echo "\nData:".$resOD;
			$code = $resJS['status']['code'];
			//echo "\nCode:".$code."\n";

			if($code == 200){
				$query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id` , `Ask`, `AverageDailyVolume`, `Bid`, `Change`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)  ON DUPLICATE KEY UPDATE `Ask` = ?, `AverageDailyVolume` = ?, `Bid` = ?, `Change` = ?, `DaysLow` = ?, `DaysHigh` = ?, `YearLow` = ?, `YearHigh` = ?";
				$params = array();
				$params[] = $row["id"];
				$params[] = $resJS['results'][0]['ask'];
				$params[] = $resJS['results'][0]['avgVolume'];
				$params[] = $resJS['results'][0]['bid'];
				$params[] = $resJS['results'][0]['netChange'];		
				$params[] = $resJS['results'][0]['low'];
				$params[] = $resJS['results'][0]['high'];
				$params[] = $resJS['results'][0]['fiftyTwoWkLow'];
				$params[] = $resJS['results'][0]['fiftyTwoWkHigh'];
				
				$params[] = $resJS['results'][0]['ask'];
				$params[] = $resJS['results'][0]['avgVolume'];
				$params[] = $resJS['results'][0]['bid'];
				$params[] = $resJS['results'][0]['netChange'];		
				$params[] = $resJS['results'][0]['low'];
				$params[] = $resJS['results'][0]['high'];
				$params[] = $resJS['results'][0]['fiftyTwoWkLow'];
				$params[] = $resJS['results'][0]['fiftyTwoWkHigh'];
				try {
					$resb = $db->prepare($query);
					$resb->execute($params);
				} catch(PDOException $ex) {
					echo "\nDatabase Error"; //user message
					die("Line: ".__LINE__." - ".$ex->getMessage());
				}

				$query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradePriceOnly` , `Name` , `Open` , `PreviousClose` , `ChangeInPercent` , `ExDividendDate` , `LastTradeTime` , `Volume` ,`DaysValueChange` , `DividendYield` , `PercentChange` , `SharesOutstandingBC`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `LastTradePriceOnly` = ?, `Name` = ?, `Open` = ?, `PreviousClose` = ?, `ChangeInPercent` = ?, `ExDividendDate` = ?, `LastTradeTime` = ?, `Volume` = ?,`DaysValueChange` = ?, `DividendYield` = ?, `PercentChange` = ?, `SharesOutstandingBC` = ?";
				
				$params = array();				
				$params[] = $row["id"];
				$params[] = $resJS['results'][0]['lastPrice'];
				$params[] = $resJS['results'][0]['name'];
				$params[] = $resJS['results'][0]['open'];
				$params[] = $resJS['results'][0]['previousClose'];
				$params[] = $resJS['results'][0]['percentChange'];
				$params[] = $resJS['results'][0]['exDividendDate'];
				$params[] = date("H:i:s", strtotime($resJS['results'][0]['tradeTimestamp']));
				$params[] = $resJS['results'][0]['volume'];
				$params[] = $resJS['results'][0]['netChange'];
				$params[] = $resJS['results'][0]['dividendYieldAnnual'];
				$params[] = $resJS['results'][0]['percentChange'];
				$params[] = $resJS['results'][0]['sharesOutstanding'];
				
				$params[] = $resJS['results'][0]['lastPrice'];
				$params[] = $resJS['results'][0]['name'];
				$params[] = $resJS['results'][0]['open'];
				$params[] = $resJS['results'][0]['previousClose'];
				$params[] = $resJS['results'][0]['percentChange'];
				$params[] = $resJS['results'][0]['exDividendDate'];
				$params[] = date("H:i:s", strtotime($resJS['results'][0]['tradeTimestamp']));
				$params[] = $resJS['results'][0]['volume'];
				$params[] = $resJS['results'][0]['netChange'];
				$params[] = $resJS['results'][0]['dividendYieldAnnual'];
				$params[] = $resJS['results'][0]['percentChange'];
				$params[] = $resJS['results'][0]['sharesOutstanding'];				
				try {
					$resbc = $db->prepare($query);
					$resbc->execute($params);
				} catch(PDOException $ex) {
					echo "\nDatabase Error"; //user message
					die("Line: ".__LINE__." - ".$ex->getMessage());
				}

			}else{
				echo "\nError on Barchart Update for ticker ".$sym."\n";
			}

	echo " Done\n";
}
echo $count2 . " rows processed\n";
echo "Quotes:\n";
echo "\t".$eupdated." tickers updates\n";
echo "\t".$enotfound." tickers not found on yahoo\n";
echo "\t".$eerrors." errors updating tickers\n";
?>
