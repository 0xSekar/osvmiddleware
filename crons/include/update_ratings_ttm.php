<?php
function update_ratings_ttm() {
$values = array();
$query = "delete from ttm_ratings";
$res = mysql_query($query) or die (mysql_error());
$tickerCount = 0;
//GET SORTED QUALITY VARIABLES
//FCF / Sales
$query = "
SELECT x.ticker_id, x.position, x.FCF_S as value
FROM (
      select ticker_id, FCF_S, @rownum := @rownum + 1 AS position from 
	 ttm_key_ratios,(SELECT @rownum := 0) r order by FCF_S desc
      ) x
";
$res = mysql_query($query) or die (mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$values[$row["ticker_id"]]["Q1"] = is_null($row["value"])?null:($row["value"] * 100);
	$values[$row["ticker_id"]]["QP1"] = $row["position"];
	$tickerCount++;
}
//Variables to be used for linear transform and squeez
$a = -100 / ($tickerCount - 1);
$b = 100 - $a;
$squ = 0.998;
$qw1 = 0.275;
$qw2 = 0.45;
$qw3 = 0.275;

//CROIC
$query = "
SELECT x.ticker_id, x.position, x.CROIC as value
FROM (
      select ticker_id, CROIC, @rownum := @rownum + 1 AS position from 
	 ttm_key_ratios,(SELECT @rownum := 0) r order by CROIC desc
      ) x
";
$res = mysql_query($query) or die (mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$values[$row["ticker_id"]]["Q2"] = is_null($row["value"])?null:($row["value"] * 100);
	$values[$row["ticker_id"]]["QP2"] = $row["position"];
}
//PIO F Score
$query = "
SELECT x.ticker_id, x.position, x.pioTotal as value
FROM (
      select ticker_id, pioTotal, @rownum := @rownum + 1 AS position from 
	 ttm_quality_checks,(SELECT @rownum := 0) r order by pioTotal desc
      ) x
";
$res = mysql_query($query) or die (mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$values[$row["ticker_id"]]["Q3"] = $row["value"];
	$values[$row["ticker_id"]]["QP3"] = $row["position"];
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
	$query = "INSERT INTO `ttm_ratings` (`ticker_id`, `Q1`, `Q2`, `Q3`, `QT`) VALUES (";
	$query .= $id.",";
	$query .= $values[$id]["QPW1"].",";
	$query .= $values[$id]["QPW2"].",";
	$query .= $values[$id]["QPW3"].",";
	$query .= $values[$id]["QF"].")";
	$save = mysql_query($query) or die (mysql_error());

	$values[$id]["id"] = $id;
}
//Export to csv
/*$o = fopen('file.csv', 'w');
fputcsv($o,array_keys($values[1]));
foreach($values as $id=>$value) {
        fputcsv($o,$value);
}
fclose($o);*/
}
?>
