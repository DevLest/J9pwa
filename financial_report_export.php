<?php

ini_set('memory_limit', '-1');

ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

include_once("client/phprpc_client.php");

define("WEB_PATH", __DIR__);

// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
// header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Expose-Headers: Content-Length");
// header('Content-Type: application/json');
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");

// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(200);
//     die();
// }

// // dt_server_side_processing
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $d = $_GET;
//     if(isset($_GET['dt_server_side_processing']) && $_GET['dt_server_side_processing'] == 1 ) {
//         // header('Content-Type: text/html; charset=UTF-8');
//         dt_server_side_processing($d);
//     }
//     exit();
// }


// $api_key = 'fghrtrvdfger';
// // $time = substr(time(), 0, -3);
// // $auth_check = md5($time . $api_key);
// $checksum = "";
// $checksum .= 'financial_reports';
// $checksum .= $api_key;
// $checksum_new = hash('sha256', $checksum);
// $auth_check = $checksum_new;

// $d = (object) json_decode(file_get_contents('php://input'), true);

// $auth = $d->auth;


// if ($auth_check != $auth) {
//     echo json_encode(array('status' => 0, 'info' => "Verification failed"));
//     exit();
// }


// if (isset($d->type) && $d->type == "export_financial") {
//     export_financial($d);
// }

export_financial(new stdClass);

function export_financial($d)
{
    date_default_timezone_set('Asia/Manila');

    $date1 = date('Y-m-d H:i:s');
    // $date1 = "2022-11-30 17:33:07";
    // $date1 = $_GET['date'];
    $d->date = (new DateTime($date1))->format("Ymd");

    $client = new PHPRPC_Client("http://j9adminaxy235.32sun.com/phprpc/cashierforfinancial_report.php");
    $response = $client->export_financial($d);

    // var_dump($response);

    // echo json_encode($response);
    // die();


    // Excel file name for download 
    $fileName = "export-financial-data_" . date('Y-m-d') . ".xls";

    // Column names 
    // game name $v['eName']
    // game code  $v['id']
    // enable status ($v['state'] == 0) ? 'Disabled' : 'Enabled'
    $fields = array(
        "MEXCICO DATE",
        "beginning balance",
        "deposit",
        "withdraw",
        "bonus",
        "rebate",
        "free spin",
        "operator ajustment(+)",
        "operator ajustment(-)",
        "competition win (cash)",
        "competition win (prize)",
        "slot win",
        "live win",
        "sports win",
        "others win",
        "jackpot win",
        "sports bet",
        "competition bet",
        "slot bets",
        "live bets",
        "jackpot bet",
        "others bets",
        "balance"
    );

    // Display column names as first row 
    $excelData = implode("\t", array_values($fields)) . "\n";

    // Fetch records from database 
    // $query = $db->query("SELECT * FROM members ORDER BY id ASC"); 
    // if($query->num_rows > 0){ 
    // Output each row of the data 
    // while($row = $query->fetch_assoc()){ 

    // $filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.u2d8899.com/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);
    // $filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.u2d8899.com/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);
    // foreach ($filedata as $row) {

    //     $status = ($row['state'] == 1) ? 'Enabled' : 'Disabled';
    //     $lineData = array(
    //         $row['eName'],
    //         $row['id'],
    //         // $platformNames[$row['platform']], 
    //         $row['platform'],
    //         $status
    //     );
    //     array_walk($lineData, 'filterData');
    //     $excelData .= implode("\t", array_values($lineData)) . "\n";
    // }
    $excelData .= implode("\t", array_values($response)) . "\n";
    // } 
    // }else{ 
    //     $excelData .= 'No records found...'. "\n"; 
    // } 

    // Headers for download 
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    // Render excel data 
    echo $excelData;

    exit;
}
