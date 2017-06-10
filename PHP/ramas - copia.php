
<?php
include_once("conexion - copia.php");
$res = $cnn->query("SELECT * FROM rama");

           while($fildss= $res->fetch(PDO::FETCH_ASSOC))
{             
    echo json_encode($fildss);
}
        ?>	