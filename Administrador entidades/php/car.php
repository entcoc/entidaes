<?php
include('server.php');
extract($_GET);
$s=$db->prepare('insert into caracter  (nom_car) values (?)');
if($s->execute(array(htmlentities($car)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>