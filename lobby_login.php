<?php
header("Content-type: text/html; charset=utf-8");

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: https://u2daszapp.u2d8899.com");
header("Access-Control-Allow-Credentials:true");
include_once("client/phprpc_client.php");
define("PHPRPC_CASHIERFORMYSQLI","http://adminu2dnewonesite.32sun.com/phprpc/cashierformysqli.php");
if(!isset($_SESSION))
{
    session_start();
}
//echo 758757;exit;
	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
		echo json_encode(array('status'=>0,'info'=>"校验不成功"));
		exit();
	}
$client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);
//$game_id = 1202;
$game_id = $_POST['gid'];

//echo 454;
$re = $client->check_gamestatus($game_id);
//print_r($re);exit;
if($re == 1){
	echo json_encode(array('status'=>1,'info'=>"El juego es normal"));
}else{
	echo json_encode(array('status'=>0,'info'=>"El juego está en mantenimiento."));
}

?>