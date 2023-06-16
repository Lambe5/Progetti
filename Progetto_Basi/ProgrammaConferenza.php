<!DOCTYPE html>
<?php require_once "./ConnSQL.php";
session_start(); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Conferenze</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center">Programmi</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>
    
    <div id="tabella">
            <table id="presenter_speaker_votomedio" class="table table-hover bg-light w-25 m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" id="acronimoCol">Acronimo</th>
                        <th scope="col" id="nomeCol">Id</th>
                        <th scope="col" id="logoCol">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaProgrammi() ?>
                </tbody>
            </table>
    </div>

    

    <?php

        function stampaProgrammi(){

            $acronimo = $_GET['acronimo'];
            $conn = ConnSQL::DBConnection();

            $sql = $conn -> prepare("SELECT AcronimoConferenza, Id, Data FROM PROGRAMMA_GIORNALIERO WHERE AcronimoConferenza = '$acronimo'");
            $sql -> execute();

            $out = "";

            if ($sql->rowCount() > 0) {
                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                    $out .= "<tr> <td>". $row['AcronimoConferenza'] ."</td>";
                    $out .= "<td>". $row['Id'] ."</td>";
                    $out .= "<td>". $row['Data'] ."</td></tr>";
                }
                echo $out;
            }

            $conn = null;
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }

        function backPHP(){
            header("Location: VisualizzaConferenze.php");    
        }   
    ?>

</body>
</html>