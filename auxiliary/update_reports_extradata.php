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
                        $query = "INSERT INTO `reports_financialscustom` (`report_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapitalExpeditures`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (";
                        $query .= "'".$row['id']."',";
                        $query .= "'".($rawdata["CostofRevenue"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["GrossProfit"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["SellingGeneralAdministrativeExpenses"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["ResearchDevelopmentExpense"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["CFDepreciationAmortization"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["EBITDA"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["OperatingProfit"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["EBIT"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["IncomeTaxes"]/$rawdata["IncomeBeforeTaxes"])."',";
                        $query .= "'".($rawdata["IncomeBeforeTaxes"]-$rawdata["IncomeTaxes"])."',";
                        $query .= "'".($rawdata["NetIncome"]/$rawdata["TotalRevenue"])."',";
                        $query .= "'".($rawdata["DividendsPaid"]/$rawdata["SharesOutstandingBasic"])."',";
                        $query .= "'".($rawdata["CurrentPortionofLongtermDebt"]+$rawdata["ShorttermBorrowings"])."',";
                        $query .= "'".($rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"])."',";
                        $query .= "'".($rawdata["LongtermDebtProceeds"]+$rawdata["LongtermDebtPayments"])."',";
                        $query .= "'".(-$rawdata["CapitalExpenditures"])."',";
                        $query .= "'".($rawdata["CashfromOperatingActivities"]+$rawdata["CapitalExpenditures"])."',";
                        $query .= "'".($rawdata["CFNetIncome"]+$rawdata["CFDepreciationAmortization"]+$rawdata["EmployeeCompensation"]+$rawdata["AdjustmentforSpecialCharges"]+$rawdata["DeferredIncomeTaxes"]+$rawdata["CapitalExpenditures"]-($rawdata["ChangeinCurrentAssets"]-$rawdata["ChangeinCurrentLiabilities"]))."'";
        		$query .= ")";
	        	mysql_query($query) or die (mysql_error());
	}
?>
