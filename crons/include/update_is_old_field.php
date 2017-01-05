<?php
function update_is_old_field() {
	$query = "SELECT ticker.id, MAX( reports_header.report_date ) AS fyear
		FROM tickers ticker
		LEFT JOIN reports_header ON ticker.id = reports_header.ticker_id
		GROUP BY ticker.id";
	$res = mysql_query($query) or die (mysql_error());
	while($row = mysql_fetch_assoc($res)) {
		$query = "update tickers set is_old = ";
		if($row["fyear"] > (date("Y") - 2)."-12-30") {
			$query .= "0";
		} else {
			$query .= "1";
		}
		$query .= " WHERE id = ".$row["id"];
		mysql_query($query) or die (mysql_error());
	}
}
?>
