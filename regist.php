<?php
header("Content-type: text/html; charset=utf-8");
if(!isset($_SESSION))
{
	session_start();
}
$_SESSION['verify_code'] = mt_rand(1000,9999);

?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>怡宝娱乐-注册</title>
    <meta name="keywords" content="怡宝娱乐娱乐手机版"/>
    <meta name="description" content="怡宝娱乐娱乐手机版,水果机,电子游戏、老虎机、slot、优惠的在线平台"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="newCss/style.css">
    <link rel="stylesheet" href="newCss/reg.css">
    <link rel="stylesheet" href="newFonts/css/font-awesome.min.css">
    <link rel="stylesheet" href="newCss/Pop_up.css">
	<link href="newJs/mobdate/css/common.css" rel="stylesheet" type="text/css" />
	<script src="newJs/jquery_2.1.3.js"></script>
	<style>
	.ttw_rig{top:50%;left:50%;margin:-10px 0px 0px -20px}
	.input_line >div{float:left;}
	.input_line >div:first-child{width:60%;}
	.input_line >div:last-child{width: 40%;text-indent: 0px;text-align: center;height: 47px;margin-top: 5px;background: #eee;line-height: 47px;font-size: 14px;border: 1px solid #949191;box-sizing: border-box;}
	</style>
</head>

<body>
<div class="md-modal md-effect-13" id="modal-13">
    <div class="md-content">
		<div class="fx">
			<div class="Pop_up_title">系统异常</div>
			<div class="md-close">我知道了</div>
		</div>
    </div>
</div>
<div class="md-overlay"></div>
<div id="overlay" class="cover">
  <div class="wrapper">
    <div class="menu">
     <!-- <a href="login.php"><i class="fa fa-chevron-left"></i></a>-->
        <div class="top_title ttw_rig">注册</div>
    </div>
	<form id="reg_form">
      <div class="reg_box">
			<input type="hidden" name="submit_type" value="regist">
			<input type="hidden" name="verify_code" value="<?php echo $_SESSION['verify_code']?>">
        <div class="enter_nane">
			<input class="enter" type="text" id="account" onkeyup="value=value.replace(/[^\d\w]/g,'')" maxlength="10" name="account" placeholder="帐号: 6-10个字符,由大小写字母和数字构成" autofocus>
		</div>
			<span id="account_ck"></span>
        <div class="enter_nane">
          <input class="enter" id="password" name="password" type="password" placeholder="密码: 6-12个字符,由大小写字母和数字构成" maxlength="12">
			<span id="password_ck"></span>
		</div>
        <div class="enter_nane">
          <input class="enter" id="passwordok" name="passwordok" type="password" placeholder="确认密码" maxlength="12">
			<span id="passwordok_ck"></span>
		</div>
         <div class="enter_nane">
          <input class="enter" id="realName" name="realName" placeholder="真实姓名,需与提款卡号姓名一致，否则不能提款。" maxlength="10">
			<span id="realName_ck"></span>
		</div>
         <div class="sex-box">
          <input type="radio" name="sex" value="0" class="reg2-radio" id="manType" checked="checked"><label for="manType">男</label>
          <input type="radio" name="sex" value="1" class="reg2-radio" id="femalType"><label for="femalType">女</label>
         </div>
         <div class="enter_nane">
          <input class="enter" type="text" id="birthday" name="birthday" placeholder="请选择生日日期" readonly>
			<span id="birthday_ck"></span>
		 </div>
         
         <div class="enter_nane">
          <input class="enter" type="text" id="email" name="email" placeholder="邮箱">
			<span id="email_ck"></span>
		 </div> 
         <div class="enter_nane">
          <input class="enter" type="text" id="qq" name="qq" placeholder="请输入QQ号" maxlength="15">
			<span id="qq_ck"></span>
		 </div> 
         <div class="enter_nane">
          <input class="enter" type="text" id="agentName" name="agentName" maxlength="16" placeholder="请输入推荐代码，没有可不填">
		 </div>
		 <div class="enter_nane">
			<input class="enter" type="text" id="telephone" name="telephone" placeholder="手机号码" maxlength="15">
			<span id="telephone_ck"></span>
		 </div>
		<div class="input_line fx">
			<div>
				<div class="enter_nane">
					<input class="enter" type="text" id="captcha" name="captcha" maxlength="6" placeholder="填写手机验证码">
					<span id="captcha_ck"></span>
				</div>
			</div>
			<div class="input" onclick="getMsgForR()">发送手机验证码</div>
		</div>		 
		 <!--<div class="input-div fx" style="padding-bottom:50px;">
			<img src="captcha/yanzheng.php" class="yanzhengma" style="padding-bottom:-5px;width: 48%;float: left;height: 47px;margin-top:5px;"/>
			<div class="enter_nane" style="width: 50%;float: right;">
				<input type="text" class="enter yanzheng" id="captcha" name="captcha" maxlength="6" placeholder="请输入验证码" >
				<span id="captcha_ck"></span>
			</div>
         </div>-->
        <div class="squaredFour">
          <input type="checkbox" id="squaredFour" />
          <label for="squaredFour"></label>
          <div class="word_reg">同意本站
            <a href="javascript:" onclick="window.open('yb/info_article.html','','width=750 height=500 resizable=yes scrollbars=yes top=20 left=400')"><span style="font-size: 12px; color: #acacac;">《怡宝协议条款》</span></a>
          </div>
        </div>

        <a href="javascript:" class="reg" id="md-trigger" data-modal="modal-13" onclick="check_form();"><div class="btn_ui" style="margin-bottom: 300px;">注册</div></a>
      </div>
	 </form>
  </div>
</div>

<div id="datePlugin"></div>
<script src="newJs/Pop_up.js"></script>
<script src="newJs/jquery.mixitup.min.js"></script>

<script src="newJs/mobdate/js/date.js" type="text/javascript" ></script>
<script src="newJs/mobdate/js/iscroll.js" type="text/javascript" ></script>
<script src="newJs/public.js"></script>
<script>
$(function(){
	$('#birthday').date();
	$('.yanzhengma').css('cursor', 'pointer').click(function(e) {
				$(this).attr('src', 'captcha/yanzheng.php?t='+Math.random());
			});
});
$("#account").blur(function(){
			$('#account_ck').removeClass("tips_ok");
			var username = $(this).val();
			var reg = new RegExp("^[A-Za-z0-9]{6,10}$"); 
			var reg1 =username.substr(0,1);
			if(!reg.test(username))
			{
				check_error("account_ck","*游戏账号应为6-10个字符");
				return;
			}else if(reg1 == 0){
				check_error("account_ck","*游戏账号不能以数字0开头");
				return;
			}else{
				$.get("ajax_check.php",{type:"check_member_info",input:username,field:"account"},
					
					function(data){
						if(data == 1)
						{
							$('#account_ck').html("<i class='fa fa-check' style='color:green'></i>游戏账号验证通过");
							$('#account_ck').removeClass("tips_error").addClass("tips_ok");
						}else{
							check_error("account_ck","*游戏账号已被注册");
						}
						//alert(data);
				});
			}
		});
$("#password").blur(function(){
			var reg = /^[0-9a-zA-Z]{6,12}$/
			var str = document.getElementById("password").value;
			if(reg.test(str))
			{
				check_ok("password_ck");
			}else{
				check_error("password_ck","*密码只能为6-12个数字、英文");
			}
		}); 
$("#passwordok").blur(function(){
			var pwd = $("#password").val();
			var pwd2 = $(this).val();
			if(pwd != pwd2 || pwd == '')
			{
				check_error("passwordok_ck","*两次输入的密码不一致");
			}else{
				check_ok("passwordok_ck");
			}
		});
//验证真实姓名
		$("#realName").blur(function(){
			var uname = $("#realName").val(); 
			var reg = /^[\u4e00-\u9fa5]{2,8}$/
			if(uname != ""){
				if(!reg.test(uname)){
					check_error("realName_ck","*请填写真实姓名");
					
				}else{
					check_ok("realName_ck");
				}
			}else{
				check_error("realName_ck","*必填，必须与提款卡号姓名一致，否则不能提款。");
				
			}
		});
$("#telephone").blur(function(){
			var phone = $("#telephone").val();
			var reg =/^((\d{3,4}-)*\d{7,8}(-\d{3,4})*|1[1-9]\d{9})$/; 
			if(!reg.test(phone))
			{
				check_error("telephone_ck","*电话格式例如：0755-33647401或11位手机号码!");
			}else{
				$.get("ajax_check.php",{type:"check_member_info",input:phone,field:"telephone"},
					function(data){
						if(data == 1)
						{
							$('#telephone_ck').html("<i class='fa fa-check' style='color:green'></i>手机号码验证通过");
							$('#telephone_ck').removeClass("tips_error").addClass("tips_ok");
						}else{
							
							check_error("telephone_ck","*手机号已被注册");
						}
				});
			}
		});
$("#email").blur(function(){
			$('#email_ck').removeClass("tips_ok");
			var email = $("#email").val();
			var reg =/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.(?:com|cn)$/; 
			if(!reg.test(email))
			{
				check_error("email_ck","*邮箱格式例如：xxxx@xxxx.com");
			}else{
				$.get("ajax_check.php",{type:"check_member_info",input:email,field:"email"},
					function(data){
						if(data == 1)
						{
							$('#email_ck').html("<i class='fa fa-check' style='color:green'></i>邮箱地址验证通过");
							$('#email_ck').removeClass("tips_error").addClass("tips_ok");
						}else{
							
							check_error("email_ck","*邮箱地址已被注册");
						}
				});
			}
		});		
$("#qq").blur(function(){
	var qq = $("#qq").val();
	var reg = new RegExp("^[0-9]{6,15}$"); 
	
	if(qq == ''){
		check_error("qq_ck","*QQ不能为空");
	}else if(!reg.test(qq)){
		check_error("qq_ck","*QQ格式有误");
	}else{
		$.get("ajax_check.php",{type:"check_member_info",input:qq,field:"qq"},
			function(data){
				if(data == 1)
				{
					$('#qq_ck').html("<i class='fa fa-check' style='color:green'></i>QQ验证通过");
					$('#qq_ck').removeClass("tips_error").addClass("tips_ok");
				}else{
					check_error("email_ck","*QQ已被注册");
				}
			});
		
		//check_ok("qq_ck");
	}
});	

		
		
function check_form(){
	var account = $("#account").val();
	var password = $("#password").val();
	var passwordok = $("#passwordok").val();
	var realName = $("#realName").val();
	var telephone = $("#telephone").val();
	
	var email = $("#email").val();
	var qq = $("#qq").val();
	var captcha = $("#captcha").val();
	var valid = true;
	var mesg = '';
	$(".reg").hide();
	

	if(valid){
		if(!check_reg1()){
			valid = false;
			mesg = '填写信息有误，请修改后再提交';
			$(".reg").show();
		}
	}
	
	if(valid){
		if(!check_reg2()){
			valid = false;
			mesg = '信息未填写完整';
			$(".reg").show();
		}
	}
	if(valid){
		if(captcha.toString().length != 6){
			valid = false;
			mesg = '验证码错误';
			$(".reg").show();
		}
	}
	
	if(valid){
		var check=$("#squaredFour").prop("checked");
		if(check==true){
			valid = false;
			mesg = '请同意本站的协议条款';
			$(".reg").show();
		}
	}
	
	if(valid){
		$(".Pop_up_title").html('<div class="loding"><i class="fa fa-spinner fa-spin"></i>系统处理中，请稍后</div>');
		$(".md-close").css("display","none");
		
		$.ajax({
			url: "center.php",
			data: $("#reg_form").serialize(),
			type: "POST",
			dataType:"json",
			success: function( d ) {
				if(d.status == 1){
					$(".Pop_up_title").html('恭喜您注册成功！怡宝平台提供<span style="color:#0aa2e7">微信转账</span>、<span style="color:#0aa2e7">支付宝转账</span>与<span style="color:#0aa2e7">网银转账</span>等多种存款方式，如有疑问请点击右上角小图标联系客服为您解答！');
					$(".md-close").css("display","block");
					$(".reg").show();
					$(".md-close").click(function(){
						//window.location = 'index.php';
						yueboJsObj.registerSucess(account,d.info,'0.00');
					});
				}else{
					$(".Pop_up_title").html(d.info);
					$(".md-close").css("display","block");
					$(".reg").show();
					$(".md-close").click(function(){
						window.location = 'regist.php';
					});
				}
			},
		});
	}else{
		$(".Pop_up_title").html(mesg);
		$(".md-close").css("display","block");
	}
}
function check_reg1(){
		if($("#realName_ck").hasClass('tips_error'))
		{
			return false;
		}
		if($("#account_ck").hasClass('tips_error'))
		{
			return false;
		}
		if($("#password_ck").hasClass('tips_error'))
		{
			return false;
		}
		if($("#passwordok_ck").hasClass('tips_error'))
		{
			return false;
		}
		if($("#telephone_ck").hasClass('tips_error'))
		{
			return false;
		}
		if($("#email_ck").hasClass('tips_error'))
		{
			return false;
		}
		return true;
}
function check_reg2(){
	if($("#realName").val() == '')
		{
			return false;
		}
		if($("#qq").val() == '')
		{
			return false;
		}
		if($("#password").val() == '')
		{
			return false;
		}
		if($("#passworddok").val() == '')
		{
			return false;
		}
		if($("#telephone").val() == '')
		{
			return false;
		}
		if($("#email").val() == '')
		{
			return false;
		}
		return true;
}
		function getMsgForR(){
			var telephone = $("#telephone").val();
			if($("#telephone_ck").hasClass('tips_error') || telephone == '')
			{
				zdalert('系统提示','请将手机号码填写正确');
			}else{
				zdwaiting();
				$.ajax({
					url:'ajax_check.php',
					type:"POST",
					data:{
						type:"get_duanxin",
						phone:telephone
					},
					dataType:'json',
					timeout: 10000,
					success: function(data){
						if(data == 1){
							zdalert('系统提示','短信已发送，请查收！');
						}else{
							zdalert('系统提示','发送失败，请稍后再试');
						}
						
					}
				});
			}
		}
</script>

</body>
</html>
