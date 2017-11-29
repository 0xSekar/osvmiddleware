<?php
// Database Connection
include_once('../config.php');
include_once('../db/db.php');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

set_time_limit(0);                   // ignore php timeout
//ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly

// Should never be cached - do not remove this
header("Content-type: text/xml; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$db = Database::GetInstance(); 

// HANDLING FINVIZ ONLY 
    $urldb = $url . $row['url']; //not used for now as osv_sites does the work
    $username = 'jae.jun@oldschoolvalue.com';
    $password = 'wjswogud0366';
    $loginUrl = 'https://finviz.com/login_submit.ashx'; //Sep 6 2017: changed from http to https
        //init curl
        $ch = curl_init();
    //Set the URL to work with
    curl_setopt($ch, CURLOPT_URL, $loginUrl);
    // ENABLE HTTP POST
    curl_setopt($ch, CURLOPT_POST, 1);
    //Sep 6 2017: Finviz modified its login procuedure
    // https://stackoverflow.com/questions/16480592/curl-https-follow-redirection
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,true);     // follow redirects
    curl_setopt($ch, CURLOPT_AUTOREFERER ,true); // set referer on redirect
    //Set the post parameters
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . $username . '&password=' . $password);
    //Handle cookies for the login
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    //Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
    //not to print out the results of its query.
    //Instead, it will return the results as a string return value
    //from curl_exec() instead of the usual true/false.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //execute the request (the login)
    $store = curl_exec($ch);
    //the login is now done and you can continue to get the
    //protected content.
    $t_parameter = "";
    $count=0;
    $batchsize=150;
    $content='';
    $response='';
    //Convert tickers list into array
    try {
        $res = $db->prepare("SELECT ticker, id FROM tickers WHERE (is_old != '1' AND secondary != '1') ORDER BY ticker");      
        $res->execute();
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $info = $res->fetchAll(PDO::FETCH_ASSOC);

    foreach ($info as $key => $value) {
        $tickerarray[] = $value["ticker"];
    }
    $tickersQty = count($tickerarray);

    echo " ---- Lista de Tickers devenida de la consulta a la BD --- \n";
    var_dump($info);

    //Start batch loop
    foreach ($tickerarray as $value){   
        if(isset($value)){
            $t_parameter .=  $value . ",";
            $count++;
            if($count == $batchsize || $count == $tickersQty){
                try {
                    $tmp = fopen('php://temp', 'r+');
                    $t_parameter=rtrim($t_parameter,",");
                    //set the URL to the protected file
                    curl_setopt($ch, CURLOPT_URL, 'http://elite.finviz.com/export.ashx?v=151&t=' . $t_parameter . "&c=1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68");
                    echo " ---- Se pide a Finbiz --- \n";
                    var_dump($t_parameter);
                    curl_setopt($ch, CURLOPT_FILE, $tmp);
                    //Execute the request
                    $content = curl_exec($ch);
                    rewind($tmp);
                    $count = 0;
                    echo "  tickersQty: ";
                    var_dump($tickersQty );
                    $tickersQty = $tickersQty - $batchsize;
                    echo "  tickersQty - batchsize: ";
                    var_dump($tickersQty );
                    $t_parameter = "";
                    rewind($tmp);
                    /** Send file to DB */
                    DBinsert($tmp, $info); 
                    echo " ----------------------- BATCH ---------------------- \n";
                    fclose($tmp);
                    $tmp = fopen('php://temp', 'w');
                    fclose($tmp);                    
                } 
                catch (Exception $e) { null;}
            } //end of batchsize = count     
        } //end of if set        
    } //end of for

function DBinsert($tmp, $info){
    $db = Database::GetInstance(); 
    $finvizData = fgets($tmp);    
    $headers = str_getcsv($finvizData);
    while(($finvizData = fgets($tmp)) !==  false){
        $a = str_getcsv($finvizData);
        foreach ($info as $key => $value) {
            if($value["ticker"] == $a[0]){
                $id = $value["id"];
                unset($info[$key]); 
                break;
            }
        }
        echo "Inserting...".$a[0]." with id: ".$id."\n";        
        
        $query = "INSERT INTO `finviz`(`ticker_id`,`company`,`sector`,`industry`,`country`,`market_cap`,`p_e`,`forward_p_e`,`peg`,`p_s`,`p_b`,`p_cash`,`p_free_cash_flow`,`dividend_yield`,`payout_ratio`,`eps_ttm`,`eps_growth_this_year`,`eps_growth_next_year`,`eps_growth_past5years`,`eps_growth_next5years`,`sales_growth_past5years`,`eps_growth_qtr_over_qtr`,`sales_growth_qtr_over_qtr`,`shares_outstanding`,`shares_float`,`insider_ownership`,`insider_transactions`,`institutional_ownership`,`institutional_transactions`,`float_short`,`short_ratio`,`return_on_assets`,`return_on_equity`,`return_on_investment`,`current_ratio`,`quick_ratio`,`lt_debt_equity`,`total_debt_equity`,`gross_margin`,`operating_margin`,`profit_margin`,`performance_week`,`performance_month`,`performance_qtr`,`performance_half_year`,`performance_year`,`performance_ytd`,`beta`,`average_true_range`,`volatility_week`,`volatility_month`,`20_day_simple_moving_avg`,`50_day_simple_moving_avg`,`200_day_simple_moving_avg`,`50_day_high`,`50_day_low`,`52_week_high`,`52_week_low`,`relative_strength_index_14`,`change_from_open`,`gap`,`analyst_recom`,`avg_volume`,`relative_volume`,`price`,`change`,`volume`,`earnings_date`) VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `company`= ?,`sector`= ?,`industry`= ?,`country`= ?,`market_cap`= ?,`p_e`= ?,`forward_p_e`= ?,`peg`= ?,`p_s`= ?,`p_b`= ?,`p_cash`= ?,`p_free_cash_flow`= ?,`dividend_yield`= ?,`payout_ratio`= ?,`eps_ttm`= ?,`eps_growth_this_year`= ?,`eps_growth_next_year`= ?,`eps_growth_past5years`= ?,`eps_growth_next5years`= ?,`sales_growth_past5years`= ?,`eps_growth_qtr_over_qtr`= ?,`sales_growth_qtr_over_qtr`= ?,`shares_outstanding`= ?,`shares_float`= ?,`insider_ownership`= ?,`insider_transactions`= ?,`institutional_ownership`= ?,`institutional_transactions`= ?,`float_short`= ?,`short_ratio`= ?,`return_on_assets`= ?,`return_on_equity`= ?,`return_on_investment`= ?,`current_ratio`= ?,`quick_ratio`= ?,`lt_debt_equity`= ?,`total_debt_equity`= ?,`gross_margin`= ?,`operating_margin`= ?,`profit_margin`= ?,`performance_week`= ?,`performance_month`= ?,`performance_qtr`= ?,`performance_half_year`= ?,`performance_year`= ?,`performance_ytd`= ?,`beta`= ?,`average_true_range`= ?,`volatility_week`= ?,`volatility_month`= ?,`20_day_simple_moving_avg`= ?,`50_day_simple_moving_avg`= ?,`200_day_simple_moving_avg`= ?,`50_day_high`= ?,`50_day_low`= ?,`52_week_high`= ?,`52_week_low`= ?,`relative_strength_index_14`= ?,`change_from_open`= ?,`gap`= ?,`analyst_recom`= ?,`avg_volume`= ?,`relative_volume`= ?,`price`= ?,`change`= ?,`volume`= ?,`earnings_date`= ?";
                $params = array();
                $params[] = (!isset($a[1])||($a[1]=="")?NULL:$a[1]);
                $params[] = (!isset($a[2])||($a[2]=="")?NULL:$a[2]);
                $params[] = (!isset($a[3])||($a[3]=="")?NULL:$a[3]);
                $params[] = (!isset($a[4])||($a[4]=="")?NULL:$a[4]); 
                $params[] = (!isset($a[5])||($a[5]=="")?NULL:$a[5]); 
                $params[] = (!isset($a[6])||($a[6]=="")?NULL:$a[6]);
                $params[] = (!isset($a[7])||($a[7]=="")?NULL:$a[7]);
                $params[] = (!isset($a[8])||($a[8]=="")?NULL:$a[8]);
                $params[] = (!isset($a[9])||($a[9]=="")?NULL:$a[9]);
                $params[] = (!isset($a[10])||($a[10]=="")?NULL:$a[10]);
                $params[] = (!isset($a[11])||($a[11]=="")?NULL:$a[11]);
                $params[] = (!isset($a[12])||($a[12]=="")?NULL:$a[12]);
                $params[] = (!isset($a[13])||($a[13]=="")?NULL:$a[13]);
                $params[] = (!isset($a[14])||($a[14]=="")?NULL:$a[14]); 
                $params[] = (!isset($a[15])||($a[15]=="")?NULL:$a[15]); 
                $params[] = (!isset($a[16])||($a[16]=="")?NULL:$a[16]);
                $params[] = (!isset($a[17])||($a[17]=="")?NULL:$a[17]);
                $params[] = (!isset($a[18])||($a[18]=="")?NULL:$a[18]);
                $params[] = (!isset($a[19])||($a[19]=="")?NULL:$a[19]);
                $params[] = (!isset($a[20])||($a[20]=="")?NULL:$a[20]);
                $params[] = (!isset($a[21])||($a[21]=="")?NULL:$a[21]);
                $params[] = (!isset($a[22])||($a[22]=="")?NULL:$a[22]);
                $params[] = (!isset($a[23])||($a[23]=="")?NULL:$a[23]);
                $params[] = (!isset($a[24])||($a[24]=="")?NULL:$a[24]); 
                $params[] = (!isset($a[25])||($a[25]=="")?NULL:$a[25]); 
                $params[] = (!isset($a[26])||($a[26]=="")?NULL:$a[26]);
                $params[] = (!isset($a[27])||($a[27]=="")?NULL:$a[27]);
                $params[] = (!isset($a[28])||($a[28]=="")?NULL:$a[28]);
                $params[] = (!isset($a[29])||($a[29]=="")?NULL:$a[29]);
                $params[] = (!isset($a[30])||($a[30]=="")?NULL:$a[30]);
                $params[] = (!isset($a[31])||($a[31]=="")?NULL:$a[31]);
                $params[] = (!isset($a[32])||($a[32]=="")?NULL:$a[32]);
                $params[] = (!isset($a[33])||($a[33]=="")?NULL:$a[33]);
                $params[] = (!isset($a[34])||($a[34]=="")?NULL:$a[34]); 
                $params[] = (!isset($a[35])||($a[35]=="")?NULL:$a[35]); 
                $params[] = (!isset($a[36])||($a[36]=="")?NULL:$a[36]);
                $params[] = (!isset($a[37])||($a[37]=="")?NULL:$a[37]);
                $params[] = (!isset($a[38])||($a[38]=="")?NULL:$a[38]);
                $params[] = (!isset($a[39])||($a[39]=="")?NULL:$a[39]);
                $params[] = (!isset($a[40])||($a[40]=="")?NULL:$a[40]);
                $params[] = (!isset($a[41])||($a[41]=="")?NULL:$a[41]);
                $params[] = (!isset($a[42])||($a[42]=="")?NULL:$a[42]);
                $params[] = (!isset($a[43])||($a[43]=="")?NULL:$a[43]);
                $params[] = (!isset($a[44])||($a[44]=="")?NULL:$a[44]); 
                $params[] = (!isset($a[45])||($a[45]=="")?NULL:$a[45]); 
                $params[] = (!isset($a[46])||($a[46]=="")?NULL:$a[46]);
                $params[] = (!isset($a[47])||($a[47]=="")?NULL:$a[47]);
                $params[] = (!isset($a[48])||($a[48]=="")?NULL:$a[48]);
                $params[] = (!isset($a[49])||($a[49]=="")?NULL:$a[49]);
                $params[] = (!isset($a[50])||($a[50]=="")?NULL:$a[50]);
                $params[] = (!isset($a[51])||($a[51]=="")?NULL:$a[51]);
                $params[] = (!isset($a[52])||($a[52]=="")?NULL:$a[52]);
                $params[] = (!isset($a[53])||($a[53]=="")?NULL:$a[53]);
                $params[] = (!isset($a[54])||($a[54]=="")?NULL:$a[54]); 
                $params[] = (!isset($a[55])||($a[55]=="")?NULL:$a[55]); 
                $params[] = (!isset($a[56])||($a[56]=="")?NULL:$a[56]);
                $params[] = (!isset($a[57])||($a[57]=="")?NULL:$a[57]);
                $params[] = (!isset($a[58])||($a[58]=="")?NULL:$a[58]);
                $params[] = (!isset($a[59])||($a[59]=="")?NULL:$a[59]);
                $params[] = (!isset($a[60])||($a[60]=="")?NULL:$a[60]);
                $params[] = (!isset($a[61])||($a[61]=="")?NULL:$a[61]);
                $params[] = (!isset($a[62])||($a[62]=="")?NULL:$a[62]);
                $params[] = (!isset($a[63])||($a[63]=="")?NULL:$a[63]);
                $params[] = (!isset($a[64])||($a[64]=="")?NULL:$a[64]); 
                $params[] = (!isset($a[65])||($a[65]=="")?NULL:$a[65]); 
                $params[] = (!isset($a[66])||($a[66]=="")?NULL:$a[66]);
                $params[] = (!isset($a[67])||($a[67]=="")?NULL:date("Y-m-d H:i:s", strtotime($a[67])));

                $params = array_merge($params, $params);
                array_unshift($params, (!isset($id)||($a[0]=="")?NULL:$id));   
                
            try {
                $res = $db->prepare($query);
                $res->execute($params);
            } catch(PDOException $ex) {
                echo "\nDatabase Error "; //user message
                die("- Line: ".__LINE__." - ".$ex->getMessage());
            }
        }
}
?>
