<?php
ini_set("display_errors", "On");

error_reporting(E_ALL | E_STRICT);

header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
define("WEB_PATH", __DIR__);
include_once ("core.class.php");
error_reporting(0);
if(!isset($_SESSION))
{
	session_start();
}
$ip = get_client_ip();

	$white_list = white_ip_list();
	$status = array_search($ip,$white_list);
	
	if($status === false)
	{
echo 0;exit;
		//error_log(date('m-d H:i:s')."b#".$ip."##".$str."#\r\n", 3,'huiding_alipayip.log');
		return false;
		
	}
       // $core = new core();
if(isset($_POST['type']) && $_POST['type'] == "gfalipay_submit")
	{
		echo gfalipay_submit($_POST['account'],$_POST['amount'],$_POST['billno']);
	}elseif(isset($_POST['type']) && $_POST['type'] == "gfalipay_huidiao")
	{
		echo gfalipay_huidiao();
	}
        function gfalipay_submit($account,$amount,$billno){
$core = new core();
$des = new DES3();

		/*$account = $_POST['account'];
                $amount = $_POST['amount'];
                $billno = $_POST['billno'];*/
        
		$method = "get";
		//$billno="s".date("YmdHis").rand(1000,9999);
	
		if($amount < 10)
		{
		   echo json_encode(array(
				 'status'=>0,
				 'info'=>'La cantidad es demasiado pequeña'
		));  exit;
		}
		/*     if($_POST['autopromo']==3 ){
                if($amount < 50)
		{
			echo "<script>alert('该活动最低存款50，详见活动细则');window.close();</script>";
			exit();
		}
                }*/
		$bankco='60112';
		$data = array();
		//$temp=explode('_',$_POST['pay_id']);
		$pay_id = 25;
		$line_type = 25;
		$monlinepay_info = $core->monlinpay_detail($pay_id);
		
		//判断支付银行选项是否正确
		$zf_bank_id = array("ABC","ICBC","CCB","BCOM","BOC","CMB","CMBC","CEBB","SHB","NBB","HXB","CIB","PSBC","SPABANK","SPDB","HZB","ECITIC");
		
	
		if($monlinepay_info['pay_status'] !=1 && $account != "feng12345")
		{
		   echo json_encode(array(
				 'status'=>0,
				 'info'=>'Pago de mantenimiento'
		));  exit;
		}
            //   print_r($_SESSION['account']);exit;
           
		$re = $core->onlinepay($account,$amount,$billno,'Alipay oficial',0);
                $alipayid=$core->get_alipayid();
		if($re == 1)
		{
			   echo json_encode(array(
				 'status'=>1,
                                'alipayid'=>$alipayid,
				 'info'=>'éxito'
		));  exit;
                   // 三方通用提交       
                  
		}elseif($re == -1){
			   echo json_encode(array(
				 'status'=>0,
				 'info'=>'Error de oferta'
		));  exit;
		}else{
			   echo json_encode(array(
				 'status'=>0,
				 'info'=>'Otros errores'
		));  exit;
		}
		
        }
        
        
        function gfalipay_huidiao(){
             $core = new core();
             $alipayid=$core->get_alipayid();
             
             	   echo json_encode(array(
				 'status'=>1,
                                'alipayid'=>$alipayid,
				 'info'=>'éxito'
		));  exit;
            
        }
function get_client_ip()
{
	if (@$_SERVER["HTTP_X_FORWARDED_FOR"]){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}else if (@$_SERVER["HTTP_CLIENT_IP"]){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}else if (@$_SERVER["REMOTE_ADDR"]){
		$ip = $_SERVER["REMOTE_ADDR"];
	}else if (@getenv("HTTP_X_FORWARDED_FOR")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}else if (@getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	}else if (@getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	}else{
		$ip = "Unknown";
	}
	$temp = explode(",",$ip);
	$ip = $temp[0];
	return $ip;
}
/**
 * 客户端请求的IP白名单列表
 * @return array ip列表 信息
 */
function white_ip_list()
{
	return array("113.107.111.29");
	// return array("180.232.85.164");
	
	
	
	
}
?>

