<?php
//Get yahoo Sector and Industry

// Database Connection
error_reporting(0);
include_once('../config.php');
include_once('../db/database.php');
include_once('../db/db.php'); // agregar esto en cada database que vea
require_once("../include/yahoo/common.inc.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();
$db = Database::GetInstance(); 

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

//Using customized Yahoo Social SDK (The default version does not work)
$yql = new YahooYQLQuery();

$count = 0;
$output = "Checking up to 10 tickets...\n";
echo "Checking up to 10 tickets...\n";
//$query = "SELECT g.* FROM tickers g JOIN (SELECT id FROM tickers WHERE RAND() < (SELECT ((10 / COUNT(*)) * 10) FROM tickers) ORDER BY RAND() LIMIT 10) AS z ON z.id = g.id";
//$res = mysql_query($query) or die(mysql_error());
try {
	$res = $db->query("SELECT g.* FROM tickers g JOIN (SELECT id FROM tickers WHERE RAND() < (SELECT ((10 / COUNT(*)) * 10) FROM tickers) ORDER BY RAND() LIMIT 10) AS z ON z.id = g.id");
} catch(PDOException $ex) {
    echo "Database Error!"; //user message
    //some_logging_function($ex->getMessage());
}

//while (($row = mysql_fetch_assoc($res)) && $count < 4) {
while ($row = $res->fetch(PDO::FETCH_ASSOC)) && $count < 4) {
	echo "Checking ".$row["ticker"]."...";
	$output .= "Checking ".$row["ticker"]."...";
	//Try to get yahoo data for the ticker
	$response = $yql->execute("select * from osv.finance.quotes where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
	if(!$response) {
		$count++;
		echo (" ERROR\n");
		$output .= " ERROR\n";
	} else {
		echo (" OK\n");
		$output .= " OK\n";
	}
}
echo ("\nFound $count errors... ");
$output .= "\nFound $count errors... ";

//$query = "SELECT value FROM system WHERE parameter = 'query_yahoo'";
//$res = mysql_query($query) or die(mysql_error());
//$row = mysql_fetch_assoc($res);

try {
	$res = $db->query("SELECT value FROM system WHERE parameter = 'query_yahoo'");
} catch(PDOException $ex) {
    echo "Database Error!"; //user message
    //ome_logging_function($ex->getMessage());
}
$row = $res->fetch(PDO::FETCH_ASSOC);

if ($count > 3) {
	echo ("Disabling YAHOO queries\n");
	$output .= "Disabling YAHOO queries\n";
	$query = "INSERT INTO system (parameter, value) values ('query_yahoo', 0) ON DUPLICATE KEY UPDATE value = 0";
	if($row["value"] == 1) {
		mail ("yahooalert.i9xgk@zapiermail.com" , "OSV Yahoo Status Checker" , $output);
	}
} else {
	echo ("Enabling YAHOO queries\n");
	$output .= "Enabling YAHOO queries\n";
	$query = "INSERT INTO system (parameter, value) values ('query_yahoo', 1) ON DUPLICATE KEY UPDATE value = 1";
	if($row["value"] == 0) {
		mail ("yahooalert.i9xgk@zapiermail.com" , "OSV Yahoo Status Checker" , $output);
	}
}
//$res = mysql_query($query) or die(mysql_error());
$res = $db->exec($query);

?>
