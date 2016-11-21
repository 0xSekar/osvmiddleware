<?php
function update_key_ratios_ttm($ti = null) {
	$db = Database::GetInstance();
	if (is_null($ti)) {
		//$query = "delete from ttm_key_ratios";
		//$res = mysql_query($query) or die (mysql_error());
		try {
			$res = $db->query("delete from ttm_key_ratios");		
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		//$query = "SELECT * FROM `ttm_balanceconsolidated` a, ttm_balancefull b, ttm_cashflowconsolidated c, ttm_cashflowfull d, ttm_financialscustom e, ttm_incomeconsolidated f, ttm_incomefull g, ttm_gf_data h WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id=d.ticker_id AND a.ticker_id=e.ticker_id AND a.ticker_id=f.ticker_id AND a.ticker_id=g.ticker_id and a.ticker_id=h.ticker_id";
		//$res = mysql_query($query) or die (mysql_error());
		try {
			$res = $db->query("SELECT * FROM `ttm_balanceconsolidated` a, ttm_balancefull b, ttm_cashflowconsolidated c, ttm_cashflowfull d, ttm_financialscustom e, ttm_incomeconsolidated f, ttm_incomefull g, ttm_gf_data h WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id=d.ticker_id AND a.ticker_id=e.ticker_id AND a.ticker_id=f.ticker_id AND a.ticker_id=g.ticker_id and a.ticker_id=h.ticker_id");		
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	} else {
		//$query = "delete from ttm_key_ratios where ticker_id = $ti";
		//$res = mysql_query($query) or die (mysql_error());
		try {
			$res = $db->query("delete from ttm_key_ratios where ticker_id = $ti");		
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		//$query = "SELECT * FROM `ttm_balanceconsolidated` a, ttm_balancefull b, ttm_cashflowconsolidated c, ttm_cashflowfull d, ttm_financialscustom e, ttm_incomeconsolidated f, ttm_incomefull g, ttm_gf_data h WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id=d.ticker_id AND a.ticker_id=e.ticker_id AND a.ticker_id=f.ticker_id AND a.ticker_id=g.ticker_id and a.ticker_id=h.ticker_id and a.ticker_id = $ti";
		//$res = mysql_query($query) or die (mysql_error());
		try {
			$res = $db->query("SELECT * FROM `ttm_balanceconsolidated` a, ttm_balancefull b, ttm_cashflowconsolidated c, ttm_cashflowfull d, ttm_financialscustom e, ttm_incomeconsolidated f, ttm_incomefull g, ttm_gf_data h WHERE a.ticker_id=b.ticker_id AND a.ticker_id=c.ticker_id AND a.ticker_id=d.ticker_id AND a.ticker_id=e.ticker_id AND a.ticker_id=f.ticker_id AND a.ticker_id=g.ticker_id and a.ticker_id=h.ticker_id and a.ticker_id = $ti");		
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
	//while ($rawdata = mysql_fetch_assoc($res)) {
	while ($rawdata = $res->fetch(PDO::FETCH_ASSOC)) {
        $qquote = "SELECT * FROM tickers_yahoo_historical_data WHERE ticker_id = '".$rawdata["ticker_id"]."' ORDER BY report_date desc limit 1";
        $rdate = date("Y-m-d");
        $price = null;
        //$rquote = mysql_query($qquote) or die (mysql_error());
        try {
			$rquote = $db->query($qquote);		
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
        //if(mysql_num_rows($rquote) > 0) {
		$row_count = $rquote->rowCount();
        if($row_count > 0) {
                //$price = mysql_fetch_assoc($rquote);
                $price = $rquote->fetch(PDO::FETCH_ASSOC);
				$rdate = $price["report_date"];
                $price = $price["adj_close"];
		}
		$entValue = ((is_null($rawdata["SharesOutstandingDiluted"]) && is_null($price) && is_null($rawdata["TotalLongtermDebt"]) && is_null($rawdata["TotalShorttermDebt"]) && is_null($rawdata["PreferredStock"]) && is_null($rawdata["MinorityInterestEquityEarnings"]) && is_null($rawdata["CashCashEquivalentsandShorttermInvestments"]))?null:((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)+$rawdata["TotalLongtermDebt"]+$rawdata["TotalShorttermDebt"]+$rawdata["PreferredStock"]+$rawdata["MinorityInterestEquityEarnings"]-$rawdata["CashCashEquivalentsandShorttermInvestments"]));
        //$query = "INSERT INTO `ttm_key_ratios` (`ticker_id`, `ReportDateAdjusted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (";
        $query = "INSERT INTO `ttm_key_ratios` (`ticker_id`, `ReportDateAdjusted`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//55par
        /*$query .= "'".$rawdata["ticker_id"]."',";
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
		$query .= ((is_null($rawdata["TotalRevenue"])||is_null($rawdata["AccountsReceivableTradeNet"])||$rawdata["AccountsReceivableTradeNet"]==0)?'null':($rawdata["TotalRevenue"] / ($rawdata["AccountsReceivableTradeNet"]))).",";
		$query .= ((is_null($rawdata["CostofRevenue"])||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?'null':($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"]))).",";
		$query .= ((is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?'null':(365 / ($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"])))).",";
		$query .= ((is_null($rawdata["GoodwillIntangibleAssetsNet"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?'null':($rawdata["GoodwillIntangibleAssetsNet"] / $rawdata["TotalStockholdersEquity"])).",";
		$query .= ((is_null($rawdata["InventoriesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?'null':($rawdata["InventoriesNet"] / $rawdata["TotalRevenue"])).",";
		$query .= (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"])||is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?'null':(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]))).",";
		$query .= ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?'null':($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]))).",";
		$query .= (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalShorttermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?'null':(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]))).",";
		$query .= ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?'null':($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]))).",";
		$query .= (((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?'null':(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]) / $rawdata["TotalAssets"])).",";
		$query .= (((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["TotalCurrentLiabilities"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||is_null($price)||$price==0)?'null':((($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]) / (toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/$price));
        $query .= ")";*/
        $params = array();
        $params[] = $rawdata["ticker_id"];
		$params[] = ($rdate == '0000-00-00'?null:$rdate);
		$params[] = $price;
		$params[] = (((is_null($rawdata["GrossProfit"])&&is_null($rawdata["OperatingExpenses"])&&is_null($rawdata["CapEx"])) || is_null($rawdata["TaxRatePercent"]))?null:(($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"])));
		$params[] = ((is_null($rawdata["SharesOutstandingDiluted"])||is_null($price))?null:(toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price));
		$params[] = $entValue;
		$params[] = ((is_null($rawdata["GoodwillIntangibleAssetsNet"]))?null:$rawdata["GoodwillIntangibleAssetsNet"]);
		$params[] = ((is_null($rawdata["TotalStockholdersEquity"])&&is_null($rawdata["GoodwillIntangibleAssetsNet"]))?null:($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"]));
		$params[] = ((is_null($rawdata["CashCashEquivalentsandShorttermInvestments"])||(is_null($rawdata["CashCashEquivalentsandShorttermInvestments"])&&is_null($rawdata["TotalCurrentLiabilities"])&&is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["LongtermInvestments"])))?null:(($rawdata["CashCashEquivalentsandShorttermInvestments"] + $rawdata["LongtermInvestments"]) - max(0, ($rawdata["TotalCurrentLiabilities"]-$rawdata["TotalCurrentAssets"]+$rawdata["CashCashEquivalentsandShorttermInvestments"]))));
		$params[] = ((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))?null:($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]));
		$params[] = ((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["TotalCurrentLiabilities"]))?null:($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]));
		$params[] = ((is_null($price)||is_null($rawdata["EPSDiluted"])||$rawdata["EPSDiluted"]==0)?null:($price / toFloat($rawdata["EPSDiluted"])));
		$params[] = ((is_null($rawdata["SharesOutstandingDiluted"])||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||is_null($rawdata["EPSDiluted"])||$rawdata["EPSDiluted"]==0)?null:((((toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price)-$rawdata["CashCashEquivalentsandShorttermInvestments"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/toFloat($rawdata["EPSDiluted"])));
		$params[] = ((is_null($entValue)||is_null($rawdata["EBITDA"])||$rawdata["EBITDA"]==0)?null:($entValue / $rawdata["EBITDA"]));
		$params[] = ((is_null($entValue)||is_null($rawdata["EBIT"])||$rawdata["EBIT"]==0)?null:($entValue / $rawdata["EBIT"]));
		$params[] = ((is_null($price)||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price / ($rawdata["TotalRevenue"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
		$params[] = ((is_null($price)||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price/($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
		$params[] = ((is_null($price)||(is_null($rawdata["TotalStockholdersEquity"])&&is_null($rawdata["GoodwillIntangibleAssetsNet"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"]==0))?null:($price / (($rawdata["TotalStockholdersEquity"] - $rawdata["GoodwillIntangibleAssetsNet"])/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
		$params[] = ((is_null($price)||(is_null($rawdata["GrossProfit"])&&is_null($rawdata["OperatingExpenses"])&&is_null($rawdata["CapEx"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"]==0)||$rawdata["TaxRatePercent"]==1)?null:($price / ((($rawdata["GrossProfit"]-$rawdata["OperatingExpenses"]-$rawdata["CapEx"])*(1-$rawdata["TaxRatePercent"]))/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
		$params[] = ((is_null($price)||is_null($rawdata["FreeCashFlow"])||$rawdata["FreeCashFlow"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price/($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
		$params[] = ((is_null($price)||is_null($rawdata["OwnerEarningsFCF"])||$rawdata["OwnerEarningsFCF"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:($price/($rawdata["OwnerEarningsFCF"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000))));
		$params[] = ((is_null($rawdata["FreeCashFlow"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["FreeCashFlow"] / $rawdata["TotalRevenue"]));
		$params[] = ((is_null($price)||$price==0||is_null($rawdata["FreeCashFlow"])||$rawdata["FreeCashFlow"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:(1/($price/($rawdata["FreeCashFlow"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))));
		$params[] = ((is_null($rawdata["EBIT"])||is_null($entValue)||$entValue==0)?null:($rawdata["EBIT"]/$entValue));
		$params[] = ((is_null($rawdata["NetIncome"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:($rawdata["NetIncome"]/$rawdata["TotalStockholdersEquity"]));
		$params[] = ((is_null($rawdata["NetIncome"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:($rawdata["NetIncome"] / $rawdata["TotalAssets"]));
		$params[] = ((is_null($rawdata["EBIT"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?null:(($rawdata["EBIT"]*(1-$rawdata["TaxRatePercent"]))/($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])));
		$params[] = ((is_null($rawdata["FreeCashFlow"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?null:($rawdata["FreeCashFlow"]/($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])));
		$params[] = ((is_null($rawdata["GrossProfit"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:($rawdata["GrossProfit"]/$rawdata["TotalAssets"]));
		$params[] = ((is_null($price)||$price==0||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0)?null:(1/($price/($rawdata["TotalStockholdersEquity"]/(toFloat($rawdata["SharesOutstandingDiluted"])*1000000)))));
		$params[] = (((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["InventoriesNet"]))||is_null($rawdata["TotalCurrentLiabilities"])||$rawdata["TotalCurrentLiabilities"]==0)?null:(($rawdata["TotalCurrentAssets"] - $rawdata["InventoriesNet"])/$rawdata["TotalCurrentLiabilities"]));
		$params[] = ((is_null($rawdata["TotalCurrentAssets"])||is_null($rawdata["TotalCurrentLiabilities"])||$rawdata["TotalCurrentLiabilities"]==0)?null:($rawdata["TotalCurrentAssets"]/$rawdata["TotalCurrentLiabilities"]));
		$params[] = (((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"])/$rawdata["TotalStockholdersEquity"]));
		$params[] = (((is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:(($rawdata["TotalLongtermDebt"])/$rawdata["TotalStockholdersEquity"]));
		$params[] = ((is_null($rawdata["TotalShorttermDebt"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:($rawdata["TotalShorttermDebt"]/$rawdata["TotalStockholdersEquity"]));
		$params[] = ((is_null($rawdata["TotalRevenue"])||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:($rawdata["TotalRevenue"]/$rawdata["TotalAssets"]));
		$params[] = ((is_null($rawdata["CashCashEquivalentsandShorttermInvestments"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["CashCashEquivalentsandShorttermInvestments"] / $rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["TotalReceivablesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["TotalReceivablesNet"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["SellingGeneralAdministrativeExpenses"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"] / $rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["ResearchDevelopmentExpense"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["ResearchDevelopmentExpense"] / $rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["TotalReceivablesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365));
		$params[] = ((is_null($rawdata["InventoriesNet"])||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?null:($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365));
		$params[] = ((is_null($rawdata["AccountsPayable"])||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?null:($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365));
		$params[] = ((is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0||is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0)?null:(($rawdata["TotalReceivablesNet"] / $rawdata["TotalRevenue"] * 365)+($rawdata["InventoriesNet"] / $rawdata["CostofRevenue"] * 365)-($rawdata["AccountsPayable"] / $rawdata["CostofRevenue"] * 365)));
		$params[] = ((is_null($rawdata["TotalRevenue"])||is_null($rawdata["AccountsReceivableTradeNet"])||$rawdata["AccountsReceivableTradeNet"]==0)?null:($rawdata["TotalRevenue"] / ($rawdata["AccountsReceivableTradeNet"])));
		$params[] = ((is_null($rawdata["CostofRevenue"])||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?null:($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"])));
		$params[] = ((is_null($rawdata["CostofRevenue"])||$rawdata["CostofRevenue"]==0||is_null($rawdata["InventoriesNet"])||$rawdata["InventoriesNet"]==0)?null:(365 / ($rawdata["CostofRevenue"] / ($rawdata["InventoriesNet"]))));
		$params[] = ((is_null($rawdata["GoodwillIntangibleAssetsNet"])||is_null($rawdata["TotalStockholdersEquity"])||$rawdata["TotalStockholdersEquity"]==0)?null:($rawdata["GoodwillIntangibleAssetsNet"] / $rawdata["TotalStockholdersEquity"]));
		$params[] = ((is_null($rawdata["InventoriesNet"])||is_null($rawdata["TotalRevenue"])||$rawdata["TotalRevenue"]==0)?null:($rawdata["InventoriesNet"] / $rawdata["TotalRevenue"]));
		$params[] = (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"])||is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?null:(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])));
		$params[] = ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["CurrentPortionofLongtermDebt"])&&is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalStockholdersEquity"]))||($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"]==0))?null:($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["CurrentPortionofLongtermDebt"]+$rawdata["TotalLongtermDebt"]+$rawdata["TotalStockholdersEquity"])));
		$params[] = (((is_null($rawdata["TotalLongtermDebt"]))||(is_null($rawdata["TotalLongtermDebt"])&&is_null($rawdata["TotalShorttermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?null:(($rawdata["TotalLongtermDebt"]) / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"])));
		$params[] = ((is_null($rawdata["TotalShorttermDebt"])||(is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]==0))?null:($rawdata["TotalShorttermDebt"] / ($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"])));
		$params[] = (((is_null($rawdata["TotalShorttermDebt"])&&is_null($rawdata["TotalLongtermDebt"]))||is_null($rawdata["TotalAssets"])||$rawdata["TotalAssets"]==0)?null:(($rawdata["TotalShorttermDebt"]+$rawdata["TotalLongtermDebt"]) / $rawdata["TotalAssets"]));
		$params[] = (((is_null($rawdata["TotalCurrentAssets"])&&is_null($rawdata["TotalCurrentLiabilities"]))||is_null($rawdata["SharesOutstandingDiluted"])||$rawdata["SharesOutstandingDiluted"]==0||is_null($price)||$price==0)?null:((($rawdata["TotalCurrentAssets"] - $rawdata["TotalCurrentLiabilities"]) / (toFloat($rawdata["SharesOutstandingDiluted"])*1000000))/$price));
		//mysql_query($query) or die ($query."\n".mysql_error());
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
