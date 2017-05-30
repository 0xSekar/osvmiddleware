<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE);
include_once('../config.php');
include_once('../db/db.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$db = Database::getInstance();

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$count2 = 0;
$eupdated = 0;
$enotfound = 0;
$eerror = 0;
echo "Updating Tickers...\n";
try {
	$query = "SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id WHERE is_old = FALSE";
	$res = $db->query($query);
} catch (Exception $e) {
	var_dump($e);
	$eerror++;
}
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
	try {
		$count2++;
		echo "Updating ".$row["ticker"]." estimates from Xignite...";

		$client = new soapclient('http://www.xignite.com/xEstimates.asmx?WSDL');
		$ticker = array($row["ticker"]);
		$param = array(
				"Identifiers" => $row["ticker"],
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
			$data = $data->EstimatesResearchFields->EstimatesResearchField;
			$query1 = "INSERT INTO tickers_xignite_estimates SET ticker_id = ".$row["ticker_id"];
			$query = xigniteString($data, true);

			$query .= " ON DUPLICATE KEY UPDATE ";
			$query .= xigniteString($data, false);

			$query1 .= $query.";";
			$db->query($query1);
			$query_up = "UPDATE tickers_control SET last_estimates_date = NOW() WHERE ticker_id = " . $row["ticker_id"];
			$db->query($query_up);
			$eupdated ++;
		} else {
			$enotfound ++;
		}
		echo " Done\n";
	} catch (Exception $e) {
		var_dump($e);
		$eerror++;
	}
}

echo "Removing orphan tickers from xignite... ";
try {
	$res = $db->query("delete a from tickers_xignite_estimates a left join tickers b on a.ticker_id = b.id where b.id IS null");
} catch (Exception $e) {
	var_dump($e);
}
echo "Done\n";

echo $count2 . " rows processed\n";
echo "Estimates:\n";
echo "\t".$eupdated." tickers updates\n";
echo "\t".$enotfound." tickers not found on xignite\n";
echo "\t".$eerror." errors procesing tickers\n";

function xigniteString($data, $full) {
	$db = Database::getInstance();
	$query = "";
	$first = true;
	foreach( $data as $estimate) {
		if($full || !$first) {
			$query .= ", ";
		} else {
			$first = false;
		}
		if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryCurrentFiscalYearVsIndustryMostRecentFiscalYearIndustry") {
			$query .= "`SA_PerDiffIndustryCurrentFYVsIndustryMostRecentFYIndustry`=";
		} else if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryCurrentFiscalYearVsIndustryMostRecentFiscalYearSector") {
			$query .= "`SA_PerDiffIndustryCurrentFYVsIndustryMostRecentFYSector`=";
		} else if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryNextFiscalYearVsIndustryCurrentFiscalYearIndustry") {
			$query .= "`SA_PerDiffIndustryNextFYVsIndustryCurrentFYIndustry`=";
		} else if ($estimate->FieldType == "SectorAnalysis_PercentDifferenceIndustryNextFiscalYearVsIndustryCurrentFiscalYearSector") {
			$query .= "`SA_PerDiffIndustryNextFYVsIndustryCurrentFYSector`=";
		} else if(substr($estimate->FieldType,0,18) == "EarningsEstimates_") {
			$query .= "`EE" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
		} else if (substr($estimate->FieldType,0,21) == "EarningsEstimatesCons") {
			$query .= "`EECT" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
		} else if (substr($estimate->FieldType,0,17) == "EarningsSurprise_") {
			$query .= "`ES" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
		} else if (substr($estimate->FieldType,0,15) == "EPSGrowthRates_") {
			$query .= "`EGR" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
		} else if (substr($estimate->FieldType,0,15) == "SectorAnalysis_") {
			$query .= "`SA" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
		} else if (substr($estimate->FieldType,0,15) == "EPSEstimatesAnd") {
			$query .= "`EER" . substr($estimate->FieldType,strpos($estimate->FieldType,"_"),60) . "`=";
		}

		if ($estimate->DataType == "Text") {
			$query .= $db->quote($estimate->Value);
		} else if ($estimate->DataType == "Date" && $estimate->DataFormat == "yyyyMMdd") {
			$query .= "'" . substr($estimate->Value,0,4) ."-". substr($estimate->Value,4,2) ."-". substr($estimate->Value,6,2) . "'";
		} else if ($estimate->DataType == "Date" && $estimate->DataFormat == "yyyyMM") {
			$query .= "'" . substr($estimate->Value,0,4) ."-". substr($estimate->Value,4,2) . "-01'";
		} else {
			$query .= (strlen($estimate->Value)==0?"NULL":str_replace(',', '', $db->quote($estimate->Value)));
		}
	}
	return $query;
}
?>
