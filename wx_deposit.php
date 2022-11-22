<?php
header("Content-type: text/html; charset=utf-8");
if(!isset($_SESSION))
{
	session_start();
}
if(!isset($_SESSION['account']) || $_SESSION['account'] == "")
{
	echo "验证已过期";
	exit(); 
}
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
	.wrapper{background:#fff;}
	#step1{padding: 15px;}
	#step2-1 .de_column,#step2-2 .de_column,#step2-3 .de_column{overflow:visible;}
	.de_payline{padding-top: 46px;display:none;}
	.de_payline .pay_btn{height: 40px;line-height: 40px;font-size: 14px;color: #fff;margin-left: 59px;background: #87c9ef;padding-left: 20px;border-bottom: 1px solid #eee;}
	.de_payline .de_hr{margin-top: 3px;}
	
	.tip_gy{font-size:12px;color:#787878;}
	.input_blue{margin: 9px auto 0px;padding: 10px 0px;display: block;width: 100%;text-indent: 12px;border: 1px solid #259ee6;border-radius: 3px;}
	.input_blue:focus{outline: 0;border-color: #47c5ff;}
	.de_content_zz{margin:10px;}
	.btn_blue{width: 100%;margin-top: 17px;margin-bottom: 30px;height: 50px;line-height: 50px;border-radius: 3px;}
	.content_zz {margin-top: 10px;}
	.content_zz span{display:inline-block;font-size: 14px;color: #787878;}
	.content_zz span.span_title{text-indent: 12px;}
	.content_zz span.span_star{height:35px;line-height:35px;color: red;}
	.content_zz span.span_content{height: 35px;line-height: 35px;border: 1px solid #9b9797;width: 70%;border-radius: 3px;text-indent: 10px;color: #0095d9;font-weight: bold;}
	.content_zz span.span_copy{height: 35px;line-height: 35px;width: 17%;border-radius: 3px;border: 1px solid #9b9797;margin-left: 8px;text-align: center;}
	.content_zz .impor_tip{display: block;color: #fff;margin-left: 12px;text-indent: 12px;height: 35px;line-height: 38px;margin-top: 5px;width: 280px;background:url(images/arror.png)no-repeat;font-size: 13px;}
	.deposit_box .newOne_tip{text-align:right;font-size:14px;font-weight:bold;color:#787878;margin-top: 15px;display:block;}
	.de_content_zz .tips{font-size: 12px;background: #eee;margin: 20px 12px;padding: 12px;}
	.de_content_zz .tips p {line-height:20px;}
	#step2-3 {background: #f5f5f5;padding-bottom: 20px;}
	.guide_content p {margin-top:10px;font-size:13px;color: #ababab;}
	.guide_content p span{color:#ff3d00;font-weight:bold;}
	.guide_content p span a{color: #ff8e19;font-weight: normal;border: 2px solid #ff8e19;padding: 5px;border-radius: 5px;margin-left: 17px;}
	.guide_content img{margin: 10px 0px 10px 0px;}
	#step2-3 .de_hr{border-bottom: 1px solid #777;}
	#onlinepay .saml_tips{font-size: 13px;color: red;margin-top: 20px;}
	#cancel_deposit{position: absolute;right: 15px;top: 5px;font-size: 15px;color: #fff;display: block;z-index: 99;height: 40px;line-height: 40px;padding: 0px 10px;border-radius: 8px;background: #ed4c19;}
	
	.inCase{margin-top:10px;}
	.inCase p{margin: 5px;color: #f89a10;}
	.inCase div{display: inline-block;width: 100px;height: 30px;line-height: 30px;border: 1px solid #116a9e;margin: 5px;border-radius: 5px;text-align: center;color: #116a9e;    font-size: 14px;}
	.inCase div.act{background:#116a9e;color:#fff;}
	</style>
	
</head>
<body>
<div class="md-modal md-effect-13" id="modal-13">
    <div class="md-content">
		<div class="fx">
			<div class="Pop_up_title">Excepción del sistema</div>
			<div class="md-jugde md-certain">Seguro</div>
			<div class="md-jugde md-close-fx" style="background:#ccc;">Cancelar</div>
			<div class="md-close">Veo</div>
		</div>
    </div>
</div>
<div id="alam_bg" class="alam_bg" style="display:none">
	<div class="alam_box">
		<p>Consejos:</p>
		<span id="mesg_str">Recibir aviso de desactivación de cuenta: ¡Estimado miembro! La transferencia bancaria en línea de C'estbon/cuenta de transferencia de Alipay Jia Xiaoyu China Merchants Bank ha sido descontinuada oficialmente y no se volverá a utilizar en el futuro. No transfiera fondos a esta cuenta para no causar que la cantidad no llegue , ¡Gracias por su cooperación!</span>
		<div id="ala_btn" class="ala_btn">Veo</div>
	</div>
</div>
<div class="md-overlay"></div>
<div id="overlay" class="cover">
	<div class="main">
		<?php //include 'navigationns.php';?>
	</div>
	<div class="wrapper">
		<?php //include 'top.php'?>
		<div class="deposit_box">
			<div id="step2-1" style="display:block;" class="slideInLeft">
				<div class="de_column"><!--支付宝/网银转账-->
					<i class="de_ico icozz"></i>
					<div class="de_title">
						<span class="de_title_text">Alipay/Banca en línea/Transferencia de código de escaneo de WeChat</span>
						<a href="deposit.php?username=<?php echo $_SESSION['account']?>"><span class="de_title_right"></span></a>
						<div class="de_hr"></div>
					</div>
				</div>
				<div class="de_content_zz" id="zfb_wy_pay">
					<form id="onlinepay" style="margin-top: 7px;">
						<p class="saml_tips">Consejos: después de enviar, complete el monto del pago real con un punto decimal al pagar, para que pueda acreditarse rápidamente en la cuenta. ¡Gracias!</p>
						<input type="hidden" name="submit_type" value="deposit">
						<input type="hidden" name="zf_type" value="2">
						<input class="input_blue" placeholder="Cantidad del depósito" name="amount1" id="amount1" maxlength="8" onkeyup="value=value.replace(/[^\d]/g,'')">
						<input class="input_blue" placeholder="Importe real de la transferencia" name="amount" id="amount2" maxlength="8" onkeyup="value=value.replace(/[^\d\.]/g,'')" style="background: #e0e0e0;" type="hidden" readonly>
						<span class="tip_amount tip_gy" ></span>
						<input class="input_blue bendimobilename" placeholder="Nombre real" name="username" id="username">
						<span class="tip_username tip_gy">*El sistema guarda automáticamente el nombre de su depósito. Si el nombre es correcto, no necesita completarlo. Si cambió el nombre del depósito para este depósito, cámbielo. </span>
						<div class="radio-group">
<select id="autopro" class="" name="autopromo"  maxlength="7">
   <option value ="0">No elijas una oferta</option>
  
</select>
             	
						</div>  
						<a href="javascript:" class="save_button5" onclick="check_form()" >
							<div class="btn_ui btn_blue">提 交</div>
						</a>
					</form>
				</div>
				<!--<a class="newOne_tip" href="https://www.yuebo100.com/weixin.html" target="_blank" style="color:#ff7d00;">微信转账银行卡教程>></a>-->
			</div><!--step2-1-->
			<div id="step2-2" style="display:none;" class="deposit_step">
				<div class="de_column"><!--微信扫码-->
					<div class="de_title" style="text-indent:10px;color: #0674a5;">
						<span class="de_title_text">Información de pago (compatible con Alipay/Banca en línea/WeChat)</span>
						<div class="de_hr"></div>
					</div>
					<span id="cancel_deposit">Solicitud cancelada</span>
					
				</div>
				<div class="inCase"></div>
				<div class="de_content_zz">
					<!--<div class="content_zz">
						<span class="span_title">收款银行：</span><br/>
						<span class="span_star">*</span>
						<span class="span_content" id="str1"></span>
					</div>
					<div class="content_zz">
						<span class="span_title">收款账户名：</span><br/>
						<span class="span_star">*</span>
						<span class="span_content" id="str2"></span>
						<span class="span_copy" id="copy2" onclick="copy_data(2)" data-clipboard-target="#str2">Copiar</span>
					</div>
					<div class="content_zz">
						<span class="span_title">收款账号：</span><br/>
						<span class="span_star">*</span>
						<span class="span_content" id="str3"></span>
						<span class="span_copy" id="copy3" onclick="copy_data(3)" data-clipboard-target="#str3">Copiar</span>
					</div>-->
					<div class="content_zz">
						<p style="text-align:center"><font size="3" color="red">Al pagar, por favor complete la cantidad con decimales dados por el sistema</font></p>
						<p style="text-align:center"><img  id="qrImg"  src="https://m.yuebet100.com/images/wx_deposit.png?version0810" height="200" width="200" /> </p>
					</div>
					<div class="content_zz">
						<span class="span_title">Cantidad de recarga:</span><br/>
						<span class="span_star">*</span>
						<span class="span_content" id="str4"></span>
						<span class="span_copy" id="copy4" onclick="copy_data(4)" data-clipboard-target="#str4">Copiar</span>
					</div>
					<div class="content_zz">
						<span class="span_title" style="color:red">Código de verificación (Importante)：</span><br/>
						<span class="span_star">*</span>
						<span class="span_content" id="str5"></span>
						<span class="span_copy" id="copy5" onclick="copy_data(5)" data-clipboard-target="#str5">Copiar</span>
						<span class="impor_tip">Asegúrese de completar este código de verificación en sus comentarios al pagar</span>
					</div>
					
					<div class="tips">
						<p style="font-size:13px;">Precauciones:</p>
						<p style="color:red">1.Por favor complete el código de verificación en los comentarios al pagar;</p>
						<p>2.Consulte el monto con decimales proporcionado por el sistema para el monto del pago.;</p>
						<!--<p style="color:red">3.微信转账至招商银行，每天23:05--00:35有延迟到帐的现象，如果已经转账，请耐心等待。</p>
						<p>4.平台账户会不定时更换，请勿保存;</p>-->
						<p>3.Si la transferencia es exitosa y no llega a la cuenta en diez minutos, comuníquese con el servicio de atención al cliente en línea para consultar y agregarla por usted.</p>
						<p>Completar la información anterior incorrectamente hará que la recarga no se transfiera con éxito a la cuenta.!</p>
					</div>
				</div>
			</div><!--step2-1-->
			<div id="step2-3" style="display:none;" class="guide_content deposit_step">
				<div class="de_column"><!--Tutorial para principiantes-->
					<div class="de_title" style="text-indent:10px;color: #0674a5;background: #f5f5f5;">
						<span class="de_title_text">Tutorial para principiantes</span>
						<span class="de_title_right" id="close2-3"></span>
						<div class="de_hr"></div>
					</div>
				</div>
				<p>① Ingrese el monto que desea transferir (mínimo 10 yuanes), complete su Nombre real y seleccione el descuento que necesita</p>
<p>② Haga clic en Siguiente para obtener la información de su tarjeta bancaria y la posdata para esta transferencia</p>
				<img src="images/guide1.png"/>
				<p>③ La siguiente imagen muestra la información que necesita completar para esta transferencia. Puede usar <span>Alipay</span> o <span>Internet Banking</span> para transferir a nuestra tarjeta bancaria, <span>debe completarnos al transferir "Postscript" que se le proporcionó. </span></p>
				<img src="images/guide2.png"/>
				<p>④ ¿Aún no realizas la transferencia por PayPal? Vamos, aquí hay un secreto.</p>
				<p>
					<span><a href="https://cshall.alipay.com/lab/help_detail.htm?help_id=560791&keyword=%CA%D6%BB%FA%20%D7%AA%D5%CB%B5%BD%D2%F8%D0%D0%BF%A8&sToken=s-db269f4c970f492a9d460e7bf895533c&from=search&flag=0" target="_blank">Tutorial móvil de Alipay</a></span>
					
					<span><a href="https://cshall.alipay.com/lab/help_detail.htm?help_id=246494" target="_blank">电脑支付宝教程</a></span>
				</p>
			</div>
		</div><!--deposit_box-->
	</div><!--wrapper-->
</div><!--cover-->

<script src="newJs/header.js"></script> 
<script src="newJs/Pop_up.js"></script> 
<script src="newJs/public.js?version0531"></script> 
<script src="newJs/clipboard.min.js"></script>

<script>

	$(function(){
		checkdeposit();
		//getpromotion();
		get_balance(0);//获取账户余额
		noread_message();
		
		$("#amount1").keyup(function(){
			var amount = $(this).val();
			 amount = (amount-0);
             if(amount%1 === 0){
             var aa= Math.ceil(Math.random()*100)/100;
                 var fee3 =parseFloat(amount+aa).toFixed(2);
             }else{
                 var fee3 =parseFloat(amount).toFixed(2);
                                        
             }
             $("#amount2").val(fee3);
                       
		});
		
		$("#amount1").keyup(function(){
                    var amount = $(this).val();
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
		
		
		//关闭提交页面
                  $('.bendimobilename').val(localStorage.bendimobilename);
		$('#close2-2, #close2-3').click(function(){
			
			$('#step2-1').fadeIn();//.addClass('slideInLeft');
			$('.deposit_step').removeClass('slideInLeft').hide();
		});
		
		$("input[name=autopromo]").change(function(){
			var status = $(this).val();
			if(status != 0){
                          /*  var check=$("input[type='radio']:checked").val();
                                 
                                       if(check!=3){
                                           return false;
                                       }*/
				confirm_z(1,'Al hacer clic para solicitar un descuento, se considera que ha entendido los términos del descuento. Para obtener más información, consulte los detalles del descuento.');
			}				
		});
		if(Clipboard.isSupported()){
			$('.span_copy').show();
		}else{
			$('.span_copy').hide();
		}
		$('#ala_btn').click(function(){
			if($(this).hasClass('kfOnLine')){
				window.open("https://rinse.mdihi.com/chat/chatClient/chatbox.jsp?companyID=365038495&configID=2605&jid=5074260129&s=1&enterurl=FirstDeposit", "livechat", "height=430, width=550, toolbar= no,directions=no,alwaysRaised=yes,hotkeys =yes, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=200,left=650");
				$('#ala_btn').attr('class','ala_btn');
				$('#ala_btn').html('Veo');
			}else{
				$('#alam_bg').hide();
			}
		});
		$('#newOne_tip').click(function(){
			$('#step2-1').hide();
			$('#step2-3').addClass('slideInLeft').show();
		});
		$('#cancel_deposit').click(function(){
			confirm_z(2,"Después de la cancelación, el depósito no se acreditará en la cuenta. ¿Está seguro de que desea cancelar?");
			$(".md-jugde").click(function(){
				$(".md-modal").removeClass("md-show");
				$(".cover").removeClass("blur-in");
				$(".md-jugde").hide();
				if($(this).index() == 1){
					$.get("ajax_data.php",{"type":"canceldeposit"},function(re){
						$('#step2-1').fadeIn();//.addClass('slideInLeft');
						$('.deposit_step').removeClass('slideInLeft').hide();
						confirm_z('1',re);
					});
				}
			});
		});
	});
	//全局变量
	var valid_flag = false;
        function wechatcheck_form(){
                // mesg = '<span style="color:red">微信转账维护，请使用其他存款渠道</span>';
         //$('#mesg_str').html(mesg);  
        // return false;
            mesg = '<span style="color:red">El tiempo de mantenimiento de transferencia de Wechat es de 23:00 a 00:30 todos los días, utilice otros métodos de depósito durante este período</span>';
			//$('#mesg_str').html(mesg);   
        }
	function check_form(){
             // mesg = '<span style="color:red">微信转账维护，请使用其他存款渠道</span>';
        // $('#mesg_str').html(mesg);  
       //  return false;
		var amount = $('#amount2').val();
		var member_name = $('#username').val();
		var reg = /^[\u4e00-\u9fa5]{2,8}$/;
		var reg1= /^\d+$/;
		var valid = true;
		var mesg = "";
		
		if(amount < 100){
			valid = false;
			mesg = "* El monto del depósito del canal de pago debe ser superior a 100";
			$('.tip_amount').html(mesg);
			$('#amount2').css("borderColor","red");
			$('.tip_amount').css("color","red");
		}else if(amount > 10000){
			valid = false;
			mesg = "* El monto del depósito del canal de pago debe ser inferior a 10000";
			$('.tip_amount').html(mesg);
			$('#amount2').css("borderColor","red");
			$('.tip_amount').css("color","red");
		}else{
			$('#amount2').css("borderColor","#259ee6");
			$('.tip_amount').css("color","#787878");
		}
		
		if(!reg.test(member_name)){
			valid = false;
			mesg = "* Asegúrate de escribir tu nombre real";
			$('.tip_username').html(mesg);
			$('#username').css("borderColor","red");
			$('.tip_username').css("color","red");
		}else{
			$('#username').css("borderColor","#259ee6");
			$('.tip_username').css("color","#787878");
		}
		
		
		
		if(valid){
                     $('.save_button5').attr("onclick","");
                    localStorage.bendimobilename=$('.bendimobilename').val();
			confirm_z("0",'<div class="loding"><i class="fa fa-spinner fa-spin"></i>Procesando el sistema, por favor espere</div>');
			$.ajax({
				url:"center.php",
				data:$("#onlinepay").serialize(),
				type:"POST",
				dataType:"json",
				success:function(d){
					if(d.status == 1){
						$('.md-close').click();
						mesg = '<span style="color:red">¡Felicitaciones por su envío exitoso!</span></br><span style="color:#000">Por favor pague:<span style="color:red;font-size: 22px;line-height: 35px;font-weight: bold;">'+amount+'</span>(Tenga en cuenta el punto decimal)</span>';
						/*if(d.info.id == 1){
							mesg = '由于您的账号为首次存款，请联系我们24小时在线客服协助您办理存款';
							$('#ala_btn').html('联系客服');
							$('#ala_btn').addClass('kfOnLine');
						}*/
						
						if(d.type == 0){
							$('#str1').html(d.info.bank_name);
							$('#str2').html(d.info.account_name);
							$('#str3').html(d.info.bank_no);
							$('#str4').html(d.info.amount);
							 var scode=parseInt(Math.random()*(999999-100000+1)+100000);
									$('#str5').html(scode);
						}else{
							mesg = '<span style="color:red">Lo sentimos, la transferencia interbancaria no está disponible actualmente, seleccione la tarjeta bancaria correspondiente para la transferencia entre pares.</span>';
							$('.content_zz').hide();
							$('.tips').hide();
							$('.inCase').html(d.info);
							$('#str4').html(d.amount);
							$('#str5').html(d.code);
						}	
						
						
						
						
						$('#mesg_str').html(mesg);
						$('#step2-2').addClass('slideInLeft').show();
						$('#step2-1').hide();
						$('#alam_bg').show();
						$('#amount2').val('');
						
					}else{
						confirm_z("1",d.info);
					}
				},
			});
                        $('.save_button5').attr("onclick","check_form()");
		}
	}
	function copy_data(id){
		var clipboard = new Clipboard('#copy'+id);
		clipboard.on('success', function(e) {
			e.clearSelection();
			confirm_z("1","Copiado al portapapeles!");
		});
		clipboard.on('error', function(e){
			confirm_z("1","Este navegador no es compatible con esta característica!");
		});
	}
	function checkdeposit(){
		$.getJSON("ajax_data.php",{"type":"checkdeposit"},function(d){
			if(d.status == 1){
				$('.md-close').click();
				mesg = 'Todavía tiene una solicitud de recarga que no se ha completado, inicie una recarga después de completarla';
				if(d.type == 0){
					$('#str1').html(d.info.bank_name);
					$('#str2').html(d.info.account_name);
					$('#str3').html(d.info.bank_no);
					$('#str4').html(d.info.amount);
                                   
                                       d.info.code=parseInt(Math.random()*(999999-100000+1)+100000);
                                         
					$('#str5').html(d.info.code);
				}else{
					mesg = '<span style="color:red">Lo sentimos, la transferencia interbancaria no está disponible actualmente, seleccione la tarjeta bancaria correspondiente para la transferencia entre pares.</span>';
					$('.content_zz').hide();
					$('.tips').hide();
					$('.inCase').html(d.info);
					$('#str4').html(d.amount);
					$('#str5').html(d.code);
				}	
				$('#mesg_str').html(mesg);
				$('#step2-1').hide();
				$('#step2-2').show();
				$('#alam_bg').show();
			}
		});
	}
	function showBankInfo(id){
		$('.inCase >div').removeClass('act');
		$('#bank'+id).addClass('act');
		$('.content_zz').hide();
		$('.tips').hide();
		$.getJSON('ajax_data.php',{'type':'getDepositBankByBankid','id':id},function(re){
			if(re != ''){
				$('#str1').html(re.bank_name);
				$('#str2').html(re.account_name);
				$('#str3').html(re.bank_no);
				$('.content_zz').show();
				$('.tips').show();
			}
		});
		
	}
</script>



</body>
</html>
		

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		