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
    $newlist = backOff('B', $list, $newlist, 3, 30, TRUE, 0, 100);
    $newlist = backOff('B', $list, $newlist, 7, 70, TRUE, 30, 100);
    $newlist = backOff('B', $list, $newlist, 30, 70, FALSE, 0, 100);
    $newlist = array_unique($newlist);
}

$lot = count($newlist);

if($lot>0){
    foreach($newlist as $i => $ticker){
        echo "Downloading data for ". $ticker."... ";
        $chek = ckeckNDown($ticker, $AnnLot, $QtrLot, FALSE, FALSE);
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
    $tickerstoupdate = array();
    try {
        $res = $db->prepare("SELECT a.ticker_id, last_eol_date, b.ticker, b.country, e.MaxDate, d.FormType FROM tickers_control a LEFT JOIN tickers b ON a.ticker_id=b.id LEFT JOIN osv_blacklist c ON b.ticker = c.ticker INNER JOIN (SELECT id, MAX(report_date) MaxDate, ticker_id FROM reports_header GROUP BY ticker_id) e ON a.ticker_id = e.ticker_id INNER JOIN reports_financialheader d ON d.report_id=e.id WHERE (DATEDIFF(now(),last_eol_date) > 100) AND b.secondary = FALSE AND c.ticker IS NULL AND (b.country = 'UNITED STATES OF AMERICA' OR FormType = '10-K' OR FormType = '10-Q' OR FormType = '8-K')");
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $ids = $res->fetchAll(PDO::FETCH_ASSOC);
    if(count($ids) > 0) {
        foreach ($ids as $key => $value) {
            $tickerstoupdate[] = array($value['ticker'], $value['last_eol_date']);
        }
        return $tickerstoupdate;
    }else{
        return NULL;
    }
}

?>
