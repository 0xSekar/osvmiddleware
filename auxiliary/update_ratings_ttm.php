<?php
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(0);
set_time_limit(0);                   // ignore php timeout
include_once('../db/db.php');
include_once('../crons/include/update_ratings_ttm.php');

update_ratings_ttm();
?>
