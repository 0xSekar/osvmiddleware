<?php
require_once(dirname(__FILE__)."/../../db/db.php");

class screener_filter {
	//Database access
	private $db;

	//Auxiliary private variables
	private $tableListG0 = ["tickers", "tickers_activity_daily_ratios", "tickers_growth_ratios", "tickers_leverage_ratios", "tickers_metadata_eol", "tickers_mini_ratios", "tickers_profitability_ratios", "tickers_valuation_ratios", "tickers_xignite_estimates", "tickers_yahoo_estimates_others", "tickers_yahoo_keystats_1", "tickers_yahoo_keystats_2", "tickers_yahoo_quotes_1", "tickers_yahoo_quotes_2", "tickers_eod_valuation"];
	private $tableListG1 = ["reports_header", "reports_balanceconsolidated", "reports_balancefull", "reports_cashflowconsolidated", "reports_cashflowfull", "reports_financialheader", "reports_financialscustom", "reports_gf_data", "reports_incomeconsolidated", "reports_incomefull", "reports_metadata_eol", "reports_variable_ratios"];
	private $tableListG1r = ["reports_alt_checks", "reports_beneish_checks", "reports_key_ratios", "reports_pio_checks", "reports_ratings"];
	private $tableListG3 = ["ttm_balanceconsolidated", "ttm_balancefull", "ttm_cashflowconsolidated", "ttm_cashflowfull", "ttm_financialscustom", "ttm_incomeconsolidated", "ttm_incomefull", "ttm_gf_data", "ttm_key_ratios", "ttm_beneish_checks", "ttm_pio_checks", "ttm_ratings"];
	private $tableListG4 = ["reports_balanceconsolidated", "reports_balancefull", "reports_cashflowconsolidated", "reports_cashflowfull", "reports_financialscustom", "reports_gf_data", "reports_incomeconsolidated", "reports_incomefull", "reports_key_ratios", "reports_variable_ratios"];
	private $tableListG8 = ["ttm_alt_checks"];
	private $tableListG10 = ["tickers_yahoo_estimates_curr_qtr"];
	private $tableListG11 = ["tickers_yahoo_estimates_curr_year"];
	private $tableListG12 = ["tickers_yahoo_estimates_next_qtr"];
	private $tableListG13 = ["tickers_yahoo_estimates_next_year"];
	private $fieldCol = array();

