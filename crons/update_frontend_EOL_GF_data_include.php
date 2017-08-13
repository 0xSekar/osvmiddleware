<?php
function update_frontend_EOL_GF_data($eticker = null) {
	$db = Database::GetInstance(); 
	//Access on dev environment
	$username = 'osv';
	$password = 'test1234!';
	$context = stream_context_create(array(
				'http' => array(
					'header'  => "Authorization: Basic " . base64_encode("$username:$password")
					)
				));

	//Get full list of symbols from backend
	$symbols = file_get_contents("http://".SERVERHOST."/webservice/get_ticker_list_frontend.php", false, $context);
	$result = json_decode($symbols);
	$count = 0;
	$inserted = 0;
	$updated = 0;
	$areports = AREPORTS;
	$qreports = QREPORTS;
	$treports = $areports+$qreports;
	$update_array = array(".","'");
	$newtick = array();
	echo "Updating ticker lists....<br>\n";
	//Process the tickers and add any missing ticket to the tables (only basic ticker data)
	foreach ($result as $key => $symbol) {
		$result[$key]->ticker = str_replace($update_array, "-", $result[$key]->ticker);
	}
	foreach ($result as $symbol) {
		if(!is_null($eticker) && strcasecmp($eticker,$symbol->ticker)) {
			continue;
		}
		$count ++;
		try {
			$res = $db->query("SELECT count(*) as C FROM tickers WHERE ticker = '$symbol->ticker'");
			$counter = $res->fetch(PDO::FETCH_OBJ);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		if ($counter->C == 0) {
			try {
				$qe = "SELECT name_to AS exchange FROM exchange_conversion WHERE name_from = ?";
				$params = array();
				$params[] = $symbol->exchange;

				$re = $db->prepare($qe);
				$re ->execute($params);
				if(!$rowe = $re->fetch(PDO::FETCH_ASSOC)) {
					$rowe["exchange"] = "";
				}
			} catch(PDOException $ex) {
				echo "\nDatabase Error"; //user message
				die("Line: ".__LINE__." - ".$ex->getMessage());
			}
			$inserted ++;
			try {
				$res = $db->prepare("INSERT INTO tickers (ticker, cik, company, exchange, sic, entityid, formername, industry, sector, country) VALUES (:ticker, :cik, :company, :exchange, :sic, :entityid, :formername, :industry, :sector, :country)");
				$res->execute(array(
							':ticker' => (is_null($symbol->ticker)?'':$symbol->ticker),
							':cik' => (is_null($symbol->cik)?'':$symbol->cik), 
							':company' => (is_null($symbol->company)?'':$symbol->company), 
							':exchange' => $rowe["exchange"], 
							':sic' => (is_null($symbol->siccode)?'':$symbol->siccode), 
							':entityid' => (is_null($symbol->entityid)?'':$symbol->entityid), 
							':formername' => (is_null($symbol->formername)?'':$symbol->formername), 
							':industry' => (is_null($symbol->industry)?'':$symbol->industry), 
							':sector' => (is_null($symbol->sector)?'':$symbol->sector),
							':country' => (is_null($symbol->country)?'':$symbol->country)
						   ));
				$id = $db->lastInsertId();
				$res = $db->exec("INSERT into tickers_control (ticker_id, last_eol_date, last_yahoo_date, last_barchart_date, last_volatile_date, last_estimates_date) VALUES ($id, '2000-01-01', '2000-01-01', '2000-01-01', '2000-01-01', '2000-01-01')");
				update_yahoo_daily($symbol->ticker);
				$newtick[] = $id;
			} catch(PDOException $ex) {
				echo "\nDatabase Error"; //user message
				die("Line: ".__LINE__." - ".$ex->getMessage());
			}
		}	
	}

	$symbols2 = file_get_contents("http://".SERVERHOST."/webservice/get_ticker_list_frontend_extra.php", false, $context);
	$result2 = json_decode($symbols2);
	foreach ($result2 as $key => $symbol) {
		$result2[$key]->ticker = str_replace($update_array, "-", $result2[$key]->ticker);
	}
	foreach ($result2 as $symbol2) {
		if(!is_null($eticker) && strcasecmp($eticker,$symbol2->ticker)) {
			continue;
		}
		$count ++;
		try {
			$res = $db->query("SELECT count(*) as C FROM tickers WHERE ticker = '$symbol2->ticker'");
			$counter = $res->fetch(PDO::FETCH_OBJ);
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		if ($counter->C == 0) {
			$fixdate = $symbol2->insdate;
			$fixticker = $symbol2->ticker;
			$fixtype = $symbol2->reporttype;
			if (!is_null($fixdate) && $fixtype != "Dummy") {
				$inserted ++;
				$csv = file_get_contents("http://".SERVERHOST."/webservice/createcsv.php?source=frontend&ticker=".$fixticker, false, $context);
				$csvst = fopen('php://memory', 'r+');
				fwrite($csvst, $csv);
				unset($csv);
				fseek($csvst, 0);
				$rawdata = array();
				while ($data = fgetcsv($csvst)) {
					$rawdata[$data[0]] = $data;
				}
				try {
					$qe = "SELECT name_to AS exchange FROM exchange_conversion WHERE name_from = ?";
					$params = array();
					$params[] = $rawdata["PrimaryExchange"][$treports];

					$re = $db->prepare($qe);
					$re ->execute($params);
					if(!$rowe = $re->fetch(PDO::FETCH_ASSOC)) {
						$rowe["exchange"] = "";
					}

					$res = $db->prepare("INSERT INTO tickers (ticker, cik, company, exchange, sic, entityid, formername, industry, sector, country) VALUES (:ticker, :cik, :company, :exchange, :sic, :entityid, :formername, :industry, :sector, :country)");
					$res->execute(array(':ticker' => (is_null($symbol2->ticker)?'':$symbol2->ticker),
								':cik' => (is_null($rawdata["CIK"][$treports])?'':$rawdata["CIK"][$treports]), 
								':company' => (is_null($rawdata["COMPANYNAME"][$treports])?'':$rawdata["COMPANYNAME"][$treports]), 
								':exchange' => $rowe["exchange"], 
								':sic' => (is_null($rawdata["SICCode"][$treports])?'':$rawdata["SICCode"][$treports]), 
								':entityid' => (is_null($rawdata["entityid"][$treports])?'':$rawdata["entityid"][$treports]), 
								':formername' => (is_null($rawdata["Formername"][$treports])?'':$rawdata["Formername"][$treports]), 
								':industry' => (is_null($rawdata["Industry"][$treports])?'':$rawdata["Industry"][$treports]), 
								':sector' => (is_null($rawdata["Sector"][$treports])?'':$rawdata["Sector"][$treports]),
								':country' => (is_null($rawdata["Country"][$treports])?'':$rawdata["Country"][$treports])));
					$id = $db->lastInsertId();					

					$res = $db->query("INSERT into tickers_control (ticker_id, last_eol_date, last_yahoo_date, last_barchart_date, last_volatile_date, last_estimates_date) VALUES ($id, '2000-01-01', '2000-01-01', '2000-01-01', '2000-01-01', '2000-01-01')");
					update_yahoo_daily($symbol2->ticker);
					$newtick[] = $id;
				} catch(PDOException $ex) {
					echo "\nDatabase Error"; //user message
					die("Line: ".__LINE__." - ".$ex->getMessage());
				}
				fclose($csvst);
			}
		}
	}

	echo "$count total rows. $inserted new rows<br>\n";

	//For each symbol in the database, check if there is new reports
	//based on the last report date in the resultset
	echo "Updating data points... (run 1) <br>\n";
	foreach ($result as $symbol) {
		if (is_null($symbol->ticker) || trim($symbol->ticker) == "") continue;
		if(!is_null($eticker) && strcasecmp($eticker,$symbol->ticker)) {
			continue;
		}
		//Get last local report date and compare with remote
		try { 
			$res = $db->query("SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$symbol->ticker'");        
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		$counter = $res->rowCount();
		if($counter == 0) continue;
		$dates = $res->fetch(PDO::FETCH_OBJ);

		//Fix for different tickers names on different databases
		$fixdate = $symbol->insdate;
		$fixticker = $symbol->ticker;
		$fixtype = $symbol->reporttype;
		//End fix for different tickers

		if (!is_null($fixdate) && $fixtype != "Dummy" && $dates->last_eol_date < $fixdate) {
			//If the remote report is newer, download the new report and update data points
			$updated++;
			echo "Downloading data for ".$fixticker."... ";
			$csv = file_get_contents("http://".SERVERHOST."/webservice/createcsv.php?source=frontend&ticker=".$fixticker, false, $context);
			echo "Updating ticker ".$symbol->ticker."<br>\n";
			$csvst = fopen('php://memory', 'r+');
			fwrite($csvst, $csv);
			unset($csv);
			fseek($csvst, 0);
			$rawdata = array();
			while ($data = fgetcsv($csvst)) {
				for($i=1; $i<=$treports;$i++) {
					if(!isset($data[$i])) {
						$data[$i] = "null";
					}
				}
				$rawdata[$data[0]] = $data;
			}
			array_walk_recursive($rawdata, 'nullValues');

			//Update Raw data
			if(isset($rawdata["AccountsPayableTurnoverDaysFY"])) {
				update_raw_data_tickers($dates, $rawdata);

				//Update Key ratios TTM
				update_key_ratios_ttm($dates->ticker_id);

				//Update Quality Checks
				update_pio_checks($dates->ticker_id);
				update_altman_checks($dates->ticker_id);
				update_beneish_checks($dates->ticker_id);
				update_dupont_checks($dates->ticker_id);
				update_accrual_checks($dates->ticker_id);

				//Finally update local report date
				try {
					$res = $db->query("UPDATE tickers_control SET last_eol_date = '$fixdate' WHERE ticker_id = $dates->ticker_id");
				} catch(PDOException $ex) {
					echo "\nDatabase Error"; //user message
					die("Line: ".__LINE__." - ".$ex->getMessage());
				}
			}
			fclose($csvst);
		}
	}

	echo "Updating data points... (run 2) <br>\n";
	foreach ($result2 as $symbol) {
		if (is_null($symbol->ticker) || trim($symbol->ticker) == "") continue;
		if(!is_null($eticker) && strcasecmp($eticker,$symbol->ticker)) {
			continue;
		}
		//Get last local report date and compare with remote
		try { 
			$res = $db->query("SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$symbol->ticker'");
		} catch(PDOException $ex) {
			echo "\nDatabase Error"; //user message
			die("Line: ".__LINE__." - ".$ex->getMessage());
		}
		$counter = $res->rowCount();
		if($counter == 0) continue;
		$dates = $res->fetch(PDO::FETCH_OBJ);

		//Fix for different tickers names on different databases
		$fixdate = $symbol->insdate;
		$fixticker = $symbol->ticker;
		$fixtype = $symbol->reporttype;
		//End fix for different tickers

		if (!is_null($fixdate) && $fixtype != "Dummy" && $dates->last_eol_date < $fixdate) {
			//If the remote report is newer, download the new report and update data points
			$updated++;
			echo "Downloading data for ".$fixticker."... ";
			$csv = file_get_contents("http://".SERVERHOST."/webservice/createcsv.php?source=frontend&ticker=".$fixticker, false, $context);
			echo "Updating ticker ".$symbol->ticker."<br>\n";
			$csvst = fopen('php://memory', 'r+');
			fwrite($csvst, $csv);
			unset($csv);
			fseek($csvst, 0);
			$rawdata = array();
			while ($data = fgetcsv($csvst)) {
				for($i=1; $i<=$treports;$i++) {
					if(!isset($data[$i])) {
						$data[$i] = "null";
					}
				}
				$rawdata[$data[0]] = $data;
			}
			array_walk_recursive($rawdata, 'nullValues');

			//Update Raw data
			if(isset($rawdata["AccountsPayableTurnoverDaysFY"])) {
				update_raw_data_tickers($dates, $rawdata);

				//Update Key ratios TTM
				update_key_ratios_ttm($dates->ticker_id);

				//Update Quality Checks
				update_pio_checks($dates->ticker_id);
				update_altman_checks($dates->ticker_id);
				update_beneish_checks($dates->ticker_id);
				update_dupont_checks($dates->ticker_id);
				update_accrual_checks($dates->ticker_id);

				//Finally update local report date
				try {
					$res = $db->query("UPDATE tickers_control SET last_eol_date = '$fixdate' WHERE ticker_id = $dates->ticker_id");
				} catch(PDOException $ex) {
					echo "\nDatabase Error"; //user message
					die("Line: ".__LINE__." - ".$ex->getMessage());
				}
			}
			fclose($csvst);
		}
	}
	echo "$count total rows. $updated stocks has new reports<br>\n";
	echo "Removing old Quality Checks (PIO)... ";
	try {
		$res = $db->query("delete a from reports_pio_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	echo "done<br>\n";
	echo "Removing old Quality Checks (ALTMAN)... ";
	try {
		$res = $db->query("delete a from reports_alt_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	echo "done<br>\n";
	echo "Removing old Quality Checks (BENEISH)... ";
	try {
		$res = $db->query("delete a from reports_beneish_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	echo "done<br>\n";
	echo "Removing old Quality Checks (DUPONT)... ";
	try {
		$res = $db->query("delete a from reports_dupont_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	echo "done<br>\n";
	echo "Removing old Quality Checks (ACCRUAL)... ";
	try {
		$res = $db->query("delete a from reports_accrual_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
	} catch(PDOException $ex) {
		echo "\nDatabase Error"; //user message
		die("Line: ".__LINE__." - ".$ex->getMessage());
	}
	echo "done<br>\n";
	echo "Updating Ratings... ";
	update_ratings();
	echo "done<br>\n";
	echo "Updating Ratings TTM... ";
	update_ratings_ttm();
	echo "done<br>\n";
	echo "Updating is_old tickers table field... ";
	update_is_old_field();
	echo "done<br>\n";

	echo "Updating EOD valuation for new tickers... ";
	foreach($newtick as $value) {
		update_eod_valuation($value);
	}
	echo "done<br>\n";
}
?>
