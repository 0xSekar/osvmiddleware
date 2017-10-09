<?php
//Get yahoo estimates using YQL.

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/db.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$indexes = ['VOO', 'DIA', 'QQQ', 'IWM'];
foreach ($indexes as $index) {
    echo("Updating index $index... ");
    $resJS1 = array();
    $queryOD1 = "http://ondemand.websol.barchart.com/getHistory.json?apikey=fbb10c94f13efa7fccbe641643f7901f&symbol=".$index."&dividends=0&type=daily&startDate=".date("Ymd", strtotime("-15 years"))."&endDate=".date("Ymd")."";
    $resOD1 = file_get_contents($queryOD1);
    $resJS1 = json_decode($resOD1, true);
    $code = $resJS1['status']['code'];
    if($code == 200){
        foreach($resJS1['results'] as $record) {
            $query_div = "INSERT INTO `market_indexes_history` (index_name, report_date, close) VALUES (?,?,?)  ON DUPLICATE KEY UPDATE close = ?";
            $params = array();
            $params[] = $index;
            $params[] = $record['tradingDay'];
            $params[] = $record['close'];

            $params[] = $record['close'];
            try {
                $res1 = $db->prepare($query_div);
                $res1->execute($params);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }
        }
        echo ("Done\n");
    } else {
        echo ("FAIL\n");
    }
}
?>
