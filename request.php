<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");

define("WEB_PATH", __DIR__);
include_once "core.class.php";

if (!isset($_SESSION)) {
    session_start();
}

//Throttling | 5  mins per IP payment request
IPThrottling();

$data = (object) $_POST;

if (isset($data->debug)) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

//check auth
$api_key = 'fghrtrvdfger';
$time = substr(time(), 0, -3);

$auth_check = md5($time . $api_key);
$auth = $data->auth;

if ($auth_check != $auth) {
    echo json_encode(['status' => 0, 'info' => "Verification failed"], JSON_UNESCAPED_UNICODE);
    exit();
}

$data->username = (isset($data->username_email)) ? $data->username_email : "";

switch ($data->type) {
    case "play_game":
        echo play_game($data);
        break;
    case "change_information":
        echo change_account_info($data);
        break;
    case "send_reset_password":
        echo send_reset_password($data);
        break;
    case "verify_reset_password":
        echo verify_reset_password($data);
        break;
    case "reset_password":
        echo reset_password($data);
        break;
    case "get_transaction":
        echo get_transaction($data);
        break;
    case "get_fin_transaction":
        echo get_transaction($data, 2);
        break;
    case "search_game":
        echo search_game($data);
        break;
    case "game_balance_summary":
        echo game_summary($data);
        break;
    case "get_account_summary":
        echo get_account_summary($data);
        break;
    case "verify_agent":
        echo verify_agent($data);
        break;
    case "app_version":
        echo app_version();
        break;
    case "agent_rank_list":
        echo agent_rank_list($data);
        break;
    case "agent_friends_list":
        echo agent_friends_list($data);
        break;
    case "get_images":
        echo get_images($data);
        break;
    case "send_verification_email":
        echo send_verification_email($data);
        break;
    case "verify_email_code":
        echo verify_email_code($data);
        break;
    case "convert_currency":
        echo convert_currency($data);
        break;
    case "sports_token":
        echo sports_token($data);
        break;
    case "free_spin_amount":
        echo free_spin_amount($data);
        break;
    case "oauth_register":
        echo oauthRegister($data);
        break;
}

function getGameData($category = "", $format = 0, $seach_string = "", $game_code = "")
{
    $gamesList = [];
    $pinned = [];

    $pin_games = [
        'spb_aviator',
    ];

    $gameIDs = [
        "PP" => 1228,
        "REVOLVER" => 1233,
        "CQ9" => 1233,
        "PT" => 1202,
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
        "TB" => "TVB",
    ];

    $filedata = json_decode(removeBomUtf8(file_get_contents(WEB_PATH . "/data/games.json")), JSON_UNESCAPED_UNICODE);

    foreach ($filedata as $detail) {
        if (!$detail['state']) {
            continue;
        }

        if (in_array($category, $detail['tag'])) {
            if (isset($detail['name']) && isset($detail['id']) && isset($detail['pic'])) {
                $gameID = 1232;
                if (isset($detail['platform']) && isset($gameIDs[$detail['platform']])) {
                    $gameID = $gameIDs[$detail['platform']];
                }

                switch ($format) {
                    case 4:
                        $picture = str_replace("games", "games_popular", $detail['pic']);
                        break;
                    case 5:
                        $picture = str_replace("games", "live_casino", $detail['pic']);
                        break;
                    default:
                        $picture = $detail['pic'];
                        break;
                }

                if ($format == 1) {
                    if (strpos(strtolower($detail['name']), strtolower($seach_string)) !== false) {
                        array_push($gamesList, [
                            "name" => $detail['name'],
                            "imgURL" => $picture,
                            "platform" => $platformNames[$detail['platform']],
                            "gameInfo" => [
                                "gameCode" => $detail['id'],
                                "gameCodeAlias" => isset($detail['alias_code']) ? $detail['alias_code'] : "",
                                "gameId" => (isset($gameIDs[$detail['platform']])) ? $gameIDs[$detail['platform']] : $gameID,
                                "jackpot_amount" => 0,
                            ],
                        ]);
                    }
                } else {
                    $jackpot = ($detail['jackpot_ticker'] != "" && in_array($detail['platform'], ["PT", "SW"])) ? getJackpot($detail['id'], $detail['platform']) : 0;

                    if (in_array($detail['id'], $pin_games)) {
                        array_push($pinned, [
                            "name" => $detail['name'],
                            "imgURL" => $picture,
                            "platform" => $platformNames[$detail['platform']],
                            "gameInfo" => [
                                "gameCode" => $detail['id'],
                                "gameCodeAlias" => isset($detail['alias_code']) ? $detail['alias_code'] : "",
                                "gameId" => (isset($gameIDs[$detail['platform']])) ? $gameIDs[$detail['platform']] : $gameID,
                                "jackpot_amount" => $jackpot,
                            ],
                        ]);
                    } else {
                        array_push($gamesList, [
                            "name" => $detail['name'],
                            "imgURL" => $picture,
                            "platform" => $platformNames[$detail['platform']],
                            "gameInfo" => [
                                "gameCode" => $detail['id'],
                                "gameCodeAlias" => isset($detail['alias_code']) ? $detail['alias_code'] : "",
                                "gameId" => (isset($gameIDs[$detail['platform']])) ? $gameIDs[$detail['platform']] : $gameID,
                                "jackpot_amount" => $jackpot,
                            ],
                        ]);
                    }
                }
            }
        } else {
            continue;
        }
    }

    shuffle($gamesList);

    usort($gamesList, function ($a, $b) {
        if ($a['gameInfo']['jackpot_amount'] == $b['gameInfo']['jackpot_amount']) {
            return 0;
        }

        return $a['gameInfo']['jackpot_amount'] < $b['gameInfo']['jackpot_amount'] ? 1 : -1;
    });

    $gamesList = array_merge($pinned, $gamesList);

    return $gamesList;
}

function getJackpot($id, $platform)
{
    include_once WEB_PATH . "/common/cache_file.class.php";
    $cachFile = new cache_file();
    $data_list = $cachFile->get("mx_jackpot", '', 'data', strtolower($platform), substr(__DIR__, 0, strrpos(__DIR__, '/')) . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "caches" . DIRECTORY_SEPARATOR);

    return (isset($data_list[$id])) ? $data_list[$id] : 0;
}

