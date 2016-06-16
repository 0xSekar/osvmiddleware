<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout

	$query = "delete from reports_alt_checks";
	$res = mysql_query($query) or die (mysql_error());
        $query = "DELETE from ttm_alt_checks";
        mysql_query($query) or die (mysql_error());
        $query = "DELETE from mrq_alt_checks";
        mysql_query($query) or die (mysql_error());

	$query = "SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year";
	$res = mysql_query($query) or die (mysql_error());
        $pid = 0;
	$ppid = 0;
        $idChange = true;
	$first = true;
        while ($row = mysql_fetch_assoc($res)) {
                if ($row["ticker_id"] != $pid) {
			$ppid = $pid;
                        $pid = $row["ticker_id"];
                        $idChange = true;
                } else {
			$first = false;
                        $idChange = false;
                }
		$query = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_balanceconsolidated c, reports_gf_data d WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id= " . $row["id"];
		$res2 = mysql_query($query) or die (mysql_error());
		$rawdata = mysql_fetch_assoc($res2);
		array_walk_recursive($rawdata, 'nullValues');

		$qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$row["ticker_id"]."' and report_date <= '".$rawdata["report_date"]."' order by report_date desc limit 1";
                $price = null;
                $rquote = mysql_query($qquote) or die (mysql_error());
                if(mysql_num_rows($rquote) > 0) {
                	$price = mysql_fetch_assoc($rquote);
                        $price = $price["adj_close"];
                }

		$query1 = "INSERT INTO `reports_alt_checks` (`report_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `MarquetValueofEquity`, `NetSales`, `X1`, `X2`, `X3`, `X4`, `X5`, `AltmanZNormal`, `AltmanZRevised`) VALUES (";
                $query1 .= "'".$rawdata["id"]."',";
		$query1 .= ($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]) . ",";
		$query1 .= $rawdata["TotalAssets"] . ",";
		$query1 .= $rawdata["TotalLiabilities"] . ",";
		$query1 .= $rawdata["RetainedEarnings"] . ",";
		$query1 .= $rawdata["EBIT"] . ",";
		$query1 .= $price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000 . ",";
		$query1 .= $rawdata["TotalRevenue"] . ",";
		$x1 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? (($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"])/$rawdata["TotalAssets"]) : 'null');
		$x2 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["RetainedEarnings"]/$rawdata["TotalAssets"]) : 'null');
		$x3 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["EBIT"]/$rawdata["TotalAssets"]) : 'null');
		$x4 = ($rawdata["TotalLiabilities"] !== 'null' && $rawdata["TotalLiabilities"] != 0 ? (($price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000)/$rawdata["TotalLiabilities"]) : 'null');
		$x5 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["TotalRevenue"]/$rawdata["TotalAssets"]) : 'null');
		$query1 .= $x1 . "," . $x2 . "," . $x3 . "," . $x4 . "," . $x5 . ",";
		$query1 .= (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null' && $x5 !== 'null') ? (1.2*$x1+1.4*$x2+3.3*$x3+0.6*$x4+0.999*$x5) : 'null') . ",";
		$query1 .= (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null') ? (6.56*$x1+3.26*$x2+6.72*$x3+1.05*$x4) : 'null');
                $query1 .= ")";
		mysql_query($query1) or die (mysql_error());

		//Update TTM Data
		if($idChange && !$first) {
			altmanTTM($ppid);
		}
	}
	altmanTTM($pid);

function altmanTTM($ppid) {
        $tquery = "SELECT * FROM ttm_incomeconsolidated a, ttm_balanceconsolidated b, ttm_gf_data c WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id= " . $ppid;
        $tres = mysql_query($tquery) or die (mysql_error());
        $trawdata = mysql_fetch_assoc($tres);
        array_walk_recursive($trawdata, 'nullValues');
        $query1 = "INSERT INTO `ttm_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `SharesOutstandingDiluted`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (";
        $query1 .= "'".$ppid."',";

        $query1 .= ($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"]) . ",";
        $query1 .= $trawdata["TotalAssets"] . ",";
        $query1 .= $trawdata["TotalLiabilities"] . ",";
        $query1 .= $trawdata["RetainedEarnings"] . ",";
        $query1 .= $trawdata["EBIT"] . ",";
        $query1 .= toFloat($trawdata["SharesOutstandingDiluted"]) * 1000000 . ",";
        $query1 .= $trawdata["TotalRevenue"] . ",";
        $x1 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? (($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"])/$trawdata["TotalAssets"]) : 'null');
        $x2 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["RetainedEarnings"]/$trawdata["TotalAssets"]) : 'null');
        $x3 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["EBIT"]/$trawdata["TotalAssets"]) : 'null');
        $x5 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["TotalRevenue"]/$trawdata["TotalAssets"]) : 'null');
        $query1 .= $x1 . "," . $x2 . "," . $x3 . "," . $x5;
        $query1 .= ")";
        mysql_query($query1) or die (mysql_error());
        //Update MRQ Data

        $tquery = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_balanceconsolidated c, reports_gf_data d WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.ticker_id= " . $ppid . " AND report_type='QTR' order by fiscal_year desc, fiscal_quarter desc limit 1";
        $tres = mysql_query($tquery) or die (mysql_error());
        $trawdata = mysql_fetch_assoc($tres);
        array_walk_recursive($trawdata, 'nullValues');
        $query1 = "INSERT INTO `mrq_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (";
        $query1 .= "'".$ppid."',";
        $query1 .= ($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"]) . ",";
        $query1 .= $trawdata["TotalAssets"] . ",";
        $query1 .= $trawdata["TotalLiabilities"] . ",";
        $query1 .= $trawdata["RetainedEarnings"] . ",";
        $query1 .= $trawdata["EBIT"] . ",";
        $query1 .= $trawdata["TotalRevenue"] . ",";
        $x1 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? (($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"])/$trawdata["TotalAssets"]) : 'null');
        $x2 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["RetainedEarnings"]/$trawdata["TotalAssets"]) : 'null');
        $x3 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["EBIT"]/$trawdata["TotalAssets"]) : 'null');
        $x5 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["TotalRevenue"]/$trawdata["TotalAssets"]) : 'null');
        $query1 .= $x1 . "," . $x2 . "," . $x3 . "," . $x5;
        $query1 .= ")";
        mysql_query($query1) or die ($query1.mysql_error());
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
function nullValues(&$item, $key) {
        if(strlen(trim($item)) == 0) {
                $item = 'null';
        } else if($item == "-") {
                $item = 'null';
        }
}

?>
