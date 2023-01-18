<?php

ini_set('memory_limit', '-1');

ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

require "boot.php";
// $app_id = "odGJtUtFaP6urmMDq6SzlsH6Q";
// $app_secret = "zNliHCE2b3tFjgWGP2jAbc6SW4KgUjmE2W6xHvuXR7MnusBGyl";
$app_id = "rJHpJqpZf6ZruE62zV4LkzPGE";
$app_secret = "";

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'rJHpJqpZf6ZruE62zV4LkzPGE');
define('CONSUMER_SECRET', 'SWE0VUrhuAXriu2ffSkHCx1z7lhFsdT16EUIIuLo1wQPxcADqJ');
define('OAUTH_CALLBACK', 'https://999j9azx.999game.online/j9pwa/oauth/twitter/callback.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

// echo "<pre>";
// print_r($connection);
// echo "</pre>";