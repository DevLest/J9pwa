<?php
/**
 *用来处理前台页面提交的表单
 *通过phprpc存储到数据库中
 */

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
//error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: https://u2daszapp.u2d8899.com");
header("Access-Control-Allow-Credentials:true");
define("WEB_PATH", __DIR__);
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}

    	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
    /*print_r($_POST['auth']);
    echo "</br>?";
 print_r($auth_check);exit;*/
    // print_r($_POST['username']);
   // print_r($_POST['auth']);exit;
    if($auth_check != $auth)
	{
		// print_r($auth);
		// print_r($auth_check);exit;
	
		echo json_encode(array('status'=>0,'info'=>"Fallo en la verificación   123"));
		exit();
	}
if(!isset($_SESSION['account'])||(isset($_SESSION['account'])&&$_SESSION['account']!=$_POST['username_email'])){
	
	$p = $_POST['password'];
	
	$co = new core();
	$re = $co->member_login($_POST['username_email'],$p);
	if(is_array($re))
	{	
		$_SESSION['account'] = $re['account'];
		$_SESSION['balance'] = $re['balance'];
		$_SESSION['member_name'] = $re['realName'];
		$_SESSION['member_type'] = $re['memberType'];
		
	}elseif($re == 1001){
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>'La cuenta del juego o la contraseña son incorrectas!'
						));
		exit();
	}elseif($re == 1002){
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>'La cuenta está bloqueada, comuníquese con el servicio al cliente en línea!'
						));
		exit();
	}else{
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>'Error de sistema. Prueba de nuevo más tarde!'
		));
		exit();
	}
}


if(isset($_POST['type']) && $_POST['type'] == "set_agent_remark")
	{
		echo set_agent_remark($_POST['id'],$_POST['remark']);
	}elseif(isset($_POST['type']) && $_POST['type'] == "deposit_address")
	{
		echo deposit_address($_POST['username_email'],$_POST['net_work'],$_POST['currency']);
	}
        
        
        
        
        function set_agent_remark($id,$remark)
	{
		$core = new core();
	
		$result = $core->set_agent_remark($id,$remark);
               // return  $result ;
		return json_encode(array('status'=>1,'info'=>"success"));
	}    
        
           function deposit_address($username_email,$net_work,$currency)
	{
               
                    $data['currency'] = $net_work;
	$data['merchant'] = $username_email;
        $data['outmemid'] = $username_email;
        $data['notifyurl'] = 'https://www.dfd.com/';

$url='152.32.214.196:8917/account/getAccount';
	
		
			$ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
//print_r($data);
	$result = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($result);
       // print_r($result->data->address);
               
               
             return json_encode(array('status'=>1,'info'=>$result->data->address));  
           }