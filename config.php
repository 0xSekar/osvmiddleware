<?php
// Base directory
define('BASE_DIR', dirname(__FILE__));

$extConfig = BASE_DIR.'/config-ext.php';
if (file_exists($extConfig) && is_file($extConfig)) {
    include_once $extConfig;
}

if (!defined('AREPORTS')) {
    define('AREPORTS', 15);
    define('QREPORTS', 20);
}

if (!defined('SERVERHOST')) {
    define('SERVERHOST', 'job.oldschoolvalue.com');
}

if (!defined('YAHOO_INTEGRATION_DEBUG')) {
    define('YAHOO_INTEGRATION_DEBUG', false);
}

if (!defined('YAHOO_INTEGRATION_URL')) {
    define('YAHOO_INTEGRATION_URL', 'http://query.yahooapis.com');
}

if (!defined('YAHOO_INTEGRATION_PROXY')) {
    define('YAHOO_INTEGRATION_PROXY', null);
}

// Database
if (!defined('DB_FRONTEND_DATABASE')) {
    define('DB_FRONTEND_HOST', 'localhost');
    define('DB_FRONTEND_DATABASE', 'jjun0366_frontend');
    define('DB_FRONTEND_USER', 'wsuser');
    define('DB_FRONTEND_PASSWORD', 'ng2Xfy2SAu');
}

if (!defined('DB_MEMBER_DATABASE')) {
    define('DB_MEMBER_HOST', 'localhost');
    define('DB_MEMBER_DATABASE', 'jjun0366_osvmember');
    define('DB_MEMBER_USER', 'wsuser');
    define('DB_MEMBER_PASSWORD', 'ng2Xfy2SAu');
}

if (!defined('CHARGEBEE_SITE')) {
    define('CHARGEBEE_SITE', 'oldschoolvalue');
    define('CHARGEBEE_API_KEY', 'live_Mx780fcE3RclWQBSWdbC3ALo8m8c6doJ');
    define('CHARGEBEE_STRIPE_KEY', 'pk_live_9WS3L6Yw7CK3lPR2QCitwzO9');

    /*list of plans displayed from within the app change plan menu*/
    $PLAN_DISPLAY = array('bird-pro-mo-49', 'bird-pro-ann-468', 'bird-pro-2yr-696');

    /*upselling popup one time after card is entered*/
    $PLAN_EXTRAS = array(
            'bird-pro-mo-49' => array(
                'plan' => 'bird-pro-ann-539',
                'coupon' => 'EXTRAYEARUPGRADEDISCOUNT',
                'message' => array(
                    'msg0' => 'Wait... Get $100 Off Now if You Upgrade to the Annual Plan',
                    'msg1' => 'Instead of paying $49 every month (which comes out to $588 in a year), you pay $439 today and save $149.',
                    'msg2' => 'Click the button below to get $100 off the annual plan.'
                    )
                ),
            'bird-pro-ann' => array(
                'plan' => 'bird-pro-2yr-936',
                'coupon' => 'EXTRAYEARUPGRADEDISCOUNT',
                'message' => array(
                    'msg0' => 'Wait... Get $100 Off Now if You Upgrade to the 2 Year Plan',
                    'msg1' => 'Instead of paying $539 on your annual plan next year, you can get an extra year for $297.',
                    'msg2' => 'Click the button below to get $100 off the 2 Yr plan.'
                    )
                ),
            );
}

if (!defined('APP_DIR')) {
    define('APP_DIR', '/app');
}
