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
include_once('../crons/include/eol_xml_parser.php');
include_once('../crons/include/guru.php'); 
include_once('../crons/include/eol.php'); 
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

//$ticker = $_REQUEST['ticker'];
$ticker = 'A';

if($ticker!=NULL){
    try {
            $res = $db->prepare("SELECT ticker FROM osv_blacklist WHERE ticker = '".$ticker."' ");            
            $res->execute();
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }        
    $res = $res->fetchAll(PDO::FETCH_COLUMN);
    if(!isset($res[0])){
       echo "Downloading data for ". $ticker."... ";
        $chek = ckeckNDown($ticker, $AnnLot, $QtrLot, FALSE, TRUE);
        $count = statusCounter($ticker, $chek, $count);
        ratings();
    }
}

?>
