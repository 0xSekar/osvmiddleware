<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include_once('../db/db.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
$query = "delete a from reports_valuation a left join reports_header b on a.report_id = b.id where b.id IS null";
try {
	$res = $db->exec($query);
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die("- Line: ".__LINE__." - ".$ex->getMessage());
}
$query = "SELECT * FROM reports_header where report_type='ANN' order by ticker_id, fiscal_year";
try {
	$res = $db->query($query);
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die("- Line: ".__LINE__." - ".$ex->getMessage());
}
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
	$query = "SELECT * FROM `reports_header` a, reports_variable_ratios b, reports_metadata_eol c, reports_incomefull d, reports_incomeconsolidated e, reports_financialheader f, reports_cashflowfull g, reports_cashflowconsolidated h, reports_balancefull i, reports_balanceconsolidated j, reports_gf_data k, reports_financialscustom l WHERE a.id=b.report_id AND a.id=c.report_id AND a.id=d.report_id AND a.id=e.report_id AND a.id=f.report_id AND a.id=g.report_id AND a.id=h.report_id AND a.id=i.report_id AND a.id=j.report_id AND a.id=k.report_id AND a.id=l.report_id AND a.id= " . $row["id"];
	try {
		$res2 = $db->query($query);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}
	$rawdata = $res2->fetch(PDO::FETCH_ASSOC);
	$rdate = date("Y-m-d",strtotime($rawdata["report_date"]));
	$qquote = "Select * from tickers_yahoo_historical_data where ticker_id = '".$rawdata["ticker_id"]."' and report_date <= '".$rdate."' order by report_date desc limit 1";
	$price = null;
	try {
		$rquote = $db->query($qquote);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
	}

	if($rquote->rowCount() > 0) {                	 
		$price = $rquote->fetch(PDO::FETCH_ASSOC);
		$rdate = $price["report_date"];
		$price = $price["adj_close"];
	}

        $query = "INSERT INTO `reports_valuation` (`report_id`, `nnwc`, `p_nnwc`, `mos_nnwc`, `ncav`, `p_ncav`, `mos_ncav`) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `nnwc`=?, `p_nnwc`=?, `mos_nnwc`=?, `ncav`=?, `p_ncav`=?, `mos_ncav`=?";
        $params = array();
        $nnwc = $rawdata["CashCashEquivalentsandShorttermInvestments"] + $rawdata["TotalReceivablesNet"] * 0.75 + $rawdata["TotalInventories"] * 0.5 * 1000000 - $rawdata["TotalLiabilities"];
        $ncav = $rawdata["TotalCurrentAssets"] - $rawdata["TotalLiabilities"];
        $p_nnwc = (($rawdata["SharesOutstandingDiluted"]=='null'||is_null($price)||$nnwc==0)? null:(toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price/$nnwc));
        $p_ncav = (($rawdata["SharesOutstandingDiluted"]=='null'||is_null($price)||$ncav==0)? null:(toFloat($rawdata["SharesOutstandingDiluted"])*1000000*$price/$ncav));
        $params[] = $nnwc;
        $params[] = $p_nnwc;
        $params[] = ((is_null($p_nnwc) || (1-$p_nnwc)*100 < 0 || $nnwc < 0) ? 0:((1-$p_nnwc)*100));
        $params[] = $ncav;
        $params[] = $p_ncav;
        $params[] = ((is_null($p_ncav) || (1-$p_ncav)*100 < 0 || $ncav < 0) ? 0:((1-$p_ncav)*100));

        $params = array_merge($params,$params);
        array_unshift($params,$row["id"]);

	try {
		$res1 = $db->prepare($query);
		$res1->execute($params);
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("- Line: ".__LINE__." - ".$ex->getMessage());
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
