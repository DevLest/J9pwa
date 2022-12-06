<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");
define("WEB_PATH", __DIR__);

/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

include_once "core.class.php";

if (!isset($_SESSION)) {
    session_start();
}

$lang = json_decode(file_get_contents("./language/".(isset($data->lang) ? $data->lang : "en").".json"));
$lang = $lang->payment;

//Throttling | 5  mins per IP payment request
IPThrottling();

$data = (object) $_POST;

//check auth
$api_key = 'fghrtrvdfger';
$time = substr(time(), 0, -3);

$auth_check = md5($time . $api_key);
$auth = $data->auth;

if ($auth_check != $auth) {
    echo json_encode(array('status' => 0, 'info' => $lang->verification_failed), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

switch ($data->type) {
    case "member_login":
        echo memberLogin($data);
        break;
    case "onlinepay_list_v1":
        echo onlinepay_list_v1($data);
        break;
    case "fiat_list":
        echo fiat_list($data);
        break;
    case "monlinepay_bank":
        echo monlinepay_bank($data);
        break;
    case "submitpay":
        echo submitpay($data);
        break;
    case "announcements":
        echo announcements($data);
        break;
    case "autopromotion_list":
        echo autopromotion_list($data);
        break;
     case "j9_autopromotion_list":
        echo j9_autopromotion_list($data);
        break;
     case "submit_autopromotion":
        echo submit_autopromotion($data);
        break;
    case "checkdeposit":
        echo checkdeposit($data);
        break;
    case "canceldeposit":
        echo canceldeposit($data);
        break;
    case "network_list":
        echo networkList($data);
        break;
      case "top1paysubmit":
        echo top1paysubmit($data);
        break;
}

function onlinepay_list_v1($data)
{
    global $lang;
    if (checkLogin($data) != true) {
        exit();
    }

    if (!isset($data->payType) || $data->payType == null) {
        echo json_encode(['status' => 0, 'info' => $lang->onlinepay_list_v1->paytype_not_defined], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    $core = new core();
    $info = $core->onlinepay_list(array("common_id" => $data->payType, "member_type" => $_SESSION['member_type'], "language" => 1));
    $output = [];

    foreach ($info as $v) {
        $v['platform'] = 'onlinepay';
        $v['pay_limit'] = pay_limit((object) $v);
        $v['fixed_amount'] = allowed_amounts((int) $v['id']);
        $v['is_fixed_amount'] = 0;

        switch ($v['id']) {
            case 1:
                $v['img'] = "https://u2daszapp.u2d8899.com/images/bank_icon.webp";
                $v['reminder'] = $lang->onlinepay_list_v1->ensure_bank_support;
                break;
            case 2:
                $v['img'] = "https://u2daszapp.u2d8899.com/images/paypal_icon.webp";
                $v['reminder'] = $lang->onlinepay_list_v1->paypal_deposit;
                break;
            default:
                $v['img'] = "https://u2daszapp.u2d8899.com/images/usdt.webp";
                $v['reminder'] = $lang->onlinepay_list_v1->usdt_deposit;
                break;
        }

        $v['show_name'] = strtoupper(str_replace("unlimit_", "", $v['show_name']));

        if (in_array((int) $v['id'], [44, 22])) {
            $v['is_fixed_amount'] = 1;
        }

        if ($_SESSION['member_type'] > 0) {
            if (!in_array((int) $v['id'], [32, 33, 35, 36])) {
                unset($v['platform']);
                array_push($output, $v);
            }
        } else {
            if (in_array((int) $v['id'], [32, 33, 35, 36, 44])) {
                $v['is_fixed_amount'] = 1;
            }

            unset($v['platform']);
            array_push($output, $v);
        }
    }
    return json_encode(['status' => 1, 'info' => $output], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function allowed_amounts($id)
{
    $amounts = [100, 200, 500, 1000];

    switch ($id) {
        case 1:
            $amounts = [502, 1006, 2004, 4992, 10881];
            break;
        case 26:
            $amounts = [502, 1006, 2004, 4992, 10881];
            break;
        case 2:
            $amounts = [200, 300, 500];
            break;
        case 7:
            $amounts = [502, 1006, 2004, 4992, 10881];
            break;
        case 4:
            $amounts = [502, 1006, 2004, 4992, 10881];
            break;
        case 27:
            $amounts = [1006, 2004, 4992, 10881];
            break;
        case 23:
            $amounts = [2004, 4992, 6889, 10881];
            break;
        case 24:
            $amounts = [2004, 4992, 6889, 10881];
            break;
        case 30:
            $amounts = [108, 306, 502, 1006, 2004, 4992, 10881];
            break;
        case 32:
            $amounts = [100];
            break;
        case 33:
            $amounts = [158, 283, 409, 536];
            break;
        case 35:
            $amounts = [266, 366];
            break;
        case 36:
            $amounts = [100];
            break;
        case 37:
            $amounts = [108, 306, 502, 1006, 2004, 4992, 10881];
            break;
        case 44:
            $amounts = [50, 100, 200];
            break;
        case 22:
            $amounts = [50, 100, 200];
            break;
        default:
            break;
    }

    sort($amounts);

    return $amounts;
}

function pay_limit($data)
{
    $amount_low = 10;
    $amount_max = 99999;

    switch ($data->platform) {
        case "onlinepay":
            if ($data->line_type != "") {
                if ($data->line_type == 1566666) {
                    $amount_low = 200;
                } elseif (in_array($data->line_type, [1, 9, 4, 7, 10, 43])) {
                    $amount_low = 500;
                } elseif (in_array($data->line_type, [3, 27])) {
                    $amount_low = 1000;
                } elseif (in_array($data->line_type, [26])) {
                    $amount_low = 300;
                } elseif (in_array($data->line_type, [23, 24])) {
                    $amount_low = 2000;
                } elseif (in_array($data->line_type, [30, 37])) {
                    $amount_low = 100;
                } elseif (in_array($data->line_type, [2])) {
                    $amount_low = 200;
                    $amount_max = 500;
                } else {
                }
            }
            break;
        case "mobilepay":
            break;
        case "weixinpay":
            $amount_max = 3000;
            break;
        case "alipay":
            break;
        case "bankpay":
            break;
        case "tenpay":
            break;
    }
    return ['minimum' => $amount_low, 'maximum' => $amount_max];
}

function fiat_list($data){
    $file = __DIR__."/data/top1-config.json";
    $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE);
    
    return json_encode(['status' => 1, 'info' => $filedata]);
}

function memberLogin($data)
{
    global $lang;
    $login = new core();
    $re = $login->member_login($data->username_email, $data->password);

    if (is_array($re)) {
        $_SESSION['account'] = $re['account'];
        $_SESSION['balance'] = $re['balance'];
        $_SESSION['member_name'] = $re['realName'];
        $_SESSION['member_type'] = $re['memberType'];
        return json_encode(array('status' => 1, 'info' => $lang->memberLogin->success), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } elseif ($re == 1001) {
        return json_encode(array('status' => 0, 'info' => $lang->memberLogin->invalid_game_account), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } elseif ($re == 1002) {
        return json_encode(array('status' => 0, 'info' => $lang->memberLogin->account_locked), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } else {
        return json_encode(array('status' => 0, 'info' => $lang->memberLogin->system_error), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}

function checkLogin($data)
{
    $return = memberLogin($data);
    if (!json_decode($return)->status) {
        echo $return;
        return false;
    }
    return true;
}

function submitpay($data)
{
    if (checkLogin($data) != true) {
        exit();
    }

    switch ($data->pay_type) {
        case 1:
            return onlinepayment($data);
            break;
        case 2:
            return banktransfer($data);
            break;
    }
}

function monlinepay_bank($data)
{
    $bank_config = include "onlinebankV2.config.php";
    $bank_type = $data->bank_type;
    $line_type = $data->line_type;
    $info = [];

    if ($line_type == 10 || $line_type == 21 || $line_type == 22) {
        //乐盈Alipay
        array_push($info, ['value' => 'zfb', 'name' => 'Alipay']);
    } elseif ($line_type == 174 || $line_type == 20) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Online Bank Transfer']);
    } elseif ($line_type == 1 || $line_type == 3 || $line_type == 2 || $line_type == 6 || $line_type == 10 || $line_type == 13 || $line_type == 14 || $line_type == 15 || $line_type == 18 || $line_type == 19 || $line_type == 23 || $line_type == 24 || $line_type == 32) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Alipay']);
    } elseif ($line_type == 2) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Nube QuickPass']);
    } elseif ($line_type == 4 || $line_type == 12 || $line_type == 31 || $line_type == 34) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Online Bank Transfer']);
    } elseif ($line_type == 5 || $line_type == 8) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'UnionPay Scan Code']);
    } elseif ($line_type == 9 || $line_type == 11 || $line_type == 33) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'WeChat']);
    } elseif ($line_type == 16) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Fast']);
    } elseif ($line_type == 26 || $line_type == 27 || $line_type == 29) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Enter and choose a bank']);
    } elseif ($line_type == 30) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'USDT Anchor']);
    } elseif ($line_type == 35 || $line_type == 36) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Alipay']);
    } elseif ($line_type == 12 || $line_type == 13 || $line_type == 14 || $line_type == 15 || $line_type == 16) {
        //共用的
        array_push($info, ['value' => '6011', 'name' => 'Pay']);
    } elseif ($line_type == 70) {
        //智付点卡
        $list = $bank_config[101];

        if (is_array($list)) {
            foreach ($list as $v) {
                array_push($info, $v);
            }
        }
    } else {
        //普通在线支付银行信息
        $list = $bank_config[$bank_type];
        if (is_array($list)) {
            foreach ($list as $v) {
                array_push($info, $v);
            }
        }
    }

    return json_encode(array('status' => 1, 'info' => $info), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function announcements($data)
{
    global $lang;
    if (checkLogin($data) != true) {
        exit();
    }

    return json_encode(array('status' => 1, 'info' => $lang->announcements->dear_sports_member), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function autopromotion_list($data)
{
    global $lang;
    if (checkLogin($data) != true) {
        exit();
    }

    $client = new PHPRPC_Client(SERVER_URL);
    $results = unserialize($client->web_autopromotion_active($data->username_email));
    if (is_array($results)) {
        unset($results['status']);
        foreach ($results as $index => $result) {
            $output[$index]['id'] = $result['id'];
            $output[$index]['title'] = $result['title'];
            $output[$index]['content'] = $result['content'];
        }
        return json_encode(["status" => 1, "info" => $output], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        return json_encode(["status" => 0, "info" =>  $lang->j9_autopromotion_list->without_discount], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

function j9_autopromotion_list($data)
{
    global $lang;
    if (checkLogin($data) != true) {
        exit();
    }

    $client = new PHPRPC_Client(SERVER_URL);
    $results = unserialize($client->web_autopromotion_active($data->username_email));
    if (is_array($results)) {
        unset($results['status']);
        foreach ($results as $index => $result) {
            $output[$index]['id'] = $result['id'];
            $output[$index]['title'] = $result['title'];
            $output[$index]['content'] = $result['content'];
        }
        return json_encode(["status" => 1, "info" => $output], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        return json_encode(["status" => 0, "info" => $lang->j9_autopromotion_list->without_discount], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

function submit_autopromotion($data)
{
    global $lang;
   // echo 5665;exit;
     if (checkLogin($data) != true) {
        exit();
    }
        $core = new core();
    $info = $core->submit_autopromotion($data->username_email,$data->promotion_id);
  //print_r($info);exit;
    if($info==1){
         return json_encode(["status" => 1, "info" => $lang->submit_autopromotion->success_application], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } elseif($info==1011) {
        return json_encode(["status" => 0, "info" => $lang->submit_autopromotion->not_made_deposit], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }elseif($info==1012) {
        return json_encode(["status" => 0, "info" => $lang->submit_autopromotion->entered_game_again], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }elseif($info== 1013) {
        return json_encode(["status" => 0, "info" => $lang->submit_autopromotion->already_applied], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }elseif($info== 1015) {
        return json_encode(["status" => 0, "info" => $lang->submit_autopromotion->already_applied_discount], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
}
function checkdeposit($data)
{
    global $lang;
    if (checkLogin($data) != true) {
        exit();
    }

    $core = new core();
    $info = $core->checkdeposit($data->username_email);

    if (is_array($info)) {
        $bank_info_arr = $core->deposit_bank(0, $_SESSION['member_type']);
        $bank_info = $bank_info_arr[0];

        if (count($bank_info_arr) < 2) {
            $arrs = [
                "id" => $bank_info['id'],
                "bank_name" => $bank_info['bank_name'],
                "account_name" => $bank_info['account_name'],
                "bank_no" => $bank_info['bank_no'],
                "amount" => $info['amount'],
                "code" => mt_rand(100000, 999999),
                "yunsfurl" => '',
            ];
            return json_encode(['status' => 1, 'info' => $arrs], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        } else {
            return json_encode(['status' => 0, 'info' => $bank_info_arr], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        }
    } else {
        return json_encode(['status' => 0, 'info' => $lang->checkdeposit->no_data]);
    }
}

function onlinepayment($data)
{
    global $lang;
    $core = new core();

    $account = $data->username_email;
    $rand_monlinepay = $core->random(6);
    $billno = "n" . date("YmdHis") . $rand_monlinepay;

    $amount = $data->amount;

    if ($amount < 10) {
        return json_encode(array('status' => 0, 'info' => $lang->onlinepayment->deposit_amount_incorrect), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    $bankco = $data->bank_type;
    $pay_id = $data->pay_id;
    $line_type = $data->line_type;

    $monlinepay_info = $core->monlinpay_detail($pay_id);

    //判断支付银行选项是否正确
    $zf_bank_id = array("ABC", "ICBC", "CCB", "BCOM", "BOC", "CMB", "CMBC", "CEBB", "SHB", "NBB", "HXB", "CIB", "PSBC", "SPABANK", "SPDB", "HZB", "ECITIC");

    if ($pay_id == 600) {
        $zf_flag = 0;
        foreach ($zf_bank_id as $v_zf) {
            if ($bankco == $v_zf) {
                $zf_flag = 1;
            }
        }
        if ($zf_flag == 0) {
            return json_encode(array('status' => 0, 'info' => $lang->onlinepayment->deposit_bank_incorrect), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        }
    }

    if ($monlinepay_info['pay_status'] != 1 && $account != "feng12345") {
        return json_encode(array('status' => 0, 'info' => $lang->onlinepayment->channel_maintenance), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    if ($_SESSION['account'] == '') {
        return json_encode(array('status' => 0, 'info' => $lang->onlinepayment->verification_expired), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    if ($monlinepay_info['pay_type'] == 17) {
        $amount = $amount * 6.4;
        $amount = number_format($amount, '2', ".", "");
    }
    //print_r($data);exit;
    $re = $core->onlinepay($_SESSION['account'], $amount, $billno, $monlinepay_info['pay_name'], $data->autopromo);
    //print_r($re);exit;
    if ($re == 1) {
        // 三方通用提交
        $params = [
            'billno' => $billno,
            'amount' => $amount,
            'bank_code' => $bankco,
            'return_url' => $monlinepay_info['return_url'],
        ];

        $_SESSION['throttle_depostRequest'] = 1;
        $_SESSION['throttle_date'] = date('Y-m-d H:i:s');

        $formoutput = $core->build_form($params, $monlinepay_info['submit_url'], "POST");

        if ($data->platform == 1) {
            str_replace("target='_self'", "target='_blank'", $formoutput);
        }

        preg_match_all('/action=\'.*.php\' method/m', $formoutput, $matches, PREG_SET_ORDER, 0);
        $url = str_replace("action='", "", $matches[0]);
        $url = str_replace("' method", "", $url);

        return json_encode(array('status' => 1, 'info' => ['form' => $formoutput, 'data' => $params]));
    } elseif ($re == -1) {
        return json_encode(array('status' => 0, 'info' => $lang->onlinepayment->already_requested_promotion), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } else {
        return json_encode(array('status' => 0, 'info' => $lang->onlinepayment->fail_submit_deposit), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}

function banktransfer($data)
{
    global $lang;
    include_once WEB_PATH . "/common/cache_file.class.php";

    $account = $data->username_email;
    $amount = $data->amount;
    $limit_check = 0;

    $cachFile = new cache_file();
    $data_list = $cachFile->get($account, '', 'data', 'deposit_limit');

    if ($data_list == 'false') {
        $limit_time = array("limit_time" => time());
        $cachFile->set($account, $limit_time, '', 'data', 'deposit_limit');
        $limit_check = 1;
    } else {
        if ((time() - $data_list['limit_time']) > 30) {
            $limit_time = array("limit_time" => time());
            $cachFile->set($account, $limit_time, '', 'data', 'deposit_limit');
            $limit_check = 1;
        }
    }

    if ($limit_check == 0) {
        return json_encode(array('status' => 0, 'info' => $lang->banktransfer->error_sending), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    if ($amount < 10) {
        return json_encode(array('status' => 0, 'info' => $lang->banktransfer->incorrect_amount), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    $core = new core();
    $rs = $core->record_status($account, "deposit", 2);

    if ($rs > 0) {
        $data->record_type = 'deposit';
        $data->page = 1;

        $deposit = json_decode(checkdeposit($data));

        if ($deposit->status == 1) {
            return json_encode(array('status' => 0, 'info' => ['message' => $lang->banktransfer->unreviewed_deposit, 'data' => $deposit->info]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        } else {
            return json_encode($deposit, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        }
    }

    $bank_info_arr = $core->deposit_bank(0, $_SESSION['member_type']);
    $bank_info = $bank_info_arr[0];

    if ($_SESSION['account'] == '') {
        return json_encode(array('status' => 0, 'info' => $lang->banktransfer->verification_expired), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    $data->autopromo = (int) $data->autopromo;
    $re = $core->backdeposit($_SESSION['account'], $data->amount, $bank_info['id'], $data->username_email, $data->autopromo, 1);

    if ($re[0] == 1) {
        if (count($bank_info_arr) < 2) {
            $arrs = array(
                "id" => $bank_info['id'],
                "bank_name" => $bank_info['bank_name'],
                "account_name" => $bank_info['account_name'],
                "bank_no" => $bank_info['bank_no'],
                "amount" => $data->amount,
                "code" => $re[1], //附言
            );

            return json_encode(array('status' => 1, 'info' => $arrs), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        } else {
            return json_encode(array('status' => 1, 'info' => $bank_info_arr), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        }
    } elseif ($re == -1) {
        return json_encode(array('status' => 0, 'info' => $lang->banktransfer->already_requested_promotion), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } else {
        error_log(date('YmdHis') . "##" . $_SESSION['account'] . "##" . $data->amount . "##" . $bank_info['id'] . "##" . json_encode($re) . "##" . $_SESSION['member_type'] . "\r\n", 3, 'common/log/depositerror.log');
        return json_encode(array('status' => 0, 'info' => $lang->banktransfer->no_card), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}

function canceldeposit($data)
{
    global $lang;
    $core = new core();
    $info = $core->canceldeposit($data->username_email);

    if ($info == 1) {
        return json_encode(array('status' => 1, 'info' => $lang->canceldeposit->success_cancel), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        return json_encode(array('status' => 0, 'info' => $lang->canceldeposit->failed_undo), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
function top1paysubmit($data)
{
    global $lang;

    $jsonparams = [
        'merchant_ref' => "n".date("Y-m-d H:i:s",time()).rand(100,999),
    
        'product' => 'TRC20Buy',
        'amount' => $data->amount,
        'extra' => ['fiat_currency'=>$data->currency],
    
    ];

    $params = [
        'merchant_no' => 1160036,
        'timestamp' => time(),
        'sign_type' => 'MD5',
        'params' => json_encode($jsonparams),
        'extend_params'=>$data->username_email
    
    ];
    $str="1160036".json_encode($jsonparams).'MD5'.time().'fafe00c991cebd9ae8f58fea04ab6dde';
    $params['sign']=md5($str);

    $ch = curl_init();	
    curl_setopt($ch,CURLOPT_URL, "https://api.top1pay.com/api/gateway/pay");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    $response=curl_exec($ch);
    curl_close($ch);
    
    $url=json_decode(json_decode($response)->params)->payurl;

    return json_encode(array('status' => 1, 'info' => $url));
  
}
function IPThrottling()
{
    global $lang;
    $core = new core();
    $ips = $core->ip_information();

    if (isset($_SESSION['throttle_depostRequest']) && $_SESSION['throttle_depostRequest'] == 1) {

        if (isset($_SESSION['throttle_depostRequest']) && $_SESSION['throttle_depostRequest'] == 0) {
            $now = new DateTime();
            $then = new DateTime(date('Y-m-d', strtotime($_SESSION['throttle_date'])));
            $diff = $now->diff($then);

            if ($diff->format('%i') <= 5) {
                echo json_encode(['status' => 0, 'info' => $diff->format($lang->IPThrottling->throttle_message)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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

function networkList($data)
{
    global $lang;
    // $coins = ["USDT", "ETH", "BTC", "AAVE", "ADA", "AIRT", "ALU", "AVAX", "BABY", "BCH", "BFG", "BNB", "BSW", "BTT", "C98", "CAKE", "CHZ", "COMP", "DAI", "DASH", "DOGE", "ENJ", "ETC", "FTM", "GLM", "HOT", "LAZIO", "LINK", "LTC", "MATIC", "MKR", "OMG", "ONT", "PORTO", "REEF", "SHIB", "SNX", "STORJ", "SUSHI", "TRX","UMA","UNI","USDC","XLM","YFI","ZIL","ZRX"];

    $coins = [
        "BTC",
        "ETH",
        "WIN",
        "BNB",
        "TRX",
        "USDT", 
        "BCH", 
        "LTC", 
        "DOGE",
        "XRP",
        "ADA",
        "TUSD",
        "mBTC",
        "mETH"
    ];
    
    $skipCoin = [
        "heco"
    ];

    $pinned = [
        "USDT",
        "USDC",
        "BTC",
        "ETH",
        "BNB",
        "TRX",
        "DAI",
        "LTC",
        "BCH",
        "DOGE"
    ];
    
    $network = [];
    $pin_network = [];
    
    include_once WEB_PATH . "/common/cache_file.class.php";
    $cachFile = new cache_file();
    $data_list = $cachFile->get("s6_api", '', 'data', 'currency'); //enable for S6 coins
    // $data_list = $cachFile->get("999_api", '', 'data', 'currency');
    $response = json_decode($data_list);

    
    if (is_array($response->data)) {
        foreach ($response->data as $wallet) {
            $wallet_name = strtoupper($wallet->item_name);
            if ($wallet_name == $data->currency && in_array($wallet_name, $coins)) {
                foreach ($wallet->chain_list as $chain) {
                    if (in_array($chain->chain_tag, $skipCoin)) continue;

                    $address = "";
                    $address = s6getAddress($data->username_email, $data->password, $chain->chain_tag, $wallet->item_id);

                    if ( $address->status == 1 && ($address->info != "" || !is_null($address->info))) {
                        $fee = $chain->fee;
                        
                        if ($wallet_name == "USDT") {
                            if($chain->chain_tag == "erc20") {
                                $fee = 5;
                            }
                            else if ($chain->chain_tag == "trc20") {
                                $fee = 1.5;
                            }
                        }

                        if (in_array(strtoupper($wallet_name), $pinned)) {
                            array_push($pin_network, [
                                "address" => $address->info, 
                                "network" => $chain->chain_tag,
                                "name" => strtoupper($chain->chain_tag),
                                "currency" => $wallet->item_id,
                                "fee" => (float) $fee,
                                "min" => (float) $chain->minout,
                                "max" => (float) $chain->maxout,
                            ]);
                        }
                        else {
                            array_push($network, [
                                "address" => $address->info, 
                                "network" => $chain->chain_tag,
                                "name" => strtoupper($chain->chain_tag),
                                "currency" => $wallet->item_id,
                                "fee" => (float) $fee,
                                "min" => (float) $chain->minout,
                                "max" => (float) $chain->maxout,
                            ]);
                        }
                    }
                }
            }
        }

        $network = array_merge($pin_network,$network);

        return json_encode(['status' => 1, 'info' => $network]);
    }
    return json_encode(['status' => 0, 'info' => $lang->networkList->error, "msg" => print_r($response)]);
}

function s6getAddress($email, $password, $network, $currencyId){

    $time = substr(time(),0,-3);
    $auth = md5($time."fghrtrvdfger");

    $postData = [
        "type" => "s6_deposit_address",
        "auth" => $auth,
        "username_email" => $email,
        "password" => $password,
        "net_work" => $network,
        "currency_id" => $currencyId,
    ];

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://999j9azx.999game.online/j9pwa/conduct.php',
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_POST => 1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_TIMEOUT => 100,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => array(
        'Content-Type: multipart/form-data; '
        ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response);
}

function get999Address($email, $network){
    $account = [];
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://152.32.214.196:8917/account/getAccount",
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_POST => 1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_TIMEOUT => 100,
        CURLOPT_POSTFIELDS => json_encode([
            "currency" => $network,
            "merchant" => "j9",
            "outmemid" => $email,
            "notifyurl" => "https://999.game",
        ]),
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

    print_r($response);
    if (isset($response->data)) {
        array_push($account, [
            "address" => $response->data->address,
            "merchant" => $response->data->merchant,
            "username" => $response->data->outmemid,
            "currency" => $response->data->currency
        ]);

        return json_encode(['status' => 1, 'info' => $account]);
    }

    return json_encode(['status' => 0, 'info' => "Error on API", "msg" => print_r($response)]);
}

function removeBomUtf8($s)
{
    if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {
        return substr($s, 3);
    } else {
        return $s;
    }
}
