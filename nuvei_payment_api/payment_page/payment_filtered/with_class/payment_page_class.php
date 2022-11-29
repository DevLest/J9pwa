<?php
/**
 * Created by ReyCSINC
 * Date: 11/15/2022
 */
class payment_page_class
{

    // // Live: https://secure.safecharge.com/ppp/purchase.do
    // // Test: https://ppp-test.safecharge.com/ppp/purchase.do

    /**
     * SANDBOX, TEST ENVIRONMENT
     * 
     */
    // public $merchantId = '3840632691800786022';
    // public $merchantSiteId = '236068';
    // public $merchantSecretKey = 'S6GP8AWEMQPBWsb0ABnaPzivHKYowBvYjifGJYd1NXaF4ElYGegj9eISEogIK9XQ';
    // public $payment_page_url = "https://ppp-test.safecharge.com/ppp/purchase.do";

    /**
     * LIVE PRODUCTION ENVIRONMENT
     *
     */
    public $merchantId = '5675453314933410740';
    public $merchantSiteId = '238488';
    public $merchantSecretKey = 'whZGObtg8f4t5eWBoqf11RG9Zwc2GvaFmByQYRpEsmjjzVsh1MevRI1CatxLbA1L';
    public $payment_page_url = "https://secure.safecharge.com/ppp/purchase.do";

    
    

    public $amount;
    public $currency;
    public $email;
    public $item_name_1;
    // public $amount;
    public $item_quantity_1;
    public $time_stamp;
    public $version;
    public $user_token;
    public $merchantLocale;
    public $first_name;
    public $last_name;
    public $country;
    public $payment_method;
    public $payment_method_mode;
    public $notifyreturn_url;


    public function __construct(
        $amount,
        $currency = "MXN",
        $email = "unknown@email.com",
        $item_name_1 = 'deposit',
        // $amount ,
        $item_quantity_1 = 1,
        // $time_stamp ,
        $version = '4.0.0',
        $user_token = 'auto',
        $merchantLocale = 'es_ES', //English (US): en_US Spanish: es_ES
        $first_name = 'The',
        $last_name = 'Payor',
        $country = "MX",
        $payment_method = "cc_card",
        $payment_method_mode  = "filter",
        $notifyreturn_url = "https://pay.116tyc.net/mexico/nuvei_payment_api/notify.php"
    ) {

        // Live: https://secure.safecharge.com/ppp/purchase.do
        // Test: https://ppp-test.safecharge.com/ppp/purchase.do
        // $payment_page_url = " https://ppp-test.safecharge.com/ppp/purchase.do";
        // $default_notifyurl = "https://pay.116tyc.net/mexico/nuvei_payment_api/notify.php";
        // $currency = "MXN";
        // $merchantLocale = 'en_US'; //English (US): en_US Spanish: es_ES

        // LIST OF payment Methods https://docs.nuvei.com/documentation/apms-overview/apm-input-fields-and-apis/
        // paymentMethod: "cc_card", paymentMethodDisplayName: (1) […], logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/default_cc_card.svg", … }
        // paymentMethod: "apmgw_ApmEmulator", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/apm_test_nuvei.svg", … }​​
        // paymentMethod: "apmgw_expresscheckout", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/paypal.svg", … }​​
        // paymentMethod: "apmgw_Astropay_TEF", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/astropay-tef.svg", … }​​
        // paymentMethod: "apmgw_PaySafeCard", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/paysafecard.svg", … }​​
        // paymentMethod: "apmgw_AstroPay", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/astropay.svg", … }​​
        // paymentMethod: "apmgw_Neteller", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/neteller.svg", … }​​
        // paymentMethod: "apmgw_Fast_Bank_Transfer", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/fastbanktransfer.svg", … }​​
        // paymentMethod: "apmgw_Oxxo", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/oxxo.svg", … }​
        // paymentMethod: "apmgw_Todito_Cash", isDirect: "false", logoURL: "https://cdn-int.safecharge.com/ppp_resources/11101137/resources/img/svg/toditocash.svg", … }

        // $this->merchantId;
        // $this->merchantSiteId;
        $this->amount =  $amount;
        $this->currency =  $currency; //"MXN" =  // USD, MXN
        $this->email =  $email;
        $this->item_name_1 = $item_name_1;
        $this->amount =  $amount;
        $this->item_quantity_1 =  $item_quantity_1;
        $this->time_stamp = date("Y-m-d.H:i:s");
        $this->version = $version;
        $this->user_token = $user_token;
        $this->merchantLocale = $merchantLocale; //English (US): en_US Spanish: es_ES
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->country = $country;
        $this->payment_method = $payment_method;
        $this->payment_method_mode = $payment_method_mode;
        $this->notifyreturn_url = $notifyreturn_url;
    }

