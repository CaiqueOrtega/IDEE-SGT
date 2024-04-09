<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');

$connection = new Database();


session_start();
$id = $_SESSION['login']['id'];


$sql = ("SELECT * FROM `treinamento` WHERE 1=1");
$treinamentos = $connection->connection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);




?>