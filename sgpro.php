<?php
header("Content-type: text/html; charset=utf-8");
if(!isset($_SESSION))
{
	session_start();
}
include_once ("core.class.php");
if(!isset($_SESSION['account']) || $_SESSION['account'] != $_GET['username'])
{
	
	if(isset($_GET['auth']) && $_GET['auth'] != ""){
		$auth = $_GET['auth'];
		$api_key = 'jr71ybappws';
		$account = strtolower($_GET['username']);
		$time = substr(time(),0,-2);
		$auth_check = md5($account.$time.$api_key);
		
		$time1 = substr(time(),0,-3);
		$auth_check1 = md5($account.$time1.$api_key);
		if($auth_check == $auth || $auth_check1 == $auth)
		{
			$core = new core();
			$re = $core->get_memberinfo($account);
			$_SESSION['account'] = $re['account'];
			$_SESSION['balance'] = $re['balance'];
			$_SESSION['member_name'] = $re['realName'];
			$_SESSION['member_type'] = $re['memberType'];
			setcookie("account", $_SESSION['account'], time()+86400);
			setcookie("member_name", urlencode($_SESSION['member_name']), time()+86400);
		}else{
			echo "验证已过期";
			error_log("special"."#".date('YmdHis')."#".$_GET['username']."#".$account."#".$auth."#".$auth_check."#".$auth_check1."\r\n", 3,'common/log/autherror.log');
			exit();   
		}
	}else{
		exit();   
	}
	
}
?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>怡宝娱乐-特殊活动</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" href="newCss/style.css">
	<link rel="stylesheet" href="newCss/animate.css">
	<script src="newJs/jquery_2.1.3.js"></script> 
	
	<style>
	
	</style>
</head>
<body style="background:#fff;">
<div class="content">
	<div class="content_main_sub animated fadeInUp">
		<div class="sgSummerPro">
			<div class="ssp_top fx">
				<div class="ssp_top_left">
					<div>
						<div></div>
						<div>烈日炎炎好礼不断，夏日存款礼金特别呈现<br/>（礼金仅限投注SG老虎机游戏）</div>
						<div></div>
						<div id="next_bonus"></div>
						<div class="rank_box">
							<div class="rankColumnar">
								<span class="filled">
									<div>10</div>
									<div>18</div>
									<div id="sgpro_btn_0">未满足</div>
								</span>
								<span class="filled">
									<div>1000</div>
									<div>58</div>
									<div id="sgpro_btn_1">未满足</div>
								</span>
								<span class="filled">
									<div>3000</div>
									<div>128</div>
									<div id="sgpro_btn_2">未满足</div>
								</span>
								<span class="filled">
									<div>10000</div>
									<div>288</div>
									<div id="sgpro_btn_3">未满足</div>
								</span>
							</div>
							<div  class="rankColumnar_shadow"></div>
							<div  class="rankColumnar_shadow_filled"></div>
						</div>
					</div>
				</div>
				<div class="ssp_top_right">
					<div>
						<div></div>
						<div>骄阳似火夏日可畏，酷暑返水助你大浪淘金<br/>（礼金仅限投注SG老虎机游戏）</div>
						<div></div>
						<div id="apply_gid1902">加载中</div>
					</div>
				</div>
			</div>
			<div class="ssp_bottom">
				<div class="ssp_b_title fx">
					<div class="act">存款礼金领取记录</div>
					<div>双重返水发放记录</div>
				</div>
				<div class="ssp_b_table">
					<table id="sg_table_1"><tr><td colspan="2">加载中...</td></tr></table>
					<table style="display:none;" id="sg_table_2"><tr><td colspan="2">加载中...</td></tr></table>
				</div>
			</div>
			<div class="ssp_tip">*怡宝国际有权延长、缩短、终止或者修改活动，恕不另行通知。</div>
		</div>
	</div>
</div>

