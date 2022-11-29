<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
define("WEB_PATH", __DIR__);

$gameIDs = [
    "USDT" => 1232,
    "MBTC" => 1236,
    "METH" => 1238,
    "USD" => 1240,
];

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
    "GAMEART" => ["USD"],
    "SM" => ["USD"],
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
    "GAMEART" => "GameArt",
    "NT" => "Netent",
    "SM" => "Slotmill",
];

$pin_games = [
    'spb_aviator',
    'pgsoft_95',
    'SGHotHotFruit',
];

$file = __DIR__."/data/games.json";
$filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE);
$newJson = [];
$pinned = [];

foreach ($filedata as $detail) {
    if (!$detail['state']) {
        continue;
    }

    $jackpot = ($detail['jackpot_ticker'] != "" && in_array($detail['platform'], ["PT", "SW"])) ? getJackpot($detail['id'], $detail['platform']) : 0;

    $currency_data = [];

    if (isset($platformNames[$detail['platform']])) {
        foreach ($currency[$detail['platform']] as $curr) {
            array_push($currency_data, [
                "symbol" => $curr,
                "gameId" => (isset($gameIDs[strtoupper($curr)])) ? $gameIDs[strtoupper($curr)] : 1240,
                "icon" => "https://999j9azx.999game.online/j9pwa/images/$curr.svg",
            ]);
        }

        if (in_array($detail['id'], $pin_games)) {
            array_push($pinned, [
                "sort" => $detail['sort'],
                "name" => $detail['name'],
                "imgURL" => $detail['pic'],
                "platform" => $platformNames[$detail['platform']],
                "category" => $detail['tag'],
                "currency" => $currency_data,
                "gameInfo" => [
                    "gameCode" => $detail['id'],
                    "gameCodeAlias" => isset($detail['alias_code']) ? explode(",", $detail['alias_code'])[0] : "",
                    "jackpot_amount" => $jackpot,
                ],
            ]);
            continue;
        } else {
            foreach ($detail['tag'] as $category) {
                $categoryId = strtolower(str_replace(' ', '_', $category));

                if (!isset($newJson[$categoryId])) {
                    $newJson[$categoryId] = [];
                }

                array_push($newJson[$categoryId], [
                    "sort" => $detail['sort'],
                    "name" => $detail['name'],
                    "imgURL" => $detail['pic'],
                    "platform" => $platformNames[$detail['platform']],
                    "category" => $detail['tag'],
                    "currency" => $currency_data,
                    "gameInfo" => [
                        "gameCode" => $detail['id'],
                        "gameCodeAlias" => isset($detail['alias_code']) ? explode(",", $detail['alias_code'])[0] : "",
                        "jackpot_amount" => $jackpot,
                    ],
                ]);
            }
        }
    }
    else continue;
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

// SEQUENCE SORT START HERE:
$x = array();
foreach ($newJson as $key => $row)
{
    $x[$key] = $row['sort'];
}
// array_multisort($x, SORT_DESC, $data);
array_multisort($x, SORT_ASC, $newJson);
// SEQUENCE SORT END HERE:


$games = json_encode($newJson);
file_put_contents(__DIR__."/data/games_list.json", $games);

print_r("Done");
exit();

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
    $data_list = $cachFile->get("jackpot_amount", '', 'data', strtolower($platform), substr(__DIR__, 0, strrpos(__DIR__, '/')) . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "caches" . DIRECTORY_SEPARATOR);

    return (isset($data_list[$id])) ? $data_list[$id] : 0;
}