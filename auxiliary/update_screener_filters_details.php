<?php
require_once(dirname(__FILE__)."/screener_filter_class.php");

$ti = new screener_filter();
$ti->updateCommentsDescriptions();

echo "Filters names and descriptions updated";
