<?php
include('server.php');
extract($_GET);
$query='insert into municipio  (dep_mun,nom_mun) values ';
$muns=explode(",", $mun);
$inserts=array();
for($i=0;$i<count($muns);$i++){
	if($i==0){
		$query.='(?,?)';
	}
	else
		$query.=',(?,?)';
	array_push($inserts, $dep);
	array_push($inserts, htmlentities($muns[$i]));
}
$s=$db->prepare($query);
if($s->execute($inserts)){
	echo 1;
}
else{
	echo $s->errorInfo()[2];
}
?>