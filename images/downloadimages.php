<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    
    $json = file_get_contents('/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/images/slots.json');
    $array = json_decode($json,true);
    $error_images = [];

    foreach ( $array as $data )
    {
        $path_parts = pathinfo($data['sourceImge']);

        $img = "/www/wwwroot/u2daszapp.u2d8899.com/j9pwa/images/games/".$data['id'].".webp";
        try
        {
            if (file_exists($img)) 
            {
                echo $data['id']."\n";
                continue;
            }
            if (file_put_contents($img, file_get_contents($data['sourceImge']))) 
            {
                echo $img."\n";
            }
            else
            {
                // array_push($error_images, [
                //     'gamecode' => $data['id'],
                //     'gamename' => $data['name'],
                //     'imageURL' => $data['sourceImge'],
                // ]);
                echo "Error ------------------------------------- ".$data['sourceImge']."\n";
            }
        }
        catch (Exception $e)
        {
            // array_push($error_images, [
            //     'gamecode' => $data['id'],
            //     'gamename' => $data['name'],
            //     'imageURL' => $data['sourceImge'],
            // ]);
            echo "Error ------------------------------------- ".$data['sourceImge']."\n";
        }
    }
    
    // file_put_contents('array.txt', json_encode($error_images));
?>