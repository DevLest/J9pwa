<?php
/**
 *用来处理前台页面提交的表单
 *通过phprpc存储到数据库中
 */
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");
//header (" Access- Control-Allow -Headers : *") ;
define("WEB_PATH", __DIR__);
include_once ("core.class.php");
if(!isset($_SESSION))
{
    session_start();
}
if(isset($_GET['type']) && $_GET['type'] == "get_promotion_content")
{
	echo get_promotion_content($_GET['pro_id']); exit;
}elseif(isset($_GET['type']) && $_GET['type'] == "get_autopromotion_list")
{
	
	echo get_autopromotion_list($_SESSION['account'],$_GET['amount']);exit;
}elseif(isset($_POST['type']) && $_POST['type'] == "get_promotion_list")
{
	$account = (isset($_POST['username'])) ? $_POST['username'] : "";
	echo get_promotion_list($account);exit();
}elseif(isset($_POST['type']) && $_POST['type'] == "get_footer_list")
{
	$account = (isset($_POST['username'])) ? $_POST['username'] : "";
	echo get_footer_list($account);exit();
}
function get_promotion_content($id){
	//加载缓存
	//return 45;
		$client = new PHPRPC_Client(SERVER_URL);
		$result = $client->web_promotion_active_detail($id);
                	//return $result;
	$result =unserialize($result);
	if($result){
	
		return json_encode(array("status"=>1,"info"=>$result[0]['content']));
	}else{
		return json_encode(array("status"=>0,"info"=>"Unsuccessful offer"));
	}
}


	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
		echo json_encode(array('status'=>0,'info'=>"Verification failed"));
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

   
if(isset($_POST['type']) && $_POST['type'] == "get_notice")
{
	echo get_notice($_POST['num']);
}



elseif(isset($_GET['type']) && $_GET['type'] == "check_member_info")
{
	$field = $_GET['field'];
	echo check_member_info($_GET['input'],$_GET['field']);
}elseif(isset($_GET['type']) && $_GET['type'] == "check_login")
{
	echo check_login();
}elseif(isset($_GET['type']) && $_GET['type'] == "clear_cache")
{
    echo clear_cache();
}elseif(isset($_GET['type']) && $_GET['type'] == "logout")
{
	echo logout();
}elseif(isset($_POST['type']) && $_POST['type'] == "get_duanxin")
{
	echo get_duanxin($_POST['phone']);
}elseif(isset($_GET['type']) && $_GET['type'] == "return_null")
{
	echo "hello";
}




	//获取历史公告	
	function get_notice($num)
	{
		$core = new core();
		$info = $core->get_notice($num);
		$str = array('status'=>1,'info'=>$info);
		return json_encode($str);
	}
/*
* 检测信息是否存在 不存在-true，存在-false
* @param string  $data 要检测的数据 如：test1234
* @param string  $field 对应的字段 如：account
*/
function check_member_info($data,$field)
{
	$core = new core();
	$code = $core->check_member_info($data,$field);
	if(isset($_GET['turn']) && $_GET['turn'] !='')
	{
		if($code == 1007)
		{
			return json_encode(array("valid"=>false));
		}elseif($code == 1006){
			return json_encode(array("valid"=>true));
		}else{
			return json_encode(array("valid"=>false));
		}
	}else{
		if($code == 1007)
		{
			return 1;
			//return true;
		}elseif($code == 1006){
			return 0;
			//return false;
		}else{
			return 0;
			//return false;
		}
	}
}
/*
 * 更新前台优惠缓存
 * @return int  0或1
 */
function clear_cache()
{
    include_once (WEB_PATH."/common/cache_file.class.php");
    //获取缓存数据
    $cachFile = new cache_file();
    $status = $cachFile->delete("active_list",'','data','active_list');
    if($status)
    {
        return "Update completed";
    }else{
        return "Failed update";
    }
}
/*
 * 检查用户是否登录
 * @return int  0或1
 */
function check_login()
{
    if(isset($_SESSION['account']) && $_SESSION['account'] != "")
    {
       return 1;
    }else{
       return 0;
    }  
}
/*
 * 用户登出
 * @return array
 */
function logout()
{
    if(isset($_SESSION['account']) && $_SESSION['account'] != "")
    {
		unset($_SESSION['account']);
		unset($_SESSION['balance']);
		unset($_SESSION['member_name']);
		unset($_SESSION['member_type']);
		return json_encode(array("status"=>1,"info"=>"Your account has been closed"));
    }else{
		return json_encode(array("status"=>0,"info"=>"No logged in account detected"));
	}	
}
/*
 * 获取优惠列表
 * return array 
 */
