<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/db.php');
include_once('./../crons/include/raw_data_update_queries.php');
include_once('./../crons/include/update_key_ratios_ttm.php');
include_once('./../crons/include/update_quality_checks.php');
include_once('./../crons/include/update_ratings.php');
include_once('./../crons/include/update_ratings_ttm.php');
include_once('./../crons/include/update_is_old_field.php');
include_once('./../crons/update_frontend_yahoo_daily_include.php');
include_once('./../crons/include/raw_data_update_yahoo_keystats.php');
require_once("../include/yahoo/common.inc.php");
include_once('./../crons/include/update_eod_valuation.php');
include_once('./../crons/update_frontend_EOL_GF_data_include.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$db = Database::GetInstance(); 

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

if (!isset($_GET["ticker"])) {
        echo "Missing Ticker parameter";
        exit;
}

update_frontend_EOL_GF_data($_GET["ticker"]);

function nullValues(&$item, $key) {
	if(strlen(trim($item)) == 0) {
		$item = 'null';
	} else if($item == "-") {
		$item = 'null';
	}
}
?>
