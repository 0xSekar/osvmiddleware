<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout

	$query = "SELECT * FROM reports_header";
	$res = mysql_query($query) or die (mysql_error());

	while ($row = mysql_fetch_assoc($res)) {
			$query = "SELECT * from reports_incomeconsolidated a left join reports_cashflowconsolidated b on a.report_id=b.report_id left join reports_incomefull c on a.report_id=c.report_id left join reports_gf_data d on a.report_id=d.report_id left join reports_balancefull e on a.report_id=e.report_id left join reports_cashflowfull f on a.report_id=f.report_id left join reports_balanceconsolidated g on a.report_id=g.report_id where a.report_id=".$row['id'];
			$res2 = mysql_query($query) or die (mysql_error());
			$rawdata = mysql_fetch_assoc($res2);
                        $query = "UPDATE `reports_financialscustom` set `FreeCashFlow` = ";
                        $query .= "'".($rawdata["CashfromOperatingActivities"]+$rawdata["CapitalExpenditures"])."'";
                        $query .= " WHERE report_id = '".$row['id']."'";
	        	mysql_query($query) or die (mysql_error());
	}
?>
