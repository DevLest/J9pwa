<?php
header("Content-type: text/html; charset=utf-8");
include_once('base.fun.php');
include_once("sessionstate.php");
include_once("client/phprpc_client.php");
//define("CHARSET","utf-8");
//$result = sendmail("2281262732@qq.com","test","hello","怡宝娱乐");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
error_reporting(E_ALL);
//print_r($_SESSION['account']);exit;
    	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
		echo json_encode(array('status'=>0,'info'=>"校验不成功"));
		exit();
	}

//echo  565646;
	$client = new PHPRPC_Client(QUERY_URL);
	if(isset($_POST['account']))
	{
		if($_POST['account'] =='' || $_POST['email'] == ''){
			//echo "<script>alert('你填写的信息不正确');location.href='http://m.yuebet.com/forgetpwd.php'</script>";
			echo json_encode(array('status'=>0,'info'=>'你填写的信息有误'));
			exit();
		}
		$add_time = date("Y-m-d H:i:s");
		$account = $_POST['account'];
		$email = $_POST['email'];
		$option = array(
				"table"=>"ks_member_info",
				"fields"=>"id",
				"condition"=>"account='$account' and email='$email'"
			);
		$option = serialize($option);
		$result = $client->row($option);
		$result = unserialize($result);
		if(!is_array($result))
		{
			//echo "<script>alert('你填写的信息不正确');location.href='http://m.yuebet.com/forgetpwd.php'</script>";
			echo json_encode(array('status'=>0,'info'=>'你填写的信息不正确'));
			exit();
		}
		$key = create_md5content($result["id"],$account,$email,$add_time);
		$base_key = base64_encode($key);
		$content = "<p>登录密码找回</p>
					<p>尊敬的怡宝玩家 ".$account.":</p>
					<p>(登陆PT/MG客户端账号前请加yb)</p>
					<p>找回密码：<a href='http://www.u2d8899.com/changepwd.php?code=".$base_key."'>点此找回</a></p>
					<p>复制地址：http://www.u2d8899.com/changepwd.php?code=".$base_key."</p>
					<p>如有任何问题，欢迎您随时联系我们的7X24小时在线客服！</p>
					<p>客服邮箱地址：cs@yuebet.ws</p>
					<p>QQ客服：800147322</p>
					<p>怡宝在线娱乐城，亚洲最强老虎机游戏！</p>";
                //echo 5465656;
		$re = sendmail($email,"怡宝登录密码找回（2小时内有效，自动邮件，请勿回复。）",$content,"怡宝娱乐");
               // echo 5456;
		if($re)
		{
			$data = array(
				"member_id"=>$result["id"],
				"account"=>$account,
				"email"=>$email,
				"md5content"=>$key,
				"add_time"=>$add_time
			);
			$data = serialize($data);
			$client->insert("ks_email_getpwd",$data);
			//echo "<script>alert('邮件发送成功，请进入邮箱验证信息。');location.href='http://m.yuebet.com/'</script>";
			echo json_encode(array('status'=>1,'info'=>'邮件发送成功，请进入邮箱验证信息。'));
			exit();
		}else{
                     echo json_encode(array('status'=>1,'info'=>'邮件发送成功，请进入邮箱验证信息。'));
			exit();
			//echo "<script>alert('邮件发送失败，请重试！');location.href='http://m.yuebet.com/'</script>";
			echo json_encode(array('status'=>0,'info'=>'邮件发送失败，请重试！'));
			exit();
		}	
	}else{
		$email = $_POST['email'];
		$option = array(
				"table"=>"ks_member_info",
				"fields"=>"account",
				"condition"=>"email='$email'"
			);
		$option = serialize($option);
		$result = $client->select($option);
		$result = unserialize($result);
		$account = "";
		foreach($result as $v)
		{
			$account .=$v['account'].",";
		}
		$content = "<p>登录帐号找回</p>
					<p>尊敬的怡宝玩家，您的帐号为：".$account."</p>
					<p>(登陆PT/MG客户端账号前请加yb)</p>
					<p>如有任何问题，欢迎您随时联系我们的7X24小时在线客服！</p>
					<p>客服邮箱地址：cs@yuebet.ws</p>
					<p>QQ客服：800147322</p>
					<p>怡宝在线娱乐城，亚洲最强老虎机游戏！</p>";
		$re = sendmail($email,"怡宝登录帐号找回（自动邮件，请勿回复。）",$content,"怡宝娱乐");
		if($re)
		{
			//echo "<script>alert('邮件发送成功，请进入邮箱验证信息。');location.href='http://m.yuebet.com/'</script>";
			echo json_encode(array('status'=>1,'info'=>'邮件发送成功，请进入邮箱验证信息。'));
			exit();
		}else{
                    
                   
			//echo "<script>alert('邮件发送失败，请重试！');location.href='http://m.yuebet.com/'</script>";
			echo json_encode(array('status'=>0,'info'=>'邮件发送失败，请重试！'));
			exit();
		}	
	}
	
	
	
	






function sendmail($toemail, $subject, $message, $sitename='') {
	include_once("email/PHPMailer.class.php");
    include_once("email/smtp.class.php");
	$config = array(
		'Host' => "smtp.zoho.com", // 您的企业邮局域名
		'SMTPAuth' => true,// 启用SMTP验证功能
		'Port' => '465',   //SMTP端口
		'Username' => 'yuebet@jr71.com', // 邮局用户名(请填写完整的email地址)
		'Password' => '1q2w3e4r!Q@W#E$R', // 邮局密码
		'From' => 'yuebet@jr71.com',	 //邮件发送者email地址
		'FromName' => 'yuebet',	 //邮件发送者名称
	);
	$mail = new PHPMailer();
	$mail->IsSMTP(); // 使用SMTP方式发送
	$mail->CharSet='UTF-8';// 设置邮件的字符编码
	$mail->Host = $config["Host"]; // 您的企业邮局域名
	$mail->SMTPAuth = true; // 启用SMTP验证功能
	$mail->Port = $config["Port"]; //SMTP端口
	$mail->SMTPSecure = 'ssl';  // 启用加密传输
	$mail->Username = $config["Username"]; // 邮局用户名(请填写完整的email地址)
	$mail->Password = $config["Password"]; // 邮局密码
	$mail->From = $config["From"]; //邮件发送者email地址
	$mail->FromName = $config["FromName"];
	$mail->AddAddress($toemail, "");//要发送的邮箱地址
	$mail->Subject = $subject; //邮件标题
	$mail->Body = $message; //邮件内容
	$mail->isHTML(true);
	$result = $mail->Send();
	return $result;	
}

function create_md5content($id,$account,$email,$add_time)
{
	return md5($id.''.md5($id.$account.$email.$add_time));
}
?>