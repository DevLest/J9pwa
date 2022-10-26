<?php
header("Content-type: text/html; charset=utf-8");
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}
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
    <meta name="keywords" content="怡宝娱乐城"/>
    <meta name="description" content="怡宝娱乐城、电子游戏、老虎机、slot、优惠的在线平台"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="newCss/style.css">
	<link rel="stylesheet" href="newCss/index.css">
	<link rel="stylesheet" href="newCss/special.css?version0901">
    <link rel="stylesheet" href="newFonts/css/font-awesome.min.css">
    <link rel="stylesheet" href="newCss/Pop_up.css">
	<script src="newJs/jquery_2.1.3.js"></script> 
	<link rel="stylesheet" href="newCss/imgSwitch.css"/>
	<script src="newJs/imgSwitch.js"></script>
	
<style>
</style>
</head>
<body>
<div class="md-modal md-effect-13" id="modal-13">
    <div class="md-content">
		<div class="fx">
			<div class="Pop_up_title">系统异常</div>
			<div class="md-jugde md-certain">确定</div>
			<div class="md-jugde md-close-fx">取消</div>
			<div class="md-close">我知道了</div>
		</div>
    </div>
</div>
<div class="all_cover"></div>

<div class="md-overlay"></div>
<div id="overlay" class="cover">
	<!--<div class="main">
		<?php //include 'navigationns.php';?>
	</div>-->
	<div class="wrapper">
		<?php //include 'top.php'?>
		<div>
		<div class="deposit_box">
			<div class="user-head child_menu" style="background: #fff;">
				<a href="javascript:;" class="dpTitle act">新春到，元宵闹</a>
			</div>
			<div class="pay_content augustBouns" style="display:none;">
			</div>
			<div class="pay_content"  id="promotion2" style="display:block;color: #dc953c;font-size: 14px;">
				<div class="eatOver">
					<div class="eatOver_y">吃光啦</div>
				</div>
				<div class="special_top">
					<div class="deposit_check">当日存款金额：<span id="number1206">---</span>
						<a href="javascript:get_total_deposit('number1206');">
							<i class="fa fa-refresh" id="tpos" style="color: #ffe8cb;margin-left: 5px;"></i>
						</a>
					</div>
					<span class="tocheck" onclick="get_total_deposit('number1206')">查询</span>
				</div>
				<div class="content newEgg">
					<div class="egg">
						<ul class="egglist">
							<p class="hammer_gold" id="hammer"></p>
							<p class="resulttip" id="resulttip"><b id="result"></b></p>
							<div id="slide" class="slide" class="index-slide" alt="star">
							<!-- 轮播图片数量可自行增减 -->
								<div class="img">
									<li class="silver" id="silver" onclick="javascript:eggClick(0,-1);"><sup class="sup"></sup></li>
								</div>
								<div class="img">
									<li class="gold" id="gold" onclick="javascript:eggClick(1,-1);"><sup class="sup"></sup></li>
								</div>
								<div class="img">
									<li class="pgold" id="pgold" onclick="javascript:eggClick(2,-1);"><sup class="sup"></sup></li>
								</div>
							</div>
							
							
						</ul>
					</div>
					<p style="text-align:center;padding-bottom:19px;">汤圆礼金说明：汤圆礼金仅能投注所有的老虎机和捕鱼游戏。</p>
					<table class="transfer_list" id="chickenoregg_promotion_record" border="0" style="width:96%;">
						<tr>
							<th colspan="5">当日吃汤圆记录</th>
						</tr>
						<tr><th>汤圆</th><th>礼金</th><th>奖品类型</th><th>总存款</th><th>吃汤圆时间</th></tr>
						<td colspan="5">暂无记录！</td>
					</table>
				</div>
				<div class="up_stage" id="stage_gold" style="display:none;"></div>
				<div class="up_stage" id="stage_pgold" style="display:none;"></div>
				<div class="up_stage" id="stage_topegg" style="display:none;"></div>
				<div id="coverbg"></div>
			</div>
			<div class="pay_content" style="display:none;">
			</div>
		<div class="cover-in"></div>	
		</div><!--deposit_box-->
		</div>
	</div>
	
