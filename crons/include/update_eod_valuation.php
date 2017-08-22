<?php
function update_eod_valuation($ti = null) {
    $db = Database::GetInstance();
    $username = 'osv';
    $password = 'test1234!';
    $context = stream_context_create(array(
                'http' => array(
                    'header' => "Authorization: Basic " . base64_encode("$username:$password"),
                    'timeout' => 180  //180 Seconds is 3 Minutes
                    )
                ));

    $url = 'http://' . SERVERHOST . APP_DIR . '/classes/';
    $urlnext = $url . "middleware_val_util.php?id=" . $ti . "&appkey=DgmNyOv2tUKBG5n6JzUI";
    $good = file_get_contents($urlnext, false, $context);
    $result = json_decode($good);

    if(!isset($result) || !isset($result->dcf_eps)) {
        return;
    }

    $params = array();
    $query = "INSERT INTO tickers_eod_valuation (ticker_id, dcf_eps, dcf_fcf, dcf_oe, graham, ebit, p_dcf_eps, p_dcf_fcf, p_dcf_oe, p_graham, p_ebit, mos_dcf_eps, mos_dcf_fcf, mos_dcf_oe, mos_graham, mos_ebit, nnwc, ncav, p_nnwc, p_ncav, mos_nnwc, mos_ncav, avg_mos) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE dcf_eps=?, dcf_fcf=?, dcf_oe=?, graham=?, ebit=?, p_dcf_eps=?, p_dcf_fcf=?, p_dcf_oe=?, p_graham=?, p_ebit=?, mos_dcf_eps=?, mos_dcf_fcf=?, mos_dcf_oe=?, mos_graham=?, mos_ebit=?, nnwc=?, ncav=?, p_nnwc=?, p_ncav=?, mos_nnwc=?, mos_ncav=?, avg_mos=?";
    $params[] = $ti;
    $params[] = $result->dcf_eps;
    $params[] = $result->dcf_fcf;
    $params[] = $result->dcf_oe;
    $params[] = $result->graham;
    $params[] = $result->ebit;
    $params[] = $result->p_dcf_eps;
    $params[] = $result->p_dcf_fcf;
    $params[] = $result->p_dcf_oe;
    $params[] = $result->p_graham;
    $params[] = $result->p_ebit;
    $params[] = $result->mos_dcf_eps;
    $params[] = $result->mos_dcf_fcf;
    $params[] = $result->mos_dcf_oe;
    $params[] = $result->mos_graham;
    $params[] = $result->mos_ebit;
    $params[] = $result->nnwc;
    $params[] = $result->ncav;
    $params[] = $result->p_nnwc;
    $params[] = $result->p_ncav;
    $params[] = $result->mos_nnwc;
    $params[] = $result->mos_ncav;
    $params[] = $result->avg_mos;
    $params[] = $result->dcf_eps;
    $params[] = $result->dcf_fcf;
    $params[] = $result->dcf_oe;
    $params[] = $result->graham;
    $params[] = $result->ebit;
    $params[] = $result->p_dcf_eps;
    $params[] = $result->p_dcf_fcf;
    $params[] = $result->p_dcf_oe;
    $params[] = $result->p_graham;
    $params[] = $result->p_ebit;
    $params[] = $result->mos_dcf_eps;
    $params[] = $result->mos_dcf_fcf;
    $params[] = $result->mos_dcf_oe;
    $params[] = $result->mos_graham;
    $params[] = $result->mos_ebit;
    $params[] = $result->nnwc;
    $params[] = $result->ncav;
    $params[] = $result->p_nnwc;
    $params[] = $result->p_ncav;
    $params[] = $result->mos_nnwc;
    $params[] = $result->mos_ncav;
    $params[] = $result->avg_mos;

    try {
        $res1 = $db->prepare($query);
        $res1->execute($params);
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
}
?>
