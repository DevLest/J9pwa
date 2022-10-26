<?php
	/**
     * 智付的 网银 微信 支付 使用RSA-S 加密
     * */ 
class zhifupay {
	
    private $merchant_code = "2000299877";//商户ID
    private $pri_key = "-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDSDbLAYAH0uGmgO0Yj5H/gbO18BCVGzmXym3ibDj+9PVn89oO4
RzMLfhAnAdhk19oNhOkk0YFKhNIyyv7Mqv3+3zyiQ+JLCtrMkKStDpa+J0BvgJ5n
dQiPMF1pk4p/P5tZR4xlCX1mobRlY2EWQfs2yy/roYVln2Hzl+vhHzu45wIDAQAB
AoGAbS6jmC0PNzXX6WqF9cgi0/OqqYi6G4jTIvy+/tG0Pdgy+zLjmpifprybBruF
0D0GYXqReQrRCnvpim9La/UBWlV5hjJDYU12SL2t5oezgsbmNLQTx8V+FuyL3MYC
jZdG2vPGNmuHMuh555c1WrNmFM5FgIpRlrN4AZIOghcfdvkCQQDphV0pi3WXFSMY
pIn2/fw33WcvkNp6S+qRnoGjLjr+LdFL7Mdj8QWAXphWfsYT/yN1l9y54OVPOyz+
5+0/IWGTAkEA5kYHN9TXgUmKv124aV4bR0SVoojw/NO9vPUzJoLpd7Sm312eAmRf
oTClZm0b94ow5VjSDo/hOgeFOU+Nic2v3QJAZcdut3DFK7hWt6LfTvucNm0VUDH+
R1dXQWiocAryQ16M3l6w+OpPz4drNifDBWwqdFJgLdPLniDYl7FWQjG2aQJBAN4W
Ngnz3jDUI9vy4NPGrlpq3EJY/stoz/r5JW8EHXWVJsgQuz8lXVHR4APMQYfZoNTh
eB4RSDC2ZhfjEOgSF4UCQCz3Ck96aSdOvT4b361In/X+gFUZTV1uAnh6Nl9BGy0B
YEKVV1icGLGQfl7X0k7Dp96oX8ReZi0+7kovkd4AEhM=
-----END RSA PRIVATE KEY-----";//商家私钥
    
    private $pub_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCbKMoXzpyFB8KN4DN1zgYSRu0Y
cEV4boAkkwn1sYTYdCwUzfWBGmeR/ZSyetdttqZgGMWVDKe/ShWAglNh+PsWiw1E
qn7mSV6eOYxw4NimNgTbyvtNrNRc78i3FKnvxqgUgA+QK91LaAgVTFh6AssW8lDU
UzIsMCtTWOlxWN7mKwIDAQAB
-----END PUBLIC KEY-----";//智付公钥
    
    
	/**
	 * 组建数组 对数据整合 包含加密
	 * $info array 
	 * return $data array
	 **/
	public function build_data($info)
	{
	    $bankco = $info['bankco'];
	    $billno = $info['billno'];
	    $amount = $info['amount'];
	    $key = openssl_get_privatekey($this->pri_key);
	    $data['merchant_code'] = $merchant_code = $this->merchant_code;
	    $data['service_type'] = $service_type = "direct_pay";
	    $data['interface_version'] = $interface_version = "V3.0";
	    if($bankco == "weixin")
	    {
	        $data['pay_type'] = $pay_type = $bankco;
	        $data['bank_code'] = $bank_code = "";
	    }else{
	        $data['pay_type'] = $pay_type = "";
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
	    $data['extra_return_param'] = $extra_return_param = $info['pay_id'];
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
}
?>