<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
define("WEB_PATH", __DIR__);

include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}
//echo 7745;exit; 能访问到这里
 //print_r($_SESSION['account']);exit;  //此处是有的
$core = new core();
$des = new DES3();

$lang = json_decode(file_get_contents("./language/".(isset($data->lang) ? $data->lang : "en").".json"));
$lang = $lang->center;

$lang_email = json_decode(file_get_contents("./language/email/".(isset($data->lang) ? $data->lang : "en").".json"));
$lang_email = $lang_email->center;

 	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	//echo $time.$api_key;
	//echo "</br>";
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
  // echo $auth_check;exit;
    if($_POST['submit_type'] != "monlinepay"&&$_POST['submit_type'] != "deposit"){
    if($auth_check != $auth)
	{
		//echo 1;
		echo json_encode(array('status'=>0,'info'=>$lang->auth_check));
		exit();
	}
        
    }
if(isset($_POST['submit_type']) && $_POST['submit_type'] == "login")
{
    echo loginMember($_POST['username_email'], $_POST['password']);
	exit();
}
elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "regist")
{
	$account = strtolower(trim($_POST['username_email']));
	$password = $_POST['password'];
	$verification_code = "";

	if (!isset($_POST['oauth'])) {

		if (!filter_var($account, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(['status' => 0, 'info' => $lang->invalid_email]);
			exit();
		}
		
		if ( !isset($_POST['verification_code']) ) {
			echo json_encode(['status' => 0, 'info' => $lang->empty_verification_code]);
			exit();
		}
		$verification_code = $_POST['verification_code'];
	}
	
	$data['referrer'] = $_SERVER['HTTP_HOST'];
	$data['regTime'] = date("Y-m-d H:i:s");
	$data['email'] = (filter_var($account, FILTER_VALIDATE_EMAIL)) ? $_POST['username_email'] : "";
	$data['uid'] = hexdec( substr(sha1(time()), 0, 6) );
	$data['nickName'] = "User".mt_rand(2648963, 9895639);

	if ( isset($_POST['promo_code']) && $_POST['promo_code'] !== ""){
		if(is_numeric($_POST['promo_code'])){
			$data['agentName'] = $_POST['promo_code'];    
		} else {
			$agent = $core->check_agent_percentage($_POST['promo_code']);

			if (is_array($agent) && !empty($agent))
			{
				$data['agentName'] = $agent['account'];
				$data['upper_level_agent_percentage'] = $agent['agent_percentage'];
			} else {
				echo json_encode(['status' => 0, 'info' => $lang->agent_not_found]);
				exit();
			}
		}
	}

	if ( $verification_code != "" ) {
		$verified = verify_email_code($account, $verification_code);

		if (!$verified['status']) {
			echo json_encode($verified);
			exit();
		}
	}

	$info = $core->member_regist($account, $password, $data);

	if(is_array($info)) {
		echo loginMember($_POST['username_email'], $_POST['password']);
	} elseif($info == 1006) {
		echo json_encode(array('status'=>0,'info'=>$lang->taken_member_account), JSON_UNESCAPED_UNICODE);
		exit();
	} elseif($info == 1007) {
		echo json_encode(array('status'=>-1,'info'=>$lang->registration_failed), JSON_UNESCAPED_UNICODE);
		exit();
	} elseif($info == 1008) {
		echo json_encode(array('status'=>-1,'info'=>$lang->taken_phone_number), JSON_UNESCAPED_UNICODE);
		exit();
	} elseif($info == 1009) {
		echo json_encode(array('status'=>-1,'info'=>$lang->taken_email), JSON_UNESCAPED_UNICODE);
		exit();
	} else {
		echo loginMember($_POST['username_email'], $_POST['password']);
	}
	
	if ( $verification_code != "" ) {
		$request = $core->set_memberEmailVerified($account);
	}
	
	sendWelcomeEmail();
	exit();
	
} elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "betslot_login")
{
	if($_POST['verify_code'] != $_SESSION['verify_code'])
	{
		echo "<script>alert('Authentication information error!');window.location.href='betslot_mobile.php'</script>";
		unset($_SESSION['verify_code']);
		exit();
	}
	$account = substr($_POST['username'],2);
	$re = $core->game_login(strtolower(trim($account)),$_POST['password'],"1208");
	if(strlen($re)>6)
	{
		$url = "http://www.vazagaming.com/lobby/bsmobile/lobby.html?token=".$re."&operatorID=30334&room=150601&logoSetup=VIVO_LOGO&language=EN&homeUrl=https://www.vivogaming.com&serverID=3649143";
		echo "<script>window.location.href='".$url."'</script>";
	}elseif($re == 1001){
		echo "<script>alert('".$lang->invalid_account."');window.location.href='betslot_mobile.php'</script>";
		exit();
	}elseif($re == 1002){
		echo "<script>alert('".$lang->account_locked."');window.location.href='betslot_mobile.php'</script>";
		exit();
	}else{
		echo "<script>alert('".$lang->system_error."');window.location.href='betslot_mobile.php'</script>";
		exit();
	}
}else
{
    
    if($_POST['submit_type'] != "monlinepay"&&$_POST['submit_type'] != "deposit"){
	  if(!isset($_SESSION['account'])||(isset($_SESSION['account'])&&$_SESSION['account']!=$_POST['username_email']))
	{

		$account = strtolower(trim($_POST['username_email']));
		$password = trim($_POST['password']);
	//print_r($account);exit;
		$re = $core->member_login($account,$password);
		if(is_array($re))
		{
                    //print_r($re);exit;
			$_SESSION['account'] = $re['account'];
			$_SESSION['balance'] = $re['balance'];
			$_SESSION['member_name'] = $re['realName'];
			$_SESSION['member_type'] = $re['memberType'];
			$_SESSION['password'] = $password;
		}elseif($re == 1001){
			echo json_encode(array(
					 'status'=>-2,
					 'info'=>$lang->invalid_account
							), JSON_UNESCAPED_UNICODE);
			exit();
		}elseif($re == 1002){
			echo json_encode(array(
					 'status'=>-2,
					 'info'=>$lang->account_locked
							));
			exit();
		}else{
			echo json_encode(array(
					 'status'=>-2,
					 'info'=>$lang->system_error
			));
			exit();
		}
	}
    }
	if(isset($_POST['submit_type']) && $_POST['submit_type'] == "change_password")
	{
		$password = $_POST['password'];
		$password_new = $_POST['password_new'];
                $password_ok= $_POST['password_newok'];
                if($password_new!=$password_ok){
                        echo json_encode(array('status'=>0,'info'=>$lang->inconsistent_password));
			exit();
                    
                }
		if (!preg_match("/[a-zA-Z0-9]{6,12}/", $password_new)){ 
			echo json_encode(array('status'=>0,'info'=>$lang->re_enter_password));
			exit();
		} 
		$re = $core->change_password($_POST['username_emaiil'],$password,$password_new);
		if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->success_reset_password));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->success_reset_password));
			exit();
		}
        } elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "check_moneypwd"){
            
            $re = $core->check_moneypwd($_SESSION['account'],$_POST['money_pwd']);
            
            if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->withdrawal_password_exists));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->withdrawal_password_not_exists));
			exit();
		}
        }elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "set_moneypwd"){
            if($_POST['money_pwd']!=$_POST['money_pwdok']){
                        echo json_encode(array('status'=>0,'info'=>$lang->inconsistent_password));
			exit();
                    
                }
            
            $re = $core->set_moneypwd($_SESSION['account'],$_POST['money_pwd']);
            
            if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->success_set_withdrawal_password));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->success_set_withdrawal_password));
			exit();
		}
        }elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "change_moneypwd"){
            if($_POST['money_newpwd']!=$_POST['money_newpwdok']){
                        echo json_encode(array('status'=>0,'info'=>$lang->inconsistent_password));
			exit();
                    
                }
            
            $re = $core->change_moneypwd($_SESSION['account'],$_POST['money_pwd'],$_POST['money_newpwd']);
            
            if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->success_change_withdrawal_password));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->invalid_old_withdrawal));
			exit();
		}
        }
        
	elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "transfer")
	{
	    $account = $_SESSION['account'];
	    $limit_check = 0;
	    //加载缓存
	    include_once (WEB_PATH."/common/cache_file.class.php");
	    //获取缓存数据
	    $cachFile = new cache_file();
	    $data_list = $cachFile->get($account,'','data','transfer_limit');
	    if($data_list == 'false')
	    {
	        $limit_time = array("limit_time"=>time());
	        $cachFile->set($account,$limit_time,'','data','transfer_limit');
	        $limit_check = 1;
	    }else{
	        if( (time() - $data_list['limit_time']) >10){
	            $limit_time = array("limit_time"=>time());
	            $cachFile->set($account,$limit_time,'','data','transfer_limit');
	            $limit_check = 1;
	        }
	    }
	    if($limit_check == 0)
	    {
			echo json_encode(array('status'=>0,'info'=>$lang->sending_failed_10_sec));
	        exit();
	    }
            
            $debittime = $cachFile->get($account,'','data','debit_limit');
            if($debittime){
               if( (time() - $debittime['limit_time'])<5 ){ 
                echo json_encode(array('status'=>0,'info'=>$lang->operation_failed));
			exit();
                
            }
            }
		$transfer_type = $_POST['transfer_type'];
	    if($transfer_type =="")
	    {
			echo json_encode(array('status'=>0,'info'=>$lang->error_select_transfer_type));
			exit();
	    }
		
		$amount = $_POST['amount'];
		if($_POST['amount'] < 1 || floor($amount) != $amount){
			echo json_encode(array('status'=>0,'info'=>$lang->transfer_amount_small));
			exit();
		}
		$re = $core->transfer($_SESSION['account'],$_POST['amount'],$transfer_type);
		if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->success_transfer));
			exit();
		}elseif($re == 1011){
			echo json_encode(array('status'=>0,'info'=>$lang->insufficient_main_account));
			exit();
		}elseif($re == 1012){
			echo json_encode(array('status'=>0,'info'=>$lang->insufficient_game_account));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->failed_transfer_contact_cs));
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "all_transfer_out")
	{
	    $account = $_SESSION['account'];
	    $limit_check = 0;
	    //加载缓存
	    include_once (WEB_PATH."/common/cache_file.class.php");
	    //获取缓存数据
	    $cachFile = new cache_file();
	    $data_list = $cachFile->get($account,'','data','transfer_limit');
	    if($data_list == 'false')
	    {
	        $limit_time = array("limit_time"=>time());
	        $cachFile->set($account,$limit_time,'','data','transfer_limit');
	        $limit_check = 1;
	    }else{
	        if( (time() - $data_list['limit_time']) >10){
	            $limit_time = array("limit_time"=>time());
	            $cachFile->set($account,$limit_time,'','data','transfer_limit');
	            $limit_check = 1;
	        }
	    }
	    if($limit_check == 0)
	    {
			echo json_encode(array('status'=>0,'info'=>$lang->sending_failed_10_sec));
	        exit();
	    }
		$transfer_type = $_POST['transfer_type'];
	  
		
		$re = $core->alltransferout($_SESSION['account']);
                
               // print_r($re);exit;
                
		if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->success_transfer_one_click));
			exit();
		}elseif($re == 1012){
			echo json_encode(array('status'=>0,'info'=>$lang->insufficient_game_account));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->failed_transfer_contact_cs));
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "bindcard")
	{
            
                login_auth();  // 这个方法是成功运行的
		if($_POST['bank_city']=='Por favor elige la ciudad'){
			echo json_encode(array('status'=>0,'info'=>$lang->empty_opened_province_city));
			exit();
		}
               // print_r($_POST['bank_city']);exit;
		$re = $core->bind_bank($_SESSION['account'],$_POST['bank_type'],$_POST['realname'],$_POST['bank_no'],$_POST['bank_addr'],$_POST['bank_province'],$_POST['bank_city']);
		if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->success_bank_information_link));
			exit();
		}elseif($re == 1005){
			echo json_encode(array('status'=>0,'info'=>$lang->many_bound_bank_information));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->failed_information_link_contact_cs));
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "debit")
	{
            
           // print_r($_POST['amount']);exit;
        
           // login_auth();
		$account = $_SESSION['account'];
		/*if($_POST['amount'] < 100){
			echo json_encode(array('status'=>0,'info'=>'The withdrawal amount cannot be less than 100'));
			exit();
		}*/
		$limit_check = 0;
		//加载缓存
		include_once ("common/cache_file.class.php");
		//获取缓存数据
		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','debit_limit');
		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','debit_limit');
			$limit_check = 1;
		}else{
			if( (time() - $data_list['limit_time']) >180){
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','debit_limit');
				$limit_check = 1;
			}
		}
		if($limit_check == 0)
		{
			echo json_encode(array('status'=>0,'info'=>$lang->sending_failed_3_min));
			exit();
		}
		$res = $core->record_status($account,"debit",0);
		if($res > 0){
			echo json_encode(array('status'=>0,'info'=>$lang->existing_unreviewed_withdrawal));
			exit();
		}
		$rest = $core->record_status($account,"debit",4);
		if($rest > 0){
			echo json_encode(array('status'=>0,'info'=>$lang->withdawal_anomaly));
			exit();
		}
                
                
		$transfertime = $cachFile->get($account,'','data','transfer_limit');
		if($transfertime){
			if( (time() - $transfertime['limit_time'])<5 ){ 
				echo json_encode(array('status'=>0,'info'=>$lang->operation_failed));
				exit();
				
			}
		}

		//check min max withdrawal amount
		$min = 0;
		$max = 0;
		$currency = $_POST['bank_type'];
		$network = $_POST['net_work'];
		
		include_once WEB_PATH . "/common/cache_file.class.php";
		$data_list = $cachFile->get("s6_api", '', 'data', 'currency');
		$fees = json_decode($data_list);
		
		if (is_array($fees->data)) {
			foreach ($fees->data as $wallet) {
				$wallet_name = strtoupper($wallet->item_name);
				if ($wallet_name == $currency ) {
					foreach ($wallet->chain_list as $chain) {
						if ($chain->chain_tag == $network ) {
							$min = (float) $chain->minout;
							$max = (float) $chain->maxout;
						}
						else continue;
					}
				}
				else continue;
			}

			if ($_POST['amount'] < $min ) {
				echo json_encode(array('status'=>0,'info'=>$lang->failed_withdrawal_request_min_withdrawal));
				exit();
			}
			
			if ($_POST['amount'] > $max ) {
				echo json_encode(array('status'=>0,'info'=>$lang->failed_withdrawal_request_max_withdrawal));
				exit();
			}
		}
