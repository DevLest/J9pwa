<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

    include_once ("../../core.class.php");
	require_once __DIR__ . '/vendor/autoload.php';

    if(!isset($_SESSION))
    {
        session_start();
    }
	
	$fb = new Facebook\Facebook([
	  'app_id' => '506497531494122',
	  'app_secret' => '2f1ac4e6696128caa6890337c41eb51a',
	  'default_graph_version' => 'v2.10',
	]);

    $helper = $fb->getRedirectLoginHelper();
    $redirect = "https://999j9azx.u2d8899.com/j9pwa/oauth/facebook/callback.php";
	$permissions  = ['email'];
	$loginUrl = $helper->getLoginUrl($redirect,$permissions);
	
	$core = new core();
	$ip = $core->ip_information();
	$time = substr(time(),0,-3);
	$hashID = hash('ripemd160',$time.$ip['ip']);

	echo json_encode(["status" => 0, "info" => ["url" => $loginUrl, "id" => $hashID]]);