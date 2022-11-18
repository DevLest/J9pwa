<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");

define("WEB_PATH", __DIR__);
include_once "../core.class.php";

$data = (Object) $_POST;


switch ($data->type) {
    case "check_game_provider_img":
        echo checkGameImages($data);
        break;
    case "generate_game_launcher_url":
        echo generate_game_launcher_url($data);
        break;
    case "check_platforms":
        echo check_platforms($data);
        break;
}

function generate_game_launcher_url () {
    $file = "not_playable_games.json";
    $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE);
    $newJson = [];

    $core = new core();
    foreach ($filedata as $detail) {
       

        $platform = (isset($detail["platform"])) ? $detail["platform"] : 0;
        $username = "guest1";

        // $gameId = 1240;
        $gameId = 124;

        $gameAlias = "";

        $gameCode = $detail['code'];

        $mode = 2;
        $game_link = $core->game_mobile_login($username, $gameId, ['game_code' => $gameCode, 'table_alias' => $gameAlias, 'mobile' => 1, 'mode' => $mode]);

        array_push($newJson, [
        	"Name" => $detail['name'],
        	"Platform" => $detail['platform'],
        	"Status" => "Not playable",
        	"URL" => $game_link
        ]);
    }

    $games = json_encode($newJson);
    file_put_contents("x.json", $games);

    print_r($games);
    exit();
}

function checkGameImages() {
    $file = "games.json";
    $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE);
    $newJson = ["game_providers_w_img" => [], "game_providers_wo_img" => []];

    $core = new core();
    foreach ($filedata as $detail) {
        if(file_exists("../images/game_providers_logo/".strtolower($detail['platform']).".webp")) {
            if(!in_array($detail['platform'], $newJson["game_providers_w_img"])) {
                array_push($newJson["game_providers_w_img"], $detail['platform']);
            }
        }
        else {
            if(!in_array($detail['platform'], $newJson["game_providers_wo_img"])) {
                array_push($newJson["game_providers_wo_img"], $detail['platform']);
            }
        }
    }

    echo json_encode($newJson);
}

function check_platforms() {

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
        "REDTIGER" => "RedTiger",
        "NETENT" => "Netent",
        "GAMEART" => "GameArt",
        "EVO" => "Evolution",
        "BETBY" => "Betby",
    ];
    $file = "games.json";
    $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE);

    foreach ($filedata as $detail) {
        // echo $platformNames[$detail['platform']];
        // if(array_key_exists(strtoupper($detail['platform']), $platformNames)) {
        //     echo $detail['platform']."<br>";
        // }

        // if($detail['platform'] == "HB" || $detail['platform'] == "MP") {
        //     echo $detail["name"]."<br>";
        // }
        echo $detail["platform"]."<br>";
    }

}

function removeBomUtf8($s)
{
    if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {
        return substr($s, 3);
    } else {
        return $s;
    }
}

?>