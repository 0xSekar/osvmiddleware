<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout
	$query = "delete from reports_pio_checks";
	$res = mysql_query($query) or die (mysql_error());
        $query = "DELETE from ttm_pio_checks";
        mysql_query($query) or die (mysql_error());

	$query = "SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year";
	$res = mysql_query($query) or die (mysql_error());
        $pid = 0;
	$ppid = 0;
        $idChange = true;
	$first = true;
        while ($row = mysql_fetch_assoc($res)) {
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
		$res2 = mysql_query($query) or die (mysql_error());
		$rawdata = mysql_fetch_assoc($res2);

		$query1 = "INSERT INTO `reports_pio_checks` (`report_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (";
                $query1 .= "'".$row["id"]."',";
		//Pio 1
		$value = (!is_null($rawdata["IncomebeforeExtraordinaryItems"]) && $rawdata["IncomebeforeExtraordinaryItems"] >= 0 ? 1 : 0);
		$total += $value;
		$query2 = "'".($value)."',";
		//Pio 2
		$value = (!is_null($rawdata["CashfromOperatingActivities"]) && $rawdata["CashfromOperatingActivities"] >= 0 ? 1 : 0);
		$total += $value;
		$query2 .= "'".($value)."',";
		//Pio 3
		if($idChange) {
			if(is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) {
				$query2 .= "'0',";
			} else {
				$value = (($rawdata["IncomebeforeExtraordinaryItems"]/$rawdata["TotalAssets"]) >= 0 ? 1 : 0);
				$total += $value;
				$query2 .= "'".($value)."',";
			}
		} else {
			$vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? 0 : ($rawdata["IncomebeforeExtraordinaryItems"]/$rawdata["TotalAssets"]);
			$vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($prawdata["IncomebeforeExtraordinaryItems"]/$prawdata["TotalAssets"]);
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$query2 .= "'".($value)."',";
		}
		//Pio 4
		$value = ($rawdata["CashfromOperatingActivities"] >= $rawdata["IncomebeforeExtraordinaryItems"] ? 1 : 0);
		$total += $value;
		$query2 .= "'".($value)."',";

		if($idChange) {
			//Pio 5
			if(is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) {
				$query2 .= "'1',";
				$total++;
			} else {
				$value = ((($rawdata["TotalLongtermDebt"])/$rawdata["TotalAssets"]) >= 0 ? 1 : 0);
				$total += $value;
				$query2 .= "'".($value)."',";
			}
			//Pio 6
			if(is_null($rawdata["TotalCurrentLiabilities"]) || $rawdata["TotalCurrentLiabilities"] == 0) {
				$query2 .= "'0',";
			} else {
				$value = (($rawdata["TotalCurrentAssets"]/$rawdata["TotalCurrentLiabilities"]) > 0.5 ? 1 : 0);
				$total += $value;
				$query2 .= "'".($value)."',";
			}
			//Pio 7, 8, 9
			$query2 .= "'0','0','0',";
		} else {
			//Pio 5
			$vn = (($rawdata["TotalAssets"]+$prawdata["TotalAssets"]) == 0) ? 0 : (($rawdata["TotalLongtermDebt"])/(($rawdata["TotalAssets"]+$prawdata["TotalAssets"])/2));
			if ($pprawdata["ticker_id"] == $prawdata["ticker_id"]) {
				$vv = (($prawdata["TotalAssets"]+$pprawdata["TotalAssets"]) == 0) ? 0 : (($prawdata["TotalLongtermDebt"])/(($prawdata["TotalAssets"]+$pprawdata["TotalAssets"])/2));
			} else {
				$vv = (is_null($prawdata["TotalAssets"]) || $pprawdata["TotalAssets"] == 0) ? 0 : (($prawdata["TotalLongtermDebt"])/$prawdata["TotalAssets"]);
			}
			$value = ($vn <= $vv ? 1 : 0);
			$total += $value;
			$query2 .= "'".($value)."',";
			//Pio 6
			$vn = (is_null($rawdata["TotalCurrentLiabilities"]) || $rawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($rawdata["TotalCurrentAssets"]/$rawdata["TotalCurrentLiabilities"]);
			$vv = (is_null($prawdata["TotalCurrentLiabilities"]) || $prawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($prawdata["TotalCurrentAssets"]/$prawdata["TotalCurrentLiabilities"]);
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$query2 .= "'".($value)."',";
			//Pio 7
			$value = (toFloat($rawdata["SharesOutstandingDiluted"]) <= toFloat($prawdata["SharesOutstandingDiluted"]) ? 1 : 0);
			$total += $value;
			$query2 .= "'".($value)."',";
			//Pio 8
			$vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? 0 : ($rawdata["GrossProfit"]/$rawdata["TotalRevenue"]);
			$vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? 0 : ($prawdata["GrossProfit"]/$prawdata["TotalRevenue"]);
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$query2 .= "'".($value)."',";
			//Pio 9
			$vn = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($rawdata["TotalRevenue"]/$prawdata["TotalAssets"]);
			if($pprawdata["ticker_id"] == $prawdata["ticker_id"]) {
				$vv = (is_null($pprawdata["TotalAssets"]) || $pprawdata["TotalAssets"] == 0) ? 0 : ($prawdata["TotalRevenue"]/$pprawdata["TotalAssets"]);
			} else {
				$vv = 0;
			}
			$value = ($vn >= $vv ? 1 : 0);
			$total += $value;
			$query2 .= "'".($value)."',";
		}
		$query2 .= "'".($total)."'";
                $query2 .= ")";
		mysql_query($query1.$query2) or die (mysql_error());

		//Update TTM Data
		if($idChange && !$first) {
			pioTTM($ppid,$prawdata,$querypre,$pprawdata);
		}
	}
	pioTTM($pid,$rawdata,$query2,$prawdata);

function pioTTM($ppid,$prawdata,$querypre,$pprawdata) {
        $queryqtr = "SELECT * FROM reports_header where report_type='QTR' and ticker_id = $ppid order by fiscal_year desc, fiscal_quarter desc limit 1";
        $resqtr = mysql_query($queryqtr) or die (mysql_error());
        $rowqtr =  mysql_fetch_assoc($resqtr);
        if ($rowqtr["fiscal_year"] == $prawdata["fiscal_year"] && $rowqtr["fiscal_quarter"] == $prawdata["fiscal_quarter"]) {
                $query1 = "INSERT INTO `ttm_pio_checks` (`ticker_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (";
                $query1 .= "'".$ppid."',";
                mysql_query($query1.$querypre) or die (mysql_error());
        } else {
                $total = 0;

                $tquery = "SELECT * FROM `ttm_balanceconsolidated` a, ttm_balancefull b, ttm_cashflowconsolidated c, ttm_cashflowfull d, ttm_financialscustom e, ttm_incomeconsolidated f, ttm_incomefull g, ttm_gf_data h WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id=d.ticker_id AND a.ticker_id=e.ticker_id AND a.ticker_id=f.ticker_id AND a.ticker_id=g.ticker_id and a.ticker_id=h.ticker_id and a.ticker_id = $ppid";
                $tres = mysql_query($tquery) or die (mysql_error());
                $trawdata = mysql_fetch_assoc($tres);
                $query1 = "INSERT INTO `ttm_pio_checks` (`ticker_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (";
                $query1 .= "'".$ppid."',";
                //Pio 1
                $value = (!is_null($trawdata["IncomebeforeExtraordinaryItems"]) && $trawdata["IncomebeforeExtraordinaryItems"] >= 0 ? 1 : 0);
                $total += $value;
                $query = "'".($value)."',";
                //Pio 2
                $value = (!is_null($trawdata["CashfromOperatingActivities"]) && $trawdata["CashfromOperatingActivities"] >= 0 ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                //Pio 3
                $vn = (is_null($trawdata["TotalAssets"]) || $trawdata["TotalAssets"] == 0) ? 0 : ($trawdata["IncomebeforeExtraordinaryItems"]/$trawdata["TotalAssets"]);
                $vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($prawdata["IncomebeforeExtraordinaryItems"]/$prawdata["TotalAssets"]);
                $value = ($vn >= $vv ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                //Pio 4
                $value = ($trawdata["CashfromOperatingActivities"] >= $trawdata["IncomebeforeExtraordinaryItems"] ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                //Pio 5
                $vn = (($trawdata["TotalAssets"]+$prawdata["TotalAssets"]) == 0) ? 0 : (($trawdata["TotalLongtermDebt"])/(($trawdata["TotalAssets"]+$prawdata["TotalAssets"])/2));
                $vv = (($prawdata["TotalAssets"]+$pprawdata["TotalAssets"]) == 0) ? 0 : (($prawdata["TotalLongtermDebt"])/(($prawdata["TotalAssets"]+$pprawdata["TotalAssets"])/2));
                $value = ($vn <= $vv ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                //Pio 6
                $vn = (is_null($trawdata["TotalCurrentLiabilities"]) || $trawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($trawdata["TotalCurrentAssets"]/$trawdata["TotalCurrentLiabilities"]);
                $vv = (is_null($prawdata["TotalCurrentLiabilities"]) || $prawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($prawdata["TotalCurrentAssets"]/$prawdata["TotalCurrentLiabilities"]);
                $value = ($vn >= $vv ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                //Pio 7
                $value = (toFloat($trawdata["SharesOutstandingDiluted"]) <= toFloat($prawdata["SharesOutstandingDiluted"]) ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                //Pio 8
                $vn = (is_null($trawdata["TotalRevenue"]) || $trawdata["TotalRevenue"] == 0) ? 0 : ($trawdata["GrossProfit"]/$trawdata["TotalRevenue"]);
                $vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? 0 : ($prawdata["GrossProfit"]/$prawdata["TotalRevenue"]);
                $value = ($vn >= $vv ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                //Pio 9
                $vn = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? 0 : ($trawdata["TotalRevenue"]/$prawdata["TotalAssets"]);
                $vv = (is_null($pprawdata["TotalAssets"]) || $pprawdata["TotalAssets"] == 0) ? 0 : ($prawdata["TotalRevenue"]/$pprawdata["TotalAssets"]);
                $value = ($vn >= $vv ? 1 : 0);
                $total += $value;
                $query .= "'".($value)."',";
                $query .= "'".($total)."'";
                $query .= ")";
                mysql_query($query1.$query) or die (mysql_error());
        }
}

function toFloat($num) {
    if (is_null($num)) {
        return 'null';
    }

    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

    if (!$sep) {
        return floatval(preg_replace("/[^\-0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^\-0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^\-0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}

?>
