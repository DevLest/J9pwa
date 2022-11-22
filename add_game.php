<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");

    ini_set('display_errors', '1');

    ini_set('display_startup_errors', '1');

    error_reporting(E_ALL);

    // $file = "https://999j9azx.999game.online/j9pwa/data/games_test.json";
    $file = "https://999j9azx.999game.online/j9pwa/data/games.json";

    $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE );

    $new_game = [];

    array_push($new_game, [
        "enName" => isset($_POST['game_name']) ? $_POST['game_name'] : "",
        "name" => isset($_POST['game_name']) ? $_POST['game_name'] : "",
        "id" => isset($_POST['game_code']) ? $_POST['game_code'] : "",
        "code" => isset($_POST['game_code']) ? $_POST['game_code'] : "",
        "category" => "",
        "type" => "",
        "subType" => "",
        "line" => "",
        "jackpot_ticker" => isset($_POST['jackpot_ticker']) ? $_POST['jackpot_ticker'] : "",
        "state" => isset($_POST['status']) ? $_POST['status'] : "",
        "pic" => isset($_POST['game_code']) ? "https://999j9azx.999game.online/j9pwa/images/games/".$_POST['game_code'].".webp" : "",
        "tag" => isset($_POST['category']) ? $_POST['category'] : "",
        "sourceImge" => "" ,
        "platform" => isset($_POST['platform']) ? $_POST['platform'] : "",
        "alias_code" => isset($_POST['alias_code']) ? $_POST['alias_code'] : ""
    ]);

    array_push($filedata, $new_game[0]);
    
    json_encode(['status'=>0,'info'=> $new_game]);
    
    function removeBomUtf8($s){
        if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){

            return substr($s,3);

        }else{

            return $s;

        }
    }

?>