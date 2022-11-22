<?php

//echo 564;exit;
header("Content-type: text/html; charset=utf-8");
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}



 	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_GET['auth'];
 
    
    	$api_keyapp='fgbtfrhrew';
    //$time = substr(time(),0,-3);
	
    $auth_checkapp = md5($time.$api_keyapp);
    if($auth_check != $auth&&$auth_checkapp!=$auth)
	{
	
		echo json_encode(array('status'=>0,'info'=>"Verification failed"));
		exit();
	}
  $_SESSION['account']=$_GET['account'];
  $account=$_SESSION['account'];
  if(isset($_GET['type'])&&$_GET['type']=='mphone'){
      
      $_SESSION['deposit_type']='m';
  }else{
      
       $_SESSION['deposit_type']='';
  }
		//if($auth_check == $auth || $auth_check1 == $auth)
		//{
			$core = new core();
			$re = $core->get_memberinfo($account);
                       // print_r($re);exit;
			$_SESSION['account'] = $re['account'];
			$_SESSION['balance'] = $re['balance'];
			$_SESSION['member_name'] = $re['realName'];
			$_SESSION['member_type'] = $re['memberType'];
			setcookie("account", $_SESSION['account'], time()+86400);
			setcookie("member_name", urlencode($_SESSION['member_name']), time()+86400);
		/*}else{
			echo "验证已过期";
			error_log("deposit"."#".date('YmdHis')."#".$_GET['username']."#".$account."#".$auth."#".$auth_check."#".$auth_check1."\r\n", 3,'common/log/autherror.log');
			exit();   
		}*/
	

