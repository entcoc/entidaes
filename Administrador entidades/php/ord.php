<?php
include('server.php');
extract($_GET);
$s=$db->prepare('insert into orden  (nom_ord) values (?)');
if($s->execute(array(htmlentities($ord)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>