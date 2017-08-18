<?php
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(0);
include_once('../db/db.php');
include_once('../crons/include/update_quality_checks.php');
$db = Database::GetInstance();

set_time_limit(0);                   // ignore php timeout

update_dupont_checks();

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

function nullValues(&$item, $key) {
    if (is_null($item)) {
        $item = 'null';
    } else if(strlen(trim($item)) == 0) {
        $item = 'null';
    } else if($item == "-") {
        $item = 'null';
    }
}

?>
