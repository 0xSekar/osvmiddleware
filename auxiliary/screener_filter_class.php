<?php
require_once(dirname(__FILE__)."/../db/db.php");

class screener_filter {
    //Database access
    private $db;

    //Auxiliary private variables
    private $tableListG0 = ["tickers", "tickers_activity_daily_ratios", "tickers_growth_ratios", "tickers_leverage_ratios", "tickers_metadata_eol", "tickers_mini_ratios", "tickers_profitability_ratios", "tickers_valuation_ratios", "tickers_xignite_estimates", "tickers_yahoo_estimates_others", "tickers_yahoo_keystats_1", "tickers_yahoo_keystats_2", "tickers_yahoo_quotes_1", "tickers_yahoo_quotes_2"];
    private $tableListG1 = ["reports_header", "reports_alt_checks", "reports_balanceconsolidated", "reports_balancefull", "reports_beneish_checks", "reports_cashflowconsolidated", "reports_cashflowfull", "reports_financialheader", "reports_financialscustom", "reports_gf_data", "reports_incomeconsolidated", "reports_incomefull", "reports_key_ratios", "reports_metadata_eol", "reports_pio_checks", "reports_ratings", "reports_variable_ratios"];
    private $tableListG3 = ["ttm_balanceconsolidated", "ttm_balancefull", "ttm_cashflowconsolidated", "ttm_cashflowfull", "ttm_financialscustom", "ttm_incomeconsolidated", "ttm_incomefull", "ttm_gf_data", "ttm_key_ratios", "ttm_beneish_checks", "ttm_pio_checks", "ttm_ratings"];
    private $tableListG4 = ["reports_balanceconsolidated", "reports_balancefull", "reports_cashflowconsolidated", "reports_cashflowfull", "reports_financialscustom", "reports_gf_data", "reports_incomeconsolidated", "reports_incomefull", "reports_key_ratios", "reports_variable_ratios"];
    private $tableListG8 = ["ttm_alt_checks"];
    private $tableListG10 = ["tickers_yahoo_estimates"];
    private $fieldCol = array();

