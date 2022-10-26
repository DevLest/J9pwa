<?php

header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
define("WEB_PATH", __DIR__);
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}
include_once ("ajax_data.2.0.php");



   	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
		echo json_encode(array('status'=>0,'info'=>"Fallo en la verificación"));
		exit();
	}
if(!isset($_SESSION['account'])||(isset($_SESSION['account'])&&$_SESSION['account']!=$_POST['username'])){
	
	$p = $_POST['password'];
	
	$co = new core();
	$re = $co->member_login($_POST['username'],$p);
	if(is_array($re))
	{	
		$_SESSION['account'] = $re['account'];
		$_SESSION['balance'] = $re['balance'];
		$_SESSION['member_name'] = $re['realName'];
		$_SESSION['member_type'] = $re['memberType'];
		
	}elseif($re == 1001){
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>'The game account or password is wrong!'
						));
		exit();
	}elseif($re == 1002){
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>'The account is locked, please contact online customer service!'
						));
		exit();
	}else{
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>'System error. Try again later!'
		));
		exit();
	}
}
/*foreach ($_POST as $key=>$val){
    $_POST[$key]=clean_xss($val);
}*/
$action = $_POST['act'];
$webClientClass=new ajax_data($action);
unset($_POST['act']);
$result=$webClientClass->$action($_POST);
//过滤特殊字符
function clean_xss($string){
    $string = trim($string);
    $string = strip_tags($string);
    $string = htmlspecialchars($string);
    $string = str_ireplace('<script>', '', $string);
    $string = str_ireplace('</script>', '', $string);
    $string = str_replace(array ('%','"', "\\", "'", "/", "..", "../", "./", "//" ,'%20','%27','%2527','*',';','<','>',"{",'}'), '', $string);
    $no = '/%0[0-8bcef]/';
    $string = preg_replace ($no,'',$string);
    $no = '/%1[0-9a-f]/';
    $string = preg_replace ($no,'',$string);
    $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
    $string = preg_replace ($no,'',$string);
    return $string;
}

//获取当前IP地址
function ip()
{
    $ip = getenv("HTTP_TRUE_CLIENT_IP");
    if(!isset($ip)|| $ip=='')
    {
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (@$_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (@$_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (@getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (@getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (@getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknown";
    }
    $temp = explode(",",$ip);
    $ip = $temp[0];
    $ipinfo['ip'] = $ip;
    return $ip;
}

