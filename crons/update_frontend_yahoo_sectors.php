<?php
//Get yahoo Sector and Industry

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/database.php');
require_once("../include/yahoo/common.inc.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

//Using customized Yahoo Social SDK (The default version does not work)
$yql = new YahooYQLQuery();

$count2 = 0;
$eupdated = 0;
$ecurrent = 0;
$enotfound = 0;
$eerrors = 0;
echo "Updating Tickers...\n";
//Analyst Estimates needs more frequent updates
$query = "SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id";
$res = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$count2++;
	echo "Updating ".$row["ticker"]." Sector and Industry...";
	//Try to get yahoo data for the ticker
	$response = $yql->execute("select * from osv.finance.industry where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://DdSfqZBdFqJyHvfhGzzxyO");	
	if(isset($response->query) && isset($response->query->results)) {
		//Check if the symbol exists
		if(isset($response->query->results->results->Sector)) {
			$eupdated ++;
                        $query_div = "UPDATE `tickers` SET industry = '" . mysql_real_escape_string($response->query->results->results->Industry) ."', ";
                        $query_div .= "sector = '" . mysql_real_escape_string($response->query->results->results->Sector) ."' ";
                        $query_div .= "WHERE id = " . $row["id"];
                        mysql_query($query_div) or die(mysql_error());
		} else {
			$enotfound ++;
		}
	} elseif(isset($response->error)) {
		$eerrors ++;
	} else {
		$eerrors ++;
	}
	echo " Done\n";
}

echo $count2 . " rows processed\n";
echo "Sectors/Industry:\n";
echo "\t".$eupdated." tickers updates\n";
echo "\t".$ecurrent." tickers don't need update\n";
echo "\t".$enotfound." tickers not found on yahoo\n";
echo "\t".$eerrors." errors updating tickers\n";

?>
