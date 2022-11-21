<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");

include_once "../../core.class.php";
require './google-api-client/vendor/autoload.php';

if (!isset($_SESSION)) {
    session_start();
}

$client = new Google_Client();

$client->setClientId("752252740023-ecbtv7ols8gjbsqn05mvc25khka9gosm.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("https://999j9azx.u2d8899.com/j9pwa/oauth/google/callback.php");

$client->addScope("email");
$client->addScope("profile");

$core = new core();
$ip = $core->ip_information();
$time = substr(time(),0,-3);
$hashID = hash('ripemd160',$time.$ip['ip']);

echo json_encode(["status" => 0, "info" => ["url" => $client->createAuthUrl(), "id" => $hashID]]);