function removeBomUtf8($s)
{
    if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {
        return substr($s, 3);
    } else {
        return $s;
    }
}

function IPThrottling()
{
    $core = new core();
    $ips = $core->ip_information();

    if (isset($_SESSION['throttle_depostRequest']) && $_SESSION['throttle_depostRequest'] == 1) {

        if (isset($_SESSION['throttle_depostRequest']) && $_SESSION['throttle_depostRequest'] == 0) {
            $now = new DateTime();
            $then = new DateTime(date('Y-m-d', strtotime($_SESSION['throttle_date'])));
            $diff = $now->diff($then);

            if ($diff->format('%i') <= 5) {
                echo json_encode(['status' => 0, 'info' => $diff->format('Hello, to provide you with a better user experience, to ensure the security of your account and to prevent IP monitoring. Your last request was %i minutes %s seconds ago, please wait 5 minutes before making a recharge request or contact customer service online for assistance. Happy gaming!')], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                die;
            } else {
                $_SESSION['throttle_date'] = date('Y-m-d H:i:s');
                $_SESSION['throttle_depostRequest'] = 0;
            }
        } else {
            $_SESSION['throttle_date'] = date('Y-m-d H:i:s');
            $_SESSION['throttle_depostRequest'] = 0;
        }
    } else {
        $_SESSION['throttle_depostRequest'] = 0;
    }
}

function play_game($data)
{
    if ($data->currency != "") {
        $currency = [
            "USDT" => 1232,
            "MBTC" => 1236,
            "METH" => 1238,
            "USD" => 1240,
        ];

        $platform = (isset($data->platform)) ? $data->platform : 0;
        $username = (isset($data->username)) ? $data->username : "guest1";

        $gameId = isset($data->currency) ? $currency[strtoupper($data->currency)] : 1240;

        $gameAlias = (isset($data->gameCodeAlias)) ? $data->gameCodeAlias : "";

        $gameCode = (isset($data->gameCode) && $data->gameCode != "sports_game") ? $data->gameCode : "betby_lobby";
        $gameCode = ($gameCode != "competition") ? $gameCode : "rkings_lobby";

        $mode = (isset($data->mode)) ? $data->mode : 1;

        $core = new core();
        $game_link = $core->game_mobile_login($username, $gameId, ['game_code' => $gameCode, 'table_alias' => $gameAlias, 'mobile' => $platform, 'mode' => $mode]);

        return json_encode(['status' => 1, 'info' => $game_link]);
    }

    return json_encode(['status' => 0, 'info' => "Invalid parameters"]);
}

function search_game($data)
{
    $search_games = [];

    foreach (['IN-HOUSE', 'LIVE', 'SPECIAL', "TABLE", "SLOTS"] as $index) {
        $search_games = array_merge($search_games, getGameData($index, 1, $data->keys));
    }

    $newArray = [];
    $games = [];
    foreach ($search_games as $key => $line) {
        if (!in_array($line['gameInfo']['gameCode'], $games)) {
            $games[] = $line['gameInfo']['gameCode'];
            array_push($newArray, $line);
        }
    }

    if (isset($data->limit) && ($data->limit != "" || $data->limit > 0)) {
        $newArray = array_slice($newArray, 0, $data->limit);
    }

    return json_encode(['status' => 1, 'info' => (array) $newArray], JSON_UNESCAPED_UNICODE);

}

function change_account_info($data)
{
    $core = new core();

    $realName = (isset($data->realName)) ? $data->realName : "";
    $birthday = (isset($data->birthday)) ? $data->birthday : "";
    $telephone = (isset($data->phone)) ? $data->phone : "";
    $state = (isset($data->state)) ? $data->state : "";
    $city = (isset($data->city)) ? $data->city : "";
    $postal = (isset($data->postal)) ? $data->postal : "";
    $country = (isset($data->country)) ? $data->country : "";
    $landline = (isset($data->landline)) ? $data->landline : "";
    $nickName = (isset($data->nickName)) ? $data->nickName : "";

    $change = $core->change_informationV2($data->username_email, ['state' => $state, 'city' => $city, 'postal' => $postal, 'country' => $country, 'landline' => $landline, "realName" => $realName, "birthday" => $birthday, "telephone" => $telephone, "nickName" => $nickName]);

    return json_encode(['status' => 1, 'info' => "Information modified successfully"], JSON_UNESCAPED_UNICODE);
}

function send_reset_password($data)
{
    include_once WEB_PATH . "/email/PHPMailer.class.php";
    include_once WEB_PATH . "/email/smtp.class.php";

    $co = new core();
    $re = $co->get_memberinfoByEmail($data->email);

    if (is_array($re)) {
        $mail = new PHPMailer(true);
        $verif_code = rand(100000, 999999);
        $time = date("Y-m-d H:i:s");
        $key = create_md5content($re["id"], $re['account'], $re['email'], $time);
        $base_key = base64_encode(json_encode(['code' => $verif_code, 'hash' => $key]));

        try {
            $mail->isSMTP();
            // $mail->SMTPDebug  = 2;
            $mail->Host = 'smtpout.secureserver.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'support@999.game';
            $mail->Password = 'download15895';
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            $mail->setFrom('support@999.game', '999Game');
            $mail->addAddress($re['email'], $re['realName']);
            $mail->isHTML(true);

            $mail->Subject = 'Account Password Reset';
            $mail->Body =
            "<div
                    style='
                    width: 100%;
                    background-image: url(https://999j9azx.999game.online/j9pwa/images/%C2%A6%C2%A6+%C2%A6+%C2%AC-+.png);
                    background-size: 100%;
                    background-position: top center;
                    '
                >
                    <div>
                    <div style='padding: 0.75rem 1.25rem; margin-bottom: 10px; text-align: center'>
                        <img src='https://999j9azx.999game.online/j9pwa/images/logo.png' style='width: 15%' />
                    </div>
                    <div style='width: 100%; height: 3px; display: flex'>
                        <div style='width: 50%; height: 3px; background-color: black'></div>
                        <div style='width: 50%; height: 3px; background-color: #0bafe6'></div>
                    </div>
                    <div style='padding: 1.25rem'>
                        <p style='margin-top: 0'>Hello " . strtoupper($re['firstName']) . ",</p>
                        <p style='margin-top: 0; margin-bottom: 30px'>
                        Forgot your 999Game password? For security reasons we have generated a temporary password for you.
                        </p>
                        <div style='width: 100%'>
                        <div style='background-color: #0bafe6; border-radius: 10px; padding: 0.5em 1.5em; margin: 0 auto; width: max-content'>
                            <p style='color: white'>Username: " . $re['account'] . "</p>
                            <p style='color: white'>Temporal password: $verif_code</p>
                        </div>
                        </div>
                        <p style='margin-top: 30px'>Remember to change your temporary password the next time you enter 999Game</p>
                        <p style='margin-top: 0'>
                        If you have not attempted to change your password, please contact customer service immediately at - or
                        <a href='mailto:support@999.game' target='_blank'>support@999.game</a>.
                        </p>
                        <a
                        href='https://999.game/?reset_password=$base_key'
                        style='
                            background-color: #0bafe6;
                            padding: 0.7rem 1.5rem;
                            border-radius: 21px;
                            color: white;
                            display: inline-block;
                            margin-top: 10px;
                        '
                        >Click here</a
                        >
                    </div>
                    </div>
                </div>";

            $info = [
                'member_id' => $re['id'],
                'email' => $re['email'],
                'add_time' => $time,
                'md5content' => $base_key,
                'verification_code' => $verif_code,
            ];
            $pwd = $co->add_password_reset($re['account'], $info);

            if ($pwd) {
                if ($mail->send()) {
                    return json_encode(['status' => 1, 'info' => "The message has been sent"], JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['status' => 0, 'info' => "Error in sending reset code"]);
                }

            } else {
                return json_encode(['status' => 0, 'info' => "Error in sending reset code | Password code Ivanlid"]);
            }

        } catch (Exception $e) {
            return json_encode(['status' => 0, 'info' => "Error in sending reset code. Error: $mail->ErrorInfo"]);
        }
    } else {
        return json_encode(['status' => 0, 'info' => "Email not registered in any guest account"], JSON_UNESCAPED_UNICODE);
    }

}

function verify_reset_password($data)
{
    include_once WEB_PATH . "/common/cache_file.class.php";
    $core = new core();
    $re = $core->get_password_reset($data->hash);

    if (!empty($re)) {
        $cachFile = new cache_file();
        $code1 = ["code" => $re['verification_code']];
        $cachFile->set($re['account'], $code1, '', 'data', 'reset_verification_code');

        return json_encode(['status' => 1, 'info' => ['code' => $re['verification_code'], 'email' => $re['email']]]);
    } else {
        return json_encode(['status' => 0, 'info' => 'Verification code expired! Please request a new code.'], JSON_UNESCAPED_UNICODE);
    }

}

function send_verification_email($data)
{
    if (!isset($data->email) && $data->email != "") {
        return json_encode(['status' => 0, "info" => "Please enter Email"], JSON_UNESCAPED_UNICODE);
    }

    include_once WEB_PATH . "/email/PHPMailer.class.php";
    include_once WEB_PATH . "/email/smtp.class.php";
    include_once WEB_PATH . "/common/cache_file.class.php";
    $cachFile = new cache_file();
    $mail = new PHPMailer(true);
    $verif_code = rand(100000, 999999);
    $cachFile->set($data->email, $verif_code, '', 'data', 'email_verification_code');

    $name = (isset($_SESSION['member_name'])) ? $_SESSION['member_name'] : "Guest";

    try {
        $mail->isSMTP();
        // $mail->SMTPDebug  = 2;
        $mail->Host = 'smtpout.secureserver.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'support@999.game';
        $mail->Password = 'download15895';
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom('support@999.game', '999Game');
        $mail->addAddress($data->email, $name);
        $mail->isHTML(true);

        $mail->Subject = 'Email Verification';
        $mail->Body =
            "<div
                style='
                width: 100%;
                background-image: url(https://999j9azx.999game.online/j9pwa/images/%C2%A6%C2%A6+%C2%A6+%C2%AC-+.png);
                background-size: 100%;
                background-position: top center;
                '
            >
                <div>
                <div style='padding: 0.75rem 1.25rem; margin-bottom: 10px; text-align: center'>
                    <img src='https://999j9azx.999game.online/j9pwa/images/logo.png' style='width: 15%' />
                </div>
                <div style='width: 100%; height: 3px; display: flex'>
                    <div style='width: 50%; height: 3px; background-color: black'></div>
                    <div style='width: 50%; height: 3px; background-color: #0bafe6'></div>
                </div>
                <div style='padding: 1.25rem'>
                    <p style='margin-top: 0; margin-bottom: 30px'>
                    This verification code is intended for validation of user.
                    </p>
                    <div style='width: 100%'>
                    <div style='background-color: #0bafe6; border-radius: 10px; padding: 0.5em 1.5em; margin: 0 auto; width: max-content'>
                        <p style='color: white'>Verification Code: $verif_code</p>
                    </div>
                    </div>
                    If you have not attempted to verify your email, please contact customer service immediately at - or
                    <a href='mailto:support@999.game' target='_blank'>support@999.game</a>.
                    </p>
                </div>
                </div>
            </div>";

        if ($mail->send()) {
            return json_encode(['status' => 1, 'info' => "Verification email has been sent"], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(['status' => 0, 'info' => "Error in sending reset code"]);
        }

    } catch (Exception $e) {
        return json_encode(['status' => 0, 'info' => "Error in sending reset code. Error: $mail->ErrorInfo"]);
    }

}

function verify_email_code($data)
{
    include_once WEB_PATH . "/common/cache_file.class.php";
    $cachFile = new cache_file();
    $core = new core();
    $data_list = $cachFile->get($data->email, '', 'data', 'email_verification_code');

    if ($data->code == $data_list) {
        $re = $core->get_memberinfoByEmail($data->email);

        if (is_array($re)) {
            $request = $core->set_memberEmailVerified($re['account']);

            if ($request) {
                return json_encode(['status' => 1, 'info' => "Email Verified"], JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(['status' => 0, 'info' => 'Error in Verifying email'], JSON_UNESCAPED_UNICODE);
            }

        }
    } else {
        return json_encode(['status' => 0, 'info' => 'Error in Verifying email'], JSON_UNESCAPED_UNICODE);
    }

}

function reset_password($data)
{
    $core = new core();
    $re = $core->get_memberinfoByEmail($data->email);

    if (is_array($re)) {
        include_once WEB_PATH . "/common/cache_file.class.php";
        $cachFile = new cache_file();
        $data_list = $cachFile->get($re['account'], '', 'data', 'reset_verification_code');

        if (is_array($data_list)) {
            if ($data->code == $data_list['code']) {
                $reset = $core->resetpwd($re['account'], $data->password);

                return json_encode(['status' => 1, 'info' => loginMember($re['account'], $data->password)], JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(['status' => 0, 'info' => 'The verification code is incorrect, please try again'], JSON_UNESCAPED_UNICODE);
            }

        } else {
            return json_encode(['status' => 0, 'info' => 'Error in code verification. Please contact customer support.'], JSON_UNESCAPED_UNICODE);
        }

    }
}

function get_transaction($data, $type = 1)
{
    $output = [];
    $core = new core();
    $start_date = "";
    $end_date = "";

    if (!isset($data->range)) {
        return json_encode(['status' => 0, 'info' => 'Please enter the date range'], JSON_UNESCAPED_UNICODE);
    }

    if (!isset($data->username_email)) {
        return json_encode(['status' => 0, 'info' => 'Please enter username'], JSON_UNESCAPED_UNICODE);
    }

    include_once WEB_PATH . "/common/cache_file.class.php";
    $cacheFile = new cache_file();
    $cacheData = $cacheFile->get("mx_game_data", '', 'data', "mexplay", substr(__DIR__, 0, strrpos(__DIR__, '/')) . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "caches" . DIRECTORY_SEPARATOR);

    switch ($data->range) {
        case 2: //3 Days
            $start_date = date('Y-m-d 00:00:00', strtotime(' - 3 days'));
            $end_date = date('Y-m-d 23:59:59', time());
            break;
        case 3: // last week
            $start_date = date('Y-m-d 00:00:00', strtotime("monday last week"));
            $end_date = date('Y-m-d 23:59:59', strtotime("sunday last week"));
            break;
        case 4: // this month
            $start_date = date('Y-m-1 00:00:00', time());
            $end_date = date('Y-m-d 23:59:59', time());
            break;
        case 5: // last month
            $start_date = date('Y-m-d 00:00:00', strtotime("first day of previous month"));
            $end_date = date('Y-m-d 23:59:59', strtotime("last day of previous month"));
            break;
        case 6: // 3 months
            $start_date = date('Y-m-d 00:00:00', strtotime("-3 month"));
            $end_date = date('Y-m-d 23:59:59', time());
            break;
        case 7: // custom
            $start_date = date('Y-m-d 00:00:00', strtotime($data->s_date));
            $end_date = date('Y-m-d 23:59:59', strtotime($data->e_date));
            break;
        default: // 24h
            $start_date = date('Y-m-d H:i:s', strtotime('-24 hours'));
            $end_date = date('Y-m-d H:i:s', time());
            break;
    }

    if ($type == 1) {
        $bets = $core->uniterecord($data->username_email, $start_date, $end_date);

        if (is_array($bets)) {
            foreach ($bets['list'] as $bet) {
                if (floatval($bet['win']) > 0 && in_array($data->category, [2, 3])) {
                    array_push($output, [
                        "type" => 'Bets',
                        "trans_type" => ucwords((isset($cacheData[$bet['gamecode']])) ? $cacheData[$bet['gamecode']]['gameType'] : "Slot") . " Wins",
                        "amount" => " +" . $bet['win'],
                        "currency" => $bet['currency'],
                        "process_time" => date('Y-m-d H:i:s', strtotime($bet['createtime'])),
                        "game_name" => (isset($cacheData[$bet['gamecode']])) ? $cacheData[$bet['gamecode']]['gameName'] : "Unknown Game",
                        "status" => $bet['id'],
                    ]);
                }

                if (in_array($data->category, [1, 3])) {
                    array_push($output, [
                        "type" => 'Bets',
                        "trans_type" => ucwords((isset($cacheData[$bet['gamecode']])) ? $cacheData[$bet['gamecode']]['gameType'] : "Slot") . " Bets",
                        "amount" => " -" . $bet['bet'],
                        "currency" => $bet['currency'],
                        "process_time" => date('Y-m-d H:i:s', strtotime($bet['createtime'])),
                        "game_name" => (isset($cacheData[$bet['gamecode']])) ? $cacheData[$bet['gamecode']]['gameName'] : "Unknown Game",
                        "status" => "processed",
                    ]);
                }

            }
        }
    } else {
        if (in_array($data->category, [2, 3])) {
            if (in_array($data->status, [0, 1])) {
                $deposit = $core->record_list_v2($data->username_email, "pending_deposit_fin_transaction", $start_date, $end_date, 0); //pending

                if (is_array($deposit) && !empty($deposit)) {

                    foreach ($deposit as $pending) {
                        $paytype = strtoupper($pending['platInfo']);
                        array_push($output, [
                            "type" => $paytype,
                            "trans_type" => $paytype,
                            "amount" => $pending['amount'],
                            "process_time" => date('Y-m-d H:i:s', strtotime($pending['requestTime'])),
                            "status" => "Pending",
                            "bill_no" => substr($pending['billno'], 0, 5) . "xxxx" . substr($pending['billno'], -4),
                            "verifyStatus" => $pending['depStatus'],
                            "reydata" => $pending,

                        ]);
                    }
                }
            }

            if (in_array($data->status, [0, 2])) {
                $deposit = $core->record_list_v2($data->username_email, "canceled_deposit_fin_transaction", $start_date, $end_date, 2); // unprocessed

                if (is_array($deposit) && !empty($deposit)) {

                    foreach ($deposit as $unprocessed) {
                        $paytype = strtoupper($unprocessed['platInfo']);
                        array_push($output, [
                            "type" => $paytype,
                            "trans_type" => $paytype,
                            "amount" => $unprocessed['amount'],
                            "process_time" => date('Y-m-d H:i:s', strtotime($unprocessed['requestTime'])),
                            "status" => "unprocessed",
                            "bill_no" => substr($pending['billno'], 0, 5) . "xxxx" . substr($pending['billno'], -4),
                            "verifyStatus" => $pending['depStatus'],
                            "reydata" => $unprocessed,

                        ]);
                    }
                }
            }

            if (in_array($data->status, [0, 3])) {
                $deposit = $core->record_list_v2($data->username_email, "canceled_deposit_fin_transaction", $start_date, $end_date, 3); // canceled

                if (is_array($deposit) && !empty($deposit)) {

                    foreach ($deposit as $cancelled) {
                        $paytype = strtoupper($cancelled['platInfo']);
                        array_push($output, [
                            "type" => $paytype,
                            "trans_type" => $paytype,
                            "amount" => $cancelled['amount'],
                            "process_time" => date('Y-m-d H:i:s', strtotime($cancelled['requestTime'])),
                            "status" => "Cancelled",
                            "bill_no" => substr($pending['billno'], 0, 5) . "xxxx" . substr($pending['billno'], -4),
                            "verifyStatus" => $pending['depStatus'],

                            "reydata" => $cancelled,

                        ]);
                    }
                }
            }

            if (in_array($data->status, [0, 4])) {
                $deposit = $core->record_list_v2($data->username_email, "deposit", $start_date, $end_date);

                if (is_array($deposit) && !empty($deposit)) {

                    foreach ($deposit as $success) {
                        $paytype = strtoupper($success['platInfo']);
                        array_push($output, [
                            "type" => $paytype,
                            "trans_type" => $paytype,
                            "amount" => $success['amount'],
                            "process_time" => date('Y-m-d H:i:s', strtotime($success['requestTime'])),
                            "status" => "Success",
                            "bill_no" => substr($pending['billno'], 0, 5) . "xxxx" . substr($pending['billno'], -4),
                            "verifyStatus" => $pending['depStatus'],

                            "reydata" => $success,

                        ]);
                    }
                }
            }
        }

        if (in_array($data->category, [1, 3])) {
            $fin_transac = [];
            if (in_array($data->status, [0, 1])) {
                $withdraw = $core->record_list_v2($data->username_email, "withdraw_fin_transaction", $start_date, $end_date, 0);
                if (is_array($withdraw)) {
                    $fin_transac = array_merge($fin_transac, $withdraw);
                }

            }

            if (in_array($data->status, [0, 2, 3])) {
                $withdraw = $core->record_list_v2($data->username_email, "withdraw_fin_transaction", $start_date, $end_date, 2);
                if (is_array($withdraw)) {
                    $fin_transac = array_merge($fin_transac, $withdraw);
                }

            }

            if (in_array($data->status, [0, 4])) {
                $withdraw = $core->record_list_v2($data->username_email, "withdraw_fin_transaction", $start_date, $end_date, 4);
                if (is_array($withdraw)) {
                    $fin_transac = array_merge($fin_transac, $withdraw);
                }

            }

            if (in_array($data->status, [0, 5])) {
                $withdraw = $core->record_list_v2($data->username_email, "withdraw_fin_transaction", $start_date, $end_date, 1);
                if (is_array($withdraw)) {
                    $fin_transac = array_merge($fin_transac, $withdraw);
                }

            }

            if (!empty($fin_transac)) {
                $fin_transac = array_map("unserialize", array_unique(array_map("serialize", $fin_transac)));
                foreach ($fin_transac as $v) {
                    switch ($v['verifyStatus']) {
                        case 2:
                            $status = (is_null($v['verify_name']) || $v['verify_name'] != "") ? "Failed" : "Cancelled";
                            break;
                        case 0:
                            $status = "Pending";
                            break;
                        case 1:
                            $status = "Processing";
                            break;
                        case 5:
                            $status = "Success";
                            $v['verifyStatus'] = 1;
                            break;
                    }

                    if ($v['verifyComment'] == "用户取消") {
                        $v['verifyComment'] = 'cancelled by user';
                    }
                    array_push($output, [
                        "type" => "Withdraw",
                        "trans_type" => $v['bankType'],
                        "amount" => $v['amount'],
                        "process_time" => date('Y-m-d H:i:s', strtotime($v['requestTime'])),
                        "status" => $status,
                        "verifyStatus" => $v['verifyStatus'],
                        "verifyComment" => $v['verifyComment'],
                        "cardNumber" => "***" . substr($v['cardNumber'], -4),
                        "bankInfo" => $v['bankInfo'],
                        "id" => $v['id'],
                    ]);
                }
            }
        }

        if (in_array($data->category, [4, 3])) {
            $commission = $core->agent_commission($data->username_email, $start_date, $end_date);

            if (is_array($commission)) {
                foreach ($commission as $v) {
                    array_push($output, [
                        "type" => 'Commission',
                        "trans_type" => "Referral Commission",
                        "amount" => $v['money'],
                        "process_time" => date('Y-m-d H:i:s', strtotime($v['date'])),
                        "status" => "processed",
                        "from_account" => $v['from_account'],
                    ]);
                }
            }
        }

        if (in_array($data->category, [3])) {
            if ($data->status == 0) {
                $bonuses = $core->record_list_v2($data->username_email, "bonuses", $start_date, $end_date, 3);

                $bonusType = [
                    1907 => "Birthday Gift",
                    1908 => "Free Play 1st chance",
                    1917 => "Free Play 2nd chance",
                    1918 => "Free Play 3rd chance",
                    1919 => "Free Play 4th chance",
                    1906 => "Monthly bonus",
                    1903 => "VIP Silver bonus",
                    1905 => "VIP Gold bonus",
                    1909 => "VIP Platinum bonus",
                ];

                if (is_array($bonuses)) {
                    foreach ($bonuses as $v) {
                        array_push($output, [
                            "type" => $bonusType[$v['game_id']],
                            "trans_type" => 'Bonus',
                            "amount" => $v['amount'],
                            "process_time" => date('Y-m-d H:i:s', strtotime($v['add_time'])),
                            "status" => "Processed",
                        ]);
                    }
                }
            }

            $rebates = "";
            if (in_array($data->status, [0])) {
                $rebates = $core->record_list_v2($data->username_email, "rebates", $start_date, $end_date, 3);
            }
            if (in_array($data->status, [2, 4])) {
                $status = ($data->status == 4) ? 1 : 0;
                $rebates = $core->record_list_v2($data->username_email, "rebates", $start_date, $end_date, $status);
            }

            if (is_array($rebates)) {
                foreach ($rebates as $v) {

                    switch ($v['status']) {
                        case 1:
                            $status = "Processed";
                            break;
                        default:
                            $status = "Failed";
                            break;
                    }

                    array_push($output, [
                        "type" => "Rebates",
                        "trans_type" => $v['type'],
                        "amount" => $v['amount'],
                        "process_time" => date('Y-m-d H:i:s', strtotime($v['add_time'])),
                        "status" => $status,
                    ]);
                }
            }

            $auto_promotions = "";
            if (in_array($data->status, [0])) {
                $auto_promotions = $core->record_list_v2($data->username_email, "auto_promotions", $start_date, $end_date, 3);
            }
            if (in_array($data->status, [2, 4])) {
                $status = ($data->status == 4) ? 1 : 0;
                $auto_promotions = $core->record_list_v2($data->username_email, "auto_promotions", $start_date, $end_date, $status);
            }

            if (is_array($auto_promotions)) {
                foreach ($auto_promotions as $v) {

                    switch ($v['status']) {
                        case 1:
                            $status = "Processed";
                            break;
                        default:
                            $status = "Failed";
                            break;
                    }

                    array_push($output, [
                        "type" => "Auto Promotions",
                        "trans_type" => $v['type'],
                        "amount" => $v['amount'],
                        "process_time" => date('Y-m-d H:i:s', strtotime($v['add_time'])),
                        "status" => $status,
                    ]);
                }
            }
        }
    }

    usort($output, function ($a, $b) {
        if ($a['process_time'] == $b['process_time']) {
            return 0;
        }

        return $a['process_time'] < $b['process_time'] ? 1 : -1;
    });

    if (isset($data->page)) {
        $page = $data->page;
        $total = count($output);
        $limit = 20;
        $totalPages = ceil($total / $limit);
        $newOutput = array_slice($output, 0, $limit * $page);

        return json_encode(['status' => 1, 'info' => $newOutput, "lastPage" => $totalPages]);
    }

    return json_encode(['status' => 1, 'info' => $output, "lastPage" => 1]);
}

function get_account_summary($data)
{
    $output = [];
    $core = new core();
    $start_date = "";
    $end_date = "";

    if (!isset($data->range)) {
        return json_encode(['status' => 0, 'info' => 'Please enter the date range'], JSON_UNESCAPED_UNICODE);
    }

    if (!isset($data->username_email)) {
        return json_encode(['status' => 0, 'info' => 'Please enter username'], JSON_UNESCAPED_UNICODE);
    }

    switch ($data->range) {
        case 2: //3 Days
            $start_date = date('Y-m-d 00:00:00', strtotime(' - 3 days'));
            $end_date = date('Y-m-d 23:59:59', time());
            break;
        case 3: // last week
            $start_date = date('Y-m-d 00:00:00', strtotime("monday last week"));
            $end_date = date('Y-m-d 23:59:59', strtotime("sunday last week"));
            break;
        case 4: // this month
            $start_date = date('Y-m-1 00:00:00', time());
            $end_date = date('Y-m-d 23:59:59', time());
            break;
        case 5: // last month
            $start_date = date('Y-m-d 00:00:00', strtotime("first day of previous month"));
            $end_date = date('Y-m-d 23:59:59', strtotime("last day of previous month"));
            break;
        case 6: // 3 months
            $start_date = date('Y-m-d 00:00:00', strtotime("-3 month"));
            $end_date = date('Y-m-d 23:59:59', time());
            break;
        case 7: // custom
            $start_date = date('Y-m-d 00:00:00', strtotime($data->s_date));
            $end_date = date('Y-m-d 23:59:59', strtotime($data->e_date));
            break;
        default: // 24h
            $start_date = date('Y-m-d H:i:s', strtotime('-24 hours'));
            $end_date = date('Y-m-d H:i:s', time());
            break;
    }

    $bet_records = $core->uniterecord_sum($data->username_email, $start_date, $end_date);
    $withdraw = $core->record_list_summary($data->username_email, "debit", $start_date, $end_date);
    $total_deposit = $core->record_list_summary($data->username_email, "deposit", $start_date, $end_date);
    $commission = $core->agent_commission($data->username_email, $start_date, $end_date);
    $total_withdraw = 0.00;
    $total_commission = 0.00;

    foreach ($withdraw as $with) {
        $total_withdraw += $with['amount'];
    }

    foreach ($commission as $coms) {
        $total_commission += $coms['money'];
    }

    return json_encode(['status' => 1, 'info' => [
        'total_win' => floatval($bet_records[0]['win']),
        'total_bets' => floatval($bet_records[0]['bet']),
        'total_deposit' => floatval($total_deposit[0]['amount']),
        'total_withdraw' => $total_withdraw,
        'total_commission' => $total_commission,
    ]]);
}

function https_submit($url, $data)
{
    $curl = curl_init();

    $data['requestId'] = (isset($data['requestId'])) ? $data['requestId'] : "SSFvobEqKOtgoafEZwmK";
    $data['brandId'] = (isset($data['brandId'])) ? $data['brandId'] : 372;

    ksort($data);
    $hash = md5(urldecode(http_build_query($data)) . $data['requestId']);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "$url?hash=$hash",
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_POST => 1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_TIMEOUT => 100,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json; charset=utf-8',
        ],
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);

    $response = json_decode($response);

    return ($response != "") ? $response : -1;
}

function create_md5content($id, $account, $email, $time)
{
    return md5($id . '' . md5($id . $account . $email . $time));
}

function loginMember($username, $password)
{
    $account = strtolower(trim($username));
    $password = trim($password);

    $core = new core();

    //check reset password request
    $reset = $core->check_password_reset($account, $password);
    if (is_array($reset)) {
        $add_time = date("Y-m-d H:i:s", (time() - 120));

        if ($reset['add_time'] < $add_time) {
            return json_encode(['status' => 0, 'info' => 'Temporary password expired'], JSON_UNESCAPED_UNICODE);
        }

        return json_encode(['status' => 2, 'info' => "?reset_password=" . $reset['md5content']]);
    }

    $emailAccount = $core->get_memberinfoByEmail($username);

    if (is_array($emailAccount)) {
        $account = $emailAccount['account'];
    }

    $re = $core->member_login($account, $password);

    if (is_array($re)) {
        $_SESSION['account'] = $re['account'];
        $_SESSION['balance'] = $re['balance'];
        $_SESSION['member_name'] = $re['realName'];
        $_SESSION['member_type'] = $re['memberType'];
        $_SESSION['password'] = $password;
        $_SESSION['levelID'] = $re['levelID'];
        $_SESSION['email'] = $re['email'];
        setcookie("account", $_SESSION['account'], time() + 86400);
        setcookie("member_name", urlencode($_SESSION['member_name']), time() + 86400);

        $imageResult = $core->get_imgurl($account);

        return json_encode([
            'status' => 1,
            'info' => [
                'username' => $re['account'],
                'balance' => $re['balance'],
                'realName' => $re['realName'],
                'email' => $re['email'],
                'email_verified' => $re['email_verified'],
                'sex' => ($re['sex']) ? "F" : "M",
                'birthday' => $re['birthday'],
                'phone' => $re['telephone'],
                'pic' => $imageResult,
                'first_name' => $re['firstName'],
                'middle_name' => $re['middleName'],
                'last_name' => $re['lastName'],
                'city' => $re['city'],
                'state' => $re['state'],
                'landline' => $re['landline'],
                'postal' => $re['postal'],
                'regTime' => $re['regTime'],
                'is_agent' => $re['is_agent'],
				'userID' => $re['uid'],
				'nickName' => $re['nickName'],
                'agent_percentage' => ($re['agent_percentage'] != "") ? $re['agent_percentage'] * 100 : null,
            ],
        ]);
    } elseif ($re == 1001) {
        return json_encode([
            'status' => 0,
            'info' => 'The game account or password is wrong!',
        ], JSON_UNESCAPED_UNICODE);
    } elseif ($re == 1002) {
        return json_encode([
            'status' => 0,
            'info' => 'The account is locked, please contact online customer service!',
        ], JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode([
            'status' => 0,
            'info' => 'System error. Try again later!',
        ], JSON_UNESCAPED_UNICODE);
    }
}

function game_summary($data)
{
    $core = new core();
    $balance = $core->get_balance($data->username_email, 1232);

    if ($balance > 0) {
        $withdraw = $core->record_list_v2($data->username_email, "withdraw_fin_transaction", date('Y-m-d 00:00:00', strtotime("-3 month")), date('Y-m-d 23:59:59', time()), 0);
        $withdrawable = 0;

        if (!empty($withdraw)) {
            foreach ($withdraw as $v) {
                $withdrawable += $v['amount'];
            }
        }

        $balance = round($balance, 2);

        return json_encode(['status' => 1, 'info' => [
            'total_balance' => floatval($balance),
            'balance_to_play' => $balance - $withdrawable,
            'free_spin' => 0,
            'bonus_balance' => 0,
            'removable' => 0,
            'bonus_credit' => 0,
            'bonus_earning' => 0,
            'total_bonus_balance' => 0,
        ]]);
    } else {
        return json_encode(['status' => 0, 'info' => "No account data!"]);
    }
}

function verify_agent($data)
{
    if (!isset($data->agent) && $data->agent == "") {
        return json_encode(['status' => 0, 'info' => "Agent name cannot be empty"]);
    }

    $string = str_replace(' ', '-', $data->agent);
    $agent = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

    $core = new core();
    $agent_info = $core->agent_info($agent);

    if (is_array($agent_info)) {
        return json_encode(['status' => 1, 'info' => $agent_info['agent_name']]);
    }

    return json_encode(['status' => 0, 'info' => "Agent not found! Enter the correct agent name if applicable, leave blank if you don't have it."]);
}

function app_version()
{
    return json_encode(['status' => 1, 'info' => "1.0.0"]);
}

function agent_rank_list($data)
{
    include_once WEB_PATH . "/common/cache_file.class.php";
    $list = [];

    $cachFile = new cache_file();
    $data_list = $cachFile->get("rank_list", '', 'data', 'random_agent_rank');

    if (!empty($data_list) && isset($data_list['last_update']) && $data_list['last_update'] <= (time() + 60 * 60)) {
        $list = $data_list['list'];
    } else {
        $x = 1;
        $referrals = [];
        $amount = [];
        while ($x <= 10) {
            array_push($referrals, [
                "username" => randomName(),
                "total_referrals" => rand(100, 2000),
                "rebate_amount" => 0,
            ]);
            array_push($amount, (rand(10000, 3000000) / 10));
            $x++;
        }

        rsort($amount);

        usort($referrals, function ($a, $b) {
            if ($a['total_referrals'] == $b['total_referrals']) {
                return 0;
            }

            return $a['total_referrals'] < $b['total_referrals'] ? 1 : -1;
        });

        foreach ($referrals as $index => $agent) {
            $agent['rebate_amount'] = $amount[$index];

            array_push($list, $agent);
        }

        $code = [
            "last_update" => time(),
            "list" => $list,
        ];

        $cachFile->set("rank_list", $code, '', 'data', 'random_agent_rank');
    }

    usort($list, function ($a, $b) {
        if ($a['total_referrals'] == $b['total_referrals']) {
            return 0;
        }

        return $a['total_referrals'] < $b['total_referrals'] ? 1 : -1;
    });

    $core = new core();
    $friends = $core->agent_rank_list($data->username_email);

    $output = [
        "status" => 1,
        "info" => [
            "total_referrals" => intval($friends['total_referrals']),
            "total_no_of_transactions" => intval(round(intval($friends['total_referrals']) / 4)),
            "total_rebate_reward" => intval($friends['total_rebate_reward']),
            "list" => $list,
        ],
    ];

    return json_encode($output);
}

function randomName()
{
    $firstname = ['Johnathon', 'Anthony', 'Erasmo', 'Raleigh', 'Nancie', 'Tama', 'Camellia', 'Augustine', 'Christeen', 'Luz', 'Diego', 'Lyndia', 'Thomas', 'Georgianna', 'Leigha', 'Alejandro', 'Marquis', 'Joan', 'Stephania', 'Elroy', 'Zonia', 'Buffy', 'Sharie', 'Blythe', 'Gaylene', 'Elida', 'Randy', 'Margarete', 'Margarett', 'Dion', 'Tomi', 'Arden', 'Clora', 'Laine', 'Becki', 'Margherita', 'Bong', 'Jeanice', 'Qiana', 'Lawanda', 'Rebecka', 'Maribel', 'Tami', 'Yuri', 'Michele', 'Rubi', 'Larisa', 'Lloyd', 'Tyisha', 'Samatha', 'Mischke', 'Serna', 'Pingree', 'Mcnaught', 'Pepper', 'Schildgen', 'Mongold', 'Wrona', 'Geddes', 'Lanz', 'Fetzer', 'Schroeder', 'Block', 'Mayoral', 'Fleishman', 'Roberie', 'Latson', 'Lupo', 'Motsinger', 'Drews', 'Coby', 'Redner', 'Culton', 'Howe', 'Stoval', 'Michaud', 'Mote', 'Menjivar', 'Wiers', 'Paris', 'Grisby', 'Noren', 'Damron', 'Kazmierczak', 'Haslett', 'Guillemette', 'Buresh', 'Center', 'Kucera', 'Catt', 'Badon', 'Grumbles', 'Antes', 'Byron', 'Volkman', 'Klemp', 'Pekar', 'Pecora', 'Schewe', 'Ramage'];

    $name = $firstname[rand(0, count($firstname) - 1)] . rand(1, 500);

    return $name;
}

function agent_friends_list($data)
{
    $core = new core();
    $friends = $core->agent_friends_list($data->username_email);

    if (!empty($friends)) {
        $output = [];
        foreach ($friends as $friend) {
            array_push($output, [
                "name" => $friend['account'],
                "referralRate" => ($friend['upper_level_agent_percentage'] * 100) . "%",
                "regTime" => $friend['regTime'],
                "profitRebate" => intval($friend['profitRebate']),
            ]);
        }
        return json_encode(['status' => 1, 'info' => $output]);
    }

    return json_encode(['status' => 0, 'info' => "No friends found!"]);
}

function get_images($data)
{
    $dir = WEB_PATH . "/images/" . $data->category;
    $files = scandir($dir);
    $images = [];

    foreach ($files as $file) {
        if (in_array($file, ['.', '..'])) {
            continue;
        }

        $url = "https://" . str_replace('/www/wwwroot/', '', $dir) . "/" . $file;

        array_push($images, $url);
    }

    return json_encode(['status' => 1, 'info' => $images]);
}

function convert_currency($data)
{

    include_once WEB_PATH . "/common/cache_file.class.php";
    $cachFile = new cache_file();
    $data = $cachFile->get("mxn_to_usdt", '', 'data', "mx", substr(__DIR__, 0, strrpos(__DIR__, '/')) . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "caches" . DIRECTORY_SEPARATOR);

    return json_encode(['status' => 1, 'info' => $data]);
}

function sports_token($data)
{
    $game_url = json_decode(play_game($data));

    $x = 0;
    while ($x < 1) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $game_url->info);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $response = curl_exec($ch);
        curl_close($ch);

        preg_match_all('/^Location:(.*)$/mi', $response, $matches);

        $redirection_Url = !empty($matches[1]) ? trim($matches[1][0]) : "";

        if (strpos($redirection_Url, 'Server Error, internal server') == false) {
            $x = 1;
            break;
        }
        sleep(2);
    }

    if ($redirection_Url != "") {
        $parameters = explode("&", ltrim(strrchr($redirection_Url, "?"), "?"));

        if ($parameters != null) {
            $urlData = [];
            foreach ($parameters as $param) {
                $lineData = explode("=", $param);
                $urlData[$lineData[0]] = $lineData[1];
            }

            if (count($urlData) > 0) {
                return json_encode(['status' => 1, 'info' => [
                    'token' => $urlData['token'],
                    'dgJs' => $urlData['dgJs'],
                    'brandId' => $urlData['brandId'],
                    'url' => $redirection_Url,
                    'source_url' => $game_url->info,
                    // 'themeName' => (isset($urlData['themeName'])) ? $urlData['themeName'] : "default",
                    'themeName' => "mexplay",
                ]]);
            } else {
                return json_encode(['status' => 0, 'info' => "Error in parsing URL Data"]);
            }

        } else {
            return json_encode(['status' => 0, 'info' => "Error in parsing URL"]);
        }

    } else {
        return json_encode(['status' => 0, 'info' => "Error! No redirection URL"]);
    }

}

function free_spin_amount($data)
{
    $params = [
        "playerId" => "mx" . $data->username_email,
    ];

    $url = "https://api.vsr888.com/bonus/player/activated";
    $result = https_submit($url, $params);

    $bonus = $result->data;
    $free_spin = [];
    $total = 0;

    foreach ($bonus as $free) {
        if ($free->available) {
            array_push($free_spin, [
                "bonusCode" => $free->bonusCode,
                "platfrom" => $free->providerCode,
                "amount" => $free->remainingAmount,
            ]);

            $total += $free->remainingAmount;
        }
    }

    $free_spin['total_amount'] = $total;

    if (count($free_spin) > 0) {
        return json_encode(['status' => 1, 'info' => $free_spin]);
    } else {
        return json_encode(['status' => 0, 'info' => "No bonus avaibale for the meantime"]);
    }

}

function oauthRegister($params) {
    
	$account = strtolower(trim($params->username_email));
	$password = $params->password;

	if (!filter_var($account, FILTER_VALIDATE_EMAIL)) {
		echo json_encode(['status' => 0, 'info' => "Please input a valid email address"]);
		exit();
	}
	
	$data['referrer'] = $_SERVER['HTTP_HOST'];
	$data['regTime'] = date("Y-m-d H:i:s");
	$data['email'] = $_POST['username_email'];
	$data['uid'] = date('d').mt_rand(52348169, 99871581);
	$data['nickName'] = "User".mt_rand(2648963, 9895639);

    $core = new core();
	$info = $core->member_regist($account, $password, $data);

	if(is_array($info)) {
		return loginMember($account, $password);
	} elseif($info == 1006) {
		return json_encode(array('status'=>0,'info'=>"Registration failed, member account has been registered"), JSON_UNESCAPED_UNICODE);
		exit();
	} elseif($info == 1007) {
		return json_encode(array('status'=>-1,'info'=>"Registration failed, Please contact Admin"), JSON_UNESCAPED_UNICODE);
		exit();
	} elseif($info == 1008) {
		return json_encode(array('status'=>-1,'info'=>"Registration failed, the phone number has been registered"), JSON_UNESCAPED_UNICODE);
		exit();
	} elseif($info == 1009) {
		return json_encode(array('status'=>-1,'info'=>"Registration failed, the email has been registered"), JSON_UNESCAPED_UNICODE);
		exit();
	} else {
		return loginMember($_POST['username_email'], $_POST['password']);
	}
	sendWelcomeEmail();
	exit();
}
