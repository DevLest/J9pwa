<?php

header("Content-type: application/json,");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("client/phprpc_client.php");

$api_key = 'fghrtrvdfger';
$time = substr(time(), 0, -3);
$auth_check = md5($time . $api_key);

$d = (object) json_decode(file_get_contents('php://input'), true);

$auth = $d->auth;


if ($auth_check != $auth) {
    echo json_encode(array('status' => 0, 'info' => "Verification failed"));
    exit();
}



if (isset($d->type) && $d->type == "get_all_games_data") {
    get_all_games_data();
}

function get_all_games_data()
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_all_games_data();

    
    foreach ($response as $key => $games) {
        // "id":2,
        // "position":1,
        // "game_id":"vs1masterjoker",
        // "game_code":"vs1masterjoker",
        // "game_ename":"Master Joker",
        // "game_cname":"Master Joker",
        // "currency":"",
        // "enabled":1,
        // "permanently_disabled":0,
        // "category":"['SLOTS']",
        // "platform":"PP",
        // "jackpot_ticker":"",
        // "alias_code":"",
        // "pic":"https:\/\/999j9azx.u2d8899.com\/j9pwa\/images\/games\/vs1masterjoker.webp",
        // "source_img":"https:\/\/img.dyn123.com\/images\/slot-images\/PP\/masterjoker.png",
        // "active":1,
        // "created_at":"2022-11-19 18:25:40",
        // "updated_at":"2022-11-19 18:25:40"
        $action = '';
        $action .= '<div class="btn-group">';
        $action .= '<button data-toggle="dropdown" class="btn dropdown-toggle">Actions</button>';
        $action .= '<ul class="dropdown-menu">';
        $action .= ($games['enabled'] == 1) ? '<li><a href"#">Disable</a></li>' : '<li><a href"#">Enable</a></li>';
        $action .= '<li>1<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModalCenter">Edit</button></li>';
        $action .= '</ul>';

        $status = ($games['enabled'] == 1) ? 'Enabled' : 'Disabled';


        $response[$key]['actions'] = $action;
        $response[$key]['status'] = $status;
    }

    echo json_encode($response);
    // var_dump($response);
}