<?php
/**
*乐富支付 的相关处理
*/

class lefupay
{
    var $_key;				//安全校验码
    var $signType;			//签名类型


	/**构造函数
	*从配置文件及入口文件中初始化变量
	*$parameter 需要签名的参数数组
	*$key 安全校验码
	*$signType 签名类型
    */

	function lefupay($key,$signType='') 
	{
        $this->_key  		= $key;
        $this->signType	    = $signType;
    }
	/**
	*后台通知 数据验证
	*
	*/
	public function notify_verify($data)
	{

		if(empty($data)) 
		{							//判断POST来的数组是否为空
			return false;
		}
		else
		{
			$mysign  = self::build_mysign($data);   //生成签名结果
			//判断veryfy_result是否为ture，生成的签名结果mysign与获得的签名结果sign是否一致
			//$veryfy_result的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if ($mysign == $data["sign"]) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}
	}
	/**
	*对数组进行加密签名
	*/
	public function build_mysign($data) 
	{
		$array = self::para_filter($data);	//对所有POST返回的参数去空
		$sort_array = self::arg_sort($array);	
		$prestr = self::create_linkstring($sort_array);     	//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $prestr.$this->_key;							//把拼接后的字符串再与安全校验码直接连接起来
		$mysgin = self::sign($prestr,$this->signType);			    //把最终的字符串签名，获得签名结果
		return $mysgin;
	}	

	/********************************************************************************/

	/**
		*把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		*$array 需要拼接的数组
		*return 拼接完成以后的字符串
	*/
	private function create_linkstring($array) 
	{
		$arg  = "";
		while (list ($key, $val) = each ($array)) 
		{
			$arg.=$key."=".$val."&";
		}
		$arg = substr($arg,0,count($arg)-2);		     //去掉最后一个&字符
		return $arg;
	}

	/********************************************************************************/

	/**
		*除去数组中的空值和签名参数
		*$parameter 签名参数组
		*return 去掉空值与签名参数后的新签名参数组
	 */
	private function para_filter($parameter) 
	{
		$para = array();
		while (list ($key, $val) = each ($parameter)) 
		{
			if($key == "sign" || $val == "")
			{
				continue;
			}
			else
			{
				$para[$key] = $parameter[$key];
			}
		}
		return $para;
	}
	/********************************************************************************/

	/**对数组排序
		*$array 排序前的数组
		*return 排序后的数组
	 */
	private function arg_sort($array) 
	{
		ksort($array);
		reset($array);
		return $array;
	}

	/********************************************************************************/

	/**签名字符串
		*$prestr 需要签名的字符串
		*return 签名结果
	 */
	private function sign($prestr,$signType) 
	{
		$sign='';
		if($signType == 'MD5') 
		{
			$sign =  strtoupper(md5($prestr));
		}
		else 
		{
			die("暂不支持".$sign_type."类型的签名方式");
		}
		return $sign;
	}
}
?>