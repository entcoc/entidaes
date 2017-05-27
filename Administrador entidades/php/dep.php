<?php
include('server.php');
extract($_GET);
$s=$db->prepare('insert into departamento  (nom_dep) values (?)');
if($s->execute(array(htmlentities($dep)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>