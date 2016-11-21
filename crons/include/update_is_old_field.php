<?php
function update_is_old_field() {
	$db = Database::GetInstance();
	//$query = "SELECT ticker.id, MAX( reports_header.fiscal_year ) AS fyear
	//	FROM tickers ticker
	//	LEFT JOIN reports_header ON ticker.id = reports_header.ticker_id
	//	GROUP BY ticker.id";
	try {
		$res = $db->query("SELECT ticker.id, MAX( reports_header.fiscal_year ) AS fyear FROM tickers ticker LEFT JOIN reports_header ON ticker.id = reports_header.ticker_id GROUP BY ticker.id");		
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	//$res = mysql_query($query) or die (mysql_error());
	//while($row = mysql_fetch_assoc($res)) {
	while($row = $res->fetch(PDO::FETCH_ASSOC)) {
		$query = "update tickers set is_old = ";
		if($row["fyear"] > (date("Y") - 2)) {
			$query .= "0";
		} else {
			$query .= "1";
		}
		$query .= " WHERE id = ".$row["id"];
		//mysql_query($query) or die (mysql_error());
		try {
			$res = $db->query($query);		
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
}
?>
