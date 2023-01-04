<?php
	/**
     * 华仁 网银 微信 支付 C8线路
     * */ 
class huarenpay {
	
    private $merchant_code = "110086";//商户ID
	private $md5_key = "sUpC4j0";//MD5密钥
	private $url = "http://api.hr-pay.com/PayInterface.aspx";
    private $pri_key = "-----BEGIN RSA PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALNPxTGt/jJkIObZ
SoLrOonq1D9hVTc/DcBfXlAKsyKyBQzXXMWcC4xglRmXucS3zGMGEH6qEdQdnZVf
LUYpPly/rf3ys9UMudPhaSquvRsTflaPa1XfskfMGXIKdrhsWHNNtYmfugZRty5K
5caQdVAzinBzkT42FZ1sVROIudsHAgMBAAECgYAq/W6RPR5rNW9f+WuwXLZBt6/g
bDUSlEb3PsYtWYteP6EPVZjz9bgdFVDZ/HL483oIqb0NqTeyC9GqrNF9d0jwata2
mj8Yr0wvsw5bsvcHmoQdV4ClP7M+SToRN7XvsuXvXQuhvNfvIojf+nFgOUSdwgE3
dq/wmfA7iZRO7F4MgQJBALjVqic7BlK5eG3KRFsmRgg4/6AcIEXAr+Y1gkSmUgqo
n1PuvqN6jfPXZmHNp6s0GuS7ptZF3J/T4ive0yD8GYECQQD4Wbmy02u7xXFSGwWe
oUIp/tXBeE3EGfNj91WEN4bEIjcZ5dWLH97n23zP4Bp8adV0n6lIc5QxuZkB6qF3
bGiHAkBPaJxdlmI1EDHJGWkcr+jOh45rPyouTYZEpSB+VeZRZfhtmtiUa4apCBWY
Vz59szkKpAQdco5CrEkzB9w1A5oBAkBe6Tt0wiPMEPrr2/Pb2dkFX6SlWaqupRRF
QgLmkqnoTwMr3JAtqTJw/YrHjufQYEn5VgNF8xXRoxJ8jwTCNSYFAkEAiOTut8u6
RZ/eDXwV9nMiJ+BrKQsajrMYqDQi/j5/Tf0vuLk+Azs0tfmzumaB08txRf+lmn+F
fG5NfNvK0w4Tgw==
-----END RSA PRIVATE KEY-----";//商家私钥
    
    private $pub_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC5AR73eG6GWSfr3/3RvBen0Dyb
haqLehwnblhjEA4d3Wolcn4+Mp6J+GNenuAhF3+hKNAYJHLat7m7/Y79grWdOzv6
LQKPH2OkA3oTw5mRcjPq71K88azGdjG17i5lsg5qkzMWvqY4Jl6oicQ4rjYhAdNc
Auoou5TpVsv6Fo3CYQIDAQAB
-----END PUBLIC KEY-----";//平台公钥
    
	
	/**
	 * 计算md5签名
	 **/
	public function sign($data,$key) {
    	$string = "";
    	foreach($data as $k=>$v)
    	{
    		$string .= $k.$v;
    	}
    	$string .= $key;
    	// echo "##".$string."##";
    	return md5($string);
	}
	/**
	 * 组建数组 对数据整合
	 * $info array  bill_no,amount,bank_code,return_url
	 * $url string 提交地址
	 * return $form_string 表单字符串
	 **/
	public function build_bank_data($info,$url = "")
	{
	    $billno = $info['billno'];
	    $amount = $info['amount'];  
	    $bankco = $info['bank_code'];
	    $return_url = $info['return_url'];
		
		$data = array();
		//按顺序
		$data['v_pagecode'] = $v_pagecode = "1001";//协议包 网银为1001
		$data['v_mid'] = $v_mid = $this->merchant_code;//商户编号
		$data['v_oid'] = $v_oid = $billno;//商户订单编号
		$data['v_rcvname'] = $v_rcvname = $this->merchant_code;
		$data['v_rcvaddr'] = $v_rcvaddr = $this->merchant_code;
		$data['v_rcvtel'] = $v_rcvtel = $this->merchant_code;
		$data['v_goodsname'] = $v_goodsname = "zxzfcz";
		$data['v_goodsdescription'] = $v_goodsdescription = "zxzfcz";
		$data['v_rcvpost'] = $v_rcvpost  = $this->merchant_code;
		$data['v_qq'] = $v_qq = $this->merchant_code;
		$data['v_amount'] = $v_amount = $amount;
		$data['v_ymd'] = $v_ymd = date("Ymd");
		$data['v_orderstatus'] = $v_orderstatus = 1;
		$data['v_ordername'] = $v_ordername  = $this->merchant_code;
		$data['v_bankno'] = $v_bankno = $bankco;
		$data['v_moneytype'] = $v_moneytype = 0;
		$data['v_url'] = $v_url = $return_url;
		$data['v_noticeurl'] = $v_noticeurl = "http://pay.116tyc.net/yb/show.php";
		$data['v_md5info'] = $v_md5info = $this->sign($data,$this->md5_key);
		
		$request_data = array();
        $request_data['data'] = urlencode($this->enCrypt('['.json_encode($data).']'));
        $request_data['mid'] = $this->merchant_code;
		
        return $request_data;
	}
	public function build_wechat_data($info,$url = "")
	{
	    $billno = $info['billno'];
	    $amount = $info['amount'];  
	    $bankco = $info['bank_code'];
	    $return_url = $info['return_url'];
		
		$data = array();
		//按顺序
		$data['v_pagecode'] = $v_pagecode = "1003";//协议包 网银为1001 微信为1003
		$data['v_mid'] = $v_mid = $this->merchant_code;//商户编号
		$data['v_oid'] = $v_oid = $billno;//商户订单编号
		$data['v_rcvname'] = $v_rcvname = $this->merchant_code;
		$data['v_rcvaddr'] = $v_rcvaddr = $this->merchant_code;
		$data['v_rcvtel'] = $v_rcvtel = $this->merchant_code;
		$data['v_goodsname'] = $v_goodsname = "zxzfcz";
		$data['v_goodsdescription'] = $v_goodsdescription = "zxzfcz";
		$data['v_rcvpost'] = $v_rcvpost  = $this->merchant_code;
		$data['v_qq'] = $v_qq = $this->merchant_code;
		$data['v_amount'] = $v_amount = $amount;
		$data['v_ymd'] = $v_ymd = date("Ymd");
		$data['v_orderstatus'] = $v_orderstatus = 1;
		$data['v_ordername'] = $v_ordername  = $this->merchant_code;
		$data['v_app'] = $v_app = $bankco;
		$data['v_moneytype'] = $v_moneytype = 0;
		$data['v_url'] = $v_url = $return_url;
		$data['v_noticeurl'] = $v_noticeurl = "http://pay.116tyc.net/yb/show.php";
		$data['v_md5info'] = $v_md5info = $this->sign($data,$this->md5_key);
		
		$enstring = (json_encode($data));
		
		$request_data = array();
        $request_data['data'] = urlencode($this->enCrypt('['.$enstring.']'));
        $request_data['mid'] = $this->merchant_code;
		
        return $request_data;
	}
	
