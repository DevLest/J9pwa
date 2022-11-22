<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");
    define("WEB_PATH", __DIR__);
    
    include_once WEB_PATH . "/common/cache_file.class.php";
    $cachFile = new cache_file();
    $data_list = $cachFile->get("s6_api", '', 'data', 'currency');

    $params = [
        'api_key' => 'ymOvnKnMvCiNrB1M0VoN0YR1',
        'nonce' => '555647',
        'timestamp' => time(),
    ];
    
    $md5str = "";
    foreach ($params as $key => $val) {
        $md5str = $md5str . $key . "=" . $val . "&";
    }
    $md5str= substr($md5str,0,-1);
    
    $sign = md5($md5str."t8Ol8wkBglmOAb6r7dPkko8f93LQlloC9IsNIb9Q5QiASL9avx0u3Nj8O4mPcInz5lGg0BwRhZA1Rf7ZONpQLqDCEcyZuDyFukE");
    $params['sign']= $sign ;
    
    $ch = curl_init();	
    curl_setopt($ch,CURLOPT_URL, "https://api.s6nn.com/V1/Market/getCoinList");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);

    $cachFile->set("s6_api", $response, '', 'data', 'currency');
    echo "done";
    exit();
?>