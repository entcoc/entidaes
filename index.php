<?php
include 'Administrador entidades/php/server.php';
include 'PHP/functions.php';
extract($_GET);
$query="SELECT id_ent, nom_ent, ram_ent, car_ent, niv_ent, ord_ent, subord_ent, par_ent, dep_ent, mun_ent FROM entidad WHERE ";
$vars=array();
$def=" ? ";
if(isset($coin)&&$coin!=""){
	$con=$coin? " AND " : " OR ";
}
if(isset($ram)&&$ram!=""){
	$def="";
	$ramas=explode(",", $ram);
	$qram=" (";
	for ($i=0; $i < count($ramas); $i++) { 
		$qram.=$i?" OR ram_ent=?":"ram_ent=?";
		array_push($vars, $ramas[$i]);
	}
	$qram.=")$con";
}
if(isset($car)&&$car!=""){
	$def="";
	$carac=explode(",", $car);
	$qcar="(";
	for ($i=0; $i <count($carac) ; $i++) { 
		$qcar.=$i?" OR car_ent=?":"car_ent=?";
		array_push($vars, $carac[$i]);
	}
	$qcar.=")$con";
}
if(isset($niv)&&$niv!=""){
	$def="";
	$nivs=explode(",", $niv);
	$qniv="(";
	for ($i=0; $i <count($nivs) ; $i++) { 
		$qniv.=$i?" OR niv_ent=?":"niv_ent=?";
		array_push($vars, $nivs[$i]);
	}
	$qniv.=")$con";
}
if(isset($ord)&&$ord!=""){
	$def="";
	$ords=explode(",", $ord);
	$qord="(";
	for ($i=0; $i <count($ords) ; $i++) { 
		$qord.=$i?" OR ord_ent=?":"ord_ent=?";
		array_push($vars, $ords[$i]);
	}
	$qord.=")$con";
}
if(isset($subord)&&$subord!=""){
	$def="";
	$subords=explode(",", $subord);
	$qsubord="(";
	for ($i=0; $i <count($subords) ; $i++) { 
		$qsubord.=$i?" OR subord_ent=?":"subord_ent=?";
		array_push($vars, $subords[$i]);
	}
	$qsubord.=")$con";
}
if(isset($dep)&&$dep!=""){
	$def="";
	$deps=explode(",", $dep);
	$qdep="(";
	for ($i=0; $i <count($deps) ; $i++) { 
		$qdep.=$i?" OR dep_ent=?":"dep_ent=?";
		array_push($vars, $deps[$i]);
	}
	$qdep.=")$con";
}
if(isset($mun)&&$mun!=""){
	$def="";
	$muns=explode(",", $mun);
	$qmun="(";
	for ($i=0; $i <count($muns) ; $i++) { 
		$qmun.=$i?" OR mun_ent=?":"mun_ent=?";
		array_push($vars, $muns[$i]);
	}
	$qmun.=")$con";
}
$query.=$def.$qram.$qcar.$qniv.$qord.$qsubord.$qdep.$qmun;
$query=explode(" ", $query);
if($query[count($query)-2]=="AND" || $query[count($query)-2]=="OR")
	$query[count($query)-2]="";
