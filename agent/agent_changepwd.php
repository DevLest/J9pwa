<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
include_once("../base.fun.php");
include_once("../sessionstate.php");
include_once("../phprpc/phprpc_client.php");

if( !isset($_SESSION['agent_name']) || $_SESSION['agent_name'] == '')
{
	echo "<script>alert('登录超时，请重新登录。');location.href='/agent/agent_login.html'</script>";
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
	<p class="header_title"><span>代理系统->修改登录密码</span></p> 
	<!--合作伙伴-->
    <div class="mb_table_box">
    
		<form class="ag_input_box" id="pwd_form">
		<!--<form action="../core.php?act=agent_changepwd" method="post" id="form1" name="form1">
		  <table style="margin-left:100px" class="table1">
			<tbody>
				<tr>
					<td class="text_l">旧密码：</td>
					<td class="text_c" style="width: 186px"><input name="oldpwd" id="oldpwd" type="password"></td>
					<td class="text_r" style="color:#000;">请输入密码</td>
				</tr>
				<tr>
					<td class="text_l">新密码：</td>
					<td class="text_c" style="width: 186px"><input name="newpwd" maxlength="18" id="newpwd" type="password"></td>
					<td class="text_r" style="color:#000">请输入新密码，6-18位。</td>
				</tr>
						<tr>
					<td class="text_l">确认密码：</td>
					<td class="text_c" style="width: 186px"><input name="repwd" maxlength="18" id="repwd" type="password"></td>
					<td class="text_r" ><span id="repwd_ck" style="color:#f00;"></span></td>
				</tr>
						<tr>
					<td class="text_l">验证码：</td>
					<td class="text_c" style="width: 186px"><input name="checkcode" id="checkcode" maxlength="4" type="text" style="width:80px;vertical-align:middle;"><img src="../functions/yanzheng.php" id="yanzheng" style="height:24px;vertical-align:middle;"></td>
					<td class="text_r" style="color:#ffdb8a;"> </td>
				</tr>			
				<tr>
					<td colspan="3" style="color:#ffdb8a; text-align:left;" height="109">
					<input class="input5" style=" margin-left:210px" name="" value="确认修改" type="submit"></td>
					</tr>
			</tbody>
		</table>
		<input name="haveprefix" value="0" type="hidden">
		</form>-->
			<div class="inp_box">
				<label>旧密码</label>
				<input class="input textin80" type="password" placeholder="请输入旧密码，必填" name="oldpwd" id="oldpwd" maxlength="12">
			</div>
			<div class="inp_box">
				<label>新密码</label>
				<input class="input textin80" type="password" placeholder="6-12位英文字母或数字，必填" name="newpwd" id="newpwd" maxlength="12">
			</div>
			<div class="inp_box">
				<label>确认密码</label>
				<input class="input textin80" type="password" placeholder="请再次填写新密码，必填" name="repwd" id="repwd" maxlength="12">
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
	$('.main-nav >a:nth-child(6)').addClass('cur');
	$('#yanzheng').css('cursor', 'pointer').click(function(e) {
		$(this).attr('src', '../functions/yanzheng.php?r='+Math.random());
	});
	/*$('#repwd').blur(function(e) {
		var zhi = $(this).val();
		var newpwd = $("#newpwd").val();
		if(zhi != newpwd)
		{
			$('#repwd_ck').html('密码不一致，请重新输入。');
		}else if(zhi.length  < 8 )
		{
			$('#repwd_ck').html('密码位数为8-18位。');
		}else{
			$('#repwd_ck').html('');
		}
	});*/
});

			function check_form(){
				var oldpwd = $('#oldpwd').val();
				var agent_pwd = $('#newpwd').val();
				var agent_pwd2 = $('#repwd').val();
				var checkcode = $('#checkcode').val();
				var reg_pwd = new RegExp("^[A-Za-z0-9]{6,12}$"); 
				
				if(!reg_pwd.test(oldpwd)){
					check_error('oldpwd','密码只能为6-12位英文字母或数字');
					return;
				}else{
					check_ok('oldpwd');
				}
				
				if(!reg_pwd.test(agent_pwd)){
					check_error('newpwd','密码只能为6-12位英文字母或数字');
					return;
				}else{
					check_ok('newpwd');
				}
				
				if(agent_pwd2 != agent_pwd){
					check_error('repwd','密码不一致，请重新输入。');
					return;
				}else{
					check_ok('repwd');
				}
				
				if(checkcode == ''){
					check_error('checkcode','请填写验证码');
					return;
				}else{
					check_ok('checkcode');
				}
				zdwaiting();
				$.ajax({
					url:"/core.php?act=agent_changepwd",
					data:$("#pwd_form").serialize(),
					type:"POST",
					dataType:"json",
					success:function(d){
						if(d.status == 1){
							zdalert('系统提示',d.info,function(){
								window.location.href = "agent_logout.php";
							});
						}else{
							$('#yanzheng').click();
							zdalert('系统提示',d.info);
						}
						
					}
				});
			}
			function check_error(id,str){
				$('#'+id).addClass('error');
				zdalert('错误提示',str);
			}
			function check_ok(id){
				$('#'+id).removeClass('error');
			}
</script>
</body>
</html>