<?php

/**
 * Created by ReyCSINC
 * Date: 11/5/2022
 */
class Nuvei_Payment_Api
{


    // public $merchantId = '3840632691800786022';
    // public $merchantSiteId = '236068';
    // public $merchantSecretKey = 'S6GP8AWEMQPBWsb0ABnaPzivHKYowBvYjifGJYd1NXaF4ElYGegj9eISEogIK9XQ';
    // // Test https://ppp-test.nuvei.com/ppp/api/v1/getSessionToken.do
    // public $url_link = "https://ppp-test.nuvei.com";



    
    /**
     * LIVE PRODUCTION ENVIRONMENT
     *
     */
    public $merchantId = '5675453314933410740';
    public $merchantSiteId = '238488';
    public $merchantSecretKey = 'whZGObtg8f4t5eWBoqf11RG9Zwc2GvaFmByQYRpEsmjjzVsh1MevRI1CatxLbA1L';
    // public $payment_page_url = "https://secure.safecharge.com/ppp/purchase.do";
    // Live: https://secure.safecharge.com/ppp/api/v1/getSessionToken.do
    public $url_link = "https://secure.safecharge.com";

    


    public $env = 'int'; // Nuvei API environment - 'int' (integration) or 'prod' (production - default if omitted)

    public $billno = '';
    public $clientRequestId = '';
    public $clientUniqueId = '';
    public $timeStamp = '';


    public $currency = 'MXN';
    public $country = 'MX';
    public $currencyCode = 'MXN';
    public $countryCode = 'MX';
    public $languageCode = 'es';

    public $paymentMethod = 'apmgw_expresscheckout'; //paypal


    public $safecharge;

    public $remote_address = '';

    public function __construct()
    {
        date_default_timezone_set("Asia/Manila");

        $this->timeStamp = date("YmdHis");
        $this->billno = $this->timeStamp . mt_rand(10, 99);
        $this->clientRequestId = 'rid' . $this->billno;
        // $this->clientRequestId = '1C6CT7V1L';
        $this->clientUniqueId = 'uid' . $this->billno;
        // $this->clientUniqueId = '12345';
        $this->remote_address = $_SERVER['REMOTE_ADDR']; // or SERVER_ADDR



        /**
         * NUVEI PAYMENT API
         */
        //Initialize the SDK (see https://docs.nuvei.com/?p=53233#initializing-the-sdk)
        // require 'safecharge-php-2.1.7/init.php';

        // $config = [
        //     // 'environment'       => \SafeCharge\Api\Environment::INT, 
        //     'environment'       => \SafeCharge\Api\Environment::TEST,
        //     'merchantId'        => $this->merchantId,
        //     'merchantSiteId'    => $this->merchantSiteId,
        //     'merchantSecretKey' => $this->merchantSecretKey,
        // ];

        // // $safecharge = new \SafeCharge\Api\RestClient();

        // $this->safecharge = new \SafeCharge\Api\SafeCharge();
        // $this->safecharge->initialize($config);
    }

