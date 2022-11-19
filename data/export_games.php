<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    header('Content-Type: text/csv');
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=game_list.xls");
    error_reporting(E_ALL);
    
    $json = file_get_contents('/www/wwwroot/999j9azx.u2d8899.com/j9pwa/data/games.json');
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
?>


<!DOCTYPE html>
<html>
<head>
    <title>Active games</title>
</head>
<body>
        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Game Code</th>
                    <th>Platform</th>
                    <th>Category</th>
                    <th>Image URL</th>
                    <th>Source Image</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($error_images as $data){
                       ?>
                        <tr>
                          <td><?php echo $data['Name']; ?></td>
                          <td><?php echo $data['GameCode']; ?></td>
                          <td><?php echo $data['Platform']; ?></td>
                          <td><?php echo $data['Category']; ?></td>
                          <td><?php echo $data['ImageURL']; ?></td>
                          <td><?php echo $data['SourceURL']; ?></td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
</body>
</html>