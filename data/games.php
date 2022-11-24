<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");
    
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
            'imgURL' => "https://img.999.game/game_providers_logo/$image",
            'category' => [],
        ]);
    }
    
    $games->providers = $output;

    echo json_encode($games);
?>