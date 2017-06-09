<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/db.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
try {
    $res = $db->query("delete a from reports_dupont_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
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
dupontTTM($pid);

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

function nullValues(&$item, $key) {
    if(strlen(trim($item)) == 0) {
        $item = 'null';
    } else if($item == "-") {
        $item = 'null';
    }
}

?>
