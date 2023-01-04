<?php
	/**
     * 融E付 微信 支付 http://www.r1pay.com/
     * */ 
class ryfpay {
	
    private $merchant_id = "1001974";
    private $key = "1650540c47475cdfb9311d7e3e3e7ce0";
	/**
	 * 计算md5签名
	 **/
	public function sign($data,$key) {
    	$string = "";
    	foreach($data as $k=>$v)
    	{
    		$string .= $k."".$v;
    	}
    	$string .= $key;
    	// echo $string;
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
	    
	    $param["userid"] = $data["userid"] = $merchant_id;
	    $param["orderid"] = $data["orderid"] = $billno;
	    $param["btype"] = $data["btype"] = $bank_code;
	    $param["ptype"] = $data["ptype"] = 0;
	    $param["value"] = $data["value"] = $amount;
	    $param["returnurl"] = $data["returnurl"] = $return_url;
	    $param["hrefreturnurl"] = $data["hrefreturnurl"] = "";
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
	        "userid"=>$info['userid'],
	        "orderid"=>$info['orderid'],
	        "btype"=>$info['btype'],
	        "result"=>$info['result'],
	        "value"=>$info['value'],
	        "value"=>$info['value'],
	        "realvalue"=>$info['realvalue']
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