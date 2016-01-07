<?php
function update_raw_data_tickers($dates, $rawdata) {
	$ttm_tables = array("ttm_balanceconsolidated","ttm_balancefull","ttm_cashflowconsolidated","ttm_cashflowfull","ttm_incomeconsolidated","ttm_incomefull","ttm_financialscustom", "ttm_gf_data");
	$pttm_tables = array("pttm_balanceconsolidated","pttm_balancefull","pttm_cashflowconsolidated","pttm_cashflowfull","pttm_incomeconsolidated","pttm_incomefull","pttm_financialscustom", "pttm_gf_data");

        //Delete all reports before updating to be sure we do not miss any manual update
        //as this is a batch process, it will not impact on the UE
        foreach($ttm_tables as $table) {
                $query = "DELETE FROM $table WHERE ticker_id = ".$dates->ticker_id;
                mysql_query($query) or die ($query." ".mysql_error());
        }
        foreach($pttm_tables as $table) {
                $query = "DELETE FROM $table WHERE ticker_id = ".$dates->ticker_id;
                mysql_query($query) or die ($query." ".mysql_error());
        }

	//Update SalesPercChange and Sales5YYCGrPerc from reports_financialscustom
	//While this should by in another file, this one has the necesary structure
	for ($i = 2; $i < 11; $i++) {
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
			mysql_query($query) or die ($query." ".mysql_error());
		}
	}

	//Update TTM and PTTM data
	//Determine if USA stock or ADR
	$stock_type = "ADR";
	$MRQRow = 10;
	$PMRQRow = 9;
	if($rawdata["Country"][10] == "UNITED STATES OF AMERICA" || $rawdata["Country"][26] == "UNITED STATES OF AMERICA" || strpos($rawdata["FormType"][10], "10-K") !== false || strpos($rawdata["FormType"][26], "10-K") !== false || strpos($rawdata["FormType"][10], "10-Q") !== false || strpos($rawdata["FormType"][26], "10-Q") !== false || strpos($rawdata["FormType"][10], "8-K") !== false || strpos($rawdata["FormType"][26], "8-K") !== false) {
		$stock_type = "USA";
		$MRQRow = 26;
		$PMRQRow = 22;
	}

	//Load Balance MRQ data
	$query = "INSERT INTO `ttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
	$query .= $rawdata["CommitmentsContingencies"][$MRQRow].",";
      	$query .= $rawdata["CommonStock"][$MRQRow].",";
       	$query .= $rawdata["DeferredCharges"][$MRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesCurrent"][$MRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesLongterm"][$MRQRow].",";
       	$query .= $rawdata["AccountsPayableandAccruedExpenses"][$MRQRow].",";
       	$query .= $rawdata["AccruedInterest"][$MRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapital"][$MRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapitalPreferredStock"][$MRQRow].",";
       	$query .= $rawdata["CashandCashEquivalents"][$MRQRow].",";
       	$query .= $rawdata["CashCashEquivalentsandShorttermInvestments"][$MRQRow].",";
       	$query .= $rawdata["Goodwill"][$MRQRow].",";
       	$query .= $rawdata["IntangibleAssets"][$MRQRow].",";
       	$query .= $rawdata["InventoriesNet"][$MRQRow].",";
       	$query .= $rawdata["LongtermDeferredIncomeTaxLiabilities"][$MRQRow].",";
       	$query .= $rawdata["LongtermDeferredLiabilityCharges"][$MRQRow].",";
       	$query .= $rawdata["LongtermInvestments"][$MRQRow].",";
       	$query .= $rawdata["MinorityInterest"][$MRQRow].",";
       	$query .= $rawdata["OtherAccumulatedComprehensiveIncome"][$MRQRow].",";
       	$query .= $rawdata["OtherAssets"][$MRQRow].",";
       	$query .= $rawdata["OtherCurrentAssets"][$MRQRow].",";
       	$query .= $rawdata["OtherCurrentLiabilities"][$MRQRow].",";
       	$query .= $rawdata["OtherEquity"][$MRQRow].",";
       	$query .= $rawdata["OtherInvestments"][$MRQRow].",";
       	$query .= $rawdata["OtherLiabilities"][$MRQRow].",";
       	$query .= $rawdata["PartnersCapital"][$MRQRow].",";
       	$query .= $rawdata["PensionPostretirementObligation"][$MRQRow].",";
       	$query .= $rawdata["PreferredStock"][$MRQRow].",";
       	$query .= $rawdata["PrepaidExpenses"][$MRQRow].",";
       	$query .= $rawdata["PropertyPlantEquipmentNet"][$MRQRow].",";
       	$query .= $rawdata["RestrictedCash"][$MRQRow].",";
       	$query .= $rawdata["RetainedEarnings"][$MRQRow].",";
       	$query .= $rawdata["TemporaryEquity"][$MRQRow].",";
       	$query .= $rawdata["TotalAssets"][$MRQRow].",";
       	$query .= $rawdata["TotalCurrentAssets"][$MRQRow].",";
       	$query .= $rawdata["TotalCurrentLiabilities"][$MRQRow].",";
       	$query .= $rawdata["TotalLiabilities"][$MRQRow].",";
       	$query .= $rawdata["TotalLongtermDebt"][$MRQRow].",";
       	$query .= $rawdata["TotalReceivablesNet"][$MRQRow].",";
       	$query .= $rawdata["TotalShorttermDebt"][$MRQRow].",";
       	$query .= $rawdata["TotalStockholdersEquity"][$MRQRow].",";
       	$query .= $rawdata["TreasuryStock"][$MRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query." ".mysql_error());

	$query = "INSERT INTO `pttm_balanceconsolidated` (`ticker_id`, `CommitmentsContingencies`, `CommonStock`, `DeferredCharges`, `DeferredIncomeTaxesCurrent`, `DeferredIncomeTaxesLongterm`, `AccountsPayableandAccruedExpenses`, `AccruedInterest`, `AdditionalPaidinCapital`, `AdditionalPaidinCapitalPreferredStock`, `CashandCashEquivalents`, `CashCashEquivalentsandShorttermInvestments`, `Goodwill`, `IntangibleAssets`, `InventoriesNet`, `LongtermDeferredIncomeTaxLiabilities`, `LongtermDeferredLiabilityCharges`, `LongtermInvestments`, `MinorityInterest`, `OtherAccumulatedComprehensiveIncome`, `OtherAssets`, `OtherCurrentAssets`, `OtherCurrentLiabilities`, `OtherEquity`, `OtherInvestments`, `OtherLiabilities`, `PartnersCapital`, `PensionPostretirementObligation`, `PreferredStock`, `PrepaidExpenses`, `PropertyPlantEquipmentNet`, `RestrictedCash`, `RetainedEarnings`, `TemporaryEquity`, `TotalAssets`, `TotalCurrentAssets`, `TotalCurrentLiabilities`, `TotalLiabilities`, `TotalLongtermDebt`, `TotalReceivablesNet`, `TotalShorttermDebt`, `TotalStockholdersEquity`, `TreasuryStock`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
	$query .= $rawdata["CommitmentsContingencies"][$PMRQRow].",";
      	$query .= $rawdata["CommonStock"][$PMRQRow].",";
       	$query .= $rawdata["DeferredCharges"][$PMRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesCurrent"][$PMRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxesLongterm"][$PMRQRow].",";
       	$query .= $rawdata["AccountsPayableandAccruedExpenses"][$PMRQRow].",";
       	$query .= $rawdata["AccruedInterest"][$PMRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapital"][$PMRQRow].",";
       	$query .= $rawdata["AdditionalPaidinCapitalPreferredStock"][$PMRQRow].",";
       	$query .= $rawdata["CashandCashEquivalents"][$PMRQRow].",";
       	$query .= $rawdata["CashCashEquivalentsandShorttermInvestments"][$PMRQRow].",";
       	$query .= $rawdata["Goodwill"][$PMRQRow].",";
       	$query .= $rawdata["IntangibleAssets"][$PMRQRow].",";
       	$query .= $rawdata["InventoriesNet"][$PMRQRow].",";
       	$query .= $rawdata["LongtermDeferredIncomeTaxLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["LongtermDeferredLiabilityCharges"][$PMRQRow].",";
       	$query .= $rawdata["LongtermInvestments"][$PMRQRow].",";
       	$query .= $rawdata["MinorityInterest"][$PMRQRow].",";
       	$query .= $rawdata["OtherAccumulatedComprehensiveIncome"][$PMRQRow].",";
       	$query .= $rawdata["OtherAssets"][$PMRQRow].",";
       	$query .= $rawdata["OtherCurrentAssets"][$PMRQRow].",";
       	$query .= $rawdata["OtherCurrentLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["OtherEquity"][$PMRQRow].",";
       	$query .= $rawdata["OtherInvestments"][$PMRQRow].",";
       	$query .= $rawdata["OtherLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["PartnersCapital"][$PMRQRow].",";
       	$query .= $rawdata["PensionPostretirementObligation"][$PMRQRow].",";
       	$query .= $rawdata["PreferredStock"][$PMRQRow].",";
       	$query .= $rawdata["PrepaidExpenses"][$PMRQRow].",";
       	$query .= $rawdata["PropertyPlantEquipmentNet"][$PMRQRow].",";
       	$query .= $rawdata["RestrictedCash"][$PMRQRow].",";
       	$query .= $rawdata["RetainedEarnings"][$PMRQRow].",";
       	$query .= $rawdata["TemporaryEquity"][$PMRQRow].",";
       	$query .= $rawdata["TotalAssets"][$PMRQRow].",";
       	$query .= $rawdata["TotalCurrentAssets"][$PMRQRow].",";
       	$query .= $rawdata["TotalCurrentLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["TotalLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["TotalLongtermDebt"][$PMRQRow].",";
       	$query .= $rawdata["TotalReceivablesNet"][$PMRQRow].",";
       	$query .= $rawdata["TotalShorttermDebt"][$PMRQRow].",";
       	$query .= $rawdata["TotalStockholdersEquity"][$PMRQRow].",";
       	$query .= $rawdata["TreasuryStock"][$PMRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query." ".mysql_error());

	$query = "INSERT INTO `ttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
       	$query .= $rawdata["TotalDebt"][$MRQRow].",";
       	$query .= $rawdata["TotalAssetsFQ"][$MRQRow].",";
       	$query .= $rawdata["TotalAssetsFY"][$MRQRow].",";
       	$query .= $rawdata["CurrentPortionofLongtermDebt"][$MRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$MRQRow].",";
       	$query .= $rawdata["DeferredLiabilityCharges"][$MRQRow].",";
       	$query .= $rawdata["AccountsNotesReceivableNet"][$MRQRow].",";
       	$query .= $rawdata["AccountsPayable"][$MRQRow].",";
       	$query .= $rawdata["AccountsReceivableTradeNet"][$MRQRow].",";
       	$query .= $rawdata["AccruedExpenses"][$MRQRow].",";
       	$query .= $rawdata["AccumulatedDepreciation"][$MRQRow].",";
       	$query .= $rawdata["AmountsDuetoRelatedPartiesShortterm"][$MRQRow].",";
       	$query .= $rawdata["GoodwillIntangibleAssetsNet"][$MRQRow].",";
       	$query .= $rawdata["IncomeTaxesPayable"][$MRQRow].",";
       	$query .= $rawdata["LiabilitiesStockholdersEquity"][$MRQRow].",";
       	$query .= $rawdata["LongtermDebt"][$MRQRow].",";
       	$query .= $rawdata["NotesPayable"][$MRQRow].",";
       	$query .= $rawdata["OperatingLeases"][$MRQRow].",";
       	$query .= $rawdata["OtherAccountsNotesReceivable"][$MRQRow].",";
       	$query .= $rawdata["OtherAccountsPayableandAccruedExpenses"][$MRQRow].",";
       	$query .= $rawdata["OtherBorrowings"][$MRQRow].",";
       	$query .= $rawdata["OtherReceivables"][$MRQRow].",";
       	$query .= $rawdata["PropertyandEquipmentGross"][$MRQRow].",";
       	$query .= $rawdata["TotalLongtermAssets"][$MRQRow].",";
       	$query .= $rawdata["TotalLongtermLiabilities"][$MRQRow].",";
       	$query .= $rawdata["TotalSharesOutstanding"][$MRQRow].",";
	$query .= $rawdata["ShorttermInvestments"][$MRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query." ".mysql_error());

	$query = "INSERT INTO `pttm_balancefull` (`ticker_id`, `TotalDebt`, `TotalAssetsFQ`, `TotalAssetsFY`, `CurrentPortionofLongtermDebt`, `DeferredIncomeTaxLiabilitiesShortterm`, `DeferredLiabilityCharges`, `AccountsNotesReceivableNet`, `AccountsPayable`, `AccountsReceivableTradeNet`, `AccruedExpenses`, `AccumulatedDepreciation`, `AmountsDuetoRelatedPartiesShortterm`, `GoodwillIntangibleAssetsNet`, `IncomeTaxesPayable`, `LiabilitiesStockholdersEquity`, `LongtermDebt`, `NotesPayable`, `OperatingLeases`, `OtherAccountsNotesReceivable`, `OtherAccountsPayableandAccruedExpenses`, `OtherBorrowings`, `OtherReceivables`, `PropertyandEquipmentGross`, `TotalLongtermAssets`, `TotalLongtermLiabilities`, `TotalSharesOutstanding`, `ShorttermInvestments`) VALUES (";
       	$query .= "'".$dates->ticker_id."',";
       	$query .= $rawdata["TotalDebt"][$PMRQRow].",";
       	$query .= $rawdata["TotalAssetsFQ"][$PMRQRow].",";
       	$query .= $rawdata["TotalAssetsFY"][$PMRQRow].",";
       	$query .= $rawdata["CurrentPortionofLongtermDebt"][$PMRQRow].",";
       	$query .= $rawdata["DeferredIncomeTaxLiabilitiesShortterm"][$PMRQRow].",";
       	$query .= $rawdata["DeferredLiabilityCharges"][$PMRQRow].",";
       	$query .= $rawdata["AccountsNotesReceivableNet"][$PMRQRow].",";
       	$query .= $rawdata["AccountsPayable"][$PMRQRow].",";
       	$query .= $rawdata["AccountsReceivableTradeNet"][$PMRQRow].",";
       	$query .= $rawdata["AccruedExpenses"][$PMRQRow].",";
       	$query .= $rawdata["AccumulatedDepreciation"][$PMRQRow].",";
       	$query .= $rawdata["AmountsDuetoRelatedPartiesShortterm"][$PMRQRow].",";
       	$query .= $rawdata["GoodwillIntangibleAssetsNet"][$PMRQRow].",";
       	$query .= $rawdata["IncomeTaxesPayable"][$PMRQRow].",";
       	$query .= $rawdata["LiabilitiesStockholdersEquity"][$PMRQRow].",";
       	$query .= $rawdata["LongtermDebt"][$PMRQRow].",";
       	$query .= $rawdata["NotesPayable"][$PMRQRow].",";
       	$query .= $rawdata["OperatingLeases"][$PMRQRow].",";
       	$query .= $rawdata["OtherAccountsNotesReceivable"][$PMRQRow].",";
       	$query .= $rawdata["OtherAccountsPayableandAccruedExpenses"][$PMRQRow].",";
       	$query .= $rawdata["OtherBorrowings"][$PMRQRow].",";
       	$query .= $rawdata["OtherReceivables"][$PMRQRow].",";
       	$query .= $rawdata["PropertyandEquipmentGross"][$PMRQRow].",";
       	$query .= $rawdata["TotalLongtermAssets"][$PMRQRow].",";
       	$query .= $rawdata["TotalLongtermLiabilities"][$PMRQRow].",";
       	$query .= $rawdata["TotalSharesOutstanding"][$PMRQRow].",";
	$query .= $rawdata["ShorttermInvestments"][$PMRQRow];
       	$query .= ")";
       	mysql_query($query) or die ($query." ".mysql_error());

	//Cashflow and Financial
	if($stock_type == "ADR") {
		$query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= toFloat($rawdata["InterestIncome"][$MRQRow]).",";
                $query .= toFloat($rawdata["InterestExpense"][$MRQRow]).",";
                $query .= toFloat($rawdata["EPSBasic"][$MRQRow]).",";
                $query .= toFloat($rawdata["EPSDiluted"][$MRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$MRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$MRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$MRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query." ".mysql_error());

                $query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= toFloat($rawdata["InterestIncome"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InterestExpense"][$PMRQRow]).",";
                $query .= toFloat($rawdata["EPSBasic"][$PMRQRow]).",";
                $query .= toFloat($rawdata["EPSDiluted"][$PMRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$PMRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$PMRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$PMRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= $rawdata["ChangeinCurrentAssets"][$MRQRow].",";
        	$query .= $rawdata["ChangeinCurrentLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinDebtNet"][$MRQRow].",";
        	$query .= $rawdata["ChangeinDeferredRevenue"][$MRQRow].",";
        	$query .= $rawdata["ChangeinEquityNet"][$MRQRow].",";
        	$query .= $rawdata["ChangeinIncomeTaxesPayable"][$MRQRow].",";
        	$query .= $rawdata["ChangeinInventories"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOperatingAssetsLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherAssets"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentAssets"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinOtherLiabilities"][$MRQRow].",";
        	$query .= $rawdata["ChangeinPrepaidExpenses"][$MRQRow].",";
        	$query .= $rawdata["DividendsPaid"][$MRQRow].",";
        	$query .= $rawdata["EffectofExchangeRateonCash"][$MRQRow].",";
        	$query .= $rawdata["EmployeeCompensation"][$MRQRow].",";
        	$query .= $rawdata["AcquisitionSaleofBusinessNet"][$MRQRow].",";
        	$query .= $rawdata["AdjustmentforEquityEarnings"][$MRQRow].",";
        	$query .= $rawdata["AdjustmentforMinorityInterest"][$MRQRow].",";
        	$query .= $rawdata["AdjustmentforSpecialCharges"][$MRQRow].",";
        	$query .= $rawdata["CapitalExpenditures"][$MRQRow].",";
        	$query .= $rawdata["CashfromDiscontinuedOperations"][$MRQRow].",";
        	$query .= $rawdata["CashfromFinancingActivities"][$MRQRow].",";
        	$query .= $rawdata["CashfromInvestingActivities"][$MRQRow].",";
        	$query .= $rawdata["CashfromOperatingActivities"][$MRQRow].",";
        	$query .= $rawdata["CFDepreciationAmortization"][$MRQRow].",";
        	$query .= $rawdata["DeferredIncomeTaxes"][$MRQRow].",";
        	$query .= $rawdata["ChangeinAccountsPayableAccruedExpenses"][$MRQRow].",";
        	$query .= $rawdata["ChangeinAccountsReceivable"][$MRQRow].",";
        	$query .= $rawdata["InvestmentChangesNet"][$MRQRow].",";
        	$query .= $rawdata["NetChangeinCash"][$MRQRow].",";
        	$query .= $rawdata["OtherAdjustments"][$MRQRow].",";
        	$query .= $rawdata["OtherAssetLiabilityChangesNet"][$MRQRow].",";
        	$query .= $rawdata["OtherFinancingActivitiesNet"][$MRQRow].",";
        	$query .= $rawdata["OtherInvestingActivities"][$MRQRow].",";
        	$query .= $rawdata["RealizedGainsLosses"][$MRQRow].",";
        	$query .= $rawdata["SaleofPropertyPlantEquipment"][$MRQRow].",";
        	$query .= $rawdata["StockOptionTaxBenefits"][$MRQRow].",";
        	$query .= $rawdata["TotalAdjustments"][$MRQRow];
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());
		
		$query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= $rawdata["ChangeinCurrentAssets"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinCurrentLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinDebtNet"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinDeferredRevenue"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinEquityNet"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinIncomeTaxesPayable"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinInventories"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOperatingAssetsLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherAssets"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentAssets"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherCurrentLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinOtherLiabilities"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinPrepaidExpenses"][$PMRQRow].",";
        	$query .= $rawdata["DividendsPaid"][$PMRQRow].",";
        	$query .= $rawdata["EffectofExchangeRateonCash"][$PMRQRow].",";
        	$query .= $rawdata["EmployeeCompensation"][$PMRQRow].",";
        	$query .= $rawdata["AcquisitionSaleofBusinessNet"][$PMRQRow].",";
        	$query .= $rawdata["AdjustmentforEquityEarnings"][$PMRQRow].",";
        	$query .= $rawdata["AdjustmentforMinorityInterest"][$PMRQRow].",";
        	$query .= $rawdata["AdjustmentforSpecialCharges"][$PMRQRow].",";
        	$query .= $rawdata["CapitalExpenditures"][$PMRQRow].",";
        	$query .= $rawdata["CashfromDiscontinuedOperations"][$PMRQRow].",";
        	$query .= $rawdata["CashfromFinancingActivities"][$PMRQRow].",";
        	$query .= $rawdata["CashfromInvestingActivities"][$PMRQRow].",";
        	$query .= $rawdata["CashfromOperatingActivities"][$PMRQRow].",";
        	$query .= $rawdata["CFDepreciationAmortization"][$PMRQRow].",";
        	$query .= $rawdata["DeferredIncomeTaxes"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinAccountsPayableAccruedExpenses"][$PMRQRow].",";
        	$query .= $rawdata["ChangeinAccountsReceivable"][$PMRQRow].",";
        	$query .= $rawdata["InvestmentChangesNet"][$PMRQRow].",";
        	$query .= $rawdata["NetChangeinCash"][$PMRQRow].",";
        	$query .= $rawdata["OtherAdjustments"][$PMRQRow].",";
        	$query .= $rawdata["OtherAssetLiabilityChangesNet"][$PMRQRow].",";
        	$query .= $rawdata["OtherFinancingActivitiesNet"][$PMRQRow].",";
        	$query .= $rawdata["OtherInvestingActivities"][$PMRQRow].",";
        	$query .= $rawdata["RealizedGainsLosses"][$PMRQRow].",";
        	$query .= $rawdata["SaleofPropertyPlantEquipment"][$PMRQRow].",";
        	$query .= $rawdata["StockOptionTaxBenefits"][$PMRQRow].",";
        	$query .= $rawdata["TotalAdjustments"][$PMRQRow];
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["ChangeinLongtermDebtNet"][$MRQRow].",";
       		$query .= $rawdata["ChangeinShorttermBorrowingsNet"][$MRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsBeginningofYear"][$MRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsEndofYear"][$MRQRow].",";
       		$query .= $rawdata["CashPaidforIncomeTaxes"][$MRQRow].",";
      		$query .= $rawdata["CashPaidforInterestExpense"][$MRQRow].",";
       		$query .= $rawdata["CFNetIncome"][$MRQRow].",";
       		$query .= $rawdata["IssuanceofEquity"][$MRQRow].",";
       		$query .= $rawdata["LongtermDebtPayments"][$MRQRow].",";
       		$query .= $rawdata["LongtermDebtProceeds"][$MRQRow].",";
      		$query .= $rawdata["OtherDebtNet"][$MRQRow].",";
       		$query .= $rawdata["OtherEquityTransactionsNet"][$MRQRow].",";
       		$query .= $rawdata["OtherInvestmentChangesNet"][$MRQRow].",";
       		$query .= $rawdata["PurchaseofInvestments"][$MRQRow].",";
       		$query .= $rawdata["RepurchaseofEquity"][$MRQRow].",";
       		$query .= $rawdata["SaleofInvestments"][$MRQRow].",";
       		$query .= $rawdata["ShorttermBorrowings"][$MRQRow].",";
       		$query .= $rawdata["TotalNoncashAdjustments"][$MRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["ChangeinLongtermDebtNet"][$PMRQRow].",";
       		$query .= $rawdata["ChangeinShorttermBorrowingsNet"][$PMRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsBeginningofYear"][$PMRQRow].",";
       		$query .= $rawdata["CashandCashEquivalentsEndofYear"][$PMRQRow].",";
       		$query .= $rawdata["CashPaidforIncomeTaxes"][$PMRQRow].",";
      		$query .= $rawdata["CashPaidforInterestExpense"][$PMRQRow].",";
       		$query .= $rawdata["CFNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["IssuanceofEquity"][$PMRQRow].",";
       		$query .= $rawdata["LongtermDebtPayments"][$PMRQRow].",";
       		$query .= $rawdata["LongtermDebtProceeds"][$PMRQRow].",";
      		$query .= $rawdata["OtherDebtNet"][$PMRQRow].",";
       		$query .= $rawdata["OtherEquityTransactionsNet"][$PMRQRow].",";
       		$query .= $rawdata["OtherInvestmentChangesNet"][$PMRQRow].",";
       		$query .= $rawdata["PurchaseofInvestments"][$PMRQRow].",";
       		$query .= $rawdata["RepurchaseofEquity"][$PMRQRow].",";
       		$query .= $rawdata["SaleofInvestments"][$PMRQRow].",";
       		$query .= $rawdata["ShorttermBorrowings"][$PMRQRow].",";
       		$query .= $rawdata["TotalNoncashAdjustments"][$PMRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["EBIT"][$MRQRow].",";
       		$query .= $rawdata["CostofRevenue"][$MRQRow].",";
       		$query .= $rawdata["DepreciationAmortizationExpense"][$MRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncome"][$MRQRow].",";
       		$query .= $rawdata["DiscontinuedOperations"][$MRQRow].",";
       		$query .= $rawdata["EquityEarnings"][$MRQRow].",";
      		$query .= $rawdata["AccountingChange"][$MRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncome"][$MRQRow].",";
       		$query .= $rawdata["ExtraordinaryItems"][$MRQRow].",";
       		$query .= $rawdata["GrossProfit"][$MRQRow].",";
       		$query .= $rawdata["IncomebeforeExtraordinaryItems"][$MRQRow].",";
       		$query .= $rawdata["IncomeBeforeTaxes"][$MRQRow].",";
       		$query .= $rawdata["IncomeTaxes"][$MRQRow].",";
       		$query .= toFloat($rawdata["InterestExpense"][$MRQRow]).",";
       		$query .= toFloat($rawdata["InterestIncome"][$MRQRow]).",";
       		$query .= $rawdata["MinorityInterestEquityEarnings"][$MRQRow].",";
       		$query .= $rawdata["NetIncome"][$MRQRow].",";
       		$query .= $rawdata["NetIncomeApplicabletoCommon"][$MRQRow].",";
       		$query .= $rawdata["OperatingProfit"][$MRQRow].",";
       		$query .= $rawdata["OtherNonoperatingIncomeExpense"][$MRQRow].",";
      		$query .= $rawdata["OtherOperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["ResearchDevelopmentExpense"][$MRQRow].",";
       		$query .= $rawdata["RestructuringRemediationImpairmentProvisions"][$MRQRow].",";
       		$query .= $rawdata["TotalRevenue"][$MRQRow].",";
       		$query .= $rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["EBIT"][$PMRQRow].",";
       		$query .= $rawdata["CostofRevenue"][$PMRQRow].",";
       		$query .= $rawdata["DepreciationAmortizationExpense"][$PMRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["DiscontinuedOperations"][$PMRQRow].",";
       		$query .= $rawdata["EquityEarnings"][$PMRQRow].",";
      		$query .= $rawdata["AccountingChange"][$PMRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["ExtraordinaryItems"][$PMRQRow].",";
       		$query .= $rawdata["GrossProfit"][$PMRQRow].",";
       		$query .= $rawdata["IncomebeforeExtraordinaryItems"][$PMRQRow].",";
       		$query .= $rawdata["IncomeBeforeTaxes"][$PMRQRow].",";
       		$query .= $rawdata["IncomeTaxes"][$PMRQRow].",";
       		$query .= toFloat($rawdata["InterestExpense"][$PMRQRow]).",";
       		$query .= toFloat($rawdata["InterestIncome"][$PMRQRow]).",";
       		$query .= $rawdata["MinorityInterestEquityEarnings"][$PMRQRow].",";
       		$query .= $rawdata["NetIncome"][$PMRQRow].",";
       		$query .= $rawdata["NetIncomeApplicabletoCommon"][$PMRQRow].",";
       		$query .= $rawdata["OperatingProfit"][$PMRQRow].",";
       		$query .= $rawdata["OtherNonoperatingIncomeExpense"][$PMRQRow].",";
      		$query .= $rawdata["OtherOperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["ResearchDevelopmentExpense"][$PMRQRow].",";
       		$query .= $rawdata["RestructuringRemediationImpairmentProvisions"][$PMRQRow].",";
       		$query .= $rawdata["TotalRevenue"][$PMRQRow].",";
       		$query .= $rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["AdjustedEBIT"][$MRQRow].",";
       		$query .= $rawdata["AdjustedEBITDA"][$MRQRow].",";
      		$query .= $rawdata["AdjustedNetIncome"][$MRQRow].",";
       		$query .= $rawdata["AftertaxMargin"][$MRQRow].",";
       		$query .= $rawdata["EBITDA"][$MRQRow].",";
      		$query .= $rawdata["GrossMargin"][$MRQRow].",";
       		$query .= $rawdata["NetOperatingProfitafterTax"][$MRQRow].",";
       		$query .= $rawdata["OperatingMargin"][$MRQRow].",";
       		$query .= $rawdata["RevenueFQ"][$MRQRow].",";
      		$query .= $rawdata["RevenueFY"][$MRQRow].",";
       		$query .= $rawdata["RevenueTTM"][$MRQRow].",";
       		$query .= $rawdata["CostOperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["DepreciationExpense"][$MRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncomefromContinuingOperations"][$MRQRow].",";
       		$query .= $rawdata["DilutedWeightedAverageShares"][$MRQRow].",";
       		$query .= $rawdata["AmortizationExpense"][$MRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncomefromContinuingOperations"][$MRQRow].",";
       		$query .= $rawdata["BasicWeightedAverageShares"][$MRQRow].",";
      		$query .= $rawdata["GeneralAdministrativeExpense"][$MRQRow].",";
       		$query .= $rawdata["IncomeAfterTaxes"][$MRQRow].",";
       		$query .= $rawdata["LaborExpense"][$MRQRow].",";
       		$query .= $rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$MRQRow].",";
       		$query .= $rawdata["InterestIncomeExpenseNet"][$MRQRow].",";
       		$query .= $rawdata["NoncontrollingInterest"][$MRQRow].",";
       		$query .= $rawdata["NonoperatingGainsLosses"][$MRQRow].",";
       		$query .= $rawdata["OperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["OtherGeneralAdministrativeExpense"][$MRQRow].",";
       		$query .= $rawdata["OtherInterestIncomeExpenseNet"][$MRQRow].",";
       		$query .= $rawdata["OtherRevenue"][$MRQRow].",";
       		$query .= $rawdata["OtherSellingGeneralAdministrativeExpenses"][$MRQRow].",";
      		$query .= $rawdata["PreferredDividends"][$MRQRow].",";
       		$query .= $rawdata["SalesMarketingExpense"][$MRQRow].",";
       		$query .= $rawdata["TotalNonoperatingIncomeExpense"][$MRQRow].",";
       		$query .= $rawdata["TotalOperatingExpenses"][$MRQRow].",";
       		$query .= $rawdata["OperatingRevenue"][$MRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
       		$query .= $rawdata["AdjustedEBIT"][$PMRQRow].",";
       		$query .= $rawdata["AdjustedEBITDA"][$PMRQRow].",";
      		$query .= $rawdata["AdjustedNetIncome"][$PMRQRow].",";
       		$query .= $rawdata["AftertaxMargin"][$PMRQRow].",";
       		$query .= $rawdata["EBITDA"][$PMRQRow].",";
      		$query .= $rawdata["GrossMargin"][$PMRQRow].",";
       		$query .= $rawdata["NetOperatingProfitafterTax"][$PMRQRow].",";
       		$query .= $rawdata["OperatingMargin"][$PMRQRow].",";
       		$query .= $rawdata["RevenueFQ"][$PMRQRow].",";
      		$query .= $rawdata["RevenueFY"][$PMRQRow].",";
       		$query .= $rawdata["RevenueTTM"][$PMRQRow].",";
       		$query .= $rawdata["CostOperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["DepreciationExpense"][$PMRQRow].",";
      		$query .= $rawdata["DilutedEPSNetIncomefromContinuingOperations"][$PMRQRow].",";
       		$query .= $rawdata["DilutedWeightedAverageShares"][$PMRQRow].",";
       		$query .= $rawdata["AmortizationExpense"][$PMRQRow].",";
       		$query .= $rawdata["BasicEPSNetIncomefromContinuingOperations"][$PMRQRow].",";
       		$query .= $rawdata["BasicWeightedAverageShares"][$PMRQRow].",";
      		$query .= $rawdata["GeneralAdministrativeExpense"][$PMRQRow].",";
       		$query .= $rawdata["IncomeAfterTaxes"][$PMRQRow].",";
       		$query .= $rawdata["LaborExpense"][$PMRQRow].",";
       		$query .= $rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][$PMRQRow].",";
       		$query .= $rawdata["InterestIncomeExpenseNet"][$PMRQRow].",";
       		$query .= $rawdata["NoncontrollingInterest"][$PMRQRow].",";
       		$query .= $rawdata["NonoperatingGainsLosses"][$PMRQRow].",";
       		$query .= $rawdata["OperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["OtherGeneralAdministrativeExpense"][$PMRQRow].",";
       		$query .= $rawdata["OtherInterestIncomeExpenseNet"][$PMRQRow].",";
       		$query .= $rawdata["OtherRevenue"][$PMRQRow].",";
       		$query .= $rawdata["OtherSellingGeneralAdministrativeExpenses"][$PMRQRow].",";
      		$query .= $rawdata["PreferredDividends"][$PMRQRow].",";
       		$query .= $rawdata["SalesMarketingExpense"][$PMRQRow].",";
       		$query .= $rawdata["TotalNonoperatingIncomeExpense"][$PMRQRow].",";
       		$query .= $rawdata["TotalOperatingExpenses"][$PMRQRow].",";
       		$query .= $rawdata["OperatingRevenue"][$PMRQRow];
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

                $query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["CostofRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["CostofRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["GrossProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["GrossProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]=='null' ||  $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["ResearchDevelopmentExpense"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["CFDepreciationAmortization"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["CFDepreciationAmortization"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["EBITDA"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["EBITDA"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["OperatingProfit"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["OperatingProfit"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["EBIT"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["EBIT"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["IncomeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$MRQRow]==0)?'null':($rawdata["IncomeTaxes"][$MRQRow]/$rawdata["IncomeBeforeTaxes"][$MRQRow])).",";
                $query .= (($rawdata["IncomeBeforeTaxes"][$MRQRow]=='null' && $rawdata["IncomeTaxes"][$MRQRow]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$MRQRow]-$rawdata["IncomeTaxes"][$MRQRow])).",";
                $query .= (($rawdata["NetIncome"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow]==0)?'null':($rawdata["NetIncome"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow])).",";
                $query .= (($rawdata["DividendsPaid"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$MRQRow]==0)?'null':(-($rawdata["DividendsPaid"][$MRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$MRQRow])*1000000))).",";
                $query .= (($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]=='null' && $rawdata["ShorttermBorrowings"][$MRQRow]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow])).",";
                $query .= (($rawdata["TotalLongtermDebt"][$MRQRow]=='null' && $rawdata["NotesPayable"][$MRQRow]=='null')?'null':($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow])).",";
                $query .= (($rawdata["LongtermDebtProceeds"][$MRQRow]=='null' && $rawdata["LongtermDebtPayments"][$MRQRow] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$MRQRow]+$rawdata["LongtermDebtPayments"][$MRQRow])).",";
                $query .= (($rawdata["CapitalExpenditures"][$MRQRow]=='null')?'null':(-$rawdata["CapitalExpenditures"][$MRQRow])).",";
                $query .= (($rawdata["CashfromOperatingActivities"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null')?'null':($rawdata["CashfromOperatingActivities"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow])).",";
                $query .= (($rawdata["CFNetIncome"][$MRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$MRQRow]=='null' && $rawdata["EmployeeCompensation"][$MRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$MRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$MRQRow]=='null' && $rawdata["CapitalExpenditures"][$MRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$MRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$MRQRow]=='null')?'null':($rawdata["CFNetIncome"][$MRQRow]+$rawdata["CFDepreciationAmortization"][$MRQRow]+$rawdata["EmployeeCompensation"][$MRQRow]+$rawdata["AdjustmentforSpecialCharges"][$MRQRow]+$rawdata["DeferredIncomeTaxes"][$MRQRow]+$rawdata["CapitalExpenditures"][$MRQRow]+($rawdata["ChangeinCurrentAssets"][$MRQRow]+$rawdata["ChangeinCurrentLiabilities"][$MRQRow]))).",";
		$query .= (($rawdata["TotalRevenue"][$MRQRow]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]=='null' || $rawdata["TotalRevenue"][$MRQRow-5]<=0 || $rawdata["TotalRevenue"][$MRQRow] < 0)?'null':(pow($rawdata["TotalRevenue"][$MRQRow]/$rawdata["TotalRevenue"][$MRQRow-5], 1/5) - 1));
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());

                $query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["CostofRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["CostofRevenue"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["GrossProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["GrossProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]=='null' ||  $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["SellingGeneralAdministrativeExpenses"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["ResearchDevelopmentExpense"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["ResearchDevelopmentExpense"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["CFDepreciationAmortization"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["EBITDA"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["EBITDA"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["OperatingProfit"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["OperatingProfit"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["EBIT"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["EBIT"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["IncomeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' || $rawdata["IncomeBeforeTaxes"][$PMRQRow]==0)?'null':($rawdata["IncomeTaxes"][$PMRQRow]/$rawdata["IncomeBeforeTaxes"][$PMRQRow])).",";
                $query .= (($rawdata["IncomeBeforeTaxes"][$PMRQRow]=='null' && $rawdata["IncomeTaxes"][$PMRQRow]=='null')?'null':($rawdata["IncomeBeforeTaxes"][$PMRQRow]-$rawdata["IncomeTaxes"][$PMRQRow])).",";
                $query .= (($rawdata["NetIncome"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]=='null' || $rawdata["TotalRevenue"][$PMRQRow]==0)?'null':($rawdata["NetIncome"][$PMRQRow]/$rawdata["TotalRevenue"][$PMRQRow])).",";
                $query .= (($rawdata["DividendsPaid"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]=='null' || $rawdata["SharesOutstandingBasic"][$PMRQRow]==0)?'null':(-($rawdata["DividendsPaid"][$PMRQRow])/(toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow])*1000000))).",";
                $query .= (($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]=='null' && $rawdata["ShorttermBorrowings"][$PMRQRow]=='null')?'null':($rawdata["CurrentPortionofLongtermDebt"][$PMRQRow]+$rawdata["ShorttermBorrowings"][$PMRQRow])).",";
                $query .= (($rawdata["TotalLongtermDebt"][$PMRQRow]=='null' && $rawdata["NotesPayable"][$PMRQRow]=='null')?'null':($rawdata["TotalLongtermDebt"][$PMRQRow]+$rawdata["NotesPayable"][$PMRQRow])).",";
                $query .= (($rawdata["LongtermDebtProceeds"][$PMRQRow]=='null' && $rawdata["LongtermDebtPayments"][$PMRQRow] == 'null')?'null':($rawdata["LongtermDebtProceeds"][$PMRQRow]+$rawdata["LongtermDebtPayments"][$PMRQRow])).",";
                $query .= (($rawdata["CapitalExpenditures"][$PMRQRow]=='null')?'null':(-$rawdata["CapitalExpenditures"][$PMRQRow])).",";
                $query .= (($rawdata["CashfromOperatingActivities"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null')?'null':($rawdata["CashfromOperatingActivities"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow])).",";
                $query .= (($rawdata["CFNetIncome"][$PMRQRow]=='null' && $rawdata["CFDepreciationAmortization"][$PMRQRow]=='null' && $rawdata["EmployeeCompensation"][$PMRQRow]=='null' && $rawdata["AdjustmentforSpecialCharges"][$PMRQRow]=='null' && $rawdata["DeferredIncomeTaxes"][$PMRQRow]=='null' && $rawdata["CapitalExpenditures"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentAssets"][$PMRQRow]=='null' && $rawdata["ChangeinCurrentLiabilities"][$PMRQRow]=='null')?'null':($rawdata["CFNetIncome"][$PMRQRow]+$rawdata["CFDepreciationAmortization"][$PMRQRow]+$rawdata["EmployeeCompensation"][$PMRQRow]+$rawdata["AdjustmentforSpecialCharges"][$PMRQRow]+$rawdata["DeferredIncomeTaxes"][$PMRQRow]+$rawdata["CapitalExpenditures"][$PMRQRow]+($rawdata["ChangeinCurrentAssets"][$PMRQRow]+$rawdata["ChangeinCurrentLiabilities"][$PMRQRow])));
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());
	} else {
                $query = "INSERT INTO `ttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
		$query .= (($rawdata["InterestIncome"][23]=='null'&&$rawdata["InterestIncome"][24]=='null'&&$rawdata["InterestIncome"][25]=='null'&&$rawdata["InterestIncome"][26]=='null')?'null':(toFloat($rawdata["InterestIncome"][23])+toFloat($rawdata["InterestIncome"][24])+toFloat($rawdata["InterestIncome"][25])+toFloat($rawdata["InterestIncome"][26]))).",";
		$query .= (($rawdata["InterestExpense"][23]=='null'&&$rawdata["InterestExpense"][24]=='null'&&$rawdata["InterestExpense"][25]=='null'&&$rawdata["InterestExpense"][26]=='null')?'null':(toFloat($rawdata["InterestExpense"][23])+toFloat($rawdata["InterestExpense"][24])+toFloat($rawdata["InterestExpense"][25])+toFloat($rawdata["InterestExpense"][26]))).",";
		$query .= (($rawdata["EPSBasic"][23]=='null'&&$rawdata["EPSBasic"][24]=='null'&&$rawdata["EPSBasic"][25]=='null'&&$rawdata["EPSBasic"][26]=='null')?'null':(toFloat($rawdata["EPSBasic"][23])+toFloat($rawdata["EPSBasic"][24])+toFloat($rawdata["EPSBasic"][25])+toFloat($rawdata["EPSBasic"][26]))).",";
		$query .= (($rawdata["EPSDiluted"][23]=='null'&&$rawdata["EPSDiluted"][24]=='null'&&$rawdata["EPSDiluted"][25]=='null'&&$rawdata["EPSDiluted"][26]=='null')?'null':(toFloat($rawdata["EPSDiluted"][23])+toFloat($rawdata["EPSDiluted"][24])+toFloat($rawdata["EPSDiluted"][25])+toFloat($rawdata["EPSDiluted"][26]))).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$MRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$MRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$MRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$MRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$MRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$MRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$MRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query." ".mysql_error());

                $query = "INSERT INTO `pttm_gf_data` (`ticker_id`, `InterestIncome`, `InterestExpense`, `EPSBasic`, `EPSDiluted`, `SharesOutstandingDiluted`, `InventoriesRawMaterialsComponents`, `InventoriesWorkInProcess`, `InventoriesInventoriesAdjustments`, `InventoriesFinishedGoods`, `InventoriesOther`, `TotalInventories`, `LandAndImprovements`, `BuildingsAndImprovements`, `MachineryFurnitureEquipment`, `ConstructionInProgress`, `GrossPropertyPlantandEquipment`, `SharesOutstandingBasic`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["InterestIncome"][19]=='null'&&$rawdata["InterestIncome"][20]=='null'&&$rawdata["InterestIncome"][21]=='null'&&$rawdata["InterestIncome"][22]=='null')?'null':(toFloat($rawdata["InterestIncome"][19])+toFloat($rawdata["InterestIncome"][20])+toFloat($rawdata["InterestIncome"][21])+toFloat($rawdata["InterestIncome"][22]))).",";
                $query .= (($rawdata["InterestExpense"][19]=='null'&&$rawdata["InterestExpense"][20]=='null'&&$rawdata["InterestExpense"][21]=='null'&&$rawdata["InterestExpense"][22]=='null')?'null':(toFloat($rawdata["InterestExpense"][19])+toFloat($rawdata["InterestExpense"][20])+toFloat($rawdata["InterestExpense"][21])+toFloat($rawdata["InterestExpense"][22]))).",";
                $query .= (($rawdata["EPSBasic"][19]=='null'&&$rawdata["EPSBasic"][20]=='null'&&$rawdata["EPSBasic"][21]=='null'&&$rawdata["EPSBasic"][22]=='null')?'null':(toFloat($rawdata["EPSBasic"][19])+toFloat($rawdata["EPSBasic"][20])+toFloat($rawdata["EPSBasic"][21])+toFloat($rawdata["EPSBasic"][22]))).",";
                $query .= (($rawdata["EPSDiluted"][19]=='null'&&$rawdata["EPSDiluted"][20]=='null'&&$rawdata["EPSDiluted"][21]=='null'&&$rawdata["EPSDiluted"][22]=='null')?'null':(toFloat($rawdata["EPSDiluted"][19])+toFloat($rawdata["EPSDiluted"][20])+toFloat($rawdata["EPSDiluted"][21])+toFloat($rawdata["EPSDiluted"][22]))).",";
                $query .= toFloat($rawdata["SharesOutstandingDiluted"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesRawMaterialsComponents"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesWorkInProcess"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesInventoriesAdjustments"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesFinishedGoods"][$PMRQRow]).",";
                $query .= toFloat($rawdata["InventoriesOther"][$PMRQRow]).",";
                $query .= toFloat($rawdata["TotalInventories"][$PMRQRow]).",";
                $query .= toFloat($rawdata["LandAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["BuildingsAndImprovements"][$PMRQRow]).",";
                $query .= toFloat($rawdata["MachineryFurnitureEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["ConstructionInProgress"][$PMRQRow]).",";
                $query .= toFloat($rawdata["GrossPropertyPlantandEquipment"][$PMRQRow]).",";
                $query .= toFloat($rawdata["SharesOutstandingBasic"][$PMRQRow]);
                $query .= ")";
                mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= (($rawdata["ChangeinCurrentAssets"][23]=='null'&&$rawdata["ChangeinCurrentAssets"][24]=='null'&&$rawdata["ChangeinCurrentAssets"][25]=='null'&&$rawdata["ChangeinCurrentAssets"][26]=='null')?'null':($rawdata["ChangeinCurrentAssets"][23]+$rawdata["ChangeinCurrentAssets"][24]+$rawdata["ChangeinCurrentAssets"][25]+$rawdata["ChangeinCurrentAssets"][26])).",";
        	$query .= (($rawdata["ChangeinCurrentLiabilities"][23]=='null'&&$rawdata["ChangeinCurrentLiabilities"][24]=='null'&&$rawdata["ChangeinCurrentLiabilities"][25]=='null'&&$rawdata["ChangeinCurrentLiabilities"][26]=='null')?'null':($rawdata["ChangeinCurrentLiabilities"][23]+$rawdata["ChangeinCurrentLiabilities"][24]+$rawdata["ChangeinCurrentLiabilities"][25]+$rawdata["ChangeinCurrentLiabilities"][26])).",";
        	$query .= (($rawdata["ChangeinDebtNet"][23]=='null'&&$rawdata["ChangeinDebtNet"][24]=='null'&&$rawdata["ChangeinDebtNet"][25]=='null'&&$rawdata["ChangeinDebtNet"][26]=='null')?'null':($rawdata["ChangeinDebtNet"][23]+$rawdata["ChangeinDebtNet"][24]+$rawdata["ChangeinDebtNet"][25]+$rawdata["ChangeinDebtNet"][26])).",";
        	$query .= (($rawdata["ChangeinDeferredRevenue"][23]=='null'&&$rawdata["ChangeinDeferredRevenue"][24]=='null'&&$rawdata["ChangeinDeferredRevenue"][25]=='null'&&$rawdata["ChangeinDeferredRevenue"][26]=='null')?'null':($rawdata["ChangeinDeferredRevenue"][23]+$rawdata["ChangeinDeferredRevenue"][24]+$rawdata["ChangeinDeferredRevenue"][25]+$rawdata["ChangeinDeferredRevenue"][26])).",";
        	$query .= (($rawdata["ChangeinEquityNet"][23]=='null'&&$rawdata["ChangeinEquityNet"][24]=='null'&&$rawdata["ChangeinEquityNet"][25]=='null'&&$rawdata["ChangeinEquityNet"][26]=='null')?'null':($rawdata["ChangeinEquityNet"][23]+$rawdata["ChangeinEquityNet"][24]+$rawdata["ChangeinEquityNet"][25]+$rawdata["ChangeinEquityNet"][26])).",";
        	$query .= (($rawdata["ChangeinIncomeTaxesPayable"][23]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][24]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][25]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][26]=='null')?'null':($rawdata["ChangeinIncomeTaxesPayable"][23]+$rawdata["ChangeinIncomeTaxesPayable"][24]+$rawdata["ChangeinIncomeTaxesPayable"][25]+$rawdata["ChangeinIncomeTaxesPayable"][26])).",";
        	$query .= (($rawdata["ChangeinInventories"][23]=='null'&&$rawdata["ChangeinInventories"][24]=='null'&&$rawdata["ChangeinInventories"][25]=='null'&&$rawdata["ChangeinInventories"][26]=='null')?'null':($rawdata["ChangeinInventories"][23]+$rawdata["ChangeinInventories"][24]+$rawdata["ChangeinInventories"][25]+$rawdata["ChangeinInventories"][26])).",";
        	$query .= (($rawdata["ChangeinOperatingAssetsLiabilities"][23]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][24]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][25]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][26]=='null')?'null':($rawdata["ChangeinOperatingAssetsLiabilities"][23]+$rawdata["ChangeinOperatingAssetsLiabilities"][24]+$rawdata["ChangeinOperatingAssetsLiabilities"][25]+$rawdata["ChangeinOperatingAssetsLiabilities"][26])).",";
        	$query .= (($rawdata["ChangeinOtherAssets"][23]=='null'&&$rawdata["ChangeinOtherAssets"][24]=='null'&&$rawdata["ChangeinOtherAssets"][25]=='null'&&$rawdata["ChangeinOtherAssets"][26]=='null')?'null':($rawdata["ChangeinOtherAssets"][23]+$rawdata["ChangeinOtherAssets"][24]+$rawdata["ChangeinOtherAssets"][25]+$rawdata["ChangeinOtherAssets"][26])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentAssets"][23]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][24]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][25]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][26]=='null')?'null':($rawdata["ChangeinOtherCurrentAssets"][23]+$rawdata["ChangeinOtherCurrentAssets"][24]+$rawdata["ChangeinOtherCurrentAssets"][25]+$rawdata["ChangeinOtherCurrentAssets"][26])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentLiabilities"][23]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][24]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][25]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][26]=='null')?'null':($rawdata["ChangeinOtherCurrentLiabilities"][23]+$rawdata["ChangeinOtherCurrentLiabilities"][24]+$rawdata["ChangeinOtherCurrentLiabilities"][25]+$rawdata["ChangeinOtherCurrentLiabilities"][26])).",";
        	$query .= (($rawdata["ChangeinOtherLiabilities"][23]=='null'&&$rawdata["ChangeinOtherLiabilities"][24]=='null'&&$rawdata["ChangeinOtherLiabilities"][25]=='null'&&$rawdata["ChangeinOtherLiabilities"][26]=='null')?'null':($rawdata["ChangeinOtherLiabilities"][23]+$rawdata["ChangeinOtherLiabilities"][24]+$rawdata["ChangeinOtherLiabilities"][25]+$rawdata["ChangeinOtherLiabilities"][26])).",";
        	$query .= (($rawdata["ChangeinPrepaidExpenses"][23]=='null'&&$rawdata["ChangeinPrepaidExpenses"][24]=='null'&&$rawdata["ChangeinPrepaidExpenses"][25]=='null'&&$rawdata["ChangeinPrepaidExpenses"][26]=='null')?'null':($rawdata["ChangeinPrepaidExpenses"][23]+$rawdata["ChangeinPrepaidExpenses"][24]+$rawdata["ChangeinPrepaidExpenses"][25]+$rawdata["ChangeinPrepaidExpenses"][26])).",";
        	$query .= (($rawdata["DividendsPaid"][23]=='null'&&$rawdata["DividendsPaid"][24]=='null'&&$rawdata["DividendsPaid"][25]=='null'&&$rawdata["DividendsPaid"][26]=='null')?'null':($rawdata["DividendsPaid"][23]+$rawdata["DividendsPaid"][24]+$rawdata["DividendsPaid"][25]+$rawdata["DividendsPaid"][26])).",";
        	$query .= (($rawdata["EffectofExchangeRateonCash"][23]=='null'&&$rawdata["EffectofExchangeRateonCash"][24]=='null'&&$rawdata["EffectofExchangeRateonCash"][25]=='null'&&$rawdata["EffectofExchangeRateonCash"][26]=='null')?'null':($rawdata["EffectofExchangeRateonCash"][23]+$rawdata["EffectofExchangeRateonCash"][24]+$rawdata["EffectofExchangeRateonCash"][25]+$rawdata["EffectofExchangeRateonCash"][26])).",";
        	$query .= (($rawdata["EmployeeCompensation"][23]=='null'&&$rawdata["EmployeeCompensation"][24]=='null'&&$rawdata["EmployeeCompensation"][25]=='null'&&$rawdata["EmployeeCompensation"][26]=='null')?'null':($rawdata["EmployeeCompensation"][23]+$rawdata["EmployeeCompensation"][24]+$rawdata["EmployeeCompensation"][25]+$rawdata["EmployeeCompensation"][26])).",";
        	$query .= (($rawdata["AcquisitionSaleofBusinessNet"][23]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][24]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][25]=='null'&&$rawdata["AcquisitionSaleofBusinessNet"][26]=='null')?'null':($rawdata["AcquisitionSaleofBusinessNet"][23]+$rawdata["AcquisitionSaleofBusinessNet"][24]+$rawdata["AcquisitionSaleofBusinessNet"][25]+$rawdata["AcquisitionSaleofBusinessNet"][26])).",";
        	$query .= (($rawdata["AdjustmentforEquityEarnings"][23]=='null'&&$rawdata["AdjustmentforEquityEarnings"][24]=='null'&&$rawdata["AdjustmentforEquityEarnings"][25]=='null'&&$rawdata["AdjustmentforEquityEarnings"][26]=='null')?'null':($rawdata["AdjustmentforEquityEarnings"][23]+$rawdata["AdjustmentforEquityEarnings"][24]+$rawdata["AdjustmentforEquityEarnings"][25]+$rawdata["AdjustmentforEquityEarnings"][26])).",";
        	$query .= (($rawdata["AdjustmentforMinorityInterest"][23]=='null'&&$rawdata["AdjustmentforMinorityInterest"][24]=='null'&&$rawdata["AdjustmentforMinorityInterest"][25]=='null'&&$rawdata["AdjustmentforMinorityInterest"][26]=='null')?'null':($rawdata["AdjustmentforMinorityInterest"][23]+$rawdata["AdjustmentforMinorityInterest"][24]+$rawdata["AdjustmentforMinorityInterest"][25]+$rawdata["AdjustmentforMinorityInterest"][26])).",";
        	$query .= (($rawdata["AdjustmentforSpecialCharges"][23]=='null'&&$rawdata["AdjustmentforSpecialCharges"][24]=='null'&&$rawdata["AdjustmentforSpecialCharges"][25]=='null'&&$rawdata["AdjustmentforSpecialCharges"][26]=='null')?'null':($rawdata["AdjustmentforSpecialCharges"][23]+$rawdata["AdjustmentforSpecialCharges"][24]+$rawdata["AdjustmentforSpecialCharges"][25]+$rawdata["AdjustmentforSpecialCharges"][26])).",";
        	$query .= (($rawdata["CapitalExpenditures"][23]=='null'&&$rawdata["CapitalExpenditures"][24]=='null'&&$rawdata["CapitalExpenditures"][25]=='null'&&$rawdata["CapitalExpenditures"][26]=='null')?'null':($rawdata["CapitalExpenditures"][23]+$rawdata["CapitalExpenditures"][24]+$rawdata["CapitalExpenditures"][25]+$rawdata["CapitalExpenditures"][26])).",";
        	$query .= (($rawdata["CashfromDiscontinuedOperations"][23]=='null'&&$rawdata["CashfromDiscontinuedOperations"][24]=='null'&&$rawdata["CashfromDiscontinuedOperations"][25]=='null'&&$rawdata["CashfromDiscontinuedOperations"][26]=='null')?'null':($rawdata["CashfromDiscontinuedOperations"][23]+$rawdata["CashfromDiscontinuedOperations"][24]+$rawdata["CashfromDiscontinuedOperations"][25]+$rawdata["CashfromDiscontinuedOperations"][26])).",";
        	$query .= (($rawdata["CashfromFinancingActivities"][23]=='null'&&$rawdata["CashfromFinancingActivities"][24]=='null'&&$rawdata["CashfromFinancingActivities"][25]=='null'&&$rawdata["CashfromFinancingActivities"][26]=='null')?'null':($rawdata["CashfromFinancingActivities"][23]+$rawdata["CashfromFinancingActivities"][24]+$rawdata["CashfromFinancingActivities"][25]+$rawdata["CashfromFinancingActivities"][26])).",";
        	$query .= (($rawdata["CashfromInvestingActivities"][23]=='null'&&$rawdata["CashfromInvestingActivities"][24]=='null'&&$rawdata["CashfromInvestingActivities"][25]=='null'&&$rawdata["CashfromInvestingActivities"][26]=='null')?'null':($rawdata["CashfromInvestingActivities"][23]+$rawdata["CashfromInvestingActivities"][24]+$rawdata["CashfromInvestingActivities"][25]+$rawdata["CashfromInvestingActivities"][26])).",";
        	$query .= (($rawdata["CashfromOperatingActivities"][23]=='null'&&$rawdata["CashfromOperatingActivities"][24]=='null'&&$rawdata["CashfromOperatingActivities"][25]=='null'&&$rawdata["CashfromOperatingActivities"][26]=='null')?'null':($rawdata["CashfromOperatingActivities"][23]+$rawdata["CashfromOperatingActivities"][24]+$rawdata["CashfromOperatingActivities"][25]+$rawdata["CashfromOperatingActivities"][26])).",";
        	$query .= (($rawdata["CFDepreciationAmortization"][23]=='null'&&$rawdata["CFDepreciationAmortization"][24]=='null'&&$rawdata["CFDepreciationAmortization"][25]=='null'&&$rawdata["CFDepreciationAmortization"][26]=='null')?'null':($rawdata["CFDepreciationAmortization"][23]+$rawdata["CFDepreciationAmortization"][24]+$rawdata["CFDepreciationAmortization"][25]+$rawdata["CFDepreciationAmortization"][26])).",";
        	$query .= (($rawdata["DeferredIncomeTaxes"][23]=='null'&&$rawdata["DeferredIncomeTaxes"][24]=='null'&&$rawdata["DeferredIncomeTaxes"][25]=='null'&&$rawdata["DeferredIncomeTaxes"][26]=='null')?'null':($rawdata["DeferredIncomeTaxes"][23]+$rawdata["DeferredIncomeTaxes"][24]+$rawdata["DeferredIncomeTaxes"][25]+$rawdata["DeferredIncomeTaxes"][26])).",";
        	$query .= (($rawdata["ChangeinAccountsPayableAccruedExpenses"][23]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][24]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][25]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][26]=='null')?'null':($rawdata["ChangeinAccountsPayableAccruedExpenses"][23]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][24]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][25]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][26])).",";
        	$query .= (($rawdata["ChangeinAccountsReceivable"][23]=='null'&&$rawdata["ChangeinAccountsReceivable"][24]=='null'&&$rawdata["ChangeinAccountsReceivable"][25]=='null'&&$rawdata["ChangeinAccountsReceivable"][26]=='null')?'null':($rawdata["ChangeinAccountsReceivable"][23]+$rawdata["ChangeinAccountsReceivable"][24]+$rawdata["ChangeinAccountsReceivable"][25]+$rawdata["ChangeinAccountsReceivable"][26])).",";
        	$query .= (($rawdata["InvestmentChangesNet"][23]=='null'&&$rawdata["InvestmentChangesNet"][24]=='null'&&$rawdata["InvestmentChangesNet"][25]=='null'&&$rawdata["InvestmentChangesNet"][26]=='null')?'null':($rawdata["InvestmentChangesNet"][23]+$rawdata["InvestmentChangesNet"][24]+$rawdata["InvestmentChangesNet"][25]+$rawdata["InvestmentChangesNet"][26])).",";
        	$query .= (($rawdata["NetChangeinCash"][23]=='null'&&$rawdata["NetChangeinCash"][24]=='null'&&$rawdata["NetChangeinCash"][25]=='null'&&$rawdata["NetChangeinCash"][26]=='null')?'null':($rawdata["NetChangeinCash"][23]+$rawdata["NetChangeinCash"][24]+$rawdata["NetChangeinCash"][25]+$rawdata["NetChangeinCash"][26])).",";
        	$query .= (($rawdata["OtherAdjustments"][23]=='null'&&$rawdata["OtherAdjustments"][24]=='null'&&$rawdata["OtherAdjustments"][25]=='null'&&$rawdata["OtherAdjustments"][26]=='null')?'null':($rawdata["OtherAdjustments"][23]+$rawdata["OtherAdjustments"][24]+$rawdata["OtherAdjustments"][25]+$rawdata["OtherAdjustments"][26])).",";
        	$query .= (($rawdata["OtherAssetLiabilityChangesNet"][23]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][24]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][25]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][26]=='null')?'null':($rawdata["OtherAssetLiabilityChangesNet"][23]+$rawdata["OtherAssetLiabilityChangesNet"][24]+$rawdata["OtherAssetLiabilityChangesNet"][25]+$rawdata["OtherAssetLiabilityChangesNet"][26])).",";
        	$query .= (($rawdata["OtherFinancingActivitiesNet"][23]=='null'&&$rawdata["OtherFinancingActivitiesNet"][24]=='null'&&$rawdata["OtherFinancingActivitiesNet"][25]=='null'&&$rawdata["OtherFinancingActivitiesNet"][26]=='null')?'null':($rawdata["OtherFinancingActivitiesNet"][23]+$rawdata["OtherFinancingActivitiesNet"][24]+$rawdata["OtherFinancingActivitiesNet"][25]+$rawdata["OtherFinancingActivitiesNet"][26])).",";
        	$query .= (($rawdata["OtherInvestingActivities"][23]=='null'&&$rawdata["OtherInvestingActivities"][24]=='null'&&$rawdata["OtherInvestingActivities"][25]=='null'&&$rawdata["OtherInvestingActivities"][26]=='null')?'null':($rawdata["OtherInvestingActivities"][23]+$rawdata["OtherInvestingActivities"][24]+$rawdata["OtherInvestingActivities"][25]+$rawdata["OtherInvestingActivities"][26])).",";
        	$query .= (($rawdata["RealizedGainsLosses"][23]=='null'&&$rawdata["RealizedGainsLosses"][24]=='null'&&$rawdata["RealizedGainsLosses"][25]=='null'&&$rawdata["RealizedGainsLosses"][26]=='null')?'null':($rawdata["RealizedGainsLosses"][23]+$rawdata["RealizedGainsLosses"][24]+$rawdata["RealizedGainsLosses"][25]+$rawdata["RealizedGainsLosses"][26])).",";
        	$query .= (($rawdata["SaleofPropertyPlantEquipment"][23]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][24]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][25]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][26]=='null')?'null':($rawdata["SaleofPropertyPlantEquipment"][23]+$rawdata["SaleofPropertyPlantEquipment"][24]+$rawdata["SaleofPropertyPlantEquipment"][25]+$rawdata["SaleofPropertyPlantEquipment"][26])).",";
        	$query .= (($rawdata["StockOptionTaxBenefits"][23]=='null'&&$rawdata["StockOptionTaxBenefits"][24]=='null'&&$rawdata["StockOptionTaxBenefits"][25]=='null'&&$rawdata["StockOptionTaxBenefits"][26]=='null')?'null':($rawdata["StockOptionTaxBenefits"][23]+$rawdata["StockOptionTaxBenefits"][24]+$rawdata["StockOptionTaxBenefits"][25]+$rawdata["StockOptionTaxBenefits"][26])).",";
        	$query .= (($rawdata["TotalAdjustments"][23]=='null'&&$rawdata["TotalAdjustments"][24]=='null'&&$rawdata["TotalAdjustments"][25]=='null'&&$rawdata["TotalAdjustments"][26]=='null')?'null':($rawdata["TotalAdjustments"][23]+$rawdata["TotalAdjustments"][24]+$rawdata["TotalAdjustments"][25]+$rawdata["TotalAdjustments"][26]));
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());
		
		$query = "INSERT INTO `pttm_cashflowconsolidated` (`ticker_id`, `ChangeinCurrentAssets`, `ChangeinCurrentLiabilities`, `ChangeinDebtNet`, `ChangeinDeferredRevenue`, `ChangeinEquityNet`, `ChangeinIncomeTaxesPayable`, `ChangeinInventories`, `ChangeinOperatingAssetsLiabilities`, `ChangeinOtherAssets`, `ChangeinOtherCurrentAssets`, `ChangeinOtherCurrentLiabilities`, `ChangeinOtherLiabilities`, `ChangeinPrepaidExpenses`, `DividendsPaid`, `EffectofExchangeRateonCash`, `EmployeeCompensation`, `AcquisitionSaleofBusinessNet`, `AdjustmentforEquityEarnings`, `AdjustmentforMinorityInterest`, `AdjustmentforSpecialCharges`, `CapitalExpenditures`, `CashfromDiscontinuedOperations`, `CashfromFinancingActivities`, `CashfromInvestingActivities`, `CashfromOperatingActivities`, `CFDepreciationAmortization`, `DeferredIncomeTaxes`, `ChangeinAccountsPayableAccruedExpenses`, `ChangeinAccountsReceivable`, `InvestmentChangesNet`, `NetChangeinCash`, `OtherAdjustments`, `OtherAssetLiabilityChangesNet`, `OtherFinancingActivitiesNet`, `OtherInvestingActivities`, `RealizedGainsLosses`, `SaleofPropertyPlantEquipment`, `StockOptionTaxBenefits`, `TotalAdjustments`) VALUES (";
	       	$query .= "'".$dates->ticker_id."',";
        	$query .= (($rawdata["ChangeinCurrentAssets"][19]=='null'&&$rawdata["ChangeinCurrentAssets"][20]=='null'&&$rawdata["ChangeinCurrentAssets"][21]=='null'&&$rawdata["ChangeinCurrentAssets"][22]=='null')?'null':($rawdata["ChangeinCurrentAssets"][19]+$rawdata["ChangeinCurrentAssets"][20]+$rawdata["ChangeinCurrentAssets"][21]+$rawdata["ChangeinCurrentAssets"][22])).",";
        	$query .= (($rawdata["ChangeinCurrentLiabilities"][19]=='null'&&$rawdata["ChangeinCurrentLiabilities"][20]=='null'&&$rawdata["ChangeinCurrentLiabilities"][21]=='null'&&$rawdata["ChangeinCurrentLiabilities"][22]=='null')?'null':($rawdata["ChangeinCurrentLiabilities"][19]+$rawdata["ChangeinCurrentLiabilities"][20]+$rawdata["ChangeinCurrentLiabilities"][21]+$rawdata["ChangeinCurrentLiabilities"][22])).",";
        	$query .= (($rawdata["ChangeinDebtNet"][19]=='null'&&$rawdata["ChangeinDebtNet"][20]=='null'&&$rawdata["ChangeinDebtNet"][21]=='null'&&$rawdata["ChangeinDebtNet"][22]=='null')?'null':($rawdata["ChangeinDebtNet"][19]+$rawdata["ChangeinDebtNet"][20]+$rawdata["ChangeinDebtNet"][21]+$rawdata["ChangeinDebtNet"][22])).",";
        	$query .= (($rawdata["ChangeinDeferredRevenue"][19]=='null'&&$rawdata["ChangeinDeferredRevenue"][20]=='null'&&$rawdata["ChangeinDeferredRevenue"][21]=='null'&&$rawdata["ChangeinDeferredRevenue"][22]=='null')?'null':($rawdata["ChangeinDeferredRevenue"][19]+$rawdata["ChangeinDeferredRevenue"][20]+$rawdata["ChangeinDeferredRevenue"][21]+$rawdata["ChangeinDeferredRevenue"][22])).",";
        	$query .= (($rawdata["ChangeinEquityNet"][19]=='null'&&$rawdata["ChangeinEquityNet"][20]=='null'&&$rawdata["ChangeinEquityNet"][21]=='null'&&$rawdata["ChangeinEquityNet"][22]=='null')?'null':($rawdata["ChangeinEquityNet"][19]+$rawdata["ChangeinEquityNet"][20]+$rawdata["ChangeinEquityNet"][21]+$rawdata["ChangeinEquityNet"][22])).",";
        	$query .= (($rawdata["ChangeinIncomeTaxesPayable"][19]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][20]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][21]=='null'&&$rawdata["ChangeinIncomeTaxesPayable"][22]=='null')?'null':($rawdata["ChangeinIncomeTaxesPayable"][19]+$rawdata["ChangeinIncomeTaxesPayable"][20]+$rawdata["ChangeinIncomeTaxesPayable"][21]+$rawdata["ChangeinIncomeTaxesPayable"][22])).",";
        	$query .= (($rawdata["ChangeinInventories"][19]=='null'&&$rawdata["ChangeinInventories"][20]=='null'&&$rawdata["ChangeinInventories"][21]=='null'&&$rawdata["ChangeinInventories"][22]=='null')?'null':($rawdata["ChangeinInventories"][19]+$rawdata["ChangeinInventories"][20]+$rawdata["ChangeinInventories"][21]+$rawdata["ChangeinInventories"][22])).",";
        	$query .= (($rawdata["ChangeinOperatingAssetsLiabilities"][19]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][20]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][21]=='null'&&$rawdata["ChangeinOperatingAssetsLiabilities"][22]=='null')?'null':($rawdata["ChangeinOperatingAssetsLiabilities"][19]+$rawdata["ChangeinOperatingAssetsLiabilities"][20]+$rawdata["ChangeinOperatingAssetsLiabilities"][21]+$rawdata["ChangeinOperatingAssetsLiabilities"][22])).",";
        	$query .= (($rawdata["ChangeinOtherAssets"][19]=='null'&&$rawdata["ChangeinOtherAssets"][20]=='null'&&$rawdata["ChangeinOtherAssets"][21]=='null'&&$rawdata["ChangeinOtherAssets"][22]=='null')?'null':($rawdata["ChangeinOtherAssets"][19]+$rawdata["ChangeinOtherAssets"][20]+$rawdata["ChangeinOtherAssets"][21]+$rawdata["ChangeinOtherAssets"][22])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentAssets"][19]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][20]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][21]=='null'&&$rawdata["ChangeinOtherCurrentAssets"][22]=='null')?'null':($rawdata["ChangeinOtherCurrentAssets"][19]+$rawdata["ChangeinOtherCurrentAssets"][20]+$rawdata["ChangeinOtherCurrentAssets"][21]+$rawdata["ChangeinOtherCurrentAssets"][22])).",";
        	$query .= (($rawdata["ChangeinOtherCurrentLiabilities"][19]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][20]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][21]=='null'&&$rawdata["ChangeinOtherCurrentLiabilities"][22]=='null')?'null':($rawdata["ChangeinOtherCurrentLiabilities"][19]+$rawdata["ChangeinOtherCurrentLiabilities"][20]+$rawdata["ChangeinOtherCurrentLiabilities"][21]+$rawdata["ChangeinOtherCurrentLiabilities"][22])).",";
        	$query .= (($rawdata["ChangeinOtherLiabilities"][19]=='null'&&$rawdata["ChangeinOtherLiabilities"][20]=='null'&&$rawdata["ChangeinOtherLiabilities"][21]=='null'&&$rawdata["ChangeinOtherLiabilities"][22]=='null')?'null':($rawdata["ChangeinOtherLiabilities"][19]+$rawdata["ChangeinOtherLiabilities"][20]+$rawdata["ChangeinOtherLiabilities"][21]+$rawdata["ChangeinOtherLiabilities"][22])).",";
        	$query .= (($rawdata["ChangeinPrepaidExpenses"][19]=='null'&&$rawdata["ChangeinPrepaidExpenses"][20]=='null'&&$rawdata["ChangeinPrepaidExpenses"][21]=='null'&&$rawdata["ChangeinPrepaidExpenses"][22]=='null')?'null':($rawdata["ChangeinPrepaidExpenses"][19]+$rawdata["ChangeinPrepaidExpenses"][20]+$rawdata["ChangeinPrepaidExpenses"][21]+$rawdata["ChangeinPrepaidExpenses"][22])).",";
        	$query .= (($rawdata["DividendsPaid"][19]=='null'&&$rawdata["DividendsPaid"][20]=='null'&&$rawdata["DividendsPaid"][21]=='null'&&$rawdata["DividendsPaid"][22]=='null')?'null':($rawdata["DividendsPaid"][19]+$rawdata["DividendsPaid"][20]+$rawdata["DividendsPaid"][21]+$rawdata["DividendsPaid"][22])).",";
        	$query .= (($rawdata["EffectofExchangeRateonCash"][19]=='null'&&$rawdata["EffectofExchangeRateonCash"][20]=='null'&&$rawdata["EffectofExchangeRateonCash"][21]=='null'&&$rawdata["EffectofExchangeRateonCash"][22]=='null')?'null':($rawdata["EffectofExchangeRateonCash"][19]+$rawdata["EffectofExchangeRateonCash"][20]+$rawdata["EffectofExchangeRateonCash"][21]+$rawdata["EffectofExchangeRateonCash"][22])).",";
        	$query .= (($rawdata["EmployeeCompensation"][19]=='null'&&$rawdata["EmployeeCompensation"][20]=='null'&&$rawdata["EmployeeCompensation"][21]=='null'&&$rawdata["EmployeeCompensation"][22]=='null')?'null':($rawdata["EmployeeCompensation"][19]+$rawdata["EmployeeCompensation"][20]+$rawdata["EmployeeCompensation"][21]+$rawdata["EmployeeCompensation"][22])).",";
        	$query .= (($rawdata["AcquisitionSaleofBusinessNet"][19]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][20]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][21]='null'&&$rawdata["AcquisitionSaleofBusinessNet"][22]='null')?'null':($rawdata["AcquisitionSaleofBusinessNet"][19]+$rawdata["AcquisitionSaleofBusinessNet"][20]+$rawdata["AcquisitionSaleofBusinessNet"][21]+$rawdata["AcquisitionSaleofBusinessNet"][22])).",";
        	$query .= (($rawdata["AdjustmentforEquityEarnings"][19]=='null'&&$rawdata["AdjustmentforEquityEarnings"][20]=='null'&&$rawdata["AdjustmentforEquityEarnings"][21]=='null'&&$rawdata["AdjustmentforEquityEarnings"][22]=='null')?'null':($rawdata["AdjustmentforEquityEarnings"][19]+$rawdata["AdjustmentforEquityEarnings"][20]+$rawdata["AdjustmentforEquityEarnings"][21]+$rawdata["AdjustmentforEquityEarnings"][22])).",";
        	$query .= (($rawdata["AdjustmentforMinorityInterest"][19]=='null'&&$rawdata["AdjustmentforMinorityInterest"][20]=='null'&&$rawdata["AdjustmentforMinorityInterest"][21]=='null'&&$rawdata["AdjustmentforMinorityInterest"][22]=='null')?'null':($rawdata["AdjustmentforMinorityInterest"][19]+$rawdata["AdjustmentforMinorityInterest"][20]+$rawdata["AdjustmentforMinorityInterest"][21]+$rawdata["AdjustmentforMinorityInterest"][22])).",";
        	$query .= (($rawdata["AdjustmentforSpecialCharges"][19]=='null'&&$rawdata["AdjustmentforSpecialCharges"][20]=='null'&&$rawdata["AdjustmentforSpecialCharges"][21]=='null'&&$rawdata["AdjustmentforSpecialCharges"][22]=='null')?'null':($rawdata["AdjustmentforSpecialCharges"][19]+$rawdata["AdjustmentforSpecialCharges"][20]+$rawdata["AdjustmentforSpecialCharges"][21]+$rawdata["AdjustmentforSpecialCharges"][22])).",";
        	$query .= (($rawdata["CapitalExpenditures"][19]=='null'&&$rawdata["CapitalExpenditures"][20]=='null'&&$rawdata["CapitalExpenditures"][21]=='null'&&$rawdata["CapitalExpenditures"][22]=='null')?'null':($rawdata["CapitalExpenditures"][19]+$rawdata["CapitalExpenditures"][20]+$rawdata["CapitalExpenditures"][21]+$rawdata["CapitalExpenditures"][22])).",";
        	$query .= (($rawdata["CashfromDiscontinuedOperations"][19]=='null'&&$rawdata["CashfromDiscontinuedOperations"][20]=='null'&&$rawdata["CashfromDiscontinuedOperations"][21]=='null'&&$rawdata["CashfromDiscontinuedOperations"][22]=='null')?'null':($rawdata["CashfromDiscontinuedOperations"][19]+$rawdata["CashfromDiscontinuedOperations"][20]+$rawdata["CashfromDiscontinuedOperations"][21]+$rawdata["CashfromDiscontinuedOperations"][22])).",";
        	$query .= (($rawdata["CashfromFinancingActivities"][19]=='null'&&$rawdata["CashfromFinancingActivities"][20]=='null'&&$rawdata["CashfromFinancingActivities"][21]=='null'&&$rawdata["CashfromFinancingActivities"][22]=='null')?'null':($rawdata["CashfromFinancingActivities"][19]+$rawdata["CashfromFinancingActivities"][20]+$rawdata["CashfromFinancingActivities"][21]+$rawdata["CashfromFinancingActivities"][22])).",";
        	$query .= (($rawdata["CashfromInvestingActivities"][19]=='null'&&$rawdata["CashfromInvestingActivities"][20]=='null'&&$rawdata["CashfromInvestingActivities"][21]=='null'&&$rawdata["CashfromInvestingActivities"][22]=='null')?'null':($rawdata["CashfromInvestingActivities"][19]+$rawdata["CashfromInvestingActivities"][20]+$rawdata["CashfromInvestingActivities"][21]+$rawdata["CashfromInvestingActivities"][22])).",";
        	$query .= (($rawdata["CashfromOperatingActivities"][19]=='null'&&$rawdata["CashfromOperatingActivities"][20]=='null'&&$rawdata["CashfromOperatingActivities"][21]=='null'&&$rawdata["CashfromOperatingActivities"][22]=='null')?'null':($rawdata["CashfromOperatingActivities"][19]+$rawdata["CashfromOperatingActivities"][20]+$rawdata["CashfromOperatingActivities"][21]+$rawdata["CashfromOperatingActivities"][22])).",";
        	$query .= (($rawdata["CFDepreciationAmortization"][19]=='null'&&$rawdata["CFDepreciationAmortization"][20]=='null'&&$rawdata["CFDepreciationAmortization"][21]=='null'&&$rawdata["CFDepreciationAmortization"][22]=='null')?'null':($rawdata["CFDepreciationAmortization"][19]+$rawdata["CFDepreciationAmortization"][20]+$rawdata["CFDepreciationAmortization"][21]+$rawdata["CFDepreciationAmortization"][22])).",";
        	$query .= (($rawdata["DeferredIncomeTaxes"][19]=='null'&&$rawdata["DeferredIncomeTaxes"][20]=='null'&&$rawdata["DeferredIncomeTaxes"][21]=='null'&&$rawdata["DeferredIncomeTaxes"][22]=='null')?'null':($rawdata["DeferredIncomeTaxes"][19]+$rawdata["DeferredIncomeTaxes"][20]+$rawdata["DeferredIncomeTaxes"][21]+$rawdata["DeferredIncomeTaxes"][22])).",";
        	$query .= (($rawdata["ChangeinAccountsPayableAccruedExpenses"][19]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][20]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][21]=='null'&&$rawdata["ChangeinAccountsPayableAccruedExpenses"][22]=='null')?'null':($rawdata["ChangeinAccountsPayableAccruedExpenses"][19]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][20]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][21]+$rawdata["ChangeinAccountsPayableAccruedExpenses"][22])).",";
        	$query .= (($rawdata["ChangeinAccountsReceivable"][19]=='null'&&$rawdata["ChangeinAccountsReceivable"][20]=='null'&&$rawdata["ChangeinAccountsReceivable"][21]=='null'&&$rawdata["ChangeinAccountsReceivable"][22]=='null')?'null':($rawdata["ChangeinAccountsReceivable"][19]+$rawdata["ChangeinAccountsReceivable"][20]+$rawdata["ChangeinAccountsReceivable"][21]+$rawdata["ChangeinAccountsReceivable"][22])).",";
        	$query .= (($rawdata["InvestmentChangesNet"][19]=='null'&&$rawdata["InvestmentChangesNet"][20]=='null'&&$rawdata["InvestmentChangesNet"][21]=='null'&&$rawdata["InvestmentChangesNet"][22]=='null')?'null':($rawdata["InvestmentChangesNet"][19]+$rawdata["InvestmentChangesNet"][20]+$rawdata["InvestmentChangesNet"][21]+$rawdata["InvestmentChangesNet"][22])).",";
        	$query .= (($rawdata["NetChangeinCash"][19]=='null'&&$rawdata["NetChangeinCash"][20]=='null'&&$rawdata["NetChangeinCash"][21]=='null'&&$rawdata["NetChangeinCash"][22]=='null')?'null':($rawdata["NetChangeinCash"][19]+$rawdata["NetChangeinCash"][20]+$rawdata["NetChangeinCash"][21]+$rawdata["NetChangeinCash"][22])).",";
        	$query .= (($rawdata["OtherAdjustments"][19]=='null'&&$rawdata["OtherAdjustments"][20]=='null'&&$rawdata["OtherAdjustments"][21]=='null'&&$rawdata["OtherAdjustments"][22]=='null')?'null':($rawdata["OtherAdjustments"][19]+$rawdata["OtherAdjustments"][20]+$rawdata["OtherAdjustments"][21]+$rawdata["OtherAdjustments"][22])).",";
        	$query .= (($rawdata["OtherAssetLiabilityChangesNet"][19]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][20]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][21]=='null'&&$rawdata["OtherAssetLiabilityChangesNet"][22]=='null')?'null':($rawdata["OtherAssetLiabilityChangesNet"][19]+$rawdata["OtherAssetLiabilityChangesNet"][20]+$rawdata["OtherAssetLiabilityChangesNet"][21]+$rawdata["OtherAssetLiabilityChangesNet"][22])).",";
        	$query .= (($rawdata["OtherFinancingActivitiesNet"][19]=='null'&&$rawdata["OtherFinancingActivitiesNet"][20]=='null'&&$rawdata["OtherFinancingActivitiesNet"][21]=='null'&&$rawdata["OtherFinancingActivitiesNet"][22]=='null')?'null':($rawdata["OtherFinancingActivitiesNet"][19]+$rawdata["OtherFinancingActivitiesNet"][20]+$rawdata["OtherFinancingActivitiesNet"][21]+$rawdata["OtherFinancingActivitiesNet"][22])).",";
        	$query .= (($rawdata["OtherInvestingActivities"][19]=='null'&&$rawdata["OtherInvestingActivities"][20]=='null'&&$rawdata["OtherInvestingActivities"][21]=='null'&&$rawdata["OtherInvestingActivities"][22]=='null')?'null':($rawdata["OtherInvestingActivities"][19]+$rawdata["OtherInvestingActivities"][20]+$rawdata["OtherInvestingActivities"][21]+$rawdata["OtherInvestingActivities"][22])).",";
        	$query .= (($rawdata["RealizedGainsLosses"][19]=='null'&&$rawdata["RealizedGainsLosses"][20]=='null'&&$rawdata["RealizedGainsLosses"][21]=='null'&&$rawdata["RealizedGainsLosses"][22]=='null')?'null':($rawdata["RealizedGainsLosses"][19]+$rawdata["RealizedGainsLosses"][20]+$rawdata["RealizedGainsLosses"][21]+$rawdata["RealizedGainsLosses"][22])).",";
        	$query .= (($rawdata["SaleofPropertyPlantEquipment"][19]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][20]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][21]=='null'&&$rawdata["SaleofPropertyPlantEquipment"][22]=='null')?'null':($rawdata["SaleofPropertyPlantEquipment"][19]+$rawdata["SaleofPropertyPlantEquipment"][20]+$rawdata["SaleofPropertyPlantEquipment"][21]+$rawdata["SaleofPropertyPlantEquipment"][22])).",";
        	$query .= (($rawdata["StockOptionTaxBenefits"][19]=='null'&&$rawdata["StockOptionTaxBenefits"][20]=='null'&&$rawdata["StockOptionTaxBenefits"][21]=='null'&&$rawdata["StockOptionTaxBenefits"][22]=='null')?'null':($rawdata["StockOptionTaxBenefits"][19]+$rawdata["StockOptionTaxBenefits"][20]+$rawdata["StockOptionTaxBenefits"][21]+$rawdata["StockOptionTaxBenefits"][22])).",";
        	$query .= (($rawdata["TotalAdjustments"][19]=='null'&&$rawdata["TotalAdjustments"][20]=='null'&&$rawdata["TotalAdjustments"][21]=='null'&&$rawdata["TotalAdjustments"][22]=='null')?'null':($rawdata["TotalAdjustments"][19]+$rawdata["TotalAdjustments"][20]+$rawdata["TotalAdjustments"][21]+$rawdata["TotalAdjustments"][22]));
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["ChangeinLongtermDebtNet"][23]=='null'&&$rawdata["ChangeinLongtermDebtNet"][24]=='null'&&$rawdata["ChangeinLongtermDebtNet"][25]=='null'&&$rawdata["ChangeinLongtermDebtNet"][26]=='null')?'null':($rawdata["ChangeinLongtermDebtNet"][23]+$rawdata["ChangeinLongtermDebtNet"][24]+$rawdata["ChangeinLongtermDebtNet"][25]+$rawdata["ChangeinLongtermDebtNet"][26])).",";
                $query .= (($rawdata["ChangeinShorttermBorrowingsNet"][23]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][24]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][25]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][26]=='null')?'null':($rawdata["ChangeinShorttermBorrowingsNet"][23]+$rawdata["ChangeinShorttermBorrowingsNet"][24]+$rawdata["ChangeinShorttermBorrowingsNet"][25]+$rawdata["ChangeinShorttermBorrowingsNet"][26])).",";
                $query .= (($rawdata["CashandCashEquivalentsBeginningofYear"][23]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][24]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][25]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][26]=='null')?'null':($rawdata["CashandCashEquivalentsBeginningofYear"][23]+$rawdata["CashandCashEquivalentsBeginningofYear"][24]+$rawdata["CashandCashEquivalentsBeginningofYear"][25]+$rawdata["CashandCashEquivalentsBeginningofYear"][26])).",";
                $query .= (($rawdata["CashandCashEquivalentsEndofYear"][23]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][24]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][25]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][26]=='null')?'null':($rawdata["CashandCashEquivalentsEndofYear"][23]+$rawdata["CashandCashEquivalentsEndofYear"][24]+$rawdata["CashandCashEquivalentsEndofYear"][25]+$rawdata["CashandCashEquivalentsEndofYear"][26])).",";
                $query .= (($rawdata["CashPaidforIncomeTaxes"][23]=='null'&&$rawdata["CashPaidforIncomeTaxes"][24]=='null'&&$rawdata["CashPaidforIncomeTaxes"][25]=='null'&&$rawdata["CashPaidforIncomeTaxes"][26]=='null')?'null':($rawdata["CashPaidforIncomeTaxes"][23]+$rawdata["CashPaidforIncomeTaxes"][24]+$rawdata["CashPaidforIncomeTaxes"][25]+$rawdata["CashPaidforIncomeTaxes"][26])).",";
                $query .= (($rawdata["CashPaidforInterestExpense"][23]=='null'&&$rawdata["CashPaidforInterestExpense"][24]=='null'&&$rawdata["CashPaidforInterestExpense"][25]=='null'&&$rawdata["CashPaidforInterestExpense"][26]=='null')?'null':($rawdata["CashPaidforInterestExpense"][23]+$rawdata["CashPaidforInterestExpense"][24]+$rawdata["CashPaidforInterestExpense"][25]+$rawdata["CashPaidforInterestExpense"][26])).",";
                $query .= (($rawdata["CFNetIncome"][23]=='null'&&$rawdata["CFNetIncome"][24]=='null'&&$rawdata["CFNetIncome"][25]=='null'&&$rawdata["CFNetIncome"][26]=='null')?'null':($rawdata["CFNetIncome"][23]+$rawdata["CFNetIncome"][24]+$rawdata["CFNetIncome"][25]+$rawdata["CFNetIncome"][26])).",";
                $query .= (($rawdata["IssuanceofEquity"][23]=='null'&&$rawdata["IssuanceofEquity"][24]=='null'&&$rawdata["IssuanceofEquity"][25]=='null'&&$rawdata["IssuanceofEquity"][26]=='null')?'null':($rawdata["IssuanceofEquity"][23]+$rawdata["IssuanceofEquity"][24]+$rawdata["IssuanceofEquity"][25]+$rawdata["IssuanceofEquity"][26])).",";
                $query .= (($rawdata["LongtermDebtPayments"][23]=='null'&&$rawdata["LongtermDebtPayments"][24]=='null'&&$rawdata["LongtermDebtPayments"][25]=='null'&&$rawdata["LongtermDebtPayments"][26]=='null')?'null':($rawdata["LongtermDebtPayments"][23]+$rawdata["LongtermDebtPayments"][24]+$rawdata["LongtermDebtPayments"][25]+$rawdata["LongtermDebtPayments"][26])).",";
                $query .= (($rawdata["LongtermDebtProceeds"][23]=='null'&&$rawdata["LongtermDebtProceeds"][24]=='null'&&$rawdata["LongtermDebtProceeds"][25]=='null'&&$rawdata["LongtermDebtProceeds"][26]=='null')?'null':($rawdata["LongtermDebtProceeds"][23]+$rawdata["LongtermDebtProceeds"][24]+$rawdata["LongtermDebtProceeds"][25]+$rawdata["LongtermDebtProceeds"][26])).",";
                $query .= (($rawdata["OtherDebtNet"][23]=='null'&&$rawdata["OtherDebtNet"][24]=='null'&&$rawdata["OtherDebtNet"][25]=='null'&&$rawdata["OtherDebtNet"][26]=='null')?'null':($rawdata["OtherDebtNet"][23]+$rawdata["OtherDebtNet"][24]+$rawdata["OtherDebtNet"][25]+$rawdata["OtherDebtNet"][26])).",";
                $query .= (($rawdata["OtherEquityTransactionsNet"][23]=='null'&&$rawdata["OtherEquityTransactionsNet"][24]=='null'&&$rawdata["OtherEquityTransactionsNet"][25]=='null'&&$rawdata["OtherEquityTransactionsNet"][26]=='null')?'null':($rawdata["OtherEquityTransactionsNet"][23]+$rawdata["OtherEquityTransactionsNet"][24]+$rawdata["OtherEquityTransactionsNet"][25]+$rawdata["OtherEquityTransactionsNet"][26])).",";
                $query .= (($rawdata["OtherInvestmentChangesNet"][23]=='null'&&$rawdata["OtherInvestmentChangesNet"][24]=='null'&&$rawdata["OtherInvestmentChangesNet"][25]=='null'&&$rawdata["OtherInvestmentChangesNet"][26]=='null')?'null':($rawdata["OtherInvestmentChangesNet"][23]+$rawdata["OtherInvestmentChangesNet"][24]+$rawdata["OtherInvestmentChangesNet"][25]+$rawdata["OtherInvestmentChangesNet"][26])).",";
                $query .= (($rawdata["PurchaseofInvestments"][23]=='null'&&$rawdata["PurchaseofInvestments"][24]=='null'&&$rawdata["PurchaseofInvestments"][25]=='null'&&$rawdata["PurchaseofInvestments"][26]=='null')?'null':($rawdata["PurchaseofInvestments"][23]+$rawdata["PurchaseofInvestments"][24]+$rawdata["PurchaseofInvestments"][25]+$rawdata["PurchaseofInvestments"][26])).",";
                $query .= (($rawdata["RepurchaseofEquity"][23]=='null'&&$rawdata["RepurchaseofEquity"][24]=='null'&&$rawdata["RepurchaseofEquity"][25]=='null'&&$rawdata["RepurchaseofEquity"][26]=='null')?'null':($rawdata["RepurchaseofEquity"][23]+$rawdata["RepurchaseofEquity"][24]+$rawdata["RepurchaseofEquity"][25]+$rawdata["RepurchaseofEquity"][26])).",";
                $query .= (($rawdata["SaleofInvestments"][23]=='null'&&$rawdata["SaleofInvestments"][24]=='null'&&$rawdata["SaleofInvestments"][25]=='null'&&$rawdata["SaleofInvestments"][26]=='null')?'null':($rawdata["SaleofInvestments"][23]+$rawdata["SaleofInvestments"][24]+$rawdata["SaleofInvestments"][25]+$rawdata["SaleofInvestments"][26])).",";
                $query .= (($rawdata["ShorttermBorrowings"][23]=='null'&&$rawdata["ShorttermBorrowings"][24]=='null'&&$rawdata["ShorttermBorrowings"][25]=='null'&&$rawdata["ShorttermBorrowings"][26]=='null')?'null':($rawdata["ShorttermBorrowings"][23]+$rawdata["ShorttermBorrowings"][24]+$rawdata["ShorttermBorrowings"][25]+$rawdata["ShorttermBorrowings"][26])).",";
                $query .= (($rawdata["TotalNoncashAdjustments"][23]=='null'&&$rawdata["TotalNoncashAdjustments"][24]=='null'&&$rawdata["TotalNoncashAdjustments"][25]=='null'&&$rawdata["TotalNoncashAdjustments"][26]=='null')?'null':($rawdata["TotalNoncashAdjustments"][23]+$rawdata["TotalNoncashAdjustments"][24]+$rawdata["TotalNoncashAdjustments"][25]+$rawdata["TotalNoncashAdjustments"][26]));
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `pttm_cashflowfull` (`ticker_id`, `ChangeinLongtermDebtNet`, `ChangeinShorttermBorrowingsNet`, `CashandCashEquivalentsBeginningofYear`, `CashandCashEquivalentsEndofYear`, `CashPaidforIncomeTaxes`, `CashPaidforInterestExpense`, `CFNetIncome`, `IssuanceofEquity`, `LongtermDebtPayments`, `LongtermDebtProceeds`, `OtherDebtNet`, `OtherEquityTransactionsNet`, `OtherInvestmentChangesNet`, `PurchaseofInvestments`, `RepurchaseofEquity`, `SaleofInvestments`, `ShorttermBorrowings`, `TotalNoncashAdjustments`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["ChangeinLongtermDebtNet"][19]=='null'&&$rawdata["ChangeinLongtermDebtNet"][20]=='null'&&$rawdata["ChangeinLongtermDebtNet"][21]=='null'&&$rawdata["ChangeinLongtermDebtNet"][22]=='null')?'null':($rawdata["ChangeinLongtermDebtNet"][19]+$rawdata["ChangeinLongtermDebtNet"][20]+$rawdata["ChangeinLongtermDebtNet"][21]+$rawdata["ChangeinLongtermDebtNet"][22])).",";
                $query .= (($rawdata["ChangeinShorttermBorrowingsNet"][19]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][20]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][21]=='null'&&$rawdata["ChangeinShorttermBorrowingsNet"][22]=='null')?'null':($rawdata["ChangeinShorttermBorrowingsNet"][19]+$rawdata["ChangeinShorttermBorrowingsNet"][20]+$rawdata["ChangeinShorttermBorrowingsNet"][21]+$rawdata["ChangeinShorttermBorrowingsNet"][22])).",";
                $query .= (($rawdata["CashandCashEquivalentsBeginningofYear"][19]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][20]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][21]=='null'&&$rawdata["CashandCashEquivalentsBeginningofYear"][22]=='null')?'null':($rawdata["CashandCashEquivalentsBeginningofYear"][19]+$rawdata["CashandCashEquivalentsBeginningofYear"][20]+$rawdata["CashandCashEquivalentsBeginningofYear"][21]+$rawdata["CashandCashEquivalentsBeginningofYear"][22])).",";
                $query .= (($rawdata["CashandCashEquivalentsEndofYear"][19]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][20]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][21]=='null'&&$rawdata["CashandCashEquivalentsEndofYear"][22]=='null')?'null':($rawdata["CashandCashEquivalentsEndofYear"][19]+$rawdata["CashandCashEquivalentsEndofYear"][20]+$rawdata["CashandCashEquivalentsEndofYear"][21]+$rawdata["CashandCashEquivalentsEndofYear"][22])).",";
                $query .= (($rawdata["CashPaidforIncomeTaxes"][19]=='null'&&$rawdata["CashPaidforIncomeTaxes"][20]=='null'&&$rawdata["CashPaidforIncomeTaxes"][21]=='null'&&$rawdata["CashPaidforIncomeTaxes"][22]=='null')?'null':($rawdata["CashPaidforIncomeTaxes"][19]+$rawdata["CashPaidforIncomeTaxes"][20]+$rawdata["CashPaidforIncomeTaxes"][21]+$rawdata["CashPaidforIncomeTaxes"][22])).",";
                $query .= (($rawdata["CashPaidforInterestExpense"][19]=='null'&&$rawdata["CashPaidforInterestExpense"][20]=='null'&&$rawdata["CashPaidforInterestExpense"][21]=='null'&&$rawdata["CashPaidforInterestExpense"][22]=='null')?'null':($rawdata["CashPaidforInterestExpense"][19]+$rawdata["CashPaidforInterestExpense"][20]+$rawdata["CashPaidforInterestExpense"][21]+$rawdata["CashPaidforInterestExpense"][22])).",";
                $query .= (($rawdata["CFNetIncome"][19]=='null'&&$rawdata["CFNetIncome"][20]=='null'&&$rawdata["CFNetIncome"][21]=='null'&&$rawdata["CFNetIncome"][22]=='null')?'null':($rawdata["CFNetIncome"][19]+$rawdata["CFNetIncome"][20]+$rawdata["CFNetIncome"][21]+$rawdata["CFNetIncome"][22])).",";
                $query .= (($rawdata["IssuanceofEquity"][19]=='null'&&$rawdata["IssuanceofEquity"][20]=='null'&&$rawdata["IssuanceofEquity"][21]=='null'&&$rawdata["IssuanceofEquity"][22]=='null')?'null':($rawdata["IssuanceofEquity"][19]+$rawdata["IssuanceofEquity"][20]+$rawdata["IssuanceofEquity"][21]+$rawdata["IssuanceofEquity"][22])).",";
                $query .= (($rawdata["LongtermDebtPayments"][19]=='null'&&$rawdata["LongtermDebtPayments"][20]=='null'&&$rawdata["LongtermDebtPayments"][21]=='null'&&$rawdata["LongtermDebtPayments"][22]=='null')?'null':($rawdata["LongtermDebtPayments"][19]+$rawdata["LongtermDebtPayments"][20]+$rawdata["LongtermDebtPayments"][21]+$rawdata["LongtermDebtPayments"][22])).",";
                $query .= (($rawdata["LongtermDebtProceeds"][19]=='null'&&$rawdata["LongtermDebtProceeds"][20]=='null'&&$rawdata["LongtermDebtProceeds"][21]=='null'&&$rawdata["LongtermDebtProceeds"][22]=='null')?'null':($rawdata["LongtermDebtProceeds"][19]+$rawdata["LongtermDebtProceeds"][20]+$rawdata["LongtermDebtProceeds"][21]+$rawdata["LongtermDebtProceeds"][22])).",";
                $query .= (($rawdata["OtherDebtNet"][19]=='null'&&$rawdata["OtherDebtNet"][20]=='null'&&$rawdata["OtherDebtNet"][21]=='null'&&$rawdata["OtherDebtNet"][22]=='null')?'null':($rawdata["OtherDebtNet"][19]+$rawdata["OtherDebtNet"][20]+$rawdata["OtherDebtNet"][21]+$rawdata["OtherDebtNet"][22])).",";
                $query .= (($rawdata["OtherEquityTransactionsNet"][19]=='null'&&$rawdata["OtherEquityTransactionsNet"][20]=='null'&&$rawdata["OtherEquityTransactionsNet"][21]=='null'&&$rawdata["OtherEquityTransactionsNet"][22]=='null')?'null':($rawdata["OtherEquityTransactionsNet"][19]+$rawdata["OtherEquityTransactionsNet"][20]+$rawdata["OtherEquityTransactionsNet"][21]+$rawdata["OtherEquityTransactionsNet"][22])).",";
                $query .= (($rawdata["OtherInvestmentChangesNet"][19]=='null'&&$rawdata["OtherInvestmentChangesNet"][20]=='null'&&$rawdata["OtherInvestmentChangesNet"][21]=='null'&&$rawdata["OtherInvestmentChangesNet"][22]=='null')?'null':($rawdata["OtherInvestmentChangesNet"][19]+$rawdata["OtherInvestmentChangesNet"][20]+$rawdata["OtherInvestmentChangesNet"][21]+$rawdata["OtherInvestmentChangesNet"][22])).",";
                $query .= (($rawdata["PurchaseofInvestments"][19]=='null'&&$rawdata["PurchaseofInvestments"][20]=='null'&&$rawdata["PurchaseofInvestments"][21]=='null'&&$rawdata["PurchaseofInvestments"][22]=='null')?'null':($rawdata["PurchaseofInvestments"][19]+$rawdata["PurchaseofInvestments"][20]+$rawdata["PurchaseofInvestments"][21]+$rawdata["PurchaseofInvestments"][22])).",";
                $query .= (($rawdata["RepurchaseofEquity"][19]=='null'&&$rawdata["RepurchaseofEquity"][20]=='null'&&$rawdata["RepurchaseofEquity"][21]=='null'&&$rawdata["RepurchaseofEquity"][22]=='null')?'null':($rawdata["RepurchaseofEquity"][19]+$rawdata["RepurchaseofEquity"][20]+$rawdata["RepurchaseofEquity"][21]+$rawdata["RepurchaseofEquity"][22])).",";
                $query .= (($rawdata["SaleofInvestments"][19]=='null'&&$rawdata["SaleofInvestments"][20]=='null'&&$rawdata["SaleofInvestments"][21]=='null'&&$rawdata["SaleofInvestments"][22]=='null')?'null':($rawdata["SaleofInvestments"][19]+$rawdata["SaleofInvestments"][20]+$rawdata["SaleofInvestments"][21]+$rawdata["SaleofInvestments"][22])).",";
                $query .= (($rawdata["ShorttermBorrowings"][19]=='null'&&$rawdata["ShorttermBorrowings"][20]=='null'&&$rawdata["ShorttermBorrowings"][21]=='null'&&$rawdata["ShorttermBorrowings"][22]=='null')?'null':($rawdata["ShorttermBorrowings"][19]+$rawdata["ShorttermBorrowings"][20]+$rawdata["ShorttermBorrowings"][21]+$rawdata["ShorttermBorrowings"][22])).",";
                $query .= (($rawdata["TotalNoncashAdjustments"][19]=='null'&&$rawdata["TotalNoncashAdjustments"][20]=='null'&&$rawdata["TotalNoncashAdjustments"][21]=='null'&&$rawdata["TotalNoncashAdjustments"][22]=='null')?'null':($rawdata["TotalNoncashAdjustments"][19]+$rawdata["TotalNoncashAdjustments"][20]+$rawdata["TotalNoncashAdjustments"][21]+$rawdata["TotalNoncashAdjustments"][22]));
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["EBIT"][23]=='null'&&$rawdata["EBIT"][24]=='null'&&$rawdata["EBIT"][25]=='null'&&$rawdata["EBIT"][26]=='null')?'null':($rawdata["EBIT"][23]+$rawdata["EBIT"][24]+$rawdata["EBIT"][25]+$rawdata["EBIT"][26])).",";
                $query .= (($rawdata["CostofRevenue"][23]=='null'&&$rawdata["CostofRevenue"][24]=='null'&&$rawdata["CostofRevenue"][25]=='null'&&$rawdata["CostofRevenue"][26]=='null')?'null':($rawdata["CostofRevenue"][23]+$rawdata["CostofRevenue"][24]+$rawdata["CostofRevenue"][25]+$rawdata["CostofRevenue"][26])).",";
                $query .= (($rawdata["DepreciationAmortizationExpense"][23]=='null'&&$rawdata["DepreciationAmortizationExpense"][24]=='null'&&$rawdata["DepreciationAmortizationExpense"][25]=='null'&&$rawdata["DepreciationAmortizationExpense"][26]=='null')?'null':($rawdata["DepreciationAmortizationExpense"][23]+$rawdata["DepreciationAmortizationExpense"][24]+$rawdata["DepreciationAmortizationExpense"][25]+$rawdata["DepreciationAmortizationExpense"][26])).",";
                $query .= (($rawdata["DilutedEPSNetIncome"][23]=='null'&&$rawdata["DilutedEPSNetIncome"][24]=='null'&&$rawdata["DilutedEPSNetIncome"][25]=='null'&&$rawdata["DilutedEPSNetIncome"][26]=='null')?'null':($rawdata["DilutedEPSNetIncome"][23]+$rawdata["DilutedEPSNetIncome"][24]+$rawdata["DilutedEPSNetIncome"][25]+$rawdata["DilutedEPSNetIncome"][26])).",";
                $query .= (($rawdata["DiscontinuedOperations"][23]=='null'&&$rawdata["DiscontinuedOperations"][24]=='null'&&$rawdata["DiscontinuedOperations"][25]=='null'&&$rawdata["DiscontinuedOperations"][26]=='null')?'null':($rawdata["DiscontinuedOperations"][23]+$rawdata["DiscontinuedOperations"][24]+$rawdata["DiscontinuedOperations"][25]+$rawdata["DiscontinuedOperations"][23])).",";
                $query .= (($rawdata["EquityEarnings"][23]=='null'&&$rawdata["EquityEarnings"][24]=='null'&&$rawdata["EquityEarnings"][25]=='null'&&$rawdata["EquityEarnings"][26]=='null')?'null':($rawdata["EquityEarnings"][23]+$rawdata["EquityEarnings"][24]+$rawdata["EquityEarnings"][25]+$rawdata["EquityEarnings"][26])).",";
                $query .= (($rawdata["AccountingChange"][23]=='null'&&$rawdata["AccountingChange"][24]=='null'&&$rawdata["AccountingChange"][25]=='null'&&$rawdata["AccountingChange"][26]=='null')?'null':($rawdata["AccountingChange"][23]+$rawdata["AccountingChange"][24]+$rawdata["AccountingChange"][25]+$rawdata["AccountingChange"][26])).",";
                $query .= (($rawdata["BasicEPSNetIncome"][23]=='null'&&$rawdata["BasicEPSNetIncome"][24]=='null'&&$rawdata["BasicEPSNetIncome"][25]=='null'&&$rawdata["BasicEPSNetIncome"][26]=='null')?'null':($rawdata["BasicEPSNetIncome"][23]+$rawdata["BasicEPSNetIncome"][24]+$rawdata["BasicEPSNetIncome"][25]+$rawdata["BasicEPSNetIncome"][26])).",";
                $query .= (($rawdata["ExtraordinaryItems"][23]=='null'&&$rawdata["ExtraordinaryItems"][24]=='null'&&$rawdata["ExtraordinaryItems"][25]=='null'&&$rawdata["ExtraordinaryItems"][26]=='null')?'null':($rawdata["ExtraordinaryItems"][23]+$rawdata["ExtraordinaryItems"][24]+$rawdata["ExtraordinaryItems"][25]+$rawdata["ExtraordinaryItems"][26])).",";
                $query .= (($rawdata["GrossProfit"][23]=='null'&&$rawdata["GrossProfit"][24]=='null'&&$rawdata["GrossProfit"][25]=='null'&&$rawdata["GrossProfit"][26]=='null')?'null':($rawdata["GrossProfit"][23]+$rawdata["GrossProfit"][24]+$rawdata["GrossProfit"][25]+$rawdata["GrossProfit"][26])).",";
                $query .= (($rawdata["IncomebeforeExtraordinaryItems"][23]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][24]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][25]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][26]=='null')?'null':($rawdata["IncomebeforeExtraordinaryItems"][23]+$rawdata["IncomebeforeExtraordinaryItems"][24]+$rawdata["IncomebeforeExtraordinaryItems"][25]+$rawdata["IncomebeforeExtraordinaryItems"][26])).",";
                $query .= (($rawdata["IncomeBeforeTaxes"][23]=='null'&&$rawdata["IncomeBeforeTaxes"][24]=='null'&&$rawdata["IncomeBeforeTaxes"][25]=='null'&&$rawdata["IncomeBeforeTaxes"][26]=='null')?'null':($rawdata["IncomeBeforeTaxes"][23]+$rawdata["IncomeBeforeTaxes"][24]+$rawdata["IncomeBeforeTaxes"][25]+$rawdata["IncomeBeforeTaxes"][26])).",";
                $query .= (($rawdata["IncomeTaxes"][23]=='null'&&$rawdata["IncomeTaxes"][24]=='null'&&$rawdata["IncomeTaxes"][25]=='null'&&$rawdata["IncomeTaxes"][26]=='null')?'null':($rawdata["IncomeTaxes"][23]+$rawdata["IncomeTaxes"][24]+$rawdata["IncomeTaxes"][25]+$rawdata["IncomeTaxes"][26])).",";
                $query .= (($rawdata["InterestExpense"][23]=='null'&&$rawdata["InterestExpense"][24]=='null'&&$rawdata["InterestExpense"][25]=='null'&&$rawdata["InterestExpense"][26]=='null')?'null':(toFloat($rawdata["InterestExpense"][23])+toFloat($rawdata["InterestExpense"][24])+toFloat($rawdata["InterestExpense"][25])+toFloat($rawdata["InterestExpense"][26]))).",";
                $query .= (($rawdata["InterestIncome"][23]=='null'&&$rawdata["InterestIncome"][24]=='null'&&$rawdata["InterestIncome"][25]=='null'&&$rawdata["InterestIncome"][26]=='null')?'null':(toFloat($rawdata["InterestIncome"][23])+toFloat($rawdata["InterestIncome"][24])+toFloat($rawdata["InterestIncome"][25])+toFloat($rawdata["InterestIncome"][26]))).",";
                $query .= (($rawdata["MinorityInterestEquityEarnings"][23]=='null'&&$rawdata["MinorityInterestEquityEarnings"][24]=='null'&&$rawdata["MinorityInterestEquityEarnings"][25]=='null'&&$rawdata["MinorityInterestEquityEarnings"][26]=='null')?'null':($rawdata["MinorityInterestEquityEarnings"][23]+$rawdata["MinorityInterestEquityEarnings"][24]+$rawdata["MinorityInterestEquityEarnings"][25]+$rawdata["MinorityInterestEquityEarnings"][26])).",";
                $query .= (($rawdata["NetIncome"][23]=='null'&&$rawdata["NetIncome"][24]=='null'&&$rawdata["NetIncome"][25]=='null'&&$rawdata["NetIncome"][26]=='null')?'null':($rawdata["NetIncome"][23]+$rawdata["NetIncome"][24]+$rawdata["NetIncome"][25]+$rawdata["NetIncome"][26])).",";
                $query .= (($rawdata["NetIncomeApplicabletoCommon"][23]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][24]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][25]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][26]=='null')?'null':($rawdata["NetIncomeApplicabletoCommon"][23]+$rawdata["NetIncomeApplicabletoCommon"][24]+$rawdata["NetIncomeApplicabletoCommon"][25]+$rawdata["NetIncomeApplicabletoCommon"][26])).",";
                $query .= (($rawdata["OperatingProfit"][23]=='null'&&$rawdata["OperatingProfit"][24]=='null'&&$rawdata["OperatingProfit"][25]=='null'&&$rawdata["OperatingProfit"][26]=='null')?'null':($rawdata["OperatingProfit"][23]+$rawdata["OperatingProfit"][24]+$rawdata["OperatingProfit"][25]+$rawdata["OperatingProfit"][26])).",";
                $query .= (($rawdata["OtherNonoperatingIncomeExpense"][23]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][24]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][25]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][26]=='null')?'null':($rawdata["OtherNonoperatingIncomeExpense"][23]+$rawdata["OtherNonoperatingIncomeExpense"][24]+$rawdata["OtherNonoperatingIncomeExpense"][25]+$rawdata["OtherNonoperatingIncomeExpense"][26])).",";
                $query .= (($rawdata["OtherOperatingExpenses"][23]=='null'&&$rawdata["OtherOperatingExpenses"][24]=='null'&&$rawdata["OtherOperatingExpenses"][25]=='null'&&$rawdata["OtherOperatingExpenses"][26]=='null')?'null':($rawdata["OtherOperatingExpenses"][23]+$rawdata["OtherOperatingExpenses"][24]+$rawdata["OtherOperatingExpenses"][25]+$rawdata["OtherOperatingExpenses"][26])).",";
                $query .= (($rawdata["ResearchDevelopmentExpense"][23]=='null'&&$rawdata["ResearchDevelopmentExpense"][24]=='null'&&$rawdata["ResearchDevelopmentExpense"][25]=='null'&&$rawdata["ResearchDevelopmentExpense"][26]=='null')?'null':($rawdata["ResearchDevelopmentExpense"][23]+$rawdata["ResearchDevelopmentExpense"][24]+$rawdata["ResearchDevelopmentExpense"][25]+$rawdata["ResearchDevelopmentExpense"][26])).",";
                $query .= (($rawdata["RestructuringRemediationImpairmentProvisions"][23]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][24]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][25]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][26]=='null')?'null':($rawdata["RestructuringRemediationImpairmentProvisions"][23]+$rawdata["RestructuringRemediationImpairmentProvisions"][24]+$rawdata["RestructuringRemediationImpairmentProvisions"][25]+$rawdata["RestructuringRemediationImpairmentProvisions"][26])).",";
                $query .= (($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')?'null':($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26])).",";
                $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][23]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][24]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][25]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][26]=='null')?'null':($rawdata["SellingGeneralAdministrativeExpenses"][23]+$rawdata["SellingGeneralAdministrativeExpenses"][24]+$rawdata["SellingGeneralAdministrativeExpenses"][25]+$rawdata["SellingGeneralAdministrativeExpenses"][26]));
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `pttm_incomeconsolidated` (`ticker_id`, `EBIT`, `CostofRevenue`, `DepreciationAmortizationExpense`, `DilutedEPSNetIncome`, `DiscontinuedOperations`, `EquityEarnings`, `AccountingChange`, `BasicEPSNetIncome`, `ExtraordinaryItems`, `GrossProfit`, `IncomebeforeExtraordinaryItems`, `IncomeBeforeTaxes`, `IncomeTaxes`, `InterestExpense`, `InterestIncome`, `MinorityInterestEquityEarnings`, `NetIncome`, `NetIncomeApplicabletoCommon`, `OperatingProfit`, `OtherNonoperatingIncomeExpense`, `OtherOperatingExpenses`, `ResearchDevelopmentExpense`, `RestructuringRemediationImpairmentProvisions`, `TotalRevenue`, `SellingGeneralAdministrativeExpenses`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["EBIT"][19]=='null'&&$rawdata["EBIT"][20]=='null'&&$rawdata["EBIT"][21]=='null'&&$rawdata["EBIT"][22]=='null')?'null':($rawdata["EBIT"][19]+$rawdata["EBIT"][20]+$rawdata["EBIT"][21]+$rawdata["EBIT"][22])).",";
                $query .= (($rawdata["CostofRevenue"][19]=='null'&&$rawdata["CostofRevenue"][20]=='null'&&$rawdata["CostofRevenue"][21]=='null'&&$rawdata["CostofRevenue"][22]=='null')?'null':($rawdata["CostofRevenue"][19]+$rawdata["CostofRevenue"][20]+$rawdata["CostofRevenue"][21]+$rawdata["CostofRevenue"][22])).",";
                $query .= (($rawdata["DepreciationAmortizationExpense"][19]=='null'&&$rawdata["DepreciationAmortizationExpense"][20]=='null'&&$rawdata["DepreciationAmortizationExpense"][21]=='null'&&$rawdata["DepreciationAmortizationExpense"][22]=='null')?'null':($rawdata["DepreciationAmortizationExpense"][19]+$rawdata["DepreciationAmortizationExpense"][20]+$rawdata["DepreciationAmortizationExpense"][21]+$rawdata["DepreciationAmortizationExpense"][22])).",";
                $query .= (($rawdata["DilutedEPSNetIncome"][19]=='null'&&$rawdata["DilutedEPSNetIncome"][20]=='null'&&$rawdata["DilutedEPSNetIncome"][21]=='null'&&$rawdata["DilutedEPSNetIncome"][22]=='null')?'null':($rawdata["DilutedEPSNetIncome"][19]+$rawdata["DilutedEPSNetIncome"][20]+$rawdata["DilutedEPSNetIncome"][21]+$rawdata["DilutedEPSNetIncome"][22])).",";
                $query .= (($rawdata["DiscontinuedOperations"][19]=='null'&&$rawdata["DiscontinuedOperations"][20]=='null'&&$rawdata["DiscontinuedOperations"][21]=='null'&&$rawdata["DiscontinuedOperations"][22]=='null')?'null':($rawdata["DiscontinuedOperations"][19]+$rawdata["DiscontinuedOperations"][20]+$rawdata["DiscontinuedOperations"][21]+$rawdata["DiscontinuedOperations"][19])).",";
                $query .= (($rawdata["EquityEarnings"][19]=='null'&&$rawdata["EquityEarnings"][20]=='null'&&$rawdata["EquityEarnings"][21]=='null'&&$rawdata["EquityEarnings"][22]=='null')?'null':($rawdata["EquityEarnings"][19]+$rawdata["EquityEarnings"][20]+$rawdata["EquityEarnings"][21]+$rawdata["EquityEarnings"][22])).",";
                $query .= (($rawdata["AccountingChange"][19]=='null'&&$rawdata["AccountingChange"][20]=='null'&&$rawdata["AccountingChange"][21]=='null'&&$rawdata["AccountingChange"][22]=='null')?'null':($rawdata["AccountingChange"][19]+$rawdata["AccountingChange"][20]+$rawdata["AccountingChange"][21]+$rawdata["AccountingChange"][22])).",";
                $query .= (($rawdata["BasicEPSNetIncome"][19]=='null'&&$rawdata["BasicEPSNetIncome"][20]=='null'&&$rawdata["BasicEPSNetIncome"][21]=='null'&&$rawdata["BasicEPSNetIncome"][22]=='null')?'null':($rawdata["BasicEPSNetIncome"][19]+$rawdata["BasicEPSNetIncome"][20]+$rawdata["BasicEPSNetIncome"][21]+$rawdata["BasicEPSNetIncome"][22])).",";
                $query .= (($rawdata["ExtraordinaryItems"][19]=='null'&&$rawdata["ExtraordinaryItems"][20]=='null'&&$rawdata["ExtraordinaryItems"][21]=='null'&&$rawdata["ExtraordinaryItems"][22]=='null')?'null':($rawdata["ExtraordinaryItems"][19]+$rawdata["ExtraordinaryItems"][20]+$rawdata["ExtraordinaryItems"][21]+$rawdata["ExtraordinaryItems"][22])).",";
                $query .= (($rawdata["GrossProfit"][19]=='null'&&$rawdata["GrossProfit"][20]=='null'&&$rawdata["GrossProfit"][21]=='null'&&$rawdata["GrossProfit"][22]=='null')?'null':($rawdata["GrossProfit"][19]+$rawdata["GrossProfit"][20]+$rawdata["GrossProfit"][21]+$rawdata["GrossProfit"][22])).",";
                $query .= (($rawdata["IncomebeforeExtraordinaryItems"][19]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][20]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][21]=='null'&&$rawdata["IncomebeforeExtraordinaryItems"][22]=='null')?'null':($rawdata["IncomebeforeExtraordinaryItems"][19]+$rawdata["IncomebeforeExtraordinaryItems"][20]+$rawdata["IncomebeforeExtraordinaryItems"][21]+$rawdata["IncomebeforeExtraordinaryItems"][22])).",";
                $query .= (($rawdata["IncomeBeforeTaxes"][19]=='null'&&$rawdata["IncomeBeforeTaxes"][20]=='null'&&$rawdata["IncomeBeforeTaxes"][21]=='null'&&$rawdata["IncomeBeforeTaxes"][22]=='null')?'null':($rawdata["IncomeBeforeTaxes"][19]+$rawdata["IncomeBeforeTaxes"][20]+$rawdata["IncomeBeforeTaxes"][21]+$rawdata["IncomeBeforeTaxes"][22])).",";
                $query .= (($rawdata["IncomeTaxes"][19]=='null'&&$rawdata["IncomeTaxes"][20]=='null'&&$rawdata["IncomeTaxes"][21]=='null'&&$rawdata["IncomeTaxes"][22]=='null')?'null':($rawdata["IncomeTaxes"][19]+$rawdata["IncomeTaxes"][20]+$rawdata["IncomeTaxes"][21]+$rawdata["IncomeTaxes"][22])).",";
                $query .= (($rawdata["InterestExpense"][19]=='null'&&$rawdata["InterestExpense"][20]=='null'&&$rawdata["InterestExpense"][21]=='null'&&$rawdata["InterestExpense"][22]=='null')?'null':(toFloat($rawdata["InterestExpense"][19])+toFloat($rawdata["InterestExpense"][20])+toFloat($rawdata["InterestExpense"][21])+toFloat($rawdata["InterestExpense"][22]))).",";
                $query .= (($rawdata["InterestIncome"][19]=='null'&&$rawdata["InterestIncome"][20]=='null'&&$rawdata["InterestIncome"][21]=='null'&&$rawdata["InterestIncome"][22]=='null')?'null':(toFloat($rawdata["InterestIncome"][19])+toFloat($rawdata["InterestIncome"][20])+toFloat($rawdata["InterestIncome"][21])+toFloat($rawdata["InterestIncome"][22]))).",";
                $query .= (($rawdata["MinorityInterestEquityEarnings"][19]=='null'&&$rawdata["MinorityInterestEquityEarnings"][20]=='null'&&$rawdata["MinorityInterestEquityEarnings"][21]=='null'&&$rawdata["MinorityInterestEquityEarnings"][22]=='null')?'null':($rawdata["MinorityInterestEquityEarnings"][19]+$rawdata["MinorityInterestEquityEarnings"][20]+$rawdata["MinorityInterestEquityEarnings"][21]+$rawdata["MinorityInterestEquityEarnings"][22])).",";
                $query .= (($rawdata["NetIncome"][19]=='null'&&$rawdata["NetIncome"][20]=='null'&&$rawdata["NetIncome"][21]=='null'&&$rawdata["NetIncome"][22]=='null')?'null':($rawdata["NetIncome"][19]+$rawdata["NetIncome"][20]+$rawdata["NetIncome"][21]+$rawdata["NetIncome"][22])).",";
                $query .= (($rawdata["NetIncomeApplicabletoCommon"][19]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][20]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][21]=='null'&&$rawdata["NetIncomeApplicabletoCommon"][22]=='null')?'null':($rawdata["NetIncomeApplicabletoCommon"][19]+$rawdata["NetIncomeApplicabletoCommon"][20]+$rawdata["NetIncomeApplicabletoCommon"][21]+$rawdata["NetIncomeApplicabletoCommon"][22])).",";
                $query .= (($rawdata["OperatingProfit"][19]=='null'&&$rawdata["OperatingProfit"][20]=='null'&&$rawdata["OperatingProfit"][21]=='null'&&$rawdata["OperatingProfit"][22]=='null')?'null':($rawdata["OperatingProfit"][19]+$rawdata["OperatingProfit"][20]+$rawdata["OperatingProfit"][21]+$rawdata["OperatingProfit"][22])).",";
                $query .= (($rawdata["OtherNonoperatingIncomeExpense"][19]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][20]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][21]=='null'&&$rawdata["OtherNonoperatingIncomeExpense"][22]=='null')?'null':($rawdata["OtherNonoperatingIncomeExpense"][19]+$rawdata["OtherNonoperatingIncomeExpense"][20]+$rawdata["OtherNonoperatingIncomeExpense"][21]+$rawdata["OtherNonoperatingIncomeExpense"][22])).",";
                $query .= (($rawdata["OtherOperatingExpenses"][19]=='null'&&$rawdata["OtherOperatingExpenses"][20]=='null'&&$rawdata["OtherOperatingExpenses"][21]=='null'&&$rawdata["OtherOperatingExpenses"][22]=='null')?'null':($rawdata["OtherOperatingExpenses"][19]+$rawdata["OtherOperatingExpenses"][20]+$rawdata["OtherOperatingExpenses"][21]+$rawdata["OtherOperatingExpenses"][22])).",";
                $query .= (($rawdata["ResearchDevelopmentExpense"][19]=='null'&&$rawdata["ResearchDevelopmentExpense"][20]=='null'&&$rawdata["ResearchDevelopmentExpense"][21]=='null'&&$rawdata["ResearchDevelopmentExpense"][22]=='null')?'null':($rawdata["ResearchDevelopmentExpense"][19]+$rawdata["ResearchDevelopmentExpense"][20]+$rawdata["ResearchDevelopmentExpense"][21]+$rawdata["ResearchDevelopmentExpense"][22])).",";
                $query .= (($rawdata["RestructuringRemediationImpairmentProvisions"][19]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][20]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][21]=='null'&&$rawdata["RestructuringRemediationImpairmentProvisions"][22]=='null')?'null':($rawdata["RestructuringRemediationImpairmentProvisions"][19]+$rawdata["RestructuringRemediationImpairmentProvisions"][20]+$rawdata["RestructuringRemediationImpairmentProvisions"][21]+$rawdata["RestructuringRemediationImpairmentProvisions"][22])).",";
                $query .= (($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')?'null':($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22])).",";
                $query .= (($rawdata["SellingGeneralAdministrativeExpenses"][19]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][20]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][21]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][22]=='null')?'null':($rawdata["SellingGeneralAdministrativeExpenses"][19]+$rawdata["SellingGeneralAdministrativeExpenses"][20]+$rawdata["SellingGeneralAdministrativeExpenses"][21]+$rawdata["SellingGeneralAdministrativeExpenses"][22]));
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `ttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["AdjustedEBIT"][23]=='null'&&$rawdata["AdjustedEBIT"][24]=='null'&&$rawdata["AdjustedEBIT"][25]=='null'&&$rawdata["AdjustedEBIT"][26]=='null')?'null':($rawdata["AdjustedEBIT"][23]+$rawdata["AdjustedEBIT"][24]+$rawdata["AdjustedEBIT"][25]+$rawdata["AdjustedEBIT"][26])).",";
                $query .= (($rawdata["AdjustedEBITDA"][23]=='null'&&$rawdata["AdjustedEBITDA"][24]=='null'&&$rawdata["AdjustedEBITDA"][25]=='null'&&$rawdata["AdjustedEBITDA"][26]=='null')?'null':($rawdata["AdjustedEBITDA"][23]+$rawdata["AdjustedEBITDA"][24]+$rawdata["AdjustedEBITDA"][25]+$rawdata["AdjustedEBITDA"][26])).",";
                $query .= (($rawdata["AdjustedNetIncome"][23]=='null'&&$rawdata["AdjustedNetIncome"][23]=='null'&&$rawdata["AdjustedNetIncome"][25]=='null'&&$rawdata["AdjustedNetIncome"][26]=='null')?'null':($rawdata["AdjustedNetIncome"][23]+$rawdata["AdjustedNetIncome"][24]+$rawdata["AdjustedNetIncome"][25]+$rawdata["AdjustedNetIncome"][26])).",";
                $divisor = 4;
                if($rawdata["AftertaxMargin"][23]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][24]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][25]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][26]=='null') {$divisor--;}
                $query .= (($divisor==0)?'null':(($rawdata["AftertaxMargin"][23]+$rawdata["AftertaxMargin"][24]+$rawdata["AftertaxMargin"][25]+$rawdata["AftertaxMargin"][26])/$divisor)).",";
                $query .= (($rawdata["EBITDA"][23]=='null'&&$rawdata["EBITDA"][24]=='null'&&$rawdata["EBITDA"][25]=='null'&&$rawdata["EBITDA"][26]=='null')?'null':($rawdata["EBITDA"][23]+$rawdata["EBITDA"][24]+$rawdata["EBITDA"][25]+$rawdata["EBITDA"][26])).",";
                $divisor = 4;
                if($rawdata["GrossMargin"][23]=='null') {$divisor--;}
                if($rawdata["GrossMargin"][24]=='null') {$divisor--;}
                if($rawdata["GrossMargin"][25]=='null') {$divisor--;}
                if($rawdata["GrossMargin"][26]=='null') {$divisor--;}
                $query .= (($divisor==0)?'null':(($rawdata["GrossMargin"][23]+$rawdata["GrossMargin"][24]+$rawdata["GrossMargin"][25]+$rawdata["GrossMargin"][26])/$divisor)).",";
                $query .= (($rawdata["NetOperatingProfitafterTax"][23]=='null'&&$rawdata["NetOperatingProfitafterTax"][24]=='null'&&$rawdata["NetOperatingProfitafterTax"][25]=='null'&&$rawdata["NetOperatingProfitafterTax"][26]=='null')?'null':($rawdata["NetOperatingProfitafterTax"][23]+$rawdata["NetOperatingProfitafterTax"][24]+$rawdata["NetOperatingProfitafterTax"][25]+$rawdata["NetOperatingProfitafterTax"][26])).",";
                $divisor = 4;
                if($rawdata["OperatingMargin"][23]=='null') {$divisor--;}
                if($rawdata["OperatingMargin"][24]=='null') {$divisor--;}
                if($rawdata["OperatingMargin"][25]=='null') {$divisor--;}
                if($rawdata["OperatingMargin"][26]=='null') {$divisor--;}
                $query .= (($divisor==0)?'null':(($rawdata["OperatingMargin"][23]+$rawdata["OperatingMargin"][24]+$rawdata["OperatingMargin"][25]+$rawdata["OperatingMargin"][26])/$divisor)).",";
                $query .= (($rawdata["RevenueFQ"][23]=='null'&&$rawdata["RevenueFQ"][24]=='null'&&$rawdata["RevenueFQ"][25]=='null'&&$rawdata["RevenueFQ"][26]=='null')?'null':($rawdata["RevenueFQ"][23]+$rawdata["RevenueFQ"][24]+$rawdata["RevenueFQ"][25]+$rawdata["RevenueFQ"][26])).",";
                $query .= (($rawdata["RevenueFY"][23]=='null'&&$rawdata["RevenueFY"][24]=='null'&&$rawdata["RevenueFY"][25]=='null'&&$rawdata["RevenueFY"][26]=='null')?'null':($rawdata["RevenueFY"][23]+$rawdata["RevenueFY"][24]+$rawdata["RevenueFY"][25]+$rawdata["RevenueFY"][26])).",";
                $query .= (($rawdata["RevenueTTM"][23]=='null'&&$rawdata["RevenueTTM"][24]=='null'&&$rawdata["RevenueTTM"][25]=='null'&&$rawdata["RevenueTTM"][26]=='null')?'null':($rawdata["RevenueTTM"][23]+$rawdata["RevenueTTM"][24]+$rawdata["RevenueTTM"][25]+$rawdata["RevenueTTM"][26])).",";
                $query .= (($rawdata["CostOperatingExpenses"][23]=='null'&&$rawdata["CostOperatingExpenses"][24]=='null'&&$rawdata["CostOperatingExpenses"][25]=='null'&&$rawdata["CostOperatingExpenses"][26]=='null')?'null':($rawdata["CostOperatingExpenses"][23]+$rawdata["CostOperatingExpenses"][24]+$rawdata["CostOperatingExpenses"][25]+$rawdata["CostOperatingExpenses"][26])).",";
                $query .= (($rawdata["DepreciationExpense"][23]=='null'&&$rawdata["DepreciationExpense"][24]=='null'&&$rawdata["DepreciationExpense"][25]=='null'&&$rawdata["DepreciationExpense"][26]=='null')?'null':($rawdata["DepreciationExpense"][23]+$rawdata["DepreciationExpense"][24]+$rawdata["DepreciationExpense"][25]+$rawdata["DepreciationExpense"][26])).",";
                $query .= (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][23]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][24]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][25]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][26]=='null')?'null':($rawdata["DilutedEPSNetIncomefromContinuingOperations"][23]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][24]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][25]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][26])).",";
                $query .= $rawdata["DilutedWeightedAverageShares"][$MRQRow].",";
                $query .= (($rawdata["AmortizationExpense"][23]=='null'&&$rawdata["AmortizationExpense"][24]=='null'&&$rawdata["AmortizationExpense"][25]=='null'&&$rawdata["AmortizationExpense"][26]=='null')?'null':($rawdata["AmortizationExpense"][23]+$rawdata["AmortizationExpense"][24]+$rawdata["AmortizationExpense"][25]+$rawdata["AmortizationExpense"][26])).",";
                $query .= (($rawdata["BasicEPSNetIncomefromContinuingOperations"][23]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][24]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][25]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][26]=='null')?'null':($rawdata["BasicEPSNetIncomefromContinuingOperations"][23]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][24]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][25]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][26])).",";
                $query .= $rawdata["BasicWeightedAverageShares"][$MRQRow].",";
                $query .= (($rawdata["GeneralAdministrativeExpense"][23]=='null'&&$rawdata["GeneralAdministrativeExpense"][24]=='null'&&$rawdata["GeneralAdministrativeExpense"][25]=='null'&&$rawdata["GeneralAdministrativeExpense"][26]=='null')?'null':($rawdata["GeneralAdministrativeExpense"][23]+$rawdata["GeneralAdministrativeExpense"][24]+$rawdata["GeneralAdministrativeExpense"][25]+$rawdata["GeneralAdministrativeExpense"][26])).",";
                $query .= (($rawdata["IncomeAfterTaxes"][23]=='null'&&$rawdata["IncomeAfterTaxes"][24]=='null'&&$rawdata["IncomeAfterTaxes"][25]=='null'&&$rawdata["IncomeAfterTaxes"][26]=='null')?'null':($rawdata["IncomeAfterTaxes"][23]+$rawdata["IncomeAfterTaxes"][24]+$rawdata["IncomeAfterTaxes"][25]+$rawdata["IncomeAfterTaxes"][26])).",";
                $query .= (($rawdata["LaborExpense"][23]=='null'&&$rawdata["LaborExpense"][24]=='null'&&$rawdata["LaborExpense"][25]=='null'&&$rawdata["LaborExpense"][26]=='null')?'null':($rawdata["LaborExpense"][23]+$rawdata["LaborExpense"][24]+$rawdata["LaborExpense"][25]+$rawdata["LaborExpense"][26])).",";
                $query .= (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][23]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][24]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][25]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][26]=='null')?'null':($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][23]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][24]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][25]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][26])).",";
                $query .= (($rawdata["InterestIncomeExpenseNet"][23]=='null'&&$rawdata["InterestIncomeExpenseNet"][24]=='null'&&$rawdata["InterestIncomeExpenseNet"][25]=='null'&&$rawdata["InterestIncomeExpenseNet"][26]=='null')?'null':($rawdata["InterestIncomeExpenseNet"][23]+$rawdata["InterestIncomeExpenseNet"][24]+$rawdata["InterestIncomeExpenseNet"][25]+$rawdata["InterestIncomeExpenseNet"][26])).",";
                $query .= (($rawdata["NoncontrollingInterest"][23]=='null'&&$rawdata["NoncontrollingInterest"][24]=='null'&&$rawdata["NoncontrollingInterest"][25]=='null'&&$rawdata["NoncontrollingInterest"][26]=='null')?'null':($rawdata["NoncontrollingInterest"][23]+$rawdata["NoncontrollingInterest"][24]+$rawdata["NoncontrollingInterest"][25]+$rawdata["NoncontrollingInterest"][26])).",";
                $query .= (($rawdata["NonoperatingGainsLosses"][23]=='null'&&$rawdata["NonoperatingGainsLosses"][24]=='null'&&$rawdata["NonoperatingGainsLosses"][25]=='null'&&$rawdata["NonoperatingGainsLosses"][26]=='null')?'null':($rawdata["NonoperatingGainsLosses"][23]+$rawdata["NonoperatingGainsLosses"][24]+$rawdata["NonoperatingGainsLosses"][25]+$rawdata["NonoperatingGainsLosses"][26])).",";
                $query .= (($rawdata["OperatingExpenses"][23]=='null'&&$rawdata["OperatingExpenses"][24]=='null'&&$rawdata["OperatingExpenses"][25]=='null'&&$rawdata["OperatingExpenses"][26]=='null')?'null':($rawdata["OperatingExpenses"][23]+$rawdata["OperatingExpenses"][24]+$rawdata["OperatingExpenses"][25]+$rawdata["OperatingExpenses"][26])).",";
                $query .= (($rawdata["OtherGeneralAdministrativeExpense"][23]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][24]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][25]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][26]=='null')?'null':($rawdata["OtherGeneralAdministrativeExpense"][23]+$rawdata["OtherGeneralAdministrativeExpense"][24]+$rawdata["OtherGeneralAdministrativeExpense"][25]+$rawdata["OtherGeneralAdministrativeExpense"][26])).",";
                $query .= (($rawdata["OtherInterestIncomeExpenseNet"][23]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][24]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][25]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][26]=='null')?'null':($rawdata["OtherInterestIncomeExpenseNet"][23]+$rawdata["OtherInterestIncomeExpenseNet"][24]+$rawdata["OtherInterestIncomeExpenseNet"][25]+$rawdata["OtherInterestIncomeExpenseNet"][26])).",";
                $query .= (($rawdata["OtherRevenue"][23]=='null'&&$rawdata["OtherRevenue"][24]=='null'&&$rawdata["OtherRevenue"][25]=='null'&&$rawdata["OtherRevenue"][26]=='null')?'null':($rawdata["OtherRevenue"][23]+$rawdata["OtherRevenue"][24]+$rawdata["OtherRevenue"][25]+$rawdata["OtherRevenue"][26])).",";
                $query .= (($rawdata["OtherSellingGeneralAdministrativeExpenses"][23]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][24]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][25]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][26]=='null')?'null':($rawdata["OtherSellingGeneralAdministrativeExpenses"][23]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][24]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][25]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][26])).",";
                $query .= (($rawdata["PreferredDividends"][23]=='null'&&$rawdata["PreferredDividends"][24]=='null'&&$rawdata["PreferredDividends"][25]=='null'&&$rawdata["PreferredDividends"][26]=='null')?'null':($rawdata["PreferredDividends"][23]+$rawdata["PreferredDividends"][24]+$rawdata["PreferredDividends"][25]+$rawdata["PreferredDividends"][26])).",";
                $query .= (($rawdata["SalesMarketingExpense"][23]=='null'&&$rawdata["SalesMarketingExpense"][24]=='null'&&$rawdata["SalesMarketingExpense"][25]=='null'&&$rawdata["SalesMarketingExpense"][26]=='null')?'null':($rawdata["SalesMarketingExpense"][23]+$rawdata["SalesMarketingExpense"][24]+$rawdata["SalesMarketingExpense"][25]+$rawdata["SalesMarketingExpense"][26])).",";
                $query .= (($rawdata["TotalNonoperatingIncomeExpense"][23]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][24]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][25]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][26]=='null')?'null':($rawdata["TotalNonoperatingIncomeExpense"][23]+$rawdata["TotalNonoperatingIncomeExpense"][24]+$rawdata["TotalNonoperatingIncomeExpense"][25]+$rawdata["TotalNonoperatingIncomeExpense"][26])).",";
                $query .= (($rawdata["TotalOperatingExpenses"][23]=='null'&&$rawdata["TotalOperatingExpenses"][24]=='null'&&$rawdata["TotalOperatingExpenses"][25]=='null'&&$rawdata["TotalOperatingExpenses"][26]=='null')?'null':($rawdata["TotalOperatingExpenses"][23]+$rawdata["TotalOperatingExpenses"][24]+$rawdata["TotalOperatingExpenses"][25]+$rawdata["TotalOperatingExpenses"][26])).",";
                $query .= (($rawdata["OperatingRevenue"][23]=='null'&&$rawdata["OperatingRevenue"][24]=='null'&&$rawdata["OperatingRevenue"][25]=='null'&&$rawdata["OperatingRevenue"][26]=='null')?'null':($rawdata["OperatingRevenue"][23]+$rawdata["OperatingRevenue"][24]+$rawdata["OperatingRevenue"][25]+$rawdata["OperatingRevenue"][26]));
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

		$query = "INSERT INTO `pttm_incomefull` (`ticker_id`, `AdjustedEBIT`, `AdjustedEBITDA`, `AdjustedNetIncome`, `AftertaxMargin`, `EBITDA`, `GrossMargin`, `NetOperatingProfitafterTax`, `OperatingMargin`, `RevenueFQ`, `RevenueFY`, `RevenueTTM`, `CostOperatingExpenses`, `DepreciationExpense`, `DilutedEPSNetIncomefromContinuingOperations`, `DilutedWeightedAverageShares`, `AmortizationExpense`, `BasicEPSNetIncomefromContinuingOperations`, `BasicWeightedAverageShares`, `GeneralAdministrativeExpense`, `IncomeAfterTaxes`, `LaborExpense`, `NetIncomefromContinuingOperationsApplicabletoCommon`, `InterestIncomeExpenseNet`, `NoncontrollingInterest`, `NonoperatingGainsLosses`, `OperatingExpenses`, `OtherGeneralAdministrativeExpense`, `OtherInterestIncomeExpenseNet`, `OtherRevenue`, `OtherSellingGeneralAdministrativeExpenses`, `PreferredDividends`, `SalesMarketingExpense`, `TotalNonoperatingIncomeExpense`, `TotalOperatingExpenses`, `OperatingRevenue`) VALUES (";
        	$query .= "'".$dates->ticker_id."',";
                $query .= (($rawdata["AdjustedEBIT"][19]=='null'&&$rawdata["AdjustedEBIT"][20]=='null'&&$rawdata["AdjustedEBIT"][21]=='null'&&$rawdata["AdjustedEBIT"][22]=='null')?'null':($rawdata["AdjustedEBIT"][19]+$rawdata["AdjustedEBIT"][20]+$rawdata["AdjustedEBIT"][21]+$rawdata["AdjustedEBIT"][22])).",";
                $query .= (($rawdata["AdjustedEBITDA"][19]=='null'&&$rawdata["AdjustedEBITDA"][20]=='null'&&$rawdata["AdjustedEBITDA"][21]=='null'&&$rawdata["AdjustedEBITDA"][22]=='null')?'null':($rawdata["AdjustedEBITDA"][19]+$rawdata["AdjustedEBITDA"][20]+$rawdata["AdjustedEBITDA"][21]+$rawdata["AdjustedEBITDA"][22])).",";
                $query .= (($rawdata["AdjustedNetIncome"][19]=='null'&&$rawdata["AdjustedNetIncome"][20]=='null'&&$rawdata["AdjustedNetIncome"][21]=='null'&&$rawdata["AdjustedNetIncome"][22]=='null')?'null':($rawdata["AdjustedNetIncome"][19]+$rawdata["AdjustedNetIncome"][20]+$rawdata["AdjustedNetIncome"][21]+$rawdata["AdjustedNetIncome"][22])).",";
                $divisor = 4;
                if($rawdata["AftertaxMargin"][19]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][20]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][21]=='null') {$divisor--;}
                if($rawdata["AftertaxMargin"][22]=='null') {$divisor--;}
                $query .= (($divisor==0)?'null':(($rawdata["AftertaxMargin"][19]+$rawdata["AftertaxMargin"][20]+$rawdata["AftertaxMargin"][21]+$rawdata["AftertaxMargin"][22])/$divisor)).",";
                $query .= (($rawdata["EBITDA"][19]=='null'&&$rawdata["EBITDA"][20]=='null'&&$rawdata["EBITDA"][21]=='null'&&$rawdata["EBITDA"][22]=='null')?'null':($rawdata["EBITDA"][19]+$rawdata["EBITDA"][20]+$rawdata["EBITDA"][21]+$rawdata["EBITDA"][22])).",";
                $divisor = 4;
                if($rawdata["GrossMargin"][19]=='null') {$divisor--;}
                if($rawdata["GrossMargin"][20]=='null') {$divisor--;}
                if($rawdata["GrossMargin"][21]=='null') {$divisor--;}
                if($rawdata["GrossMargin"][22]=='null') {$divisor--;}
                $query .= (($divisor==0)?'null':(($rawdata["GrossMargin"][19]+$rawdata["GrossMargin"][20]+$rawdata["GrossMargin"][21]+$rawdata["GrossMargin"][22])/$divisor)).",";
                $query .= (($rawdata["NetOperatingProfitafterTax"][19]=='null'&&$rawdata["NetOperatingProfitafterTax"][20]=='null'&&$rawdata["NetOperatingProfitafterTax"][21]=='null'&&$rawdata["NetOperatingProfitafterTax"][22]=='null')?'null':($rawdata["NetOperatingProfitafterTax"][19]+$rawdata["NetOperatingProfitafterTax"][20]+$rawdata["NetOperatingProfitafterTax"][21]+$rawdata["NetOperatingProfitafterTax"][22])).",";
                $divisor = 4;
                if($rawdata["OperatingMargin"][19]=='null') {$divisor--;}
                if($rawdata["OperatingMargin"][20]=='null') {$divisor--;}
                if($rawdata["OperatingMargin"][21]=='null') {$divisor--;}
                if($rawdata["OperatingMargin"][22]=='null') {$divisor--;}
                $query .= (($divisor==0)?'null':(($rawdata["OperatingMargin"][19]+$rawdata["OperatingMargin"][20]+$rawdata["OperatingMargin"][21]+$rawdata["OperatingMargin"][22])/$divisor)).",";
                $query .= (($rawdata["RevenueFQ"][19]=='null'&&$rawdata["RevenueFQ"][20]=='null'&&$rawdata["RevenueFQ"][21]=='null'&&$rawdata["RevenueFQ"][22]=='null')?'null':($rawdata["RevenueFQ"][19]+$rawdata["RevenueFQ"][20]+$rawdata["RevenueFQ"][21]+$rawdata["RevenueFQ"][22])).",";
                $query .= (($rawdata["RevenueFY"][19]=='null'&&$rawdata["RevenueFY"][20]=='null'&&$rawdata["RevenueFY"][21]=='null'&&$rawdata["RevenueFY"][22]=='null')?'null':($rawdata["RevenueFY"][19]+$rawdata["RevenueFY"][20]+$rawdata["RevenueFY"][21]+$rawdata["RevenueFY"][22])).",";
                $query .= (($rawdata["RevenueTTM"][19]=='null'&&$rawdata["RevenueTTM"][20]=='null'&&$rawdata["RevenueTTM"][21]=='null'&&$rawdata["RevenueTTM"][22]=='null')?'null':($rawdata["RevenueTTM"][19]+$rawdata["RevenueTTM"][20]+$rawdata["RevenueTTM"][21]+$rawdata["RevenueTTM"][22])).",";
                $query .= (($rawdata["CostOperatingExpenses"][19]=='null'&&$rawdata["CostOperatingExpenses"][20]=='null'&&$rawdata["CostOperatingExpenses"][21]=='null'&&$rawdata["CostOperatingExpenses"][22]=='null')?'null':($rawdata["CostOperatingExpenses"][19]+$rawdata["CostOperatingExpenses"][20]+$rawdata["CostOperatingExpenses"][21]+$rawdata["CostOperatingExpenses"][22])).",";
                $query .= (($rawdata["DepreciationExpense"][19]=='null'&&$rawdata["DepreciationExpense"][20]=='null'&&$rawdata["DepreciationExpense"][21]=='null'&&$rawdata["DepreciationExpense"][22]=='null')?'null':($rawdata["DepreciationExpense"][19]+$rawdata["DepreciationExpense"][20]+$rawdata["DepreciationExpense"][21]+$rawdata["DepreciationExpense"][22])).",";
                $query .= (($rawdata["DilutedEPSNetIncomefromContinuingOperations"][19]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][20]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][21]=='null'&&$rawdata["DilutedEPSNetIncomefromContinuingOperations"][22]=='null')?'null':($rawdata["DilutedEPSNetIncomefromContinuingOperations"][19]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][20]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][21]+$rawdata["DilutedEPSNetIncomefromContinuingOperations"][22])).",";
                $query .= $rawdata["DilutedWeightedAverageShares"][$PMRQRow].",";
                $query .= (($rawdata["AmortizationExpense"][19]=='null'&&$rawdata["AmortizationExpense"][20]=='null'&&$rawdata["AmortizationExpense"][21]=='null'&&$rawdata["AmortizationExpense"][22]=='null')?'null':($rawdata["AmortizationExpense"][19]+$rawdata["AmortizationExpense"][20]+$rawdata["AmortizationExpense"][21]+$rawdata["AmortizationExpense"][22])).",";
                $query .= (($rawdata["BasicEPSNetIncomefromContinuingOperations"][19]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][20]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][21]=='null'&&$rawdata["BasicEPSNetIncomefromContinuingOperations"][22]=='null')?'null':($rawdata["BasicEPSNetIncomefromContinuingOperations"][19]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][20]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][21]+$rawdata["BasicEPSNetIncomefromContinuingOperations"][22])).",";
                $query .= $rawdata["BasicWeightedAverageShares"][$PMRQRow].",";
                $query .= (($rawdata["GeneralAdministrativeExpense"][19]=='null'&&$rawdata["GeneralAdministrativeExpense"][20]=='null'&&$rawdata["GeneralAdministrativeExpense"][21]=='null'&&$rawdata["GeneralAdministrativeExpense"][22]=='null')?'null':($rawdata["GeneralAdministrativeExpense"][19]+$rawdata["GeneralAdministrativeExpense"][20]+$rawdata["GeneralAdministrativeExpense"][21]+$rawdata["GeneralAdministrativeExpense"][22])).",";
                $query .= (($rawdata["IncomeAfterTaxes"][19]=='null'&&$rawdata["IncomeAfterTaxes"][20]=='null'&&$rawdata["IncomeAfterTaxes"][21]=='null'&&$rawdata["IncomeAfterTaxes"][22]=='null')?'null':($rawdata["IncomeAfterTaxes"][19]+$rawdata["IncomeAfterTaxes"][20]+$rawdata["IncomeAfterTaxes"][21]+$rawdata["IncomeAfterTaxes"][22])).",";
                $query .= (($rawdata["LaborExpense"][19]=='null'&&$rawdata["LaborExpense"][20]=='null'&&$rawdata["LaborExpense"][21]=='null'&&$rawdata["LaborExpense"][22]=='null')?'null':($rawdata["LaborExpense"][19]+$rawdata["LaborExpense"][20]+$rawdata["LaborExpense"][21]+$rawdata["LaborExpense"][22])).",";
                $query .= (($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][19]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][20]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][21]=='null'&&$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][22]=='null')?'null':($rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][19]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][20]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][21]+$rawdata["NetIncomefromContinuingOperationsApplicabletoCommon"][22])).",";
                $query .= (($rawdata["InterestIncomeExpenseNet"][19]=='null'&&$rawdata["InterestIncomeExpenseNet"][20]=='null'&&$rawdata["InterestIncomeExpenseNet"][21]=='null'&&$rawdata["InterestIncomeExpenseNet"][22]=='null')?'null':($rawdata["InterestIncomeExpenseNet"][19]+$rawdata["InterestIncomeExpenseNet"][20]+$rawdata["InterestIncomeExpenseNet"][21]+$rawdata["InterestIncomeExpenseNet"][22])).",";
                $query .= (($rawdata["NoncontrollingInterest"][19]=='null'&&$rawdata["NoncontrollingInterest"][20]=='null'&&$rawdata["NoncontrollingInterest"][21]=='null'&&$rawdata["NoncontrollingInterest"][22]=='null')?'null':($rawdata["NoncontrollingInterest"][19]+$rawdata["NoncontrollingInterest"][20]+$rawdata["NoncontrollingInterest"][21]+$rawdata["NoncontrollingInterest"][22])).",";
                $query .= (($rawdata["NonoperatingGainsLosses"][19]=='null'&&$rawdata["NonoperatingGainsLosses"][20]=='null'&&$rawdata["NonoperatingGainsLosses"][21]=='null'&&$rawdata["NonoperatingGainsLosses"][22]=='null')?'null':($rawdata["NonoperatingGainsLosses"][19]+$rawdata["NonoperatingGainsLosses"][20]+$rawdata["NonoperatingGainsLosses"][21]+$rawdata["NonoperatingGainsLosses"][22])).",";
                $query .= (($rawdata["OperatingExpenses"][19]=='null'&&$rawdata["OperatingExpenses"][20]=='null'&&$rawdata["OperatingExpenses"][21]=='null'&&$rawdata["OperatingExpenses"][22]=='null')?'null':($rawdata["OperatingExpenses"][19]+$rawdata["OperatingExpenses"][20]+$rawdata["OperatingExpenses"][21]+$rawdata["OperatingExpenses"][22])).",";
                $query .= (($rawdata["OtherGeneralAdministrativeExpense"][19]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][20]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][21]=='null'&&$rawdata["OtherGeneralAdministrativeExpense"][22]=='null')?'null':($rawdata["OtherGeneralAdministrativeExpense"][19]+$rawdata["OtherGeneralAdministrativeExpense"][20]+$rawdata["OtherGeneralAdministrativeExpense"][21]+$rawdata["OtherGeneralAdministrativeExpense"][22])).",";
                $query .= (($rawdata["OtherInterestIncomeExpenseNet"][19]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][20]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][21]=='null'&&$rawdata["OtherInterestIncomeExpenseNet"][22]=='null')?'null':($rawdata["OtherInterestIncomeExpenseNet"][19]+$rawdata["OtherInterestIncomeExpenseNet"][20]+$rawdata["OtherInterestIncomeExpenseNet"][21]+$rawdata["OtherInterestIncomeExpenseNet"][22])).",";
                $query .= (($rawdata["OtherRevenue"][19]=='null'&&$rawdata["OtherRevenue"][20]=='null'&&$rawdata["OtherRevenue"][21]=='null'&&$rawdata["OtherRevenue"][22]=='null')?'null':($rawdata["OtherRevenue"][19]+$rawdata["OtherRevenue"][20]+$rawdata["OtherRevenue"][21]+$rawdata["OtherRevenue"][22])).",";
                $query .= (($rawdata["OtherSellingGeneralAdministrativeExpenses"][19]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][20]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][21]=='null'&&$rawdata["OtherSellingGeneralAdministrativeExpenses"][22]=='null')?'null':($rawdata["OtherSellingGeneralAdministrativeExpenses"][19]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][20]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][21]+$rawdata["OtherSellingGeneralAdministrativeExpenses"][22])).",";
                $query .= (($rawdata["PreferredDividends"][19]=='null'&&$rawdata["PreferredDividends"][20]=='null'&&$rawdata["PreferredDividends"][21]=='null'&&$rawdata["PreferredDividends"][22]=='null')?'null':($rawdata["PreferredDividends"][19]+$rawdata["PreferredDividends"][20]+$rawdata["PreferredDividends"][21]+$rawdata["PreferredDividends"][22])).",";
                $query .= (($rawdata["SalesMarketingExpense"][19]=='null'&&$rawdata["SalesMarketingExpense"][20]=='null'&&$rawdata["SalesMarketingExpense"][21]=='null'&&$rawdata["SalesMarketingExpense"][22]=='null')?'null':($rawdata["SalesMarketingExpense"][19]+$rawdata["SalesMarketingExpense"][20]+$rawdata["SalesMarketingExpense"][21]+$rawdata["SalesMarketingExpense"][22])).",";
                $query .= (($rawdata["TotalNonoperatingIncomeExpense"][19]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][20]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][21]=='null'&&$rawdata["TotalNonoperatingIncomeExpense"][22]=='null')?'null':($rawdata["TotalNonoperatingIncomeExpense"][19]+$rawdata["TotalNonoperatingIncomeExpense"][20]+$rawdata["TotalNonoperatingIncomeExpense"][21]+$rawdata["TotalNonoperatingIncomeExpense"][22])).",";
                $query .= (($rawdata["TotalOperatingExpenses"][19]=='null'&&$rawdata["TotalOperatingExpenses"][20]=='null'&&$rawdata["TotalOperatingExpenses"][21]=='null'&&$rawdata["TotalOperatingExpenses"][22]=='null')?'null':($rawdata["TotalOperatingExpenses"][19]+$rawdata["TotalOperatingExpenses"][20]+$rawdata["TotalOperatingExpenses"][21]+$rawdata["TotalOperatingExpenses"][22])).",";
                $query .= (($rawdata["OperatingRevenue"][19]=='null'&&$rawdata["OperatingRevenue"][20]=='null'&&$rawdata["OperatingRevenue"][21]=='null'&&$rawdata["OperatingRevenue"][22]=='null')?'null':($rawdata["OperatingRevenue"][19]+$rawdata["OperatingRevenue"][20]+$rawdata["OperatingRevenue"][21]+$rawdata["OperatingRevenue"][22]));
       		$query .= ")";
        	mysql_query($query) or die ($query." ".mysql_error());

                $query = "INSERT INTO `ttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`, `Sales5YYCGrPerc`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= ((($rawdata["CostofRevenue"][23]=='null'&&$rawdata["CostofRevenue"][24]=='null'&&$rawdata["CostofRevenue"][25]=='null'&&$rawdata["CostofRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["CostofRevenue"][23]+$rawdata["CostofRevenue"][24]+$rawdata["CostofRevenue"][25]+$rawdata["CostofRevenue"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["GrossProfit"][23]=='null'&&$rawdata["GrossProfit"][24]=='null'&&$rawdata["GrossProfit"][25]=='null'&&$rawdata["GrossProfit"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["GrossProfit"][23]+$rawdata["GrossProfit"][24]+$rawdata["GrossProfit"][25]+$rawdata["GrossProfit"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["SellingGeneralAdministrativeExpenses"][23]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][24]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][25]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["SellingGeneralAdministrativeExpenses"][23]+$rawdata["SellingGeneralAdministrativeExpenses"][24]+$rawdata["SellingGeneralAdministrativeExpenses"][25]+$rawdata["SellingGeneralAdministrativeExpenses"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["ResearchDevelopmentExpense"][23]=='null'&&$rawdata["ResearchDevelopmentExpense"][24]=='null'&&$rawdata["ResearchDevelopmentExpense"][25]=='null'&&$rawdata["ResearchDevelopmentExpense"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["ResearchDevelopmentExpense"][23]+$rawdata["ResearchDevelopmentExpense"][24]+$rawdata["ResearchDevelopmentExpense"][25]+$rawdata["ResearchDevelopmentExpense"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["CFDepreciationAmortization"][23]=='null'&&$rawdata["CFDepreciationAmortization"][24]=='null'&&$rawdata["CFDepreciationAmortization"][25]=='null'&&$rawdata["CFDepreciationAmortization"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["CFDepreciationAmortization"][23]+$rawdata["CFDepreciationAmortization"][24]+$rawdata["CFDepreciationAmortization"][25]+$rawdata["CFDepreciationAmortization"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["EBITDA"][23]=='null'&&$rawdata["EBITDA"][24]=='null'&&$rawdata["EBITDA"][25]=='null'&&$rawdata["EBITDA"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["EBITDA"][23]+$rawdata["EBITDA"][24]+$rawdata["EBITDA"][25]+$rawdata["EBITDA"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["OperatingProfit"][23]=='null'&&$rawdata["OperatingProfit"][24]=='null'&&$rawdata["OperatingProfit"][25]=='null'&&$rawdata["OperatingProfit"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["OperatingProfit"][23]+$rawdata["OperatingProfit"][24]+$rawdata["OperatingProfit"][25]+$rawdata["OperatingProfit"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["EBIT"][23]=='null'&&$rawdata["EBIT"][24]=='null'&&$rawdata["EBIT"][25]=='null'&&$rawdata["EBIT"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["EBIT"][23]+$rawdata["EBIT"][24]+$rawdata["EBIT"][25]+$rawdata["EBIT"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $query .= ((($rawdata["IncomeTaxes"][23]=='null'&&$rawdata["IncomeTaxes"][24]=='null'&&$rawdata["IncomeTaxes"][25]=='null'&&$rawdata["IncomeTaxes"][26]=='null')||($rawdata["IncomeBeforeTaxes"][23]=='null'&&$rawdata["IncomeBeforeTaxes"][24]=='null'&&$rawdata["IncomeBeforeTaxes"][25]=='null'&&$rawdata["IncomeBeforeTaxes"][26]=='null')||($rawdata["IncomeBeforeTaxes"][23]+$rawdata["IncomeBeforeTaxes"][24]+$rawdata["IncomeBeforeTaxes"][25]+$rawdata["IncomeBeforeTaxes"][26]==0))?'null':(($rawdata["IncomeTaxes"][23]+$rawdata["IncomeTaxes"][24]+$rawdata["IncomeTaxes"][25]+$rawdata["IncomeTaxes"][26])/($rawdata["IncomeBeforeTaxes"][23]+$rawdata["IncomeBeforeTaxes"][24]+$rawdata["IncomeBeforeTaxes"][25]+$rawdata["IncomeBeforeTaxes"][26]))).",";
                $query .= ((($rawdata["IncomeTaxes"][23]=='null'&&$rawdata["IncomeTaxes"][24]=='null'&&$rawdata["IncomeTaxes"][25]=='null'&&$rawdata["IncomeTaxes"][26]=='null')&&($rawdata["IncomeBeforeTaxes"][23]=='null'&&$rawdata["IncomeBeforeTaxes"][24]=='null'&&$rawdata["IncomeBeforeTaxes"][25]=='null'&&$rawdata["IncomeBeforeTaxes"][26]=='null'))?'null':(($rawdata["IncomeBeforeTaxes"][23]+$rawdata["IncomeBeforeTaxes"][24]+$rawdata["IncomeBeforeTaxes"][25]+$rawdata["IncomeBeforeTaxes"][26])-($rawdata["IncomeTaxes"][23]+$rawdata["IncomeTaxes"][24]+$rawdata["IncomeTaxes"][25]+$rawdata["IncomeTaxes"][26]))).",";
                $query .= ((($rawdata["NetIncome"][23]=='null'&&$rawdata["NetIncome"][24]=='null'&&$rawdata["NetIncome"][25]=='null'&&$rawdata["NetIncome"][26]=='null')||($rawdata["TotalRevenue"][23]=='null'&&$rawdata["TotalRevenue"][24]=='null'&&$rawdata["TotalRevenue"][25]=='null'&&$rawdata["TotalRevenue"][26]=='null')||($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]==0))?'null':(($rawdata["NetIncome"][23]+$rawdata["NetIncome"][24]+$rawdata["NetIncome"][25]+$rawdata["NetIncome"][26])/($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26]))).",";
                $value = 0;
                if(($rawdata["DividendsPaid"][23]=='null'&&$rawdata["DividendsPaid"][24]=='null'&&$rawdata["DividendsPaid"][25]=='null'&&$rawdata["DividendsPaid"][26]=='null')||($rawdata["SharesOutstandingBasic"][23]=='null'&&$rawdata["SharesOutstandingBasic"][24]=='null'&&$rawdata["SharesOutstandingBasic"][25]=='null'&&$rawdata["SharesOutstandingBasic"][26]=='null')||($rawdata["SharesOutstandingBasic"][23]+$rawdata["SharesOutstandingBasic"][24]+$rawdata["SharesOutstandingBasic"][25]+$rawdata["SharesOutstandingBasic"][26]==0)) {
                        $value = "'null'";
                } else {
                        if($rawdata["DividendsPaid"][23]!='null'&&$rawdata["SharesOutstandingBasic"][23]!='null'&&$rawdata["SharesOutstandingBasic"][23]!=0) {
                                $value -= ($rawdata["DividendsPaid"][23]/(toFloat($rawdata["SharesOutstandingBasic"][23])*1000000));
                        }
                        if($rawdata["DividendsPaid"][24]!='null'&&$rawdata["SharesOutstandingBasic"][24]!='null'&&$rawdata["SharesOutstandingBasic"][24]!=0) {
                                $value -= ($rawdata["DividendsPaid"][24]/(toFloat($rawdata["SharesOutstandingBasic"][24])*1000000));
                        }
                        if($rawdata["DividendsPaid"][25]!='null'&&$rawdata["SharesOutstandingBasic"][25]!='null'&&$rawdata["SharesOutstandingBasic"][25]!=0) {
                                $value -= ($rawdata["DividendsPaid"][25]/(toFloat($rawdata["SharesOutstandingBasic"][25])*1000000));
                        }
                        if($rawdata["DividendsPaid"][26]!='null'&&$rawdata["SharesOutstandingBasic"][26]!='null'&&$rawdata["SharesOutstandingBasic"][26]!=0) {
                                $value -= ($rawdata["DividendsPaid"][26]/(toFloat($rawdata["SharesOutstandingBasic"][26])*1000000));
                        }
                }
                $query .= $value.",";
                $query .= ((($rawdata["CurrentPortionofLongtermDebt"][23]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][24]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][25]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][26]=='null')&&($rawdata["ShorttermBorrowings"][23]=='null'&&$rawdata["ShorttermBorrowings"][24]=='null'&&$rawdata["ShorttermBorrowings"][25]=='null'&&$rawdata["ShorttermBorrowings"][26]=='null'))?'null':($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow])).",";
                $query .= ((($rawdata["TotalLongtermDebt"][23]=='null'&&$rawdata["TotalLongtermDebt"][24]=='null'&&$rawdata["TotalLongtermDebt"][25]=='null'&&$rawdata["TotalLongtermDebt"][26]=='null')&&($rawdata["NotesPayable"][23]=='null'&&$rawdata["NotesPayable"][24]=='null'&&$rawdata["NotesPayable"][25]=='null'&&$rawdata["NotesPayable"][26]=='null'))?'null':($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow])).",";
                $query .= ((($rawdata["LongtermDebtProceeds"][23]=='null'&&$rawdata["LongtermDebtProceeds"][24]=='null'&&$rawdata["LongtermDebtProceeds"][25]=='null'&&$rawdata["LongtermDebtProceeds"][26]=='null')&&($rawdata["LongtermDebtPayments"][23]=='null'&&$rawdata["LongtermDebtPayments"][24]=='null'&&$rawdata["LongtermDebtPayments"][25]=='null'&&$rawdata["LongtermDebtPayments"][26]=='null'))?'null':(($rawdata["LongtermDebtProceeds"][23]+$rawdata["LongtermDebtProceeds"][24]+$rawdata["LongtermDebtProceeds"][25]+$rawdata["LongtermDebtProceeds"][26])+($rawdata["LongtermDebtPayments"][23]+$rawdata["LongtermDebtPayments"][24]+$rawdata["LongtermDebtPayments"][25]+$rawdata["LongtermDebtPayments"][26]))).",";
                $query .= (($rawdata["CapitalExpenditures"][23]=='null'&&$rawdata["CapitalExpenditures"][24]=='null'&&$rawdata["CapitalExpenditures"][25]=='null'&&$rawdata["CapitalExpenditures"][26]=='null')?'null':(-($rawdata["CapitalExpenditures"][23]+$rawdata["CapitalExpenditures"][24]+$rawdata["CapitalExpenditures"][25]+$rawdata["CapitalExpenditures"][26]))).",";
                $query .= ((($rawdata["CashfromOperatingActivities"][23]=='null'&&$rawdata["CashfromOperatingActivities"][24]=='null'&&$rawdata["CashfromOperatingActivities"][25]=='null'&&$rawdata["CashfromOperatingActivities"][26]=='null')&&($rawdata["CapitalExpenditures"][23]=='null'&&$rawdata["CapitalExpenditures"][24]=='null'&&$rawdata["CapitalExpenditures"][25]=='null'&&$rawdata["CapitalExpenditures"][26]=='null'))?'null':(($rawdata["CashfromOperatingActivities"][23]+$rawdata["CashfromOperatingActivities"][24]+$rawdata["CashfromOperatingActivities"][25]+$rawdata["CashfromOperatingActivities"][26])+($rawdata["CapitalExpenditures"][23]+$rawdata["CapitalExpenditures"][24]+$rawdata["CapitalExpenditures"][25]+$rawdata["CapitalExpenditures"][26]))).",";
                $query .= ((($rawdata["CFNetIncome"][23]=='null'&&$rawdata["CFNetIncome"][24]=='null'&&$rawdata["CFNetIncome"][25]=='null'&&$rawdata["CFNetIncome"][26]=='null')&&($rawdata["CFDepreciationAmortization"][23]=='null'&&$rawdata["CFDepreciationAmortization"][24]=='null'&&$rawdata["CFDepreciationAmortization"][25]=='null'&&$rawdata["CFDepreciationAmortization"][26]=='null')&&($rawdata["EmployeeCompensation"][23]=='null'&&$rawdata["EmployeeCompensation"][24]=='null'&&$rawdata["EmployeeCompensation"][25]=='null'&&$rawdata["EmployeeCompensation"][26]=='null')&&($rawdata["AdjustmentforSpecialCharges"][23]=='null'&&$rawdata["AdjustmentforSpecialCharges"][24]=='null'&&$rawdata["AdjustmentforSpecialCharges"][25]=='null'&&$rawdata["AdjustmentforSpecialCharges"][26]=='null')&&($rawdata["DeferredIncomeTaxes"][23]=='null'&&$rawdata["DeferredIncomeTaxes"][24]=='null'&&$rawdata["DeferredIncomeTaxes"][25]=='null'&&$rawdata["DeferredIncomeTaxes"][26]=='null')&&($rawdata["CapitalExpenditures"][23]=='null'&&$rawdata["CapitalExpenditures"][24]=='null'&&$rawdata["CapitalExpenditures"][25]=='null'&&$rawdata["CapitalExpenditures"][26]=='null')&&($rawdata["ChangeinCurrentAssets"][23]=='null'&&$rawdata["ChangeinCurrentAssets"][24]=='null'&&$rawdata["ChangeinCurrentAssets"][25]=='null'&&$rawdata["ChangeinCurrentAssets"][26]=='null')&&($rawdata["ChangeinCurrentLiabilities"][23]=='null'&&$rawdata["ChangeinCurrentLiabilities"][24]=='null'&&$rawdata["ChangeinCurrentLiabilities"][25]=='null'&&$rawdata["ChangeinCurrentLiabilities"][26]=='null'))?'null':(($rawdata["CFNetIncome"][23]+$rawdata["CFNetIncome"][24]+$rawdata["CFNetIncome"][25]+$rawdata["CFNetIncome"][26])+($rawdata["CFDepreciationAmortization"][23]+$rawdata["CFDepreciationAmortization"][24]+$rawdata["CFDepreciationAmortization"][25]+$rawdata["CFDepreciationAmortization"][26])+($rawdata["EmployeeCompensation"][23]+$rawdata["EmployeeCompensation"][24]+$rawdata["EmployeeCompensation"][25]+$rawdata["EmployeeCompensation"][26])+($rawdata["AdjustmentforSpecialCharges"][23]+$rawdata["AdjustmentforSpecialCharges"][24]+$rawdata["AdjustmentforSpecialCharges"][25]+$rawdata["AdjustmentforSpecialCharges"][26])+($rawdata["DeferredIncomeTaxes"][23]+$rawdata["DeferredIncomeTaxes"][24]+$rawdata["DeferredIncomeTaxes"][25]+$rawdata["DeferredIncomeTaxes"][26])+($rawdata["CapitalExpenditures"][23]+$rawdata["CapitalExpenditures"][24]+$rawdata["CapitalExpenditures"][25]+$rawdata["CapitalExpenditures"][26])+(($rawdata["ChangeinCurrentAssets"][23]+$rawdata["ChangeinCurrentAssets"][24]+$rawdata["ChangeinCurrentAssets"][25]+$rawdata["ChangeinCurrentAssets"][26])+($rawdata["ChangeinCurrentLiabilities"][23]+$rawdata["ChangeinCurrentLiabilities"][24]+$rawdata["ChangeinCurrentLiabilities"][25]+$rawdata["ChangeinCurrentLiabilities"][26])))).",";
		$query .= ((($rawdata["TotalRevenue"][23]=='null' && $rawdata["TotalRevenue"][24]=='null' && $rawdata["TotalRevenue"][25]=='null' && $rawdata["TotalRevenue"][26]=='null') || $rawdata["TotalRevenue"][5]=='null' || $rawdata["TotalRevenue"][5]<=0 || ($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26] < 0))?'null':(pow(($rawdata["TotalRevenue"][23]+$rawdata["TotalRevenue"][24]+$rawdata["TotalRevenue"][25]+$rawdata["TotalRevenue"][26])/$rawdata["TotalRevenue"][5], 1/5) - 1));
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());

                $query = "INSERT INTO `pttm_financialscustom` (`ticker_id`, `COGSPercent`, `GrossMarginPercent`, `SGAPercent`, `RDPercent`, `DepreciationAmortizationPercent`, `EBITDAPercent`, `OperatingMarginPercent`, `EBITPercent`, `TaxRatePercent`, `IncomeAfterTaxes`, `NetMarginPercent`, `DividendsPerShare`, `ShortTermDebtAndCurrentPortion`, `TotalLongTermDebtAndNotesPayable`, `NetChangeLongTermDebt`, `CapEx`, `FreeCashFlow`, `OwnerEarningsFCF`) VALUES (";
                $query .= "'".$dates->ticker_id."',";
                $query .= ((($rawdata["CostofRevenue"][19]=='null'&&$rawdata["CostofRevenue"][20]=='null'&&$rawdata["CostofRevenue"][21]=='null'&&$rawdata["CostofRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["CostofRevenue"][19]+$rawdata["CostofRevenue"][20]+$rawdata["CostofRevenue"][21]+$rawdata["CostofRevenue"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["GrossProfit"][19]=='null'&&$rawdata["GrossProfit"][20]=='null'&&$rawdata["GrossProfit"][21]=='null'&&$rawdata["GrossProfit"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["GrossProfit"][19]+$rawdata["GrossProfit"][20]+$rawdata["GrossProfit"][21]+$rawdata["GrossProfit"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["SellingGeneralAdministrativeExpenses"][19]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][20]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][21]=='null'&&$rawdata["SellingGeneralAdministrativeExpenses"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["SellingGeneralAdministrativeExpenses"][19]+$rawdata["SellingGeneralAdministrativeExpenses"][20]+$rawdata["SellingGeneralAdministrativeExpenses"][21]+$rawdata["SellingGeneralAdministrativeExpenses"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["ResearchDevelopmentExpense"][19]=='null'&&$rawdata["ResearchDevelopmentExpense"][20]=='null'&&$rawdata["ResearchDevelopmentExpense"][21]=='null'&&$rawdata["ResearchDevelopmentExpense"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["ResearchDevelopmentExpense"][19]+$rawdata["ResearchDevelopmentExpense"][20]+$rawdata["ResearchDevelopmentExpense"][21]+$rawdata["ResearchDevelopmentExpense"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["CFDepreciationAmortization"][19]=='null'&&$rawdata["CFDepreciationAmortization"][20]=='null'&&$rawdata["CFDepreciationAmortization"][21]=='null'&&$rawdata["CFDepreciationAmortization"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["CFDepreciationAmortization"][19]+$rawdata["CFDepreciationAmortization"][20]+$rawdata["CFDepreciationAmortization"][21]+$rawdata["CFDepreciationAmortization"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["EBITDA"][19]=='null'&&$rawdata["EBITDA"][20]=='null'&&$rawdata["EBITDA"][21]=='null'&&$rawdata["EBITDA"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["EBITDA"][19]+$rawdata["EBITDA"][20]+$rawdata["EBITDA"][21]+$rawdata["EBITDA"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["OperatingProfit"][19]=='null'&&$rawdata["OperatingProfit"][20]=='null'&&$rawdata["OperatingProfit"][21]=='null'&&$rawdata["OperatingProfit"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["OperatingProfit"][19]+$rawdata["OperatingProfit"][20]+$rawdata["OperatingProfit"][21]+$rawdata["OperatingProfit"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["EBIT"][19]=='null'&&$rawdata["EBIT"][20]=='null'&&$rawdata["EBIT"][21]=='null'&&$rawdata["EBIT"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["EBIT"][19]+$rawdata["EBIT"][20]+$rawdata["EBIT"][21]+$rawdata["EBIT"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $query .= ((($rawdata["IncomeTaxes"][19]=='null'&&$rawdata["IncomeTaxes"][20]=='null'&&$rawdata["IncomeTaxes"][21]=='null'&&$rawdata["IncomeTaxes"][22]=='null')||($rawdata["IncomeBeforeTaxes"][19]=='null'&&$rawdata["IncomeBeforeTaxes"][20]=='null'&&$rawdata["IncomeBeforeTaxes"][21]=='null'&&$rawdata["IncomeBeforeTaxes"][22]=='null')||($rawdata["IncomeBeforeTaxes"][19]+$rawdata["IncomeBeforeTaxes"][20]+$rawdata["IncomeBeforeTaxes"][21]+$rawdata["IncomeBeforeTaxes"][22]==0))?'null':(($rawdata["IncomeTaxes"][19]+$rawdata["IncomeTaxes"][20]+$rawdata["IncomeTaxes"][21]+$rawdata["IncomeTaxes"][22])/($rawdata["IncomeBeforeTaxes"][19]+$rawdata["IncomeBeforeTaxes"][20]+$rawdata["IncomeBeforeTaxes"][21]+$rawdata["IncomeBeforeTaxes"][22]))).",";
                $query .= ((($rawdata["IncomeTaxes"][19]=='null'&&$rawdata["IncomeTaxes"][20]=='null'&&$rawdata["IncomeTaxes"][21]=='null'&&$rawdata["IncomeTaxes"][22]=='null')&&($rawdata["IncomeBeforeTaxes"][19]=='null'&&$rawdata["IncomeBeforeTaxes"][20]=='null'&&$rawdata["IncomeBeforeTaxes"][21]=='null'&&$rawdata["IncomeBeforeTaxes"][22]=='null'))?'null':(($rawdata["IncomeBeforeTaxes"][19]+$rawdata["IncomeBeforeTaxes"][20]+$rawdata["IncomeBeforeTaxes"][21]+$rawdata["IncomeBeforeTaxes"][22])-($rawdata["IncomeTaxes"][19]+$rawdata["IncomeTaxes"][20]+$rawdata["IncomeTaxes"][21]+$rawdata["IncomeTaxes"][22]))).",";
                $query .= ((($rawdata["NetIncome"][19]=='null'&&$rawdata["NetIncome"][20]=='null'&&$rawdata["NetIncome"][21]=='null'&&$rawdata["NetIncome"][22]=='null')||($rawdata["TotalRevenue"][19]=='null'&&$rawdata["TotalRevenue"][20]=='null'&&$rawdata["TotalRevenue"][21]=='null'&&$rawdata["TotalRevenue"][22]=='null')||($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]==0))?'null':(($rawdata["NetIncome"][19]+$rawdata["NetIncome"][20]+$rawdata["NetIncome"][21]+$rawdata["NetIncome"][22])/($rawdata["TotalRevenue"][19]+$rawdata["TotalRevenue"][20]+$rawdata["TotalRevenue"][21]+$rawdata["TotalRevenue"][22]))).",";
                $value = 0;
                if(($rawdata["DividendsPaid"][19]=='null'&&$rawdata["DividendsPaid"][20]=='null'&&$rawdata["DividendsPaid"][21]=='null'&&$rawdata["DividendsPaid"][22]=='null')||($rawdata["SharesOutstandingBasic"][19]=='null'&&$rawdata["SharesOutstandingBasic"][20]=='null'&&$rawdata["SharesOutstandingBasic"][21]=='null'&&$rawdata["SharesOutstandingBasic"][22]=='null')||($rawdata["SharesOutstandingBasic"][19]+$rawdata["SharesOutstandingBasic"][20]+$rawdata["SharesOutstandingBasic"][21]+$rawdata["SharesOutstandingBasic"][22]==0)) {
                        $value = "'null'";
                } else {
                        if($rawdata["DividendsPaid"][19]!='null'&&$rawdata["SharesOutstandingBasic"][19]!='null'&&$rawdata["SharesOutstandingBasic"][19]!=0) {
                                $value -= ($rawdata["DividendsPaid"][19]/(toFloat($rawdata["SharesOutstandingBasic"][19])*1000000));
                        }
                        if($rawdata["DividendsPaid"][20]!='null'&&$rawdata["SharesOutstandingBasic"][20]!='null'&&$rawdata["SharesOutstandingBasic"][20]!=0) {
                                $value -= ($rawdata["DividendsPaid"][20]/(toFloat($rawdata["SharesOutstandingBasic"][20])*1000000));
                        }
                        if($rawdata["DividendsPaid"][21]!='null'&&$rawdata["SharesOutstandingBasic"][21]!='null'&&$rawdata["SharesOutstandingBasic"][21]!=0) {
                                $value -= ($rawdata["DividendsPaid"][21]/(toFloat($rawdata["SharesOutstandingBasic"][21])*1000000));
                        }
                        if($rawdata["DividendsPaid"][22]!='null'&&$rawdata["SharesOutstandingBasic"][22]!='null'&&$rawdata["SharesOutstandingBasic"][22]!=0) {
                                $value -= ($rawdata["DividendsPaid"][22]/(toFloat($rawdata["SharesOutstandingBasic"][22])*1000000));
                        }
                }
                $query .= $value.",";
                $query .= ((($rawdata["CurrentPortionofLongtermDebt"][19]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][20]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][21]=='null'&&$rawdata["CurrentPortionofLongtermDebt"][22]=='null')&&($rawdata["ShorttermBorrowings"][19]=='null'&&$rawdata["ShorttermBorrowings"][20]=='null'&&$rawdata["ShorttermBorrowings"][21]=='null'&&$rawdata["ShorttermBorrowings"][22]=='null'))?'null':($rawdata["CurrentPortionofLongtermDebt"][$MRQRow]+$rawdata["ShorttermBorrowings"][$MRQRow])).",";
                $query .= ((($rawdata["TotalLongtermDebt"][19]=='null'&&$rawdata["TotalLongtermDebt"][20]=='null'&&$rawdata["TotalLongtermDebt"][21]=='null'&&$rawdata["TotalLongtermDebt"][22]=='null')&&($rawdata["NotesPayable"][19]=='null'&&$rawdata["NotesPayable"][20]=='null'&&$rawdata["NotesPayable"][21]=='null'&&$rawdata["NotesPayable"][22]=='null'))?'null':($rawdata["TotalLongtermDebt"][$MRQRow]+$rawdata["NotesPayable"][$MRQRow])).",";
                $query .= ((($rawdata["LongtermDebtProceeds"][19]=='null'&&$rawdata["LongtermDebtProceeds"][20]=='null'&&$rawdata["LongtermDebtProceeds"][21]=='null'&&$rawdata["LongtermDebtProceeds"][22]=='null')&&($rawdata["LongtermDebtPayments"][19]=='null'&&$rawdata["LongtermDebtPayments"][20]=='null'&&$rawdata["LongtermDebtPayments"][21]=='null'&&$rawdata["LongtermDebtPayments"][22]=='null'))?'null':(($rawdata["LongtermDebtProceeds"][19]+$rawdata["LongtermDebtProceeds"][20]+$rawdata["LongtermDebtProceeds"][21]+$rawdata["LongtermDebtProceeds"][22])+($rawdata["LongtermDebtPayments"][19]+$rawdata["LongtermDebtPayments"][20]+$rawdata["LongtermDebtPayments"][21]+$rawdata["LongtermDebtPayments"][22]))).",";
                $query .= (($rawdata["CapitalExpenditures"][19]=='null'&&$rawdata["CapitalExpenditures"][20]=='null'&&$rawdata["CapitalExpenditures"][21]=='null'&&$rawdata["CapitalExpenditures"][22]=='null')?'null':(-($rawdata["CapitalExpenditures"][19]+$rawdata["CapitalExpenditures"][20]+$rawdata["CapitalExpenditures"][21]+$rawdata["CapitalExpenditures"][22]))).",";
                $query .= ((($rawdata["CashfromOperatingActivities"][19]=='null'&&$rawdata["CashfromOperatingActivities"][20]=='null'&&$rawdata["CashfromOperatingActivities"][21]=='null'&&$rawdata["CashfromOperatingActivities"][22]=='null')&&($rawdata["CapitalExpenditures"][19]=='null'&&$rawdata["CapitalExpenditures"][20]=='null'&&$rawdata["CapitalExpenditures"][21]=='null'&&$rawdata["CapitalExpenditures"][22]=='null'))?'null':(($rawdata["CashfromOperatingActivities"][19]+$rawdata["CashfromOperatingActivities"][20]+$rawdata["CashfromOperatingActivities"][21]+$rawdata["CashfromOperatingActivities"][22])+($rawdata["CapitalExpenditures"][19]+$rawdata["CapitalExpenditures"][20]+$rawdata["CapitalExpenditures"][21]+$rawdata["CapitalExpenditures"][22]))).",";
                $query .= ((($rawdata["CFNetIncome"][19]=='null'&&$rawdata["CFNetIncome"][20]=='null'&&$rawdata["CFNetIncome"][21]=='null'&&$rawdata["CFNetIncome"][22]=='null')&&($rawdata["CFDepreciationAmortization"][19]=='null'&&$rawdata["CFDepreciationAmortization"][20]=='null'&&$rawdata["CFDepreciationAmortization"][21]=='null'&&$rawdata["CFDepreciationAmortization"][22]=='null')&&($rawdata["EmployeeCompensation"][19]=='null'&&$rawdata["EmployeeCompensation"][20]=='null'&&$rawdata["EmployeeCompensation"][21]=='null'&&$rawdata["EmployeeCompensation"][22]=='null')&&($rawdata["AdjustmentforSpecialCharges"][19]=='null'&&$rawdata["AdjustmentforSpecialCharges"][20]=='null'&&$rawdata["AdjustmentforSpecialCharges"][21]=='null'&&$rawdata["AdjustmentforSpecialCharges"][22]=='null')&&($rawdata["DeferredIncomeTaxes"][19]=='null'&&$rawdata["DeferredIncomeTaxes"][20]=='null'&&$rawdata["DeferredIncomeTaxes"][21]=='null'&&$rawdata["DeferredIncomeTaxes"][22]=='null')&&($rawdata["CapitalExpenditures"][19]=='null'&&$rawdata["CapitalExpenditures"][20]=='null'&&$rawdata["CapitalExpenditures"][21]=='null'&&$rawdata["CapitalExpenditures"][22]=='null')&&($rawdata["ChangeinCurrentAssets"][19]=='null'&&$rawdata["ChangeinCurrentAssets"][20]=='null'&&$rawdata["ChangeinCurrentAssets"][21]=='null'&&$rawdata["ChangeinCurrentAssets"][22]=='null')&&($rawdata["ChangeinCurrentLiabilities"][19]=='null'&&$rawdata["ChangeinCurrentLiabilities"][20]=='null'&&$rawdata["ChangeinCurrentLiabilities"][21]=='null'&&$rawdata["ChangeinCurrentLiabilities"][22]=='null'))?'null':(($rawdata["CFNetIncome"][19]+$rawdata["CFNetIncome"][20]+$rawdata["CFNetIncome"][21]+$rawdata["CFNetIncome"][22])+($rawdata["CFDepreciationAmortization"][19]+$rawdata["CFDepreciationAmortization"][20]+$rawdata["CFDepreciationAmortization"][21]+$rawdata["CFDepreciationAmortization"][22])+($rawdata["EmployeeCompensation"][19]+$rawdata["EmployeeCompensation"][20]+$rawdata["EmployeeCompensation"][21]+$rawdata["EmployeeCompensation"][22])+($rawdata["AdjustmentforSpecialCharges"][19]+$rawdata["AdjustmentforSpecialCharges"][20]+$rawdata["AdjustmentforSpecialCharges"][21]+$rawdata["AdjustmentforSpecialCharges"][22])+($rawdata["DeferredIncomeTaxes"][19]+$rawdata["DeferredIncomeTaxes"][20]+$rawdata["DeferredIncomeTaxes"][21]+$rawdata["DeferredIncomeTaxes"][22])+($rawdata["CapitalExpenditures"][19]+$rawdata["CapitalExpenditures"][20]+$rawdata["CapitalExpenditures"][21]+$rawdata["CapitalExpenditures"][22])+(($rawdata["ChangeinCurrentAssets"][19]+$rawdata["ChangeinCurrentAssets"][20]+$rawdata["ChangeinCurrentAssets"][21]+$rawdata["ChangeinCurrentAssets"][22])+($rawdata["ChangeinCurrentLiabilities"][19]+$rawdata["ChangeinCurrentLiabilities"][20]+$rawdata["ChangeinCurrentLiabilities"][21]+$rawdata["ChangeinCurrentLiabilities"][22]))));
        	$query .= ")";
	       	mysql_query($query) or die ($query." ".mysql_error());
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
