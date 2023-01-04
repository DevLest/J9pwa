<?php
	/**
     * 乐盈 网银 微信 支付
     * */ 
class leyingpay {
	
    private $key = "30820122300d06092a864886f70d01010105000382010f003082010a02820101008c7aadb136983671c2378ccab665c5db26d07a1006e25a97fbf388d7e6f64c5c98f7336c6e68edbe206bccdb067e87a46e803b8c44f35640347b2f65fd5bd64d4246451bced1abc42b92409e9833a985efe004b94f8cf40b3c4a49ca8e1df0bb48839205b01e55ab336b9ce1427d10c34a849be91a3a2ce2cf8c0f95ef25928d37a87ffa37c91598e272e86cf6c0919f2eb7a4150f4f556753a3d12dfc1dfb2a13c4a1b0e68be25a610db58a7cf9fe8b2a2cdad8db697ab05969eb0135574f7eb365e0204189dcae029cfff129f892126610186bc09c2a1a6d076b8699ba1c8701c6763d3d9a4c37c8fcccd87799444c1b2c872b67ac18ad060c305e9f3e66450203010001";
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
	 * $info array 
	 * return $data array
	 **/
	public function build_data($info)
	{
	    $billno = $info['billno'];
	    $amount = $info['amount']*100;
	    $bank_code = $info['bank_code'];
	    if($bank_code == "wx")
	    {
	        $pay_type = "WX";
	    }elseif($bank_code == "zfb")
	    {
	        $pay_type = "ZFB";
	    }else{
	        $pay_type = "BANK_B2C";
	    }
	    $data["version"] = "1.0";  //版本信息 默认 1.0
	    $data["serialID"] = "yb".date("YmdHis").mt_rand(100000,999999);    //商户请求序列号 非订单号
	    $data["submitTime"] = date("YmdHis");  //提交时间
	    $data["failureTime"] = ""; //过期时间 默认为90天
	    $data["customerIP"] = "";  //客户端IP
	    $data["orderDetails"] = $billno.",".$amount.",,网上自动订单,1";  //订单信息 订单号,金额 分,描述,商品名称,商品数量
	    $data["totalAmount"] = $amount;    //订单总金额 分
	    $data["type"] = 1000;  //固定值 1000
	    $data["buyerMarked"] = ""; //付款标识
	    $data["payType"] = $pay_type;  //付款方式
	    $data["orgCode"] = $bank_code; //付款代码
	    $data["currencyCode"] = "1";   //币种 默认 1 RMB
	    $data["directFlag"] = "1"; //是否直连  1为直连
	    $data["borrowingMarked"] = "";
	    $data["couponFlag"] = "";
	    $data["platformID"] = "";
	    $data["returnUrl"] = "http://pay.116tyc.net/yb/show.php"; //商户回调地址 完成后跳转
	    $data["noticeUrl"] = $info['return_url'];  //商户通知地址
	    $data["partnerID"] = $info['merchant_id']; //商户ID号
	    $data["remark"] = $info['pay_id'];
	    $data["charset"] = "1";    //字符编码 1为UTF-8
	    $data["signType"] = "2";   //签名方式 1为RSA 2为MD5
	    
	    $data["signMsg"] = $this->sign($data, $this->key);
	    
	    return $data;
	}
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_sign($info)
	{
	    $sign_bak = $info['signMsg'];
	    unset($info['signMsg']);
	    $sign = $this->sign($info, $this->key);
	    if($sign_bak == $sign)
	    {
	        return true;
	    }else{
	        return false;
	    }
	}
}
?>