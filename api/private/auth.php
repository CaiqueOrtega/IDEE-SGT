<?php


session_start();
if(!isset($_SESSION['login'])){
    header('Location: /projeto/home.php');
    exit;
}

