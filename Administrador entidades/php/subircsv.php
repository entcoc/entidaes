<?php 
include 'server.php';
$ram="(SELECT id_ram FROM rama WHERE nom_ram LIKE CONCAT('%',?,'%') LIMIT 1)";
$car="(SELECT id_car FROM caracter WHERE nom_car LIKE CONCAT('%',?,'%') LIMIT 1)";
$niv="(SELECT id_niv FROM nivel WHERE nom_niv LIKE CONCAT('%',?,'%') LIMIT 1)";
$ord="(SELECT id_ord FROM orden WHERE nom_ord LIKE CONCAT('%',?,'%') LIMIT 1)";
$mun="(SELECT id_mun FROM municipio WHERE nom_mun LIKE CONCAT('%',?,'%') LIMIT 1)";
$dep="(SELECT id_dep FROM departamento WHERE nom_dep LIKE CONCAT('%',?,'%') LIMIT 1)";
$subord="(SELECT id_subord FROM suborden WHERE nom_subord LIKE CONCAT('%',?,'%') LIMIT 1)";
$tdoc="(SELECT id_tdoc FROM tipodoc WHERE nom_tdoc LIKE CONCAT('%',?,'%') LIMIT 1)";
$par="(SELECT enti.id_ent FROM entidad as enti WHERE enti.id_ent=? OR enti.nom_ent LIKE CONCAT('%',?,'%') LIMIT 1)";
$insertValues="(?,$par,$ram,$car,$niv,$ord,$subord,$tdoc,?,?,?,?,?,?,?,?,$dep,$mun,?,?,?,?,?)";
$query="INSERT INTO entidad(nom_ent,par_ent,ram_ent,car_ent,niv_ent,ord_ent,subord_ent,tdoc_ent,doc_ent,sig_ent,tel1_ent,tel2_ent,tel3_ent,fax_ent,web_ent,dir_ent,dep_ent,mun_ent,rep_ent,lat_ent,lng_ent,des_ent,img_ent) VALUES $insertValues";
$csv= array_map('str_getcsv', file($_FILES['sel_file']['tmp_name']));
array_walk($csv, function(&$a){
	array_walk($a, function(&$v){
		$v=htmlentities($v);
	});
	array_splice($a, 8,1);
	array_splice($a, 24,1);
});
$s=$db->prepare($query);
$error=false;
for ($i=1; $i < count($csv); $i++) {
	$c=count($csv[$i]);
	if($c!=24){
		echo "Verifique el formato en que escribe los textos, por ejemplo en las descripciones";
		$error=true;
		break;
	}
	echo "$i -> ";
	if($s->execute($csv[$i])===false){
		print_r($csv[$i]);
		echo "<br>\nNo se pudo insertar<br>\n";
		$error=true;
		break;
	}
	echo "Exito en la inserción<br>\n";
}
if($error){
	echo "Algo malio sal XD<br>\n";
	print_r($s->errorInfo());
	exit();
}else{
	echo "Todas las inserciones se han logrado con exito<br>\n";
}
// $fname = $_FILES['sel_file']['name'];
// echo 'Cargando nombre del archivo: '.$fname.' <br>';
// $chk_ext = explode(".",$fname);
// if(strtolower(end($chk_ext)) == "csv"){
// 	$filename = $_FILES['sel_file']['tmp_name'];
// 	$handle = fopen($filename, "r");
// 	while($data = fgetcsv($handle, 1000, ",")){
// 		$inserts=array();
// 	}
// }
?>