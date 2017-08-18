<?php
include_once('../config.php');

error_reporting(E_ALL & ~E_NOTICE);

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly
ini_set('default_socket_timeout', 900);

echo "Updating Portfolio dividends...\n";
$username = 'osv';
$password = 'test1234!';
$context = stream_context_create(array(
            'http' => array(
                'header' => "Authorization: Basic " . base64_encode("$username:$password"),
                'timeout' => 900  //180 Seconds is 3 Minutes
                )
            ));

$url = 'http://' . SERVERHOST . APP_DIR . '/classes/';
$urlnext = $url . "middleware_por_util.php?id=" . $ti . "&appkey=DgmNyOv2tUKBG5n6JzUI";
$good = file_get_contents($urlnext, false, $context);
echo $good."\n";
?>
