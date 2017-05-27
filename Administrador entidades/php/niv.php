<?php
include('server.php');
extract($_GET);
$s=$db->prepare('insert into nivel  (nom_niv) values (?)');
if($s->execute(array(htmlentities($niv)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>