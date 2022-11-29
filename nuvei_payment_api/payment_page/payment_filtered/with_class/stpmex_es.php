<?php

ini_set('display_errors', 1);

require_once 'payment_page_class.php';

header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

$info = $_POST;

$amount = (isset($info['amount'])) ? $info['amount'] : 200;
$billno = (isset($info['billno'])) ? $info['billno'] : date("YmdHis") . mt_rand(10, 99);
$notifyreturn_url = (isset($info['return_url'])) ? $info['return_url'] : $default_notifyurl;
$email = (isset($info['email'])) ? $info['email'] : "unknown@email.com";

// e.g. cc_card, apmgw_ApmEmulator, apmgw_expresscheckout,
// apmgw_Astropay_TEF, apmgw_PaySafeCard, apmgw_AstroPay, 
// apmgw_Neteller, apmgw_Fast_Bank_Transfer, apmgw_Oxxo, apmgw_Todito_Cash
$payment_method = "apmgw_STPmex";
$merchantLocale = "es_ES"; //English (US): en_US Spanish: es_ES
$country = "MX";

$app = new payment_page_class(
    $amount,  // $amount
    "MXN", // $currency
    $email, // $email
    'deposit', // $item_name_1
    1, // $item_quantity_1
    '4.0.0', // $version
    'auto', // $user_token
    $merchantLocale, // $merchantLocale //English (US): en_US Spanish: es_ES
    'The', // $first_name
    'Payor', // $last_name
    $country, // $country
    $payment_method, // $payment_method
    "filter", // $payment_method_mode
    $notifyreturn_url // $notifyreturn_url
);

$payment_page = $app->getPaymentUrlPage_v3();

header("Location: $payment_page");