	/**
	 * 组建数组 对数据整合
	 * $info array  bill_no,amount,bank_code,return_url
	 * $url string 提交地址
	 * return $form_string 表单字符串
	 **/
	public function build_data($info,$url = "")
	{
	    $bankco = $info['bank_code'];
	    if($bankco == "wechat"){
	        $result = $this->build_wechat_data($info);
	        $url = $this->url."?mid=".$result['mid']."&data=".$result['data'];
	        header("location:$url");
	    }elseif($bankco == "alipay"){
	        $result = $this->build_alipay_data($info);
	        $url = $this->url."?mid=".$result['mid']."&data=".$result['data'];
	        header("location:$url");
	    }else{
	        $result = $this->build_bank_data($info);
	        $url = $this->url."?mid=".$result['mid']."&data=".$result['data'];
	        header("location:$url");
	    }
	}
	/**
	 * 验证签名信息
	 * $info array
	 * return true or false
	 **/
	public function verify_sign($info)
	{
	    $sign = $info['v_sign'];
	    unset($info['v_sign']);
	    
	    $sign_str = '';
	    foreach ($info as $k => $v){
	        $sign_str .= $k.$v;
	    }
	    if($sign === md5($sign_str.$this->md5_key))
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
	
	
	
	 /**
     * RSA加密
     * @param $plaintext
     * @return bool|string
     */
    public function enCrypt($plaintext){
        $split_data = str_split($plaintext, 117);    //The second arguments' value must be <= 117 if your key length is 1024 bit

        $encrypt_array = array();
        foreach ($split_data as $part) {
            $result = openssl_public_encrypt($part, $encrypt_string, $this->pub_key);
            if(!$result){
                return false;
            }

            $encrypt_array[]= base64_encode($encrypt_string);
        }

        return join('@',$encrypt_array);
    }

    /**
     * RSA解密
     * @param $ciphertext
     * @return bool|string
     */
    public function deCrypt($ciphertext)
    {
        $split_array = explode('@', $ciphertext); //At here using @ split ciphertext as array
		// var_dump($split_array);
        $decrypt_string = '';
        foreach ($split_array as $part) {
            $result = openssl_private_decrypt(base64_decode($part), $decrypt_data, $this->pri_key);
			// var_dump($result);
            if (!$result) {
				// var_dump($part);
                return false;
            }

            $decrypt_string .= $decrypt_data;
        }
		// echo "###".($decrypt_string);
        return $decrypt_string;
    }
    
    public function curl_submit($url)
    {
        $curlHandle = curl_init();
        curl_setopt( $curlHandle , CURLOPT_URL, $url );
        curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curlHandle , CURLOPT_TIMEOUT, 30 );
        $result = curl_exec( $curlHandle );
        curl_close( $curlHandle );
        return $result;
    }
}
?>