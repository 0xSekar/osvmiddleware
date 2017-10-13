<?php
function update_ratings() {
    $db = Database::GetInstance();

    $query = "delete a from reports_ratings a left join reports_header b on a.report_id = b.id where b.id IS null";
    try {
        $res = $db->exec($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }

    $query = "SELECT DISTINCT fiscal_year from reports_header WHERE report_type='ANN' order by fiscal_year";
    try {
        $resy = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }

//Values from DB for variables
    try {
        $res = $db->prepare("SELECT variable, weight FROM ratings_weight");            
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

//Variables to be used for linear transform and squeez
    $squ = 0.998;
    foreach ($res as $key => $value) {
        switch ($value['variable']) {
            case 'G1':
                $gw1 = $value['weight'];
                break;
            case 'G2':
                $gw2 = $value['weight'];
                break;
            case 'G3':
                $gw3 = $value['weight'];
                break;
            case 'G4':
                $gw4 = $value['weight'];
                break;       
            case 'Q1':
                $qw1 = $value['weight'];
                break;
            case 'Q2':
                $qw2 = $value['weight'];
                break;
            case 'Q3':
                $qw3 = $value['weight'];
                break;
            case 'V1':
                $vw1 = $value['weight'];
                break;
            case 'V2':
                $vw2 = $value['weight'];
                break;
            case 'V3':
                $vw3 = $value['weight'];
                break;
            case 'V4':
                $vw4 = $value['weight'];
                break;
            default:
                echo "unknow variable: ".$value['variable']."\n";
                break;
        }
    }

    while($rowy = $resy->fetch(PDO::FETCH_ASSOC)) {
        $values = array();
        $tickerCount = 0;

        //GET SORTED QUALITY VARIABLES
        //FCF / Sales

        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='FCF_S' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '1':
                    $st1 = $value['value1'];
                    $st2 = $value['value2'];
                    break;
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 as rank, report_id, -FCF_S as value, ticker_id FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND FCF_S >= ".$st1." AND FCF_S < ".$st2." AND FCF_S IS NOT NULL
            UNION SELECT 2 as rank, report_id, FCF_S as value, ticker_id FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND FCF_S >= ".$nd1." AND FCF_S IS NOT NULL
            UNION SELECT 3 as rank, report_id, -FCF_S as value, ticker_id FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND FCF_S < ".$rd1." AND FCF_S IS NOT NULL
            UNION SELECT 4 as rank, report_id, FCF_S as value, ticker_id FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND FCF_S IS NULL
            ORDER BY rank, value 
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
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
        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='CROIC' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '1':
                    $st1 = $value['value1'];
                    $st2 = $value['value2'];
                    break;
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 as rank, report_id, -CROIC AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND CROIC >= ".$st1." AND CROIC < ".$st2." AND CROIC IS NOT NULL
            UNION SELECT 2 as rank, report_id, CROIC AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND CROIC >= ".$nd1." AND CROIC IS NOT NULL
            UNION SELECT 3 as rank, report_id, -CROIC AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND CROIC < ".$rd1." AND CROIC IS NOT NULL
            UNION SELECT 4 as rank, report_id, CROIC AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND CROIC IS NULL
            ORDER BY rank, value
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["Q2"] = is_null($row["value"])?null:($row["value"] * 100);
            $values[$row["report_id"]]["QP2"] = $position;
            $position++;
        }
        //PIO F Score
        $query = "
            SELECT report_id, pioTotal AS value FROM reports_pio_checks r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." ORDER BY pioTotal DESC, ticker_id 
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["Q3"] = $row["value"];
            $values[$row["report_id"]]["QP3"] = round(($tickerCount / 10) * (9 - $row["value"])) + 1;
        }

        //Correction for missing PIO Values
        foreach($values as $id => $value) {
            if(!array_key_exists("Q3",$value) || is_null($value["Q3"])) {
                $values[$id]["Q3"] = null;
                $values[$id]["QP3"] = $tickerCount;
            }
        }

        //GET SORTED GROWTH VARIABLES
        //SalesPercChange
        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='SalesPercChange' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '1':
                    $st1 = $value['value1'];
                    $st2 = $value['value2'];
                    break;
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 as rank, report_id, -SalesPercChange as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND SalesPercChange >= ".$st1." AND SalesPercChange < ".$st2." AND SalesPercChange IS NOT NULL
            UNION SELECT 2 as rank, report_id, SalesPercChange as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND SalesPercChange >= ".$nd1." AND SalesPercChange IS NOT NULL
            UNION SELECT 3 as rank, report_id, -SalesPercChange as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND SalesPercChange < ".$rd1." AND SalesPercChange IS NOT NULL
            UNION SELECT 4 as rank, report_id, SalesPercChange as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND SalesPercChange IS NULL
            ORDER BY rank, value
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["G1"] = is_null($row["value"])?null:($row["value"] * 100);
            $values[$row["report_id"]]["GP1"] = $position;
            $position++;
        }
        //Sales5YYCGrPerc
        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='Sales5YYCGrPerc' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '1':
                    $st1 = $value['value1'];
                    $st2 = $value['value2'];
                    break;
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 as rank, report_id, -Sales5YYCGrPerc as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND Sales5YYCGrPerc >= ".$st1." AND Sales5YYCGrPerc < ".$st2." AND Sales5YYCGrPerc IS NOT NULL
            UNION SELECT 2 as rank, report_id, Sales5YYCGrPerc as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND Sales5YYCGrPerc >= ".$nd1." AND Sales5YYCGrPerc IS NOT NULL
            UNION SELECT 3 as rank, report_id, -Sales5YYCGrPerc as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND Sales5YYCGrPerc < ".$rd1." AND Sales5YYCGrPerc IS NOT NULL
            UNION SELECT 4 as rank, report_id, Sales5YYCGrPerc as value FROM reports_financialscustom r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND Sales5YYCGrPerc IS NULL
            ORDER BY rank, value
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["G2"] = is_null($row["value"])?null:($row["value"] * 100);
            $values[$row["report_id"]]["GP2"] = $position;
            $position++;
        }
        //GrossProfitAstTotal
        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='GPA' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '1':
                    $st1 = $value['value1'];
                    $st2 = $value['value2'];
                    break;
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 as rank, report_id, -GPA AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND GPA >= ".$st1." AND GPA < ".$st2." AND GPA IS NOT NULL
            UNION SELECT 2 as rank, report_id, GPA AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND GPA >= ".$nd1." AND GPA IS NOT NULL
            UNION SELECT 3 as rank, report_id, -GPA AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND GPA < ".$rd1." AND GPA IS NOT NULL
            UNION SELECT 4 as rank, report_id, GPA AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND GPA IS NULL
            ORDER BY rank, value
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["G3"] = is_null($row["value"])?null:($row["value"]);
            $values[$row["report_id"]]["GP3"] = $position;
            $position++;
        }

        //GET SORTED VALUE VARIABLES
        //EV/EBIT
        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='EV_EBIT' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 AS rank, report_id, EV_EBIT AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND EV_EBIT >= ".$nd1." AND EV_EBIT IS NOT NULL
            UNION SELECT 2 AS rank, report_id, -EV_EBIT AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND EV_EBIT < ".$rd1." AND EV_EBIT IS NOT NULL
            UNION SELECT 3 AS rank, report_id, EV_EBIT AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND EV_EBIT IS NULL
            ORDER BY rank, value
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["V1"] = is_null($row["value"])?null:($row["value"]);
            $values[$row["report_id"]]["VP1"] = $position;
            $position++;
        }
        //P/FCF
        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='P_FCF' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 AS rank, report_id, P_FCF AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND P_FCF >= ".$nd1." AND P_FCF IS NOT NULL
            UNION SELECT 2 AS rank, report_id, -P_FCF AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND P_FCF < ".$rd1." AND P_FCF IS NOT NULL
            UNION SELECT 3 AS rank, report_id, P_FCF AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND P_FCF IS NULL
            ORDER BY rank, value
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["V2"] = is_null($row["value"])?null:($row["value"]);
            $values[$row["report_id"]]["VP2"] = $position;
            $position++;
        }
        //-Pr2BookQ
        try {
            $res = $db->prepare("SELECT field_order, value1, value2 FROM ratings_filters WHERE variable='P_BV' ORDER BY field_order ASC");
            $res->execute();
        } catch(PDOException $ex) {
            echo " Database Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value) {
            switch ($value['field_order']) {
                case '2':
                    $nd1 = $value['value1'];
                    break;
                case '3':
                    $rd1 = $value['value1'];
                    break;
                default:
                    break;
            }
        }
        $position = 1;
        $query = "
            SELECT 1 AS rank, report_id, P_BV AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND P_BV >= ".$nd1." AND P_BV IS NOT NULL
            UNION SELECT 2 AS rank, report_id, -P_BV AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND P_BV < ".$rd1." AND P_BV IS NOT NULL
            UNION SELECT 3 AS rank, report_id, P_BV AS value FROM reports_key_ratios r LEFT JOIN reports_header h ON r.report_id = h.id
            WHERE h.report_type = 'ANN' AND h.fiscal_year = ".$rowy["fiscal_year"]." AND P_BV IS NULL
            ORDER BY rank, value
            ";
        try {
            $res = $db->query($query);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("- Line: ".__LINE__." - ".$ex->getMessage());
        }
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $values[$row["report_id"]]["V3"] = is_null($row["value"])?null:($row["value"]);
            $values[$row["report_id"]]["VP3"] = $position;
            $position++;
        }

        foreach($values as $id => $value) {
            //PENALIZE RATINGS
            //FCF / Sales
            if(!array_key_exists("Q1",$value) || is_null($value["Q1"])) {
                $values[$id]["QPP1"] = $tickerCount;
            } else {
                $values[$id]["QPP1"] = $value["QP1"];
            }
            //CROIC
            if(!array_key_exists("Q2",$value) || is_null($value["Q2"])) {
                $values[$id]["QPP2"] = $tickerCount;
            } else {
                $values[$id]["QPP2"] = $value["QP2"];
            }
            //PIO F Score
            if(!array_key_exists("QP3",$value) || is_null($value["QP3"])) {
                $values[$id]["QPP3"] = $tickerCount;
            } else {
                $values[$id]["QPP3"] = $value["QP3"];
            }
            //SalesPercChange
            if(!array_key_exists("G1",$value) || is_null($value["G1"])) {
                $values[$id]["GPP1"] = $tickerCount;
            } else {
                $values[$id]["GPP1"] = $value["GP1"];
            }
            //Sales5YYCGrPerc
            if(!array_key_exists("G2",$value) || is_null($value["G2"])) {
                $values[$id]["GPP2"] = $tickerCount;
            } else {
                $values[$id]["GPP2"] = $value["GP2"];
            }
            //GrossProfitAstTotal
            if(!array_key_exists("G3",$value) || is_null($value["G3"])) {
                $values[$id]["GPP3"] = $tickerCount;
            } else {
                $values[$id]["GPP3"] = $value["GP3"];
            }
            //EV/EBIT
            if(!array_key_exists("V1",$value) || is_null($value["V1"])) {
                $values[$id]["VPP1"] = $tickerCount;
            } else {
                $values[$id]["VPP1"] = $value["VP1"];
            }
            //P/FCF
            if(!array_key_exists("V2",$value) || is_null($value["V2"])) {
                $values[$id]["VPP2"] = $tickerCount;
            } else {
                $values[$id]["VPP2"] = $value["VP2"];
            }
            //-Pr2BookQ
            if(!array_key_exists("V3",$value) || is_null($value["V3"])) {
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
            $values[$id]["QPW1"] = (!array_key_exists("Q1",$values[$id]) || is_null($values[$id]["Q1"]))?0:($values[$id]["QPS1"] * $qw1);
            $values[$id]["QPW2"] = (!array_key_exists("Q2",$values[$id]) || is_null($values[$id]["Q2"]))?0:($values[$id]["QPS2"] * $qw2);
            $values[$id]["QPW3"] = (!array_key_exists("Q3",$values[$id]) || is_null($values[$id]["Q3"]))?0:($values[$id]["QPS3"] * $qw3);
            $values[$id]["QF"] = $values[$id]["QPW1"] + $values[$id]["QPW2"] + $values[$id]["QPW3"];
            $values[$id]["GPW1"] = (!array_key_exists("G1",$values[$id]) || is_null($values[$id]["G1"]))?0:($values[$id]["GPS1"] * $gw1);
            $values[$id]["GPW2"] = (!array_key_exists("G2",$values[$id]) || is_null($values[$id]["G2"]))?0:($values[$id]["GPS2"] * $gw2);
            $values[$id]["GPW3"] = (!array_key_exists("G3",$values[$id]) || is_null($values[$id]["G3"]))?0:($values[$id]["GPS3"] * $gw3);
            $values[$id]["GPW4"] = (!array_key_exists("Q3",$values[$id]) || is_null($values[$id]["Q3"]))?0:($values[$id]["QPS3"] * $gw4);
            $values[$id]["GF"] = $values[$id]["GPW1"] + $values[$id]["GPW2"] + $values[$id]["GPW3"] + $values[$id]["GPW4"];
            $values[$id]["VPW1"] = (!array_key_exists("V1",$values[$id]) || is_null($values[$id]["V1"]))?0:($values[$id]["VPS1"] * $vw1);
            $values[$id]["VPW2"] = (!array_key_exists("V2",$values[$id]) || is_null($values[$id]["V2"]))?0:($values[$id]["VPS2"] * $vw2);
            $values[$id]["VPW3"] = (!array_key_exists("V3",$values[$id]) || is_null($values[$id]["V3"]))?0:($values[$id]["VPS3"] * $vw3);
            $values[$id]["VPW4"] = (!array_key_exists("Q3",$values[$id]) || is_null($values[$id]["Q3"]))?0:($values[$id]["QPS3"] * $vw4);
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
            $query = "INSERT INTO `reports_ratings` (`report_id`, `Q1`, `Q2`, `Q3`, `QT`, `G1`, `G2`, `G3`, `G4`, `GT`, `V1`, `V2`, `V3`, `V4`, `VT`, `AS`, `AS_grade`, `Q_grade`, `V_grade`, `G_grade`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `Q1`=?, `Q2`=?, `Q3`=?, `QT`=?, `G1`=?, `G2`=?, `G3`=?, `G4`=?, `GT`=?, `V1`=?, `V2`=?, `V3`=?, `V4`=?, `VT`=?, `AS`=?, `AS_grade`=?, `Q_grade`=?, `V_grade`=?, `G_grade`=?"; 
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
        /*$o = fopen('file'.$rowy['fiscal_year'].'.csv', 'w');
          fputcsv($o,array_keys($values[1]));
          foreach($values as $id=>$value) {
          fputcsv($o,$value);
          }
          fclose($o);*/
    }
    exit;
}
?>
