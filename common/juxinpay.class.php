<?php
	/**
     * 聚鑫的 网银 微信 支付宝 使用MD5 加密 YB
     * */ 
class juxinpay {
	private $merchant_key = "51e8e11993ae47bfd739467ea3828bfd";
	private $merchant_id = "jxgaoke10044";
    
	/**
	 * 组建数组 对数据整合 包含加密 网银
	 * $info array 
	 * return $data array
	 **/
	public function build_bank_data($info)
	{
	    $bank_code = $info['bank_code'];
	    $billno = $info['billno'];
	    $amount = $info['amount'];
	    $return_url = $info['return_url'];
	    
		
	    $data['merchantId'] = $this->merchant_id;
	    $data['orderId'] = $billno;
	    $data['productName'] = 15;
	    $data['bankId'] = $bank_code;
	    $data['notifyUrl'] = $return_url;
	    $data['amount'] = $amount;
		$sign = $this->bank_sign($data);
	    $data['remark'] = "";
	    $data['noticePage'] = "http://pay.116tyc.net/yb/show.php";
		
		//数据签名加密 结束
		$data['hmac'] = $sign;
	    return $data;
	}
	/**
	 * 组建数组 对数据整合 包含加密 微信
	 * $info array 
	 * return $data array
	 **/
	public function build_wechat_data($info)
	{
	    $bank_code = $info['bank_code'];
	    $billno = $info['billno'];
	    $amount = $info['amount'];
	    $return_url = $info['return_url'];
	    
		
	    $data['version'] = 1;
	    $data['agentId'] = $this->merchant_id;
	    $data['agentOrderId'] = $billno;
		$data['OrderTime'] = date("YmdHis");
	    $data['payType'] = 10;
	    $data['payAmt'] = $amount;
	    $data['notifyUrl'] = $return_url;
		$sign = $this->wechat_sign($data);
	    $data['goodsName'] = "虚拟充值";
	    $data['noticePage'] = "http://pay.116tyc.net/yb/show.php";
		//数据签名加密 结束
		$data['sign'] = $sign;
	    return $data;
	}
	/**
	 * 组建数组 对数据整合 包含加密 支付宝
	 * $info array 
	 * return $data array
	 **/
	public function build_alipay_data($info)
	{
	    $bank_code = $info['bank_code'];
	    $billno = $info['billno'];
	    $amount = $info['amount'];
	    $return_url = $info['return_url'];
	    $ip = $info['ip'];
	    
	    $data['version'] = 1;
	    $data['agent_id'] = $this->merchant_id;
	    $data['agent_bill_id'] = $billno;
		$data['agent_bill_time'] = date("YmdHis");
	    $data['pay_type'] = 50;
		$data['pay_amt'] = $amount;
	    $data['notify_url'] = $return_url;
	    $data['user_ip'] = $ip;
		
		$sign = $this->alipay_sign($data);
		$data['return_url'] = "http://pay.116tyc.net/yb/show.php";
		$data['is_phone'] = 1;
	    $data['goods_name'] = "虚拟充值";
	    $data['goods_note'] = "";
		//数据签名加密 结束
		$data['sign'] = $sign;
	    return $data;
	}
	
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_bank_sign($info)
	{
	    $data['merchantId'] = $info['merchantId'];
	    $data['orderId'] = $info['orderId'];
	    $data['realAmount'] = $info['realAmount'];
	    $data['payResult'] = $info['payResult'];
		$hmac = $info['hmac'];
		$sign = $this->bank_sign($data);
        if($sign == $hmac)
        {
            return true;
        }else{
            return false;
        }
	}
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_wechat_sign($info)
	{
	    $data['result'] = $info['result'];
	    $data['agentId'] = $info['agentId'];
	    $data['jnetOrderId'] = $info['jnetOrderId'];
	    $data['agentOderId'] = $info['agentOderId'];
	    $data['payAmt'] = $info['payAmt'];
		$hmac = $info['sign'];
		$sign = $this->wechat_sign($data);
        if($sign == $hmac)
        {
            return true;
        }else{
            return false;
        }
	}
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_alipay_sign($info)
	{
	    $data['result'] = $info['result'];
	    $data['agentId'] = $info['agentId'];
	    $data['jnetOrderId'] = $info['jnetOrderId'];
	    $data['agentOderId'] = $info['agentOderId'];
	    $data['payAmt'] = $info['payAmt'];
	    $hmac = $info['sign'];
	    $sign = $this->alipay_sign($data);
	    if($sign == $hmac)
	    {
	        return true;
	    }else{
	        return false;
	    }
	}
	
	
	/**
	 * 计算md5签名 网银
	 **/
	public function bank_sign($data) {
    	$string = "";
    	foreach($data as $k=>$v)
    	{
    		$string .= $v."|";
    	}
    	$string .= $this->merchant_key;
    	return md5($string);
	}
	/**
	 * 计算md5签名 微信
	 **/
	public function wechat_sign($data) {
    	$string = "";
    	foreach($data as $k=>$v)
    	{
    		$string .= $k."=".$v."&";
    	}
    	$string .= "key=".$this->merchant_key;
    	return md5($string);
	}
	/**
	 * 计算md5签名 支付宝
	 **/
	public function alipay_sign($data) {
    	$string = "";
    	foreach($data as $k=>$v)
    	{
    		$string .= $k."=".$v."&";
    	}
    	$string .= "key=".$this->merchant_key;
    	return md5($string);
	}
}
?>