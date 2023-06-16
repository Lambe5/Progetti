<!DOCTYPE html>
<?php 
require_once "./ConnSQL.php";
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Info Aggiuntive</title>
</head>
<body class="bg-info">
    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Lista Info Aggiuntive</h1>
    </div>

    <div>
        <form action="" method="POST">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }

        $conn = ConnSQL::DBConnection();

        $codiceTutorial = $_GET["codiceTutorial"];

        $info = $conn -> prepare("SELECT * FROM  INFO_AGGIUNTIVE WHERE  CodiceTutorial='$codiceTutorial'");
    
        $info -> execute(); 

         echo ("<table class='table table-hover bg-light w-25 m-3'>"
         . "<thead class='thead-dark'>"
         . "<tr>"
         . "<th> Speaker   </th> "
         . "<th> Link </th> "
         . "<th> Descrizione </th> "
         . "</tr></thead>");

         foreach($info as $row)
         {
             echo ("<tr>"
             . "<td>" . $row["UsernameSpeaker"] . "</td>"
             . "<td>" . $row["LinkWeb"] . "</td>"
             . "<td>" . $row["Descrizione"] . "</td>"
             . "</tr>");
         }

         echo ("</table>");


        function backPHP(){
            header("Location:VisualizzaPresentazioni.php");
        }
    ?>

</body>
</html>