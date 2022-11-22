<?php

ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

$platformNames = [

	"PGSOFT" => "PGSoft",

	"SPB" => "Spribe",

	"SW" => "Skywind",

	"PT" => "PlayTech",

	"BG" => "BetGames",

	"BNG" => "Booongo",

	"CALETA" => "Caleta",

	"CQ9" => "CQGames",

	"EM" => "EveryMatrix",

	"EZG" => "Ezugi",

	"HAB" => "Habanero",

	"MPLAY" => "MPlay",

	"PNG" => "Play'N'GO",

	"PS" => "Playson",

	"PP" => "PragmaticPlay",

	"REVOLVER" => "Revolver",

	"RB" => "RubyPlay",

	"TB" => "TVB",

	"SS" => "SuperSpade",

];


// $data = array( 
//     array("NAME" => "John Doe", "EMAIL" => "john.doe@gmail.com", "GENDER" => "Male", "COUNTRY" => "United States"), 
//     array("NAME" => "Gary Riley", "EMAIL" => "gary@hotmail.com", "GENDER" => "Male", "COUNTRY" => "United Kingdom"), 
//     array("NAME" => "Edward Siu", "EMAIL" => "siu.edward@gmail.com", "GENDER" => "Male", "COUNTRY" => "Switzerland"), 
//     array("NAME" => "Betty Simons", "EMAIL" => "simons@example.com", "GENDER" => "Female", "COUNTRY" => "Australia"), 
//     array("NAME" => "Frances Lieberman", "EMAIL" => "lieberman@gmail.com", "GENDER" => "Female", "COUNTRY" => "United Kingdom") 
// );
// echo "<pre>";
// print_r($data);
// echo "</pre>";

// $filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.999game.online/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);$filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.999game.online/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);

// echo "<pre>";
// print_r($filedata);
// echo "</pre>";


// die();

/**
 * EXPORT
 */
// if(isset($_POST['export']) && $_POST['export'] == "go") {
	
//     $filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.999game.online/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);$filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.999game.online/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);

// 	// Excel file name for download 
// 	$fileName = "codexworld_export_data-" . date('Ymd') . ".xlsx"; 
	
// 	// Headers for download 
// 	header("Content-Disposition: attachment; filename=\"$fileName\""); 
// 	header("Content-Type: application/vnd.ms-excel"); 
	
// 	$flag = false; 
// 	// foreach($data as $row) { 
// 	foreach($filedata as $row) { 
// 		if(!$flag) { 
// 			// display column names as first row 
// 			echo implode("\t", array_keys($row)) . "\n"; 
// 			$flag = true; 
// 		} 
// 		// filter data 
// 		array_walk($row, 'filterData'); 
// 		echo implode("\t", array_values($row)) . "\n"; 
// 	} 
	
// 	exit;
// // }

// // function filterData(&$str){ 
// //     $str = preg_replace("/\t/", "\\t", $str); 
// //     $str = preg_replace("/\r?\n/", "\\n", $str); 
// //     if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
// // }

// if (is_array($filedata)) {
//     // game name $v['eName']
//     // game code  $v['id']
//     // enable status ($v['state'] == 0) ? 'Disabled' : 'Enabled'

// 	$i = 1;
// 	foreach ($filedata as $k => $v) {

// 		$i++;
// 	}
// }



function removeBomUtf8($s)
{

	if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {

		return substr($s, 3);
	} else {

		return $s;
	}
}



// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
 
// Excel file name for download 
$fileName = "games-data_" . date('Y-m-d') . ".xls"; 
 
// Column names 
// game name $v['eName']
// game code  $v['id']
// enable status ($v['state'] == 0) ? 'Disabled' : 'Enabled'
$fields = array('GAME NAME', 'GAME CODE', 'PROVIDER','STATUS'); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
// Fetch records from database 
// $query = $db->query("SELECT * FROM members ORDER BY id ASC"); 
// if($query->num_rows > 0){ 
    // Output each row of the data 
    // while($row = $query->fetch_assoc()){ 

$filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.999game.online/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);$filedata = json_decode(removeBomUtf8(file_get_contents("https://999j9azx.999game.online/j9pwa/data/games.json")), JSON_UNESCAPED_UNICODE);
foreach($filedata as $row) { 

    $status = ($row['state'] == 1)?'Enabled':'Disabled'; 
    $lineData = array(
        $row['eName'], 
        $row['id'], 
        // $platformNames[$row['platform']], 
        $row['platform'], 
        $status
    ); 
    array_walk($lineData, 'filterData'); 
    $excelData .= implode("\t", array_values($lineData)) . "\n";    
} 

        
        


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