<?php
require_once(dirname(__FILE__)."/../../db/db.php");

class screener_filter {
	//Database access
	private $db;

	//Auxiliary private variables
	private $fieldCol = array();

	//Constructor
	public function __construct($forSD = false) {
		$this->db = Database::getInstance();

		//Populate field list
		$this->fieldCol[-1]["id"] = array("table" => "tickers", "title" => "ID", "comment" => "Internal Ticker ID", "format" => "osvnumber:0", "stitle" => "ID", "min" => null, "max" => null);
		$this->fieldCol[-1]["ticker"] = array("table" => "tickers", "title" => "Symbol", "comment" => "Symbol", "format" => "osvtext", "stitle" => "Symbol", "min" => null, "max" => null);
		$flist = $this->getTooltip(null, null, null, array(0,1,2,3,4,5,6,7,8,9,10,11,12,13));
		foreach($flist as $field_group => $data1) {
			foreach($data1 as $field_name => $data) {
				$this->fieldCol[$field_group][$data["field_name"]] = array("table" => $data["table_name"], "title" => $data["title"], "comment" => $data["comment"], "format" => $data["format"], "stitle" => $data["short_title"], "min" => $data["min"], "max" => $data["max"], "sel_group" => $data["field_group"], "tooltip_id" => $data["tooltip_id"]);
			}
		}
	}

