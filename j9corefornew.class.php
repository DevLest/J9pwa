<?php
/**
 *核心处理类，对网站各种方法进行归纳整理 
 * 2016年11月17日
 * Shawn
 */
header("Content-type: text/html; charset=utf-8");
include_once("client/phprpc_client.php");

class corefornew {
    private $phprpc_url = "";
    /**
     * 构造函数 
     * @param class_type 用于区分调用API的类型 mysql 和 mysqli 
     */
	function __construct($class_type="mysql")
	{
		if($class_type == "mysqli"){
		    $this->phprpc_url = "http://j9adminaxy235.32sun.com/phprpc/cashierformysqli.php";
		}else{
		    $this->phprpc_url = "http://j9adminaxy235.32sun.com/phprpc/cashier.php";
		    //$this->phprpc_url = "http://j9adminaxy235.32sun.com/phprpc/cashierformysqli.php";
		}
	}
	
	
	
	
	/**
	 * 获取 多线路在线支付的 详细内容
	 * @param pay_id 该支付线路在数据库中的ID号
	 * 返回 info 线路信息
	 */
	public function monlinpay_detail($pay_id)
	{
	    $client = new PHPRPC_Client($this->phprpc_url);
	    $info = $client->monlinepay_detail($pay_id);
	    return $info;
	}
	/**
	 * 在线支付信息确认
	 * @param bill_no 系统订单号
	 * @param order_no 支付平台单号
	 * @param amount 支付的金额
	 * @param array ratio手续费比例默认为0，微信支付宝为1.5%,点卡为15%或其它 
	 * 返回 status 
	 */		
	public function onlinepay_sure($bill_no,$order_no,$amount,$arr="")
	{
		$client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierformysqli.php");
		$status = $client->onlines6pay_sure($bill_no,$order_no,$amount,$arr);
		return $status;
	}
	

	
	public function test()
	{
		$client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierformysqli1.php");
		$status = $client->check_iplist132();
		return $status;
	}
	
		public function check_iplist()
	{
		$client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierformysqli1.php");
		$status = $client->check_iplist();
		return $status;
	}
	
	
	
	
	
	/**
	 *获取客户的IP和地址。
	 *@param type 为0 则只获取ip 为1 则获取ip和地址
	 *返回 info 数组
	 */
	public function ip_information($type="0")
	{
	    $ipinfo = array();
	    if (@$_SERVER["HTTP_X_FORWARDED_FOR"]){
	        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	    }else if (@$_SERVER["HTTP_CLIENT_IP"]){
	        $ip = $_SERVER["HTTP_CLIENT_IP"];
	    }else if (@$_SERVER["REMOTE_ADDR"]){
	        $ip = $_SERVER["REMOTE_ADDR"];
	    }else if (@getenv("HTTP_X_FORWARDED_FOR")){
	        $ip = getenv("HTTP_X_FORWARDED_FOR");
	    }else if (@getenv("HTTP_CLIENT_IP")){
	        $ip = getenv("HTTP_CLIENT_IP");
	    }else if (@getenv("REMOTE_ADDR")){
	        $ip = getenv("REMOTE_ADDR");
	    }else{
	        $ip = "Unknown";
	    }
	    $temp = explode(",",$ip);
	    $ip = $temp[0];
	    $ipinfo['ip'] = $ip;
	    if($type == 1)
	    {
	        include ("ip_area.class.php");
	        $iparea = new ip_area();
	        $ipinfo['address'] = $iparea->data_full($ip);
	    }
	    return $ipinfo;
	}
}
?>
