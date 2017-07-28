<?php 
include 'server.php';
$ram="(SELECT id_ram WHERE nom_ram LIKE '%?%')";
$car="(SELECT id_car WHERE nom_car LIKE '%?%')";
$niv="(SELECT id_niv WHERE nom_niv LIKE '%?%')";
$ord="(SELECT id_ord WHERE nom_ord LIKE '%?%')";
$mun="(SELECT id_mun WHERE nom_mun LIKE '%?%')";
$dep="(SELECT id_dep WHERE nom_dep LIKE '%?%')";
$subord="(SELECT id_subord WHERE nom_subord LIKE '%?%')";
$tdoc="(SELECT id_tdoc WHERE nom_tdoc LIKE '%?%')";
$query="INSERT INTO entidad(nom_ent,ram_ent,car_ent,niv_ent,ord_ent,subord_ent,mun_ent,dep_ent,tdoc_ent,doc_ent,par_ent,sig_ent,tel1_ent,tel2_ent,tel3_ent,fax_ent,web_ent,dir_ent,rep_ent,des_ent,img_ent,lat_ent,lng_ent) VALUES (?,$ram,$car,$niv,$ord,$subord,$mun,$dep,$tdoc,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$s=$db->prepare($query);
$fname = $_FILES['sel_file']['name'];
echo 'Cargando nombre del archivo: '.$fname.' <br>';
$chk_ext = explode(".",$fname);
if(strtolower(end($chk_ext)) == "csv"){
	$filename = $_FILES['sel_file']['tmp_name'];
	$handle = fopen($filename, "r");
	while($data = fgetcsv($handle, 1000, ",")){
		$inserts=array();
	}
}
?>