<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/database.php');
connectfe();

set_time_limit(0);                   // ignore php timeout

        $query = "DELETE FROM reports_financialscustom";
        mysql_query($query) or die (mysql_error());

	$query = "SELECT * FROM reports_header";
	$res = mysql_query($query) or die (mysql_error());

	while ($row = mysql_fetch_assoc($res)) {
			$query = "SELECT * from reports_incomeconsolidated a left join reports_cashflowconsolidated b on a.report_id=b.report_id left join reports_incomefull c on a.report_id=c.report_id left join reports_gf_data d on a.report_id=d.report_id left join reports_balancefull e on a.report_id=e.report_id left join reports_cashflowfull f on a.report_id=f.report_id left join reports_balanceconsolidated g on a.report_id=g.report_id where a.report_id=".$row['id'];
			$res2 = mysql_query($query) or die (mysql_error());
			$rawdata = mysql_fetch_assoc($res2);
                        $query = "INSERT INTO `reports_financialscustom` (`report_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (";
                        $query .= "'".$row['id']."',";
                        $query .= ((is_null($rawdata["CostofRevenue"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["CostofRevenue"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["GrossProfit"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["GrossProfit"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["SellingGeneralAdministrativeExpenses"]) ||  is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["ResearchDevelopmentExpense"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["ResearchDevelopmentExpense"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["CFDepreciationAmortization"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["CFDepreciationAmortization"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["EBITDA"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["EBITDA"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["OperatingProfit"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["OperatingProfit"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["EBIT"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["EBIT"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["IncomeTaxes"]) || is_null($rawdata["IncomeBeforeTaxes"]) || $rawdata["IncomeBeforeTaxes"]==0)?'null':($rawdata["IncomeTaxes"]/$rawdata["IncomeBeforeTaxes"])).",";
                        $query .= ((is_null($rawdata["IncomeBeforeTaxes"]) && is_null($rawdata["IncomeTaxes"]))?'null':($rawdata["IncomeBeforeTaxes"]-$rawdata["IncomeTaxes"])).",";
                        $query .= ((is_null($rawdata["NetIncome"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?'null':($rawdata["NetIncome"]/$rawdata["TotalRevenue"])).",";
                        $query .= ((is_null($rawdata["DividendsPaid"]) || is_null($rawdata["SharesOutstandingBasic"]) || $rawdata["SharesOutstandingBasic"]==0)?'null':(-($rawdata["DividendsPaid"])/($rawdata["SharesOutstandingBasic"]*1000000))).",";
                        $query .= ((is_null($rawdata["CurrentPortionofLongtermDebt"]) && is_null($rawdata["ShorttermBorrowings"]))?'null':($rawdata["CurrentPortionofLongtermDebt"]+$rawdata["ShorttermBorrowings"])).",";
                        $query .= ((is_null($rawdata["TotalLongtermDebt"]) && is_null($rawdata["NotesPayable"]))?'null':($rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"])).",";
                        $query .= ((is_null($rawdata["LongtermDebtProceeds"]) && is_null($rawdata["LongtermDebtPayments"]))?'null':($rawdata["LongtermDebtProceeds"]+$rawdata["LongtermDebtPayments"])).",";
                        $query .= ((is_null($rawdata["CapitalExpenditures"]))?'null':(-$rawdata["CapitalExpenditures"])).",";
                        $query .= ((is_null($rawdata["CashfromOperatingActivities"]) && is_null($rawdata["CapitalExpenditures"]))?'null':($rawdata["CashfromOperatingActivities"]+$rawdata["CapitalExpenditures"])).",";
                        $query .= ((is_null($rawdata["CFNetIncome"]) && is_null($rawdata["CFDepreciationAmortization"]) && is_null($rawdata["EmployeeCompensation"]) && is_null($rawdata["AdjustmentforSpecialCharges"]) && is_null($rawdata["DeferredIncomeTaxes"]) && is_null($rawdata["CapitalExpenditures"]) && is_null($rawdata["ChangeinCurrentAssets"]) && is_null($rawdata["ChangeinCurrentLiabilities"]))?'null':($rawdata["CFNetIncome"]+$rawdata["CFDepreciationAmortization"]+$rawdata["EmployeeCompensation"]+$rawdata["AdjustmentforSpecialCharges"]+$rawdata["DeferredIncomeTaxes"]+$rawdata["CapitalExpenditures"]+($rawdata["ChangeinCurrentAssets"]+$rawdata["ChangeinCurrentLiabilities"])));
        		$query .= ")";
	        	mysql_query($query) or die (mysql_error());
	}
?>
