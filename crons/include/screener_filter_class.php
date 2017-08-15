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
		$this->fieldCol[-1]["id"] = array("table" => "tickers", "title" => "ID", "tooltip" => "Internal Ticker ID", "format" => "osvnumber:0", "stitle" => "ID", "min" => null, "max" => null);
		$this->fieldCol[-1]["ticker"] = array("table" => "tickers", "title" => "Symbol", "tooltip" => "Symbol", "format" => "osvtext", "stitle" => "Symbol", "min" => null, "max" => null);
		$flist = $this->getTooltip(null, null, null, array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16));
		foreach($flist as $field_group => $data1) {
			foreach($data1 as $field_name => $data) {
				$this->fieldCol[$field_group][$data["field_name"]] = array("table" => $data["table_name"], "title" => $data["title"], "tooltip" => $data["tooltip"], "format" => $data["format"], "stitle" => $data["short_title"], "min" => $data["min"], "max" => $data["max"], "sel_group" => $data["field_group"], "metadata_id" => $data["metadata_id"]);
			}
		}
	}

	public function fullFiltersReplace() {
		$q = $this->db->query("DROP TEMPORARY TABLE IF EXISTS screener_filter_fields_temp");
		$q = $this->db->query("DROP TEMPORARY TABLE IF EXISTS screener_filter_criteria_temp");
		$q = $this->db->query("CREATE TEMPORARY TABLE screener_filter_fields_temp LIKE screener_filter_fields");
		$q = $this->db->query("CREATE TEMPORARY TABLE screener_filter_criteria_temp LIKE screener_filter_criteria");
		$pfinalrun = array();
		for ($i = 0; $i<14; $i++) {
			foreach ($this->fieldCol[$i] as $key => $value) {
				$params = array();
				$query = "INSERT INTO screener_filter_fields_temp (field_id, report_type, metadata_id) VALUES (?, ?, ?)";
				if($i == 1) {
					$params[] = "ANN";
				} else if($i == 2) {
					$params[] = "QTR";
				} else {
					$params[] = NULL;
				}
				$params[] = $value["metadata_id"];

				//Get id if available
				$queryid = "SELECT field_id FROM screener_filter_fields WHERE metadata_id = ?";
				$q = $this->db->prepare($queryid);
				$q->execute(array($value["metadata_id"]));
				$rid = $q->fetchColumn();
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
		$query = "INSERT INTO screener_filter_fields_temp (field_id, report_type, metadata_id) VALUES (?, ?, ?)";
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

		$counter = 0;
		$u1 = "SELECT * FROM fields_metadata order by title";
                $r1 = $this->db->query($u1);
		$u2 = "UPDATE fields_metadata SET field_type = ?, field_order = ? WHERE metadata_id = ?";
		$r2 = $this->db->prepare($u2);
                while($row = $r1->fetch(PDO::FETCH_ASSOC)) {
			$p1 = array();
                        $type = $row["format"];
                        if(substr($type, 0, 7) == "osvdate") {
                                $p1[] = "D";
                        } else if ($type == "" || substr($type, 0, 7) == "osvtext" || substr($type, 0, 7) == "osvstri") {
                                $p1[] = "S";
                        } else {
                                $p1[] = "N";
                        }
			$counter++;
                        switch($row["table_group"]) {
                                case 3:
                                case 8:
                                        $p1[] = $counter+10000;
                                        break;
                                case 9:
                                        $p1[] = $counter+20000;
                                        break;
                                case 1:
                                        $p1[] = $counter+40000;
                                        break;
                                case 2:
                                        $p1[] = $counter+30000;
                                        break;
                                case 4:
                                        $p1[] = $counter+50000;
                                        break;
                                case 5:
                                        $p1[] = $counter+60000;
                                        break;
                                case 6:
                                        $p1[] = $counter+70000;
                                        break;
                                case 7:
                                        $p1[] = $counter+80000;
                                        break;
                                case 14:
                                        $p1[] = $counter+90000;
                                        break;
                                case 15:
                                        $p1[] = $counter+100000;
                                        break;
                                case 16:
                                        $p1[] = $counter+110000;
                                        break;
                                default:
                                        $p1[] = $counter;
                        }
			$p1[] = $row["metadata_id"];
			$r2->execute($p1);
		}
	}

	private function getTooltip($id = null, $table = null, $field = null, $group = array(), $addPrefix = true, $addSufix = true) {
		if(empty($id) && empty($table) && empty($field) && (empty($group) || !is_array($group))) {
			return array();
		}
		$params = array();
		$index = 0;
		$query = "SELECT * FROM fields_metadata ";
		if(!empty($id) && is_numeric($id)) {
			$query .= "WHERE metadata_id = ?";
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
						$line["tooltip"] = "Latest Annual. " . $line["tooltip"];
						break;
					case 2:
					case 9:
						$line["tooltip"] = "Most Recent Quarter. " . $line["tooltip"];
						break;
					case 3:
					case 8:
						$line["tooltip"] = "Trailing Twelve Months. " . $line["tooltip"];
						break;
					case 4:
						$line["tooltip"] = "3 Year Compounded Annual Growth Rate. " . $line["tooltip"];
						break;
					case 5:
						$line["tooltip"] = "5 Year Compounded Annual Growth Rate. " . $line["tooltip"];
						break;
					case 6:
						$line["tooltip"] = "7 Year Compounded Annual Growth Rate. " . $line["tooltip"];
						break;
					case 7:
						$line["tooltip"] = "10 Year Compounded Annual Growth Rate. " . $line["tooltip"];
						break;
					case 14:
						$line["tooltip"] = "5 Year Maximum Value. " . $line["tooltip"];
						break;
					case 15:
						$line["tooltip"] = "5 Year Minimum Value. " . $line["tooltip"];
						break;
					case 16:
						$line["tooltip"] = "5 Year Median Value. " . $line["tooltip"];
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
					case 14:
						$line["title"] .= ", 5Yr Max";
						$line["short_title"] .= ", 5yMax";
						break;
					case 15:
						$line["title"] .= ", 5Yr Min";
						$line["short_title"] .= ", 5yMin";
						break;
					case 16:
						$line["title"] .= ", 5Yr Median";
						$line["short_title"] .= ", 5yMed";
						break;
				}
			}
			if($index) {
				$result[$line["table_group"]][$line["field_name"]] = $line;
			} else {
				$result[$line["metadata_id"]] = $line;
			}
		}
		return $result;
	}
}
