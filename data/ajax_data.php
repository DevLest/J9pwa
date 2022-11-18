<?php
/**
 *用来处理前台页面提交的表单
 *通过phprpc存储到数据库中
 */

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
//error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: https://u2daszapp.u2d8899.com");
header("Access-Control-Allow-Credentials:true");
define("WEB_PATH", __DIR__);
//error_reporting(E_ALL);
include_once "core.class.php";
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['type']) && $_GET['type'] == "onlinepay_list_v1") {
    echo onlinepay_list_v1($_GET['payType']);exit;
} elseif (isset($_GET['type']) && $_GET['type'] == "washcodeself_list") {
    echo washcodeself_list();
} elseif (isset($_GET['type']) && $_GET['type'] == "washcodeself_receive") {
    echo washcodeself_receive($_GET['id']);
} elseif (isset($_GET['type']) && $_GET['type'] == "transfer_list") {
    echo get_transferlist();
} elseif (isset($_GET['type']) && $_GET['type'] == "deposit_bank") {
    echo deposit_bank($_GET['bank_type']);
} elseif (isset($_GET['type']) && $_GET['type'] == "onlinepay_bank") {
    echo onlinepay_bank();
} elseif (isset($_POST['type']) && $_POST['type'] == "count_record") {
    echo count_record($_POST['username_email'], $_POST['record_type']);
} elseif (isset($_GET['type']) && $_GET['type'] == "noread_message") {
    echo count_noread_message();
} elseif (isset($_GET['type']) && $_GET['type'] == "get_notice") {
    echo get_notice($_GET['num']);
} elseif (isset($_GET['type']) && $_GET['type'] == "delete_message") {
    echo delete_message($_GET['ids']);
} elseif (isset($_GET['type']) && $_GET['type'] == "monlinepay_list") {
    echo monlinepay_list();
} elseif (isset($_GET['type']) && $_GET['type'] == "onlinepay_list") {
    echo onlinepay_list();
} elseif (isset($_GET['type']) && $_GET['type'] == "monlinepay_bank") {
    echo monlinepay_bank($_GET['id']);
} elseif (isset($_GET['type']) && $_GET['type'] == "syn_password") {
    echo syn_password();
} elseif (isset($_GET['type']) && $_GET['type'] == "return_null") {
    echo return_null();
} elseif (isset($_GET['type']) && $_GET['type'] == "get_number") {
    echo get_number($_SESSION['account'], $_GET['gameid']);
} elseif (isset($_GET['type']) && $_GET['type'] == "get_prize") {
    echo get_prize($_SESSION['account'], $_GET['name']);
} elseif (isset($_GET['type']) && $_GET['type'] == "check_egg_status") {
    echo check_egg_status($_SESSION['account'], $_GET['prize_type']);
} elseif (isset($_GET['type']) && $_GET['type'] == "get_lastweek_deposit") {
    echo get_lastweek_deposit($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == "apply_special_spring") {
    echo apply_special_spring($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == "get_promotion") {
    if (isset($_GET['id']) && $_GET['id'] != '') {
        $id = $_GET['id'];
    } else {
        $id = "";
    }
    echo get_promotion($_GET['list'], $id);
} elseif (isset($_GET['type']) && $_GET['type'] == "get_total_deposit") {
    echo get_total_deposit($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == "apply_nt_promotion") {
    echo apply_nt_promotion($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == "promotion_history") {
    echo promotion_history($_SESSION['account'], $_GET['promotion_name']);
} elseif (isset($_GET['type']) && $_GET['type'] == "get_qt_bet") {
    echo get_qt_bet($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == "apply_qt_promotion") {
    echo apply_qt_promotion($_SESSION['account'], $_GET['promotion_level']);
} elseif (isset($_GET['type']) && $_GET['type'] == "special_promotion_record") {
    echo special_promotion_record($_SESSION['account'], $_GET['promotion_name']);
} elseif (isset($_GET['type']) && $_GET['type'] == "newegg_promotion_record") {
    echo newegg_promotion_record($_SESSION['account'], $_GET['promotion_name']);
} elseif (isset($_GET['type']) && $_GET['type'] == "get_prize_new") {
    echo get_prize_new($_SESSION['account'], $_GET['name'], $_GET['Is_up']);
    //echo 111;
} elseif (isset($_GET['type']) && $_GET['type'] == "check_egg_status_new") {
    echo check_egg_status_new($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == "chickenoregg_promotion_record") {
    echo chickenoregg_promotion_record($_SESSION['account'], $_GET['promotion_name']);
} elseif (isset($_GET['type']) && $_GET['type'] == "get_laborPro_history") {
    echo get_laborPro_history($_SESSION['account'], $_GET['promotion_name']);
} elseif (isset($_GET['type']) && $_GET['type'] == "zongzi") {
    echo apply_zongzi_promotion();
} elseif (isset($_GET['type']) && $_GET['type'] == 'week_gift_list') {
    echo week_gift_list($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == 'get_address') {
    echo get_address();
} elseif (isset($_GET['type']) && $_GET['type'] == 'august_gift_list') {
    echo august_gift_list($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == 'get_bet') {
    echo get_bet($_GET['gametype']);
} elseif (isset($_GET['type']) && $_GET['type'] == 'checkdeposit') {
    echo checkdeposit($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == 'canceldeposit') {
    echo canceldeposit($_SESSION['account']);
} elseif (isset($_GET['type']) && $_GET['type'] == 'getDepositBankByBankid') {
    echo getDepositBankByBankid($_GET['id']);
} elseif (isset($_GET['type']) && $_GET['type'] == 'apply_bet_bonus') {
    echo apply_bet_bonus($_GET['id']);
}
//print_r($_SESSION['account']);exit;
$api_key = 'fghrtrvdfger';
$time = substr(time(), 0, -3);

$auth_check = md5($time . $api_key);
$auth = $_POST['auth'];
/*print_r($_POST['auth']);
echo "</br>?";
print_r($auth_check);exit;*/
// print_r($_POST['username']);
// print_r($_POST['auth']);exit;
if ($auth_check != $auth) {

    echo json_encode(array('status' => 0, 'info' => "Verification failed"));
    exit();
}
if (!isset($_SESSION['account']) || (isset($_SESSION['account']) && $_SESSION['account'] != $_POST['username_email'])) {

    $p = $_POST['password'];

    $co = new core();
    $re = $co->member_login($_POST['username_email'], $p);
    if (is_array($re)) {
        $_SESSION['account'] = $re['account'];
        $_SESSION['balance'] = $re['balance'];
        $_SESSION['member_name'] = $re['realName'];
        $_SESSION['member_type'] = $re['memberType'];

    } elseif ($re == 1001) {
        echo json_encode(array(
            'status' => -2,
            'info' => 'The game account or password is incorrect!',
        ));
        exit();
    } elseif ($re == 1002) {
        echo json_encode(array(
            'status' => -2,
            'info' => 'The account is locked, please contact online customer service!',
        ));
        exit();
    } else {
        echo json_encode(array(
            'status' => -2,
            'info' => 'System error. Try again later!',
        ));
        exit();
    }
}

if (isset($_POST['type']) && $_POST['type'] == "get_memberinfo") {
    echo get_memberinfo();
} elseif (isset($_POST['type']) && $_POST['type'] == "get_point") {
    echo get_point();
} elseif (isset($_POST['type']) && $_POST['type'] == "washcodeself_list") {

    echo washcodeself_list();
} elseif (isset($_POST['type']) && $_POST['type'] == "washcodeself_receive") {
    echo washcodeself_receive($_POST['id']);
} elseif (isset($_POST['type']) && $_POST['type'] == "apply_rescue") {
    echo apply_rescue($_POST['rescue_type']);
} elseif (isset($_POST['type']) && $_POST['type'] == "unlock") {
    echo gameapi_unlock($_POST['gameid']);
} elseif (isset($_POST['type']) && $_POST['type'] == "active") {
    echo gameapi_active($_POST['gameid']);
} elseif (isset($_POST['type']) && $_POST['type'] == "sync") {
    echo gameapi_password($_POST['gameid']);
} elseif (isset($_POST['type']) && $_POST['type'] == "logout") {
    echo gameapi_logout($_POST['gameid']);
} elseif (isset($_POST['type']) && $_POST['type'] == "get_platform") {
    echo get_platform();
} elseif (isset($_POST['type']) && $_POST['type'] == "record_list") {
    echo record_list($_POST['record_type'], $_POST['page']);
} elseif (isset($_POST['type']) && $_POST['type'] == "msgcontent") {
    echo get_msgcontent($_POST['id']);
} elseif (isset($_POST['type']) && $_POST['type'] == "transfer_list_v1") {
    echo get_transferlist_v1();
} elseif (isset($_POST['type']) && $_POST['type'] == "get_balance") {
    echo get_balance($_SESSION['account'], $_POST['gameid']);
} elseif (isset($_POST['type']) && $_POST['type'] == "get_bindCardBankType") {
    echo get_bindCardBankType();
} elseif (isset($_POST['type']) && $_POST['type'] == "bank_list") {
    echo bank_list($_SESSION['account']);
} elseif (isset($_POST['type']) && $_POST['type'] == "bank_unbind") {
    echo bank_unbind($_POST['id']);
} elseif (isset($_POST['type']) && $_POST['type'] == "cancel_debit") {
    echo cancel_debit($_POST['id']);
} elseif (isset($_POST['type']) && $_POST['type'] == "app_first_login") {
    echo app_first_login($_SESSION['account'], $_POST['device_id']);
} elseif (isset($_POST['type']) && $_POST['type'] == "app_pay_give") {
    echo app_pay_give($_SESSION['account']);
} elseif (isset($_POST['type']) && $_POST['type'] == "change_information") {
    echo change_information($_POST['email'], $_POST['realname'], $_POST['birthday'], $_POST['qq'], $_POST['wechat'], $_POST['phone']);
} elseif (isset($_POST['type']) && $_POST['type'] == "change_phone_verify") {
    echo change_phone_verify();
} elseif (isset($_POST['type']) && $_POST['type'] == "noread_message") {

    ///echo 1324;exit;// 这一步能访问到 说明是下一步的问题
    echo count_noread_message();
} elseif (isset($_POST['type']) && $_POST['type'] == "verification_code") {

    ///echo 1324;exit;// 这一步能访问到 说明是下一步的问题
    echo verification_code($_POST['phone']);
} elseif (isset($_POST['type']) && $_POST['type'] == "get_vip_level") {
    echo get_vip_level($_SESSION['account']);
} elseif (isset($_POST['type']) && $_POST['type'] == "upload_pictures") {
    echo upload_pictures($_SESSION['account']);
} elseif (isset($_POST['type']) && $_POST['type'] == "get_imgurl") {
    echo get_imgurl($_SESSION['account']);
} elseif (isset($_POST['type']) && $_POST['type'] == "get_transfer_status") {
    echo get_transfer_status($_SESSION['account']);
} elseif (isset($_POST['type']) && $_POST['type'] == "change_transfer_status") {
    echo change_transfer_status($_SESSION['account'], $_POST['transfer_status']);
} elseif (isset($_POST['type']) && $_POST['type'] == "auto_transfer_in") {
    echo auto_transfer_in($_SESSION['account'], $_POST['gameId'], $_POST['currency']);
} elseif (isset($_POST['type']) && $_POST['type'] == "agent_member_deposit") {
    echo agent_member_deposit($_POST['agent_member']);
} elseif (isset($_POST['type']) && $_POST['type'] == "agent_member_withdrawal") {
    echo agent_member_withdrawal($_POST['agent_member']);
} elseif (isset($_POST['type']) && $_POST['type'] == "agent_member_fanshui") {
    echo agent_member_fanshui($_POST['agent_member']);
} elseif (isset($_POST['type']) && $_POST['type'] == "agent_member_promotions") {
    echo agent_member_promotions($_POST['agent_member']);
} elseif (isset($_POST['type']) && $_POST['type'] == "agent_member_total") {
    echo agent_member_total($_POST['agent_member'], $_POST['total_type']);
} elseif (isset($_POST['type']) && $_POST['type'] == "add_game_collect") {
    if ((isset($_POST['username_email']) && $_POST['username_email'] != "") && (isset($_POST['gameId']) && $_POST['gameId'] != "") && (isset($_POST['gameCode']) && $_POST['gameCode'] != "")) {
        echo add_game_collect($_POST['username_email'], $_POST['gameId'], $_POST['gameCode'], $_POST['platform']);
    }
} elseif (isset($_POST['type']) && $_POST['type'] == "get_collect_game") {
    echo get_collect_game($_POST['username_email']);
} elseif (isset($_POST['type']) && $_POST['type'] == "remove_game_collect") {
    echo remove_game_collect($_POST['username_email'], $_POST['gameId'], $_POST['gameCode']);
} elseif (isset($_POST['type']) && $_POST['type'] == "agent_percentage_set") {
    //echo 4224;exit;
    echo agent_percentage_set($_POST['username_email'], $_POST['agent_percentage'], $_POST['is_default'], $_POST['remark']);
} elseif (isset($_POST['type']) && $_POST['type'] == "agent_percentage_list") {
    echo agent_percentage_list($_POST['username_email']);
} elseif (isset($_POST['type']) && $_POST['type'] == "set_agent_percentage_default") {
    echo set_agent_percentage_default($_POST['username_email'], $_POST['id']);
}

//获取游戏转账列表
function get_transferlist()
{
    $core = new core();
    $result = $core->get_transferlist();
    $str = "";
    if (is_array($result)) {
        $temp = array();
        foreach ($result as $key => $v) {
            if ($v['id'] == '120601' or $v['id'] == '120602') {
                $temp[] = $result[$key];
                unset($result[$key]);
            }
        }
        $result = array_merge($temp, $result);
        foreach ($result as $v) {
            $str .= "<option value='" . $v['id'] . "'>" . $v['platName'] . "</option>";
        }
    } else {
        $str .= "<option value=''>Gaming platform maintenance</option>";
    }
    return $str;
}
//获取游戏转账列表 v1
function get_transferlist_v1()
{
    $core = new core();
    $result = $core->get_transferlist_v1();
    $str = array();
    if (is_array($result)) {
        $temp = array();
        foreach ($result as $key => $v) {
            if ($v['id'] == '121401' or $v['id'] == '121402') {
                $temp[] = $result[$key];
                unset($result[$key]);
            }
        }
        $result = array_merge($temp, $result);

        $str = array('status' => 1, 'info' => $result);
    } else {
        $str = array('status' => 0, 'info' => '');
    }
    return json_encode($str);
}
//获取用户基本资料
function get_memberinfo()
{
    $core = new core();
    $info = $core->get_memberinfo($_SESSION['account']);
    return json_encode(array('status' => '1', 'info' => $info));
}
//获取Cuenta principal或游戏帐号余额
function get_balance($account, $gameid)
{
    //echo 21232;exit;
    $core = new core();
    $result = $core->get_balance($account, $gameid);
    if (!$result) {
        return json_encode(array('status' => 0, 'info' => 'Query failed'));
    } else {
        $path = '/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/images/currency';
        $files = array_diff(scandir($path), array('.', '..'));
        $output = [];
        $pin_output = [];

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

        foreach ($files as $file) {
            $current = str_replace(".png", "", $file);
            $balance = 0;
            foreach ($result as $currency => $value) {
                if ($currency == $current) {
                    $balance = $value;
                }
            }
            
            if (in_array(strtoupper($current), $pinned)) {
                array_push($pin_output, [
                    "currency" => $current,
                    "icon" => "https://999j9azx.u2d8899.com/j9pwa/images/currency/$file",
                    "balance" => $balance,
                ]);
            }
            else {
                array_push($output, [
                    "currency" => $current,
                    "icon" => "https://999j9azx.u2d8899.com/j9pwa/images/currency/$file",
                    "balance" => $balance,
                ]);
            }
        }
        
        $sorted = array_map(function($v) use ($pin_output) {
            return $pin_output[$v - 1];
        }, $pinned);
        
        // print_r($sorted);
        // print_r($pin_output);
        
        $output = array_merge($pin_output,$output);
        //$result = round($result,2);
        return json_encode(array('status' => 1, 'info' => $output));
    }
}
//获取可绑定的银行卡类型
function get_bindCardBankType()
{
    $str = array(
        "ICBC",
        "Agricultural Bank of China",
        "China Construction Bank",
        "Bank of China",
        "Minsheng Bank of China",
        "Merchant Bank of China",
        "Bank of Communications",
        "HSBC Bank",
        "Postal Savings Bank of China",
        "CITIC Bank",
        "Industrial Bank",
        "Ping a bank",
        "Everbright Bank of China",
        "Shanghai Pudong Development Bank",
    );
    return json_encode(array('status' => 1, 'info' => $str));
}
//获取用户银行信息
function bank_list($account)
{
    $core = new core();
    $result = $core->bank_list($account);
    $bank_list = array();
    if (is_array($result)) {
        foreach ($result as $v) {
            $v['debit_bank'] = str_replace("USDT", "", $v['bank_type']) . " | " . substr($v['bank_no'], -4) . ((str_contains($v['bank_type'], "USDT")) ? " | " . $v['bank_addr'] : "");

            $bank_list_branch = array(
                'id' => $v['id'],
                'member_id' => $v['member_id'],
                'account' => $v['account'],
                'bank_no' => $v['bank_no'],
                'bank_type' => $v['bank_type'],
                'bank_addr' => $v['bank_addr'],
                'realname' => $v['realname'],
                'bank_province' => $v['bank_province'],
                'bank_city' => $v['bank_city'],
                'debit_bank' => $v['debit_bank'],
            );
            Array_push($bank_list, $bank_list_branch);
            unset($bank_list_branch);
        }
        return json_encode(array('status' => 1, 'info' => $bank_list));
    } else {
        return json_encode(array('status' => 0, 'info' => 'Could not get bank card information'));
    }
}
//解绑银行信息
function bank_unbind($id)
{
    $core = new core();
    $result = $core->bank_unbind($id);
    if ($result == 1) {
        $info = $core->bank_list($_SESSION['account']);
        if (is_array($info)) {
            $bank_list = array();
            foreach ($info as $v) {
                $v['debit_bank'] = $v['bank_type'] . "-" . $v['realname'] . "-" . substr($v['bank_no'], -4);
                $bank_list_branch = array(
                    'id' => $v['id'],
                    'member_id' => $v['member_id'],
                    'account' => $v['account'],
                    'bank_no' => $v['bank_no'],
                    'bank_type' => $v['bank_type'],
                    'bank_addr' => $v['bank_addr'],
                    'realname' => $v['realname'],
                    'bank_province' => $v['bank_province'],
                    'bank_city' => $v['bank_city'],
                    'debit_bank' => $v['debit_bank'],
                );
                Array_push($bank_list, $bank_list_branch);
                unset($bank_list_branch);
            }
        }
        return json_encode(array('status' => 1, 'info' => $bank_list));
    } else {
        json_encode(array('status' => 0, 'info' => 'Failed to unlink the information, please try again or contact customer service online'));
    }
}
//获取存款银行信息
function deposit_bank($type)
{
    $core = new core();
    $member_type = $_SESSION['member_type'];
    $result = $core->deposit_bank($type, $member_type);
    $bank_list = "";
    if ($type == 0) {
        $bank_info = "<tr><td>Type of Bank</td><td>Name</td><td>Card number information</td></tr>";
    } else if ($type == 1) {
        $bank_info = "<tr><td>Payment Methods</td><td>AliPay Name</td><td>Alipay Account</td></tr>";
    }
    $erweima_name = "";
    if (is_array($result)) {
        foreach ($result as $v) {
            $bank_list .= "<option value='" . $v['id'] . "'>" . $v['bank_name'] . "-" . $v['account_name'] . "</option>";
            $bank_info .= "<tr><td>" . $v['bank_name'] . "</td><td>" . $v['account_name'] . "</td><td>" . $v['bank_no'] . "</td></tr>";
        }
        $erweima_name = explode("@", $v['bank_no']);
        $str = array($bank_list, $bank_info, $erweima_name[0]);
        return json_encode($str);
    } else {
        return "";
    }
}
//获取Pago en línea的银行列表
function onlinepay_bank()
{
    $data = include "onlinebank.config.php";
    $core = new core();
    $result = $core->onlinepay_info();
    $bank_type = $result['payCompany'];
    $list = $data[$bank_type];
    $info = "";
    foreach ($list as $v) {
        $info .= $v;
    }
    return $info;
}
//获取记录信息
function record_list($record_type, $page = 1, $pages = 20)
{
    $core = new core();
    if ($record_type == 'agent' || $record_type == 'promotions') {
        $pages = 5;
    }
    $result = $core->record_list($_SESSION['account'], $record_type, $page, $pages);
    //    return  $result;
    $str = array();
    if ($record_type == "deposit") {
        if (is_array($result)) {
            foreach ($result as $v) {
                switch ($v['payType']) {
                    case 5:$paytype = "Alipay";
                        break;
                    case 6:$paytype = "Tenpay";
                        break;
                    case 100:$paytype = "Pago en línea";
                        break;
                    default:$paytype = "Banca en línea/Alipay";
                        $endtime = strtotime($v['endTime']);
                        if ($endtime > 1506081600) {
                            if ($v['amount'] <= 50 && $v['amount'] >= 20) {
                                $amount_rate = 1;
                                $v['amount'] = $v['amount'];
                            } elseif ($v['amount'] >= 50) {
                                $amount_rate = $v['amount'] * 0.02;
                                $v['amount'] = $v['amount'];
                            }
                        }
                        break;
                }

                $deposit_info = array(
                    "payType" => $paytype,
                    "amount" => $v['amount'],
                    "endTime" => $v['endTime'],
                    "checkInfo" => $v['checkInfo'],
                    "status" => "éxito",
                );
                Array_push($str, $deposit_info);
                unset($deposit_info);

            }
            return json_encode(array('status' => 1, 'info' => $str));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "deposit_fail") {
        if (is_array($result)) {
            foreach ($result as $v) {
                switch ($v['payType']) {
                    case 5:$paytype = "Alipay";
                        break;
                    case 6:$paytype = "Tenpay";
                        break;
                    case 100:$paytype = "Pago en línea";
                        break;
                    default:$paytype = "Banca en línea/Alipay";
                        break;
                }
                $deposit_fail_info = array(
                    "payType" => $paytype,
                    "amount" => $v['amount'],
                    "requestTime" => $v['requestTime'],
                    "checkInfo" => $v['checkInfo'],
                    "status" => "Fallar",
                );
                Array_push($str, $deposit_fail_info);
                unset($deposit_fail_info);
            }
            return json_encode(array('status' => 1, 'info' => $str));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "debit") {
        // return $result;
        if (is_array($result)) {
            foreach ($result as $v) {
                switch ($v['verifyStatus']) {
                    case 0:$status = "Not reviewed";
                        break;
                    case 1:$status = "Consignment";
                        break;
                    case 2:$status = "Failed";
                        break;
                    case 3:$status = "success";
                        break;
                    case 4:$status = "Under review";
                        break;
                }
                $cardNumber = "***" . substr($v['cardNumber'], -4);
                if ($v['verifyComment'] == "用户取消") {
                    $v['verifyComment'] = 'cancelled by user';
                }
                $debit_info = array(
                    "id" => $v['id'],
                    "amount" => $v['amount'],
                    "bankInfo" => $v['bankInfo'],
                    "cardNumber" => $cardNumber,
                    "requestTime" => $v['requestTime'],
                    "verifyStatus" => $v['verifyStatus'],
                    'status' => $status,
                    "verifyComment" => $v['verifyComment'],
                );
                Array_push($str, $debit_info);
                unset($debit_info);
            }
            ;
            return json_encode(array('status' => 1, 'info' => array_slice($str, 0, 5)));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "transfer") {
        if (is_array($result)) {
            foreach ($result as $v) {
                switch ($v['tranStatus']) {
                    case 0:$status = "Fallar";
                        break;
                    case 1:$status = "éxito";
                        break;
                    case 2:$status = "Fallar";
                        break;
                }
                switch ($v['platName']) {
                    case 'Main Account-->IM':$platName = "Central Wallet-->IM Platform";
                        break;
                    case 'Main account-->BTI':$platName = "Central wallet-->BTI Physical Education";
                        break;
                    case 'Main account-->IBC':$platName = "Central wallet-->Saba Deportes";
                        break;
                    case 'Main Account-->AG':$platName = "Central Wallet-->AG Platform";
                        break;
                    case 'Main Account-->ebet':$platName = "Central Wallet-->EB Real Person";
                        break;
                    case 'Main Account-->KY':$platName = "Central Wallet-->Kaiyuan Chess & Cards";
                        break;
                    case 'Main Account-->CQ':$platName = "Central Wallet-->CQ Electronic";
                        break;
                    case 'Main Account-->PT':$platName = "Central Wallet-->Electronic PT";
                        break;
                    case 'Main account-->MG':$platName = "Central wallet-->MG Electronic";
                        break;
                    case 'Main Account-->Real BG':$platName = "Central Wallet-->Dayou Live";
                        break;
                    case 'Main Account-->Advanced Chess & Cards':$platName = "Central Wallet-->Gold Chess";
                        break;
                    case 'Main account-->TF games':$platName = "Main wallet-->Lightning";
                        break;
                    case 'Main Account-->AB Live':$platName = "Central Wallet-->Ober Live";
                        break;
                    case 'Main account-->Chess win-win':$platName = "Central wallet-->IM chess and cards";
                        break;

                    case 'IM-->Main Account':$platName = "IM Platform-->Central Wallet";
                        break;
                    case 'BTI-->Main account':$platName = "BTI Physical Education-->Central wallet";
                        break;
                    case 'IBC-->Main account':$platName = "Saba Deportes-->Central wallet";
                        break;
                    case 'AG-->Main Account':$platName = "AG Platform-->Central Wallet";
                        break;
                    case 'ebet-->Main Account':$platName = "EB Real Person-->Central Wallet";
                        break;
                    case 'KY-->Main Account':$platName = "Kaiyuan Chess & Cards-->Central Wallet";
                        break;
                    case 'CQ-->Main Account':$platName = "CQ Electronic-->Central Wallet";
                        break;
                    case 'PT-->Main Account':$platName = "PT Electronic-->Central Wallet";
                        break;
                    case 'MG-->Main Account':$platName = "MG Electronics-->Central Wallet";
                        break;

                    case 'Real BG-->Main Account':$platName = "Dayo Live-->Central Wallet";
                        break;
                    case 'Advanced Chess & Cards-->Main Account':$platName = "Golden Chess-->Central Wallet";
                        break;
                    case 'TF games-->Main account':$platName = "Lightning-->Middle wallet";
                        break;
                    case 'AB Live-->Main Account':$platName = "Ober Live-->Central Wallet";
                        break;
                    case 'Win-Win Chess-->Main Account':$platName = "IM Chess & Cards-->Main Wallet";
                        break;
                    default:$platName = $v['platName'];
                }
                $transfer_info = array(
                    "amount" => $v['amount'],
                    "requestTime" => $v['requestTime'],
                    "tranStatus" => $status,
                    "platName" => $platName,
                );
                Array_push($str, $transfer_info);
                unset($transfer_info);
            }
            return json_encode(array('status' => 1, 'info' => $str));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "promotion") {
        if (is_array($result)) {
            return json_encode(array('status' => 1, 'info' => $result));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "point") {
        if (is_array($result)) {
            return json_encode(array('status' => 1, 'info' => $result));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "rescue") {
        if (is_array($result)) {
            foreach ($result as $v) {
                $rescue_info = array(
                    "deposit_money" => $v['deposit_money'],
                    "debit_money" => $v['debit_money'],
                    "balance_money" => $v['balance_money'],
                    "ratio_limit" => $v['ratio_limit'] . "%",
                    "rescue_money" => $v['rescue_money'],
                    "add_time" => $v['add_time'],
                );
                Array_push($str, $rescue_info);
                unset($rescue_info);
            }
            return json_encode(array('status' => 1, 'info' => $str));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "autopromo") {
        if (is_array($result)) {
            foreach ($result as $v) {
                switch ($v['promotion_type']) {
                    case 1:$promotion_type = "Pen deposit";
                        break;
                    case 4:$promotion_type = "Pen deposit";
                        break;
                    case 2:$promotion_type = "First daily deposit";
                        break;
                    case 3:$promotion_type = "100% discount at Dragon Boat Festival in June";
                        break;
                    default:$promotion_type = "Special event";
                        break;
                }
                $autopromo_info = array(
                    "promotion_type" => $promotion_type,
                    "money" => $v['money'],
                    "amount" => $v['amount'],
                    "ratio" => $v['ratio'],
                    "add_time" => $v['add_time'],
                );
                Array_push($str, $autopromo_info);
                unset($autopromo_info);
            }
            return json_encode(array('status' => 1, 'info' => $str));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "washcode") {
        if (is_array($result)) {
            foreach ($result as $v) {
                //if($v['game_id'] != 1901 && $v['game_id'] != 1902){
                $v['game_id'] = $core->switch_array_view(1, $v['game_id']);
                $v['ratio'] = $v['ratio'] * 100;
                $v['ratio'] = $v['ratio'] . '%';
                if ($v['game_id'] == 1901 || $v['game_id'] == 1902 || $v['game_id'] == 1903) {
                    $v['ratio'] = '***';
                }
                $washcode_info = array(
                    "game_id" => $v['game_id'],
                    "number" => $v['number'],
                    "ratio" => $v['ratio'],
                    "money" => $v['money'],
                    "add_date" => $v['add_date'],
                );
                Array_push($str, $washcode_info);
                unset($washcode_info);
                //}
            }
            return json_encode(array('status' => 1, 'info' => $str));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "message") {
        if (is_array($result)) {
            return json_encode(array('status' => 1, 'info' => $result));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } elseif ($record_type == "batchpromo") {

        if (is_array($result)) {
            return json_encode(array('status' => 1, 'info' => $result));
        } else {
            return json_encode(array('status' => 0, 'info' => 'No related data found'));
        }
    } else if ($record_type == "agent") {
        $result = $core->record_list($_SESSION['account'], 'agent', $page, 5);
        //var_dump($result);
        //    $str = '<tr><td>好友账号</td><td>注册时间</td><td>操作</td></tr>';
        if (is_array($result)) {
            if (count($result) > 0) {
                $i = 1;
                /*foreach($result as $v){
                if(strtotime($v['regTime']) >strtotime('2018-04-11')){
                $str .='<tr><td id="acc_'.$i.'">'.$v['account'].'</td><td id="regT_'.$i.'">'.$v['regTime'].'</td><td><span onclick="apply_recdFriends('.$i.')">申请Dinero de regalo</span></td></tr>';

                $i++;
                }*/

                foreach ($result as $v) {

                    if (strtotime($v['regTime']) > strtotime('2018-04-11')) {
                        $deposit_info = array(

                            "regTime" => $v['regTime'],
                            "account" => $v['account'],

                        );
                        Array_push($str, $deposit_info);
                        unset($deposit_info);
                    }
                }
                return json_encode(array('status' => 1, 'info' => $str));
            } else {
                return json_encode(array('status' => 0, 'info' => "No records"));
            }
        } else {
            return json_encode(array('status' => 0, 'info' => "No records"));
        }
        return $str;
    } else if ($record_type == "promotions") {
        $result = $core->record_list($_SESSION['account'], 'promotions', $page, 5);
        //var_dump($result);
        //$str = '<tr><td>彩金</td><td>好友账号</td><td>申请状态</td><td>Tiempo de aplicación</td><td>备注</td></tr>';
        if (is_array($result)) {
            if (count($result) > 0) {
                /*foreach($result as $v){
                switch ($v['check_state']){
                case 0: $state = "Bajo revisión";break;
                case 1: $state = "éxito";break;
                case 2: $state = "Fallar";break;
                }

                $str .='<tr><td>'.$v['apply_money'].'</td><td>'.$v['invite_person'].'</td><td>'.$state.'</td><td>'.$v['add_time'].'</td><td>'.$v['check_info'].'</td></tr>';
                }*/

                foreach ($result as $v) {
                    switch ($v['check_state']) {
                        case 0:$state = "Bajo revisión";
                            break;
                        case 1:$state = "éxito";
                            break;
                        case 2:$state = "Fallar";
                            break;
                    }

                    $deposit_info = array(

                        "apply_money" => $v['apply_money'],
                        "invite_person" => $v['invite_person'],
                        "state" => $state,
                        "add_time" => $v['add_time'], //check_info
                        "check_info" => $v['check_info'],
                    );
                    Array_push($str, $deposit_info);
                    unset($deposit_info);

                }
                return json_encode(array('status' => 1, 'info' => $str));
            } else {
                return json_encode(array('status' => 0, 'info' => "No records"));
            }
        } else {
            return json_encode(array('status' => 0, 'info' => "No records"));
        }
        //return $str;
    }
}
//获取记录的总个数
function count_record($account, $record_type, $pages = 10)
{
    $core = new core();
    if ($record_type == 'agent' || $record_type == 'promotions') {
        $pages = 5;
    }
    $result = $core->count_record($account, $record_type);
    $page = ceil($result / $pages);
    if ($page == 0) {
        $page = 1;
    }
    return $page;
}
//用户自助Cancelar retiro con fallar
function cancel_debit($id)
{
    $core = new core();
    $status = $core->cancel_debit($_SESSION['account'], $id);
    if ($status == 1) {
        return json_encode(array('status' => 1, 'info' => 'Cancel withdrawal successfully'));
    } elseif ($status == 1201) {
        return json_encode(array('status' => 0, 'info' => 'Cancel withdrawal with fail，Customer service is already under review.。'));
    } else {
        return json_encode(array('status' => 0, 'info' => 'Cancel withdrawal with fail，Contact online customer service' . $status));
    }
}
//获取用户的积分信息
function get_point()
{
    $core = new core();
    $info = $core->get_point($_SESSION['account']);
    $str = array(
        'all' => $info['credits'],
        'use' => $info['credits_use'],
    );
    return json_encode(array('status' => 1, 'info' => $str));
}
//用户自助申请老虎机救援
function apply_rescue($rescue_type)
{
    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        $account = $_SESSION['account'];
        $limit_check = 0;
        //加载缓存
        include_once WEB_PATH . "/common/cache_file.class.php";
        //获取缓存数据
        $cachFile = new cache_file();
        $data_list = $cachFile->get($account, '', 'data', 'rescue_limit');
        if ($data_list == 'false') {
            $limit_time = array("limit_time" => time());
            $cachFile->set($account, $limit_time, '', 'data', 'rescue_limit');
            $limit_check = 1;
        } else {
            if ((time() - $data_list['limit_time']) > 180) {
                $limit_time = array("limit_time" => time());
                $cachFile->set($account, $limit_time, '', 'data', 'rescue_limit');
                $limit_check = 1;
            }
        }
        if ($limit_check == 0) {
            return json_encode(array('status' => 0, 'info' => 'Click Apply again after 3 minutes.'));
        }
        $core = new core();

        switch ($rescue_type) {
            case 1: //3 Days
                $start_date = date('Y-m-d 00:00:00', time());
                $end_date = date('Y-m-d 23:59:59', time());
                break;
            default: // 24h
                $start_date = date('Y-m-d 00:00:00', strtotime(' - 1 days'));
                $end_date = date('Y-m-d 23:59:59', strtotime(' - 1 days'));
                break;
        }
        $bets = $core->uniterecord($account, $start_date, $end_date);

        if (is_array($bets)) {
            foreach ($bets['list'] as $bet) {
                if ($cacheData[$bet['gamecode']]['gameType'] == 'live' || $cacheData[$bet['gamecode']]['gameType'] == 'sports') {
                    return json_encode(array('status' => 0, 'info' => 'You bet on sports or live games that makes you unable to meet the terms and conditions of the offer.'));

                }

            }
        }
        $status = $core->apply_rescue($account, $rescue_type);
        if ($status === 1) {
            return json_encode(array('status' => 1, 'info' => 'Rescue Bonus redemption is successful, please check'));
        } elseif ($status == 1041) {
            return json_encode(array('status' => 0, 'info' => 'The redemption application failed, the application date is invalid'));
        } elseif ($status == 1042) {
            return json_encode(array('status' => 0, 'info' => 'The rescue bonus request has been rejected due to a pending withdrawal request'));
        } elseif ($status == 1043) {
            return json_encode(array('status' => 0, 'info' => 'The rescue bonus request failed. Unable to meet the terms and conditions of the offer'));
        } elseif ($status == 1044) {
            return json_encode(array('status' => 0, 'info' => 'The rescue bonus request failed because your total account balance is greater than 15MXN'));
        } elseif ($status == 1045) {
            return json_encode(array('status' => 0, 'info' => 'The rescue bonus request failed. The minimum loss profit must be greater than 900MXN'));
        } elseif ($status == 1046) {
            return json_encode(array('status' => 0, 'info' => 'The rescue bonus has been claimed yesterday, please check the record'));
        } else {
            return json_encode(array('status' => 0, 'info' => 'The rescue bonus request failed. Please refer to the terms and conditions of the offer'));
        }
    } else {
        return json_encode(array('status' => 0, 'info' => 'System error, please log in again'));
    }
}
//获取站内信的详细内容
function get_msgcontent($id)
{
    $core = new core();
    $info = $core->get_msgcontent($_SESSION['account'], $id);
    return json_encode(array('status' => 1, 'info' => $info));
}
//获取未读信息的数量
function count_noread_message()
{
    $core = new core();
    $info = $core->count_noread_message($_SESSION['account']);
    return json_encode(array('status' => 1, 'info' => $info));
}
//获取历史公告
function get_notice($num)
{
    $core = new core();
    $info = $core->get_notice($num);
    $str = "";
    if ($num == 1) {
        $str .= $info;
    } else {
        $str .= "<tr><td>Ad content</td><td>Ad time</td></tr>";
        foreach ($info as $v) {
            $str .= "<tr><td>" . $v['content'] . "</td><td>" . $v['edit_time'] . "</td></tr>";
        }
    }
    return $str;
}
//根据传递的ID序列 进行删除站内信
function delete_message($ids)
{
    $core = new core();
    $status = $core->delete_data($ids, "ks_message_data");
    if ($status == 1) {
        return "Delete data successfully";
    } else {
        return "Failed to delete data, please refresh and try again";
    }
}

//获取多线路Pago en línea的银行列表
function monlinepay_bank($id)
{
    $data = include "onlinebank.config.php";
    $temp = explode("_", $id);
    $bank_type = $temp[2];
    $line_type = $temp[3];
    $info = "";
    if ($line_type == 10 || $line_type == 21 || $line_type == 22) {
        //乐盈Alipay
        $info = '<option value="zfb">Alipay</option>';

        //共用的
        //    $info = '<option value="6011">手机Banca en línea</option>';
    } elseif ($line_type == 174 || $line_type == 20) {
        //共用的
        $info = '<option value="6011">Banking in bank transfer</option>';
    } elseif ($line_type == 1 || $line_type == 3 || $line_type == 6 || $line_type == 10 || $line_type == 13 || $line_type == 14 || $line_type == 15 || $line_type == 18 || $line_type == 19 || $line_type == 23 || $line_type == 24 || $line_type == 32) {
        //共用的
        $info = '<option value="6011">Alipay</option>';
    } elseif ($line_type == 2) {
        //共用的
        $info = '<option value="6011">Cloud QuickPass</option>';
    } elseif ($line_type == 4 || $line_type == 12 || $line_type == 31 || $line_type == 34) {
        //共用的
        $info = '<option value="6011">Banking in bank transfer</option>';
    } elseif ($line_type == 5 || $line_type == 8) {
        //共用的
        $info = '<option value="6011">UnionPay scan code</option>';
    } elseif ($line_type == 9 || $line_type == 11 || $line_type == 33) {
        //共用的
        $info = '<option value="6011">WeChat</option>';
    } elseif ($line_type == 16) {
        //共用的
        $info = '<option value="6011">Fast</option>';
    } elseif ($line_type == 26 || $line_type == 27 || $line_type == 29) {
        //共用的
        $info = '<option value="6011">Enter and choose a bank</option>';
    } elseif ($line_type == 30 || $line_type == 37) {
        //共用的
        $info = '<option value="6011">USDT Anchor</option>';
    } elseif ($line_type == 35 || $line_type == 36) {
        //共用的
        $info = '<option value="6011">Alipay</option>';
    } elseif ($line_type == 38 || $line_type == 39 || $line_type == 41 || $line_type == 42 || $line_type == 40) {
        //共用的
        $info = '<option value="6011">Pay</option>';
    } elseif ($line_type == 70) {
        //智付点卡
        $list = $data[101];
        if (is_array($list)) {
            foreach ($list as $v) {
                $info .= $v;
            }
        }
    } else {
        //普通Pago en línea银行信息
        $list = $data[$bank_type];
        if (is_array($list)) {
            foreach ($list as $v) {
                $info .= $v;
            }
        }
    }
    return $info;
}
//同步用户密码到游戏平台
function syn_password()
{
    $core = new core();
    $name = "syn_password";
    $data = array(
        "account" => $_SESSION['account'],
    );
    $info = $core->asyn_execute($name, $data);
    return "The function has not been activated.";
    //return "密码同步完成，请5分钟后登录游戏。如无法登录，Póngase en contacto con el servicio de atención al cliente en línea";
}
//获取会员的当天 agua corriendo情况
function get_number($account, $gameid)
{
    $core = new core();
    $result = $core->get_number($account, $gameid);
    if ($result < 0) {
        return "Query failed";
    } else {
        return $result . " Water running";
    }
}

//查询会员上周的Deposito total
function get_lastweek_deposit($account)
{
    $core = new core();
    $result = $core->get_lastweek_deposit($account);
    return $result . "USDT";
}
//会员申请 春季赞歌优惠活动
function apply_special_spring($account)
{
    exit();
    $core = new core();
    $status = $core->apply_special_spring($account);
    if ($status == 1) {
        return "Discount request is successful";
    } elseif ($status == 1051) {
        return "Conditions not met or applied";
    } elseif ($status == 1052) {
        return "Before the time of application, please check the official website for more details.";
    } else {
        return "System error. Try again later";
    }
}
//获取优惠活动列表（id=0）或者信息（id=..）
function get_promotion($list, $id)
{
    //加载缓存
    include_once WEB_PATH . "/common/cache_file.class.php";
    //获取缓存数据
    $cachFile = new cache_file();
    $data_list = $cachFile->get('active_list', '', 'data', 'active_list');
    if ($data_list == 'false') {
        $core = new core();
        $result = $core->get_promotion();
        if (is_array($result)) {
            $cachFile->set('active_list', $result, '', 'data', 'active_list');
        }
        $data_list = $cachFile->get('active_list', '', 'data', 'active_list');
    }

    if ($list == 0) {
        $li = "";
        foreach ($data_list as $key => $val) {
            $li .= "<li><a href='javascript:get_promocontent(" . $key . ")'>" . $val['title'] . "</a></li>";
        }
        return json_encode($li);
    } else {

        $content = $data_list[$id]['content'];
        return json_encode($content);
    }
}

//计算概率
function getRand($num)
{
    $id = 0;
    if ($num <= 55) {
        $id = 0;
    } elseif ($num <= 75 && $num > 55) {
        $id = 1;
    } elseif ($num <= 90 && $num > 75) {
        $id = 2;
    } elseif ($num <= 97 && $num > 90) {
        $id = 3;
    } else {
        $id = 4;
    }
    return $id;
}
//返回空值，用于模拟窗口关闭
function return_null()
{
    return "";
}

function get_platform()
{
    //游戏解锁
    $unlock_plat = array('1203', '1206');
    //游戏激活
    $active_plat = array('1202', '1203', '1204', '1206', '1207', '1227', '1210', '1211', '1212', '1213', '1214', '1215', '1219', '1222', '1223', '1224', '1225', '1226', '1229', '1230', '1231');
    //同步密码
    $sync_plat = array('1202', '1203', '1204', '1206', '1227', '1212', '1224', '1229');
    //强制登出
    $logout_plat = array('1203', '1206');
    $all_platform = array('unlock' => $unlock_plat, 'active' => $active_plat, 'sync' => $sync_plat, 'logout' => $logout_plat);
    return json_encode(array('status' => 1, 'info' => $all_platform));
}

//游戏解锁
function gameapi_unlock($gameid)
{
    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        $account = $_SESSION['account'];
        $core = new core();
        $info = $core->gameapi_unlock($account, $gameid);
        if ($info == 1) {
            return json_encode(array('status' => 1, 'info' => 'Congratulations, the game has been successfully unlocked!'));
        } elseif ($info == 1101) {
            return json_encode(array('status' => 0, 'info' => 'Sorry, the game platform is under maintenance....'));
        } elseif ($info == 1102) {
            return json_encode(array('status' => 0, 'info' => 'Game unlock failed, please contact customer service'));
        } else {
            return json_encode(array('status' => 0, 'info' => 'Unknown error, please contact customer service'));
        }
    } else {
        return json_encode(array('status' => 1, 'info' => 'System error, please log in again'));
    }
}
//游戏激活
function gameapi_active($gameid)
{
    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        $account = $_SESSION['account'];
        $core = new core();
        $info = $core->gameapi_active($account, $gameid);

        if ($info == 1) {
            return json_encode(array('status' => 1, 'info' => 'Congratulations, the game has been activated successfully!'));
        } elseif ($info == 1101) {
            return json_encode(array('status' => 0, 'info' => 'Sorry, the game platform is under maintenance....'));
        } elseif ($info == 1102) {
            return json_encode(array('status' => 0, 'info' => 'The account has been activated'));
        } else {
            return json_encode(array('status' => 0, 'info' => 'Unknown error, please contact customer service'));
        }
    } else {
        return json_encode(array('status' => 0, 'info' => 'System error, please log in again'));
    }
}
//游戏同步密码
function gameapi_password($gameid)
{
    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        $account = $_SESSION['account'];
        $core = new core();
        $info = $core->gameapi_password($account, $gameid);

        if ($info == 1) {
            return json_encode(array('status' => 1, 'info' => 'Congratulations, you have successfully synced the passwords!'));
        } elseif ($info == 1101) {
            return json_encode(array('status' => 0, 'info' => 'Sorry, the game platform is under maintenance....'));
        } elseif ($info == 1102) {
            return json_encode(array('status' => 0, 'info' => 'Failed to sync password, please contact customer service'));
        } else {
            return json_encode(array('status' => 0, 'info' => 'Unknown error, please contact customer service'));
        }
    } else {
        return json_encode(array('status' => 0, 'info' => 'System error, please log in again'));
    }
}
//游戏强制离线
function gameapi_logout($gameid)
{
    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        $account = $_SESSION['account'];
        $core = new core();
        $info = $core->gameapi_logout($account, $gameid);

        if ($info == 1) {
            return json_encode(array('status' => 1, 'info' => 'Congratulations, the game was successfully forced offline!'));
        } elseif ($info == 1101) {
            return json_encode(array('status' => 0, 'info' => 'Sorry, the game is under maintenance....'));
        } elseif ($info == 1102) {
            return json_encode(array('status' => 0, 'info' => 'Could not force disconnection, please contact customer service'));
        } else {
            return json_encode(array('status' => 0, 'info' => 'Unknown error, please contact customer service'));
        }
    } else {
        return json_encode(array('status' => 0, 'info' => 'System error, please log in again'));
    }
}
//自助领取返水列表
function washcodeself_list()
{

    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        $account = $_SESSION['account'];
        $core = new core();
        $result = $core->washcodeself_list($account);
        /*if($result == null){
        $platform = array(
        array('gameid'=>'gid1201','amount'=>'0.00','platName'=>'Plataforma IM'),
        array('gameid'=>'gid1204','amount'=>'0.00','platName'=>'Plataforma AG'),
        array('gameid'=>'gid1206','amount'=>'0.00','platName'=>'BTI平台'),

        );
        }else{
        $platform = array(
        array('gameid'=>'gid1201','amount'=>$result['gid1201'],'platName'=>'Plataforma IM'),
        array('gameid'=>'gid1204','amount'=>$result['gid1204'],'platName'=>'Plataforma AG'),
        array('gameid'=>'gid1206','amount'=>$result['gid1206'],'platName'=>'BTI平台'),

        );
        }*/
        if (empty($result)) {

            $result = array('gid1906' => 0, 'gid1907' => 0, 'gid1908' => 0, 'gid1903' => 0, 'gid1905' => 0, 'gid1909' => 0, 'gid1910' => 0, 'gid1910' => 0, 'gid1911' => 0, 'gid1912' => 0, 'gid1913' => 0, 'gid1914' => 0, 'gid1915' => 0, 'gid1916' => 0);
        }
        return json_encode(array('status' => 1, 'info' => $result));
    }
}
//自助领取返水
function washcodeself_receive($gid)
{

    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        if ($gid == 'account' || $gid == 'id') {
            exit();
        }
        $account = $_SESSION['account'];

        $limit_check = 0;
        //加载缓存
        include_once "common/cache_file.class.php";
        //获取缓存数据
        $cachFile = new cache_file();
        $data_list = $cachFile->get($account, '', 'data', 'washcode_limit');
        if ($data_list == 'false') {
            $limit_time = array("limit_time" => time());
            $cachFile->set($account, $limit_time, '', 'data', 'washcode_limit');
            $limit_check = 1;
        } else {
            if ((time() - $data_list['limit_time']) > 30) {
                $limit_time = array("limit_time" => time());
                $cachFile->set($account, $limit_time, '', 'data', 'washcode_limit');
                $limit_check = 1;
            }
        }
        if ($limit_check == 0) {
            //return json_encode(array('status'=>0,'info'=>'请30秒之后再点击领取'));
        }
        $core = new core();
        $ips = $core->ip_information();
        /*
         *gid为1时，全部领取
         */
        if ($gid == 1) {
            $list = $core->washcodeself_list($account);
            if (is_array($list)) {
                for ($i = 1201; $i <= 1206; $i++) {
                    if ($i != 1202 && $i != 1203 && $i != 1205) {
                        $gids = "gid" . $i;
                        if ($list[$gids] > 0) {
                            $result = $core->washcodeself_receive($account, $gids, $ips);
                            error_log(date('YmdHis') . "#" . $account . "#" . $gids . "#" . $list[$gids] . "#" . $result . "\r\n", 3, 'common/log/washcoderecord1.log');
                        }
                    }
                }
                $msg = array('status' => 1, 'info' => 'Receive complete! Please check the balance');
            } else {
                $msg = array('status' => 0, 'info' => 'Could not claim, please try again later');
            }
        } else {
            $result = $core->washcodeself_receive($account, $gid, $ips);
            if ($result == 1902) {
                $msg = array('status' => 0, 'info' => 'The amount is 0 and cannot be claimed');
            } else if ($result == 2001) {
                $msg = array('status' => 0, 'info' => 'The status is incorrect, please update and click to receive');
            } else if ($result == 1) {
                $msg = array('status' => 1, 'info' => 'Congratulations, you have received it successfully!');
            }
        }
        return json_encode($msg);
    }
}

//获取新的Pago en línea列表 代替 onlinepay_list
//返回$btn_id
function onlinepay_list_v1($payType)
{
    // return $_SESSION['member_type'];
    $core = new core();
    $info = $core->onlinepay_list(array("common_id" => $payType, "member_type" => $_SESSION['member_type'])); //获取所有的可用Pagar线路
    /*$info1 = $info;
    $btn_id = "";
    $pay_count = count($info1);
    if($pay_count > 0){
    if($pay_count > 1){
    //随机获取一个
    $randNum = mt_rand(0,$pay_count-1);
    }else{
    $randNum = 0;
    }
    $btn_id = "mpay_".$info[$randNum]['id']."_".$info[$randNum]['pay_type']."_".$info[$randNum]['line_type'];
    }
    return json_encode($arr=array("id"=>$btn_id));*/
    $str = '';
    foreach ($info as $v) {
        if ($_SESSION['member_type'] > 0) {
            if ($v['id'] != 32 && $v['id'] != 33 && $v['id'] != 35 && $v['id'] != 36) {
                $btn_id = "mpay_" . $v['id'] . "_" . $v['pay_type'] . "_" . $v['line_type'];
                $str .= '<div class="pay_btn" onclick="javascript:show_paytable(' . $payType . ',\'' . $btn_id . '\')" id="' . $btn_id . '">' . $v['show_name'] . '</div>';
            }
        } else {
            $btn_id = "mpay_" . $v['id'] . "_" . $v['pay_type'] . "_" . $v['line_type'];
            $str .= '<div class="pay_btn" onclick="javascript:show_paytable(' . $payType . ',\'' . $btn_id . '\')" id="' . $btn_id . '">' . $v['show_name'] . '</div>';
        }
    }
    /*if($payType == 1){
    $str .='<div class="pay_btn" onclick="javascript:get_payline(11,\'wx_z\')" >WeChat扫码</div>';
    }*/
    return json_encode($str);
}

//会员申请 NT新纪USDT优惠活动
function apply_nt_promotion($account)
{
    exit();
    $limit_check = 0;
    //加载缓存
    include_once "common/cache_file.class.php";
    //获取缓存数据
    $cachFile = new cache_file();
    $data_list = $cachFile->get($account, '', 'data', 'special_limit');
    if ($data_list == 'false') {
        $limit_time = array("limit_time" => time());
        $cachFile->set($account, $limit_time, '', 'data', 'special_limit');
        $limit_check = 1;
    } else {
        if ((time() - $data_list['limit_time']) > 120) {
            $limit_time = array("limit_time" => time());
            $cachFile->set($account, $limit_time, '', 'data', 'special_limit');
            $limit_check = 1;
        }
    }
    if ($limit_check == 0) {
        return "Haga clic en Aplicar después de 2 minutos";
    }
    //1.查询当日Deposito total，2.查询当前为第几次领取，3.判断是否达到当前领取优惠的条件(Deposito total是否满足)，4.插入数据，Descuento转入主帐号，再自动转入NT
    $starttime = strtotime("2016-10-13");
    $endtime = strtotime("2017-1-31 23:59:59");
    $now_time = time();
    if ($now_time > $starttime && $now_time < $endtime) {
        $core = new core();
        $ips = $core->ip_information();
        $ip = $ips['ip'];
        $address = iconv('GB2312', 'UTF-8', $ips['address']);
        $status = $core->apply_nt_promotion($account, $ip, $address);
        if ($status == 1) {
            return "Congratulations, the request was successful, the discount has been automatically transferred to the game platform.";
        } elseif ($status == 1051) {
            return "The total amount of the deposit does not meet the conditions of the request";
        } elseif ($status == 1053) {
            return "You have already requested this offer, come back tomorrow";
        } elseif ($status == 2001) {
            return "System error. Please try again later";
        }
    } else {
        return "The event has not started or has finished";
    }
}
//会员查询 当日优惠记录
function promotion_history($account, $promotion_name, $starttime = "", $endtime = "")
{
    $core = new core();
    $result = $core->promotion_history($account, $promotion_name, $starttime, $endtime);
    $list = "";
    //echo json_encode($result);
    if (is_array($result)) {
        $list .= "<tr><td colspan='4'>Today's discount record</td></tr><tr><td>Frequency</td><td>Discount</td><td>Total deposit</td><td>Application time</td ></tr>";
        foreach ($result as $v) {
            $list .= "<tr><td>" . $v['promotion_level'] . "</td><td>" . $v['amount'] . "</td><td>" . $v['total_deposit'] . "</td><td>" . $v['add_time'] . "</td></tr>";
        }
        return json_encode($list);
    } else {
        return "";
    }
}

//统计Deposito total
function get_total_deposit($account, $arr = "")
{
    if (is_array($arr)) {
        $starttime = $arr[0];
        $endtime = $arr[1];
    } else {
        $starttime = date("Y-m-d");
        $endtime = date("Y-m-d H:i:s");
    }
    $core = new core();
    $total_deposit = $core->get_total_deposit($account, $starttime, $endtime);
    if ($total_deposit == "") {
        $total_deposit = 0;
    }
    return $total_deposit;
}
//获取当日qt投注金额
function get_qt_bet($account)
{
    $core = new core();
    $total_bet = $core->get_qt_bet($account);
    if ($total_bet == "") {
        $total_bet = 0;
    }
    return $total_bet;
}
//获取QT优惠领取记录
function special_promotion_record($account, $promotion_name)
{
    $core = new core();
    $result = $core->special_promotion_record($account, $promotion_name);
    $list = "";
    //echo json_encode($result);
    if (is_array($result)) {
        $list .= "<tr><td colspan='4'>Today's discount record</td></tr><tr><td>Frequency</td><td>Discount</td><td>Total amount wagered QT</td><td>Application time< /td></tr>";
        foreach ($result as $v) {
            $list .= "<tr><td>" . $v['promotion_level'] . "</td><td>" . $v['amount'] . "</td><td>" . $v['total_bet'] . "</td><td>" . $v['add_time'] . "</td></tr>";
        }
        return json_encode($list);
    } else {
        return "";
    }
}
//申请USDT旦送礼，QT至上活动
function apply_qt_promotion($account, $promotion_level, $arr = "")
{
    exit();
    $limit_check = 0;
    //加载缓存
    include_once "common/cache_file.class.php";
    //获取缓存数据
    $cachFile = new cache_file();
    $data_list = $cachFile->get($account, '', 'data', 'special_limit');
    if ($data_list == 'false') {
        $limit_time = array("limit_time" => time());
        $cachFile->set($account, $limit_time, '', 'data', 'special_limit');
        $limit_check = 1;
    } else {
        if ((time() - $data_list['limit_time']) > 120) {
            $limit_time = array("limit_time" => time());
            $cachFile->set($account, $limit_time, '', 'data', 'special_limit');
            $limit_check = 1;
        }
    }
    if ($limit_check == 0) {
        return "Haga clic en Aplicar después de 2 minutos";
    }
    //申请QT的特殊优惠 1.查询是否已申请 2.查询投注量是否满足 3.写入记录和金额 4.转账到游戏平台
    $starttime = strtotime("2016-12-31");
    $endtime = strtotime("2017-1-31 23:59:59");
    $now_time = time();
    if ($now_time > $starttime && $now_time < $endtime) {
        $core = new core();
        $ips = $core->ip_information();
        $info['ip'] = $ips['ip'];
        $info['address'] = iconv('GB2312', 'UTF-8', $ips['address']);
        $status = $core->apply_qt_promotion($account, $promotion_level, $info);if ($status == 1) {
            return "Congratulations, the request was successful, the discount has been automatically transferred to the game platform.";
        } elseif ($status == 1051) {
            return "当日Total amount wagered QT does not meet the requirements of the application";
        } elseif ($status == 1053) {
            return "You have already requested this offer, come back tomorrow";
        } elseif ($status == 2001) {
            return "System error. Please try again later";
        }
    } else {
        return "The event has not started or has finished";
    }

}

//获取会员吃汤圆中奖信息---先有鸡or先有蛋
/*
 *$name,所砸的蛋的名称0,1,2,3
 *Is_up,从那个蛋升级来的0,1,2
 */
function get_prize_new($account, $name, $Is_up)
{
    //1.活动时间是否正确，2.查询存款情况，3.存款金额是否满足吃汤圆要求，4.获取吃汤圆记录，5.满足且没有砸过则随机获取奖金并发放到账户余额
    $starttime = strtotime("2018-02-23");
    $endtime = strtotime("2018-03-06 23:59:59");
    $now_time = time();

    if (($now_time > $starttime && $now_time < $endtime) || $account == "jacktest") {
        $limit_check = 0;
        $arr = array();
        //加载缓存
        if ($Is_up == -1) {
            include_once "common/cache_file.class.php";
            //获取缓存数据
            $cachFile = new cache_file();
            $data_list = $cachFile->get($account, '', 'data', 'special_limit');
            if ($data_list == 'false') {
                $limit_time = array("limit_time" => time());
                $cachFile->set($account, $limit_time, '', 'data', 'special_limit');
                $limit_check = 1;
            } else {
                if ((time() - $data_list['limit_time']) >= 30) {
                    $limit_time = array("limit_time" => time());
                    $cachFile->set($account, $limit_time, '', 'data', 'special_limit');
                    $limit_check = 1;
                }
            }
            if ($limit_check == 0) {
                $left_time = 30 - (time() - $data_list['limit_time']);
                $arr = array("msg" => 0, "prize" => "Please " . $left_time . " Go back to eating meatballs in seconds.");
                return json_encode($arr);
            }
        }
        $core = new core();
        $result = get_total_deposit($account);
        $add_time = date("Y-m-d H:i:s");
        $total_deposit = $result;
        //奖品列表
        $prize_arr = array(
            array("amount" => 8, "prize_name" => "Lucky Gift 8"), //0
            array("amount" => 12, "prize_name" => "Lucky gift 12"), //1
            array("amount" => 18, "prize_name" => "Lucky gift 18"), //2
            array("amount" => 22, "prize_name" => "Lucky gift 22"), //3
            array("amount" => 28, "prize_name" => "Lucky gift 28"), //4

            array("amount" => 36, "prize_name" => "Lucky gift 36"), //5
            array("amount" => 58, "prize_name" => "Lucky gift 58"), //6
            array("amount" => 68, "prize_name" => "Lucky gift 68"), //7
            array("amount" => 72, "prize_name" => "Lucky gift 72"), //8
            array("amount" => 88, "prize_name" => "Lucky gift 88"), //9

            array("amount" => 108, "prize_name" => "Lucky gift 108"), //10
            array("amount" => 128, "prize_name" => "Lucky gift 128"), //11
            array("amount" => 158, "prize_name" => "Lucky gift 158"), //12
            array("amount" => 188, "prize_name" => "Lucky gift 188"), //13
            array("amount" => 228, "prize_name" => "Lucky gift 228"), //14

            array("amount" => 188, "prize_name" => "Lucky gift 188"), //15
            array("amount" => 288, "prize_name" => "Lucky gift 288"), //16
            array("amount" => 388, "prize_name" => "Lucky gift 388"), //17
            array("amount" => 588, "prize_name" => "Lucky gift 588"), //18
            array("amount" => 688, "prize_name" => "Lucky gift 688"), //19

            array("amount" => 888, "prize_name" => "Lucky gift 888"), //20
            array("amount" => -1, "prize_name" => "Double Refund Card"), //21
            array("amount" => -2, "prize_name" => "Double points card"), //22
            array("amount" => -3, "prize_name" => "Double transfer card"), //23
        );
        //存款要求列表
        $money_arr = array(100, 500, 2000);

        //session值列表
        $state_arr = array(
            array('', 101, 102, 103),
            array('', '', 201, 202),
            array('', '', '', 301),
        );
        //获取$id
        if ($Is_up == -1) {
            if ($result >= $money_arr[$name]) {
                //设置session
                $_SESSION['eggState'] = ($name + 1) * 100 + 1;
                $id = getRand_new(mt_rand(1, 100), $name);
            } else {
                $arr = array("msg" => 0, "prize" => "Today's deposit is more than " . $money_arr[$name] . " To eat this plate of meatballs");
                return json_encode($arr);
            }
        } else {
            if (isset($_SESSION['eggState']) && $_SESSION['eggState'] == $state_arr[$Is_up][$name]) {
                if ($name == 2 && $Is_up == 0) {
                    $id = getRand_new(mt_rand(1, 100), 201);
                } else {
                    $id = getRand_new(mt_rand(1, 100), $name);
                }
                $_SESSION['eggState'] = $_SESSION['eggState'] + 1;
            } else {
                $arr = array("msg" => 0, "prize" => "Your operation is incorrect, please refresh the page.");
                unset($_SESSION['eggState']);
                return json_encode($arr);
            }
        }

        //获取对应奖励
        if ($id < 0) {
            if ($id == -1) {
                $prize_name = 'gold';
            } elseif ($id == -2) {
                $prize_name = 'pgold';
            } elseif ($id == -3) {
                $prize_name = 'topegg';
            }
            if ($Is_up == -1) {
                $Is_up = $name;
            }
            $arr = array("msg" => 2, "prize" => $prize_name, "Is_up" => $Is_up);
        } else {
            unset($_SESSION['eggState']);
            if ($Is_up == -1) {
                $egg_type = $name;
            } else {
                $egg_type = $Is_up;
            }
            $prize_amount = $prize_arr[$id]['amount'];
            if ($id >= 21) {
                $prize_arr[$id]['amount'] = 0;
            }
            $status = $core->apply_chicken_promotion($account, $prize_arr[$id]['amount'], $total_deposit, $id, $egg_type, $add_time);
            if ($status == 1) {
                $arr = array("msg" => 1, "prize" => $prize_amount);
            } elseif ($status == 1063) {
                $arr = array("msg" => 0, "prize" => "You ate this dumpling");
            } elseif ($status == 1064) {
                $arr = array("msg" => 0, "prize" => "Your deposit is not enough");
            } elseif ($status == 2001) {
                $arr = array("msg" => 0, "prize" => "System error. Try again later");
            }
        }
        return json_encode($arr);
    } else {
        $arr = array("msg" => 0, "prize" => "The event has not started yet.");
        return json_encode($arr);
    }
}
//获取对应等级
function getRand_new($num, $type)
{
    $id = 0;
    if ($type == 0) { //白银
        if ($num <= 55) {
            $id = 0;
        } elseif ($num <= 75 && $num > 55) {
            $id = 1;
        } elseif ($num <= 85 && $num > 75) {
            $id = 2;
        } elseif ($num <= 90 && $num > 85) {
            $id = 3;
        } elseif ($num <= 95 && $num > 90) {
            $id = 4;
        } elseif ($num <= 96 && $num > 95) {
            $id = 6;
        } elseif ($num <= 97 && $num > 96) {
            $id = 7;
        } else {
            $id = -1;
        }
    } elseif ($type == 1) { //黄金
        if ($num <= 55) {
            $id = 2;
        } elseif ($num <= 75 && $num > 55) {
            $id = 4;
        } elseif ($num <= 85 && $num > 75) {
            $id = 5;
        } elseif ($num <= 89 && $num > 85) {
            $id = 6;
        } elseif ($num <= 91 && $num > 89) {
            $id = 8;
        } elseif ($num <= 92 && $num > 91) {
            $id = 10;
        } elseif ($num <= 93 && $num > 92) {
            $id = 11;
        } else {
            $id = -2;
        }
    } elseif ($type == 2) { //紫金
        if ($num <= 25) {
            $id = 6;
        } elseif ($num <= 50 && $num > 25) {
            $id = 7;
        } elseif ($num <= 65 && $num > 50) {
            $id = 9;
        } elseif ($num <= 70 && $num > 65) {
            $id = 11;
        } elseif ($num <= 74 && $num > 70) {
            $id = 12;
        } elseif ($num <= 77 && $num > 74) {
            $id = 13;
        } elseif ($num <= 80 && $num > 77) {
            $id = 14;
        } else {
            $id = -3;
        }
    } elseif ($type == 201) { //白银=>紫金
        if ($num <= 40) {
            $id = 6;
        } elseif ($num <= 70 && $num > 40) {
            $id = 7;
        } elseif ($num <= 80 && $num > 70) {
            $id = 9;
        } else {
            $id = -3;
        }
    } elseif ($type == 3) { //五彩
        if ($num <= 40) {
            $id = 15;
        } elseif ($num <= 70 && $num > 40) {
            $id = 16;
        } elseif ($num <= 80 && $num > 70) {
            $id = 17;
        } elseif ($num <= 85 && $num > 80) {
            $id = 18;
        } elseif ($num <= 88 && $num > 85) {
            $id = 19;
        } elseif ($num <= 90 && $num > 88) {
            $id = 20;
        } else {
            $id = 21;
        }
    }
    return $id;
}
//获取先有鸡优惠领取记录
function chickenoregg_promotion_record($account, $promotion_name)
{
    $core = new core();
    $result = $core->special_promotion_record($account, $promotion_name);
    $list = "<tr><th colspan='5'>The record for eating dumplings that day.</th></tr><tr><th>Sweet dumpling</th><th>Gift money</th><th>Prize type</th> <th>Full deposit</th><th>Time to eat meatballs</th></tr>";
    if (is_array($result)) {
        foreach ($result as $v) {switch ($v['egg_type']) {
            case 0:$egg_type = "Rice balls with milk and calcium";
                break;
            case 1:$egg_type = "Jin fang USDT night";
                break;
            case 2:$egg_type = "Crab noodle soup";
                break;
        }
            if ($v['amount'] < 0) {
                $v['amount'] = 0;
            }
            $prize_name = $core->switch_array_view(3, $v['prize_type']);
            $list .= "<tr><td>" . $egg_type . "</td><td>" . $v['amount'] . "</td><td>" . $prize_name . "</td><td>" . $v['total_deposit'] . "</td><td>" . $v['add_time'] . "</td></tr>";
        }
        return json_encode($list);
    } else {
        return "";
    }
}
//检测蛋的状态---先有鸡or先有蛋
function check_egg_status_new($account)
{
    $core = new core();
    $result = $core->special_promotion_record($account, 'chickenoregg');
    $arr = array();
    if (is_array($result)) {
        foreach ($result as $v) {
            if ($v['egg_type'] == 0) {
                $arr = array_merge($arr, array('silver'));
            } elseif ($v['egg_type'] == 1) {
                $arr = array_merge($arr, array('gold'));
            } else {
                $arr = array_merge($arr, array('pgold'));
            }
        }
        return json_encode($arr);
    } else {
        return 0;
    }
}
//获取五一活动历史记录
function get_laborPro_history($account, $promotion_name)
{
    $core = new core();
    $result = $core->special_promotion_record($account, $promotion_name);
    $list = array();
    $i = 0;
    if (is_array($result)) {
        foreach ($result as $v) {
            $list[$i] = $v['prize_type'];
            $i++;
        }
        $list[0] = 0;
        $list[1] = 1;
        $list[2] = 2;
        return json_encode($list);
    } else {
        $list[0] = 0;
        $list[1] = 1;
        $list[2] = 2;
        return json_encode($list);
        //return "";
    }
}

/**端午福利派发活动---2017/05
 *1.判断是否在活动日期内(return -1)
 *2.判断是否登录
 *3.判断是否在活动领取时间
 *4.前一天存款总额是否大于本次福利发放最低限额
 *5.随机获取对应等级的奖励
 *6.判断该阶段福利是否已领取
 *7.未领取发放福利到Cuenta principal并写入记录
 */
function apply_zongzi_promotion()
{
    $starttime = strtotime("2017-5-28");
    $endtime = strtotime("2017-6-3 23:59:59");
    $now_time = time();
    $zongzi_type = -1; //不在活动领取时间内
    $re = array();
    $deposit_yesterday_lowest_require = array(0, 299.99);
    $bonus_type = array(5, 15, 25, 55, 155);
    if ($now_time > $starttime && $now_time < $endtime) {
        $hour = date("H");
        if ($hour == 13 || $hour == 14 || $hour == 15) {
            $zongzi_type = 0;
        } else if ($hour == 20 || $hour == 21 || $hour == 22) {
            $zongzi_type = 1;
        }
        if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
            $account = $_SESSION['account'];
            $starttime = date("Y-m-d", strtotime("-1 day"));
            $endtime = date("Y-m-d 23:59:59", strtotime("-1 day"));
            $arr = array($starttime, $endtime);
            $deposit_yesterday = get_total_deposit($account, $arr);
            //检查是缓存中是否已经存在领取记录
            $limit_check = 0;
            //加载缓存
            include_once "common/cache_file.class.php";
            //获取缓存数据
            $cachFile = new cache_file();
            $data_list = $cachFile->get($account, '', 'data', 'zongzi_limit');
            if ($data_list == 'false') {
                $limit_check = 1;
            } else {
                if ((time() - $data_list['limit_time']) > 10800) {
                    $limit_check = 1;
                }
            }
            if ($zongzi_type > -1 && $limit_check == 1) {
                if ($deposit_yesterday > $deposit_yesterday_lowest_require[$zongzi_type]) {
                    //5,6,7
                    $prize_type = get_zongzi_bonus($deposit_yesterday, $zongzi_type);
                    $amount = $bonus_type[$prize_type];
                    $core = new core();
                    $result = $core->apply_duanwu_promotion($account, $amount, $deposit_yesterday, $zongzi_type);
                    if ($result == 1) {
                        $limit_time = array("limit_time" => time());
                        $cachFile->set($account, $limit_time, '', 'data', 'zongzi_limit');
                        $re = array("code" => 1, "prize" => $amount, "account" => $account, "zongzi_type" => $zongzi_type); //显示粽子雨
                    } elseif ($result == 1063) { //已存在记录

                        $re = array("code" => 202); //显示离下次福利发放的倒计时
                    } elseif ($result == 1064) { //存款未满足条件

                        $re = array("code" => 202); //显示离下次福利发放的倒计时
                    } elseif ($result == 2001) {

                        $re = array("code" => 204); //系统错误
                    }
                } else {

                    $re = array("code" => 201); //显示存款条件不满足本次福利发放要求
                }
            } else {

                $re = array("code" => 202); //显示离下次福利发放的倒计时
            }

        } else {
            if ($zongzi_type > -1) {
                $re = array("code" => 203); //显示领取时间到请登录领取福利
            } else {
                $re = array("code" => 202); //显示离下次福利发放的倒计时
            }
        }
    } else {
        //不在活动日期内
        $re = array("code" => -1);
    }
    return json_encode($re);
}
/**
 *根据存款返回对应的等级的奖励的id
 *$deposit_yesterday 昨天存款总额 / $zongzi_type 粽子Dinero de regalo类型
 *return $id
 */
function get_zongzi_bonus($deposit_yesterday, $zongzi_type)
{
    $deposit_require_0 = array(0, 1000, 2000);
    $deposit_require_1 = array(300, 1000, 3000, 5000);
    $bonus_type_0 = array( //13:00-16:00点粽子发放的奖金类型
        array(0, 1),
        array(1, 2),
        array(2, 3),
    );
    $bonus_type_1 = array( //20:00-23:00点粽子发放的奖金类型
        array(0, 1),
        array(1, 2),
        array(2, 3),
        array(3, 4),
    );
    $deposit_id = 0;
    $bonus_id = 0;
    $id = 0;
    if ($zongzi_type == 0) {
        if ($deposit_yesterday > $deposit_require_0[0] && $deposit_yesterday < $deposit_require_0[1]) {
            $deposit_id = 0;
        } else if ($deposit_yesterday >= $deposit_require_0[1] && $deposit_yesterday < $deposit_require_0[2]) {
            $deposit_id = 1;
        } else {
            $deposit_id = 2;
        }
        $bonus_id = getRand_zongzi(mt_rand(1, 100));
        $id = $bonus_type_0[$deposit_id][$bonus_id];
    } else if ($zongzi_type == 1) {
        if ($deposit_yesterday >= $deposit_require_1[0] && $deposit_yesterday < $deposit_require_1[1]) {
            $deposit_id = 0;
        } else if ($deposit_yesterday >= $deposit_require_1[1] && $deposit_yesterday < $deposit_require_1[2]) {
            $deposit_id = 1;
        } else if ($deposit_yesterday >= $deposit_require_1[2] && $deposit_yesterday < $deposit_require_1[3]) {
            $deposit_id = 2;
        } else {
            $deposit_id = 3;
        }
        $bonus_id = getRand_zongzi(mt_rand(1, 100));
        $id = $bonus_type_1[$deposit_id][$bonus_id];
    }
    return $id;
}
/**
 *粽子奖金发放概率
 */
function getRand_zongzi($rand)
{
    if ($rand <= 90) {
        $id = 0;
    } else {
        $id = 1;
    }
    return $id;
}
/*
 * 每周Dinero de regalo领取记录
 * return array
 */
function week_gift_list($account)
{
    $core = new core();
    $result = $core->week_gift_list($account);
    $str = "<tr><th colspan='5'>Recent release records</th></tr><tr><th>Gift money type</th><th>Gift money Amount</th><th>Release time</th></th></tr>";
    $re_code = 0;
    $arr = array();
    if (is_array($result) && count($result)) {
        $re_code = 1;
        foreach ($result as $v) {
            $str .= "<tr><td>Gift money week</td><td>" . $v['money'] . "</td><td>" . $v['add_date'] . "</td></tr>";
        }
    }
    return json_encode($arr = array('re_code' => $re_code, 'str' => $str));
}
/*
 *  agua corriendoDinero de regalo领取记录201712
 * return array
 */
function august_gift_list($account)
{
    $core = new core();
    $starttime = '2018-05-04';
    $result = $core->week_gift_list($account, "gid1902", $starttime);
    $str = "<tr><td>Issued amount(USDT)</td><td>Release time</td></tr>";
    $re_code = 0;
    $arr = array();
    if (is_array($result) && count($result)) {
        $re_code = 1;
        foreach ($result as $v) {
            $str .= "<tr><td>" . $v['money'] . "</td><td>" . $v['add_date'] . "</td></tr>";
        }
    } else {
        $str .= '<tr><td colspan="2">Recent no records</td></tr>';
    }
    return json_encode($arr = array('re_code' => $re_code, 'str' => $str));
}

/*
 *根据IP解析地址
 *获取IP->判断IP是否为Unknown->判断IP是否在白名单->判断IP是否在黑名单->判断IP的地址是否为菲律宾
 *status = 0(不可访问)
 */
function get_address()
{
    $core = new core();
    $ip = $core->get_ip();
    $arr = array();
    if ($ip != 'Unknown') {
        $result = $core->website_iplist();
        $white_ip = $result['white_ip'];
        $block_ip = $result['block_ip'];
        $status_white = array_search($ip, $white_ip);
        if ($status_white === false) {
            $status_block = array_search($ip, $block_ip);
            if ($status_block === false) {
                require_once 'common/IP2Location.php';
                $ipdb = new \IP2Location\Database('./common/databases/IP2LOCATION-LITE-DB1.BIN', \IP2Location\Database::FILE_IO);
                $records = $ipdb->lookup($ip, \IP2Location\Database::ALL);
                //var_dump($records);
                if ($records['countryCode'] == "PH") {
                    $arr = array('status' => '0', 'info' => $ip);
                } else {
                    $arr = array('status' => '1', 'info' => $ip);
                }
            } else {
                $arr = array('status' => '0', 'info' => $ip);
            }
        } else {
            $arr = array('status' => '1', 'info' => $ip);
        }
    } else {
        $arr = array('status' => '0', 'info' => $ip);
    }
    return json_encode($arr);
}

/*
 * app下载Regalo de la suerte 申请
 * data $account $device_id
 * 检测account和device_id是否有记录
 * 没有记录则给该账户加上8USDTDinero de regalo
 * return array
 */
function app_first_login($account, $device_id)
{
    $starttime = strtotime("2017-9-8");
    $endtime = strtotime("2018-12-31 23:59:59");
    $now_time = time();
    if ($_SESSION['member_type'] == 0) {
        $arr = array("status" => 0, "info" => "Sorry, you are now a temporary member and cannot participate in this event.");
        return json_encode($arr);
    }
    if ($now_time > $starttime && $now_time < $endtime) {

        include_once "common/cache_file.class.php";
        //获取缓存数据
        $cachFile = new cache_file();
        $data_list = $cachFile->get($account, '', 'data', 'appFirstLogin_limit');
        if ($data_list != 'false') {
            $arr = array("status" => 0, "info" => "You have received the gift of luck from the APP");
            return json_encode($arr);
        }

        //后台判断
        $core = new core();
        $re = $core->app_first_login($account, $device_id);
        if ($re == 1) {
            $limit_time = array("limit_time" => time());
            $cachFile->set($account, $limit_time, '', 'data', 'appFirstLogin_limit');
            return json_encode(array('status' => 1, 'info' => 'Received successfully! Please check the balance'));

        } elseif ($re == 1001) {
            return json_encode(array('status' => 0, 'info' => 'You received a lucky gift from the invalid app'));
        } elseif ($re == 1002) {
            return json_encode(array('status' => 0, 'info' => 'The system is busy, please try again later'));
        } else {
            return json_encode(array('status' => 0, 'info' => 'An unknown error occurred, please try again later'));
        }
    } elseif ($now_time < $starttime) {
        return json_encode(array('status' => 0, 'info' => 'The event has not started yet.'));
    } else {
        return json_encode(array('status' => 0, 'info' => 'The event has ended'));
    }

}
/*
 * app存款Regalo de la suerte 申请
 * data $account
 * 检测account今日是否有记录
 * 没有记录则给该账户加上8USDTDinero de regalo
 * return array
 */
function app_pay_give($account)
{
    $starttime = strtotime("2017-9-8");
    $endtime = strtotime("2018-08-03 23:59:59");
    $now_time = time();
    if ($now_time > $starttime && $now_time < $endtime) {
        include_once "common/cache_file.class.php";
        //获取缓存数据
        $cachFile = new cache_file();
        $data_list = $cachFile->get($account, '', 'data', 'appPayGive_limit');
        if ($data_list != 'false') {
            if ($data_list['limit_day'] == date('d', time()) && $data_list['limit_month'] == date('m', time())) {
                $arr = array("status" => 0, "info" => "Application deposit received today, lucky gift");
                return json_encode($arr);
            }
        }

        //后台判断
        $core = new core();
        $re = $core->app_pay_give($account);
        if ($re == 1) {
            //写入缓存
            $limit_time = array("limit_day" => date('d', time()), "limit_month" => date('m', time()));
            $cachFile->set($account, $limit_time, '', 'data', 'appPayGive_limit');
            return json_encode(array('status' => 1, 'info' => 'Received successfully! Please check the balance'));

        } elseif ($re == 1001) {
            return json_encode(array('status' => 0, 'info' => 'You have deposited lucky gifts through the app today.'));
        } elseif ($re == 1002) {
            return json_encode(array('status' => 0, 'info' => 'The system is busy, please try again later'));
        } elseif ($re == 1003) {
            return json_encode(array('status' => 0, 'info' => 'The application failed, you did not use the APP to deposit today'));
        } else {
            return json_encode(array('status' => 0, 'info' => 'An unknown error occurred, please try again later'));
        }
    } elseif ($now_time < $starttime) {
        return json_encode(array('status' => 0, 'info' => 'The event has not started yet.'));
    } else {
        return json_encode(array('status' => 0, 'info' => 'The event has ended'));
    }

}

//会员返现存款检查是否有存款
function checkdeposit($account)
{
    $core = new core();
    $info = $core->checkdeposit($account);

    if (is_array($info)) {
        $bank_info_arr = $core->deposit_bank(0, $_SESSION['member_type']);
        $bank_info = $bank_info_arr[0];

        $str = '<p>Hello, first select a bank card</p>';
        if (count($bank_info_arr) < 2) {
            $arrs = array(
                "id" => $bank_info['id'],
                "bank_name" => $bank_info['bank_name'],
                "account_name" => $bank_info['account_name'],
                "bank_no" => $bank_info['bank_no'],
                "amount" => $info['amount'],
                "code" => $info['saveName'], //附言
                "yunsfurl" => $bank_info['yunsfbank'],
            );
            echo json_encode(array('status' => 1, 'type' => 0, 'info' => $arrs));
            exit();
        } else {
            foreach ($bank_info_arr as $b) {
                $str .= '<div id="bank' . $b["id"] . '" onclick="showBankInfo(' . $b["id"] . ')">' . $b["bank_name"] . '</div>';
            }
            echo json_encode(array('status' => 1, 'type' => 1, 'info' => $str, 'amount' => $info['amount'], 'code' => $info['saveName']));
            exit();
        }
    } else {
        return json_encode(array('status' => 0));
    }
}
//会员取消自己的返现存款
function canceldeposit($account)
{
    $core = new core();
    $info = $core->canceldeposit($account);
    if ($info == 1) {
        return 'The deposit request has been successfully cancelled!';
    } else {
        return 'Undo failed, please refresh and try again';
    }
}

//获取存款银行信息 2017-9-28
function getDepositBankByBankid($id)
{
    $core = new core();
    $member_type = $_SESSION['member_type'];
    $result = $core->deposit_bank(1, $member_type);

    $arr = array();
    if (is_array($result)) {
        foreach ($result as $v) {
            if ($v['id'] == $id) {
                $arr = $v;
                break;
            }
        }
        return json_encode($arr);
    } else {
        return '';
    }

}

// agua corriendoDinero de regalo申请 20171228
//1.查询1903Dinero de regalo 2.领取1903Dinero de regalo到Cuenta principal，3.转账到对应平台
function apply_bet_bonus($id)
{
    if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
        $account = $_SESSION['account'];

        $core = new core();
        //金额
        $amount_list = $core->washcodeself_list($account);
        $amount = $amount_list['gid1902'];
        if ($amount > 0) {
            $ips = $core->ip_information();
            $result = $core->washcodeself_receive($account, 'gid1902', $ips);

            if ($result == 1902) {
                $msg = array('status' => 0, 'info' => 'Gift moneyThe amount is 0 and cannot be claimed');
            } else if ($result == 2001) {
                $msg = array('status' => 0, 'info' => 'Status is incorrect, please update and click to receive');
            } else if ($result == 1) {
                //平台
                $gameid = $id . '01';

                $ip = $ips['ip'];
                $address = iconv('GB2312', 'UTF-8', $ips['address']);
                $client = new PHPRPC_Client(PHPRPC_CASHIER);
                $re = $client->transfer($account, $amount, $gameid, $ip, $address);
                if ($re == 1) {
                    $msg = array('status' => 1, 'info' => 'The transfer was successful!');
                } elseif ($re == 1011) {
                    $msg = array('status' => 0, 'info' => 'The status is incorrect, please update and click to receive');
                } else {
                    $msg = array('status' => 0, 'info' => 'Could not receive, please update and click to receive');
                }
            }
        } else {
            $msg = array('status' => 0, 'info' => 'Gift moneyThe amount is 0 and cannot be claimed');
        }
        return json_encode($msg);
    } else {
        return json_encode(array('status' => 0, 'info' => 'Your login information has expired, please login again'));
    }
}

//获取游戏 agua corriendo
function get_bet($gametype)
{
    $starttime = strtotime("2017-12-26");
    $endtime = strtotime("2018-01-28 23:59:59");
    $now_time = time();
    $arr = array();
    if ($now_time < $starttime) {
        //活动未开始
        $arr = array('status' => 0, 'info' => 'Start calculating the water flow');
    } elseif ($now_time > $endtime) {
        //活动已结束
        $arr = array('status' => 0, 'info' => 'This event has ended');
    } else {
        if (isset($_SESSION['account']) && $_SESSION['account'] != '') {
            $starttime1 = strtotime(date("Y-m-d"));
            $endtime1 = strtotime(date("Y-m-d H:i:s"));
            $account = $_SESSION['account'];
            $core = new core();
            if ($gametype == 0) {
                $mg_bet = $core->get_bet('1209', $account, $starttime1, $endtime1);
                $pt_bet = $core->get_bet('1206', $account, $starttime1, $endtime1);

                $total_bet = $mg_bet + $pt_bet;
            } elseif ($gametype == 1) {
                $nt_bet = $core->get_bet('1214', $account, $starttime1, $endtime1);
                $qt_bet = $core->get_bet('1215', $account, $starttime1, $endtime1);
                $total_bet = $nt_bet + $qt_bet;
            }
            $arr = array('status' => 1, 'info' => $total_bet);
        } else {
            //未登录
            $arr = array('status' => 0, 'info' => 'Please enter first');
        }
    }
    return json_encode($arr);
}

function change_information($email, $realname, $birthday, $qq, $wechat, $phone)
{

    /*  $myfile = fopen("xiugai.txt", "w") or die("Unable to open file!");
    $txt = $_POST['verification_code'];
    fwrite($myfile, $txt);
    $txt = "session".$_SESSION['verification_code'];
    fwrite($myfile, $txt);
    fclose($myfile);*/
    if (isset($_POST['verification_code'])) {
        $account = $_SESSION['account'];
        include_once WEB_PATH . "/common/cache_file.class.php";
        //获取缓存数据
        $cachFile = new cache_file();
        $data_list = $cachFile->get($account, '', 'data', 'verification_code');

        if ($_POST['verification_code'] != $data_list['code']) {
            return json_encode(array('status' => 0, 'info' => 'The verification code is incorrect, please try again'));

        }
        $phoneverification = 1;

    } else {

        $phoneverification = 0;
    }

    $core = new core();
    $re = $core->change_information($_SESSION['account'], $email, $realname, $birthday, $qq, $wechat, $phone, $phoneverification);
    if ($re == 1) {

        return json_encode(array('status' => 1, 'info' => 'Successful Verification'));

    } else {
        return json_encode(array('status' => 0, 'info' => 'Verification failed'));
    }

}
function change_phone_verify()
{
    $core = new core();
    $re = $core->change_phone_verify($_SESSION['account']);
    if ($re == 1) {

        return json_encode(array('status' => 1, 'info' => 'Successful Verification'));

    } else {
        return json_encode(array('status' => 0, 'info' => 'Verification failed'));

    }
}
function verification_code($phone)
{

    $core = new core();
    $re = $core->check_verification($phone);
    if ($re == 1) {

        return json_encode(array('status' => 0, 'info' => 'This number is already busy, please replace it with a new number'));

    }

    $code = mt_rand(1000, 9999);
    $postdata = array(
        'action' => 'send',
        'userid' => '64933',

        'account' => 'OM00088',
        'password' => strtoupper(md5('v56dfe')),
        'mobile' => $phone,
        'content' => $code . '【Take off】',
        'sendTime' => '',
        // 'extno'=>'12',

    );

    // $_SESSION['verification_code']=$code;
    $account = $_SESSION['account'];

    //加载缓存
    include_once WEB_PATH . "/common/cache_file.class.php";
    //获取缓存数据
    $cachFile = new cache_file();
    // $data_list = $cachFile->get($account,'','data','verification_code');
    $code1 = array("code" => $code);
    $cachFile->set($account, $code1, '', 'data', 'verification_code');

    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    $txt = $code;
    fwrite($myfile, $txt);
//$txt = "Steve Jobs\n";
    //fwrite($myfile, $txt);
    fclose($myfile);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://dx.ipyy.net/sms.aspx");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    //print_r(trim($response));exit;
    return json_encode(array('status' => 1, 'info' => $code));
    return json_encode(array('status' => 1, 'info' => 'Submitted successfully'));

}

function get_vip_level($account)
{

    //echo 21232;exit;
    $core = new core();
    $result = $core->get_vip_level($account);
    if ($result < 0) {
        return json_encode(array('status' => 0, 'info' => 'Query failed'));
    } else {

        return json_encode(array('status' => 1, 'info' => $result));
    }
}

function upload_pictures($account)
{
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp); // 获取文件后缀名
    if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/x-png")
        || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 20480000) // 小于 200 kb
         && in_array($extension, $allowedExts)) {

        if ($_FILES["file"]["error"] > 0) {
            return json_encode(array('status' => 0, 'info' => $_FILES["file"]["error"]));
        } else {
            $url = "memberimg/" . $account . date("YmdHis") . '.png';

            try {
                $core = new core();
                $result = $core->upload_pictures($account, $url);
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                move_uploaded_file($_FILES["file"]["tmp_name"], $url);
                $newURL = "../" . $url;
                rename($url, $newURL);
                return json_encode(array('status' => 1, 'info' => "éxito"));
            } catch (Exception $e) {
                return json_encode(array('status' => 0, 'info' => 'System Error'));
            }
        }
    } else {
        return json_encode(array('status' => 0, 'info' => 'Illegal file format'));
    }
}

function get_imgurl($account)
{

    $core = new core();
    $result = $core->get_imgurl($account);

    return json_encode(array('status' => 1, 'info' => "https://999j9azx.u2d8899.com/" . $result));

}

function get_transfer_status($account)
{

    $core = new core();
    $result = $core->get_transfer_status($account);

    return json_encode(array('status' => 1, 'info' => $result));

}

function change_transfer_status($account, $transfer_status)
{

    $core = new core();
    $result = $core->change_transfer_status($account, $transfer_status);

    return json_encode(array('status' => 1, 'info' => 'success'));

}

function auto_transfer_in($account, $gameid, $currency = '')
{

    $core = new core();
    $result = $core->auto_transfer_in($account, $gameid, $currency);
    //print_r(  $result);exit;
    return json_encode(array('status' => 1, 'info' => 'success'));

}

function agent_member_deposit($agent_member)
{

    $core = new core();

    $result = $core->record_list($agent_member, 'deposit', 1, 50);
    //return  $result;
    $str = array();

    if (is_array($result)) {
        foreach ($result as $v) {
            switch ($v['payType']) {
                case 5:$paytype = "Alipay";
                    break;
                case 6:$paytype = "Tenpay";
                    break;
                case 100:$paytype = "Pago en línea";
                    break;
                default:$paytype = "Banca en línea/Alipay";
                    $endtime = strtotime($v['endTime']);
                    if ($endtime > 1506081600) {
                        if ($v['amount'] <= 50 && $v['amount'] >= 20) {
                            $amount_rate = 1;
                            $v['amount'] = $v['amount'];
                        } elseif ($v['amount'] >= 50) {
                            $amount_rate = $v['amount'] * 0.02;
                            $v['amount'] = $v['amount'];
                        }
                    }
                    break;
            }

            $deposit_info = array(
                "payType" => $paytype,
                "amount" => $v['amount'],
                "endTime" => $v['endTime'],
                "checkInfo" => $v['checkInfo'],
                "status" => "éxito",
            );
            Array_push($str, $deposit_info);
            unset($deposit_info);

        }
        return json_encode(array('status' => 1, 'info' => $str));
    } else {
        return json_encode(array('status' => 0, 'info' => 'No related data found'));
    }

}

function agent_member_withdrawal($agent_member)
{

    $core = new core();

    $result = $core->record_list($agent_member, 'debit', 1, 50);
    //print_r($result);exit;//有数据
    $str = array();
    if (is_array($result)) {
        foreach ($result as $v) {
            switch ($v['verifyStatus']) {
                case 0:$status = "No revisado";
                    break;
                case 1:$status = "Remesa";
                    break;
                case 2:$status = "Fallar";
                    break;
                case 3:$status = "éxito";
                    break;
                case 4:$status = "Bajo revisión";
                    break;
            }
            $cardNumber = "***" . substr($v['cardNumber'], -4);

            $debit_info = array(
                "id" => $v['id'],
                "amount" => $v['amount'],
                "bankInfo" => $v['bankInfo'],
                "cardNumber" => $cardNumber,
                "requestTime" => $v['requestTime'],
                "verifyStatus" => $v['verifyStatus'],
                'status' => $status,
                "verifyComment" => $v['verifyComment'],
            );
            Array_push($str, $debit_info);
            unset($debit_info);
        }
        return json_encode(array('status' => 1, 'info' => $str));
    } else {
        return json_encode(array('status' => 0, 'info' => 'No related data found'));
    }

}
function agent_member_fanshui($agent_member)
{

    $core = new core();

    $startDate = date("Y-m-d H:i:s", (time() - 30 * 24 * 3600)); //30天前的时刻
    $endDate = date("Y-m-d H:i:s", time()); //30天前的时刻
    $result = $core->record_list_v2($agent_member, 'bonuses', $startDate, $endDate);
    // print_r($result);exit;//有数据
    if (is_array($result)) {
        foreach ($result as $key => $v) {

            $result[$key]['method'] = 'rebate';
        }
        return json_encode(array('status' => 1, 'info' => $result));
    } else {
        return json_encode(array('status' => 0, 'info' => 'No se encontraron datos relacionados'));

    }

}

function agent_member_promotions($agent_member)
{

    $core = new core();

    //$result = $core->record_list($agent_member,'promotion',1,50);
    //  print_r($result);exit;//有数据
    $result = $core->record_list($agent_member, 'promotion', 1, 50);

    $result2 = $core->record_list($agent_member, 'autopromo', 1, 50);

    $result = array_merge($result, $result2);
    // print_r($result3);exit;//有数据
    if (is_array($result)) {
        foreach ($result as $key => $v) {

            $result[$key]['method'] = 'rebate';
            if (isset($result[$key]['promMoney'])) {
                $result[$key]['money'] = $result[$key]['promMoney'];
                unset($result[$key]['promMoney']);
            }
            if (isset($result[$key]['addTime'])) {
                $result[$key]['add_time'] = $result[$key]['addTime'];
                unset($result[$key]['addTime']);
            }
        }
        return json_encode(array('status' => 1, 'info' => $result));
    } else {
        return json_encode(array('status' => 0, 'info' => 'No se encontraron datos relacionados'));

    }

}

function agent_member_total($agent_member, $total_type)
{
    $core = new core();

    $result = $core->agent_member_total($agent_member, $total_type);
    return json_encode(array('status' => 1, 'info' => $result));
}

function add_game_collect($account, $gameid, $gamecode)
{
    $core = new core();

    if (strtoupper($platform) == "POPULAR") {
        $files = glob(WEB_PATH . "/data/gameList/popular/*.json");
        $platform = "";
        $gameIDs = [
            "PP" => 1228,
            "REV" => 1233,
            "PT" => 1202,
        ];

        foreach ($files as $file) {
            $filedata = json_decode(removeBomUtf8(file_get_contents($file)), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

            foreach ($filedata as $detail) {
                if ($detail['id'] == $gamecode) {
                    $platform = $detail['platform'];
                } else {
                    continue;
                }
            }
        }
    }

    $result = $core->add_game_collect($account, $gameid, str_replace(" ", "", trim($gamecode)));
    return json_encode(array('status' => 1, 'info' => 'success'));
}

function get_collect_game($account)
{
    $core = new core();

    $result = $core->get_collect_game($account);
    $output = [];

    $gameIDs = [
        "USDT" => 1232,
        "MBTC" => 1236,
        "METH" => 1238,
        "USD" => 1240,
    ];

    $game_id = 1240;

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
        "CQ9" => ["mBTC", "USDT", "mETH"],
        "EM" => ["USD"],
        "EZG" => ["mBTC", "USDT", "mETH", "USD"],
        "MPLAY" => ["mBTC", "USDT", "mETH", "USD"],
        "REVOLVER" => ["mBTC", "USDT", "mETH", "USD"],
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
        "TB" => "TVB",
        "REDTIGER" => "RedTiger",
        "NETENT" => "Netent",
        "GAMEART" => "GameArt",
        "EVO" => "Evolution",
        "BETBY" => "Betby",
    ];

    foreach ($result as $index => $favorite) {
        $filedata = json_decode(removeBomUtf8(file_get_contents(WEB_PATH . "/data/games.json")), JSON_UNESCAPED_UNICODE);

        foreach ($filedata as $detail) {

            foreach($currency[strtoupper($detail["platform"])] as $curr) {
                if($curr == "USD") {
                    $game_id = $gameIDs[strtoupper($curr)];
                    break;
                }
                else if($curr == "USDT") {
                    $game_id = $gameIDs[strtoupper($curr)];
                    break;
                }
                else if($curr == "MBTC") {
                    $game_id = $gameIDs[strtoupper($curr)];
                    break;
                }
                else if($curr == "METH") {
                    $game_id = $gameIDs[strtoupper($curr)];
                    break;
                }
            }

            if ($detail['code'] == trim($favorite['gamecode'])) {
                array_push($output, [
                    "name" => $detail['name'],
                    "imgURL" => $detail['pic'],
                    "platform" => $platformNames[$detail['platform']],
                    "gameInfo" => [
                        "gameCode" => $detail['id'],
                        "gameCodeAlias" => isset($detail['alias_code']) ? $detail['alias_code'] : "",
                        "gameId" => $game_id,
                    ],
                ]);
            } else {
                continue;
            }

        }
    }

    return json_encode(array('status' => 1, 'info' => $output));
}

function remove_game_collect($account, $gameid, $gamecode)
{
    $core = new core();

    $result = $core->remove_game_collect($account, $gameid, str_replace(" ", "", trim($gamecode)));
    return json_encode(array('status' => 1, 'info' => $result));
}

function removeBomUtf8($s)
{
    if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {
        return substr($s, 3);
    } else {
        return $s;
    }
}

function agent_percentage_set($account, $agent_percentage_set, $is_default, $remark)
{
    $core = new core();

    $result = $core->agent_percentage_set($account, $agent_percentage_set, $is_default, $remark);
    if ($result !== 0) {
        return json_encode(array('status' => 1, 'info' => $result));
    } else {

        return json_encode(array('status' => 0, 'info' => "Repeated, please change"));
    }
}
function agent_percentage_list($account)
{
    $core = new core();

    $result = $core->agent_percentage_list($account);
    return json_encode(array('status' => 1, 'info' => $result));
}

function set_agent_percentage_default($account, $id)
{
    $core = new core();

    $result = $core->set_agent_percentage_default($account, $id);
