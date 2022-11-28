<?php

header("Content-type: text/html; charset=utf-8");

include_once "client/phprpc_client.php";

include_once "des3.php";

define('FOREGROUND_URL', 'http://j9adminaxy235.32sun.com/phprpc/foreground.php');

define("PHPRPC_CASHIER", "http://j9adminaxy235.32sun.com/phprpc/cashier.php");

define("PHPRPC_CASHIERFORMYSQLI", "http://j9adminaxy235.32sun.com/phprpc/cashierformysqli.php");

define('SERVER_URL', 'http://j9adminaxy235.32sun.com/phprpc/server.php');

define('ACTIVITY_URL', 'http://j9adminaxy235.32sun.com/phprpc/activity.php');
define('OWNGAME_URL', 'http://j9adminaxy235.32sun.com/phprpc/owngame.php');

define("FLOW", "http://ybrecord.system.jr71.com/phprpc/flow.php");

define('READ_FOREGROUND_URL', 'http://j9adminaxy235.32sun.com/phprpc/foreground.php');

define("READ_PHPRPC_CASHIER", "http://j9adminaxy235.32sun.com/phprpc/cashier.php");

define("READ_PHPRPC_CASHIERFORMYSQLI", "http://j9adminaxy235.32sun.com/phprpc/cashierformysqli.php");

define('READ_SERVER_URL', 'http://j9adminaxy235.32sun.com/phprpc/server.php');

define('READ_ACTIVITY_URL', 'http://j9adminaxy235.32sun.com/phprpc/activity.php');
define('READ_OWNGAME_URL', 'http://j9adminaxy235.32sun.com/phprpc/owngame.php');

/*define('READ_FOREGROUND_URL','http://j9adminaxy235.32sun.com/phprpc/foreground.php');

define("READ_PHPRPC_CASHIER","http://j9adminaxy235.32sun.com/phprpc/cashier.php");

define("READ_PHPRPC_CASHIERFORMYSQLI","http://j9adminaxy235.32sun.com/phprpc/cashierformysqli.php");

define('READ_SERVER_URL','http://j9adminaxy235.32sun.com/phprpc/server.php');

define('READ_ACTIVITY_URL','http://j9adminaxy235.32sun.com/phprpc/activity.php');
define('READ_OWNGAME_URL','http://j9adminaxy235.32sun.com/phprpc/owngame.php');*/

if (!defined("WEB_PATH")) {

    define("WEB_PATH", __DIR__);

}

if (!isset($_SESSION)) {

    session_start();

}

class core
{

    public function __construct()
    {

    }

    /**

     * 会员注册帐号

     * @param $account string 游戏帐号

     * @param $password string 密码

     * @param $data array 相关信息 姓名、手机、邮箱等

     * @return 返回的是 会员信息或者error code

     */

    public function member_regist($account, $password, $data)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $ip_info = $this->ip_information();

        $data['ip'] = $ip_info['ip'];

        $data['macaddress'] = $ip_info['address'];

        $info = $client->member_regist($account, $password, $data);

