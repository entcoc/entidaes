<?php
include('server.php');
extract($_GET);
$query='insert into caracter  (nom_car) values ';
$car=explode(",", $car);
$inserts=array();
for($i=0;$i<count($car);$i++){
	if($i==0){
		$query.='(?)';
	}
	else
		$query.=',(?)';
	array_push($inserts, htmlentities($car[$i]));
}
$s=$db->prepare($query);
if($s->execute(array(htmlentities($car)))){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>