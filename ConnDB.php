<?php

class ConnDB {

    var $servername = "localhost";
    var $username = "root";
    var $password = "";
    var $dbname = "AnimalHouseDB";

    public static function DBConnection(){
        try {
            $conn = new PDO('mysql:host=sql8.freesqldatabase.com;dbname=sql8516965','sql8516965', 'gSVfHUkJgs');
          } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
          }
          return $conn;
    }
}

?> 