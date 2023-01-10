<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
include_once("../client/phprpc_client.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE);

error_reporting(E_ALL);

define('FOREGROUND_URL','http://j9adminaxy235.32sun.com/phprpc/foreground.php');

   	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
	echo json_encode(array('status'=>0,'info'=>"校验不成功"));
		exit();
	}
        
//echo 2563;
      
	 $client = new PHPRPC_Client(FOREGROUND_URL);
         
      
	//$account = $_POST['account'];
        $account = $_POST['username_email'];
       //  echo 55;
     //  print_r($_POST);
        // print_r($account);
	$result = $client->spagent_info($account);
	$result = unserialize($result);
       // print_r($result);exit;
	$record = $client->spagent_member($result['agent_id']);
	$record = unserialize($record);
     //   print_r($record);
        foreach($record as $key=>$val){
            $record[$key]['ip']=substr($record[$key]['ip'],0,3)."**";
             $client = new PHPRPC_Client('http://j9adminaxy235.32sun.com/phprpc/activity.php');
            $result = $client->agent_member_total($record[$key]['account'],"total");
            $record[$key]['total_deposit']=$result['deposit'];
             $record[$key]['total_withdraw']=$result['withdraw'];
			   $record[$key]['total_promotions']=$result['promotions'];
             $record[$key]['total_fanshui']=$result['fanshui'];
        }
        //echo 21;
        if(isset($record )){
            echo json_encode(array('status'=>1,'info'=>$record));
            
            
        }else{
            
             echo json_encode(array('status'=>0,'info'=>'您还没有代理下的会员'));
        }
    
