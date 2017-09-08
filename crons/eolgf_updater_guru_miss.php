<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/db.php');
include_once('./include/raw_data_update_queries.php');
include_once('./include/update_key_ratios_ttm.php');
include_once('./include/update_quality_checks.php');
include_once('./include/update_ratings.php');
include_once('./include/update_ratings_ttm.php');
include_once('./include/update_is_old_field.php');
include_once('./update_frontend_yahoo_daily_include.php');
include_once('./include/raw_data_update_yahoo_keystats.php');
require_once("../include/yahoo/common.inc.php");
include_once('./include/update_eod_valuation.php');
include_once('./update_frontend_EOL_GF_data_include.php');

// Tools & functions
include_once('./include/guru.php'); 
include_once('./include/eol.php'); 
include_once('./include/eol_xml_parser.php');
include_once('./include/eolgf_updater.php');

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

// Should never be cached - do not remove this
header("Content-type: text/xml; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$db = Database::GetInstance(); 

$AnnLot = AREPORTS;
$QtrLot = QREPORTS;

$count = array(0,0,0);
$newlist = array();

$list = listOfTickers();
$lot = count($list);
if($lot>0){
    $newlist = backOff('C', $list, $newlist, 1, 7, TRUE);
    $newlist = backOff('C', $list, $newlist, 2, 17, TRUE, 7);
    $newlist = backOff('C', $list, $newlist, 7, 50, TRUE, 17);
    $newlist = backOff('C', $list, $newlist, 30, 50, FALSE);
    $newlist = array_unique($newlist);
}
$lot = count($newlist);

if($lot>0){
    foreach($newlist as $i => $ticker){
        echo "Downloading data for ". $ticker."... ";
        $chek = ckeckNDown($ticker, $AnnLot, $QtrLot, FALSE, TRUE, TRUE);
        $count = statusCounter($ticker, $chek, $count);
    }
}else{
    echo "No tickers to Process...<br>\n";
}

if($count[0]>0){
    ratings();
}

resumeEcho($count);

// --------------------------------- Functions --------------------------------- 

function listOfTickers(){
    $db = Database::GetInstance(); 
    $today = date('Y/m/d');

    try {
        $res = $db->prepare("SELECT a.ticker, a.insdate, a.missing_gf_period FROM tickers_proedgard_updates AS a LEFT JOIN osv_blacklist AS b ON a.ticker = b.ticker WHERE b.ticker is null AND a.missing_gf_period is not NULL ORDER BY a.ticker");        
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $row = $res->fetchAll(PDO::FETCH_ASSOC);
    //$row = array_unique($row);
    if(count($row)>0){
        return $row;
    }else{
        return NULL;
    }
}

?>
