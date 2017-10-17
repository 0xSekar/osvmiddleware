<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email-OSV</title>
</head>

<body style="background:#F6F7F9; overflow: auto;">
<?php
    $styleh3="float:left; margin-bottom:-5px;margin-top: 15px;color: #54657e;";
    $container="width:658px; margin: 0 auto; background: #fff;padding: 15px;border: 1px solid #c0c0c0; overflow: auto;";
    $logo="float: left;background: #262e40; width: 100%;height: 70px; ";
    $title="background:#343f56; height:30px; width:100%; margin-top: 25px; float:left;color: #fefefe;";
    $stylep="float:left;color: #262624; margin-bottom: 10px;";
    $styleh4="padding-left:10px; margin-top: 7px;";
    $table=" margin-top:10px; width: 100%; max-width: 100%;border-collapse: collapse;";
    $styletr="border-bottom: 1px solid #ddd;";
    $td150="width:150px;padding: 7px 0;Color:#54657e;";
    $td100="width:100px;padding: 7px 0;text-align:center;Color:#54657e;";
    $td50="width:50px;text-align:center; Color:#54657e;font-size:14px;padding: 7px 0;";
    $td200="width:200px;padding: 7px 0;Color:#54657e;";
    $tdName150="width:150px;color: #54657E;padding: 7px 0;";
    $tdName200="width:200px;color: #54657E;padding-left:10px";
    $td="vertical-align: middle;line-height: 15px; color:#b1b4ba;";
    $spanUp="vertical-align:middle;display: inline-block;  border-right: 4px solid transparent;   border-left: 4px solid transparent;   border-top: 0;border-bottom: 4px dashed;margin-right: 4px;";
    $spanDown="vertical-align:middle;display: inline-block;  margin-right: 4px;  vertical-align: middle;  border-top: 4px dashed; border-top: 4px solid \9;   border-right: 4px solid transparent;   border-left: 4px solid transparent;";
