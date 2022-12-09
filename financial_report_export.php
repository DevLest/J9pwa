<?php

ini_set('memory_limit', '-1');

ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

include_once("client/phprpc_client.php");

define("WEB_PATH", __DIR__);

// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
// header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Expose-Headers: Content-Length");
// header('Content-Type: application/json');
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");

// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(200);
//     die();
// }

// // dt_server_side_processing
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $d = $_GET;
//     if(isset($_GET['dt_server_side_processing']) && $_GET['dt_server_side_processing'] == 1 ) {
//         // header('Content-Type: text/html; charset=UTF-8');
//         dt_server_side_processing($d);
//     }
//     exit();
// }


$api_key = 'fghrtrvdfger';
// $time = substr(time(), 0, -3);
// $auth_check = md5($time . $api_key);
$checksum = "";
$checksum .= 'financial_reports';
$checksum .= $api_key;
$checksum_new = hash('sha256', $checksum);
$auth_check = $checksum_new;

$d = (object) json_decode(file_get_contents('php://input'), true);

$auth = $d->auth;


if ($auth_check != $auth) {
    echo json_encode(array('status' => 0, 'info' => "Verification failed"));
    exit();
}


if (isset($d->type) && $d->type == "export_financial") {
    export_financial($d);
}


function export_financial($d)
{   
    date_default_timezone_set('Asia/Manila');

    // $date1 = date('Y-m-d H:i:s');
    $date1 = "2022-11-30 17:33:07";
    $d->date = (new DateTime($date1))->format("Ymd");
    
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforfinancial_report.php");
    $response = $client->export_financial($d);

    // var_dump($response);

    echo json_encode($response);
}

function get_all_games_data($d)
{

    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_all_games_data($d);

    // echo count($response);
    // die();

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

        $newJson["platform"]  = $games['platform'] . '('.$games['seller_name'].')';
        

        $response[$key]['platform'] = $games['platform'] . ' ('.$games['seller_name'].')';

        $response[$key]['actions'] = $action;
        $response[$key]['status'] = $status;
        $response[$key]['category'] = unserialize($response[$key]['category']);
    }

    
    // ob_start();
    echo json_encode($response);
    // //would normally get printed to the screen/output to browser
    // $output = ob_get_contents();
    // $length = strlen($output);
    // ob_end_clean();

    // header('Content-Length: '.$length);

    // echo $output;
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


function games_total_count()
{
    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->games_total_count();
    echo json_encode($response);
}


function apply_changes_to_frontend()
{

    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_all_games_data_v2();

    // GET ALL CATEGORIES
    // $category_list = [];
    // foreach ($response as $key => $games) {
    //     $category = unserialize($response[$key]['category']);

    //     foreach ($category as $val) {
    //         if (!in_array($val, $category_list)) {
    //             array_push($category_list, $val);
    //         }
    //     }
    // }
    // "category_list" value is now
    // e.g.
    // [category_list] => Array
    //     (
    //         [0] => SLOTS
    //         [1] => NEW
    //     )

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
        $newJson["seller_name"]  = $games['seller_name'];
        $newJson["alias_code"]  = $games['alias_code'];


        array_push($data, $newJson);
    }


    // $games = json_encode($data);

    // echo $games;
    // echo json_encode($response);
    
    // echo json_encode($data);
    // die();

    $x = array();
    foreach ($data as $key => $row)
    {
        $x[$key] = $row['sort'];
    }
    // array_multisort($x, SORT_DESC, $data);
    array_multisort($x, SORT_ASC, $data);
    // echo json_encode($data);
    // die();

    $games = json_encode($data);

    $filename = WEB_PATH . '/data/games.json';

    file_put_contents($filename, $games);

    echo json_encode(['success',]);
}
