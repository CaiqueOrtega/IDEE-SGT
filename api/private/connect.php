<?php


class Database
{
   
    public function connection()
    {
        $host = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "banco";

        try {
            
            $connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            ));
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}



