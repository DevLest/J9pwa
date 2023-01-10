<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include_once (__DIR__ . "/../common/cache_file.class.php");
    
    $filedata = json_decode(removeBomUtf8(file_get_contents(__DIR__."/../data/games.json")), JSON_UNESCAPED_UNICODE );
    $game = [];
    $skip = [
        'sw_2pd'
    ];
    
    foreach ($filedata as $detail)
    {
        if ( !$detail['state'] ) continue;
        if ( $detail['platform'] != "SW" ) continue;
        if ( ( $detail['jackpot_ticker'] != "" && $detail['platform'] == "SW" ) )
        {
            $id = explode(',', $detail['jackpot_ticker']);
            $url = "https://jpn-ticker-eu-gcp-str.ss211208.com/v1/ticker?currency=USD&jackpotIds=".$id[0];
            
            $array = json_decode(@file_get_contents($url),TRUE);
            
            if (!empty($array )) 
            {
                $pools = (!in_array($skip, $detail['id'])) ? $array[0]['pools'] : 10000;
                usort($pools, function($a, $b) 
                { 
                    if($a['amount']==$b['amount']) return 0;
                    return $a['amount'] < $b['amount'] ? 1 : -1;
                });

                $pool = array_values($pools)[0];
                print_r( $detail['tag'] ); echo " | ". $detail['id']."\n";
                $game[$detail['id']] = $pool['amount'] > 0 ? $pool['amount'] : 0;
            }
        }
    }

    $cachFile = new cache_file();
    $cachFile->set("jackpot_amount",$game,'','data','sw');
    echo count($game);;
    
    function removeBomUtf8($s){
        if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){
            return substr($s,3);
        }else{
            return $s;
        }
    }
?>