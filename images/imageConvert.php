<?php

    foreach ( ["live", "table", "lotto", "poker", "arcade", "slot"] as $data )
    {
        $files = glob(__DIR__."/images/$data"."_games/*.png");

        foreach ($files as $file)
        {
            $name = pathinfo($file)['filename'];

            $image = imagecreatefrompng($file);
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));

            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, TRUE);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            imagedestroy($image);
            
            imagejpeg($bg, __DIR__."/images/games/$name.jpg", 80);
            imagedestroy($bg);

            echo $file."\n";
        }
    }
?>