//print_r($_POST['amount']);exit;
		$re = $core->debit($_SESSION['account'],$_POST['amount'],$_POST['card_number'],$currency,$_POST['net_work']);
		//print_r($re);exit;
                if($re == 1)
		{
			echo json_encode(array('status'=>1,'info'=>$lang->success_withdrawal_request));
			exit();
		}elseif($re == 1011){
			echo json_encode(array('status'=>0,'info'=>$lang->insufficient_main_account_fill));
			exit();
		}elseif($re == 1012){
			echo json_encode(array('status'=>0,'info'=>$lang->wrong_background_password));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->failed_withdrawal_request_contact));
			exit();
		}
        }elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "game_mobile_login"){
            
                    switch ($_POST['gameid'])
{   case '1207':
    		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "https://mobile.u2dgamelobby.kkk123456.com/index.php?account={$_SESSION['account']}&auth=$auth_check&showType=CQ";        
  break;
    case '1203':
       		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "https://mobile.u2dgamelobby.kkk123456.com/index.php?account={$_SESSION['account']}&auth=$auth_check&showType=MGS";        
  break;  
   case '1202':
       		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "https://mobile.u2dgamelobby.kkk123456.com/index.php?account={$_SESSION['account']}&auth=$auth_check&showType=PT66";        
  break;  
   case '1209':
       		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "http://u2dcny.zxc.today/h5/cb6434&username={$_SESSION['account']}&accessToken=$auth_check";        
  break;  
      default:     
          $arr = array();
	if (isset($_POST['ty']) && $_POST['ty'] == "fish") {
                                          
                   $arr['game_type'] = 'fish';   
 
					}
                                        
             
                           if (isset($_POST['language']) && $_POST['language'] == "english") {
                                          
                   $arr['language'] = 'english';   
 
					}
            $res = $core->game_mobile_login($_SESSION['account'],$_POST['gameid'],$arr);
}
            if($res!='-1'){
                
                echo json_encode(array('status'=>1,'info'=>$res));
			exit();
                
            }else{
                
               echo json_encode(array('status'=>0,'info'=>$lang->game_maintenance));
			exit(); 
                
            }
            
        }elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "game_login"){
            
                         switch ($_POST['gameid'])
{   case '1207':
    		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "https://mobile.u2dgamelobby.kkk123456.com/index.php?account={$_SESSION['account']}&auth=$auth_check&showType=CQ";        
  break;
    case '1203':
       		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "https://mobile.u2dgamelobby.kkk123456.com/index.php?account={$_SESSION['account']}&auth=$auth_check&showType=MGS";        
  break;  
   case '1202':
       		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "https://mobile.u2dgamelobby.kkk123456.com/index.php?account={$_SESSION['account']}&auth=$auth_check&showType=PT66";        
  break;  
   case '1209':
       		$api_key='dfafdgf';
    $time = substr(time(),0,-3);
    $auth_check = md5($time.$api_key);
   $res=    "http://u2dcny.zxc.today/pc/cb6434&username={$_SESSION['account']}&accessToken=$auth_check";        
  break;  
      default:     
          $arr = array();
	if (isset($_POST['ty']) && $_POST['ty'] == "fish") {
                                          
                   $arr['game_type'] = 'fish';   
 }   if (isset($_POST['language']) && $_POST['ty'] == "english") {
                                          
                   $arr['language'] = 'english';   
 
					}
		 $res = $core->gameapi_login($_SESSION['account'],$_POST['gameid'],$arr);			
          //  $res = $core->game_mobile_login($_SESSION['account'],$_POST['gameid'],$arr);
}
           

            if($res!='-1'){
                
                echo json_encode(array('status'=>1,'info'=>$res));
			exit();
                
            }else{
                
               echo json_encode(array('status'=>0,'info'=>$lang->game_maintenance));
			exit(); 
                
            }
            
        }elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "zfbdeposit")
	{
		$account = $_SESSION['account'];
		$amount = $_POST['amount'];
		$limit_check = 0;
		//加载缓存
		include_once (WEB_PATH."/common/cache_file.class.php");
		//获取缓存数据
		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','deposit_limit');
		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','deposit_limit');
			$limit_check = 1;
		}else{
			if( (time() - $data_list['limit_time']) >180){
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','deposit_limit');
				$limit_check = 1;
			}
		}
		if($limit_check == 0)
		{
			echo $lang->sending_failed_3_min;
			exit();
		}
		if($amount < 10)
		{
			echo $lang->incorrect_deposit_account;
			exit();
		}
		$re = $core->zfbdeposit($_SESSION['account'],$_POST['amount'],$_POST['zfb_billno'],$_POST['autopromo'],$_POST['bankid']);
		if($re == 1)
		{
			echo $lang->success_deposit_review;
			exit();
		}elseif($re == -1){
			echo $lang->duplicate_deposit_request;
			exit();
		}else{
			echo $lang->failed_submit_deposit_update;
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "deposit")
	{
		$account = $_SESSION['account'];
		$amount = $_POST['amount'];
                 if(isset($_POST['zf_type'])){
                    
                    $zf_type=2;
                }else{
                    
                    $zf_type=1;
                }
		$limit_check = 0;
		//加载缓存
		include_once (WEB_PATH."/common/cache_file.class.php");
		//获取缓存数据
		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','deposit_limit');
		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','deposit_limit');
			$limit_check = 1;
		}else{
			if( (time() - $data_list['limit_time']) >30){
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','deposit_limit');
				$limit_check = 1;
			}
		}
		if($limit_check == 0)
		{
			echo json_encode(array('status'=>0,'info'=>$lang->sending_failed_30_sec));
			exit();
		}
		if($amount < 10)
		{
			echo json_encode(array('status'=>0,'info'=>$lang->incorrect_deposit_account));
			exit();
		}
		    /*if($_POST['autopromo']==3 ){
                if($amount < 50)
		{
			echo json_encode(array('status'=>0,'info'=>'该活动最低存款50元,详见活动细则'));
			exit();
		}
                }*/
		$rs = $core->record_status($account,"deposit",2);
		if($rs > 0){
			echo json_encode(array('status'=>0,'info'=>$lang->existing_unreviewed_withdrawal_do_not_resubmit));
			exit();
		}
		$bank_info_arr = $core->deposit_bank(0,$_SESSION['member_type']);

		$bank_info = $bank_info_arr[0];
                	//print_r($_SESSION['account']);exit;
                if($_SESSION['account']==''){
                    
                      echo $lang->expired_verification_reenter;
			exit(); 
                }
		$re = $core->backdeposit($_SESSION['account'],$_POST['amount'],$bank_info['id'],$_POST['username'],$_POST['autopromo'],$zf_type);
		//print_r($re);exit;
		if($re[0] == 1)
		{
			//echo "The deposit was sent successfully and is under review";
			
			$str = '<p>Hello, first select a bank card</p>';
			if(count($bank_info_arr) < 2){
				$arrs = array(
					"id"=>$bank_info['id'],
					"bank_name"=>$bank_info['bank_name'],
					"account_name"=>$bank_info['account_name'],
					"bank_no"=>$bank_info['bank_no'],
					"amount"=>$_POST['amount'],
					"code"=>$re[1]//附言
				);
				echo json_encode(array('status'=>1,'type'=>0,'info'=>$arrs));
				exit();
			}else{
				foreach($bank_info_arr as $b){
					$str .='<div id="bank'.$b["id"].'" onclick="showBankInfo('.$b["id"].')">'.$b["bank_name"].'</div>';
				}
				echo json_encode(array('status'=>1,'type'=>1,'info'=>$str,'amount'=>$_POST['amount'],'code'=>$re[1]));
				exit();
			}
		}elseif($re == -1){
			echo json_encode(array('status'=>0,'info'=>$lang->duplicate_deposit_request));
			exit();
		}else{
			error_log(date('YmdHis')."##".$_SESSION['account']."##".$_POST['amount']."##".$bank_info['id']."##".$_POST['username']."##".$_POST['autopromo']."\r\n", 3, 'common/log/depositerror.log');
			echo json_encode(array('status'=>0,'info'=>$lang->failed_submit_deposit_update));
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "yunsfdeposit")
	{
		$account = $_SESSION['account'];
		$amount = $_POST['amount'];
		$limit_check = 0;
		//加载缓存
		include_once (WEB_PATH."/common/cache_file.class.php");
		//获取缓存数据
		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','deposit_limit');
		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','deposit_limit');
			$limit_check = 1;
		}else{
			if( (time() - $data_list['limit_time']) >30){
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','deposit_limit');
				$limit_check = 1;
			}
		}
		if($limit_check == 0)
		{
			echo json_encode(array('status'=>0,'info'=>$lang->sending_failed_30_sec));
			exit();
		}
		if($amount < 10)
		{
			echo json_encode(array('status'=>0,'info'=>$lang->incorrect_deposit_account));
			exit();
		}
		    /*if($_POST['autopromo']==3 ){
                if($amount < 50)
		{
			echo json_encode(array('status'=>0,'info'=>'该活动最低存款50元,详见活动细则'));
			exit();
		}
                }*/
		$rs = $core->record_status($account,"deposit",2);
		if($rs > 0){
			echo json_encode(array('status'=>0,'info'=>$lang->existing_unreviewed_withdrawal_do_not_resubmit));
			exit();
		}
		$bank_info_arr = $core->deposit_bank(0,$_SESSION['member_type']);

		$bank_info = $bank_info_arr[0];
		$re = $core->yunsfdeposit($_SESSION['account'],$_POST['amount'],$bank_info['id'],$_POST['username'],$_POST['autopromo']);
		
		if($re[0] == 1)
		{
			//echo "The deposit was sent successfully and is under review";
			
			$str = '<p>Hello, first select a bank card</p>';
			if(count($bank_info_arr) < 2){
				$arrs = array(
					"id"=>$bank_info['id'],
					"bank_name"=>$bank_info['bank_name'],
					"account_name"=>$bank_info['account_name'],
					"bank_no"=>$bank_info['bank_no'],
					"amount"=>$_POST['amount'],
					"code"=>$re[1],//附言
                                     "yunsfurl"=>$bank_info['yunsfbank']
				);
				echo json_encode(array('status'=>1,'type'=>0,'info'=>$arrs));
				exit();
			}else{
				foreach($bank_info_arr as $b){
					$str .='<div id="bank'.$b["id"].'" onclick="showBankInfo('.$b["id"].')">'.$b["bank_name"].'</div>';
				}
				echo json_encode(array('status'=>1,'type'=>1,'info'=>$str,'amount'=>$_POST['amount'],'code'=>$re[1]));
				exit();
			}
		}elseif($re == -1){
			echo json_encode(array('status'=>0,'info'=>$lang->duplicate_deposit_request));
			exit();
		}else{
			error_log(date('YmdHis')."##".$_SESSION['account']."##".$_POST['amount']."##".$bank_info['id']."##".$_POST['username']."##".$_POST['autopromo']."\r\n", 3, 'common/log/depositerror.log');
			echo json_encode(array('status'=>0,'info'=>$lang->failed_submit_deposit_update));
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "point")
	{
		$account = $_SESSION['account'];
		$limit_check = 0;
		//加载缓存
		include_once (WEB_PATH."/common/cache_file.class.php");
		//获取缓存数据
		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','deposit_limit');
		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','deposit_limit');
			$limit_check = 1;
		}else{
			if( (time() - $data_list['limit_time']) >180){
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','deposit_limit');
				$limit_check = 1;
			}
		}
		if($limit_check == 0)
		{
			echo json_encode(array('status'=>0,'info'=>$lang->sending_failed_3_min));
			exit();
		}
		$re = $core->apply_point($_SESSION['account'],intval($_POST['credits']));
		if($re == 1)
		{
			$point_info = $core->get_point($_SESSION['account']);
			$point_str = array(
				'all' => $point_info['credits'],
				'use' => $point_info['credits_use'],
				'msg' => $lang->success_point_redeem
			);
			echo json_encode(array('status'=>1,'info'=>$point_str));
			exit();
		}elseif($re == 1031){
			echo json_encode(array('status'=>0,'info'=>$lang->point_not_reach_monthly_redemption_limit));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>$lang->insufficient_points));
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "monlinepay")
	{
		$account = $_SESSION['account'];
              ///  print_r($_SESSION);exit;
                
              
		$method = "get";
		//$billno="s".date("YmdHis").rand(1000,9999);
		$rand_monlinepay = $core->random(6);
                 if($_SESSION['deposit_type']=='m'){
      
      $billno="n".date("YmdHis").$rand_monlinepay;
  }else{
		$billno="p".date("YmdHis").$rand_monlinepay;
  }
		$amount=$_POST['amount'];
		if($amount < 10)
		{
			echo $lang->incorrect_deposit_account;
			exit();
		}
		/*     if($_POST['autopromo']==3 ){
                if($amount < 50)
		{
			echo "<script>alert('该活动最低存款50，详见活动细则');window.close();</script>";
			exit();
		}
                }*/
		$bankco=$_POST['bank_type'];
		$data = array();
		$temp=explode('_',$_POST['pay_id']);
		$pay_id = $temp[1];
		$line_type = $temp[3];
		$monlinepay_info = $core->monlinpay_detail($pay_id);
		
		//判断支付银行选项是否正确
		$zf_bank_id = array("ABC","ICBC","CCB","BCOM","BOC","CMB","CMBC","CEBB","SHB","NBB","HXB","CIB","PSBC","SPABANK","SPDB","HZB","ECITIC");
		
		if($pay_id == 600){
			$zf_flag = 0;
			foreach($zf_bank_id as $v_zf){
				if($bankco == $v_zf){
					$zf_flag = 1;
				}
			}
			if($zf_flag == 0){
				echo $lang->incorrect_deposit_bank_reenter;
				exit();
			}
		}
		if($monlinepay_info['pay_status'] !=1 && $account != "feng12345")
		{
		    echo $lang->deposit_channel_maintenance;
			exit();
		}
            //   print_r($_SESSION['account']);exit;
                if($_SESSION['account']==''){
                    
                      echo $lang->expired_verification_reenter;
			exit(); 
                }
        if($monlinepay_info['pay_type']==17){
                     
                 $amount= $amount*6.42;
$amount= number_format($amount,'2',".","");
                 }
		$re = $core->onlinepay($_SESSION['account'],$amount,$billno,$monlinepay_info['pay_name'],$_POST['autopromo']);
		if($re == 1)
		{
			
                   // 三方通用提交       
                                       
                       $data = array();
	                   $data['billno'] = $billno;//订单号
                    
                       $data['amount'] = $amount;//钱
                       $data['bank_code'] = $bankco;
		               $data['return_url'] = $monlinepay_info['return_url'];
		               $method = "POST";
                                      
                           
                           
                       
                        
			echo $lang->jumping_for_you;
			echo $str = $core->build_form($data, $monlinepay_info['submit_url'],$method);
		}elseif($re == -1){
			echo "<script>alert('$lang->duplicate_deposit_request');window.close();</script>";
			exit();
		}else{
			echo "<script>alert('$lang->failed_submit_deposit_update');window.close();</script>";
			exit();
		}
		
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "mobiledeposit")
	{
		//手机摩宝支付
		//$billno="s".date("YmdHis").rand(1000,9999);
		$rand_monlinepay = $core->random(6);
		$billno="s".date("YmdHis").$rand_monlinepay;
		$amount=$_POST['amount'];
		if($amount < 10)
		{
			echo $lang->incorrect_deposit_account;
			exit();
		}
		$bankco=$_POST['bank_type'];
		$pay_id = 15;
		$monlinepay_info = $core->monlinpay_detail($pay_id);
		if($monlinepay_info['pay_status'] != 1)
		{
			echo "<script>alert('$lang->mobile_payment_maintenance');window.location.href='depositinfo.php?type=mobiledeposit';</script>";
			exit();
		}
		$re = $core->onlinepay($_SESSION['account'],$amount,$billno,"Pago movil",$_POST['autopromo']);
		if($re == 1)
		{
			//MB支付 
			require_once(WEB_PATH."/MBzhifu/lib/MobaoPay.class.php");				
			$mobaopay_apiname_pay = "WAP_PAY_B2C";
			$mobaopay_api_version = "1.0.0.0";
			// Mo宝支付系统密钥
			$mbp_key = $monlinepay_info['merchant_key'];
			// Mo宝支付系统网关地址（正式环境）
			$mobaopay_gateway = $monlinepay_info['submit_url'];
			// 商户在Mo宝支付的平台号
			$platform_id = $monlinepay_info['merchant_id'];
			// Mo宝支付分配给商户的账号
			$merchant_acc = $monlinepay_info['merchant_id'];
			// 商户通知地址（请根据自己的部署情况设置下面的路径）
			$merchant_notify_url = $monlinepay_info['return_url'];
			// 请求数据赋值
			$data = "";
			// 商户APINMAE，WEB渠道一般支付
			$data['apiName'] = $mobaopay_apiname_pay;
			// 商户API版本
			$data['apiVersion'] = $mobaopay_api_version;
			// 商户在Mo宝支付的平台号
			$data['platformID'] = $platform_id;
			// Mo宝支付分配给商户的账号
			$data['merchNo'] = $merchant_acc;
			// 商户通知地址
			$data['merchUrl'] = $merchant_notify_url;
			// 银行代码，不传输此参数则跳转Mo宝收银台
			$data['bankCode'] = $bankco;
			//商户订单号
			$data['orderNo'] = $billno;
			// 商户订单日期
			$data['tradeDate'] = date("Ymd");
			// 商户交易金额
			$data['amt'] = number_format($amount, 2, '.', '');
			//对点卡进行手续费扣除
			$dianka_money = $core->switch_mbdianka($bankco,$amount);
			// 商户参数
			$data['merchParam'] = "$pay_id";
			// 商户交易摘要
			$data['tradeSummary'] = "Información del pago";
			// 对含有中文的参数进行UTF-8编码
			// 将中文转换为UTF-8
			if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['merchUrl']))
			{
				$data['merchUrl'] = iconv("GBK","UTF-8", $data['merchUrl']);
			}
			if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['merchParam']))
			{
				$data['merchParam'] = iconv("GBK","UTF-8", $data['merchParam']);
			}
			if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['tradeSummary']))
			{
				$data['tradeSummary'] = iconv("GBK","UTF-8", $data['tradeSummary']);
			}
			// 初始化
			$cMbPay = new MbPay($mbp_key, $mobaopay_gateway);
			// 准备待签名数据
			$str_to_sign = $cMbPay->prepareSign($data);
			// 数据签名
			$sign = $cMbPay->sign($str_to_sign);
			$data['signMsg'] = $sign;
			echo $str = $core->build_form($data, $monlinepay_info['submit_url'],"get");
			// echo "<script>top.location.href='".$url."'</script>";
			//echo $url;
		}elseif($re == -1){
			echo "<script>alert('$lang->duplicate_deposit_request');window.close();</script>";
			exit();
		}else{
			echo "<script>alert('$lang->failed_submit_deposit_update');window.close();</script>";
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "weixindeposit")
	{
		//微信智付支付
		//$billno="s".date("YmdHis").rand(100000,999999);
		$rand_monlinepay = $core->random(6);
		$billno="s".date("YmdHis").$rand_monlinepay;
		$amount=$_POST['amount'];
		if($amount < 10)
		{
			echo "$lang->incorrect_deposit_account";
			exit();
		}
		$pay_id = 16;
		$monlinepay_info = $core->monlinpay_detail($pay_id);
		if($monlinepay_info['pay_status'] != 1)
		{
			echo "<script>alert('$lang->wechat_payment_maintenance');window.close();</script>";
			exit();
		}
		$re = $core->onlinepay($_SESSION['account'],$amount,$billno,"Pago de WeChat",$_POST['autopromo']);
		if($re == 1)
		{
			$data['merchant_code'] = $monlinepay_info['merchant_id'];
			$data['amount'] = $amount;
			$data['billno'] = $billno;
			echo $str = $core->build_form($data, $monlinepay_info['submit_url'],"POST");
		}elseif($re == -1){
			echo "<script>alert('$lang->duplicate_deposit_request');window.close();</script>";
			exit();
		}else{
			echo "<script>alert('$lang->failed_submit_deposit_update');window.close();</script>";
			exit();
		}
	}elseif(isset($_POST['submit_type']) && $_POST['submit_type'] == "cftdeposit")
	{
		$account = $_SESSION['account'];
		$limit_check = 0;
		$amount = $_POST['amount'];
		//加载缓存
		include_once (WEB_PATH."/common/cache_file.class.php");
		//获取缓存数据
		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','deposit_limit');
		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','deposit_limit');
			$limit_check = 1;
		}else{
			if( (time() - $data_list['limit_time']) >180){
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','deposit_limit');
				$limit_check = 1;
			}
		}
		if($limit_check == 0)
		{
			//echo "<script>alert('Failed to send, please re-send after 3 minutes');window.location.href='deposit/tenpay.php'</script>";
			echo $lang->sending_failed_3_min;
			exit();
		}
		if($amount < 10)
		{
			//echo "<script>alert('The deposit amount is incorrect, please enter the correct deposit amount');window.location.href='deposit/tenpay.php'</script>";
			echo $lang->incorrect_deposit_account;
			exit();
		}
		$re = $core->cftdeposit($_SESSION['account'],$_POST['amount'],$_POST['cft_billno'],$_POST['autopromo'],$_POST['bankid']);
		if($re == 1)
		{
			//echo "<script>alert('The deposit was sent successfully and is under review');window.location.href='deposit/tenpay.php'</script>";
			echo $lang->success_deposit_review;
			exit();
		}elseif($re == -1){
			//echo "<script>alert('You have already requested this promotion, please review the promotion rules');window.location.href='deposit/tenpay.php'</script>";
			echo $lang->duplicate_deposit_request;
			exit();
		}else{
			//echo "<script>alert('Failed to submit deposit, please update and resubmit');window.location.href='deposit/tenpay.php'</script>";
			echo $lang->failed_submit_deposit_update;
			exit();
		}
	}
}
function login_auth(){
    
    global $lang;
    
    if(!isset($_SESSION['account'])||(isset($_SESSION['account'])&&$_SESSION['account']!=$_POST['username_email'])){
	
	$p = $_POST['password'];
	
	$co = new core();
	$re = $co->member_login($_POST['username_email'],$p);
       // echo $_POST['username_email'];exit;
	if(is_array($re))
	{	
		$_SESSION['account'] = $re['account'];
		$_SESSION['balance'] = $re['balance'];
		$_SESSION['member_name'] = $re['realName'];
		$_SESSION['member_type'] = $re['memberType'];
		
	}elseif($re == 1001){
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>$lang->login_auth->invalid_account
						));
		exit();
	}elseif($re == 1002){
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>$lang->login_auth->account_locked
						));
		exit();
	}else{
		echo json_encode(array(
				 'status'=>-2,
				 'info'=>$lang->login_auth->system_error
		));
		exit();
	}
}
}
function getClientIP()  
{  
    global $ip;  
    if (getenv("HTTP_CLIENT_IP"))  
        $ip = getenv("HTTP_CLIENT_IP");  
    else if(getenv("HTTP_X_FORWARDED_FOR"))  
        $ip = getenv("HTTP_X_FORWARDED_FOR");  
    else if(getenv("REMOTE_ADDR"))  
        $ip = getenv("REMOTE_ADDR");  
    else $ip = "Unknow";  
    return $ip;  
} 

