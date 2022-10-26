<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");
    
    $games = json_decode(file_get_contents("games_list.json"));
    $output = [];

    $files = array_diff(scandir("../images/game_providers_logo/"), ['.','..']);
    foreach ($files as $image) {
        $platform = strtoupper(str_replace(".webp", "", $image));
        array_push($output, [
            'name' => $platform,
            'imgURL' => "https://999j9azx.u2d8899.com/j9pwa/images/game_providers_logo/$image",
            'platform' => $platform,
            'category' => [],
        ]);
    }
    
    $games->providers = $output;

    echo json_encode($games);
?>