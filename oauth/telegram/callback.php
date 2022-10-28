<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

    include_once ("../../core.class.php");
	require_once __DIR__ . '/vendor/autoload.php';

    if(!isset($_SESSION))
    {
        session_start();
    }

    if (isset($_GET['auth_id'])) {
        include_once (WEB_PATH."../../common/cache_file.class.php");
        $cachFile = new cache_file();
        $data_list = $cachFile->get($_GET['auth_id'],'','data','telegram_oauth');

        if (!$data_list){
            echo json_encode(['status'=>0,'info'=> "Not authorize. Please try again"]);
        } else echo $data_list;

        exit();
    }

	$api_key='fghrtrvdfger';
	$core = new core();
    
    $account = $_GET['id']."@gmail.com";
    $password = substr($_GET['id'],0,-5). "telegram";
    
    $re = $core->member_login($account,$password);
    
    if(is_array($re))
    {
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

        cacheData(json_encode([
            'status'=>1,
            'info'=> [
                'username' => $re['account'],
                'balance' => $re['balance'],
                'realName' => $re['realName'],
                'password' => $password,
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
                'nickName' => $re['nickName'],
                'agent_percentage' => (isset($re['agent_percentage']) && $re['agent_percentage'] != "") ? $re['agent_percentage'] * 100 : null,
            ]
        ]));

    } else {
        $time = substr(time(),0,-3);
        $auth = md5($time.$api_key);

        $postData = [
            "submit_type" => "regist",
            "auth" => $auth,
            "username_email" => $account,
            "password" => $password,
            "first_name" => $_GET['first_name'], 
            "last_name" => $_GET['last_name']
        ];

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://999j9azx.u2d8899.com/j9pwa/center.php',
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
        cacheData($response);
    }

    function cacheData($data){
	    include_once (WEB_PATH."../../common/cache_file.class.php");
        $cachFile = new cache_file();
        $core = new core();
        $ip = $core->ip_information();
        $time = substr(time(),0,-3);
        $hashID = hash('ripemd160',$time.$ip['ip']);
        $cachFile->set($hashID,$data,'','data','fb_oauth');
        echo "<script>window.close();</script>";
    }