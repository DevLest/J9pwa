<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    define("WEB_PATH", __DIR__);
    include_once ("core.class.php");

	if(!isset($_SESSION))
	{
		session_start();
	}
	
    $lang = json_decode(file_get_contents("./language/".(isset($data->lang) ? $data->lang : "en").".json"));
    $lang = $lang->footer;

    $data = (object) $_POST;

	//check auth
    $api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $data->auth;
 
    if($auth_check != $auth)
	{
		echo json_encode(['status'=>0,'info'=>$lang->verification_failed], JSON_UNESCAPED_UNICODE );
		exit();
	}

	$client = new PHPRPC_Client(SERVER_URL);
	$result = unserialize($client->get_footer_english_list("", 3));

    $output = [];

	if(is_array($result))
	{
        unset($result['status']);
        foreach ( $result as $data ) {
            $output[strtolower(str_replace(' ', '_', $data['title']))] = $data['content'];
        }

		echo json_encode(array("status"=>1,"info"=>$output));
        exit();
	}
    else{
		echo json_encode(array("status"=>0,"info"=>$lang->unsuccessful_offer));
        exit();
	}