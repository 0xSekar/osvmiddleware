<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout
	$query = "delete from reports_key_ratios";
	$res = mysql_query($query) or die (mysql_error());
	$query = "SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year";
	$res = mysql_query($query) or die (mysql_error());
	$pid = $arpy = $inpy = 0;
	$idChange = true;
	while ($row = mysql_fetch_assoc($res)) {
		if ($row["ticker_id"] != $pid) {
			$pid = $row["ticker_id"];
			$arpy = $inpy = 0;
			$idChange = true;
		} else {
			$arpy = $rawdata["AccountsReceivableTradeNet"];
			$inpy = $rawdata["InventoriesNet"];
			$idChange = false;
		}
		$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.id= " . $row["id"];
		$res2 = mysql_query($query) or die (mysql_error());
		$rawdata = mysql_fetch_assoc($res2);

                $rdate = date("Y-m-d",strtotime($rawdata["report_date"]));
                $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$rawdata["ticker_id"]."' and report_date = '".$rdate."'";
                $price = 0;
                $rquote = mysql_query($qquote) or die (mysql_error());
                if(mysql_num_rows($rquote) < 1) {
	                $rdate = date("Y-m-d",strtotime($rawdata["report_date"] . " - 2 days"));
                        $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$rawdata["ticker_id"]."' and report_date = '".$rdate."'";
                        $rquote = mysql_query($qquote) or die (mysql_error());
                        if(mysql_num_rows($rquote) < 1) {
	                        $rdate = date("Y-m-d",strtotime($rawdata["report_date"] . " - 4 days"));
                                $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$rawdata["ticker_id"]."' and report_date = '".$rdate."'";
                                $rquote = mysql_query($qquote) or die (mysql_error());
                                if(mysql_num_rows($rquote) > 0) {
                                        $price = mysql_fetch_assoc($rquote);
                                        $price = $price["close"];
                                }
                        } else {
                                $price = mysql_fetch_assoc($rquote);
                                $price = $price["close"];
                        }
                } else {
                        $price = mysql_fetch_assoc($rquote);
                        $price = $price["close"];
                }
		$entValue = ((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)+$rawdata["TotalLongtermDebt"]+$rawdata["TotalShorttermDebt"]+$rawdata["NotesPayable"]+$rawdata["PreferredStock"]+$rawdata["MinorityInterestEquityEarnings"]-$rawdata["CashCashEquivalentsandShorttermInvestments"]);
                $query = "INSERT INTO `reports_key_ratios` (`report_id`, `ReportYear`, `ReportDate`, `ReportDateAdjusted`, `SharesOutstandingDiluted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (";
                $query .= "'".$row["id"]."',";
		$query .= "'".$rawdata["fiscal_year"]."',";
		$query .= "'".date("Y-m-d",strtotime($rawdata["report_date"]))."',";
		$query .= "'".$rdate."',";
		$query .= "'".(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)."',";
		$query .= "'".$price."',";
		$query .= "'".($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-($rawdata["CapEx"]*(1-$rawdata["TaxRatePercent"])))."',";
		$query .= "'".(toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)."',";
		$query .= "'".$entValue."',";
		$query .= "'".$rawdata["GoodwillIntangibleAssetsNet"]."',";
		$query .= "'".($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])."',";
		$query .= "'".($rawdata["CashandCashEquivalents"] - max(0, ($rawdata["TotalCurrentLiabilities"]-$rawdata["TotalCurrentAssets"]+$rawdata["CashandCashEquivalents"])))."',";
		$query .= "'".($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"])."',";
		$query .= "'".($price / toFloat($rawdata["EPSDiluted"]))."',";
		$query .= "'".((((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)-$rawdata["CashCashEquivalentsandShorttermInvestments"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/toFloat($rawdata["EPSDiluted"]))."',";
		$query .= "'".($entValue / $rawdata["EBITDA"])."',";
		$query .= "'".($entValue / $rawdata["EBIT"])."',";
		$query .= "'".($price / ($rawdata["TotalRevenue"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / ($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / (($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / (($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-($rawdata["CapEx"]*(1-$rawdata["TaxRatePercent"])))/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / ($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($price / ($rawdata["OwnerEarningsFCF"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))."',";
		$query .= "'".($rawdata["FreeCashFlow"] / $rawdata["TotalRevenue"])."',";
		$query .= "'".($rawdata["TotalRevenue"] / $rawdata["FreeCashFlow"])."',";
		$query .= "'".($rawdata["EBIT"] / $entValue)."',";
		$query .= "'".($rawdata["CFNetIncome"] / $rawdata["TotalStockholdersEquity"])."',";
		$query .= "'".($rawdata["CFNetIncome"] / $rawdata["TotalAssets"])."',";
		$query .= "'".(($rawdata["EBIT"]*(1-$rawdata["TaxRatePercent"])) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"]))."',";
		$query .= "'".($rawdata["FreeCashFlow"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]+$rawdata["TotalStockholdersEquity"]))."',";
		$query .= "'".($rawdata["GrossProfit"] / $rawdata["TotalAssets"])."',";
		$query .= "'".($rawdata["TotalAssets"] / $rawdata["GrossProfit"])."',";
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
		$query .= "'".(($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365)+($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365)+($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365))."',";
		if($idChange==true) {
			$query .= "'0','0','0',";
		} else {
			$query .= "'".($rawdata["TotalRevenue"] / ($arpy + $rawdata["AccountsReceivableTradeNet"]))."',";
			$query .= "'".($rawdata["CostofRevenue"] / ($inpy + $rawdata["InventoriesNet"]))."',";
			$query .= "'".(365 / ($rawdata["CostofRevenue"] / ($inpy + $rawdata["InventoriesNet"])))."',";
		}
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
