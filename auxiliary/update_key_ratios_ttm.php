<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout
	$query = "delete from ttm_key_ratios";
	$res = mysql_query($query) or die (mysql_error());
	$query = "SELECT * FROM `ttm_balanceconsolidated` a, ttm_balancefull b, ttm_cashflowconsolidated c, ttm_cashflowfull d, ttm_financialscustom e, ttm_incomeconsolidated f, ttm_incomefull g, ttm_gf_data h WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id=d.ticker_id AND a.ticker_id=e.ticker_id AND a.ticker_id=f.ticker_id AND a.ticker_id=g.ticker_id and a.ticker_id=h.ticker_id";
	$res = mysql_query($query) or die (mysql_error());
	while ($rawdata = mysql_fetch_assoc($res)) {
                $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$rawdata["ticker_id"]."' order by report_date desc limit 1";
                $rdate = date("Y-m-d");
                $price = 0;
                $rquote = mysql_query($qquote) or die (mysql_error());
                if(mysql_num_rows($rquote) > 0) {
                        $price = mysql_fetch_assoc($rquote);
			$rdate = $price["report_date"];
                        $price = $price["adj_close"];
                }
		$entValue = ((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)+$rawdata["TotalLongtermDebt"]+$rawdata["TotalShorttermDebt"]+$rawdata["NotesPayable"]+$rawdata["PreferredStock"]+$rawdata["MinorityInterestEquityEarnings"]-$rawdata["CashCashEquivalentsandShorttermInvestments"]);
                $query = "INSERT INTO `ttm_key_ratios` (`ticker_id`, `ReportDateAdjusted`, `SharesOutstandingDiluted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (";
                $query .= "'".$rawdata["ticker_id"]."',";
		$query .= "'".$rdate."',";
		$query .= "'".(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)."',";
		$query .= "'".$price."',";
		$query .= "'".(($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"]))."',";
		$query .= "'".(toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)."',";
		$query .= "'".$entValue."',";
		$query .= "'".$rawdata["GoodwillIntangibleAssetsNet"]."',";
		$query .= "'".($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])."',";
		$query .= "'".($rawdata["CashCashEquivalentsandShorttermInvestments"] - max(0, ($rawdata["TotalCurrentLiabilities"]-$rawdata["TotalCurrentAssets"]+$rawdata["CashCashEquivalentsandShorttermInvestments"])))."',";
		$query .= "'".($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"])."',";
		$query .= "'".($price / toFloat($rawdata["EPSDiluted"]))."',";
		$query .= "'".((((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)-$rawdata["CashCashEquivalentsandShorttermInvestments"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/toFloat($rawdata["EPSDiluted"]))."',";
		$query .= "'".($entValue / $rawdata["EBITDA"])."',";
		$query .= "'".($entValue / $rawdata["EBIT"])."',";
		$query .= "'".($price / ($rawdata["TotalRevenue"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / ($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / (($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / ((($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"]))/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / ($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / ($rawdata["OwnerEarningsFCF"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($rawdata["FreeCashFlow"] / $rawdata["TotalRevenue"])."',";
		$query .= "'".(1 / ($price / ($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))))."',";
		$query .= "'".($rawdata["EBIT"] / $entValue)."',";
		$query .= "'".($rawdata["NetIncome"] / $rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".($rawdata["NetIncome"] / $rawdata["TotalAssets"])."',";
		$query .= "'".(($rawdata["EBIT"]*(1-$rawdata["TaxRatePercent"])) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"]))."',";
		$query .= "'".($rawdata["FreeCashFlow"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"]))."',";
		$query .= "'".($rawdata["GrossProfit"] / $rawdata["TotalAssets"])."',";
		$query .= "'".(1 / ($price / ($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))))."',";
		$query .= "'".(($rawdata["TotalCurrentAssets"] - $rawdata["InventoriesNet"]) / $rawdata["TotalCurrentLiabilities"])."',";
		$query .= "'".($rawdata["TotalCurrentAssets"] / $rawdata["TotalCurrentLiabilities"])."',";
		$query .= "'".(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]) / $rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".(($rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]) / $rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".($rawdata["TotalShorttermDebt"] / $rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".($rawdata["TotalRevenue"] / $rawdata["TotalAssets"])."',";
		$query .= "'".($rawdata["CashCashEquivalentsandShorttermInvestments"] / $rawdata["TotalRevenue"])."',";
		$query .= "'".($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"])."',";
		$query .= "'".($rawdata["SellingGeneralAdministrativeExpenses"] / $rawdata["TotalRevenue"])."',";
		$query .= "'".($rawdata["ResearchDevelopmentExpense"] / $rawdata["TotalRevenue"])."',";
		$query .= "'".($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365)."',";
		$query .= "'".($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365)."',";
		$query .= "'".($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365)."',";
		$query .= "'".(($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365)+($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365)-($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365))."',";
		$query .= "'".($rawdata["TotalRevenue"] / ($rawdata["AccountsReceivableTradeNet"]))."',";
		$query .= "'".($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"]))."',";
		$query .= "'".(365 / ($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"])))."',";
		$query .= "'".($rawdata["GoodwillIntangibleAssetsNet"] / $rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".($rawdata["InventoriesNet"] / $rawdata["TotalRevenue"])."',";
		$query .= "'".(($rawdata["TotalLongtermDebt"] + $rawdata["NotesPayable"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"]))."',";
		$query .= "'".($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"]))."',";
		$query .= "'".(($rawdata["TotalLongtermDebt"] + $rawdata["NotesPayable"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]))."',";
		$query .= "'".($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]))."',";
		$query .= "'".(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]) / $rawdata["TotalAssets"])."',";
		$query .= "'".((($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]) / (toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/$price)."'";
                $query .= ")";
		mysql_query($query) or die (mysql_error());
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
