<?php
require_once(dirname(__FILE__)."/../crons/include/screener_filter_class.php");

$ti = new screener_filter();
$ti->fullFiltersReplace();

