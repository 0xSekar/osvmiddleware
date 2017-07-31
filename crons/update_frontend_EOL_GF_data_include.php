<?php
function nullValues(&$item, $key) {
    if(strlen(trim($item)) == 0) {
        $item = 'null';
    } else if($item == "-") {
        $item = 'null';
    }
}

function update_frontend_EOL_GF_data($eticker, $rawdata, $tAdded) {
    $db = Database::GetInstance(); 

    try { 
        $res = $db->query("SELECT b.* FROM tickers a LEFT JOIN tickers_control b ON a.id = b.ticker_id WHERE a.ticker = '$eticker'");        
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $counter = $res->rowCount();
    if($counter == 0) return;
    $dates = $res->fetch(PDO::FETCH_OBJ);

    update_yahoo_daily($eticker);

    array_walk_recursive($rawdata, 'nullValues');

    //Update Raw data
    if(isset($rawdata["AccountsPayableTurnoverDaysFY"])) {
        update_raw_data_tickers($dates, $rawdata);

        //Update Key ratios TTM
        update_key_ratios_ttm($dates->ticker_id);

        //Update Quality Checks
        update_pio_checks($dates->ticker_id);
        update_altman_checks($dates->ticker_id);
        update_beneish_checks($dates->ticker_id);
        update_dupont_checks($dates->ticker_id);
        update_accrual_checks($dates->ticker_id);

        //Finally update local report date                     
        try {
            $res = $db->query("UPDATE tickers_control SET last_eol_date = now() WHERE ticker_id = $dates->ticker_id");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
    }

    echo "Removing old Quality Checks (PIO)... ";
    try {
        $res = $db->query("delete a from reports_pio_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    echo "done<br>\n";
    echo "Removing old Quality Checks (ALTMAN)... ";
    try {
        $res = $db->query("delete a from reports_alt_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    echo "done<br>\n";
    echo "Removing old Quality Checks (BENEISH)... ";
    try {
        $res = $db->query("delete a from reports_beneish_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    echo "done<br>\n";
    echo "Removing old Quality Checks (DUPONT)... ";
    try {
        $res = $db->query("delete a from reports_dupont_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    echo "done<br>\n";
    echo "Removing old Quality Checks (ACCRUAL)... ";
    try {
        $res = $db->query("delete a from reports_accrual_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
    } catch(PDOException $ex) {
        echo " Database Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    echo "done<br>\n";


    echo "Updating EOD valuation for new tickers... ";
    update_eod_valuation($dates->ticker_id); 
    echo "done<br>\n";

    return;
}
?>
