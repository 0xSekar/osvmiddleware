<?php
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(0);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout
$query = "delete from reports_ratings";
$res = mysql_query($query) or die (mysql_error());

$query = "SELECT DISTINCT fiscal_year from reports_header WHERE report_type='ANN' order by fiscal_year";
$resy = mysql_query($query) or die (mysql_error());

//Variables to be used for linear transform and squeez
$squ = 0.998;
$qw1 = 0.275;
$qw2 = 0.45;
$qw3 = 0.275;
$gw1 = 0.1;
$gw2 = 0.1;
$gw3 = 0.55;
$gw4 = 0.25;

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
	//Aditional variables for linear transform and squeez
	$a = -100 / ($tickerCount - 1);
	$b = 100 - $a;

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

	//GET SORTED GROWTH VARIABLES
	//SalesPercChange
	$position = 1;
        $query = "
                SELECT report_id, SalesPercChange as value, ticker_id
                FROM reports_financialscustom r
                LEFT JOIN reports_header h ON r.report_id = h.id
                WHERE h.report_type =  'ANN'
                AND h.fiscal_year = ".$rowy["fiscal_year"]."
                ORDER BY SalesPercChange DESC
        ";
        $res = mysql_query($query) or die (mysql_error());
        while ($row = mysql_fetch_assoc($res)) {
                $values[$row["report_id"]]["G1"] = is_null($row["value"])?null:($row["value"] * 100);
                $values[$row["report_id"]]["GP1"] = $position;
		$position++;
        }
	//Sales5YYCGrPerc
	$position = 1;
        $query = "
                SELECT report_id, Sales5YYCGrPerc as value, ticker_id
                FROM reports_financialscustom r
                LEFT JOIN reports_header h ON r.report_id = h.id
                WHERE h.report_type =  'ANN'
                AND h.fiscal_year = ".$rowy["fiscal_year"]."
                ORDER BY Sales5YYCGrPerc DESC
        ";
        $res = mysql_query($query) or die (mysql_error());
        while ($row = mysql_fetch_assoc($res)) {
                $values[$row["report_id"]]["G2"] = is_null($row["value"])?null:($row["value"] * 100);
                $values[$row["report_id"]]["GP2"] = $position;
		$position++;
        }
	//GrossProfitAstTotal
        $position = 1;
        $query = "
                SELECT report_id, GPA AS value
                FROM reports_key_ratios r
                LEFT JOIN reports_header h ON r.report_id = h.id
                WHERE h.report_type =  'ANN'
                AND h.fiscal_year = ".$rowy["fiscal_year"]."
                ORDER BY GPA DESC
        ";
        $res = mysql_query($query) or die (mysql_error());
        while ($row = mysql_fetch_assoc($res)) {
                $values[$row["report_id"]]["G3"] = is_null($row["value"])?null:($row["value"]);
                $values[$row["report_id"]]["GP3"] = $position;
                $position++;
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
		//SalesPercChange
                if(is_null($value["G1"])) {
                        $values[$id]["GPP1"] = round(3*$value["GP1"]);
                } else {
                        if($value["G1"] < 0)
                                $values[$id]["GPP1"] = round(3*$value["GP1"]);
                        if($value["G1"] >= 0 && $value["G1"] < 60)
                                $values[$id]["GPP1"] = round(0.2*$value["GP1"]);
                        if($value["G1"] >= 60 && $value["G1"] < 100)
                                $values[$id]["GPP1"] = round(1.3*$value["GP1"]);
                        if($value["G1"] >= 100)
                                $values[$id]["GPP1"] = round(3*$value["GP1"]);
                }
		//Sales5YYCGrPerc
                if(is_null($value["G2"])) {
                        $values[$id]["GPP2"] = round(3*$value["GP2"]);
                } else {
                        if($value["G2"] < 0)
                                $values[$id]["GPP2"] = round(3*$value["GP2"]);
                        if($value["G2"] >= 0 && $value["G2"] < 40)
                                $values[$id]["GPP2"] = round(0.2*$value["GP2"]);
                        if($value["G2"] >= 40 && $value["G2"] < 80)
                                $values[$id]["GPP2"] = round(1.25*$value["GP2"]);
                        if($value["G2"] >= 80)
                                $values[$id]["GPP2"] = round(2*$value["GP2"]);
                }
		//GrossProfitAstTotal
                if(is_null($value["G3"])) {
                        $values[$id]["GPP3"] = round(3*$value["GP3"]);
                } else {
                        if($value["G3"] < 0)
                                $values[$id]["GPP3"] = round(3*$value["GP3"]);
                        if($value["G3"] >= 0 && $value["G3"] < 1)
                                $values[$id]["GPP3"] = $value["GP3"];
                        if($value["G3"] >= 1 && $value["G3"] < 1.8)
                                $values[$id]["GPP3"] = round(0.01*$value["GP3"]);
                        if($value["G3"] >= 1.8 && $value["G3"] < 2.5)
                                $values[$id]["GPP3"] = round(3*$value["GP3"]);
                        if($value["G3"] >= 2.5)
                                $values[$id]["GPP3"] = round(3*$value["GP3"]);
                }

		//Cut values that exceed the number of tickers
		if($values[$id]["QPP1"] > $tickerCount) {
			$values[$id]["QPP1"] = $tickerCount;
		}
		if($values[$id]["QPP2"] > $tickerCount) {
			$values[$id]["QPP2"] = $tickerCount;
		}
		if($values[$id]["GPP1"] > $tickerCount) {
			$values[$id]["GPP1"] = $tickerCount;
		}
		if($values[$id]["GPP2"] > $tickerCount) {
			$values[$id]["GPP2"] = $tickerCount;
		}
		if($values[$id]["GPP3"] > $tickerCount) {
			$values[$id]["GPP3"] = $tickerCount;
		}

		//Linear transform
		$values[$id]["QPT1"] = $a * $values[$id]["QPP1"] + $b;
		$values[$id]["QPT2"] = $a * $values[$id]["QPP2"] + $b;
		$values[$id]["QPT3"] = $a * $values[$id]["QPP3"] + $b;
		$values[$id]["GPT1"] = $a * $values[$id]["GPP1"] + $b;
		$values[$id]["GPT2"] = $a * $values[$id]["GPP2"] + $b;
		$values[$id]["GPT3"] = $a * $values[$id]["GPP3"] + $b;

		//Apply Squeez
		$values[$id]["QPS1"] = ($values[$id]["QPT1"] - 50) * $squ + 50;
		$values[$id]["QPS2"] = ($values[$id]["QPT2"] - 50) * $squ + 50;
		$values[$id]["QPS3"] = ($values[$id]["QPT3"] - 50) * $squ + 50;
		$values[$id]["GPS1"] = ($values[$id]["GPT1"] - 50) * $squ + 50;
		$values[$id]["GPS2"] = ($values[$id]["GPT2"] - 50) * $squ + 50;
		$values[$id]["GPS3"] = ($values[$id]["GPT3"] - 50) * $squ + 50;

		//Apply Weight
		$values[$id]["QPW1"] = is_null($values[$id]["Q1"])?0:($values[$id]["QPS1"] * $qw1);
		$values[$id]["QPW2"] = is_null($values[$id]["Q2"])?0:($values[$id]["QPS2"] * $qw2);
		$values[$id]["QPW3"] = is_null($values[$id]["Q3"])?0:($values[$id]["QPS3"] * $qw3);
		$values[$id]["QF"] = $values[$id]["QPW1"] + $values[$id]["QPW2"] + $values[$id]["QPW3"];
		$values[$id]["GPW1"] = is_null($values[$id]["G1"])?0:($values[$id]["GPS1"] * $gw1);
		$values[$id]["GPW2"] = is_null($values[$id]["G2"])?0:($values[$id]["GPS2"] * $gw2);
		$values[$id]["GPW3"] = is_null($values[$id]["G3"])?0:($values[$id]["GPS3"] * $gw3);
		$values[$id]["GPW4"] = is_null($values[$id]["Q3"])?0:($values[$id]["QPS3"] * $gw4);
		$values[$id]["GF"] = $values[$id]["GPW1"] + $values[$id]["GPW2"] + $values[$id]["GPW3"] + $values[$id]["GPW4"];

		//Save data
		$query = "INSERT INTO `reports_ratings` (`report_id`, `Q1`, `Q2`, `Q3`, `QT`, `G1`, `G2`, `G3`, `G4`, `GT`) VALUES (";
		$query .= $id.",";
		$query .= $values[$id]["QPW1"].",";
		$query .= $values[$id]["QPW2"].",";
		$query .= $values[$id]["QPW3"].",";
		$query .= $values[$id]["QF"].",";
		$query .= $values[$id]["GPW1"].",";
		$query .= $values[$id]["GPW2"].",";
		$query .= $values[$id]["GPW3"].",";
		$query .= $values[$id]["GPW4"].",";
		$query .= $values[$id]["GF"];
		$query .= ")";
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
?>
