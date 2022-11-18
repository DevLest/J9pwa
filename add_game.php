<?php

    ini_set('display_errors', '1');

    ini_set('display_startup_errors', '1');

    error_reporting(E_ALL);



    $data = (Object) $_POST;

    $file = "https://999j9azx.u2d8899.com/j9pwa/data/games_test.json";

    $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE );

    $new_game = [];

    print_r($data);
        //upload img
        // $file_name = $_FILES['game_image']['name'];
        // $file_size =$_FILES['game_image']['size'];
        // $file_tmp =$_FILES['game_image']['tmp_name'];
        // $file_type=$_FILES['game_image']['type'];
        // $file_ext=explode('.',$_FILES['game_image']['name']);
        // $file_ext=strtolower($file_ext[1]);

        //  move_uploaded_file($file_tmp,"https://999j9azx.u2d8899.com/j9pwa/images/games/".$_POST["game_code"].".".$file_ext);
        //  echo "Success";

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
            "alias_code" => isset($_POST['alias_code']) ? $_POST['alias_code'] : ""
        ]);

        array_push($filedata, $new_game[0]);

        file_put_contents("data/games_test.json", json_encode($filedata));



    
    echo json_encode(['status'=>1,'info'=> "OK"], JSON_UNESCAPED_UNICODE );

    

    function removeBomUtf8($s){

        if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){

            return substr($s,3);

        }else{

            return $s;

        }

    }

?>