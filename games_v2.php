<?php

ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Expose-Headers: Content-Length");

include_once("client/phprpc_client.php");


define("WEB_PATH", __DIR__);


$api_key = 'fghrtrvdfger';
// $time = substr(time(), 0, -3);
// $auth_check = md5($time . $api_key);
$checksum = "";
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

    ini_set('memory_limit', '-1');

    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_all_games_data();

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


function apply_changes_to_frontend()
{

    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforgames_v2.php");
    $response = $client->get_all_games_data();

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


function make_comparer() {
    // Normalize criteria up front so that the comparer finds everything tidy
    $criteria = func_get_args();
    foreach ($criteria as $index => $criterion) {
        $criteria[$index] = is_array($criterion)
            ? array_pad($criterion, 3, null)
            : array($criterion, SORT_ASC, null);
    }

    return function($first, $second) use (&$criteria) {
        foreach ($criteria as $criterion) {
            // How will we compare this round?
            list($column, $sortOrder, $projection) = $criterion;
            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

            // If a projection was defined project the values now
            if ($projection) {
                $lhs = call_user_func($projection, $first[$column]);
                $rhs = call_user_func($projection, $second[$column]);
            }
            else {
                $lhs = $first[$column];
                $rhs = $second[$column];
            }

            // Do the actual comparison; do not return if equal
            if ($lhs < $rhs) {
                return -1 * $sortOrder;
            }
            else if ($lhs > $rhs) {
                return 1 * $sortOrder;
            }
        }

        return 0; // tiebreakers exhausted, so $first == $second
    };
}