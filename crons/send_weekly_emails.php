<?php
// Database Connection
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once('../config.php');
include_once('../db/db.php');

set_time_limit(0);                   // ignore php timeout
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
echo "<br>\n$count mails sent<br>\n";

function getUserList() {
    $db = Database::GetInstance();

    $query = $db->prepare("SELECT DISTINCT user_id FROM `user_persistent_data` u Where NOT Exists (SELECT * from user_persistent_data t WHERE t.user_id=u.user_id AND t.name='email_weekly')
            UNION SELECT DISTINCT user_id FROM `user_persistent_data` u Where u.name='email_weekly' AND u.value = ?");
    $query->execute(array('"1"'));
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function getUserStocks($user) {
    $db = Database::GetInstance();

    $stocks = array();
    $stocks1 = null;
    $query = $db->prepare("SELECT value FROM user_persistent_data WHERE user_id = ? AND name = 'favorite_stocks'");
    $query->execute(array($user));
    if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $stocks1 =  json_decode($row["value"]);
    }
    $query = "SELECT distinct ticker FROM `portfolio_persistent` a inner join portfolio_stocks b on a.id=b.portfolio_id inner join tickers c on b.ticker_id = c.id WHERE user_id = ?";
    $res = $db->prepare($query);
    $res->execute(array($user));
    $stocks2 = $res->fetchAll(PDO::FETCH_COLUMN);
    $stocks = array_values(array_unique(array_merge($stocks1, $stocks2)));
    return $stocks;
}

function getUpTickers($stocks, $reference_date, $year, $limit) {
    $db = Database::GetInstance();

    $result = array();
    $query = "SELECT t.id, t.ticker, t.company, r.AS AS c_AS, h.AS AS o_AS, k.MarketCapIntraday, ((q.LastTradePriceOnly - qh.adj_close) / qh.adj_close * 100) AS YTD, h2.ratings_date as c_date
        FROM tickers t
        INNER JOIN ttm_ratings r ON t.id = r.ticker_id
        INNER JOIN ttm_ratings_history h ON t.id = h.ticker_id
        LEFT JOIN tickers_yahoo_keystats_1 k ON t.id = k.ticker_id
        LEFT JOIN tickers_yahoo_quotes_2 q ON t.id = q.ticker_id
        LEFT JOIN tickers_yahoo_historical_data qh ON t.id = qh.ticker_id
        INNER JOIN ttm_ratings_history h2 ON t.id = h2.ticker_id
        WHERE h.ratings_date = '$reference_date'
        AND r.AS_grade =  'A'
        AND h.AS_grade !=  'A'
        AND t.is_old = FALSE
        AND t.secondary = FALSE
        AND qh.report_date = (SELECT MAX(report_date) from tickers_yahoo_historical_data te where te.ticker_id=t.id and te.report_date < '$year-01-01')
        AND h2.ratings_date = (SELECT MIN(h3.ratings_date) from ttm_ratings_history h3 where h3.ticker_id=t.id and h3.ratings_date <= now() AND h3.ratings_date >= '$reference_date' AND h3.AS_grade = 'A') ORDER BY r.AS DESC LIMIT $limit";
    $res = $db->prepare($query);
    $res->execute();
    $res1 = $res->fetchAll(PDO::FETCH_ASSOC);
    foreach ($res1 as $value) {
        $result[$value["ticker"]] = $value;
    }
    return $result;
}

function getDownTickers($stocks, $reference_date, $year, $limit) {
    $db = Database::GetInstance();

    $result = array();
    /*    $query = "SELECT t.id, t.ticker, t.company, r.AS AS c_AS, h.AS AS o_AS, k.MarketCapIntraday, ((q.LastTradePriceOnly - qh.adj_close) / qh.adj_close * 100) AS YTD, h2.ratings_date as c_date
          FROM tickers t
          INNER JOIN ttm_ratings r ON t.id = r.ticker_id
          INNER JOIN ttm_ratings_history h ON t.id = h.ticker_id
          LEFT JOIN tickers_yahoo_keystats_1 k ON t.id = k.ticker_id
          LEFT JOIN tickers_yahoo_quotes_2 q ON t.id = q.ticker_id
          LEFT JOIN tickers_yahoo_historical_data qh ON t.id = qh.ticker_id
          INNER JOIN ttm_ratings_history h2 ON t.id = h2.ticker_id
          WHERE h.ratings_date = '$reference_date'
          AND r.AS_grade !=  'A'
          AND h.AS_grade =  'A'
          AND t.is_old = FALSE
          AND t.secondary = FALSE
          AND t.ticker IN ('".implode("','", $stocks)."')
          AND qh.report_date = (SELECT MAX(report_date) from tickers_yahoo_historical_data te where te.ticker_id=t.id and te.report_date < '$year-01-01')
          AND h2.ratings_date = (SELECT MIN(h3.ratings_date) from ttm_ratings_history h3 where h3.ticker_id=t.id and h3.ratings_date <= now() AND h3.ratings_date >= '$reference_date' AND h3.AS_grade != 'A') ORDER BY r.AS DESC LIMIT $limit";*/
    $query = "SELECT t.id, t.ticker, t.company, r.AS AS c_AS, h.AS AS o_AS, k.MarketCapIntraday, ((q.LastTradePriceOnly - qh.adj_close) / qh.adj_close * 100) AS YTD, h2.ratings_date as c_date
        FROM tickers t
        INNER JOIN ttm_ratings r ON t.id = r.ticker_id
        INNER JOIN ttm_ratings_history h ON t.id = h.ticker_id
        LEFT JOIN tickers_yahoo_keystats_1 k ON t.id = k.ticker_id
        LEFT JOIN tickers_yahoo_quotes_2 q ON t.id = q.ticker_id
        LEFT JOIN tickers_yahoo_historical_data qh ON t.id = qh.ticker_id
        INNER JOIN ttm_ratings_history h2 ON t.id = h2.ticker_id
        WHERE h.ratings_date = '$reference_date'
        AND r.AS_grade !=  'A'
        AND h.AS_grade =  'A'
        AND t.is_old = FALSE
        AND t.secondary = FALSE
        AND qh.report_date = (SELECT MAX(report_date) from tickers_yahoo_historical_data te where te.ticker_id=t.id and te.report_date < '$year-01-01')
        AND h2.ratings_date = (SELECT MIN(h3.ratings_date) from ttm_ratings_history h3 where h3.ticker_id=t.id and h3.ratings_date <= now() AND h3.ratings_date >= '$reference_date' AND h3.AS_grade != 'A') ORDER BY r.AS DESC LIMIT $limit";
    $res = $db->prepare($query);
    $res->execute();
    $res1 = $res->fetchAll(PDO::FETCH_ASSOC);
    foreach ($res1 as $value) {
        $result[$value["ticker"]] = $value;
    }
    return $result;
}

function getMaxTickers($reference_date, $year, $limit) {
    $db = Database::GetInstance();

    $result = array();
    $query = "SELECT t.id, t.ticker, t.company, r.QT, r.VT, r.GT, r.AS, r.AS_grade, k.MarketCapIntraday, ((q.LastTradePriceOnly - qh.adj_close) / qh.adj_close * 100) AS YTD, h2.52WeekHighDate as c_date
        FROM tickers t
        INNER JOIN ttm_ratings r ON t.id = r.ticker_id
        LEFT JOIN tickers_yahoo_keystats_1 k ON t.id = k.ticker_id
        LEFT JOIN tickers_yahoo_quotes_2 q ON t.id = q.ticker_id
        LEFT JOIN tickers_yahoo_historical_data qh ON t.id = qh.ticker_id
        INNER JOIN  tickers_yahoo_keystats_2 h2 ON t.id = h2.ticker_id
        WHERE t.is_old = FALSE
        AND t.secondary = FALSE
        AND qh.report_date = (SELECT MAX(report_date) from tickers_yahoo_historical_data te where te.ticker_id=t.id and te.report_date < '$year-01-01')
        AND h2.52WeekHighDate > '$reference_date'
        ORDER BY k.MarketCapIntraday DESC LIMIT $limit";
    $res = $db->prepare($query);
    $res->execute();
    $res1 = $res->fetchAll(PDO::FETCH_ASSOC);
    foreach ($res1 as $value) {
        $result[$value["ticker"]] = $value;
    }
    return $result;
}

function getMinTickers($reference_date, $year, $limit) {
    $db = Database::GetInstance();

    $result = array();
    $query = "SELECT t.id, t.ticker, t.company, r.QT, r.VT, r.GT, r.AS, r.AS_grade, k.MarketCapIntraday, ((q.LastTradePriceOnly - qh.adj_close) / qh.adj_close * 100) AS YTD, h2.52WeekLowDate as c_date
        FROM tickers t
        INNER JOIN ttm_ratings r ON t.id = r.ticker_id
        LEFT JOIN tickers_yahoo_keystats_1 k ON t.id = k.ticker_id
        LEFT JOIN tickers_yahoo_quotes_2 q ON t.id = q.ticker_id
        LEFT JOIN tickers_yahoo_historical_data qh ON t.id = qh.ticker_id
        INNER JOIN  tickers_yahoo_keystats_2 h2 ON t.id = h2.ticker_id
        WHERE t.is_old = FALSE
        AND t.secondary = FALSE
        AND qh.report_date = (SELECT MAX(report_date) from tickers_yahoo_historical_data te where te.ticker_id=t.id and te.report_date < '$year-01-01')
        AND h2.52WeekLowDate > '$reference_date'
        ORDER BY k.MarketCapIntraday DESC LIMIT $limit";
    $res = $db->prepare($query);
    $res->execute();
    $res1 = $res->fetchAll(PDO::FETCH_ASSOC);
    foreach ($res1 as $value) {
        $result[$value["ticker"]] = $value;
    }
    return $result;
}

function getPopularTickers($limit) {
    $db = Database::GetInstance();

    $query = "SELECT l.ticker, COUNT(l.ticker) as tcount, t.company, k.MarketCapIntraday, ((q.LastTradePriceOnly - qh.adj_close) / qh.adj_close * 100) AS YTD, r.QT, r.VT, r.GT, r.AS, r.AS_grade FROM ttm_ratings r LEFT JOIN tickers t on r.ticker_id=t.id LEFT JOIN ticker_view_log l ON t.ticker=l.ticker LEFT JOIN tickers_yahoo_keystats_1 k ON t.id=k.ticker_id LEFT JOIN tickers_yahoo_quotes_2 q ON t.id = q.ticker_id LEFT JOIN tickers_yahoo_historical_data qh ON t.id = qh.ticker_id WHERE t.is_old = FALSE AND t.secondary = FALSE AND qh.report_date = (SELECT MAX(report_date) from tickers_yahoo_historical_data te where te.ticker_id=t.id and te.report_date < '2017-01-01') AND k.MarketCapIntraday > 500000000 AND DATEDIFF(now(), created_date) <= 30 GROUP BY l.ticker order by tcount DESC LIMIT :limit";
    $res = $db->prepare($query);
    $res->bindParam(':limit', $limit, PDO::PARAM_INT);
    $res->execute();
    $res1 = $res->fetchAll(PDO::FETCH_ASSOC);
    $result = array();
    foreach ($res1 as $value) {
        $result[$value["ticker"]] = $value;
    }
    return $result;
}

function getActionWidget($year, $sort = "action", $sorttype = "desc", $number = 10, $filter = false, $mc = null, $qt = null, $vt = null, $gt = null, $as = null) {
    $db = Database::GetInstance();

    $result = array();
    if (!is_numeric($number) || (!is_null($mc) && !is_numeric($mc)) || (!is_null($qt) && !is_numeric($qt)) || (!is_null($vt) && !is_numeric($vt)) || (!is_null($gt) && !is_numeric($gt)) || (!is_null($as) && !is_numeric($as))) {
        return $result;
    }
    $query = "SELECT t.ticker, t.company, k.MarketCapIntraday, ((q.LastTradePriceOnly - qh.adj_close) / qh.adj_close * 100) AS YTD, r.QT, r.VT, r.GT, r.AS, r.AS_grade FROM ttm_ratings r LEFT JOIN tickers t on r.ticker_id=t.id LEFT JOIN tickers_yahoo_keystats_1 k ON t.id=k.ticker_id LEFT JOIN tickers_yahoo_quotes_2 q ON t.id = q.ticker_id LEFT JOIN tickers_yahoo_historical_data qh ON t.id = qh.ticker_id";
    $query .= " WHERE t.is_old = FALSE AND t.secondary = FALSE AND qh.report_date = (SELECT MAX(report_date) from tickers_yahoo_historical_data te where te.ticker_id=t.id and te.report_date < '$year-01-01')";
    if (!is_null($mc)) {
        $mc = $mc * 1000000;
        $query .= " AND k.MarketCapIntraday > :mc";
    }
    if($filter) {
        $query .= " AND ";
        $query .= "t.sector != 'Financial' AND t.sector != 'Basic Materials' AND t.sector !='Utilities' AND t.exchange != 'OTC'";
    }
    if(!is_null($qt)) {
        $query .= " AND ";
        $query .= "r.QT >= :qt";
    }
    if(!is_null($vt)) {
        $query .= " AND ";
        $query .= "r.VT >= :vt";
    }
    if(!is_null($gt)) {
        $query .= " AND ";
        $query .= "r.GT >= :gt";
    }
    if(!is_null($as)) {
        $query .= " AND ";
        $query .= "r.AS >= :as";
    }
    $query .= " ORDER BY ";
    switch(strtolower($sort)) {
        case "growth":
            $query .= "r.GT";
            break;
        case "value":
            $query .= "r.VT";
            break;
        case "quality":
            $query .= "r.QT";
            break;
        default:
            $query .= "r.AS";
    }
    if(strtolower($sorttype) == "asc")
        $query .= " ASC";
    else
        $query .= " DESC";
    if(!$filter) {
        $query .= " LIMIT :limit";
    }
    $res = $db->prepare($query);
    if (!is_null($mc))
        $res->bindParam(':mc', $mc, PDO::PARAM_INT);
    if (!$filter)
        $res->bindParam(':limit', $number, PDO::PARAM_INT);
    if (!is_null($gt))
        $res->bindParam(':gt', $gt, PDO::PARAM_STR);
    if (!is_null($vt))
        $res->bindParam(':vt', $vt, PDO::PARAM_STR);
    if (!is_null($qt))
        $res->bindParam(':qt', $qt, PDO::PARAM_STR);
    if (!is_null($as))
        $res->bindParam(':as', $as, PDO::PARAM_STR);
    $res->execute();
    $count = 0;
    $tickers = array();
    while(($row = $res->fetch(PDO::FETCH_ASSOC)) && $count < $number) {
        $count++;
        $tickers[] = $row;
    }
    $result = array();
    if(count($tickers) > 0) {
        foreach($tickers as $symbol) {
            $result[$symbol["ticker"]] = $symbol;
        }
    }
    return $result;
}

function getContent($file = "templates/email.php", $upStocks = array(), $downStocks = array(), $topAction = array(), $topQuality = array(), $topValue = array(), $topGrowth = array(), $popular = array(), $maxTick = array(), $minTick = array()) {
    ob_start(); // start output buffer

    include $file;
    $template = ob_get_contents(); // get contents of buffer
    ob_end_clean();
    return $template;
}

function cutValue($value, $max, $tail) {
    if (!$value) return '';

    $totalMax = $max + ($tail ? strlen($tail) : 0);
    if (!$max) return $value;
    if (strlen($value) <= $totalMax) return $value;

    $value = substr($value,0, $max);

    return trim($value) . ($tail ? $tail : '...');
}

function formatCurrency($value) {
    if(!is_numeric($value)) {
        return "N/A";
    }
    $suffix = '';
    if($value > 1000000000) {
        $value = $value / 1000000000;
        $suffix = 'B';
    } else if($value > 1000000) {
        $value = $value / 1000000;
        $suffix = 'M';
    } else if($value > 1000) {
        $value = $value / 1000;
        $suffix = 'K';
    }
    $formatted = number_format($value, 2, '.', '');
    while (strpos($formatted, '.') !== false && substr($formatted,-1) == '0') {
        $formatted = substr($formatted, 0, -1);
    }
    if(substr($formatted,-1) == '.') {
        $formatted = substr($formatted, 0, -1);
    }
    return '$'.$formatted.$suffix;
}
?>
