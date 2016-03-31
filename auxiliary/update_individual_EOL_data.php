<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../db/database.php');
include_once('./../crons/include/raw_data_update_queries.php');
include_once('./../crons/include/update_key_ratios_ttm.php');
/*include_once('./include/update_quality_checks.php');
include_once('./include/update_ratings.php');
include_once('./include/update_ratings_ttm.php');*/

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

if (!isset($_GET["ticker"])) {
	echo "Missing Ticker parameter";
	exit;
}
echo "Updating ticker ".$_GET["ticker"]."... <br>";
$query = "SELECT count(*) as C FROM tickers WHERE ticker = '".$_GET['ticker']."'";
$res = mysql_query($query) or die(mysql_error());
$counter = mysql_fetch_object($res);
if ($counter->C == 0) {
	echo "Ticker not found in Frontend database";
	exit;
}

$symbols = file_get_contents("http://www.oldschoolvalue.com/webservice/get_ticker_list_frontend_special.php?ticker=".$_GET['ticker']);
$result = json_decode($symbols);
$fixdate = $result[0]->insdate;
$fixtype = $result[0]->reporttype;
$fixticker = $result[0]->ticker;


if (!is_null($fixdate) && $fixtype != "Dummy") {
        $query = "SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$fixticker'";
        $res = mysql_query($query) or die(mysql_error());
        if(mysql_num_rows($res) == 0) {
	        echo "Ticker not found in Backend database";
        	exit;
	}
	echo "Downloading new data... <br>";
        $dates = mysql_fetch_object($res);

	$csv = file_get_contents("http://job.oldschoolvalue.com/webservice/createcsv.php?ticker=".$fixticker);
	$csvst = fopen('php://memory', 'r+');
	fwrite($csvst, $csv);
	unset($csv);
	fseek($csvst, 0);
	$rawdata = array();
	while ($data = fgetcsv($csvst)) {
                for($i=1; $i<27;$i++) {
                        if(!isset($data[$i])) {
                                $data[$i] = "null";
                        }
                }
		$rawdata[$data[0]] = $data;
	}
	array_walk_recursive($rawdata, 'nullValues');
	echo "Updating frontend database... ";
	//Update Raw data
	if(isset($rawdata["AccountsPayableTurnoverDaysFY"])) {
		update_raw_data_tickers($dates, $rawdata);
	}
		
	//Finally update local report date
	$query = "UPDATE tickers_control SET last_eol_date = '$fixdate' WHERE ticker_id = $dates->ticker_id";
	mysql_query($query) or die (mysql_error());
	fclose($csvst);
}

update_key_ratios_ttm($dates->ticker_id);
/*update_quality_checks();
update_ratings();
update_ratings_ttm();*/

echo "Done <br>";
exit;

function nullValues(&$item, $key) {
        if(strlen(trim($item)) == 0) {
                $item = 'null';
        } else if($item == "-") {
                $item = 'null';
        }
}
?>
