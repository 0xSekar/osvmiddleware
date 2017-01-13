<?php
function update_raw_data_tickers($dates, $rawdata) {
	$db = Database::GetInstance();
	$areports = AREPORTS;
	$qreports = QREPORTS;
	$treports = $areports+$qreports;

	$ttm_tables = array("ttm_balanceconsolidated","ttm_balancefull","ttm_cashflowconsolidated","ttm_cashflowfull","ttm_incomeconsolidated","ttm_incomefull","ttm_financialscustom", "ttm_gf_data");
	$pttm_tables = array("pttm_balanceconsolidated","pttm_balancefull","pttm_cashflowconsolidated","pttm_cashflowfull","pttm_incomeconsolidated","pttm_incomefull","pttm_financialscustom", "pttm_gf_data");

	//Delete all reports before updating to be sure we do not miss any manual update
	//as this is a batch process, it will not impact on the UE
	foreach($ttm_tables as $table) {
		$query = "DELETE FROM $table WHERE ticker_id = ".$dates->ticker_id;
		try {
			$res = $db->exec($query);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}
	}
	foreach($pttm_tables as $table) {
		$query = "DELETE FROM $table WHERE ticker_id = ".$dates->ticker_id;
		try {
			$res = $db->exec($query);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}
	}

	//Update SalesPercChange and Sales5YYCGrPerc from reports_financialscustom
	//While this should by in another file, this one has the necesary structure
	for ($i = 2; $i <= $areports; $i++) {
		if(!is_null($rawdata["report_id"][$i])) {
			$query = "UPDATE reports_financialscustom SET SalesPercChange = ";
			$query .= ((($rawdata["TotalRevenue"][$i]=='null' && $rawdata["TotalRevenue"][$i-1]=='null') || $rawdata["TotalRevenue"][$i-1]=='null' || $rawdata["TotalRevenue"][$i-1]==0)?'null':(($rawdata["TotalRevenue"][$i]-$rawdata["TotalRevenue"][$i-1])/$rawdata["TotalRevenue"][$i-1])).", Sales5YYCGrPerc = ";
			if ($i > 5) {
				if ($rawdata["TotalRevenue"][$i]=='null' || $rawdata["TotalRevenue"][$i-5]=='null' || $rawdata["TotalRevenue"][$i-5]<=0 || $rawdata["TotalRevenue"][$i] < 0) {
					$query .= "null";
				} else {
					$query .= (pow($rawdata["TotalRevenue"][$i]/$rawdata["TotalRevenue"][$i-5], 1/5) - 1);
				}
			} else {
				$query .= "null";
			}
			$query .= " WHERE report_id = ".$rawdata["report_id"][$i];
			try {
				$res = $db->exec($query);
			} catch(PDOException $ex) {
				echo "\nDatabase Error"; //user message
				echo "\nQuery". $query;
				die("- Line: ".__LINE__." - ".$ex->getMessage());

			}
		}
	}

	//Update TTM and PTTM data
	//Determine if USA stock or ADR
	$stock_type = "ADR";
	$MRQRow = $areports;
	$PMRQRow = $areports - 1;
	if($rawdata["Country"][$areports] == "UNITED STATES OF AMERICA" || $rawdata["Country"][$treports] == "UNITED STATES OF AMERICA" || strpos($rawdata["FormType"][$areports], "10-K") !== false || strpos($rawdata["FormType"][$treports], "10-K") !== false || strpos($rawdata["FormType"][$areports], "10-Q") !== false || strpos($rawdata["FormType"][$treports], "10-Q") !== false || strpos($rawdata["FormType"][$areports], "8-K") !== false || strpos($rawdata["FormType"][$treports], "8-K") !== false) {
		$stock_type = "USA";
		$MRQRow = $treports;
		$PMRQRow = $treports - 4;
	}

	//Load Balance MRQ data
	$query = "INSERT INTO `ttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //43
	$params = array();
	$params[] = $dates->ticker_id;
	$params[] = ($rawdata["CommitmentsContingencies"][$MRQRow] == 'null' ? null: $rawdata["CommitmentsContingencies"][$MRQRow]);
	$params[] = ($rawdata["CommonStock"][$MRQRow] == 'null' ? null: $rawdata["CommonStock"][$MRQRow]);
	$params[] = ($rawdata["DeferredCharges"][$MRQRow] == 'null' ? null: $rawdata["DeferredCharges"][$MRQRow]);
	$params[] = ($rawdata["DeferredIncomeTaxesCurrent"][$MRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxesCurrent"][$MRQRow]);
	$params[] = ($rawdata["DeferredIncomeTaxesLongterm"][$MRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxesLongterm"][$MRQRow]);
	$params[] = ($rawdata["AccountsPayableandAccruedExpenses"][$MRQRow] == 'null' ? null: $rawdata["AccountsPayableandAccruedExpenses"][$MRQRow]);
	$params[] = ($rawdata["AccruedInterest"][$MRQRow] == 'null' ? null: $rawdata["AccruedInterest"][$MRQRow]);
	$params[] = ($rawdata["AdditionalPaidinCapital"][$MRQRow] == 'null' ? null: $rawdata["AdditionalPaidinCapital"][$MRQRow]);
	$params[] = ($rawdata["AdditionalPaidinCapitalPreferredStock"][$MRQRow] == 'null' ? null: $rawdata["AdditionalPaidinCapitalPreferredStock"][$MRQRow]);
	$params[] = ($rawdata["CashandCashEquivalents"][$MRQRow] == 'null' ? null: $rawdata["CashandCashEquivalents"][$MRQRow]);
	$params[] = ($rawdata["CashCashEquivalentsandShorttermInvestments"][$MRQRow] == 'null' ? null: $rawdata["CashCashEquivalentsandShorttermInvestments"][$MRQRow]);
	$params[] = ($rawdata["Goodwill"][$MRQRow] == 'null' ? null: $rawdata["Goodwill"][$MRQRow]);
	$params[] = ($rawdata["IntangibleAssets"][$MRQRow] == 'null' ? null: $rawdata["IntangibleAssets"][$MRQRow]);
	$params[] = ($rawdata["InventoriesNet"][$MRQRow] == 'null' ? null: $rawdata["InventoriesNet"][$MRQRow]);
	$params[] = ($rawdata["LongtermDeferredIncomeTaxLiabilities"][$MRQRow] == 'null' ? null: $rawdata["LongtermDeferredIncomeTaxLiabilities"][$MRQRow]);
	$params[] = ($rawdata["LongtermDeferredLiabilityCharges"][$MRQRow] == 'null' ? null: $rawdata["LongtermDeferredLiabilityCharges"][$MRQRow]);
	$params[] = ($rawdata["LongtermInvestments"][$MRQRow] == 'null' ? null: $rawdata["LongtermInvestments"][$MRQRow]);
	$params[] = ($rawdata["MinorityInterest"][$MRQRow] == 'null' ? null: $rawdata["MinorityInterest"][$MRQRow]);
	$params[] = ($rawdata["OtherAccumulatedComprehensiveIncome"][$MRQRow] == 'null' ? null: $rawdata["OtherAccumulatedComprehensiveIncome"][$MRQRow]);
	$params[] = ($rawdata["OtherAssets"][$MRQRow] == 'null' ? null: $rawdata["OtherAssets"][$MRQRow]);
	$params[] = ($rawdata["OtherCurrentAssets"][$MRQRow] == 'null' ? null: $rawdata["OtherCurrentAssets"][$MRQRow]);
	$params[] = ($rawdata["OtherCurrentLiabilities"][$MRQRow] == 'null' ? null: $rawdata["OtherCurrentLiabilities"][$MRQRow]);
	$params[] = ($rawdata["OtherEquity"][$MRQRow] == 'null' ? null: $rawdata["OtherEquity"][$MRQRow]);
	$params[] = ($rawdata["OtherInvestments"][$MRQRow] == 'null' ? null: $rawdata["OtherInvestments"][$MRQRow]);
	$params[] = ($rawdata["OtherLiabilities"][$MRQRow] == 'null' ? null: $rawdata["OtherLiabilities"][$MRQRow]);
	$params[] = ($rawdata["PartnersCapital"][$MRQRow] == 'null' ? null: $rawdata["PartnersCapital"][$MRQRow]);
	$params[] = ($rawdata["PensionPostretirementObligation"][$MRQRow] == 'null' ? null: $rawdata["PensionPostretirementObligation"][$MRQRow]);
	$params[] = ($rawdata["PreferredStock"][$MRQRow] == 'null' ? null: $rawdata["PreferredStock"][$MRQRow]);
	$params[] = ($rawdata["PrepaidExpenses"][$MRQRow] == 'null' ? null: $rawdata["PrepaidExpenses"][$MRQRow]);
	$params[] = ($rawdata["PropertyPlantEquipmentNet"][$MRQRow] == 'null' ? null: $rawdata["PropertyPlantEquipmentNet"][$MRQRow]);
	$params[] = ($rawdata["RestrictedCash"][$MRQRow] == 'null' ? null: $rawdata["RestrictedCash"][$MRQRow]);
	$params[] = ($rawdata["RetainedEarnings"][$MRQRow] == 'null' ? null: $rawdata["RetainedEarnings"][$MRQRow]);
	$params[] = ($rawdata["TemporaryEquity"][$MRQRow] == 'null' ? null: $rawdata["TemporaryEquity"][$MRQRow]);
	$params[] = ($rawdata["TotalAssets"][$MRQRow] == 'null' ? null: $rawdata["TotalAssets"][$MRQRow]);
	$params[] = ($rawdata["TotalCurrentAssets"][$MRQRow] == 'null' ? null: $rawdata["TotalCurrentAssets"][$MRQRow]);
	$params[] = ($rawdata["TotalCurrentLiabilities"][$MRQRow] == 'null' ? null: $rawdata["TotalCurrentLiabilities"][$MRQRow]);
	$params[] = ($rawdata["TotalLiabilities"][$MRQRow] == 'null' ? null: $rawdata["TotalLiabilities"][$MRQRow]);
	$params[] = ($rawdata["TotalLongtermDebt"][$MRQRow] == 'null' ? null: $rawdata["TotalLongtermDebt"][$MRQRow]);
	$params[] = ($rawdata["TotalReceivablesNet"][$MRQRow] == 'null' ? null: $rawdata["TotalReceivablesNet"][$MRQRow]);
	$params[] = ($rawdata["TotalShorttermDebt"][$MRQRow] == 'null' ? null: $rawdata["TotalShorttermDebt"][$MRQRow]);
	$params[] = ($rawdata["TotalStockholdersEquity"][$MRQRow] == 'null' ? null: $rawdata["TotalStockholdersEquity"][$MRQRow]);
	$params[] = ($rawdata["TreasuryStock"][$MRQRow] == 'null' ? null: $rawdata["TreasuryStock"][$MRQRow]);
	try {
		$res = $db->prepare($query);
		$res->execute($params);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}

	$query = "INSERT INTO `pttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //43
	$params = array();
	$params[] = $dates->ticker_id;
	$params[] = ($rawdata["CommitmentsContingencies"][$PMRQRow] == 'null' ? null: $rawdata["CommitmentsContingencies"][$PMRQRow]);
	$params[] = ($rawdata["CommonStock"][$PMRQRow] == 'null' ? null: $rawdata["CommonStock"][$PMRQRow]);
	$params[] = ($rawdata["DeferredCharges"][$PMRQRow] == 'null' ? null: $rawdata["DeferredCharges"][$PMRQRow]);
	$params[] = ($rawdata["DeferredIncomeTaxesCurrent"][$PMRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxesCurrent"][$PMRQRow]);
	$params[] = ($rawdata["DeferredIncomeTaxesLongterm"][$PMRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxesLongterm"][$PMRQRow]);
	$params[] = ($rawdata["AccountsPayableandAccruedExpenses"][$PMRQRow] == 'null' ? null: $rawdata["AccountsPayableandAccruedExpenses"][$PMRQRow]);
	$params[] = ($rawdata["AccruedInterest"][$PMRQRow] == 'null' ? null: $rawdata["AccruedInterest"][$PMRQRow]);
	$params[] = ($rawdata["AdditionalPaidinCapital"][$PMRQRow] == 'null' ? null: $rawdata["AdditionalPaidinCapital"][$PMRQRow]);
	$params[] = ($rawdata["AdditionalPaidinCapitalPreferredStock"][$PMRQRow] == 'null' ? null: $rawdata["AdditionalPaidinCapitalPreferredStock"][$PMRQRow]);
	$params[] = ($rawdata["CashandCashEquivalents"][$PMRQRow] == 'null' ? null: $rawdata["CashandCashEquivalents"][$PMRQRow]);
	$params[] = ($rawdata["CashCashEquivalentsandShorttermInvestments"][$PMRQRow] == 'null' ? null: $rawdata["CashCashEquivalentsandShorttermInvestments"][$PMRQRow]);
	$params[] = ($rawdata["Goodwill"][$PMRQRow] == 'null' ? null: $rawdata["Goodwill"][$PMRQRow]);
	$params[] = ($rawdata["IntangibleAssets"][$PMRQRow] == 'null' ? null: $rawdata["IntangibleAssets"][$PMRQRow]);
	$params[] = ($rawdata["InventoriesNet"][$PMRQRow] == 'null' ? null: $rawdata["InventoriesNet"][$PMRQRow]);
	$params[] = ($rawdata["LongtermDeferredIncomeTaxLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["LongtermDeferredIncomeTaxLiabilities"][$PMRQRow]);
	$params[] = ($rawdata["LongtermDeferredLiabilityCharges"][$PMRQRow] == 'null' ? null: $rawdata["LongtermDeferredLiabilityCharges"][$PMRQRow]);
	$params[] = ($rawdata["LongtermInvestments"][$PMRQRow] == 'null' ? null: $rawdata["LongtermInvestments"][$PMRQRow]);
	$params[] = ($rawdata["MinorityInterest"][$PMRQRow] == 'null' ? null: $rawdata["MinorityInterest"][$PMRQRow]);
	$params[] = ($rawdata["OtherAccumulatedComprehensiveIncome"][$PMRQRow] == 'null' ? null: $rawdata["OtherAccumulatedComprehensiveIncome"][$PMRQRow]);
	$params[] = ($rawdata["OtherAssets"][$PMRQRow] == 'null' ? null: $rawdata["OtherAssets"][$PMRQRow]);
	$params[] = ($rawdata["OtherCurrentAssets"][$PMRQRow] == 'null' ? null: $rawdata["OtherCurrentAssets"][$PMRQRow]);
	$params[] = ($rawdata["OtherCurrentLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["OtherCurrentLiabilities"][$PMRQRow]);
	$params[] = ($rawdata["OtherEquity"][$PMRQRow] == 'null' ? null: $rawdata["OtherEquity"][$PMRQRow]);
	$params[] = ($rawdata["OtherInvestments"][$PMRQRow] == 'null' ? null: $rawdata["OtherInvestments"][$PMRQRow]);
	$params[] = ($rawdata["OtherLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["OtherLiabilities"][$PMRQRow]);
	$params[] = ($rawdata["PartnersCapital"][$PMRQRow] == 'null' ? null: $rawdata["PartnersCapital"][$PMRQRow]);
	$params[] = ($rawdata["PensionPostretirementObligation"][$PMRQRow] == 'null' ? null: $rawdata["PensionPostretirementObligation"][$PMRQRow]);
	$params[] = ($rawdata["PreferredStock"][$PMRQRow] == 'null' ? null: $rawdata["PreferredStock"][$PMRQRow]);
	$params[] = ($rawdata["PrepaidExpenses"][$PMRQRow] == 'null' ? null: $rawdata["PrepaidExpenses"][$PMRQRow]);
	$params[] = ($rawdata["PropertyPlantEquipmentNet"][$PMRQRow] == 'null' ? null: $rawdata["PropertyPlantEquipmentNet"][$PMRQRow]);
	$params[] = ($rawdata["RestrictedCash"][$PMRQRow] == 'null' ? null: $rawdata["RestrictedCash"][$PMRQRow]);
	$params[] = ($rawdata["RetainedEarnings"][$PMRQRow] == 'null' ? null: $rawdata["RetainedEarnings"][$PMRQRow]);
	$params[] = ($rawdata["TemporaryEquity"][$PMRQRow] == 'null' ? null: $rawdata["TemporaryEquity"][$PMRQRow]);
	$params[] = ($rawdata["TotalAssets"][$PMRQRow] == 'null' ? null: $rawdata["TotalAssets"][$PMRQRow]);
	$params[] = ($rawdata["TotalCurrentAssets"][$PMRQRow] == 'null' ? null: $rawdata["TotalCurrentAssets"][$PMRQRow]);
	$params[] = ($rawdata["TotalCurrentLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["TotalCurrentLiabilities"][$PMRQRow]);
	$params[] = ($rawdata["TotalLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["TotalLiabilities"][$PMRQRow]);
	$params[] = ($rawdata["TotalLongtermDebt"][$PMRQRow] == 'null' ? null: $rawdata["TotalLongtermDebt"][$PMRQRow]);
	$params[] = ($rawdata["TotalReceivablesNet"][$PMRQRow] == 'null' ? null: $rawdata["TotalReceivablesNet"][$PMRQRow]);
	$params[] = ($rawdata["TotalShorttermDebt"][$PMRQRow] == 'null' ? null: $rawdata["TotalShorttermDebt"][$PMRQRow]);
	$params[] = ($rawdata["TotalStockholdersEquity"][$PMRQRow] == 'null' ? null: $rawdata["TotalStockholdersEquity"][$PMRQRow]);
	$params[] = ($rawdata["TreasuryStock"][$PMRQRow] == 'null' ? null: $rawdata["TreasuryStock"][$PMRQRow]);
	try {
		$res = $db->prepare($query);
		$res->execute($params);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}

	$query = "INSERT INTO `ttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //28
	$params = array();
	$params[] = $dates->ticker_id;
	$params[] = ($rawdata["TotalDebt"][$MRQRow] == 'null' ? null: $rawdata["TotalDebt"][$MRQRow]);
	$params[] = ($rawdata["TotalAssetsFQ"][$MRQRow] == 'null' ? null: $rawdata["TotalAssetsFQ"][$MRQRow]);
	$params[] = ($rawdata["TotalAssetsFY"][$MRQRow] == 'null' ? null: $rawdata["TotalAssetsFY"][$MRQRow]);
	$params[] = ($rawdata["CurrentPortionofLongtermDebt"][$MRQRow] == 'null' ? null: $rawdata["CurrentPortionofLongtermDebt"][$MRQRow]);
	$params[] = ($rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$MRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$MRQRow]);
	$params[] = ($rawdata["DeferredLiabilityCharges"][$MRQRow] == 'null' ? null: $rawdata["DeferredLiabilityCharges"][$MRQRow]);
	$params[] = ($rawdata["AccountsNotesReceivableNet"][$MRQRow] == 'null' ? null: $rawdata["AccountsNotesReceivableNet"][$MRQRow]);
	$params[] = ($rawdata["AccountsPayable"][$MRQRow] == 'null' ? null: $rawdata["AccountsPayable"][$MRQRow]);
	$params[] = ($rawdata["AccountsReceivableTradeNet"][$MRQRow] == 'null' ? null: $rawdata["AccountsReceivableTradeNet"][$MRQRow]);
	$params[] = ($rawdata["AccruedExpenses"][$MRQRow] == 'null' ? null: $rawdata["AccruedExpenses"][$MRQRow]);
	$params[] = ($rawdata["AccumulatedDepreciation"][$MRQRow] == 'null' ? null: $rawdata["AccumulatedDepreciation"][$MRQRow]);
	$params[] = ($rawdata["AmountsDuetoRelatedPartiesShortterm"][$MRQRow] == 'null' ? null: $rawdata["AmountsDuetoRelatedPartiesShortterm"][$MRQRow]);
	$params[] = ($rawdata["GoodwillIntangibleAssetsNet"][$MRQRow] == 'null' ? null: $rawdata["GoodwillIntangibleAssetsNet"][$MRQRow]);
	$params[] = ($rawdata["IncomeTaxesPayable"][$MRQRow] == 'null' ? null: $rawdata["IncomeTaxesPayable"][$MRQRow]);
	$params[] = ($rawdata["LiabilitiesStockholdersEquity"][$MRQRow] == 'null' ? null: $rawdata["LiabilitiesStockholdersEquity"][$MRQRow]);
	$params[] = ($rawdata["LongtermDebt"][$MRQRow] == 'null' ? null: $rawdata["LongtermDebt"][$MRQRow]);
	$params[] = ($rawdata["NotesPayable"][$MRQRow] == 'null' ? null: $rawdata["NotesPayable"][$MRQRow]);
	$params[] = ($rawdata["OperatingLeases"][$MRQRow] == 'null' ? null: $rawdata["OperatingLeases"][$MRQRow]);
	$params[] = ($rawdata["OtherAccountsNotesReceivable"][$MRQRow] == 'null' ? null: $rawdata["OtherAccountsNotesReceivable"][$MRQRow]);
	$params[] = ($rawdata["OtherAccountsPayableandAccruedExpenses"][$MRQRow] == 'null' ? null: $rawdata["OtherAccountsPayableandAccruedExpenses"][$MRQRow]);
	$params[] = ($rawdata["OtherBorrowings"][$MRQRow] == 'null' ? null: $rawdata["OtherBorrowings"][$MRQRow]);
	$params[] = ($rawdata["OtherReceivables"][$MRQRow] == 'null' ? null: $rawdata["OtherReceivables"][$MRQRow]);
	$params[] = ($rawdata["PropertyandEquipmentGross"][$MRQRow] == 'null' ? null: $rawdata["PropertyandEquipmentGross"][$MRQRow]);
	$params[] = ($rawdata["TotalLongtermAssets"][$MRQRow] == 'null' ? null: $rawdata["TotalLongtermAssets"][$MRQRow]);
	$params[] = ($rawdata["TotalLongtermLiabilities"][$MRQRow] == 'null' ? null: $rawdata["TotalLongtermLiabilities"][$MRQRow]);
	$params[] = ($rawdata["TotalSharesOutstanding"][$MRQRow] == 'null' ? null: $rawdata["TotalSharesOutstanding"][$MRQRow]);
	$params[] = ($rawdata["ShorttermInvestments"][$MRQRow] == 'null' ? null: $rawdata["ShorttermInvestments"][$MRQRow]);
	try {
		$res = $db->prepare($query);
		$res->execute($params);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}

	$query = "INSERT INTO `pttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //28
	$params = array();
	$params[] = $dates->ticker_id;
	$params[] = ($rawdata["TotalDebt"][$PMRQRow] == 'null' ? null: $rawdata["TotalDebt"][$PMRQRow]);
	$params[] = ($rawdata["TotalAssetsFQ"][$PMRQRow] == 'null' ? null: $rawdata["TotalAssetsFQ"][$PMRQRow]);
	$params[] = ($rawdata["TotalAssetsFY"][$PMRQRow] == 'null' ? null: $rawdata["TotalAssetsFY"][$PMRQRow]);
	$params[] = ($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow] == 'null' ? null: $rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]);
	$params[] = ($rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$PMRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$PMRQRow]);
	$params[] = ($rawdata["DeferredLiabilityCharges"][$PMRQRow] == 'null' ? null: $rawdata["DeferredLiabilityCharges"][$PMRQRow]);
	$params[] = ($rawdata["AccountsNotesReceivableNet"][$PMRQRow] == 'null' ? null: $rawdata["AccountsNotesReceivableNet"][$PMRQRow]);
	$params[] = ($rawdata["AccountsPayable"][$PMRQRow] == 'null' ? null: $rawdata["AccountsPayable"][$PMRQRow]);
	$params[] = ($rawdata["AccountsReceivableTradeNet"][$PMRQRow] == 'null' ? null: $rawdata["AccountsReceivableTradeNet"][$PMRQRow]);
	$params[] = ($rawdata["AccruedExpenses"][$PMRQRow] == 'null' ? null: $rawdata["AccruedExpenses"][$PMRQRow]);
	$params[] = ($rawdata["AccumulatedDepreciation"][$PMRQRow] == 'null' ? null: $rawdata["AccumulatedDepreciation"][$PMRQRow]);
	$params[] = ($rawdata["AmountsDuetoRelatedPartiesShortterm"][$PMRQRow] == 'null' ? null: $rawdata["AmountsDuetoRelatedPartiesShortterm"][$PMRQRow]);
	$params[] = ($rawdata["GoodwillIntangibleAssetsNet"][$PMRQRow] == 'null' ? null: $rawdata["GoodwillIntangibleAssetsNet"][$PMRQRow]);
	$params[] = ($rawdata["IncomeTaxesPayable"][$PMRQRow] == 'null' ? null: $rawdata["IncomeTaxesPayable"][$PMRQRow]);
	$params[] = ($rawdata["LiabilitiesStockholdersEquity"][$PMRQRow] == 'null' ? null: $rawdata["LiabilitiesStockholdersEquity"][$PMRQRow]);
	$params[] = ($rawdata["LongtermDebt"][$PMRQRow] == 'null' ? null: $rawdata["LongtermDebt"][$PMRQRow]);
	$params[] = ($rawdata["NotesPayable"][$PMRQRow] == 'null' ? null: $rawdata["NotesPayable"][$PMRQRow]);
	$params[] = ($rawdata["OperatingLeases"][$PMRQRow] == 'null' ? null: $rawdata["OperatingLeases"][$PMRQRow]);
	$params[] = ($rawdata["OtherAccountsNotesReceivable"][$PMRQRow] == 'null' ? null: $rawdata["OtherAccountsNotesReceivable"][$PMRQRow]);
	$params[] = ($rawdata["OtherAccountsPayableandAccruedExpenses"][$PMRQRow] == 'null' ? null: $rawdata["OtherAccountsPayableandAccruedExpenses"][$PMRQRow]);
	$params[] = ($rawdata["OtherBorrowings"][$PMRQRow] == 'null' ? null: $rawdata["OtherBorrowings"][$PMRQRow]);
	$params[] = ($rawdata["OtherReceivables"][$PMRQRow] == 'null' ? null: $rawdata["OtherReceivables"][$PMRQRow]);
	$params[] = ($rawdata["PropertyandEquipmentGross"][$PMRQRow] == 'null' ? null: $rawdata["PropertyandEquipmentGross"][$PMRQRow]);
	$params[] = ($rawdata["TotalLongtermAssets"][$PMRQRow] == 'null' ? null: $rawdata["TotalLongtermAssets"][$PMRQRow]);
	$params[] = ($rawdata["TotalLongtermLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["TotalLongtermLiabilities"][$PMRQRow]);
	$params[] = ($rawdata["TotalSharesOutstanding"][$PMRQRow] == 'null' ? null: $rawdata["TotalSharesOutstanding"][$PMRQRow]);
	$params[] = ($rawdata["ShorttermInvestments"][$PMRQRow] == 'null' ? null: $rawdata["ShorttermInvestments"][$PMRQRow]);
	try {
		$res = $db->prepare($query);
		$res->execute($params);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}

	//Cashflow and Financial
	if($stock_type == "ADR") {
		$query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //18
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["InterestIncome"][$MRQRow] == 'null' ? null: toFloat($rawdata["InterestIncome"][$MRQRow]));
		$params[] = ($rawdata["InterestExpense"][$MRQRow] == 'null' ? null: toFloat($rawdata["InterestExpense"][$MRQRow]));
		$params[] = ($rawdata["EPSBasic"][$MRQRow] == 'null' ? null: toFloat($rawdata["EPSBasic"][$MRQRow]));
		$params[] = ($rawdata["EPSDiluted"][$MRQRow] == 'null' ? null: toFloat($rawdata["EPSDiluted"][$MRQRow]));
		$params[] = ($rawdata["SharesOutstandingDiluted"][$MRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]));
		$params[] = ($rawdata["InventoriesRawMaterialsComponents"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]));
		$params[] = ($rawdata["InventoriesWorkInProcess"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]));
		$params[] = ($rawdata["InventoriesInventoriesAdjustments"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]));
		$params[] = ($rawdata["InventoriesFinishedGoods"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]));
		$params[] = ($rawdata["InventoriesOther"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesOther"][$MRQRow]));
		$params[] = ($rawdata["TotalInventories"][$MRQRow] == 'null' ? null: toFloat($rawdata["TotalInventories"][$MRQRow]));
		$params[] = ($rawdata["LandAndImprovements"][$MRQRow] == 'null' ? null: toFloat($rawdata["LandAndImprovements"][$MRQRow]));
		$params[] = ($rawdata["BuildingsAndImprovements"][$MRQRow] == 'null' ? null: toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]));
		$params[] = ($rawdata["MachineryFurnitureEquipment"][$MRQRow] == 'null' ? null: toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]));
		$params[] = ($rawdata["ConstructionInProgress"][$MRQRow] == 'null' ? null: toFloat($rawdata["ConstructionInProgress"][$MRQRow]));
		$params[] = ($rawdata["GrossPropertyPlantandEquipment"][$MRQRow] == 'null' ? null: toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]));
		$params[] = ($rawdata["SharesOutstandingBasic"][$MRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //18
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["InterestIncome"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InterestIncome"][$PMRQRow]));
		$params[] = ($rawdata["InterestExpense"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InterestExpense"][$PMRQRow]));
		$params[] = ($rawdata["EPSBasic"][$PMRQRow] == 'null' ? null: toFloat($rawdata["EPSBasic"][$PMRQRow]));
		$params[] = ($rawdata["EPSDiluted"][$PMRQRow] == 'null' ? null: toFloat($rawdata["EPSDiluted"][$PMRQRow]));
		$params[] = ($rawdata["SharesOutstandingDiluted"][$PMRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesWorkInProcess"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesFinishedGoods"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesOther"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesOther"][$PMRQRow]));
		$params[] = ($rawdata["TotalInventories"][$PMRQRow] == 'null' ? null: toFloat($rawdata["TotalInventories"][$PMRQRow]));
		$params[] = ($rawdata["LandAndImprovements"][$PMRQRow] == 'null' ? null: toFloat($rawdata["LandAndImprovements"][$PMRQRow]));
		$params[] = ($rawdata["BuildingsAndImprovements"][$PMRQRow] == 'null' ? null: toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]));
		$params[] = ($rawdata["MachineryFurnitureEquipment"][$PMRQRow] == 'null' ? null: toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]));
		$params[] = ($rawdata["ConstructionInProgress"][$PMRQRow] == 'null' ? null: toFloat($rawdata["ConstructionInProgress"][$PMRQRow]));
		$params[] = ($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow] == 'null' ? null: toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]));
		$params[] = ($rawdata["SharesOutstandingBasic"][$PMRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //40
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["ChangeinCurrentAssets"][$MRQRow] == 'null' ? null: $rawdata["ChangeinCurrentAssets"][$MRQRow]);
		$params[] = ($rawdata["ChangeinCurrentLiabilities"][$MRQRow] == 'null' ? null: $rawdata["ChangeinCurrentLiabilities"][$MRQRow]);
		$params[] = ($rawdata["ChangeinDebtNet"][$MRQRow] == 'null' ? null: $rawdata["ChangeinCurrentLiabilities"][$MRQRow]);
		$params[] = ($rawdata["ChangeinDeferredRevenue"][$MRQRow] == 'null' ? null: $rawdata["ChangeinDeferredRevenue"][$MRQRow]);
		$params[] = ($rawdata["ChangeinEquityNet"][$MRQRow] == 'null' ? null: $rawdata["ChangeinEquityNet"][$MRQRow]);
		$params[] = ($rawdata["ChangeinIncomeTaxesPayable"][$MRQRow] == 'null' ? null: $rawdata["ChangeinIncomeTaxesPayable"][$MRQRow]);
		$params[] = ($rawdata["ChangeinInventories"][$MRQRow] == 'null' ? null: $rawdata["ChangeinInventories"][$MRQRow]);
		$params[] = ($rawdata["ChangeinOperatingAssetsLiabilities"][$MRQRow] == 'null' ? null: $rawdata["ChangeinOperatingAssetsLiabilities"][$MRQRow]);
		$params[] = ($rawdata["ChangeinOtherAssets"][$MRQRow] == 'null' ? null: $rawdata["ChangeinOtherAssets"][$MRQRow]);
		$params[] = ($rawdata["ChangeinOtherCurrentAssets"][$MRQRow] == 'null' ? null: $rawdata["ChangeinOtherCurrentAssets"][$MRQRow]);
		$params[] = ($rawdata["ChangeinOtherCurrentLiabilities"][$MRQRow] == 'null' ? null: $rawdata["ChangeinOtherCurrentLiabilities"][$MRQRow]);
		$params[] = ($rawdata["ChangeinOtherLiabilities"][$MRQRow] == 'null' ? null: $rawdata["ChangeinOtherLiabilities"][$MRQRow]);
		$params[] = ($rawdata["ChangeinPrepaidExpenses"][$MRQRow] == 'null' ? null: $rawdata["ChangeinPrepaidExpenses"][$MRQRow]);
		$params[] = ($rawdata["DividendsPaid"][$MRQRow] == 'null' ? null: $rawdata["DividendsPaid"][$MRQRow]);
		$params[] = ($rawdata["EffectofExchangeRateonCash"][$MRQRow] == 'null' ? null: $rawdata["EffectofExchangeRateonCash"][$MRQRow]);
		$params[] = ($rawdata["EmployeeCompensation"][$MRQRow] == 'null' ? null: $rawdata["EmployeeCompensation"][$MRQRow]);
		$params[] = ($rawdata["AcquisitionSaleofBusinessNet"][$MRQRow] == 'null' ? null: $rawdata["AcquisitionSaleofBusinessNet"][$MRQRow]);
		$params[] = ($rawdata["AdjustmentforEquityEarnings"][$MRQRow] == 'null' ? null: $rawdata["AdjustmentforEquityEarnings"][$MRQRow]);
		$params[] = ($rawdata["AdjustmentforMinorityInterest"][$MRQRow] == 'null' ? null: $rawdata["AdjustmentforMinorityInterest"][$MRQRow]);
		$params[] = ($rawdata["AdjustmentforSpecialCharges"][$MRQRow] == 'null' ? null: $rawdata["AdjustmentforSpecialCharges"][$MRQRow]);
		$params[] = ($rawdata["CapitalExpenditures"][$MRQRow] == 'null' ? null: $rawdata["CapitalExpenditures"][$MRQRow]);
		$params[] = ($rawdata["CashfromDiscontinuedOperations"][$MRQRow] == 'null' ? null: $rawdata["CashfromDiscontinuedOperations"][$MRQRow]);
		$params[] = ($rawdata["CashfromFinancingActivities"][$MRQRow] == 'null' ? null: $rawdata["CashfromFinancingActivities"][$MRQRow]);
		$params[] = ($rawdata["CashfromInvestingActivities"][$MRQRow] == 'null' ? null: $rawdata["CashfromInvestingActivities"][$MRQRow]);
		$params[] = ($rawdata["CashfromOperatingActivities"][$MRQRow] == 'null' ? null: $rawdata["CashfromOperatingActivities"][$MRQRow]);
		$params[] = ($rawdata["CFDepreciationAmortization"][$MRQRow] == 'null' ? null: $rawdata["CFDepreciationAmortization"][$MRQRow]);
		$params[] = ($rawdata["DeferredIncomeTaxes"][$MRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxes"][$MRQRow]);
		$params[] = ($rawdata["ChangeinAccountsPayableAccruedExpenses"][$MRQRow] == 'null' ? null: $rawdata["ChangeinAccountsPayableAccruedExpenses"][$MRQRow]);
		$params[] = ($rawdata["ChangeinAccountsReceivable"][$MRQRow] == 'null' ? null: $rawdata["ChangeinAccountsReceivable"][$MRQRow]);
		$params[] = ($rawdata["InvestmentChangesNet"][$MRQRow] == 'null' ? null: $rawdata["InvestmentChangesNet"][$MRQRow]);
		$params[] = ($rawdata["NetChangeinCash"][$MRQRow] == 'null' ? null: $rawdata["NetChangeinCash"][$MRQRow]);
		$params[] = ($rawdata["OtherAdjustments"][$MRQRow] == 'null' ? null: $rawdata["OtherAdjustments"][$MRQRow]);
		$params[] = ($rawdata["OtherAssetLiabilityChangesNet"][$MRQRow] == 'null' ? null: $rawdata["OtherAssetLiabilityChangesNet"][$MRQRow]);
		$params[] = ($rawdata["OtherFinancingActivitiesNet"][$MRQRow] == 'null' ? null: $rawdata["OtherFinancingActivitiesNet"][$MRQRow]);
		$params[] = ($rawdata["OtherInvestingActivities"][$MRQRow] == 'null' ? null: $rawdata["OtherInvestingActivities"][$MRQRow]);
		$params[] = ($rawdata["RealizedGainsLosses"][$MRQRow] == 'null' ? null: $rawdata["RealizedGainsLosses"][$MRQRow]);
		$params[] = ($rawdata["SaleofPropertyPlantEquipment"][$MRQRow] == 'null' ? null: $rawdata["SaleofPropertyPlantEquipment"][$MRQRow]);
		$params[] = ($rawdata["StockOptionTaxBenefits"][$MRQRow] == 'null' ? null: $rawdata["StockOptionTaxBenefits"][$MRQRow]);
		$params[] = ($rawdata["TotalAdjustments"][$MRQRow] == 'null' ? null: $rawdata["TotalAdjustments"][$MRQRow]);
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //40
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["ChangeinCurrentAssets"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinCurrentAssets"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinCurrentLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinCurrentLiabilities"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinDebtNet"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinDebtNet"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinDeferredRevenue"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinDeferredRevenue"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinEquityNet"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinEquityNet"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinIncomeTaxesPayable"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinIncomeTaxesPayable"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinInventories"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinInventories"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinOperatingAssetsLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinOperatingAssetsLiabilities"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinOtherAssets"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinOtherAssets"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinOtherCurrentAssets"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinOtherCurrentAssets"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinOtherCurrentLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinOtherCurrentLiabilities"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinOtherLiabilities"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinOtherLiabilities"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinPrepaidExpenses"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinPrepaidExpenses"][$PMRQRow]);
		$params[] = ($rawdata["DividendsPaid"][$PMRQRow] == 'null' ? null: $rawdata["DividendsPaid"][$PMRQRow]);
		$params[] = ($rawdata["EffectofExchangeRateonCash"][$PMRQRow] == 'null' ? null: $rawdata["EffectofExchangeRateonCash"][$PMRQRow]);
		$params[] = ($rawdata["EmployeeCompensation"][$PMRQRow] == 'null' ? null: $rawdata["EmployeeCompensation"][$PMRQRow]);
		$params[] = ($rawdata["AcquisitionSaleofBusinessNet"][$PMRQRow] == 'null' ? null: $rawdata["AcquisitionSaleofBusinessNet"][$PMRQRow]);
		$params[] = ($rawdata["AdjustmentforEquityEarnings"][$PMRQRow] == 'null' ? null: $rawdata["AdjustmentforEquityEarnings"][$PMRQRow]);
		$params[] = ($rawdata["AdjustmentforMinorityInterest"][$PMRQRow] == 'null' ? null: $rawdata["AdjustmentforMinorityInterest"][$PMRQRow]);
		$params[] = ($rawdata["AdjustmentforSpecialCharges"][$PMRQRow] == 'null' ? null: $rawdata["AdjustmentforSpecialCharges"][$PMRQRow]);
		$params[] = ($rawdata["CapitalExpenditures"][$PMRQRow] == 'null' ? null: $rawdata["CapitalExpenditures"][$PMRQRow]);
		$params[] = ($rawdata["CashfromDiscontinuedOperations"][$PMRQRow] == 'null' ? null: $rawdata["CashfromDiscontinuedOperations"][$PMRQRow]);
		$params[] = ($rawdata["CashfromFinancingActivities"][$PMRQRow] == 'null' ? null: $rawdata["CashfromFinancingActivities"][$PMRQRow]);
		$params[] = ($rawdata["CashfromInvestingActivities"][$PMRQRow] == 'null' ? null: $rawdata["CashfromInvestingActivities"][$PMRQRow]);
		$params[] = ($rawdata["CashfromOperatingActivities"][$PMRQRow] == 'null' ? null: $rawdata["CashfromOperatingActivities"][$PMRQRow]);
		$params[] = ($rawdata["CFDepreciationAmortization"][$PMRQRow] == 'null' ? null: $rawdata["CFDepreciationAmortization"][$PMRQRow]);
		$params[] = ($rawdata["DeferredIncomeTaxes"][$PMRQRow] == 'null' ? null: $rawdata["DeferredIncomeTaxes"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinAccountsPayableAccruedExpenses"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinAccountsPayableAccruedExpenses"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinAccountsReceivable"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinAccountsReceivable"][$PMRQRow]);
		$params[] = ($rawdata["InvestmentChangesNet"][$PMRQRow] == 'null' ? null: $rawdata["InvestmentChangesNet"][$PMRQRow]);
		$params[] = ($rawdata["NetChangeinCash"][$PMRQRow] == 'null' ? null: $rawdata["NetChangeinCash"][$PMRQRow]);
		$params[] = ($rawdata["OtherAdjustments"][$PMRQRow] == 'null' ? null: $rawdata["OtherAdjustments"][$PMRQRow]);
		$params[] = ($rawdata["OtherAssetLiabilityChangesNet"][$PMRQRow] == 'null' ? null: $rawdata["OtherAssetLiabilityChangesNet"][$PMRQRow]);
		$params[] = ($rawdata["OtherFinancingActivitiesNet"][$PMRQRow] == 'null' ? null: $rawdata["OtherFinancingActivitiesNet"][$PMRQRow]);
		$params[] = ($rawdata["OtherInvestingActivities"][$PMRQRow] == 'null' ? null: $rawdata["OtherInvestingActivities"][$PMRQRow]);
		$params[] = ($rawdata["RealizedGainsLosses"][$PMRQRow] == 'null' ? null: $rawdata["RealizedGainsLosses"][$PMRQRow]);
		$params[] = ($rawdata["SaleofPropertyPlantEquipment"][$PMRQRow] == 'null' ? null: $rawdata["SaleofPropertyPlantEquipment"][$PMRQRow]);
		$params[] = ($rawdata["StockOptionTaxBenefits"][$PMRQRow] == 'null' ? null: $rawdata["StockOptionTaxBenefits"][$PMRQRow]);
		$params[] = ($rawdata["TotalAdjustments"][$PMRQRow] == 'null' ? null: $rawdata["TotalAdjustments"][$PMRQRow]);
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //19
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["ChangeinLongtermDebtNet"][$MRQRow] == 'null' ? null: $rawdata["ChangeinLongtermDebtNet"][$MRQRow]);
		$params[] = ($rawdata["ChangeinShorttermBorrowingsNet"][$MRQRow] == 'null' ? null: $rawdata["ChangeinShorttermBorrowingsNet"][$MRQRow]);
		$params[] = ($rawdata["CashandCashEquivalentsBeginningofYear"][$MRQRow] == 'null' ? null: $rawdata["CashandCashEquivalentsBeginningofYear"][$MRQRow]);
		$params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$MRQRow] == 'null' ? null: $rawdata["CashandCashEquivalentsEndofYear"][$MRQRow]);
		$params[] = ($rawdata["CashPaidforIncomeTaxes"][$MRQRow] == 'null' ? null: $rawdata["CashPaidforIncomeTaxes"][$MRQRow]);
		$params[] = ($rawdata["CashPaidforInterestExpense"][$MRQRow] == 'null' ? null: $rawdata["CashPaidforInterestExpense"][$MRQRow]);
		$params[] = ($rawdata["CFNetIncome"][$MRQRow] == 'null' ? null: $rawdata["CFNetIncome"][$MRQRow]);
		$params[] = ($rawdata["IssuanceofEquity"][$MRQRow] == 'null' ? null: $rawdata["IssuanceofEquity"][$MRQRow]);
		$params[] = ($rawdata["LongtermDebtPayments"][$MRQRow] == 'null' ? null: $rawdata["LongtermDebtPayments"][$MRQRow]);
		$params[] = ($rawdata["LongtermDebtProceeds"][$MRQRow] == 'null' ? null: $rawdata["LongtermDebtProceeds"][$MRQRow]);
		$params[] = ($rawdata["OtherDebtNet"][$MRQRow] == 'null' ? null: $rawdata["OtherDebtNet"][$MRQRow]);
		$params[] = ($rawdata["OtherEquityTransactionsNet"][$MRQRow] == 'null' ? null: $rawdata["OtherEquityTransactionsNet"][$MRQRow]);
		$params[] = ($rawdata["OtherInvestmentChangesNet"][$MRQRow] == 'null' ? null: $rawdata["OtherInvestmentChangesNet"][$MRQRow]);
		$params[] = ($rawdata["PurchaseofInvestments"][$MRQRow] == 'null' ? null: $rawdata["PurchaseofInvestments"][$MRQRow]);
		$params[] = ($rawdata["RepurchaseofEquity"][$MRQRow] == 'null' ? null: $rawdata["RepurchaseofEquity"][$MRQRow]);
		$params[] = ($rawdata["SaleofInvestments"][$MRQRow] == 'null' ? null: $rawdata["SaleofInvestments"][$MRQRow]);
		$params[] = ($rawdata["ShorttermBorrowings"][$MRQRow] == 'null' ? null: $rawdata["ShorttermBorrowings"][$MRQRow]);
		$params[] = ($rawdata["TotalNoncashAdjustments"][$MRQRow] == 'null' ? null: $rawdata["TotalNoncashAdjustments"][$MRQRow]);
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //19
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["ChangeinLongtermDebtNet"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinLongtermDebtNet"][$PMRQRow]);
		$params[] = ($rawdata["ChangeinShorttermBorrowingsNet"][$PMRQRow] == 'null' ? null: $rawdata["ChangeinShorttermBorrowingsNet"][$PMRQRow]);
		$params[] = ($rawdata["CashandCashEquivalentsBeginningofYear"][$PMRQRow] == 'null' ? null: $rawdata["CashandCashEquivalentsBeginningofYear"][$PMRQRow]);
		$params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow] == 'null' ? null: $rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow]);
		$params[] = ($rawdata["CashPaidforIncomeTaxes"][$PMRQRow] == 'null' ? null: $rawdata["CashPaidforIncomeTaxes"][$PMRQRow]);
		$params[] = ($rawdata["CashPaidforInterestExpense"][$PMRQRow] == 'null' ? null: $rawdata["CashPaidforInterestExpense"][$PMRQRow]);
		$params[] = ($rawdata["CFNetIncome"][$PMRQRow] == 'null' ? null: $rawdata["CFNetIncome"][$PMRQRow]);
		$params[] = ($rawdata["IssuanceofEquity"][$PMRQRow] == 'null' ? null: $rawdata["IssuanceofEquity"][$PMRQRow]);
		$params[] = ($rawdata["LongtermDebtPayments"][$PMRQRow] == 'null' ? null: $rawdata["LongtermDebtPayments"][$PMRQRow]);
		$params[] = ($rawdata["LongtermDebtProceeds"][$PMRQRow] == 'null' ? null: $rawdata["LongtermDebtProceeds"][$PMRQRow]);
		$params[] = ($rawdata["OtherDebtNet"][$PMRQRow] == 'null' ? null: $rawdata["OtherDebtNet"][$PMRQRow]);
		$params[] = ($rawdata["OtherEquityTransactionsNet"][$PMRQRow] == 'null' ? null: $rawdata["OtherEquityTransactionsNet"][$PMRQRow]);
		$params[] = ($rawdata["OtherInvestmentChangesNet"][$PMRQRow] == 'null' ? null: $rawdata["OtherInvestmentChangesNet"][$PMRQRow]);
		$params[] = ($rawdata["PurchaseofInvestments"][$PMRQRow] == 'null' ? null: $rawdata["PurchaseofInvestments"][$PMRQRow]);
		$params[] = ($rawdata["RepurchaseofEquity"][$PMRQRow] == 'null' ? null: $rawdata["RepurchaseofEquity"][$PMRQRow]);
		$params[] = ($rawdata["SaleofInvestments"][$PMRQRow] == 'null' ? null: $rawdata["SaleofInvestments"][$PMRQRow]);
		$params[] = ($rawdata["ShorttermBorrowings"][$PMRQRow] == 'null' ? null: $rawdata["ShorttermBorrowings"][$PMRQRow]);
		$params[] = ($rawdata["TotalNoncashAdjustments"][$PMRQRow] == 'null' ? null: $rawdata["TotalNoncashAdjustments"][$PMRQRow]);
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //26
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["EBIT"][$MRQRow] == 'null' ? null: $rawdata["EBIT"][$MRQRow]);
		$params[] = ($rawdata["CostofRevenue"][$MRQRow] == 'null' ? null: $rawdata["CostofRevenue"][$MRQRow]);
		$params[] = ($rawdata["DepreciationAmortizationExpense"][$MRQRow] == 'null' ? null: $rawdata["DepreciationAmortizationExpense"][$MRQRow]);
		$params[] = ($rawdata["DilutedEPSNetIncome"][$MRQRow] == 'null' ? null: $rawdata["DilutedEPSNetIncome"][$MRQRow]);
		$params[] = ($rawdata["DiscontinuedOperations"][$MRQRow] == 'null' ? null: $rawdata["DiscontinuedOperations"][$MRQRow]);
		$params[] = ($rawdata["EquityEarnings"][$MRQRow] == 'null' ? null: $rawdata["EquityEarnings"][$MRQRow]);
		$params[] = ($rawdata["AccountingChange"][$MRQRow] == 'null' ? null: $rawdata["AccountingChange"][$MRQRow]);
		$params[] = ($rawdata["BasicEPSNetIncome"][$MRQRow] == 'null' ? null: $rawdata["BasicEPSNetIncome"][$MRQRow]);
		$params[] = ($rawdata["ExtraordinaryItems"][$MRQRow] == 'null' ? null: $rawdata["ExtraordinaryItems"][$MRQRow]);
		$params[] = ($rawdata["GrossProfit"][$MRQRow] == 'null' ? null: $rawdata["GrossProfit"][$MRQRow]);
		$params[] = ($rawdata["IncomebeforeExtraordinaryItems"][$MRQRow] == 'null' ? null: $rawdata["IncomebeforeExtraordinaryItems"][$MRQRow]);
		$params[] = ($rawdata["IncomeBeforeTaxes"][$MRQRow] == 'null' ? null: $rawdata["IncomeBeforeTaxes"][$MRQRow]);
		$params[] = ($rawdata["IncomeTaxes"][$MRQRow] == 'null' ? null: $rawdata["IncomeTaxes"][$MRQRow]);
		$params[] = ($rawdata["InterestExpense"][$MRQRow] == 'null' ? null: toFloat($rawdata["InterestExpense"][$MRQRow]));
		$params[] = ($rawdata["InterestIncome"][$MRQRow] == 'null' ? null: toFloat($rawdata["InterestIncome"][$MRQRow]));
		$params[] = ($rawdata["MinorityInterestEquityEarnings"][$MRQRow] == 'null' ? null: $rawdata["MinorityInterestEquityEarnings"][$MRQRow]);
		$params[] = ($rawdata["NetIncome"][$MRQRow] == 'null' ? null: $rawdata["NetIncome"][$MRQRow]);
		$params[] = ($rawdata["NetIncomeApplicabletoCommon"][$MRQRow] == 'null' ? null: $rawdata["NetIncomeApplicabletoCommon"][$MRQRow]);
		$params[] = ($rawdata["OperatingProfit"][$MRQRow] == 'null' ? null: $rawdata["OperatingProfit"][$MRQRow]);
		$params[] = ($rawdata["OtherNonoperatingIncomeExpense"][$MRQRow] == 'null' ? null: $rawdata["OtherNonoperatingIncomeExpense"][$MRQRow]);
		$params[] = ($rawdata["OtherOperatingExpenses"][$MRQRow] == 'null' ? null: $rawdata["OtherOperatingExpenses"][$MRQRow]);
		$params[] = ($rawdata["ResearchDevelopmentExpense"][$MRQRow] == 'null' ? null: $rawdata["ResearchDevelopmentExpense"][$MRQRow]);
		$params[] = ($rawdata["RestructuringRemediationImpairmentProvisions"][$MRQRow] == 'null' ? null: $rawdata["RestructuringRemediationImpairmentProvisions"][$MRQRow]);
		$params[] = ($rawdata["TotalRevenue"][$MRQRow] == 'null' ? null: $rawdata["TotalRevenue"][$MRQRow]);
		$params[] = ($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow] == 'null' ? null: $rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]);
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //26
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["EBIT"][$PMRQRow] == 'null' ? null: $rawdata["EBIT"][$PMRQRow]);
		$params[] = ($rawdata["CostofRevenue"][$PMRQRow] == 'null' ? null: $rawdata["CostofRevenue"][$PMRQRow]);
		$params[] = ($rawdata["DepreciationAmortizationExpense"][$PMRQRow] == 'null' ? null: $rawdata["DepreciationAmortizationExpense"][$PMRQRow]);
		$params[] = ($rawdata["DilutedEPSNetIncome"][$PMRQRow] == 'null' ? null: $rawdata["DilutedEPSNetIncome"][$PMRQRow]);
		$params[] = ($rawdata["DiscontinuedOperations"][$PMRQRow] == 'null' ? null: $rawdata["DiscontinuedOperations"][$PMRQRow]);
		$params[] = ($rawdata["EquityEarnings"][$PMRQRow] == 'null' ? null: $rawdata["EquityEarnings"][$PMRQRow]);
		$params[] = ($rawdata["AccountingChange"][$PMRQRow] == 'null' ? null: $rawdata["AccountingChange"][$PMRQRow]);
		$params[] = ($rawdata["BasicEPSNetIncome"][$PMRQRow] == 'null' ? null: $rawdata["BasicEPSNetIncome"][$PMRQRow]);
		$params[] = ($rawdata["ExtraordinaryItems"][$PMRQRow] == 'null' ? null: $rawdata["ExtraordinaryItems"][$PMRQRow]);
		$params[] = ($rawdata["GrossProfit"][$PMRQRow] == 'null' ? null: $rawdata["GrossProfit"][$PMRQRow]);
		$params[] = ($rawdata["IncomebeforeExtraordinaryItems"][$PMRQRow] == 'null' ? null: $rawdata["IncomebeforeExtraordinaryItems"][$PMRQRow]);
		$params[] = ($rawdata["IncomeBeforeTaxes"][$PMRQRow] == 'null' ? null: $rawdata["IncomeBeforeTaxes"][$PMRQRow]);
		$params[] = ($rawdata["IncomeTaxes"][$PMRQRow] == 'null' ? null: $rawdata["IncomeTaxes"][$PMRQRow]);
		$params[] = ($rawdata["InterestExpense"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InterestExpense"][$PMRQRow]));
		$params[] = ($rawdata["InterestIncome"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InterestIncome"][$PMRQRow]));
		$params[] = ($rawdata["MinorityInterestEquityEarnings"][$PMRQRow] == 'null' ? null: $rawdata["MinorityInterestEquityEarnings"][$PMRQRow]);
		$params[] = ($rawdata["NetIncome"][$PMRQRow] == 'null' ? null: $rawdata["NetIncome"][$PMRQRow]);
		$params[] = ($rawdata["NetIncomeApplicabletoCommon"][$PMRQRow] == 'null' ? null: $rawdata["NetIncomeApplicabletoCommon"][$PMRQRow]);
		$params[] = ($rawdata["OperatingProfit"][$PMRQRow] == 'null' ? null: $rawdata["OperatingProfit"][$PMRQRow]);
		$params[] = ($rawdata["OtherNonoperatingIncomeExpense"][$PMRQRow] == 'null' ? null: $rawdata["OtherNonoperatingIncomeExpense"][$PMRQRow]);
		$params[] = ($rawdata["OtherOperatingExpenses"][$PMRQRow] == 'null' ? null: $rawdata["OtherOperatingExpenses"][$PMRQRow]);
		$params[] = ($rawdata["ResearchDevelopmentExpense"][$PMRQRow] == 'null' ? null: $rawdata["ResearchDevelopmentExpense"][$PMRQRow]);
		$params[] = ($rawdata["RestructuringRemediationImpairmentProvisions"][$PMRQRow] == 'null' ? null: $rawdata["RestructuringRemediationImpairmentProvisions"][$PMRQRow]);
		$params[] = ($rawdata["TotalRevenue"][$PMRQRow] == 'null' ? null: $rawdata["TotalRevenue"][$PMRQRow]);
		$params[] = ($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow] == 'null' ? null: $rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]);     
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //36
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["AdjustedEBIT"][$MRQRow] == 'null' ? null: $rawdata["AdjustedEBIT"][$MRQRow]);
		$params[] = ($rawdata["AdjustedEBITDA"][$MRQRow] == 'null' ? null: $rawdata["AdjustedEBITDA"][$MRQRow]);
		$params[] = ($rawdata["AdjustedNetIncome"][$MRQRow] == 'null' ? null: $rawdata["AdjustedNetIncome"][$MRQRow]);
		$params[] = ($rawdata["AftertaxMargin"][$MRQRow] == 'null' ? null: $rawdata["AftertaxMargin"][$MRQRow]);
		$params[] = ($rawdata["EBITDA"][$MRQRow] == 'null' ? null: $rawdata["EBITDA"][$MRQRow]);
		$params[] = ($rawdata["GrossMargin"][$MRQRow] == 'null' ? null: $rawdata["GrossMargin"][$MRQRow]);
		$params[] = ($rawdata["NetOperatingProfitafterTax"][$MRQRow] == 'null' ? null: $rawdata["NetOperatingProfitafterTax"][$MRQRow]);
		$params[] = ($rawdata["OperatingMargin"][$MRQRow] == 'null' ? null: $rawdata["OperatingMargin"][$MRQRow]);
		$params[] = ($rawdata["RevenueFQ"][$MRQRow] == 'null' ? null: $rawdata["RevenueFQ"][$MRQRow]);
		$params[] = ($rawdata["RevenueFY"][$MRQRow] == 'null' ? null: $rawdata["RevenueFY"][$MRQRow]);
		$params[] = ($rawdata["RevenueTTM"][$MRQRow] == 'null' ? null: $rawdata["RevenueTTM"][$MRQRow]);
		$params[] = ($rawdata["CostOperatingExpenses"][$MRQRow] == 'null' ? null: $rawdata["CostOperatingExpenses"][$MRQRow]);
		$params[] = ($rawdata["DepreciationExpense"][$MRQRow] == 'null' ? null: $rawdata["DepreciationExpense"][$MRQRow]);
		$params[] = ($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$MRQRow] == 'null' ? null: $rawdata["DilutedEPSNetIncomefromContinuingOperations"][$MRQRow]);
		$params[] = ($rawdata["DilutedWeightedAverageShares"][$MRQRow] == 'null' ? null: $rawdata["DilutedWeightedAverageShares"][$MRQRow]);
		$params[] = ($rawdata["AmortizationExpense"][$MRQRow] == 'null' ? null: $rawdata["AmortizationExpense"][$MRQRow]);
		$params[] = ($rawdata["BasicEPSNetIncomefromContinuingOperations"][$MRQRow] == 'null' ? null: $rawdata["BasicEPSNetIncomefromContinuingOperations"][$MRQRow]);
		$params[] = ($rawdata["BasicWeightedAverageShares"][$MRQRow] == 'null' ? null: $rawdata["BasicWeightedAverageShares"][$MRQRow]);
		$params[] = ($rawdata["GeneralAdministrativeExpense"][$MRQRow] == 'null' ? null: $rawdata["GeneralAdministrativeExpense"][$MRQRow]);
		$params[] = ($rawdata["IncomeAfterTaxes"][$MRQRow] == 'null' ? null: $rawdata["IncomeAfterTaxes"][$MRQRow]);
		$params[] = ($rawdata["LaborExpense"][$MRQRow] == 'null' ? null: $rawdata["LaborExpense"][$MRQRow]);
		$params[] = ($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$MRQRow] == 'null' ? null: $rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$MRQRow]);
		$params[] = ($rawdata["InterestIncomeExpenseNet"][$MRQRow] == 'null' ? null: $rawdata["InterestIncomeExpenseNet"][$MRQRow]);
		$params[] = ($rawdata["NoncontrollingInterest"][$MRQRow] == 'null' ? null: $rawdata["NoncontrollingInterest"][$MRQRow]);
		$params[] = ($rawdata["NonoperatingGainsLosses"][$MRQRow] == 'null' ? null: $rawdata["NonoperatingGainsLosses"][$MRQRow]);
		$params[] = ($rawdata["OperatingExpenses"][$MRQRow] == 'null' ? null: $rawdata["OperatingExpenses"][$MRQRow]);
		$params[] = ($rawdata["OtherGeneralAdministrativeExpense"][$MRQRow] == 'null' ? null: $rawdata["OtherGeneralAdministrativeExpense"][$MRQRow]);
		$params[] = ($rawdata["OtherInterestIncomeExpenseNet"][$MRQRow] == 'null' ? null: $rawdata["OtherInterestIncomeExpenseNet"][$MRQRow]);
		$params[] = ($rawdata["OtherRevenue"][$MRQRow] == 'null' ? null: $rawdata["OtherRevenue"][$MRQRow]);
		$params[] = ($rawdata["OtherSellingGeneralAdministrativeExpenses"][$MRQRow] == 'null' ? null: $rawdata["OtherSellingGeneralAdministrativeExpenses"][$MRQRow]);
		$params[] = ($rawdata["PreferredDividends"][$MRQRow] == 'null' ? null: $rawdata["PreferredDividends"][$MRQRow]);
		$params[] = ($rawdata["SalesMarketingExpense"][$MRQRow] == 'null' ? null: $rawdata["SalesMarketingExpense"][$MRQRow]);
		$params[] = ($rawdata["TotalNonoperatingIncomeExpense"][$MRQRow] == 'null' ? null: $rawdata["TotalNonoperatingIncomeExpense"][$MRQRow]);
		$params[] = ($rawdata["TotalOperatingExpenses"][$MRQRow] == 'null' ? null: $rawdata["TotalOperatingExpenses"][$MRQRow]);
		$params[] = ($rawdata["OperatingRevenue"][$MRQRow] == 'null' ? null: $rawdata["OperatingRevenue"][$MRQRow]);
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //36
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ($rawdata["AdjustedEBIT"][$PMRQRow] == 'null' ? null: $rawdata["AdjustedEBIT"][$PMRQRow]);
		$params[] = ($rawdata["AdjustedEBITDA"][$PMRQRow] == 'null' ? null: $rawdata["AdjustedEBITDA"][$PMRQRow]);
		$params[] = ($rawdata["AdjustedNetIncome"][$PMRQRow] == 'null' ? null: $rawdata["AdjustedNetIncome"][$PMRQRow]);
		$params[] = ($rawdata["AftertaxMargin"][$PMRQRow] == 'null' ? null: $rawdata["AftertaxMargin"][$PMRQRow]);
		$params[] = ($rawdata["EBITDA"][$PMRQRow] == 'null' ? null: $rawdata["EBITDA"][$PMRQRow]);
		$params[] = ($rawdata["GrossMargin"][$PMRQRow] == 'null' ? null: $rawdata["GrossMargin"][$PMRQRow]);
		$params[] = ($rawdata["NetOperatingProfitafterTax"][$PMRQRow] == 'null' ? null: $rawdata["NetOperatingProfitafterTax"][$PMRQRow]);
		$params[] = ($rawdata["OperatingMargin"][$PMRQRow] == 'null' ? null: $rawdata["OperatingMargin"][$PMRQRow]);
		$params[] = ($rawdata["RevenueFQ"][$PMRQRow] == 'null' ? null: $rawdata["RevenueFQ"][$PMRQRow]);
		$params[] = ($rawdata["RevenueFY"][$PMRQRow] == 'null' ? null: $rawdata["RevenueFY"][$PMRQRow]);
		$params[] = ($rawdata["RevenueTTM"][$PMRQRow] == 'null' ? null: $rawdata["RevenueTTM"][$PMRQRow]);
		$params[] = ($rawdata["CostOperatingExpenses"][$PMRQRow] == 'null' ? null: $rawdata["CostOperatingExpenses"][$PMRQRow]);
		$params[] = ($rawdata["DepreciationExpense"][$PMRQRow] == 'null' ? null: $rawdata["DepreciationExpense"][$PMRQRow]);
		$params[] = ($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$PMRQRow] == 'null' ? null: $rawdata["DilutedEPSNetIncomefromContinuingOperations"][$PMRQRow]);
		$params[] = ($rawdata["DilutedWeightedAverageShares"][$PMRQRow] == 'null' ? null: $rawdata["DilutedWeightedAverageShares"][$PMRQRow]);
		$params[] = ($rawdata["AmortizationExpense"][$PMRQRow] == 'null' ? null: $rawdata["AmortizationExpense"][$PMRQRow]);
		$params[] = ($rawdata["BasicEPSNetIncomefromContinuingOperations"][$PMRQRow] == 'null' ? null: $rawdata["BasicEPSNetIncomefromContinuingOperations"][$PMRQRow]);
		$params[] = ($rawdata["BasicWeightedAverageShares"][$PMRQRow] == 'null' ? null: $rawdata["BasicWeightedAverageShares"][$PMRQRow]);
		$params[] = ($rawdata["GeneralAdministrativeExpense"][$PMRQRow] == 'null' ? null: $rawdata["GeneralAdministrativeExpense"][$PMRQRow]);
		$params[] = ($rawdata["IncomeAfterTaxes"][$PMRQRow] == 'null' ? null: $rawdata["IncomeAfterTaxes"][$PMRQRow]);
		$params[] = ($rawdata["LaborExpense"][$PMRQRow] == 'null' ? null: $rawdata["LaborExpense"][$PMRQRow]);
		$params[] = ($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$PMRQRow] == 'null' ? null: $rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$PMRQRow]);
		$params[] = ($rawdata["InterestIncomeExpenseNet"][$PMRQRow] == 'null' ? null: $rawdata["InterestIncomeExpenseNet"][$PMRQRow]);
		$params[] = ($rawdata["NoncontrollingInterest"][$PMRQRow] == 'null' ? null: $rawdata["NoncontrollingInterest"][$PMRQRow]);
		$params[] = ($rawdata["NonoperatingGainsLosses"][$PMRQRow] == 'null' ? null: $rawdata["NonoperatingGainsLosses"][$PMRQRow]);
		$params[] = ($rawdata["OperatingExpenses"][$PMRQRow] == 'null' ? null: $rawdata["OperatingExpenses"][$PMRQRow]);
		$params[] = ($rawdata["OtherGeneralAdministrativeExpense"][$PMRQRow] == 'null' ? null: $rawdata["OtherGeneralAdministrativeExpense"][$PMRQRow]);
		$params[] = ($rawdata["OtherInterestIncomeExpenseNet"][$PMRQRow] == 'null' ? null: $rawdata["OtherInterestIncomeExpenseNet"][$PMRQRow]);
		$params[] = ($rawdata["OtherRevenue"][$PMRQRow] == 'null' ? null: $rawdata["OtherRevenue"][$PMRQRow]);
		$params[] = ($rawdata["OtherSellingGeneralAdministrativeExpenses"][$PMRQRow] == 'null' ? null: $rawdata["OtherSellingGeneralAdministrativeExpenses"][$PMRQRow]);
		$params[] = ($rawdata["PreferredDividends"][$PMRQRow] == 'null' ? null: $rawdata["PreferredDividends"][$PMRQRow]);
		$params[] = ($rawdata["SalesMarketingExpense"][$PMRQRow] == 'null' ? null: $rawdata["SalesMarketingExpense"][$PMRQRow]);
		$params[] = ($rawdata["TotalNonoperatingIncomeExpense"][$PMRQRow] == 'null' ? null: $rawdata["TotalNonoperatingIncomeExpense"][$PMRQRow]);
		$params[] = ($rawdata["TotalOperatingExpenses"][$PMRQRow] == 'null' ? null: $rawdata["TotalOperatingExpenses"][$PMRQRow]);
		$params[] = ($rawdata["OperatingRevenue"][$PMRQRow] == 'null' ? null: $rawdata["OperatingRevenue"][$PMRQRow]);
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//20
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["CostofRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["CostofRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["GrossProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["GrossProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]=='null' ||  $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["ResearchDevelopmentExpense"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["ResearchDevelopmentExpense"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["CFDepreciationAmortization"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["CFDepreciationAmortization"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["EBITDA"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["EBITDA"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["OperatingProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["OperatingProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["EBIT"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["EBIT"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["IncomeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]==0)?null:($rawdata["IncomeTaxes"][$MRQRow]/$rawdata["IncomeBeforeTaxes"][$MRQRow]));
		$params[] = (($rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' && $rawdata["IncomeTaxes"][$MRQRow]=='null')?null:($rawdata["IncomeBeforeTaxes"][$MRQRow]-$rawdata["IncomeTaxes"][$MRQRow]));
		$params[] = (($rawdata["NetIncome"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?null:($rawdata["NetIncome"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow]));
		$params[] = (($rawdata["DividendsPaid"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]==0)?null:(-($rawdata["DividendsPaid"][$MRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$MRQRow])*1000000)));
		$params[] = (($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]=='null' && $rawdata["ShorttermBorrowings"][$MRQRow]=='null')?null:($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow]));
		$params[] = (($rawdata["TotalLongtermDebt"][$MRQRow]=='null' && $rawdata["NotesPayable"][$MRQRow]=='null')?null:($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow]));
		$params[] = (($rawdata["LongtermDebtProceeds"][$MRQRow]=='null' && $rawdata["LongtermDebtPayments"][$MRQRow] == 'null')?null:($rawdata["LongtermDebtProceeds"][$MRQRow]+$rawdata["LongtermDebtPayments"][$MRQRow]));
		$params[] = (($rawdata["CapitalExpenditures"][$MRQRow]=='null')?null:(-$rawdata["CapitalExpenditures"][$MRQRow]));
		$params[] = (($rawdata["CashfromOperatingActivities"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null')?null:($rawdata["CashfromOperatingActivities"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow]));
		$params[] = (($rawdata["CFNetIncome"][$MRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$MRQRow]=='null' && $rawdata["EmployeeCompensation"][$MRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$MRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$MRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$MRQRow]=='null')?null:($rawdata["CFNetIncome"][$MRQRow]+$rawdata["CFDepreciationAmortization"][$MRQRow]+$rawdata["EmployeeCompensation"][$MRQRow]+$rawdata["AdjustmentforSpecialCharges"][$MRQRow]+$rawdata["DeferredIncomeTaxes"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow]+($rawdata["ChangeinCurrentAssets"][$MRQRow]+$rawdata["ChangeinCurrentLiabilities"][$MRQRow])));
		$params[] = (($rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]<=0 || $rawdata["TotalRevenue"][$MRQRow] < 0)?null:(pow($rawdata["TotalRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow-5], 1/5) - 1));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//19
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["CostofRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["CostofRevenue"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["GrossProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["GrossProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]=='null' ||  $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["ResearchDevelopmentExpense"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["ResearchDevelopmentExpense"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["CFDepreciationAmortization"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["EBITDA"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["EBITDA"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["OperatingProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["OperatingProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["EBIT"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["EBIT"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["IncomeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]==0)?null:($rawdata["IncomeTaxes"][$PMRQRow]/$rawdata["IncomeBeforeTaxes"][$PMRQRow]));
		$params[] = (($rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' && $rawdata["IncomeTaxes"][$PMRQRow]=='null')?null:($rawdata["IncomeBeforeTaxes"][$PMRQRow]-$rawdata["IncomeTaxes"][$PMRQRow]));
		$params[] = (($rawdata["NetIncome"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?null:($rawdata["NetIncome"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow]));
		$params[] = (($rawdata["DividendsPaid"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]==0)?null:(-($rawdata["DividendsPaid"][$PMRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow])*1000000)));
		$params[] = (($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]=='null' && $rawdata["ShorttermBorrowings"][$PMRQRow]=='null')?null:($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]+$rawdata["ShorttermBorrowings"][$PMRQRow]));
		$params[] = (($rawdata["TotalLongtermDebt"][$PMRQRow]=='null' && $rawdata["NotesPayable"][$PMRQRow]=='null')?null:($rawdata["TotalLongtermDebt"][$PMRQRow]+$rawdata["NotesPayable"][$PMRQRow]));
		$params[] = (($rawdata["LongtermDebtProceeds"][$PMRQRow]=='null' && $rawdata["LongtermDebtPayments"][$PMRQRow] == 'null')?null:($rawdata["LongtermDebtProceeds"][$PMRQRow]+$rawdata["LongtermDebtPayments"][$PMRQRow]));
		$params[] = (($rawdata["CapitalExpenditures"][$PMRQRow]=='null')?null:(-$rawdata["CapitalExpenditures"][$PMRQRow]));
		$params[] = (($rawdata["CashfromOperatingActivities"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null')?null:($rawdata["CashfromOperatingActivities"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow]));
		$params[] = (($rawdata["CFNetIncome"][$PMRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' && $rawdata["EmployeeCompensation"][$PMRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$PMRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$PMRQRow]=='null')?null:($rawdata["CFNetIncome"][$PMRQRow]+$rawdata["CFDepreciationAmortization"][$PMRQRow]+$rawdata["EmployeeCompensation"][$PMRQRow]+$rawdata["AdjustmentforSpecialCharges"][$PMRQRow]+$rawdata["DeferredIncomeTaxes"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow]+($rawdata["ChangeinCurrentAssets"][$PMRQRow]+$rawdata["ChangeinCurrentLiabilities"][$PMRQRow])));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}
	} else {
		$query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//18
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["InterestIncome"][$treports-3]=='null'&&$rawdata["InterestIncome"][$treports-2]=='null'&&$rawdata["InterestIncome"][$treports-1]=='null'&&$rawdata["InterestIncome"][$treports]=='null')?null:(toFloat($rawdata["InterestIncome"][$treports-3])+toFloat($rawdata["InterestIncome"][$treports-2])+toFloat($rawdata["InterestIncome"][$treports-1])+toFloat($rawdata["InterestIncome"][$treports])));
		$params[] = (($rawdata["InterestExpense"][$treports-3]=='null'&&$rawdata["InterestExpense"][$treports-2]=='null'&&$rawdata["InterestExpense"][$treports-1]=='null'&&$rawdata["InterestExpense"][$treports]=='null')?null:(toFloat($rawdata["InterestExpense"][$treports-3])+toFloat($rawdata["InterestExpense"][$treports-2])+toFloat($rawdata["InterestExpense"][$treports-1])+toFloat($rawdata["InterestExpense"][$treports])));
		$params[] = (($rawdata["EPSBasic"][$treports-3]=='null'&&$rawdata["EPSBasic"][$treports-2]=='null'&&$rawdata["EPSBasic"][$treports-1]=='null'&&$rawdata["EPSBasic"][$treports]=='null')?null:(toFloat($rawdata["EPSBasic"][$treports-3])+toFloat($rawdata["EPSBasic"][$treports-2])+toFloat($rawdata["EPSBasic"][$treports-1])+toFloat($rawdata["EPSBasic"][$treports])));
		$params[] = (($rawdata["EPSDiluted"][$treports-3]=='null'&&$rawdata["EPSDiluted"][$treports-2]=='null'&&$rawdata["EPSDiluted"][$treports-1]=='null'&&$rawdata["EPSDiluted"][$treports]=='null')?null:(toFloat($rawdata["EPSDiluted"][$treports-3])+toFloat($rawdata["EPSDiluted"][$treports-2])+toFloat($rawdata["EPSDiluted"][$treports-1])+toFloat($rawdata["EPSDiluted"][$treports])));
		$params[] = ($rawdata["SharesOutstandingDiluted"][$MRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]));
		$params[] = ($rawdata["InventoriesRawMaterialsComponents"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]));
		$params[] = ($rawdata["InventoriesWorkInProcess"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]));
		$params[] = ($rawdata["InventoriesInventoriesAdjustments"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]));
		$params[] = ($rawdata["InventoriesFinishedGoods"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]));
		$params[] = ($rawdata["InventoriesOther"][$MRQRow] == 'null' ? null: toFloat($rawdata["InventoriesOther"][$MRQRow]));
		$params[] = ($rawdata["TotalInventories"][$MRQRow] == 'null' ? null: toFloat($rawdata["TotalInventories"][$MRQRow]));
		$params[] = ($rawdata["LandAndImprovements"][$MRQRow] == 'null' ? null: toFloat($rawdata["LandAndImprovements"][$MRQRow]));
		$params[] = ($rawdata["BuildingsAndImprovements"][$MRQRow] == 'null' ? null: toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]));
		$params[] = ($rawdata["MachineryFurnitureEquipment"][$MRQRow] == 'null' ? null: toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]));
		$params[] = ($rawdata["ConstructionInProgress"][$MRQRow] == 'null' ? null: toFloat($rawdata["ConstructionInProgress"][$MRQRow]));
		$params[] = ($rawdata["GrossPropertyPlantandEquipment"][$MRQRow] == 'null' ? null: toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]));
		$params[] = ($rawdata["SharesOutstandingBasic"][$MRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//18
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["InterestIncome"][$treports-7]=='null'&&$rawdata["InterestIncome"][$treports-6]=='null'&&$rawdata["InterestIncome"][$treports-5]=='null'&&$rawdata["InterestIncome"][$treports-4]=='null')?null:(toFloat($rawdata["InterestIncome"][$treports-7])+toFloat($rawdata["InterestIncome"][$treports-6])+toFloat($rawdata["InterestIncome"][$treports-5])+toFloat($rawdata["InterestIncome"][$treports-4])));
		$params[] = (($rawdata["InterestExpense"][$treports-7]=='null'&&$rawdata["InterestExpense"][$treports-6]=='null'&&$rawdata["InterestExpense"][$treports-5]=='null'&&$rawdata["InterestExpense"][$treports-4]=='null')?null:(toFloat($rawdata["InterestExpense"][$treports-7])+toFloat($rawdata["InterestExpense"][$treports-6])+toFloat($rawdata["InterestExpense"][$treports-5])+toFloat($rawdata["InterestExpense"][$treports-4])));
		$params[] = (($rawdata["EPSBasic"][$treports-7]=='null'&&$rawdata["EPSBasic"][$treports-6]=='null'&&$rawdata["EPSBasic"][$treports-5]=='null'&&$rawdata["EPSBasic"][$treports-4]=='null')?null:(toFloat($rawdata["EPSBasic"][$treports-7])+toFloat($rawdata["EPSBasic"][$treports-6])+toFloat($rawdata["EPSBasic"][$treports-5])+toFloat($rawdata["EPSBasic"][$treports-4])));
		$params[] = (($rawdata["EPSDiluted"][$treports-7]=='null'&&$rawdata["EPSDiluted"][$treports-6]=='null'&&$rawdata["EPSDiluted"][$treports-5]=='null'&&$rawdata["EPSDiluted"][$treports-4]=='null')?null:(toFloat($rawdata["EPSDiluted"][$treports-7])+toFloat($rawdata["EPSDiluted"][$treports-6])+toFloat($rawdata["EPSDiluted"][$treports-5])+toFloat($rawdata["EPSDiluted"][$treports-4])));
		$params[] = ($rawdata["SharesOutstandingDiluted"][$PMRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesWorkInProcess"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesFinishedGoods"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]));
		$params[] = ($rawdata["InventoriesOther"][$PMRQRow] == 'null' ? null: toFloat($rawdata["InventoriesOther"][$PMRQRow]));
		$params[] = ($rawdata["TotalInventories"][$PMRQRow] == 'null' ? null: toFloat($rawdata["TotalInventories"][$PMRQRow]));
		$params[] = ($rawdata["LandAndImprovements"][$PMRQRow] == 'null' ? null: toFloat($rawdata["LandAndImprovements"][$PMRQRow]));
		$params[] = ($rawdata["BuildingsAndImprovements"][$PMRQRow] == 'null' ? null: toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]));
		$params[] = ($rawdata["MachineryFurnitureEquipment"][$PMRQRow] == 'null' ? null: toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]));
		$params[] = ($rawdata["ConstructionInProgress"][$PMRQRow] == 'null' ? null: toFloat($rawdata["ConstructionInProgress"][$PMRQRow]));
		$params[] = ($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow] == 'null' ? null: toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]));
		$params[] = ($rawdata["SharesOutstandingBasic"][$PMRQRow] == 'null' ? null: toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}         

		$query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //40
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["ChangeinCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports]=='null')?null:($rawdata["ChangeinCurrentAssets"][$treports-3]+$rawdata["ChangeinCurrentAssets"][$treports-2]+$rawdata["ChangeinCurrentAssets"][$treports-1]+$rawdata["ChangeinCurrentAssets"][$treports]));
		$params[] = (($rawdata["ChangeinCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports]=='null')?null:($rawdata["ChangeinCurrentLiabilities"][$treports-3]+$rawdata["ChangeinCurrentLiabilities"][$treports-2]+$rawdata["ChangeinCurrentLiabilities"][$treports-1]+$rawdata["ChangeinCurrentLiabilities"][$treports]));
		$params[] = (($rawdata["ChangeinDebtNet"][$treports-3]=='null'&&$rawdata["ChangeinDebtNet"][$treports-2]=='null'&&$rawdata["ChangeinDebtNet"][$treports-1]=='null'&&$rawdata["ChangeinDebtNet"][$treports]=='null')?null:($rawdata["ChangeinDebtNet"][$treports-3]+$rawdata["ChangeinDebtNet"][$treports-2]+$rawdata["ChangeinDebtNet"][$treports-1]+$rawdata["ChangeinDebtNet"][$treports]));
		$params[] = (($rawdata["ChangeinDeferredRevenue"][$treports-3]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-2]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-1]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports]=='null')?null:($rawdata["ChangeinDeferredRevenue"][$treports-3]+$rawdata["ChangeinDeferredRevenue"][$treports-2]+$rawdata["ChangeinDeferredRevenue"][$treports-1]+$rawdata["ChangeinDeferredRevenue"][$treports]));
		$params[] = (($rawdata["ChangeinEquityNet"][$treports-3]=='null'&&$rawdata["ChangeinEquityNet"][$treports-2]=='null'&&$rawdata["ChangeinEquityNet"][$treports-1]=='null'&&$rawdata["ChangeinEquityNet"][$treports]=='null')?null:($rawdata["ChangeinEquityNet"][$treports-3]+$rawdata["ChangeinEquityNet"][$treports-2]+$rawdata["ChangeinEquityNet"][$treports-1]+$rawdata["ChangeinEquityNet"][$treports]));
		$params[] = (($rawdata["ChangeinIncomeTaxesPayable"][$treports-3]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-2]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-1]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports]=='null')?null:($rawdata["ChangeinIncomeTaxesPayable"][$treports-3]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-2]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-1]+$rawdata["ChangeinIncomeTaxesPayable"][$treports]));
		$params[] = (($rawdata["ChangeinInventories"][$treports-3]=='null'&&$rawdata["ChangeinInventories"][$treports-2]=='null'&&$rawdata["ChangeinInventories"][$treports-1]=='null'&&$rawdata["ChangeinInventories"][$treports]=='null')?null:($rawdata["ChangeinInventories"][$treports-3]+$rawdata["ChangeinInventories"][$treports-2]+$rawdata["ChangeinInventories"][$treports-1]+$rawdata["ChangeinInventories"][$treports]));
		$params[] = (($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports]=='null')?null:($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-3]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-2]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-1]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports]));
		$params[] = (($rawdata["ChangeinOtherAssets"][$treports-3]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-2]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-1]=='null'&&$rawdata["ChangeinOtherAssets"][$treports]=='null')?null:($rawdata["ChangeinOtherAssets"][$treports-3]+$rawdata["ChangeinOtherAssets"][$treports-2]+$rawdata["ChangeinOtherAssets"][$treports-1]+$rawdata["ChangeinOtherAssets"][$treports]));
		$params[] = (($rawdata["ChangeinOtherCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports]=='null')?null:($rawdata["ChangeinOtherCurrentAssets"][$treports-3]+$rawdata["ChangeinOtherCurrentAssets"][$treports-2]+$rawdata["ChangeinOtherCurrentAssets"][$treports-1]+$rawdata["ChangeinOtherCurrentAssets"][$treports]));
		$params[] = (($rawdata["ChangeinOtherCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports]=='null')?null:($rawdata["ChangeinOtherCurrentLiabilities"][$treports-3]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-2]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-1]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports]));
		$params[] = (($rawdata["ChangeinOtherLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports]=='null')?null:($rawdata["ChangeinOtherLiabilities"][$treports-3]+$rawdata["ChangeinOtherLiabilities"][$treports-2]+$rawdata["ChangeinOtherLiabilities"][$treports-1]+$rawdata["ChangeinOtherLiabilities"][$treports]));
		$params[] = (($rawdata["ChangeinPrepaidExpenses"][$treports-3]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-2]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-1]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports]=='null')?null:($rawdata["ChangeinPrepaidExpenses"][$treports-3]+$rawdata["ChangeinPrepaidExpenses"][$treports-2]+$rawdata["ChangeinPrepaidExpenses"][$treports-1]+$rawdata["ChangeinPrepaidExpenses"][$treports]));
		$params[] = (($rawdata["DividendsPaid"][$treports-3]=='null'&&$rawdata["DividendsPaid"][$treports-2]=='null'&&$rawdata["DividendsPaid"][$treports-1]=='null'&&$rawdata["DividendsPaid"][$treports]=='null')?null:($rawdata["DividendsPaid"][$treports-3]+$rawdata["DividendsPaid"][$treports-2]+$rawdata["DividendsPaid"][$treports-1]+$rawdata["DividendsPaid"][$treports]));
		$params[] = (($rawdata["EffectofExchangeRateonCash"][$treports-3]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-2]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-1]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports]=='null')?null:($rawdata["EffectofExchangeRateonCash"][$treports-3]+$rawdata["EffectofExchangeRateonCash"][$treports-2]+$rawdata["EffectofExchangeRateonCash"][$treports-1]+$rawdata["EffectofExchangeRateonCash"][$treports]));
		$params[] = (($rawdata["EmployeeCompensation"][$treports-3]=='null'&&$rawdata["EmployeeCompensation"][$treports-2]=='null'&&$rawdata["EmployeeCompensation"][$treports-1]=='null'&&$rawdata["EmployeeCompensation"][$treports]=='null')?null:($rawdata["EmployeeCompensation"][$treports-3]+$rawdata["EmployeeCompensation"][$treports-2]+$rawdata["EmployeeCompensation"][$treports-1]+$rawdata["EmployeeCompensation"][$treports]));
		$params[] = (($rawdata["AcquisitionSaleofBusinessNet"][$treports-3]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-2]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-1]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports]=='null')?null:($rawdata["AcquisitionSaleofBusinessNet"][$treports-3]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-2]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-1]+$rawdata["AcquisitionSaleofBusinessNet"][$treports]));
		$params[] = (($rawdata["AdjustmentforEquityEarnings"][$treports-3]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-2]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-1]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports]=='null')?null:($rawdata["AdjustmentforEquityEarnings"][$treports-3]+$rawdata["AdjustmentforEquityEarnings"][$treports-2]+$rawdata["AdjustmentforEquityEarnings"][$treports-1]+$rawdata["AdjustmentforEquityEarnings"][$treports]));
		$params[] = (($rawdata["AdjustmentforMinorityInterest"][$treports-3]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-2]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-1]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports]=='null')?null:($rawdata["AdjustmentforMinorityInterest"][$treports-3]+$rawdata["AdjustmentforMinorityInterest"][$treports-2]+$rawdata["AdjustmentforMinorityInterest"][$treports-1]+$rawdata["AdjustmentforMinorityInterest"][$treports]));
		$params[] = (($rawdata["AdjustmentforSpecialCharges"][$treports-3]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-2]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-1]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports]=='null')?null:($rawdata["AdjustmentforSpecialCharges"][$treports-3]+$rawdata["AdjustmentforSpecialCharges"][$treports-2]+$rawdata["AdjustmentforSpecialCharges"][$treports-1]+$rawdata["AdjustmentforSpecialCharges"][$treports]));
		$params[] = (($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')?null:($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports]));
		$params[] = (($rawdata["CashfromDiscontinuedOperations"][$treports-3]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-2]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-1]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports]=='null')?null:($rawdata["CashfromDiscontinuedOperations"][$treports-3]+$rawdata["CashfromDiscontinuedOperations"][$treports-2]+$rawdata["CashfromDiscontinuedOperations"][$treports-1]+$rawdata["CashfromDiscontinuedOperations"][$treports]));
		$params[] = (($rawdata["CashfromFinancingActivities"][$treports-3]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-2]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-1]=='null'&&$rawdata["CashfromFinancingActivities"][$treports]=='null')?null:($rawdata["CashfromFinancingActivities"][$treports-3]+$rawdata["CashfromFinancingActivities"][$treports-2]+$rawdata["CashfromFinancingActivities"][$treports-1]+$rawdata["CashfromFinancingActivities"][$treports]));
		$params[] = (($rawdata["CashfromInvestingActivities"][$treports-3]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-2]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-1]=='null'&&$rawdata["CashfromInvestingActivities"][$treports]=='null')?null:($rawdata["CashfromInvestingActivities"][$treports-3]+$rawdata["CashfromInvestingActivities"][$treports-2]+$rawdata["CashfromInvestingActivities"][$treports-1]+$rawdata["CashfromInvestingActivities"][$treports]));
		$params[] = (($rawdata["CashfromOperatingActivities"][$treports-3]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-2]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-1]=='null'&&$rawdata["CashfromOperatingActivities"][$treports]=='null')?null:($rawdata["CashfromOperatingActivities"][$treports-3]+$rawdata["CashfromOperatingActivities"][$treports-2]+$rawdata["CashfromOperatingActivities"][$treports-1]+$rawdata["CashfromOperatingActivities"][$treports]));
		$params[] = (($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')?null:($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports]));
		$params[] = (($rawdata["DeferredIncomeTaxes"][$treports-3]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-2]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-1]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports]=='null')?null:($rawdata["DeferredIncomeTaxes"][$treports-3]+$rawdata["DeferredIncomeTaxes"][$treports-2]+$rawdata["DeferredIncomeTaxes"][$treports-1]+$rawdata["DeferredIncomeTaxes"][$treports]));
		$params[] = (($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-3]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-2]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-1]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports]=='null')?null:($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-3]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-2]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-1]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports]));
		$params[] = (($rawdata["ChangeinAccountsReceivable"][$treports-3]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-2]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-1]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports]=='null')?null:($rawdata["ChangeinAccountsReceivable"][$treports-3]+$rawdata["ChangeinAccountsReceivable"][$treports-2]+$rawdata["ChangeinAccountsReceivable"][$treports-1]+$rawdata["ChangeinAccountsReceivable"][$treports]));
		$params[] = (($rawdata["InvestmentChangesNet"][$treports-3]=='null'&&$rawdata["InvestmentChangesNet"][$treports-2]=='null'&&$rawdata["InvestmentChangesNet"][$treports-1]=='null'&&$rawdata["InvestmentChangesNet"][$treports]=='null')?null:($rawdata["InvestmentChangesNet"][$treports-3]+$rawdata["InvestmentChangesNet"][$treports-2]+$rawdata["InvestmentChangesNet"][$treports-1]+$rawdata["InvestmentChangesNet"][$treports]));
		$params[] = ($rawdata["NetChangeinCash"][$MRQRow]=='null'?null:$rawdata["NetChangeinCash"][$MRQRow]);
		$params[] = (($rawdata["OtherAdjustments"][$treports-3]=='null'&&$rawdata["OtherAdjustments"][$treports-2]=='null'&&$rawdata["OtherAdjustments"][$treports-1]=='null'&&$rawdata["OtherAdjustments"][$treports]=='null')?null:($rawdata["OtherAdjustments"][$treports-3]+$rawdata["OtherAdjustments"][$treports-2]+$rawdata["OtherAdjustments"][$treports-1]+$rawdata["OtherAdjustments"][$treports]));
		$params[] = (($rawdata["OtherAssetLiabilityChangesNet"][$treports-3]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-2]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-1]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports]=='null')?null:($rawdata["OtherAssetLiabilityChangesNet"][$treports-3]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-2]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-1]+$rawdata["OtherAssetLiabilityChangesNet"][$treports]));
		$params[] = (($rawdata["OtherFinancingActivitiesNet"][$treports-3]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-2]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-1]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports]=='null')?null:($rawdata["OtherFinancingActivitiesNet"][$treports-3]+$rawdata["OtherFinancingActivitiesNet"][$treports-2]+$rawdata["OtherFinancingActivitiesNet"][$treports-1]+$rawdata["OtherFinancingActivitiesNet"][$treports]));
		$params[] = (($rawdata["OtherInvestingActivities"][$treports-3]=='null'&&$rawdata["OtherInvestingActivities"][$treports-2]=='null'&&$rawdata["OtherInvestingActivities"][$treports-1]=='null'&&$rawdata["OtherInvestingActivities"][$treports]=='null')?null:($rawdata["OtherInvestingActivities"][$treports-3]+$rawdata["OtherInvestingActivities"][$treports-2]+$rawdata["OtherInvestingActivities"][$treports-1]+$rawdata["OtherInvestingActivities"][$treports]));
		$params[] = (($rawdata["RealizedGainsLosses"][$treports-3]=='null'&&$rawdata["RealizedGainsLosses"][$treports-2]=='null'&&$rawdata["RealizedGainsLosses"][$treports-1]=='null'&&$rawdata["RealizedGainsLosses"][$treports]=='null')?null:($rawdata["RealizedGainsLosses"][$treports-3]+$rawdata["RealizedGainsLosses"][$treports-2]+$rawdata["RealizedGainsLosses"][$treports-1]+$rawdata["RealizedGainsLosses"][$treports]));
		$params[] = (($rawdata["SaleofPropertyPlantEquipment"][$treports-3]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-2]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-1]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports]=='null')?null:($rawdata["SaleofPropertyPlantEquipment"][$treports-3]+$rawdata["SaleofPropertyPlantEquipment"][$treports-2]+$rawdata["SaleofPropertyPlantEquipment"][$treports-1]+$rawdata["SaleofPropertyPlantEquipment"][$treports]));
		$params[] = (($rawdata["StockOptionTaxBenefits"][$treports-3]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-2]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-1]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports]=='null')?null:($rawdata["StockOptionTaxBenefits"][$treports-3]+$rawdata["StockOptionTaxBenefits"][$treports-2]+$rawdata["StockOptionTaxBenefits"][$treports-1]+$rawdata["StockOptionTaxBenefits"][$treports]));
		$params[] = (($rawdata["TotalAdjustments"][$treports-3]=='null'&&$rawdata["TotalAdjustments"][$treports-2]=='null'&&$rawdata["TotalAdjustments"][$treports-1]=='null'&&$rawdata["TotalAdjustments"][$treports]=='null')?null:($rawdata["TotalAdjustments"][$treports-3]+$rawdata["TotalAdjustments"][$treports-2]+$rawdata["TotalAdjustments"][$treports-1]+$rawdata["TotalAdjustments"][$treports]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //40
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["ChangeinCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-4]=='null')?null:($rawdata["ChangeinCurrentAssets"][$treports-7]+$rawdata["ChangeinCurrentAssets"][$treports-6]+$rawdata["ChangeinCurrentAssets"][$treports-5]+$rawdata["ChangeinCurrentAssets"][$treports-4]));
		$params[] = (($rawdata["ChangeinCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinCurrentLiabilities"][$treports-7]+$rawdata["ChangeinCurrentLiabilities"][$treports-6]+$rawdata["ChangeinCurrentLiabilities"][$treports-5]+$rawdata["ChangeinCurrentLiabilities"][$treports-4]));
		$params[] = (($rawdata["ChangeinDebtNet"][$treports-7]=='null'&&$rawdata["ChangeinDebtNet"][$treports-6]=='null'&&$rawdata["ChangeinDebtNet"][$treports-5]=='null'&&$rawdata["ChangeinDebtNet"][$treports-4]=='null')?null:($rawdata["ChangeinDebtNet"][$treports-7]+$rawdata["ChangeinDebtNet"][$treports-6]+$rawdata["ChangeinDebtNet"][$treports-5]+$rawdata["ChangeinDebtNet"][$treports-4]));
		$params[] = (($rawdata["ChangeinDeferredRevenue"][$treports-7]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-6]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-5]=='null'&&$rawdata["ChangeinDeferredRevenue"][$treports-4]=='null')?null:($rawdata["ChangeinDeferredRevenue"][$treports-7]+$rawdata["ChangeinDeferredRevenue"][$treports-6]+$rawdata["ChangeinDeferredRevenue"][$treports-5]+$rawdata["ChangeinDeferredRevenue"][$treports-4]));
		$params[] = (($rawdata["ChangeinEquityNet"][$treports-7]=='null'&&$rawdata["ChangeinEquityNet"][$treports-6]=='null'&&$rawdata["ChangeinEquityNet"][$treports-5]=='null'&&$rawdata["ChangeinEquityNet"][$treports-4]=='null')?null:($rawdata["ChangeinEquityNet"][$treports-7]+$rawdata["ChangeinEquityNet"][$treports-6]+$rawdata["ChangeinEquityNet"][$treports-5]+$rawdata["ChangeinEquityNet"][$treports-4]));
		$params[] = (($rawdata["ChangeinIncomeTaxesPayable"][$treports-7]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-6]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-5]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][$treports-4]=='null')?null:($rawdata["ChangeinIncomeTaxesPayable"][$treports-7]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-6]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-5]+$rawdata["ChangeinIncomeTaxesPayable"][$treports-4]));
		$params[] = (($rawdata["ChangeinInventories"][$treports-7]=='null'&&$rawdata["ChangeinInventories"][$treports-6]=='null'&&$rawdata["ChangeinInventories"][$treports-5]=='null'&&$rawdata["ChangeinInventories"][$treports-4]=='null')?null:($rawdata["ChangeinInventories"][$treports-7]+$rawdata["ChangeinInventories"][$treports-6]+$rawdata["ChangeinInventories"][$treports-5]+$rawdata["ChangeinInventories"][$treports-4]));
		$params[] = (($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinOperatingAssetsLiabilities"][$treports-7]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-6]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-5]+$rawdata["ChangeinOperatingAssetsLiabilities"][$treports-4]));
		$params[] = (($rawdata["ChangeinOtherAssets"][$treports-7]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-6]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-5]=='null'&&$rawdata["ChangeinOtherAssets"][$treports-4]=='null')?null:($rawdata["ChangeinOtherAssets"][$treports-7]+$rawdata["ChangeinOtherAssets"][$treports-6]+$rawdata["ChangeinOtherAssets"][$treports-5]+$rawdata["ChangeinOtherAssets"][$treports-4]));
		$params[] = (($rawdata["ChangeinOtherCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][$treports-4]=='null')?null:($rawdata["ChangeinOtherCurrentAssets"][$treports-7]+$rawdata["ChangeinOtherCurrentAssets"][$treports-6]+$rawdata["ChangeinOtherCurrentAssets"][$treports-5]+$rawdata["ChangeinOtherCurrentAssets"][$treports-4]));
		$params[] = (($rawdata["ChangeinOtherCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinOtherCurrentLiabilities"][$treports-7]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-6]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-5]+$rawdata["ChangeinOtherCurrentLiabilities"][$treports-4]));
		$params[] = (($rawdata["ChangeinOtherLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinOtherLiabilities"][$treports-4]=='null')?null:($rawdata["ChangeinOtherLiabilities"][$treports-7]+$rawdata["ChangeinOtherLiabilities"][$treports-6]+$rawdata["ChangeinOtherLiabilities"][$treports-5]+$rawdata["ChangeinOtherLiabilities"][$treports-4]));
		$params[] = (($rawdata["ChangeinPrepaidExpenses"][$treports-7]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-6]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-5]=='null'&&$rawdata["ChangeinPrepaidExpenses"][$treports-4]=='null')?null:($rawdata["ChangeinPrepaidExpenses"][$treports-7]+$rawdata["ChangeinPrepaidExpenses"][$treports-6]+$rawdata["ChangeinPrepaidExpenses"][$treports-5]+$rawdata["ChangeinPrepaidExpenses"][$treports-4]));
		$params[] = (($rawdata["DividendsPaid"][$treports-7]=='null'&&$rawdata["DividendsPaid"][$treports-6]=='null'&&$rawdata["DividendsPaid"][$treports-5]=='null'&&$rawdata["DividendsPaid"][$treports-4]=='null')?null:($rawdata["DividendsPaid"][$treports-7]+$rawdata["DividendsPaid"][$treports-6]+$rawdata["DividendsPaid"][$treports-5]+$rawdata["DividendsPaid"][$treports-4]));
		$params[] = (($rawdata["EffectofExchangeRateonCash"][$treports-7]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-6]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-5]=='null'&&$rawdata["EffectofExchangeRateonCash"][$treports-4]=='null')?null:($rawdata["EffectofExchangeRateonCash"][$treports-7]+$rawdata["EffectofExchangeRateonCash"][$treports-6]+$rawdata["EffectofExchangeRateonCash"][$treports-5]+$rawdata["EffectofExchangeRateonCash"][$treports-4]));
		$params[] = (($rawdata["EmployeeCompensation"][$treports-7]=='null'&&$rawdata["EmployeeCompensation"][$treports-6]=='null'&&$rawdata["EmployeeCompensation"][$treports-5]=='null'&&$rawdata["EmployeeCompensation"][$treports-4]=='null')?null:($rawdata["EmployeeCompensation"][$treports-7]+$rawdata["EmployeeCompensation"][$treports-6]+$rawdata["EmployeeCompensation"][$treports-5]+$rawdata["EmployeeCompensation"][$treports-4]));
		$params[] = (($rawdata["AcquisitionSaleofBusinessNet"][$treports-7]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-6]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-5]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][$treports-4]='null')?null:($rawdata["AcquisitionSaleofBusinessNet"][$treports-7]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-6]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-5]+$rawdata["AcquisitionSaleofBusinessNet"][$treports-4]));
		$params[] = (($rawdata["AdjustmentforEquityEarnings"][$treports-7]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-6]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-5]=='null'&&$rawdata["AdjustmentforEquityEarnings"][$treports-4]=='null')?null:($rawdata["AdjustmentforEquityEarnings"][$treports-7]+$rawdata["AdjustmentforEquityEarnings"][$treports-6]+$rawdata["AdjustmentforEquityEarnings"][$treports-5]+$rawdata["AdjustmentforEquityEarnings"][$treports-4]));
		$params[] = (($rawdata["AdjustmentforMinorityInterest"][$treports-7]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-6]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-5]=='null'&&$rawdata["AdjustmentforMinorityInterest"][$treports-4]=='null')?null:($rawdata["AdjustmentforMinorityInterest"][$treports-7]+$rawdata["AdjustmentforMinorityInterest"][$treports-6]+$rawdata["AdjustmentforMinorityInterest"][$treports-5]+$rawdata["AdjustmentforMinorityInterest"][$treports-4]));
		$params[] = (($rawdata["AdjustmentforSpecialCharges"][$treports-7]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-6]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-5]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-4]=='null')?null:($rawdata["AdjustmentforSpecialCharges"][$treports-7]+$rawdata["AdjustmentforSpecialCharges"][$treports-6]+$rawdata["AdjustmentforSpecialCharges"][$treports-5]+$rawdata["AdjustmentforSpecialCharges"][$treports-4]));
		$params[] = (($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')?null:($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4]));
		$params[] = (($rawdata["CashfromDiscontinuedOperations"][$treports-7]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-6]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-5]=='null'&&$rawdata["CashfromDiscontinuedOperations"][$treports-4]=='null')?null:($rawdata["CashfromDiscontinuedOperations"][$treports-7]+$rawdata["CashfromDiscontinuedOperations"][$treports-6]+$rawdata["CashfromDiscontinuedOperations"][$treports-5]+$rawdata["CashfromDiscontinuedOperations"][$treports-4]));
		$params[] = (($rawdata["CashfromFinancingActivities"][$treports-7]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-6]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-5]=='null'&&$rawdata["CashfromFinancingActivities"][$treports-4]=='null')?null:($rawdata["CashfromFinancingActivities"][$treports-7]+$rawdata["CashfromFinancingActivities"][$treports-6]+$rawdata["CashfromFinancingActivities"][$treports-5]+$rawdata["CashfromFinancingActivities"][$treports-4]));
		$params[] = (($rawdata["CashfromInvestingActivities"][$treports-7]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-6]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-5]=='null'&&$rawdata["CashfromInvestingActivities"][$treports-4]=='null')?null:($rawdata["CashfromInvestingActivities"][$treports-7]+$rawdata["CashfromInvestingActivities"][$treports-6]+$rawdata["CashfromInvestingActivities"][$treports-5]+$rawdata["CashfromInvestingActivities"][$treports-4]));
		$params[] = (($rawdata["CashfromOperatingActivities"][$treports-7]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-6]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-5]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-4]=='null')?null:($rawdata["CashfromOperatingActivities"][$treports-7]+$rawdata["CashfromOperatingActivities"][$treports-6]+$rawdata["CashfromOperatingActivities"][$treports-5]+$rawdata["CashfromOperatingActivities"][$treports-4]));
		$params[] = (($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')?null:($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4]));
		$params[] = (($rawdata["DeferredIncomeTaxes"][$treports-7]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-6]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-5]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-4]=='null')?null:($rawdata["DeferredIncomeTaxes"][$treports-7]+$rawdata["DeferredIncomeTaxes"][$treports-6]+$rawdata["DeferredIncomeTaxes"][$treports-5]+$rawdata["DeferredIncomeTaxes"][$treports-4]));
		$params[] = (($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-7]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-6]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-5]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-4]=='null')?null:($rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-7]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-6]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-5]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][$treports-4]));
		$params[] = (($rawdata["ChangeinAccountsReceivable"][$treports-7]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-6]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-5]=='null'&&$rawdata["ChangeinAccountsReceivable"][$treports-4]=='null')?null:($rawdata["ChangeinAccountsReceivable"][$treports-7]+$rawdata["ChangeinAccountsReceivable"][$treports-6]+$rawdata["ChangeinAccountsReceivable"][$treports-5]+$rawdata["ChangeinAccountsReceivable"][$treports-4]));
		$params[] = (($rawdata["InvestmentChangesNet"][$treports-7]=='null'&&$rawdata["InvestmentChangesNet"][$treports-6]=='null'&&$rawdata["InvestmentChangesNet"][$treports-5]=='null'&&$rawdata["InvestmentChangesNet"][$treports-4]=='null')?null:($rawdata["InvestmentChangesNet"][$treports-7]+$rawdata["InvestmentChangesNet"][$treports-6]+$rawdata["InvestmentChangesNet"][$treports-5]+$rawdata["InvestmentChangesNet"][$treports-4]));
		$params[] = ($rawdata["NetChangeinCash"][$PMRQRow]=='null'?null:$rawdata["NetChangeinCash"][$PMRQRow]);
		$params[] = (($rawdata["OtherAdjustments"][$treports-7]=='null'&&$rawdata["OtherAdjustments"][$treports-6]=='null'&&$rawdata["OtherAdjustments"][$treports-5]=='null'&&$rawdata["OtherAdjustments"][$treports-4]=='null')?null:($rawdata["OtherAdjustments"][$treports-7]+$rawdata["OtherAdjustments"][$treports-6]+$rawdata["OtherAdjustments"][$treports-5]+$rawdata["OtherAdjustments"][$treports-4]));
		$params[] = (($rawdata["OtherAssetLiabilityChangesNet"][$treports-7]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-6]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-5]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][$treports-4]=='null')?null:($rawdata["OtherAssetLiabilityChangesNet"][$treports-7]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-6]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-5]+$rawdata["OtherAssetLiabilityChangesNet"][$treports-4]));
		$params[] = (($rawdata["OtherFinancingActivitiesNet"][$treports-7]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-6]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-5]=='null'&&$rawdata["OtherFinancingActivitiesNet"][$treports-4]=='null')?null:($rawdata["OtherFinancingActivitiesNet"][$treports-7]+$rawdata["OtherFinancingActivitiesNet"][$treports-6]+$rawdata["OtherFinancingActivitiesNet"][$treports-5]+$rawdata["OtherFinancingActivitiesNet"][$treports-4]));
		$params[] = (($rawdata["OtherInvestingActivities"][$treports-7]=='null'&&$rawdata["OtherInvestingActivities"][$treports-6]=='null'&&$rawdata["OtherInvestingActivities"][$treports-5]=='null'&&$rawdata["OtherInvestingActivities"][$treports-4]=='null')?null:($rawdata["OtherInvestingActivities"][$treports-7]+$rawdata["OtherInvestingActivities"][$treports-6]+$rawdata["OtherInvestingActivities"][$treports-5]+$rawdata["OtherInvestingActivities"][$treports-4]));
		$params[] = (($rawdata["RealizedGainsLosses"][$treports-7]=='null'&&$rawdata["RealizedGainsLosses"][$treports-6]=='null'&&$rawdata["RealizedGainsLosses"][$treports-5]=='null'&&$rawdata["RealizedGainsLosses"][$treports-4]=='null')?null:($rawdata["RealizedGainsLosses"][$treports-7]+$rawdata["RealizedGainsLosses"][$treports-6]+$rawdata["RealizedGainsLosses"][$treports-5]+$rawdata["RealizedGainsLosses"][$treports-4]));
		$params[] = (($rawdata["SaleofPropertyPlantEquipment"][$treports-7]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-6]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-5]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][$treports-4]=='null')?null:($rawdata["SaleofPropertyPlantEquipment"][$treports-7]+$rawdata["SaleofPropertyPlantEquipment"][$treports-6]+$rawdata["SaleofPropertyPlantEquipment"][$treports-5]+$rawdata["SaleofPropertyPlantEquipment"][$treports-4]));
		$params[] = (($rawdata["StockOptionTaxBenefits"][$treports-7]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-6]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-5]=='null'&&$rawdata["StockOptionTaxBenefits"][$treports-4]=='null')?null:($rawdata["StockOptionTaxBenefits"][$treports-7]+$rawdata["StockOptionTaxBenefits"][$treports-6]+$rawdata["StockOptionTaxBenefits"][$treports-5]+$rawdata["StockOptionTaxBenefits"][$treports-4]));
		$params[] = (($rawdata["TotalAdjustments"][$treports-7]=='null'&&$rawdata["TotalAdjustments"][$treports-6]=='null'&&$rawdata["TotalAdjustments"][$treports-5]=='null'&&$rawdata["TotalAdjustments"][$treports-4]=='null')?null:($rawdata["TotalAdjustments"][$treports-7]+$rawdata["TotalAdjustments"][$treports-6]+$rawdata["TotalAdjustments"][$treports-5]+$rawdata["TotalAdjustments"][$treports-4]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//19
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["ChangeinLongtermDebtNet"][$treports-3]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-2]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-1]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports]=='null')?null:($rawdata["ChangeinLongtermDebtNet"][$treports-3]+$rawdata["ChangeinLongtermDebtNet"][$treports-2]+$rawdata["ChangeinLongtermDebtNet"][$treports-1]+$rawdata["ChangeinLongtermDebtNet"][$treports]));
		$params[] = (($rawdata["ChangeinShorttermBorrowingsNet"][$treports-3]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-2]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-1]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports]=='null')?null:($rawdata["ChangeinShorttermBorrowingsNet"][$treports-3]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-2]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-1]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports]));
		$params[] = (($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-3]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-2]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-1]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports]=='null')?null:($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-3]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-2]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-1]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports]));
		$params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$MRQRow] == 'null' ? null: $rawdata["CashandCashEquivalentsEndofYear"][$MRQRow]);
		$params[] = (($rawdata["CashPaidforIncomeTaxes"][$treports-3]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-2]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-1]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports]=='null')?null:($rawdata["CashPaidforIncomeTaxes"][$treports-3]+$rawdata["CashPaidforIncomeTaxes"][$treports-2]+$rawdata["CashPaidforIncomeTaxes"][$treports-1]+$rawdata["CashPaidforIncomeTaxes"][$treports]));
		$params[] = (($rawdata["CashPaidforInterestExpense"][$treports-3]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-2]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-1]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports]=='null')?null:($rawdata["CashPaidforInterestExpense"][$treports-3]+$rawdata["CashPaidforInterestExpense"][$treports-2]+$rawdata["CashPaidforInterestExpense"][$treports-1]+$rawdata["CashPaidforInterestExpense"][$treports]));
		$params[] = (($rawdata["CFNetIncome"][$treports-3]=='null'&&$rawdata["CFNetIncome"][$treports-2]=='null'&&$rawdata["CFNetIncome"][$treports-1]=='null'&&$rawdata["CFNetIncome"][$treports]=='null')?null:($rawdata["CFNetIncome"][$treports-3]+$rawdata["CFNetIncome"][$treports-2]+$rawdata["CFNetIncome"][$treports-1]+$rawdata["CFNetIncome"][$treports]));
		$params[] = (($rawdata["IssuanceofEquity"][$treports-3]=='null'&&$rawdata["IssuanceofEquity"][$treports-2]=='null'&&$rawdata["IssuanceofEquity"][$treports-1]=='null'&&$rawdata["IssuanceofEquity"][$treports]=='null')?null:($rawdata["IssuanceofEquity"][$treports-3]+$rawdata["IssuanceofEquity"][$treports-2]+$rawdata["IssuanceofEquity"][$treports-1]+$rawdata["IssuanceofEquity"][$treports]));
		$params[] = (($rawdata["LongtermDebtPayments"][$treports-3]=='null'&&$rawdata["LongtermDebtPayments"][$treports-2]=='null'&&$rawdata["LongtermDebtPayments"][$treports-1]=='null'&&$rawdata["LongtermDebtPayments"][$treports]=='null')?null:($rawdata["LongtermDebtPayments"][$treports-3]+$rawdata["LongtermDebtPayments"][$treports-2]+$rawdata["LongtermDebtPayments"][$treports-1]+$rawdata["LongtermDebtPayments"][$treports]));
		$params[] = (($rawdata["LongtermDebtProceeds"][$treports-3]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-2]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-1]=='null'&&$rawdata["LongtermDebtProceeds"][$treports]=='null')?null:($rawdata["LongtermDebtProceeds"][$treports-3]+$rawdata["LongtermDebtProceeds"][$treports-2]+$rawdata["LongtermDebtProceeds"][$treports-1]+$rawdata["LongtermDebtProceeds"][$treports]));
		$params[] = (($rawdata["OtherDebtNet"][$treports-3]=='null'&&$rawdata["OtherDebtNet"][$treports-2]=='null'&&$rawdata["OtherDebtNet"][$treports-1]=='null'&&$rawdata["OtherDebtNet"][$treports]=='null')?null:($rawdata["OtherDebtNet"][$treports-3]+$rawdata["OtherDebtNet"][$treports-2]+$rawdata["OtherDebtNet"][$treports-1]+$rawdata["OtherDebtNet"][$treports]));
		$params[] = (($rawdata["OtherEquityTransactionsNet"][$treports-3]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-2]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-1]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports]=='null')?null:($rawdata["OtherEquityTransactionsNet"][$treports-3]+$rawdata["OtherEquityTransactionsNet"][$treports-2]+$rawdata["OtherEquityTransactionsNet"][$treports-1]+$rawdata["OtherEquityTransactionsNet"][$treports]));
		$params[] = (($rawdata["OtherInvestmentChangesNet"][$treports-3]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-2]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-1]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports]=='null')?null:($rawdata["OtherInvestmentChangesNet"][$treports-3]+$rawdata["OtherInvestmentChangesNet"][$treports-2]+$rawdata["OtherInvestmentChangesNet"][$treports-1]+$rawdata["OtherInvestmentChangesNet"][$treports]));
		$params[] = (($rawdata["PurchaseofInvestments"][$treports-3]=='null'&&$rawdata["PurchaseofInvestments"][$treports-2]=='null'&&$rawdata["PurchaseofInvestments"][$treports-1]=='null'&&$rawdata["PurchaseofInvestments"][$treports]=='null')?null:($rawdata["PurchaseofInvestments"][$treports-3]+$rawdata["PurchaseofInvestments"][$treports-2]+$rawdata["PurchaseofInvestments"][$treports-1]+$rawdata["PurchaseofInvestments"][$treports]));
		$params[] = (($rawdata["RepurchaseofEquity"][$treports-3]=='null'&&$rawdata["RepurchaseofEquity"][$treports-2]=='null'&&$rawdata["RepurchaseofEquity"][$treports-1]=='null'&&$rawdata["RepurchaseofEquity"][$treports]=='null')?null:($rawdata["RepurchaseofEquity"][$treports-3]+$rawdata["RepurchaseofEquity"][$treports-2]+$rawdata["RepurchaseofEquity"][$treports-1]+$rawdata["RepurchaseofEquity"][$treports]));
		$params[] = (($rawdata["SaleofInvestments"][$treports-3]=='null'&&$rawdata["SaleofInvestments"][$treports-2]=='null'&&$rawdata["SaleofInvestments"][$treports-1]=='null'&&$rawdata["SaleofInvestments"][$treports]=='null')?null:($rawdata["SaleofInvestments"][$treports-3]+$rawdata["SaleofInvestments"][$treports-2]+$rawdata["SaleofInvestments"][$treports-1]+$rawdata["SaleofInvestments"][$treports]));
		$params[] = (($rawdata["ShorttermBorrowings"][$treports-3]=='null'&&$rawdata["ShorttermBorrowings"][$treports-2]=='null'&&$rawdata["ShorttermBorrowings"][$treports-1]=='null'&&$rawdata["ShorttermBorrowings"][$treports]=='null')?null:($rawdata["ShorttermBorrowings"][$treports-3]+$rawdata["ShorttermBorrowings"][$treports-2]+$rawdata["ShorttermBorrowings"][$treports-1]+$rawdata["ShorttermBorrowings"][$treports]));
		$params[] = (($rawdata["TotalNoncashAdjustments"][$treports-3]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-2]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-1]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports]=='null')?null:($rawdata["TotalNoncashAdjustments"][$treports-3]+$rawdata["TotalNoncashAdjustments"][$treports-2]+$rawdata["TotalNoncashAdjustments"][$treports-1]+$rawdata["TotalNoncashAdjustments"][$treports]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//19
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["ChangeinLongtermDebtNet"][$treports-7]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-6]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-5]=='null'&&$rawdata["ChangeinLongtermDebtNet"][$treports-4]=='null')?null:($rawdata["ChangeinLongtermDebtNet"][$treports-7]+$rawdata["ChangeinLongtermDebtNet"][$treports-6]+$rawdata["ChangeinLongtermDebtNet"][$treports-5]+$rawdata["ChangeinLongtermDebtNet"][$treports-4]));
		$params[] = (($rawdata["ChangeinShorttermBorrowingsNet"][$treports-7]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-6]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-5]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][$treports-4]=='null')?null:($rawdata["ChangeinShorttermBorrowingsNet"][$treports-7]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-6]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-5]+$rawdata["ChangeinShorttermBorrowingsNet"][$treports-4]));
		$params[] = (($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-7]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-6]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-5]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-4]=='null')?null:($rawdata["CashandCashEquivalentsBeginningofYear"][$treports-7]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-6]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-5]+$rawdata["CashandCashEquivalentsBeginningofYear"][$treports-4]));
		$params[] = ($rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow] == 'null' ? null: $rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow]);
		$params[] = (($rawdata["CashPaidforIncomeTaxes"][$treports-7]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-6]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-5]=='null'&&$rawdata["CashPaidforIncomeTaxes"][$treports-4]=='null')?null:($rawdata["CashPaidforIncomeTaxes"][$treports-7]+$rawdata["CashPaidforIncomeTaxes"][$treports-6]+$rawdata["CashPaidforIncomeTaxes"][$treports-5]+$rawdata["CashPaidforIncomeTaxes"][$treports-4]));
		$params[] = (($rawdata["CashPaidforInterestExpense"][$treports-7]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-6]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-5]=='null'&&$rawdata["CashPaidforInterestExpense"][$treports-4]=='null')?null:($rawdata["CashPaidforInterestExpense"][$treports-7]+$rawdata["CashPaidforInterestExpense"][$treports-6]+$rawdata["CashPaidforInterestExpense"][$treports-5]+$rawdata["CashPaidforInterestExpense"][$treports-4]));
		$params[] = (($rawdata["CFNetIncome"][$treports-7]=='null'&&$rawdata["CFNetIncome"][$treports-6]=='null'&&$rawdata["CFNetIncome"][$treports-5]=='null'&&$rawdata["CFNetIncome"][$treports-4]=='null')?null:($rawdata["CFNetIncome"][$treports-7]+$rawdata["CFNetIncome"][$treports-6]+$rawdata["CFNetIncome"][$treports-5]+$rawdata["CFNetIncome"][$treports-4]));
		$params[] = (($rawdata["IssuanceofEquity"][$treports-7]=='null'&&$rawdata["IssuanceofEquity"][$treports-6]=='null'&&$rawdata["IssuanceofEquity"][$treports-5]=='null'&&$rawdata["IssuanceofEquity"][$treports-4]=='null')?null:($rawdata["IssuanceofEquity"][$treports-7]+$rawdata["IssuanceofEquity"][$treports-6]+$rawdata["IssuanceofEquity"][$treports-5]+$rawdata["IssuanceofEquity"][$treports-4]));
		$params[] = (($rawdata["LongtermDebtPayments"][$treports-7]=='null'&&$rawdata["LongtermDebtPayments"][$treports-6]=='null'&&$rawdata["LongtermDebtPayments"][$treports-5]=='null'&&$rawdata["LongtermDebtPayments"][$treports-4]=='null')?null:($rawdata["LongtermDebtPayments"][$treports-7]+$rawdata["LongtermDebtPayments"][$treports-6]+$rawdata["LongtermDebtPayments"][$treports-5]+$rawdata["LongtermDebtPayments"][$treports-4]));
		$params[] = (($rawdata["LongtermDebtProceeds"][$treports-7]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-6]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-5]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-4]=='null')?null:($rawdata["LongtermDebtProceeds"][$treports-7]+$rawdata["LongtermDebtProceeds"][$treports-6]+$rawdata["LongtermDebtProceeds"][$treports-5]+$rawdata["LongtermDebtProceeds"][$treports-4]));
		$params[] = (($rawdata["OtherDebtNet"][$treports-7]=='null'&&$rawdata["OtherDebtNet"][$treports-6]=='null'&&$rawdata["OtherDebtNet"][$treports-5]=='null'&&$rawdata["OtherDebtNet"][$treports-4]=='null')?null:($rawdata["OtherDebtNet"][$treports-7]+$rawdata["OtherDebtNet"][$treports-6]+$rawdata["OtherDebtNet"][$treports-5]+$rawdata["OtherDebtNet"][$treports-4]));
		$params[] = (($rawdata["OtherEquityTransactionsNet"][$treports-7]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-6]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-5]=='null'&&$rawdata["OtherEquityTransactionsNet"][$treports-4]=='null')?null:($rawdata["OtherEquityTransactionsNet"][$treports-7]+$rawdata["OtherEquityTransactionsNet"][$treports-6]+$rawdata["OtherEquityTransactionsNet"][$treports-5]+$rawdata["OtherEquityTransactionsNet"][$treports-4]));
		$params[] = (($rawdata["OtherInvestmentChangesNet"][$treports-7]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-6]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-5]=='null'&&$rawdata["OtherInvestmentChangesNet"][$treports-4]=='null')?null:($rawdata["OtherInvestmentChangesNet"][$treports-7]+$rawdata["OtherInvestmentChangesNet"][$treports-6]+$rawdata["OtherInvestmentChangesNet"][$treports-5]+$rawdata["OtherInvestmentChangesNet"][$treports-4]));
		$params[] = (($rawdata["PurchaseofInvestments"][$treports-7]=='null'&&$rawdata["PurchaseofInvestments"][$treports-6]=='null'&&$rawdata["PurchaseofInvestments"][$treports-5]=='null'&&$rawdata["PurchaseofInvestments"][$treports-4]=='null')?null:($rawdata["PurchaseofInvestments"][$treports-7]+$rawdata["PurchaseofInvestments"][$treports-6]+$rawdata["PurchaseofInvestments"][$treports-5]+$rawdata["PurchaseofInvestments"][$treports-4]));
		$params[] = (($rawdata["RepurchaseofEquity"][$treports-7]=='null'&&$rawdata["RepurchaseofEquity"][$treports-6]=='null'&&$rawdata["RepurchaseofEquity"][$treports-5]=='null'&&$rawdata["RepurchaseofEquity"][$treports-4]=='null')?null:($rawdata["RepurchaseofEquity"][$treports-7]+$rawdata["RepurchaseofEquity"][$treports-6]+$rawdata["RepurchaseofEquity"][$treports-5]+$rawdata["RepurchaseofEquity"][$treports-4]));
		$params[] = (($rawdata["SaleofInvestments"][$treports-7]=='null'&&$rawdata["SaleofInvestments"][$treports-6]=='null'&&$rawdata["SaleofInvestments"][$treports-5]=='null'&&$rawdata["SaleofInvestments"][$treports-4]=='null')?null:($rawdata["SaleofInvestments"][$treports-7]+$rawdata["SaleofInvestments"][$treports-6]+$rawdata["SaleofInvestments"][$treports-5]+$rawdata["SaleofInvestments"][$treports-4]));
		$params[] = (($rawdata["ShorttermBorrowings"][$treports-7]=='null'&&$rawdata["ShorttermBorrowings"][$treports-6]=='null'&&$rawdata["ShorttermBorrowings"][$treports-5]=='null'&&$rawdata["ShorttermBorrowings"][$treports-4]=='null')?null:($rawdata["ShorttermBorrowings"][$treports-7]+$rawdata["ShorttermBorrowings"][$treports-6]+$rawdata["ShorttermBorrowings"][$treports-5]+$rawdata["ShorttermBorrowings"][$treports-4]));
		$params[] = (($rawdata["TotalNoncashAdjustments"][$treports-7]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-6]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-5]=='null'&&$rawdata["TotalNoncashAdjustments"][$treports-4]=='null')?null:($rawdata["TotalNoncashAdjustments"][$treports-7]+$rawdata["TotalNoncashAdjustments"][$treports-6]+$rawdata["TotalNoncashAdjustments"][$treports-5]+$rawdata["TotalNoncashAdjustments"][$treports-4]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //26
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["EBIT"][$treports-3]=='null'&&$rawdata["EBIT"][$treports-2]=='null'&&$rawdata["EBIT"][$treports-1]=='null'&&$rawdata["EBIT"][$treports]=='null')?null:($rawdata["EBIT"][$treports-3]+$rawdata["EBIT"][$treports-2]+$rawdata["EBIT"][$treports-1]+$rawdata["EBIT"][$treports]));
		$params[] = (($rawdata["CostofRevenue"][$treports-3]=='null'&&$rawdata["CostofRevenue"][$treports-2]=='null'&&$rawdata["CostofRevenue"][$treports-1]=='null'&&$rawdata["CostofRevenue"][$treports]=='null')?null:($rawdata["CostofRevenue"][$treports-3]+$rawdata["CostofRevenue"][$treports-2]+$rawdata["CostofRevenue"][$treports-1]+$rawdata["CostofRevenue"][$treports]));
		$params[] = (($rawdata["DepreciationAmortizationExpense"][$treports-3]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-2]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-1]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports]=='null')?null:($rawdata["DepreciationAmortizationExpense"][$treports-3]+$rawdata["DepreciationAmortizationExpense"][$treports-2]+$rawdata["DepreciationAmortizationExpense"][$treports-1]+$rawdata["DepreciationAmortizationExpense"][$treports]));
		$params[] = (($rawdata["DilutedEPSNetIncome"][$treports-3]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-2]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-1]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports]=='null')?null:($rawdata["DilutedEPSNetIncome"][$treports-3]+$rawdata["DilutedEPSNetIncome"][$treports-2]+$rawdata["DilutedEPSNetIncome"][$treports-1]+$rawdata["DilutedEPSNetIncome"][$treports]));
		$params[] = (($rawdata["DiscontinuedOperations"][$treports-3]=='null'&&$rawdata["DiscontinuedOperations"][$treports-2]=='null'&&$rawdata["DiscontinuedOperations"][$treports-1]=='null'&&$rawdata["DiscontinuedOperations"][$treports]=='null')?null:($rawdata["DiscontinuedOperations"][$treports-3]+$rawdata["DiscontinuedOperations"][$treports-2]+$rawdata["DiscontinuedOperations"][$treports-1]+$rawdata["DiscontinuedOperations"][$treports-3]));
		$params[] = (($rawdata["EquityEarnings"][$treports-3]=='null'&&$rawdata["EquityEarnings"][$treports-2]=='null'&&$rawdata["EquityEarnings"][$treports-1]=='null'&&$rawdata["EquityEarnings"][$treports]=='null')?null:($rawdata["EquityEarnings"][$treports-3]+$rawdata["EquityEarnings"][$treports-2]+$rawdata["EquityEarnings"][$treports-1]+$rawdata["EquityEarnings"][$treports]));
		$params[] = (($rawdata["AccountingChange"][$treports-3]=='null'&&$rawdata["AccountingChange"][$treports-2]=='null'&&$rawdata["AccountingChange"][$treports-1]=='null'&&$rawdata["AccountingChange"][$treports]=='null')?null:($rawdata["AccountingChange"][$treports-3]+$rawdata["AccountingChange"][$treports-2]+$rawdata["AccountingChange"][$treports-1]+$rawdata["AccountingChange"][$treports]));
		$params[] = (($rawdata["BasicEPSNetIncome"][$treports-3]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-2]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-1]=='null'&&$rawdata["BasicEPSNetIncome"][$treports]=='null')?null:($rawdata["BasicEPSNetIncome"][$treports-3]+$rawdata["BasicEPSNetIncome"][$treports-2]+$rawdata["BasicEPSNetIncome"][$treports-1]+$rawdata["BasicEPSNetIncome"][$treports]));
		$params[] = (($rawdata["ExtraordinaryItems"][$treports-3]=='null'&&$rawdata["ExtraordinaryItems"][$treports-2]=='null'&&$rawdata["ExtraordinaryItems"][$treports-1]=='null'&&$rawdata["ExtraordinaryItems"][$treports]=='null')?null:($rawdata["ExtraordinaryItems"][$treports-3]+$rawdata["ExtraordinaryItems"][$treports-2]+$rawdata["ExtraordinaryItems"][$treports-1]+$rawdata["ExtraordinaryItems"][$treports]));
		$params[] = (($rawdata["GrossProfit"][$treports-3]=='null'&&$rawdata["GrossProfit"][$treports-2]=='null'&&$rawdata["GrossProfit"][$treports-1]=='null'&&$rawdata["GrossProfit"][$treports]=='null')?null:($rawdata["GrossProfit"][$treports-3]+$rawdata["GrossProfit"][$treports-2]+$rawdata["GrossProfit"][$treports-1]+$rawdata["GrossProfit"][$treports]));
		$params[] = (($rawdata["IncomebeforeExtraordinaryItems"][$treports-3]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-2]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-1]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports]=='null')?null:($rawdata["IncomebeforeExtraordinaryItems"][$treports-3]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-2]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-1]+$rawdata["IncomebeforeExtraordinaryItems"][$treports]));
		$params[] = (($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null')?null:($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports]));
		$params[] = (($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')?null:($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports]));
		$params[] = (($rawdata["InterestExpense"][$treports-3]=='null'&&$rawdata["InterestExpense"][$treports-2]=='null'&&$rawdata["InterestExpense"][$treports-1]=='null'&&$rawdata["InterestExpense"][$treports]=='null')?null:(toFloat($rawdata["InterestExpense"][$treports-3])+toFloat($rawdata["InterestExpense"][$treports-2])+toFloat($rawdata["InterestExpense"][$treports-1])+toFloat($rawdata["InterestExpense"][$treports])));
		$params[] = (($rawdata["InterestIncome"][$treports-3]=='null'&&$rawdata["InterestIncome"][$treports-2]=='null'&&$rawdata["InterestIncome"][$treports-1]=='null'&&$rawdata["InterestIncome"][$treports]=='null')?null:(toFloat($rawdata["InterestIncome"][$treports-3])+toFloat($rawdata["InterestIncome"][$treports-2])+toFloat($rawdata["InterestIncome"][$treports-1])+toFloat($rawdata["InterestIncome"][$treports])));
		$params[] = (($rawdata["MinorityInterestEquityEarnings"][$treports-3]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-2]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-1]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports]=='null')?null:($rawdata["MinorityInterestEquityEarnings"][$treports-3]+$rawdata["MinorityInterestEquityEarnings"][$treports-2]+$rawdata["MinorityInterestEquityEarnings"][$treports-1]+$rawdata["MinorityInterestEquityEarnings"][$treports]));
		$params[] = (($rawdata["NetIncome"][$treports-3]=='null'&&$rawdata["NetIncome"][$treports-2]=='null'&&$rawdata["NetIncome"][$treports-1]=='null'&&$rawdata["NetIncome"][$treports]=='null')?null:($rawdata["NetIncome"][$treports-3]+$rawdata["NetIncome"][$treports-2]+$rawdata["NetIncome"][$treports-1]+$rawdata["NetIncome"][$treports]));
		$params[] = (($rawdata["NetIncomeApplicabletoCommon"][$treports-3]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-2]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-1]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports]=='null')?null:($rawdata["NetIncomeApplicabletoCommon"][$treports-3]+$rawdata["NetIncomeApplicabletoCommon"][$treports-2]+$rawdata["NetIncomeApplicabletoCommon"][$treports-1]+$rawdata["NetIncomeApplicabletoCommon"][$treports]));
		$params[] = (($rawdata["OperatingProfit"][$treports-3]=='null'&&$rawdata["OperatingProfit"][$treports-2]=='null'&&$rawdata["OperatingProfit"][$treports-1]=='null'&&$rawdata["OperatingProfit"][$treports]=='null')?null:($rawdata["OperatingProfit"][$treports-3]+$rawdata["OperatingProfit"][$treports-2]+$rawdata["OperatingProfit"][$treports-1]+$rawdata["OperatingProfit"][$treports]));
		$params[] = (($rawdata["OtherNonoperatingIncomeExpense"][$treports-3]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-2]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-1]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports]=='null')?null:($rawdata["OtherNonoperatingIncomeExpense"][$treports-3]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-2]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-1]+$rawdata["OtherNonoperatingIncomeExpense"][$treports]));
		$params[] = (($rawdata["OtherOperatingExpenses"][$treports-3]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-2]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-1]=='null'&&$rawdata["OtherOperatingExpenses"][$treports]=='null')?null:($rawdata["OtherOperatingExpenses"][$treports-3]+$rawdata["OtherOperatingExpenses"][$treports-2]+$rawdata["OtherOperatingExpenses"][$treports-1]+$rawdata["OtherOperatingExpenses"][$treports]));
		$params[] = (($rawdata["ResearchDevelopmentExpense"][$treports-3]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-2]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-1]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports]=='null')?null:($rawdata["ResearchDevelopmentExpense"][$treports-3]+$rawdata["ResearchDevelopmentExpense"][$treports-2]+$rawdata["ResearchDevelopmentExpense"][$treports-1]+$rawdata["ResearchDevelopmentExpense"][$treports]));
		$params[] = (($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-3]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-2]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-1]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports]=='null')?null:($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-3]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-2]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-1]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports]));
		$params[] = (($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')?null:($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]));
		$params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports]=='null')?null:($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //26
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["EBIT"][$treports-7]=='null'&&$rawdata["EBIT"][$treports-6]=='null'&&$rawdata["EBIT"][$treports-5]=='null'&&$rawdata["EBIT"][$treports-4]=='null')?null:($rawdata["EBIT"][$treports-7]+$rawdata["EBIT"][$treports-6]+$rawdata["EBIT"][$treports-5]+$rawdata["EBIT"][$treports-4]));
		$params[] = (($rawdata["CostofRevenue"][$treports-7]=='null'&&$rawdata["CostofRevenue"][$treports-6]=='null'&&$rawdata["CostofRevenue"][$treports-5]=='null'&&$rawdata["CostofRevenue"][$treports-4]=='null')?null:($rawdata["CostofRevenue"][$treports-7]+$rawdata["CostofRevenue"][$treports-6]+$rawdata["CostofRevenue"][$treports-5]+$rawdata["CostofRevenue"][$treports-4]));
		$params[] = (($rawdata["DepreciationAmortizationExpense"][$treports-7]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-6]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-5]=='null'&&$rawdata["DepreciationAmortizationExpense"][$treports-4]=='null')?null:($rawdata["DepreciationAmortizationExpense"][$treports-7]+$rawdata["DepreciationAmortizationExpense"][$treports-6]+$rawdata["DepreciationAmortizationExpense"][$treports-5]+$rawdata["DepreciationAmortizationExpense"][$treports-4]));
		$params[] = (($rawdata["DilutedEPSNetIncome"][$treports-7]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-6]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-5]=='null'&&$rawdata["DilutedEPSNetIncome"][$treports-4]=='null')?null:($rawdata["DilutedEPSNetIncome"][$treports-7]+$rawdata["DilutedEPSNetIncome"][$treports-6]+$rawdata["DilutedEPSNetIncome"][$treports-5]+$rawdata["DilutedEPSNetIncome"][$treports-4]));
		$params[] = (($rawdata["DiscontinuedOperations"][$treports-7]=='null'&&$rawdata["DiscontinuedOperations"][$treports-6]=='null'&&$rawdata["DiscontinuedOperations"][$treports-5]=='null'&&$rawdata["DiscontinuedOperations"][$treports-4]=='null')?null:($rawdata["DiscontinuedOperations"][$treports-7]+$rawdata["DiscontinuedOperations"][$treports-6]+$rawdata["DiscontinuedOperations"][$treports-5]+$rawdata["DiscontinuedOperations"][$treports-7]));
		$params[] = (($rawdata["EquityEarnings"][$treports-7]=='null'&&$rawdata["EquityEarnings"][$treports-6]=='null'&&$rawdata["EquityEarnings"][$treports-5]=='null'&&$rawdata["EquityEarnings"][$treports-4]=='null')?null:($rawdata["EquityEarnings"][$treports-7]+$rawdata["EquityEarnings"][$treports-6]+$rawdata["EquityEarnings"][$treports-5]+$rawdata["EquityEarnings"][$treports-4]));
		$params[] = (($rawdata["AccountingChange"][$treports-7]=='null'&&$rawdata["AccountingChange"][$treports-6]=='null'&&$rawdata["AccountingChange"][$treports-5]=='null'&&$rawdata["AccountingChange"][$treports-4]=='null')?null:($rawdata["AccountingChange"][$treports-7]+$rawdata["AccountingChange"][$treports-6]+$rawdata["AccountingChange"][$treports-5]+$rawdata["AccountingChange"][$treports-4]));
		$params[] = (($rawdata["BasicEPSNetIncome"][$treports-7]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-6]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-5]=='null'&&$rawdata["BasicEPSNetIncome"][$treports-4]=='null')?null:($rawdata["BasicEPSNetIncome"][$treports-7]+$rawdata["BasicEPSNetIncome"][$treports-6]+$rawdata["BasicEPSNetIncome"][$treports-5]+$rawdata["BasicEPSNetIncome"][$treports-4]));
		$params[] = (($rawdata["ExtraordinaryItems"][$treports-7]=='null'&&$rawdata["ExtraordinaryItems"][$treports-6]=='null'&&$rawdata["ExtraordinaryItems"][$treports-5]=='null'&&$rawdata["ExtraordinaryItems"][$treports-4]=='null')?null:($rawdata["ExtraordinaryItems"][$treports-7]+$rawdata["ExtraordinaryItems"][$treports-6]+$rawdata["ExtraordinaryItems"][$treports-5]+$rawdata["ExtraordinaryItems"][$treports-4]));
		$params[] = (($rawdata["GrossProfit"][$treports-7]=='null'&&$rawdata["GrossProfit"][$treports-6]=='null'&&$rawdata["GrossProfit"][$treports-5]=='null'&&$rawdata["GrossProfit"][$treports-4]=='null')?null:($rawdata["GrossProfit"][$treports-7]+$rawdata["GrossProfit"][$treports-6]+$rawdata["GrossProfit"][$treports-5]+$rawdata["GrossProfit"][$treports-4]));
		$params[] = (($rawdata["IncomebeforeExtraordinaryItems"][$treports-7]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-6]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-5]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][$treports-4]=='null')?null:($rawdata["IncomebeforeExtraordinaryItems"][$treports-7]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-6]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-5]+$rawdata["IncomebeforeExtraordinaryItems"][$treports-4]));
		$params[] = (($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null')?null:($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4]));
		$params[] = (($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')?null:($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4]));
		$params[] = (($rawdata["InterestExpense"][$treports-7]=='null'&&$rawdata["InterestExpense"][$treports-6]=='null'&&$rawdata["InterestExpense"][$treports-5]=='null'&&$rawdata["InterestExpense"][$treports-4]=='null')?null:(toFloat($rawdata["InterestExpense"][$treports-7])+toFloat($rawdata["InterestExpense"][$treports-6])+toFloat($rawdata["InterestExpense"][$treports-5])+toFloat($rawdata["InterestExpense"][$treports-4])));
		$params[] = (($rawdata["InterestIncome"][$treports-7]=='null'&&$rawdata["InterestIncome"][$treports-6]=='null'&&$rawdata["InterestIncome"][$treports-5]=='null'&&$rawdata["InterestIncome"][$treports-4]=='null')?null:(toFloat($rawdata["InterestIncome"][$treports-7])+toFloat($rawdata["InterestIncome"][$treports-6])+toFloat($rawdata["InterestIncome"][$treports-5])+toFloat($rawdata["InterestIncome"][$treports-4])));
		$params[] = (($rawdata["MinorityInterestEquityEarnings"][$treports-7]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-6]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-5]=='null'&&$rawdata["MinorityInterestEquityEarnings"][$treports-4]=='null')?null:($rawdata["MinorityInterestEquityEarnings"][$treports-7]+$rawdata["MinorityInterestEquityEarnings"][$treports-6]+$rawdata["MinorityInterestEquityEarnings"][$treports-5]+$rawdata["MinorityInterestEquityEarnings"][$treports-4]));
		$params[] = (($rawdata["NetIncome"][$treports-7]=='null'&&$rawdata["NetIncome"][$treports-6]=='null'&&$rawdata["NetIncome"][$treports-5]=='null'&&$rawdata["NetIncome"][$treports-4]=='null')?null:($rawdata["NetIncome"][$treports-7]+$rawdata["NetIncome"][$treports-6]+$rawdata["NetIncome"][$treports-5]+$rawdata["NetIncome"][$treports-4]));
		$params[] = (($rawdata["NetIncomeApplicabletoCommon"][$treports-7]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-6]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-5]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][$treports-4]=='null')?null:($rawdata["NetIncomeApplicabletoCommon"][$treports-7]+$rawdata["NetIncomeApplicabletoCommon"][$treports-6]+$rawdata["NetIncomeApplicabletoCommon"][$treports-5]+$rawdata["NetIncomeApplicabletoCommon"][$treports-4]));
		$params[] = (($rawdata["OperatingProfit"][$treports-7]=='null'&&$rawdata["OperatingProfit"][$treports-6]=='null'&&$rawdata["OperatingProfit"][$treports-5]=='null'&&$rawdata["OperatingProfit"][$treports-4]=='null')?null:($rawdata["OperatingProfit"][$treports-7]+$rawdata["OperatingProfit"][$treports-6]+$rawdata["OperatingProfit"][$treports-5]+$rawdata["OperatingProfit"][$treports-4]));
		$params[] = (($rawdata["OtherNonoperatingIncomeExpense"][$treports-7]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-6]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-5]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][$treports-4]=='null')?null:($rawdata["OtherNonoperatingIncomeExpense"][$treports-7]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-6]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-5]+$rawdata["OtherNonoperatingIncomeExpense"][$treports-4]));
		$params[] = (($rawdata["OtherOperatingExpenses"][$treports-7]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-6]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-5]=='null'&&$rawdata["OtherOperatingExpenses"][$treports-4]=='null')?null:($rawdata["OtherOperatingExpenses"][$treports-7]+$rawdata["OtherOperatingExpenses"][$treports-6]+$rawdata["OtherOperatingExpenses"][$treports-5]+$rawdata["OtherOperatingExpenses"][$treports-4]));
		$params[] = (($rawdata["ResearchDevelopmentExpense"][$treports-7]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-6]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-5]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-4]=='null')?null:($rawdata["ResearchDevelopmentExpense"][$treports-7]+$rawdata["ResearchDevelopmentExpense"][$treports-6]+$rawdata["ResearchDevelopmentExpense"][$treports-5]+$rawdata["ResearchDevelopmentExpense"][$treports-4]));
		$params[] = (($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-7]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-6]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-5]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-4]=='null')?null:($rawdata["RestructuringRemediationImpairmentProvisions"][$treports-7]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-6]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-5]+$rawdata["RestructuringRemediationImpairmentProvisions"][$treports-4]));
		$params[] = (($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')?null:($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]));
		$params[] = (($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]=='null')?null:($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//36
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["AdjustedEBIT"][$treports-3]=='null'&&$rawdata["AdjustedEBIT"][$treports-2]=='null'&&$rawdata["AdjustedEBIT"][$treports-1]=='null'&&$rawdata["AdjustedEBIT"][$treports]=='null')?null:($rawdata["AdjustedEBIT"][$treports-3]+$rawdata["AdjustedEBIT"][$treports-2]+$rawdata["AdjustedEBIT"][$treports-1]+$rawdata["AdjustedEBIT"][$treports]));
		$params[] = (($rawdata["AdjustedEBITDA"][$treports-3]=='null'&&$rawdata["AdjustedEBITDA"][$treports-2]=='null'&&$rawdata["AdjustedEBITDA"][$treports-1]=='null'&&$rawdata["AdjustedEBITDA"][$treports]=='null')?null:($rawdata["AdjustedEBITDA"][$treports-3]+$rawdata["AdjustedEBITDA"][$treports-2]+$rawdata["AdjustedEBITDA"][$treports-1]+$rawdata["AdjustedEBITDA"][$treports]));
		$params[] = (($rawdata["AdjustedNetIncome"][$treports-3]=='null'&&$rawdata["AdjustedNetIncome"][$treports-3]=='null'&&$rawdata["AdjustedNetIncome"][$treports-1]=='null'&&$rawdata["AdjustedNetIncome"][$treports]=='null')?null:($rawdata["AdjustedNetIncome"][$treports-3]+$rawdata["AdjustedNetIncome"][$treports-2]+$rawdata["AdjustedNetIncome"][$treports-1]+$rawdata["AdjustedNetIncome"][$treports]));
		$divisor = 4;
		if($rawdata["AftertaxMargin"][$treports-3]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports-2]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports-1]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports]=='null') {$divisor--;}
		$params[] = (($divisor==0)?null:(($rawdata["AftertaxMargin"][$treports-3]+$rawdata["AftertaxMargin"][$treports-2]+$rawdata["AftertaxMargin"][$treports-1]+$rawdata["AftertaxMargin"][$treports])/$divisor));
		$params[] = (($rawdata["EBITDA"][$treports-3]=='null'&&$rawdata["EBITDA"][$treports-2]=='null'&&$rawdata["EBITDA"][$treports-1]=='null'&&$rawdata["EBITDA"][$treports]=='null')?null:($rawdata["EBITDA"][$treports-3]+$rawdata["EBITDA"][$treports-2]+$rawdata["EBITDA"][$treports-1]+$rawdata["EBITDA"][$treports]));
		$divisor = 4;
		if($rawdata["GrossMargin"][$treports-3]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-2]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-1]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports]=='null') {$divisor--;}
		$params[] = (($divisor==0)?null:(($rawdata["GrossMargin"][$treports-3]+$rawdata["GrossMargin"][$treports-2]+$rawdata["GrossMargin"][$treports-1]+$rawdata["GrossMargin"][$treports])/$divisor));
		$params[] = (($rawdata["NetOperatingProfitafterTax"][$treports-3]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-2]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-1]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports]=='null')?null:($rawdata["NetOperatingProfitafterTax"][$treports-3]+$rawdata["NetOperatingProfitafterTax"][$treports-2]+$rawdata["NetOperatingProfitafterTax"][$treports-1]+$rawdata["NetOperatingProfitafterTax"][$treports]));
		$divisor = 4;
		if($rawdata["OperatingMargin"][$treports-3]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-2]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-1]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports]=='null') {$divisor--;}
		$params[] = (($divisor==0)?null:(($rawdata["OperatingMargin"][$treports-3]+$rawdata["OperatingMargin"][$treports-2]+$rawdata["OperatingMargin"][$treports-1]+$rawdata["OperatingMargin"][$treports])/$divisor));
		$params[] = (($rawdata["RevenueFQ"][$treports-3]=='null'&&$rawdata["RevenueFQ"][$treports-2]=='null'&&$rawdata["RevenueFQ"][$treports-1]=='null'&&$rawdata["RevenueFQ"][$treports]=='null')?null:($rawdata["RevenueFQ"][$treports-3]+$rawdata["RevenueFQ"][$treports-2]+$rawdata["RevenueFQ"][$treports-1]+$rawdata["RevenueFQ"][$treports]));
		$params[] = (($rawdata["RevenueFY"][$treports-3]=='null'&&$rawdata["RevenueFY"][$treports-2]=='null'&&$rawdata["RevenueFY"][$treports-1]=='null'&&$rawdata["RevenueFY"][$treports]=='null')?null:($rawdata["RevenueFY"][$treports-3]+$rawdata["RevenueFY"][$treports-2]+$rawdata["RevenueFY"][$treports-1]+$rawdata["RevenueFY"][$treports]));
		$params[] = (($rawdata["RevenueTTM"][$treports-3]=='null'&&$rawdata["RevenueTTM"][$treports-2]=='null'&&$rawdata["RevenueTTM"][$treports-1]=='null'&&$rawdata["RevenueTTM"][$treports]=='null')?null:($rawdata["RevenueTTM"][$treports-3]+$rawdata["RevenueTTM"][$treports-2]+$rawdata["RevenueTTM"][$treports-1]+$rawdata["RevenueTTM"][$treports]));
		$params[] = (($rawdata["CostOperatingExpenses"][$treports-3]=='null'&&$rawdata["CostOperatingExpenses"][$treports-2]=='null'&&$rawdata["CostOperatingExpenses"][$treports-1]=='null'&&$rawdata["CostOperatingExpenses"][$treports]=='null')?null:($rawdata["CostOperatingExpenses"][$treports-3]+$rawdata["CostOperatingExpenses"][$treports-2]+$rawdata["CostOperatingExpenses"][$treports-1]+$rawdata["CostOperatingExpenses"][$treports]));
		$params[] = (($rawdata["DepreciationExpense"][$treports-3]=='null'&&$rawdata["DepreciationExpense"][$treports-2]=='null'&&$rawdata["DepreciationExpense"][$treports-1]=='null'&&$rawdata["DepreciationExpense"][$treports]=='null')?null:($rawdata["DepreciationExpense"][$treports-3]+$rawdata["DepreciationExpense"][$treports-2]+$rawdata["DepreciationExpense"][$treports-1]+$rawdata["DepreciationExpense"][$treports]));
		$params[] = (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-3]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-2]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-1]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports]=='null')?null:($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-3]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-2]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-1]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports]));
		$params[] = ($rawdata["DilutedWeightedAverageShares"][$MRQRow] == 'null' ? null: $rawdata["DilutedWeightedAverageShares"][$MRQRow]);
		$params[] = (($rawdata["AmortizationExpense"][$treports-3]=='null'&&$rawdata["AmortizationExpense"][$treports-2]=='null'&&$rawdata["AmortizationExpense"][$treports-1]=='null'&&$rawdata["AmortizationExpense"][$treports]=='null')?null:($rawdata["AmortizationExpense"][$treports-3]+$rawdata["AmortizationExpense"][$treports-2]+$rawdata["AmortizationExpense"][$treports-1]+$rawdata["AmortizationExpense"][$treports]));
		$params[] = (($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-3]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-2]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-1]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports]=='null')?null:($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-3]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-2]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-1]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports]));
		$params[] = ($rawdata["BasicWeightedAverageShares"][$MRQRow] == 'null' ? null: $rawdata["BasicWeightedAverageShares"][$MRQRow]);
		$params[] = (($rawdata["GeneralAdministrativeExpense"][$treports-3]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-2]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-1]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports]=='null')?null:($rawdata["GeneralAdministrativeExpense"][$treports-3]+$rawdata["GeneralAdministrativeExpense"][$treports-2]+$rawdata["GeneralAdministrativeExpense"][$treports-1]+$rawdata["GeneralAdministrativeExpense"][$treports]));
		$params[] = (($rawdata["IncomeAfterTaxes"][$treports-3]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-2]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-1]=='null'&&$rawdata["IncomeAfterTaxes"][$treports]=='null')?null:($rawdata["IncomeAfterTaxes"][$treports-3]+$rawdata["IncomeAfterTaxes"][$treports-2]+$rawdata["IncomeAfterTaxes"][$treports-1]+$rawdata["IncomeAfterTaxes"][$treports]));
		$params[] = (($rawdata["LaborExpense"][$treports-3]=='null'&&$rawdata["LaborExpense"][$treports-2]=='null'&&$rawdata["LaborExpense"][$treports-1]=='null'&&$rawdata["LaborExpense"][$treports]=='null')?null:($rawdata["LaborExpense"][$treports-3]+$rawdata["LaborExpense"][$treports-2]+$rawdata["LaborExpense"][$treports-1]+$rawdata["LaborExpense"][$treports]));
		$params[] = (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-3]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-2]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-1]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports]=='null')?null:($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-3]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-2]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-1]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports]));
		$params[] = (($rawdata["InterestIncomeExpenseNet"][$treports-3]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-2]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-1]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports]=='null')?null:($rawdata["InterestIncomeExpenseNet"][$treports-3]+$rawdata["InterestIncomeExpenseNet"][$treports-2]+$rawdata["InterestIncomeExpenseNet"][$treports-1]+$rawdata["InterestIncomeExpenseNet"][$treports]));
		$params[] = (($rawdata["NoncontrollingInterest"][$treports-3]=='null'&&$rawdata["NoncontrollingInterest"][$treports-2]=='null'&&$rawdata["NoncontrollingInterest"][$treports-1]=='null'&&$rawdata["NoncontrollingInterest"][$treports]=='null')?null:($rawdata["NoncontrollingInterest"][$treports-3]+$rawdata["NoncontrollingInterest"][$treports-2]+$rawdata["NoncontrollingInterest"][$treports-1]+$rawdata["NoncontrollingInterest"][$treports]));
		$params[] = (($rawdata["NonoperatingGainsLosses"][$treports-3]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-2]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-1]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports]=='null')?null:($rawdata["NonoperatingGainsLosses"][$treports-3]+$rawdata["NonoperatingGainsLosses"][$treports-2]+$rawdata["NonoperatingGainsLosses"][$treports-1]+$rawdata["NonoperatingGainsLosses"][$treports]));
		$params[] = (($rawdata["OperatingExpenses"][$treports-3]=='null'&&$rawdata["OperatingExpenses"][$treports-2]=='null'&&$rawdata["OperatingExpenses"][$treports-1]=='null'&&$rawdata["OperatingExpenses"][$treports]=='null')?null:($rawdata["OperatingExpenses"][$treports-3]+$rawdata["OperatingExpenses"][$treports-2]+$rawdata["OperatingExpenses"][$treports-1]+$rawdata["OperatingExpenses"][$treports]));
		$params[] = (($rawdata["OtherGeneralAdministrativeExpense"][$treports-3]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-2]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-1]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports]=='null')?null:($rawdata["OtherGeneralAdministrativeExpense"][$treports-3]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-2]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-1]+$rawdata["OtherGeneralAdministrativeExpense"][$treports]));
		$params[] = (($rawdata["OtherInterestIncomeExpenseNet"][$treports-3]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-2]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-1]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports]=='null')?null:($rawdata["OtherInterestIncomeExpenseNet"][$treports-3]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-2]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-1]+$rawdata["OtherInterestIncomeExpenseNet"][$treports]));
		$params[] = (($rawdata["OtherRevenue"][$treports-3]=='null'&&$rawdata["OtherRevenue"][$treports-2]=='null'&&$rawdata["OtherRevenue"][$treports-1]=='null'&&$rawdata["OtherRevenue"][$treports]=='null')?null:($rawdata["OtherRevenue"][$treports-3]+$rawdata["OtherRevenue"][$treports-2]+$rawdata["OtherRevenue"][$treports-1]+$rawdata["OtherRevenue"][$treports]));
		$params[] = (($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports]=='null')?null:($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports]));
		$params[] = (($rawdata["PreferredDividends"][$treports-3]=='null'&&$rawdata["PreferredDividends"][$treports-2]=='null'&&$rawdata["PreferredDividends"][$treports-1]=='null'&&$rawdata["PreferredDividends"][$treports]=='null')?null:($rawdata["PreferredDividends"][$treports-3]+$rawdata["PreferredDividends"][$treports-2]+$rawdata["PreferredDividends"][$treports-1]+$rawdata["PreferredDividends"][$treports]));
		$params[] = (($rawdata["SalesMarketingExpense"][$treports-3]=='null'&&$rawdata["SalesMarketingExpense"][$treports-2]=='null'&&$rawdata["SalesMarketingExpense"][$treports-1]=='null'&&$rawdata["SalesMarketingExpense"][$treports]=='null')?null:($rawdata["SalesMarketingExpense"][$treports-3]+$rawdata["SalesMarketingExpense"][$treports-2]+$rawdata["SalesMarketingExpense"][$treports-1]+$rawdata["SalesMarketingExpense"][$treports]));
		$params[] = (($rawdata["TotalNonoperatingIncomeExpense"][$treports-3]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-2]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-1]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports]=='null')?null:($rawdata["TotalNonoperatingIncomeExpense"][$treports-3]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-2]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-1]+$rawdata["TotalNonoperatingIncomeExpense"][$treports]));
		$params[] = (($rawdata["TotalOperatingExpenses"][$treports-3]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-2]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-1]=='null'&&$rawdata["TotalOperatingExpenses"][$treports]=='null')?null:($rawdata["TotalOperatingExpenses"][$treports-3]+$rawdata["TotalOperatingExpenses"][$treports-2]+$rawdata["TotalOperatingExpenses"][$treports-1]+$rawdata["TotalOperatingExpenses"][$treports]));
		$params[] = (($rawdata["OperatingRevenue"][$treports-3]=='null'&&$rawdata["OperatingRevenue"][$treports-2]=='null'&&$rawdata["OperatingRevenue"][$treports-1]=='null'&&$rawdata["OperatingRevenue"][$treports]=='null')?null:($rawdata["OperatingRevenue"][$treports-3]+$rawdata["OperatingRevenue"][$treports-2]+$rawdata["OperatingRevenue"][$treports-1]+$rawdata["OperatingRevenue"][$treports]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";//36
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = (($rawdata["AdjustedEBIT"][$treports-7]=='null'&&$rawdata["AdjustedEBIT"][$treports-6]=='null'&&$rawdata["AdjustedEBIT"][$treports-5]=='null'&&$rawdata["AdjustedEBIT"][$treports-4]=='null')?null:($rawdata["AdjustedEBIT"][$treports-7]+$rawdata["AdjustedEBIT"][$treports-6]+$rawdata["AdjustedEBIT"][$treports-5]+$rawdata["AdjustedEBIT"][$treports-4]));
		$params[] = (($rawdata["AdjustedEBITDA"][$treports-7]=='null'&&$rawdata["AdjustedEBITDA"][$treports-6]=='null'&&$rawdata["AdjustedEBITDA"][$treports-5]=='null'&&$rawdata["AdjustedEBITDA"][$treports-4]=='null')?null:($rawdata["AdjustedEBITDA"][$treports-7]+$rawdata["AdjustedEBITDA"][$treports-6]+$rawdata["AdjustedEBITDA"][$treports-5]+$rawdata["AdjustedEBITDA"][$treports-4]));
		$params[] = (($rawdata["AdjustedNetIncome"][$treports-7]=='null'&&$rawdata["AdjustedNetIncome"][$treports-6]=='null'&&$rawdata["AdjustedNetIncome"][$treports-5]=='null'&&$rawdata["AdjustedNetIncome"][$treports-4]=='null')?null:($rawdata["AdjustedNetIncome"][$treports-7]+$rawdata["AdjustedNetIncome"][$treports-6]+$rawdata["AdjustedNetIncome"][$treports-5]+$rawdata["AdjustedNetIncome"][$treports-4]));
		$divisor = 4;
		if($rawdata["AftertaxMargin"][$treports-7]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports-6]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports-5]=='null') {$divisor--;}
		if($rawdata["AftertaxMargin"][$treports-4]=='null') {$divisor--;}
		$params[] = (($divisor==0)?null:(($rawdata["AftertaxMargin"][$treports-7]+$rawdata["AftertaxMargin"][$treports-6]+$rawdata["AftertaxMargin"][$treports-5]+$rawdata["AftertaxMargin"][$treports-4])/$divisor));
		$params[] = (($rawdata["EBITDA"][$treports-7]=='null'&&$rawdata["EBITDA"][$treports-6]=='null'&&$rawdata["EBITDA"][$treports-5]=='null'&&$rawdata["EBITDA"][$treports-4]=='null')?null:($rawdata["EBITDA"][$treports-7]+$rawdata["EBITDA"][$treports-6]+$rawdata["EBITDA"][$treports-5]+$rawdata["EBITDA"][$treports-4]));
		$divisor = 4;
		if($rawdata["GrossMargin"][$treports-7]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-6]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-5]=='null') {$divisor--;}
		if($rawdata["GrossMargin"][$treports-4]=='null') {$divisor--;}
		$params[] = (($divisor==0)?null:(($rawdata["GrossMargin"][$treports-7]+$rawdata["GrossMargin"][$treports-6]+$rawdata["GrossMargin"][$treports-5]+$rawdata["GrossMargin"][$treports-4])/$divisor));
		$params[] = (($rawdata["NetOperatingProfitafterTax"][$treports-7]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-6]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-5]=='null'&&$rawdata["NetOperatingProfitafterTax"][$treports-4]=='null')?null:($rawdata["NetOperatingProfitafterTax"][$treports-7]+$rawdata["NetOperatingProfitafterTax"][$treports-6]+$rawdata["NetOperatingProfitafterTax"][$treports-5]+$rawdata["NetOperatingProfitafterTax"][$treports-4]));
		$divisor = 4;
		if($rawdata["OperatingMargin"][$treports-7]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-6]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-5]=='null') {$divisor--;}
		if($rawdata["OperatingMargin"][$treports-4]=='null') {$divisor--;}
		$params[] = (($divisor==0)?null:(($rawdata["OperatingMargin"][$treports-7]+$rawdata["OperatingMargin"][$treports-6]+$rawdata["OperatingMargin"][$treports-5]+$rawdata["OperatingMargin"][$treports-4])/$divisor));
		$params[] = (($rawdata["RevenueFQ"][$treports-7]=='null'&&$rawdata["RevenueFQ"][$treports-6]=='null'&&$rawdata["RevenueFQ"][$treports-5]=='null'&&$rawdata["RevenueFQ"][$treports-4]=='null')?null:($rawdata["RevenueFQ"][$treports-7]+$rawdata["RevenueFQ"][$treports-6]+$rawdata["RevenueFQ"][$treports-5]+$rawdata["RevenueFQ"][$treports-4]));
		$params[] = (($rawdata["RevenueFY"][$treports-7]=='null'&&$rawdata["RevenueFY"][$treports-6]=='null'&&$rawdata["RevenueFY"][$treports-5]=='null'&&$rawdata["RevenueFY"][$treports-4]=='null')?null:($rawdata["RevenueFY"][$treports-7]+$rawdata["RevenueFY"][$treports-6]+$rawdata["RevenueFY"][$treports-5]+$rawdata["RevenueFY"][$treports-4]));
		$params[] = (($rawdata["RevenueTTM"][$treports-7]=='null'&&$rawdata["RevenueTTM"][$treports-6]=='null'&&$rawdata["RevenueTTM"][$treports-5]=='null'&&$rawdata["RevenueTTM"][$treports-4]=='null')?null:($rawdata["RevenueTTM"][$treports-7]+$rawdata["RevenueTTM"][$treports-6]+$rawdata["RevenueTTM"][$treports-5]+$rawdata["RevenueTTM"][$treports-4]));
		$params[] = (($rawdata["CostOperatingExpenses"][$treports-7]=='null'&&$rawdata["CostOperatingExpenses"][$treports-6]=='null'&&$rawdata["CostOperatingExpenses"][$treports-5]=='null'&&$rawdata["CostOperatingExpenses"][$treports-4]=='null')?null:($rawdata["CostOperatingExpenses"][$treports-7]+$rawdata["CostOperatingExpenses"][$treports-6]+$rawdata["CostOperatingExpenses"][$treports-5]+$rawdata["CostOperatingExpenses"][$treports-4]));
		$params[] = (($rawdata["DepreciationExpense"][$treports-7]=='null'&&$rawdata["DepreciationExpense"][$treports-6]=='null'&&$rawdata["DepreciationExpense"][$treports-5]=='null'&&$rawdata["DepreciationExpense"][$treports-4]=='null')?null:($rawdata["DepreciationExpense"][$treports-7]+$rawdata["DepreciationExpense"][$treports-6]+$rawdata["DepreciationExpense"][$treports-5]+$rawdata["DepreciationExpense"][$treports-4]));
		$params[] = (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-7]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-6]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-5]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-4]=='null')?null:($rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-7]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-6]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-5]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][$treports-4]));
		$params[] = ($rawdata["DilutedWeightedAverageShares"][$PMRQRow] == 'null' ? null: $rawdata["DilutedWeightedAverageShares"][$PMRQRow]);
		$params[] = (($rawdata["AmortizationExpense"][$treports-7]=='null'&&$rawdata["AmortizationExpense"][$treports-6]=='null'&&$rawdata["AmortizationExpense"][$treports-5]=='null'&&$rawdata["AmortizationExpense"][$treports-4]=='null')?null:($rawdata["AmortizationExpense"][$treports-7]+$rawdata["AmortizationExpense"][$treports-6]+$rawdata["AmortizationExpense"][$treports-5]+$rawdata["AmortizationExpense"][$treports-4]));
		$params[] = (($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-7]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-6]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-5]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-4]=='null')?null:($rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-7]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-6]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-5]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][$treports-4]));
		$params[] = ($rawdata["BasicWeightedAverageShares"][$PMRQRow] == 'null' ? null: $rawdata["BasicWeightedAverageShares"][$PMRQRow]);
		$params[] = (($rawdata["GeneralAdministrativeExpense"][$treports-7]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-6]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-5]=='null'&&$rawdata["GeneralAdministrativeExpense"][$treports-4]=='null')?null:($rawdata["GeneralAdministrativeExpense"][$treports-7]+$rawdata["GeneralAdministrativeExpense"][$treports-6]+$rawdata["GeneralAdministrativeExpense"][$treports-5]+$rawdata["GeneralAdministrativeExpense"][$treports-4]));
		$params[] = (($rawdata["IncomeAfterTaxes"][$treports-7]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-6]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-5]=='null'&&$rawdata["IncomeAfterTaxes"][$treports-4]=='null')?null:($rawdata["IncomeAfterTaxes"][$treports-7]+$rawdata["IncomeAfterTaxes"][$treports-6]+$rawdata["IncomeAfterTaxes"][$treports-5]+$rawdata["IncomeAfterTaxes"][$treports-4]));
		$params[] = (($rawdata["LaborExpense"][$treports-7]=='null'&&$rawdata["LaborExpense"][$treports-6]=='null'&&$rawdata["LaborExpense"][$treports-5]=='null'&&$rawdata["LaborExpense"][$treports-4]=='null')?null:($rawdata["LaborExpense"][$treports-7]+$rawdata["LaborExpense"][$treports-6]+$rawdata["LaborExpense"][$treports-5]+$rawdata["LaborExpense"][$treports-4]));
		$params[] = (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-7]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-6]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-5]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-4]=='null')?null:($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-7]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-6]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-5]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$treports-4]));
		$params[] = (($rawdata["InterestIncomeExpenseNet"][$treports-7]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-6]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-5]=='null'&&$rawdata["InterestIncomeExpenseNet"][$treports-4]=='null')?null:($rawdata["InterestIncomeExpenseNet"][$treports-7]+$rawdata["InterestIncomeExpenseNet"][$treports-6]+$rawdata["InterestIncomeExpenseNet"][$treports-5]+$rawdata["InterestIncomeExpenseNet"][$treports-4]));
		$params[] = (($rawdata["NoncontrollingInterest"][$treports-7]=='null'&&$rawdata["NoncontrollingInterest"][$treports-6]=='null'&&$rawdata["NoncontrollingInterest"][$treports-5]=='null'&&$rawdata["NoncontrollingInterest"][$treports-4]=='null')?null:($rawdata["NoncontrollingInterest"][$treports-7]+$rawdata["NoncontrollingInterest"][$treports-6]+$rawdata["NoncontrollingInterest"][$treports-5]+$rawdata["NoncontrollingInterest"][$treports-4]));
		$params[] = (($rawdata["NonoperatingGainsLosses"][$treports-7]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-6]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-5]=='null'&&$rawdata["NonoperatingGainsLosses"][$treports-4]=='null')?null:($rawdata["NonoperatingGainsLosses"][$treports-7]+$rawdata["NonoperatingGainsLosses"][$treports-6]+$rawdata["NonoperatingGainsLosses"][$treports-5]+$rawdata["NonoperatingGainsLosses"][$treports-4]));
		$params[] = (($rawdata["OperatingExpenses"][$treports-7]=='null'&&$rawdata["OperatingExpenses"][$treports-6]=='null'&&$rawdata["OperatingExpenses"][$treports-5]=='null'&&$rawdata["OperatingExpenses"][$treports-4]=='null')?null:($rawdata["OperatingExpenses"][$treports-7]+$rawdata["OperatingExpenses"][$treports-6]+$rawdata["OperatingExpenses"][$treports-5]+$rawdata["OperatingExpenses"][$treports-4]));
		$params[] = (($rawdata["OtherGeneralAdministrativeExpense"][$treports-7]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-6]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-5]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][$treports-4]=='null')?null:($rawdata["OtherGeneralAdministrativeExpense"][$treports-7]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-6]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-5]+$rawdata["OtherGeneralAdministrativeExpense"][$treports-4]));
		$params[] = (($rawdata["OtherInterestIncomeExpenseNet"][$treports-7]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-6]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-5]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][$treports-4]=='null')?null:($rawdata["OtherInterestIncomeExpenseNet"][$treports-7]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-6]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-5]+$rawdata["OtherInterestIncomeExpenseNet"][$treports-4]));
		$params[] = (($rawdata["OtherRevenue"][$treports-7]=='null'&&$rawdata["OtherRevenue"][$treports-6]=='null'&&$rawdata["OtherRevenue"][$treports-5]=='null'&&$rawdata["OtherRevenue"][$treports-4]=='null')?null:($rawdata["OtherRevenue"][$treports-7]+$rawdata["OtherRevenue"][$treports-6]+$rawdata["OtherRevenue"][$treports-5]+$rawdata["OtherRevenue"][$treports-4]));
		$params[] = (($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-4]=='null')?null:($rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][$treports-4]));
		$params[] = (($rawdata["PreferredDividends"][$treports-7]=='null'&&$rawdata["PreferredDividends"][$treports-6]=='null'&&$rawdata["PreferredDividends"][$treports-5]=='null'&&$rawdata["PreferredDividends"][$treports-4]=='null')?null:($rawdata["PreferredDividends"][$treports-7]+$rawdata["PreferredDividends"][$treports-6]+$rawdata["PreferredDividends"][$treports-5]+$rawdata["PreferredDividends"][$treports-4]));
		$params[] = (($rawdata["SalesMarketingExpense"][$treports-7]=='null'&&$rawdata["SalesMarketingExpense"][$treports-6]=='null'&&$rawdata["SalesMarketingExpense"][$treports-5]=='null'&&$rawdata["SalesMarketingExpense"][$treports-4]=='null')?null:($rawdata["SalesMarketingExpense"][$treports-7]+$rawdata["SalesMarketingExpense"][$treports-6]+$rawdata["SalesMarketingExpense"][$treports-5]+$rawdata["SalesMarketingExpense"][$treports-4]));
		$params[] = (($rawdata["TotalNonoperatingIncomeExpense"][$treports-7]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-6]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-5]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][$treports-4]=='null')?null:($rawdata["TotalNonoperatingIncomeExpense"][$treports-7]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-6]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-5]+$rawdata["TotalNonoperatingIncomeExpense"][$treports-4]));
		$params[] = (($rawdata["TotalOperatingExpenses"][$treports-7]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-6]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-5]=='null'&&$rawdata["TotalOperatingExpenses"][$treports-4]=='null')?null:($rawdata["TotalOperatingExpenses"][$treports-7]+$rawdata["TotalOperatingExpenses"][$treports-6]+$rawdata["TotalOperatingExpenses"][$treports-5]+$rawdata["TotalOperatingExpenses"][$treports-4]));
		$params[] = (($rawdata["OperatingRevenue"][$treports-7]=='null'&&$rawdata["OperatingRevenue"][$treports-6]=='null'&&$rawdata["OperatingRevenue"][$treports-5]=='null'&&$rawdata["OperatingRevenue"][$treports-4]=='null')?null:($rawdata["OperatingRevenue"][$treports-7]+$rawdata["OperatingRevenue"][$treports-6]+$rawdata["OperatingRevenue"][$treports-5]+$rawdata["OperatingRevenue"][$treports-4]));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //20
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ((($rawdata["CostofRevenue"][$treports-3]=='null'&&$rawdata["CostofRevenue"][$treports-2]=='null'&&$rawdata["CostofRevenue"][$treports-1]=='null'&&$rawdata["CostofRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["CostofRevenue"][$treports-3]+$rawdata["CostofRevenue"][$treports-2]+$rawdata["CostofRevenue"][$treports-1]+$rawdata["CostofRevenue"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["GrossProfit"][$treports-3]=='null'&&$rawdata["GrossProfit"][$treports-2]=='null'&&$rawdata["GrossProfit"][$treports-1]=='null'&&$rawdata["GrossProfit"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["GrossProfit"][$treports-3]+$rawdata["GrossProfit"][$treports-2]+$rawdata["GrossProfit"][$treports-1]+$rawdata["GrossProfit"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["SellingGeneralAdministrativeExpenses"][$treports-3]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-2]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-1]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["ResearchDevelopmentExpense"][$treports-3]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-2]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-1]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["ResearchDevelopmentExpense"][$treports-3]+$rawdata["ResearchDevelopmentExpense"][$treports-2]+$rawdata["ResearchDevelopmentExpense"][$treports-1]+$rawdata["ResearchDevelopmentExpense"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["EBITDA"][$treports-3]=='null'&&$rawdata["EBITDA"][$treports-2]=='null'&&$rawdata["EBITDA"][$treports-1]=='null'&&$rawdata["EBITDA"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["EBITDA"][$treports-3]+$rawdata["EBITDA"][$treports-2]+$rawdata["EBITDA"][$treports-1]+$rawdata["EBITDA"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["OperatingProfit"][$treports-3]=='null'&&$rawdata["OperatingProfit"][$treports-2]=='null'&&$rawdata["OperatingProfit"][$treports-1]=='null'&&$rawdata["OperatingProfit"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["OperatingProfit"][$treports-3]+$rawdata["OperatingProfit"][$treports-2]+$rawdata["OperatingProfit"][$treports-1]+$rawdata["OperatingProfit"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["EBIT"][$treports-3]=='null'&&$rawdata["EBIT"][$treports-2]=='null'&&$rawdata["EBIT"][$treports-1]=='null'&&$rawdata["EBIT"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["EBIT"][$treports-3]+$rawdata["EBIT"][$treports-2]+$rawdata["EBIT"][$treports-1]+$rawdata["EBIT"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$params[] = ((($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports]==0))?null:(($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports])/($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports])));
		$params[] = ((($rawdata["IncomeTaxes"][$treports-3]=='null'&&$rawdata["IncomeTaxes"][$treports-2]=='null'&&$rawdata["IncomeTaxes"][$treports-1]=='null'&&$rawdata["IncomeTaxes"][$treports]=='null')&&($rawdata["IncomeBeforeTaxes"][$treports-3]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-2]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-1]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports]=='null'))?null:(($rawdata["IncomeBeforeTaxes"][$treports-3]+$rawdata["IncomeBeforeTaxes"][$treports-2]+$rawdata["IncomeBeforeTaxes"][$treports-1]+$rawdata["IncomeBeforeTaxes"][$treports])-($rawdata["IncomeTaxes"][$treports-3]+$rawdata["IncomeTaxes"][$treports-2]+$rawdata["IncomeTaxes"][$treports-1]+$rawdata["IncomeTaxes"][$treports])));
		$params[] = ((($rawdata["NetIncome"][$treports-3]=='null'&&$rawdata["NetIncome"][$treports-2]=='null'&&$rawdata["NetIncome"][$treports-1]=='null'&&$rawdata["NetIncome"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]=='null'&&$rawdata["TotalRevenue"][$treports-2]=='null'&&$rawdata["TotalRevenue"][$treports-1]=='null'&&$rawdata["TotalRevenue"][$treports]=='null')||($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports]==0))?null:(($rawdata["NetIncome"][$treports-3]+$rawdata["NetIncome"][$treports-2]+$rawdata["NetIncome"][$treports-1]+$rawdata["NetIncome"][$treports])/($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])));
		$value = 0;
		if(($rawdata["DividendsPaid"][$treports-3]=='null'&&$rawdata["DividendsPaid"][$treports-2]=='null'&&$rawdata["DividendsPaid"][$treports-1]=='null'&&$rawdata["DividendsPaid"][$treports]=='null')||($rawdata["SharesOutstandingBasic"][$treports-3]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-2]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-1]=='null'&&$rawdata["SharesOutstandingBasic"][$treports]=='null')||($rawdata["SharesOutstandingBasic"][$treports-3]+$rawdata["SharesOutstandingBasic"][$treports-2]+$rawdata["SharesOutstandingBasic"][$treports-1]+$rawdata["SharesOutstandingBasic"][$treports]==0)) {
			$value = "'null'";
		} else {
			if($rawdata["DividendsPaid"][$treports-3]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-3]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-3]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-3]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-3])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-2]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-2]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-2]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-2]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-2])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-1]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-1]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-1]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-1]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-1])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports]!='null'&&$rawdata["SharesOutstandingBasic"][$treports]!='null'&&$rawdata["SharesOutstandingBasic"][$treports]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports]/(toFloat($rawdata["SharesOutstandingBasic"][$treports])*1000000));
			}
		}
		$params[] = $value;
		$params[] = ((($rawdata["CurrentPortionofLongtermDebt"][$treports-3]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-2]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-1]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports]=='null')&&($rawdata["ShorttermBorrowings"][$treports-3]=='null'&&$rawdata["ShorttermBorrowings"][$treports-2]=='null'&&$rawdata["ShorttermBorrowings"][$treports-1]=='null'&&$rawdata["ShorttermBorrowings"][$treports]=='null'))?null:($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow]));
		$params[] = ((($rawdata["TotalLongtermDebt"][$treports-3]=='null'&&$rawdata["TotalLongtermDebt"][$treports-2]=='null'&&$rawdata["TotalLongtermDebt"][$treports-1]=='null'&&$rawdata["TotalLongtermDebt"][$treports]=='null')&&($rawdata["NotesPayable"][$treports-3]=='null'&&$rawdata["NotesPayable"][$treports-2]=='null'&&$rawdata["NotesPayable"][$treports-1]=='null'&&$rawdata["NotesPayable"][$treports]=='null'))?null:($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow]));
		$params[] = ((($rawdata["LongtermDebtProceeds"][$treports-3]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-2]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-1]=='null'&&$rawdata["LongtermDebtProceeds"][$treports]=='null')&&($rawdata["LongtermDebtPayments"][$treports-3]=='null'&&$rawdata["LongtermDebtPayments"][$treports-2]=='null'&&$rawdata["LongtermDebtPayments"][$treports-1]=='null'&&$rawdata["LongtermDebtPayments"][$treports]=='null'))?null:(($rawdata["LongtermDebtProceeds"][$treports-3]+$rawdata["LongtermDebtProceeds"][$treports-2]+$rawdata["LongtermDebtProceeds"][$treports-1]+$rawdata["LongtermDebtProceeds"][$treports])+($rawdata["LongtermDebtPayments"][$treports-3]+$rawdata["LongtermDebtPayments"][$treports-2]+$rawdata["LongtermDebtPayments"][$treports-1]+$rawdata["LongtermDebtPayments"][$treports])));
		$params[] = (($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')?null:(-($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])));
		$params[] = ((($rawdata["CashfromOperatingActivities"][$treports-3]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-2]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-1]=='null'&&$rawdata["CashfromOperatingActivities"][$treports]=='null')&&($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null'))?null:(($rawdata["CashfromOperatingActivities"][$treports-3]+$rawdata["CashfromOperatingActivities"][$treports-2]+$rawdata["CashfromOperatingActivities"][$treports-1]+$rawdata["CashfromOperatingActivities"][$treports])+($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])));
		$params[] = ((($rawdata["CFNetIncome"][$treports-3]=='null'&&$rawdata["CFNetIncome"][$treports-2]=='null'&&$rawdata["CFNetIncome"][$treports-1]=='null'&&$rawdata["CFNetIncome"][$treports]=='null')&&($rawdata["CFDepreciationAmortization"][$treports-3]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-2]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-1]=='null'&&$rawdata["CFDepreciationAmortization"][$treports]=='null')&&($rawdata["EmployeeCompensation"][$treports-3]=='null'&&$rawdata["EmployeeCompensation"][$treports-2]=='null'&&$rawdata["EmployeeCompensation"][$treports-1]=='null'&&$rawdata["EmployeeCompensation"][$treports]=='null')&&($rawdata["AdjustmentforSpecialCharges"][$treports-3]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-2]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-1]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports]=='null')&&($rawdata["DeferredIncomeTaxes"][$treports-3]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-2]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-1]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports]=='null')&&($rawdata["CapitalExpenditures"][$treports-3]=='null'&&$rawdata["CapitalExpenditures"][$treports-2]=='null'&&$rawdata["CapitalExpenditures"][$treports-1]=='null'&&$rawdata["CapitalExpenditures"][$treports]=='null')&&($rawdata["ChangeinCurrentAssets"][$treports-3]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-2]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-1]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports]=='null')&&($rawdata["ChangeinCurrentLiabilities"][$treports-3]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-2]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-1]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports]=='null'))?null:(($rawdata["CFNetIncome"][$treports-3]+$rawdata["CFNetIncome"][$treports-2]+$rawdata["CFNetIncome"][$treports-1]+$rawdata["CFNetIncome"][$treports])+($rawdata["CFDepreciationAmortization"][$treports-3]+$rawdata["CFDepreciationAmortization"][$treports-2]+$rawdata["CFDepreciationAmortization"][$treports-1]+$rawdata["CFDepreciationAmortization"][$treports])+($rawdata["EmployeeCompensation"][$treports-3]+$rawdata["EmployeeCompensation"][$treports-2]+$rawdata["EmployeeCompensation"][$treports-1]+$rawdata["EmployeeCompensation"][$treports])+($rawdata["AdjustmentforSpecialCharges"][$treports-3]+$rawdata["AdjustmentforSpecialCharges"][$treports-2]+$rawdata["AdjustmentforSpecialCharges"][$treports-1]+$rawdata["AdjustmentforSpecialCharges"][$treports])+($rawdata["DeferredIncomeTaxes"][$treports-3]+$rawdata["DeferredIncomeTaxes"][$treports-2]+$rawdata["DeferredIncomeTaxes"][$treports-1]+$rawdata["DeferredIncomeTaxes"][$treports])+($rawdata["CapitalExpenditures"][$treports-3]+$rawdata["CapitalExpenditures"][$treports-2]+$rawdata["CapitalExpenditures"][$treports-1]+$rawdata["CapitalExpenditures"][$treports])+(($rawdata["ChangeinCurrentAssets"][$treports-3]+$rawdata["ChangeinCurrentAssets"][$treports-2]+$rawdata["ChangeinCurrentAssets"][$treports-1]+$rawdata["ChangeinCurrentAssets"][$treports])+($rawdata["ChangeinCurrentLiabilities"][$treports-3]+$rawdata["ChangeinCurrentLiabilities"][$treports-2]+$rawdata["ChangeinCurrentLiabilities"][$treports-1]+$rawdata["ChangeinCurrentLiabilities"][$treports]))));
		$params[] = ((($rawdata["TotalRevenue"][$treports-3]=='null' && $rawdata["TotalRevenue"][$treports-2]=='null' && $rawdata["TotalRevenue"][$treports-1]=='null' && $rawdata["TotalRevenue"][$treports]=='null') || $rawdata["TotalRevenue"][$areports-5]=='null' || $rawdata["TotalRevenue"][$areports-5]<=0 || ($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports] < 0))?null:(pow(($rawdata["TotalRevenue"][$treports-3]+$rawdata["TotalRevenue"][$treports-2]+$rawdata["TotalRevenue"][$treports-1]+$rawdata["TotalRevenue"][$treports])/$rawdata["TotalRevenue"][$areports-5], 1/5) - 1));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
		}

		$query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //19
		$params = array();
		$params[] = $dates->ticker_id;
		$params[] = ((($rawdata["CostofRevenue"][$treports-7]=='null'&&$rawdata["CostofRevenue"][$treports-6]=='null'&&$rawdata["CostofRevenue"][$treports-5]=='null'&&$rawdata["CostofRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["CostofRevenue"][$treports-7]+$rawdata["CostofRevenue"][$treports-6]+$rawdata["CostofRevenue"][$treports-5]+$rawdata["CostofRevenue"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["GrossProfit"][$treports-7]=='null'&&$rawdata["GrossProfit"][$treports-6]=='null'&&$rawdata["GrossProfit"][$treports-5]=='null'&&$rawdata["GrossProfit"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["GrossProfit"][$treports-7]+$rawdata["GrossProfit"][$treports-6]+$rawdata["GrossProfit"][$treports-5]+$rawdata["GrossProfit"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["SellingGeneralAdministrativeExpenses"][$treports-7]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-6]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-5]+$rawdata["SellingGeneralAdministrativeExpenses"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["ResearchDevelopmentExpense"][$treports-7]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-6]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-5]=='null'&&$rawdata["ResearchDevelopmentExpense"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["ResearchDevelopmentExpense"][$treports-7]+$rawdata["ResearchDevelopmentExpense"][$treports-6]+$rawdata["ResearchDevelopmentExpense"][$treports-5]+$rawdata["ResearchDevelopmentExpense"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["EBITDA"][$treports-7]=='null'&&$rawdata["EBITDA"][$treports-6]=='null'&&$rawdata["EBITDA"][$treports-5]=='null'&&$rawdata["EBITDA"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["EBITDA"][$treports-7]+$rawdata["EBITDA"][$treports-6]+$rawdata["EBITDA"][$treports-5]+$rawdata["EBITDA"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["OperatingProfit"][$treports-7]=='null'&&$rawdata["OperatingProfit"][$treports-6]=='null'&&$rawdata["OperatingProfit"][$treports-5]=='null'&&$rawdata["OperatingProfit"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["OperatingProfit"][$treports-7]+$rawdata["OperatingProfit"][$treports-6]+$rawdata["OperatingProfit"][$treports-5]+$rawdata["OperatingProfit"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["EBIT"][$treports-7]=='null'&&$rawdata["EBIT"][$treports-6]=='null'&&$rawdata["EBIT"][$treports-5]=='null'&&$rawdata["EBIT"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["EBIT"][$treports-7]+$rawdata["EBIT"][$treports-6]+$rawdata["EBIT"][$treports-5]+$rawdata["EBIT"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$params[] = ((($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null')||($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4]==0))?null:(($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4])/($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4])));
		$params[] = ((($rawdata["IncomeTaxes"][$treports-7]=='null'&&$rawdata["IncomeTaxes"][$treports-6]=='null'&&$rawdata["IncomeTaxes"][$treports-5]=='null'&&$rawdata["IncomeTaxes"][$treports-4]=='null')&&($rawdata["IncomeBeforeTaxes"][$treports-7]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-6]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-5]=='null'&&$rawdata["IncomeBeforeTaxes"][$treports-4]=='null'))?null:(($rawdata["IncomeBeforeTaxes"][$treports-7]+$rawdata["IncomeBeforeTaxes"][$treports-6]+$rawdata["IncomeBeforeTaxes"][$treports-5]+$rawdata["IncomeBeforeTaxes"][$treports-4])-($rawdata["IncomeTaxes"][$treports-7]+$rawdata["IncomeTaxes"][$treports-6]+$rawdata["IncomeTaxes"][$treports-5]+$rawdata["IncomeTaxes"][$treports-4])));
		$params[] = ((($rawdata["NetIncome"][$treports-7]=='null'&&$rawdata["NetIncome"][$treports-6]=='null'&&$rawdata["NetIncome"][$treports-5]=='null'&&$rawdata["NetIncome"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]=='null'&&$rawdata["TotalRevenue"][$treports-6]=='null'&&$rawdata["TotalRevenue"][$treports-5]=='null'&&$rawdata["TotalRevenue"][$treports-4]=='null')||($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4]==0))?null:(($rawdata["NetIncome"][$treports-7]+$rawdata["NetIncome"][$treports-6]+$rawdata["NetIncome"][$treports-5]+$rawdata["NetIncome"][$treports-4])/($rawdata["TotalRevenue"][$treports-7]+$rawdata["TotalRevenue"][$treports-6]+$rawdata["TotalRevenue"][$treports-5]+$rawdata["TotalRevenue"][$treports-4])));
		$value = 0;
		if(($rawdata["DividendsPaid"][$treports-7]=='null'&&$rawdata["DividendsPaid"][$treports-6]=='null'&&$rawdata["DividendsPaid"][$treports-5]=='null'&&$rawdata["DividendsPaid"][$treports-4]=='null')||($rawdata["SharesOutstandingBasic"][$treports-7]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-6]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-5]=='null'&&$rawdata["SharesOutstandingBasic"][$treports-4]=='null')||($rawdata["SharesOutstandingBasic"][$treports-7]+$rawdata["SharesOutstandingBasic"][$treports-6]+$rawdata["SharesOutstandingBasic"][$treports-5]+$rawdata["SharesOutstandingBasic"][$treports-4]==0)) {
			$value = "'null'";
		} else {
			if($rawdata["DividendsPaid"][$treports-7]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-7]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-7]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-7]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-7])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-6]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-6]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-6]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-6]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-6])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-5]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-5]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-5]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-5]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-5])*1000000));
			}
			if($rawdata["DividendsPaid"][$treports-4]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-4]!='null'&&$rawdata["SharesOutstandingBasic"][$treports-4]!=0) {
				$value -= ($rawdata["DividendsPaid"][$treports-4]/(toFloat($rawdata["SharesOutstandingBasic"][$treports-4])*1000000));
			}
		}
		$params[] = $value;
		$params[] = ((($rawdata["CurrentPortionofLongtermDebt"][$treports-7]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-6]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-5]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][$treports-4]=='null')&&($rawdata["ShorttermBorrowings"][$treports-7]=='null'&&$rawdata["ShorttermBorrowings"][$treports-6]=='null'&&$rawdata["ShorttermBorrowings"][$treports-5]=='null'&&$rawdata["ShorttermBorrowings"][$treports-4]=='null'))?null:($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow]));
		$params[] = ((($rawdata["TotalLongtermDebt"][$treports-7]=='null'&&$rawdata["TotalLongtermDebt"][$treports-6]=='null'&&$rawdata["TotalLongtermDebt"][$treports-5]=='null'&&$rawdata["TotalLongtermDebt"][$treports-4]=='null')&&($rawdata["NotesPayable"][$treports-7]=='null'&&$rawdata["NotesPayable"][$treports-6]=='null'&&$rawdata["NotesPayable"][$treports-5]=='null'&&$rawdata["NotesPayable"][$treports-4]=='null'))?null:($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow]));
		$params[] = ((($rawdata["LongtermDebtProceeds"][$treports-7]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-6]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-5]=='null'&&$rawdata["LongtermDebtProceeds"][$treports-4]=='null')&&($rawdata["LongtermDebtPayments"][$treports-7]=='null'&&$rawdata["LongtermDebtPayments"][$treports-6]=='null'&&$rawdata["LongtermDebtPayments"][$treports-5]=='null'&&$rawdata["LongtermDebtPayments"][$treports-4]=='null'))?null:(($rawdata["LongtermDebtProceeds"][$treports-7]+$rawdata["LongtermDebtProceeds"][$treports-6]+$rawdata["LongtermDebtProceeds"][$treports-5]+$rawdata["LongtermDebtProceeds"][$treports-4])+($rawdata["LongtermDebtPayments"][$treports-7]+$rawdata["LongtermDebtPayments"][$treports-6]+$rawdata["LongtermDebtPayments"][$treports-5]+$rawdata["LongtermDebtPayments"][$treports-4])));
		$params[] = (($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')?null:(-($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])));
		$params[] = ((($rawdata["CashfromOperatingActivities"][$treports-7]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-6]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-5]=='null'&&$rawdata["CashfromOperatingActivities"][$treports-4]=='null')&&($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null'))?null:(($rawdata["CashfromOperatingActivities"][$treports-7]+$rawdata["CashfromOperatingActivities"][$treports-6]+$rawdata["CashfromOperatingActivities"][$treports-5]+$rawdata["CashfromOperatingActivities"][$treports-4])+($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])));
		$params[] = ((($rawdata["CFNetIncome"][$treports-7]=='null'&&$rawdata["CFNetIncome"][$treports-6]=='null'&&$rawdata["CFNetIncome"][$treports-5]=='null'&&$rawdata["CFNetIncome"][$treports-4]=='null')&&($rawdata["CFDepreciationAmortization"][$treports-7]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-6]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-5]=='null'&&$rawdata["CFDepreciationAmortization"][$treports-4]=='null')&&($rawdata["EmployeeCompensation"][$treports-7]=='null'&&$rawdata["EmployeeCompensation"][$treports-6]=='null'&&$rawdata["EmployeeCompensation"][$treports-5]=='null'&&$rawdata["EmployeeCompensation"][$treports-4]=='null')&&($rawdata["AdjustmentforSpecialCharges"][$treports-7]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-6]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-5]=='null'&&$rawdata["AdjustmentforSpecialCharges"][$treports-4]=='null')&&($rawdata["DeferredIncomeTaxes"][$treports-7]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-6]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-5]=='null'&&$rawdata["DeferredIncomeTaxes"][$treports-4]=='null')&&($rawdata["CapitalExpenditures"][$treports-7]=='null'&&$rawdata["CapitalExpenditures"][$treports-6]=='null'&&$rawdata["CapitalExpenditures"][$treports-5]=='null'&&$rawdata["CapitalExpenditures"][$treports-4]=='null')&&($rawdata["ChangeinCurrentAssets"][$treports-7]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-6]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-5]=='null'&&$rawdata["ChangeinCurrentAssets"][$treports-4]=='null')&&($rawdata["ChangeinCurrentLiabilities"][$treports-7]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-6]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-5]=='null'&&$rawdata["ChangeinCurrentLiabilities"][$treports-4]=='null'))?null:(($rawdata["CFNetIncome"][$treports-7]+$rawdata["CFNetIncome"][$treports-6]+$rawdata["CFNetIncome"][$treports-5]+$rawdata["CFNetIncome"][$treports-4])+($rawdata["CFDepreciationAmortization"][$treports-7]+$rawdata["CFDepreciationAmortization"][$treports-6]+$rawdata["CFDepreciationAmortization"][$treports-5]+$rawdata["CFDepreciationAmortization"][$treports-4])+($rawdata["EmployeeCompensation"][$treports-7]+$rawdata["EmployeeCompensation"][$treports-6]+$rawdata["EmployeeCompensation"][$treports-5]+$rawdata["EmployeeCompensation"][$treports-4])+($rawdata["AdjustmentforSpecialCharges"][$treports-7]+$rawdata["AdjustmentforSpecialCharges"][$treports-6]+$rawdata["AdjustmentforSpecialCharges"][$treports-5]+$rawdata["AdjustmentforSpecialCharges"][$treports-4])+($rawdata["DeferredIncomeTaxes"][$treports-7]+$rawdata["DeferredIncomeTaxes"][$treports-6]+$rawdata["DeferredIncomeTaxes"][$treports-5]+$rawdata["DeferredIncomeTaxes"][$treports-4])+($rawdata["CapitalExpenditures"][$treports-7]+$rawdata["CapitalExpenditures"][$treports-6]+$rawdata["CapitalExpenditures"][$treports-5]+$rawdata["CapitalExpenditures"][$treports-4])+(($rawdata["ChangeinCurrentAssets"][$treports-7]+$rawdata["ChangeinCurrentAssets"][$treports-6]+$rawdata["ChangeinCurrentAssets"][$treports-5]+$rawdata["ChangeinCurrentAssets"][$treports-4])+($rawdata["ChangeinCurrentLiabilities"][$treports-7]+$rawdata["ChangeinCurrentLiabilities"][$treports-6]+$rawdata["ChangeinCurrentLiabilities"][$treports-5]+$rawdata["ChangeinCurrentLiabilities"][$treports-4]))));
		try {
			$res = $db->prepare($query);
			$res->execute($params);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("- Line: ".__LINE__." - ".$ex->getMessage());
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
