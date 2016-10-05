<?php
//Get complete ticker list from backend.
//Update data points if they are available
//and newer than the ones stored locally

//This script will not force download of new datapoints in the backend
//if the datapoints does not exist on the backend they must be downloaded
//first there and then this script will detect them and download

// This will avoid server overload

// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/database.php');
include_once('./include/raw_data_update_queries.php');
include_once('./include/update_key_ratios_ttm.php');
include_once('./include/update_quality_checks.php');
include_once('./include/update_ratings.php');
include_once('./include/update_ratings_ttm.php');
include_once('./include/update_is_old_field.php');

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

//Get full list of symbols from backend
$symbols = file_get_contents("http://".SERVERHOST."/webservice/get_ticker_list_frontend.php", false, $context);
$result = json_decode($symbols);
$count = 0;
$inserted = 0;
$updated = 0;
$areports = AREPORTS;
$qreports = QREPORTS;
$treports = $areports+$qreports;
$update_array = array(".","'");
echo "Updating ticker lists....<br>\n";
//Process the tickers and add any missing ticket to the tables (only basic ticker data)
foreach ($result as $key => $symbol) {
	$result[$key]->ticker = str_replace($update_array, "-", $result[$key]->ticker);
}
foreach ($result as $symbol) {
	$count ++;
	$query = "SELECT count(*) as C FROM tickers WHERE ticker = '$symbol->ticker'";
	$res = mysql_query($query) or die(mysql_error());
	$counter = mysql_fetch_object($res);
	if ($counter->C == 0) {
		$inserted ++;
		$query = "INSERT INTO tickers (ticker, cik, company, exchange, sic, entityid, formername, industry, sector, country) values ('".mysql_real_escape_string($symbol->ticker)."', '".mysql_real_escape_string($symbol->cik)."', '".mysql_real_escape_string($symbol->company)."', '".mysql_real_escape_string($symbol->exchange)."', '".mysql_real_escape_string($symbol->siccode)."', '".mysql_real_escape_string($symbol->entityid)."', '".mysql_real_escape_string($symbol->formername)."', '".mysql_real_escape_string($symbol->industry)."', '".mysql_real_escape_string($symbol->sector)."', '".mysql_real_escape_string($symbol->country)."')";
		$res = mysql_query($query) or die(mysql_error());
		$id = mysql_insert_id();
		$query = "INSERT into tickers_control (ticker_id, last_eol_date, last_yahoo_date, last_volatile_date, last_estimates_date) VALUES ($id, '2000-01-01', '2000-01-01', '2000-01-01', '2000-01-01')";
		$res = mysql_query($query) or die(mysql_error());
	}
}


$symbols2 = file_get_contents("http://".SERVERHOST."/webservice/get_ticker_list_frontend_extra.php", false, $context);
$result2 = json_decode($symbols2);
foreach ($result2 as $key => $symbol) {
	$result2[$key]->ticker = str_replace($update_array, "-", $result2[$key]->ticker);
}
foreach ($result2 as $symbol2) {
        $count ++;
        $query = "SELECT count(*) as C FROM tickers WHERE ticker = '$symbol2->ticker'";
        $res = mysql_query($query) or die(mysql_error());
        $counter = mysql_fetch_object($res);
        if ($counter->C == 0) {
		$fixdate = $symbol2->insdate;
		$fixticker = $symbol2->ticker;
		$fixtype = $symbol2->reporttype;
		if (!is_null($fixdate) && $fixtype != "Dummy") {
                	$inserted ++;
	                $csv = file_get_contents("http://".SERVERHOST."/webservice/createcsv.php?source=frontend&ticker=".$fixticker, false, $context);
                	$csvst = fopen('php://memory', 'r+');
	                fwrite($csvst, $csv);
        	        unset($csv);
                	fseek($csvst, 0);
	                $rawdata = array();
        	        while ($data = fgetcsv($csvst)) {
                	        $rawdata[$data[0]] = $data;
	                }
	                $query = "INSERT INTO tickers (ticker, cik, company, exchange, sic, entityid, formername, industry, sector, country) values ('".mysql_real_escape_string($symbol2->ticker)."', '".mysql_real_escape_string($rawdata["CIK"][$treports])."', '".mysql_real_escape_string($rawdata["COMPANYNAME"][$treports])."', '".mysql_real_escape_string($rawdata["PrimaryExchange"][$treports])."', '".mysql_real_escape_string($rawdata["SICCode"][$treports])."', '".mysql_real_escape_string($rawdata["entityid"][$treports])."', '".mysql_real_escape_string($rawdata["Formername"][$treports])."', '".mysql_real_escape_string($rawdata["Industry"][$treports])."', '".mysql_real_escape_string($rawdata["Sector"][$treports])."', '".mysql_real_escape_string($rawdata["Country"][$treports])."')";
        	        $res = mysql_query($query) or die(mysql_error());
                	$id = mysql_insert_id();
	                $query = "INSERT into tickers_control (ticker_id, last_eol_date, last_yahoo_date, last_volatile_date, last_estimates_date) VALUES ($id, '2000-01-01', '2000-01-01', '2000-01-01', '2000-01-01')";
        	        $res = mysql_query($query) or die(mysql_error());
			fclose($csvst);
		}

        }
}

echo "$count total rows. $inserted new rows<br>\n";

