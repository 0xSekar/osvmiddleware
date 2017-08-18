<?php
function update_pio_checks($ti = null) {
    $db = Database::GetInstance();
    if (is_null($ti)) {
        try {
            $res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    } else {
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
    $max_min = array();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $total = 0;
        $value = 0;
        if ($row["ticker_id"] != $pid) {
            $ppid = $pid;
            $pid = $row["ticker_id"];
            $idChange = true;
            $querypre = $query2;
            $pre_max_min = $max_min;
            $max_min = array();
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
        $query1 = "INSERT INTO `reports_pio_checks` (`report_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `pio1`=?, `pio2`=?, `pio3`=?, `pio4`=?, `pio5`=?, `pio6`=?, `pio7`=?, `pio8`=?, `pio9`=?, `pioTotal`=?"; 
        $params = array();
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
        $max_min[] = $total;
        $params = array_merge($params,$params);
        $query2 = $params;
        array_unshift($params,$row["id"]);

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
            pioMinMax($ppid, $pre_max_min);
        }
        $first = false;
    }

    if (!$first) {
        pioTTM($pid,$rawdata,$query2,$prawdata);
        pioMinMax($pid, $max_min);
    }
}

function update_altman_checks($ti = null) {
    $db = Database::GetInstance();
    if (is_null($ti)) {
        try {
            $res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    } else {
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
    $max_min = array();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        if ($row["ticker_id"] != $pid) {
            $ppid = $pid;
            $pid = $row["ticker_id"];
            $idChange = true;
            $pre_max_min = $max_min;
            $max_min = array();
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

        $query1 = "INSERT INTO `reports_alt_checks` (`report_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `MarketValueofEquity`, `NetSales`, `X1`, `X2`, `X3`, `X4`, `X5`, `AltmanZNormal`, `AltmanZRevised`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `WorkingCapital`=?, `TotalAssets`=?, `TotalLiabilities`=?, `RetainedEarnings`=?, `EBIT`=?, `MarketValueofEquity`=?, `NetSales`=?, `X1`=?, `X2`=?, `X3`=?, `X4`=?, `X5`=?, `AltmanZNormal`=?, `AltmanZRevised`=?";
        $params = array();
        $params[] = ($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]);
        $params[] = ($rawdata["TotalAssets"] =='null' ? null:$rawdata["TotalAssets"]);
        $params[] = ($rawdata["TotalLiabilities"] =='null' ? null:$rawdata["TotalLiabilities"]);
        $params[] =  ($rawdata["RetainedEarnings"] =='null' ? null:$rawdata["RetainedEarnings"]);
        $params[] = ($rawdata["EBIT"] =='null' ? null:$rawdata["EBIT"]);
        $params[] = $price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000;
        $params[] = $rawdata["TotalRevenue"];
        $max_min["x1"][] = $x1 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? (($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"])/$rawdata["TotalAssets"]) : null);
        $max_min["x2"][] = $x2 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["RetainedEarnings"]/$rawdata["TotalAssets"]) : null);
        $max_min["x3"][] = $x3 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["EBIT"]/$rawdata["TotalAssets"]) : null);
        $max_min["x4"][] = $x4 = ($rawdata["TotalLiabilities"] !== 'null' && $rawdata["TotalLiabilities"] != 0 ? (($price * toFloat($rawdata["SharesOutstandingDiluted"]) * 1000000)/$rawdata["TotalLiabilities"]) : null);
        $max_min["x5"][] = $x5 = ($rawdata["TotalAssets"] !== 'null' && $rawdata["TotalAssets"] != 0 ? ($rawdata["TotalRevenue"]/$rawdata["TotalAssets"]) : null);

        $params[] = $x1;
        $params[] = $x2;
        $params[] = $x3;
        $params[] = $x4;
        $params[] = $x5;
        $max_min["AltmanZNormal"][] = $params[] = (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null' && $x5 !== 'null') ? (1.2*$x1+1.4*$x2+3.3*$x3+0.6*$x4+0.999*$x5) : null);
        $max_min["AltmanZRevised"][] = $params[] = (($x1 !== 'null' && $x2 !== 'null' && $x3 !== 'null' && $x4 !== 'null') ? (6.56*$x1+3.26*$x2+6.72*$x3+1.05*$x4) : null);
        $params = array_merge($params,$params);
        array_unshift($params,$row["id"]);

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
            altmanMinMax($ppid, $pre_max_min);
        }
        $first = false;
    }
    if (!$first) {
        altmanTTM($pid);
        altmanMinMax($pid, $max_min);
    }
}

