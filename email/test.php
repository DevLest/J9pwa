<?php
include_once("PHPMailer.class.php");
include_once("smtp.class.php");

$mail = new PHPMailer();

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

// $mail->isSMTP();                                      // Set mailer to use SMTP
// $mail->CharSet='UTF-8';// 设置邮件的字符编码
// $mail->Host = 'smtp.zoho.com';  // Specify main and backup SMTP servers
// $mail->SMTPAuth = true;                               // Enable SMTP authentication
// $mail->Username = 'kaisheng@jr71.com';                 // SMTP username
// $mail->Password = 'abc123456abc';                           // SMTP password
// $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
// $mail->Port = 465;                                    // TCP port to connect to

// $mail->setFrom('kaisheng@jr71.com', 'kaisheng');
// $mail->addAddress('2281262732@qq.com', '');     // Add a recipient

// $mail->isHTML(true);                                  // Set email format to HTML

// $mail->Subject = "asdfasdf"; //邮件标题
// $mail->Body = "lasdfjlssdfasdfdjfldsf1111"; //邮件内容
// $mail->isHTML(true);

// if(!$mail->send()) {
    // echo 'Message could not be sent.';
    // echo 'Mailer Error: ' . $mail->ErrorInfo;
// } else {
    // echo 'Message has been sent';
// }
phpinfo();
$result = sendmail("3209891827@qq.com","tiajd11slfj","helloksdlfjadskf");
var_dump($result);
function sendmail($toemail, $subject, $message, $sitename='') {
	// include_once("email/PHPMailer.class.php");
    // include_once("email/smtp.class.php");
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
	$mail->SMTPDebug = 4;
	$result = $mail->Send();
	var_dump($mail->ErrorInfo);
	return $result;	
}