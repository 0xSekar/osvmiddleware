<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/db.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
$query = "delete from reports_beneish_checks";
try {
        $db->exec($query);
} catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
}
$query = "DELETE from ttm_beneish_checks";
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
            die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $rawdata = $res2->fetch(PDO::FETCH_ASSOC);

	//Update TTM Data
	if($idChange && !$first) {
		beneishTTM($ppid,$prawdata,$querypre);
	}
	//Skip calculations for first report of each ticket
	if($idChange) {
		continue;
	}
    $query1 = "INSERT INTO `reports_beneish_checks` (`report_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
    try {
            $res1 = $db->prepare($query1);
            $res1->execute($params);
    } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
    }

}
beneishTTM($pid,$rawdata,$query2);

function beneishTTM($ppid,$prawdata,$querypre) {
    $queryqtr = "SELECT * FROM reports_header where report_type='QTR' and ticker_id = $ppid order by fiscal_year desc, fiscal_quarter desc limit 1";
    try {
        $resqtr = $db->query($queryqtr);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $rawqtr = $resqtr->fetch(PDO::FETCH_ASSOC);
    if ($rowqtr["fiscal_year"] == $prawdata["fiscal_year"] && $rowqtr["fiscal_quarter"] == $prawdata["fiscal_quarter"]) {
            $query1 = "INSERT INTO `ttm_beneish_checks` (`ticker_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = $querypre;
            array_unshift($params, $ppid);
            try {
                    $res = $db->prepare($query1);
                    $res->execute($params);
            } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("- Line: ".__LINE__." - ".$ex->getMessage());
            }
    } else {
	        $tquery = "SELECT * FROM ttm_incomeconsolidated a, ttm_cashflowconsolidated b, ttm_balanceconsolidated c WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id= " . $ppid;            
            try {
                    $tres = $db->query($tquery);
            } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("- Line: ".__LINE__." - ".$ex->getMessage());
            }
            $rawdata = $tres->fetch(PDO::FETCH_ASSOC);
	        $query1 = "INSERT INTO `ttm_beneish_checks` (`ticker_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
                    $res = $db->prepare($query1);
                    $res->execute($params);
            } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("- Line: ".__LINE__." - ".$ex->getMessage());
            }
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