    public function generic_curl_request(
        $payload,
        $request_type = 'POST',
        $body_type = 'JSON',
        $return_type = 'OBJECT'
    ) {

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
        // var_dump($this->api_url);
        // echo '</pre>';


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_link . $this->api_url,
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

    // getSessionToken
    public function getSessionToken()
    {
        $this->api_url = '/ppp/api/v1/getSessionToken.do';
        // YYYYMMDDHHmmss
        // $timeStamp = date("YmdHis");
        $params = [
            "merchantId" => $this->merchantId,
            "merchantSiteId" => $this->merchantSiteId,
            "clientRequestId" => $this->clientRequestId,
            "timeStamp" => $this->timeStamp
        ];

        $checksum = "";
        foreach ($params as $key => $value) {
            $checksum .= $value;
        }
        $checksum .= $this->merchantSecretKey;

        // checksum FORMULA
        // $checksum = merchantId . merchantSiteId . clientRequestId . timeStamp. merchantSecretKey
        $checksum_new = hash('sha256', $checksum);

        $params['checksum'] = $checksum_new;

        // echo '<pre>';
        // print_r($params);
        // echo '</pre>';


        return $this->generic_curl_request($params);
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

    // public function accountCapture($sessionToken)
    // {
    //     //accountCapture
    //     $accountCaptureResponse = $this->safecharge->getPaymentService()->accountCapture([
    //         'sessionToken'      => $sessionToken,
    //         'merchantId'        => $this->merchantId,
    //         'merchantSiteId'    => $this->merchantSiteId,
    //         'paymentMethod'     => 'apmgw_STPmex',
    //         'userTokenId'       => 'test03@gmail.com',
    //         'currencyCode'      => 'MXN',
    //         'countryCode'       => 'MX',
    //         'amount'            => '200',
    //         'languageCode'      => 'en',
    //         'notificationUrl'   => 'http://gmtest.116tyc.net/mexico/nuvei_payment_api/notify.php'
    //     ]);



    //     echo '<pre>';
    //     print_r($accountCaptureResponse);
    //     echo '</pre>';
    // }

    public function accountCaptureV2(
        $sessionToken = "",
        $amount = "",
        $userTokenId = "test03@gmail.com",
        $notificationUrl = "http://gmtest.116tyc.net/mexico/nuvei_payment_api/notify.php",
        $paymentMethod = "apmgw_STPmex", 
        $currencyCode = "MXN",
        $countryCode = "MX",
        $languageCode = "en",
        )
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

        $this->api_url = '/ppp/api/v1/accountCapture.do';
        // YYYYMMDDHHmmss
        // $timeStamp = date("YmdHis");
        $params = [
            'sessionToken'      => $sessionToken,
            'merchantId'        => $this->merchantId,
            'merchantSiteId'    => $this->merchantSiteId,
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
        // $checksum .= $this->merchantSecretKey;

        // // checksum FORMULA
        // // $checksum = merchantId . merchantSiteId . clientRequestId . timeStamp. merchantSecretKey
        // $checksum_new = hash('sha256', $checksum);

        // $params['checksum'] = $checksum_new;

        // echo '<pre>';
        // print_r($params);
        // echo '</pre>';


        return $this->generic_curl_request($params);
    }

    public function payout_approval()
    {
        // {
        //     "merchantId": "427583496191624621",
        //     "merchantSiteId": "142033",
        //     "userTokenId": "230811147",
        //     "clientUniqueId": "12345",
        //     "amount": "200",
        //     "currency": "USD",
        //     "userPaymentOption":{
        //         "userPaymentOptionId":"1459503"
        //     },
        //     "deviceDetails":{ 
        //         "ipAddress":"192.168.2.38"
        //     },
        //     "timeStamp": "20190915143321",
        //     "checksum": "1cff28783432713e5dfc4fdc8f011b76"
        // }
        $this->api_url = '/ppp/api/v1/payout.do';
        // YYYYMMDDHHmmss
        // $timeStamp = date("YmdHis");
        $params = [
            // 'sessionToken'      => $sessionToken,
            'merchantId'        => $this->merchantId,
            'merchantSiteId'    => $this->merchantSiteId,
            'userTokenId'       => 'test03@gmail.com',
            'clientUniqueId'       => 'test03@gmail.com',
            'amount'            => '200',
            'currency'      => 'MXN',
            'userPaymentOption'       => [
                "userPaymentOptionId" => "1459503"
            ],
            'deviceDetails'       => [
                "ipAddress" => "127.0.0.1"
            ],
            'timeStamp'      => 'en'
        ];

        // $checksum = "";
        // foreach ($params as $key => $value) {
        //     $checksum .= $value;
        // }
        // $checksum .= $this->merchantSecretKey;

        // // checksum FORMULA
        // // $checksum = merchantId . merchantSiteId . clientRequestId . timeStamp. merchantSecretKey
        // $checksum_new = hash('sha256', $checksum);

        // $params['checksum'] = $checksum_new;

        // echo '<pre>';
        // print_r($params);
        // echo '</pre>';


        return $this->generic_curl_request($params);
    }


}

