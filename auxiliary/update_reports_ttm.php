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
include_once('../config.php');
include_once('../db/database.php');
include_once('./update_reports_ttm_extra.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
connectfe();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$areports = AREPORTS;
$qreports = QREPORTS;
$treports = $areports+$qreports;

//Get full list of symbols from backend
$query = "SELECT a.* from tickers a inner join reports_header b on a.id=b.ticker_id group by a.id";
$res = mysql_query($query) or die (mysql_error());

$count = 0;
$inserted = 0;
$updated = 0;

echo "Updating data points...<br>\n";
while($row = mysql_fetch_assoc($res)) {
	$query = "Select count(*) as c from reports_header a where a.ticker_id=".$row["id"]." AND a.report_type='ANN'";
	$res2 = mysql_query($query) or die (mysql_error());
	$annCount = mysql_fetch_assoc($res2);
	$annCount = $annCount["c"];
	$query = "Select count(*) as c from reports_header a where a.ticker_id=".$row["id"]." AND a.report_type='QTR'";
	$res2 = mysql_query($query) or die (mysql_error());
	$qtrCount = mysql_fetch_assoc($res2);
	$qtrCount = $qtrCount["c"];
	$count++;
	$rawdata = array();
	$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.ticker_id=".$row["id"]." AND a.report_type='ANN' order by a.fiscal_year";
	$res2 = mysql_query($query) or die (mysql_error());
	$pos = $areports - $annCount;
	while($row2 = mysql_fetch_assoc($res2)) {
		$row2b = $row2;
		$pos++;
		foreach ($row2 as $v=>$y) {
			$rawdata[$v][0] = $v;
			$rawdata[$v][$pos]=$y;
		}
	}
	for ($i = 1; $i <= $areports - $annCount; $i++) {
                foreach ($row2b as $v=>$y) {
                        $rawdata[$v][$i] = null;
                }
	}

	$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.ticker_id=".$row["id"]." AND a.report_type='QTR' order by a.fiscal_year, a.fiscal_quarter";
	$res2 = mysql_query($query) or die (mysql_error());
	$pos = $treports - $qtrCount;
	while($row2 = mysql_fetch_assoc($res2)) {
		$row2b = $row2;
		$pos++;
		foreach ($row2 as $v=>$y) {
			$rawdata[$v][$pos]=$y;
		}
	}
	for($i = 1; $i <= $treports - $qtrCount; $i++) {
                foreach ($row2b as $v=>$y) {
                        $rawdata[$v][$areports+$i] = null;
                }
	}
	$dates->ticker_id = $row["id"];
	array_walk_recursive($rawdata, 'nullValues');
        update_raw_data_tickers($dates, $rawdata);
}

echo "$count total rows. $updated stocks has new reports<br>\n";

function nullValues(&$item, $key) {
	if (is_null($item)) {
                $item = 'null';
        } else if(strlen(trim($item)) == 0) {
                $item = 'null';
        } else if($item == "-") {
                $item = 'null';
        }
}
?>
