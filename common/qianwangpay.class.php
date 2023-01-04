<?php
	/**
     * 千网 网银 微信 支付 http://vip.10001000.com
     * */ 
class qianwangpay {
	
    private $key = "d83a6cac595c4492b50651f57c792c9b";
	/**
	 * 计算md5签名
	 **/
	public function sign($data,$key) {
    	$string = "";
    	foreach($data as $k=>$v)
    	{
    		$string .= $k."=".$v."&";
    	}
    	$string = substr($string,0,-1);
    	$string .= $key;
    	//echo $string;
    	return md5($string);
	}
	/**
	 * 组建数组 对数据整合
	 * $info array 
	 * return $data array billno,amount,bank_code,return_url,merchant_id,pay_id
	 **/
	public function build_data($info)
	{
	    $billno = $info['billno'];
	    $amount = $info['amount'];
	    $bank_code = $info['bank_code'];
	    $return_url = $info['return_url'];
	    $merchant_id = $info['merchant_id'];
	    $pay_id = $info['pay_id'];
	    $data = array();
	    $param = array();
	    
	    $param["parter"] = $data["parter"] = $merchant_id;
	    $param["type"] = $data["type"] = $bank_code;
	    $param["value"] = $data["value"] = $amount;
	    $param["orderid"] = $data["orderid"] = $billno;
	    $param["callbackurl"] = $data["callbackurl"] = $return_url;
	    $data["hrefbackurl"] = "";
	    $data["payerIp"] = "";
	    $data["attach"] = $pay_id;
	    
	    
	    $data["sign"] = $this->sign($param, $this->key);
	    
	    return $data;
	}
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_sign($info)
	{
	    $sign_bak = $info['sign'];
	    $data = array(
	        "orderid"=>$info['orderid'],
	        "opstate"=>$info['opstate'],
	        "ovalue"=>$info['ovalue']
	    );
	    $sign = $this->sign($data, $this->key);
	    if($sign_bak == $sign)
	    {
	        return true;
	    }else{
	        return false;
	    }
	}
}
?>