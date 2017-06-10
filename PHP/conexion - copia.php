<?php
header('Content-type: application/json; charset="utf-8"');

 $host='localhost';
 $user='myentidade';
 $pass='ATN2FW0U';
 $db='entidades';

 try {
 $cnn = new PDO("mysql:host={$host}; dbname={$db}", $user, $pass)or exit("Connection Error");    
} catch (Exception $e) {
    echo $e->getMessage();
}
?> 


	