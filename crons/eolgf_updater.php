<?php
// Database Connection
include_once('../config.php');
include_once('../db/db.php');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
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

$list = listOfTickers();
$lot = count($list);

if(! is_null($list)){
    echo "Starting main Process...<br>\n";
    foreach($list as $i => $ticker){
        echo "Downloading data for ". $ticker."... ";
        $chek = ckeckNDown($ticker, $AnnLot, $QtrLot);
        $count = statusCounter($ticker, $chek, $count);
    }
}else{
    echo "No tickers to Process...<br>\n";
}

$list = listOfTickersOTC();
$lot = count($list);

if(! is_null($list)){
    echo "\n<br>Starting OTC Process...<br>\n";
    foreach($list as $i => $ticker){
        echo "Downloading data for ". $ticker."... ";
        $chek = ckeckNDown($ticker, $AnnLot, $QtrLot, TRUE);
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
        $res = $db->prepare("SELECT a.ticker FROM tickers_proedgard_updates AS a LEFT JOIN osv_blacklist AS b ON a.ticker = b.ticker WHERE (a.downloaded is null AND b.ticker is null 
            AND 
            (a.subject LIKE '%filed a 20-F %' OR a.subject LIKE '%filed a 20-F/A %' OR a.subject LIKE '%filed a 10-Q %' OR a.subject LIKE '%filed a 10-Q/A %' OR a.subject LIKE '%filed a 10-K %' OR a.subject LIKE '%filed a 10-K/A %') 
            AND (
            (DATEDIFF('".$today."',a.insdate) > 90 AND (a.tested_for_today is null OR (a.tested_for_today is not null AND DATEDIFF('".$today."', a.tested_for_today)>6))) 
            OR 
            ( DATEDIFF('".$today."',a.insdate) <= 90 AND (a.tested_for_today is null OR (a.tested_for_today is not null AND DATEDIFF('".$today."', a.tested_for_today)>1 )))   
            ) 
            AND a.otc != 'Y')");
        
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $row = $res->fetchAll(PDO::FETCH_COLUMN);
    $row = array_unique($row);
    if(count($row)>0){
        return $row;
    }else{
        return NULL;
    }
}

function listOfTickersOTC(){
    $db = Database::GetInstance(); 
    $today = date('Y/m/d');
    try {
        $res = $db->prepare("SELECT a.ticker FROM tickers_proedgard_updates AS a LEFT JOIN osv_blacklist AS b ON a.ticker = b.ticker WHERE (a.downloaded is null AND b.ticker is null
            AND 
            (a.subject LIKE '%filed a 20-F %' OR a.subject LIKE '%filed a 20-F/A %' OR a.subject LIKE '%filed a 10-Q %' OR a.subject LIKE '%filed a 10-Q/A %' OR a.subject LIKE '%filed a 10-K %' OR a.subject LIKE '%filed a 10-K/A %') 
            AND (
            (DATEDIFF('".$today."',a.insdate) > 90 AND (a.tested_for_today is null OR (a.tested_for_today is not null AND DATEDIFF('".$today."', a.tested_for_today)>6))) 
            OR 
            ( DATEDIFF('".$today."',a.insdate) <= 90 AND (a.tested_for_today is null OR (a.tested_for_today is not null AND DATEDIFF('".$today."', a.tested_for_today)>1 )))   
            ) 
            AND a.otc = 'Y') ");        
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $row = $res->fetchAll(PDO::FETCH_COLUMN);
    $row = array_unique($row);
    if(count($row)>0){
        return $row;
    }else{
        return NULL;
    }
}

?>
