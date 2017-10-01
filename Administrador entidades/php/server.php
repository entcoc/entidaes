<?php

$host="localhost";

$dbname="id2905317_visent";

$user="id2905317_admin";

$pass="95100136";

$sCon="mysql:host=$host;dbname=$dbname;";

try {

	$db=new PDO($sCon,$user,$pass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch (PDOException $e) {

	echo $e->getMessage();

}

?>