<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");

    ini_set('display_errors', '1');

    ini_set('display_startup_errors', '1');

    error_reporting(E_ALL);

    // $file = "https://999j9azx.u2d8899.com/j9pwa/data/games_test.json";
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
        "pic" => isset($_POST['game_code']) ? "https://999j9azx.u2d8899.com/j9pwa/images/games/".$_POST['game_code'].".webp" : "",
        "tag" => isset($_POST['category']) ? $_POST['category'] : "",
        "sourceImge" => "" ,
        "platform" => isset($_POST['platform']) ? $_POST['platform'] : "",
        "alias_code" => isset($_POST['alies_code']) ? $_POST['alies_code'] : ""
    ]);

    if(isset($_FILES["game_image"])) {
        $filename = $_FILES["game_image"]["name"];
        $tempname = $_FILES["game_image"]["tmp_name"]; 
        $upload_name = isset($_POST["game_code"]) ? "/".$_POST["game_code"].".webp" : "";
        $destination = "images/games";

        if(!move_uploaded_file($tempname, $destination.$upload_name)) {
            echo json_encode(['status'=>0,'info'=> "Error uploading image! failed to save the game."]);
            exit();
        }
    }

    array_push($filedata, $new_game[0]);
    $games = json_encode($filedata);

    if(file_put_contents("/www/wwwroot/999j9azx.999game.online/j9pwa/data/games.json", $games) === false) {
        echo json_encode(['status'=>0,'info'=> "Error saving game!"]);
        exit();
    }

    echo json_encode(['status'=>1,'info'=> $new_game]);
    
    function removeBomUtf8($s){
        if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){

            return substr($s,3);

        }else{

            return $s;

        }
    }

?>