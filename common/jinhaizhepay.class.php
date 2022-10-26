<?php
	/**
     * 金海哲的 网银 微信 支付 使用RSA-S 加密
     * */ 
class jinhaizhepay {
	
    private $merchant_code = "zz1492162497353HMTE22TDS8";//商户ID
    private $pri_key = "-----BEGIN RSA PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAImeOkq4fkx32CtF
YiEoEhW8WsU+YYwGzzHn6rTPxBQ4jDdgyLePago2iXQBIxZRWlH+vp1x8ixvjQCb
L+6K9FaJ0MUEWb3pqjTyw88GEuBojbGghripswMc2h+TdeuFrzjOm69whNtZoCp3
syYEHKH6GArFQ4l26ZD2ZO51de1HAgMBAAECgYEAhlEt+dH6S25JSWqN7WirxhUx
zwQkt0PKJJ6D4PhMG6RZjo9jOG28hL1YCSY29chvTEpEB224fZBe4fGVpdBvyzIW
NnvipCv06z0ym6pXoOaLkwFnwqFlMRq+wyMidjZRH4Nm/wNxiqGcuKKC5UASsMLz
vFrw5HV39x2nRhcbCAkCQQDuRfUHdIqegaW7KxFMnIDc7pDLh7DBfAfL++gYugNv
ZcgtQ1TJPYxqx8kKKoawLO4pDliHsp4qtThmGgxd4rHlAkEAk9s895JoJ/Z7t9HC
xoIjgP1o3AdsX5e/pcNTlEcPsY/8upCceL8znAO4I2tFcmSqwtL5HM+p8coRylvU
V9JfuwJADUnbg4HtZS+n1YtuWI515VxsuN0lb3UCXk77P73ICUNs6ZFSvjJvjVj9
DlMW1eZ7ldCWAWNlzM30ikUzoVzOPQJAApcIjl2mLW0H5cRq4QdOj+fgelo278W8
ua6ePC6ye63GA46c7xCKWuVFyHkMkmBpDVicvIB2vaGIhj7tjUxGGQJAagi6UFh2
V6IVHlOlx7yJZikRqPwMOlHokRXS0QAD9uIZOvDc6dAWsEOzBZrdMH1s/o7y8cBX
jPlqEsjCna0jhQ==
-----END RSA PRIVATE KEY-----";//商家私钥
    
    private $pub_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCL4nMv6qK7Lt1MzfK20LrVd/0g
0pXIvV281sT16s4xIWEg/Hfv0su0MHdbTobZfHcziyO/xdmItCzkcJOIIskuC3Qu
kNrWnt7kf1wZ1OmIMWAcS5s9wnMd0QcpDpcyfZfJvlZgFDtgJtApXvCBBVIEX65W
1FnmlZ7wccO3Ca+J8QIDAQAB
-----END PUBLIC KEY-----";//公钥
    private $api_url = "http://zf.szjhzxxkj.com/ownPay/pay";
    
	/**
	 * 组建数组 对数据整合 包含加密
	 * $info array 
	 * return $data array
	 **/
	public function build_data($info)
	{
	    $bankco = $info['bank_code'];
	    $billno = $info['billno'];
	    $amount = $info['amount'];
	    $return_url = $info['return_url'];
	    $key = openssl_get_privatekey($this->pri_key);
		
	    $data['merchantNo'] = $merchantNo = $this->merchant_code;
	    $data['requestNo'] = $requestNo = $billno;
	    $data['amount'] = $amount = $amount*100;
		if($bankco == "6001" || $bankco == "6003"){
			$payMethod = $bankco;
		}else{
			$payMethod = "6002";
		}
	    $data['payMethod'] = $payMethod;//6002 网银支付 6001 微信扫码 6003 支付宝扫码 6005 微信WAP
	    $data['pageUrl'] = $pageUrl = "";
	    $data['backUrl'] = $backUrl = $return_url;
	    $data['payDate'] = $payDate = time();
	    $data['agencyCode'] = $agencyCode = 0;
	    $data['remark1'] = $remark1 = "";
	    $data['remark2'] = $remark2 = "";
	    $data['remark3'] = $remark3 = "";
		if($payMethod == "6002"){
			$data['bankType'] = $bankco;
			$data['bankAccountType'] = 11;
		}
	    $signStr = $data['merchantNo']."|".$data['requestNo']."|".$data['amount']."|".$data['pageUrl']."|".$data['backUrl']."|".$data['payDate']."|".$data['agencyCode']."|".$data['remark1']."|".$data['remark2']."|".$data['remark3'];

	    openssl_sign($signStr,$sign_info,$key);
	    $sign = base64_encode($sign_info);
		//数据签名加密 结束
		$data['signature'] = $sign;
	    return $data;
	}
	
	public function get_code_url($info)
	{
	    $qrurl = "";
	    $data = $this->build_data($info);
	    $result = $this->_curlSubmit($this->api_url,$data);
	    $status = $this->verify_sign($result);
	    if($status === true){
	        $qrurl = $result['backQrCodeUrl'];
	    }else{
	        $qrurl = "error";
	    }
	    return $qrurl;
	}
	
	
	
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_sign($info,$sign="")
	{
	    if(isset($info['sign'])){
	        $dinpaySign = $info['sign'];
	        unset($info['sign']);
	        $signStr = json_encode($info,JSON_UNESCAPED_SLASHES);
	    }elseif($sign != ""){
	        $dinpaySign = $sign;
	        $signStr = $info;
	    }
	    
		
		$key = openssl_get_publickey($this->pub_key);
		$dinpaySign = base64_decode($dinpaySign);

        //数据签名加密 结束
        $status = openssl_verify($signStr,$dinpaySign,$key);
        if($status == 1)
        {
            return true;
        }else{
            return false;
        }
	}
	
	private function _curlSubmit($url, $data = "", $method = 'POST', $timeOut = 10)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut); // 超时设置
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 不验证证书下同
	    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return json_decode($result, true);
	}
}
?>