<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/database.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

//Access on dev environment
$username = 'osv';
$password = 'test1234!';
$context = stream_context_create(array(
        'http' => array(
                'header'  => "Authorization: Basic " . base64_encode("$username:$password")
        )
));

if (!isset($_GET["ticker"])) {
	echo "Missing Ticker parameter";
	exit;
}
echo "Deleting ticker ".$_GET["ticker"]."... <br>";

$query = "SELECT count(*) as C FROM tickers WHERE ticker = '".$_GET['ticker']."'";
$res = mysql_query($query) or die(mysql_error());
$counter = mysql_fetch_object($res);
if ($counter->C == 0) {
	echo "Ticker not found in Frontend database";
	exit;
}

$query = "SELECT * FROM tickers WHERE ticker = '".$_GET['ticker']."'";
$res = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc($res);
$ticker_id = $row["id"];

$report_tables = array("reports_ratings","reports_pio_checks","reports_beneish_checks","reports_alt_checks","reports_balanceconsolidated","reports_balanceconsolidated_3cagr","reports_balanceconsolidated_5cagr","reports_balanceconsolidated_7cagr","reports_balanceconsolidated_10cagr","reports_balancefull","reports_balancefull_3cagr","reports_balancefull_5cagr","reports_balancefull_7cagr","reports_balancefull_10cagr","reports_cashflowconsolidated","reports_cashflowconsolidated_3cagr","reports_cashflowconsolidated_5cagr","reports_cashflowconsolidated_7cagr","reports_cashflowconsolidated_10cagr","reports_cashflowfull","reports_cashflowfull_3cagr","reports_cashflowfull_5cagr","reports_cashflowfull_7cagr","reports_cashflowfull_10cagr","reports_financialheader","reports_gf_data","reports_gf_data_3cagr","reports_gf_data_5cagr","reports_gf_data_7cagr","reports_gf_data_10cagr","reports_incomeconsolidated","reports_incomeconsolidated_3cagr","reports_incomeconsolidated_5cagr","reports_incomeconsolidated_7cagr","reports_incomeconsolidated_10cagr","reports_incomefull","reports_incomefull_3cagr","reports_incomefull_5cagr","reports_incomefull_7cagr","reports_incomefull_10cagr","reports_metadata_eol","reports_variable_ratios","reports_variable_ratios_3cagr","reports_variable_ratios_5cagr","reports_variable_ratios_7cagr","reports_variable_ratios_10cagr","reports_financialscustom","reports_financialscustom_3cagr","reports_financialscustom_5cagr","reports_financialscustom_7cagr","reports_financialscustom_10cagr","reports_key_ratios","reports_key_ratios_3cagr","reports_key_ratios_5cagr","reports_key_ratios_7cagr","reports_key_ratios_10cagr");
$ticker_tables = array("tickers_activity_daily_ratios", "tickers_growth_ratios", "tickers_leverage_ratios", "tickers_metadata_eol", "tickers_mini_ratios", "tickers_profitability_ratios", "tickers_valuation_ratios","tickers_xignite_estimates","tickers_yahoo_dividend_history","tickers_yahoo_estimates_curr_qtr","tickers_yahoo_estimates_curr_year", "tickers_yahoo_estimates_earn_hist", "tickers_yahoo_estimates_next_qtr", "tickers_yahoo_estimates_next_year", "tickers_yahoo_estimates_others","tickers_yahoo_historical_data","tickers_yahoo_keystats_1","tickers_yahoo_keystats_2","tickers_yahoo_quotes_1", "tickers_yahoo_quotes_2");
$ttm_tables = array("ttm_balanceconsolidated","ttm_balancefull","ttm_cashflowconsolidated","ttm_cashflowfull","ttm_incomeconsolidated","ttm_incomefull","ttm_financialscustom", "ttm_gf_data","ttm_alt_checks","ttm_beneish_checks","ttm_key_ratios","ttm_pio_checks","ttm_ratings","ttm_ratings_history","mrq_alt_checks");
$pttm_tables = array("pttm_balanceconsolidated","pttm_balancefull","pttm_cashflowconsolidated","pttm_cashflowfull","pttm_incomeconsolidated","pttm_incomefull","pttm_financialscustom", "pttm_gf_data");

echo "Deleting Reports... ";
foreach($report_tables as $table) {
	$query = "DELETE FROM $table WHERE report_id IN (SELECT id FROM reports_header WHERE ticker_id = ".$ticker_id.")";
        mysql_query($query) or die (mysql_error());
}
$query = "DELETE FROM reports_header WHERE ticker_id = ".$ticker_id;
mysql_query($query) or die (mysql_error());
echo "Done<br>";

echo "Deleting Tickers and TTM tables... ";
foreach($ticker_tables as $table) {
        $query = "DELETE FROM $table WHERE ticker_id = ".$ticker_id;
        mysql_query($query) or die (mysql_error());
}
foreach($ttm_tables as $table) {
        $query = "DELETE FROM $table WHERE ticker_id = ".$ticker_id;
        mysql_query($query) or die (mysql_error());
}
foreach($pttm_tables as $table) {
        $query = "DELETE FROM $table WHERE ticker_id = ".$ticker_id;
        mysql_query($query) or die (mysql_error());
}
echo "Done<br>";

echo "Deleting from demo_tickers table if applicable... ";
$query = "DELETE FROM demo_tickers WHERE ticker = '".$row["ticker"]."'";
mysql_query($query) or die (mysql_error());
echo "Done<br>";

echo "Deleting from tickers_conversion table if applicable... ";
$query = "DELETE FROM tickers_conversion WHERE name_from = '".$row["ticker"]."'";
mysql_query($query) or die (mysql_error());
echo "Done<br>";

echo "Deleting from control table... ";
$query = "DELETE FROM tickers_control WHERE ticker_id = ".$ticker_id;
mysql_query($query) or die (mysql_error());
echo "Done<br>";

echo "Deleting from tickers table... ";
$query = "DELETE FROM tickers WHERE id = ".$ticker_id;
mysql_query($query) or die (mysql_error());
echo "Done<br>";

echo "Ticker removed from front end database";
exit;

?>
