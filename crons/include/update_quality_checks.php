<?php
function update_pio_checks($ti = null) {
	$db = Database::GetInstance();
	if (is_null($ti)) {
		try {
			$res = $db->query("delete from reports_pio_checks");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("DELETE from ttm_pio_checks");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	} else {
		try {
			$res = $db->query("DELETE from ttm_pio_checks where ticker_id = $ti");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("SELECT * FROM reports_header where report_type='ANN' and ticker_id = $ti order by ticker_id, fiscal_year");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
	$pid = 0;
	$ppid = 0;
	$idChange = true;
	$first = true;
	$query2 = array();
	$rawdata = null;
	$prawdata = null;
	while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
		$total = 0;
		$value = 0;
		if ($row["ticker_id"] != $pid) {
			$ppid = $pid;
			$pid = $row["ticker_id"];
			$idChange = true;
			$querypre = $query2;
		} else {
			$first = false;
			$idChange = false;
		}
		$pprawdata = $prawdata;
		$prawdata = $rawdata;
		$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.id= " . $row["id"];
		try {
			$res2 = $db->query($query);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}                
		$rawdata = $res2->fetch(PDO::FETCH_ASSOC);
		$query1 = "INSERT INTO `reports_pio_checks` (`report_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //11
		$params = array();
		$params[] = $row["id"];
		//Pio 1
		$value = (!is_null($rawdata["IncomebeforeExtraordinaryItems"]) && $rawdata["IncomebeforeExtraordinaryItems"] >= 0 ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 2
		$value = (!is_null($rawdata["CashfromOperatingActivities"]) && $rawdata["CashfromOperatingActivities"] >= 0 ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 3
		if($idChange) {
			if(is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) {
				$params[] = 0;
			} else {
				$value = (($rawdata["IncomebeforeExtraordinaryItems"]/$rawdata["TotalAssets"]) >= 0 ? 1 : 0);
				$total += $value;
				$params[] = ($value);
			}
		} else {
			$vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? 0 : ($rawdata["IncomebeforeExtraordinaryItems"]/$rawdata["TotalAssets"]);
			$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($prawdata["IncomebeforeExtraordinaryItems"]/$prawdata["TotalAssets"]);
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$params[] = ($value);
		}
		//Pio 4
		$value = ($rawdata["CashfromOperatingActivities"] >= $rawdata["IncomebeforeExtraordinaryItems"] ? 1 : 0);
		$total += $value;
		$params[] = ($value);

		if($idChange) {
			//Pio 5
			if(is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) {
				$params[] = 1;
				$total++;
			} else {
				$value = ((($rawdata["TotalLongtermDebt"])/$rawdata["TotalAssets"]) >= 0 ? 1 : 0);
				$total += $value;
				$params[] = ($value);
			}
			//Pio 6
			if(is_null($rawdata["TotalCurrentLiabilities"]) || $rawdata["TotalCurrentLiabilities"] == 0) {
				$params[] = 0;
			} else {
				$value = (($rawdata["TotalCurrentAssets"]/$rawdata["TotalCurrentLiabilities"]) > 0.5 ? 1 : 0);
				$total += $value;
				$params[] = ($value);
			}
			//Pio 7, 8, 9
			$params[] = 0;
			$params[] = 0;
			$params[] = 0;
		} else {
			//Pio 5
			$vn = (($rawdata["TotalAssets"]+$prawdata["TotalAssets"]) == 0) ? 0 : (($rawdata["TotalLongtermDebt"])/(($rawdata["TotalAssets"]+$prawdata["TotalAssets"])/2));
			if ($pprawdata["ticker_id"] == $prawdata["ticker_id"]) {
				$vv = (($prawdata["TotalAssets"]+$pprawdata["TotalAssets"]) == 0) ? 0 : (($prawdata["TotalLongtermDebt"])/(($prawdata["TotalAssets"]+$pprawdata["TotalAssets"])/2));
			} else {
				$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : (($prawdata["TotalLongtermDebt"])/$prawdata["TotalAssets"]);
			}
			$value = ($vn <= $vv ? 1 : 0);
			$total += $value;
			$params[] = ($value);
			//Pio 6
			$vn = (is_null($rawdata["TotalCurrentLiabilities"]) || $rawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($rawdata["TotalCurrentAssets"]/$rawdata["TotalCurrentLiabilities"]);
			$vv = (is_null($prawdata["TotalCurrentLiabilities"]) || $prawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($prawdata["TotalCurrentAssets"]/$prawdata["TotalCurrentLiabilities"]);
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$params[] = ($value);
			//Pio 7
			$value = (toFloat($rawdata["SharesOutstandingDiluted"]) <= toFloat($prawdata["SharesOutstandingDiluted"]) ? 1 : 0);
			$total += $value;
			$params[] = ($value);
			//Pio 8
			$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? 0 : ($rawdata["GrossProfit"]/$rawdata["TotalRevenue"]);
			$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? 0 : ($prawdata["GrossProfit"]/$prawdata["TotalRevenue"]);
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$params[] = ($value);
			//Pio 9
			$vn = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($rawdata["TotalRevenue"]/$prawdata["TotalAssets"]);
			if($pprawdata["ticker_id"] == $prawdata["ticker_id"]) {
				$vv = (is_null($pprawdata["TotalAssets"]) || $pprawdata["TotalAssets"] == 0) ? 0 : ($prawdata["TotalRevenue"]/$pprawdata["TotalAssets"]);
			} else {
				$vv = 0;
			}
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$params[] = ($value);
		}
		$params[] = ($total);
		$query2 = $params;
		array_shift($query2);
		try {
			$res1 = $db->prepare($query1);
			$res1->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}

		//Update TTM Data
		if($idChange && !$first) {
			pioTTM($ppid,$prawdata,$querypre,$pprawdata);
		}
		$first = false;
	}

	if (!$first) {
		pioTTM($pid,$rawdata,$query2,$prawdata);
	}
}

function update_altman_checks($ti = null) {
	$db = Database::GetInstance();
	if (is_null($ti)) {
		try {
			$res = $db->query("delete from reports_alt_checks");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("DELETE from ttm_alt_checks");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("DELETE from mrq_alt_checks");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	} else {
		try {
			$res = $db->query("DELETE from ttm_alt_checks where ticker_id = $ti");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("DELETE from mrq_alt_checks where ticker_id = $ti");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("SELECT * FROM reports_header where report_type='ANN' and ticker_id = $ti order by ticker_id, fiscal_year");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}

	$pid = 0;
	$ppid = 0;
	$idChange = true;
	$first = true;  
	while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
		if ($row["ticker_id"] != $pid) {
			$ppid = $pid;
			$pid = $row["ticker_id"];
			$idChange = true;
		} else {
			$first = false;
			$idChange = false;
		}
		$query = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_balanceconsolidated c, reports_gf_data d WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id= " . $row["id"];
		try {
			$res2 = $db->query($query);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}        
		$rawdata = $res2->fetch(PDO::FETCH_ASSOC);

		array_walk_recursive($rawdata, 'nullValues');

		$qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$row["ticker_id"]."' and report_date <= '".$rawdata["report_date"]."' order by report_date desc limit 1";
		$price = null;
		try {
			$rquote = $db->query($qquote);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		} 
		$row_count = $rquote->rowCount();
		if($row_count > 0) {
			$pricerow = $rquote->fetch(PDO::FETCH_ASSOC);
			$price = $pricerow["adj_close"];
			$rawdata["SharesOutstandingDiluted"] = max($rawdata["SharesOutstandingDiluted"], $pricerow["SharesOutstandingY"]/1000000, $pricerow["SharesOutstandingBC"]/1000000);
		}

		$query1 = "INSERT INTO `reports_alt_checks` (`report_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `MarketValueofEquity`, `NetSales`, `X1`, `X2`, `X3`, `X4`, `X5`, `AltmanZNormal`, `AltmanZRevised`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//15
		$params = array();
		$params[] = ($rawdata["id"] =='null' ? null:$rawdata["id"]);
		$params[] = ($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]);
		$params[] = ($rawdata["TotalAssets"] =='null' ? null:$rawdata["TotalAssets"]);
		$params[] = ($rawdata["TotalLiabilities"] =='null' ? null:$rawdata["TotalLiabilities"]);
		$params[] =  ($rawdata["RetainedEarnings"] =='null' ? null:$rawdata["RetainedEarnings"]);
		$params[] = ($rawdata["EBIT"] =='null' ? null:$rawdata["EBIT"]);
		$params[] = $price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000;
		$params[] = $rawdata["TotalRevenue"];
		$x1 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? (($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"])/$rawdata["TotalAssets"]) : null);
		$x2 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["RetainedEarnings"]/$rawdata["TotalAssets"]) : null);
		$x3 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["EBIT"]/$rawdata["TotalAssets"]) : null);
		$x4 = ($rawdata["TotalLiabilities"] !== 'null' && $rawdata["TotalLiabilities"] != 0 ? (($price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000)/$rawdata["TotalLiabilities"]) : null);
		$x5 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["TotalRevenue"]/$rawdata["TotalAssets"]) : null);

		$params[] = $x1;
		$params[] = $x2;
		$params[] = $x3;
		$params[] = $x4;
		$params[] = $x5;
		$params[] = (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null' && $x5 !== 'null') ? (1.2*$x1+1.4*$x2+3.3*$x3+0.6*$x4+0.999*$x5) : null);
		$params[] = (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null') ? (6.56*$x1+3.26*$x2+6.72*$x3+1.05*$x4) : null);
		try {
			$res1 = $db->prepare($query1);
			$res1->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}

		//Update TTM Data
		if($idChange && !$first) {
			altmanTTM($ppid);
		}
		$first = false;
	}
	if (!$first) {
		altmanTTM($pid);
	}
}

function update_beneish_checks($ti = null) {
	$db = Database::GetInstance();
	if (is_null($ti)) {
		try {
			$res = $db->query("delete from reports_beneish_checks");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("DELETE from ttm_beneish_checks");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}                                                                                                                   
	} else {
		try {
			$res = $db->query("DELETE from ttm_beneish_checks where ticker_id = $ti");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		try {
			$res = $db->query("SELECT * FROM reports_header where report_type='ANN' and ticker_id = $ti order by ticker_id, fiscal_year");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}

	$pid = 0;
	$ppid = 0;
	$idChange = true;
	$first = true;
	$query2 = array();
	$rawdata = null;
	while ($row = $res->fetch(PDO::FETCH_ASSOC)) {          
		if ($row["ticker_id"] != $pid) {
			$ppid = $pid;
			$pid = $row["ticker_id"];
			$idChange = true;
			$querypre = $query2;
		} else {
			$first = false;
			$idChange = false;
		}
		$prawdata = $rawdata;
		$query = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_cashflowconsolidated c, reports_balanceconsolidated d WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id= " . $row["id"];
		try {
			$res2 = $db->query($query);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		$rawdata = $res2->fetch(PDO::FETCH_ASSOC);       

		//Update TTM Data
		if($idChange && !$first) {
			beneishTTM($ppid,$prawdata,$querypre);
		}

		$query1 = "INSERT INTO `reports_beneish_checks` (`report_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //11
		$params = array();
		$params[] = $row["id"];
		//DSRI
		$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : ($rawdata["TotalReceivablesNet"]/$rawdata["TotalRevenue"]);
		$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($prawdata["TotalReceivablesNet"]/$prawdata["TotalRevenue"]);
		$dsri = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $dsri;
		//GMI
		$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : (($rawdata["TotalRevenue"]-$rawdata["CostofRevenue"])/$rawdata["TotalRevenue"]);
		$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : (($prawdata["TotalRevenue"]-$prawdata["CostofRevenue"])/$prawdata["TotalRevenue"]);
		$gmi = ((is_null($vn) || $vn == 0) ? null : ($vv/$vn));
		$params[] = $gmi;
		//AQI
		$vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["TotalAssets"]-$rawdata["PropertyPlantEquipmentNet"]-$rawdata["TotalCurrentAssets"])/$rawdata["TotalAssets"]);
		$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? null : (($prawdata["TotalAssets"]-$prawdata["PropertyPlantEquipmentNet"]-$prawdata["TotalCurrentAssets"])/$prawdata["TotalAssets"]);
		$aqi = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $aqi;
		//SGI
		$sgi = ((is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($rawdata["TotalRevenue"]/$prawdata["TotalRevenue"]));
		$params[] = $sgi;
		//DEPI
		$vn = ((is_null($rawdata["CFDepreciationAmortization"]) && is_null($rawdata["PropertyPlantEquipmentNet"])) || ($rawdata["CFDepreciationAmortization"]+$rawdata["PropertyPlantEquipmentNet"]) == 0) ? null : ($rawdata["CFDepreciationAmortization"]/($rawdata["CFDepreciationAmortization"]+$rawdata["PropertyPlantEquipmentNet"]));
		$vv = ((is_null($prawdata["CFDepreciationAmortization"]) && is_null($prawdata["PropertyPlantEquipmentNet"])) || ($prawdata["CFDepreciationAmortization"]+$prawdata["PropertyPlantEquipmentNet"]) == 0) ? null : ($prawdata["CFDepreciationAmortization"]/($prawdata["CFDepreciationAmortization"]+$prawdata["PropertyPlantEquipmentNet"]));
		$depi = ((is_null($vn) || $vn == 0) ? null : ($vv/$vn));
		$params[] = $depi;
		//SGAI
		$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : ($rawdata["SellingGeneralAdministrativeExpenses"]/$rawdata["TotalRevenue"]);
		$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($prawdata["SellingGeneralAdministrativeExpenses"]/$prawdata["TotalRevenue"]);
		$sgai = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $sgai;
		//TATA
		$tata = ((is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["IncomebeforeExtraordinaryItems"]-$rawdata["CashfromOperatingActivities"])/$rawdata["TotalAssets"]));
		$params[] = $tata;
		//LVGI
		$vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["TotalCurrentLiabilities"]+$rawdata["TotalLongtermDebt"])/$rawdata["TotalAssets"]);
		$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? null : (($prawdata["TotalCurrentLiabilities"]+$prawdata["TotalLongtermDebt"])/$prawdata["TotalAssets"]);
		$lvgi = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $lvgi;
		//BM5
		$bm5 = -6.065+(0.823*($dsri == 'null'?0:$dsri))+(0.906*($gmi=='null'?0:$gmi))+(0.593*($aqi=='null'?0:$aqi))+(0.717*($sgi=='null'?0:$sgi))+(0.107*($depi=='null'?0:$depi));
		$params[] = $bm5;
		//BM8
		$bm8 = -4.84+(0.92*($dsri == 'null'?0:$dsri))+(0.528*($gmi=='null'?0:$gmi))+(0.404*($aqi=='null'?0:$aqi))+(0.892*($sgi=='null'?0:$sgi))+(0.115*($depi=='null'?0:$depi))-(0.172*($sgai=='null'?0:$sgai))+(4.679*($tata=='null'?0:$tata))-(0.327*($lvgi=='null'?0:$lvgi));
		$params[] = $bm8;
		$query2 = $params;
		array_shift($query2);

		$first = false;
		//Skip calculations for first report of each ticket
		if($idChange) {
			continue;
		}

		try {
			$res1 = $db->prepare($query1);
			$res1->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
	if (!$first) {        
		beneishTTM($pid,$rawdata,$query2);
	}
}

function pioTTM($ppid,$prawdata,$querypre,$pprawdata) {
	$db = Database::GetInstance();
	$queryqtr = "SELECT * FROM reports_header where report_type='QTR' and ticker_id = $ppid order by fiscal_year desc, fiscal_quarter desc limit 1";
	try {
		$resqtr = $db->query($queryqtr);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	$rowqtr = $resqtr->fetch(PDO::FETCH_ASSOC);
	if ($rowqtr["fiscal_year"] == $prawdata["fiscal_year"] && $rowqtr["fiscal_quarter"] == $prawdata["fiscal_quarter"]) {
		$query1 = "INSERT INTO `ttm_pio_checks` (`ticker_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$params = $querypre;
		array_unshift($params, $ppid);
		try {
			$res = $db->prepare($query1);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	} else {
		$total = 0;
		$tquery = "SELECT * FROM `ttm_balanceconsolidated` a, ttm_balancefull b, ttm_cashflowconsolidated c, ttm_cashflowfull d, ttm_financialscustom e, ttm_incomeconsolidated f, ttm_incomefull g, ttm_gf_data h WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id=d.ticker_id AND a.ticker_id=e.ticker_id AND a.ticker_id=f.ticker_id AND a.ticker_id=g.ticker_id and a.ticker_id=h.ticker_id and a.ticker_id = $ppid";
		try {
			$tres = $db->query($tquery);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		$trawdata = $tres->fetch(PDO::FETCH_ASSOC);
		$qquote = "SELECT * FROM tickers_yahoo_quotes_2 WHERE ticker_id = '$ppid'";
                try {
                        $rquote = $db->query($qquote);
                } catch(PDOException $ex) {
                        echo "\nDatabase Error"; //user message
                        die("Line: ".__LINE__." - ".$ex->getMessage());
                }
		$row_count = $rquote->rowCount();
		if($row_count > 0) {
			$pricerow  = $rquote->fetch(PDO::FETCH_ASSOC);
			$trawdata["SharesOutstandingDiluted"] = max($trawdata["SharesOutstandingDiluted"], $pricerow["SharesOutstanding"]/1000000, $pricerow["SharesOutstandingBC"]/1000000);
		}
		$query1 = "INSERT INTO `ttm_pio_checks` (`ticker_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$params = array();
		$params[] = $ppid;
		//Pio 1
		$value = (!is_null($trawdata["IncomebeforeExtraordinaryItems"]) && $trawdata["IncomebeforeExtraordinaryItems"] >= 0 ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 2
		$value = (!is_null($trawdata["CashfromOperatingActivities"]) && $trawdata["CashfromOperatingActivities"] >= 0 ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 3
		$vn = (is_null($trawdata["TotalAssets"]) || $trawdata["TotalAssets"] == 0) ? 0 : ($trawdata["IncomebeforeExtraordinaryItems"]/$trawdata["TotalAssets"]);
		$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($prawdata["IncomebeforeExtraordinaryItems"]/$prawdata["TotalAssets"]);
		$value = ($vn >= $vv ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 4
		$value = ($trawdata["CashfromOperatingActivities"] >= $trawdata["IncomebeforeExtraordinaryItems"] ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 5
		$vn = (($trawdata["TotalAssets"]+$prawdata["TotalAssets"]) == 0) ? 0 : (($trawdata["TotalLongtermDebt"])/(($trawdata["TotalAssets"]+$prawdata["TotalAssets"])/2));
                if ($pprawdata["ticker_id"] == $prawdata["ticker_id"]) {
                        $vv = (($prawdata["TotalAssets"]+$pprawdata["TotalAssets"]) == 0) ? 0 : (($prawdata["TotalLongtermDebt"])/(($prawdata["TotalAssets"]+$pprawdata["TotalAssets"])/2));
                } else {
                        $vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : (($prawdata["TotalLongtermDebt"])/$prawdata["TotalAssets"]);
                }
		$value = ($vn <= $vv ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 6
		$vn = (is_null($trawdata["TotalCurrentLiabilities"]) || $trawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($trawdata["TotalCurrentAssets"]/$trawdata["TotalCurrentLiabilities"]);
		$vv = (is_null($prawdata["TotalCurrentLiabilities"]) || $prawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($prawdata["TotalCurrentAssets"]/$prawdata["TotalCurrentLiabilities"]);
		$value = ($vn >= $vv ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 7
		$value = (toFloat($trawdata["SharesOutstandingDiluted"]) <= toFloat($prawdata["SharesOutstandingDiluted"]) ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 8
		$vn = (is_null($trawdata["TotalRevenue"]) || $trawdata["TotalRevenue"] == 0) ? 0 : ($trawdata["GrossProfit"]/$trawdata["TotalRevenue"]);
		$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? 0 : ($prawdata["GrossProfit"]/$prawdata["TotalRevenue"]);
		$value = ($vn >= $vv ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		//Pio 9
		$vn = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($trawdata["TotalRevenue"]/$prawdata["TotalAssets"]);
                if($pprawdata["ticker_id"] == $prawdata["ticker_id"]) {
                        $vv = (is_null($pprawdata["TotalAssets"]) || $pprawdata["TotalAssets"] == 0) ? 0 : ($prawdata["TotalRevenue"]/$pprawdata["TotalAssets"]);
                } else {
                        $vv = 0;
                }
		$value = ($vn >= $vv ? 1 : 0);
		$total += $value;
		$params[] = ($value);
		$params[] = ($total);
		$query = $params;
		array_shift($query);
		try {
			$res1 = $db->prepare($query1);
			$res1->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
}

function altmanTTM($ppid) {
	$db = Database::GetInstance();
	$tquery = "SELECT * FROM ttm_incomeconsolidated a, ttm_balanceconsolidated b, ttm_gf_data c WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id= " . $ppid;
	try {
		$tres = $db->query($tquery);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	$trawdata = $tres->fetch(PDO::FETCH_ASSOC);
	array_walk_recursive($trawdata, 'nullValues');
	$qquote = "SELECT * FROM tickers_yahoo_quotes_2 WHERE ticker_id = '$ppid'";
        try {
                $rquote = $db->query($qquote);
        } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
        }
	$row_count = $rquote->rowCount();
	if($row_count > 0) {
		$pricerow = $rquote->fetch(PDO::FETCH_ASSOC);
		$trawdata["SharesOutstandingDiluted"] = max($trawdata["SharesOutstandingDiluted"], $pricerow["SharesOutstanding"]/1000000, $pricerow["SharesOutstandingBC"]/1000000);
	}
	$query1 = "INSERT INTO `ttm_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `SharesOutstandingDiluted`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//12
	$params = array();
	$params[] = $ppid;
	$params[] = ($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"]);
	$params[] = ($trawdata["TotalAssets"] =='null' ? null:$trawdata["TotalAssets"]);
	$params[] = ($trawdata["TotalLiabilities"] =='null' ? null:$trawdata["TotalLiabilities"]);
	$params[] = ($trawdata["RetainedEarnings"] =='null' ? null:$trawdata["RetainedEarnings"]);
	$params[] = ($trawdata["EBIT"] =='null' ? null:$trawdata["EBIT"]);
	$params[] = toFloat($trawdata["SharesOutstandingDiluted"]) * 1000000;
	$params[] = ($trawdata["TotalRevenue"] =='null' ? null:$trawdata["TotalRevenue"]);
	$x1 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? (($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"])/$trawdata["TotalAssets"]) : null);
	$x2 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["RetainedEarnings"]/$trawdata["TotalAssets"]) : null);
	$x3 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["EBIT"]/$trawdata["TotalAssets"]) : null);
	$x5 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["TotalRevenue"]/$trawdata["TotalAssets"]) : null);
	$params[] = $x1;
	$params[] = $x2;
	$params[] = $x3;
	$params[] = $x5;
	try {
		$res1 = $db->prepare($query1);
		$res1->execute($params);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}

	//Update MRQ Data
	$tquery = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_balanceconsolidated c, reports_gf_data d WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.ticker_id= " . $ppid . " AND report_type='QTR' order by fiscal_year desc, fiscal_quarter desc limit 1";
	try {
		$tres = $db->query($tquery);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	$trawdata = $tres->fetch(PDO::FETCH_ASSOC);
	array_walk_recursive($trawdata, 'nullValues');
	$query1 = "INSERT INTO `mrq_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//11
	$params = array();
	$params[] = $ppid;
	$params[] = ($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"]);
	$params[] = ($trawdata["TotalAssets"] =='null' ? null:$trawdata["TotalAssets"]);
	$params[] = ($trawdata["TotalLiabilities"] =='null' ? null:$trawdata["TotalLiabilities"]);
	$params[] = ($trawdata["RetainedEarnings"] =='null' ? null:$trawdata["RetainedEarnings"]);
	$params[] = ($trawdata["EBIT"] =='null' ? null:$trawdata["EBIT"]);
	$params[] = ($trawdata["TotalRevenue"] =='null' ? null:$trawdata["TotalRevenue"]);
	$x1 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? (($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"])/$trawdata["TotalAssets"]) : null);
	$x2 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["RetainedEarnings"]/$trawdata["TotalAssets"]) : null);
	$x3 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["EBIT"]/$trawdata["TotalAssets"]) : null);
	$x5 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["TotalRevenue"]/$trawdata["TotalAssets"]) : null);
	$params[] = $x1;
	$params[] = $x2;
	$params[] = $x3;
	$params[] = $x5;
	try {
		$res1 = $db->prepare($query1);
		$res1->execute($params);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
}

function beneishTTM($ppid,$prawdata,$querypre) {
	$db = Database::GetInstance();
	$queryqtr = "SELECT * FROM reports_header where report_type='QTR' and ticker_id = $ppid order by fiscal_year desc, fiscal_quarter desc limit 1";
	try {
		$resqtr = $db->query($queryqtr);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	$rowqtr = $resqtr->fetch(PDO::FETCH_ASSOC);
	if ($rowqtr["fiscal_year"] == $prawdata["fiscal_year"] && $rowqtr["fiscal_quarter"] == $prawdata["fiscal_quarter"]) {
		$query1 = "INSERT INTO `ttm_beneish_checks` (`ticker_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$params = $querypre;
		array_unshift($params, $ppid);
		try {
			$res1 = $db->prepare($query1);
			$res1->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	} else {
		$tquery = "SELECT * FROM ttm_incomeconsolidated a, ttm_cashflowconsolidated b, ttm_balanceconsolidated c WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id= " . $ppid;
		try {
			$tres = $db->query($tquery);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		$rawdata = $tres->fetch(PDO::FETCH_ASSOC);
		$query1 = "INSERT INTO `ttm_beneish_checks` (`ticker_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//11
		$params = array();
		$params[] = $ppid;
		//DSRI
		$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : ($rawdata["TotalReceivablesNet"]/$rawdata["TotalRevenue"]);
		$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($prawdata["TotalReceivablesNet"]/$prawdata["TotalRevenue"]);
		$dsri = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $dsri;
		//GMI
		$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : (($rawdata["TotalRevenue"]-$rawdata["CostofRevenue"])/$rawdata["TotalRevenue"]);
		$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : (($prawdata["TotalRevenue"]-$prawdata["CostofRevenue"])/$prawdata["TotalRevenue"]);
		$gmi = ((is_null($vn) || $vn == 0) ? null : ($vv/$vn));
		$params[] = $gmi;
		//AQI
		$vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["TotalAssets"]-$rawdata["PropertyPlantEquipmentNet"]-$rawdata["TotalCurrentAssets"])/$rawdata["TotalAssets"]);
		$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? null : (($prawdata["TotalAssets"]-$prawdata["PropertyPlantEquipmentNet"]-$prawdata["TotalCurrentAssets"])/$prawdata["TotalAssets"]);
		$aqi = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $aqi;
		//SGI
		$sgi = ((is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($rawdata["TotalRevenue"]/$prawdata["TotalRevenue"]));
		$params[] = $sgi;
		//DEPI
		$vn = ((is_null($rawdata["CFDepreciationAmortization"]) && is_null($rawdata["PropertyPlantEquipmentNet"])) || ($rawdata["CFDepreciationAmortization"]+$rawdata["PropertyPlantEquipmentNet"]) == 0) ? null : ($rawdata["CFDepreciationAmortization"]/($rawdata["CFDepreciationAmortization"]+$rawdata["PropertyPlantEquipmentNet"]));
		$vv = ((is_null($prawdata["CFDepreciationAmortization"]) && is_null($prawdata["PropertyPlantEquipmentNet"])) || ($prawdata["CFDepreciationAmortization"]+$prawdata["PropertyPlantEquipmentNet"]) == 0) ? null : ($prawdata["CFDepreciationAmortization"]/($prawdata["CFDepreciationAmortization"]+$prawdata["PropertyPlantEquipmentNet"]));
		$depi = ((is_null($vn) || $vn == 0) ? null : ($vv/$vn));
		$params[] = $depi;
		//SGAI
		$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : ($rawdata["SellingGeneralAdministrativeExpenses"]/$rawdata["TotalRevenue"]);
		$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($prawdata["SellingGeneralAdministrativeExpenses"]/$prawdata["TotalRevenue"]);
		$sgai = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $sgai;
		//TATA
		$tata = ((is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["IncomebeforeExtraordinaryItems"]-$rawdata["CashfromOperatingActivities"])/$rawdata["TotalAssets"]));
		$params[] = $tata;
		//LVGI
		$vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["TotalCurrentLiabilities"]+$rawdata["TotalLongtermDebt"])/$rawdata["TotalAssets"]);
		$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? null : (($prawdata["TotalCurrentLiabilities"]+$prawdata["TotalLongtermDebt"])/$prawdata["TotalAssets"]);
		$lvgi = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
		$params[] = $lvgi;
		//BM5
		$bm5 = -6.065+(0.823*($dsri == 'null'?0:$dsri))+(0.906*($gmi=='null'?0:$gmi))+(0.593*($aqi=='null'?0:$aqi))+(0.717*($sgi=='null'?0:$sgi))+(0.107*($depi=='null'?0:$depi));
		$params[] = $bm5;
		//BM8
		$bm8 = -4.84+(0.92*($dsri == 'null'?0:$dsri))+(0.528*($gmi=='null'?0:$gmi))+(0.404*($aqi=='null'?0:$aqi))+(0.892*($sgi=='null'?0:$sgi))+(0.115*($depi=='null'?0:$depi))-(0.172*($sgai=='null'?0:$sgai))+(4.679*($tata=='null'?0:$tata))-(0.327*($lvgi=='null'?0:$lvgi));
		$params[] = $bm8;
		$query2 = $params;
		array_shift($query2);
		try {
			$res1 = $db->prepare($query1);
			$res1->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
}

?>
