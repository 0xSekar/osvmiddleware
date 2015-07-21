<?php

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/database.php');
include_once('../include/get_xignite_estimates.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly


$count = 0;
echo "Updating Tickers...\n";

//Select all tickers not updated for at least a day
$query = "SELECT * FROM tickers";
$restmp = mysql_query($query) or die(mysql_error());
while ($rowtmp = mysql_fetch_assoc($restmp)) {
	$count ++;
	echo "Updating ".$rowtmp["ticker"]."...";
	$res2 = get_xignite_estimates_data($rowtmp["id"],$rowtmp["ticker"]);
	echo " Done\n";
}

echo $count . " rows processed\n";
?>