//For each symbol in the database, check if there is new reports
//vased on the last report date in the resultset
echo "Updating data points... (run 1) <br>\n";
$report_tables = array("reports_balanceconsolidated","reports_balancefull","reports_cashflowconsolidated","reports_cashflowfull","reports_financialheader","reports_gf_data","reports_incomeconsolidated","reports_incomefull","reports_metadata_eol","reports_variable_ratios");
$ticker_tables = array("tickers_activity_daily_ratios", "tickers_growth_ratios", "tickers_leverage_ratios", "tickers_metadata_eol", "tickers_mini_ratios", "tickers_profitability_ratios", "tickers_valuation_ratios");
foreach ($result as $symbol) {
	if (is_null($symbol->ticker) || trim($symbol->ticker) == "") continue;
	//Get last local report date and compare with remote
	$query = "SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$symbol->ticker'";
	$res = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($res) == 0) continue;
	$dates = mysql_fetch_object($res);

	//Fix for different tickers names on different databases
	$fixdate = $symbol->insdate;
	$fixticker = $symbol->ticker;
	$fixtype = $symbol->reporttype;
	//End fix for different tickers

	if (!is_null($fixdate) && $dates->last_eol_date < $fixdate && $fixtype != "Dummy") {
		//If the remote report is newer, download the new report and update data points
		$updated++;
		echo "Downloading data for ".$fixticker."... ";
		$csv = file_get_contents("http://".SERVERHOST."/webservice/createcsv.php?source=frontend&ticker=".$fixticker, false, $context);
		echo "Updating ticker ".$symbol->ticker."\n";
		$csvst = fopen('php://memory', 'r+');
		fwrite($csvst, $csv);
		unset($csv);
		fseek($csvst, 0);
		$rawdata = array();
		while ($data = fgetcsv($csvst)) {
                        for($i=1; $i<=$treports;$i++) {
                                if(!isset($data[$i])) {
                                        $data[$i] = "null";
                                }
                        }
			$rawdata[$data[0]] = $data;
		}
		array_walk_recursive($rawdata, 'nullValues');

		//Update Raw data
		if(isset($rawdata["AccountsPayableTurnoverDaysFY"])) {
			update_raw_data_tickers($dates, $rawdata);
		}
		
		//Update Key ratios TTM
		update_key_ratios_ttm($dates->ticker_id);

		//Update Quality Checks
		update_pio_checks($dates->ticker_id);
		update_altman_checks($dates->ticker_id);
		update_beneish_checks($dates->ticker_id);

		//Finally update local report date
		$query = "UPDATE tickers_control SET last_eol_date = '$fixdate' WHERE ticker_id = $dates->ticker_id";
		mysql_query($query) or die (mysql_error());
		fclose($csvst);
	}
}

echo "Updating data points... (run 2) <br>\n";
foreach ($result2 as $symbol) {
	if (is_null($symbol->ticker) || trim($symbol->ticker) == "") continue;
        //Get last local report date and compare with remote
        $query = "SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$symbol->ticker'";
        $res = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($res) == 0) continue;
        $dates = mysql_fetch_object($res);

        //Fix for different tickers names on different databases
        $fixdate = $symbol->insdate;
        $fixticker = $symbol->ticker;
        $fixtype = $symbol->reporttype;
        //End fix for different tickers

        if (!is_null($fixdate) && $dates->last_eol_date < $fixdate && $fixtype != "Dummy") {
                //If the remote report is newer, download the new report and update data points
                $updated++;
		echo "Downloading data for ".$fixticker."... ";
                $csv = file_get_contents("http://".SERVERHOST."/webservice/createcsv.php?source=frontend&ticker=".$fixticker, false, $context);
		echo "Updating ticker ".$symbol->ticker."\n";
                $csvst = fopen('php://memory', 'r+');
                fwrite($csvst, $csv);
                unset($csv);
                fseek($csvst, 0);
                $rawdata = array();
                while ($data = fgetcsv($csvst)) {
			for($i=1; $i<=$treports;$i++) {
				if(!isset($data[$i])) {
					$data[$i] = "null";
				}
			}
                        $rawdata[$data[0]] = $data;
                }
		array_walk_recursive($rawdata, 'nullValues');

                //Update Raw data
		if(isset($rawdata["AccountsPayableTurnoverDaysFY"])) {
	                update_raw_data_tickers($dates, $rawdata);
		}

		//Update Key ratios TTM
		update_key_ratios_ttm($dates->ticker_id);

		//Update Quality Checks
		update_pio_checks($dates->ticker_id);
		update_altman_checks($dates->ticker_id);
		update_beneish_checks($dates->ticker_id);

                //Finally update local report date
                $query = "UPDATE tickers_control SET last_eol_date = '$fixdate' WHERE ticker_id = $dates->ticker_id";
                mysql_query($query) or die (mysql_error());
                fclose($csvst);
	}
}
echo "$count total rows. $updated stocks has new reports<br>\n";
echo "Removing old Quality Checks (PIO)... ";
$query = "delete a from reports_pio_checks a left join reports_header b on a.report_id = b.id where b.id IS null";
mysql_query($query) or die (mysql_error());
echo "done<br>\n";
echo "Removing old Quality Checks (ALTMAN)... ";
$query = "delete a from reports_alt_checks a left join reports_header b on a.report_id = b.id where b.id IS null";
mysql_query($query) or die (mysql_error());
echo "done<br>\n";
echo "Removing old Quality Checks (BENEISH)... ";
$query = "delete a from reports_beneish_checks a left join reports_header b on a.report_id = b.id where b.id IS null";
mysql_query($query) or die (mysql_error());
echo "done<br>\n";
echo "Updating Ratings... ";
update_ratings();
echo "done<br>\n";
echo "Updating Ratings TTM... ";
update_ratings_ttm();
echo "done<br>\n";
echo "Updating is_old tickers table field... ";
update_is_old_field();
echo "done<br>\n";

function nullValues(&$item, $key) {
        if(strlen(trim($item)) == 0) {
                $item = 'null';
        } else if($item == "-") {
                $item = 'null';
        }
}
?>
