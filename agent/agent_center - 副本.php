<?php
header("Content-type: text/html; charset=utf-8");
include("client/phprpc_client.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
error_reporting(E_ALL);
//echo 546;exit;
 //define('FOREGROUND_URL','http://adminu2dnewonesite.32sun.com/phprpc/foreground.php');
define('FOREGROUND_URL','http://adminu2dnewonesite.32sun.com/phprpc/foreground.php');

   	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
	//echo json_encode(array('status'=>0,'info'=>"校验不成功"));
		//exit();
	}
        
//echo 2563;
        ad();
        function ad(){
	 $client = new PHPRPC_Client(FOREGROUND_URL);
         
        }
	$account = $_POST['account'];
       //  echo 55;
       print_r($_POST);
         print_r($account);
	$result = $client->spagent_info("test01");
	$result = unserialize($result);
        print_r($result);exit;
	$record = $client->spagent_member($result['agent_id']);
	$record = unserialize($record);
        
        //echo 21;
      print_r( $record);exit;
?>