        return $info;

    }

    /**

     *会员登录  帐号，密码。

     *返回帐号的相关信息

     */

    public function member_login($account, $password)
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->member_login($account, $password);

        return $info;

    }

    /**

     *查询余额  帐号，游戏ID。

     *主账户ID为0

     *返回帐号或游戏的余额

     */

    public function get_balance($account, $gameid)
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->get_balance($account, $gameid);

        return $info;

    }

    /**

     *修改密码  帐号，原密码，新密码。

     *返回status

     */

    public function change_password($account, $oldpwd, $newpwd)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->change_password($account, $oldpwd, $newpwd);

        return $status;

    }

    public function game_mobile_login($account, $gameid, $data = "")
    {

        $client = new PHPRPC_Client(SERVER_URL);

        $status = $client->mobilelogin($account, $gameid, $data);

        return $status;

    }

    public function get_app_popup()

	{

	    $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

	    $result = $client->get_app_popup();

	    return $result;

	}
    public function create_one_round()
    {
//return 1;
        $client = new PHPRPC_Client(OWNGAME_URL);

        $status = $client->create_one_round();

        return $status;

    }
    
        public function submit_autopromotion($username_email,$promotion_id)
    {
//return 1;
        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->submit_autopromotion($username_email,$promotion_id);

        return $status;

    }
    /**

     *用户支付宝存款  帐号，金额，存款方式，数据信息。

     *返回status

     */

    public function zfbdeposit($account, $amount, $zfb_billno, $autopromo, $bankid)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        if ($autopromo == 2) {

            $result = $client->check_autopromotion($account, date("Y-m-d"));

            if (is_array($result)) {

                return -1;

            }

        }

        $deposit_type = "zhifubao";

        $data = array(

            "zfb_billno" => $zfb_billno,

            "autopromo" => $autopromo,

        );

        $status = $client->deposit($account, $amount, $deposit_type, $bankid, $data);

        return $status;

    }

    /**

     *用户财付通存款  帐号，金额，存款方式，数据信息。

     *返回status

     */

    public function cftdeposit($account, $amount, $cft_billno, $autopromo, $bankid)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        if ($autopromo == 2) {

            $result = $client->check_autopromotion($account, date("Y-m-d"));

            if (is_array($result)) {

                return -1;

            }

        }

        $deposit_type = "caifutong";

        $data = array(

            "cft_billno" => $cft_billno,

            "autopromo" => $autopromo,

        );

        $status = $client->deposit($account, $amount, $deposit_type, $bankid, $data);

        return $status;

    }

    /**

     *用户网银存款  帐号，金额，存款方式，数据信息。

     *返回status

     */

    public function deposit($account, $amount, $bankid, $member_name, $deposit_addr, $autopromo)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        if ($autopromo == 2) {

            $result = $client->check_autopromotion($account, date("Y-m-d"));

            if (is_array($result)) {

                return -1;

            }

        }

        $deposit_type = "wangyin";

        if ($_SESSION['deposit_type'] == 'm') {

            $billno = "n" . date("YmdHis", time()) . mt_rand(1000, 9999);

        } else {

            $billno = "p" . date("YmdHis", time()) . mt_rand(1000, 9999);

        }

        $data = array(

            "billno" => $billno,

            "member_name" => $member_name,

            "deposit_addr" => $deposit_addr,

            "autopromo" => $autopromo,

        );

        $status = $client->deposit($account, $amount, $deposit_type, $bankid, $data);

        return $status;

    }

    /**

     *用户在线存款  帐号，金额，存款方式，数据信息。

     *返回 status

     */

    public function onlinepay($account, $amount, $billno, $platname, $autopromo)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $bankid = "";

        /*if($autopromo == 2)

        {

        $result = $client->check_autopromotion($account,date("Y-m-d"));

        if(is_array($result))

        {

        return -1;

        }

        }

        if($autopromo == 3)

        {

        $result = $client->check_autopromotion2($account,date("Y-m-d"),$autopromo);

        if(is_array($result))

        {

        return -1;

        }

        }*/

        $deposit_type = "onlinepay";

        $data = array(

            "billno" => $billno,

            "platname" => $platname,

            "autopromo" => $autopromo,

        );

        $status = $client->deposit($account, $amount, $deposit_type, $bankid, $data);

        return $status;

    }

    /**

     *用户取款  帐号，金额，取款银行id。

     *返回status

     */

    public function debit($account, $amount, $card_number, $bank_type, $net_work)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->debit($account, $amount, $card_number, $bank_type, $net_work);

        return $status;

    }

    /**

     *用户自助取消取款  帐号，取款id。

     *返回status

     */

    public function cancel_debit($account, $id)
    {

        if ($id == "") {

            return -2;

        }

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->cancel_debit($account, $id);

        return $status;

    }

    /**

     *用户取款  帐号，金额，游戏平台id+转账标识。

     *返回status

     */

    public function transfer($account, $amount, $gameid)
    {

        if ($amount < 1 || $gameid == '') {

            return -2;

        }

        $ipinfo = $this->ip_information();

        $ip = $ipinfo['ip'];

        //$address = $ipinfo['address'];

        $address = iconv('GB2312', 'UTF-8', $ipinfo['address']);

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->transfer($account, $amount, $gameid, $ip, $address);

        return $status;

    }

    public function alltransferout($account)
    {

        $ipinfo = $this->ip_information();

        $ip = $ipinfo['ip'];

        //$address = $ipinfo['address'];

        $address = iconv('GB2312', 'UTF-8', $ipinfo['address']);

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->all_transfer_out($account, $ip, $address);

        return $status;

    }

    /**

     *获取用户信息  帐号。

     *返回 info

     */

    public function get_memberinfo($account)
    {

        if ($account == '') {

            return '';

        }

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->member_info($account);

        return $info;

    }

    public function get_memberinfoByEmail($account)
    {
        if ($account == '') {
            return '';
        }

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->member_info_by_email($account);

        return $info;
    }

    public function set_memberEmailVerified($account)
    {
        if ($account == '') {
            return '';
        }

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->member_email_verified($account);

        return $info;
    }

    /**

     *获取游戏转账列表。

     *返回 info

     */

    public function get_transferlist()
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->get_transferlist();

        return $info;

    }

    /**

     *获取游戏转账列表。 v1新版

     *返回 info

     */

    public function get_transferlist_v1()
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->get_transferlist_v1();

        return $info;

    }

    public function check_moneypwd($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->check_moneypwd($account);

        return $info;

    }

    public function set_moneypwd($account, $moneypwd)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->set_moneypwd($account, $moneypwd);

        return $info;

    }

    public function change_moneypwd($account, $moneypwd, $moneynewpwd)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->change_moneypwd($account, $moneypwd, $moneynewpwd);

        return $info;

    }

    /**

     *获取会员银行帐号信息。

     *返回 info

     */

    public function bank_list($account)
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->bank_list($account);

        return $info;

    }

    /**

     *绑定会员银行信息

     *返回 status

     */

    public function bind_bank($account, $bank_type, $realname, $bank_no, $bank_addr, $bank_province, $bank_city)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->bind_bank($account, $bank_type, $realname, $bank_no, $bank_addr, $bank_province, $bank_city);

        return $status;

    }

    /**

     *解绑会员银行信息

     *返回 status

     */

    public function bank_unbind($id)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->bank_unbind($id);

        return $status;

    }

    /**

     *获取存款银行信息

     *返回 status

     */

    public function deposit_bank($type, $member_type)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->deposit_bank($type, $member_type, 1);

        return $info;

    }

    /**

     *获取记录的总个数

     *返回 int

     */

    public function count_record($account, $record_type)
    {

        //$client = new PHPRPC_Client(PHPRPC_CASHIER);

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIERFORMYSQLI);

        $result = $client->count_record($account, $record_type);

        return $result;

    }

    /**

     *获取记录的数据信息

     *返回 array info

     */

    public function record_list($account, $record_type, $page = 1, $pages = 10)
    {

        //$client = new PHPRPC_Client(PHPRPC_CASHIER);

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIERFORMYSQLI);

        $info = $client->record_list($account, $record_type, $page, $pages);

        //error_log(date('m-d H:i:s')."#".serialize($info)."\r\n", 3,'error_log.php');

        return $info;

    }

    public function record_list_v2($account, $record_type, $startDate, $endDate, $status = 1, $page = 1, $pages = 10)
    {

        //$client = new PHPRPC_Client(PHPRPC_CASHIER);

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIERFORMYSQLI);

        $info = $client->record_list_v2($account, $record_type, $startDate, $endDate, $status, $page, $pages);

        //error_log(date('m-d H:i:s')."#".serialize($info)."\r\n", 3,'error_log.php');

        return $info;

    }

    public function record_list_summary($account, $record_type, $startDate, $endDate, $page = 1, $pages = 10)
    {
        $client = new PHPRPC_Client(READ_PHPRPC_CASHIERFORMYSQLI);

        $info = $client->record_list_summary($account, $record_type, $startDate, $endDate, $page, $pages);

        return $info;
    }

    /**

     *用户积分兑换申请

     *返回 status

     */

    public function apply_point($account, $point)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->apply_point($account, $point);

        return $status;

    }

    /**

     *用户老虎机救援申请

     *返回 status

     */

    public function apply_rescue($account, $rescue_type)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->apply_rescue($account, $rescue_type);

        return $status;

    }

    /**

     *通过ID获取站内信详细内容

     *返回 info

     */

    public function get_msgcontent($account, $id)
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->get_msgcontent($account, $id);

        return $info;

    }

    /**

     *获取站内信未读信息的数量

     *返回 info 个数

     */

    public function count_noread_message($account)
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->count_noread_message($account);

        return $info;

    }

    /**

     *获取站内信未读信息的数量

     *返回 info 个数

     */

    public function get_notice($num)
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->get_notice($num);

        return $info;

    }

    /**

     *传递ID序列，对数据进行批量删除

     *返回 状态

     */

    public function delete_data($ids, $table)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->delete_data($ids, $table);

        return $status;

    }

    /**

     *获取多线路在线支付的列表状态

     *返回 info 线路的type和status

     */

    public function monlinepay_list()
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->monlinepay_list();

        return $info;

    }

    /**

     *获取 多线路在线支付的 详细内容

     *返回 info 线路信息

     */

    public function monlinpay_detail($pay_id)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->monlinepay_detail($pay_id);

        return $info;

    }

    /**

     *在线支付信息确认

     *返回 status

     */

    public function onlinepay_sure($bill_no, $order_no, $amount)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->onlinepay_sure($bill_no, $order_no, $amount);

        return $status;

    }

    /**

     *同步游戏密码

     *返回 status

     */

    public function syn_password($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->syn_password($account);

        return $status;

    }

    /*

     *修改密码 账号 新密码

     */

    public function resetpwd($accont, $pwd)
    {

        $client = new PHPRPC_Client(FOREGROUND_URL);

        $info = $client->web_change_pwd($accont, $pwd);

        return $info;

    }

    /*

     *游戏解锁  账号 游戏ID

     *返回是否解锁成功

     */

    public function gameapi_unlock($account, $gameid)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->gameapi_unlock($account, $gameid);

        return $info;

    }

    /*

     *游戏激活  账号 游戏ID

     *返回是否激活成功

     */

    public function gameapi_active($account, $gameid)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->gameapi_active($account, $gameid);

        return $info;

    }

    /*

     *游戏同步密码  账号 游戏ID

     *返回是否同步成功

     */

    public function gameapi_password($account, $gameid)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->gameapi_password($account, $gameid);

        return $info;

    }

    /*

     *游戏强制离线  账号 游戏ID

     *返回是否离线成功

     */

    public function gameapi_logout($account, $gameid)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->gameapi_logout($account, $gameid);

        return $info;

    }

    /**

     *查询当日返水记录  自助返水列表

     *返回空或者记录列表

     */

    public function washcodeself_list($account, $arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $result = $client->washcodeself_list($account);

        return $result;

    }

    /**

     *领取自助返水金额

     *$arr 需要传 ip 和 address

     *返回 1 或 错误代码

     */

    public function washcodeself_receive($account, $gid, $arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $result = $client->washcodeself_receive($account, $gid, $arr);

        return $result;

    }

    /**

     *新的获取支付线路列表 代替 monlinepay_list

     *$arr array 可传的参数为 common_id 0,1,2,3 网银，微信，支付宝，财付通

     *返回 array id,pay_type,line_type,pay_status,show_name,common_id

     */

    public function onlinepay_list($arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->onlinepay_list($arr);

        return $info;

    }

    /**

     *统计会员的总存款

     *返回帐号的总存款或者NULL

     */

    public function get_total_deposit($account, $starttime, $endtime, $arr = "")
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIERFORMYSQLI);

        $result = $client->get_total_deposit($account, $starttime, $endtime);

        return $result;

    }

    /**

     *申请NT新纪元优惠活动

     *返回 1或者1051,1052,2001 成功、规则不匹配、活动未开始

     */

    public function apply_nt_promotion($account, $ip, $address)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $result = $client->apply_nt_promotion($account, $ip, $address);

        return $result;

    }

    /**

     *查询特殊优惠的申请记录

     *返回空或者记录列表

     */

    public function promotion_history($account, $promotion_name, $starttime, $endtime, $arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $result = $client->promotion_history($account, $promotion_name, $starttime, $endtime);

        return $result;

    }

    /**

     *特殊活动，QT，获取当日总投注金额

     *$account

     *返回当日QT总投注额

     */

    public function get_qt_bet($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->get_qt_bet($account, $arr = "");

        return $info;

    }

    /**

     *特殊活动，QT，获取领取记录

     *$account $promotion_name

     *返回领取记录account,amount,total_bet,promotion_level,add_time

     */

    public function special_promotion_record($account, $promotion_name)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->special_promotion_record($account, $promotion_name, $arr = "");

        return $info;

    }

    /**

     *特殊活动，QT，申请活动

     *$account $promotion_level  $arr array(ip,address)

     *返回status 错误代码或提示

     */

    public function apply_qt_promotion($account, $promotion_level, $arr)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->apply_qt_promotion($account, $promotion_level, $arr);

        return $info;

    }

    /**

     *申请 2017彩蛋贺岁 的特殊优惠 1.查询是否已申请 2.查询总存款是否满足 3.写入记录和金额

     *@param $account string 游戏帐号

     *@param $amount 优惠金额

     *@param $total_deposit 总存款

     *@param $prize_type 优惠中奖类型

     *@param $add_time 时间

     *@param $arr array

     *@return status 错误代码或提示

     */

    public function apply_egg_promotion($account, $amount, $total_deposit, $prize_type, $add_time, $arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->apply_egg_promotion($account, $amount, $total_deposit, $prize_type, $add_time, $arr = "");

        return $info;

    }

    public function apply_chicken_promotion($account, $amount, $total_deposit, $prize_type, $egg_type, $add_time, $arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->apply_chicken_promotion($account, $amount, $total_deposit, $prize_type, $egg_type, $add_time, $arr = "");

        return $info;

    }

    /**

     *查询某些数据的状态信息

     *返回 int 数量

     */

    public function record_status($account, $type, $status, $arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->record_status($account, $type, $status, $arr = "");

        return $info;

    }

    /**

     *申请端午福利派发奖金

     *返回1或1063

     */

    public function apply_duanwu_promotion($account, $amount, $deposit, $prize_type)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->apply_duanwu_promotion($account, $amount, $deposit, $prize_type);

        return $info;

    }

    /**

     *周礼金领取记录

     */

    public function week_gift_list($account, $gid = "gid1901", $arr = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->week_gift_list($account, $gid, $arr);

        return $info;

    }

    /**

     * 产生随机字符串

     *

     * @param int $length

     *            输出长度

     * @param string $chars

     *            可选的 ，默认为 0123456789

     * @return string 字符串

     */

    public function random($length, $chars = '0123456789')
    {

        $hash = '';

        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {

            $hash .= $chars[mt_rand(0, $max)];

        }

        return $hash;

    }

    /**

     *申请欢乐五一活动奖金

     *返回1或2001

     */

    public function apply_unlimited_promotion($account, $amount, $deposit, $prize_type)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->apply_unlimited_promotion($account, $amount, $deposit, $prize_type);

        return $info;

    }

    /**

     *获取white_ip/block_ip

     */

    public function website_iplist()
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->website_iplist();

        return $info;

    }

    /**

     *申请 app下载幸运礼金

     */

    public function app_first_login($account, $device_id)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->app_first_login($account, $device_id);

        return $info;

    }

    /**

     *申请 app存款幸运礼金

     */

    public function app_pay_give($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);

        $info = $client->app_pay_give($account);

        return $info;

    }

    /**

     *转账存款返现(2017-9-21)

     *用户网银存款  帐号，金额，存款方式，数据信息。

     *返回arr

     */

    public function backdeposit($account, $amount, $bankid, $deposit_addr, $autopromo, $zf_type = 1)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        if ($autopromo == 2) {

            $result = $client->check_autopromotion($account, date("Y-m-d"));

            if (is_array($result)) {

                return -1;

            }

        }

        if ($autopromo == 3) {

            $result = $client->check_autopromotion2($account, date("Y-m-d"), $autopromo);

            if (is_array($result)) {

                return -1;

            }

        }

        $billno = "p" . date("YmdHis", time()) . mt_rand(1000, 9999);

        $deposit_type = "wangyin";

        $data = array(

            "billno" => $billno,

            "deposit_addr" => $deposit_addr,

            "autopromo" => $autopromo,

        );

        $status = $client->backdeposit($account, $amount, $deposit_type, $bankid, $data, $zf_type);

        return $status;

    }

    public function yunsfdeposit($account, $amount, $bankid, $deposit_addr, $autopromo)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        if ($autopromo == 2) {

            $result = $client->check_autopromotion($account, date("Y-m-d"));

            if (is_array($result)) {

                return -1;

            }

        }

        if ($autopromo == 3) {

            $result = $client->check_autopromotion2($account, date("Y-m-d"), $autopromo);

            if (is_array($result)) {

                return -1;

            }

        }

        $billno = "p" . date("YmdHis", time()) . mt_rand(1000, 9999);

        $deposit_type = "wangyin";

        $data = array(

            "billno" => $billno,

            "deposit_addr" => $deposit_addr,

            "autopromo" => $autopromo,

        );

        $status = $client->yunsfdeposit($account, $amount, $deposit_type, $bankid, $data);

        return $status;

    }

    /*

     *会员返现存款检查是否有存款(2017-9-21)

     *return arr或-1

     */

    public function checkdeposit($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->checkdeposit($account);

        return $info;

    }

    /*

     *会员返现存款检查是否有存款(2017-9-21)

     *return arr或-1

     */

    public function canceldeposit($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->canceldeposit($account);

        return $info;

    }

    /**

     *获取游戏投注

     */

    public function get_bet($gameid, $account, $starttime, $now_time)
    {

        $client = new PHPRPC_Client(FLOW);

        $info = $client->get_flow($gameid, $account, $starttime, $now_time);

        return $info;

    }

    /**

     *异步执行相关操作

     *返回 status

     */

    public function asyn_execute($name, $data)
    {

        $url = "http://" . $_SERVER["HTTP_HOST"];

        $url .= "/common/asyn_execute.php?name=" . $name;

        error_log("#" . $url, 3, "error_log.php");

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        curl_exec($ch);

        curl_close($ch);

        return 1;

    }

    /**

     *获取客户的IP和地址。

     *返回 info 数组

     */

    public function ip_information()
    {

        $ipinfo = array();

        if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) {

            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

        } else if (@$_SERVER["HTTP_CLIENT_IP"]) {

            $ip = $_SERVER["HTTP_CLIENT_IP"];

        } else if (@$_SERVER["REMOTE_ADDR"]) {

            $ip = $_SERVER["REMOTE_ADDR"];

        } else if (@getenv("HTTP_X_FORWARDED_FOR")) {

            $ip = getenv("HTTP_X_FORWARDED_FOR");

        } else if (@getenv("HTTP_CLIENT_IP")) {

            $ip = getenv("HTTP_CLIENT_IP");

        } else if (@getenv("REMOTE_ADDR")) {

            $ip = getenv("REMOTE_ADDR");

        } else {

            $ip = "Unknown";

        }

        $temp = explode(",", $ip);

        $ip = $temp[0];

        $ipinfo['ip'] = $ip;

        include "ip_area.class.php";

        $iparea = new ip_area();

        $ipinfo['address'] = $iparea->data_full($ip);

        return $ipinfo;

    }

    /**

     *对字符串信息进行中间屏蔽。

     *返回 string 字符串

     */

    public function str_mid_replace($string, $left = 4, $right = 4, $type = "*")
    {

        $length = strlen($string);

        $left_str = substr($string, 0, $left);

        $right_str = substr($string, -1 * ($right));

        $mid_str = "****";

        /*for($i=0;$i<($length-$left-$right);$i++)

        {

        $mid_str .= $type;

        }*/

        $str = $left_str . $mid_str . $right_str;

        return $str;

    }

    /**

     *获取可用的在线支付信息

     *返回 相关信息

     */

    public function onlinepay_info()
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->onlinepay_info();

        return $info;

    }

    /**

     *获取用户的积分信息

     *返回 相关信息

     */

    public function get_point($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->get_point($account);

        return $info;

    }

    /**

     *选择点卡扣点 智付的点卡

     *返回 扣除后的金额

     */

    public function switch_dianka($bank_code, $amount)
    {

        $money = 0;

        switch ($bank_code) {

            case "DXGK":$money = $amount * (0.95);
                break;

            case "GYYKT":$money = $amount * (0.83);
                break;

            case "JWYKT":$money = $amount * (0.84);
                break;

            case "JYYKT":$money = $amount * (0.8);
                break;

            case "LTYKT":$money = $amount * (0.95);
                break;

            case "QBCZK":$money = $amount * (0.86);
                break;

            case "SDYKT":$money = $amount * (0.87);
                break;

            case "SFYKT":$money = $amount * (0.86);
                break;

            case "SHYKT":$money = $amount * (0.84);
                break;

            case "THYKT":$money = $amount * (0.86);
                break;

            case "TXYKT":$money = $amount * (0.82);
                break;

            case "TXYKTZX":$money = $amount * (0.81);
                break;

            case "WMYKT":$money = $amount * (0.86);
                break;

            case "WYYKT":$money = $amount * (0.86);
                break;

            case "YDSZX":$money = $amount * (0.95);
                break;

            case "ZTYKT":$money = $amount * (0.87);
                break;

            case "ZYYKT":$money = $amount * (0.84);
                break;

            default:$money = $amount * (1);

        }

        $money = number_format($money, 2, '.', '');

        return $money;

    }

    /**

     *选择点卡扣点 MO宝的点卡

     *返回 扣除后的金额

     */

    public function switch_mbdianka($bank_code, $amount)
    {

        $money = 0;

        switch ($bank_code) {

            case "SZX":$money = $amount * (0.85);
                break;

            case "JW":$money = $amount * (0.85);
                break;

            case "TH":$money = $amount * (0.85);
                break;

            case "ZYYKT":$money = $amount * (0.85);
                break;

            case "LT":$money = $amount * (0.85);
                break;

            case "DX":$money = $amount * (0.85);
                break;

            case "JY":$money = $amount * (0.85);
                break;

            case "SD":$money = $amount * (0.85);
                break;

            case "SH":$money = $amount * (0.85);
                break;

            case "QQ":$money = $amount * (0.85);
                break;

            case "WM":$money = $amount * (0.85);
                break;

            case "WY":$money = $amount * (0.85);
                break;

            case "ZT":$money = $amount * (0.85);
                break;

            case "TX":$money = $amount * (0.85);
                break;

            default:$money = $amount * (0.85);

        }

        $money = number_format($money, 2, '.', '');

        return $money;

    }

    /**

     * 创建表单

     * @data        表单内容

     * @url 支付网关地址

     */

    public function build_form($data, $url, $method = "get", $script_on = 1)
    {

        $sHtml = "<form id='pay_form' name='pay_form' action='" . $url . "' method='$method' target='_self'>";

        foreach ($data as $key => $val) {

            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";

        }

        $sHtml .= "</form>";

        if ($script_on == 1) {
            $sHtml .= "<script>document.getElementById('pay_form').submit();</script>";
        }

        return $sHtml;

    }

    /**

     *查询当天流水情况  帐号，游戏ID。

     *返回帐号或游戏的流水量

     */

    public function get_number($account, $gameid)
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $info = $client->get_number($account, $gameid);

        return $info;

    }

    /**

     *查询当天该会员中奖情况  帐号，类型。

     *返回中奖记录或空

     */

    public function get_prize($account, $prize_type)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->get_prize($account, $prize_type);

        return $info;

    }

    /**

     *记录砸蛋中奖情况

     *返回1或2001

     */

    public function prize_record($account, $prize, $number, $prize_type, $add_time)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->prize_record($account, $prize, $number, $prize_type, $add_time);

        return $status;

    }

    /**

     *查询砸蛋的状态

     *返回 砸蛋信息

     */

    public function check_egg_status($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $result = $client->check_egg_status($account);

        return $result;

    }

    /**

     *查询上周总存款金额

     *返回 金额

     */

    public function get_lastweek_deposit($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $result = $client->get_lastweek_deposit($account);

        return $result;

    }

    /**

     *申请春季赞歌活动

     *返回 1或者1051,1052,2001 成功、规则不匹配、活动未开始

     */

    public function apply_special_spring($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $status = $client->apply_special_spring($account);

        return $status;

    }

    /**

     *会员登录betslot 手机网页版  帐号，密码，游戏ID。

     *返回token

     */

    public function game_login($account, $password, $gameid)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->game_login($account, $password, $gameid);

        return $info;

    }

    /**

     * 检测会员信息是否存在

     * @param string  $data 会员信息

     * @param string  $field 信息自字段

     * @return code or error_code 错误代码

     */

    public function check_member_info($data, $field)
    {

        if ($data == '' || $field == '') {

            return 1006;

        }

        try {

            $client = new PHPRPC_Client(PHPRPC_CASHIER);

            $code = $client->check_member_info($data, $field);

            if (strlen($code) > 5) {

                //如果返回的不是error_code

                throw new Exception('1006');

            }

            return $code;

        } catch (Exception $e) {

            return $e->getMessage();

        }

    }

    /**

     * 获取优惠信息

     * @return array  数据

     */

    public function get_promotion()
    {

        $client = new PHPRPC_Client(READ_PHPRPC_CASHIER);

        $result = $client->get_promotion();

        return $result;

    }

    /**

     *会员登录网页游戏   帐号，游戏ID。

     *返回token

     */

    public function gameapi_login($account, $gameid, $data = "")
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->gameapi_login($account, $gameid, $data);

        return $info;

    }

    /**

     * 替换file_get_contents

     */

    public function curl_get_contents($url, $timeout = 10)
    {

        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $url);

        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);

        $result = curl_exec($curlHandle);

        curl_close($curlHandle);

        return $result;

    }

    public function change_information($account, $email, $realname, $birthday, $qq, $wechat, $phone, $phoneverification)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->change_information($account, $email, $realname, $birthday, $qq, $wechat, $phone, $phoneverification);

        return $info;

    }

    public function change_informationV2($account, $data = [])
    {
        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->change_informationv2(array_filter($data), $account);

        return $info;
    }

    public function change_phone_verify($account)
    {

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->change_phone_verify($account);

        return $info;

    }

    public function get_vip_level($account)
    {

        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->vip_level($account);

        return $info;

    }

    public function upload_pictures($account, $url)
    {

        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->upload_pictures($account, $url);

        //return $info;

    }

    public function get_imgurl($account)
    {

        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->get_imgurl($account);

        return $info;

    }

    public function get_transfer_status($account)
    {

        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->get_transfer_status($account);

        return $info;

    }

    public function change_transfer_status($account, $transfer_status)
    {

        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->change_transfer_status($account, $transfer_status);

        return $info;

    }

    public function auto_transfer_in($account, $gameid, $currency = '')
    {

        $ipinfo = $this->ip_information();

        $ip = $ipinfo['ip'];

        //$address = $ipinfo['address'];

        $address = iconv('GB2312', 'UTF-8', $ipinfo['address']);

        $client = new PHPRPC_Client(PHPRPC_CASHIER);

        $info = $client->auto_transfer_in($account, $gameid, $ip, $address, $currency);

        return $info;

    }

    public function check_verification($phone)
    {

        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->check_verification($phone);

        return $info;

    }

    public function get_alipayid()
    {

        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->get_alipayid();

        return $info;

    }

    public function agent_member_total($agent_member, $total_type)
    {
        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->agent_member_total($agent_member, $total_type);

        return $info;

    }

    public function get_phone_account($phone)
    {

        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->get_phone_account($phone);

        return $info;

    }

    public function add_game_collect($account, $gameid, $gamecode)
    {

        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->add_game_collect($account, $gameid, $gamecode, $platform);

        return $info;

    }

    public function get_collect_game($account)
    {
        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->get_collect_game($account);
//return 566;
        return $info;

    }

    public function remove_game_collect($account, $gameid, $gamecode)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->remove_game_collect($account, $gameid, $gamecode);

        return $info;
    }
    /**

     *定义数组工厂

     */

    private function array_factory($array_type)
    {

        switch ($array_type) {

            case 1: //游戏ID game_id

                return array(

                    1201 => "IM体育",

                    1204 => "AG",

                    1203 => "MG平台",

                    1206 => "BTI",

                    1207 => "TTG平台",

                    1208 => "3D平台",

                    1209 => "极速MG平台",

                    1210 => "SG平台",

                    1211 => "PNG平台",

                    1212 => "GG平台",

                    1213 => "AG平台",

                    1214 => "NT平台",

                    1215 => "QT平台",

                    1218 => "开元棋牌",

                    1219 => "IM体育",

                    1220 => "TD平台",

                    1222 => "HC电竞",

                    1223 => "IM电竞",

                    1224 => "CQ平台",

                    1225 => "SW平台",

                    1226 => "申博真人",

                    1227 => "极速MG平台",

                    1229 => "欧博真人",

                    1230 => "GM棋牌",

                    1231 => "三国棋牌",

                    1901 => "周礼金",

                    1902 => "特殊礼金",

                    1903 => "元旦礼金",

                );

            case 2: //领取状态

                return array("未领取", "已领取");

            case 3: //先有鸡or先有蛋奖励类型

                return array(

                    0 => "幸运礼金8", //0

                    1 => "幸运礼金12", //1

                    2 => "幸运礼金18", //2

                    3 => "幸运礼金22", //3

                    4 => "幸运礼金28", //4

                    5 => "幸运礼金36", //5

                    6 => "幸运礼金58", //6

                    7 => "幸运礼金68", //7

                    8 => "幸运礼金72", //8

                    9 => "幸运礼金88", //9

                    10 => "幸运礼金108", //10

                    11 => "幸运礼金128", //11

                    12 => "幸运礼金158", //12

                    13 => "幸运礼金188", //13

                    14 => "幸运礼金228", //14

                    15 => "幸运礼金188", //15

                    16 => "幸运礼金288", //16

                    17 => "幸运礼金388", //17

                    18 => "幸运礼金588", //18

                    19 => "幸运礼金688", //19

                    20 => "幸运礼金888", //20

                    21 => "双倍返水卡", //21

                    22 => "双倍积分卡", //22

                    23 => "双倍转运卡", //23

                );

            default:return array("未定义");

        }

    }

    /**

     * 转换为数组中表示的值

     * @param $arrayType    数组类型

     * @param $type    需要处理的type

     * @return string

     */

    public function switch_array_view($array_type, $type)
    {

        $result = $this->array_factory($array_type);

        return $result[$type];

    }

    /**

     * 转换为数组中表示的值带字体颜色

     * @param $arrayType    数组类型

     * @param $type    需要处理的type

     * @return string

     */

    public function switch_color_view($array_type, $type)
    {

        $result = $this->array_factory($array_type);

        $color = "#000";

        if ($type == 0) {

            $color = "#00f";

        } elseif ($type == 1) {

            $color = "#0f0";

        } elseif ($type == 2) {

            $color = "#f00";

        }

        return "<span style='color:" . $color . ";'>" . $result[$type] . "</span>";

    }

    /**

     * 将定义的数组转化为下拉框

     * @param $arrayType    数组类型

     * @param $type        是否包已选择项

     * @param $isall        是否包默认项

     * @return string

     */

    public function bind_array_selected($array_type, $type = '', $isall = false)
    {

        $result = $this->array_factory($array_type);

        $returnstr = '';

        if ($isall) {

            $returnstr .= "<option value='-1'>请选择</option>";

        }

        foreach ($result as $k => $v) {

            if ($v != '') {

                if ($type != '' && $type == $k) {

                    $selected = "selected";

                }

                $returnstr .= "<option value='" . $k . "' " . $selected . ">" . $v . "</option>";

            }

            $selected = '';

        }

        return $returnstr;

    }

    public function uniterecord($account, $start_date, $end_date, $page = 1)
    {
        $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");

        $list = $client->usdt_uniterecord($account, $start_date, $end_date, $page);
        $memberlist = $list['a'];

        if (isset($memberlist)) {
            $info['list'] = $memberlist;
            $info['total'] = '';
            return $info;
        } else {
            return 0;
        }
    }

    public function uniterecord_sum($account, $start_date, $end_date, $page = 1)
    {
        $client = new PHPRPC_Client("http://testrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");

        $list = $client->usdt_uniterecord_sum($account, $start_date, $end_date, $page);
        return $list;
    }

    //获取用户IP

    public function get_ip()
    {

        $ip = getenv("HTTP_TRUE_CLIENT_IP");

        if (!isset($ip) || $ip == '') {

            if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (@$_SERVER["HTTP_CLIENT_IP"]) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } else if (@$_SERVER["REMOTE_ADDR"]) {
                $ip = $_SERVER["REMOTE_ADDR"];
            } else if (@getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (@getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            } else if (@getenv("REMOTE_ADDR")) {
                $ip = getenv("REMOTE_ADDR");
            } else {
                $ip = "Unknown";
            }

        }

        $temp = explode(",", $ip);

        $ip = $temp[0];

        $ipinfo['ip'] = $ip;

        return $ip;

    }

    /*根据会员等级显示会员等级名称20180102*/

    public function showMemberLevelName($id)
    {

        $arr = array(

            0 => '临时',

            1 => '正式',

            2 => '初级',

            3 => '中级',

            4 => '高级',

            5 => '终极',

            6 => '一级',

            7 => '黑名单',

            8 => '潜力',

            9 => '四级',

            10 => '五级',

            11 => '六级',

            12 => 'VIP',

        );

        return $arr[$id];

    }

    /*根据会员vip等级显示名称*/

    public function showViplevelName($id)
    {

        $arr = array(

            0 => '正式会员',

            1 => '正式会员',

            2 => '1星VIP',

            3 => '2星VIP',

            4 => '3星VIP',

            5 => '4星VIP',

            6 => '5星VIP',

        );

        return $arr[$id];

    }

    /*根据会员vip等级显示名称*/

    public function get_game_platform()
    {
        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->get_game_platform();

        return $info;
    }

    public function add_password_reset($account, $info)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->add_password_reset($account, $info);

        return $info;
    }

    public function get_password_reset($hash)
    {
        $add_time = date("Y-m-d H:i:s", (time() - 120));
        $data = json_decode(base64_decode($hash));

        $client = new PHPRPC_Client(ACTIVITY_URL);
        $info = $client->get_password_reset($hash, $add_time, $data->code);

        return $info;
    }

    public function check_password_reset($account, $code)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);
        $info = $client->check_password_reset($code, $account);

        return $info;
    }

    public function agent_info($account)
    {
        $client = new PHPRPC_Client(READ_FOREGROUND_URL);
        $info = $client->spagent_info($account);

        return unserialize($info);
    }

    public function agent_commission($account, $startDate, $endDate)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->agent_commission($account, $startDate, $endDate);
        return $info;
    }

    public function agent_percentage_set($account, $agent_percentage, $is_default, $remark)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->agent_percentage_set($account, $agent_percentage, $is_default, $remark);
        return $info;
    }

    public function agent_percentage_list($account)
    {
        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->agent_percentage_list($account);
        return $info;
    }
    public function set_agent_percentage_default($account, $id)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->set_agent_percentage_default($account, $id);
        return $info;
    }

    public function set_agent_remark($id, $remark)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->set_agent_remark($id, $remark);
        return $info;
    }
    public function check_agent_percentage($agent_code)
    {
        $client = new PHPRPC_Client(ACTIVITY_URL);

        $info = $client->check_agent_percentage($agent_code);
        return $info;
    }

    public function agent_friends_list($account)
    {
        $client = new PHPRPC_Client(READ_ACTIVITY_URL);

        $info = $client->agent_friends_list($account);

        return $info;
    }

    public function agent_rank_list($account)
    {
        $client = new PHPRPC_Client(READ_ACTIVITY_URL);
        $info = $client->agent_rank_list($account);

        return $info;
    }
}
