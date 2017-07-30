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
	<title><?=$entidad->nom?></title>
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
				<img src="<?=$entidad->img?>" alt="" class="img_ent" width="595" height="440">
				<p class="des_ent"><?=$entidad->des?></p>
			</div>
			<div class="datos-col">
				<div class="info">
					<h2>Tipo de entidad</h2>
					<ul>
						<li><strong>Rama: </strong><?=$entidad->ram?></li>
						<li><strong>Caracter: </strong><?=$entidad->car?></li>
						<li><strong>Nivel: </strong><?=$entidad->niv?></li>
						<li><strong>Orden: </strong><?=$entidad->ord?></li>
						<li><strong>Suborden: </strong><?=$entidad->subord?></li>
					</ul>
				</div>
				<div class="info">
					<h2>Información General</h2>
					<ul>
						<li><strong><?=$entidad->tdoc?>: </strong><?=$entidad->doc?></li>
						<li><strong>Sigla: </strong><?=$entidad->sig?></li>
						<li><strong>Web: </strong><a href="<?=$entidad->web?>" target="_blank"><?=$entidad->web?></a></li>
						<li><strong>Representante: </strong><?=$entidad->rep?></li>
						<li><strong>Telefono 1: </strong><?=$entidad->tel1?></li>
						<li><strong>Telefono 2: </strong><?=$entidad->tel2?></li>
						<li><strong>Telefono 3: </strong><?=$entidad->tel3?></li>
						<li><strong>Fax: </strong><?=$entidad->fax?></li>
					</ul>
				</div>
				<div class="info">
					<h2>Ubicación</h2>
					<ul>
						<li><strong>Departamento: </strong><?=$entidad->dep?></li>
						<li><strong>Municipio: </strong><?=$entidad->mun?></li>
						<li><strong>Direccion: </strong><?=$entidad->dir?></li>
					</ul>
					<div id="map" style="width:1000px; height:800px; margin-top: 0.5cm; margin-left: 4cm"></div>
				</div>
			</div>
		</div>
	</section>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=AIzaSyAjvryudz2XTVjean_g4BQDdeT0O3J-cuU" type="text/javascript"></script>  
	<script type="text/javascript">
		function inicializar() {
			if (GBrowserIsCompatible()) {
				var map = new GMap2(document.getElementById("map"));
				var ubicacion=new GLatLng(<?=$entidad->lat?>,<?=$entidad->lng?>);
				map.setCenter(ubicacion, 20);
				map.addControl(new GMapTypeControl());
				map.addControl(new GLargeMapControl());
				map.addControl(new GScaleControl());
				function informacion(ubicacion, descripcion) {
					var marca = new GMarker(ubicacion);
					GEvent.addListener(marca, "click", function(){
						marca.openInfoWindowHtml(descripcion); } );
					return marca;
				}
				var descripcion = '<b><?=$entidad->nom?></b><br/><?=$entidad->dir?><br />';
				var marca = informacion(ubicacion, descripcion);
				map.addOverlay(marca);
			}  
		}
		inicializar();
	</script>
</body>
</html>