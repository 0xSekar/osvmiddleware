<?php
function get_xignite_estimates_data($ticker_id, $symbol) {
	$query = "select *, TIMESTAMPDIFF(MINUTE , last_estimates_date, NOW( )) as tdiff from tickers_control where ticker_id = " . $ticker_id;
	$res = mysql_query($query) or die (mysql_error());
	$row = mysql_fetch_assoc($res);
	if (isset($row) && isset($row["last_estimates_date"])) {
		//record exists, check date
		if($row["tdiff"] > 1440) {
			//Old data, update
			update_xignite_estimates_data($ticker_id, $symbol);
		}
	} else {
		//Missing data, update
		update_xignite_estimates_data($ticker_id, $symbol);
	}
	$query = "SELECT * FROM `tickers_xignite_estimates` a WHERE a.ticker_id= " . $ticker_id;
	$res = mysql_query($query) or die (mysql_error());
	$row = mysql_fetch_assoc($res);
	return $row;
}

function update_xignite_estimates_data($ticker_id, $symbol) {
	$client = new soapclient('http://www.xignite.com/xEstimates.asmx?WSDL');
	$ticker = array($symbol);
        $param = array(
  	      	"Identifiers" => $symbol,
              	"IdentifierType" => "Symbol",
              	"EstimateFieldTypes" => array(
			"EarningsEstimates_CurrentFiscalYearEndDate",
			"EarningsEstimates_CurrentFiscalYearMean",
			"EarningsEstimates_CurrentFiscalYearYearPriorActualEarningsPerShare",
			"EarningsEstimates_CurrentQuarterEndDate",
			"EarningsEstimates_CurrentQuarterMean",
			"EarningsEstimates_CurrentQuarterYearPriorActualEarningsPerShare",
			"EarningsEstimates_LongTermGrowthCurrentMean",
			"EarningsEstimates_LongTermGrowthHighEstimate",
			"EarningsEstimates_LongTermGrowthLowEstimate",
			"EarningsEstimates_NextFiscalYearCurrentMean",
			"EarningsEstimates_NextFiscalYearEndDate",
			"EarningsEstimates_NextFiscalYearYearEstimatedEarningsPerShare",
			"EarningsEstimates_NextQuarterCurrentMean",
			"EarningsEstimates_NextQuarterEndDate",
			"EarningsEstimates_NextQuarterYearPriorActualEarningsPerShare",
			"EarningsEstimates_PercentGrowthNextFiscalYearMeanOverCurrentFiscalYearMean",
			"EarningsEstimatesConsensusTrend_LongTermGrowthMeanCurrent",
			"EarningsEstimatesConsensusTrend_NextFiscalYearEndDate",
			"EPSEstimatesAndRecommendations_IndustryName",
			"EPSEstimatesAndRecommendations_NextQuarterToReportExpectedReportDate",
			"EPSGrowthRates_CompanyIndustryCurrentFiscalYearEnd",
			"EPSGrowthRates_CompanyIndustryNextFiscalYearEnd",
			"EPSGrowthRates_CompanyLast5YearActualPercentageGrowth",
			"EPSGrowthRates_CompanyLongTermGrowthRate",
			"EPSGrowthRates_CompanyNextFiscalYearPERatio",
			"EPSGrowthRates_IndustryLast5YearActualPercentGrowth",
			"EPSGrowthRates_IndustryLongTermGrowthRate",
			"EPSGrowthRates_IndustryNextFiscalYearPERatio",
			"EPSGrowthRates_SP500Last5YearActualPercentGrowth",
			"EPSGrowthRates_SP500LongTermGrowthRate",
			"EPSGrowthRates_SP500NextFiscalYearPERatio",
			"SectorAnalysis_CurrentFiscalYearPriceEarningsGrowthIndustry",
			"SectorAnalysis_CurrentFiscalYearPriceEarningsGrowthSector",
			"SectorAnalysis_CurrentFiscalYearPriceEarningsGrowthSP500",
			"SectorAnalysis_FiveYearHistoricEarningsPerShareGrowthCompany",
			"SectorAnalysis_FiveYearHistoricEarningsPerShareGrowthIndustry",
			"SectorAnalysis_FiveYearHistoricEarningsPerShareGrowthSector",
			"SectorAnalysis_FiveYearHistoricEarningsPerShareGrowthSP500",
			"SectorAnalysis_IndustryCurrentFiscalYearEstimateIndustry",
			"SectorAnalysis_IndustryCurrentFiscalYearEstimateSector",
			"SectorAnalysis_IndustryMostRecentFiscalYearActualIndustry",
			"SectorAnalysis_IndustryMostRecentFiscalYearActualSector",
			"SectorAnalysis_IndustryName",
			"SectorAnalysis_IndustryNextFiscalYearEstimateIndustry",
			"SectorAnalysis_IndustryNextFiscalYearEstimateSector",
			"SectorAnalysis_MeanEstimateIndustryLongTermGrowth",
			"SectorAnalysis_MeanEstimateSectorLongTermGrowth",
			"SectorAnalysis_MeanEstimateSP500LongTermGrowth",
			"SectorAnalysis_NextQuarterEstimateIndustry",
			"SectorAnalysis_NextQuarterEstimateSector",
			"SectorAnalysis_NextQuarterEstimateSP500",
			"SectorAnalysis_Price",
			"SectorAnalysis_SectorName")
	);
	
        // add authentication info
        $xignite_header = new SoapHeader('http://www.xignite.com/services/', "Header", array("Username" => "jae.jun@oldschoolvalue.com"));
        $client->__setSoapHeaders(array($xignite_header));

        $fields= "Security_Ticker or Security_CIK or Security_Cusip or Security_ISIN or Security_CompanyName";
        // call the service, passing the parameters and the name of the operation
        $result = $client->GetResearchFieldListsByCollection($param);

	$data = $result->GetResearchFieldListsByCollectionResult->EstimatesResearchFieldList;
	if (isset($data->EstimatesResearchFields)) {
                $query = "delete from tickers_xignite_estimates where ticker_id = " . $ticker_id;
                $res = mysql_query($query) or die (mysql_error());
		$data = $data->EstimatesResearchFields->EstimatesResearchField;
		$query = "INSERT INTO tickers_xignite_estimates SET ticker_id = ".$ticker_id;
		foreach( $data as $estimate) {
			$query .= ", `" . substr($estimate->FieldType,0,64) . "` = ";
			if ($estimate->DataType == "Text") {
				$query .= "'" . mysql_real_escape_string($estimate->Value) . "'";
			} else if ($estimate->DataType == "Date" && $estimate->DataFormat == "yyyyMMdd") {
				$query .= "'" . substr($estimate->Value,0,4) ."-". substr($estimate->Value,4,2) ."-". substr($estimate->Value,6,2) . "'";
			} else if ($estimate->DataType == "Date" && $estimate->DataFormat == "yyyyMM") {
				$query .= "'" . substr($estimate->Value,0,4) ."-". substr($estimate->Value,4,2) . "-01'";
			} else {
				$query .= (strlen($estimate->Value)==0?"NULL":str_replace(',', '', $estimate->Value));
			}
		}
		$query .= ";";
		$res = mysql_query($query) or die ($query ."\n" . mysql_error());
                $query_up = "UPDATE tickers_control SET last_estimates_date = NOW() WHERE ticker_id = " . $ticker_id;
                mysql_query($query_up) or die(mysql_error());
	}
}
?>