function get_promotion_list($account){
	//加载缓存
	/*include_once (WEB_PATH."/common/cache_file.class.php");
	//获取缓存数据
	$cachFile = new cache_file();
	$result = array();
	$data_list = $cachFile->get('active_list','','data','active_list');
	if($data_list == 'false')
	{
		$client = new PHPRPC_Client(SERVER_URL);
		$result = unserialize($client->web_promotion_active());
		if(is_array($result) && $result['status'] == 0)
		{
			unset($result['status']);
			$cachFile->set('active_list',$result,'','data','active_list');
		}
	}else{
		$result = $data_list;
	}
	$pro_list = array();*/
        //define('SERVER_URL','http://adminu2dnewonesite.32sun.com/phprpc/server.php');
        $client = new PHPRPC_Client(SERVER_URL);
	$result = unserialize($client->web_promotion_active_english($account));
       // $result = $client->web_promotion_active($account);
       // $result = $client->mobilelogin($account,1201);
       // print_r($account);exit;
        //echo 999;
       //print_r($result);exit;
	if(is_array($result))
	{
            
            unset($result['status']);
                foreach ($result as  $k=>$v){
                    
                    $result1[$k]['id']=$v['id'];
                    $result1[$k]['orderid']=$v['orderid'];
                     $result1[$k]['title']=$v['title'];
                      $result1[$k]['startTime']=$v['startTime'];
                       $result1[$k]['endTime']=$v['endTime'];
                        $result1[$k]['bannerurl']="https://www.u2d8899.com/mexicoimages/banner/".$v['sPicURL'];
                       //  $result1[$k]['content']=$v['content'];
                }
                
              
                
                
		return json_encode(array("status"=>1,"info"=>$result1));
	}else{
		return json_encode(array("status"=>0,"info"=>"Unsuccessful offer"));
	}
}


function get_footer_list($account){
	
      
        $client = new PHPRPC_Client(SERVER_URL);
	$result = unserialize($client->get_footer_english_list($account));

	if(is_array($result))
	{
        
                
		return json_encode(array("status"=>1,"info"=>$result));
	}else{
		return json_encode(array("status"=>0,"info"=>"Unsuccessful offer"));
	}
}
function get_autopromotion_list($account,$amount){
    
     $client = new PHPRPC_Client(SERVER_URL);
	$result = unserialize($client->web_autopromotion_active($account,$amount));
   //return  $result;
	if(is_array($result))
	{
            
            unset($result['status']);
                foreach ($result as  $k=>$v){
                    
                    $result1[$k]['id']=$v['id'];
                    //$result1[$k]['orderid']=$v['orderid'];
                     $result1[$k]['title']=$v['title'];
                     // $result1[$k]['startTime']=$v['startTime'];
                      // $result1[$k]['endTime']=$v['endTime'];
                     //   $result1[$k]['bannerurl']="www.u2d8899.com/images/banner/".$v['sPicURL'];
                       //  $result1[$k]['content']=$v['content'];
                }
                
              
                
                
		return json_encode(array("status"=>1,"info"=>$result1));
	}else{
		return json_encode(array("status"=>0,"info"=>'Without discount'));
	}
    
    
    
}
 
 
/*
 * 获取优惠内容
 * 参数 $id
 * retern array
 */

 
function get_duanxin($phone){
	$a=urlencode('cam555');
	$b=urlencode('eec62ad8e1f3823b9d53');
	$c=urlencode($phone);
	$d=rand(100000,999999);
	$d=urlencode($d);
	$e=urlencode('Tecnología Co., Ltd. de Wuhan East Lake Huxin');
	$_SESSION['duanxincode']=$d;
	//$url="http://utf8.api.smschinese.cn/?Uid=$a&Key=$b&smsMob=$c&smsText=验证码:3321546【'.$e.'】";
	$url="http://utf8.api.smschinese.cn/?Uid=$a&Key=$b&smsMob=$c&smsText=验证码:'.$d.'【'.$e.'】";
	 echo Get($url); //正确返回1
	
} 
function Get($url){
	if(function_exists('file_get_contents'))
	{
	$file_contents = file_get_contents($url);
	}
	else
	{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	}
	return $file_contents;
}
  
 
 
 
 
 
 
 
 
 
 
 
 
 
 
?>