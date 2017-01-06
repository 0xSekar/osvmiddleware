<?php
//Get yahoo Sector and Industry

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/database.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$count2 = 0;
echo "Updating Tickers...\n";
//Analyst Estimates needs more frequent updates
$query = "SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id";
$res = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$count2++;
	echo "Updating ".$row["ticker"]." Quote...";

        $query = "delete from tickers_alt_aux where ticker_id = " . $row["id"];
        mysql_query($query) or die (mysql_error());

	$query1 = "SELECT *,
        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
        THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
        FROM (SELECT c.id,a.*, MarketCapitalization as MarketValueofEquity,
        (CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE MarketCapitalization / TotalLiabilities END) AS X4
        FROM tickers c, mrq_alt_checks a, tickers_yahoo_quotes_1 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
	$res1 = mysql_query($query1) or die(mysql_error());
	$row1 = mysql_fetch_assoc($res1);

	$query2 = "SELECT *,
        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
        THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
        (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
        FROM (SELECT c.id,a.*, SharesOutstandingDiluted * LastTradePriceOnly as MarketValueofEquity,
        (CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE SharesOutstandingDiluted * LastTradePriceOnly / TotalLiabilities END) AS X4
        FROM tickers c, ttm_alt_checks a, tickers_yahoo_quotes_2 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
	$res2 = mysql_query($query2) or die(mysql_error());
	$row2 = mysql_fetch_assoc($res2);

	$query = "INSERT INTO  `jjun0366_frontend`.`tickers_alt_aux` (`ticker_id` ,`mrq_MarketValueofEquity` ,`mrq_X4` ,`mrq_AltmanZNormal` ,`mrq_AltmanZRevised` ,`ttm_MarketValueofEquity`, `ttm_X4` ,`ttm_AltmanZNormal` ,`ttm_AltmanZRevised`)VALUES (";
	$query .= "'".$row["id"]."',";
	if(is_null($row1)) {
		$query .= "null,null,null,null,";
	} else {
		$query .= (is_null($row1["MarketValueofEquity"])?'null':($row1["MarketValueofEquity"])).",";
		$query .= (is_null($row1["X4"])?'null':($row1["X4"])).",";
		$query .= (is_null($row1["AltmanZNormal"])?'null':($row1["AltmanZNormal"])).",";
		$query .= (is_null($row1["AltmanZRevised"])?'null':($row1["AltmanZRevised"])).",";
	}
	if(is_null($row2)) {
		$query .= "null,null,null,null";
	} else {
		$query .= (is_null($row2["MarketValueofEquity"])?'null':($row2["MarketValueofEquity"])).",";
		$query .= (is_null($row2["X4"])?'null':($row2["X4"])).",";
		$query .= (is_null($row2["AltmanZNormal"])?'null':($row2["AltmanZNormal"])).",";
		$query .= (is_null($row2["AltmanZRevised"])?'null':($row2["AltmanZRevised"]));
	}
	$query .= ")";
	mysql_query($query) or die(mysql_error());

	echo " Done\n";
}

echo $count2 . " rows processed\n";
?>
