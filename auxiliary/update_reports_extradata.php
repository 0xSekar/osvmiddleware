<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once('../db/db.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout

$query = "DELETE FROM reports_financialscustom";
try {
	$res = $db->exec($query);
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die("- Line: ".__LINE__." - ".$ex->getMessage());
}

$query = "SELECT * FROM reports_header";
try {
	$res = $db->query($query);

	while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
		$query = "SELECT * from reports_incomeconsolidated a left join reports_cashflowconsolidated b on a.report_id=b.report_id left join reports_incomefull c on a.report_id=c.report_id left join reports_gf_data d on a.report_id=d.report_id left join reports_balancefull e on a.report_id=e.report_id left join reports_cashflowfull f on a.report_id=f.report_id left join reports_balanceconsolidated g on a.report_id=g.report_id where a.report_id=".$row['id'];

		$res2 = $db->query($query);

		$rawdata = $res2->fetch(PDO::FETCH_ASSOC);
		$query = "INSERT INTO `reports_financialscustom` (`report_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//19
		$params = array();
		$params[] = $row['id'];
		$params[] = ((is_null($rawdata["CostofRevenue"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["CostofRevenue"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["GrossProfit"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["GrossProfit"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["SellingGeneralAdministrativeExpenses"]) ||  is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["ResearchDevelopmentExpense"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["ResearchDevelopmentExpense"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["CFDepreciationAmortization"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["CFDepreciationAmortization"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["EBITDA"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["EBITDA"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["OperatingProfit"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["OperatingProfit"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["EBIT"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["EBIT"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["IncomeTaxes"]) || is_null($rawdata["IncomeBeforeTaxes"]) || $rawdata["IncomeBeforeTaxes"]==0)?null:($rawdata["IncomeTaxes"]/$rawdata["IncomeBeforeTaxes"]));
		$params[] = ((is_null($rawdata["IncomeBeforeTaxes"]) && is_null($rawdata["IncomeTaxes"]))?null:($rawdata["IncomeBeforeTaxes"]-$rawdata["IncomeTaxes"]));
		$params[] = ((is_null($rawdata["NetIncome"]) || is_null($rawdata["TotalRevenue"]) || $rawdata["TotalRevenue"]==0)?null:($rawdata["NetIncome"]/$rawdata["TotalRevenue"]));
		$params[] = ((is_null($rawdata["DividendsPaid"]) || is_null($rawdata["SharesOutstandingBasic"]) || $rawdata["SharesOutstandingBasic"]==0)?null:(-($rawdata["DividendsPaid"])/($rawdata["SharesOutstandingBasic"]*1000000)));
		$params[] = ((is_null($rawdata["CurrentPortionofLongtermDebt"]) && is_null($rawdata["ShorttermBorrowings"]))?null:($rawdata["CurrentPortionofLongtermDebt"]+$rawdata["ShorttermBorrowings"]));
		$params[] = ((is_null($rawdata["TotalLongtermDebt"]) && is_null($rawdata["NotesPayable"]))?null:($rawdata["TotalLongtermDebt"]+$rawdata["NotesPayable"]));
		$params[] = ((is_null($rawdata["LongtermDebtProceeds"]) && is_null($rawdata["LongtermDebtPayments"]))?null:($rawdata["LongtermDebtProceeds"]+$rawdata["LongtermDebtPayments"]));
		$params[] = ((is_null($rawdata["CapitalExpenditures"]))?null:(-$rawdata["CapitalExpenditures"]));
		$params[] = ((is_null($rawdata["CashfromOperatingActivities"]) && is_null($rawdata["CapitalExpenditures"]))?null:($rawdata["CashfromOperatingActivities"]+$rawdata["CapitalExpenditures"]));
		$params[] = ((is_null($rawdata["CFNetIncome"]) && is_null($rawdata["CFDepreciationAmortization"]) && is_null($rawdata["EmployeeCompensation"]) && is_null($rawdata["AdjustmentforSpecialCharges"]) && is_null($rawdata["DeferredIncomeTaxes"]) && is_null($rawdata["CapitalExpenditures"]) && is_null($rawdata["ChangeinCurrentAssets"]) && is_null($rawdata["ChangeinCurrentLiabilities"]))?null:($rawdata["CFNetIncome"]+$rawdata["CFDepreciationAmortization"]+$rawdata["EmployeeCompensation"]+$rawdata["AdjustmentforSpecialCharges"]+$rawdata["DeferredIncomeTaxes"]+$rawdata["CapitalExpenditures"]+($rawdata["ChangeinCurrentAssets"]+$rawdata["ChangeinCurrentLiabilities"])));

		$res2 = $db->prepare($query);
		$res2->execute($params);

	}
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die("- Line: ".__LINE__." - ".$ex->getMessage());
}
?>
