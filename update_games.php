<?php

    ini_set('display_errors', '1');

    ini_set('display_startup_errors', '1');

    error_reporting(E_ALL);



    $api_key='fghrtrvdfger';

    $time = substr(time(),0,-3);



    $auth_check = md5($time.$api_key);



    $auth = $_GET['auth'];



    if($auth_check != $auth)

    {

        echo json_encode(['status'=>0,'info'=>"Can't do"], JSON_UNESCAPED_UNICODE );

        exit();

    }



    $file = "/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/data/games.json";

    $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE );

    $newJson = [];



    foreach ($filedata as $detail)

    {

        if ($detail['id'] == $_GET['id'] && $detail['name'] == $_GET['name']) 

        {

            if (isset($_GET['state'])) {

                $detail['state'] = json_decode($_GET['state']);

            }

            if (isset($_GET['tag'])) {

                $detail['tag'] = explode(",",$_GET['tag']);

            }

        }



        array_push($newJson, $detail);

    }



    file_put_contents($file, json_encode($newJson));



    return json_encode(['status'=>1,'info'=>json_encode($newJson)], JSON_UNESCAPED_UNICODE );

    

    function removeBomUtf8($s){

        if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){

            return substr($s,3);

        }else{

            return $s;

        }

    }