    //Constructor
    public function __construct($forSD = false) {
        $this->db = Database::getInstance();

	//Populate field list
	$this->fieldCol[-1]["id"] = array("table" => "tickers", "title" => "ID", "comment" => "Internal Ticker ID", "format" => "osvnumber:0", "ftitle" => "ID");
	$this->fieldCol[-1]["ticker"] = array("table" => "tickers", "title" => "Symbol", "comment" => "Symbol", "format" => "osvtext", "ftitle" => "Symbol");
	foreach ($this->tableListG0 as $table) {
	    $q = $this->db->query("SHOW FULL COLUMNS FROM $table");
	    $table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
	    foreach($table_fields as $fieldName) {
		if(empty($fieldName["Comment"])) {
		    continue;
		}
		$tmp = explode("|", $fieldName["Comment"]);
		if(!isset($tmp[1])) {
		    $tmp[1] = "";
		}
		if(!isset($tmp[2])) {
		    $tmp[2] = "";
		}
		if(!isset($tmp[3])) {
		    $tmp[3] = $tmp[0];
		}
		$this->fieldCol[0][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3]);
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
		if(!isset($tmp[1])) {
		    $tmp[1] = "";
		}
		if(!isset($tmp[2])) {
		    $tmp[2] = "";
		}
		if(!isset($tmp[3])) {
		    $tmp[3] = $tmp[0];
		}
		$this->fieldCol[1][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3]);
		$this->fieldCol[2][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3]);
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
		if(!isset($tmp[1])) {
		    $tmp[1] = "";
		}
		if(!isset($tmp[2])) {
		    $tmp[2] = "";
		}
		if(!isset($tmp[3])) {
		    $tmp[3] = $tmp[0];
		}
		$this->fieldCol[3][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0].", TTM", "comment" => "Trailing Twelve Months. ".$tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3].", TTM");
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
		if(!isset($tmp[1])) {
		    $tmp[1] = "";
		}
		if(!isset($tmp[2])) {
		    $tmp[2] = "";
		}
		if(!isset($tmp[3])) {
		    $tmp[3] = $tmp[0];
		}
		$this->fieldCol[4][$fieldName["Field"]] = array("table" => $table."_3cagr", "title" => $tmp[0].", 3Yr Growth", "comment" => "3 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3].", 3yCAGR");
		$this->fieldCol[5][$fieldName["Field"]] = array("table" => $table."_5cagr", "title" => $tmp[0].", 5Yr Growth", "comment" => "5 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3].", 5yCAGR");
		$this->fieldCol[6][$fieldName["Field"]] = array("table" => $table."_7cagr", "title" => $tmp[0].", 7Yr Growth", "comment" => "7 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3].", 7yCAGR");
		$this->fieldCol[7][$fieldName["Field"]] = array("table" => $table."_10cagr", "title" => $tmp[0].", 10Yr Growth", "comment" => "10 Year Compounded Annual Growth Rate. ".$tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3].", 10yCAGR");
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
		if(!isset($tmp[1])) {
		    $tmp[1] = "";
		}
		if(!isset($tmp[2])) {
		    $tmp[2] = "";
		}
		if(!isset($tmp[3])) {
		    $tmp[3] = $tmp[0];
		}
		$this->fieldCol[8][$fieldName["Field"]] = array("table" => $table, "title" => $tmp[0].", TTM", "comment" => "Trailing Twelve Months. ".$tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3].", TTM");
		$this->fieldCol[9][$fieldName["Field"]] = array("table" => "mrq_alt_checks", "title" => $tmp[0].", TTM", "comment" => "Most Recent Quarter. ".$tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3].", MRQ");
	    }
	    $this->fieldCol[8]["MarketValueofEquity"] = array("table" => "ttm_alt_checks", "title" => "Market Value of Equity, TTM", "comment" => "Trailing Twelve Months. The stock market value of the equity only.<br><br>The equity market value serves as a proxy for the company asset values.", "format" => "osvnumber:2", "ftitle" => "MktValue, TTM");
	    $this->fieldCol[9]["MarketValueofEquity"] = array("table" => "mrq_alt_checks", "title" => "Market Value of Equity, MRQ", "comment" => "Most Recent Quarter. The stock market value of the equity only.<br><br>The equity market value serves as a proxy for the company asset values.", "format" => "osvnumber:2", "ftitle" => "MktValue, MRQ");
	    $this->fieldCol[8]["X4"] = array("table" => "ttm_alt_checks", "title" => "Altman X4, TTM", "comment" => "Trailing Twelve Months. X4 = MVoE/TL<br><br>The measure shows how much the firm's assets can decline in value (measured by market value of equity plus debt) before the liabilities exceed the assets and the firm becomes insolvent.<br><br>E.g. a company with a market value of its equity of $1,000 and debt of $500 could experience a two-thirds drop in asset value before insolvency.<br><br>However, the same firm with $250 equity will be insolvent if assets drop only one-third in value.", "format" => "osvnumber:2", "ftitle" => "AltX4, TTM");
	    $this->fieldCol[9]["X4"] = array("table" => "mrq_alt_checks", "title" => "Altman X4, MRQ", "comment" => "Most Recent Quarter. X4 = MVoE/TL<br><br>The measure shows how much the firm's assets can decline in value (measured by market value of equity plus debt) before the liabilities exceed the assets and the firm becomes insolvent.<br><br>E.g. a company with a market value of its equity of $1,000 and debt of $500 could experience a two-thirds drop in asset value before insolvency.<br><br>However, the same firm with $250 equity will be insolvent if assets drop only one-third in value.", "format" => "osvnumber:2", "ftitle" => "AltX4, MRQ");
	    $this->fieldCol[8]["AltmanZNormal"] = array("table" => "ttm_alt_checks", "title" => "Altman Z Score Original (Manufacturer), TTM", "comment" => "Trailing Twelve Months. Original Altman Z score used for manufacturing companies.<br><br>When Z is below 1.8, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "ftitle" => "AltZOrig, TTM");
	    $this->fieldCol[9]["AltmanZNormal"] = array("table" => "mrq_alt_checks", "title" => "Altman Z Score Original (Manufacturer), MRQ", "comment" => "Most Recent Quarter. Original Altman Z score used for manufacturing companies.<br><br>When Z is below 1.8, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "ftitle" => "AltZOrig, MRQ");
	    $this->fieldCol[8]["AltmanZRevised"] = array("table" => "ttm_alt_checks", "title" => "Altman Z Score Revised (Non-Manufacturer), TTM", "comment" => "Trailing Twelve Months. Revised Altman Z score used for non-manufacturing companies.<br><br>When Z is below 1.1, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "ftitle" => "AltZRev, TTM");
	    $this->fieldCol[9]["AltmanZRevised"] = array("table" => "mrq_alt_checks", "title" => "Altman Z Score Revised (Non-Manufacturer), MRQ", "comment" => "Most Recent Quarter. Revised Altman Z score used for non-manufacturing companies.<br><br>When Z is below 1.1, the company is highly likely to be bankrupt. If a company is generating lower than 1.8, serious studies must be performed to ensure the company can survive.", "format" => "osvnumber:2", "ftitle" => "AltZRev, MRQ");
	}
        foreach ($this->tableListG10 as $table) {
            $q = $this->db->query("SHOW FULL COLUMNS FROM $table"."_curr_qtr");
            $table_fields = $q->fetchAll(PDO::FETCH_ASSOC);
            foreach($table_fields as $fieldName) {
		if(empty($fieldName["Comment"])) {
		    continue;
		}
		$tmp = explode("|", $fieldName["Comment"]);
		if(!isset($tmp[1])) {
		    $tmp[1] = "";
		}
		if(!isset($tmp[2])) {
		    $tmp[2] = "";
		}
		if(!isset($tmp[3])) {
		    $tmp[3] = $tmp[0];
		}
                $this->fieldCol[10][$fieldName["Field"]] = array("table" => $table."_curr_qtr", "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3]);
                $this->fieldCol[11][$fieldName["Field"]] = array("table" => $table."_curr_year", "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3]);
                $this->fieldCol[12][$fieldName["Field"]] = array("table" => $table."_next_qtr", "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3]);
                $this->fieldCol[13][$fieldName["Field"]] = array("table" => $table."_next_year", "title" => $tmp[0], "comment" => $tmp[1], "format" => $tmp[2], "ftitle" => $tmp[3]);
            }
        }
    }

    public function fullFiltersReplace() {
	$q = $this->db->query("truncate screener_filter_fields");
	$q = $this->db->query("truncate screener_filter_criteria");
	$counter = 0;
	for ($i = 0; $i<14; $i++) {
	    foreach ($this->fieldCol[$i] as $key => $value) {
		$params = array();
		$query = "INSERT INTO screener_filter_fields (field_table_name, field_table_field, field_name, field_desc, field_type, field_group, field_order, report_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$params[] = $value["table"];
		$params[] = $key;
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
		$params[] = $counter;
		if($i == 1) {
		    $params[] = "ANN";
		} else if($i == 2) {
		    $params[] = "QTR";
		} else {
		    $params[] = NULL;
		}
		$q = $this->db->prepare($query);
		$q->execute($params);
		$lastId = $this->db->lastInsertId();
		
		$query = "INSERT INTO screener_filter_criteria (field_id, crit_text, crit_cond, crit_order) VALUES (?, ?, ?, ?)";
		$par = array();
		$par[] = $lastId;
		$par[] = "User Defined";
		$par[] = "cu";
		$par[] = 20;
		$q = $this->db->prepare($query);
		$q->execute($par);
		$counter++;
	    }
	}
    }

    public function updateCommentsDescriptions() {
	for ($i = 0; $i<14; $i++) {
	    foreach ($this->fieldCol[$i] as $key => $value) {
		$params = array();
		$table = $value["table"];
		$query = "UPDATE screener_filter_fields SET field_name = ?, field_desc = ? WHERE field_table_name = '$table' AND field_table_field = '$key'";
		$params[] = $value["title"];
		$params[] = $value["comment"];
		$q = $this->db->prepare($query);
		$q->execute($params);
	    }
	}
    }
}
