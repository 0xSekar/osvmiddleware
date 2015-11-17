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
include_once('../db/database.php');
include_once('./include/raw_data_update_queries.php');
include_once('./include/update_key_ratios_ttm.php');
include_once('./include/update_quality_checks.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

//Get full list of symbols from backend
$symbols = file_get_contents("http://www.oldschoolvalue.com/webservice/get_ticker_list_frontend.php");
$result = json_decode($symbols);
$count = 0;
$inserted = 0;
$updated = 0;
echo "Updating ticker lists....<br>\n";

//Process the tickers and add any missing ticket to the tables (only basic ticker data)
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


$symbols2 = file_get_contents("http://www.oldschoolvalue.com/webservice/get_ticker_list_frontend_extra.php");
$result2 = json_decode($symbols2);
foreach ($result2 as $symbol2) {
        $count ++;
        $query = "SELECT count(*) as C FROM tickers WHERE ticker = '$symbol2->ticker'";
        $res = mysql_query($query) or die(mysql_error());
        $counter = mysql_fetch_object($res);
        if ($counter->C == 0) {
		$fixdate = $symbol2->insdate;
		$fixticker = $symbol2->ticker;
		$fixtype = $symbol2->reporttype;
	        if (preg_match("/[\.\-\']/",$symbol2->ticker, $match)) {
        	        $fixsym = file_get_contents("http://www.oldschoolvalue.com/webservice/get_ticker_list_frontend_special.php?ticker=$symbol2->ticker");
                	$fixsym = json_decode($fixsym);
	                $fixsym = $fixsym[0];
        	        if(isset($fixsym->ticker)) {
                	        if($fixsym->ticker != $symbol2->ticker) {
                        	        if(!is_null($fixsym->insdate) && (is_null($fixdate) || $fixdate < $fixsym->insdate)) {
                                	        $fixdate = $fixsym->insdate;
	                                        $fixticker = $fixsym->ticker;
        	                                $fixtype = $fixsym->reporttype;
                	                }
                        	}
	                }
        	}
		if (!is_null($fixdate) && $fixtype != "Dummy") {
                	$inserted ++;
	                $csv = file_get_contents("http://job.oldschoolvalue.com/webservice/createcsv.php?ticker=".$fixticker);
                	$csvst = fopen('php://memory', 'r+');
	                fwrite($csvst, $csv);
        	        unset($csv);
                	fseek($csvst, 0);
	                $rawdata = array();
        	        while ($data = fgetcsv($csvst)) {
                	        $rawdata[$data[0]] = $data;
	                }
	                $query = "INSERT INTO tickers (ticker, cik, company, exchange, sic, entityid, formername, industry, sector, country) values ('".mysql_real_escape_string($symbol2->ticker)."', '".mysql_real_escape_string($rawdata["CIK"][26])."', '".mysql_real_escape_string($rawdata["COMPANYNAME"][26])."', '".mysql_real_escape_string($rawdata["PrimaryExchange"][26])."', '".mysql_real_escape_string($rawdata["SICCode"][26])."', '".mysql_real_escape_string($rawdata["entityid"][26])."', '".mysql_real_escape_string($rawdata["Formername"][26])."', '".mysql_real_escape_string($rawdata["Industry"][26])."', '".mysql_real_escape_string($rawdata["Sector"][26])."', '".mysql_real_escape_string($rawdata["Country"][26])."')";
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
	if (preg_match("/[\.\-\']/",$symbol->ticker, $match)) {
		$fixsym = file_get_contents("http://www.oldschoolvalue.com/webservice/get_ticker_list_frontend_special.php?ticker=$symbol->ticker");
		$fixsym = json_decode($fixsym);
		$fixsym = $fixsym[0];
		if(isset($fixsym->ticker)) {
			if($fixsym->ticker != $symbol->ticker) {
				if(!is_null($fixsym->insdate) && (is_null($fixdate) || $fixdate < $fixsym->insdate)) {
				        $fixdate = $fixsym->insdate;
				        $fixticker = $fixsym->ticker;
				        $fixtype = $fixsym->reporttype;
				}
			}
		}
	}
	//End fix for different tickers

	if (!is_null($fixdate) && $dates->last_eol_date < $fixdate && $fixtype != "Dummy") {
		//If the remote report is newer, download the new report and update data points
		$updated++;
		$csv = file_get_contents("http://job.oldschoolvalue.com/webservice/createcsv.php?ticker=".$fixticker);
		$csvst = fopen('php://memory', 'r+');
		fwrite($csvst, $csv);
		unset($csv);
		fseek($csvst, 0);
		$rawdata = array();
		while ($data = fgetcsv($csvst)) {
			$rawdata[$data[0]] = $data;
		}

		//Update Raw data
		update_raw_data_tickers($dates, $rawdata);
		

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
        if (preg_match("/[\.\-\']/",$symbol->ticker, $match)) {
                $fixsym = file_get_contents("http://www.oldschoolvalue.com/webservice/get_ticker_list_frontend_special.php?ticker=$symbol->ticker");
                $fixsym = json_decode($fixsym);
                $fixsym = $fixsym[0];
                if(isset($fixsym->ticker)) {
                        if($fixsym->ticker != $symbol->ticker) {
                                if(!is_null($fixsym->insdate) && (is_null($fixdate) || $fixdate < $fixsym->insdate)) {
                                        $fixdate = $fixsym->insdate;
                                        $fixticker = $fixsym->ticker;
                                        $fixtype = $fixsym->reporttype;
                                }
                        }
                }
        }
        //End fix for different tickers

        if (!is_null($fixdate) && $dates->last_eol_date < $fixdate && $fixtype != "Dummy") {
                //If the remote report is newer, download the new report and update data points
                $updated++;
                $csv = file_get_contents("http://job.oldschoolvalue.com/webservice/createcsv.php?ticker=".$fixticker);
                $csvst = fopen('php://memory', 'r+');
                fwrite($csvst, $csv);
                unset($csv);
                fseek($csvst, 0);
                $rawdata = array();
                while ($data = fgetcsv($csvst)) {
                        $rawdata[$data[0]] = $data;
                }

                //Update Raw data
                update_raw_data_tickers($dates, $rawdata);


                //Finally update local report date
                $query = "UPDATE tickers_control SET last_eol_date = '$fixdate' WHERE ticker_id = $dates->ticker_id";
                mysql_query($query) or die (mysql_error());
                fclose($csvst);
	}
}
echo "$count total rows. $updated stocks has new reports<br>\n";
echo "Updating key ratios TTM... ";
update_key_ratios_ttm();
echo "done<br>\n";
echo "Updating Quality Checks... ";
update_quality_checks();
echo "done<br>\n";
?>
