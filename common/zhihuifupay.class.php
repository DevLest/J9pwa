<?php
	/**
     * 智汇付 网银 微信 支付 YB线路
     * */ 
class zhihuifupay {
	
    private $merchant_code = "Z800006000010";//商户ID
    private $pri_key = "-----BEGIN RSA PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAM+oiNHAHJCDTR1a
pz4ZvY+bV4E185BNx6nvk7OctqurplvTJwyyWpkGSM1or8hnZDOOna27NoMnqdq/
Dx85JspeE8T1Ydfaes6ep7oCZt3Z988SI/F6B7qVRIMJP8plk3Jm4yZm1aXKoMD5
Qfdy6jje6rl3lptuPFmB3QSqvvKrAgMBAAECgYAlkqb+3LXOaBOB5i8wi1PDMiZJ
GMvzJj+kPdcJvqdGtI4rOy9rmrHN0ldF41U6+4oj0gAxuRgJ7xlKnRtNWPftfjHV
DInmhqNNLKSul50hwO6wDecCMc/y7yJhJvds8/uYmYbG9tElyfRM2FeAJ/t9H/fB
eOnwP5afbik+jMtjQQJBAPWYy0EtBB+UNOZT3t2C1JRa0BDfutSzQCRfONCPGuu3
SHLTiYYf2n3FyKJoGZ5uVvOasQ3nfjrdsmHDgoJRL3sCQQDYdFemf6GAvokkGhnU
430Sp3eLSEUiwgD1gkcKWR990gw5En6/oji7TT5yIb8ZRgQgqT/3y1O+gyi6RbT6
JMqRAkAHoya+8h1stfknKHiHvufJbUGHJM30i1Z1SxjDM5AMwHhaScW/DAKJYrso
gcA6Mwg1LxxOaGJ+hiJj8+Z9EvU9AkB56LTBCps8dpOo6KZp16JG0lkq8g42MEv3
+mLmeiGZbKcDsd3/Mm9/Vlb4UvddVajXeFuVxUeqQha7Kq1uQTBxAkAosutn3D4S
GzLM+Q9eQTwnrQdHvpM8rDo6yt6IR0HluJTiRySAy4mWc+1Ng4Us98ANVFIkqTlb
EqRitpd70gGp
-----END RSA PRIVATE KEY-----";//商家私钥
    
