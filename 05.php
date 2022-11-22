<?php 
function aac(){
date_default_timezone_set('Asia/Hong_Kong');
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include_once("phpseclib/Crypt/RSA.php");

$privateKey = '-----BEGIN PRIVATE KEY-----
MIIBVQIBADANBgkqhkiG9w0BAQEFAASCAT8wggE7AgEAAkEAnsIsZSTjVIktHV9k
O/+PVh8h49zYflhnghpKpOToW5URJAKR1M35rEWs2Vq7wlx8G9xWGA2S05IpVOjV
vobCCwIDAQABAkAWhrCr7U8ASLKJD2b2iG17J9G0NjrVuo99S2O5/+zkSYjbueny
6npBAOFtJ7JJwzasOdQhpqrzNq+HJ2HAr1tRAiEA+xpWvosMGvj18idV0UtsQbnJ
my6a2gN0AjhP+ChM/DkCIQCh2stNB55vMPtBgg8P64p+SYCztqIxwTvyeqQ867r4
YwIhANAvxju0jRTP1RowArbEEb1si/pdaYXX1xcAGU1mHG4BAiBi/8cWSLC55kXo
3bqEzFebwy27vtwafs1CFY3bzXxBbQIhANDI9bw83vAy40VVJvS3SODA3qTw1gwf
VeOe19MPeXGZ
-----END PRIVATE KEY-----';

$api_url = "http://u2dcny.ebet.im:8888/";
$account = 'test02';

$endTime = date('Y-m-d H:m:s');
$startTime = date('Y-m-d H:m:s', strtotime('-30 days', strtotime(date('Y-m-d 00:00:00'))));

$url = 'api/userbethistory';
$time = time();

$rsa = new Crypt_RSA();
$rsa->loadKey($privateKey);    
$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
$rsa->setHash("md5");
//  Change signature if account search
  $signature = $rsa->sign($account.$time);
//$signature = $rsa->sign($time);
$encrypted= base64_encode($signature);
$params = [
        "channelId" => 1340,
        "timestamp" => $time,
        "signature" => $encrypted,
        // "username" => $account,
        "currency" => "USD",
        // "subChannelId" => 0,
        "startTimeStr" => $startTime,
        "endTimeStr" => $endTime,
        "pageNum" => 1,
        "pageSize" => 50,
        "betStatus" => 1,
        "gameType" => 1,
        "isSeparate" => false,
        "judgeTime" => 0,
];
        
    $data = json_encode($params);

    $curl = curl_init($api_url.$url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        )
    );

    $result = curl_exec($curl);
	//print_r($result);
    return json_decode($result);
    // echo '<pre>';
    // print_r($result);
    // echo '</pre>';
}

print_r(aac());
?>