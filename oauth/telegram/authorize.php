<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");

include_once "../../core.class.php";

if (!isset($_SESSION)) {
    session_start();
}

$bot_id = 5664886742;
$redirectUrl = "https%3A%2F%2Fsghsrthth9i9.u2d12345.com%2Fmexicopwa%2Foauth%2Ftelegram%2Fget_data.php";

try
{
    $url = 'https://999j9azx.999game.online/j9pwa/oauth/telegram/telegram_login.php';
    
    $core = new core();
    $ip = $core->ip_information();
    $time = substr(time(),0,-3);
    $hashID = hash('ripemd160',$time.$ip['ip']);

    echo json_encode(["status" => 0, "info" => ["url" => $url, "id" => $hashID]]);
}
catch(Exception $e)
{
    echo json_encode(["status" => 0, "info" => $e->getMessage()]); 
}