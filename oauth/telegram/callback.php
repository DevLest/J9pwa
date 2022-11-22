<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

    include_once ("../../core.class.php");

    if(!isset($_SESSION))
    {
        session_start();
    }

    if (isset($_GET['auth_id'])) {
        include_once ("../../common/cache_file.class.php");
        $cachFile = new cache_file();
        $data_list = $cachFile->get($_GET['auth_id'],'','data','telegram_oauth');

        if (!$data_list){
            echo json_encode(['status'=>0,'info'=> "Not authorize. Please try again"]);
        } else echo $data_list;

        exit();
    }
    
    if(isset($_GET['tg_data'])) {

        $api_key='fghrtrvdfger';
        $core = new core();

        $tg_data = json_decode($_GET['tg_data'], true);

        $account = $tg_data['id']."@gmail.com";
        $password = substr($tg_data['id'],0,-5). "telegram";
        
        $re = json_decode(loginMember($account,$password));
        
        if(!$re->status) {
            $time = substr(time(), 0, -3);
            $auth = md5($time . $api_key);

            $postData = [
                "type" => "oauth_register",
                "auth" => $auth,
                "username_email" => $account,
                "password" => $password,
                "first_name" => isset($tg_data['first_name']) ? $tg_data['first_name'] : "", 
                "last_name" => isset($tg_data['last_name']) ? $tg_data['last_name'] : "",
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://999j9azx.999game.online/j9pwa/request.php',
                CURLOPT_FOLLOWLOCATION => 0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_POST => 1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_TIMEOUT => 100,
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: multipart/form-data; ',
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            // $checkData = json_decode($response);
            // if (!$checkData->status){
            //     $response = loginMember($account,$password);
            // }
            cacheData($response);
        }
        else
        {
            $re = json_encode($re);
            cacheData($re);
        }
    }

    function loginMember($account,$password){
        
        $core = new core();
        $re = $core->member_login($account,$password);

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
                    'userID' => $re['uid'],
                    'agent_percentage' => (isset($re['agent_percentage']) && $re['agent_percentage'] != "") ? $re['agent_percentage'] * 100 : null,
                ],
            ]);
        }
        return json_encode(['status' => 0, "info" => "account not found"]);
    }

    function cacheData($data){
        include_once ("../../common/cache_file.class.php");
        $cachFile = new cache_file();
        $core = new core();
        $ip = $core->ip_information();
        $time = substr(time(),0,-3);
        $hashID = hash('ripemd160',$time.$ip['ip']);
        $result = $cachFile->set($hashID,$data,'','data','telegram_oauth');
        echo $data;
        echo "<script>window.close();</script>";
    }