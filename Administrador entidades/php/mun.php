<?php
include('server.php');
extract($_GET);
$s=$db->prepare('insert into municipio  (nom_mun) values (?)');
if($s->execute(array(htmlentities($mun)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>