<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visualización de entidades públicas de Colombia</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/jquery.ui.position.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.contextMenu.min.js"></script>
    <script src="js/jquery.bxslider.min.js"></script>
    <script src="js/interface.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="css/jquery.contextMenu.min.css">
    <link rel="stylesheet" href="css/jquery.bxslider.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="icon" href="images/favicon.ico">
</head>
<body>
    <!-- header -->
    <header>
        <a class="nav-logo" href="">
            <img src="images/Logo.svg" alt="" height="100" width="auto">
        </a>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="" class="is-active">Inicio</a></li>
                    <li><a href="entidades.php">Entidades</a></li>
                    <li><a href="#">Manual de Uso</a></li>
                    <li><a href="#">Acerca de</a></li>
                </ul>
            </nav>
            <form action="entidades.php" method="post">
                <input name="search" type="text" placeholder="Buscar..." class="button red">
            </form>
        </div>
    </header>
    <div id="main_container">
        <section class="entidades_principal">
            <div class="content_slider">
                <ul class="slider_principal">
<?php include 'Administrador entidades/php/server.php';
$s=$db->prepare("SELECT * FROM destacadas");
if($s->execute()){
    while($ar=$s->fetch(PDO::FETCH_ASSOC)){
        $entidad=(object)$ar; ?>
                    <li>
                        <figure style="background:url(<?=$entidad->img?>);"></figure>
                        <div>
                            <p><a style="text-decoration: none; color: white;" href="entidad.php?ent=<?=$entidad->id?>"><?=$entidad->nom?></a></p>
                        </div>
                        <span class="overlay"></span>
                    </li>
<?php }
} ?>
                </ul>
            </div>
        </section>
    </div>
</body>
</html>
