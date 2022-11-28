<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include_once (__DIR__ . "../common/cache_file.class.php");
    
    $filedata = json_decode(removeBomUtf8(file_get_contents(__DIR__."../data/games.json")), JSON_UNESCAPED_UNICODE );
    $game = [];
    
    foreach ($filedata as $detail)
    {
        if ( !$detail['state'] ) continue;
        if ( $detail['platform'] != "PT" ) continue;
        if ( ( $detail['jackpot_ticker'] != "" && $detail['platform'] == "PT" ) )
        {
            $id = explode(',', $detail['jackpot_ticker']);
            $url = "https://tickers.playtech.com/js?info=1&casino=1club&currency=usd&game=".$id[0];
            $json_data = explode(";",@file_get_contents($url));
        
            $json = substr(str_replace("\\", "", str_replace("xmlstring.jpxml = ", "", $json_data[0])), 1, -1);
            
            $xml = simplexml_load_string($json);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            
            if (!empty($array )) 
            {
                $game[$detail['id']] = isset($array['gamedata']['amount-list']['amount']) ? floatval($array['gamedata']['amount-list']['amount']) : 0;
            }
        }
    }

    $cachFile = new cache_file();
    $cachFile->set("jackpot",$game,'','data','pt');
    echo count($game);;
    
    function removeBomUtf8($s){
        if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){
            return substr($s,3);
        }else{
            return $s;
        }
    }
?>