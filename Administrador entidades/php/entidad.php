<?php
include('server.php');
extract($_POST);
$query='insert into entidad 
		(nom_ent,ram_ent,car_ent,niv_ent,ord_ent,subord_ent,
		tdoc_ent,doc_ent,par_ent,sig_ent,tel1_ent,tel2_ent,
		tel3_ent,fax_ent,web_ent,dir_ent,dep_ent,mun_ent,
		rep_ent,des_ent,img_ent,lat_ent,lng_ent) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
$inserts=array(htmlentities($nom_ent),$ram_ent,$car_ent,$niv_ent,$ord_ent,$subord_ent,
		$tdoc_ent,htmlentities($doc_ent),$par_ent,htmlentities($sig_ent),htmlentities($tel1_ent),htmlentities($tel2_ent),
		htmlentities($tel3_ent),htmlentities($fax_ent),htmlentities($web_ent),htmlentities($dir_ent),$dep_ent,$mun_ent,
		htmlentities($rep_ent),htmlentities($des_ent),htmlentities($img_ent),floatval($lat_ent),floatval($lng_ent));
$s=$db->prepare($query);
if($s->execute($inserts)){
	echo $db->lastInsertId();
}
else{
	echo $s->errorInfo()[2];
}
?>