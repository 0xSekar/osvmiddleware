<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/db.php');
include_once('./include/update_eod_valuation.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly
$db = Database::getInstance();

$count2 = 0;
echo "Updating Tickers...\n";
try {
	$res = $db->query("SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id WHERE is_old = false");
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die("Line: ".__LINE__." - ".$ex->getMessage());
}
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
	$count2++;
	echo "Updating ".$row["ticker"]." valuation...";

	update_eod_valuation($row["id"]);

	echo " Done\n";
}

echo $count2 . " rows processed\n";
?>
