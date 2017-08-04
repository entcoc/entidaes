<?php include 'Administrador entidades/php/server.php';
include 'PHP/functions.php';
$s=$db->prepare("CALL getEntidad(?)");
if($s->execute(array($_GET['ent']))){
	$row = $s->fetchAll(PDO::FETCH_ASSOC);
	$entidad=(object)$row[0];
	$s->closeCursor();
	$asc=$db->prepare("SELECT id_ent as id, nom_ent as nom, par_ent FROM entidad WHERE id_ent=? LIMIT 1");
	$entas=array();
	$entas=ascendientes($entas,$asc,$entidad->par,1,false);
	$ds=$db->prepare("SELECT id_ent as id, nom_ent as nom FROM entidad WHERE par_ent=? ORDER BY RAND() LIMIT 3");
	$entds=descendientes(array(),$ds,$_GET['ent'],1,1);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?=$entidad->nom?></title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="icon" href="images/favicon.ico">
</head>
<body>
	<header>
		<a class="nav-logo" href="/">
			<img src="images/Logo.svg" alt="" height="100" width="auto">
		</a>
		<div class="header-right">
			<nav>
				<ul>
					<li><a href="/">Inicio</a></li>
					<li><a href="entidades.php" class="is-active">Entidades</a></li>
					<li><a href="#">Manual de Uso</a></li>
					<li><a href="#">Acerca de</a></li>
				</ul>
			</nav>
			<form action="entidades.php" method="post">
				<input name="search" type="text" placeholder="Buscar..." class="button red">
			</form>
		</div>
	</header>
	<section class="entidad">
		<h1><?=$entidad->nom?></h1>
		<div class="datos">
			<div class="datos-col">
				<img src="<?=$entidad->img?>" alt="<?=$entidad->nom?>" class="img_ent">
				<p class="des_ent"><?=$entidad->des?></p>
				<h2>Entidades relacionadas</h2>
				<div class="info">
					<h2>Nivel Superior</h2>
					<ul class="datos-list">
						<li><a href="?ent=<?=$entas[0]->id?>"><?=$entas[0]->nom?></a></li>
						<li><a href="?ent=<?=$entas[1]->id?>"><?=$entas[1]->nom?></a></li>
						<li><a href="?ent=<?=$entas[2]->id?>"><?=$entas[2]->nom?></a></li>
					</ul>
				</div>
				<div class="info">
					<h2>Nivel inferior</h2>
					<ul class="datos-list">
						<li><a href="?ent=<?=$entds[0]->id?>"><?=$entds[0]->nom?></a></li>
						<li><a href="?ent=<?=$entds[1]->id?>"><?=$entds[1]->nom?></a></li>
						<li><a href="?ent=<?=$entds[2]->id?>"><?=$entds[2]->nom?></a></li>
					</ul>
				</div>
			</div>
			<div class="datos-col">
				<div class="info">
					<h2>Tipo de entidad</h2>
					<ul class="datos-list">
						<li><strong>Rama: </strong><?=$entidad->ram?></li>
						<li><strong>Caracter: </strong><?=$entidad->car?></li>
						<li><strong>Nivel: </strong><?=$entidad->niv?></li>
						<li><strong>Orden: </strong><?=$entidad->ord?></li>
						<li><strong>Suborden: </strong><?=$entidad->subord?></li>
					</ul>
				</div>
				<div class="info">
					<h2>Información General</h2>
					<ul class="datos-list">
						<li><strong><?=$entidad->tdoc?>: </strong><?=$entidad->doc?></li>
						<li><strong>Sigla: </strong><?=$entidad->sig?></li>
						<li><strong>Web: </strong><a href="<?=$entidad->web?>" target="_blank"><?=$entidad->web?></a></li>
						<li><strong>Representante: </strong><?=$entidad->rep?></li>
						<li><strong>Telefono 1: </strong><a href="tel:<?=$entidad->tel1?>"><?=$entidad->tel1?></a></li>
						<li><strong>Telefono 2: </strong><a href="tel:<?=$entidad->tel2?>"><?=$entidad->tel2?></a></li>
						<li><strong>Telefono 3: </strong><a href="tel:<?=$entidad->tel3?>"><?=$entidad->tel3?></a></li>
						<li><strong>Fax: </strong><?=$entidad->fax?></li>
					</ul>
				</div>
				<div class="info">
					<h2>Ubicación</h2>
					<ul class="datos-list">
						<li><strong>Departamento: </strong><?=$entidad->dep?></li>
						<li><strong>Municipio: </strong><?=$entidad->mun?></li>
						<li><strong>Direccion: </strong><?=$entidad->dir?></li>
					</ul>
					<div id="map"></div>
				</div>
			</div>
		</div>
	</section>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript">
		function initMap(){
			function inicializar(lat,lng) {
				var ubicacion=new google.maps.LatLng(lat,lng);
				var map = new google.maps.Map(document.getElementById("map"),{center:ubicacion,zoom:20});
				function informacion(ubicacion, descripcion,map) {
					var marca = new google.maps.Marker({
						position: ubicacion,
						map: map
					});
					var infoWindow=new google.maps.InfoWindow({content:descripcion});
					marca.addListener('click',function(){
						infoWindow.open(map,marca);
					});
					return marca;
				}
				var descripcion = '<b><?=$entidad->nom?></b><br/><?=$entidad->dir?><br />';
				var marca = informacion(ubicacion, descripcion,map); 
			}
<? if($entidad->lat!=""){ ?>
			inicializar(<?=$entidad->lat?>,<?=$entidad->lng?>);
<? }else{ ?>
			function codeAddress() {
				var geocoder = new google.maps.Geocoder();
				var nombre=$('<p>').html("<?=$entidad->nom?>").text();
				var address = nombre;
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == 'OK') {
						var loc=results[0].geometry.location;
						inicializar(loc.lat(),loc.lng());
						var data={ent:"<?=$entidad->nom?>",lat:loc.lat(),lng:loc.lng()};
						$.post("latlng.php",data,function(d){
							console.log(d);
						});
					} else {
						console.log("no encontrado");
					}
				});
			}
			codeAddress();
		<? } ?>
		}
		</script>
	<!-- <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=AIzaSyAjvryudz2XTVjean_g4BQDdeT0O3J-cuU" type="text/javascript"></script> -->
	<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjvryudz2XTVjean_g4BQDdeT0O3J-cuU&callback=initMap">
    </script>
	</body>
	</html>