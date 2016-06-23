<?php
//Get yahoo estimates using YQL.

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/database.php');
include_once('./include/raw_data_update_yahoo_keystats.php');
require_once("../include/yahoo/common.inc.php");
include_once('./include/update_key_ratios_ttm.php');
include_once('./include/update_ratings_ttm.php');

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

//Access on dev environment
$username = 'osv';
$password = 'test1234!';
$context = stream_context_create(array(
        'http' => array(
                'header'  => "Authorization: Basic " . base64_encode("$username:$password")
        )
));

//Using customized Yahoo Social SDK (The default version does not work)
$yql = new YahooYQLQuery();

$count = 0;
$dupdated = 0;
$dnotfound = 0;
$derrors = 0;
$hupdated = 0;
$hnotfound = 0;
$herrors = 0;
$kupdated = 0;
$knotfound = 0;
$kerrors = 0;
echo "Updating Tickers...\n";

//Select all tickers not updated for at least a day
$query = "SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id WHERE TIMESTAMPDIFF(MINUTE,tc.last_yahoo_date,NOW()) > 1380";
$res = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$count ++;
	echo "Updating ".$row["ticker"]."...";

	//UPDATE DIVIDEN HISTORY
	$response = $yql->execute("select * from yahoo.finance.dividendhistory where startDate = '".date("Y-m-d", strtotime("-1 years"))."' and endDate = '".date("Y-m-d")."' and  symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://datatables.org/alltableswithkeys");	
	if(isset($response->query) && isset($response->query->results)) {
		foreach($response->query->results->quote as $element) {
			if (isset($element->Date) && !is_null($element->Date) && $element->Date!="0000-00-00") {
				$query_div = "INSERT INTO `tickers_yahoo_dividend_history` (ticker_id, qtrDate, dividends) VALUES (";
				$query_div .= "'".$row["id"]."',";
				$query_div .= "'".$element->Date."',";
				$query_div .= (is_null($element->Dividends)?"NULL":$element->Dividends);
				$query_div .= ") ON DUPLICATE KEY UPDATE dividends = ";
				$query_div .= (is_null($element->Dividends)?"NULL":$element->Dividends);
				mysql_query($query_div) or die(mysql_error());
			}
		}
		$dupdated ++;
        } elseif(isset($response->error)) {
                $derrors ++;
        } else {
                $dnotfound ++;
        }

	//UPDATE HISTORICAL DATA
	$q_count = "select count(*) as a from `tickers_yahoo_historical_data` where ticker_id = '".$row["id"]."'";
	$r_count = mysql_query($q_count) or die (mysql_error());
	$r_row = mysql_fetch_assoc($r_count);

	$split_date = date("Ymd",strtotime($row["last_split_date"]));
	$sresponse = $yql->execute("select * from osv.finance.splits where symbol = '".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");

	if($r_row["a"] < 260 || (isset($sresponse->query) && isset($sresponse->query->results) && isset($sresponse->query->results->SplitDate) && $sresponse->query->results->SplitDate > $split_date)) {
		for ($years = -12; $years < 0; $years++) {
			$response = $yql->execute("select * from yahoo.finance.historicaldata where startDate = '".date("Y-m-d", strtotime($years ." years"))."' and endDate = '".date("Y-m-d", strtotime(($years+1) ." years"))."' and  symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://datatables.org/alltableswithkeys");	
			if(isset($response->query) && isset($response->query->results)) {
				foreach($response->query->results->quote as $element) {
					$query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, open, high, low, close, volume, adj_close) VALUES (";
					$query_div .= "'".$row["id"]."',";
					$query_div .= "'".$element->Date."',";
					$query_div .= (is_null($element->Open)?"NULL":$element->Open).",";
					$query_div .= (is_null($element->High)?"NULL":$element->High).",";
					$query_div .= (is_null($element->Low)?"NULL":$element->Low).",";
					$query_div .= (is_null($element->Close)?"NULL":$element->Close).",";
					$query_div .= (is_null($element->Volume)?"NULL":$element->Volume).",";
					$query_div .= (is_null($element->Adj_Close)?"NULL":$element->Adj_Close);
					$query_div .= ") ON DUPLICATE KEY UPDATE ";
					$query_div .= "open = ".(is_null($element->Open)?"NULL":$element->Open).",";
					$query_div .= "high = ".(is_null($element->High)?"NULL":$element->High).",";
					$query_div .= "low = ".(is_null($element->Low)?"NULL":$element->Low).",";
					$query_div .= "close = ".(is_null($element->Close)?"NULL":$element->Close).",";
					$query_div .= "volume = ".(is_null($element->Volume)?"NULL":$element->Volume).",";
					$query_div .= "adj_close = ".(is_null($element->Adj_Close)?"NULL":$element->Adj_Close);
					mysql_query($query_div) or die(mysql_error());
				}
			}
		}
		if (isset($sresponse->query) && isset($sresponse->query->results) && isset($sresponse->query->results->SplitDate) && $sresponse->query->results->SplitDate > $split_date) {
		        $query_up = "UPDATE tickers_control SET last_split_date = '".date("Y-m-d",strtotime($sresponse->query->results->SplitDate))."' WHERE ticker_id = " . $row["id"];
        		mysql_query($query_up) or die(mysql_error());		

			//Need to get latest shares outstandings from yahoo quotes to compare on webservices
			$response = $yql->execute("select * from osv.finance.quotes where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
			$sharesOut = 0;
			if(isset($response->query) && isset($response->query->results)) {
				$sharesOut = $response->query->results->quote->SharesOutstanding / 1000000;
			}
			//report to webservice so backend updates his own data
			$tmp = file_get_contents("http://".SERVERHOST."/webservice/gf_split_parser.php?ticker=".$row["ticker"]."&split_date=".date("Y-m-d",strtotime($sresponse->query->results->SplitDate))."&appkey=DgmNyOv2tUKBG5n6JzUI&shares=".$sharesOut, false, $context);

		}
	} else {
		$response = $yql->execute("select * from yahoo.finance.historicaldata where startDate = '".date("Y-m-d", strtotime("-1 month"))."' and endDate = '".date("Y-m-d")."' and  symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://datatables.org/alltableswithkeys");	
		if(isset($response->query) && isset($response->query->results)) {
			foreach($response->query->results->quote as $element) {
				if (isset($element->Date) && !is_null($element->Date) && $element->Date!="0000-00-00") {
					$query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, open, high, low, close, volume, adj_close) VALUES (";
					$query_div .= "'".$row["id"]."',";
					$query_div .= "'".$element->Date."',";
					$query_div .= (is_null($element->Open)?"NULL":$element->Open).",";
					$query_div .= (is_null($element->High)?"NULL":$element->High).",";
					$query_div .= (is_null($element->Low)?"NULL":$element->Low).",";
					$query_div .= (is_null($element->Close)?"NULL":$element->Close).",";
					$query_div .= (is_null($element->Volume)?"NULL":$element->Volume).",";
					$query_div .= (is_null($element->Adj_Close)?"NULL":$element->Adj_Close);
					$query_div .= ") ON DUPLICATE KEY UPDATE ";
					$query_div .= "open = ".(is_null($element->Open)?"NULL":$element->Open).",";
					$query_div .= "high = ".(is_null($element->High)?"NULL":$element->High).",";
					$query_div .= "low = ".(is_null($element->Low)?"NULL":$element->Low).",";
					$query_div .= "close = ".(is_null($element->Close)?"NULL":$element->Close).",";
					$query_div .= "volume = ".(is_null($element->Volume)?"NULL":$element->Volume).",";
					$query_div .= "adj_close = ".(is_null($element->Adj_Close)?"NULL":$element->Adj_Close);
					mysql_query($query_div) or die(mysql_error());
				}
			}
			$hupdated ++;
	        } elseif(isset($response->error)) {
        	        $herrors ++;
	        } else {
        	        $hnotfound ++;
	        }
	}

        //UPDATE KEYSTATS
        //Try to get yahoo data for the ticker
	$sharesOut = 0;
        $response = $yql->execute("select * from osv.finance.keystats where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
        if(isset($response->query) && isset($response->query->results)) {
                //Check if the symbol exists
                if(isset($response->query->results->stats->MarketCap)) {
			if(is_array($response->query->results->stats->MarketCap)) {
				foreach($response->query->results->stats as $key=>$value) {
					if($key != "symbol" && count($value) == 2) {
						$response->query->results->stats->{$key} = $value[0];
					} 
				}
			}
                        update_raw_data_yahoo_keystats($row["id"], $response->query->results->stats);
                        $kupdated ++;
                } else {
                        $knotfound ++;
                }
        } elseif(isset($response->error)) {
                $kerrors ++;
        } else {
                $kerrors ++;
        }

	// UPDATE DATES
	$query_up = "UPDATE tickers_control SET last_yahoo_date = NOW() WHERE ticker_id = " . $row["id"];
	mysql_query($query_up) or die(mysql_error());
	echo " Done\n";
}

echo $count . " rows processed\n";
echo "Dividend History:\n";
echo "\t".$dupdated." tickers updates\n";
echo "\t".$dnotfound." tickers not found on yahoo\n";
echo "\t".$derrors." errors updating tickers\n";
echo "Historical Data:\n";
echo "\t".$hupdated." tickers updates\n";
echo "\t".$hnotfound." tickers not found on yahoo\n";
echo "\t".$herrors." errors updating tickers\n";
echo "Key Stats:\n";
echo "\t".$kupdated." tickers updates\n";
echo "\t".$knotfound." tickers not found on yahoo\n";
echo "\t".$kerrors." errors updating tickers\n";
echo "Updating key ratios TTM... ";
update_key_ratios_ttm();
echo "done\n";
echo "Updating Ratings TTM... ";
update_ratings_ttm();
echo "done\n";

function toFloat($num) {
    if (is_null($num)) {
        return 'null';
    }

    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

    if (!$sep) {
        return floatval(preg_replace("/[^\-0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^\-0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^\-0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}
?>
