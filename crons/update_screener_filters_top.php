<?php
// Database Connection
error_reporting(0);
include_once('../config.php');
include_once('../db/db.php');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$db = Database::GetInstance(); 

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

$admin_filters = array();

try {
	$res = $db->query("SELECT * from screener_filter_top1");
	$admin_filters_field = $res->fetchAll(PDO::FETCH_COLUMN);
	$in = join(',', array_fill(0, count($admin_filters_field), '?'));
	$res = $db->prepare("(SELECT crit_id FROM screener_filter_criteria WHERE field_id IN ($in)) UNION (SELECT crit_id FROM screener_filter_criteria2 WHERE field_id IN ($in))");
	$res->execute(array_merge($admin_filters_field,$admin_filters_field));
	$admin_filters = $res->fetchAll(PDO::FETCH_COLUMN);
	$res = $db->query("SELECT criteria from screener_persistent");
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die($ex->getMessage());
}

$result = array();

while (($row = $res->fetch(PDO::FETCH_ASSOC))) {
	$crit = unserialize($row["criteria"]);
	foreach ($crit as $value) {
		if(!in_array($value, $admin_filters) && !empty($value)) {
			$result[$value]++;
		}
	}
}
arsort($result);
$result = array_slice($result, 0, 30-count($admin_filters), TRUE);
$res_val = array_keys($result);
$in = join(',', array_fill(0, count($res_val), '?'));

try {
	$res = $db->prepare("(SELECT field_id FROM screener_filter_criteria WHERE crit_id IN ($in)) UNION (SELECT field_id FROM screener_filter_criteria2 WHERE crit_id IN ($in))"); 
	$res->execute(array_merge($res_val,$res_val));
	$result = $res->fetchAll(PDO::FETCH_COLUMN);
	$in = join('),(', array_fill(0, count($result), '?'));
	$db->exec("TRUNCATE TABLE screener_filter_top2");
	$res = $db->prepare("INSERT INTO screener_filter_top2 (filter_id) VALUES ($in)");
	$res->execute($result);
} catch(PDOException $ex) {
	echo "\nDatabase Error"; //user message
	die($ex->getMessage());
}
?>
