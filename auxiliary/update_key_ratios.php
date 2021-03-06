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
			$arpy = $rawdata["AccountsReceivableTradeNet"]=='null'?null:$rawdata["AccountsReceivableTradeNet"];
			$inpy = $rawdata["InventoriesNet"]=='null'?null:$rawdata["InventoriesNet"];
			$idChange = false;
		}
		$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.id= " . $row["id"];
		$res2 = mysql_query($query) or die (mysql_error());
		$rawdata = mysql_fetch_assoc($res2);

                $rdate = date("Y-m-d",strtotime($rawdata["report_date"]));
                $qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$rawdata["ticker_id"]."' and report_date <= '".$rdate."' order by report_date desc limit 1";
                $price = null;
                $rquote = mysql_query($qquote) or die (mysql_error());
                if(mysql_num_rows($rquote) > 0) {
                        $price = mysql_fetch_assoc($rquote);
	                $rdate = $price["report_date"];
                        $price = $price["adj_close"];
                }
		$entValue = ((is_null($rawdata["SharesOutstandingDiluted"]) && is_null($price) && is_null($rawdata["TotalLongtermDebt"]) && is_null($rawdata["TotalShorttermDebt"]) && is_null($rawdata["PreferredStock"]) && is_null($rawdata["MinorityInterestEquityEarnings"]) && is_null($rawdata["CashCashEquivalentsandShorttermInvestments"]))?null:((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)+$rawdata["TotalLongtermDebt"]+$rawdata["TotalShorttermDebt"]+$rawdata["PreferredStock"]+$rawdata["MinorityInterestEquityEarnings"]-$rawdata["CashCashEquivalentsandShorttermInvestments"]));
                $query = "INSERT INTO `reports_key_ratios` (`report_id`, `ReportYear`, `ReportDate`, `ReportDateAdjusted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (";
                $query .= "'".$row["id"]."',";
		$query .= $rawdata["fiscal_year"].",";
		$query .= "'".date("Y-m-d",strtotime($rawdata["report_date"]))."',";
		$query .= ($rdate == '0000-00-00'?'null':"'".$rdate."'").",";
		$query .= "'".$price."',";
		$query .= (((is_null($rawdata["GrossProfit"]) && is_null($rawdata["OperatingExpenses"]) && is_null($rawdata["CapEx"])) || is_null($rawdata["TaxRatePercent"]))?'null':(($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"]))).",";
		$query .= ((is_null($rawdata["SharesOutstandingDiluted"])||is_null($price))?'null':(toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)).",";
		$query .= "'".$entValue."',";
		$query .= ((is_null($rawdata["GoodwillIntangibleAssetsNet"]))?'null':$rawdata["GoodwillIntangibleAssetsNet"]).",";
		$query .= ((is_null($rawdata["TotalStockholdersEquity"])&&is_null($rawdata["GoodwillIntangibleAssetsNet"]))?'null':($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])).",";
		$query .= ((is_null($rawdata["CashCashEquivalentsandShorttermInvestments"]) || (is_null($rawdata["CashCashEquivalentsandShorttermInvestments"])&&is_null($rawdata["TotalCurrentLiabilities"])&&is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["LongtermInvestments"])))?'null':(($rawdata["CashCashEquivalentsandShorttermInvestments"] + $rawdata["LongtermInvestments"]) - max(0, ($rawdata["TotalCurrentLiabilities"]-$rawdata["TotalCurrentAssets"]+$rawdata["CashCashEquivalentsandShorttermInvestments"])))).",";
		$query .= ((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))?'null':($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])).",";
		$query .= ((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["TotalCurrentLiabilities"]))?'null':($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"])).",";
		$query .= ((is_null($price)||is_null($rawdata["EPSDiluted"])||$rawdata["EPSDiluted"]==0)?'null':($price / toFloat($rawdata["EPSDiluted"]))).",";
		$query .= ((is_null($rawdata["SharesOutstandingDiluted"])||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||is_null($rawdata["EPSDiluted"])||$rawdata["EPSDiluted"]==0)?'null':((((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)-$rawdata["CashCashEquivalentsandShorttermInvestments"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/toFloat($rawdata["EPSDiluted"]))).",";
		$query .= ((is_null($entValue)||is_null($rawdata["EBITDA"])||$rawdata["EBITDA"]==0)?'null':($entValue / $rawdata["EBITDA"])).",";
		$query .= ((is_null($entValue)||is_null($rawdata["EBIT"])||$rawdata["EBIT"]==0)?'null':($entValue / $rawdata["EBIT"])).",";
		$query .= ((is_null($price)||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?'null':($price / ($rawdata["TotalRevenue"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))).",";
		$query .= ((is_null($price)||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?'null':($price / ($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))).",";
		$query .= ((is_null($price)||(is_null($rawdata["TotalStockholdersEquity"])&&is_null($rawdata["GoodwillIntangibleAssetsNet"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"]==0))?'null':($price / (($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))).",";
		$query .= ((is_null($price)||(is_null($rawdata["GrossProfit"])&&is_null($rawdata["OperatingExpenses"])&&is_null($rawdata["CapEx"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"]==0)||$rawdata["TaxRatePercent"]==1)?'null':($price / ((($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"]))/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))).",";
		$query .= ((is_null($price)||is_null($rawdata["FreeCashFlow"])||$rawdata["FreeCashFlow"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?'null':($price / ($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))).",";
		$query .= ((is_null($price)||is_null($rawdata["OwnerEarningsFCF"])||$rawdata["OwnerEarningsFCF"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?'null':($price / ($rawdata["OwnerEarningsFCF"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))).",";
		$query .= ((is_null($rawdata["FreeCashFlow"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["FreeCashFlow"] / $rawdata["TotalRevenue"])).",";
		$query .= ((is_null($price)||$price==0||is_null($rawdata["FreeCashFlow"])||$rawdata["FreeCashFlow"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?'null':(1 / ($price / ($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))))).",";
		$query .= ((is_null($rawdata["EBIT"])||is_null($entValue)||$entValue==0)?'null':($rawdata["EBIT"] / $entValue)).",";
		$query .= ((is_null($rawdata["NetIncome"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?'null':($rawdata["NetIncome"] / $rawdata["TotalStockholdersEquity"])).",";
		$query .= ((is_null($rawdata["NetIncome"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?'null':($rawdata["NetIncome"] / $rawdata["TotalAssets"])).",";
		$query .= ((is_null($rawdata["EBIT"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?'null':(($rawdata["EBIT"]*(1-$rawdata["TaxRatePercent"])) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]))).",";
		$query .= ((is_null($rawdata["FreeCashFlow"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?'null':($rawdata["FreeCashFlow"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]))).",";
		$query .= ((is_null($rawdata["GrossProfit"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?'null':($rawdata["GrossProfit"] / $rawdata["TotalAssets"])).",";
		$query .= ((is_null($price)||$price==0||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?'null':(1 / ($price / ($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))))).",";
		$query .= (((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["InventoriesNet"]))||is_null($rawdata["TotalCurrentLiabilities"])||$rawdata["TotalCurrentLiabilities"]==0)?'null':(($rawdata["TotalCurrentAssets"] - $rawdata["InventoriesNet"]) / $rawdata["TotalCurrentLiabilities"])).",";
		$query .= ((is_null($rawdata["TotalCurrentAssets"])||is_null($rawdata["TotalCurrentLiabilities"])||$rawdata["TotalCurrentLiabilities"]==0)?'null':($rawdata["TotalCurrentAssets"] / $rawdata["TotalCurrentLiabilities"])).",";
		$query .= (((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?'null':(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]) / $rawdata["TotalStockholdersEquity"])).",";
		$query .= (((is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?'null':(($rawdata["TotalLongtermDebt"]) / $rawdata["TotalStockholdersEquity"])).",";
		$query .= ((is_null($rawdata["TotalShorttermDebt"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?'null':($rawdata["TotalShorttermDebt"] / $rawdata["TotalStockholdersEquity"])).",";
		$query .= ((is_null($rawdata["TotalRevenue"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?'null':($rawdata["TotalRevenue"] / $rawdata["TotalAssets"])).",";
		$query .= ((is_null($rawdata["CashCashEquivalentsandShorttermInvestments"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["CashCashEquivalentsandShorttermInvestments"] / $rawdata["TotalRevenue"])).",";
		$query .= ((is_null($rawdata["TotalReceivablesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"])).",";
		$query .= ((is_null($rawdata["SellingGeneralAdministrativeExpenses"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"] / $rawdata["TotalRevenue"])).",";
		$query .= ((is_null($rawdata["ResearchDevelopmentExpense"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["ResearchDevelopmentExpense"] / $rawdata["TotalRevenue"])).",";
		$query .= ((is_null($rawdata["TotalReceivablesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365)).",";
		$query .= ((is_null($rawdata["InventoriesNet"])||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?'null':($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365)).",";
		$query .= ((is_null($rawdata["AccountsPayable"])||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?'null':($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365)).",";
		$query .= ((is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?'null':(($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365)+($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365)-($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365))).",";
		if($idChange==true) {
			$query .= ((is_null($rawdata["TotalRevenue"])||is_null($rawdata["AccountsReceivableTradeNet"])||$rawdata["AccountsReceivableTradeNet"]==0)?'null':($rawdata["TotalRevenue"] / ($rawdata["AccountsReceivableTradeNet"]))).",";
			$query .= ((is_null($rawdata["CostofRevenue"])||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?'null':($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"]))).",";
			$query .= ((is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?'null':(365 / ($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"])))).",";
		} else {
			$query .= ((is_null($rawdata["TotalRevenue"])||(is_null($rawdata["AccountsReceivableTradeNet"])&&is_null($arpy))||($rawdata["AccountsReceivableTradeNet"]+$arpy==0))?'null':($rawdata["TotalRevenue"] / (($arpy + $rawdata["AccountsReceivableTradeNet"])/2))).",";
			$query .= ((is_null($rawdata["CostofRevenue"])||(is_null($rawdata["InventoriesNet"])&&is_null($inpy))||($rawdata["InventoriesNet"]+$inpy==0))?'null':($rawdata["CostofRevenue"] / (($inpy + $rawdata["InventoriesNet"])/2))).",";
			$query .= ((is_null($rawdata["CostofRevenue"])||(is_null($rawdata["InventoriesNet"])&&is_null($inpy))||($rawdata["InventoriesNet"]+$inpy==0)||$rawdata["CostofRevenue"]==0)?'null':(365 / ($rawdata["CostofRevenue"] / (($inpy + $rawdata["InventoriesNet"])/2)))).",";
		}
		$query .= ((is_null($rawdata["GoodwillIntangibleAssetsNet"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?'null':($rawdata["GoodwillIntangibleAssetsNet"] / $rawdata["TotalStockholdersEquity"])).",";
		$query .= ((is_null($rawdata["InventoriesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["InventoriesNet"] / $rawdata["TotalRevenue"])).",";
		$query .= (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"])||is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?'null':(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]))).",";
		$query .= ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?'null':($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]))).",";
		$query .= (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalLongtermDebt"]) && is_null($rawdata["TotalShorttermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?'null':(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]))).",";
		$query .= ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?'null':($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]))).",";
		$query .= (((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?'null':(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]) / $rawdata["TotalAssets"])).",";
		$query .= (((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["TotalCurrentLiabilities"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||is_null($price)||$price==0)?'null':((($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]) / (toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/$price));
                $query .= ")";
		mysql_query($query) or die ($query."\n".mysql_error());
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