//echo 526;exit;
                      // print_r($_SESSION['account']);exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>U Sports - Recarga</title>
    <meta name="keywords" content="U Sports"/>
    <meta name="description" content="U Sports"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="newCss/style.css">
	<link rel="stylesheet" href="newCss/index.css">
    <link rel="stylesheet" href="newFonts/css/font-awesome.min.css">
    <link rel="stylesheet" href="newCss/Pop_up.css">
	<script src="newJs/jquery_2.1.3.js"></script> 
	<style>
	.deposit_box {padding:0px;margin: 0px;margin-top: 0px;position:relative;}
	#step1{padding: 15px;}
	#step2-1 .de_column,#step2-2 .de_column,#step2-3 .de_column{overflow:visible;}
	.de_payline{padding-top: 46px;display:none;}
	.de_payline .pay_btn{height: 40px;line-height: 40px;font-size: 14px;color: #fff;margin-left: 59px;background: #87c9ef;padding-left: 20px;border-bottom: 1px solid #eee;}
	.de_payline .de_hr{margin-top: 3px;}
	.wrapper{background:#eeeeee;}
	
	.mpay_sty_bg{position:absolute;width:100%;height:100%;top:0px;background: rgba(51, 51, 51, 0.5);display:none;z-index: 9;}
	.mpay_sty{display:block;width: 86%;height: auto;top: 50px;position: absolute;background: #1d3541;left: 50%;margin-left: -45%;padding: 2%;border: 2px solid #f5a32c;color: #fff;border-radius: 10px;font-size: 14px;}
	.mpay_sty >p{text-align: center;font-size: 16px;color: #ff4e4e;font-weight: bold;margin: 10px 0px;}
	.mpay_sty >span{color: #ff4e4e;font-weight: bold;}
	.mpay_sty >btn{display: block;width: 100px;margin: 10px auto;text-align: center;height: 30px;line-height: 30px;background: #fa9f1c;border-radius: 5px;}
	</style>
</head>
<body>
<div class="md-modal md-effect-13" id="modal-13">
    <div class="md-content">
		<div class="fx">
			<div class="Pop_up_title">Excepción del sistema</div>
			<div class="md-jugde md-certain">Pagar ahora</div>
			<div class="md-jugde md-close-fx" style="background:#ccc;">Cancelar</div>
			<div class="md-close">Veo</div>
		</div>
    </div>
</div>
<div class="md-overlay"></div>
<div id="overlay" class="cover">
	<!--<div class="main">
		<?php include 'navigation.php';?>
	</div>-->
	<div class="wrapper">
		<?php //include 'top.php'?>
		<div class="deposit_box">
			<div id="step1" style="display:block;">
			
				<div class="de_column" id="wy" onclick="get_payline(0,'wy')"><!--网银在线支付-->
					<i class="de_ico icowy"></i>
					<div class="de_title">
						<span class="de_title_text">Pago en línea banca en línea</span><span class="colorText">&nbsp;HOT!</span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
					<div class="de_payline" id="de_payline_0">
						<div class="pay_btn pay_btn_init"><i class="fa fa-spinner fa-spin"></i>Carga de línea...</div>
					</div>
				</div>
				<div class="de_column" id="wx" onclick="get_payline(1,'wx')"><!--Pago de WeChat-->
				<i class="de_ico icowx"></i>
					<div class="de_title">
						<span class="de_title_text">Pago de WeChat</span><span class="colorText"></span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
					<div class="de_payline" id="de_payline_1">
						<div class="pay_btn pay_btn_init"><i class="fa fa-spinner fa-spin"></i>Carga de línea...</div>
					</div>
				</div> 
				
				<div class="de_column" id="zfb" onclick="get_payline(2,'zfb')">
					<i class="de_ico icozfb"></i>
					<div class="de_title">
						<span class="de_title_text">Pago en línea Alipay</span><span class="colorText"></span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
					<div class="de_payline" id="de_payline_2">
						<div class="pay_btn pay_btn_init"><i class="fa fa-spinner fa-spin"></i>Carga de línea...</div>
					</div>
				</div>
			
				<!--<div class="de_column" id="dk" onclick="get_payline(4,'dk')"><!--点卡支付-->
					<!--<i class="de_ico icodk"></i>
					<div class="de_title">
						<span class="de_title_text">银联扫码</span><span class="colorText"></span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
					<div class="de_payline" id="de_payline_4">
						<div class="pay_btn pay_btn_init"><i class="fa fa-spinner fa-spin"></i>Carga de línea...</div>
					</div>
				</div>-->
				<!--<div class="de_column" id="ysf123" onclick="get_payline(5,'ysf123')"><!--云闪付-->
				<!--			<i class="de_ico icoysf123"></i>
							<div class="de_title">
								<span class="de_title_text">云闪付</span><span class="colorText"></span>
								<span class="de_title_right"></span>
								<div class="de_hr"></div>
							</div>
							<div class="de_payline" id="de_payline_5">
								<div class="pay_btn pay_btn_init"><i class="fa fa-spinner fa-spin"></i>Carga de línea...</div>
							</div>
						</div> -->
				<!--<div class="de_column" id="zz" onclick="get_payline(11,'zz')">
					<i class="de_ico icozz"></i>
					<div class="de_title">
						<span class="de_title_text">支付宝/网银/微信扫码转账</span><span class="colorText"></span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
				</div>-->
				
				<div class="de_column" id="qq" onclick="get_payline(3,'qq')"><!--QQ钱包-->
					<i class="de_ico icoqq"></i>
					<div class="de_title">
						<span class="de_title_text">Anclaje USDT</span><span class="colorText"></span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
					<div class="de_payline" id="de_payline_3">
						<div class="pay_btn pay_btn_init"><i class="fa fa-spinner fa-spin"></i>Carga de línea...</div>
					</div>
				</div>
				<div class="de_column" id="ysf" onclick="get_payline(12,'yunsf')" style="display:none;">
					<i class="de_ico icoysf"></i>
					<div class="de_title">
						<span class="de_title_text">Transferencia de código de escaneo de banca móvil</span><span class="colorText">&nbsp;23:00-01:00 sin demora</span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
				</div>
				<div class="de_column" id="zz" onclick="get_payline(9,'zz')"><!--Alipay/transferencia bancaria por Internet-->
					<i class="de_ico icozfb"></i>
					<div class="de_title">
						<span class="de_title_text">Alipay/transferencia bancaria por Internet</span><span class="colorText">&nbsp;</span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
				</div>				
				<!--<div class="de_column" id="ztz" onclick="get_payline(10,'ztz')"><!--支付宝转支付宝
					<i class="de_ico icozfb"></i>
					<div class="de_title">
						<span class="de_title_text">支付宝转支付宝</span><span class="colorText"></span>
						<span class="de_title_right"></span>
						<div class="de_hr"></div>
					</div>
				</div>-->
				
			</div><!--step1-->
			<div id="step2-1" class="deposit_step" style="display:none;">
				<div class="de_column">
					<i class="de_ico" id="de_ico"></i>
					<div class="de_title">
						<span class="de_title_text" id="payType"></span>
						<span class="de_title_right" id="close2-1"></span>
						<div class="de_hr"></div>
					</div>
				</div>
				<div class="de_content">
					<div class="pay_content" >
						<div class="onlinepay_list fx" id="list"></div>
						<div class="pay_content1" id="zfb_pay">
							<form id="onlinepay1" class="onlinepay" action="center.php" method="post">
								<input type="hidden" name="submit_type" value="monlinepay">
								<input type="hidden" class="pay_id" name="pay_id" id="pay_id1" value="">
								<div class="enter_nane enter_deposit">
									<p style="color:#222">Cantidad del depósito</p><input class="enter enter1 noselect" placeholder="Por favor ingrese el monto del depósito" name="amount" id="amount1" maxlength="7">
								               					<select id="amount1" class="enter enter1 select" name="amount"  maxlength="7">
   <option value ="0">Por favor elige</option>
  
 
 <!-- <option value ="100">100</option>
  <option value ="200">200</option>

  <option value="300">300</option>-->
  <option value="500">500</option>
   <option value="1000">1000</option>
    <option value="2000">2000</option>
     <option value="3000">3000</option>
      <option value="5000">5000</option>
</select>
                                                                </div>
								   <!--<div class="enter_nane  enter_nane1 " style="display:none;">
									实际支付<input  class="enter enter2"  name="amount" id="amount2" maxlength="7" readonly="readonly">
                                                                       
								</div>-->
								<p id="amount1_ck" style="font-size:12px;color:#ea0606;margin-top:-5px;display:none;margin-bottom:5px;">Los sistemas de pago QQ y WeChat hacen coincidir automáticamente la cantidad con un punto decimal</p>
								<select name="bank_type" id="bank_type1" class="bank_type" style="display:none">
									
								</select>
								<table id="fee_table" class="fee_table" style="display:none;">
									<tr>
										<td style="width:44px;color:#222">Tasa de tramitación</td>
										<td style="width:20px;color:#222" class="fee opfee">---</td>
										<td style="width:56px;color:#222">Cantidad recibida</td>
										<td  style="color:#222"class="fee realfee">---</td>
									</tr>
								</table>
								<div class="radio-group">
									<!--<input type="radio" name="autopromo" value="0" checked="checked" id="proNone"><label for="proNone">No elijas una oferta</label> <br> 
									<input type="radio" name="autopromo" value="1" id="pro01"><label for="pro01">笔笔存20%优惠</label><br>
									<input type="radio" name="autopromo" value="2" id="pro02"><label for="pro02">每日首存50%优惠</label><br>-->
                                                                    								               					<select id="autopro" class="" name="autopromo"  maxlength="7">
   <option value ="0">No elijas una oferta</option>
  
</select>
                                                 
								</div>       
								<a href="javascript:" id="md-trigger" data-modal="modal-13" class="save_button5" onclick="check_form('1')">
									<div class="btn_ui" style="width: 100%; margin-top:17px; margin-bottom:30px;">Entregar</div>
								</a>
							</form>
							<div class="onlinepay_notice" style="font-size: 12px;">
								<p style="color:#222">Consejos:</p>
								<p class="otherTip" style="color:#222">1. Ocasionalmente hay un retraso en la llegada (3 ~ 10 minutos), si no llega a la cuenta por más de 10 minutos, contáctenos.</br>2、Para mejorar su tasa de éxito de pago, los sistemas de pago QQ y WeChat automáticamente igualan la cantidad con un punto decimal</p>
								<!--<p class="dkTip">1、点卡充值会扣除相应Tasa de tramitación。</p>
								<p class="dkTip">2、点卡充值偶尔会出现延迟到账，慢则需要1~3个工作日，不需要联系客服。</p>
								<p class="dkTip">3、点卡充值请务必使用与您选择的面额相同的卡进行支付，否则引起的交易失败金额不予退还!</p>-->
							</div>
						</div>
					</div>
				</div>
			</div><!--step2-1-->
		</div><!--deposit_box-->
		<div class="mpay_sty_bg">
			<div class="mpay_sty"  style="color:#222">
				<p>Recordatorio importante</p>
				Este canal de pago solo se puede utilizar<span>Aplicación Cloud QuickPass, no puede usar otras aplicaciones de banca móvil</span>, el uso de otras APPs de banca móvil hará que el ingreso no llegue (<span>Utilice la aplicación UnionPay para pagar gracias</span>）</br>
				<!--<span>2.</span>付款金额一定要<span>精确到小数点后1位</span>。比如：a充值100元，需支付宝100.3。B充值300元，需支付300.9。</br>
				<span>3.</span>扫码支付成功之后一定返回币宝充值页面--点击右下角的“<span>我已付款</span>”，否则将无法支付成功。（如长时间未看到商家放币，可点击左下角的“<span>提醒放币</span>”）</br>
				<span>4.</span>每天只有<span>3次</span>取消交易的机会，如果3次都失败了，那么今天无法再使用币宝支付。</br>
				<span>5.</span>币宝支付支持微信、支付宝、银行卡、PayPal</br>-->
				<btn id="btn_cls">Veo</btn>
			</div>
		</div>
	</div><!--wrapper-->
</div><!--cover-->

<script src="newJs/header.js"></script> 
<script src="newJs/Pop_up.js"></script> 
<script src="newJs/public.js?version0531"></script> 

<script>

	$(function(){
		//getpromotion();
		get_balance(0);//获取账户余额
		noread_message();
		//新手教程
		$('#fno').click(function(){
			$('#fno_box').show();
		});
		$('#fno_zfb').click(function(){
			$('#fno_zfb_box').show();
		});
		$('#example_close,#example_close_zfb').click(function(){
			$('.zfb_wy_pay_box').hide();
		});
		//关闭弹窗
				$('#btn_cls').click(function(){
					$('.mpay_sty_bg').hide();
				});
		//生成平台费
		$("#amount1").keyup(function(){
                 //alert(23);
			/*if(payTypeFlag == 1 || payTypeFlag == 2){
				$(this).val($(this).val().replace(/[^\d/.]/g,''));
			}else{
				$(this).val($(this).val().replace(/[^\d]/g,''));
			}*/
			if( payTypeFlag == 1 || payTypeFlag == 2 || payTypeFlag == 0 || payTypeFlag == 4 || payTypeFlag == 5){
						$(this).val($(this).val().replace(/[^\d/]/g,''));
					}else{
			   $(this).val($(this).val().replace(/[^\d/.]/g,''));
                                        }
            var rate = 0;
			if(payTypeFlag == 0){
				rate = 0;
			}
			var amount = $(this).val();
			if(payTypeFlag == 3){
                            
                             amount = (amount-0);
                              if(amount%1 === 0){
                                var aa=    Math.ceil(Math.random()*100)/100;
                                        var fee3 =amount;
                                }else{
                                         var fee3 =parseFloat(amount).toFixed(2);
                                        
                                }
                               var fee1 = parseFloat(fee3*0.015).toFixed(2);
			       var fee2 = fee3;
                               $(".realfee").html(fee2);
                               $("#amount2").val(fee3);
                        }else if( payTypeFlag == 1 ||payTypeFlag == 2){
                            amount = (amount-0);
				if(amount%1 === 0){
					var aa=   0;
					var fee3 =parseFloat(amount+aa).toFixed(2);
				}else{
					var fee3 =parseFloat(amount).toFixed(2);
				}
				var fee1 = parseFloat(fee3*0.015).toFixed(2);
				var fee2 = fee3;
				$(".realfee").html(fee2);
				$("#amount2").val(amount);
                            
                            
                        }else{
			var fee1 = parseFloat(amount*rate).toFixed(2);
			var fee2 = parseFloat(amount-fee1).toFixed(2);
			if((!isNaN(fee1)) && fee2 != 0){
				//$(".opfee").html(fee1+"元");
				$(".realfee").html(fee2);
			}else{
				//$(".opfee").html('---');
				$(".realfee").html('---');
			}
                    }
                 // alert(amount);
                    $.ajax({
			type: "GET",
			url: "ajax_check.php",
			data: "type=get_autopromotion_list&amount="+amount,
			dataType: "json",
			async: false,
			success: function(data){
                       
                        //    $("#autopro").append("<option value ='656'> adfgafgda活动</option>") ;
                            data = eval(data);
                            // alert(data.status);
                             if(data.status==0){
                                 
                                  $("#autopro").html("<option value ='0'>Actualmente no hay ofertas disponibles</option>");
                                   return fasle;
                                 
                             }
				data=data.info;
                              //  $str='';
                              $("#autopro").html("<option value ='0'>No elijas una oferta</option>");
                                 for (var i = 0; i < data.length; i++) {
              $("#autopro").append("<option value =" + data[i].id + "> "+ data[i].title + "</option>") ;
              
             
         }
			}
		});
		});
	$(".select").bind("change",function(){
              //alert(23555);
			/*if(payTypeFlag == 1 || payTypeFlag == 2){
				$(this).val($(this).val().replace(/[^\d/.]/g,''));
			}else{
				$(this).val($(this).val().replace(/[^\d]/g,''));
			}*/
			if( payTypeFlag == 1 || payTypeFlag == 2){
						$(this).val($(this).val().replace(/[^\d/]/g,''));
					}else{
			   $(this).val($(this).val().replace(/[^\d/.]/g,''));
                                        }
            var rate = 0;
			if(payTypeFlag == 0){
				rate = 0;
			}
			var amount = $(this).val();
			if(payTypeFlag == 3){
                            
                             amount = (amount-0);
                              if(amount%1 === 0){
                                var aa=    Math.ceil(Math.random()*100)/100;
                                        var fee3 =parseFloat(amount+aa).toFixed(2);
                                }else{
                                         var fee3 =parseFloat(amount).toFixed(2);
                                        
                                }
                               var fee1 = parseFloat(fee3*0.015).toFixed(2);
			       var fee2 = fee3;
                               $(".realfee").html(fee2);
                               $("#amount2").val(fee3);
                        }else if( payTypeFlag == 1 ||payTypeFlag == 2){
                            amount = (amount-0);
				if(amount%1 === 0){
					var aa=   0;
					var fee3 =parseFloat(amount+aa).toFixed(2);
				}else{
					var fee3 =parseFloat(amount).toFixed(2);
				}
				var fee1 = parseFloat(fee3*0.015).toFixed(2);
				var fee2 = fee3;
				$(".realfee").html(fee2);
				$("#amount2").val(amount);
                            
                            
                        }else{
			var fee1 = parseFloat(amount*rate).toFixed(2);
			var fee2 = parseFloat(amount-fee1).toFixed(2);
			if((!isNaN(fee1)) && fee2 != 0){
				//$(".opfee").html(fee1+"元");
				$(".realfee").html(fee2);
			}else{
				//$(".opfee").html('---');
				$(".realfee").html('---');
			}
                    }
		});
		//关闭Entregar页面
		$('#close2-1,#close2-2,#close2-3').click(function(){
			$('#de_ico').attr("class","de_ico");
			$('#amount1').val("");
			$('.fee').html('---');
			$('#bank_type1').html("").hide();
			$('.deposit_step').hide();
			$('#fee_table').hide();
			$('#step1').fadeIn();//.addClass('slideInLeft');
			$('.deposit_step').removeClass('slideInLeft');
		});
		
		$("input[name=autopromo]").change(function(){
			var status = $(this).val();
			if(status != 0){
                             /*var check=$("input[type='radio']:checked").val();
                                 
                                       if(check!=3){
                                           return false;
                                       }*/
				confirm_z(1,'Al hacer clic para solicitar un descuento, se considera que ha entendido los términos del descuento. Para obtener más información, consulte los detalles del descuento.');
			}				
		});
		$('#username').focus(function(){
			$('#usernameTip').show();
		});
		$('#zfb_id_6').focus(function(){
			$('#zfbidTip').show();
		});
	});
	//全局变量payType flag
	var payTypeFlag = -1;
	//获取支付线路
	function get_payline(payType,type){
		if(!$("#"+type).hasClass('maintenance')){
			 payTypeFlag = payType;
			if(payType < 9){
				$('#de_payline_'+payType).slideToggle();
				$('#de_payline_'+payType).parent().toggleClass('act');
				if($('#de_payline_'+payType).find('.pay_btn').hasClass('pay_btn_init')){
					$.getJSON("ajax_data.php",{type: "onlinepay_list_v1",payType:payType},function(data){
						
						if(data !=''){
							$('#de_payline_'+payType).html(data);
						}else{
							//if(payType == 1 || payType == 11){
							//	confirm_z(2,"该支付通道维护中，推荐您使用微信转账方式进行存款");
							//}else{
								
							//}
							/*confirm_z(2,"该支付通道维护中，推荐您使用支付宝转账方式进行存款");
							$('#de_ico').attr("class","de_ico");
							$('#step2-1').hide();
							$('#fee_table').hide();
							$('#bank_type1').hide();
							$('#step1').show();
							$('#'+type).addClass('maintenance');
							$("#"+type).find('span.colorText').html("&nbsp;(维护中)");
							$('.md-jugde').click(function(){
								if($(this).index() == 1){
									if(payType == 1 || payType == 11){
										window.location.href='wx_deposit.php';
									}else{
										window.location.href='zz_deposit.php';
									}
								}else{
									$('#de_payline_'+payType).parent().removeClass('act');
									$('#de_payline_'+payType).slideUp();
								}
								$('.md-close').click();
								$('.md-jugde').hide();
							});*/
							confirm_z(1,'El canal de pago está en mantenimiento, se recomienda que utilice otros métodos para depositar');
						}
					});
				}
				
			}else if(payType == 9){
				window.location.href='zz_deposit.php';
			}else if(payType == 11){
				
				window.location.href='wx_deposit.php';
			}else if(payType == 12){
				
				window.location.href='yunsf_deposit.php';
			}else if(payType == 10){
				/*$('#step1').hide();
				$('#step2-3').show().addClass('slideInLeft');
				//获取存款支付宝信息
				$.getJSON("ajax_data.php",{type: "deposit_bank",bank_type:"1"},function(data){
					$("#zfbid").html(data[0]);
					$("#zfb_info").html(data[1]);
				});*/
			}
		}
		
	}
	function show_paytable(payType,btn_id){
		var arr_pay = ['wy','wx','zfb','qq','dk','ysf123'];
		var type = arr_pay[payType];
		if(payType != 4&&payType != 5){
			$('#fee_table').show();
			$('.dkTip').hide();
			$('.otherTip').show();
		}else{
			$('.dkTip').show();
			$('.otherTip').hide();
		}
		  if(payType == 3 || payType == 1){
			  //$('input.enter1').attr("name","cd");
                        //  $('.enter_nane1').show();
		}else{
                    $('input.enter2').attr("name","ad"); 
                    
                }
		if(payType == 4 || payType == 0||payType == 5){
			$('#bank_type1').show();
		}
		if(payType == 1 || payType == 2 || payType == 3){
			$('.opfee').html('0');
		}
		if(payType == 1 || payType == 3){
			//$('#amount1_ck').show();
		}else{
			$('#amount1_ck').hide();
		}
		if(payType == 0){
			$('.opfee').html('无');
		}
		var title = $("#"+type).find('span.de_title_text').html();
		$('#payType').html(title+"-"+$('#'+btn_id).html());
		$('#de_ico').addClass("ico"+type);
		$('#step1').hide();
		$('#step2-1').show().addClass('slideInLeft');
                      if(btn_id=="mpay_176_64_166"||btn_id=="mpay_155_57_145"||btn_id=="mpay_178_66_168" ||btn_id=="mpay_182_68_1721"){
                                  
                                    // alert()
                                    $('.noselect').hide();
                                    $('.select').show();
                                  
                                   $(".noselect").attr("name","aa");
								    $(".noselect").attr("id","aa");
                                }else{
                                    $('.noselect').show();
                                    $('.select').hide();
                                    $(".select").attr("name","aa");
                                }
		if(btn_id == 'mpay_217_57_207'||btn_id == 'mpay_218_70_208'){
			$('.mpay_sty_bg').show();
		}
		$('#pay_id1').val(btn_id);
		/*if(payType == 4){
			$("#bank_type1").html("<option value='YDSZX'>移动神州行</option><option value='LTYKT'>联通一卡通</option><option value='DXGK'>电信国卡</option><option value='JWYKT'>骏网一卡通</option><option value='SFYKT'>盛大一卡通</option><option value='QBCZK'>Q币充值卡</option><option value='WMYKT'>完美一卡通</option><option value='ZTYKT'>征途一卡通</option><option value='SHYKT'>搜狐一卡通</option><option value='JYYKT'>久游一卡通</option><option value='THYKT'>天宏一卡通</option><option value='TXYKTZX'>天下一卡通</option>");
		}else{*/
			$.get("ajax_data.php",{type: "monlinepay_bank",id:btn_id},function(re){
				$("#bank_type1").html(re);
			});
		//}
		
	}
	
	function check_form(id){
		//alert(id); //是id是1
		//return;
		$('#usernameTip').hide();
		var valid = true;
		var mesg = '';
		var amount_low = 10;
		var amount_up = 9999;
		var amount = $("#amount"+id).val();
		//alert(amount);//此处为空
		//return false;
		var pay_id = $('#pay_id'+id).val();
		$(".save_button"+id).hide();
		
		$.ajax({
			type: "GET",
			url: "ajax_limit.php",
			data: "type=onlinepay&pay_id="+pay_id,
			dataType: "json",
			async: false,
			success: function(data){
				amount_low = data[0];
				amount_up = data[1];
			}
		});
		if(id == 1){
			var bank_type = $('#bank_type1').val();
			if(bank_type == null){
				valid = false;
				mesg = 'Por favor, haga clic en enviar más tarde';
				$(".save_button"+id).show();
			}
		}
		if(valid){
			if(amount == ''){
				valid = false;
				mesg = 'El monto de la recarga no puede estar vacío';
				$(".save_button"+id).show();
			}
		}
		if(valid){
			//var reg = new RegExp("^[0-9]+$"); 
			var reg = new RegExp("^[0-9]+([.]{1}[0-9]+){0,1}$");
			if(!reg.test(amount)){
				valid = false;
				mesg = 'El monto de la recarga es incorrecto';
				$(".save_button"+id).show();
			}
		}
		if(valid){ 
			if(amount < amount_low){
				valid = false;
				mesg = 'El importe de la recarga no debe ser inferior a '+amount_low;
				$(".save_button"+id).show();
			}
			if(amount > amount_up){
				valid = false;
				mesg = 'El monto de la recarga no debe ser mayor a '+amount_up;
				$(".save_button"+id).show();
			}
                    if ((pay_id=="mpay_34_24_29" ||pay_id=="mpay_48_24_43"||pay_id=="mpay_37_24_32" ||pay_id=="mpay_35_24_30") && parseInt(amount)%10==0 ){
                        
                               valid = false;
				mesg = 'El dígito único del monto del depósito del pago de dpay no puede ser 0';
				$(".save_button"+id).show();
			}
		}
		if(valid){
			if(id == 2){
				var username = $('#username').val();
				if(username == ''){
					valid = false;
					mesg = 'Por favor ingrese el nombre del depositante';
					$(".save_button"+id).show();
				}
			}
		}
		if(valid){
			if(id == 2){
				var username = $('#username').val();
				var reg = /^[\u4e00-\u9fa5]{2,8}$/
				if(!reg.test(username)){
					valid = false;
					mesg = 'Por favor ingrese el nombre correcto';
					$(".save_button"+id).show();
				}
			}
		}
		if(valid){
			if(id == 3){
				var zfbid = $('#zfb_id_6').val();
				if(zfbid == '' ||zfbid.length <6){
					valid = false;
					mesg = 'Ingrese los últimos 6 dígitos del número de pedido';
					$(".save_button"+id).show();
				}
			}
		}
		if(valid){
			if(id==1){
				$('#onlinepay'+id).submit();
				$(".Pop_up_title").html("Te está redirigiendo a la página de pago...");
				$(".md-close").css("display","none");
				$('#close2-1').click();
			}else{
				$(".Pop_up_title").html('<div class="loding"><i class="fa fa-spinner fa-spin"></i>Procesando el sistema, por favor espere</div>');
				$(".md-close").css("display","none");
				$.ajax({
					url:"center.php",
					data:$("#onlinepay"+id).serialize(),
					type:"POST",
					dataType:"text",
					success:function(d){
						$("#onlinepay"+id)[0].reset();
						if(d != ''){
							$(".Pop_up_title").html(d);
							$(".md-close").css("display","block");
							$(".save_button"+id).show();
						}else{
							$(".Pop_up_title").html("Te está redirigiendo a la página de pago...");
							$(".md-close").css("display","none");
							$(".save_button"+id).show();
						}
					},
				});
			}
		}else{
			$(".Pop_up_title").html(mesg);
			$(".md-close").css("display","block");
		}
	}
</script>

<div style="display:none">
	<script src="https://s13.cnzz.com/z_stat.php?id=1262049736&web_id=1262049736" language="JavaScript"></script>
</div>
</body>
</html>