<?php
include 'server.php';
extract($_GET);
$s=$db->prepare($q);
$vars=explode(',', $v);
if($s->execute($vars)){
	$ar=$s->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($ar);
}
else{
	echo $s->errorInfo()[2];
}
?>