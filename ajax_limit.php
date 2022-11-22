<?php
/**
 *用来处理前台页面提交的表单
 *通过phprpc存储到数据库中
 */
header("Content-type: text/html; charset=utf-8");
if(!isset($_SESSION))
{
	session_start();
}
$limit = new ajax_limit();
if(isset($_GET['type']) && $_GET['type'] == "onlinepay")
{
	$pay_id = $_GET['pay_id'];
	$result = $limit->onlinepay_limit($pay_id);
	echo json_encode($result);
}elseif(isset($_GET['type']) && $_GET['type'] == "mobilepay")
{
	$pay_id = $_GET['pay_id'];
	$result = $limit->mobilepay_limit($pay_id);
	echo json_encode($result);
}elseif(isset($_GET['type']) && $_GET['type'] == "weixinpay")
{
	$pay_id = $_GET['pay_id'];
	$result = $limit->weixinpay_limit($pay_id);
	echo json_encode($result);
}elseif(isset($_GET['type']) && $_GET['type'] == "alipay")
{
	$pay_id = $_GET['pay_id'];
	$result = $limit->alipay_limit($pay_id);
	echo json_encode($result);
}elseif(isset($_GET['type']) && $_GET['type'] == "bankpay")
{
	$pay_id = $_GET['pay_id'];
	$result = $limit->bankpay_limit($pay_id);
	echo json_encode($result);
}elseif(isset($_GET['type']) && $_GET['type'] == "tenpay")
{
	$pay_id = $_GET['pay_id'];
	$result = $limit->tenpay_limit($pay_id);
	echo json_encode($result);
}






class ajax_limit
{
	var $amount_low = 10;
	var $amount_up = 99999;
	
	
	//根据在线支付的pay_id判断存款金额限制
	public function onlinepay_limit($pay_id)
	{
		if($pay_id != "")
		{
			$temp = explode("_",$pay_id);//0mpay,1为id,2为pay_type,3为line_type
			if($temp[3] == 10000)
			{
				//盈宝支付宝
				$this->amount_up = 2000;
			}elseif($temp[3] == 25 || $temp[3] == 26){
				//华仁 支付宝
				$this->amount_up = 3000;
			}elseif( $temp[3] == 43 || $temp[3] == 30){
				//dpay 支付宝
				$this->amount_low=50; 
				$this->amount_up = 3000;
                        }elseif($temp[3] == 235 ||$temp[3] == 14 ){				
							
				$this->amount_low=200;            
			}elseif($temp[3] == 2  ||$temp[3] == 8){				
							
				$this->amount_low=50;            
			}elseif($temp[3] == 23212 ){				
							
				$this->amount_low=300;            
			}elseif( $temp[3] == 233 ){				
							
				$this->amount_low=30;            
			}elseif( $temp[3] == 217 ||$temp[3] == 239 ||$temp[3] == 241  ||$temp[3] == 248 ||$temp[3] == 252||$temp[3] == 253||$temp[3] == 232 ||$temp[3] == 5 ){				
							
				$this->amount_low=100;            
			}elseif($temp[3] == 240 ||$temp[3] ==23 ||$temp[3] == 24){				
							
				$this->amount_low=1000;            
			}elseif($temp[3] == 249){				
							
				$this->amount_low=101;            
			}elseif($temp[3] == 250){				
							
				$this->amount_low=151;            
			}elseif($temp[3] == 3  ||$temp[3] == 6 ||$temp[3] == 7  ||$temp[3] == 12 ||$temp[3] == 15 ||$temp[3] == 16 ||$temp[3] == 22 ){				
							
				$this->amount_low=100;            
			}elseif($temp[3] == 10 ||$temp[3] == 11 ||$temp[3] == 1 ||$temp[3] == 3111 ||$temp[3] == 13 ||$temp[3] == 9 ||$temp[3] == 20||$temp[3] == 21 ||$temp[3] == 27||$temp[3] == 29 ){				
							
				$this->amount_low=500;            
			}elseif( $temp[3] == 18  ||$temp[3] == 19 ||$temp[3] == 4 ||$temp[3] == 34){				
							
				$this->amount_low=300;            
			}elseif($temp[3] == 31){				
							
				$this->amount_low=200;            
			}else{
				//普通在线支付
			}
		}
		return array($this->amount_low,$this->amount_up);
	}
	
	public function mobilepay_limit($pay_id)
	{
		return array($this->amount_low,$this->amount_up);
	}
	
	public function weixinpay_limit($pay_id)
	{
		$this->amount_up = 3000;
		return array($this->amount_low,$this->amount_up);
	}
	
	public function alipay_limit($pay_id)
	{
		return array($this->amount_low,$this->amount_up);
	}
	
	public function tenpay_limit($pay_id)
	{
		return array($this->amount_low,$this->amount_up);
	}
	
	public function bankpay_limit($pay_id)
	{
		return array($this->amount_low,$this->amount_up);
	}
}
?>