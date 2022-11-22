<?php
header("Content-type: text/html; charset=utf-8");
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}
if(!isset($_GET['proNo'])){
	exit();
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
			echo "Verificación caducada";
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
    <title>C'estbon Entertainment - Eventos especiales</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="newCss/style.css">
	<link rel="stylesheet" href="newCss/index.css">
	<link rel="stylesheet" href="newCss/special.css">
    <link rel="stylesheet" href="newFonts/css/font-awesome.min.css">
    <link rel="stylesheet" href="newCss/Pop_up.css">
	<script src="newJs/jquery_2.1.3.js"></script> 
	<!--<link rel="stylesheet" href="newCss/imgSwitch.css"/>
	<script src="newJs/imgSwitch.js"></script>-->
	
<style>

		

</style>
</head>
<body style="background:#fff;">
<div class="md-modal md-effect-13" id="modal-13">
    <div class="md-content">
		<div class="fx">
			<div class="Pop_up_title">Excepción del sistema</div>
			<div class="md-jugde md-certain">OK</div>
			<div class="md-jugde md-close-fx">Cancelar</div>
			<div class="md-close">Veo</div>
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
		<div class="deposit_box" style="margin-top:0px;">
			<div class="user-head child_menu" style="background: #fff;">
				<!--<a href="javascript:menu_change(1);" id="pro1">先有鸡or先有蛋</a>-->
				<a id="pro2" class="weekTitle" href="javascript:menu_change(2);">Regalos Chou</a>
				<a id="pro3" class="sfTitle" href="javascript:menu_change(3);">Tarjeta de regalo gratis de marzo</a>
			</div>
			<!--<div class="pay_content" id="promotion1" style="display:none;color: #dc953c;font-size: 14px;margin-top: 10px;">
				<div class="special_top">
					<div class="deposit_check">当日存款金额：<span id="number1206">---</span>
						<a href="javascript:get_total_deposit('number1206');">
							<i class="fa fa-refresh" id="tpos" style="color: #ffe8cb;margin-left: 5px;"></i>
						</a>
					</div>
				</div>
			
				<div class="content newEgg">
					<div class="egg">
						<ul class="egglist">
							<p class="hammer_gold" id="hammer">锤子</p>
							<p class="resulttip" id="resulttip"><b id="result"></b></p>
							<div id="slide" class="slide" class="index-slide" alt="star">
							<!-- 轮播图片数量可自行增减
								<div class="img">
									<li class="silver" id="silver" onclick="javascript:eggClick(0,-1);"><sup class="sup supsilver"></sup></li>
									<p class="span_leter" id="span_leter_silver"></p>
								</div>
								<div class="img">
									<li class="gold" id="gold" onclick="javascript:eggClick(1,-1);"><sup class="sup supgold"></sup></li>
									<p class="span_leter" id="span_leter_gold"></p>
								</div>
								<div class="img">
									<li class="pgold" id="pgold" onclick="javascript:eggClick(2,-1);"><sup class="sup suppgold"></sup></li>
									<p class="span_leter" id="span_leter_pgold"></p>
								</div>
							</div>
							
							
						</ul>
					</div>
					<p style="text-align:center;padding-bottom:19px;">砸蛋彩金说明：彩蛋礼金仅能投注所有的老虎机和捕鱼游戏。</p>
					<table class="transfer_list" id="chickenoregg_promotion_record" border="0" style="width:96%;text-align: center;">
						<tr>
							
							<td colspan="5">当日砸蛋记录<div class="bgFortrL"></div><div class="bgFortrR"></div></td>
						</tr>
						<tr><td>彩蛋</td><td>礼金</td><td>奖品类型</td><td>总存款</td><td>砸蛋时间</td></tr>
					</table>
				</div>
				<div class="up_stage" id="stage_gold" style="display:none;"></div>
				<div class="up_stage" id="stage_pgold" style="display:none;"></div>
				<div class="up_stage" id="stage_topegg" style="display:none;"></div>
				<div id="coverbg"></div>
			</div>-->
			<div class="pay_content" id="promotion2" style="display:none;">
				<div class="bounsBox">
					<div class="bounsCenter">
						<p>Regalos semanales actuales disponibles(Mex$)</p>
						<p id="gid1901"><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></p>
						<p>Después de recibirlo, se distribuirá automáticamente a la cuenta principal</p>
						<span onclick="apply_bonus_week('gid1901')" id="apply_gid1901">Consiguelo ahora</span>
					</div>
					<div class="bounsBottom">
						<div>
							<span class="bounsIco ico1"></span><br/>
							<span>Actualización semanal</span>
						</div>
						<div>
							<span class="bounsIco ico2"></span><br/>
							<span>Sin límite de tiempo</span>
						</div>
						<div>
							<span class="bounsIco ico3"></span><br/>
							<span>Superposición admitida</span>
						</div>
						<div>
							<span class="bounsIco ico4"></span><br/>
							<span>Colección personal</span>
						</div>
					</div>
					<div class="bonus_tips">
						<table class="transfer_list" id="week_gift_list" border="0">
							<tr>
								<th colspan="5">Registros de lanzamientos recientes</th>
							</tr>
							<tr><th>Tipo de recompensa</th><th>Importe de la recompensa</th><th>Tiempo de desembolso</th></th></tr>
						</table>
						<p>
						*El dinero de regalo semanal solo se puede usar para apostar en juegos de máquinas tragamonedas o juegos de pesca; de lo contrario, no podrá pagar normalmente.
C'estbon International se reserva el derecho de extender, acortar, terminar o modificar las actividades sin previo aviso.
						</p>
					</div>
				</div>
			</div>
			<div class="pay_content augustBouns" id="promotion3" style="display:none;">
				<div class="bounsBox">
					
					<div class="bounsCenter">
						<p class="text1" id="pro_text"><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></p>
						<p class="text3">Después de recibirlo, se distribuirá automáticamente a la cuenta principal</p>
						<span onclick="apply_newYear_promotion()" id="newYear_pro"><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></span>
					</div>
					<div class="bounsBottom">
						<div>
							<span class="bounsIco ico1"></span><br/>
							<span>Exclusivo de marzo</span>
						</div>
						<div>
							<span class="bounsIco ico2"></span><br/>
							<span>Vistoso</span>
						</div>
						<div>
							<span class="bounsIco ico3"></span><br/>
							<span>Deposita hoy</span>
						</div>
						<div>
							<span class="bounsIco ico4"></span><br/>
							<span>Gratis al día siguiente</span>
						</div>
					</div>
					<div class="bonus_tips">
						<table class="transfer_list" id="newYear_list" border="0">
							<tr>
								<th colspan="3">Registros de recogida de hoy</th>
							</tr>
							<tr><th>Tipo de recompensas</th><th>Cantidad de las recompensas</th><th>Tiempo de entrega</th></th></tr>
							<tr>
								<td colspan="3">No hay registros!</td>
							</tr>
						</table>
						<p>
						*El obsequio de marzo solo se usa para apostar en juegos de tragamonedas o juegos de pesca; de lo contrario, no podrá pagar normalmente.
C'estbon International se reserva el derecho de extender, acortar, terminar o modificar las actividades sin previo aviso.。
						</p>
					</div>
				</div>
			</div>
		<div class="cover-in"></div>
		</div><!--deposit_box-->
		</div>
	</div>
</div>
<div class="bonuse" id="popBonus">
	<div class="gongxi">Enhorabuena, lo has acertado</div>
	<div class="gongxi"><span id="bouns_num">28</span>Mex$</div>
	<p class="bon-tips">El bono ganador ha sido depositado en su cuenta principal!</p>
	<div class="bon-ok">Veo</div>
	<div class="b-clo" id="popClo"></div>
</div>
<div class="bonus_top" id="popBonus_top">
	<div class="gongxi">Enhorabuena, has acertado</div>
	<div class="gongxi"><span id="bouns_num_top"></span>Mex$</div>
	<p class="bon-tips">Canjee efectivo de inmediato, comuníquese con el servicio al cliente las 24 horas para solicitar el canje</p>
	<div class="bon-ok_top">Intercambiar ahora</div>
	<div class="ex_ico"></div>
</div>
<script src="newJs/header.js"></script> 
<!-- <script src="js/menu_open.js"></script> --> 
<script src="newJs/Pop_up.js"></script> 
<script src="newJs/public.js"></script> 
<script>
	$(function(){
		//切换优惠活动
		menu_change(<?php echo $_GET['proNo']?>);
		get_week_bonus();// 1901 1902获取金额
		check_newYear_pro();//新春礼金201801
		//get_bet(1);//查询投注
		//get_total_deposit('number1206');//获取今日存款
		//chickenoregg_promotion_record();//砸蛋
		//check_egg_status();//砸蛋
		week_gift_list();//1901 获取记录
		
		//august_gift_list();//1902获取记录
		//special_promotion_record();
		//promotion_history();
		
		$('#popClo,.bon-ok').click(function(){
			$('#popBonus').hide();
			$('.cover').removeClass('blur-in');
			$('.cover-in').hide();			
			$('.up_stage').fadeOut(1000);
			$('#coverbg').fadeOut(1000);
			
			$("#hammer").attr("class","hammer_"+$('.img3 li').attr("id"));
			var winwidth = document.documentElement.clientWidth;
			var posL = winwidth*0.56;
			var posX = winwidth*0.10;
			$("#hammer").show().css('left', posL);
			$("#hammer").show().css('top', posX);
			chickenoregg_promotion_record();
			
		});
		
	
	});
	/*菜单切换*/
	function menu_change(id){
		$('.child_menu>a').removeClass('act');
		$('#pro'+id).addClass('act');
		$('.pay_content').hide();
		$('#promotion'+id).show();
		
		var win_width = $(window).width();
		$(".wrapper").css("min-width",win_width);
		$(".slide").height(win_width*0.64);
		$(".up_stage").height(win_width*1.08625);
		$(".deposit_check").height(win_width*0.08238);
		$(".deposit_check").css("line-height",win_width*0.08238+'px');
	}
	var numflag = 1;
	function bonClick(){
		if(numflag ==1){
			cs();
			numflag = 2;
			$('.bon-ok_top').text("Veo");
		}else{
			$('#popBonus_top').hide();
			$('.cover').removeClass('blur-in'); 
			$('.cover-in').hide();
			$('.up_stage').fadeOut(1000);
			$('#coverbg').fadeOut(1000);

			$("#hammer").attr("class","hammer_"+$('.img3 li').attr("id"));
			var winwidth = document.documentElement.clientWidth;
			var posL = winwidth*0.56;
			var posX = winwidth*0.10;
			$("#hammer").show().css('left', posL);
			$("#hammer").show().css('top', posX);
			chickenoregg_promotion_record();
			numflag = 1;
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
							for(var name in data)
							{
								$("#"+data[name]).addClass("curr"+data[name]);
								$("#"+data[name]).find("sup").show(); //金花四溅
							}
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
					//$("#result").html("");
					//$(".bs-example-modal-sm").modal({backdrop:"static",keyboard:false});
					
					confirm_z(0,"<i class='fa fa-spinner fa-spin' aria-hidden='true'></i>Espere por favor...");
				$.getJSON("ajax_data.php",{type:"get_prize_new",name:name_or,Is_up:Is_up},function(res){
					//_this.unbind('click');
					//return false;
					var winwidth = document.documentElement.clientWidth;
					if(Is_up > -1){
						addHeight = winwidth*0.141;
						$('.lightBg').fadeOut(800);
						$('#stage_'+name+' .crownIco').hide();
					}			
					$("#hammer").animate({
									"top": 0,
									"left": winwidth*0.59//330   560
								},200);
					$(".md-modal").removeClass("md-show");
					$(".cover").removeClass("blur-in");
					$("#hammer").animate({
						"top": winwidth*0.143+addHeight,//80,
						"left": winwidth*0.464//260
						},300,function(){
							if(res.msg==1)
							{
								_this.addClass("curr"+name); //蛋碎效果
								_this.find("sup").show(); //金花四溅
								//$(".hammer").hide();
								$("#hammer").animate({
									"top": winwidth*0.071+addHeight,//40,
									"left": winwidth*0.568//318
								},300);
								if(Is_up > -1){
									//$(".hammer").hide();
									$('#up_egg_'+name).removeClass("eggpop");
								}
								$('.cover-in').show();
								$('.cover').addClass('blur-in');
								if(name == 'topegg'){
									//$("#bouns_num_top").html(res.prize);
									//$('#popBonus_top').show();
									
									var topContent = '';
									if(res.prize >0){
										topContent = '<div class="gongxi">Enhorabuena, has acertado</div><div class="gongxi"><span id="bouns_num_top">'+res.prize+'</span>Mex$</div><p class="bon-tips">Canjee efectivo de inmediato, comuníquese con el servicio al cliente las 24 horas para solicitar el canje</p><div class="bon-ok_top" onclick="bonClick()">Intercambiar ahora</div><div class="ex_ico"></div>';
										//$("#bouns_num_top").html(res.prize);
									}else if(res.prize == '-1'){
										numflag = 2;
										topContent = '<div class="gongxi">Enhorabuena, has acertado</div><div class="gongxi"><span id="bouns_num_top" style="color: #fdea1b;font-size:30px;">Tarjeta de Reembolso Doble</span></div><p class="bon-tips">¡El monto del reembolso en todas las plataformas hoy se puede duplicar mañana!</p><div class="bon-ok_top" onclick="bonClick()">Veo</div><div class="ex_ico1"></div>';
									}else if(res.prize == '-2'){
										numflag = 2;
										topContent = '<div class="gongxi">Enhorabuena, has acertado</div><div class="gongxi"><span id="bouns_num_top" style="color: #fdea1b;font-size:30px;">Tarjeta de puntos dobles</span></div><p class="bon-tips">Obtenga instantáneamente puntos dobles en todos los depósitos dentro de las 24 horas!</p><div class="bon-ok_top" onclick="bonClick()">Veo</div><div class="ex_ico2"></div>';
									}else if(res.prize == '-3'){
										numflag = 2;
										topContent = '<div class="gongxi">Enhorabuena, has acertado</div><div class="gongxi"><span id="bouns_num_top" style="color: #fdea1b;font-size:30px;">Tarjeta de doble transferencia</span></div><p class="bon-tips">Inmediatamente, puede obtener dinero de doble transferencia dentro de las 24 horas!</p><div class="bon-ok_top" onclick="bonClick()">Veo</div><div class="ex_ico3"></div>';
									}
									$('#popBonus_top').html(topContent);
									$('#popBonus_top').show();
								}else{
									$("#bouns_num").html(res.prize);
									$('#popBonus').show();
								}
							}else if(res.msg==2){
								_this.addClass("curr"+name); //蛋碎效果
								_this.find("sup").show(); //金花四溅
								
								$('#coverbg').show();
								var str = '';
								if(res.prize == 'topegg'){
									str = '<marquee onmouseover="this.stop();" onmouseout="this.start();" scrolldelay="100"  class="news-box"><p>¡Oh Dios mío! avanzas a<span id="up_span" style="color: #ffcf64;">Huevo colorido</span>La！</p></marquee><div class="up_egg topegg" id="up_egg_topegg" onclick="javascript:eggClick(3,'+res.Is_up+');"><div class="crownIco"></div><span></span></div><div class="lightBg"></div><div class="cloudL cloudmoveL"></div><div class="cloudR cloudmoveR"></div>';
									$("#hammer").attr("class","hammer_topegg");
								}else if(res.prize == 'pgold'){
									str = '<marquee onmouseover="this.stop();" onmouseout="this.start();" scrolldelay="100"  class="news-box"><p>¡Muy afortunado! avanzas a<span id="up_span" style="color: #951ff3;">Huevo de oro morado</span>La!</p></marquee><div class="up_egg pgold" id="up_egg_pgold" onclick="javascript:eggClick(2,'+res.Is_up+');"><div class="crownIco"></div><span></span></div><div class="cloudL cloudmoveL"></div><div class="cloudR cloudmoveR"></div>';
									$("#hammer").attr("class","hammer_pgold");
								}else{
									str = '<marquee onmouseover="this.stop();" onmouseout="this.start();" scrolldelay="100"  class="news-box"><p>¡Felicidades! avanzas a<span id="up_span" style="color: #ffcf64;">Huevos de oro</span>La！</p></marquee><div class="up_egg gold" id="up_egg_gold" onclick="javascript:eggClick(1,'+res.Is_up+');"><div class="crownIco"></div><span></span>';
									$("#hammer").attr("class","hammer_gold");
								}
								$('#stage_'+res.prize).html(str);
								$('#stage_'+res.prize).fadeIn(600);
								//$('.newEgg').hide();
								//$('.up_stage').show();
								$('#stage_'+res.prize).addClass('eggbg');
								
								var posL = winwidth*0.56;//$(this).position().left + $(this).width()*0.5+20;
								var posX = winwidth*0.117;//$(this).position().top + 3000;
								$("#hammer").fadeIn(1000).css('left', posL);
								$("#hammer").fadeIn(1000).css('top', posX);
								setTimeout(function () {
											$('#up_egg_'+res.prize).addClass("eggpop");
								}, 200);
							}else{
								var posL = winwidth*0.56;
								var posX = winwidth*0.10;
								$("#hammer").show().css('left', posL);
								$("#hammer").show().css('top', posX);
								_this.addClass('egg_shake');
								setTimeout(function () {
									 confirm_z(1,"对不起，"+res.prize);
									_this.removeClass('egg_shake');
								}, 300);
								
								return false;
							}
							
							
						}
					);
				});
			}
			/*周礼金 2017-06-24*/
			function week_gift_list(){
				
				$.getJSON("ajax_data.php",{type: "week_gift_list"},function(data){
					if(data.re_code == 1){
						$('#week_gift_list').html(data.str);
					}else{
						$('#week_gift_list').append('<tr><td colspan="5">No hay registros</td></tr>');
					}
				});
			}
			function get_week_bonus(){
				$('#cover-in').show();
				$.getJSON("ajax_data.php",{type: "washcodeself_list"},function(data){
					if(data != null){
						if(data.info.gid1901 != ''){
							$('#gid1901').html(data.info.gid1901);
							if(data.info.gid1901 >0){
								$('#apply_gid1901').addClass('apply_bonus_week');
							}else{
								$('#apply_gid1901').removeClass('apply_bonus_week');
							}
						}else{
							$('#gid1901').html('0.00');
						}
						if(data.info.gid1902 != ''){
							$('#gid1902').html(data.info.gid1902);
							if(data.info.gid1902 > 0){
								$('#apply_gid1902').addClass('apply_bonus_week');
							}else{
								$('#apply_gid1902').removeClass('apply_bonus_week');
							}
						}else{
							$('#gid1902').html('0.00');
						}
					}
					$('#cover-in').hide();
				});
				
			}
			/*圣诞礼金 2017-12-18*/
			function august_gift_list(){
				
				$.getJSON("ajax_data.php",{type: "august_gift_list"},function(data){
					if(data.re_code == 1){
						$('#august_gift_list').html(data.str);
					}else{
						$('#august_gift_list').append('<tr><td colspan="5">No hay registros</td></tr>');
					}
				});
			}
			
			function apply_bonus_week(gid){
				if($('#apply_'+gid).hasClass('apply_bonus_week')){
					$('#apply_'+gid).hide();
					$.getJSON("ajax_data.php",{type:"washcodeself_receive",id:gid},function(data){
						confirm_z(1,data.info);
						get_week_bonus();
						$('#apply_'+gid).show();
					});
				}
			}
			//查询投注
			function get_bet(gametype){
				$('#bet'+gametype).html("<i class='fa fa-spinner fa-spin' aria-hidden='true'></i>");
				$.getJSON("ajax_data.php",{'type':'get_bet','gametype':gametype},function(data){
					if(data.status == 1){
						$('#bet'+gametype).html(data.info+'Mex$');
					}else{
						$('#get_re').html(data.info);
						$('#alam_bg').show();
					}
				});
			}
			//弹出提示
			function alert_notice(){
				if($('#apply_gid1902').hasClass('apply_bonus_week')){
					$('body').append('<div class="chooseplatf"><div class="chooseplatf_er">\
						<span class="text" id="ch_text">¿Transferir el dinero del regalo a QT o NT?</span>\
						<div class="btn_war" id="btn_war">\
							<span class="btn_ch" style="float:left;background:#e44e24;" onclick="apply_bet_bonus(1215)">plataforma QT</span>\
							<span class="btn_ch" onclick="apply_bet_bonus(1214)" style="float:right;">plataforma NT</span>\
						</div>\
					</div>\
					<div class="ch_close" onclick="$(\'.chooseplatf\').remove();"></div></div>');
				$('.chooseplatf').fadeIn();
				}
			}
			//申请流水礼金
			function apply_bet_bonus(id){
				$('.ch_close').remove();
				$('#apply_gid1902').removeClass('apply_bonus_week');
				$('#ch_text').html('El sistema está procesando...');
				$('#btn_war').html('');
				$.getJSON('ajax_data.php',{'type':'apply_bet_bonus','id':id},function(data){
					var platfname = "plataforma NT";
					if(id == 1215){
						platfname = "plataforma QT";
					}
					if(data.status == 1){
						$('#ch_text').html('Transferencia de dinero de regalo<span style="color:red">'+platfname+'</span>¡éxito!');
					}else{
						$('#ch_text').html(data.info);
					}
					$('#btn_war').html('<span class="btn_ch" style="width: 150px;" onclick="$(\'.chooseplatf\').remove();">Seguro</span>');
					get_week_bonus();
				});
			}
	//查询三月免单礼金记录201803
	function check_newYear_pro(){
		$.getJSON('specialProData.php',{type:'wzqh_history'},function(d){
			$('#pro_text').html(d.info);
			if(d.status ==1){
				$('#newYear_pro').addClass('get_chance').html('Consiguelo ahora');
			}else if(d.status == 2){
				$('#newYear_pro').html('Recibió');
				if(d.record != ''){
					$('#newYear_list').html(d.record);
				}
			}else if(d.status == -1){
				$('#newYear_pro').addClass('todeposit').html('Deposite ahora para el evento de mañana');
			}else{
				$('#newYear_pro').html('No puedo conseguirlo');
			}
		});
	}
	//申请三月免单礼金201803
	function apply_newYear_promotion(){
		if($('#newYear_pro').hasClass('get_chance')){
			$('#newYear_pro').removeClass('get_chance');
			confirm_z(0,"<i class='fa fa-spinner fa-spin' aria-hidden='true'></i>Procesando el sistema, por favor espere...")
			$.getJSON('specialProData.php',{type:'wzqh_pro'},function(d){
				confirm_z(1,d.info);
				check_newYear_pro();
			});
		}else if($('#newYear_pro').hasClass('todeposit')){
			window.open('deposit.php?username=<?php echo $_SESSION['account']?>','memberinfo','width=1000,height=690,screenX=200,screenY=200,scrollbars=yes,resizable=yes,toolbar=no,menubar=no,location=no,status=no');
		}
	}
			
</script>
</body>
</html>