?>
<div class = "container" style="<?php echo $container ?>">
    <div class="logo" style="<?php echo $logo ?>">
        <img alt="old school value" src="http://cdn8.oldschoolvalue.com/images/oldschoolvalue-logo.png" style="padding: 18px">
    </div>
    <div class="title" style="<?php echo $title?>">
        <h4 style="<?php echo $styleh4?>"><b>OLD SCHOOL VALUE</b> - <?php echo date('l, F j, Y');?></h4>
    </div>
    <h3 style="<?php echo $styleh3?>">New "A" Grade Action Score Stocks</h3>
    <p style="<?php echo $stylep ?>">The following stocks have entered the A list. This list is currently limited to 10 stocks. To see the full list, please <a href="https://app.oldschoolvalue.com">log in</a> and go to "Stock Database" to see the full list.</p>
    <div class= "table1" style="float:left">
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td100,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td100,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td100,$td?>">
                    New Action Score
                </td>
                <td style="<?php echo $td100,$td?>">
                    Old Action Score
                </td>
                <td style="<?php echo $td100,$td?>">
                    Date Changed
                </td>
            </tr>
            <?php if (count($upStocks) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="6" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td >
            </tr>
            <?php } else {?>
            <?php foreach($upStocks as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 12)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td100?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?> font-size: 14px;">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td100?>">
                    <?php echo number_format($stock["c_AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td100?>">
                    <?php echo number_format($stock["o_AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td100?>;font-size: 14px;">
                    <?php echo date("n/j/Y",strtotime($stock["c_date"]));?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">Downgraded Action Score Stocks</h3>
        <p style="<?php echo $stylep ?>">The following stocks have left the A list. This list is currently limited to 10 stocks. To see the full list, please <a href="https://app.oldschoolvalue.com">log in</a> and go to "Stock Database" to see the full list.</p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td100,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td100,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td100,$td?>">
                    New Action Score
                </td>
                <td style="<?php echo $td100,$td?>">
                    Old Action Score
                </td>
                <td style="<?php echo $td100,$td?>">
                    Date Changed
                </td>
            </tr>
            <?php if (count($downStocks) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="6" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td >
            </tr>
            <?php } else {?>
            <?php foreach($downStocks as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 12)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td100?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?> font-size: 14px;">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td100?>">
                    <?php echo number_format($stock["c_AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td100?>">
                    <?php echo number_format($stock["o_AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td100?>;font-size: 14px;">
                    <?php echo date("n/j/Y",strtotime($stock["c_date"]));?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">New 52 Week High Stocks</h3>
        <p style="<?php echo $stylep?>">Top 10 stocks (based on Market Cap) that reached its 52 Week High price this week.</p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr ;$td?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td50,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td50,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Grade
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Score
                </td>
                <td style="<?php echo $td50,$td?>">
                    Q
                <td style="<?php echo $td50,$td?>">
                    V
                </td>
                <td style="<?php echo $td50,$td?>">
                    G
                </td>
                <td style="<?php echo $td50,$td?>">
                    Hit Date
                </td>
            </tr>
            <?php if (count($maxTick) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="9" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td>
            </tr>
            <?php } else {?>
            <?php foreach($maxTick as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 11)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?>">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo $stock["AS_grade"];?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["QT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["VT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["GT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo date("n/j/Y",strtotime($stock["c_date"]));?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">New 52 Week Low Stocks</h3>
        <p style="<?php echo $stylep?>">Top 10 stocks (based on Market Cap) that reached its 52 Week Low price this week.</p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr ;$td?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td50,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td50,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Grade
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Score
                </td>
                <td style="<?php echo $td50,$td?>">
                    Q
                <td style="<?php echo $td50,$td?>">
                    V
                </td>
                <td style="<?php echo $td50,$td?>">
                    G
                </td>
                <td style="<?php echo $td50,$td?>">
                    Hit Date
                </td>
            </tr>
            <?php if (count($minTick) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="9" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td>
            </tr>
            <?php } else {?>
            <?php foreach($minTick as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 11)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?>">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo $stock["AS_grade"];?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["QT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["VT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["GT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo date("n/j/Y",strtotime($stock["c_date"]));?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">Top 5 ACTION Stocks (MktCap>$500M)</h3>
        <p style="<?php echo $stylep?>">The Top 5 ACTION Score stocks in our full universe where market cap is > $500m.
        Stock universe includes financials, basic materials, utilities, OTC and ADR stocks. Read how the Action Score was created from <a href='http://www.oldschoolvalue.com/blog/valuation-methods/action-score-quality-value-growth/' target='_blank'>this article</a></p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr ;$td?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td50,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td50,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Grade
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Score
                </td>
                <td style="<?php echo $td50,$td?>">
                    Q
                <td style="<?php echo $td50,$td?>">
                    V
                </td>
                <td style="<?php echo $td50,$td?>">
                    G
                </td>
            </tr>
            <?php if (count($topAction) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="8" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td>
            </tr>
            <?php } else {?>
            <?php foreach($topAction as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 12)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?>">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo $stock["AS_grade"];?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["QT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["VT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["GT"], 2, '.', '');?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">Top 5 POPULAR Stocks (MktCap>$500M)</h3>
        <p style="<?php echo $stylep?>">The Top 5 most viewed stocks by OSV insiders the past 30 days in our full universe where market cap is > $500m.
        Stock universe includes financials, basic materials, utilities, OTC and ADR stocks.</p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr ;$td?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td50,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td50,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Grade
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Score
                </td>
                <td style="<?php echo $td50,$td?>">
                    Q
                <td style="<?php echo $td50,$td?>">
                    V
                </td>
                <td style="<?php echo $td50,$td?>">
                    G
                </td>
            </tr>
            <?php if (count($popular) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="8" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td>
            </tr>
            <?php } else {?>
            <?php foreach($popular as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 12)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?>">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo $stock["AS_grade"];?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["QT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["VT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["GT"], 2, '.', '');?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">Top 5 QUALITY Stocks (MktCap>$500M)</h3>
        <p style="<?php echo $stylep?>">The Top 5 QUALITY stocks in our full universe where market cap is > $500m.
        Stock universe includes financials, basic materials, utilities, OTC and ADR stocks. Read how the Action Score was created from <a href='http://www.oldschoolvalue.com/blog/valuation-methods/action-score-quality-value-growth/' target='_blank'>this article</a></p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr ;$td?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td50,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td50,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Grade
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Score
                </td>
                <td style="<?php echo $td50,$td?>">
                    Q
                <td style="<?php echo $td50,$td?>">
                    V
                </td>
                <td style="<?php echo $td50,$td?>">
                    G
                </td>
            </tr>
            <?php if (count($topQuality) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="8" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td>
            </tr>
            <?php } else {?>
            <?php foreach($topQuality as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 12)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?>">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo $stock["AS_grade"];?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["QT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["VT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["GT"], 2, '.', '');?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">Top 5 VALUE Stocks (MktCap>$500M)</h3>
        <p style="<?php echo $stylep?>">The Top 5 VALUE stocks in our full universe where market cap is > $500m.
        Stock universe includes financials, basic materials, utilities, OTC and ADR stocks. Read how the Action Score was created from <a href='http://www.oldschoolvalue.com/blog/valuation-methods/action-score-quality-value-growth/' target='_blank'>this article</a></p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr ;$td?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td50,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td50,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Grade
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Score
                </td>
                <td style="<?php echo $td50,$td?>">
                    Q
                <td style="<?php echo $td50,$td?>">
                    V
                </td>
                <td style="<?php echo $td50,$td?>">
                    G
                </td>
            </tr>
            <?php if (count($topValue) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="8" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td>
            </tr>
            <?php } else {?>
            <?php foreach($topValue as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 12)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?>">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo $stock["AS_grade"];?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["QT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["VT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["GT"], 2, '.', '');?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
        <h3 style="<?php echo $styleh3?>">Top 5 GROWTH Stocks (MktCap>$500M)</h3>
        <p style="<?php echo $stylep?>">The Top 5 GROWTH stocks in our full universe where market cap is > $500m.
        Stock universe includes financials, basic materials, utilities, OTC and ADR stocks. Read how the Action Score was created from <a href='http://www.oldschoolvalue.com/blog/valuation-methods/action-score-quality-value-growth/' target='_blank'>this article</a></p>
        <table style="<?php echo $table?>">
            <tr style="<?php echo $styletr ;$td?>">
                <td style="<?php echo $tdName200?>;color:#b1b4ba;">
                    Name
                </td >
                <td style="<?php echo $td50,$td?>">
                    Market Cap
                </td>
                <td style="<?php echo $td50,$td?>">
                    YTD %Chg
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Grade
                </td>
                <td style="<?php echo $td50,$td?>">
                    Action Score
                </td>
                <td style="<?php echo $td50,$td?>">
                    Q
                <td style="<?php echo $td50,$td?>">
                    V
                </td>
                <td style="<?php echo $td50,$td?>">
                    G
                </td>
            </tr>
            <?php if (count($topGrowth) == 0) {?>
            <tr style="<?php echo $styletr?>">
                <td colspan="8" style="<?php echo $td100?>;width:100%;">
                    There is no stocks matching this criteria
                </td>
            </tr>
            <?php } else {?>
            <?php foreach($topGrowth as $stock) {?>
            <tr style="<?php echo $styletr?>">
                <td style="<?php echo $tdName200?>">
                    <?php echo cutValue($stock["company"], 12)." (".$stock["ticker"].")";?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo formatCurrency($stock["MarketCapIntraday"]);?>
                </td>
                <td style="<?php echo $td100; echo $stock["YTD"] < 0 ? ' color:red;' : ' color:green;';?>">
                    <span class="caret" style="<?php echo $stock["YTD"] < 0 ? $spanDown : $spanUp;?>"></span><?php echo number_format($stock["YTD"], 2, '.', '').'%';?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo $stock["AS_grade"];?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["AS"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["QT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["VT"], 2, '.', '');?>
                </td>
                <td style="<?php echo $td50?>">
                    <?php echo number_format($stock["GT"], 2, '.', '');?>
                </td>
            </tr>
            <?php }?>
            <?php }?>
        </table>
    </div>
    <div style="clear: both"></div>
</div>
</body>
</html>