function update_beneish_checks($ti = null) {
    $db = Database::GetInstance();
    if (is_null($ti)) {
        try {
            $res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }                                                                                                                   
    } else {
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
    $max_min = array();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {          
        if ($row["ticker_id"] != $pid) {
            $ppid = $pid;
            $pid = $row["ticker_id"];
            $idChange = true;
            $querypre = $query2;
            $pre_max_min = $max_min;
            $max_min = array();
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
            beneishMinMax($ppid, $pre_max_min);
        }

        $query1 = "INSERT INTO `reports_beneish_checks` (`report_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `DSRI`=?, `GMI`=?, `AQI`=?, `SGI`=?, `DEPI`=?, `SGAI`=?, `TATA`=?, `LVGI`=?, `BM5`=?, `BM8`=?";
        $params = array();
        //DSRI
        $vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : ($rawdata["TotalReceivablesNet"]/$rawdata["TotalRevenue"]);
        $vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($prawdata["TotalReceivablesNet"]/$prawdata["TotalRevenue"]);
        $dsri = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
        $max_min["DSRI"][] = $params[] = $dsri;
        //GMI
        $vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : (($rawdata["TotalRevenue"]-$rawdata["CostofRevenue"])/$rawdata["TotalRevenue"]);
        $vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : (($prawdata["TotalRevenue"]-$prawdata["CostofRevenue"])/$prawdata["TotalRevenue"]);
        $gmi = ((is_null($vn) || $vn == 0) ? null : ($vv/$vn));
        $max_min["GMI"][] = $params[] = $gmi;
        //AQI
        $vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["TotalAssets"]-$rawdata["PropertyPlantEquipmentNet"]-$rawdata["TotalCurrentAssets"])/$rawdata["TotalAssets"]);
        $vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? null : (($prawdata["TotalAssets"]-$prawdata["PropertyPlantEquipmentNet"]-$prawdata["TotalCurrentAssets"])/$prawdata["TotalAssets"]);
        $aqi = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
        $max_min["AQI"][] = $params[] = $aqi;
        //SGI
        $sgi = ((is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($rawdata["TotalRevenue"]/$prawdata["TotalRevenue"]));
        $max_min["SGI"][] = $params[] = $sgi;
        //DEPI
        $vn = ((is_null($rawdata["CFDepreciationAmortization"]) && is_null($rawdata["PropertyPlantEquipmentNet"])) || ($rawdata["CFDepreciationAmortization"]+$rawdata["PropertyPlantEquipmentNet"]) == 0) ? null : ($rawdata["CFDepreciationAmortization"]/($rawdata["CFDepreciationAmortization"]+$rawdata["PropertyPlantEquipmentNet"]));
        $vv = ((is_null($prawdata["CFDepreciationAmortization"]) && is_null($prawdata["PropertyPlantEquipmentNet"])) || ($prawdata["CFDepreciationAmortization"]+$prawdata["PropertyPlantEquipmentNet"]) == 0) ? null : ($prawdata["CFDepreciationAmortization"]/($prawdata["CFDepreciationAmortization"]+$prawdata["PropertyPlantEquipmentNet"]));
        $depi = ((is_null($vn) || $vn == 0) ? null : ($vv/$vn));
        $max_min["DEPI"][] = $params[] = $depi;
        //SGAI
        $vn = (is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"] == 0) ? null : ($rawdata["SellingGeneralAdministrativeExpenses"]/$rawdata["TotalRevenue"]);
        $vv = (is_null($prawdata["TotalRevenue"]) || $prawdata["TotalRevenue"] == 0) ? null : ($prawdata["SellingGeneralAdministrativeExpenses"]/$prawdata["TotalRevenue"]);
        $sgai = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
        $max_min["SGAI"][] = $params[] = $sgai;
        //TATA
        $tata = ((is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["IncomebeforeExtraordinaryItems"]-$rawdata["CashfromOperatingActivities"])/$rawdata["TotalAssets"]));
        $max_min["TATA"][] = $params[] = $tata;
        //LVGI
        $vn = (is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["TotalCurrentLiabilities"]+$rawdata["TotalLongtermDebt"])/$rawdata["TotalAssets"]);
        $vv = (is_null($prawdata["TotalAssets"]) || $prawdata["TotalAssets"] == 0) ? null : (($prawdata["TotalCurrentLiabilities"]+$prawdata["TotalLongtermDebt"])/$prawdata["TotalAssets"]);
        $lvgi = ((is_null($vv) || $vv == 0) ? null : ($vn/$vv));
        $max_min["LVGI"][] = $params[] = $lvgi;
        //BM5
        $bm5 = -6.065+(0.823*($dsri == 'null'?0:$dsri))+(0.906*($gmi=='null'?0:$gmi))+(0.593*($aqi=='null'?0:$aqi))+(0.717*($sgi=='null'?0:$sgi))+(0.107*($depi=='null'?0:$depi));
        $max_min["BM5"][] = $params[] = $bm5;
        //BM8
        $bm8 = -4.84+(0.92*($dsri == 'null'?0:$dsri))+(0.528*($gmi=='null'?0:$gmi))+(0.404*($aqi=='null'?0:$aqi))+(0.892*($sgi=='null'?0:$sgi))+(0.115*($depi=='null'?0:$depi))-(0.172*($sgai=='null'?0:$sgai))+(4.679*($tata=='null'?0:$tata))-(0.327*($lvgi=='null'?0:$lvgi));
        $max_min["BM8"][] = $params[] = $bm8;
        $params = array_merge($params,$params);
        $query2 = $params;
        array_unshift($params,$row["id"]);

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
        beneishMinMax($pid, $max_min);
    }
}