$query[count($query)-2];
$query=implode(" ", $query);
$s=$db->prepare($query);
if($def==" ? "){
	$vars=array(1);
}
if($s->execute($vars)){
	$ramIDs=array();
	$entidades=array();
	$as=$db->prepare("SELECT id_ent, nom_ent, ram_ent, car_ent, niv_ent, ord_ent, subord_ent, par_ent, dep_ent, mun_ent FROM entidad WHERE id_ent=?");
	while($ar=$s->fetch(PDO::FETCH_ASSOC))
	{
		array_push($entidades, $ar);
		if($def!=" ? "){
			$entidades=ascendientes($entidades,$as,$ar['par_ent']);
		}
	}
	if($def!=" ? "){
		$nEn=count($entidades);
		for ($i=0; $i < $nEn && count($ramIDs)<9 ; $i++) {
			if(!contiene($ramIDs,$entidades[$i]['ram_ent'])){
				array_push($ramIDs,$entidades[$i]['ram_ent']);
			}
		}
	}
}
else{
	echo $s->errorInfo()[2];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="js/jquery.ui.position.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/jquery.contextMenu.min.js"></script>
	<script src="http://d3js.org/d3.v4.min.js"></script>
	<script src="js/variables.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.min.css">
	<link rel="stylesheet" href="css/jquery.contextMenu.min.css">
	<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
	<form action="#" method="get" onsubmit="cargarfiltros()">
		<select name="fram" id="sel_ram">
<?php if($s=$db->query("SELECT nom_ram, id_ram FROM rama")){ $ramas=array();
	while($ar=$s->fetch(PDO::FETCH_ASSOC)){ if(!count($ramIDs) || contiene($ramIDs,$ar['id_ram'])) array_push($ramas, $ar); ?>
			<option value="<?=$ar['id_ram']?>"><?=$ar["nom_ram"]?></option>
<?	}
}
 ?>
		</select>
		<input type="hidden" name="ram">
		<select name="fcar" id="sel_car">
<?php if($s=$db->query("SELECT nom_car, id_car FROM caracter")){ $carac=array();
	while($ar=$s->fetch(PDO::FETCH_ASSOC)){ array_push($carac, $ar); ?>
			<option value="<?=$ar['id_car']?>"><?=$ar["nom_car"]?></option>
<?	}
}
 ?>
		</select>
		<input type="hidden" name="car">
		<select name="fniv" id="sel_niv">
<?php if($s=$db->query("SELECT nom_niv, id_niv FROM nivel")){ $nivs=array();
	while($ar=$s->fetch(PDO::FETCH_ASSOC)){ array_push($nivs, $ar); ?>
			<option value="<?=$ar['id_niv']?>"><?=$ar["nom_niv"]?></option>
<?	}
}
 ?>
		</select>
		<input type="hidden" name="niv">
		<select name="ford" id="sel_ord">
<?php if($s=$db->query("SELECT nom_ord, id_ord FROM orden")){ $ords=array();
	while($ar=$s->fetch(PDO::FETCH_ASSOC)){ array_push($ords, $ar); ?>
			<option value="<?=$ar['id_ord']?>"><?=$ar["nom_ord"]?></option>
<?	}
}
 ?>
		</select>
		<input type="hidden" name="ord">
		<select name="fsubord" id="sel_subord">
<?php if($s=$db->query("SELECT nom_subord, id_subord FROM suborden")){ $subords=array();
	while($ar=$s->fetch(PDO::FETCH_ASSOC)){ array_push($subords, $ar); ?>
			<option value="<?=$ar['id_subord']?>"><?=$ar["nom_subord"]?></option>
<?	}
}
 ?>
		</select>
		<input type="hidden" name="subord">
		<select name="fdep" id="sel_dep">
<?php if($s=$db->query("SELECT nom_dep, id_dep FROM departamento")){ $deps=array();
	while($ar=$s->fetch(PDO::FETCH_ASSOC)){ array_push($deps, $ar); ?>
			<option value="<?=$ar['id_dep']?>"><?=$ar["nom_dep"]?></option>
<?	}
}
 ?>
		</select>
		<input type="hidden" name="dep">
		<select name="fmun" id="sel_mun">
<?php if($s=$db->query("SELECT nom_mun, id_mun FROM municipio")){ $muns=array();
	while($ar=$s->fetch(PDO::FETCH_ASSOC)){ array_push($muns, $ar) ?>
			<option value="<?=$ar['id_mun']?>"><?=$ar["nom_mun"]?></option>
<?	}
}
 ?>
		</select>
		<input type="hidden" name="mun">
		<br>
		<label for="coin">Coincidir filtros</label><input id="coin" name="fcoin" type="checkbox">
		<div class="filtros"></div>
		<input type="hidden" name="coin">
		<input type="submit" value="Crear vista" style="display: block;">
	</form>
	<div style="background: white;" id="treeShow"></div>
	<script>
		//Listado de ramas del poder público
		var rama=<?=json_encode($ramas)?>;
		//Listado de caracter de las entidades
		var caracter=<?=json_encode($carac)?>;
		//Listado de niveles de las entidades
		var nivel=<?=json_encode($nivs)?>;
		//Listado de los ordenes de las entidades
		var orden=<?=json_encode($ords)?>;
		//Listado de los subordenes de las entidades
		var suborden=<?=json_encode($subords)?>;
		//Listado completo de entidades Públicas
		var entidad=<?=json_encode($entidades)?>;
		//Listado de departamentos de Colombia
		var departamento=<?=json_encode($deps)?>;
		//Listado de municipios de Colombia
		var municipio=<?=json_encode($muns)?>;
	</script>
	<script src="./js/functions.js"></script>
	<script>
		addFiltro(<?=json_encode(explode(",", $ram))?>,"ram");
		addFiltro(<?=json_encode(explode(",", $car))?>,"car");
		addFiltro(<?=json_encode(explode(",", $niv))?>,"niv");
		addFiltro(<?=json_encode(explode(",", $ord))?>,"ord");
		addFiltro(<?=json_encode(explode(",", $subord))?>,"subord");
		addFiltro(<?=json_encode(explode(",", $dep))?>,"dep");
		addFiltro(<?=json_encode(explode(",", $mun))?>,"mun");
	</script>
</body>
</html>