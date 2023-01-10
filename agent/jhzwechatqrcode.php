<?php
header("Content-type: text/html; charset=utf-8");
include("phpqrcode.php");

	$url = $_GET['url'];;

	if($url != ""){
		$errorCorrectionLevel = "L";
		$matrixPointSize = "8";
		QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
	}
?> 