function update_dupont_checks($ti = null) {
    $db = Database::GetInstance();
    if (is_null($ti)) {
        try {
            $res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    } else {
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
        $query = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_balanceconsolidated c WHERE a.id=b.report_id AND a.id=c.report_id AND a.id= " . $row["id"];
        try {
            $res2 = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        $rawdata = $res2->fetch(PDO::FETCH_ASSOC);
        if($rawdata == false || count($rawdata) == 0) {
            continue;
        }
        if ($row["ticker_id"] != $pid) {
            $ppid = $pid;
            $pid = $row["ticker_id"];
            $idChange = true;
        } else {
            $first = false;
            $idChange = false;
        }
        array_walk_recursive($rawdata, 'nullValues');

        $query1 = "INSERT INTO `reports_dupont_checks` (`report_id`, `net_profit_margin`, `asset_turnover`, `equity_multiplier`, `roe_3`, `tax_burden`, `interest_burden`, `operation_income_margin`, `roe_5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `net_profit_margin`=?, `asset_turnover`=?, `equity_multiplier`=?, `roe_3`=?, `tax_burden`=?, `interest_burden`=?, `operation_income_margin`=?, `roe_5`=?";
        $params = array();
        $p1 = ($rawdata["TotalRevenue"] == 'null' || $rawdata["TotalRevenue"] == 0 ? null : ($rawdata["NetIncome"] / $rawdata["TotalRevenue"]));
        $p2 = ($rawdata["TotalAssets"] == 'null' || $rawdata["TotalAssets"] == 0 ? null : ($rawdata["TotalRevenue"] / $rawdata["TotalAssets"]));
        $p3 = ($rawdata["TotalStockholdersEquity"] == 'null' || $rawdata["TotalStockholdersEquity"] == 0 ? null : ($rawdata["TotalAssets"] / $rawdata["TotalStockholdersEquity"]));
        $params[] = $p1;
        $params[] = $p2;
        $params[] = $p3;
        $params[] = (is_null($p1) || is_null($p2) || is_null($p3) ? null : ($p1 * $p2 * $p3));
        $p1_b = ($rawdata["IncomeBeforeTaxes"] == 'null' || $rawdata["IncomeBeforeTaxes"] == 0 ? null : ($rawdata["NetIncome"] / $rawdata["IncomeBeforeTaxes"]));
        $p2_b = ($rawdata["EBIT"] == 'null' || $rawdata["EBIT"] == 0 ? null : ($rawdata["IncomeBeforeTaxes"] / $rawdata["EBIT"]));
        $p3_b = ($rawdata["TotalRevenue"] == 'null' || $rawdata["TotalRevenue"] == 0 ? null : ($rawdata["EBIT"] / $rawdata["TotalRevenue"]));
        $params[] = $p1_b;
        $params[] = $p2_b;
        $params[] = $p3_b;
        $params[] = (is_null($p2) || is_null($p3) || is_null($p1_b) || is_null($p2_b) || is_null($p3_b) ? null : ($p2 * $p3 * $p1_b * $p2_b * $p3_b));
        $params = array_merge($params,$params);
        array_unshift($params,$rawdata["id"]);

        try {
            $res1 = $db->prepare($query1);
            $res1->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        //Update TTM Data
        if($idChange && !$first) {
            dupontTTM($ppid);
        }
        $first = false;
    }
    if (!$first) {
        dupontTTM($pid);
    }
}

function update_accrual_checks($ti = null) {
    $db = Database::GetInstance();
    if (is_null($ti)) {
        try {
            $res = $db->query("SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    } else {
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
    $rawdata = array();
    $query2 = array();
    $max_min = array();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        if ($row["ticker_id"] != $pid) {
            $ppid = $pid;
            $pid = $row["ticker_id"];
            $idChange = true;
            $querypre = $query2;
            $pre_max_min = $max_min;
            $max_min = array();
        } else {
            $first = false;
            $idChange = false;
        }
        $prawdata = $rawdata;
        $query = "SELECT * FROM `reports_header` a, reports_incomeconsolidated b, reports_cashflowconsolidated c, reports_balanceconsolidated d, reports_balancefull e, reports_cashflowfull f, reports_gf_data g WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id= " . $row["id"];
        try {
            $res2 = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        $rawdata = $res2->fetch(PDO::FETCH_ASSOC);

        //Update TTM Data
        if($idChange && !$first) {
            accrualTTM($ppid,$prawdata,$querypre);
            accrualMinMax($ppid, $pre_max_min);
            $prawdata = array();
        }

        $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$row["ticker_id"]."' and report_date <= '".$rawdata["report_date"]."' order by report_date desc limit 1";
        $price = null;
        try {
            $rquote =$db->query($qquote);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        if($rowcount = $rquote->rowCount() > 0) {
            $pricerow = $rquote->fetch(PDO::FETCH_ASSOC);
            $price = $pricerow["adj_close"];
        }

        $query1 = "INSERT INTO `reports_accrual_checks` (`report_id`, `net_operating_assets`, `balance_sheet_aggregate_accrual`, `cash_flow_aggregate_accrual`, `balance_sheet_accrual_ratio`, `cash_flow_accrual_ratio`, `sloan_accrual_ratio`, `stock_price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `net_operating_assets`=?, `balance_sheet_aggregate_accrual`=?, `cash_flow_aggregate_accrual`=?, `balance_sheet_accrual_ratio`=?, `cash_flow_accrual_ratio`=?, `sloan_accrual_ratio`=?, `stock_price`=?";
        $params = array();
        //NOA
        $noa = ($rawdata["TotalAssets"] - $rawdata["CashandCashEquivalents"]) - ($rawdata["TotalLiabilities"] - ($rawdata["CurrentPortionofLongtermDebt"] + $rawdata["ShorttermBorrowings"]) - $rawdata["TotalLongtermDebt"]);
        $noa_v = (empty($prawdata)) ? null : ($prawdata["TotalAssets"] - $prawdata["CashandCashEquivalents"]) - ($prawdata["TotalLiabilities"] - ($prawdata["CurrentPortionofLongtermDebt"] + $prawdata["ShorttermBorrowings"]) - $prawdata["TotalLongtermDebt"]);
        $params[] = $noa;
        //BSAA
        $bsaa = (is_null($noa_v)) ? null : ($noa - $noa_v);
        $max_min["balance_sheet_aggregate_accrual"][] = $params[] = $bsaa;
        //CFAA
        $cfaa = $rawdata["NetIncome"] - ($rawdata["CashfromOperatingActivities"] + $rawdata["CashfromInvestingActivities"]);
        $den = (is_null($noa_v)) ? null : (($noa + $noa_v) / 2);
        $max_min["cash_flow_aggregate_accrual"][] = $params[] = $cfaa;
        //BSAR
        $bsar = ((is_null($den) || $den == 0) ? null : ($bsaa/$den));
        $max_min["balance_sheet_accrual_ratio"][] = $params[] = $bsar;
        //CFAR
        $cfar = ((is_null($den) || $den == 0) ? null : ($cfaa/$den));
        $max_min["cash_flow_accrual_ratio"][] = $params[] = $cfar;
        //SAR
        $sar = ((is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["NetIncome"] - $rawdata["CashfromOperatingActivities"] - $rawdata["CashfromInvestingActivities"])/$rawdata["TotalAssets"]));
        $max_min["sloan_accrual_ratio"][] = $params[] = $sar;
        //price
        $params[] = $price;

        $params = array_merge($params,$params);
        $query2 = $params;
        array_unshift($params,$row["id"]);

        $first = false;
        try {
            $res1 = $db->prepare($query1);
            $res1->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
    }
    if (!$first) {
        accrualTTM($pid, $rawdata, $query2);
        accrualMinMax($pid, $max_min);
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
        $query1 = "INSERT INTO `ttm_pio_checks` (`ticker_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `pio1`=?, `pio2`=?, `pio3`=?, `pio4`=?, `pio5`=?, `pio6`=?, `pio7`=?, `pio8`=?, `pio9`=?, `pioTotal`=?";
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
        $query1 = "INSERT INTO `ttm_pio_checks` (`ticker_id`, `pio1`, `pio2`, `pio3`, `pio4`, `pio5`, `pio6`, `pio7`, `pio8`, `pio9`, `pioTotal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `pio1`=?, `pio2`=?, `pio3`=?, `pio4`=?, `pio5`=?, `pio6`=?, `pio7`=?, `pio8`=?, `pio9`=?, `pioTotal`=?";
        $params = array();
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
        $params = array_merge($params,$params);
        $query2 = $params;
        array_unshift($params,$ppid);

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
    $query1 = "INSERT INTO `ttm_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `SharesOutstandingDiluted`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `WorkingCapital`=?, `TotalAssets`=?, `TotalLiabilities`=?, `RetainedEarnings`=?, `EBIT`=?, `SharesOutstandingDiluted`=?, `NetSales`=?, `X1`=?, `X2`=?, `X3`=?, `X5`=?";
    $params = array();
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
    $params = array_merge($params,$params);
    array_unshift($params,$ppid);

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
    $query1 = "INSERT INTO `mrq_alt_checks` (`ticker_id`, `WorkingCapital`, `TotalAssets`, `TotalLiabilities`, `RetainedEarnings`, `EBIT`, `NetSales`, `X1`, `X2`, `X3`, `X5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `WorkingCapital`=?, `TotalAssets`=?, `TotalLiabilities`=?, `RetainedEarnings`=?, `EBIT`=?, `NetSales`=?, `X1`=?, `X2`=?, `X3`=?, `X5`=?";
    $params = array();
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
    $params = array_merge($params,$params);
    array_unshift($params,$ppid);

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
        $query1 = "INSERT INTO `ttm_beneish_checks` (`ticker_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `DSRI`=?, `GMI`=?, `AQI`=?, `SGI`=?, `DEPI`=?, `SGAI`=?, `TATA`=?, `LVGI`=?, `BM5`=?, `BM8`=?";
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
        $query1 = "INSERT INTO `ttm_beneish_checks` (`ticker_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `DSRI`=?, `GMI`=?, `AQI`=?, `SGI`=?, `DEPI`=?, `SGAI`=?, `TATA`=?, `LVGI`=?, `BM5`=?, `BM8`=?";
        $params = array();
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
        $params = array_merge($params,$params);
        $query2 = $params;
        array_unshift($params,$ppid);

        try {
            $res1 = $db->prepare($query1);
            $res1->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    }
}

function dupontTTM($ppid) {
    $db = Database::GetInstance();
    $tquery = "SELECT * FROM ttm_incomeconsolidated a, ttm_balanceconsolidated b WHERE a.ticker_id=b.ticker_id AND a.ticker_id= " . $ppid;
    try {
        $tres = $db->query($tquery);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $trawdata = $tres->fetch(PDO::FETCH_ASSOC);
    array_walk_recursive($trawdata, 'nullValues');

    $query1 = "INSERT INTO `ttm_dupont_checks` (`ticker_id`, `net_profit_margin`, `asset_turnover`, `equity_multiplier`, `roe_3`, `tax_burden`, `interest_burden`, `operation_income_margin`, `roe_5`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `net_profit_margin`=?, `asset_turnover`=?, `equity_multiplier`=?, `roe_3`=?, `tax_burden`=?, `interest_burden`=?, `operation_income_margin`=?, `roe_5`=?";
    $params = array();

    $p1 = ($trawdata["TotalRevenue"] == 'null' || $trawdata["TotalRevenue"] == 0 ? null : ($trawdata["NetIncome"] / $trawdata["TotalRevenue"]));
    $p2 = ($trawdata["TotalAssets"] == 'null' || $trawdata["TotalAssets"] == 0 ? null : ($trawdata["TotalRevenue"] / $trawdata["TotalAssets"]));
    $p3 = ($trawdata["TotalStockholdersEquity"] == 'null' || $trawdata["TotalStockholdersEquity"] == 0 ? null : ($trawdata["TotalAssets"] / $trawdata["TotalStockholdersEquity"]));
    $params[] = $p1;
    $params[] = $p2;
    $params[] = $p3;
    $params[] = (is_null($p1) || is_null($p2) || is_null($p3) ? null : ($p1 * $p2 * $p3));
    $p1_b = ($trawdata["IncomeBeforeTaxes"] == 'null' || $trawdata["IncomeBeforeTaxes"] == 0 ? null : ($trawdata["NetIncome"] / $trawdata["IncomeBeforeTaxes"]));
    $p2_b = ($trawdata["EBIT"] == 'null' || $trawdata["EBIT"] == 0 ? null : ($trawdata["IncomeBeforeTaxes"] / $trawdata["EBIT"]));
    $p3_b = ($trawdata["TotalRevenue"] == 'null' || $trawdata["TotalRevenue"] == 0 ? null : ($trawdata["EBIT"] / $trawdata["TotalRevenue"]));
    $params[] = $p1_b;
    $params[] = $p2_b;
    $params[] = $p3_b;
    $params[] = (is_null($p2) || is_null($p3) || is_null($p1_b) || is_null($p2_b) || is_null($p3_b) ? null : ($p2 * $p3 * $p1_b * $p2_b * $p3_b));
    $params = array_merge($params,$params);
    array_unshift($params,$ppid);

    try {
        $res2 = $db->prepare($query1);
        $res2->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
}

function accrualTTM($ppid,$prawdata,$querypre) {
    $db = Database::GetInstance();
    $queryqtr = "SELECT * FROM reports_header where report_type='QTR' and ticker_id = $ppid order by fiscal_year desc, fiscal_quarter desc limit 1";
    try {
        $resqtr = $db->query($queryqtr);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $rowqtr = $resqtr->fetch(PDO::FETCH_ASSOC);
    if ($rowqtr["fiscal_year"] == $prawdata["fiscal_year"] && $rowqtr["fiscal_quarter"] == $prawdata["fiscal_quarter"]) {
        $query1 = "INSERT INTO `ttm_accrual_checks` (`ticker_id`, `net_operating_assets`, `balance_sheet_aggregate_accrual`, `cash_flow_aggregate_accrual`, `balance_sheet_accrual_ratio`, `cash_flow_accrual_ratio`, `sloan_accrual_ratio`, `stock_price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `net_operating_assets`=?, `balance_sheet_aggregate_accrual`=?, `cash_flow_aggregate_accrual`=?, `balance_sheet_accrual_ratio`=?, `cash_flow_accrual_ratio`=?, `sloan_accrual_ratio`=?, `stock_price`=?";
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
        $tquery = "SELECT * FROM ttm_incomeconsolidated b, ttm_cashflowconsolidated c, ttm_balanceconsolidated d, ttm_balancefull e, ttm_cashflowfull f, ttm_gf_data g WHERE b.ticker_id=c.ticker_id AND b.ticker_id=d.ticker_id AND b.ticker_id=e.ticker_id AND b.ticker_id=f.ticker_id AND b.ticker_id=g.ticker_id AND b.ticker_id= " . $ppid;
        try {
            $tres = $db->query($tquery);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        $rawdata = $tres->fetch(PDO::FETCH_ASSOC);

        $price = null;
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
            $price = $pricerow["LastTradePriceOnly"];
        }

        $query1 = "INSERT INTO `ttm_accrual_checks` (`ticker_id`, `net_operating_assets`, `balance_sheet_aggregate_accrual`, `cash_flow_aggregate_accrual`, `balance_sheet_accrual_ratio`, `cash_flow_accrual_ratio`, `sloan_accrual_ratio`, `stock_price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `net_operating_assets`=?, `balance_sheet_aggregate_accrual`=?, `cash_flow_aggregate_accrual`=?, `balance_sheet_accrual_ratio`=?, `cash_flow_accrual_ratio`=?, `sloan_accrual_ratio`=?, `stock_price`=?";
        $params = array();
        //NOA
        $noa = ($rawdata["TotalAssets"] - $rawdata["CashandCashEquivalents"]) - ($rawdata["TotalLiabilities"] - ($rawdata["CurrentPortionofLongtermDebt"] + $rawdata["ShorttermBorrowings"]) - $rawdata["TotalLongtermDebt"]);
        $noa_v = (empty($prawdata)) ? null : ($prawdata["TotalAssets"] - $prawdata["CashandCashEquivalents"]) - ($prawdata["TotalLiabilities"] - ($prawdata["CurrentPortionofLongtermDebt"] + $prawdata["ShorttermBorrowings"]) - $prawdata["TotalLongtermDebt"]);
        $params[] = $noa;
        //BSAA
        $bsaa = (is_null($noa_v)) ? null : ($noa - $noa_v);
        $params[] = $bsaa;
        //CFAA
        $cfaa = $rawdata["NetIncome"] - ($rawdata["CashfromOperatingActivities"] + $rawdata["CashfromInvestingActivities"]);
        $den = (is_null($noa_v)) ? null : (($noa + $noa_v) / 2);
        $params[] = $cfaa;
        //BSAR
        $bsar = ((is_null($den) || $den == 0) ? null : ($bsaa/$den));
        $params[] = $bsar;
        //CFAR
        $cfar = ((is_null($den) || $den == 0) ? null : ($cfaa/$den));
        $params[] = $cfar;
        //SAR
        $sar = ((is_null($rawdata["TotalAssets"]) || $rawdata["TotalAssets"] == 0) ? null : (($rawdata["NetIncome"] - $rawdata["CashfromOperatingActivities"] - $rawdata["CashfromInvestingActivities"])/$rawdata["TotalAssets"]));
        $params[] = $sar;
        //price
        $params[] = $price;

        $params = array_merge($params,$params);
        $query2 = $params;
        array_unshift($params,$ppid);

        try {
            $res = $db->prepare($query1);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
    }
}

function pioMinMax($pid, $max_min_array) {
    $db = Database::GetInstance();
    $max_min_array = array_slice($max_min_array,-5);
    sort($max_min_array);
    $step = array();
    $step[0] = 0;
    $step[2] = count($max_min_array) - 1;
    if($step[2] < 0) {
        $step[2]++;
    }
    $step[1] = $step[2] / 2;
    foreach($step as $key => $i) {
        if($key == 0) {
            $t1 = "5yr_min_pio_checks";
            $value = $max_min_array[$i];
        } else if($key == 1) {
            $t1 = "5yr_median_pio_checks";
            $value = ($i % 2 == 0 ? $max_min_array[$i] : ($max_min_array[floor($i)] + $max_min_array[ceil($i)]) / 2);
        } else {
            $t1 = "5yr_max_pio_checks";
            $value = $max_min_array[$i];
        }
        if (count($max_min_array) == 0) {
            $value = null;
        }
        $query = "INSERT INTO $t1 (`ticker_id`, `pioTotal`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `pioTotal`=?";
        try {
            $res = $db->prepare($query);
            $res->execute(array($pid, $value, $value));
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

    }
}

function altmanMinMax($pid, $max_min_array) {
    $db = Database::GetInstance();
    foreach($max_min_array as $key => $value) {
        $tmp_array = array_diff(array_slice($value,-5),array(null, "null"));
        sort($tmp_array);
        $max_min_array[$key] = $tmp_array;
    }
    for($step = 0; $step < 3; $step++) {
        if($step == 0) {
            $t1 = "5yr_min_alt_checks";
        } else if($step == 1) {
            $t1 = "5yr_median_alt_checks";
        } else {
            $t1 = "5yr_max_alt_checks";
        }
        $query = "INSERT INTO $t1 (`ticker_id`, `X1`, `X2`, `X3`, `X4`, `X5`, `AltmanZNormal`, `AltmanZRevised`) VALUES (?, ?, ?, ?, ? ,?, ?, ?) ON DUPLICATE KEY UPDATE `X1`=?, `X2`=?, `X3`=?, `X4`=?, `X5`=?, `AltmanZNormal`=?, `AltmanZRevised`=?";
        $params = array();
        foreach($max_min_array as $value) {
            $count = count($value) - 1;
            if($count < 0) {
                $params[] = null;
                continue;
            }
            if ($step == 0) {
                $params[] = $value[0];
            } else if ($step == 1) {
                $params[] = ($count % 2 == 0 ? $value[$count/2] : ($value[floor($count/2)] + $value[ceil($count/2)]) / 2);
            } else {
                $params[] = $value[$count];
            }
        }
        $params = array_merge($params,$params);
        array_unshift($params,$pid);
        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    }
}

function beneishMinMax($pid, $max_min_array) {
    $db = Database::GetInstance();
    foreach($max_min_array as $key => $value) {
        array_shift($value); 
        $tmp_array = array_diff(array_slice($value,-5),array(null, "null"));
        sort($tmp_array);
        $max_min_array[$key] = $tmp_array;
    }
    for($step = 0; $step < 3; $step++) {
        if($step == 0) {
            $t1 = "5yr_min_beneish_checks";
        } else if($step == 1) {
            $t1 = "5yr_median_beneish_checks";
        } else {
            $t1 = "5yr_max_beneish_checks";
        }
        $query = "INSERT INTO $t1 (`ticker_id`, `DSRI`, `GMI`, `AQI`, `SGI`, `DEPI`, `SGAI`, `TATA`, `LVGI`, `BM5`, `BM8`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `DSRI`=?, `GMI`=?, `AQI`=?, `SGI`=?, `DEPI`=?, `SGAI`=?, `TATA`=?, `LVGI`=?, `BM5`=?, `BM8`=?";
        $params = array();
        foreach($max_min_array as $value) {
            $count = count($value) - 1;
            if($count < 0) {
                $params[] = null;
                continue;
            }
            if ($step == 0) {
                $params[] = $value[0];
            } else if ($step == 1) {
                $params[] = ($count % 2 == 0 ? $value[$count/2] : ($value[floor($count/2)] + $value[ceil($count/2)]) / 2);
            } else {
                $params[] = $value[$count];
            }
        }
        $params = array_merge($params,$params);
        array_unshift($params,$pid);
        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    }
}

function accrualMinMax($pid, $max_min_array) {
    $db = Database::GetInstance();
    foreach($max_min_array as $key => $value) {
        $tmp_array = array_diff(array_slice($value,-5),array(null, "null"));
        sort($tmp_array);
        $max_min_array[$key] = $tmp_array;
    }
    for($step = 0; $step < 3; $step++) {
        if($step == 0) {
            $t1 = "5yr_min_accrual_checks";
        } else if($step == 1) {
            $t1 = "5yr_median_accrual_checks";
        } else {
            $t1 = "5yr_max_accrual_checks";
        }
        $query = "INSERT INTO $t1 (`ticker_id`, `balance_sheet_aggregate_accrual`, `cash_flow_aggregate_accrual`, `balance_sheet_accrual_ratio`, `cash_flow_accrual_ratio`, `sloan_accrual_ratio`) VALUES (?, ?, ?, ?, ? ,?) ON DUPLICATE KEY UPDATE `balance_sheet_aggregate_accrual`=?, `cash_flow_aggregate_accrual`=?, `balance_sheet_accrual_ratio`=?, `cash_flow_accrual_ratio`=?, `sloan_accrual_ratio`=?";
        $params = array();
        foreach($max_min_array as $value) {
            $count = count($value) - 1;
            if($count < 0) {
                $params[] = null;
                continue;
            }
            if ($step == 0) {
                $params[] = $value[0];
            } else if ($step == 1) {
                $params[] = ($count % 2 == 0 ? $value[$count/2] : ($value[floor($count/2)] + $value[ceil($count/2)]) / 2);
            } else {
                $params[] = $value[$count];
            }
        }
        $params = array_merge($params,$params);
        array_unshift($params,$pid);
        try {
            $res = $db->prepare($query);
            $res->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    }
}
?>
