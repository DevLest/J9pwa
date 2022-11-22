<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");
define("WEB_PATH", __DIR__);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once "core.class.php";

if (!isset($_SESSION)) {
    session_start();
}

//Throttling | 5  mins per IP payment request
IPThrottling();

$data = (object) $_POST;

//check auth
$api_key = 'fghrtrvdfger';
$time = substr(time(), 0, -3);

$auth_check = md5($time . $api_key);
$auth = $data->auth;

if ($auth_check != $auth) {
    echo json_encode(array('status' => 0, 'info' => "Verification failed"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

switch ($data->type) {
    case "member_login":
        echo memberLogin($data);
        break;
    case "onlinepay_list_v1":
        echo onlinepay_list_v1($data);
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
}

function onlinepay_list_v1($data)
{
    if (checkLogin($data) != true) {
        exit();
    }

    if (!isset($data->payType) || $data->payType == null) {
        echo json_encode(['status' => 0, 'info' => "payType not defined"], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
                $v['reminder'] = "Please ensure that the bank card you will use supports this payment method.";
                break;
            case 2:
                $v['img'] = "https://u2daszapp.u2d8899.com/images/paypal_icon.webp";
                $v['reminder'] = "You are about to deposit via Paypal. You will be directed to Paypal to use this service.";
                break;
            default:
                $v['img'] = "https://u2daszapp.u2d8899.com/images/usdt.webp";
                $v['reminder'] = "You are about to deposit via USDT transfer. Please choose the correct network and chain to use.";
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

function memberLogin($data)
{
    $login = new core();
    $re = $login->member_login($data->username_email, $data->password);

    if (is_array($re)) {
        $_SESSION['account'] = $re['account'];
        $_SESSION['balance'] = $re['balance'];
        $_SESSION['member_name'] = $re['realName'];
        $_SESSION['member_type'] = $re['memberType'];
        return json_encode(array('status' => 1, 'info' => '¡éxito!'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } elseif ($re == 1001) {
        return json_encode(array('status' => 0, 'info' => 'The game account or password is wrong!'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } elseif ($re == 1002) {
        return json_encode(array('status' => 0, 'info' => 'The account is locked, please contact online customer service!'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } else {
        return json_encode(array('status' => 0, 'info' => 'System error. Try again later!'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
    if (checkLogin($data) != true) {
        exit();
    }

    return json_encode(array('status' => 1, 'info' => "Dear U Sports members: Due to the further strengthening of the bank's intelligent data system and Alipay's official risk control, our company constantly updates the recharge method and does its best to avoid risk control. If your payment is subject to risk control, use mobile banking to transfer the payment or choose virtual currency deposit"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function autopromotion_list($data)
{
    if (checkLogin($data) != true) {
        exit();
    }

    $client = new PHPRPC_Client(SERVER_URL);
    $results = unserialize($client->web_autopromotion_active($data->username_email, $data->amount, 1));
    if (is_array($results)) {
        unset($results['status']);
        foreach ($results as $index => $result) {
            $output[$index]['id'] = $result['id'];
            $output[$index]['title'] = $result['title'];
            $output[$index]['content'] = $result['content'];
        }
        return json_encode(["status" => 1, "info" => $output], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        return json_encode(["status" => 0, "info" => 'Without discount'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

function j9_autopromotion_list($data)
{
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
        return json_encode(["status" => 0, "info" => 'Without discount'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

function submit_autopromotion($data)
{
   // echo 5665;exit;
     if (checkLogin($data) != true) {
        exit();
    }
        $core = new core();
    $info = $core->submit_autopromotion($data->username_email,$data->promotion_id);
   print_r($info);exit;
    if($info==1){
         return json_encode(["status" => 1, "info" => "application has been successful"], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        return json_encode(["status" => 0, "info" => 'application failed'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
}
function checkdeposit($data)
{
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
        return json_encode(['status' => 0, 'info' => "No data found"]);
    }
}

function onlinepayment($data)
{
    $core = new core();

    $account = $data->username_email;
    $rand_monlinepay = $core->random(6);
    $billno = "n" . date("YmdHis") . $rand_monlinepay;

    $amount = $data->amount;

    if ($amount < 10) {
        return json_encode(array('status' => 0, 'info' => 'The deposit amount is incorrect, please enter the correct deposit amount'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
            return json_encode(array('status' => 0, 'info' => "The deposit bank is incorrect, please re-enter it."), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        }
    }

    if ($monlinepay_info['pay_status'] != 1 && $account != "feng12345") {
        return json_encode(array('status' => 0, 'info' => "Deposit channel maintenance, choose another deposit method"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    if ($_SESSION['account'] == '') {
        return json_encode(array('status' => 0, 'info' => "Verification expired, please go back and refresh to re-enter"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
        return json_encode(array('status' => 0, 'info' => "You have already requested this promotion, please review the promotion rules"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } else {
        return json_encode(array('status' => 0, 'info' => "Failed to submit deposit, please update and resubmit"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}

function banktransfer($data)
{
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
        return json_encode(array('status' => 0, 'info' => 'Error sending, please re-send after 30 seconds'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    if ($amount < 10) {
        return json_encode(array('status' => 0, 'info' => 'The deposit amount is incorrect, please enter the correct deposit amount'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    $core = new core();
    $rs = $core->record_status($account, "deposit", 2);

    if ($rs > 0) {
        $data->record_type = 'deposit';
        $data->page = 1;

        $deposit = json_decode(checkdeposit($data));

        if ($deposit->status == 1) {
            return json_encode(array('status' => 0, 'info' => ['message' => 'An unreviewed deposit record already exists, please do not resubmit it', 'data' => $deposit->info]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        } else {
            return json_encode($deposit, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        }
    }

    $bank_info_arr = $core->deposit_bank(0, $_SESSION['member_type']);
    $bank_info = $bank_info_arr[0];

    if ($_SESSION['account'] == '') {
        return json_encode(array('status' => 0, 'info' => 'Verification expired, please go back and refresh to re-enter'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
        return json_encode(array('status' => 0, 'info' => 'You have already requested this promotion, please review the promotion rules'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    } else {
        error_log(date('YmdHis') . "##" . $_SESSION['account'] . "##" . $data->amount . "##" . $bank_info['id'] . "##" . json_encode($re) . "##" . $_SESSION['member_type'] . "\r\n", 3, 'common/log/depositerror.log');
        return json_encode(array('status' => 0, 'info' => 'No cards available at this time, please try again later'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}

function canceldeposit($data)
{
    $core = new core();
    $info = $core->canceldeposit($data->username_email);

    if ($info == 1) {
        return json_encode(array('status' => 1, 'info' => "The deposit request has been canceled successfully!"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        return json_encode(array('status' => 0, 'info' => "Undo failed, please refresh and try again"), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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

function networkList($data)
{
    $coins = ["USDT", "ETH", "BTC", "AAVE", "ADA", "AIRT", "ALU", "AVAX", "BABY", "BCH", "BFG", "BNB", "BSW", "BTT", "C98", "CAKE", "CHZ", "COMP", "DAI", "DASH", "DOGE", "ENJ", "ETC", "FTM", "GLM", "HOT", "LAZIO", "LINK", "LTC", "MATIC", "MKR", "OMG", "ONT", "PORTO", "REEF", "SHIB", "SNX", "STORJ", "SUSHI", "TRX","UMA","UNI","USDC","XLM","YFI","ZIL","ZRX"];
    $network = [];
    $pin_network = [];
    
    include_once WEB_PATH . "/common/cache_file.class.php";
    $cachFile = new cache_file();
    $data_list = $cachFile->get("s6_api", '', 'data', 'currency');
    $response = json_decode($data_list);

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
                        
                        if($chain->chain_tag == "erc20") {
                            $fee = 5;
                        }
                        else if ($chain->chain_tag == "trc20") {
                            $fee = 1.5;
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
    return json_encode(['status' => 0, 'info' => "Error on API", "msg" => print_r($response)]);

    // $coins = [
    //     "USDT" => 825,
    //     "ETH" => 1027,
    //     "BTC" => 1,
    //     "AAVE" => 7278,
    //     "ADA" => 2010,
    //     "AIRT" => 10905,
    //     "ALU" => 9637,
    //     "AVAX" => 5805,
    //     "BABY" => 10334,
    //     "BCH" => 1831,
    //     "BFG" => 11038,
    //     "BNB" => 1839,
    //     "BSW" => 10746,
    //     "BTT(OLD)" => 3718,
    //     "BTT(NEW)" => 16086,
    //     "C98" => 10903,
    //     "CAKE" => 7186,
    //     "CHZ" => 4066,
    //     "COMP" => 5692,
    //     "DAI" => 4943,
    //     "DASH" => 131,
    //     "DOGE" => 74,
    //     "ENJ" => 2130,
    //     "ETC" => 1321,
    //     "FTM" => 3513,
    //     "GLM" => 1455,
    //     "HOT" => 2682,
    //     "LAZIO" => 12687,
    //     "LINK" => 1975,
    //     "LTC" => 2,
    //     "MATIC" => 3890,
    //     "MKR" => 1518,
    //     "OMG" => 1808,
    //     "ONT" => 2566,
    //     "PORTO" => 14052,
    //     "REEF" => 6951,
    //     "SHIB" => 5994,
    //     "SNX" => 2586,
    //     "STORJ" => 1772,
    //     "SUSHI" => 6758,
    //     "TRX" => 1958,
    //     "UMA" => 5617,
    //     "UNI" => 7083,
    //     "USDC" => 3408,
    //     "XLM" => 512,
    //     "YFI" => 5864,
    //     "ZIL" => 2469,
    //     "ZRX" => 1896,
    // ];

    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_URL => "152.32.214.196:8917/account/getAddress",
    //     CURLOPT_FOLLOWLOCATION => 0,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_TIMEOUT => 3,
    //     CURLOPT_POST => 1,
    //     CURLOPT_CUSTOMREQUEST => "POST",
    //     CURLOPT_TIMEOUT => 100,
    //     CURLOPT_POSTFIELDS => json_encode([
    //         "merchant" => "j9",
    //         "outmemid" => $data->username_email,
    //         "notifyurl" => "https://999.game",
    //     ]),
    //     CURLOPT_HTTPHEADER => [
    //         'Content-Type: application/json; charset=utf-8',
    //     ],
    // ));

    // $response = curl_exec($curl);

    // if (curl_errno($curl)) {
    //     return curl_error($curl);
    // }
    // curl_close($curl);

    // $response = json_decode($response);

    // if (isset($response->data)) {
    //     foreach ($response->data as $wallet) {
    //         array_push($network, [
    //             "address" => $wallet->address,
    //             "network" => $wallet->currency,
    //             "name" => strtoupper($wallet->currencytype),
    //         ]);
    //     }

    //     return json_encode(['status' => 1, 'info' => $network]);
    // }
    // return json_encode(['status' => 0, 'info' => "Error on API", "msg" => print_r($response)]);
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
