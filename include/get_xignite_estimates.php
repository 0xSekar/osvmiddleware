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
              	"EstimateGroup" => "All"
	);
	
        // add authentication info
        $xignite_header = new SoapHeader('http://www.xignite.com/services/', "Header", array("Username" => "jae.jun@oldschoolvalue.com"));
        $client->__setSoapHeaders(array($xignite_header));

        $fields= "Security_Ticker or Security_CIK or Security_Cusip or Security_ISIN or Security_CompanyName";
        // call the service, passing the parameters and the name of the operation
        $result = $client->GetResearchFieldLists($param);

	$data = $result->GetResearchFieldListsResult->EstimatesResearchFieldList;
	if (isset($data->EstimatesResearchFields)) {
                $query = "delete from tickers_xignite_estimates where ticker_id = " . $ticker_id;
                $res = mysql_query($query) or die (mysql_error());
		$data = $data->EstimatesResearchFields->EstimatesResearchField;
		$query = "INSERT INTO tickers_xignite_estimates SET ticker_id = ".$ticker_id;
		foreach( $data as $estimate) {
	                if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryCurrentFiscalYearVsIndustryMostRecentFiscalYearIndustry") {
        	                $query .= ", `SA_PerDiffIndustryCurrentFYVsIndustryMostRecentFYIndustry`=";
                	} else if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryCurrentFiscalYearVsIndustryMostRecentFiscalYearSector") {
                        	$query .= ", `SA_PerDiffIndustryCurrentFYVsIndustryMostRecentFYSector`=";
	                } else if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryNextFiscalYearVsIndustryCurrentFiscalYearIndustry") {
        	                $query .= ", `SA_PerDiffIndustryNextFYVsIndustryCurrentFYIndustry`=";
                	} else if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryNextFiscalYearVsIndustryCurrentFiscalYearSector") {
                        	$query .= ", `SA_PerDiffIndustryNextFYVsIndustryCurrentFYSector`=";
	                } else if(substr($estimate->FieldType,0,18) == "EarningsEstimates_") {
        	                $query .= ", `EE" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
                	} else if (substr($estimate->FieldType,0,21) == "EarningsEstimatesCons") {
                        	$query .= ", `EECT" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
	                } else if (substr($estimate->FieldType,0,17) == "EarningsSurprise_") {
        	                $query .= ", `ES" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
                	} else if (substr($estimate->FieldType,0,15) == "EPSGrowthRates_") {
                        	$query .= ", `EGR" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
	                } else if (substr($estimate->FieldType,0,15) == "SectorAnalysis_") {
        	                $query .= ", `SA" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
                	} else if (substr($estimate->FieldType,0,15) == "EPSEstimatesAnd") {
                        	$query .= ", `EER" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
	                }

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
