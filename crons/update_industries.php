<?php
// Database Connection
include_once('../config.php');
include_once('../db/db.php');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

// Should never be cached - do not remove this
header("Content-type: text/xml; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$db = Database::GetInstance(); 

$listofnotmatch = array();

$list = listOfTickers();
$lot = count($list);

if($lot>0){
    echo "Starting main Process...<br>\n";
    foreach($list as $i => $ticker){
        if(!is_null($ticker["industry_coc"])){
            try {
                $res = $db->prepare("UPDATE tickers SET industry = '".$ticker["industry_coc"]."' WHERE ticker = ?");
                $res->execute(array(strval($ticker["ticker"]))); 
            } catch(PDOException $ex) {
                echo " Database Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }
        }else{
            if(!is_null($ticker["industry"]) AND $ticker["industry"]!=""){
                $listofnotmatch[] = $ticker["industry"];
            }
        }
    }
    echo "Tickers industry correctly updated...<br>\n";
}else{
    echo "No tickers to Process...<br>\n";
}

if(count($listofnotmatch)>0){
    $listofnotmatch = array_unique($listofnotmatch);
    echo "The following ".count($listofnotmatch)." industries has no coincidence on matching table: <br>\n";
    foreach($listofnotmatch as $i => $ticker){
        echo $ticker." <br>\n";
    }
}

// --------------------------------- Functions --------------------------------- 

function listOfTickers(){
    $db = Database::GetInstance(); 
    try {
        $res = $db->prepare("SELECT a.ticker, TRIM(a.industry) AS industry, b.industry_coc FROM tickers AS a LEFT JOIN industry_match AS b ON TRIM(a.industry) = b.industry_ticker ORDER BY TRIM(a.industry)");        
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $row = $res->fetchAll(PDO::FETCH_ASSOC);
    if(count($row)>0){
        return $row;
    }else{
        return NULL;
    }
}

?>
