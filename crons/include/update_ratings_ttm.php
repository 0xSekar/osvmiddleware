<?php
function update_ratings_ttm() {
    $db = Database::GetInstance();

    $values = array();
    $query = "delete from ttm_ratings_history where ratings_date = curdate()";
    try {
        $res = $db->exec($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $query = "insert into ttm_ratings_history (ticker_id, ratings_date, Q1, Q2, Q3, QT, V1, V2, V3, V4, VT, G1, G2, G3, G4, GT, `AS`, AS_grade, Q_grade, V_grade, G_grade) select ticker_id, curdate() as ratings_date, Q1, Q2, Q3, QT, V1, V2, V3, V4, VT, G1, G2, G3, G4, GT, `AS`, AS_grade, Q_grade, V_grade, G_grade from ttm_ratings";
    try {
        $res = $db->exec($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $tickerCount = 0;
    //Variables to be used for linear transform and squeez
    $squ = 0.998;
    $qw1 = 0.275;
    $qw2 = 0.45;
    $qw3 = 0.275;
    $gw1 = 0.1;
    $gw2 = 0.1;
    $gw3 = 0.55;
    $gw4 = 0.25;
    $vw1 = 0.275;
    $vw2 = 0.375;
    $vw3 = 0.075;
    $vw4 = 0.275;

    //GET SORTED QUALITY VARIABLES
    //FCF / Sales
    $position = 1;
    $query = "
        select 1 as rank, ticker_id, -FCF_S AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND FCF_S >= 0 AND FCF_S < 0.30 AND FCF_S IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, FCF_S AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND FCF_S >= 0.30 AND FCF_S IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, -FCF_S AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND FCF_S < 0 AND FCF_S IS NOT NULL
        UNION SELECT 4 as rank, ticker_id, FCF_S AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND FCF_S IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["Q1"] = is_null($row["value"])?null:($row["value"] * 100);
        $values[$row["ticker_id"]]["QP1"] = $position;
        $position++;
        $tickerCount++;
    }
    //Aditional variables for linear transform and squeez
    $a = -100 / ($tickerCount - 1);
    $b = 100 - $a;

    //CROIC
    $position = 1;
    $query = "
        select 1 as rank, ticker_id, -CROIC AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND CROIC >= 0 AND CROIC < 0.40 AND CROIC IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, CROIC AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND CROIC >= 0.40 AND CROIC IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, -CROIC AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND CROIC < 0 AND CROIC IS NOT NULL
        UNION SELECT 4 as rank, ticker_id, CROIC AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND CROIC IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["Q2"] = is_null($row["value"])?null:($row["value"] * 100);
        $values[$row["ticker_id"]]["QP2"] = $position;
        $position++;
    }
    //PIO F Score
    $query = "
        select ticker_id, pioTotal AS value from
        ttm_pio_checks a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE order by pioTotal desc, ticker_id
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["Q3"] = $row["value"];
        $values[$row["ticker_id"]]["QP3"] = round(($tickerCount / 10) * (9 - $row["value"])) + 1;
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
        select 1 as rank, ticker_id, -RevenuePctGrowthTTM AS value from
        tickers_growth_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND RevenuePctGrowthTTM >= 0 AND RevenuePctGrowthTTM < 0.60 AND RevenuePctGrowthTTM IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, RevenuePctGrowthTTM AS value from
        tickers_growth_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND RevenuePctGrowthTTM >= 0.60 AND RevenuePctGrowthTTM IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, -RevenuePctGrowthTTM AS value from
        tickers_growth_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND RevenuePctGrowthTTM < 0 AND RevenuePctGrowthTTM IS NOT NULL
        UNION SELECT 4 as rank, ticker_id, RevenuePctGrowthTTM AS value from
        tickers_growth_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND RevenuePctGrowthTTM IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["G1"] = is_null($row["value"])?null:($row["value"] * 100);
        $values[$row["ticker_id"]]["GP1"] = $position;
        $position++;
    }
    //Sales5YYCGrPerc
    $position = 1;
    $query = "
        select 1 as rank, ticker_id, -Sales5YYCGrPerc AS value from
        ttm_financialscustom a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND Sales5YYCGrPerc >= 0 AND Sales5YYCGrPerc < 0.40 AND Sales5YYCGrPerc IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, Sales5YYCGrPerc AS value from
        ttm_financialscustom a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND Sales5YYCGrPerc >= 0.40 AND Sales5YYCGrPerc IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, -Sales5YYCGrPerc AS value from
        ttm_financialscustom a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND Sales5YYCGrPerc < 0 AND Sales5YYCGrPerc IS NOT NULL
        UNION SELECT 4 as rank, ticker_id, Sales5YYCGrPerc AS value from
        ttm_financialscustom a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND Sales5YYCGrPerc IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["G2"] = is_null($row["value"])?null:($row["value"] * 100);
        $values[$row["ticker_id"]]["GP2"] = $position;
        $position++;
    }
    //GrossProfitAstTotal
    $position = 1;
    $query = "
        select 1 as rank, ticker_id, -GPA AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND GPA >= 0 AND GPA < 1 AND GPA IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, GPA AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND GPA >= 1 AND GPA IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, -GPA AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND GPA < 0 AND GPA IS NOT NULL
        UNION SELECT 4 as rank, ticker_id, GPA AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND GPA IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["G3"] = is_null($row["value"])?null:($row["value"]);
        $values[$row["ticker_id"]]["GP3"] = $position;
        $position++;
    }
    //GET SORTED VALUE VARIABLES
    //EV/EBIT
    $position = 1;
    $query = "
        select 1 as rank, ticker_id, EV_EBIT AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND EV_EBIT >= 0 AND EV_EBIT IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, -EV_EBIT AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND EV_EBIT < 0 AND EV_EBIT IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, EV_EBIT AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND EV_EBIT IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["V1"] = is_null($row["value"])?null:($row["value"]);
        $values[$row["ticker_id"]]["VP1"] = $position;
        $position++;
    }
    //P/FCF
    $position = 1;
    $query = "
        select 1 as rank, ticker_id, P_FCF AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND P_FCF >= 0 AND P_FCF IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, -P_FCF AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND P_FCF < 0 AND P_FCF IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, P_FCF AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND P_FCF IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["V2"] = is_null($row["value"])?null:($row["value"]);
        $values[$row["ticker_id"]]["VP2"] = $position;
        $position++;
    }
    //-Pr2BookQ
    $position = 1;
    $query = "
        select 1 as rank, ticker_id, P_BV AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND P_BV >= 0 AND P_BV IS NOT NULL
        UNION SELECT 2 as rank, ticker_id, -P_BV AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND P_BV < 0 AND P_BV IS NOT NULL
        UNION SELECT 3 as rank, ticker_id, P_BV AS value from
        ttm_key_ratios a INNER JOIN tickers b on a.ticker_id=b.id where is_old = FALSE AND P_BV IS NULL
        ORDER BY rank, value
        ";
    try {
        $res = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $values[$row["ticker_id"]]["V3"] = is_null($row["value"])?null:($row["value"]);
        $values[$row["ticker_id"]]["VP3"] = $position;
        $position++;
    }

    foreach($values as $id => $value) {
        //PENALIZE RATINGS
        //FCF / Sales
        if(is_null($value["Q1"])) {
            $values[$id]["QPP1"] = $tickerCount;
        } else {
            $values[$id]["QPP1"] = $value["QP1"];
        }
        //CROIC
        if(is_null($value["Q2"])) {
            $values[$id]["QPP2"] = $tickerCount;
        } else {
            $values[$id]["QPP2"] = $value["QP2"];
        }
        //PIO F Score
        $values[$id]["QPP3"] = $value["QP3"];
        //SalesPercChange
        if(is_null($value["G1"])) {
            $values[$id]["GPP1"] = $tickerCount;
        } else {
            $values[$id]["GPP1"] = $value["GP1"];
        }
        //Sales5YYCGrPerc
        if(is_null($value["G2"])) {
            $values[$id]["GPP2"] = $tickerCount;
        } else {
            $values[$id]["GPP2"] = $value["GP2"];
        }
        //GrossProfitAstTotal
        if(is_null($value["G3"])) {
            $values[$id]["GPP3"] = $tickerCount;
        } else {
            $values[$id]["GPP3"] = $value["GP3"];
        }
        //EV/EBIT
        if(is_null($value["V1"])) {
            $values[$id]["VPP1"] = $tickerCount;
        } else {
            $values[$id]["VPP1"] = $value["VP1"];
        }
        //P/FCF
        if(is_null($value["V2"])) {
            $values[$id]["VPP2"] = $tickerCount;
        } else {
            $values[$id]["VPP2"] = $value["VP2"];
        }
        //Pr2BookQ
        if(is_null($value["V3"])) {
            $values[$id]["VPP3"] = $tickerCount;
        } else {
            $values[$id]["VPP3"] = $value["VP3"];
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
        if($values[$id]["VPP1"] > $tickerCount) {
            $values[$id]["VPP1"] = $tickerCount;
        }
        if($values[$id]["VPP2"] > $tickerCount) {
            $values[$id]["VPP2"] = $tickerCount;
        }
        if($values[$id]["VPP3"] > $tickerCount) {
            $values[$id]["VPP3"] = $tickerCount;
        }

        //Linear transform
        $values[$id]["QPT1"] = $a * $values[$id]["QPP1"] + $b;
        $values[$id]["QPT2"] = $a * $values[$id]["QPP2"] + $b;
        $values[$id]["QPT3"] = $a * $values[$id]["QPP3"] + $b;
        $values[$id]["GPT1"] = $a * $values[$id]["GPP1"] + $b;
        $values[$id]["GPT2"] = $a * $values[$id]["GPP2"] + $b;
        $values[$id]["GPT3"] = $a * $values[$id]["GPP3"] + $b;
        $values[$id]["VPT1"] = $a * $values[$id]["VPP1"] + $b;
        $values[$id]["VPT2"] = $a * $values[$id]["VPP2"] + $b;
        $values[$id]["VPT3"] = $a * $values[$id]["VPP3"] + $b;

        //Apply Squeez
        $values[$id]["QPS1"] = ($values[$id]["QPT1"] - 50) * $squ + 50;
        $values[$id]["QPS2"] = ($values[$id]["QPT2"] - 50) * $squ + 50;
        $values[$id]["QPS3"] = ($values[$id]["QPT3"] - 50) * $squ + 50;
        $values[$id]["GPS1"] = ($values[$id]["GPT1"] - 50) * $squ + 50;
        $values[$id]["GPS2"] = ($values[$id]["GPT2"] - 50) * $squ + 50;
        $values[$id]["GPS3"] = ($values[$id]["GPT3"] - 50) * $squ + 50;
        $values[$id]["VPS1"] = ($values[$id]["VPT1"] - 50) * $squ + 50;
        $values[$id]["VPS2"] = ($values[$id]["VPT2"] - 50) * $squ + 50;
        $values[$id]["VPS3"] = ($values[$id]["VPT3"] - 50) * $squ + 50;

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
        $values[$id]["VPW1"] = is_null($values[$id]["V1"])?0:($values[$id]["VPS1"] * $vw1);
        $values[$id]["VPW2"] = is_null($values[$id]["V2"])?0:($values[$id]["VPS2"] * $vw2);
        $values[$id]["VPW3"] = is_null($values[$id]["V3"])?0:($values[$id]["VPS3"] * $vw3);
        $values[$id]["VPW4"] = is_null($values[$id]["Q3"])?0:($values[$id]["QPS3"] * $vw4);
        $values[$id]["VF"] = $values[$id]["VPW1"] + $values[$id]["VPW2"] + $values[$id]["VPW3"] + $values[$id]["VPW4"];
        $values[$id]["AS"] = ($values[$id]["QF"] + $values[$id]["GF"] + $values[$id]["VF"])/3;
        if ($values[$id]["AS"] >= 85)
            $values[$id]["RS"] = 'A';
        if ($values[$id]["AS"] >= 75 && $values[$id]["AS"] < 85)
            $values[$id]["RS"] = 'B';
        if ($values[$id]["AS"] >= 65 && $values[$id]["AS"] < 75)
            $values[$id]["RS"] = 'C';
        if ($values[$id]["AS"] >= 50 && $values[$id]["AS"] < 65)
            $values[$id]["RS"] = 'D';
        if ($values[$id]["AS"] < 50)
            $values[$id]["RS"] = 'F';
        if ($values[$id]["QF"] >= 85)
            $values[$id]["QG"] = 'A';
        if ($values[$id]["QF"] >= 75 && $values[$id]["QF"] < 85)
            $values[$id]["QG"] = 'B';
        if ($values[$id]["QF"] >= 65 && $values[$id]["QF"] < 75)
            $values[$id]["QG"] = 'C';
        if ($values[$id]["QF"] >= 50 && $values[$id]["QF"] < 65)
            $values[$id]["QG"] = 'D';
        if ($values[$id]["QF"] < 50)
            $values[$id]["QG"] = 'F';
        if ($values[$id]["GF"] >= 85)
            $values[$id]["GG"] = 'A';
        if ($values[$id]["GF"] >= 75 && $values[$id]["GF"] < 85)
            $values[$id]["GG"] = 'B';
        if ($values[$id]["GF"] >= 65 && $values[$id]["GF"] < 75)
            $values[$id]["GG"] = 'C';
        if ($values[$id]["GF"] >= 50 && $values[$id]["GF"] < 65)
            $values[$id]["GG"] = 'D';
        if ($values[$id]["GF"] < 50)
            $values[$id]["GG"] = 'F';
        if ($values[$id]["VF"] >= 85)
            $values[$id]["VG"] = 'A';
        if ($values[$id]["VF"] >= 75 && $values[$id]["VF"] < 85)
            $values[$id]["VG"] = 'B';
        if ($values[$id]["VF"] >= 65 && $values[$id]["VF"] < 75)
            $values[$id]["VG"] = 'C';
        if ($values[$id]["VF"] >= 50 && $values[$id]["VF"] < 65)
            $values[$id]["VG"] = 'D';
        if ($values[$id]["VF"] < 50)
            $values[$id]["VG"] = 'F';

        //Save data
        $query = "INSERT INTO `ttm_ratings` (`ticker_id`, `Q1`, `Q2`, `Q3`, `QT`, `G1`, `G2`, `G3`, `G4`, `GT`, `V1`, `V2`, `V3`, `V4`, `VT`, `AS`, `AS_grade`, `Q_grade`, `V_grade`, `G_grade`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `Q1`=?, `Q2`=?, `Q3`=?, `QT`=?, `G1`=?, `G2`=?, `G3`=?, `G4`=?, `GT`=?, `V1`=?, `V2`=?, `V3`=?, `V4`=?, `VT`=?, `AS`=?, `AS_grade`=?, `Q_grade`=?, `V_grade`=?, `G_grade`=?";
        $params = array();
        $params[] = $values[$id]["QPW1"];
        $params[] = $values[$id]["QPW2"];
        $params[] = $values[$id]["QPW3"];
        $params[] = $values[$id]["QF"];
        $params[] = $values[$id]["GPW1"];
        $params[] = $values[$id]["GPW2"];
        $params[] = $values[$id]["GPW3"];
        $params[] = $values[$id]["GPW4"];
        $params[] = $values[$id]["GF"];
        $params[] = $values[$id]["VPW1"];
        $params[] = $values[$id]["VPW2"];
        $params[] = $values[$id]["VPW3"];
        $params[] = $values[$id]["VPW4"];
        $params[] = $values[$id]["VF"];
        $params[] = $values[$id]["AS"];
        $params[] = $values[$id]["RS"];
        $params[] = $values[$id]["QG"];
        $params[] = $values[$id]["VG"];
        $params[] = $values[$id]["GG"];
        $params = array_merge($params,$params);
        array_unshift($params,$id);

        try {
            $save = $db->prepare($query);
            $save->execute($params);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        $values[$id]["id"] = $id;
    }
    //Export to csv
    /*
    $o = fopen('file.csv', 'w');
    fputcsv($o,array_keys($values[19]));
    foreach($values as $id=>$value) {
        $query = "select ticker from tickers where id=".$id;
        $res = $db->query($query);
        $value["ticker"] = $res->fetchColumn();
        fputcsv($o,$value);
    }
    fclose($o);
    */
}
?>