</div>
<div class="bonuse zoomInDown" id="popBonus">
	<div class="gongxi">恭喜,您的汤圆礼金为</div>
	<div class="gongxi"><span id="bouns_num"></span></div>
	<p class="bon-tips">中奖礼金,已存入您的主账户!</p>
	<div class="bon-ok">我知道了</div>
</div>
<div class="bonus_top zoomInDown" id="popBonus_top">
	<div class="gongxi">恭喜,您获得了兑换礼金</div>
	<div class="gongxi"><span id="bouns_num_top">999</span>元</div>
	<p class="bon-tips">立即换取礼金，请联系24小时客服申请换取</p>
	<div class="bon-ok_top" onclick="bonClick()">马上换取</div>
	<div class="ex_ico"></div>
</div>
<script src="newJs/header.js"></script> 
<!-- <script src="js/menu_open.js"></script> --> 
<script src="newJs/Pop_up.js"></script> 
<script src="newJs/public.js"></script> 
<script>
	$(function(){
		check_egg_status();//砸蛋
		get_total_deposit('number1206');//砸蛋
		
		$('#popClo,.bon-ok').click(function(){
			$('#popBonus').hide();
			$('.cover').removeClass('blur-in');
			$('.cover-in').hide();			
			$('.up_stage').fadeOut(1000);
			$('#coverbg').fadeOut(1000);
			
			$("#hammer").attr("class","hammer_"+$('.img3 li').attr("id"));
			var winwidth = document.documentElement.clientWidth;
			var posL = winwidth*0.63;
			var posX = winwidth*0.085;
			$("#hammer").show().css('left', posL);
			$("#hammer").show().css('top', posX);
			chickenoregg_promotion_record();
			$('.cover-in').hide();
		});
		
	
	});
	var numflag = 1;
	function bonClick(){
		if(numflag ==1){
			cs();
			numflag = 2;
			$('.bon-ok_top').text("我知道了");
		}else{
			$('#popBonus_top').hide();
			$('.cover').removeClass('blur-in'); 
			$('.cover-in').hide();
			$('.up_stage').fadeOut(1000);
			$('#coverbg').fadeOut(1000);

			$("#hammer").attr("class","hammer_"+$('.img3 li').attr("id"));
			var winwidth = document.documentElement.clientWidth;
			var posL = winwidth*0.63;
			var posX = winwidth*0.085;
			$("#hammer").show().css('left', posL);
			$("#hammer").show().css('top', posX);
			chickenoregg_promotion_record();
			numflag = 1;
			$('.cover-in').hide();
		}
	}
	//2017砸蛋
			function get_total_deposit(id)
    		{
    			$("#"+id).html("<i class='fa fa-spinner fa-spin' aria-hidden='true'></i>");
    			$.get("ajax_data.php",{type: "get_total_deposit"},function(data){
    					$("#"+id).html(data);
    			});
    		}
			function chickenoregg_promotion_record()
			{
				$.getJSON("ajax_data.php",{type: "chickenoregg_promotion_record",promotion_name:"chickenoregg"},function(data){
					$("#chickenoregg_promotion_record").html(data);
				});
			}
			function check_egg_status()
			{
				$('.cover-in').show();
				$.getJSON("ajax_data.php",{type: "check_egg_status_new"},function(data){
						$('.cover-in').hide();
						if(Object.prototype.toString.call(data) === '[object Array]')
						{
							var a=0;
							for(var name in data)
							{
								$("#"+data[name]).addClass("curr"+data[name]);
								$("#"+data[name]).find("sup").show(); //金花四溅
								console.log(a);
								a++;
							}
							if(a == 3){
								$('.eatOver').height($('#promotion2').height());
								$('.eatOver').show();
							}
							chickenoregg_promotion_record();
						}else{
							return false;
						}
				});
			}
			function eggClick(name,Is_up) {
				var name_or = name;
				var eggName = new Array('silver','gold','pgold','topegg');
				name = eggName[name];
				var _this = $("#"+name);
				var addHeight = 0;
				if(Is_up == -1){
					if(!_this.parent().hasClass('img3')){
						return false;
					}
				}else{
					_this = $("#up_egg_"+name);
				}
				if(_this.hasClass("curr"+name)){
						return false;
				}
				$('.cover-in').show();//加一个档层
					confirm_z(0,"<i class='fa fa-spinner fa-spin' aria-hidden='true'></i>请稍后...");
					$.getJSON("ajax_data.php",{type:"get_prize_new",name:name_or,Is_up:Is_up},function(res){
					//_this.unbind('click');
					//return false;
					var winwidth = document.documentElement.clientWidth;
					if(Is_up > -1){
						addHeight = winwidth*0.141;//暂时注释
						$('.lightBg').fadeOut(800);
						$('#stage_'+name+' .crownIco').hide();
					}			
					/*$("#hammer").animate({
									"top": 0,
									"left": winwidth*0.59//330   560
								},300);*/
					$('#hammer').addClass('Rotation');
					$(".md-modal").removeClass("md-show");
					$(".cover").removeClass("blur-in");
					$("#hammer").animate({
						"top": winwidth*0.330-addHeight,//180,
						"left": winwidth*0.464
						},1000,function(){
							if(res.msg==1)
							{
								_this.addClass("curr"+name); //蛋碎效果
								_this.find("sup").show(); //金花四溅
								//$(".hammer").hide();
								$("#hammer").animate({
									"top": winwidth*0.085+addHeight,//40,
									"left": winwidth*0.63//318
								},1000);
								
								if(Is_up > -1){
									//$(".hammer").hide();
									$('#up_egg_'+name).removeClass("eggpop");
									$('#up_egg_'+name).fadeOut();
								}
								$('.cover-in').show();
								//$('.cover').addClass('blur-in');
								/*if(name == 'topegg'){
									
									var topContent = '';
									if(res.prize >0){
										topContent = '<div class="gongxi">恭喜,您得到了兑换礼金</div><div class="gongxi"><span id="bouns_num_top">'+res.prize+'元</span></div><p class="bon-tips">立即换取礼金，请联系24小时客服申请换取</p><div class="bon-ok_top" onclick="bonClick()">马上换取</div><div class="ex_ico"></div>';
										//$("#bouns_num_top").html(res.prize);
									}else if(res.prize == '-1'){
										numflag = 2;
										topContent = '<div class="gongxi">恭喜,您得到了</div><div class="gongxi"><span id="bouns_num_top" style="color: #FF5722;font-size:30px;">双倍返水卡</span></div><p class="bon-tips">今日之内所有平台的返水金额明日可发放双倍！</p><div class="bon-ok_top" onclick="bonClick()">我知道了</div><div class="ex_ico1"></div>';
									}else if(res.prize == '-2'){
										numflag = 2;
										topContent = '<div class="gongxi">恭喜,您得到了</div><div class="gongxi"><span id="bouns_num_top" style="color: #FF5722;font-size:30px;">双倍积分卡</span></div><p class="bon-tips">即时起，24小时内所有存款获得双倍积分!</p><div class="bon-ok_top" onclick="bonClick()">我知道了</div><div class="ex_ico2"></div>';
									}else if(res.prize == '-3'){
										numflag = 2;
										topContent = '<div class="gongxi">恭喜,您得到了</div><div class="gongxi"><span id="bouns_num_top" style="color: #FF5722;font-size:30px;">双倍转运卡</span></div><p class="bon-tips">即时起，24小时内可获取双倍转运金!</p><div class="bon-ok_top" onclick="bonClick()">我知道了</div><div class="ex_ico3"></div>';
									}
									$('#popBonus_top').html(topContent);
									$('#popBonus_top').show();
								}else{
									$("#bouns_num").html(res.prize+"元");
									$('#popBonus').show();
								}*/
								if(res.prize == '-1'){
									numflag = 2;
									topContent = '<div class="gongxi">恭喜,您得到了</div><div class="gongxi"><span id="bouns_num_top" style="color: #FF5722;font-size:30px;">双倍返水卡</span></div><p class="bon-tips">今日之内所有平台的返水金额明日可发放双倍！</p><div class="bon-ok_top" onclick="bonClick()">我知道了</div><div class="ex_ico1"></div>';
									$('#popBonus_top').html(topContent);
									$('#popBonus_top').show();
								}else{
									$("#bouns_num").html(res.prize+"元");
									$('#popBonus').show();
								}
							}else if(res.msg==2){
								_this.addClass("curr"+name); //蛋碎效果
								_this.find("sup").show(); //金花四溅
								$("#hammer").hide();
								
								$('#coverbg').show();
								var str = '';
								if(res.prize == 'topegg'){
									str = '<marquee onmouseover="this.stop();" onmouseout="this.start();" scrolldelay="100"  class="news-box"><p>我的天呐！您进阶到<span id="up_span" style="color: #ffcf64;">流沙凤凰汤圆</span>啦！</p></marquee><div class="up_egg topegg" id="up_egg_topegg" onclick="javascript:eggClick(3,'+res.Is_up+');"><div class="base_4"></div><span></span></div><div class="kmlight1 cloudmoveL"></div><div class="kmlight2 cloudmoveR"></div><div class="kmlight3 cloudmoveL"></div>';
									$("#hammer").attr("class","hammer_topegg");
								}else if(res.prize == 'pgold'){
									str = '<marquee onmouseover="this.stop();" onmouseout="this.start();" scrolldelay="100"  class="news-box"><p>太幸运了！您进阶到<span id="up_span" style="color: #eb9d16;">蟹粉汤圆</span>啦~</p></marquee><div class="up_egg pgold" id="up_egg_pgold" onclick="javascript:eggClick(2,'+res.Is_up+');"><div class="base_3"></div><span></span></div>';
									$("#hammer").attr("class","hammer_pgold");
								}else{
									str = '<marquee onmouseover="this.stop();" onmouseout="this.start();" scrolldelay="100"  class="news-box"><p>恭喜！您进阶到<span id="up_span" style="color: #f34219;">锦芳元宵</span>啦！</p></marquee><div class="up_egg gold" id="up_egg_gold" onclick="javascript:eggClick(1,'+res.Is_up+');"><div class="base_2"></div><span></span>';
									$("#hammer").attr("class","hammer_gold");
								}
								$('.cover-in').hide();
								$('#stage_'+res.prize).html(str);
								$('#stage_'+res.prize).fadeIn(600);
								//$('.newEgg').hide();
								//$('.up_stage').show();
								$('#stage_'+res.prize).addClass('eggbg');
								
								var posL = winwidth*0.62;//$(this).position().left + $(this).width()*0.5+20;
								var posX = winwidth*0.117;//$(this).position().top + 3000;
								$("#hammer").fadeIn(1000).css('left', posL);
								$("#hammer").fadeIn(1000).css('top', posX);
								setTimeout(function () {
									$('#up_egg_'+res.prize).addClass("eggpop");
								}, 200);
							}else{
								$("#hammer").animate({
									"top": winwidth*0.085+addHeight,//40,
									"left": winwidth*0.63//318
								},2000);
								_this.addClass('egg_shake');
								setTimeout(function () {
									 confirm_z(1,res.prize);
									_this.removeClass('egg_shake');
								}, 300);
								$('#hammer').removeClass('Rotation');	
								$('.cover-in').hide();
								return false;
								
							}
						//$('.cover-in').hide();
						$('#hammer').removeClass('Rotation');	
						}
					);
				});
			}
</script>
</body>
</html>