    public function getPaymentUrlPage_v3()
    {

        $merchant_id = $this->merchantId;
        $merchant_site_id = $this->merchantSiteId;
        $total_amount = $this->amount;
        $currency = $this->currency; //"MXN"; // USD, MXN
        $user_token_id = $this->email;
        // $item_name_1 = "deposit";
        $item_name_1 = $this->item_name_1;
        $item_amount_1 = $this->amount;
        $item_quantity_1 = 1;
        // $time_stamp = date("Y-m-d.H:i:s");
        $time_stamp = $this->time_stamp;
        // $version = '4.0.0';
        $version =  $this->version;
        // $user_token = 'auto';
        $user_token =  $this->user_token;
        $merchantLocale = $this->merchantLocale; //English (US): en_US Spanish: es_ES
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        $country = $this->country;
        $payment_method = $this->payment_method;
        // $payment_method_mode = "filter";
        $payment_method_mode = $this->payment_method_mode;
        $notify_url = $this->notifyreturn_url;


        $checksum = "";
        $checksum .= $this->merchantSecretKey;
        $checksum .= $merchant_id;
        $checksum .= $merchant_site_id;
        $checksum .= $total_amount;
        $checksum .= $currency;
        $checksum .= $user_token_id;
        $checksum .= $item_name_1;
        $checksum .= $item_amount_1;
        $checksum .= $item_quantity_1;
        $checksum .= $time_stamp;
        $checksum .= $version;
        $checksum .= $user_token;
        $checksum .= $merchantLocale;
        $checksum .= $first_name;
        $checksum .= $last_name;
        $checksum .= $country;
        $checksum .= $payment_method;
        $checksum .= $payment_method_mode;
        $checksum .= $notify_url;
        $checksum = hash('sha256', $checksum);

        $checkout_query_params = 'merchant_id=' . $merchant_id;
        $checkout_query_params .= '&merchant_site_id=' . $merchant_site_id;
        $checkout_query_params .= '&total_amount=' . $total_amount;
        $checkout_query_params .= '&currency=' . $currency;
        $checkout_query_params .= '&user_token_id=' . $user_token_id;
        $checkout_query_params .= '&item_name_1=' . $item_name_1;
        $checkout_query_params .= '&item_amount_1=' . $item_amount_1;
        $checkout_query_params .= '&item_quantity_1=' . $item_quantity_1;
        $checkout_query_params .= '&time_stamp=' . $time_stamp;
        $checkout_query_params .= '&version=' . $version;
        $checkout_query_params .= '&user_token=' . $user_token;
        $checkout_query_params .= '&merchantLocale=' . $merchantLocale;
        $checkout_query_params .= '&first_name=' . $first_name;
        $checkout_query_params .= '&last_name=' . $last_name;
        $checkout_query_params .= '&country=' . $country;
        $checkout_query_params .= '&payment_method=' . $payment_method;
        $checkout_query_params .= '&payment_method_mode=' . $payment_method_mode;
        $checkout_query_params .= '&notify_url=' . $notify_url;
        $checkout_query_params .= '&checksum=' . $checksum;


        // e.g. https: //ppp-test.safecharge.com/ppp/purchase.do?currency=EUR&item_name_1=Test%20Product&item_number_1=1&item_quantity_1=1&item_amount_1=50.00&numberofitems=1&encoding=utf-8&merchant_id=640817950595693192&merchant_site_id=148133&time_stamp=2018-05-15.02%3A35%3A21&version=4.0.0&user_token_id=ran100418_scobd%40mailinator.com&user_token=auto&total_amount=50.00&notify_url=https%3A%2F%2Fsandbox.nuvei.com%2Flib%2Fdemo_process_request%2Fresponse.php&theme_id=178113&checksum=3f907ff30d33239880c853ad5bdf0a0aaf3a351de7220d6e2379f8804b58097f
        $payment_page = $this->payment_page_url . '?' . $checkout_query_params;
        return $payment_page;
    }
}
