<?php
ini_set('display_errors', 1);
//Initialize the SDK (see https://docs.nuvei.com/?p=53233#initializing-the-sdk)
require 'nuvei_payment_api.php';
//Initialize the SDK (see https://docs.nuvei.com/?p=53233#initializing-the-sdk)
// require 'safecharge-php-2.1.7/init.php';


$payout = New Nuvei_Payment_Api();

$sess = $payout->getSessionToken();

// echo '<pre>';
// print_r($sess);
// echo '</pre>';

$sessionToken = $sess->sessionToken;

// $payout->accountCapture($sessionToken);


$acv2 = $payout->accountCaptureV2($sessionToken, "200");


$payout_page = $acv2->redirectUrl;
header("Location: $payout_page");
