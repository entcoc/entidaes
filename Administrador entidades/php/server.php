<?php
$host="localhost";
$dbname="entidades";
$user="myentidade";
$pass="ATN2FW0U";
$sCon="mysql:host=$host;dbname=$dbname;";
try {
	$db=new PDO($sCon,$user,$pass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
	echo $e->getMessage();
}
?>