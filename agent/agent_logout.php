<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
include_once("../sessionstate.php");
if( isset($_SESSION['agent_name']) || $_SESSION['agent_name'] != '')
{
	unset($_SESSION['agent_name']);
	echo "<script>location.href='/agent.html'</script>";
}
?>