    private $pub_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDuCiDtSKTLzhYMgY3roxYoLkD2
4qfslscP9+gDoaMdfOnYl8iPBwahPkGjz/RVm99JbG+m2HlLCRWomqBPqYUk3IL7
ya5FqQtNWHa6T3d0mtPHKzCChpDj2qK8LSQLQuhWC58FbSMG3mwUHyXbQxsQkAHo
VEfGJfWVkgZ3a/MyTwIDAQAB
-----END PUBLIC KEY-----";//智汇付公钥
    
	
	/**
	 * 计算md5签名
	 **/
	public function sign($data,$key) {
    	$string = "";
    	foreach($data as $k=>$v)
    	{
    		$string .= $k."=".$v."&";
    	}
    	$string .= "pkey=".$key;
    	//echo $string;
    	return md5($string);
	}
	/**
	 * 组建数组 对数据整合
	 * $info array  bill_no,amount,bank_code,return_url
	 * $url string 提交地址
	 * return $form_string 表单字符串
	 **/
	public function build_data($info,$url = "")
	{
	    $billno = $info['billno'];
	    $amount = $info['amount'];  
	    $bankco = $info['bank_code'];
	    
	    $key = openssl_get_privatekey($this->pri_key);
	    $data['merchant_code'] = $merchant_code = $this->merchant_code;
	    $data['service_type'] = $service_type = "direct_pay";
	    $data['interface_version'] = $interface_version = "V3.0";
	    if($bankco == "weixin")
	    {
	        $data['pay_type'] = $pay_type = "weixin";
	        $data['bank_code'] = $bank_code = "";
	    }elseif($bankco == "alipay"){
	        $data['pay_type'] = $pay_type = "alipay_scan";
	        $data['bank_code'] = $bank_code = "";
	    }elseif($bankco == "tenpay"){
	        $data['pay_type'] = $pay_type = "tenpay_scan";
	        $data['bank_code'] = $bank_code = "";
	    }else{
	        $data['pay_type'] = $pay_type = "b2c";
	        $data['bank_code'] = $bank_code = $bankco;
	    }
	    $data['sign_type'] = $sign_type = "RSA-S";
	    $data['input_charset'] = $input_charset = "UTF-8";
	    $data['notify_url'] = $notify_url = $info['return_url'];
	    $data['order_no'] = $order_no = $billno;
	    $data['order_time'] = $order_time = date("Y-m-d H:i:s");
	    $data['order_amount'] = $order_amount = number_format($amount, 2, '.', '');
	    $data['product_name'] = $product_name = "在线支付充值";
	    $data['product_code'] = $product_code = "ZXZFCZ";
	    $data['product_desc'] = $product_desc = "在线支付充值";
	    $data['product_num'] = $product_num = 1;
	    $data['show_url'] = $show_url = "";
	    $data['client_ip'] = $client_ip = "";
	     
	    $data['redo_flag'] = $redo_flag = 1;
	    $data['extend_param'] = $extend_param = "";
	    $data['extra_return_param'] = $extra_return_param = "";
	    $data['return_url'] = $return_url = "";
	    //数据签名加密 开始
	    $signStr= "";
	    if($bank_code != ""){
	        $signStr = $signStr."bank_code=".$bank_code."&";
	    }
	    if($client_ip != ""){
	        $signStr = $signStr."client_ip=".$client_ip."&";
	    }
	    if($extend_param != ""){
	        $signStr = $signStr."extend_param=".$extend_param."&";
	    }
	    if($extra_return_param != ""){
	        $signStr = $signStr."extra_return_param=".$extra_return_param."&";
	    }
	    $signStr = $signStr."input_charset=".$input_charset."&";
	    $signStr = $signStr."interface_version=".$interface_version."&";
	    $signStr = $signStr."merchant_code=".$merchant_code."&";
	    $signStr = $signStr."notify_url=".$notify_url."&";
	    $signStr = $signStr."order_amount=".$order_amount."&";
	    $signStr = $signStr."order_no=".$order_no."&";
	    $signStr = $signStr."order_time=".$order_time."&";
	    if($pay_type != ""){
	        $signStr = $signStr."pay_type=".$pay_type."&";
	    }
	    if($product_code != ""){
	        $signStr = $signStr."product_code=".$product_code."&";
	    }
	    if($product_desc != ""){
	        $signStr = $signStr."product_desc=".$product_desc."&";
	    }
	    $signStr = $signStr."product_name=".$product_name."&";
	    if($product_num != ""){
	        $signStr = $signStr."product_num=".$product_num."&";
	    }
	    if($redo_flag != ""){
	        $signStr = $signStr."redo_flag=".$redo_flag."&";
	    }
	    if($return_url != ""){
	        $signStr = $signStr."return_url=".$return_url."&";
	    }
	    if($show_url != ""){
	        $signStr = $signStr."service_type=".$service_type."&";
	        $signStr = $signStr."show_url=".$show_url;
	    }else{
	        $signStr = $signStr."service_type=".$service_type;
	    }
	    openssl_sign($signStr,$sign_info,$key,OPENSSL_ALGO_MD5);
	    $sign = base64_encode($sign_info);
	    //数据签名加密 结束
	    $data['sign'] = $sign;

	    return $data;
	}
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_sign($info)
	{
	    $key = openssl_get_publickey($this->pub_key);
	     
	    $merchant_code	= $info["merchant_code"];
	    $interface_version = $info["interface_version"];
	    $sign_type = $info["sign_type"];
	    $dinpaySign = base64_decode($info["sign"]);
	    $notify_type = $info["notify_type"];
	    $notify_id = $info["notify_id"];
	    $order_no = $info["order_no"];
	    $order_time = $info["order_time"];
	    $order_amount = $info["order_amount"];
	    $trade_status = $info["trade_status"];
	    $trade_time = $info["trade_time"];
	    $trade_no = $info["trade_no"];
	    $bank_seq_no = $info["bank_seq_no"];
	    $extra_return_param = $info["extra_return_param"];
	    //数据签名加密 开始
	    $signStr = "";
	    if($bank_seq_no != ""){
	        $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
	    }
	    if($extra_return_param != ""){
	        $signStr = $signStr."extra_return_param=".$extra_return_param."&";
	    }
	    $signStr = $signStr."interface_version=".$interface_version."&";
	    $signStr = $signStr."merchant_code=".$merchant_code."&";
	    $signStr = $signStr."notify_id=".$notify_id."&";
	    $signStr = $signStr."notify_type=".$notify_type."&";
	    $signStr = $signStr."order_amount=".$order_amount."&";
	    $signStr = $signStr."order_no=".$order_no."&";
	    $signStr = $signStr."order_time=".$order_time."&";
	    $signStr = $signStr."trade_no=".$trade_no."&";
	    $signStr = $signStr."trade_status=".$trade_status."&";
	    $signStr = $signStr."trade_time=".$trade_time;
	    //数据签名加密 结束
	    $status = openssl_verify($signStr,$dinpaySign,$key,OPENSSL_ALGO_MD5);
	    if($status == 1)
	    {
	        return true;
	    }else{
	        return false;
	    }
	}
	/**
	 * 组建表单
	 * $info array
	 * return 表单字符串
	 **/
	public function build_form($info,$url,$method="post")
	{
	    $str = "<form id='pay' name='pay' action='".$url."' method='".$method."'>";
	    foreach($info as $key=>$val)
	    {
	        $str .= "<input type='hidden' name='".$key."' value='".$val."'/>";
	    }
	    //$str .= "<input type='submit' value='submit' />";
	    $str .= "</form>";
	    $str.= "<script>document.forms['pay'].submit();</script>";
	    return $str;
	}
}
?>