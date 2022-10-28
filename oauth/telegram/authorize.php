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
    $user_data = $_GET;
    $params = http_build_query($user_data);
    $url = 'http://oldfront.u2d8899.com/j9pwa/oauth/google/callback.php?' . $params;
    

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