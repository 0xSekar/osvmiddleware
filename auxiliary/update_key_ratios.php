<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/db.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
$query = "delete a from reports_key_ratios a left join reports_header b on a.report_id = b.id where b.id IS null";
try {
    $res = $db->exec($query);
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
$pid = $arpy = $inpy = 0;
$idChange = true;
$first = true;
$max_min_array = array();
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    if ($row["ticker_id"] != $pid) {
        $ppid = $pid;
        $pid = $row["ticker_id"];
        $arpy = $inpy = 0;
        $idChange = true;
        $pre_max_min = $max_min_array;
        $max_min_array = array();
    } else {
        $arpy = $rawdata["TotalReceivablesNet"]=='null'?null:$rawdata["TotalReceivablesNet"];
        $inpy = $rawdata["InventoriesNet"]=='null'?null:$rawdata["InventoriesNet"];
        $idChange = false;
    }
    $query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.id= " . $row["id"];
    try {
        $res2 = $db->query($query);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $rawdata = $res2->fetch(PDO::FETCH_ASSOC);

    //Update TTM Data
    if($idChange && !$first) {
        keyratiosMinMax($ppid, $pre_max_min);
    }


    $rdate = date("Y-m-d",strtotime($rawdata["report_date"]));
    $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$rawdata["ticker_id"]."' and report_date <= '".$rdate."' order by report_date desc limit 1";
    $price = null;
    try {
        $rquote = $db->query($qquote);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }

    if($rquote->rowCount() > 0) {                	 
        $price = $rquote->fetch(PDO::FETCH_ASSOC);
        $rdate = $price["report_date"];
        $price = $price["adj_close"];
    }
    $entValue = ((is_null($rawdata["SharesOutstandingDiluted"]) && is_null($price) && is_null($rawdata["TotalLongtermDebt"]) && is_null($rawdata["TotalShorttermDebt"]) && is_null($rawdata["PreferredStock"]) && is_null($rawdata["MinorityInterestEquityEarnings"]) && is_null($rawdata["CashCashEquivalentsandShorttermInvestments"]))?null:((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)+$rawdata["TotalLongtermDebt"]+$rawdata["TotalShorttermDebt"]+$rawdata["PreferredStock"]+$rawdata["MinorityInterestEquityEarnings"]-$rawdata["CashCashEquivalentsandShorttermInvestments"]));
    $query = "INSERT INTO `reports_key_ratios` (`report_id`, `ReportYear`, `ReportDate`, `ReportDateAdjusted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ReportYear`=?, `ReportDate`=?, `ReportDateAdjusted`=?, `ReportDatePrice`=?, `CashFlow`=?, `MarketCap`=?, `EnterpriseValue`=?, `GoodwillIntangibleAssetsNet`=?, `TangibleBookValue`=?, `ExcessCash`=?, `TotalInvestedCapital`=?, `WorkingCapital`=?, `P_E`=?, `P_E_CashAdjusted`=?, `EV_EBITDA`=?, `EV_EBIT`=?, `P_S`=?, `P_BV`=?, `P_Tang_BV`=?, `P_CF`=?, `P_FCF`=?, `P_OwnerEarnings`=?, `FCF_S`=?, `FCFYield`=?, `MagicFormulaEarningsYield`=?, `ROE`=?, `ROA`=?, `ROIC`=?, `CROIC`=?, `GPA`=?, `BooktoMarket`=?, `QuickRatio`=?, `CurrentRatio`=?, `TotalDebt_EquityRatio`=?, `LongTermDebt_EquityRatio`=?, `ShortTermDebt_EquityRatio`=?, `AssetTurnover`=?, `CashPercofRevenue`=?, `ReceivablesPercofRevenue`=?, `SG_APercofRevenue`=?, `R_DPercofRevenue`=?, `DaysSalesOutstanding`=?, `DaysInventoryOutstanding`=?, `DaysPayableOutstanding`=?, `CashConversionCycle`=?, `ReceivablesTurnover`=?, `InventoryTurnover`=?, `AverageAgeofInventory`=?, `IntangiblesPercofBookValue`=?, `InventoryPercofRevenue`=?, `LT_DebtasPercofInvestedCapital`=?, `ST_DebtasPercofInvestedCapital`=?, `LT_DebtasPercofTotalDebt`=?, `ST_DebtasPercofTotalDebt`=?, `TotalDebtPercofTotalAssets`=?, `WorkingCapitalPercofPrice`=?";
    $params = array();
    $params[] = $rawdata["fiscal_year"];
    $params[] = date("Y-m-d",strtotime($rawdata["report_date"]));
    $params[] = ($rdate == '0000-00-00'?null:$rdate);
    $params[] = $max_min_array["ReportDatePrice"][] = $price;
    $params[] = $max_min_array["CashFlow"][] = (((is_null($rawdata["GrossProfit"]) && is_null($rawdata["OperatingExpenses"]) && is_null($rawdata["CapEx"])) || is_null($rawdata["TaxRatePercent"]))?null:(($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"])));
    $params[] = $max_min_array["MarketCap"][] = ((is_null($rawdata["SharesOutstandingDiluted"])||is_null($price))?null:(toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price));
    $params[] = $max_min_array["EnterpriseValue"][] = $entValue;
    $params[] = $max_min_array["GoodwillIntangibleAssetsNet"][] = ((is_null($rawdata["GoodwillIntangibleAssetsNet"]))?null:$rawdata["GoodwillIntangibleAssetsNet"]);
    $params[] = $max_min_array["TangibleBookValue"][] = ((is_null($rawdata["TotalStockholdersEquity"])&&is_null($rawdata["GoodwillIntangibleAssetsNet"]))?null:($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"]));
    $params[] = $max_min_array["ExcessCash"][] = ((is_null($rawdata["CashCashEquivalentsandShorttermInvestments"]) || (is_null($rawdata["CashCashEquivalentsandShorttermInvestments"])&&is_null($rawdata["TotalCurrentLiabilities"])&&is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["LongtermInvestments"])))?null:(($rawdata["CashCashEquivalentsandShorttermInvestments"] + $rawdata["LongtermInvestments"]) - max(0, ($rawdata["TotalCurrentLiabilities"]-$rawdata["TotalCurrentAssets"]+$rawdata["CashCashEquivalentsandShorttermInvestments"]))));
    $params[] = $max_min_array["TotalInvestedCapital"][] = ((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))?null:($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]));
    $params[] = $max_min_array["WorkingCapital"][] = ((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["TotalCurrentLiabilities"]))?null:($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]));
    $params[] = $max_min_array["P_E"][] = ((is_null($price)||is_null($rawdata["EPSDiluted"])||$rawdata["EPSDiluted"]==0)?null:($price / toFloat($rawdata["EPSDiluted"])));
    $params[] = $max_min_array["P_E_CashAdjusted"][] = ((is_null($rawdata["SharesOutstandingDiluted"])||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||is_null($rawdata["EPSDiluted"])||$rawdata["EPSDiluted"]==0)?null:((((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)-$rawdata["CashCashEquivalentsandShorttermInvestments"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/toFloat($rawdata["EPSDiluted"])));
    $params[] = $max_min_array["EV_EBITDA"][] = ((is_null($entValue)||is_null($rawdata["EBITDA"])||$rawdata["EBITDA"]==0)?null:($entValue / $rawdata["EBITDA"]));
    $params[] = $max_min_array["EV_EBIT"][] = ((is_null($entValue)||is_null($rawdata["EBIT"])||$rawdata["EBIT"]==0)?null:($entValue / $rawdata["EBIT"]));
    $params[] = $max_min_array["P_S"][] = ((is_null($price)||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price / ($rawdata["TotalRevenue"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
    $params[] = $max_min_array["P_BV"][] = ((is_null($price)||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price / ($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
    $params[] = $max_min_array["P_Tang_BV"][] = ((is_null($price)||(is_null($rawdata["TotalStockholdersEquity"])&&is_null($rawdata["GoodwillIntangibleAssetsNet"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"]==0))?null:($price / (($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
    $params[] = $max_min_array["P_CF"][] = ((is_null($price)||(is_null($rawdata["GrossProfit"])&&is_null($rawdata["OperatingExpenses"])&&is_null($rawdata["CapEx"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"]==0)||$rawdata["TaxRatePercent"]==1)?null:($price / ((($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"]))/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
    $params[] = $max_min_array["P_FCF"][] = ((is_null($price)||is_null($rawdata["FreeCashFlow"])||$rawdata["FreeCashFlow"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price / ($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
    $params[] = $max_min_array["P_OwnerEarnings"][] = ((is_null($price)||is_null($rawdata["OwnerEarningsFCF"])||$rawdata["OwnerEarningsFCF"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price / ($rawdata["OwnerEarningsFCF"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
    $params[] = $max_min_array["FCF_S"][] = ((is_null($rawdata["FreeCashFlow"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["FreeCashFlow"] / $rawdata["TotalRevenue"]));
    $params[] = $max_min_array["FCFYield"][] = ((is_null($price)||$price==0||is_null($rawdata["FreeCashFlow"])||$rawdata["FreeCashFlow"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:(1 / ($price / ($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))));
    $params[] = $max_min_array["MagicFormulaEarningsYield"][] = ((is_null($rawdata["EBIT"])||is_null($entValue)||$entValue==0)?null:($rawdata["EBIT"] / $entValue));
    $params[] = $max_min_array["ROE"][] = ((is_null($rawdata["NetIncome"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:($rawdata["NetIncome"] / $rawdata["TotalStockholdersEquity"]));
    $params[] = $max_min_array["ROA"][] = ((is_null($rawdata["NetIncome"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:($rawdata["NetIncome"] / $rawdata["TotalAssets"]));
    $params[] = $max_min_array["ROIC"][] = ((is_null($rawdata["EBIT"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["NotesPayable"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"])&&is_null($rawdata["CashCashEquivalentsandShorttermInvestments"]))||($rawdata["TotalShorttermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]-$rawdata["CashCashEquivalentsandShorttermInvestments"]==0))?null:(($rawdata["EBIT"]*(1-$rawdata["TaxRatePercent"])) / ($rawdata["TotalShorttermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]-$rawdata["CashCashEquivalentsandShorttermInvestments"])));
    $params[] = $max_min_array["CROIC"][] = ((is_null($rawdata["FreeCashFlow"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?null:($rawdata["FreeCashFlow"] / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])));
    $params[] = $max_min_array["GPA"][] = ((is_null($rawdata["GrossProfit"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:($rawdata["GrossProfit"] / $rawdata["TotalAssets"]));
    $params[] = $max_min_array["BooktoMarket"][] = ((is_null($price)||$price==0||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:(1 / ($price / ($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))));
    $params[] = $max_min_array["QuickRatio"][] = (((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["InventoriesNet"]))||is_null($rawdata["TotalCurrentLiabilities"])||$rawdata["TotalCurrentLiabilities"]==0)?null:(($rawdata["TotalCurrentAssets"] - $rawdata["InventoriesNet"]) / $rawdata["TotalCurrentLiabilities"]));
    $params[] = $max_min_array["CurrentRatio"][] = ((is_null($rawdata["TotalCurrentAssets"])||is_null($rawdata["TotalCurrentLiabilities"])||$rawdata["TotalCurrentLiabilities"]==0)?null:($rawdata["TotalCurrentAssets"] / $rawdata["TotalCurrentLiabilities"]));
    $params[] = $max_min_array["TotalDebt_EquityRatio"][] = (((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]) / $rawdata["TotalStockholdersEquity"]));
    $params[] = $max_min_array["LongTermDebt_EquityRatio"][] = (((is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:(($rawdata["TotalLongtermDebt"]) / $rawdata["TotalStockholdersEquity"]));
    $params[] = $max_min_array["ShortTermDebt_EquityRatio"][] = ((is_null($rawdata["TotalShorttermDebt"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:($rawdata["TotalShorttermDebt"] / $rawdata["TotalStockholdersEquity"]));
    $params[] = $max_min_array["AssetTurnover"][] = ((is_null($rawdata["TotalRevenue"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:($rawdata["TotalRevenue"] / $rawdata["TotalAssets"]));
    $params[] = $max_min_array["CashPercofRevenue"][] = ((is_null($rawdata["CashCashEquivalentsandShorttermInvestments"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["CashCashEquivalentsandShorttermInvestments"] / $rawdata["TotalRevenue"]));
    $params[] = $max_min_array["ReceivablesPercofRevenue"][] = ((is_null($rawdata["TotalReceivablesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"]));
    $params[] = $max_min_array["SG_APercofRevenue"][] = ((is_null($rawdata["SellingGeneralAdministrativeExpenses"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"] / $rawdata["TotalRevenue"]));
    $params[] = $max_min_array["R_DPercofRevenue"][] = ((is_null($rawdata["ResearchDevelopmentExpense"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["ResearchDevelopmentExpense"] / $rawdata["TotalRevenue"]));
    $params[] = $max_min_array["DaysSalesOutstanding"][] = ((is_null($rawdata["TotalReceivablesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365));
    $params[] = $max_min_array["DaysInventoryOutstanding"][] = ((is_null($rawdata["InventoriesNet"])||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?null:($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365));
    $params[] = $max_min_array["DaysPayableOutstanding"][] = ((is_null($rawdata["AccountsPayable"])||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?null:($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365));
    $params[] = $max_min_array["CashConversionCycle"][] = ((is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?null:(($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365)+($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365)-($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365)));
    if($idChange==true) {
        $params[] = $max_min_array["ReceivablesTurnover"][] = ((is_null($rawdata["TotalRevenue"])||is_null($rawdata["TotalReceivablesNet"])||$rawdata["TotalReceivablesNet"]==0)?null:($rawdata["TotalRevenue"] / ($rawdata["TotalReceivablesNet"])));
        $params[] = $max_min_array["InventoryTurnover"][] = ((is_null($rawdata["CostofRevenue"])||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?null:($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"])));
        $params[] = $max_min_array["AverageAgeofInventory"][] = ((is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?null:(365 / ($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"]))));
    } else {
        $params[] = $max_min_array["ReceivablesTurnover"][] = ((is_null($rawdata["TotalRevenue"])||(is_null($rawdata["TotalReceivablesNet"])&&is_null($arpy))||($rawdata["TotalReceivablesNet"]+$arpy==0))?null:($rawdata["TotalRevenue"] / (($arpy + $rawdata["TotalReceivablesNet"])/2)));
        $params[] = $max_min_array["InventoryTurnover"][] = ((is_null($rawdata["CostofRevenue"])||(is_null($rawdata["InventoriesNet"])&&is_null($inpy))||($rawdata["InventoriesNet"]+$inpy==0))?null:($rawdata["CostofRevenue"] / (($inpy + $rawdata["InventoriesNet"])/2)));
        $params[] = $max_min_array["AverageAgeofInventory"][] = ((is_null($rawdata["CostofRevenue"])||(is_null($rawdata["InventoriesNet"])&&is_null($inpy))||($rawdata["InventoriesNet"]+$inpy==0)||$rawdata["CostofRevenue"]==0)?null:(365 / ($rawdata["CostofRevenue"] / (($inpy + $rawdata["InventoriesNet"])/2))));
    }
    $params[] = $max_min_array["IntangiblesPercofBookValue"][] = ((is_null($rawdata["GoodwillIntangibleAssetsNet"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:($rawdata["GoodwillIntangibleAssetsNet"] / $rawdata["TotalStockholdersEquity"]));
    $params[] = $max_min_array["InventoryPercofRevenue"][] = ((is_null($rawdata["InventoriesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["InventoriesNet"] / $rawdata["TotalRevenue"]));
    $params[] = $max_min_array["LT_DebtasPercofInvestedCapital"][] = (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"])||is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?null:(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])));
    $params[] = $max_min_array["ST_DebtasPercofInvestedCapital"][] = ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?null:($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])));
    $params[] = $max_min_array["LT_DebtasPercofTotalDebt"][] = (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalLongtermDebt"]) && is_null($rawdata["TotalShorttermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?null:(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"])));
    $params[] = $max_min_array["ST_DebtasPercofTotalDebt"][] = ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?null:($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"])));
    $params[] = $max_min_array["TotalDebtPercofTotalAssets"][] = (((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]) / $rawdata["TotalAssets"]));
    $params[] = $max_min_array["WorkingCapitalPercofPrice"][] = (((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["TotalCurrentLiabilities"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||is_null($price)||$price==0)?null:((($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]) / (toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/$price));
    $params = array_merge($params,$params);
    array_unshift($params,$row["id"]);

    try {
        $res1 = $db->prepare($query);
        $res1->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("- Line: ".__LINE__." - ".$ex->getMessage());
    }
    $first = false;
}
keyratiosMinMax($pid, $max_min_array);

function keyratiosMinMax($pid, $max_min_array) {
    $db = Database::GetInstance();
    foreach($max_min_array as $key => $value) {
        $tmp_array = array_diff(array_slice($value,-5),array(null, "null"));
        sort($tmp_array);
        $max_min_array[$key] = $tmp_array;
    }
    for($step = 0; $step < 3; $step++) {
        if($step == 0) {
            $t1 = "5yr_min_key_ratios";
        } else if($step == 1) {
            $t1 = "5yr_median_key_ratios";
        } else {
            $t1 = "5yr_max_key_ratios";
        }
        $query = "INSERT INTO $t1 (`ticker_id`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?, ?, ?) ON DUPLICATE KEY UPDATE `ReportDatePrice`=?, `CashFlow`=?, `MarketCap`=?, `EnterpriseValue`=?, `GoodwillIntangibleAssetsNet`=?, `TangibleBookValue`=?, `ExcessCash`=?, `TotalInvestedCapital`=?, `WorkingCapital`=?, `P_E`=?, `P_E_CashAdjusted`=?, `EV_EBITDA`=?, `EV_EBIT`=?, `P_S`=?, `P_BV`=?, `P_Tang_BV`=?, `P_CF`=?, `P_FCF`=?, `P_OwnerEarnings`=?, `FCF_S`=?, `FCFYield`=?, `MagicFormulaEarningsYield`=?, `ROE`=?, `ROA`=?, `ROIC`=?, `CROIC`=?, `GPA`=?, `BooktoMarket`=?, `QuickRatio`=?, `CurrentRatio`=?, `TotalDebt_EquityRatio`=?, `LongTermDebt_EquityRatio`=?, `ShortTermDebt_EquityRatio`=?, `AssetTurnover`=?, `CashPercofRevenue`=?, `ReceivablesPercofRevenue`=?, `SG_APercofRevenue`=?, `R_DPercofRevenue`=?, `DaysSalesOutstanding`=?, `DaysInventoryOutstanding`=?, `DaysPayableOutstanding`=?, `CashConversionCycle`=?, `ReceivablesTurnover`=?, `InventoryTurnover`=?, `AverageAgeofInventory`=?, `IntangiblesPercofBookValue`=?, `InventoryPercofRevenue`=?, `LT_DebtasPercofInvestedCapital`=?, `ST_DebtasPercofInvestedCapital`=?, `LT_DebtasPercofTotalDebt`=?, `ST_DebtasPercofTotalDebt`=?, `TotalDebtPercofTotalAssets`=?, `WorkingCapitalPercofPrice`=?";
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
