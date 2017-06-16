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


try {
    echo "Cleaning up Portfolio tables...\n";
    $res = $db->query("DELETE a FROM portfolio_stocks a LEFT JOIN portfolio_persistent b ON a.portfolio_id = b.id WHERE b.id IS NULL");
    echo $res->rowCount() . " Stocks deleted.\n";
    $res = $db->query("DELETE a FROM portfolio_transactions a LEFT JOIN portfolio_persistent b ON a.portfolio_id = b.id WHERE b.id IS NULL");
    echo $res->rowCount() . " Transactions deleted.\n";
    $res = $db->query("DELETE a FROM portfolio_notes a LEFT JOIN portfolio_stocks b ON a.pstock_id = b.pstock_id WHERE b.pstock_id IS NULL");
    echo $res->rowCount() . " Notes deleted.\n";
    echo "Done\n";
} catch(PDOException $ex) {
    echo "\nDatabase Error"; //user message
    die($ex->getMessage());
}
?>
