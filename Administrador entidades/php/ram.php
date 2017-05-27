<?php
include('server.php');
extract($_GET);
$s=$db->prepare('insert into rama  (nom_ram) values (?)');
if($s->execute(array(htmlentities($ram)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>