	public function fullFiltersReplace() {
		$q = $this->db->query("DROP TEMPORARY TABLE IF EXISTS screener_filter_fields_temp");
		$q = $this->db->query("DROP TEMPORARY TABLE IF EXISTS screener_filter_criteria_temp");
		$q = $this->db->query("CREATE TEMPORARY TABLE screener_filter_fields_temp LIKE screener_filter_fields");
		$q = $this->db->query("CREATE TEMPORARY TABLE screener_filter_criteria_temp LIKE screener_filter_criteria");
		$counter = 0;
		$pfinalrun = array();
		for ($i = 0; $i<14; $i++) {
			foreach ($this->fieldCol[$i] as $key => $value) {
				$params = array();
				$parid = array();
				$query = "INSERT INTO screener_filter_fields_temp (field_id, field_table_name, field_table_field, field_name, field_desc, field_type, field_group, field_order, report_type, format, min, max, tooltip_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$params[] = $value["table"];
				$params[] = $key;
				$parid[] = $value["table"];
				$parid[] = $key;
				$params[] = $value["title"];
				$params[] = $value["comment"];
				$type = $value["format"];
				if(substr($type, 0, 7) == "osvdate") {
					$params[] = "D";
				} else if ($type == "" || substr($type, 0, 7) == "osvtext" || substr($type, 0, 7) == "osvstri") {
					$params[] = "S";
				} else {
					$params[] = "N";
				}
				switch ($value["table"]) {
					case "mrq_alt_checks":
					case "reports_alt_checks":
					case "ttm_alt_checks":
						$params[] = 1;
						break;
					case "pttm_balanceconsolidated":
					case "ttm_balanceconsolidated":
					case "reports_balanceconsolidated":
					case "pttm_balancefull":
					case "ttm_balancefull":
					case "reports_balancefull":
						$params[] = 2;
						break;
					case "reports_balanceconsolidated_3cagr":
					case "reports_balanceconsolidated_5cagr":
					case "reports_balanceconsolidated_7cagr":
					case "reports_balanceconsolidated_10cagr":
					case "reports_balancefull_3cagr":
					case "reports_balancefull_5cagr":
					case "reports_balancefull_7cagr":
					case "reports_balancefull_10cagr":
						$params[] = 3;
						break;
					case "reports_beneish_checks":
					case "ttm_beneish_checks":
						$params[] = 4;
						break;
					case "pttm_cashflowconsolidated":
					case "ttm_cashflowconsolidated":
					case "reports_cashflowconsolidated":
					case "pttm_cashflowfull":
					case "ttm_cashflowfull":
					case "reports_cashflowfull":
						$params[] = 5;
						break;
					case "reports_cashflowconsolidated_3cagr":
					case "reports_cashflowconsolidated_5cagr":
					case "reports_cashflowconsolidated_7cagr":
					case "reports_cashflowconsolidated_10cagr":
					case "reports_cashflowfull_3cagr":
					case "reports_cashflowfull_5cagr":
					case "reports_cashflowfull_7cagr":
					case "reports_cashflowfull_10cagr":
						$params[] = 6;
						break;
					case "reports_metadata_eol":
					case "tickers":
						$params[] = 7;
						break;
					case "tickers_xignite_estimates":
					case "tickers_yahoo_estimates_curr_qtr":
					case "tickers_yahoo_estimates_curr_year":
					case "tickers_yahoo_estimates_earn_hist":
					case "tickers_yahoo_estimates_next_qtr":
					case "tickers_yahoo_estimates_next_year":
					case "tickers_yahoo_estimates_others":
						$params[] = 8;
						break;
					case "pttm_incomeconsolidated":
					case "ttm_incomeconsolidated":
					case "reports_incomeconsolidated":
						$params[] = 9;
						break;
					case "reports_incomeconsolidated_3cagr":
					case "reports_incomeconsolidated_5cagr":
					case "reports_incomeconsolidated_7cagr":
					case "reports_incomeconsolidated_10cagr":
					case "reports_incomefull_3cagr":
					case "reports_incomefull_5cagr":
					case "reports_incomefull_7cagr":
					case "reports_incomefull_10cagr":
						$params[] = 10;
						break;
					case "reports_financialscustom_3cagr":
					case "reports_financialscustom_5cagr":
					case "reports_financialscustom_7cagr":
					case "reports_financialscustom_10cagr":
					case "tickers_growth_ratios":
						$params[] = 12;
						break;
					case "ttm_ratings":
					case "reports_ratings":
						$params[] = 13;
						break;
					case "tickers_profitability_ratios":
					case "tickers_yahoo_keystats_2":
					case "tickers_yahoo_quotes_1":
					case "tickers_yahoo_quotes_2":
						$params[] = 14;
						break;
					case "tickers_yahoo_historical_data":
						$params[] = 15;
						break;
					case "ttm_pio_checks":
					case "reports_pio_checks":
						$params[] = 16;
						break;
					case "reports_variable_ratios":
					case "reports_variable_ratios_3cagr":
					case "reports_variable_ratios_5cagr":
					case "reports_variable_ratios_7cagr":
					case "reports_variable_ratios_10cagr":
					case "tickers_leverage_ratios":
						$params[] = 17;
						break;
					case "tickers_eod_valuation":
					case "reports_valuation":
						$params[] = 20;
						break;
					case "reports_valuation_3cagr":
					case "reports_valuation_5cagr":
					case "reports_valuation_7cagr":
					case "reports_valuation_10cagr":
						$params[] = 21;
						break;
					case "ttm_financialscustom":
					case "pttm_financialscustom":
					case "reports_financialscustom":
						switch ($key) {
							case "IncomeAfterTaxes":
								$params[] = 9;
								break;
							case "ShortTermDebtAndCurrentPortion":
							case "TotalLongTermDebtAndNotesPayable":
							case "NetChangeLongTermDebt":
								$params[] = 2;
								break;
							case "CapEx":
							case "FreeCashFlow":
							case "OwnerEarningsFCF":
								$params[] = 5;
								break;
							case "SalesPercChange":
							case "Sales5YYCGrPerc":
								$params[] = 10;
								break;
							default:
								$params[] = 17;
						}
						break;
					case "pttm_gf_data":
					case "ttm_gf_data":
					case "reports_gf_data":
						switch ($key) {
							case "Interest Income":
							case "Interest Expense":
							case "Basic Earnings per Share":
							case "Diluted Earnings per Share":
							case "Diluted Shares Outstanding":
							case "Basic Shares Outstanding":
								$params[] = 9;
								break;
							default:
								$params[] = 2;
						}
						break;
					case "reports_gf_data_3cagr":
					case "reports_gf_data_5cagr":
					case "reports_gf_data_7cagr":
					case "reports_gf_data_10cagr":
						switch ($key) {
							case "Interest Income":
							case "Interest Expense":
							case "Basic Earnings per Share":
							case "Diluted Earnings per Share":
							case "Diluted Shares Outstanding":
							case "Basic Shares Outstanding":
								$params[] = 10;
								break;
							default:
								$params[] = 3;
						}
						break;
					case "pttm_incomefull":
					case "ttm_incomefull":
					case "reports_incomefull":
						switch ($key) {
							case "AftertaxMargin":
							case "GrossMargin":
							case "OperatingMargin":
								$params[] = 17;
								break;
							default:
								$params[] = 9;
						}
						break;
					case "ttm_key_ratios":
					case "reports_key_ratios":
						switch ($key) {
							case "GoodwillIntangibleAssetsNet":
							case "TangibleBookValue":
							case "ExcessCash":
							case "TotalInvestedCapital":
							case "WorkingCapital":
								$params[] = 2;
								break;
							case "P_E":
							case "P_E_CashAdjusted":
							case "EV_EBITDA":
							case "EV_EBIT":
							case "P_S":
							case "P_BV":
							case "P_Tang_BV":
							case "P_CF":
							case "P_FCF":
							case "P_OwnerEarnings":
								$params[] = 20;
								break;
							default:
								$params[] = 17;
						}
						break;
					case "reports_key_ratios_3cagr":
					case "reports_key_ratios_5cagr":
					case "reports_key_ratios_7cagr":
					case "reports_key_ratios_10cagr":
						switch ($key) {
							case "GoodwillIntangibleAssetsNet":
							case "TangibleBookValue":
							case "ExcessCash":
							case "TotalInvestedCapital":
							case "WorkingCapital":
								$params[] = 3;
								break;
							case "P_E":
							case "P_E_CashAdjusted":
							case "EV_EBITDA":
							case "EV_EBIT":
							case "P_S":
							case "P_BV":
							case "P_Tang_BV":
							case "P_CF":
							case "P_FCF":
							case "P_OwnerEarnings":
								$params[] = 21;
								break;
							default:
								$params[] = 18;
						}
						break;
					case "tickers_yahoo_keystats_1":
						switch ($key) {
							case "DilutedEPSTTM":
								$params[] = 14;
								break;
							default:
								$params[] = 17;
						}
						break;
					default:
						$params[] = 19;
				}
				switch($i) {
					case 3:
					case 8:
						$params[] = $counter+10000;
						break;
					case 9:
						$params[] = $counter+20000;
						break;
					case 1:
						$params[] = $counter+40000;
						break;
					case 2:
						$params[] = $counter+30000;
						break;
					case 4:
					case 5:
					case 6:
					case 7:
						$params[] = $counter+50000;
						break;
					default:
						$params[] = $counter;
				}
				if($i == 1) {
					$params[] = "ANN";
					$parid[] = "ANN";
				} else if($i == 2) {
					$params[] = "QTR";
					$parid[] = "QTR";
				} else {
					$params[] = NULL;
				}
				$params[] = $type;
				$params[] = $value["min"];
				$params[] = $value["max"];
				$params[] = $value["tooltip_id"];

				//Get id if available
				if($i == 1 || $i == 2) {
					$queryid = "SELECT field_id FROM screener_filter_fields WHERE field_table_name = ? AND field_table_field = ? AND report_type = ?";
				} else {
					$queryid = "SELECT field_id FROM screener_filter_fields WHERE field_table_name = ? AND field_table_field = ? AND report_type IS NULL";
				}
				$q = $this->db->prepare($queryid);
				$q->execute($parid);
				$rid = $q->fetchColumn();
				$counter++;
				if(empty($rid)) {
					array_unshift($params, NULL);
					$pfinalrun[] = $params;
				} else {
					array_unshift($params, $rid);

					$q = $this->db->prepare($query);
					$q->execute($params);
					$lastId = $this->db->lastInsertId();

					//Also keep crit_id static
					$par = array();
					$par[] = $lastId;
					$par[] = "User Defined";
					$par[] = "cu";
					$par[] = 20;
					$queryid = "SELECT crit_id FROM screener_filter_criteria WHERE field_id = ?";
					$q = $this->db->prepare($queryid);
					$q->execute(array($lastId));
					$rid2 = $q->fetchColumn();
					if(empty($rid2)) {
						array_unshift($par, NULL);
					} else {
						array_unshift($par, $rid2);
					}
					$query = "INSERT INTO screener_filter_criteria_temp (crit_id, field_id, crit_text, crit_cond, crit_order) VALUES (?, ?, ?, ?, ?)";
					$q = $this->db->prepare($query);
					$q->execute($par);
				}
			}
		}
		//FINAL RUN
		$query = "INSERT INTO screener_filter_fields_temp (field_id, field_table_name, field_table_field, field_name, field_desc, field_type, field_group, field_order, report_type, format, min, max, tooltip_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$q = $this->db->prepare($query);
		$query1 = "INSERT INTO screener_filter_criteria_temp (field_id, crit_text, crit_cond, crit_order) VALUES (?, ?, ?, ?)";
		$q1 = $this->db->prepare($query1);
		foreach($pfinalrun as $params) {
			$q->execute($params);
			$lastId = $this->db->lastInsertId();
			$par = array();
			$par[] = $lastId;
			$par[] = "User Defined";
			$par[] = "cu";
			$par[] = 20;
			$q1 = $this->db->prepare($query1);
			$q1->execute($par);
		}
		$q = $this->db->query("truncate screener_filter_fields");
		$q = $this->db->query("truncate screener_filter_criteria");
		$q = $this->db->query("INSERT INTO screener_filter_fields SELECT * FROM screener_filter_fields_temp");
		$q = $this->db->query("INSERT INTO screener_filter_criteria SELECT * FROM screener_filter_criteria_temp");
		$q = $this->db->query("DROP TEMPORARY TABLE IF EXISTS screener_filter_fields_temp");
		$q = $this->db->query("DROP TEMPORARY TABLE IF EXISTS screener_filter_criteria_temp");
	}

