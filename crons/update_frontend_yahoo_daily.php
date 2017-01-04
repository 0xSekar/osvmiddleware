<?php
//Get yahoo estimates using YQL.

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/db.php');
include_once('./include/raw_data_update_yahoo_keystats.php');
require_once("../include/yahoo/common.inc.php");
include_once('./include/update_key_ratios_ttm.php');
include_once('./include/update_ratings_ttm.php');

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
$supdated = 0;
$snotfound = 0;
$supdated2 = 0;
$snotfound2 = 0;
echo "Updating Tickers...\n";

//Select all tickers not updated for at least a day
try {
	$res = $db->query("SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id WHERE TIMESTAMPDIFF(MINUTE,tc.last_yahoo_date,NOW()) > 1380");
} catch(PDOException $ex) {
    echo "\nDatabase Error"; //user message
    die("Line: ".__LINE__." - ".$ex->getMessage());
}
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {	
	$count ++;
	echo "Updating ".$row["ticker"]."...";

	//UPDATE DIVIDEN HISTORY
	$response = $yql->execute("select * from yahoo.finance.dividendhistory where startDate = '".date("Y-m-d", strtotime("-1 years"))."' and endDate = '".date("Y-m-d")."' and  symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://datatables.org/alltableswithkeys");	
	if(isset($response->query) && isset($response->query->results)) {
		foreach($response->query->results->quote as $element) {
			if (isset($element->Date) && !is_null($element->Date) && $element->Date!="0000-00-00") {

				$query_div = "INSERT INTO `tickers_yahoo_dividend_history` (ticker_id, qtrDate, dividends) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE dividends = ?";
				$params = array();
				$params[] = $row["id"];
				$params[] = $element->Date;
				$params[] = (is_null($element->Dividends)?NULL:$element->Dividends);
				$params[] = (is_null($element->Dividends)?NULL:$element->Dividends);
				try {
					$res1 = $db->prepare($query_div);
                	$res1->execute($params);
				} catch(PDOException $ex) {
					    echo "\nDatabase Error"; //user message
					    die("Line: ".__LINE__." - ".$ex->getMessage());
				}
			}
		}
		$dupdated ++;
        } elseif(isset($response->error)) {
                $derrors ++;
        } else {
                $dnotfound ++;
        }

	//UPDATE HISTORICAL DATA
	try {
		$r_count = $db->query("select count(*) as a from `tickers_yahoo_historical_data` where ticker_id = '".$row["id"]."'"); 
		//$r_row = mysql_fetch_assoc($r_count);
		$r_row = $r_count->fetch(PDO::FETCH_ASSOC);
	} catch(PDOException $ex) {
	    echo "\nDatabase Error"; //user message
	    die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	

	$split_date = date("Ymd",strtotime($row["last_split_date"]));
	$sresponse = $yql->execute("select * from osv.finance.splits where symbol = '".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");

	if($r_row["a"] < 260 || (isset($sresponse->query) && isset($sresponse->query->results) && isset($sresponse->query->results->SplitDate) && $sresponse->query->results->SplitDate > $split_date)) {
		for ($years = -15; $years < 0; $years++) {
			$response = $yql->execute("select * from yahoo.finance.historicaldata where startDate = '".date("Y-m-d", strtotime($years ." years"))."' and endDate = '".date("Y-m-d", strtotime(($years+1) ." years"))."' and  symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://datatables.org/alltableswithkeys");	
			if(isset($response->query) && isset($response->query->results)) {
				foreach($response->query->results->quote as $element) {

					$query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, open, high, low, close, volume, adj_close) VALUES (?,?,?,?,?,?,?,?)  ON DUPLICATE KEY UPDATE open = ?, high =  ?, low = ?, close = ?, volume = ?, adj_close = ?";
					$params = array();
					$params[] = $row["id"];
					$params[] = $element->Date;
					$params[] = (is_null($element->Open)?NULL:$element->Open);
					$params[] = (is_null($element->High)?NULL:$element->High);
					$params[] = (is_null($element->Low)?NULL:$element->Low);
					$params[] = (is_null($element->Close)?NULL:$element->Close);
					$params[] = (is_null($element->Volume)?NULL:$element->Volume);
					$params[] = (is_null($element->Adj_Close)?NULL:$element->Adj_Close);

					$params[] = (is_null($element->Open)?NULL:$element->Open);
					$params[] = (is_null($element->High)?NULL:$element->High);
					$params[] = (is_null($element->Low)?NULL:$element->Low);
					$params[] = (is_null($element->Close)?NULL:$element->Close);
					$params[] = (is_null($element->Volume)?NULL:$element->Volume);
					$params[] = (is_null($element->Adj_Close)?NULL:$element->Adj_Close);
					try {
						$res1 = $db->prepare($query_div);
                		$res1->execute($params);
					} catch(PDOException $ex) {
					    echo "\nDatabase Error"; //user message
					    die("Line: ".__LINE__." - ".$ex->getMessage());
					}
				}
			}
		}
		if (isset($sresponse->query) && isset($sresponse->query->results) && isset($sresponse->query->results->SplitDate) && $sresponse->query->results->SplitDate > $split_date) {
        		try {
        			$res1 = $db->prepare("UPDATE tickers_control SET last_split_date = ? WHERE ticker_id = ?");
					$res1->execute(array((date("Y-m-d",strtotime($sresponse->query->results->SplitDate))), $row["id"])); 
					} catch(PDOException $ex) {
					    echo "\nDatabase Error"; //user message
					    die("Line: ".__LINE__." - ".$ex->getMessage());
					}

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

					$query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, open, high, low, close, volume, adj_close) VALUES (?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE open = ?, high =  ?, low = ?, close = ?, volume = ?, adj_close = ?";
					$params = array();
					$params[] = $row["id"];
					$params[] = $element->Date;
					$params[] = (is_null($element->Open)?NULL:$element->Open);
					$params[] = (is_null($element->High)?NULL:$element->High);
					$params[] = (is_null($element->Low)?NULL:$element->Low);
					$params[] = (is_null($element->Close)?NULL:$element->Close);
					$params[] = (is_null($element->Volume)?NULL:$element->Volume);
					$params[] = (is_null($element->Adj_Close)?NULL:$element->Adj_Close);

					$params[] = (is_null($element->Open)?NULL:$element->Open);
					$params[] = (is_null($element->High)?NULL:$element->High);
					$params[] = (is_null($element->Low)?NULL:$element->Low);
					$params[] = (is_null($element->Close)?NULL:$element->Close);
					$params[] = (is_null($element->Volume)?NULL:$element->Volume);
					$params[] = (is_null($element->Adj_Close)?NULL:$element->Adj_Close);
					try {
						$res1 = $db->prepare($query_div);
                		$res1->execute($params);
					} catch(PDOException $ex) {
						    echo "\nDatabase Error"; //user message
						    die("Line: ".__LINE__." - ".$ex->getMessage());
					}
				}
			}
			$hupdated ++;
        } elseif(isset($response->error)) {
    	        $herrors ++;
        } else {
    	        $hnotfound ++;
        }
	}
        //UPDATE KEYSTATS, SECTOR, INDUSTRY AND DESCRIPTION
        //Try to get yahoo data for the ticker
	$sharesOut = 0;
        $response = $yql->execute("select * from osv.finance.keystats_new where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
        if(isset($response->query) && isset($response->query->results)) {
                //Check if the symbol exists
		//Keystats
                if(isset($response->query->results->result->marketCap)) {
                        update_raw_data_yahoo_keystats($row["id"], $response->query->results->result);
                        $kupdated ++;
                } else {
                        $knotfound ++;
                }

		//Sector and Industry
		if(isset($response->query->results->result->assetProfile->sector) && !empty($response->query->results->result->assetProfile->sector)) {
			$supdated ++;
                        try {
							$res1 = $db->prepare("UPDATE `tickers` SET industry = ?, sector = ? WHERE id = ?");
							$res1->execute(array((is_null($response->query->results->result->assetProfile->industry)?'':$response->query->results->result->assetProfile->industry), (is_null($response->query->results->result->assetProfile->sector)?'':$response->query->results->result->assetProfile->sector), $row["id"]));					
						} catch(PDOException $ex) {
							echo "\nDatabase Error"; //user message
					    	die("Line: ".__LINE__." - ".$ex->getMessage());
						}

		} else {
			$snotfound ++;
		}

		//Description
		if(isset($response->query->results->result->assetProfile->longBusinessSummary)) {
			$supdated2 ++;
                        try {
							$res1 = $db->prepare("UPDATE `tickers` SET description = ? WHERE id = ?");
							$res1->execute(array((is_null($response->query->results->result->assetProfile->longBusinessSummary)?'':$response->query->results->result->assetProfile->longBusinessSummary), $row["id"]));					
						} catch(PDOException $ex) {
							echo "\nDatabase Error"; //user message
					    	die("Line: ".__LINE__." - ".$ex->getMessage());
						}
		} else {
			$snotfound2 ++;
		}

        } elseif(isset($response->error)) {
                $kerrors ++;
        } else {
                $kerrors ++;
        }

	//Update key ratios ttm
	update_key_ratios_ttm($row["id"]);

	// UPDATE DATES
	$query_up = "UPDATE tickers_control SET last_yahoo_date = NOW() WHERE ticker_id = ? ";
	$params = array();
	$params[] = $row["id"];
	try {
		$res1 = $db->prepare($query_up);
        $res1->execute($params);
	} catch(PDOException $ex) {
		    echo "\nDatabase Error"; //user message
		    die("Line: ".__LINE__." - ".$ex->getMessage());
	}
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
echo "Sector & Industry:\n";
echo "\t".$supdated." tickers updates\n";
echo "\t".$snotfound." tickers not found on yahoo\n";
echo "\t".$kerrors." errors updating tickers\n";
echo "Description:\n";
echo "\t".$supdated2." tickers updates\n";
echo "\t".$snotfound2." tickers not found on yahoo\n";
echo "\t".$kerrors." errors updating tickers\n";
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
