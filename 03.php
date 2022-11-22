<?php
header("Content-type: text/html; charset=utf-8");
	ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
/*include_once ("j9corefornew.class.php");
$core = new corefornew("mysqli");// 使用新的接口


    $result = $core->onlinepay_sure(1,1,1,"");
		print_r($result);exit;*/
		
		
		include_once ("j9corefornew.class.php");
 $core = new corefornew("mysqli");
    $result = $core->test();
		print_r($result);exit;