<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/db.php');
include_once('../crons/include/raw_data_update_queries.php');
include_once('../crons/include/update_key_ratios_ttm.php');
include_once('../crons/include/update_quality_checks.php');
include_once('../crons/include/update_ratings.php');
include_once('../crons/include/update_ratings_ttm.php');
include_once('../crons/include/update_is_old_field.php');
include_once('../crons/update_frontend_yahoo_daily_include.php');
include_once('../crons/include/raw_data_update_yahoo_keystats.php');
require_once("../include/yahoo/common.inc.php");
include_once('../crons/include/update_eod_valuation.php');
include_once('../crons/update_frontend_EOL_GF_data_include.php');

// Tools & functions
include_once('../crons/include/guru.php'); 
include_once('../crons/include/eol.php'); 
include_once('../crons/include/eol_xml_parser.php');
include_once('../crons/include/eolgf_updater.php');

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

// Should never be cached - do not remove this
header("Content-type: text/xml; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$db = Database::GetInstance(); 

$AnnLot = 15;
$QtrLot = 20;

$count = array(0,0,0);

$list = listOfTickers();
$lot = count($list);

foreach($list as $i => $ticker){
    echo "Downloading data for ". $ticker."... ";
    $chek = ckeckNDown($ticker, $AnnLot, $QtrLot, FALSE, TRUE);
    $count = statusCounter($ticker, $chek, $count);
}

resumeEcho($count);

if($lot>0){
    ratings();
}

// --------------------------------- Functions --------------------------------- 

function listOfTickers(){
    $db = Database::GetInstance(); 
    $today = date('Y/m/d');
    try {
        $res = $db->prepare("SELECT a.ticker FROM tickers AS a LEFT JOIN osv_blacklist AS b ON a.ticker = b.ticker WHERE a.is_old = FALSE AND b.ticker is null");
        
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $row = $res->fetchAll(PDO::FETCH_COLUMN);
    $row = array_unique($row);
    return $row;
}

?>