<script src="newJs/public.js"></script>
<script>
	$(function(){
		$('.ssp_b_title >div').click(function(){
			$(this).siblings().removeClass('act');
			$(this).addClass('act');
			var index = $(this).index();
			$('.ssp_b_table >table').hide();
			$('.ssp_b_table').find('table').eq(index).fadeIn();
		});
		get_sgpro1_status();//获取sg存款优惠的领取状态
		get_sgcode_status();//获取sg返水优惠的领取状态
	});
	//领取sg存款优惠
	var applyBtn = true;
	function apply_sgpro1(type)
	{
		if(applyBtn == true){
			zdwaiting();
			applyBtn = false;
			$.ajax({
				url:"action.php?code="+Math.random(),
				type:"POST",
				data:{
					act:'apply_sgxrkw_pro',
					type:type
				},
				dataType:'json',
				timeout: 10000,
				success: function(data){
					if(data.status == true){
						$('#sgpro_btn_'+type).attr('class','').html('已领').attr('onclick','');
						get_sgpro1_record();
					}
					zdalert('活动提示',data.message);
					applyBtn = true;
				}
			})
		}
	}
	//领取SG返水优惠
	var applyBtn_2 = false;
	function apply_sgbet_bonus()
	{
		if(applyBtn_2 == true){
			zdwaiting();
			applyBtn_2 = false;
			$.ajax({
				url:"action.php?code="+Math.random(),
				type:"POST",
				data:{
					act:'apply_bet_bonus'
				},
				dataType:'json',
				timeout: 10000,
				success: function(data){
					if(data.status == true){
						get_sgcode_status();
					}
					zdalert('活动提示',data.message);
					applyBtn_2 = true;
				}
			})
		}
	}
	//领取SG存款优惠记录
	function get_sgpro1_record()
	{
		$.ajax({
			url:"action.php?code="+Math.random(),
			type:"POST",
			data:{
				act:'get_sgpro_record',
				type:'2'
			},
			dataType:'json',
			timeout: 10000,
			success: function(data){
				if(data.status == true){
					$('#sg_table_1').html(data.data);
				}
			}
		})
	}
	//获取sg存款优惠的领取状态
	function get_sgpro1_status()
	{
		zdwaiting();
		applyBtn = false;
		$.ajax({
			url:"action.php?code="+Math.random(),
			type:"POST",
			data:{
				act:'get_sgpro_status'
			},
			dataType:'json',
			timeout: 10000,
			success: function(data){
				$('#mb_box').remove();
				if(data.status == true){
					var bonus_status = data.data.bonus_status;
					var total_deposit = data.data.total_deposit;
					filled_rank(total_deposit);
					for(var i=0;i<4;i++){
						switch (bonus_status[i]){
							case 1:
							applyBtn = true;
							$('#sgpro_btn_'+i).attr('class','act').html('领取').attr('onclick','apply_sgpro1('+i+')');
							break;
							case 2:
							$('#sgpro_btn_'+i).attr('class','').html('已领').attr('onclick','');
							break;
						}
					}
					get_sgpro1_record();
				}else{
					zdalert('活动提示',data.message);
				}
			}
		})
	}
	//填充存款进度条
	function filled_rank(val){
		var money = 0;
		var width = 0;
		if(val < 10){
			width = val;
			money = parseFloat(10 - val).toFixed(2);
		}else if(val >=10 && val <1000){
			money = parseFloat(1000 - val).toFixed(2);
			val = val -10;
			width = 10 + val/990 * 20;
		}else if(val >=1000 && val <3000){
			money = parseFloat(3000 - val).toFixed(2);
			val = val -1000;
			width = 30 + val/2000 * 30;
		}else if(val >=3000 && val <10000){
			money = parseFloat(10000 - val).toFixed(2);
			val = val -3000;
			width = 60 + val/7000 * 35;
		}else{
			width = 100;
		}
		
		if(money == 0){
			$('#next_bonus').html('今日存款总额为'+val+'元');
		}else{
			$('#next_bonus').html('离下一级优惠还差'+money+'元');
		}
		$('.rankColumnar_shadow_filled').animate({'width':width+'%'},2000);
	}
	//获取返水优惠是否可领
	function get_sgcode_status(){
		applyBtn_2 = false;
		$.getJSON("ajax_data.php",{type: "washcodeself_list"},function(data){
			if(data.status != 0){
				if(data.info.gid1902 != ''){
					if(data.info.gid1902 >0){
						applyBtn_2 = true;
						$('#apply_gid1902').addClass('apply_bonus_week').attr('onclick','apply_sgbet_bonus()').html('申请领取');
					}else{
						$('#apply_gid1902').removeClass('apply_bonus_week').attr('onclick','').html('暂无礼金可领');
					}
				}
			}else{
				$('#apply_gid1902').html('暂无礼金可领');
			}
			august_gift_list();
		});
	}
	//sg夏日可畏记录 2018-5-4
	function august_gift_list(){	
		$.getJSON("ajax_data.php",{type: "august_gift_list"},function(data){
			$('#sg_table_2').html(data.str);
		});
	}
	
	
</script>
</body>
</html>