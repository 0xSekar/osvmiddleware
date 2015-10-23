<?php
//Get complete ticker list from backend.
//Update data points if they are available
//and newer than the ones stored locally

//This script will not force download of new datapoints in the backend
//if the datapoints does not exist on the backend they must be downloaded
//first there and then this script will detect them and download

// This will avoid server overload

// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/database.php');
include_once('./update_reports_ttm_extra.php');

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
}

$symbols2 = file_get_contents("http://www.oldschoolvalue.com/webservice/get_ticker_list_frontend_extra.php");
$result2 = json_decode($symbols2);
foreach ($result2 as $symbol) {
        $count ++;
}

echo "$count total rows. $inserted new rows<br>\n";
//For each symbol in the database, check if there is new reports
//vased on the last report date in the resultset
echo "Updating data points... (run1)<br>\n";
$report_tables = array("reports_balanceconsolidated","reports_balancefull","reports_cashflowconsolidated","reports_cashflowfull","reports_financialheader","reports_gf_data","reports_incomeconsolidated","reports_incomefull","reports_metadata_eol","reports_variable_ratios");
$ticker_tables = array("tickers_activity_daily_ratios", "tickers_growth_ratios", "tickers_leverage_ratios", "tickers_metadata_eol", "tickers_mini_ratios", "tickers_profitability_ratios", "tickers_valuation_ratios");
foreach ($result as $symbol) {
	//Get last local report date and compare with remote
	$query = "SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$symbol->ticker'";
	$res = mysql_query($query) or die(mysql_error());
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

	if (!is_null($fixdate) && $fixtype != "Dummy") {
		//If the remote report is newer, download the new report and update data points
		$updated++;
		$csv = file_get_contents("http://job.oldschoolvalue.com/webservice/createcsv.php?ticker=".$fixticker);
//echo ("Get: $symbol->ticker<br>");
		$csvst = fopen('php://memory', 'r+');
		fwrite($csvst, $csv);
		unset($csv);
		fseek($csvst, 0);
		$rawdata = array();
		while ($data = fgetcsv($csvst)) {
			$rawdata[$data[0]] = $data;
		}
//fseek($csvst,0);
//file_put_contents("/tmp/test.csv", $csvst);
		//Update Raw data
		update_raw_data_tickers($dates, $rawdata);
		
		fclose($csvst);
	}
}

echo "Updating data points... (run2)<br>\n";
foreach ($result2 as $symbol) {
        //Get last local report date and compare with remote
        $query = "SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$symbol->ticker'";
        $res = mysql_query($query) or die(mysql_error());
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

        if (!is_null($fixdate) && $fixtype != "Dummy") {
                //If the remote report is newer, download the new report and update data points
                $updated++;
                $csv = file_get_contents("http://job.oldschoolvalue.com/webservice/createcsv.php?ticker=".$fixticker);
//echo ("Get: $symbol->ticker<br>");
                $csvst = fopen('php://memory', 'r+');
                fwrite($csvst, $csv);
                unset($csv);
                fseek($csvst, 0);
                $rawdata = array();
                while ($data = fgetcsv($csvst)) {
                        $rawdata[$data[0]] = $data;
                }
//fseek($csvst,0);
//file_put_contents("/tmp/test.csv", $csvst);
                //Update Raw data
                update_raw_data_tickers($dates, $rawdata);

                fclose($csvst);
        }
}

echo "$count total rows. $updated stocks has new reports<br>\n";
?>
