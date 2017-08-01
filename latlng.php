<?php 
/*$ent=array("Agencia colombiana para la Reintegración de Personas y Grupos Alzados En Armas","Empresa Nacional de Renovación y Desarrollo Urbano, Virgilio Barco Vargas S.A.S.","Agencia Presidencial de Cooperación Internacional de Colombia","Unidad Nacional Para la Gestión del Riesgo de Desastres","Agencia Nacional de Contratación Pública - Colombia Compra Eficiente","Fondo Financiero de Proyectos de Desarrollo","Superintendencia de Servicios Públicos Domiciliarios","Fondo Rotatorio del Departamento Administrativo de Seguridad","Agencia Nacional Para la Superación de la Pobreza Extrema","Centro Nacional de Memoria Histórica","Instituto Colombiano de Bienestar Familiar","Unidad Administrativa Especial para la Atención y Reparación Integral a las Victimas","Unidad Administrativa Especial para la Consolidación Territorial","Contraloría Departamental de Amazonas","Contraloría Municipal de Envigado en Antioquia","Contraloría Departamental de Antioquía","Contraloría Municipal de Bello en Antioquia","Contraloría Municipal de Itagui en Antioquia","Contraloría Municipal de Medellin en Antioquia","Contraloría Departamental de Arauca","Contraloría Departamental del Archipielago de San Andrés y Providencia","Contraloría Departamental del Atlántico","Contraloría Municipal de Barranquilla en Atlántico","Contraloría Municipal de Soledad en Atlántico","Auditoría General de la Republica","Contraloría General de la Republica","Defensoría del Pueblo","Fondo de Bienestar Social de la Contraloría General de la República","Instituto de Estudios del Ministerio Publico","Procuraduría General de la Nación","Contraloría Departamental de Bolívar","Contraloría Municipal de Cartagena en Bolívar","Contraloría Departamental de Boyacá","Contraloría Municipal de Tunja en Boyacá","Contraloría Departamental de Caldas","Contraloría Municipal de Manizales en Caldas","Contraloría Departamental de Caquetá","Contraloría Departamental de Casanare","Contraloría Municipal de Popayan en Cauca","Contraloría Departamental de Cauca","Contraloría Departamental de Cesar","Contraloría Municipal de Valledupar en Cesar","Contraloría Departamental de Chocó","Contraloría Departamental de Córdoba","Contraloría Municipal de Monteria  en Córdoba","Contraloría Departamental de Cundinamarca","Contraloría Municipal de Soacha en Cundinamarca","Contraloría Departamental de Guainía","Contraloría Departamental de Guaviare","Contraloría Departamental de Huila","Contraloría Municipal de Neiva en Huila","Contraloría Departamental de La Guajira","Contraloría Municipal de Santa Marta en Magdalena","Contraloría Departamental de Magdalena","Contraloría Departamental de Meta","Contraloría Municipal de Villavicencio en Meta","Contraloría Departamental de Nariño","Contraloría Municipal de Pasto en Nariño","Contraloría Departamental de Norte de Santander","Contraloría Municipal de Cucuta en Norte de Santander","Contraloría Departamental de Putumayo","Contraloría Departamental del Quindío","Contraloría Municipal de Armenia en Quindío","Contraloría Municipal de Dosquebradas en Risaralda","Contraloría Departamental de Risaralda","Contraloría Municipal de Pereira en Risaralda","Contraloría Municipal de Barrancabermeja en Santander","Contraloría Departamental de Santander","Contraloría Municipal de Floridablanca en Santander","Contraloría Municipal de Bucaramanga en Santander","Contraloría Departamental de Sucre","Contraloría Departamental de Tolima","Contraloría Municipal de Ibague en Tolima","Contraloría Departamental de Valle del Cauca","Contraloría Municipal de Palmira en Valle del Cauca","Contraloría Municipal de Buenaventura en Valle del Cauca","Contraloría Municipal de Cali en Valle del Cauca","Contraloría Municipal de Tuluá en Valle del Cauca","Contraloría Municipal de Yumbo en Valle del Cauca","Contraloría Departamental de Vaupés","Contraloría Departamental de Vichada","Junta Central de Contadores");
$lat=array("4.600933","4.683619","4.64422","4.6831","4.612081","4.612898","4.6701","","4.647524","4.622278","4.671367","4.602388","4.685853","-4.212086","6.244136","","","","6.17009","7.08332","","","","","","4.602426","4.603874","4.599506","4.601539","4.601539","10.42489","","5.531907","","5.068669","","1.615122","5.347479","2.440656","2.441989","","10.473302","5.688579","8.751624","","4.636287","","","2.573441","2.927041","","11.553011","11.243579","11.243736","4.148526","","1.214783","","7.885607","","1.155161","","","","","4.814653","","","","7.116539","","","","","","","","","","","","");
$lng=array("-74.073901","-74.119032","-74.102235","-74.120877","-74.06973","-74.071592","-74.057303","","-74.063867","-74.065755","-74.08976","-74.07253","-74.130654","-69.943856","-75.573234","","","","-75.588098","-70.758795","","","","","","-74.074717","-74.074513","-74.075395","-74.071542","-74.071542","-75.55285","","-73.36202","","-75.517517","","-75.614499","-72.400783","-76.607247","-76.60577","","-73.250793","-76.661942","-75.884274","","-74.06612","","","-72.640915","-75.289515","","-72.907419","-74.213974","-74.210749","-73.638123","","-77.277406","","-72.503691","","-76.646881","","","","","-75.693787","","","","-73.129955","","","","","","","","","","","","");*/
include 'Administrador entidades/php/server.php';
extract($_POST);
$ent=array($ent);
$lat=array($lat);
$lng=array($lng);
$query="CALL setLatLng(?,?,?)";
$s=$db->prepare($query);
for ($i=0; $i < count($ent); $i++) { 
	$vars=array(htmlentities($ent[$i]),$lat[$i],$lng[$i]);
	echo $ent[$i].": ";
	if($s->execute($vars)){
		echo "Exito<br>";
	}else{
		echo $s->errorInfo()[2].'<br>';
	}
}
 ?>