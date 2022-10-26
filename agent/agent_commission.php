<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
include_once("../client/phprpc_client.php");
define('FOREGROUND_URL','http://j9adminaxy235.32sun.com/phprpc/foreground.php');
define('READ_ACTIVITY_URL','http://j9adminaxy235.32sun.com/phprpc/activity.php');
define('ACTIVITY_URL','http://j9adminaxy235.32sun.com/phprpc/activity.php');
   	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
	echo json_encode(array('status'=>0,'info'=>"校验不成功"));
		exit();
	}

$client = new PHPRPC_Client(FOREGROUND_URL);
	if(isset($_POST['ty']) && $_POST['ty'] == 2)
	{
		if(isset($_POST['endtime'])  && $_POST['endtime'] != ''){
			$starttime = $_POST['starttime'];
			$endtime = $_POST['endtime'].' 23:59:59';
                }else{
                            
                           $starttime = date('Y-m-d H:i:s',time()-7776000);
			$endtime = date('Y-m-d',time()).' 23:59:59'; 
                        }
			$record = $client->spagent_report($_POST['username_email'],$starttime,$endtime);
			$record = unserialize($record);
                         foreach($record as $key=>$val){
            unset($record[$key]['rescue_amount']);
              unset($record[$key]['agent_id']);
                unset($record[$key]['batch']);
            
        }
                        if(isset($record)){
            echo json_encode(array('status'=>1,'info'=>$record));
            
            
        }else{
            
             echo json_encode(array('status'=>0,'info'=>'您还没有代理下的会员'));
        }
		
	}elseif(isset($_POST['ty']) && $_POST['ty'] == 3)
	{
		if(isset($_POST['endtime']) && $_POST['endtime'] != ''){
			$starttime = $_POST['starttime'];
			$endtime = $_POST['endtime'].' 23:59:59';
                        }else{
                            
                           $starttime = date('Y-m-d H:i:s',time()-7776000);
			$endtime = date('Y-m-d',time()).' 23:59:59'; 
                        }
			$record = $client->spagent_debit($_POST['username_email'],$starttime,$endtime);
			$record = unserialize($record);
                        
                                                         foreach($record as $key=>$val){
            unset($record[$key]['operator']);
              unset($record[$key]['add_time']);
               unset($record[$key]['comments']);
            
        }
                                          if(isset($record)){
            echo json_encode(array('status'=>1,'info'=>$record));
            
            
        }else{
            
             echo json_encode(array('status'=>0,'info'=>'您还没代理取款'));
        }
		
	}elseif(isset($_POST['ty'])  && $_POST['ty'] == 1){
		if(isset($_POST['endtime'])  && $_POST['endtime'] != ''){
			$starttime = $_POST['starttime'];
			$endtime = $_POST['endtime'].' 23:59:59';
                }else{
                            
                           $starttime = date('Y-m-d H:i:s',time()-7776000);
			$endtime = date('Y-m-d',time()).' 23:59:59'; 
                        }
			$record = $client->spagent_commission($_POST['username_email'],$starttime,$endtime);
			$record = unserialize($record);
                                         foreach($record as $key=>$val){
            unset($record[$key]['operator']);
              unset($record[$key]['add_time']);
               unset($record[$key]['comments']);
            
        }
                                          if(isset($record)){
            echo json_encode(array('status'=>1,'info'=>$record));
            
            
        }else{
            
             echo json_encode(array('status'=>0,'info'=>'您还没有代理佣金'));
        }
		
	}elseif(isset($_POST['type']) && $_POST['type'] == "agent_statistics")
	{
		echo agent_statistics($_POST['username_email'],$_POST['time_type']);
		//echo 111;
	}
        
         function agent_statistics($account,$time_type)
	{
            $client = new PHPRPC_Client(ACTIVITY_URL);
           	$record = $client->agent_statistics($account,$time_type); 
            
                echo json_encode(array('status'=>1,'info'=>$record));
            
        }
?>
