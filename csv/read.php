<?php


$file = fopen('Missing Games in MX - overwrite in 999.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
   //$line[0] = '1004000018' in first iteration
   print_r($line);
}
fclose($file);