<?php
//Get complete ticker list to be used on the frontend and the date it was last updated
// Database Connection
include_once('./db/database.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connect();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$ticker1 = "";
if(isset($_REQUEST['ticker'])) {
	if (preg_match("/[\.\']/",$_REQUEST['ticker'], $res)) {
		$ticker1 = " AND ticker = '".str_replace($res[0],"-",$_REQUEST['ticker'])."' ";
	} else {
		$ticker1 = " AND ticker = '".$_REQUEST['ticker']."' ";
	}
}

//$sql = "SELECT c.id, c.ticker, c.cik, c.company, c.exchange, c.siccode, c.entityid, r.insdate, r.reporttype
//FROM `eol_cik_ticker` c
$sql = "SELECT r.ticker, MAX(r.insdate) as insdate, r.reporttype
FROM eol_reports r 
WHERE r.reporttype != 'Dummy' $ticker1
GROUP BY ticker ORDER by MAX(insdate) DESC limit 1";

$res = mysql_query($sql) or die (mysql_error());
$result = array();
while ($row = mysql_fetch_assoc($res)) {
	$result[] = $row;
}
echo json_encode($result);
?>
