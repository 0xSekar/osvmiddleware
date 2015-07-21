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

//$sql = "SELECT c.id, c.ticker, c.cik, c.company, c.exchange, c.siccode, c.entityid, r.insdate, r.reporttype
//FROM `eol_cik_ticker` c
$sql = "SELECT c.id, c.ticker, c.cik, c.companyname as company, c.exchange, c.formername, c.industry, c.sector, c.country, d.siccode, d.entityid, r.insdate, r.reporttype
FROM `eol_cik_ticker_list` c
LEFT JOIN eol_reports r ON c.ticker = r.ticker
LEFT JOIN eol_cik_ticker d ON c.ticker = d.ticker
WHERE insdate IS NULL
OR insdate = (
SELECT MAX( insdate )
FROM eol_reports r2
WHERE r2.ticker = c.ticker
GROUP BY r2.idcontrol LIMIT 1)
GROUP BY ticker";

$res = mysql_query($sql) or die (mysql_error());
$result = array();
while ($row = mysql_fetch_assoc($res)) {
	$result[] = $row;
}
echo json_encode($result);
?>