	//Constructor
	public function __construct($forSD = false) {
		$this->db = Database::getInstance();

		//Populate field list
		$this->fieldCol[-1]["id"] = array("table" => "tickers", "title" => "ID", "comment" => "Internal Ticker ID", "format" => "osvnumber:0", "stitle" => "ID", "min" => null, "max" => null);
		$this->fieldCol[-1]["ticker"] = array("table" => "tickers", "title" => "Symbol", "comment" => "Symbol", "format" => "osvtext", "stitle" => "Symbol", "min" => null, "max" => null);
		foreach ($this->tableListG0 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[0][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "stitle" => $tmp[3], "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG1 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[1][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0].", ANN", "comment" => "Latest Annual. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", ANN", "min" => $tmp[4], "max" => $tmp[5]);
				$this->fieldCol[2][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0].", MRQ", "comment" => "Most Recent Quarter. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", MRQ", "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG1r as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[1][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0].", ANN", "comment" => "Latest Annual. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", ANN", "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG3 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[3][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0].", TTM", "comment" => "Trailing Twelve Months. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", TTM", "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG4 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table"."_3cagr");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[4][$fieldName["Field"]] = array("table" => $table."_3cagr", "title" => $tmp[0].", 3Yr Growth", "comment" => "3 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", 3yCAGR", "min" => $tmp[4], "max" => $tmp[5]);
				$this->fieldCol[5][$fieldName["Field"]] = array("table" => $table."_5cagr", "title" => $tmp[0].", 5Yr Growth", "comment" => "5 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", 5yCAGR", "min" => $tmp[4], "max" => $tmp[5]);
				$this->fieldCol[6][$fieldName["Field"]] = array("table" => $table."_7cagr", "title" => $tmp[0].", 7Yr Growth", "comment" => "7 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", 7yCAGR", "min" => $tmp[4], "max" => $tmp[5]);
				$this->fieldCol[7][$fieldName["Field"]] = array("table" => $table."_10cagr", "title" => $tmp[0].", 10Yr Growth", "comment" => "10 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", 10yCAGR", "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG8 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[8][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0].", TTM", "comment" => "Trailing Twelve Months. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", TTM", "min" => $tmp[4], "max" => $tmp[5]);
				$this->fieldCol[9][$fieldName["Field"]] = array("table" => "mrq_alt_checks", "title" => $tmp[0].", MRQ", "comment" => "Most Recent Quarter. ".$tmp[1], "format" => $tmp[2], "stitle" => $tmp[3].", MRQ", "min" => $tmp[4], "max" => $tmp[5]);
			}
			$this->fieldCol[8]["MarketValueofEquity"] = array("table" => "ttm_alt_checks", "title" => "Market Value of Equity, TTM", "comment" => "Trailing Twelve Months. The stock market value of the equity only.<br><br>The equity market value serves as a proxy for the company asset values.", "format" => "osvnumber:2", "stitle" => "MktValue, TTM", "min" => 0, "max" => null);
			$this->fieldCol[9]["MarketValueofEquity"] = array("table" => "mrq_alt_checks", "title" => "Market Value of Equity, MRQ", "comment" => "Most Recent Quarter. The stock market value of the equity only.<br><br>The equity market value serves as a proxy for the company asset values.", "format" => "osvnumber:2", "stitle" => "MktValue, MRQ", "min" => 0, "max" => null);
			$this->fieldCol[8]["AltmanZNormal"] = array("table" => "ttm_alt_checks", "title" => "Altman Z Score Original (Manufacturer), TTM", "comment" => "Trailing Twelve Months. Original Altman Z score used for manufacturing companies.<br><br>When Z is below 1.8, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "stitle" => "AltZOrig, TTM", "min" => null, "max" => null);
			$this->fieldCol[9]["AltmanZNormal"] = array("table" => "mrq_alt_checks", "title" => "Altman Z Score Original (Manufacturer), MRQ", "comment" => "Most Recent Quarter. Original Altman Z score used for manufacturing companies.<br><br>When Z is below 1.8, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "stitle" => "AltZOrig, MRQ", "min" => null, "max" => null);
			$this->fieldCol[8]["AltmanZRevised"] = array("table" => "ttm_alt_checks", "title" => "Altman Z Score Revised (Non-Manufacturer), TTM", "comment" => "Trailing Twelve Months. Revised Altman Z score used for non-manufacturing companies.<br><br>When Z is below 1.1, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "stitle" => "AltZRev, TTM", "min" => null, "max" => null);
			$this->fieldCol[9]["AltmanZRevised"] = array("table" => "mrq_alt_checks", "title" => "Altman Z Score Revised (Non-Manufacturer), MRQ", "comment" => "Most Recent Quarter. Revised Altman Z score used for non-manufacturing companies.<br><br>When Z is below 1.1, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "stitle" => "AltZRev, MRQ", "min" => null, "max" => null);
		}
		foreach ($this->tableListG10 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[10][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "stitle" => $tmp[3], "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG11 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[11][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "stitle" => $tmp[3], "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG12 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[12][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "stitle" => $tmp[3], "min" => $tmp[4], "max" => $tmp[5]);
			}
		}
		foreach ($this->tableListG13 as $table) {
			$q = $this->db->query("SHOW FULL COLUMNS FROM $table");
			$table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($table_fields as $fieldName) {
				if(empty($fieldName["Comment"])) {
					continue;
				}
				$tmp = explode("|", $fieldName["Comment"]);
				if(!isset($tmp[1]) || empty($tmp[1])) {
					$tmp[1] = "";
				}
				if(!isset($tmp[2]) || empty($tmp[2])) {
					$tmp[2] = "";
				}
				if(!isset($tmp[3]) || empty($tmp[3])) {
					$tmp[3] = $tmp[0];
				}
				if(!isset($tmp[4]) || $tmp[4] == "") {
					$tmp[4] = null;
				}
				if(!isset($tmp[5]) || $tmp[5] == "") {
					$tmp[5] = null;
				}
				$this->fieldCol[13][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "stitle" => $tmp[3], "min" => $tmp[4], "max" => $tmp[5]);
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
				$query = "INSERT INTO screener_filter_fields_temp (field_id, field_table_name, field_table_field, field_name, field_desc, field_type, field_group, field_order, report_type, format, min, max) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
						$params[] = 20;
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
		$query = "INSERT INTO screener_filter_fields_temp (field_id, field_table_name, field_table_field, field_name, field_desc, field_type, field_group, field_order, report_type, format, min, max) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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

	public function updateCommentsDescriptions() {
		for ($i = 0; $i<14; $i++) {
			foreach ($this->fieldCol[$i] as $key => $value) {
				$params = array();
				$table = $value["table"];
				if($i == 1 || $i == 2) {
					$query = "UPDATE screener_filter_fields SET field_name = ?, field_desc = ?, format = ? , min = ?, max = ? WHERE field_table_name = '$table' AND field_table_field = '$key' and report_type = ?";
				} else {
					$query = "UPDATE screener_filter_fields SET field_name = ?, field_desc = ?, format = ? , min = ?, max = ? WHERE field_table_name = '$table' AND field_table_field = '$key' and report_type IS NULL";
				}
				$params[] = $value["title"];
				$params[] = $value["comment"];
				$params[] = $value["format"];
				$params[] = $value["min"];
				$params[] = $value["max"];
                                if($i == 1) {
                                        $params[] = "ANN";
                                } else if($i == 2) {
                                        $params[] = "QTR";
                                }
				$q = $this->db->prepare($query);
				$q->execute($params);
			}
		}
	}
}
