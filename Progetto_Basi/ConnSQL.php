<?php

class ConnSQL
{

  var $servername = "localhost";
  var $username = "root";
  var $password = "";
  var $dbname = "CONFVIRTUAL";

  public static function DBConnection()
  {
    try {
      $conn = new PDO('mysql:host=localhost;dbname=CONFVIRTUAL', 'root', '');
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
  }
}
?>