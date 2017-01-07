<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/db.php');
include_once('./../crons/include/raw_data_update_queries.php');
include_once('./../crons/include/update_key_ratios_ttm.php');
include_once('./../crons/include/update_quality_checks.php');
include_once('./../crons/include/update_ratings.php');
include_once('./../crons/include/update_ratings_ttm.php');
include_once('./../crons/include/update_is_old_field.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$areports = AREPORTS;
$qreports = QREPORTS;
$treports = $areports+$qreports;

//Access on dev environment
$username = 'osv';
$password = 'test1234!';
$context = stream_context_create(array(
        'http' => array(
                'header'  => "Authorization: Basic " . base64_encode("$username:$password")
        )
));

if (!isset($_GET["ticker"])) {
	echo "Missing Ticker parameter";
	exit;
}
echo "Updating ticker ".$_GET["ticker"]."... <br>";
$query = "SELECT count(*) as C FROM tickers WHERE ticker = '".$_GET['ticker']."'";
try {
        $res = $db->query($query);
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
}
$counter = $res->fetch(PDO::FETCH_OBJ);
if ($counter->C == 0) {
	echo "Ticker not found in Frontend database";
	exit;
}

$symbols = file_get_contents("http://".SERVERHOST."/webservice/get_ticker_list_frontend_special.php?ticker=".$_GET['ticker'], false, $context);
$result = json_decode($symbols);
$fixdate = $result[0]->insdate;
$fixtype = $result[0]->reporttype;
$fixticker = $result[0]->ticker;


if (!is_null($fixdate) && $fixtype != "Dummy") {
        $query = "SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$fixticker'";
        try {
		        $res = $db->query($query);
		} catch(PDOException $ex) {
		        echo "\nDatabase Error"; //user message
		        die("- Line: ".__LINE__." - ".$ex->getMessage());
		}
        if($res->rowCount() == 0) {
	        echo "Ticker not found in Backend database";
        	exit;
	}
	echo "Downloading new data... <br>";
        $dates = $res->fetch(PDO::FETCH_OBJ);

	$csv = file_get_contents("http://".SERVERHOST."/webservice/createcsv.php?source=frontend&ticker=".$fixticker, false, $context);
	$csvst = fopen('php://memory', 'r+');
	fwrite($csvst, $csv);
	unset($csv);
	fseek($csvst, 0);
	$rawdata = array();
	while ($data = fgetcsv($csvst)) {
                for($i=1; $i<=$treports;$i++) {
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
	try {
	        $db->exec($query);
	} catch(PDOException $ex) {
	        echo "\nDatabase Error"; //user message
	        die("- Line: ".__LINE__." - ".$ex->getMessage());
	}
	fclose($csvst);
}

update_key_ratios_ttm($dates->ticker_id);
update_pio_checks($dates->ticker_id);
update_altman_checks($dates->ticker_id);
update_beneish_checks($dates->ticker_id);

echo "Done <br>";
echo "Removing old Quality Checks (PIO)... ";
$query = "delete a from reports_pio_checks a left join reports_header b on a.report_id = b.id where b.id IS null";
try {
        $db->exec($query);
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
}
echo "done<br>\n";
echo "Removing old Quality Checks (ALTMAN)... ";
$query = "delete a from reports_alt_checks a left join reports_header b on a.report_id = b.id where b.id IS null";
try {
        $db->exec($query);
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
}
echo "done<br>\n";
echo "Removing old Quality Checks (BENEISH)... ";
$query = "delete a from reports_beneish_checks a left join reports_header b on a.report_id = b.id where b.id IS null";
try {
        $db->exec($query);
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
}
echo "done<br>\n";
echo "Updating Ratings... ";
update_ratings();
echo "done<br>\n";
echo "Updating Ratings TTM... ";
update_ratings_ttm();
echo "done<br>\n";
echo "Updating is_old tickers table field... ";
update_is_old_field();
echo "done<br>\n";

exit;

function nullValues(&$item, $key) {
        if(strlen(trim($item)) == 0) {
                $item = 'null';
        } else if($item == "-") {
                $item = 'null';
        }
}
?>
