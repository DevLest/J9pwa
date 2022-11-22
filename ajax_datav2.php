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
if(isset($_POST['type']) && $_POST['type'] == "verification_code")
	{
		echo verification_code($_POST['phone'],$_POST['account']);
	}elseif(isset($_POST['type']) && $_POST['type'] == "retrieve_password")
	{
            
            echo  json_encode(array('status'=>1,'info'=>'Verificación exitosa'));
		//echo retrieve_password();
	}elseif(isset($_POST['type']) && $_POST['type'] == "get_latest_version")
	{
            
         echo get_latest_version();
		//echo retrieve_password();
	}elseif(isset($_POST['type']) && $_POST['type'] == "get_phone_account")
	{
		echo get_phone_account($_POST['phone']);
	}elseif(isset($_POST['type']) && $_POST['type'] == "get_address")
	{
		echo get_address();
	}elseif(isset($_POST['type']) && $_POST['type'] == "get_app_popup")
	{
		echo get_app_popup();
	}
	
	
	
	
	 function verification_code($phone,$account){
          $code=  mt_rand(1000, 9999);
                  $postdata=array(
    'action'=>'send',
	'userid'=>'64933',

    'account'=>'OM00088',
      'password'=> strtoupper(md5('v56dfe')),
      'mobile'=>$phone,
      'content'=>$code.'【Despegar】',
      'sendTime'=>'',
 // 'extno'=>'12',
    
    );

     // $_SESSION['verification_code']=$code;
     //  $account = $_SESSION['account'];
	
	    //加载缓存
	    include_once (WEB_PATH."/common/cache_file.class.php");
	    //获取缓存数据
	    $cachFile = new cache_file();
	   // $data_list = $cachFile->get($account,'','data','verification_code');
	   $code1 = array("code"=>$code);
           $cachFile->set($account,$code1,'','data','verification_code');
	      

     
$ch = curl_init();	
	curl_setopt($ch,CURLOPT_URL, "https://dx.ipyy.net/sms.aspx");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));  
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$response=curl_exec($ch);
	curl_close($ch);
  //print_r(trim($response));exit;
        return json_encode(array('status'=>1,'info'=>$code));
  // return json_encode(array('status'=>1,'info'=>'发送成功'));
            
        }
	
	
	
	 function get_latest_version(){
             
             
             
             $version=array("android"=>"2.09.16","ios"=>"2.09.04.02");
             return json_encode(array('status'=>1,'info'=>$version));
             
             
         }
	                 	function get_phone_account($phone)
	{
      
                $core = new core();
		$result = $core->get_phone_account($phone);
                                                if(!empty($result)){
          return json_encode(array('status'=>1,'info'=>$result));
                                                }else{
                                                    
                                  return json_encode(array('status'=>0,'info'=>"Error, no hay tal cuenta"));                     
                                                }
    
}

    	function get_app_popup()
	{
      
                $core = new core();
		$result = $core->get_app_popup($phone);
     
          return json_encode(array('status'=>1,'info'=>$result));
                                             
                                                }
 function get_address(){
		$core = new core();
		$ip = $core->get_ip();
                	$ip = $core->get_ip();
                     //  $ip = '148.233.239.0';
                                 $iplist=array("144.139.7.58","49.180.31.251","13.211.216.41","136.158.16.142","13.124.129.145","216.238.72.62","88.208.41.216","168.119.110.82/32");
                   $isip=  in_array($ip,$iplist);
		$arr = array();
		if($ip != 'Unknown'){
			$result = $core->website_iplist();
			
					require_once 'common/IP2Location.php';
					$ipdb = new \IP2Location\Database('./common/databases/IP2LOCATION-LITE-DB1.BIN', \IP2Location\Database::FILE_IO);
					$records = $ipdb->lookup($ip, \IP2Location\Database::ALL);
					//print_r($records); exit;
					if($records['countryCode'] == "PH"||$records['countryCode'] == "MX"||$records['countryCode'] == "NO"||$records['countryCode'] == "Invalid IP address."||$isip== 1)
					{
						$arr = array('status'=>1,'info'=>$ip);
					}else{
						$arr = array('status'=>1,'info'=>$ip);
					}
				
			
		}else{
			$arr = array('status'=>1,'info'=>$ip);
		}
		return json_encode($arr);
	 }
	
	
	
	
	
			




?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
