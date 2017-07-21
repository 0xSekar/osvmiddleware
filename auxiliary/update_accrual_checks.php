<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/db.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout

try {
    $res = $db->query("delete a from reports_accrual_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
} catch(PDOException $ex) {
    echo "\nDatabase Error"; //user message
    die("Line: ".__LINE__." - ".$ex->getMessage());
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
$rawdata = array();
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
accrualTTM($pid,$rawdata,$query2);

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
?>
