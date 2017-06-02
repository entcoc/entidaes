<?php
include('server.php');
extract($_GET);
$s=$db->prepare('insert into suborden  (nom_subord) values (?)');
if($s->execute(array(htmlentities($ord)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>