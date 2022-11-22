<?php

header("Content-type: text/html; charset=utf-8");
   // $date = date("Y-m-d h:m:s A");
    // $startTime = date("Y-m-d h:m:s A", strtotime($date) - (10 * 60) );
  //  $startTime = date("Y-m-d h:m:s A", (strtotime("-1 days")));
 $date = date("Y-m-d h:m:s A");
    // $startTime = date("Y-m-d h:m:s A", strtotime($date) - (10 * 60) );
    $startTime = date("Y-m-d h:m:s A", (strtotime("-1 days")));
    $postData = [
        'timeStamp' => generate_imsb_time_stamp(return_timestamp(),'cae2e1ed7185b841'),
      //  'memberCode' => $account,
        'currencyCode' => "RMB",
        'sportsId' => 1,
        'dateFilterType' => "1",
        'startDateTime' => '2021-06-29 5:06:49 AM',
        'endDateTime' => '2021-06-29 10:06:49 AM',
       // 'betStatus' => 1,
        'languageCode' => "CHS"
    ];
print_r($postData);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://u2d668.sfsbws.imapi.net/api/getBetDetails",
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_POST => 1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => array(
            'Content-Type:application/json; charset=utf-8'
        ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);

print_r(json_decode($response));exit;
print_r(array_reverse(json_decode($response)->wagers));exit;

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

  

?>