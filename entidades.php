<?php include 'carga_entidades.php'; ?>
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
	<link rel="icon" href="images/favicon.ico">
</head>
<body>
	<header>
        <a class="nav-logo" href="">
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
    <div class="content_entidades">
	<form action="#" id="filtros" method="post" onsubmit="cargarfiltros()">
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
	</div>
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