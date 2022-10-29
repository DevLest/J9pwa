<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");

include_once "../../core.class.php";

if (!isset($_SESSION)) {
    session_start();
}

try
{
    $url = 'https://oauth.telegram.org/auth?bot_id=5700543622&origin=https%3A%2F%2Foldfront.u2d8899.com%2Fj9pwa%2Foauth%2Ftelegram%2Fget_data.php&request_access=true&return_to=https%3A%2F%2Foldfront.u2d8899.com%2Fj9pwa%2Foauth%2Ftelegram%2Fget_data.php';
    
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