<?php
function update_yahoo_daily($pticker = NULL) {
    $db = Database::GetInstance(); 
    $yquery = true;
    try {
        $res = $db->query("SELECT value FROM system WHERE parameter = 'query_yahoo'");
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    $row = $res->fetch(PDO::FETCH_ASSOC);
    if($row["value"] == 0) {
        echo "Skip yahoo queries as they are currently dissabled.<br>\n";
        $yquery = false;
    }

    //Access on dev environment
    $username = 'osv';
    $password = 'test1234!';
    $context = stream_context_create(array(
                'http' => array(
                    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
                    )
                ));

    //Using customized Yahoo Social SDK (The default version does not work)
    $yql = new YahooYQLQuery();

    $count = 0;
    $count2 = 0;
    $dupdated = 0;
    $dnotfound = 0;
    $derrors = 0;
    $hupdated = 0;
    $hnotfound = 0;
    $herrors = 0;
    $kupdated = 0;
    $knotfound = 0;
    $kerrors = 0;
    $supdated = 0;
    $snotfound = 0;
    $supdated2 = 0;
    $snotfound2 = 0;
    $kbnotfound = 0;
    $kbupdated = 0;
    $eupdated = 0;
    $enotfound = 0;
    $eerrors = 0;
    $eupdated2 = 0;
    $enotfound2 = 0;

    $addq = "";
    if(!is_null($pticker)) {
        $addq = " AND ticker = '".$pticker."' ";
    }

    echo "Updating Tickers (yahoo)...<br>\n";

    //Select all tickers not updated for at least a day
    try {
        $res = $db->query("SELECT * FROM tickers t LEFT JOIN tickers_control tc ON t.id = tc.ticker_id WHERE TIMESTAMPDIFF(MINUTE,tc.last_yahoo_date,NOW()) > 1200 AND is_old = FALSE $addq order by ticker");
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {	
        $count ++;
        echo "Updating ".$row["ticker"]."...";

        if($yquery) {
            //UPDATE DIVIDEN HISTORY
            $response = $yql->execute("select * from osv.finance.dividendhistory where startDate = '".date("Y-m-d", strtotime("-1 years"))."' and endDate = '".date("Y-m-d")."' and  symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");	
            if(isset($response->query) && isset($response->query->results) && (is_array($response->query->results->quote) || ($response->query->results->quote instanceof Traversable))) {
                foreach($response->query->results->quote as $element) {
                    if (isset($element->Date) && !is_null($element->Date) && $element->Date!="0000-00-00") {

                        $query_div = "INSERT INTO `tickers_yahoo_dividend_history` (ticker_id, qtrDate, dividends) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE dividends = ?";
                        $params = array();
                        $params[] = $row["id"];
                        $params[] = $element->Date;
                        $params[] = (is_null($element->Dividends)?NULL:$element->Dividends);
                        $params[] = (is_null($element->Dividends)?NULL:$element->Dividends);
                        try {
                            $res1 = $db->prepare($query_div);
                            $res1->execute($params);
                        } catch(PDOException $ex) {
                            echo "\nDatabase Error"; //user message
                            die("Line: ".__LINE__." - ".$ex->getMessage());
                        }
                    }
                }
                $dupdated ++;
            } elseif(isset($response->error)) {
                $derrors ++;
            } else {
                $dnotfound ++;
            }
        }

        //UPDATE KEYSTATS, SECTOR, INDUSTRY AND DESCRIPTION
        //Try to get yahoo data for the ticker
        if($yquery) {
            $response = $yql->execute("select * from osv.finance.keystats_new where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
            if(isset($response->query) && isset($response->query->results)) {
                //Check if the symbol exists
                //Keystats
                if(isset($response->query->results->result->marketCap)) {
                    update_raw_data_yahoo_keystats($row["id"], $response->query->results->result);
                    $kupdated ++;
                } else {
                    $knotfound ++;
                }

                //Sector and Industry
                //Loading manually so disabling for now
                /*
                   if(isset($response->query->results->result->assetProfile->sector) && !empty($response->query->results->result->assetProfile->sector)) {
                   $supdated ++;
                   try {
                   $res1 = $db->prepare("UPDATE `tickers` SET industry = ?, sector = ? WHERE id = ?");
                   $res1->execute(array((is_null($response->query->results->result->assetProfile->industry)?'':$response->query->results->result->assetProfile->industry), (is_null($response->query->results->result->assetProfile->sector)?'':$response->query->results->result->assetProfile->sector), $row["id"]));					
                   } catch(PDOException $ex) {
                   echo "\nDatabase Error"; //user message
                   die("Line: ".__LINE__." - ".$ex->getMessage());
                   }

                   } else {
                   $snotfound ++;
                   }*/

                //Description
                if(isset($response->query->results->result->assetProfile->longBusinessSummary)) {
                    $supdated2 ++;
                    try {
                        $res1 = $db->prepare("UPDATE `tickers` SET description = ? WHERE id = ?");
                        $res1->execute(array((is_null($response->query->results->result->assetProfile->longBusinessSummary)?'':$response->query->results->result->assetProfile->longBusinessSummary), $row["id"]));					
                    } catch(PDOException $ex) {
                        echo "\nDatabase Error"; //user message
                        die("Line: ".__LINE__." - ".$ex->getMessage());
                    }
                } else {
                    $snotfound2 ++;
                }

            } elseif(isset($response->error)) {
                $kerrors ++;
            } else {
                $kerrors ++;
            }
        }

        // UPDATE QUOTES
        if($yquery) {
            $response = $yql->execute("select * from osv.finance.quotes where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
            if(isset($response->query) && isset($response->query->results)) {
                //Check if the symbol exists
                if(isset($response->query->results->quote)) {
                    $eupdated ++;
                    $rawdata = $response->query->results->quote;

                    $query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id`, `Ask`, `AverageDailyVolume`, `Bid`, `AskRealTime`, `BidRealTime`, `BookValue`, `Change`, `Commision`, `Currency`, `ChangeRealTime`, `AfterHoursChangeRealTime`, `DividendShare`, `LastTradeDate`, `TradeDate`, `EarningsShare`, `EPSEstimateCurrentYear`, `EPSEstimateNextYear`, `EPSEstimateNextQuarter`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`, `HoldingsGainPercent`, `AnnualizedGain`, `HoldingsGain`, `HoldingsGainPercentRealTime`, `AnnualizedGainRealTime`, `MoreInfo`, `OrderBookRealTime`, `MarketCapitalization`, `MarketCapRealTime`, `EBITDA`, `ChangeFromYearLow`, `PercentChangeFromYearLow`, `LastTradeRealTimeWithTime`, `ChangePercentRealTime`, `ChangeFromYearHigh`, `PercentChangeFromYearHigh`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `AskRealTime` = ?, `BidRealTime` = ?, `BookValue` = ?, `Commision` = ?, `Currency` = ?, `ChangeRealTime` = ?, `AfterHoursChangeRealTime` = ?, `DividendShare` = ?, `TradeDate` = ?, `EarningsShare` = ?, `EPSEstimateCurrentYear` = ?, `EPSEstimateNextYear` = ?, `EPSEstimateNextQuarter` = ?, `HoldingsGainPercent` = ?, `AnnualizedGain` = ?, `HoldingsGain` = ?, `HoldingsGainPercentRealTime` = ?, `AnnualizedGainRealTime` = ?, `MoreInfo` = ?, `OrderBookRealTime` = ?, `MarketCapitalization` = ?, `MarketCapRealTime` = ?, `EBITDA` = ?, `ChangeFromYearLow` = ?, `PercentChangeFromYearLow` = ?, `LastTradeRealTimeWithTime` = ?, `ChangePercentRealTime` = ?, `ChangeFromYearHigh` = ?, `PercentChangeFromYearHigh` = ?";
                    $params = array();
                    $params[] = $row["id"];
                    $params[] = (!isset($rawdata->Ask)?NULL:$rawdata->Ask);
                    $params[] = (!isset($rawdata->AverageDailyVolume)?NULL:$rawdata->AverageDailyVolume);
                    $params[] = (!isset($rawdata->Bid)?NULL:$rawdata->Bid);
                    $params[] = (!isset($rawdata->AskRealtime)?NULL:$rawdata->AskRealtime);
                    $params[] = (!isset($rawdata->BidRealtime)?NULL:$rawdata->BidRealtime);
                    $params[] = (!isset($rawdata->BookValue)?NULL:$rawdata->BookValue);
                    $params[] = (!isset($rawdata->Change)?NULL:$rawdata->Change);
                    $params[] = (!isset($rawdata->Commision)?NULL:$rawdata->Commision);
                    $params[] = $rawdata->Currency;
                    $params[] = (!isset($rawdata->ChangeRealtime)?NULL:$rawdata->ChangeRealtime);
                    $params[] = (!isset($rawdata->AfterHoursChangeRealtime)?NULL:$rawdata->AfterHoursChangeRealtime);
                    $params[] = (!isset($rawdata->DividendShare)?NULL:$rawdata->DividendShare);
                    $params[] = date("Y-m-d", strtotime($rawdata->LastTradeDate));
                    $params[] = date("Y-m-d", strtotime($rawdata->TradeDate));
                    $params[] = (!isset($rawdata->EarningsShare)?NULL:$rawdata->EarningsShare);
                    $params[] = (!isset($rawdata->EPSEstimateCurrentYear)?NULL:$rawdata->EPSEstimateCurrentYear);
                    $params[] = (!isset($rawdata->EPSEstimateNextYear)?NULL:$rawdata->EPSEstimateNextYear);
                    $params[] = (!isset($rawdata->EPSEstimateNextQuarter)?NULL:$rawdata->EPSEstimateNextQuarter);
                    $params[] = (!isset($rawdata->DaysLow)?NULL:$rawdata->DaysLow);
                    $params[] = (!isset($rawdata->DaysHigh)?NULL:$rawdata->DaysHigh);
                    $params[] = (!isset($rawdata->YearLow)?NULL:$rawdata->YearLow);
                    $params[] = (!isset($rawdata->YearHigh)?NULL:$rawdata->YearHigh);
                    $params[] = (!isset($rawdata->HoldingsGainPercent)?NULL:$rawdata->HoldingsGainPercent);
                    $params[] = (!isset($rawdata->AnnualizedGain)?NULL:$rawdata->AnnualizedGain);
                    $params[] = (!isset($rawdata->HoldingsGain)?NULL:$rawdata->HoldingsGain);
                    $params[] = (!isset($rawdata->HoldingsGainPercentRealtime)?NULL:$rawdata->HoldingsGainPercentRealtime);
                    $params[] = (!isset($rawdata->HoldingsGainRealtime)?NULL:$rawdata->HoldingsGainRealtime);
                    $params[] = $rawdata->MoreInfo;
                    $params[] = (!isset($rawdata->OrderBookRealtime)?NULL:$rawdata->OrderBookRealtime);
                    $params[] = (!isset($rawdata->MarketCapitalization)?NULL:$rawdata->MarketCapitalization);
                    $params[] = (!isset($rawdata->MarketCapRealtime)?NULL:$rawdata->MarketCapRealtime);
                    $params[] = (!isset($rawdata->EBITDA)?NULL:$rawdata->EBITDA);
                    $params[] = (!isset($rawdata->ChangeFromYearLow)?NULL:$rawdata->ChangeFromYearLow);
                    $params[] = (!isset($rawdata->PercentChangeFromYearLow)?NULL:$rawdata->PercentChangeFromYearLow);
                    if(isset($rawdata->LastTradeRealTimeWithTime)) {
                        $params[] = date("H:i",strtotime(substr($rawdata->LastTradeRealTimeWithTime, 0, strpos($rawdata->LastTradeRealTimeWithTime,"-")-1)));
                    } else {
                        $params[] = NULL;
                    }
                    $params[] = (!isset($rawdata->ChangePercentRealtime)?NULL:$rawdata->ChangePercentRealtime);
                    $params[] = (!isset($rawdata->ChangeFromYearHigh)?NULL:$rawdata->ChangeFromYearHigh);
                    $params[] = (!isset($rawdata->PercebtChangeFromYearHigh)?NULL:$rawdata->PercebtChangeFromYearHigh);

                    $params[] = (!isset($rawdata->AskRealtime)?NULL:$rawdata->AskRealtime);
                    $params[] = (!isset($rawdata->BidRealtime)?NULL:$rawdata->BidRealtime);
                    $params[] = (!isset($rawdata->BookValue)?NULL:$rawdata->BookValue);
                    $params[] = (!isset($rawdata->Commision)?NULL:$rawdata->Commision);
                    $params[] = $rawdata->Currency;
                    $params[] = (!isset($rawdata->ChangeRealtime)?NULL:$rawdata->ChangeRealtime);
                    $params[] = (!isset($rawdata->AfterHoursChangeRealtime)?NULL:$rawdata->AfterHoursChangeRealtime);
                    $params[] = (!isset($rawdata->DividendShare)?NULL:$rawdata->DividendShare);
                    $params[] = date("Y-m-d", strtotime($rawdata->TradeDate));
                    $params[] = (!isset($rawdata->EarningsShare)?NULL:$rawdata->EarningsShare);
                    $params[] = (!isset($rawdata->EPSEstimateCurrentYear)?NULL:$rawdata->EPSEstimateCurrentYear);
                    $params[] = (!isset($rawdata->EPSEstimateNextYear)?NULL:$rawdata->EPSEstimateNextYear);
                    $params[] = (!isset($rawdata->EPSEstimateNextQuarter)?NULL:$rawdata->EPSEstimateNextQuarter);
                    $params[] = (!isset($rawdata->HoldingsGainPercent)?NULL:$rawdata->HoldingsGainPercent);
                    $params[] = (!isset($rawdata->AnnualizedGain)?NULL:$rawdata->AnnualizedGain);
                    $params[] = (!isset($rawdata->HoldingsGain)?NULL:$rawdata->HoldingsGain);
                    $params[] = (!isset($rawdata->HoldingsGainPercentRealtime)?NULL:$rawdata->HoldingsGainPercentRealtime);
                    $params[] = (!isset($rawdata->HoldingsGainRealtime)?NULL:$rawdata->HoldingsGainRealtime);
                    $params[] = $rawdata->MoreInfo;
                    $params[] = (!isset($rawdata->OrderBookRealtime)?NULL:$rawdata->OrderBookRealtime);
                    $params[] = (!isset($rawdata->MarketCapitalization)?NULL:$rawdata->MarketCapitalization);
                    $params[] = (!isset($rawdata->MarketCapRealtime)?NULL:$rawdata->MarketCapRealtime);
                    $params[] = (!isset($rawdata->EBITDA)?NULL:$rawdata->EBITDA);
                    $params[] = (!isset($rawdata->ChangeFromYearLow)?NULL:$rawdata->ChangeFromYearLow);
                    $params[] = (!isset($rawdata->PercentChangeFromYearLow)?NULL:$rawdata->PercentChangeFromYearLow);
                    if(isset($rawdata->LastTradeRealTimeWithTime)) {
                        $params[] = date("H:i",strtotime(substr($rawdata->LastTradeRealTimeWithTime, 0, strpos($rawdata->LastTradeRealTimeWithTime,"-")-1)));
                    } else {
                        $params[] = NULL;
                    }
                    $params[] = (!isset($rawdata->ChangePercentRealtime)?NULL:$rawdata->ChangePercentRealtime);
                    $params[] = (!isset($rawdata->ChangeFromYearHigh)?NULL:$rawdata->ChangeFromYearHigh);
                    $params[] = (!isset($rawdata->PercebtChangeFromYearHigh)?NULL:$rawdata->PercebtChangeFromYearHigh);

                    try {
                        $res1 = $db->prepare($query);
                        $res1->execute($params);
                    } catch(PDOException $ex) {
                        echo "\nDatabase Error"; //user message
                        die("Line: ".__LINE__." - ".$ex->getMessage());
                    }


                    $query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradeWithTime`, `LastTradePriceOnly`, `HighLimit`, `LowLimit`, `FiftyDayMovingAverage`, `TwoHundredDayMovingAverage`, `ChangeFromTwoHundredDayMovingAverage`, `PercentageChangeFromTwoHundredDayMovingAverage`, `ChangeFromFiftyDayMovingAverage`, `PercentChangeFromFiftyDayMovingAverage`, `Name`, `Notes`, `Open`, `PreviousClose`, `PricePaid`, `ChangeInPercent`, `PriceSales`, `PriceBook`, `ExDividendDate`, `PERatio`, `DividendPayDate`, `PERatioRealTime`, `PEGRatio`, `PriceEPSEstimateCurrentYear`, `PriceEPSEstimateNextYear`, `SharesOwned`, `ShortRatio`, `LastTradeTime`, `TickerTrend`, `OneYrTargetPrice`, `Volume`, `HoldingsValue`, `HoldingsValueRealTime`, `DaysValueChange`, `DaysValueChangeRealTime`, `StockExchange`, `DividendYield`, `PercentChange`, `SharesOutstanding`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `LastTradeWithTime` = ?, `HighLimit` = ?, `LowLimit` = ?, `FiftyDayMovingAverage` = ?, `TwoHundredDayMovingAverage` = ?, `ChangeFromTwoHundredDayMovingAverage` = ?, `PercentageChangeFromTwoHundredDayMovingAverage` = ?, `ChangeFromFiftyDayMovingAverage` = ?, `PercentChangeFromFiftyDayMovingAverage` = ?, `Notes` = ?, `PricePaid` = ?, `PriceSales` = ?, `PriceBook` = ?, `PERatio` = ?, `DividendPayDate` = ?, `PERatioRealTime` = ?, `PEGRatio` = ?, `PriceEPSEstimateCurrentYear` = ?, `PriceEPSEstimateNextYear` = ?, `SharesOwned` = ?, `ShortRatio` = ?, `TickerTrend` = ?, `OneYrTargetPrice` = ?, `HoldingsValue` = ?, `HoldingsValueRealTime` = ?, `DaysValueChangeRealTime` = ?, `StockExchange` = ?, `SharesOutstanding` = ?";
                    $params = array();
                    $params[] = $row["id"];
                    if(isset($rawdata->LastTradeWithTime)) {
                        $params[] = date("H:i",strtotime(substr($rawdata->LastTradeWithTime, 0, strpos($rawdata->LastTradeWithTime,"-")-1)));
                    } else {
                        $params[] = NULL;
                    }
                    $params[] = (!isset($rawdata->LastTradePriceOnly)?NULL:$rawdata->LastTradePriceOnly);
                    $params[] = (!isset($rawdata->HighLimit)?NULL:$rawdata->HighLimit);
                    $params[] = (!isset($rawdata->LowLimit)?NULL:$rawdata->LowLimit);
                    $params[] = (!isset($rawdata->FiftydayMovingAverage)?NULL:$rawdata->FiftydayMovingAverage);
                    $params[] = (!isset($rawdata->TwoHundreddayMovingAverage)?NULL:$rawdata->TwoHundreddayMovingAverage);
                    $params[] = (!isset($rawdata->ChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->ChangeFromTwoHundreddayMovingAverage);
                    $params[] = (!isset($rawdata->PercentChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->PercentChangeFromTwoHundreddayMovingAverage);
                    $params[] = (!isset($rawdata->ChangeFromFiftydayMovingAverage)?NULL:$rawdata->ChangeFromFiftydayMovingAverage);
                    $params[] = (!isset($rawdata->PercentChangeFromFiftydayMovingAverage)?NULL:$rawdata->PercentChangeFromFiftydayMovingAverage);
                    $params[] = $rawdata->Name;
                    $params[] = $rawdata->Notes;
                    $params[] = (!isset($rawdata->Open)?NULL:$rawdata->Open);
                    $params[] = (!isset($rawdata->PreviousClose)?NULL:$rawdata->PreviousClose);
                    $params[] = (!isset($rawdata->PricePaid)?NULL:$rawdata->PricePaid);
                    $params[] = (!isset($rawdata->ChangeinPercent)?NULL:$rawdata->ChangeinPercent);
                    $params[] = (!isset($rawdata->PriceSales)?NULL:$rawdata->PriceSales);
                    $params[] = (!isset($rawdata->PriceBook)?NULL:$rawdata->PriceBook);
                    $params[] = date("Y-m-d", strtotime($rawdata->ExDividendDate));
                    $params[] = (!isset($rawdata->PERatio)?NULL:$rawdata->PERatio);
                    $params[] = date("Y-m-d", strtotime($rawdata->DividendPayDate));
                    $params[] = (!isset($rawdata->PERatioRealtime)?NULL:$rawdata->PERatioRealtime);
                    $params[] = (!isset($rawdata->PEGRatio)?NULL:$rawdata->PEGRatio);
                    $params[] = (!isset($rawdata->PriceEPSEstimateCurrentYear)?NULL:$rawdata->PriceEPSEstimateCurrentYear);
                    $params[] = (!isset($rawdata->PriceEPSEstimateNextYear)?NULL:$rawdata->PriceEPSEstimateNextYear);
                    $params[] = (!isset($rawdata->SharesOwned)?NULL:$rawdata->SharesOwned);
                    $params[] = (!isset($rawdata->ShortRatio)?NULL:$rawdata->ShortRatio);
                    if(isset($rawdata->LastTradeTime)) {
                        $params[] = date("H:i",strtotime($rawdata->LastTradeTime));
                    } else {
                        $params[] = NULL;
                    }
                    $params[] = $rawdata->TickerTrend;
                    $params[] = (!isset($rawdata->OneyrTargetPrice)?NULL:$rawdata->OneyrTargetPrice);
                    $params[] = (!isset($rawdata->Volume)?NULL:$rawdata->Volume);
                    $params[] = (!isset($rawdata->HoldingsValue)?NULL:$rawdata->HoldingsValue);
                    $params[] = (!isset($rawdata->HoldingsValueRealtime)?NULL:$rawdata->HoldingsValueRealtime);
                    $params[] = (!isset($rawdata->DaysValueChange)?NULL:$rawdata->DaysValueChange);
                    $params[] = (!isset($rawdata->DaysValueChangeRealtime)?NULL:$rawdata->DaysValueChangeRealtime);
                    $params[] = $rawdata->StockExchange;
                    $params[] = (!isset($rawdata->DividendYield)?NULL:$rawdata->DividendYield);
                    $params[] = (!isset($rawdata->PercentChange)?NULL:$rawdata->PercentChange);
                    $params[] = (!isset($rawdata->SharesOutstanding)?NULL:$rawdata->SharesOutstanding);

                    if(isset($rawdata->LastTradeWithTime)) {
                        $params[] = date("H:i",strtotime(substr($rawdata->LastTradeWithTime, 0, strpos($rawdata->LastTradeWithTime,"-")-1)));
                    } else {
                        $params[] = NULL;
                    }
                    $params[] = (!isset($rawdata->HighLimit)?NULL:$rawdata->HighLimit);
                    $params[] = (!isset($rawdata->LowLimit)?NULL:$rawdata->LowLimit);
                    $params[] = (!isset($rawdata->FiftydayMovingAverage)?NULL:$rawdata->FiftydayMovingAverage);
                    $params[] = (!isset($rawdata->TwoHundreddayMovingAverage)?NULL:$rawdata->TwoHundreddayMovingAverage);
                    $params[] = (!isset($rawdata->ChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->ChangeFromTwoHundreddayMovingAverage);
                    $params[] = (!isset($rawdata->PercentChangeFromTwoHundreddayMovingAverage)?NULL:$rawdata->PercentChangeFromTwoHundreddayMovingAverage);
                    $params[] = (!isset($rawdata->ChangeFromFiftydayMovingAverage)?NULL:$rawdata->ChangeFromFiftydayMovingAverage);
                    $params[] = (!isset($rawdata->PercentChangeFromFiftydayMovingAverage)?NULL:$rawdata->PercentChangeFromFiftydayMovingAverage);
                    $params[] = $rawdata->Notes;
                    $params[] = (!isset($rawdata->PricePaid)?NULL:$rawdata->PricePaid);
                    $params[] = (!isset($rawdata->PriceSales)?NULL:$rawdata->PriceSales);
                    $params[] = (!isset($rawdata->PriceBook)?NULL:$rawdata->PriceBook);
                    $params[] = (!isset($rawdata->PERatio)?NULL:$rawdata->PERatio);
                    $params[] = date("Y-m-d", strtotime($rawdata->DividendPayDate));
                    $params[] = (!isset($rawdata->PERatioRealtime)?NULL:$rawdata->PERatioRealtime);
                    $params[] = (!isset($rawdata->PEGRatio)?NULL:$rawdata->PEGRatio);
                    $params[] = (!isset($rawdata->PriceEPSEstimateCurrentYear)?NULL:$rawdata->PriceEPSEstimateCurrentYear);
                    $params[] = (!isset($rawdata->PriceEPSEstimateNextYear)?NULL:$rawdata->PriceEPSEstimateNextYear);
                    $params[] = (!isset($rawdata->SharesOwned)?NULL:$rawdata->SharesOwned);
                    $params[] = (!isset($rawdata->ShortRatio)?NULL:$rawdata->ShortRatio);
                    $params[] = $rawdata->TickerTrend;
                    $params[] = (!isset($rawdata->OneyrTargetPrice)?NULL:$rawdata->OneyrTargetPrice);
                    $params[] = (!isset($rawdata->HoldingsValue)?NULL:$rawdata->HoldingsValue);
                    $params[] = (!isset($rawdata->HoldingsValueRealtime)?NULL:$rawdata->HoldingsValueRealtime);
                    $params[] = (!isset($rawdata->DaysValueChangeRealtime)?NULL:$rawdata->DaysValueChangeRealtime);
                    $params[] = $rawdata->StockExchange;
                    $params[] = (!isset($rawdata->SharesOutstanding)?NULL:$rawdata->SharesOutstanding);

                    try {
                        $res1 = $db->prepare($query);
                        $res1->execute($params);
                    } catch(PDOException $ex) {
                        echo "\nDatabase Error "; //user message
                        die("- Line: ".__LINE__." - ".$ex->getMessage());
                    }

                    //Save histical SO
                    if(isset($rawdata->SharesOutstanding)) {
                        $query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, SharesOutstandingY) VALUES (?,?,?)  ON DUPLICATE KEY UPDATE SharesOutstandingY = ?";
                        $params = array();
                        $params[] = $row["id"];
                        $params[] = date("Y-m-d", strtotime($rawdata->LastTradeDate));
                        $params[] = $rawdata->SharesOutstanding;
                        $params[] = $rawdata->SharesOutstanding;
                        try {
                            $resbc = $db->prepare($query_div);
                            $resbc->execute($params);
                        } catch(PDOException $ex) {
                            echo "\nDatabase Error"; //user message
                            die("Line: ".__LINE__." - ".$ex->getMessage());
                        }
                    }
                } else {
                    $enotfound ++;
                }
            } elseif(isset($response->error)) {
                $eerrors ++;
            } else {
                $eerrors ++;
            }
        }

        // UPDATE DATES
        if($yquery) {
            $query_up = "UPDATE tickers_control SET last_yahoo_date = NOW() WHERE ticker_id = ? ";
            $params = array();
            $params[] = $row["id"];
            try {
                $res1 = $db->prepare($query_up);
                $res1->execute($params);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }
        }	
        echo " Done<br>\n";
    }

    echo "\nUpdating Tickers (barchart)...<br>\n";

    //Select all tickers not updated for at least a day
    try {
        $res = $db->query("SELECT * FROM tickers t INNER JOIN tickers_control tc ON t.id = tc.ticker_id WHERE TIMESTAMPDIFF(MINUTE,tc.last_barchart_date,NOW()) > 1200 AND is_old = FALSE $addq order by ticker");
    } catch(PDOException $ex) {
        echo "\nDatabase Error"; //user message
        die("Line: ".__LINE__." - ".$ex->getMessage());
    }
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $count2 ++;
        $procAlt = false;
        echo "Updating ".$row["ticker"]."...";

        //UPDATE HISTORICAL DATA
        try {
            $r_count = $db->query("select count(*) as a from `tickers_yahoo_historical_data` where ticker_id = '".$row["id"]."'");
            $r_row = $r_count->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }

        $sresponse = new stdClass();
        $split_date = date("Y-m-d",strtotime($row["last_split_date"]));
        if($yquery) {
            $sresponse = $yql->execute("select * from osv.finance.splits where symbol = '".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
        }
        $sym = $row["ticker"]; //get symbol from yahoo rawdata
        //Prequering Quotes in case we need for splits
        $resJS = array();
        $queryOD = "http://ondemand.websol.barchart.com/getQuote.json?apikey=fbb10c94f13efa7fccbe641643f7901f&symbols=".$row["ticker"]."&mode=I&fields=ask,avgVolume,bid,netChange,low,high,fiftyTwoWkLow,fiftyTwoWkHigh,lastPrice,percentChange,name,open,previousClose,exDividendDate,tradeTimestamp,volume,dividendYieldAnnual,sharesOutstanding,fiftyTwoWkHighDate,fiftyTwoWkLowDate,dividendRateAnnual,twentyDayAvgVol,averageQuarterlyVolume";
        $resOD = file_get_contents($queryOD);
        $resJS = json_decode($resOD, true);

        if($r_row["a"] < 260 || (isset($sresponse->query) && isset($sresponse->query->results) && isset($sresponse->query->results->splits) && isset($sresponse->query->results->splits->SplitDate) && $sresponse->query->results->splits->SplitDate > $split_date)) {
            $resJS1 = array();
            $queryOD1 = "http://ondemand.websol.barchart.com/getHistory.json?apikey=fbb10c94f13efa7fccbe641643f7901f&symbol=".$sym."&type=daily&startDate=".date("Ymd", strtotime("-15 years"))."&endDate=".date("Ymd")."";
            $resOD1 = file_get_contents($queryOD1);
            $resJS1 = json_decode($resOD1, true);
            $code = $resJS1['status']['code'];

            if($code == 200){
                $hupdated ++;
                foreach($resJS1['results'] as $record) {
                    $query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, open, high, low, close, volume, adj_close) VALUES (?,?,?,?,?,?,?,?)  ON DUPLICATE KEY UPDATE open = ?, high =  ?, low = ?, close = ?, volume = ?, adj_close = ?";
                    $params = array();
                    $params[] = $row["id"];
                    $params[] = $record['tradingDay'];
                    $params[] = $record['open'];
                    $params[] = $record['high'];
                    $params[] = $record['low'];
                    $params[] = $record['close'];
                    $params[] = $record['volume'];
                    $params[] = $record['close'];

                    $params[] = $record['open'];
                    $params[] = $record['high'];
                    $params[] = $record['low'];
                    $params[] = $record['close'];
                    $params[] = $record['volume'];
                    $params[] = $record['close'];
                    try {
                        $res1 = $db->prepare($query_div);
                        $res1->execute($params);
                    } catch(PDOException $ex) {
                        echo "\nDatabase Error"; //user message
                        die("Line: ".__LINE__." - ".$ex->getMessage());
                    }
                }
            } else {
                $herrors ++;
            }
            if (isset($sresponse->query) && isset($sresponse->query->results) && isset($sresponse->query->results->splits) && isset($sresponse->query->results->splits->SplitDate) && $sresponse->query->results->splits->SplitDate > $split_date) {
                try {
                    $res1 = $db->prepare("UPDATE tickers_control SET last_split_date = ? WHERE ticker_id = ?");
                    $res1->execute(array((date("Y-m-d",strtotime($sresponse->query->results->splits->SplitDate))), $row["id"]));
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }

                //UPDATE DIVIDEND HISTORY
                $divresponse = $yql->execute("select * from osv.finance.dividendhistory where startDate = '".date("Y-m-d", strtotime("-15 years"))."' and endDate = '".date("Y-m-d")."' and  symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");	
                if(isset($response->query) && isset($response->query->results)) {
                    foreach($divresponse->query->results->quote as $element) {
                        if (isset($element->Date) && !is_null($element->Date) && $element->Date!="0000-00-00") {

                            $query_div = "INSERT INTO `tickers_yahoo_dividend_history` (ticker_id, qtrDate, dividends) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE dividends = ?";
                            $params = array();
                            $params[] = $row["id"];
                            $params[] = $element->Date;
                            $params[] = (is_null($element->Dividends)?NULL:$element->Dividends);
                            $params[] = (is_null($element->Dividends)?NULL:$element->Dividends);
                            try {
                                $res_div = $db->prepare($query_div);
                                $res_div->execute($params);
                            } catch(PDOException $ex) {
                                echo "\nDatabase Error"; //user message
                                die("Line: ".__LINE__." - ".$ex->getMessage());
                            }
                        }
                    }
                }

                //UPDATE PORTFOLIOS
                list($splitFactor_div, $splitFactor_mul) = explode("/", $sresponse->query->results->splits->SplitFactor);
                try {
                    $query_port = "UPDATE portfolio_stocks SET current_shares = current_shares * ? / ? WHERE ticker_id = ?";
                    $res_port = $db->prepare($query_port);
                    $res_port->execute(array($splitFactor_div,$splitFactor_mul,$row["id"]));
                    $query_port = "UPDATE portfolio_transactions SET transac_price = transac_price * ? / ?, transac_shares = transac_shares * ? / ?, cost_per_share = cost_per_share * ? / ? WHERE ticker_id = ?";
                    $res_port = $db->prepare($query_port);
                    $res_port->execute(array($splitFactor_mul, $splitFactor_div, $splitFactor_div, $splitFactor_mul, $splitFactor_mul, $splitFactor_div, $row["id"]));
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }

                //INFORM BACKEND
                if(is_null($pticker)) {
                    if($resJS['status']['code'] == 200 && !is_null($resJS['results'][0]['sharesOutstanding'])){
                        $sharesOut = $resJS['results'][0]['sharesOutstanding']/1000;
                        //report to webservice so backend updates his own data
                        $tmp = file_get_contents("http://".SERVERHOST."/webservice/gf_split_parser.php?ticker=".$row["ticker"]."&split_date=".date("Y-m-d",strtotime($sresponse->query->results->splits->SplitDate))."&appkey=DgmNyOv2tUKBG5n6JzUI&shares=".$sharesOut, false, $context);
                    } else {
                        if($yquery) {
                            //Need to get latest shares outstandings from yahoo quotes to compare on webservices
                            $response = $yql->execute("select * from osv.finance.quotes where symbol='".str_replace(".", ",", $row["ticker"])."';", array(), 'GET', "oauth", "store://rNXPWuZIcepkvSahuezpUq");
                            $sharesOut = 0;
                            if(isset($response->query) && isset($response->query->results)) {
                                $sharesOut = $response->query->results->quote->SharesOutstanding / 1000000;
                            }
                            //report to webservice so backend updates his own data
                            $tmp = file_get_contents("http://".SERVERHOST."/webservice/gf_split_parser.php?ticker=".$row["ticker"]."&split_date=".date("Y-m-d",strtotime($sresponse->query->results->splits->SplitDate))."&appkey=DgmNyOv2tUKBG5n6JzUI&shares=".$sharesOut, false, $context);
                        }
                    }
                }
            }
        } else {
            $resJS1 = array();
            $queryOD1 = "http://ondemand.websol.barchart.com/getHistory.json?apikey=fbb10c94f13efa7fccbe641643f7901f&symbol=".$sym."&type=daily&startDate=".date("Ymd", strtotime("-1 month"))."&endDate=".date("Ymd")."";
            $resOD1 = file_get_contents($queryOD1);
            $resJS1 = json_decode($resOD1, true);
            $code = $resJS1['status']['code'];

            if($code == 200){
                foreach($resJS1['results'] as $record) {
                    if (isset($record['tradingDay']) && !is_null($record['tradingDay']) && $record['tradingDay']!="00000000") {
                        $query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, open, high, low, close, volume, adj_close) VALUES (?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE open = ?, high =  ?, low = ?, close = ?, volume = ?, adj_close = ?";
                        $params = array();
                        $params[] = $row["id"];
                        $params[] = $record['tradingDay'];
                        $params[] = $record['open'];
                        $params[] = $record['high'];
                        $params[] = $record['low'];
                        $params[] = $record['close'];
                        $params[] = $record['volume'];
                        $params[] = $record['close'];

                        $params[] = $record['open'];
                        $params[] = $record['high'];
                        $params[] = $record['low'];
                        $params[] = $record['close'];
                        $params[] = $record['volume'];
                        $params[] = $record['close'];
                        try {
                            $res1 = $db->prepare($query_div);
                            $res1->execute($params);
                        } catch(PDOException $ex) {
                            echo "\nDatabase Error"; //user message
                            die("Line: ".__LINE__." - ".$ex->getMessage());
                        }
                    }
                }
                $hupdated ++;
            } else {
                $herrors ++;
            }
        }

        //Keystats & Quotes From Barchart
        if($resJS['status']['code'] == 200){
            update_raw_data_barchart_keystats($row["id"], $resJS);
            $query = "INSERT INTO `tickers_yahoo_quotes_1` (`ticker_id` , `Ask`, `AverageDailyVolume`, `Bid`, `Change`, `DaysLow`, `DaysHigh`, `YearLow`, `YearHigh`, LastTradeDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)  ON DUPLICATE KEY UPDATE `Ask` = ?, `AverageDailyVolume` = ?, `Bid` = ?, `Change` = ?, `DaysLow` = ?, `DaysHigh` = ?, `YearLow` = ?, `YearHigh` = ?, LastTradeDate = ?";
            $params = array();
            $params[] = $row["id"];
            $params[] = $resJS['results'][0]['ask'];
            $params[] = $resJS['results'][0]['avgVolume'];
            $params[] = $resJS['results'][0]['bid'];
            $params[] = $resJS['results'][0]['netChange'];
            $params[] = $resJS['results'][0]['low'];
            $params[] = $resJS['results'][0]['high'];
            $params[] = $resJS['results'][0]['fiftyTwoWkLow'];
            $params[] = $resJS['results'][0]['fiftyTwoWkHigh'];
            $params[] = substr($resJS['results'][0]['tradeTimestamp'],0,10);

            $params[] = $resJS['results'][0]['ask'];
            $params[] = $resJS['results'][0]['avgVolume'];
            $params[] = $resJS['results'][0]['bid'];
            $params[] = $resJS['results'][0]['netChange'];
            $params[] = $resJS['results'][0]['low'];
            $params[] = $resJS['results'][0]['high'];
            $params[] = $resJS['results'][0]['fiftyTwoWkLow'];
            $params[] = $resJS['results'][0]['fiftyTwoWkHigh'];
            $params[] = substr($resJS['results'][0]['tradeTimestamp'],0,10);
            try {
                $resb = $db->prepare($query);
                $resb->execute($params);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }

            $query = "INSERT INTO `tickers_yahoo_quotes_2` (`ticker_id`, `LastTradePriceOnly` , `Name` , `Open` , `PreviousClose` , `ChangeInPercent` , `ExDividendDate` , `LastTradeTime` , `Volume` ,`DaysValueChange` , `DividendYield` , `PercentChange` , `SharesOutstandingBC`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `LastTradePriceOnly` = ?, `Name` = ?, `Open` = ?, `PreviousClose` = ?, `ChangeInPercent` = ?, `ExDividendDate` = ?, `LastTradeTime` = ?, `Volume` = ?,`DaysValueChange` = ?, `DividendYield` = ?, `PercentChange` = ?, `SharesOutstandingBC` = ?";

            $params = array();
            $params[] = $row["id"];
            $params[] = $resJS['results'][0]['lastPrice'];
            $params[] = $resJS['results'][0]['name'];
            $params[] = $resJS['results'][0]['open'];
            $params[] = $resJS['results'][0]['previousClose'];
            $params[] = $resJS['results'][0]['percentChange'];
            $params[] = $resJS['results'][0]['exDividendDate'];
            $params[] = substr($resJS['results'][0]['tradeTimestamp'],11,8);
            $params[] = $resJS['results'][0]['volume'];
            $params[] = $resJS['results'][0]['netChange'];
            $params[] = $resJS['results'][0]['dividendYieldAnnual'];
            $params[] = $resJS['results'][0]['percentChange'];
            $params[] = (is_null($resJS['results'][0]['sharesOutstanding']))?null:$resJS['results'][0]['sharesOutstanding']*1000;

            $params[] = $resJS['results'][0]['lastPrice'];
            $params[] = $resJS['results'][0]['name'];
            $params[] = $resJS['results'][0]['open'];
            $params[] = $resJS['results'][0]['previousClose'];
            $params[] = $resJS['results'][0]['percentChange'];
            $params[] = $resJS['results'][0]['exDividendDate'];
            $params[] = substr($resJS['results'][0]['tradeTimestamp'],11,8);
            $params[] = $resJS['results'][0]['volume'];
            $params[] = $resJS['results'][0]['netChange'];
            $params[] = $resJS['results'][0]['dividendYieldAnnual'];
            $params[] = $resJS['results'][0]['percentChange'];
            $params[] = (is_null($resJS['results'][0]['sharesOutstanding']))?null:$resJS['results'][0]['sharesOutstanding']*1000;
            try {
                $resbc = $db->prepare($query);
                $resbc->execute($params);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }

            if(!is_null($resJS['results'][0]['sharesOutstanding'])) {
                $query_div = "INSERT INTO `tickers_yahoo_historical_data` (ticker_id, report_date, SharesOutstandingBC) VALUES (?,?,?)  ON DUPLICATE KEY UPDATE SharesOutstandingBC = ?";
                $params = array();
                $params[] = $row["id"];
                $params[] = substr($resJS['results'][0]['tradeTimestamp'],0,10);
                $params[] = $resJS['results'][0]['sharesOutstanding'] * 1000;
                $params[] = $resJS['results'][0]['sharesOutstanding'] * 1000;
                try {
                    $resbc = $db->prepare($query_div);
                    $resbc->execute($params);
                } catch(PDOException $ex) {
                    echo "\nDatabase Error"; //user message
                    die("Line: ".__LINE__." - ".$ex->getMessage());
                }
            }

            $kbupdated ++;
            $eupdated2 ++;
            $procAlt = true;
        } else {
            $kbnotfound++;
            $enotfound2 ++;
        }

        //Update altman data
        if($procAlt && is_null($pticker)) {
            altmanTTM($row["id"]);
            update_pio_checks($row["id"]);
            $query1 = "SELECT *,
                (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
                 THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
                (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
                    FROM (SELECT c.id,a.*, MarketCapitalization as MarketValueofEquity,
                            (CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE MarketCapitalization / TotalLiabilities END) AS X4
                            FROM tickers c, mrq_alt_checks a, tickers_yahoo_quotes_1 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
            try {
                $res1 = $db->query($query1);
                $row1 = $res1->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("- Line: ".__LINE__." - ".$ex->getMessage());
            }

            $query2 = "SELECT *,
                (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL OR X5 IS NULL)
                 THEN NULL ELSE (1.2 * X1 + 1.4 * X2 + 3.3 * X3 + 0.6 * X4 + 0.999 * X5) END) AS AltmanZNormal,
                (CASE WHEN (X1 IS NULL OR X2 IS NULL OR X3 IS NULL OR X4 IS NULL) THEN NULL ELSE (6.56 * X1 + 3.26 * X2 + 6.72 * X3 + 1.05 * X4) END) AS AltmanZRevised
                    FROM (SELECT c.id,a.*, SharesOutstandingDiluted * LastTradePriceOnly as MarketValueofEquity,
                            (CASE WHEN (TotalLiabilities IS NULL OR TotalLiabilities = 0) THEN NULL ELSE SharesOutstandingDiluted * LastTradePriceOnly / TotalLiabilities END) AS X4
                            FROM tickers c, ttm_alt_checks a, tickers_yahoo_quotes_2 b WHERE c.id=a.ticker_id and c.id=b.ticker_id AND c.id=".$row["id"].") AS x";
            try {
                $res2 = $db->query($query2);
                $row2 = $res2->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("- Line: ".__LINE__." - ".$ex->getMessage());
            }

            $query = "INSERT INTO  `tickers_alt_aux` (`ticker_id` ,`mrq_MarketValueofEquity` ,`mrq_X4` ,`mrq_AltmanZNormal` ,`mrq_AltmanZRevised` ,`ttm_MarketValueofEquity`, `ttm_X4` ,`ttm_AltmanZNormal` ,`ttm_AltmanZRevised`) VALUES (?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE `mrq_MarketValueofEquity`=? ,`mrq_X4`=? ,`mrq_AltmanZNormal`=? ,`mrq_AltmanZRevised`=? ,`ttm_MarketValueofEquity`=?, `ttm_X4`=? ,`ttm_AltmanZNormal`=? ,`ttm_AltmanZRevised`=?";
            $params = array();

            if(is_null($row1)) {
                $params[] = null;
                $params[] = null;
                $params[] = null;
                $params[] = null;
            } else {
                $params[] = $row1["MarketValueofEquity"];
                $params[] = $row1["X4"];
                $params[] = $row1["AltmanZNormal"];
                $params[] = $row1["AltmanZRevised"];
            }
            if(is_null($row2)) {
                $params[] = null;
                $params[] = null;
                $params[] = null;
                $params[] = null;
            } else {
                $params[] = $row2["MarketValueofEquity"];
                $params[] = $row2["X4"];
                $params[] = $row2["AltmanZNormal"];
                $params[] = $row2["AltmanZRevised"];
            }
            $params = array_merge($params,$params);
            array_unshift($params,$row["id"]);

            try {
                $resf = $db->prepare($query);
                $resf->execute($params);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("- Line: ".__LINE__." - ".$ex->getMessage());
            }

            try {
                $db->exec("UPDATE tickers_control SET last_volatile_date = NOW() WHERE ticker_id = " . $row["id"]);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("- Line: ".__LINE__." - ".$ex->getMessage());
            }
        }

        //Update key ratios ttm
        if(is_null($pticker)) {
            update_key_ratios_ttm($row["id"]);
            update_eod_valuation($row["id"]);
        }

        // UPDATE DATES
        if($resJS['status']['code'] == 200) {
            $query_up = "UPDATE tickers_control SET last_barchart_date = NOW() WHERE ticker_id = ? ";
            $params = array();
            $params[] = $row["id"];
            try {
                $res1 = $db->prepare($query_up);
                $res1->execute($params);
            } catch(PDOException $ex) {
                echo "\nDatabase Error"; //user message
                die("Line: ".__LINE__." - ".$ex->getMessage());
            }
        }
        echo " Done<br>\n";
    }

    if(is_null($pticker)) {
        echo "Removing old Quality Checks (PIO)... ";
        try {
            $res = $db->query("delete a from reports_pio_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        echo "done<br>\n";
        echo "Removing old Quality Checks (ALTMAN)... ";
        try {
            $res = $db->query("delete a from reports_alt_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        echo "done<br>\n";
        echo "Removing old Quality Checks (BENEISH)... ";
        try {
            $res = $db->query("delete a from reports_beneish_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        echo "done<br>\n";
        echo "Removing old Quality Checks (DUPONT)... ";
        try {
            $res = $db->query("delete a from reports_dupont_checks a left join reports_header b on a.report_id = b.id where b.id IS null");
        } catch(PDOException $ex) {
            echo "\nDatabase Error"; //user message
            die("Line: ".__LINE__." - ".$ex->getMessage());
        }
        echo "done<br>\n";

        echo $count . " rows processed for yahoo\n";
        echo $count2 . " rows processed for barchart\n";
        echo "Dividend History (yahoo):\n";
        echo "\t".$dupdated." tickers updates\n";
        echo "\t".$dnotfound." tickers not found on yahoo\n";
        echo "\t".$derrors." errors updating tickers\n";
        echo "Historical Data (barchart):\n";
        echo "\t".$hupdated." tickers updates\n";
        echo "\t".$herrors." tickers not found on barchart\n";
        echo "Key Stats (mixed):\n";
        echo "\t".$kupdated." tickers updates from yahoo\n";
        echo "\t".$knotfound." tickers not found on yahoo\n";
        echo "\t".$kbupdated." tickers updates from barchart\n";
        echo "\t".$kbnotfound." tickers not found on barchart\n";
        echo "\t".$kerrors." errors updating tickers\n";
        echo "Sector & Industry (yahoo):\n";
        echo "\t".$supdated." tickers updates\n";
        echo "\t".$snotfound." tickers not found on yahoo\n";
        echo "\t".$kerrors." errors updating tickers\n";
        echo "Description (yahoo):\n";
        echo "\t".$supdated2." tickers updates\n";
        echo "\t".$snotfound2." tickers not found on yahoo\n";
        echo "\t".$kerrors." errors updating tickers\n";
        echo "Quotes Yahoo:\n";
        echo "\t".$eupdated." tickers updates\n";
        echo "\t".$enotfound." tickers not found on yahoo\n";
        echo "\t".$eerrors." errors updating tickers\n";
        echo "Quotes Barchart:\n";
        echo "\t".$eupdated2." tickers updates\n";
        echo "\t".$enotfound2." tickers not found on barchart\n";

        echo "Updating Ratings TTM... ";
        update_ratings_ttm();
    }
    echo "done<br>\n";
}
?>
