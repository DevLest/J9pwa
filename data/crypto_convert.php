<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('safe_mode', '0');
error_reporting(E_ALL);
ini_set("user_agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");

$coins = [
    "USDT" => 825,
    "mETH" => 1027,
    "mBTC" => 1,
    "AAVE" => 7278,
    "ADA" => 2010,
    "AIRT" => 10905,
    "ALU" => 9637,
    "AVAX" => 5805,
    "BABY" => 10334,
    "BCH" => 1831,
    "BFG" => 11038,
    "BNB" => 1839,
    "BSW" => 10746,
    "BTT(OLD)" => 3718,
    "BTT(NEW)" => 16086,
    "C98" => 10903,
    "CAKE" => 7186,
    "CHZ" => 4066,
    "COMP" => 5692,
    "DAI" => 4943,
    "DASH" => 131,
    "DOGE" => 74,
    "ENJ" => 2130,
    "ETC" => 1321,
    "FTM" => 3513,
    "GLM" => 1455,
    "HOT" => 2682,
    "LAZIO" => 12687,
    "LINK" => 1975,
    "LTC" => 2,
    "MATIC" => 3890,
    "MKR" => 1518,
    "OMG" => 1808,
    "ONT" => 2566,
    "PORTO" => 14052,
    "REEF" => 6951,
    "SHIB" => 5994,
    "SNX" => 2586,
    "STORJ" => 1772,
    "SUSHI" => 6758,
    "TRX" => 1958,
    "UMA" => 5617,
    "UNI" => 7083,
    "USDC" => 3408,
    "XLM" => 512,
    "YFI" => 5864,
    "ZIL" => 2469,
    "ZRX" => 1896,
];

$currency = [];
foreach ($coins as $index => $coin) {
    $data = json_decode(file_get_contents("https://api.coinmarketcap.com/data-api/v3/tools/price-conversion?amount=1&convert_id=$coin&id=2781"));
    $qoute = $data->data->quote;

    $price = sprintf('%.8f', floatval($qoute[0]->price));

    if ($index == 'mETH' || $index == 'mBTC') {
        $price = $price * 1000;
    }

    array_push($currency, [
        "symbol" => $index,
        "vs" => "USD",
        "amount" => $price,
    ]);
}

//// inverted conversion
// foreach ($coins as $index => $coin) {
//     $data = json_decode(file_get_contents("https://api.coinmarketcap.com/data-api/v3/tools/price-conversion?amount=1&convert_id=2781&id=$coin"));
//     $qoute = $data->data->quote;

//     $price = sprintf('%.8f', floatval($qoute[0]->price));

//     if ($index == 'mETH' || $index == 'mBTC') {
//         $price = $price * 1000;
//     }

//     array_push($currency, [
//         "vs" => $index,
//         "symbol" => "USD",
//         "amount" => $price,
//     ]);
// }

file_put_contents("/www/wwwroot/999j9azx.u2d8899.com/j9pwa/data/coins.json", json_encode($currency));
