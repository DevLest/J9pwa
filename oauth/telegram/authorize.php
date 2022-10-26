<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials:true");

include_once "../../core.class.php";
// require 'google-api-client/vendor/autoload.php';

if (!isset($_SESSION)) {
    session_start();
}

// $client = new Google_Client();

// $client->setClientId("752252740023-ecbtv7ols8gjbsqn05mvc25khka9gosm.apps.googleusercontent.com");
// $client->setClientSecret("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");
// $client->setRedirectUri("http://oldfront.u2d8899.com/j9pwa/oauth/google/authorize.php");

// $client->addScope("email");
// $client->addScope("profile");

if (isset($_GET['code'])) {

    // $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    // $client->setAccessToken($token['access_token']);

    // $google_oauth = new Google_Service_Oauth2($client);
    // $google_account_info = $google_oauth->userinfo->get();

    // if (isset($google_account_info['id']) && $google_account_info['id'] != "") {
    //     $api_key = 'fghrtrvdfger';
    //     $core = new core();
    //     $account = $google_account_info['email'];
    //     $password = substr($google_account_info['id'], 0, -5) . $api_key;

    //     $re = $core->member_login($account, $password);

    //     if (is_array($re)) {
    //         $_SESSION['account'] = $re['account'];
    //         $_SESSION['balance'] = $re['balance'];
    //         $_SESSION['member_name'] = $re['realName'];
    //         $_SESSION['member_type'] = $re['memberType'];
    //         $_SESSION['password'] = $password;
    //         $_SESSION['levelID'] = $re['levelID'];
    //         $_SESSION['email'] = $re['email'];
    //         setcookie("account", $_SESSION['account'], time() + 86400);
    //         setcookie("member_name", urlencode($_SESSION['member_name']), time() + 86400);

    //         $imageResult = $core->get_imgurl($account);

    //         echo json_encode([
    //             'status' => 1,
    //             'info' => [
    //                 'username' => $re['account'],
    //                 'balance' => $re['balance'],
    //                 'realName' => $re['realName'],
    //                 'email' => $re['email'],
    //                 'email_verified' => $re['email_verified'],
    //                 'sex' => ($re['sex']) ? "F" : "M",
    //                 'birthday' => $re['birthday'],
    //                 'phone' => $re['telephone'],
    //                 'pic' => $imageResult,
    //                 'first_name' => $re['firstName'],
    //                 'middle_name' => $re['middleName'],
    //                 'last_name' => $re['lastName'],
    //                 'city' => $re['city'],
    //                 'state' => $re['state'],
    //                 'landline' => $re['landline'],
    //                 'postal' => $re['postal'],
    //                 'regTime' => $re['regTime'],
    //                 'is_agent' => $re['is_agent'],
    //                 'nickName' => $re['nickName'],
    //                 'agent_percentage' => (isset($re['agent_percentage']) && $re['agent_percentage'] != "") ? $re['agent_percentage'] * 100 : null,
    //             ],
    //         ]);

    //     } else {
    //         $time = substr(time(), 0, -3);
    //         $auth = md5($time . $api_key);
    //         $postData = [
    //             "submit_type" => "regist",
    //             "auth" => $auth,
    //             "username_email" => $account,
    //             "password" => $password,
    //         ];

    //         $curl = curl_init();

    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => 'http://oldfront.u2d8899.com/j9pwa/center.php',
    //             CURLOPT_FOLLOWLOCATION => 0,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_TIMEOUT => 3,
    //             CURLOPT_POST => 1,
    //             CURLOPT_CUSTOMREQUEST => "POST",
    //             CURLOPT_TIMEOUT => 100,
    //             CURLOPT_POSTFIELDS => $postData,
    //             CURLOPT_HTTPHEADER => array(
    //                 'Content-Type: multipart/form-data; ',
    //             ),
    //         ));

    //         $response = curl_exec($curl);

    //         curl_close($curl);

    //         echo $response;
    //     }
    // }
} else {
    // header('Location: ' . $client->createAuthUrl());
}
