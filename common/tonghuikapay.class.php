<?php
	/**
     * 通汇卡网银 微信 支付
     * */ 
class tonghuikapay {
	
	/**
	 * 计算md5签名
	 **/	
	public function sign($data,$key) {
		ksort($data);
    	$string = "";
    	//var_dump($data);
    	foreach($data as $k=>$v)
    	{
    		if($v != "")
    		{
    			$string .= $k."=".$v."&";
    		}
    	}
    	$string .= "key=".$key;
    	//echo $string;
    	return md5($string);
	}
}
?>