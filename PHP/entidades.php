<?php
header('Content-Type: application/json, charset="utf-8"');
include_once("conexion.php");
$res = $cnn->query('SELECT * FROM entidad');
while($fildss= $res->fetchall(PDO::FETCH_ASSOC)){             
    echo json_encode($fildss);
}
?>