<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include_once (__DIR__ . "/common/cache_file.class.php");
    
    $filedata = json_decode(removeBomUtf8(file_get_contents(__DIR__."/data/games.json")), JSON_UNESCAPED_UNICODE );
    $game = [];
    
    foreach ($filedata as $detail)
    {
        $params = [
            "gameCode" => $detail['id'],
        ];

        $game_type = "slots";

        $url = "https://data.vsr888.com/game/list";
        $result = https_submit($url, $params);

        if (!$result->error)
        {
            foreach ($result->records as $index => $games)
            {
                $game_type = $games->gameType;
            }
        }

        $game[$detail['id']] = [
            "gameCode" => $detail['id'],
            "gameName" => $detail['name'],
            "gameType" => $game_type,
        ];
    }

    $cachFile = new cache_file();
    $cachFile->set("game_data",$game,'','data','999');
    echo count($game);;
    
    function removeBomUtf8($s){
        if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){
            return substr($s,3);
        }else{
            return $s;
        }
    }
        
    function https_submit($url, $data)
    {
        $curl = curl_init();

        $data['requestId'] = "AqbUONfIDjQh057fIOno";
        $data['brandId'] = 621;

        ksort($data);
        $hash = md5(urldecode( http_build_query( $data ) )."AqbUONfIDjQh057fIOno");
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url?hash=$hash",
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_POST => 1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_TIMEOUT => 100,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8'
            ],
        ));
        
        $response = curl_exec($curl);
        
        if (curl_errno($curl))
        {
            return curl_error($curl);
        }
        curl_close($curl);
    
        $response = json_decode($response);
        
        return ($response != "") ? $response : -1;
    }
?>