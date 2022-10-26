<?php
/**
*易宝支付 的相关处理
*/
class yibaopay{
	
	public $p1_MerId;  //商户号
	public $merchantKey;  //密钥
	
	//构造函数
	function yibaopay($merid,$key)
	{
		$this->p1_MerId = $merid;
		$this->merchantKey = $key;
	}
	
	//签名函数生成签名串
	public function getReqHmacString($data)
	{
		
					
		//进行签名处理，一定按照文档中标明的签名顺序进行
		$sbOld = "";
		//加入业务类型
		$sbOld = $sbOld.$data['p0_Cmd'];
		//加入商户编号
		$sbOld = $sbOld.$this->p1_MerId;
		//加入商户订单号
		$sbOld = $sbOld.$data['p2_Order'];     
		//加入支付金额
		$sbOld = $sbOld.$data['p3_Amt'];
		//加入交易币种
		$sbOld = $sbOld.$data['p4_Cur'];
		//加入商品名称
		$sbOld = $sbOld.$data['p5_Pid'];
		//加入商品分类
		$sbOld = $sbOld.$data['p6_Pcat'];
		//加入商品描述
		$sbOld = $sbOld.$data['p7_Pdesc'];
		//加入商户接收支付成功数据的地址
		$sbOld = $sbOld.$data['p8_Url'];
		//加入送货地址标识
		$sbOld = $sbOld.$data['p9_SAF'];
		//加入商户扩展信息
		$sbOld = $sbOld.$data['pa_MP'];
		//加入支付通道编码
		$sbOld = $sbOld.$data['pd_FrpId'];
		//加入是否需要应答机制
		$sbOld = $sbOld.$data['pr_NeedResponse'];
		
		return $this->HmacMd5($sbOld,$this->merchantKey); 
	}
	
	//对返回的参数进行前面加密验证
	public function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
	{
		//取得加密前的字符串
		$sbOld = "";
		//加入商家ID
		$sbOld = $sbOld.$this->p1_MerId;
		//加入消息类型
		$sbOld = $sbOld.$r0_Cmd;
		//加入业务返回码
		$sbOld = $sbOld.$r1_Code;
		//加入交易ID
		$sbOld = $sbOld.$r2_TrxId;
		//加入交易金额
		$sbOld = $sbOld.$r3_Amt;
		//加入货币单位
		$sbOld = $sbOld.$r4_Cur;
		//加入产品Id
		$sbOld = $sbOld.$r5_Pid;
		//加入订单ID
		$sbOld = $sbOld.$r6_Order;
		//加入用户ID
		$sbOld = $sbOld.$r7_Uid;
		//加入商家扩展信息
		$sbOld = $sbOld.$r8_MP;
		//加入交易结果返回类型
		$sbOld = $sbOld.$r9_BType;

		return $this->HmacMd5($sbOld,$this->merchantKey);

	}
	//验证签名是否正确
	public function CheckHmac($data)
	{
		$hmac_check = $this->getCallbackHmacString($data['r0_Cmd'],$data['r1_Code'],$data['r2_TrxId'],$data['r3_Amt'],$data['r4_Cur'],$data['r5_Pid'],$data['r6_Order'],$data['r7_Uid'],$data['r8_MP'],$data['r9_BType']);
		if($data['hmac'] == $hmac_check)
		{
			return true;
		}
		else{
			return false;
		}
	}
	
	//签名加密方法
	public function HmacMd5($data,$key)
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing(NOTE: Hacked means written)

		//需要配置环境支持iconv，否则中文参数不能正常处理
		$key = iconv("GB2312","UTF-8",$key);
		$data = iconv("GB2312","UTF-8",$data);

		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;

		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}
}
?> 