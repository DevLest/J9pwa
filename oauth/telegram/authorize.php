<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");

include_once "../../core.class.php";

if (!isset($_SESSION)) {
    session_start();
}

$bot_id = 5664886742;
$redirectUrl = "https%3A%2F%2F999j9azx.u2d8899.com%2Fj9pwa%2Foauth%2Ftelegram%2Fget_data.php";

try
{
    $url = 'https://999j9azx.u2d8899.com/j9pwa/oauth/telegram/telegram_login.php';
    // $url = 'https://oauth.telegram.org/auth?bot_id='.$bot_id.'&origin='.$redirectUrl.'&return_to='.$redirectUrl;
    // $url = 'https://oauth.telegram.org/auth?bot_id=5664886742&origin=https%3A%2F%2F999j9azx.u2d8899.com%2Fj9pwa%2Foauth%2Ftelegram%2Fget_data.php?&request_access=true&return_to=https%3A%2F%2F999j9azx.u2d8899.com%2Fj9pwa%2Foauth%2Ftelegram%2Fget_data.php?';
    // https://oauth.telegram.org/auth?bot_id=547043436&origin=https%3A%2F%2Fcore.telegram.org&embed=1&request_access=write&return_to=https%3A%2F%2Fcore.telegram.org%2Fwidgets%2Flogin
    // https://oauth.telegram.org/auth/push?bot_id=5664886742&origin=https%3A%2F%2F999j9azx.u2d8899.com%2Fj9pwa%2Foauth%2Ftelegram%2Fget_data.php&embed=1&request_access=write&return_to=https%3A%2F%2F999j9azx.u2d8899.com%2Fj9pwa%2Foauth%2Ftelegram%2Fget_data.php
    
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