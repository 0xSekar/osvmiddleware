<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/db.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout

	$query = "delete from reports_alt_checks";
    try {
            $db->exec($query);
    } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $query = "DELETE from ttm_alt_checks";
    try {
            $db->exec($query);
    } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $query = "DELETE from mrq_alt_checks";
    try {
            $db->exec($query);
    } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
    }

    $query = "SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year";
    try {
            $res = $db->query($query);
    } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
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
	                die("- Line: ".__LINE__." - ".$ex->getMessage());
	        }
        	$rawdata = $res2->fetch(PDO::FETCH_ASSOC);
		array_walk_recursive($rawdata, 'nullValues');

		$qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$row["ticker_id"]."' and report_date <= '".$rawdata["report_date"]."' order by report_date desc limit 1";
                $price = null;
                try {
                        $rquote =$db->query($qquote);
                } catch(PDOException $ex) {
                        echo "\nDatabase Error"; //user message
                        die("- Line: ".__LINE__." - ".$ex->getMessage());
                }
                if($rowcount = $rquote->rowCount() > 0) {
                    $price = $rquote->fetch(PDO::FETCH_ASSOC);
                    $price = $price["adj_close"];
                }

        $query1 = "INSERT INTO `reports_alt_checks` (`report_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `MarketValueofEquity`, `NetSales`, `X1`, `X2`, `X3`, `X4`, `X5`, `AltmanZNormal`, `AltmanZRevised`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array();
        $params[] = $rawdata["id"];
        $params[] = ($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]);
        $params[] = $rawdata["TotalAssets"];
        $params[] = $rawdata["TotalLiabilities"];
        $params[] = $rawdata["RetainedEarnings"];
        $params[] = $rawdata["EBIT"];
        $params[] = $price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000 ;
        $params[] = $rawdata["TotalRevenue"] ;
		$x1 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? (($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"])/$rawdata["TotalAssets"]) : 'null');
		$x2 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["RetainedEarnings"]/$rawdata["TotalAssets"]) : 'null');
		$x3 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["EBIT"]/$rawdata["TotalAssets"]) : 'null');
		$x4 = ($rawdata["TotalLiabilities"] !== 'null' && $rawdata["TotalLiabilities"] != 0 ? (($price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000)/$rawdata["TotalLiabilities"]) : 'null');
		$x5 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["TotalRevenue"]/$rawdata["TotalAssets"]) : 'null');
        $params[] = $x1;
        $params[] = $x2;
        $params[] = $x3;
        $params[] = $x4;
        $params[] = $x5;
        $params[] = (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null' && $x5 !== 'null') ? (1.2*$x1+1.4*$x2+3.3*$x3+0.6*$x4+0.999*$x5) : 'null');
        $params[] = (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null') ? (6.56*$x1+3.26*$x2+6.72*$x3+1.05*$x4) : 'null');
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
	}
	altmanTTM($pid);

function altmanTTM($ppid) {
        $tquery = "SELECT * FROM ttm_incomeconsolidated a, ttm_balanceconsolidated b, ttm_gf_data c WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id= " . $ppid;
        try {
                $tres = $db->query($tquery);
        } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        $trawdata = $tres->fetch(PDO::FETCH_ASSOC);
        array_walk_recursive($trawdata, 'nullValues');
        $query1 = "INSERT INTO `ttm_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `SharesOutstandingDiluted`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";   
        $params = array();
        $params[] = $ppid;

        $params[] = ($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"]) ;
        $params[] = $trawdata["TotalAssets"] ;
        $params[] = $trawdata["TotalLiabilities"] ;
        $params[] = $trawdata["RetainedEarnings"] ;
        $params[] = $trawdata["EBIT"] ;
        $params[] = toFloat($trawdata["SharesOutstandingDiluted"]) * 1000000 ;
        $params[] = $trawdata["TotalRevenue"] ;
        $x1 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? (($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"])/$trawdata["TotalAssets"]) : 'null');
        $x2 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["RetainedEarnings"]/$trawdata["TotalAssets"]) : 'null');
        $x3 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["EBIT"]/$trawdata["TotalAssets"]) : 'null');
        $x5 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["TotalRevenue"]/$trawdata["TotalAssets"]) : 'null');
        $params[] = $x1;
        $params[] = $x2;
        $params[] = $x3;
        $params[] = $x5;
        
        try {
                $res = $db->prepare($query1);
                $res->execute($params);
        } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        //Update MRQ Data

        $tquery = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_balanceconsolidated c, reports_gf_data d WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.ticker_id= " . $ppid . " AND report_type='QTR' order by fiscal_year desc, fiscal_quarter desc limit 1";
        try {
                $tres =$db->query($tquery);
        } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        $trawdata = $tres->fetch(PDO::FETCH_ASSOC);
        array_walk_recursive($trawdata, 'nullValues');
        $query1 = "INSERT INTO `mrq_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array();
        $params[] = $ppid;
        $params[] = ($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"]) ;
        $params[] = $trawdata["TotalAssets"] ;
        $params[] = $trawdata["TotalLiabilities"] ;
        $params[] = $trawdata["RetainedEarnings"] ;
        $params[] = $trawdata["EBIT"] ;
        $params[] = $trawdata["TotalRevenue"] ;
        $x1 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? (($trawdata["TotalCurrentAssets"] - $trawdata["TotalCurrentLiabilities"])/$trawdata["TotalAssets"]) : 'null');
        $x2 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["RetainedEarnings"]/$trawdata["TotalAssets"]) : 'null');
        $x3 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["EBIT"]/$trawdata["TotalAssets"]) : 'null');
        $x5 = ($trawdata["TotalAssets"] !== 'null' && $trawdata["TotalAssets"] != 0 ? ($trawdata["TotalRevenue"]/$trawdata["TotalAssets"]) : 'null');
        $params[] = $x1;
        $params[] = $x2;
        $params[] = $x3;
        $params[] = $x5;
        try {
                $res = $db->prepare($query1);
                $res->execute($params);
        } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
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
function nullValues(&$item, $key) {
        if(strlen(trim($item)) == 0) {
                $item = 'null';
        } else if($item == "-") {
                $item = 'null';
        }
}

?>
