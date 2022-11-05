<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    
    $json = file_get_contents('/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/data/games.json');
    $array = json_decode($json,true);
    $error_images = [];

    foreach ( $array as $data ) {
        if ($data['state']) {
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // $f = fopen('php://output', 'w'); 
    
        // fputcsv($f,[
        //     "Name", "Game Code", "Platform", "Category", "Image", "Original Image"
        // ]);

        // foreach ($error_images as $line) { 
        //     fputcsv($f, $line, ","); 
        // }
        // rewind($f);
        // header('Content-Type: text/csv');
        // header("Content-Transfer-Encoding: Binary"); 
        // header('Content-Disposition: attachment; filename="Mexplay_active_games'.date('y-m-d_h-s').'.csv";');
        // readfile($f); 
        // fpassthru($f);
    }
    else
    {echo "<!DOCTYPE html>
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
        
        <h2>Active Games ".count($error_images)."</h2>
        <form action='export_games.php' method='post'>
            <button type='submit'>Download All</button>
        </form> 
    
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
    }
?>