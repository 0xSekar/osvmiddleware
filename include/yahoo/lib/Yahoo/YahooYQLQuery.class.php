<?php

/**
 * Yahoo! PHP5 SDK
 *
 *  * Yahoo! Query Language
 *  * Yahoo! Social API
 *
 * Find documentation and support on Yahoo! Developer Network: http://developer.yahoo.com
 *
 * Hosted on GitHub: http://github.com/yahoo/yos-social-php5/tree/master
 *
 * @package    yos-social-php5
 * @subpackage yahoo
 *
 * @author     Dustin Whittle <dustin@yahoo-inc.com>
 * @copyright  Copyrights for code authored by Yahoo! Inc. is licensed under the following terms:
 * @license    BSD Open Source License
 *
 *   Permission is hereby granted, free of charge, to any person obtaining a copy
 *   of this software and associated documentation files (the "Software"), to deal
 *   in the Software without restriction, including without limitation the rights
 *   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *   copies of the Software, and to permit persons to whom the Software is
 *   furnished to do so, subject to the following conditions:
 *
 *   The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *   THE SOFTWARE.
 **/

require_once('YahooCurl.class.php');

class YahooYQLQuery
{

  # Yahoo! YQL API
  const DATATABLES_URL  = 'http://datatables.org/alltables.env';

  public function execute($yql, $parameters = array(), $method = YahooCurl::GET, $endpoint = "default", $env = null)
  {
    $PUBLIC_API_URL  = YAHOO_INTEGRATION_URL . '/v1/public/yql';
    $OAUTH_API_URL   = YAHOO_INTEGRATION_URL . '/v1/yql';
    $env = (is_null($env)) ? self::DATATABLES_URL : $env;
    $parameters = array_merge(array('q' => $yql, 'format' => 'json', 'env' => $env), $parameters);
    $url = ( $endpoint == "oauth" ) ? $OAUTH_API_URL : $PUBLIC_API_URL;

    if ($endpoint == "oauth") {
        $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
	$oauthConsumer = new OAuthConsumer(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, NULL);
	$oauthRequest = OAuthRequest::from_consumer_and_token($oauthConsumer, NULL, $method, $url, $parameters);
	$oauthRequest->sign_request($signatureMethod, $oauthConsumer, NULL);
	$parameters = $oauthRequest->parameters;
    }

    $http = YahooCurl::fetch($url, $parameters, array(), $method);
    //print_r( $http );
    return ($http) ? json_decode($http['response_body']) : false;
  }

}
