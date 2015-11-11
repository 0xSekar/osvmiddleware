<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout
	$query = "delete from reports_quality_checks";
	$res = mysql_query($query) or die (mysql_error());
	$query = "SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year";
	$res = mysql_query($query) or die (mysql_error());
        $pid = 0;
        $idChange = true;
        while ($row = mysql_fetch_assoc($res)) {
		$total = 0;
		$value = 0;
                if ($row["ticker_id"] != $pid) {
                        $pid = $row["ticker_id"];
                        $idChange = true;
                } else {
                        $prawdata = $rawdata;
                        $idChange = false;
                }
		$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.id= " . $row["id"];
		$res2 = mysql_query($query) or die (mysql_error());
		$rawdata = mysql_fetch_assoc($res2);

		$query = "INSERT INTO `reports_quality_checks` (`report_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (";
                $query .= "'".$row["id"]."',";
		//Pio 1
		$value = ($rawdata["NetIncome"] > 0 ? 1 : 0);
		$total += $value;
		$query .= "'".($value)."',";
		//Pio 2
		$value = ($rawdata["CashfromOperatingActivities"] > 0 ? 1 : 0);
		$total += $value;
		$query .= "'".($value)."',";
		//Pio 3
		if($idChange) {
			if($rawdata["TotalAssets"] == 0) {
				$query .= "'0',";
			} else {
				$value = (($rawdata["NetIncome"]/$rawdata["TotalAssets"]) > 0 ? 1 : 0);
				$total += $value;
				$query .= "'".($value)."',";
			}
		} else {
			$vn = ($rawdata["TotalAssets"] == 0) ? 0 : ($rawdata["NetIncome"]/$rawdata["TotalAssets"]);
			$vv = ($prawdata["TotalAssets"] == 0) ? 0 : ($prawdata["NetIncome"]/$prawdata["TotalAssets"]);
			$value = ($vn > $vv ? 1 : 0);
			$total += $value;
			$query .= "'".($value)."',";
		}
		//Pio 4
		$value = ($rawdata["CashfromOperatingActivities"] > $rawdata["NetIncome"] ? 1 : 0);
		$total += $value;
		$query .= "'".($value)."',";

		if($idChange) {
			//Pio 5
			if($rawdata["TotalAssets"] == 0) {
				$query .= "'1',";
				$total++;
			} else {
				$value = ((($rawdata["TotalLongtermDebt"] + $rawdata["NotesPayable"])/$rawdata["TotalAssets"]) >= 0 ? 1 : 0);
				$total += $value;
				$query .= "'".($value)."',";
			}
			//Pio 6
			if($rawdata["TotalCurrentLiabilities"] == 0) {
				$query .= "'0',";
			} else {
				$value = (($rawdata["TotalCurrentAssets"]/$rawdata["TotalCurrentLiabilities"]) > 0.5 ? 1 : 0);
				$total += $value;
				$query .= "'".($value)."',";
			}
			//Pio 7, 8, 9
			$query .= "'0','0','0',";
		} else {
			//Pio 5
			$vn = ($rawdata["TotalAssets"] == 0) ? 0 : (($rawdata["TotalLongtermDebt"] + $rawdata["NotesPayable"])/$rawdata["TotalAssets"]);
			$vv = ($prawdata["TotalAssets"] == 0) ? 0 : (($prawdata["TotalLongtermDebt"] + $prawdata["NotesPayable"])/$prawdata["TotalAssets"]);
			$value = ($vn <= $vv ? 1 : 0);
			$total += $value;
			$query .= "'".($value)."',";
			//Pio 6
			$vn = ($rawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($rawdata["TotalCurrentAssets"]/$rawdata["TotalCurrentLiabilities"]);
			$vv = ($prawdata["TotalCurrentLiabilities"] == 0) ? 0 : ($prawdata["TotalCurrentAssets"]/$prawdata["TotalCurrentLiabilities"]);
			$value = ($vn > $vv ? 1 : 0);
			$total += $value;
			$query .= "'".($value)."',";
			//Pio 7
			$value = (toFloat($rawdata["SharesOutstandingDiluted"]) < toFloat($prawdata["SharesOutstandingDiluted"]) ? 1 : 0);
			$total += $value;
			$query .= "'".($value)."',";
			//Pio 8
			$vn = ($rawdata["TotalRevenue"] == 0) ? 0 : ($rawdata["GrossProfit"]/$rawdata["TotalRevenue"]);
			$vv = ($prawdata["TotalRevenue"] == 0) ? 0 : ($prawdata["GrossProfit"]/$prawdata["TotalRevenue"]);
			$value = ($vn > $vv ? 1 : 0);
			$total += $value;
			$query .= "'".($value)."',";
			//Pio 9
			$vn = ($rawdata["TotalAssets"] == 0) ? 0 : ($rawdata["TotalRevenue"]/$rawdata["TotalAssets"]);
			$vv = ($prawdata["TotalAssets"] == 0) ? 0 : ($prawdata["TotalRevenue"]/$prawdata["TotalAssets"]);
			$value = ($vn > $vv ? 1 : 0);
			$total += $value;
			$query .= "'".($value)."',";
		}
		$query .= "'".($total)."'";
                $query .= ")";
		mysql_query($query) or die (mysql_error());
	}

function toFloat($num) {
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
