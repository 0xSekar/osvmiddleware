<?php
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
//include_once('../db/database.php');
include_once('../db/db.php');
include_once('../crons/include/update_is_old_field.php');
//connectfe();
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout
echo "Updating is_old tickers table field... ";
update_is_old_field();
echo "Done<br>\n";
?>
