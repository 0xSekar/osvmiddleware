<?php
// Base directory
define('BASE_DIR', dirname(__FILE__));

$extConfig = BASE_DIR.'/config-ext.php';
if (file_exists($extConfig) && is_file($extConfig)) {
    include_once $extConfig;
}

if (!defined('AREPORTS')) {
	define('AREPORTS', 14);
	define('QREPORTS', 20);
}

if (!defined('SERVERHOST')) {
        define('SERVERHOST', 'www.oldschoolvalue.com');
}

