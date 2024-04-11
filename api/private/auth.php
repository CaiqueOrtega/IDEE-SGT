<?php


session_start();
if(!isset($_SESSION['login'])){
    header('Location: /IDEE-SGT/home.php');
    exit;
}

