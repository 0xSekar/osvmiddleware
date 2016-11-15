<?php
//Get yahoo estimates using YQL.
//AFTER FIRST RUN, DATES FOR HISTORY NEEDS TO BE MODIFIED TO TAKE ONLY LAST FEW RECORDS:
//YEAR FOR DIVIDEND
//MONTH FOR HISTORY

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/database.php');
include_once('../db/db.php');
include_once('./include/raw_data_update_yahoo_estimates.php');
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
$ecurrent = 0;
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
	echo "Updating ".$row["ticker"]." Estimates...";
	//UPDATE ESTIMATES
	//Try to get yahoo data for the ticker
	$response = $yql->execute("select * from osv.finance.analystestimate_new where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");	
	if(isset($response->query) && isset($response->query->results)) {
		//Check if the symbol exists
		if(isset($response->query->results->result->earningsTrend)) {
			$rawdata = new stdClass();
			$rawdata->earningsHistory = new stdClass();
			$rawdata->currQtr = new stdClass();
			$rawdata->nextQtr = new stdClass();
			$rawdata->currYear = new stdClass();
			$rawdata->nextYear = new stdClass();
			$rawdata->plus5Year = new stdClass();
			$rawdata->minus5Year = new stdClass();
			$rawdata->industryPegRatio = $response->query->results->result->industryTrend->pegRatio;
			$rawdata->sectorPegRatio = $response->query->results->result->sectorTrend->pegRatio;
			//Get dates from fetched ticker
			foreach($response->query->results->result->earningsHistory->history as $value) {
				if($value->period == "-4q") {
					$rawdata->earningsHistory->minus4q = $value;
				} elseif($value->period == "-3q") {
					$rawdata->earningsHistory->minus3q = $value;
				} elseif($value->period == "-2q") {
					$rawdata->earningsHistory->minus2q = $value;
				} elseif($value->period == "-1q") {
					$rawdata->earningsHistory->minus1q = $value;
				}
			}
			foreach($response->query->results->result->earningsTrend->trend as $value) {
				if($value->period == "0q") {
					$rawdata->currQtr = $value;
				} elseif ($value->period == "+1q") {
					$rawdata->nextQtr = $value;
				} elseif ($value->period == "0y") {
					$rawdata->currYear = $value;
				} elseif ($value->period == "+1y") {
					$rawdata->nextYear = $value;
				} elseif ($value->period == "+5y") {
					$rawdata->plus5Year = $value;
				} elseif ($value->period == "-5y") {
					$rawdata->minus5Year = $value;
				}
			}
			foreach($response->query->results->result->industryTrend->estimates as $value) {
				if($value->period == "0q") {
					$rawdata->currQtr->industryTrend = $value;
				} elseif($value->period == "+1q") {
					$rawdata->nextQtr->industryTrend = $value;
				} elseif($value->period == "0y") {
					$rawdata->currYear->industryTrend = $value;
				} elseif($value->period == "+1y") {
					$rawdata->nextYear->industryTrend = $value;
				} elseif($value->period == "+5y") {
					$rawdata->plus5Year->industryTrend = $value;
				} elseif($value->period == "-5y") {
					$rawdata->minus5Year->industryTrend = $value;
				}
			}
			update_raw_data_yahoo_estimates($row["id"], $rawdata);
			$eupdated ++;
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
echo "Estimates:\n";
echo "\t".$eupdated." tickers updates\n";
echo "\t".$ecurrent." tickers don't need update\n";
echo "\t".$enotfound." tickers not found on yahoo\n";
echo "\t".$eerrors." errors updating tickers\n";

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
