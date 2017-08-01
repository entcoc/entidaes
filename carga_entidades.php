<?php
include 'Administrador entidades/php/server.php';
include 'PHP/functions.php';
extract($_POST);
$query="SELECT id_ent, nom_ent, ram_ent, car_ent, niv_ent, ord_ent, subord_ent, par_ent, dep_ent, mun_ent, tdoc_ent FROM entidad WHERE ";
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
if(isset($search)){
	$def="";
	$search=htmlentities($search);
	$qsearch="nom_ent LIKE CONCAT('%',?,'%') OR sig_ent LIKE CONCAT('%',?,'%') OR web_ent LIKE CONCAT('%',?,'%') OR des_ent LIKE CONCAT('%',?,'%')";
	$vars=array_merge($vars,array($search,$search,$search,$search));
}
$query.=$def.$qram.$qcar.$qniv.$qord.$qsubord.$qdep.$qmun.$qsearch;
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
	$as=$db->prepare("SELECT id_ent, nom_ent, ram_ent, car_ent, niv_ent, ord_ent, subord_ent, par_ent, dep_ent, mun_ent, tdoc_ent FROM entidad WHERE id_ent=?");
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