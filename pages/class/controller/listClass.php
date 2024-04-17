<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');

$connection = new Database();

session_start();
$id = $_SESSION['login']['id'];
$idPermissao = $_SESSION['login']['permissao'];

