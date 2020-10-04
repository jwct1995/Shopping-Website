<?php

$Host = "localhost";
$User = "129751";
$Pass = "soo12345";
$dbName = "129751";

$mysqli=new mysqli($Host,$User,$Pass,$dbName);
if($mysqli->connect_error)
	die("Connection Failed : ".$mysqli->connect_error."<br>");
?>