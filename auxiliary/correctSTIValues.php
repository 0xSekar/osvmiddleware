<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout

	$query = "SELECT * FROM reports_header";
	$res = mysql_query($query) or die (mysql_error());

	while ($row = mysql_fetch_assoc($res)) {
			$query = "SELECT * from reports_cashflowfull where report_id=".$row['id'];
			$res2 = mysql_query($query) or die (mysql_error());
			$rawdata = mysql_fetch_assoc($res2);
                        $query = "UPDATE `reports_balancefull` set `ShorttermInvestments` = ";
                        $query .= "'".($rawdata["ShorttermInvestments"])."'";
                        $query .= " WHERE report_id = '".$row['id']."'";
	        	mysql_query($query) or die (mysql_error());
	}
?>
