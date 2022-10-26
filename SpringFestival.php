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
			echo "Verificación caducada";
			error_log("special"."#".date('YmdHis')."#".$_GET['username']."#".$account."#".$auth."#".$auth_check."#".$auth_check1."\r\n", 3,'common/log/autherror.log');
			exit();   
		}
	}else{
		exit();   
	}
	
}
?>
<!DOCTYPE HTML>
<!--端午特别活动-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Yibao_Evento especial del Festival del Bote del Dragón_Tormenta Zongzi</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<script src="newJs/jquery_2.1.3.js"></script> 
	<link rel="stylesheet" type="text/css" href="newCss/style.css">
	<link rel="stylesheet" type="text/css" href="newCss/flutter.css">
	<link rel="stylesheet" type="text/css" href="newCss/animate.css">
	<script type="text/javascript" src="newJs/flutter.js?version0531"></script>
	<style>
		/*红包雨活动*/
		.ma-bg{background:url(images/zongzi2018/rain_bg.jpg) no-repeat top center #36a077;background-size: 100%;}
		.pro_footer{position: absolute;bottom: 0px;text-align: center;color: #fcd357;width: 100%;height: 33px;line-height: 15px;background: #000;font-size:12px;}
		.block_bg{position:absolute;width:100%;height:100%;z-index:2;}
		.pub_box{width: 350px;height: 265px;position: absolute;top: 50%;left: 50%;margin-top: -133px;margin-left: -175px;}
		.fir_box{background: #ffebd7;text-align:left;display:block;box-shadow: 1px 1px 10px #ff5c5c;}
		.fir_box .title{font-size: 19px;font-weight: bold;color: #b74a60;margin: 5px 0px;}
		.fir_box .content{line-height: 17px;font-size: 14px;color: #92673d; background: none;min-height: 100px;    margin-top: 18px;}
		.fir_box .content span{color: #ff9d9d;font-size: 12px;}
		.sec_box{background: #ffebd7;display:none;background-size:100%;text-align: center;box-shadow: 1px 1px 10px #ff5c5c;}
		.btn_box{width: 310px;margin: 18px auto 18px;}
		.btn_box span{display: inline-block;width: 110px;text-align: center;color: #ca472f;font-size: 16px;border: 2px solid #ca472f;height: 40px;line-height: 40px;border-radius: 40px;}
		.btn_box span:nth-child(2){margin-left: 6px;width: 180px;background: #be4d64;cursor: pointer;color: #ffebd7;font-weight: bold;}
		.btn_box span:nth-child(2):hover{background: #d55a73;}
		.sec_box .btn_ok{position: absolute;width: 70%;height: 45px;background: #be4d64;cursor:pointer;bottom: 25px;left: 49px;border-radius: 40px;border: 2px solid #be4d64;line-height: 45px;font-size: 18px;font-weight: bold;color: #ffebd7;}
		.sec_box .btn_ok:hover{background: #8d063b;}
		.sec_box .title{font-size: 17px;font-weight: bold;color: #4d2a23;margin-top: 19px;text-align:left;}
		.sec_box .content{font-size:40px;font-weight: bold;color: #4d2a23;margin-top: 25px; background: none;min-height: auto;}
		.alam_bg .ala_btn{background:#e09527;}
		.alam_box p{color: #de7915;text-align: center;}
		.smal_tip{color: #be8f99;margin-top: 20px;display:none;position: absolute;bottom: 68px;width: 88%;font-size: 12px;}
	</style>
</head>
<body class="ma-bg">
	<div id="alam_bg" class="alam_bg" style="display:none;">
		<div class="alam_box">
			<p id="get_re">---</p>
			<div id="ala_btn" class="ala_btn" onclick="$('#alam_bg').hide();">Veo</div>
		</div>
	</div>
	<div class="block_bg" id="block_bg">
		<div class="fir_box pub_box">
			<div style="padding: 20px;">
				<p class="title">C'estbon dumplings, se acercan truenos y aguaceros</p>
				<p class="content">Del 1 al 30 de junio, habrá una tormenta de albóndigas de arroz cuatro veces al día</br><span>(00:00-02:00 | 08:00-10:00 | 13:00-15: 00 | 20:00-22:00)<br/></span>Zongzi Lucky Rewards puede apostar en PT/Super Speed ​​PT/MG/ Super Speed ​​​​MG/QT Platform/NT Platform/PNG Platform/SG Plataforma/Juego de tragamonedas de plataforma TTG/Juego de pesca, ¡se puede retirar 1 vez la facturación! Participa y ten la oportunidad de ganar grandes premios, ¡ven y participa!</p>
				<div class="btn_box">
					<span>No participando ahora</span>
					<span id="join_game">Involúcrese ahora</span>
				</div>
			</div>
		</div><!--开场弹窗-->
		<div class="sec_box pub_box animated rubberBand">
			<div style="padding: 20px;">
				<p class="title" id="money_info"></p>
				<p class="content" id="money"></p>
				<p class="smal_tip" id="tip1">*Este beneficio es un regalo especial para el Festival del Bote del Dragón de C'estbon Consulte las ofertas relevantes para conocer las reglas de restricciones de rotación. </p>
				<p class="smal_tip" id="tip2">*Hoy, si ahorras 500 yuanes, puedes recuperarlos. No le digo a la persona promedio~</p>
				<span class="btn_ok" id="btn_ok">Lo entiendo</span>
			</div>
		</div><!--领奖弹窗-->
		<span id="count" style="display:none;font-size:100px;color:fff;">4</span>
	</div>
	<div class="pro_footer">COPYRIGHT © C'est bon ONLINE ENTERTAINMENT.<br/> ALL RIGHTS RESERVED.</div>
	<script type="text/javascript" src="newJs/public.js"></script>
<script>
	var flag = true;
	var flag_join = true;
	$('#join_game').click(function(){
		if(flag){
			if(flag_join){
				flag_join = false;
			}else{
				return;
			}
			//获取现在的时间段
			var myDate = new Date();
			var hour = myDate.getHours(); //获取当前小时数(0-23)
			var cake_type = -1;
			if(hour == 0 || hour == 1){
				cake_type = 0;
			}else if(hour == 8 || hour == 9){
				cake_type = 1;
			}else if(hour == 13 || hour == 14){
				cake_type = 2;
			}else if(hour == 20 || hour == 21){
				cake_type = 3;
			}
			
			if(cake_type > -1){
				zdwaiting();
				$.get('SpringFestivalData.php',{'type':'get_sf_history','cake_type':cake_type},function(re){
					if(re == 1063){
						zdalert("Indicador de actividad", 'Ya ha participado en las actividades de este período de tiempo, vuelva en el próximo período de tiempo');
					}else if(re == -1){
						zdalert("Indicación del evento", 'Inicie sesión en su cuenta antes de participar');
					}else{
						$('.fir_box').hide();
						$('#mb_box').remove();
						cake_rain();
						setTimeout(apply_midAutumn_pro,3000);
					}
					flag_join = true;
				});
			}else{
				zdalert("Recordatorio de evento","Aún no es la hora del evento",function(r){
					location.reload();
				});
			}
			
			
			
			
		}else{
			zdalert("Recordatorio de evento","¡Vuelve en la próxima franja horaria!");
		}
	});
	$('#btn_ok').click(function(){
		if($(this).hasClass('todeposit')){
			$(this).removeClass('todeposit');
			$('#btn_ok').html('Veo');
			window.open('deposit.php?username=<?php echo $_SESSION['account']?>','memberinfo','width=1000,height=690,screenX=200,screenY=200,scrollbars=yes,resizable=yes,toolbar=no,menubar=no,location=no,status=no');
			location.reload();
		}else{
			$('.sec_box').removeClass('rubberBand').addClass('rotateOut ');
			setTimeout(function(){$('.sec_box').hide();},1000);
			$('.couten').remove();
			$('.fir_box').show();
			
		}
		
		//window.opener=null;window.open(' ','_self');window.close();
	});
	//领粽子礼金
	function apply_midAutumn_pro(){
		$.getJSON('SpringFestivalData.php',{'type':'apply_sf_pro'},function(data){
			if(data.status == 1){
				$('#money').html(data.amount);
				$('#money_info').html(data.info);
				$('.sec_box').show();
				$('#tip1').show();
				$('#tip2').hide();
				
			}else if(data.status == 201){
				$('#money').hide();
				$('#money_info').html(data.info);
				$('.sec_box').show();
				$('#btn_ok').html('¡Quiero un depósito!');
				$('#btn_ok').addClass('todeposit');
				$('#tip2').show();
				$('#tip1').hide();
				//加上去充值的按钮
			}else if(data.status == 0){
				zdalert("Recordatorio de actividad",data.info,function(){
					location.reload();
				});
			}
		});
	}
</script>
</body>
</html>