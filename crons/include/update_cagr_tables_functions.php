<?php
function updateCAGR($table, $fieldArray, $years, $period, $report_id, $rawdata, $toFloat = false) {
	$query = "INSERT INTO `$table` (`report_id`";
	foreach ($fieldArray as $value) {
		$query .= ",`$value`";
	}
	$query .= ") VALUES (";
	$query .= "'".$report_id."'";
	foreach ($fieldArray as $value) {
        	if ($rawdata[$value][$period]=='null' || $rawdata[$value][$period-$years]=='null' || $rawdata[$value][$period-$years]==0 || ($rawdata[$value][$period] < 0 && $rawdata[$value][$period-$years] > 0) || ($rawdata[$value][$period] > 0 && $rawdata[$value][$period-$years] < 0)) {
	        	$query .= ",null";
	        } else {
			if ($toFloat) {
				if ($rawdata[$value][$period] > 0) {
	        	        	$query .= ",".(pow(toFloat($rawdata[$value][$period])/toFloat($rawdata[$value][$period-$years]), 1/$years) - 1);
				} else {
	        	        	$query .= ",".((pow(toFloat($rawdata[$value][$period])/toFloat($rawdata[$value][$period-$years]), 1/$years) - 1) * -1);
				}
			} else {
				if ($rawdata[$value][$period] > 0) {
        		        	$query .= ",".(pow($rawdata[$value][$period]/$rawdata[$value][$period-$years], 1/$years) - 1);
				} else {
        		        	$query .= ",".((pow($rawdata[$value][$period]/$rawdata[$value][$period-$years], 1/$years) - 1) * -1);
				}
			}
	        }
	}
        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
}

function updateCAGR_concat($vv, $va, $years) {
        if ($va=='null' || $vv=='null' || $vv==0 || ($va < 0 && $vv > 0) || ($va > 0 && $vv < 0)) {
                return ",null";
        } else {
		if ($va > 0) {
	                return ",".(pow($va/$vv, 1/$years) - 1);
		} else {
	                return ",".((pow($va/$vv, 1/$years) - 1) * -1);
		}
        }
}

