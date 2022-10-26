<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
include_once("../base.fun.php");
include_once("../sessionstate.php");
include_once("../phprpc/phprpc_client.php");

if( !isset($_SESSION['agent_name']) || $_SESSION['agent_name'] == '')
{
	echo "<script>alert('登录超时，请重新登录。');location.href='/agent.html'</script>";
	exit();
}
	$client = new PHPRPC_Client(FOREGROUND_URL);
	$account = $_SESSION['agent_name'];
	$result = $client->spagent_info($account);
	$result = unserialize($result);
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
	<script src="../js/jquery.js" type="text/javascript"></script>
	<script src="../js/pub.js" type="text/javascript"></script>
	<!--<script src="../js/dwin.js" type="text/javascript"></script>-->
	<style>
		
	</style>
</head>
<body style="background:#eee;">
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
	<p class="header_title"><span>代理系统->设定银行资料</span></p> 
    <div class="mb_table_box">
		<!--<div>
			<form action="../core.php?act=agent_info" method="post" id="agent_info" name="agent_info">
			<table style="margin-left:180px" class="table1">
				<tbody>
					<tr>
						<td class="text_l FF0">帐户姓名：</td>
						<td class="text_c" style="width: 186px;color:white "><input name="real_name" id="real_name" value="<?php echo $result['real_name']?>" maxlength="16" type="text"></td>
						<td class="text_r FF0">真实姓名</td>
					</tr>
					<tr>
						<td class="text_l">银行类型：</td>
						<td class="text_c" style="width: 186px">
						   <select id="bank_type"  name="bank_type" >
							 <option value="工商银行">工商银行</option>
							 <option value="建设银行">建设银行</option>
							 <option value="农业银行">农业银行</option>
							 <option value="中国银行">中国银行</option>
							 <option value="招商银行">招商银行</option>
							 <option value="交通银行">交通银行</option>
						   </select>	
						</td>
						<td class="text_r" style="color:#ffdb8a;">&nbsp;</td>
					</tr>
					<tr>
						<td class="text_l FF0">银行账号：</td>
						<td class="text_c" style="width: 186px">
						<input  name="bank_no" id="bank_no" value="<?php echo $result['bank_no']?>" maxlength="32" type="text"></td>
						<td class="text_r FF0" style="color:#ffdb8a;">
						</td>
					</tr>			
					<tr>
						<td class="text_l">银行地址：</td>
						<td class="text_c" style="width: 186px"><input  name="bank_addr" id="bank_addr" value="<?php echo $result['bank_addr']?>" maxlength="16" type="text"></td>
						<td class="text_r" style="color:#ffdb8a;"></td>
					</tr>
					<tr>
						<td class="text_l FF0">验证码：</td>
						<td class="text_c" style="width: 186px">
						<input name="checkcode" id="checkcode" maxlength="4" type="text" style="width:80px;vertical-align:middle;"><img src="../functions/yanzheng.php" id="yanzheng" style="height:24px;vertical-align:middle;"></td>
						<td class="text_r FF0" style="color:#ffdb8a;">
						
						</td>
					</tr>			
					<tr>
						<td colspan="3" style="color:#ffdb8a; text-align:left;" height="109">
						<input class="input5" style=" margin-left:210px" name="dosubmit" value="确认修改" type="submit"></td>
						</tr>
					
				</tbody>
			</table>
			</form>
		</div>-->	
		<form class="ag_input_box" id="bank_info_form">
			<div class="inp_box">
				<label>帐户姓名</label>
				<input class="input textin80" placeholder="" name="real_name" id="real_name" value="<?php echo $result['real_name']?>" maxlength="16">
			</div>
			<select id="bank_type"  name="bank_type" >
				<option value="">请选择银行</option>
				<option value="工商银行">工商银行</option>
				<option value="建设银行">建设银行</option>
				<option value="农业银行">农业银行</option>
				<option value="中国银行">中国银行</option>
				<option value="招商银行">招商银行</option>
				<option value="交通银行">交通银行</option>
			</select>
			<div class="inp_box">
				<label>银行账号</label>
				<input class="input textin80" placeholder="" value="<?php echo $result['bank_no']?>" name="bank_no" id="bank_no" maxlength="32" onkeyup="value=value.replace(/[^\d]/g,'')">
			</div>
			<div class="inp_box">
				<label>银行地址</label>
				<input class="input textin80" placeholder="" name="bank_addr" value="<?php echo $result['bank_addr']?>" id="bank_addr" maxlength="16">
			</div>
			<div class="inp_box">
				<label>验证码</label>
				<input class="input textin80" placeholder="" name="checkcode" id="checkcode" maxlength="4" style="width: 70%;" onkeyup="value=value.replace(/[^\d]/g,'')">
				<img src="../functions/yanzheng.php" id="yanzheng" style="height:24px;vertical-align:middle;">
			</div>
			<div class="btn100 blue" onclick="check_form()">确认修改</div>
		</form>
	</div>
	</div>
	<!--<div class="updown">
		<div class="updown_box">
		<img src="../images/qrcode.png" />
		</div>
	</div>-->
<script>
$(function() {
	$('.main-nav >a:nth-child(5)').addClass('cur');
   $('#yanzheng').css('cursor', 'pointer').click(function(e) {
		$(this).attr('src', '../functions/yanzheng.php?r='+Math.random());
	});
	$('#bank_type').val('<?php echo $result['bank_type']?>');
});

	function check_form(){
		var real_name = $('#real_name').val();
		var bank_type = $('#bank_type').val();
		var bank_no = $('#bank_no').val();
		var bank_addr = $('#bank_addr').val();
		var checkcode = $('#checkcode').val();
		
		if(real_name == '' || bank_type == '' || bank_no == '' || bank_addr == '' || checkcode == ''){
			zdalert('系统提示','请将信息填写完整');
		}else{
			zdwaiting();
			$.ajax({
				url:"/core.php?act=agent_info",
				data:$("#bank_info_form").serialize(),
				type:"POST",
				dataType:"json",
				success:function(d){
					if(d.status == 1){
						zdalert('系统提示',d.info,function(){
							location.reload();
						});
					}else{
						$('#yanzheng').click();
						zdalert('错误提示',d.info);
					}
				}
			});
		}
	}
</script>
</body>
</html>