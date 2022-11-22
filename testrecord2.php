<?php

header("Content-type: text/html; charset=utf-8");
   $token='test02';
    $timeStamp = generate_imsb_time_stamp(return_timestamp(),'cae2e1ed7185b841');
    $postdata = array(
        'TimeStamp'=>$timeStamp,
        'Token'=>$token,
        'BetConfirmationStatus'=>[1,2,3,4],
        'LanguageCode'=>"CHS",
          'MemberCode'=>'test02',
        "StartDate" => date("Y-m-d", strtotime('today - 7 days')),
		"EndDate" => date("Y-m-d"),
    );
    $ch = curl_init();

   $header = array ();  
  
    $header [] = 'Content-Type:application/json';  
 
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );  
	curl_setopt($ch,CURLOPT_URL,"http://ipis-u2d668.imapi.net/api/mobile/GETBETLIST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response=curl_exec($ch);
	
	//$res=simplexml_load_string($response);
	curl_close($ch);
    print_r(json_decode($response));
  
function generate_imsb_time_stamp($data, $secret_key)
{
	$secret_key = md5(utf8_encode($secret_key), true);
	$result = openssl_encrypt(
		$data,
		"aes-128-ecb",
		$secret_key,
		$options = OPENSSL_RAW_DATA
	);
	return base64_encode($result);
}
function return_timestamp()
{
	date_default_timezone_set("GMT");
	$date = date("D, d M Y H:i:s") . " GMT";
	return $date;
}
function formatted_date($date)
{
	date_default_timezone_set("Etc/GMT+4");
	$date = date_create($date);

	return date_format($date, "c");
}

?>