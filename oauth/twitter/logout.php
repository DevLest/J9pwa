<?php

require "boot.php";

unset($_SESSION['twitter_auth']);
unset($_SESSION['TwitterPayload']);
header("location: index.php");