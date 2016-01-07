<?php
function update_ratings() {
$query = "delete from reports_ratings";
$res = mysql_query($query) or die (mysql_error());

$query = "SELECT DISTINCT fiscal_year from reports_header WHERE report_type='ANN' order by fiscal_year";
$resy = mysql_query($query) or die (mysql_error());
while($rowy = mysql_fetch_assoc($resy)) {
	$values = array();
	$tickerCount = 0;

	//GET SORTED QUALITY VARIABLES
	//FCF / Sales
	$position = 1;
	$query = "
		SELECT report_id, FCF_S as value, ticker_id
		FROM reports_key_ratios r
		LEFT JOIN reports_header h ON r.report_id = h.id
		WHERE h.report_type =  'ANN'
		AND h.fiscal_year = ".$rowy["fiscal_year"]."
		ORDER BY FCF_S DESC 
	";
	$res = mysql_query($query) or die (mysql_error());
	while ($row = mysql_fetch_assoc($res)) {
		$values[$row["report_id"]]["ticker_id"] = $row["ticker_id"];
		$values[$row["report_id"]]["Q1"] = is_null($row["value"])?null:($row["value"] * 100);
		$values[$row["report_id"]]["QP1"] = $position;
		$tickerCount++;
		$position++;
	}
	//Variables to be used for linear transform and squeez
	$a = -100 / ($tickerCount - 1);
	$b = 100 - $a;
	$squ = 0.998;
	$qw1 = 0.275;
	$qw2 = 0.45;
	$qw3 = 0.275;

	//CROIC
	$position = 1;
	$query = "
		SELECT report_id, CROIC AS value
		FROM reports_key_ratios r
		LEFT JOIN reports_header h ON r.report_id = h.id
		WHERE h.report_type =  'ANN'
		AND h.fiscal_year = ".$rowy["fiscal_year"]."
		ORDER BY CROIC DESC 
	";
	$res = mysql_query($query) or die (mysql_error());
	while ($row = mysql_fetch_assoc($res)) {
		$values[$row["report_id"]]["Q2"] = is_null($row["value"])?null:($row["value"] * 100);
		$values[$row["report_id"]]["QP2"] = $position;
		$position++;
	}
	//PIO F Score
	$position = 1;
	$query = "
		SELECT report_id, pioTotal AS value
		FROM reports_pio_checks r
		LEFT JOIN reports_header h ON r.report_id = h.id
		WHERE h.report_type =  'ANN'
		AND h.fiscal_year = ".$rowy["fiscal_year"]."
		ORDER BY pioTotal DESC 
	";
	$res = mysql_query($query) or die (mysql_error());
	while ($row = mysql_fetch_assoc($res)) {
		$values[$row["report_id"]]["Q3"] = $row["value"];
		$values[$row["report_id"]]["QP3"] = $position;
		$position++;
	}

	//Correction for missing PIO Values
	foreach($values as $id => $value) {
		if(!isset($value["Q3"]) || is_null($value["Q3"])) {
			$values[$id]["Q3"] = null;
			$values[$id]["QP3"] = $tickerCount;
		}
	}

	foreach($values as $id => $value) {
		//PENALIZE RATINGS
		//FCF / Sales
		if(is_null($value["Q1"])) {
			$values[$id]["QPP1"] = round(5*$value["QP1"]);
		} else {
			if($value["Q1"] < 0) 
				$values[$id]["QPP1"] = round(5*$value["QP1"]);
			if($value["Q1"] >= 0 && $value["Q1"] < 30) 
				$values[$id]["QPP1"] = round(0.01*$value["QP1"]);
			if($value["Q1"] >= 30 && $value["Q1"] < 60) 
				$values[$id]["QPP1"] = round(1.5*$value["QP1"]);
			if($value["Q1"] >= 60) 
				$values[$id]["QPP1"] = round(5*$value["QP1"]);
		}
		//CROIC
	        if(is_null($value["Q2"])) {
        	        $values[$id]["QPP2"] = round(3*$value["QP2"]);
	        } else {
			if($value["Q2"] < 0) 
				$values[$id]["QPP2"] = round(3*$value["QP2"]);
			if($value["Q2"] >= 0 && $value["Q2"] < 23) 
				$values[$id]["QPP2"] = $value["QP2"];
			if($value["Q2"] >= 23 && $value["Q2"] < 40) 
				$values[$id]["QPP2"] = round(0.01*$value["QP2"]);
			if($value["Q2"] >= 40 && $value["Q2"] < 60) 
				$values[$id]["QPP2"] = round(1.5*$value["QP2"]);
			if($value["Q2"] >= 60) 
				$values[$id]["QPP2"] = round(3*$value["QP2"]);
		}
		//PIO F Score
		$values[$id]["QPP3"] = $value["QP3"];

		//Cut values that exceed the number of tickers
		if($values[$id]["QPP1"] > $tickerCount) {
			$values[$id]["QPP1"] = $tickerCount;
		}
		if($values[$id]["QPP2"] > $tickerCount) {
			$values[$id]["QPP2"] = $tickerCount;
		}

		//Linear transform
		$values[$id]["QPT1"] = $a * $values[$id]["QPP1"] + $b;
		$values[$id]["QPT2"] = $a * $values[$id]["QPP2"] + $b;
		$values[$id]["QPT3"] = $a * $values[$id]["QPP3"] + $b;

		//Apply Squeez
		$values[$id]["QPS1"] = ($values[$id]["QPT1"] - 50) * $squ + 50;
		$values[$id]["QPS2"] = ($values[$id]["QPT2"] - 50) * $squ + 50;
		$values[$id]["QPS3"] = ($values[$id]["QPT3"] - 50) * $squ + 50;

		//Apply Weight
		$values[$id]["QPW1"] = is_null($values[$id]["Q1"])?0:($values[$id]["QPS1"] * $qw1);
		$values[$id]["QPW2"] = is_null($values[$id]["Q2"])?0:($values[$id]["QPS2"] * $qw2);
		$values[$id]["QPW3"] = is_null($values[$id]["Q3"])?0:($values[$id]["QPS3"] * $qw3);
		$values[$id]["QF"] = $values[$id]["QPW1"] + $values[$id]["QPW2"] + $values[$id]["QPW3"];

		//Save data
		$query = "INSERT INTO `reports_ratings` (`report_id`, `Q1`, `Q2`, `Q3`, `QT`) VALUES (";
		$query .= $id.",";
		$query .= $values[$id]["QPW1"].",";
		$query .= $values[$id]["QPW2"].",";
		$query .= $values[$id]["QPW3"].",";
		$query .= $values[$id]["QF"].")";
		$save = mysql_query($query) or die (mysql_error());

		$values[$id]["id"] = $id;
	}
	//Export to csv
	/*$o = fopen('file'.$rowy['fiscal_year'].'.csv', 'w');
	fputcsv($o,array_keys($values[1]));
	foreach($values as $id=>$value) {
        	fputcsv($o,$value);
	}
	fclose($o);*/
}
}
?>
