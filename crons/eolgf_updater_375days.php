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

$list = listOfTickers();
$lot = count($list);

if(! is_null($list)){
    foreach($list as $i => $ticker){
        echo "Downloading data for ". $ticker."... ";
        $chek = ckeckNDown($ticker, $AnnLot, $QtrLot, TRUE, FALSE);
        $count = statusCounter($ticker, $chek, $count);
    }
}

if($count[0]>0){
    ratings();
}

resumeEcho($count);

// --------------------------------- Functions --------------------------------- 

function listOfTickers(){
    $db = Database::GetInstance(); 
    $today = date('Y/m/d');
    $tickers = array();
    try {
        $res = $db->prepare("SELECT ticker_id FROM tickers_control WHERE (DATEDIFF('".$today."',last_eol_date) > 375)");        
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $ids = $res->fetchAll(PDO::FETCH_COLUMN);
    foreach ($ids as $key => $value) {
        try {
            $res = $db->prepare("SELECT ticker FROM tickers WHERE id = '".$value."' AND exchange = 'OTC'");            
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_COLUMN);
        if(isset($res[0])){
            $tickers[] = $res[0];
        }        
    }
    if(count($tickers)>0){    
        foreach ($tickers as $key => $value) {
            try {
                    $res = $db->prepare("SELECT ticker FROM osv_blacklist WHERE ticker = ?");            
                    $res->execute(array($value));
                } catch(PDOException $ex) {
                    echo " Database Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }        
            $res = $res->fetchAll(PDO::FETCH_COLUMN);
            if(!isset($res[0])){
                try {
                    $res = $db->prepare("SELECT ticker FROM tickers_proedgard_updates WHERE ticker = ? AND ((DATEDIFF('".$today."', tested_for_today)>7) OR tested_for_today is null) AND downloaded is null" );           
                    $res->execute(array($value));
                } catch(PDOException $ex) {
                    echo " Database Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
                $res = $res->fetchAll(PDO::FETCH_COLUMN);
                if(isset($res[0])){
                    $tickerstoupdate[] = $res[0];
                }else{
                    try {
                        $res = $db->prepare("SELECT ticker FROM tickers_proedgard_updates WHERE ticker = ?" );            
                        $res->execute(array($value));
                    } catch(PDOException $ex) {
                        echo " Database Error"; //user message
                        die("Line: ".__LINE__." - ".$ex->getMessage());
                    }
                    $res = $res->fetchAll(PDO::FETCH_COLUMN);
                    if(!isset($res[0])){
                        $tickerstoupdate[] = $value;
                    }
                }
            }
        }
        return $tickerstoupdate;
    }else{
        echo " There is no tickers to process ";
        return NULL;
    }    
}

?>