function loginMember($username, $password)
{
    include_once WEB_PATH . "/common/cache_file.class.php";
	global $lang;

	$account = strtolower(trim($username));
	$password = trim($password);
    $cachFile = new cache_file();
	$core = new core();

	//check reset password request
	$reset = $core->check_password_reset($account,$password);
	if(is_array($reset))
	{
		$add_time = date("Y-m-d H:i:s",(time()-1800));

		if ( $reset['add_time'] < $add_time )
		{
			return json_encode(['status'=>0,'info'=>$lang->loginMember->expire_temporary_password], JSON_UNESCAPED_UNICODE);
		}

		return json_encode([ 'status' => 2, 'info' => "?reset_password=".$reset['md5content']]);
	}

	$re = $core->member_login($account,$password);
	
	if(is_array($re))
	{
		$_SESSION['account'] = $re['account'];
		$_SESSION['balance'] = $re['balance'];
		$_SESSION['member_name'] = $re['realName'];
		$_SESSION['member_type'] = $re['memberType'];
		$_SESSION['password'] = $password;
		$_SESSION['levelID'] = $re['levelID'];
		$_SESSION['email'] = $re['email'];
		setcookie("account", $_SESSION['account'], time()+86400);
		setcookie("member_name", urlencode($_SESSION['member_name']), time()+86400);

        $bet_records = $core->uniterecord_sum(cleanString($re['account']), date('Y-m-d 00:00:00', strtotime($re['regTime'])), date('Y-m-d 23:59:59', time()));
        $total_deposit = $core->record_list_summary($re['account'], "deposit", date('Y-m-d 00:00:00', strtotime($re['regTime'])), date('Y-m-d 23:59:59', time()));
        $friends = $core->agent_rank_list($re['account']);

		$imageResult = $core->get_imgurl($account);

        $played_games = $cachFile->get($account, '', 'data', 'played_games');
        
        if (is_array($played_games)) {
            rsort($played_games);
            $played_games = array_slice(array_keys($played_games), 0, 3);
			$top_games = [];

			$filedata = json_decode(removeBomUtf8(file_get_contents(WEB_PATH . "/data/games.json")), JSON_UNESCAPED_UNICODE);

			foreach ($played_games as $played) {

				foreach ($filedata as $detail) {
					if (!$detail['state']) continue;
						
					if (isset($detail['id']) == $played) {
						array_push($top_games, [
							"name" => $detail['name'],
							"imgURL" => $detail['pic'],
							"gameInfo" => [
								"gameCode" => $detail['id'],
								"gameCodeAlias" => isset($detail['alias_code']) ? $detail['alias_code'] : "",
							],
						]);
					} else continue;
				}
			}
            $played_games = $top_games;
        } else {
            $played_games = [];
        }

		return json_encode([
			'status'=>1,
			'info'=> [
				'username' => $re['account'],
				'balance' => $re['balance'],
				'realName' => $re['realName'],
				'password' => $password,
				'email' => $re['email'],
                'email_verified' => $re['email_verified'],
				'sex' => ($re['sex']) ? "F" : "M",
				'birthday' => $re['birthday'],
				'phone' => $re['telephone'],
				'pic' => $imageResult,
				'first_name' => $re['firstName'],
				'middle_name' => $re['middleName'],
				'last_name' => $re['lastName'],
				'city' => $re['city'],
				'state' => $re['state'],
				'landline' => $re['landline'],
				'postal' => $re['postal'],
				'regTime' => $re['regTime'],
				'is_agent' => $re['is_agent'],
				'nickName' => $re['nickName'],
				'userID' => $re['uid'],
                'agent_percentage' => ($re['agent_percentage'] != "") ? $re['agent_percentage'] * 100 : null,
                'total_win' => floatval($bet_records[0]['win']),
                'total_bets' => floatval($bet_records[0]['bet']),
                'total_deposit' => floatval($total_deposit[0]['amount']),
                'total_referrals' => floatval($friends['total_referrals']),
                'most_played' => $played_games,
				]
		]);
	}
	elseif($re == 1001)
	{
		return json_encode([
			'status'=>0,
			'info'=>$lang->loginMember->invalid_account
		], JSON_UNESCAPED_UNICODE);
	}
	elseif($re == 1002)
	{
		return json_encode([
			'status'=>0,
			'info'=>$lang->loginMember->account_locked
		], JSON_UNESCAPED_UNICODE);
	}
	else
	{
		return json_encode([
			'status'=>0,
			'info'=>$lang->loginMember->system_error
		], JSON_UNESCAPED_UNICODE);
	}
}

