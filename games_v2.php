<?php

ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

header("Content-type: application/json,");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("client/phprpc_client.php");


define("WEB_PATH", __DIR__);


$api_key = 'fghrtrvdfger';
// $time = substr(time(), 0, -3);
// $auth_check = md5($time . $api_key);
$checksum = "";
$checksum .=  date('y-m-d');
$checksum .= 'games_v2_actions';
$checksum .= $api_key;
$checksum_new = hash('sha256', $checksum);
$auth_check = $checksum_new;
$d = (object) json_decode(file_get_contents('php://input'), true);

$auth = $d->auth;


if ($auth_check != $auth) {
    echo json_encode(array('status' => 0, 'info' => "Verification failed"));
    exit();
}



if (isset($d->type) && $d->type == "get_all_games_data") {
    get_all_games_data();
}

if (isset($d->type) && $d->type == "get_single_games_data") {
    get_single_games_data($d->id);
}

if (isset($d->type) && $d->type == "update_games_data") {
    update_games_data($d);
}

if (isset($d->type) && $d->type == "enable_games_data") {
    enable_games_data($d->id);
}

if (isset($d->type) && $d->type == "disable_games_data") {
    disable_games_data($d->id);
}
if (isset($d->type) && $d->type == "add_new_game") {
    add_new_game($d);
}

if (isset($d->type) && $d->type == "delete_game") {
    delete_game($d->id);
}
if (isset($d->type) && $d->type == "get_all_category") {
    get_all_category();
}

if (isset($d->type) && $d->type == "apply_changes_to_frontend") {
    apply_changes_to_frontend();
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
        $action .= '<button data-toggle="dropdown" class="btn dropdown-toggle btn-primary">Actions</button>';
        $action .= '<ul class="dropdown-menu">';
        // $action .= ($games['enabled'] == 1) ? '<li><a href"#">Disable</a></li>' : '<li><a href"#">Enable</a></li>';
        // $action .= '<li><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModalCenter">Edit</button></li>';



        $enable_btn = '<li><button type="button" class="btn btn-link btn-sm" onclick="enable_game(' . $games['id'] . ')">Enable</button></li>';
        $disable_btn = '<li><button type="button" class="btn btn-link btn-sm" onclick="disable_game(' . $games['id'] . ')">Disable</button></li>';
        $action .= ($games['enabled'] == 1) ?  $disable_btn : $enable_btn;

        // $enable_btn1 = '<li><button type="button" class="btn btn-link btn-sm" onclick="enable_game('.$games['id'].')">Permanently Enable</button></li>';
        // $disable_btn1 = '<li><button type="button" class="btn btn-link btn-sm" onclick="disable_game('.$games['id'].')">Permanently Disable</button></li>';
        // $action .= ($games['permanently_disabled'] == 1) ? $enable_btn1 : $disable_btn1;

        $action .= '<li><button type="button" class="btn btn-link btn-sm" onclick="openEditModal(' . $games['id'] . ')">Edit</button></li>';

        $action .= '<li><button type="button" class="btn btn-link btn-sm" onclick="delete_game(' . $games['id'] . ')">Delete</button></li>';
        $action .= '</ul>';

        $status = ($games['enabled'] == 1) ? 'Enabled' : 'Disabled';


        $response[$key]['actions'] = $action;
        $response[$key]['status'] = $status;
        $response[$key]['category'] = unserialize($response[$key]['category']);
    }

    echo json_encode($response);
    // var_dump($response);
}

function get_single_games_data($id)
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_single_games_data($id);

    $response[0]['category'] = unserialize($response[0]['category']);

    echo json_encode($response[0]);
}

function update_games_data($data)
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->update_games_data($data);
    echo json_encode($response);
}

function enable_games_data($id)
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->enable_games_data($id);
    echo json_encode($response);
}

function disable_games_data($id)
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->disable_games_data($id);
    echo json_encode($response);
}
function delete_game($id)
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->delete_game($id);
    echo json_encode($response);
}


function add_new_game($data)
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->add_new_game($data);
    echo json_encode($response);
}


function get_all_category()
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_all_category();
    echo json_encode($response);
}


function apply_changes_to_frontend()
{

    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_all_games_data();

    $newJson = [];
    $data = [];

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

        $status = ($games['enabled'] == 1) ? 'Enabled' : 'Disabled';

        // $response[$key]['sort'] = $games['position'];
        // $response[$key]['actions'] = $action;
        $response[$key]['status'] = $status;
        $response[$key]['state'] = $status;
        $response[$key]['category'] = unserialize($response[$key]['category']);
        $tag = $response[$key]['category'];

       
        $newJson["sort"] = $games['position'];
        $newJson["eName"] = $games['game_ename'];
        $newJson["name"] = $games['game_cname'];
        $newJson["id"] =  $games['game_code'];
        $newJson["code"] = $games['game_code'];
        $newJson["category"] = "";
        $newJson["type"] = "";
        $newJson["subType"]  = "";
        $newJson["line"]  = "";
        $newJson["jackpot_ticker"]  = $games['jackpot_ticker'];
        $newJson["state"]  = $games['enabled'];
        $newJson["pic"]  = $games['pic'];
        $newJson["tag"]  = $tag;
        $newJson["sourceImge"]  = $games['source_img'];
        $newJson["platform"]  = $games['platform'];
        $newJson["alias_code"]  = $games['alias_code'];


        array_push($data, $newJson);

    }

    // echo json_encode($response);

    $games = json_encode($data);

    $filename = WEB_PATH . '/data/games.json';

    file_put_contents($filename, $games);

    echo json_encode(['success',]);
}
