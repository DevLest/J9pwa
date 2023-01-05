<?php
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);

    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");

    header("Access-Control-Expose-Headers: Content-Length");

    
    $games = json_decode(file_get_contents("games_list.json"));
    
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
        "GAMEART" => "GameArt",
        "SLOTMILL" => "Slotmill",
        "INBET" => "InBet",
    ];

    $output = [];

    $data = (object) $_GET;

    if (isset($data->account) && $data->account != "")
    {
        $favorites = [];
        include_once "../core.class.php";
        $core = new core();

        $result = $core->get_collect_game($data->account);

        foreach ($result as $index => $favorite) {
            foreach ($games as $category) {
                foreach ($category as $detail){
                    $gameInfo = $detail->gameInfo;
                    if ($gameInfo->gameCode == trim($favorite['gamecode'])) {
                        if(!in_array($detail, $favorites)) {
                            array_push($favorites, $detail);
                        }
                    } else {
                        continue;
                    }
                }
            }
        }

        $games->favorites = $favorites;
    }

    $files = array_diff(scandir("../images/game_providers_logo/"), ['.','..']);
    foreach ($files as $image) {
        $platform = str_replace(".webp", "", $image);
        array_push($output, [
            'name' => isset($platformNames[strtoupper($platform)]) ? $platformNames[strtoupper($platform)] : ucwords($platform),
            'imgURL' => "https://999j9azx.999game.online/images/999/game_providers_logo/$image",
            'category' => [],
        ]);
    }
    
    $games->providers = $output;

    // SEQUENCE SORT START HERE:
    $x_games = (array) $games;

    foreach ($x_games as $key => $value) {
      
       $x = array();
       foreach ($x_games[$key] as $key2 => $row)
       {
            $x[$key2] = $row->sort;
       }
       // array_multisort($x, SORT_DESC, $data);
       array_multisort($x, SORT_ASC, $x_games[$key]);
      
    }
    // SEQUENCE SORT END HERE:

    $games = (object) $x_games;

    // echo '<pre>';
    // print_r($games);
    // echo '</pre>';
    // die();


    echo json_encode($games);
?>