function updateCAGR_FC($table, $years, $i, $report_id, $rawdata) {
        $query = "INSERT INTO `$table` (`report_id`";
	$query .= ", `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `SalesPercChange`) VALUES (";
        $query .= "'".$report_id."'";

        $va = (($rawdata["CostofRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CostofRevenue"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["CostofRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["CostofRevenue"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["GrossProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["GrossProfit"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["GrossProfit"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["GrossProfit"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null' ||  $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years]=='null' ||  $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["ResearchDevelopmentExpense"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["ResearchDevelopmentExpense"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CFDepreciationAmortization"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CFDepreciationAmortization"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["CFDepreciationAmortization"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["CFDepreciationAmortization"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["EBITDA"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["EBITDA"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["EBITDA"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["EBITDA"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["OperatingProfit"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["OperatingProfit"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["OperatingProfit"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["OperatingProfit"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["EBIT"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["EBIT"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["EBIT"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["EBIT"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["IncomeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]=='null' || $rawdata["IncomeBeforeTaxes"][$i]==0)?'null':($rawdata["IncomeTaxes"][$i]/$rawdata["IncomeBeforeTaxes"][$i]));
        $vv = (($rawdata["IncomeTaxes"][$i-$years]=='null' || $rawdata["IncomeBeforeTaxes"][$i-$years]=='null' || $rawdata["IncomeBeforeTaxes"][$i-$years]==0)?'null':($rawdata["IncomeTaxes"][$i-$years]/$rawdata["IncomeBeforeTaxes"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["IncomeBeforeTaxes"][$i]=='null' && $rawdata["IncomeTaxes"][$i]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$i]-$rawdata["IncomeTaxes"][$i]));
        $vv = (($rawdata["IncomeBeforeTaxes"][$i-$years]=='null' && $rawdata["IncomeTaxes"][$i-$years]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$i-$years]-$rawdata["IncomeTaxes"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["NetIncome"][$i]=='null' || $rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["NetIncome"][$i]/$rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["NetIncome"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]=='null' || $rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["NetIncome"][$i-$years]/$rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["DividendsPaid"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]=='null' || $rawdata["SharesOutstandingBasic"][$i]==0)?'null':(-($rawdata["DividendsPaid"][$i])/(toFloat($rawdata["SharesOutstandingBasic"][$i])*1000000)));
        $vv = (($rawdata["DividendsPaid"][$i-$years]=='null' || $rawdata["SharesOutstandingBasic"][$i-$years]=='null' || $rawdata["SharesOutstandingBasic"][$i-$years]==0)?'null':(-($rawdata["DividendsPaid"][$i-$years])/(toFloat($rawdata["SharesOutstandingBasic"][$i-$years])*1000000)));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CurrentPortionofLongtermDebt"][$i]=='null' && $rawdata["ShorttermBorrowings"][$i]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["ShorttermBorrowings"][$i]));
        $vv = (($rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null' && $rawdata["ShorttermBorrowings"][$i-$years]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["ShorttermBorrowings"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["NotesPayable"][$i]=='null')?'null':($rawdata["TotalLongtermDebt"][$i]+$rawdata["NotesPayable"][$i]));
        $vv = (($rawdata["TotalLongtermDebt"][$i-$years]=='null' && $rawdata["NotesPayable"][$i-$years]=='null')?'null':($rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["NotesPayable"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["LongtermDebtProceeds"][$i]=='null' && $rawdata["LongtermDebtPayments"][$i] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$i]+$rawdata["LongtermDebtPayments"][$i]));
        $vv = (($rawdata["LongtermDebtProceeds"][$i-$years]=='null' && $rawdata["LongtermDebtPayments"][$i-$years] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$i-$years]+$rawdata["LongtermDebtPayments"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CapitalExpenditures"][$i]=='null')?'null':(-$rawdata["CapitalExpenditures"][$i]));
        $vv = (($rawdata["CapitalExpenditures"][$i-$years]=='null')?'null':(-$rawdata["CapitalExpenditures"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?'null':($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i]));
        $vv = (($rawdata["CashfromOperatingActivities"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null')?'null':($rawdata["CashfromOperatingActivities"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?'null':($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i])));
        $vv = (($rawdata["CFNetIncome"][$i-$years]=='null' && $rawdata["CFDepreciationAmortization"][$i-$years]=='null' && $rawdata["EmployeeCompensation"][$i-$years]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i-$years]=='null' && $rawdata["DeferredIncomeTaxes"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null' && $rawdata["ChangeinCurrentAssets"][$i-$years]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i-$years]=='null')?'null':($rawdata["CFNetIncome"][$i-$years]+$rawdata["CFDepreciationAmortization"][$i-$years]+$rawdata["EmployeeCompensation"][$i-$years]+$rawdata["AdjustmentforSpecialCharges"][$i-$years]+$rawdata["DeferredIncomeTaxes"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]+($rawdata["ChangeinCurrentAssets"][$i-$years]+$rawdata["ChangeinCurrentLiabilities"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        if ($i - $years > 1) {
                $va = ((($rawdata["TotalRevenue"][$i]=='null' && $rawdata["TotalRevenue"][$i-1]=='null') || $rawdata["TotalRevenue"][$i-1]=='null' || $rawdata["TotalRevenue"][$i-1]==0)?'null':(($rawdata["TotalRevenue"][$i]-$rawdata["TotalRevenue"][$i-1])/$rawdata["TotalRevenue"][$i-1]));
                $vv = ((($rawdata["TotalRevenue"][$i-$years]=='null' && $rawdata["TotalRevenue"][$i-$years-1]=='null') || $rawdata["TotalRevenue"][$i-$years-1]=='null' || $rawdata["TotalRevenue"][$i-$years-1]==0)?'null':(($rawdata["TotalRevenue"][$i-$years]-$rawdata["TotalRevenue"][$i-$years-1])/$rawdata["TotalRevenue"][$i-$years-1]));
		$query .= updateCAGR_concat($vv, $va, $years);
        } else {
                $query .= ",null";
        }

        $query .= ")";
        mysql_query($query) or die ($query."\n".mysql_error());
}

function updateCAGR_KR($table, $years, $i, $report_id, $rawdata, $ticker_id) {
	$CapEx_a = (($rawdata["CapitalExpenditures"][$i]=='null')?null:(-$rawdata["CapitalExpenditures"][$i]));
	$CapEx_v = (($rawdata["CapitalExpenditures"][$i-$years]=='null')?null:(-$rawdata["CapitalExpenditures"][$i-$years]));
	$FreeCashFlow_a = (($rawdata["CashfromOperatingActivities"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null')?null:($rawdata["CashfromOperatingActivities"][$i]+$rawdata["CapitalExpenditures"][$i]));
	$FreeCashFlow_v = (($rawdata["CashfromOperatingActivities"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null')?null:($rawdata["CashfromOperatingActivities"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]));
	$OwnerEarningsFCF_a = (($rawdata["CFNetIncome"][$i]=='null' && $rawdata["CFDepreciationAmortization"][$i]=='null' && $rawdata["EmployeeCompensation"][$i]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i]=='null' && $rawdata["DeferredIncomeTaxes"][$i]=='null' && $rawdata["CapitalExpenditures"][$i]=='null' && $rawdata["ChangeinCurrentAssets"][$i]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i]=='null')?null:($rawdata["CFNetIncome"][$i]+$rawdata["CFDepreciationAmortization"][$i]+$rawdata["EmployeeCompensation"][$i]+$rawdata["AdjustmentforSpecialCharges"][$i]+$rawdata["DeferredIncomeTaxes"][$i]+$rawdata["CapitalExpenditures"][$i]+($rawdata["ChangeinCurrentAssets"][$i]+$rawdata["ChangeinCurrentLiabilities"][$i])));
	$OwnerEarningsFCF_v = (($rawdata["CFNetIncome"][$i-$years]=='null' && $rawdata["CFDepreciationAmortization"][$i-$years]=='null' && $rawdata["EmployeeCompensation"][$i-$years]=='null' && $rawdata["AdjustmentforSpecialCharges"][$i-$years]=='null' && $rawdata["DeferredIncomeTaxes"][$i-$years]=='null' && $rawdata["CapitalExpenditures"][$i-$years]=='null' && $rawdata["ChangeinCurrentAssets"][$i-$years]=='null' && $rawdata["ChangeinCurrentLiabilities"][$i-$years]=='null')?null:($rawdata["CFNetIncome"][$i-$years]+$rawdata["CFDepreciationAmortization"][$i-$years]+$rawdata["EmployeeCompensation"][$i-$years]+$rawdata["AdjustmentforSpecialCharges"][$i-$years]+$rawdata["DeferredIncomeTaxes"][$i-$years]+$rawdata["CapitalExpenditures"][$i-$years]+($rawdata["ChangeinCurrentAssets"][$i-$years]+$rawdata["ChangeinCurrentLiabilities"][$i-$years])));
        $arpy_a = $rawdata["AccountsReceivableTradeNet"][$i-1]=='null'?null:$rawdata["AccountsReceivableTradeNet"][$i-1];
	$inpy_a = $rawdata["InventoriesNet"][$i-1]=='null'?null:$rawdata["InventoriesNet"][$i-1];
	if($i - $years == 1) {
		$arpy_v = $inpy_v = 0;
	} else {
                $arpy_v = $rawdata["AccountsReceivableTradeNet"][$i-$years-1]=='null'?null:$rawdata["AccountsReceivableTradeNet"][$i-$years-1];
	        $inpy_v = $rawdata["InventoriesNet"][$i-$years-1]=='null'?null:$rawdata["InventoriesNet"][$i-$years-1];
	}
	$rdate_a = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i]));
	$qquote_a = "Select * from tickers_yahoo_historical_data where ticker_id = '".$ticker_id."' and report_date <= '".$rdate_a."' order by report_date desc limit 1";
	$price_a = null;
        $rquote_a = mysql_query($qquote_a) or die (mysql_error());
	if(mysql_num_rows($rquote_a) > 0) {
             	$price_a = mysql_fetch_assoc($rquote_a);
	        $price_a = $price_a["adj_close"];
	}
	$rdate_v = date("Y-m-d",strtotime($rawdata["PeriodEndDate"][$i-$years]));
	$qquote_v = "Select * from tickers_yahoo_historical_data where ticker_id = '".$ticker_id."' and report_date <= '".$rdate_v."' order by report_date desc limit 1";
	$price_v = null;
        $rquote_v = mysql_query($qquote_v) or die (mysql_error());
	if(mysql_num_rows($rquote_v) > 0) {
             	$price_v = mysql_fetch_assoc($rquote_v);
	        $price_v = $price_v["adj_close"];
	}
        $entValue_a = (($rawdata["SharesOutstandingDiluted"][$i]=='null' && is_null($price_a) && $rawdata["TotalLongtermDebt"][$i]=='null' && $rawdata["TotalShorttermDebt"][$i]=='null' && $rawdata["PreferredStock"][$i]=='null' && $rawdata["MinorityInterestEquityEarnings"][$i]=='null' && $rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null')?null:((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price_a)+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalShorttermDebt"][$i]+$rawdata["PreferredStock"][$i]+$rawdata["MinorityInterestEquityEarnings"][$i]-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]));
        $entValue_v = (($rawdata["SharesOutstandingDiluted"][$i-$years]=='null' && is_null($price_v) && $rawdata["TotalLongtermDebt"][$i-$years]=='null' && $rawdata["TotalShorttermDebt"][$i-$years]=='null' && $rawdata["PreferredStock"][$i-$years]=='null' && $rawdata["MinorityInterestEquityEarnings"][$i-$years]=='null' && $rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null')?null:((toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000*$price_v)+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["PreferredStock"][$i-$years]+$rawdata["MinorityInterestEquityEarnings"][$i-$years]-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]));

	$query = "INSERT INTO $table (`report_id`, `ReportDatePrice`, `CashFlow`, `MarketCap`, `EnterpriseValue`, `GoodwillIntangibleAssetsNet`, `TangibleBookValue`, `ExcessCash`, `TotalInvestedCapital`, `WorkingCapital`, `P_E`, `P_E_CashAdjusted`, `EV_EBITDA`, `EV_EBIT`, `P_S`, `P_BV`, `P_Tang_BV`, `P_CF`, `P_FCF`, `P_OwnerEarnings`, `FCF_S`, `FCFYield`, `MagicFormulaEarningsYield`, `ROE`, `ROA`, `ROIC`, `CROIC`, `GPA`, `BooktoMarket`, `QuickRatio`, `CurrentRatio`, `TotalDebt_EquityRatio`, `LongTermDebt_EquityRatio`, `ShortTermDebt_EquityRatio`, `AssetTurnover`, `CashPercofRevenue`, `ReceivablesPercofRevenue`, `SG_APercofRevenue`, `R_DPercofRevenue`, `DaysSalesOutstanding`, `DaysInventoryOutstanding`, `DaysPayableOutstanding`, `CashConversionCycle`, `ReceivablesTurnover`, `InventoryTurnover`, `AverageAgeofInventory`, `IntangiblesPercofBookValue`, `InventoryPercofRevenue`, `LT_DebtasPercofInvestedCapital`, `ST_DebtasPercofInvestedCapital`, `LT_DebtasPercofTotalDebt`, `ST_DebtasPercofTotalDebt`, `TotalDebtPercofTotalAssets`, `WorkingCapitalPercofPrice`) VALUES (";
	$query .= "'".$report_id."'";
        $va = $price_a;
        $vv = $price_v;
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null' && is_null($CapEx_a)) || $rawdata["TaxRatePercent"][$i]=='null')?'null':(($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx_a)*(1-$rawdata["TaxRatePercent"][$i])));
        $vv = ((($rawdata["GrossProfit"][$i-$years]=='null'&&$rawdata["OperatingExpenses"][$i-$years]=='null' && is_null($CapEx_v)) || $rawdata["TaxRatePercent"][$i-$years]=='null')?'null':(($rawdata["GrossProfit"][$i-$years]-$rawdata["OperatingExpenses"][$i-$years]-$CapEx_v)*(1-$rawdata["TaxRatePercent"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["SharesOutstandingDiluted"][$i]=='null'||is_null($price_a))?'null':(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price_a));
	$vv = (($rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||is_null($price_v))?'null':(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000*$price_v));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = $entValue_a;
        $vv = $entValue_v;
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = $rawdata["GoodwillIntangibleAssetsNet"][$i];
	$vv = $rawdata["GoodwillIntangibleAssetsNet"][$i-$years];
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')?'null':($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i]));
        $vv = (($rawdata["TotalStockholdersEquity"][$i-$years]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i-$years]=='null')?'null':($rawdata["TotalStockholdersEquity"][$i-$years] - $rawdata["GoodwillIntangibleAssetsNet"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null' ||($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null'&&$rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["LongtermInvestments"][$i]=='null'))?'null':(($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] + $rawdata["LongtermInvestments"][$i]) - max(0, ($rawdata["TotalCurrentLiabilities"][$i]-$rawdata["TotalCurrentAssets"][$i]+$rawdata["CashCashEquivalentsandShorttermInvestments"][$i]))));
	$vv = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null' ||($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null'&&$rawdata["TotalCurrentLiabilities"][$i-$years]=='null'&&$rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["LongtermInvestments"][$i-$years]=='null'))?'null':(($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years] + $rawdata["LongtermInvestments"][$i-$years]) - max(0, ($rawdata["TotalCurrentLiabilities"][$i-$years]-$rawdata["TotalCurrentAssets"][$i-$years]+$rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')?'null':($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]));
        $vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')?'null':($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')?'null':($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i]));
	$vv = (($rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["TotalCurrentLiabilities"][$i-$years]=='null')?'null':($rawdata["TotalCurrentAssets"][$i-$years] - $rawdata["TotalCurrentLiabilities"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?'null':($price_a / toFloat($rawdata["EPSDiluted"][$i])));
        $vv = ((is_null($price_v)||$rawdata["EPSDiluted"][$i-$years]=='null'||$rawdata["EPSDiluted"][$i-$years]==0)?'null':($price_v / toFloat($rawdata["EPSDiluted"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||$rawdata["EPSDiluted"][$i]=='null'||$rawdata["EPSDiluted"][$i]==0)?'null':((((toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000*$price_a)-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/toFloat($rawdata["EPSDiluted"][$i])));
	$vv = (($rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||$rawdata["EPSDiluted"][$i-$years]=='null'||$rawdata["EPSDiluted"][$i-$years]==0)?'null':((((toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000*$price_v)-$rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years])/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))/toFloat($rawdata["EPSDiluted"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($entValue_a)||$rawdata["EBITDA"][$i]=='null'||$rawdata["EBITDA"][$i]==0)?'null':($entValue_a / $rawdata["EBITDA"][$i]));
        $vv = ((is_null($entValue_v)||$rawdata["EBITDA"][$i-$years]=='null'||$rawdata["EBITDA"][$i-$years]==0)?'null':($entValue_v / $rawdata["EBITDA"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($entValue_a)||$rawdata["EBIT"][$i]=='null'||$rawdata["EBIT"][$i]==0)?'null':($entValue_a / $rawdata["EBIT"][$i]));
	$vv = ((is_null($entValue_v)||$rawdata["EBIT"][$i-$years]=='null'||$rawdata["EBIT"][$i-$years]==0)?'null':($entValue_v / $rawdata["EBIT"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($rawdata["TotalRevenue"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($rawdata["TotalRevenue"][$i-$years]/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($price_a)||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
	$vv = ((is_null($price_v)||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($rawdata["TotalStockholdersEquity"][$i-$years]/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||($rawdata["TotalStockholdersEquity"][$i]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i]==0))?'null':($price_a / (($rawdata["TotalStockholdersEquity"][$i] - $rawdata["GoodwillIntangibleAssetsNet"][$i])/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||($rawdata["TotalStockholdersEquity"][$i-$years]=='null'&&$rawdata["GoodwillIntangibleAssetsNet"][$i-$years]=='null')||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||($rawdata["TotalStockholdersEquity"][$i-$years] - $rawdata["GoodwillIntangibleAssetsNet"][$i-$years]==0))?'null':($price_v / (($rawdata["TotalStockholdersEquity"][$i-$years] - $rawdata["GoodwillIntangibleAssetsNet"][$i-$years])/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($price_a)||($rawdata["GrossProfit"][$i]=='null'&&$rawdata["OperatingExpenses"][$i]=='null'&&is_null($CapEx_a))||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx_a==0)||$rawdata["TaxRatePercent"][$i]==1)?'null':($price_a / ((($rawdata["GrossProfit"][$i]-$rawdata["OperatingExpenses"][$i]-$CapEx_a)*(1-$rawdata["TaxRatePercent"][$i]))/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
	$vv = ((is_null($price_v)||($rawdata["GrossProfit"][$i-$years]=='null'&&$rawdata["OperatingExpenses"][$i-$years]=='null'&&is_null($CapEx_v))||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||($rawdata["GrossProfit"][$i-$years]-$rawdata["OperatingExpenses"][$i-$years]-$CapEx_v==0)||$rawdata["TaxRatePercent"][$i-$years]==1)?'null':($price_v / ((($rawdata["GrossProfit"][$i-$years]-$rawdata["OperatingExpenses"][$i-$years]-$CapEx_v)*(1-$rawdata["TaxRatePercent"][$i-$years]))/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||is_null($FreeCashFlow_a)||$FreeCashFlow_a==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($FreeCashFlow_a/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||is_null($FreeCashFlow_v)||$FreeCashFlow_v==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($FreeCashFlow_v/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($price_a)||is_null($OwnerEarningsFCF_a)||$OwnerEarningsFCF_a==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':($price_a / ($OwnerEarningsFCF_a/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))));
        $vv = ((is_null($price_v)||is_null($OwnerEarningsFCF_v)||$OwnerEarningsFCF_v==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':($price_v / ($OwnerEarningsFCF_v/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($FreeCashFlow_a)||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($FreeCashFlow_a / $rawdata["TotalRevenue"][$i]));
	$vv = ((is_null($FreeCashFlow_v)||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($FreeCashFlow_v / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
       	$va = ((is_null($price_a)||$price_a==0||is_null($FreeCashFlow_a)||$FreeCashFlow_a==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':(1 / ($price_a / ($FreeCashFlow_a/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))));
       	$vv = ((is_null($price_v)||$price_v==0||is_null($FreeCashFlow_v)||$FreeCashFlow_v==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':(1 / ($price_v / ($FreeCashFlow_v/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000)))));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["EBIT"][$i]=='null'||is_null($entValue_a)||$entValue_a==0)?'null':($rawdata["EBIT"][$i] / $entValue_a));
        $vv = (($rawdata["EBIT"][$i-$years]=='null'||is_null($entValue_v)||$entValue_v==0)?'null':($rawdata["EBIT"][$i-$years] / $entValue_v));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["NetIncome"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
	$vv = (($rawdata["NetIncome"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':($rawdata["NetIncome"][$i-$years] / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["NetIncome"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["NetIncome"][$i] / $rawdata["TotalAssets"][$i]));
        $vv = (($rawdata["NetIncome"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':($rawdata["NetIncome"][$i-$years] / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["EBIT"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':(($rawdata["EBIT"][$i]*(1-$rawdata["TaxRatePercent"][$i])) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
	$vv = (($rawdata["EBIT"][$i-$years]=='null'||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':(($rawdata["EBIT"][$i-$years]*(1-$rawdata["TaxRatePercent"][$i-$years])) / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((is_null($FreeCashFlow_a)||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':($FreeCashFlow_a / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
        $vv = ((is_null($FreeCashFlow_v)||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':($FreeCashFlow_v / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["GrossProfit"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["GrossProfit"][$i] / $rawdata["TotalAssets"][$i]));
	$vv = (($rawdata["GrossProfit"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':($rawdata["GrossProfit"][$i-$years] / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((is_null($price_a)||$price_a==0||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0)?'null':(1 / ($price_a / ($rawdata["TotalStockholdersEquity"][$i]/(toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000)))));
	$vv = ((is_null($price_v)||$price_v==0||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0)?'null':(1 / ($price_v / ($rawdata["TotalStockholdersEquity"][$i-$years]/(toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000)))));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["InventoriesNet"][$i]=='null')||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?'null':(($rawdata["TotalCurrentAssets"][$i] - $rawdata["InventoriesNet"][$i]) / $rawdata["TotalCurrentLiabilities"][$i]));
	$vv = ((($rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["InventoriesNet"][$i-$years]=='null')||$rawdata["TotalCurrentLiabilities"][$i-$years]=='null'||$rawdata["TotalCurrentLiabilities"][$i-$years]==0)?'null':(($rawdata["TotalCurrentAssets"][$i-$years] - $rawdata["InventoriesNet"][$i-$years]) / $rawdata["TotalCurrentLiabilities"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalCurrentAssets"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]=='null'||$rawdata["TotalCurrentLiabilities"][$i]==0)?'null':($rawdata["TotalCurrentAssets"][$i] / $rawdata["TotalCurrentLiabilities"][$i]));
        $vv = (($rawdata["TotalCurrentAssets"][$i-$years]=='null'||$rawdata["TotalCurrentLiabilities"][$i-$years]=='null'||$rawdata["TotalCurrentLiabilities"][$i-$years]==0)?'null':($rawdata["TotalCurrentAssets"][$i-$years] / $rawdata["TotalCurrentLiabilities"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i]));
	$vv = ((($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null')||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':(($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]) / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':(($rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalStockholdersEquity"][$i]));
        $vv = ((($rawdata["TotalLongtermDebt"][$i-$years]=='null')||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':(($rawdata["TotalLongtermDebt"][$i-$years]) / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalShorttermDebt"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["TotalShorttermDebt"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
	$vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':($rawdata["TotalShorttermDebt"][$i-$years] / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':($rawdata["TotalRevenue"][$i] / $rawdata["TotalAssets"][$i]));
        $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':($rawdata["TotalRevenue"][$i-$years] / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["CashCashEquivalentsandShorttermInvestments"][$i] / $rawdata["TotalRevenue"][$i]));
	$vv = (($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["CashCashEquivalentsandShorttermInvestments"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["TotalReceivablesNet"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["TotalReceivablesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["SellingGeneralAdministrativeExpenses"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i] / $rawdata["TotalRevenue"][$i]));
	$vv = (($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["ResearchDevelopmentExpense"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i] / $rawdata["TotalRevenue"][$i]));
        $vv = (($rawdata["ResearchDevelopmentExpense"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalReceivablesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365));
	$vv = (($rawdata["TotalReceivablesNet"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["TotalReceivablesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years] * 365));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365));
        $vv = (($rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0)?'null':($rawdata["InventoriesNet"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["AccountsPayable"][$i]=='null'||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365));
	$vv = (($rawdata["AccountsPayable"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0)?'null':($rawdata["AccountsPayable"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = (($rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0||$rawdata["CostofRevenue"][$i]=='null'||$rawdata["CostofRevenue"][$i]==0)?'null':(($rawdata["TotalReceivablesNet"][$i] / $rawdata["TotalRevenue"][$i] * 365)+($rawdata["InventoriesNet"][$i] / $rawdata["CostofRevenue"][$i] * 365)-($rawdata["AccountsPayable"][$i] / $rawdata["CostofRevenue"][$i] * 365)));
        $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0||$rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0)?'null':(($rawdata["TotalReceivablesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years] * 365)+($rawdata["InventoriesNet"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365)-($rawdata["AccountsPayable"][$i-$years] / $rawdata["CostofRevenue"][$i-$years] * 365)));
	$query .= updateCAGR_concat($vv, $va, $years);
	if($i - $years == 1) {
                $va = (($rawdata["TotalRevenue"][$i]=='null'||($rawdata["AccountsReceivableTradeNet"][$i]=='null'&&is_null($arpy_a))||($rawdata["AccountsReceivableTradeNet"][$i]+$arpy_a==0))?'null':($rawdata["TotalRevenue"][$i] / (($arpy_a + $rawdata["AccountsReceivableTradeNet"][$i])/2)));
                $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["AccountsReceivableTradeNet"][$i-$years]=='null'||$rawdata["AccountsReceivableTradeNet"][$i-$years]==0)?'null':($rawdata["TotalRevenue"][$i-$years] / ($rawdata["AccountsReceivableTradeNet"][$i-$years])));
		$query .= updateCAGR_concat($vv, $va, $years);
	        $va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0))?'null':($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2)));
	        $vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["InventoriesNet"][$i-$years]==0)?'null':($rawdata["CostofRevenue"][$i-$years] / ($rawdata["InventoriesNet"][$i-$years])));
		$query .= updateCAGR_concat($vv, $va, $years);
        	$va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0)||$rawdata["CostofRevenue"][$i]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2))));
        	$vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||$rawdata["CostofRevenue"][$i-$years]==0||$rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["InventoriesNet"][$i-$years]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i-$years] / ($rawdata["InventoriesNet"][$i-$years]))));
		$query .= updateCAGR_concat($vv, $va, $years);
	} else {
                $va = (($rawdata["TotalRevenue"][$i]=='null'||($rawdata["AccountsReceivableTradeNet"][$i]=='null'&&is_null($arpy_a))||($rawdata["AccountsReceivableTradeNet"][$i]+$arpy_a==0))?'null':($rawdata["TotalRevenue"][$i] / (($arpy_a + $rawdata["AccountsReceivableTradeNet"][$i])/2)));
                $vv = (($rawdata["TotalRevenue"][$i-$years]=='null'||($rawdata["AccountsReceivableTradeNet"][$i-$years]=='null'&&is_null($arpy_v))||($rawdata["AccountsReceivableTradeNet"][$i-$years]+$arpy_v==0))?'null':($rawdata["TotalRevenue"][$i-$years] / (($arpy_v + $rawdata["AccountsReceivableTradeNet"][$i-$years])/2)));
		$query .= updateCAGR_concat($vv, $va, $years);
	        $va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0))?'null':($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2)));
	        $vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||($rawdata["InventoriesNet"][$i-$years]=='null'&&is_null($inpy_v))||($rawdata["InventoriesNet"][$i-$years]+$inpy_v==0))?'null':($rawdata["CostofRevenue"][$i-$years] / (($inpy_v + $rawdata["InventoriesNet"][$i-$years])/2)));
		$query .= updateCAGR_concat($vv, $va, $years);
        	$va = (($rawdata["CostofRevenue"][$i]=='null'||($rawdata["InventoriesNet"][$i]=='null'&&is_null($inpy_a))||($rawdata["InventoriesNet"][$i]+$inpy_a==0)||$rawdata["CostofRevenue"][$i]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i] / (($inpy_a + $rawdata["InventoriesNet"][$i])/2))));
        	$vv = (($rawdata["CostofRevenue"][$i-$years]=='null'||($rawdata["InventoriesNet"][$i-$years]=='null'&&is_null($inpy_v))||($rawdata["InventoriesNet"][$i-$years]+$inpy_v==0)||$rawdata["CostofRevenue"][$i-$years]==0)?'null':(365 / ($rawdata["CostofRevenue"][$i-$years] / (($inpy_v + $rawdata["InventoriesNet"][$i-$years])/2))));
		$query .= updateCAGR_concat($vv, $va, $years);
	}
        $va = (($rawdata["GoodwillIntangibleAssetsNet"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalStockholdersEquity"][$i]==0)?'null':($rawdata["GoodwillIntangibleAssetsNet"][$i] / $rawdata["TotalStockholdersEquity"][$i]));
        $vv = (($rawdata["GoodwillIntangibleAssetsNet"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalStockholdersEquity"][$i-$years]==0)?'null':($rawdata["GoodwillIntangibleAssetsNet"][$i-$years] / $rawdata["TotalStockholdersEquity"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["InventoriesNet"][$i]=='null'||$rawdata["TotalRevenue"][$i]=='null'||$rawdata["TotalRevenue"][$i]==0)?'null':($rawdata["InventoriesNet"][$i] / $rawdata["TotalRevenue"][$i]));
	$vv = (($rawdata["InventoriesNet"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]=='null'||$rawdata["TotalRevenue"][$i-$years]==0)?'null':($rawdata["InventoriesNet"][$i-$years] / $rawdata["TotalRevenue"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null'||$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
        $vv = ((($rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null'||$rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':(($rawdata["TotalLongtermDebt"][$i-$years]) / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["CurrentPortionofLongtermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null'&&$rawdata["TotalStockholdersEquity"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i]==0))?'null':($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["CurrentPortionofLongtermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]+$rawdata["TotalStockholdersEquity"][$i])));
	$vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'||($rawdata["CurrentPortionofLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null'&&$rawdata["TotalStockholdersEquity"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years]==0))?'null':($rawdata["TotalShorttermDebt"][$i-$years] / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["CurrentPortionofLongtermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]+$rawdata["TotalStockholdersEquity"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalLongtermDebt"][$i]=='null' &&$rawdata["TotalShorttermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?'null':(($rawdata["TotalLongtermDebt"][$i]) / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i])));
        $vv = ((($rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalLongtermDebt"][$i-$years]=='null' &&$rawdata["TotalShorttermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]==0))?'null':(($rawdata["TotalLongtermDebt"][$i-$years]) / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = (($rawdata["TotalShorttermDebt"][$i]=='null'||($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]==0))?'null':($rawdata["TotalShorttermDebt"][$i] / ($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i])));
	$vv = (($rawdata["TotalShorttermDebt"][$i-$years]=='null'||($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null')||($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]==0))?'null':($rawdata["TotalShorttermDebt"][$i-$years] / ($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years])));
	$query .= updateCAGR_concat($vv, $va, $years);
        $va = ((($rawdata["TotalShorttermDebt"][$i]=='null'&&$rawdata["TotalLongtermDebt"][$i]=='null')||$rawdata["TotalAssets"][$i]=='null'||$rawdata["TotalAssets"][$i]==0)?'null':(($rawdata["TotalShorttermDebt"][$i]+$rawdata["TotalLongtermDebt"][$i]) / $rawdata["TotalAssets"][$i]));
        $vv = ((($rawdata["TotalShorttermDebt"][$i-$years]=='null'&&$rawdata["TotalLongtermDebt"][$i-$years]=='null')||$rawdata["TotalAssets"][$i-$years]=='null'||$rawdata["TotalAssets"][$i-$years]==0)?'null':(($rawdata["TotalShorttermDebt"][$i-$years]+$rawdata["TotalLongtermDebt"][$i-$years]) / $rawdata["TotalAssets"][$i-$years]));
	$query .= updateCAGR_concat($vv, $va, $years);
	$va = ((($rawdata["TotalCurrentAssets"][$i]=='null'&&$rawdata["TotalCurrentLiabilities"][$i]=='null')||$rawdata["SharesOutstandingDiluted"][$i]=='null'||$rawdata["SharesOutstandingDiluted"][$i]==0||is_null($price_a)||$price_a==0)?'null':((($rawdata["TotalCurrentAssets"][$i] - $rawdata["TotalCurrentLiabilities"][$i]) / (toFloat($rawdata["SharesOutstandingDiluted"][$i])*1000000))/$price_a));
	$vv = ((($rawdata["TotalCurrentAssets"][$i-$years]=='null'&&$rawdata["TotalCurrentLiabilities"][$i-$years]=='null')||$rawdata["SharesOutstandingDiluted"][$i-$years]=='null'||$rawdata["SharesOutstandingDiluted"][$i-$years]==0||is_null($price_v)||$price_v==0)?'null':((($rawdata["TotalCurrentAssets"][$i-$years] - $rawdata["TotalCurrentLiabilities"][$i-$years]) / (toFloat($rawdata["SharesOutstandingDiluted"][$i-$years])*1000000))/$price_v));
	$query .= updateCAGR_concat($vv, $va, $years);
        $query .= ")";
	mysql_query($query) or die ($query."\n".mysql_error());
}
?>
