<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    
    $json = file_get_contents(__DIR__.'/../data/games.json');
    $array = json_decode($json,true);
    $error_images = [];

    foreach ( $array as $data ) {
        $path_parts = pathinfo($data['pic']);

        $img = __DIR__.str_replace('https://999j9azx.999game.online/','../../',$data['pic']);
        // $img = $data['pic'];
        try {
            if (file_exists($img)) {
                if (filesize($img) >= 1) {
                    continue;
                }
                else {
                    array_push($error_images, [
                        "Name" => $data['eName'],
                        "GameCode" => $data['id'],
                        "Platform" => $data['platform'],
                        "Category" => implode(",",$data['tag']),
                        "ImageURL" => $data['pic'],
                        "SourceURL" => $data['sourceImge'],
                    ]);
                }
            }
            else {
                array_push($error_images, [
                    "Name" => $data['eName'],
                    "GameCode" => $data['id'],
                    "Platform" => $data['platform'],
                    "Category" => implode(",",$data['tag']),
                    "ImageURL" => $data['pic'],
                    "SourceURL" => $data['sourceImge'],
                ]);
            }
        }
        catch (Exception $e) {
            array_push($error_images, [
                "Name" => $data['eName'],
                "GameCode" => $data['id'],
                "Platform" => $data['platform'],
                "Category" => implode(",",$data['tag']),
                "ImageURL" => $data['pic'],
                "SourceURL" => $data['sourceImge'],
            ]);
        }
    }

    echo "<!DOCTYPE html>
    <html>
    <head>
    <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    
    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    
    tr:nth-child(even) {
      background-color: #dddddd;
    }
    </style>
    </head>
    <body>
    
    <h2>Error Images ".count($error_images)."</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Game Code</th>
            <th>Platform</th>
            <th>Category</th>
            <th>Image URL</th>
            <th>Source Image</th>
        </tr>";
    foreach ($error_images as $data){
        echo "
        <tr>
          <td>".$data['Name']."</td>
          <td>".$data['GameCode']."</td>
          <td>".$data['Platform']."</td>
          <td>".$data['Category']."</td>
          <td>".$data['ImageURL']."</td>
          <td>".$data['SourceURL']."</td>
        </tr>";
    }
    echo "</table>";

    // echo json_encode($error_images);
?>