function sendWelcomeEmail()
{
	global $lang_email;
	include_once(WEB_PATH."/email/PHPMailer.class.php");
	include_once(WEB_PATH."/email/smtp.class.php");

	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();
		// $mail->SMTPDebug  = 2;
		$mail->Host       = 'smtpout.secureserver.net';
		$mail->SMTPAuth   = true;
        $mail->Username = 'support@999.game';
        $mail->Password = 'download15895';
		$mail->SMTPSecure = "ssl";
		$mail->Port       = 465;
		$mail->CharSet = 'UTF-8';
		$mail->AddAttachment( "data/Bonus terms and conditions.pdf" , 'Bonus terms and conditions.pdf' );
		$mail->AddAttachment( "data/Terms and Conditions.pdf" , 'Terms and Conditions.pdf' );

		$mail->setFrom('support@999.game', 'AGame');
		$mail->addReplyTo('support@999.game', 'AGame');
		$mail->addAddress($_SESSION['email'], $_SESSION['member_name']);
		$mail->isHTML(true);

		$mail->Subject = $lang_email->sendWelcomeEmail->subject;
		$mail->Body    =
			"<!doctype html>
			<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml'
				xmlns:o='urn:schemas-microsoft-com:office:office'>
				
				<head>
					<title></title>
					<meta http-equiv='X-UA-Compatible' content='IE=edge'>
					<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
					<meta name='viewport' content='width=device-width,initial-scale=1'>
					<style type='text/css'>
					#outlook a {
						padding: 0;
					}
				
					.ReadMsgBody {
						width: 100%;
					}
				
					.ExternalClass {
						width: 100%;
					}
				
					.ExternalClass * {
						line-height: 100%;
					}
				
					body {
						margin: 0;
						padding: 0;
						-webkit-text-size-adjust: 100%;
						-ms-text-size-adjust: 100%;
					}
				
					table,
					td {
						border-collapse: collapse;
						mso-table-lspace: 0pt;
						mso-table-rspace: 0pt;
					}
				
					img {
						border: 0;
						height: auto;
						line-height: 100%;
						outline: none;
						text-decoration: none;
						-ms-interpolation-mode: bicubic;
					}
				
					p {
						display: block;
						margin: 13px 0;
					}
					</style>
					<!--[if !mso]><!-->
					<style type='text/css'>
					@media only screen and (max-width:480px) {
						@-ms-viewport {
						width: 320px;
						}
				
						@viewport{ width:320px; }
					}
					</style>
					<link href='https://fonts.googleapis.com/css2?family=Rubik' rel='stylesheet' type='text/css'>
					<style type='text/css'>
					@import url(https://fonts.googleapis.com/css2?family=Rubik);
					</style>
					<style type='text/css'>
					@media only screen and (min-width:480px) {
						.mj-column-per-100 {
						width: 100% !important;
						max-width: 100%;
						}
					}
					</style>
					<style type='text/css'>
					@media only screen and (max-width:480px) {
						table.full-width-mobile {
						width: 100% !important;
						}
				
						td.full-width-mobile {
						width: auto !important;
						}
					}
					</style>
				</head>
				
				<body style='background-color:#1c1e22;'>
					<div style='background-color:#1c1e22;'>
					<div style='Margin:0px auto;max-width:768px;'>
						<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
						<tbody>
							<tr>
							<td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
								<div class='mj-column-per-100 outlook-group-fix'
								style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
								<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;'
									width='100%'>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<table border='0' cellpadding='0' cellspacing='0' role='presentation'
										style='border-collapse:collapse;border-spacing:0px;'>
										<tbody>
											<tr>
											<td style='width:128px;'><img height='auto' src='https://img.999.game/email/999game.png'
												style='border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;'
												width='128'></td>
											</tr>
										</tbody>
										</table>
									</td>
									</tr>
								</table>
								</div>
							</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div style='Margin:0px auto;max-width:768px;'>
						<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
						<tbody>
							<tr>
							<td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
								<div class='mj-column-per-100 outlook-group-fix'
								style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
								<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;'
									width='100%'>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<table border='0' cellpadding='0' cellspacing='0' role='presentation'
										style='border-collapse:collapse;border-spacing:0px;'>
										<tbody>
											<tr>
											<td style='width:718px;'><a href='https://999.game/promotions' target='_blank'><img
													height='auto' src='https://img.999.game/email/main-banner.png'
													style='border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;'
													width='718'></a></td>
											</tr>
										</tbody>
										</table>
									</td>
									</tr>
								</table>
								</div>
							</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div style='Margin:0px auto;max-width:768px;'>
						<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
						<tbody>
							<tr>
							<td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
								<div class='mj-column-per-100 outlook-group-fix'
								style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
								<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;'
									width='100%'>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<div
										style='font-family:Rubik, sans-serif;font-size:15px;font-weight:800;line-height:1;text-align:center;color:#888888;'>
										" . $lang_email->sendWelcomeEmail->body_1 . "</div>
									</td>
									</tr>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<div
										style='font-family:Rubik, sans-serif;font-size:15px;line-height:1;text-align:center;color:#888888;'>
										" . $lang_email->sendWelcomeEmail->body_2 . "
										</div>
									</td>
									</tr>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<div
										style='font-family:Rubik, sans-serif;font-size:15px;line-height:1;text-align:center;color:#888888;'>
										" . $lang_email->sendWelcomeEmail->body_3 . " ".$_SESSION['account']."</div>
									</td>
									</tr>
									<tr>
									<td align='center'
										style='font-size:0px;padding:10px 25px;padding-bottom:24px;word-break:break-word;'>
										<div
										style='font-family:Rubik, sans-serif;font-size:15px;line-height:1;text-align:center;color:#888888;'>
										" . $lang_email->sendWelcomeEmail->body_4 . "</div>
									</td>
									</tr>
									<tr>
									<td align='center' vertical-align='middle'
										style='font-size:0px;padding:10px 25px;padding-bottom:24px;word-break:break-word;'>
										<table border='0' cellpadding='0' cellspacing='0' role='presentation'
										style='border-collapse:separate;width:140px;line-height:100%;'>
										<tr>
											<td align='center' bgcolor='#2283F6' role='presentation'
											style='border:none;border-radius:10px;cursor:auto;height:45px;padding:10px 25px;background:#2283F6;'
											valign='middle'><a href='https://999.game/'
												style='background:#2283F6;color:white;font-family:Rubik, sans-serif;font-size:15px;font-weight:600;line-height:120%;Margin:0;text-decoration:none;text-transform:none;'
												target='_blank'>Play Now</a></td>
										</tr>
										</table>
									</td>
									</tr>
								</table>
								</div>
							</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div style='Margin:0px auto;max-width:768px;'>
						<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
						<tbody>
							<tr>
							<td
								style='border-bottom:2px solid #24262b;border-top:2px solid #24262b;direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
								<div class='mj-column-per-100 outlook-group-fix'
								style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
								<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;'
									width='100%'>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<div
										style='font-family:Rubik, sans-serif;font-size:15px;line-height:1;text-align:center;color:#888888;'>
										<a href='https://bitcoin.org/en/' target='_blank' rel='noopener noreferrer nofollow'
											style='text-decoration: none'><img src='https://img.999.game/email/bitcoin.png'
											width='140px' style='padding: 16px'> </a><a href='https://www.ethereum.org/'
											target='_blank' rel='noopener noreferrer nofollow' style='text-decoration: none'><img
											src='https://img.999.game/email/ethereum.png' width='140px' style='padding: 16px'> </a><a
											href='https://tether.to/en/' target='_blank' rel='noopener noreferrer nofollow'
											style='text-decoration: none'><img src='https://img.999.game/email/tether.png' width='140px'
											style='padding: 16px'> </a><a href='https://tron.network/' target='_blank'
											rel='noopener noreferrer nofollow' style='text-decoration: none'><img
											src='https://img.999.game/email/tron.png' width='140px' style='padding: 16px'> </a><a
											href='https://docs.binance.org/smart-chain/guides/bsc-intro.html' target='_blank'
											rel='noopener noreferrer nofollow' style='text-decoration: none'><img
											src='https://img.999.game/email/binance.png' width='140px' style='padding: 16px'> </a>
											<a href='https://dogecoin.com/' target='_blank'
											rel='noopener noreferrer nofollow' style='text-decoration: none'><img
											src='https://img.999.game/email/dogecoin.png' width='140px' style='padding: 16px'> </a>
											<a href='https://cardano.org/' target='_blank'
											rel='noopener noreferrer nofollow' style='text-decoration: none'><img
											src='https://img.999.game/email/cardano.png' width='140px' style='padding: 16px'> </a>
											<a href='https://ripple.com/xrp/' target='_blank'
											rel='noopener noreferrer nofollow' style='text-decoration: none'><img
											src='https://img.999.game/email/xrp.png' width='140px' style='padding: 16px'> </a></div>
									</td>
									</tr>
								</table>
								</div>
							</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div style='Margin:0px auto;max-width:768px;'>
						<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
						<tbody>
							<tr>
							<td
								style='direction:ltr;font-size:0px;padding:20px 0;padding-top:40px;text-align:center;vertical-align:top;'>
								<div class='mj-column-per-100 outlook-group-fix'
								style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
								<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;'
									width='100%'>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<div
										style='font-family:Rubik, sans-serif;font-size:15px;line-height:1;text-align:center;color:#888888;'>
										" . $lang_email->sendWelcomeEmail->body_5 . "</div>
									</td>
									</tr>
									<tr>
									<td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
										<div
										style='font-family:Rubik, sans-serif;font-size:12px;line-height:1;text-align:center;color:#888888;'>
										Copyright © 2022 AGame. " . $lang_email->sendWelcomeEmail->body_6 . "</div>
									</td>
									</tr>
								</table>
								</div>
							</td>
							</tr>
						</tbody>
						</table>
					</div>
					</div>
				</body>
				
				</html>";
		
		if ($mail->send())
		{
			// return json_encode(['status'=>1,'info'=>"The message has been sent"], JSON_UNESCAPED_UNICODE );
		}
		// else return json_encode(['status'=>0,'info'=>"Error in sending reset code"]);
	} 
	catch (Exception $e) 
	{
		// return json_encode(['status'=>0,'info'=>"Error in sending reset code. Error: $mail->ErrorInfo"]);
	}
}

