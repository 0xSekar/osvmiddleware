<?php
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
$today = date('Y/m/d');

$AnnLot = 15;
$QtrLot = 20;

$count = array(0,0,0);

$list = listOfTickers();
$lot = count($list);

foreach($list as $i => $ticker){
    echo "Downloading data for ". $ticker."... ";
    $chek = ckeckNDown($ticker, $AnnLot, $QtrLot, FALSE, TRUE);
    $count = statusCounter($ticker, $chek, $count);    
    if($chek==1){
        try {
            $res = $db->prepare("UPDATE tickers_split_parser SET updated_date = '".$today."' WHERE (ticker = ? AND  updated_date is null) ");            
            $res->execute(array(strval($ticker)));
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    }else{
        try {
            $res = $db->prepare("UPDATE tickers_split_parser SET tested_for_today = '".$today."' WHERE (ticker = ? AND  tested_for_today is null) ");            
            $res->execute(array(strval($ticker)));
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    };
}

if($count[0]>0){
    ratings();
}

resumeEcho($count);

// --------------------------------- Functions --------------------------------- 

function listOfTickers(){
    $db = Database::GetInstance(); 
    $tickerstoupdate = array();
    $today = date('Y/m/d');
    try {
        $res = $db->prepare("SELECT a.ticker FROM tickers_split_parser AS a LEFT JOIN osv_blacklist AS b ON a.ticker = b.ticker WHERE a.updated_date is null AND b.ticker is null AND (DATEDIFF('".$today."',a.tested_for_today) > 2 OR a.tested_for_today is null)");        
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $tickers = $res->fetchAll(PDO::FETCH_COLUMN);
    $tickers = array_unique($tickers);

    foreach ($tickers as $key => $value) {
        $gurufile = downloadguru($value);
        $arrayguru = array_map('str_getcsv', preg_split('/\r*\n+|\r+/', $gurufile));

        if(count($arrayguru)>20){
            foreach($arrayguru as $name => $val) {
                if($val[0] == "EPS (Basic)"){
                    $i = count($val)-1;
                    $EPS = $val[$i];
                    continue 1;
                } 
            }            
            try {
                $res = $db->prepare("SELECT ticker FROM tickers_split_parser WHERE ticker = '".$value."' AND  old_eps != '".$EPS."' ");            
                $res->execute();
            } catch(PDOException $ex) {
                echo " Database Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }
            $res = $res->fetchAll(PDO::FETCH_COLUMN);
            if(isset($res[0])){
                $tickerstoupdate[] = $res[0];
                
            }else{
                try {
                    $res = $db->prepare("UPDATE tickers_split_parser SET tested_for_today = '".$today."' WHERE (ticker = '".$value."' AND  tested_for_today is null) ");            
                    $res->execute();
                } catch(PDOException $ex) {
                    echo " Database Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
            }
        }else{
            //guru bad
            echo " Guru error ";
        }       
    }
    return $tickerstoupdate;
}

?>
