<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/db.php');
include_once('../crons/include/update_cagr_tables_functions.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$areports = AREPORTS;

//Get full list of symbols from backend
$query = "SELECT a.* from tickers a inner join reports_header b on a.id=b.ticker_id group by a.id";
try {
	$res = $db->query($query);
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die("- Line: ".__LINE__." - ".$ex->getMessage());
}

$count = 0;
$inserted = 0;
$updated = 0;
$dates = new stdClass();
$report_tables = array("reports_balanceconsolidated_3cagr","reports_balanceconsolidated_5cagr","reports_balanceconsolidated_7cagr","reports_balanceconsolidated_10cagr","reports_balancefull_3cagr","reports_balancefull_5cagr","reports_balancefull_7cagr","reports_balancefull_10cagr","reports_cashflowconsolidated_3cagr","reports_cashflowconsolidated_5cagr","reports_cashflowconsolidated_7cagr","reports_cashflowconsolidated_10cagr","reports_cashflowfull_3cagr","reports_cashflowfull_5cagr","reports_cashflowfull_7cagr","reports_cashflowfull_10cagr","reports_gf_data_3cagr","reports_gf_data_5cagr","reports_gf_data_7cagr","reports_gf_data_10cagr","reports_incomeconsolidated_3cagr","reports_incomeconsolidated_5cagr","reports_incomeconsolidated_7cagr","reports_incomeconsolidated_10cagr","reports_incomefull_3cagr","reports_incomefull_5cagr","reports_incomefull_7cagr","reports_incomefull_10cagr","reports_variable_ratios_3cagr","reports_variable_ratios_5cagr","reports_variable_ratios_7cagr","reports_variable_ratios_10cagr","reports_financialscustom_3cagr","reports_financialscustom_5cagr","reports_financialscustom_7cagr","reports_financialscustom_10cagr","reports_key_ratios_3cagr","reports_key_ratios_5cagr","reports_key_ratios_7cagr","reports_key_ratios_10cagr","reports_valuation_3cagr","reports_valuation_5cagr","reports_valuation_7cagr","reports_valuation_10cagr");

echo "Updating CAGR data points...<br>\n";
while($row = $res->fetch(PDO::FETCH_ASSOC)) {    
	echo "Updating ".$row["ticker"]."<br>\n";
	$query = "Select count(*) as c from reports_header a where a.ticker_id=".$row["id"]." AND a.report_type='ANN'";
	try {
		$res2 = $db->query($query);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}
	$annCount = $res2->fetch(PDO::FETCH_ASSOC);
	$annCount = $annCount["c"];
	$count++;
	$rawdata = array();
	$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_key_ratios c, reports_incomefull d, reports_incomeconsolidated e, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.ticker_id=".$row["id"]." AND a.report_type='ANN' order by a.fiscal_year";
	try {
		$res3 = $db->query($query);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}
	$pos = $areports - $annCount;
	while($row2 = $res3->fetch(PDO::FETCH_ASSOC)) {
		$row2b = $row2;
		$pos++;
		foreach ($row2 as $v=>$y) {
			$rawdata[$v][0] = $v;
			$rawdata[$v][$pos]=$y;
		}
	}
	for ($i = 1; $i <= $areports - $annCount; $i++) {
		foreach ($row2b as $v=>$y) {
			$rawdata[$v][$i] = null;
		}
	}
	$dates->ticker_id = $row["id"];
	$rawdata["PeriodEndDate"] = $rawdata["report_date"];
	array_walk_recursive($rawdata, 'nullValues');

	foreach($report_tables as $table) {
		$query = "DELETE FROM $table WHERE report_id IN (SELECT id FROM reports_header WHERE ticker_id = ".$dates->ticker_id.")";
		try {
			$db->exec($query);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}
	}

	//Update each CAGR table
	for ($i = 4; $i <= $areports; $i++) {
		$report_id = $rawdata["id"][$i];
		if ($report_id == "null") { continue; }
		//reports_balanceconsolidated CAGR
		$fieldArray = Array("CommitmentsContingencies", "CommonStock", "DeferredCharges", "DeferredIncomeTaxesCurrent", "DeferredIncomeTaxesLongterm", "AccountsPayableandAccruedExpenses", "AccruedInterest", "AdditionalPaidinCapital", "AdditionalPaidinCapitalPreferredStock", "CashandCashEquivalents", "CashCashEquivalentsandShorttermInvestments", "Goodwill", "IntangibleAssets", "InventoriesNet", "LongtermDeferredIncomeTaxLiabilities", "LongtermDeferredLiabilityCharges", "LongtermInvestments", "MinorityInterest", "OtherAccumulatedComprehensiveIncome", "OtherAssets", "OtherCurrentAssets", "OtherCurrentLiabilities", "OtherEquity", "OtherInvestments", "OtherLiabilities", "PartnersCapital", "PensionPostretirementObligation", "PreferredStock", "PrepaidExpenses", "PropertyPlantEquipmentNet", "RestrictedCash", "RetainedEarnings", "TemporaryEquity", "TotalAssets", "TotalCurrentAssets", "TotalCurrentLiabilities", "TotalLiabilities", "TotalLongtermDebt", "TotalReceivablesNet", "TotalShorttermDebt", "TotalStockholdersEquity", "TreasuryStock");
		updateCAGR("reports_balanceconsolidated_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
		if ($i > 5) {
			updateCAGR("reports_balanceconsolidated_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
		}
		if ($i > 7) {
			updateCAGR("reports_balanceconsolidated_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
		}
		if ($i > 10) {
			updateCAGR("reports_balanceconsolidated_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
		}
		//reports_balancefull CAGR
		$fieldArray = Array("TotalDebt", "TotalAssetsFQ", "TotalAssetsFY", "CurrentPortionofLongtermDebt", "DeferredIncomeTaxLiabilitiesShortterm", "DeferredLiabilityCharges", "AccountsNotesReceivableNet", "AccountsPayable", "AccountsReceivableTradeNet", "AccruedExpenses", "AccumulatedDepreciation", "AmountsDuetoRelatedPartiesShortterm", "GoodwillIntangibleAssetsNet", "IncomeTaxesPayable", "LiabilitiesStockholdersEquity", "LongtermDebt", "NotesPayable", "OperatingLeases", "OtherAccountsNotesReceivable", "OtherAccountsPayableandAccruedExpenses", "OtherBorrowings", "OtherReceivables", "PropertyandEquipmentGross", "TotalLongtermAssets", "TotalLongtermLiabilities", "TotalSharesOutstanding", "ShorttermInvestments");
		updateCAGR("reports_balancefull_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
		if ($i > 5) {
			updateCAGR("reports_balancefull_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
		}
		if ($i > 7) {
			updateCAGR("reports_balancefull_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
		}
		if ($i > 10) {
			updateCAGR("reports_balancefull_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
		}
		//reports_cashflowconsolidated CAGR
		$fieldArray = Array("ChangeinCurrentAssets", "ChangeinCurrentLiabilities", "ChangeinDebtNet", "ChangeinDeferredRevenue", "ChangeinEquityNet", "ChangeinIncomeTaxesPayable", "ChangeinInventories", "ChangeinOperatingAssetsLiabilities", "ChangeinOtherAssets", "ChangeinOtherCurrentAssets", "ChangeinOtherCurrentLiabilities", "ChangeinOtherLiabilities", "ChangeinPrepaidExpenses", "DividendsPaid", "EffectofExchangeRateonCash", "EmployeeCompensation", "AcquisitionSaleofBusinessNet", "AdjustmentforEquityEarnings", "AdjustmentforMinorityInterest", "AdjustmentforSpecialCharges", "CapitalExpenditures", "CashfromDiscontinuedOperations", "CashfromFinancingActivities", "CashfromInvestingActivities", "CashfromOperatingActivities", "CFDepreciationAmortization", "DeferredIncomeTaxes", "ChangeinAccountsPayableAccruedExpenses", "ChangeinAccountsReceivable", "InvestmentChangesNet", "NetChangeinCash", "OtherAdjustments", "OtherAssetLiabilityChangesNet", "OtherFinancingActivitiesNet", "OtherInvestingActivities", "RealizedGainsLosses", "SaleofPropertyPlantEquipment", "StockOptionTaxBenefits", "TotalAdjustments");
		updateCAGR("reports_cashflowconsolidated_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
		if ($i > 5) {
			updateCAGR("reports_cashflowconsolidated_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
		}
		if ($i > 7) {
			updateCAGR("reports_cashflowconsolidated_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
		}
		if ($i > 10) {
			updateCAGR("reports_cashflowconsolidated_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
		}
		//reports_cashflowfull CAGR
		$fieldArray = Array("ChangeinLongtermDebtNet", "ChangeinShorttermBorrowingsNet", "CashandCashEquivalentsBeginningofYear", "CashandCashEquivalentsEndofYear", "CashPaidforIncomeTaxes", "CashPaidforInterestExpense", "CFNetIncome", "IssuanceofEquity", "LongtermDebtPayments", "LongtermDebtProceeds", "OtherDebtNet", "OtherEquityTransactionsNet", "OtherInvestmentChangesNet", "PurchaseofInvestments", "RepurchaseofEquity", "SaleofInvestments", "ShorttermBorrowings", "TotalNoncashAdjustments");
		updateCAGR("reports_cashflowfull_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
		if ($i > 5) {
			updateCAGR("reports_cashflowfull_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
		}
		if ($i > 7) {
			updateCAGR("reports_cashflowfull_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
		}
		if ($i > 10) {
			updateCAGR("reports_cashflowfull_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
		}
		//reports_gf_data CAGR
		$fieldArray = Array("InterestIncome", "InterestExpense", "EPSBasic", "EPSDiluted", "SharesOutstandingDiluted", "InventoriesRawMaterialsComponents", "InventoriesWorkInProcess", "InventoriesInventoriesAdjustments", "InventoriesFinishedGoods", "InventoriesOther", "TotalInventories", "LandAndImprovements", "BuildingsAndImprovements", "MachineryFurnitureEquipment", "ConstructionInProgress", "GrossPropertyPlantandEquipment", "SharesOutstandingBasic");
		updateCAGR("reports_gf_data_3cagr", $fieldArray, 3, $i, $report_id, $rawdata, true);
		if ($i > 5) {
			updateCAGR("reports_gf_data_5cagr", $fieldArray, 5, $i, $report_id, $rawdata, true);
		}
		if ($i > 7) {
			updateCAGR("reports_gf_data_7cagr", $fieldArray, 7, $i, $report_id, $rawdata, true);
		}
		if ($i > 10) {
			updateCAGR("reports_gf_data_10cagr", $fieldArray, 10, $i, $report_id, $rawdata, true);
		}
		//reports_incomeconsolidated CAGR
		$fieldArray = Array("EBIT", "CostofRevenue", "DepreciationAmortizationExpense", "DilutedEPSNetIncome", "DiscontinuedOperations", "EquityEarnings", "AccountingChange", "BasicEPSNetIncome", "ExtraordinaryItems", "GrossProfit", "IncomebeforeExtraordinaryItems", "IncomeBeforeTaxes", "IncomeTaxes", "InterestExpense", "InterestIncome", "MinorityInterestEquityEarnings", "NetIncome", "NetIncomeApplicabletoCommon", "OperatingProfit", "OtherNonoperatingIncomeExpense", "OtherOperatingExpenses", "ResearchDevelopmentExpense", "RestructuringRemediationImpairmentProvisions", "TotalRevenue", "SellingGeneralAdministrativeExpenses");
		updateCAGR("reports_incomeconsolidated_3cagr", $fieldArray, 3, $i, $report_id, $rawdata, true);
		if ($i > 5) {
			updateCAGR("reports_incomeconsolidated_5cagr", $fieldArray, 5, $i, $report_id, $rawdata, true);
		}
		if ($i > 7) {
			updateCAGR("reports_incomeconsolidated_7cagr", $fieldArray, 7, $i, $report_id, $rawdata, true);
		}
		if ($i > 10) {
			updateCAGR("reports_incomeconsolidated_10cagr", $fieldArray, 10, $i, $report_id, $rawdata, true);
		}
		//reports_incomefull CAGR
		$fieldArray = Array("AdjustedEBIT", "AdjustedEBITDA", "AdjustedNetIncome", "AftertaxMargin", "EBITDA", "GrossMargin", "NetOperatingProfitafterTax", "OperatingMargin", "RevenueFQ", "RevenueFY", "RevenueTTM", "CostOperatingExpenses", "DepreciationExpense", "DilutedEPSNetIncomefromContinuingOperations", "DilutedWeightedAverageShares", "AmortizationExpense", "BasicEPSNetIncomefromContinuingOperations", "BasicWeightedAverageShares", "GeneralAdministrativeExpense", "IncomeAfterTaxes", "LaborExpense", "NetIncomefromContinuingOperationsApplicabletoCommon", "InterestIncomeExpenseNet", "NoncontrollingInterest", "NonoperatingGainsLosses", "OperatingExpenses", "OtherGeneralAdministrativeExpense", "OtherInterestIncomeExpenseNet", "OtherRevenue", "OtherSellingGeneralAdministrativeExpenses", "PreferredDividends", "SalesMarketingExpense", "TotalNonoperatingIncomeExpense", "TotalOperatingExpenses", "OperatingRevenue");
		updateCAGR("reports_incomefull_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
		if ($i > 5) {
			updateCAGR("reports_incomefull_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
		}
		if ($i > 7) {
			updateCAGR("reports_incomefull_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
		}
		if ($i > 10) {
			updateCAGR("reports_incomefull_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
		}
		//reports_variable_ratios CAGR
		$fieldArray = Array("BookEquity", "DebttoAssets", "DegreeofCombinedLeverage", "DegreeofFinancialLeverage", "DegreeofOperationalLeverage", "FreeCashFlow", "DebttoEquity", "AdjustedEPSBasic", "AdjustedEPSDiluted", "FreeCashFlowReturnonAssets", "ReturnonAssets", "ReturnonEquity", "ReturnonInvestedCapital", "RevenueperEmployee", "CashRatio", "CurrentRatio", "FreeCashFlowMargin", "LongTermCapital", "LongTermDebttoLongTermCapital", "LongTermDebttoTotalCapital", "NetDebt", "NetIncomeperEmployee", "NetWorkingCapital", "PretaxMargin", "QuickRatio", "TaxRate", "TotalCapital");
		updateCAGR("reports_variable_ratios_3cagr", $fieldArray, 3, $i, $report_id, $rawdata);
		if ($i > 5) {
			updateCAGR("reports_variable_ratios_5cagr", $fieldArray, 5, $i, $report_id, $rawdata);
		}
		if ($i > 7) {
			updateCAGR("reports_variable_ratios_7cagr", $fieldArray, 7, $i, $report_id, $rawdata);
		}
		if ($i > 10) {
			updateCAGR("reports_variable_ratios_10cagr", $fieldArray, 10, $i, $report_id, $rawdata);
		}
		//reports_financialscustom CAGR
		updateCAGR_FC("reports_financialscustom_3cagr", 3, $i, $report_id, $rawdata);
		if ($i > 5) {
			updateCAGR_FC("reports_financialscustom_5cagr", 5, $i, $report_id, $rawdata);
		}
		if ($i > 7) {
			updateCAGR_FC("reports_financialscustom_7cagr", 7, $i, $report_id, $rawdata);
		}
		if ($i > 10) {
			updateCAGR_FC("reports_financialscustom_10cagr", 10, $i, $report_id, $rawdata);
		}
		//reports_key_ratios CAGR
		updateCAGR_KR("reports_key_ratios_3cagr", 3, $i, $report_id, $rawdata, $dates->ticker_id);
		if ($i > 5) {
			updateCAGR_KR("reports_key_ratios_5cagr", 5, $i, $report_id, $rawdata, $dates->ticker_id);
		}
		if ($i > 7) {
			updateCAGR_KR("reports_key_ratios_7cagr", 7, $i, $report_id, $rawdata, $dates->ticker_id);
		}
		if ($i > 10) {
			updateCAGR_KR("reports_key_ratios_10cagr", 10, $i, $report_id, $rawdata, $dates->ticker_id);
		}
		//reports_valuation CAGR
		updateCAGR_V("reports_valuation_3cagr", 3, $i, $report_id, $rawdata, $dates->ticker_id);
                if ($i > 5) {
                        updateCAGR_V("reports_valuation_5cagr", 5, $i, $report_id, $rawdata, $dates->ticker_id);
                }
                if ($i > 7) {
                        updateCAGR_V("reports_valuation_7cagr", 7, $i, $report_id, $rawdata, $dates->ticker_id);
                }
                if ($i > 10) {
                        updateCAGR_V("reports_valuation_10cagr", 10, $i, $report_id, $rawdata, $dates->ticker_id);
                }
	}
}

echo "$count tickers updated<br>\n";

function nullValues(&$item, $key) {
	if (is_null($item)) {
		$item = 'null';
	} else if(strlen(trim($item)) == 0) {
		$item = 'null';
	} else if($item == "-") {
		$item = 'null';
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
