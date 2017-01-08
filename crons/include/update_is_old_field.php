<?php
function update_is_old_field() {
	$db = Database::GetInstance();
	try {
		$res = $db->query("SELECT ticker.id, MAX( reports_header.report_date ) AS fyear FROM tickers ticker LEFT JOIN reports_header ON ticker.id = reports_header.ticker_id GROUP BY ticker.id");		
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	while($row = $res->fetch(PDO::FETCH_ASSOC)) {
		$query = "update tickers set is_old = ";
		if($row["fyear"] > (date("Y") - 2)."-12-28") {
			$query .= "0";
		} else {
			$query .= "1";
		}
		$query .= " WHERE id = ".$row["id"];
		try {
			$res = $db->query($query);		
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
}
?>
