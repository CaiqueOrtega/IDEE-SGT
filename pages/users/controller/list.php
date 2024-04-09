<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');

$connection = new Database();




$sql = ("SELECT * FROM `usuario` INNER JOIN `login` 
ON usuario.id = login.id
WHERE 1=1" );
$usuariosData = $connection->connection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);


?>