function send_verification_email($email)
{
	global $lang;
	
	if (!isset($email) && $email != "") return json_encode(['status' => 0, "info" => $lang->send_verification_email->enter_email], JSON_UNESCAPED_UNICODE );
	
	include_once(WEB_PATH."/email/PHPMailer.class.php");
	include_once(WEB_PATH."/email/smtp.class.php");
	include_once (WEB_PATH."/common/cache_file.class.php");
	$cachFile = new cache_file();
	$mail = new PHPMailer(true);
	$verif_code = rand(100000,999999);
	$cachFile->set($email,$verif_code,'','data','email_verification_code');

	$name = (isset($_SESSION['member_name'])) ? $_SESSION['member_name'] : "Guest";

	try {
		$mail->isSMTP();
		// $mail->SMTPDebug  = 2;
		$mail->Host       = 'smtpout.secureserver.net';
		$mail->SMTPAuth   = true;
        $mail->Username = 'support@999.game';
        $mail->Password = 'download15895';
		$mail->SMTPSecure = "ssl";
		$mail->Port       = 465;
		$mail->CharSet = 'UTF-8';

		$mail->setFrom('support@999.game', 'AGame');
		$mail->addReplyTo('support@999.game', 'AGame');
		$mail->addAddress($email, $name);
		$mail->isHTML(true);

		$mail->Subject = 'Email Verification';
		$mail->Body    =
		"<div
			style='
			width: 100%;
			background-image: url(https://999j9azx.999game.online/j9pwa/images/%C2%A6%C2%A6+%C2%A6+%C2%AC-+.png);
			background-size: 100%;
			background-position: top center;
			'
		>
			<div>
			<div style='padding: 0.75rem 1.25rem; margin-bottom: 10px; text-align: center'>
				<img src='https://999j9azx.999game.online/j9pwa/images/logo.png' style='width: 15%' />
			</div>
			<div style='width: 100%; height: 3px; display: flex'>
				<div style='width: 50%; height: 3px; background-color: black'></div>
				<div style='width: 50%; height: 3px; background-color: #0bafe6'></div>
			</div>
			<div style='padding: 1.25rem'>
				<p style='margin-top: 0; margin-bottom: 30px'>
				This verification code is intended for validation of user.
				</p>
				<div style='width: 100%'>
				<div style='background-color: #0bafe6; border-radius: 10px; padding: 0.5em 1.5em; margin: 0 auto; width: max-content'>
					<p style='color: white'>Verification Code: $verif_code</p>
				</div>
				</div>
				If you have not attempted to verify your email, please contact customer service immediately at - or
				<a href='mailto:support@999.game' target='_blank'>support@999.game</a>.
				</p>
			</div>
			</div>
		</div>";

		
		if ($mail->send())
		{
			return json_encode(['status'=>1,'info'=>$lang->send_verification_email->success_email_sent], JSON_UNESCAPED_UNICODE );
		}
		else return json_encode(['status'=>0,'info'=>$lang->send_verification_email->mail_error]);
	} 
	catch (Exception $e) 
	{
		return json_encode(['status'=>0,'info'=>$lang->send_verification_email->smtp_catch . "" . $mail->ErrorInfo]);
	}

}


function verify_email_code($email, $code)
{
    include_once "common/cache_file.class.php";
    $cachFile = new cache_file();
    $data_list = $cachFile->get($email, '', 'data', 'email_verification_code');

    if ($code == $data_list) {
		return ['status' => 1, 'info' => $lang->verify_email_code->email_verified];
    } else {
        return ['status' => 0, 'info' => $lang->verify_email_code->error_email_verify];
    }

}

function cleanString($string) {
   $string = str_replace(' ', '-', $string);

   return preg_replace('/[^A-Za-z0-9\-\_]/', '', $string);
}


?>

