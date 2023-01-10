<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
include_once("../client/phprpc_client.php");
define('FOREGROUND_URL','http://j9adminaxy235.32sun.com/phprpc/foreground.php');
	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
	echo json_encode(array('status'=>0,'info'=>"校验不成功"));
		exit();
	}
	$client = new PHPRPC_Client(FOREGROUND_URL);
       // $account = $_POST['account'];
        $account = $_POST['username_email'];
	$result = $client->spagent_info($account);
	$result = unserialize($result);
   //     print_r($account);exit;
	$temp = explode("=",$result['agent_url']);
	$agent_id = $temp[1];
        
                      if(isset($agent_id )){
            echo json_encode(array('status'=>1,'info'=>"https://xx/register?referral=$agent_id"));
            
            
        }else{
            
             echo json_encode(array('status'=>'0','info'=>'查询失败'));
        }
        exit;
       // print_r("https://www.u2d8899.com/agent.php?act=$agent_id");exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-control" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<title>怡宝_代理中心</title>
	<link type="text/css" href="../css/css.css" rel="stylesheet" />
	<link type="text/css" href="../css/index.css" rel="stylesheet" />
	<link type="text/css" href="../css/agent.css" rel="stylesheet" />
	<link type="text/css" href="../css/animate.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico"/>
	<script src="../js/jquery.js" type="text/javascript"></script>
	<script src="../js/pub.js" type="text/javascript"></script>
	<!--<script src="../js/dwin.js" type="text/javascript"></script>-->
</head>
<body style="background:#eee">
	<?php include 'header.php'?>
	<div class="main_table">
	<!--头部
        <div class="header">
		 <embed class="fl" src="../images/logo.swf" width="380" height="77" quality="high" wmode="transparent">
        	<!--<div class="top" id="top">
            </div>-->
		<!--导航
    		<div class="nav agent_nav" id="nav">
				<ul>
					<li>
						<a href="../agent/agent_center.php">会员列表</a>
					</li>
					<li>
						<a href="../agent/agent_link.php">推广链接</a>
					</li>
					<li>
						<a href="../agent/agent_changepwd.php">修改密码</a>
					</li>
					<li>
						<a href="../agent/agent_info.php">银行资料</a>
					</li>
					<li>
						<a href="../agent/agent_commission.php">佣金报表</a>
					</li>
					<li style="color:#000; padding-left:22px; font-weight:bold;">
						帐号：<span style="color:#f00"><?php echo $result['agent_name']?></span>
					</li>
					<li style="color:#000; padding-left:22px; font-weight:bold;">
						剩余额度：<span style="color:#f00"><?php echo $result['balance']?></span>
					</li>
					<li><a href="../agent/agent_logout.php">安全退出</a>
					</li>
				</ul>
			</div>
		</div>-->
	<!--合作伙伴-->
	<p class="header_title"><span>代理系统->推广链接</span></p> 
    <div class="mb_table_box">
		<p class="header_title" style="color:red;text-align:center;">代理链接： </p>
		<p class="header_title" style="color:red;text-align:center;"><?php echo "https://www.yuebet100.com/agent.php?act=".$agent_id;?></p>
	<p class="header_title" style="color:red;text-align:center;">代理二维码： </p>
	<p style="color:red;text-align:center">请使用浏览器扫描二维码（如UC，QQ等浏览器）</p>
           <p style="text-align:center"><img id="qrImg" src="jhzwechatqrcode.php?url=https://www.yuebet100.com/agent.php?act=<?php echo $agent_id?>"  height="250" width="250"/></p>
		   
    </div>
	<!--<div class="updown">
		<div class="updown_box">
			<img src="../images/qrcode.png" />
		</div>
	</div>-->
	<script>
		$('.main-nav >a:nth-child(3)').addClass('cur');
	</script>
</body>
</html>