	private function getTooltip($id = null, $table = null, $field = null, $group = array(), $addPrefix = true, $addSufix = true) {
		if(empty($id) && empty($table) && empty($field) && (empty($group) || !is_array($group))) {
			return array();
		}
		$params = array();
		$index = 0;
		$query = "SELECT * FROM tooltips ";
		if(!empty($id) && is_numeric($id)) {
			$query .= "WHERE tooltip_id = ?";
			$params[] = $id;
		} else {
			$cond = "WHERE ";
			if(!empty($group) && is_array($group)) {
				$in = join(',', array_fill(0, count($group), '?'));
				$query .= $cond . "table_group IN (" . $in . ") ";
				$cond = "AND ";
				$params = $group;
			}
			if(!empty($table)) {
				$query .= $cond . "table_name = ? ";
				$cond = "AND ";
				$params[] = $table;
			}
			if(!empty($field)) {
				$query .= $cond . "field_name = ? ";
				$cond = "AND ";
				$params[] = $field;
			}
			$index = 1;
		}
		$res = $this->db->prepare($query);
		$res->execute($params);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		$result = array();
		foreach ($row as $line) {
			if($addPrefix) {
				switch($line["table_group"]) {
					case 1:
						$line["comment"] = "Latest Annual. " . $line["comment"];
						break;
					case 2:
					case 9:
						$line["comment"] = "Most Recent Quarter. " . $line["comment"];
						break;
					case 3:
					case 8:
						$line["comment"] = "Trailing Twelve Months. " . $line["comment"];
						break;
					case 4:
						$line["comment"] = "3 Year Compounded Annual Growth Rate. " . $line["comment"];
						break;
					case 5:
						$line["comment"] = "5 Year Compounded Annual Growth Rate. " . $line["comment"];
						break;
					case 6:
						$line["comment"] = "7 Year Compounded Annual Growth Rate. " . $line["comment"];
						break;
					case 7:
						$line["comment"] = "10 Year Compounded Annual Growth Rate. " . $line["comment"];
						break;
				}
			}
			if($addSufix) {
				switch($line["table_group"]) {
					case 1:
						$line["title"] .= ", ANN";
						$line["short_title"] .= ", ANN";
						break;
					case 2:
					case 9:
						$line["title"] .= ", MRQ";
						$line["short_title"] .= ", MRQ";
						break;
					case 3:
					case 8:
						$line["title"] .= ", TTM";
						$line["short_title"] .= ", TTM";
						break;
					case 4:
						$line["title"] .= ", 3Yr Growth";
						$line["short_title"] .= ", 3yCAGR";
						break;
					case 5:
						$line["title"] .= ", 5Yr Growth";
						$line["short_title"] .= ", 5yCAGR";
						break;
					case 6:
						$line["title"] .= ", 7Yr Growth";
						$line["short_title"] .= ", 7yCAGR";
						break;
					case 7:
						$line["title"] .= ", 10Yr Growth";
						$line["short_title"] .= ", 10yCAGR";
						break;
				}
			}
			if($index) {
				$result[$line["table_group"]][$line["field_name"]] = $line;
			} else {
				$result[$line["tooltip_id"]] = $line;
			}
		}
		return $result;
	}
}
