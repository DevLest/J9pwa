<?php
/**
 *用来处理前台页面提交的表单
 *通过phprpc存储到数据库中
 */
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");
define("WEB_PATH", __DIR__);
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}


//print_r($_SESSION['account']);exit;
    	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
		echo json_encode(array('status'=>0,'info'=>"Fallo en la verificación"));
		exit();
	}
if(isset($_POST['type']) && $_POST['type'] == "create_one_round")
	{
		echo create_one_round();
	}
	
	
	
	
	 function create_one_round(){
		 
		 
		 $core = new core();
		$result = $core->create_one_round();
		
			return json_encode(array('status'=>1,'info'=>$result));
		
         
        }
	
	
	

	
	
	
	
			




?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
