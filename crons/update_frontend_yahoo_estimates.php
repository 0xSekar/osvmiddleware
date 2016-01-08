<?php
//Get yahoo estimates using YQL.
//AFTER FIRST RUN, DATES FOR HISTORY NEEDS TO BE MODIFIED TO TAKE ONLY LAST FEW RECORDS:
//YEAR FOR DIVIDEND
//MONTH FOR HISTORY

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/database.php');
include_once('./include/raw_data_update_yahoo_estimates.php');
require_once("../include/yahoo/common.inc.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

//Using customized Yahoo Social SDK (The default version does not work)
$yql = new YahooYQLQuery();

$count2 = 0;
$eupdated = 0;
$ecurrent = 0;
$enotfound = 0;
$eerrors = 0;
echo "Updating Tickers...\n";
//Analyst Estimates needs more frequent updates
$query = "SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id";
$res = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$count2++;
	echo "Updating ".$row["ticker"]." Estimates...";
	//UPDATE ESTIMATES
	//Try to get yahoo data for the ticker
	$response = $yql->execute("select * from osv.finance.analystestimate where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");	
	if(isset($response->query) && isset($response->query->results)) {
		//Check if the symbol exists
		if(isset($response->query->results->results->EarningsEst)) {
			$dates = new stdClass();;
			//Get dates from fetched ticker
			foreach($response->query->results->results->EarningsEst->AvgEstimate as $property => $value) {
				//Get dates
				if(substr($property, 0, 10) == "CurrentQtr") {
					$dates->currQtrDate = date("Y-m-d", strtotime("1 ".substr($property,-5)));
					$dates->currQtrDateText = substr($property,-5);
				} elseif (substr($property, 0, 7) == "NextQtr") {
					$dates->nextQtrDate = date("Y-m-d", strtotime("1 ".substr($property,-5)));
					$dates->nextQtrDateText = substr($property,-5);
				} elseif (substr($property, 0, 11) == "CurrentYear") {
					$dates->currYearDate = date("Y-m-d", strtotime("1 ".substr($property,-5)));
					$dates->currYearDateText = substr($property,-5);
				} elseif (substr($property, 0, 8) == "NextYear") {
					$dates->nextYearDate = date("Y-m-d", strtotime("1 ".substr($property,-5)));
					$dates->nextYearDateText = substr($property,-5);
				}
			}
			foreach($response->query->results->results->EarningsHistory->EPSEst as $property => $value) {
                                $dates->hDate[] = date("Y-m-d", strtotime("1 ".$property));
                                $dates->hDateText[] = $property;
			}
			update_raw_data_yahoo_estimates($row["id"], $dates, $response->query->results->results);
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
