<?php

//  $merchantId = '3840632691800786022';
//  $merchantSiteId = '236068';
//  $merchantSecretKey = 'S6GP8AWEMQPBWsb0ABnaPzivHKYowBvYjifGJYd1NXaF4ElYGegj9eISEogIK9XQ';
// // Test https://ppp-test.nuvei.com/ppp/api/v1/getSessionToken.do
//  $url_link = "https://ppp-test.nuvei.com";

/**
 * LIVE PRODUCTION ENVIRONMENT
 *
 */
 $merchantId = '5675453314933410740';
 $merchantSiteId = '238488';
 $merchantSecretKey = 'whZGObtg8f4t5eWBoqf11RG9Zwc2GvaFmByQYRpEsmjjzVsh1MevRI1CatxLbA1L';
//  $payment_page_url = "https://secure.safecharge.com/ppp/purchase.do";
// Live: https://secure.safecharge.com/ppp/api/v1/getSessionToken.do
 $url_link = "https://secure.safecharge.com";

 date_default_timezone_set("Asia/Manila");

$timeStamp = date("YmdHis");
$billno = $timeStamp . mt_rand(10, 99);
$clientRequestId = 'rid' . $billno;
// $clientRequestId = '1C6CT7V1L';
$clientUniqueId = 'uid' . $billno;
// $clientUniqueId = '12345';
$remote_address = $_SERVER['REMOTE_ADDR']; // or SERVER_ADDR

$sess = getSessionToken();

$sessionToken = $sess->sessionToken;


// GET THE PAYOUT REDIRECT URL HERE
$accountCapture = accountCapture($sessionToken, "200");

$payout_page = $accountCapture->redirectUrl;

// Redirect user to Payout Page
header("Location: $payout_page");


// FUNCTIONS HERE:
function accountCapture($sessionToken, $amount)
{
    // {
        //     "sessionToken":"9610a8f6-44cf-4c4f-976a-005da69a2a3b",
        //     "merchantId":"427583496191624621",
        //     "merchantSiteId":"142033",
        //     "paymentMethod":"apmgw_BankPayouts",
        //     "userTokenId":"230811147",
        //     "currencyCode":"USD",
        //     "countryCode":"US",
        //     "amount":"200",
        //     "languageCode":"en",
        //     "notificationUrl":"<notificationURL>"
        // }

        // api_url = '/ppp/api/v1/accountCapture.do';
        // YYYYMMDDHHmmss
        // $timeStamp = date("YmdHis");
        // $sessionToken = "";
        // $amount = "";
        $userTokenId = "test03@gmail.com";
        $notificationUrl = "http://gmtest.116tyc.net/mexico/nuvei_payment_api/notify.php";
        $paymentMethod = "apmgw_STPmex";
        $currencyCode = "MXN";
        $countryCode = "MX";
        $languageCode = "en";


        $params = [
            'sessionToken'      => $sessionToken,
            'merchantId'        => $GLOBALS['merchantId'],
            'merchantSiteId'    => $GLOBALS['merchantSiteId'],
            'paymentMethod'     => $paymentMethod,
            'userTokenId'       => $userTokenId,
            'currencyCode'      => $currencyCode,
            'countryCode'       => $countryCode,
            'amount'            => $amount,
            'languageCode'      => $languageCode,
            'notificationUrl'   => $notificationUrl,
        ];

        // $checksum = "";
        // foreach ($params as $key => $value) {
        //     $checksum .= $value;
        // }
        // $checksum .= merchantSecretKey;

        // // checksum FORMULA
        // // $checksum = merchantId . merchantSiteId . clientRequestId . timeStamp. merchantSecretKey
        // $checksum_new = hash('sha256', $checksum);

        // $params['checksum'] = $checksum_new;

        // echo '<pre>';
        // print_r($params);
        // echo '</pre>';


        return generic_curl_request($params,'/ppp/api/v1/accountCapture.do');
}

function getSessionToken()
{
    // echo '<pre>';
    // print_r($GLOBALS);
    // echo '</pre>';
    // die();
    // var_dump();die();
    // $api_url = '/ppp/api/v1/getSessionToken.do';
    // YYYYMMDDHHmmss
    // $timeStamp = date("YmdHis");
    $params = [
        "merchantId" => $GLOBALS['merchantId'],
        "merchantSiteId" => $GLOBALS['merchantSiteId'],
        "clientRequestId" => $GLOBALS['clientRequestId'],
        "timeStamp" => $GLOBALS['timeStamp']
    ];

    $checksum = "";
    foreach ($params as $key => $value) {
        $checksum .= $value;
    }
    $checksum .= $GLOBALS['merchantSecretKey'];

    // checksum FORMULA
    // $checksum = merchantId . merchantSiteId . clientRequestId . timeStamp. merchantSecretKey
    $checksum_new = hash('sha256', $checksum);

    $params['checksum'] = $checksum_new;

    // echo '<pre>';
    // print_r($params);
    // echo '</pre>';


    return generic_curl_request($params,'/ppp/api/v1/getSessionToken.do');
    // SAMPLE SUCCESS RESPONSE
    // stdClass Object
    // (
    //     [sessionToken] => 4e144189-44ed-4a51-8596-f27abfa87719
    //     [internalRequestId] => 550390668
    //     [status] => SUCCESS
    //     [errCode] => 0
    //     [reason] => 
    //     [merchantId] => 3840632691800786022
    //     [merchantSiteId] => 236068
    //     [version] => 1.0
    //     [clientRequestId] => rid2022110522241079
    // )
}

function generic_curl_request(
    $payload,
    $api_url,
    $request_type = 'POST',
    $body_type = 'JSON',
    $return_type = 'OBJECT'
) {

    // sandbox
    // $url_link = "https://ppp-test.nuvei.com";

    // Live Prod
    // $url_link = "https://secure.safecharge.com";


    if ($body_type == 'JSON') {
        $PAYLOAD = json_encode($payload); // for json request
        $HEADER = array(
            'Content-Type: application/json',
        );
    } else if ($body_type == 'form-data') {
        $PAYLOAD = http_build_query($payload); // form data request
        $HEADER = '';
    }

    // echo '<br/> cURL PAYLOAD <pre>';
    // print_r($PAYLOAD);
    // echo '</pre>';

    // echo '<br/> API URL <pre>';
    // var_dump($api_url);
    // echo '</pre>';


    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $GLOBALS['url_link'] . $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $request_type,
        CURLOPT_POSTFIELDS => $PAYLOAD,
        CURLOPT_HTTPHEADER => $HEADER,
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    if ($return_type = 'OBJECT') {
        $response = json_decode($response);
    } else if ($return_type = 'ASSOCIATIVE_ARRAY') {
        $response = json_decode($response, true);
    }

    return $response;
}