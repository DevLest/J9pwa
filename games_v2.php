<?php

header("Content-type: application/json,");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("client/phprpc_client.php");


$gameIDs = [

    "USDT" => 1232,

    "MBTC" => 1236,

    "METH" => 1238,

    "USD" => 1240,

];







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

    $currency = [

        "BGAMING" => ["mBTC", "USDT", "mETH", "USD"],

        "BNG" => ["mBTC", "USDT", "mETH", "USD"],

        "CALETA" => ["mBTC", "USDT", "mETH", "USD"],

        "ENDORPHINA" => ["mBTC", "USDT", "mETH", "USD"],

        "HAB" => ["mBTC", "USDT", "mETH", "USD"],

        "MG" => ["USD"],

        "PGSOFT" => ["mBTC", "USD"],

        "PNG" => ["mBTC", "USD"],

        "PP" => ["mBTC", "USDT", "mETH", "USD"],

        "PS" => ["mBTC", "USDT", "mETH", "USD"],

        "PT" => ["USD"],

        "PG" => ["USD"],

        "RB" => ["USD"],

        "RELAX" => ["USD"],

        "SPB" => ["mBTC", "mETH", "USD"],

        "SS" => ["mBTC", "USDT", "mETH", "USD"],

        "SW" => ["USD"],

        "BG" => ["mBTC", "USDT", "mETH", "USD"],

        "CQ9" => ["mBTC", "USDT", "mETH", "USD"],

        "EM" => ["USD"],

        "EZG" => ["mBTC", "USDT", "mETH", "USD"],

        "MPLAY" => ["mBTC", "mETH", "USD"],

        "REVOLVER" => ["mBTC", "mETH", "USD"],

        "RELAX" => ["USD"],

        "TB" => ["mBTC", "USDT", "mETH", "USD"],

        "REDTIGER" => ["USD"],

        "NETENT" => ["USD"],

        "GAMEART" => ["USD"],

        "EVO" => ["USD"],

        "BETBY" => ["USD"],

    ];



    $platformNames = [

        "BGAMING" => "BGaming",

        "BNG" => "Booongo",

        "CALETA" => "Caleta",

        "ENDORPHINA" => "Endorphina",

        "HAB" => "Habanero",

        "MG" => "Microgaming",

        "PGSOFT" => "PGSoft",

        "PNG" => "Play'N'GO",

        "PP" => "PragmaticPlay",

        "PS" => "Playson",

        "PT" => "PlayTech",

        "PG" => "PocketGames",

        "RB" => "RubyPlay",

        "RELAX" => "Relax",

        "SPB" => "Spribe",

        "SS" => "SuperSpade",

        "SW" => "Skywind",

        "BG" => "BetGames",

        "CQ9" => "CQGames",

        "EM" => "EveryMatrix",

        "EZG" => "Ezugi",

        "MPLAY" => "MPlay",

        "REVOLVER" => "Revolver",

        "RELAX" => "RELAX",

        "RP" => "RubyPlay",

        "TB" => "TVB",

        "GAMEART" => "GameArt",

        "EVO" => "Evolution",

        "BETBY" => "Betby",

        "REDTIGER" => "RedTiger",

        "NETENT" => "Netent",

        "RT" => "RedTiger",

        "NT" => "Netent",

    ];



    $pin_games = [

        'spb_aviator',

        'pgsoft_95',

        'SGHotHotFruit',

    ];


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
        // $action = '';
        // $action .= '<div class="btn-group">';
        // $action .= '<button data-toggle="dropdown" class="btn dropdown-toggle btn-primary">Actions</button>';
        // $action .= '<ul class="dropdown-menu">';
        // $action .= ($games['enabled'] == 1) ? '<li><a href"#">Disable</a></li>' : '<li><a href"#">Enable</a></li>';
        // $action .= '<li><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModalCenter">Edit</button></li>';
        $status = ($games['enabled'] == 1) ? 'Enabled' : 'Disabled';

        $response[$key]['sort'] = $games['position'];
        // $response[$key]['actions'] = $action;
        $response[$key]['status'] = $status;
        $response[$key]['state'] = $status;
        $response[$key]['category'] = unserialize($response[$key]['category']);
    }

    // echo json_encode($response);

    // $games = json_encode($response);

    // file_put_contents("/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/data/games_list_v2.json", $games);

    // echo json_encode(['success']);


    // die();

    // $file = "/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/data/games.json";

    // $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE);

    $newJson = [];

    $pinned = [];



    foreach ($response as $detail) {

        if (!$detail['state']) {

            continue;
        }



        // $jackpot = ($detail['jackpot_ticker'] != "" && in_array($detail['platform'], ["PT", "SW"])) ? getJackpot($detail['game_id'], $detail['platform']) : 0;

        $jackpot = ($detail['jackpot_ticker'] != "" && in_array($detail['platform'], ["PT", "SW"])) ? ((in_array($detail['platform'], ["PT", "SW"])) ? 100000 : getJackpot($detail['game_id'], $detail['platform'])) : 0;



        $currency_data = [];



        if (isset($platformNames[$detail['platform']])) {



            foreach ($currency[$detail['platform']] as $curr) {

                array_push($currency_data, [

                    "symbol" => $curr,

                    "gameId" => (isset($gameIDs[strtoupper($curr)])) ? $gameIDs[strtoupper($curr)] : 1240,

                    "icon" => "https://999j9azx.u2d8899.com/j9pwa/images/$curr.svg",

                ]);
            }



            if (in_array($detail['game_id'], $pin_games)) {

                array_push($pinned, [

                    "name" => $detail['game_ename'],

                    "imgURL" => $detail['pic'],

                    "platform" => $platformNames[$detail['platform']],

                    "category" => $detail['category'],

                    "currency" => $currency_data,

                    "gameInfo" => [

                        "gameCode" => $detail['game_id'],

                        "gameCodeAlias" => isset($detail['alias_code']) ? explode(",", $detail['alias_code'])[0] : "",

                        "jackpot_amount" => $jackpot,

                    ],
                    "sort" => $detail['position'],

                ]);

                continue;
            } else {

                foreach ($detail['category'] as $category) {

                    $categoryId = strtolower(str_replace(' ', '_', $category));

                    if (!isset($newJson[$categoryId])) {

                        $newJson[$categoryId] = [];
                    }



                    array_push($newJson[$categoryId], [

                        "name" => $detail['game_ename'],

                        "imgURL" => $detail['pic'],

                        "platform" => $platformNames[$detail['platform']],

                        "category" => $detail['category'],

                        "currency" => $currency_data,

                        "gameInfo" => [

                            "gameCode" => $detail['game_id'],

                            "gameCodeAlias" => isset($detail['alias_code']) ? explode(",", $detail['alias_code'])[0] : "",

                            "jackpot_amount" => $jackpot,

                        ],
                        "sort" => $detail['position'],
                    ]);
                }
            }
        } else continue;
    }



    foreach ($newJson as $index => $value) {



        shuffle($value);



        usort($value, function ($a, $b) {

            if ($a['gameInfo']['jackpot_amount'] == $b['gameInfo']['jackpot_amount']) {

                return 0;
            }



            return $a['gameInfo']['jackpot_amount'] < $b['gameInfo']['jackpot_amount'] ? 1 : -1;
        });



        $newJson[$index] = $value;
    }



    foreach ($pinned as $first) {

        foreach ($first['category'] as $cat) {

            $categoryId = strtolower(str_replace(' ', '_', $cat));



            $pinned_data = array_merge([$first], $newJson[$categoryId]);



            $newJson[$categoryId] = $pinned_data;
        }
    }



    if (!isset($newJson['cards'])) $newJson['cards'] = [];



    $games = json_encode($newJson);

    file_put_contents("/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/data/games_list.json", $games);


    echo 'success';
    // print_r("Done");

    // exit();
}












#############

function removeBomUtf8($s)

{

    if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {

        return substr($s, 3);
    } else {

        return $s;
    }
}



function getJackpot($id, $platform)

{

    include_once WEB_PATH . "/common/cache_file.class.php";

    $cachFile = new cache_file();

    $data_list = $cachFile->get("mx_jackpot", '', 'data', strtolower($platform), substr(__DIR__, 0, strrpos(__DIR__, '/')) . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "caches" . DIRECTORY_SEPARATOR);



    return (isset($data_list[$id])) ? $data_list[$id] : 0;
}
