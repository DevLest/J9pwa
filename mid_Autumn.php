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
		if($auth_check == $auth)
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
			exit();   
		}
	}else{
		exit();   
	}
	
}
?>
<!DOCTYPE HTML>
<!--BBIN平台-->
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Yibao_Festival del medio otoño Evento especial_Tormenta de pastel de luna</title>
	<meta name="viewport"
		content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<script src="newJs/jquery_2.1.3.js"></script>
	<link rel="stylesheet" type="text/css" href="newCss/style.css">
	<link rel="stylesheet" type="text/css" href="newCss/flutter.css">
	<link rel="stylesheet" type="text/css" href="newCss/animate.css">
	<script type="text/javascript" src="newJs/flutter.js"></script>
	<style>
		.ma-bg {
			background: url(images/yuebing/midAutumn_pc_back.jpg) no-repeat top center;
			background-size: 100%;
		}

		.pro_footer {
			position: absolute;
			bottom: 0px;
			text-align: center;
			color: #fcd357;
			width: 100%;
			height: 33px;
			line-height: 15px;
			background: #000;
			font-size: 12px;
		}

		.block_bg {
			position: absolute;
			width: 100%;
			height: 100%;
			z-index: 2;
		}

		.pub_box {
			width: 350px;
			height: 212px;
			position: absolute;
			top: 50%;
			left: 50%;
			margin-top: -120px;
			margin-left: -175px;
		}

		.fir_box {
			background: url(images/yuebing/law_pop.png)no-repeat;
			background-size: 100%;
			text-align: left;
			display: block;
		}

		.fir_box .title {
			font-size: 14px;
			font-weight: bold;
			color: #ff9b54;
			margin: 5px 0px;
		}

		.fir_box .content {
			line-height: 15px;
			font-size: 12px;
			color: #fca754;
		}

		.fir_box .content span {
			color: #fff;
			font-size: 12px;
		}

		.sec_box {
			background: url(images/yuebing/bonus_pop.png)no-repeat;
			display: none;
			background-size: 100%;
			text-align: center;
		}

		.btn_box {
			width: 310px;
			margin: 18px auto 18px;
		}

		.btn_box span {
			display: inline-block;
			width: 145px;
			text-align: center;
			color: #ff932a;
			font-size: 16px;
			font-weight: bold;
			border: 2px solid #ff932a;
			height: 40px;
			line-height: 40px;
			border-radius: 40px;
		}

		.btn_box span:nth-child(2) {
			margin-left: 6px;
			background: #ff932a;
			color: #762448;
			cursor: pointer;
		}

		.btn_box span:nth-child(2):hover {
			background: #ff9e3f;
		}

		.sec_box .btn_ok {
			position: absolute;
			width: 70%;
			height: 45px;
			background: #600529;
			cursor: pointer;
			bottom: 25px;
			left: 55px;
			border-radius: 40px;
			border: 2px solid #ffaf06;
			line-height: 45px;
			font-size: 18px;
			font-weight: bold;
			color: #ffca5b;
		}

		.sec_box .btn_ok:hover {
			background: #8d063b;
		}

		.sec_box .title {
			font-size: 17px;
			font-weight: bold;
			color: #ffc878;
			margin-top: 19px;
			text-align: left;
		}

		.sec_box .content {
			font-size: 33px;
			font-weight: bold;
			color: #fecf67;
			margin-top: 25px;
		}

		.alam_bg .ala_btn {
			background: #e09527;
		}

		.alam_box p {
			color: #de7915;
			text-align: center;
		}
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
				<p class="title">C'estbon Mooncake Thunder Shower viene caliente:</p>
				<p class="content">Del 28 de septiembre al 7 de octubre, habrá tormentas de pastel de luna cuatro veces
					al día.</br><span>(00:00-02:00 | 08:00-10:00 | 13:00-15:00 | 20:00-22:00)<br /></span>Mooncake Lucky
					Cash se puede utilizar para apostar en todos los juegos de máquinas tragamonedas/juegos de pesca en
					la sala de juegos, ¡y se puede retirar con 1x de facturación! Participa y ten la oportunidad de
					ganar grandes premios, ¡ven y participa!</p>
				<div class="btn_box">
					<span>No participando ahora</span>
					<span id="join_game">Involúcrese ahora</span>
				</div>
			</div>
		</div>
		<!--开场弹窗-->
		<div class="sec_box pub_box animated rubberBand">
			<div style="padding: 20px;">
				<p class="title" id="money_info"></p>
				<p class="content" id="money"></p>
				<span class="btn_ok" id="btn_ok">Veo</span>
			</div>
		</div>
		<!--领奖弹窗-->
		<span id="count" style="display:none;font-size:100px;color:fff;">4</span>
	</div>
	<div class="pro_footer">COPYRIGHT © C'est bon ONLINE ENTERTAINMENT.<br /> ALL RIGHTS RESERVED.</div>
	<script>
		var flag = true;
		var flag_join = true;
		$('#join_game').click(function () {
			if (flag) {
				if (flag_join) {
					flag_join = false;
				} else {
					return;
				}
				//获取现在的时间段
				var myDate = new Date();
				var hour = myDate.getHours(); //获取当前小时数(0-23)
				var cake_type = -1;
				if (hour == 0 || hour == 1) {
					cake_type = 0;
				} else if (hour == 8 || hour == 9) {
					cake_type = 1;
				} else if (hour == 13 || hour == 14) {
					cake_type = 2;
				} else if (hour == 20 || hour == 21) {
					cake_type = 3;
				}

				if (cake_type > -1) {
					$.get('midAutumnData.php', {
						'type': 'get_midAutumn_history',
						'cake_type': cake_type
					}, function (re) {
						if (re == 1063) {
							confirm_z("0",
								"Ya ha participado en las actividades de este período de tiempo, por favor regrese en el próximo período de tiempo"
							);
						} else if (re == -1) {
							confirm_z("1", "Inicie sesión en su cuenta primero para participar");
						} else {
							$('.fir_box').hide();
							cake_rain();
							setTimeout(apply_midAutumn_pro, 3000);
						}
					});
				} else {
					confirm_z("0", "No es hora del evento.");
				}




			} else {
				confirm_z("0", "Vuelve la próxima vez!");
			}
		});
		$('#btn_ok').click(function () {
			if ($(this).hasClass('todeposit')) {
				window.open('deposit.php?username=<?php echo $_SESSION['
					account ']?>', 'memberinfo',
					'width=1000,height=690,screenX=200,screenY=200,scrollbars=yes,resizable=yes,toolbar=no,menubar=no,location=no,status=no'
				);
			} else {
				$('.sec_box').removeClass('rubberBand').addClass('rotateOut ');
				setTimeout(function () {
					$('.sec_box').hide();
				}, 1000);
				$('.couten').remove();
				$('.fir_box').show();
				flag = false;
			}

			//window.opener=null;window.open(' ','_self');window.close();
		});
		//领月饼礼金
		function apply_midAutumn_pro() {
			$.getJSON('midAutumnData.php', {
				'type': 'apply_midAutumn_pro'
			}, function (data) {
				if (data.status == 1) {
					$('#money').html(data.amount);
					$('#money_info').html(data.info);
					$('.sec_box').show();
				} else if (data.status == 201) {
					$('#money').hide();
					$('#money_info').html(data.info);
					$('.sec_box').show();
					$('#btn_ok').html('¡Quiero un depósito!');
					$('#btn_ok').addClass('todeposit');
					//加上去充值的按钮
				} else if (data.status == 0) {
					confirm_z(0, data.info);
				}
			});
		}



		function confirm_z(type, content) {
			$('#get_re').html(content);
			if (type == 0) {
				$('#ala_btn').hide();
			} else if (type == 1) {
				$('#ala_btn').show();
			}
			$('#alam_bg').show();
		}
	</script>
</body>

</html>