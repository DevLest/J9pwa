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
	<link rel="stylesheet" href="newCss/animate.css">
	<script src="newJs/jquery_2.1.3.js"></script> 
	
	<style>
	body{color: #787878;font-size:13px;}
	</style>
</head>
<body style="background:#fff;">
<div class="all_cover"></div>
<div class="content">
	<!--<div class="header">
		<span class="index_l back"></span>
		<span id="title">好友推荐</span>
		<span class="index_r index_kf" id="index_r"></span>
		<div class="index_r_content animated fadeIn">
			<a href="javascript:qq_cs();"><span class="ico_qq"></span><span class="text">QQ客服</span></a>
			<a href="javascript:cs();"><span class="ico_800"></span><span class="text">800客服</span></a>
		</div>
	</div>-->
	<div class="content_main_sub animated fadeInUp">
		<div class="friends_link">
			<div class="fl_top">
				<span>Su enlace de referencia exclusivo y código QR:</span>
				<span id="str1" >http://www.ss88ss88.com/ss88ss88/friend.php?act=<?php echo $_SESSION['account']?></span>
				<span id="copy1" onclick="copy_data(1)" data-clipboard-target="#str1">Copiar link</span>
			</div>
			<div class="fl_bottom">
				<div class="fl_bottom_left">
					<img src="yunzfqrcode.php?url=http://www.ss88ss88.com/ss88ss88/friend.php?act=<?php echo $_SESSION['account']?>" width="60%"/>
					<div>Otros pueden convertirse en tus amigos recomendados escaneando el código QR para registrarse.</div>
					<div style="color:red;">Utilice un navegador para escanear el código QR (como UC, QQ y otros navegadores)</div>
				</div>
				<div class="form_title">Registro de registro de amigos</div>
				<div class="fl_bottom_right">
					<table class="fri_list" id="agent_table">
						<tr>
							<td>Cuenta de amigo</td>
							<td>Tiempo de registro</td>
							<td>Funcionar</td>
						</tr>
						<tr>
							<td colspan="3">Cargando...</td>
						</tr>
					</table>
					<nav class="record_pagination">
						<ul id="record_pagination" class="pagination pagination-sm">
						</ul>
					</nav>
				</div>
			</div>
			<div class="get_fy_table">
				<div class="form_title">Registro de solicitud de bonificación por recomendación de amigos</div>
				<table class="fri_list" id="promotions_table">
					<tr>
						<td>Bote</td>
						<td>Cuenta de amigo</td>
						<td>Estado de la aplicación</td>
						<td>Tiempo de aplicación</td>
						<td>Observación</td>
					</tr>
					<tr>
						<td colspan="5">Cargando...</td>
					</tr>
				</table>
				<nav class="record_pagination">
					<ul id="record_pagination1" class="pagination pagination-sm">
					</ul>
				</nav>
			</div>
		</div>
		
	</div>
</div>

<script src="newJs/public.js?Version0411"></script>
<script src="newJs/clipboard.min.js"></script> 
<script src="newJs/bootstrap.paginator.js"></script> 
<script>
	$(function(){

		data_page_agent("agent",1); //获取自动优惠第一页的数据
		count_page("agent");  //获取页面的总页数
				
		data_page_agent("promotions",1); //获取自动优惠第一页的数据
		count_page("promotions");  //获取页面的总页数
	});
	
	var applyBtn = true;
	function apply_recdFriends(id)
	{
		if(applyBtn){
			zdwaiting();
			applyBtn = false;
			$.ajax({
				url:"action.php?code="+Math.random(),
				type:"POST",
				data:{
					act:"applyFriendsPro",
					acc:$('#acc_'+id).text(),
					regTime:$('#regT_'+id).text(),
				},
				dataType:'json',
				timeout: 10000,
				success: function(data){
					if(data.status == true){
						data_page_agent("promotions",1); //获取自动优惠第一页的数据
						count_page("promotions");  //获取页面的总页数
					}
					zdalert('Insinuación',data.message);
					applyBtn = true;
				}
			})
		}
	}
	function copy_data(id){
		var clipboard = new Clipboard('#copy'+id);
		clipboard.on('success', function(e) {
			e.clearSelection();
			zdalert("Aviso "," ¡copiado al portapapeles!");
		});
		clipboard.on('error', function(e){
			zdalert("Prompt","¡Este navegador no es compatible con esta función!");
		});
	}

	//数据分页函数
	function data_page_agent(record_type,page)
	{
		zdwaiting();
		$.ajax({
			type: "POST",
			url:"ajax_data.php",
			data:"type=record_list&record_type="+record_type+"&page="+page,
			success:function(msg){
				$('#mb_box').remove();
				$("#"+record_type+"_table").html(msg);
			}
		});
	}
	//获取数据分页的总页数
	function count_page(record_type)
	{
		$.ajax({
			type: "POST",
			url:"ajax_data.php",
			data:"type=count_record&record_type="+record_type,
			success:function(msg){
				var options = {
						bootstrapMajorVersion:3,
						currentPage: 1,//当前页面
						numberOfPages: 3,//一页显示几个按钮（在ul里面生成5个li）
						totalPages:msg, //总页数
						size:"small",
						onPageClicked:function (e, originalEvent, type, page) {  
							data_page_agent(record_type,page); 
						}
					};
				if(record_type == 'promotions'){
				   $('#record_pagination1').bootstrapPaginator(options);
				}else{
					$('#record_pagination').bootstrapPaginator(options);
				}
			}
		});
	}	
</script>
</body>
</html>