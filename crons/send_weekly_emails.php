<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/db.php');

set_time_limit(0);                   // ignore php timeout

require_once('../include/chargebeephp/lib/ChargeBee.php');
ChargeBee_Environment::configure(CHARGEBEE_SITE, CHARGEBEE_API_KEY);

require_once('include/email_helper.php');
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

// Should never be cached - do not remove this
header("Content-type: text/xml; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$reference_date = date('Y-m-d', strtotime('-7 days'));
$year = date('Y');
$count = 0;

$headers = array();
$headers[] = 'From: "Jae Jun" <osv@oldschoolvalue.com>';
$headers[] = 'Reply-To: "Jae Jun" <osv@oldschoolvalue.com>';
$headers[] = 'MIME-Version: 1.0\r\n';
$headers[] = 'Content-Type: text/html; charset=UTF-8';
$subject = "Weekly Old School Value Rating Update";

//Get Valid users
$user_list = getUserList();

//Get Stocks arrays
//Up and Downs can ve put outside foreach while no user selected stocks is used
$stocks = array();
$upStocks = getUpTickers($stocks, $reference_date, $year, 10); //New A Grade stocks
$downStocks = getDownTickers($stocks, $reference_date, $year, 10); //Downgraded from A stocks
$topAction = getActionWidget($year, "action", "desc", 5, false, 500);
$topQuality = getActionWidget($year, "quality", "desc", 5, false, 500, null, null, null, 75);
$topValue = getActionWidget($year, "value", "desc", 5, false, 500, null, null, null, 75);
$topGrowth = getActionWidget($year, "growth", "desc", 5, false, 500, null, null, null, 75);
$popular = getPopularTickers(5);
$maxTick = getMaxTickers($reference_date, $year, 10);
$minTick = getMinTickers($reference_date, $year, 10);
$first_run = true;

foreach ($user_list as $user) {
    //Get relevant Tickers
    //$stocks = getUserStocks($user);

    //Send Email
    if(userStatus($user)) {
        $content = getContent("templates/email.php", $upStocks, $downStocks, $topAction, $topQuality, $topValue, $topGrowth, $popular, $maxTick, $minTick);
        mail($user, $subject, $content, implode( "\r\n" , $headers ));
        echo "Email sent to $user<br>\n";
        $count++;
        if($first_run) {
            $fd = fopen("../../weeklyupdate.php","w");
            fwrite($fd, $content);
        }
        $first_run = false;
    }
}
echo "<br>\n$count mails sent<br>\n";
?>
