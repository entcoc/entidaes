<?php include 'Administrador entidades/php/server.php';
$query="CALL getEntidad(?)";
$s=$db->prepare($query);
if($s->execute(array($_GET['ent']))){
	if($ar=$s->fetch(PDO::FETCH_ASSOC)){
		$entidad=(object)$ar;
	}
}else{
	echo $s->errorInfo()[2];
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?=$entidad?></title>
</head>
<body>
	
</body>
</html>