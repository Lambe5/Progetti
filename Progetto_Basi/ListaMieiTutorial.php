<!DOCTYPE html>
<?php require_once "./ConnSQL.php";
session_start(); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Miei Tutorial</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center">Miei Tutorial</h1>
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
                        <th scope="col" id="acronimoCol">CodiceTutorial</th>
                        <th scope="col" id="nomeCol">CodiceSessioneTutorial</th>
                        <th scope="col" id="acronimoCol">Titolo</th>
                        <th scope="col" id="nomeCol">Abstract</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaConfPres() ?>
                </tbody>
            </table>
    </div>

    <?php

        function stampaConfPres(){
            $conn = ConnSQL::DBConnection();
            $lista_present_sess = $conn -> prepare("SELECT * FROM PRESENTAZIONE_TUTORIAL WHERE UsernameSpeaker = '$_SESSION[username]'");
            $lista_present_sess -> execute();
            $out = "";

            while($row = $lista_present_sess->fetch(PDO::FETCH_ASSOC)){
                $out .= "<tr> <td>". $row['CodiceTutorial'] ."</td>";
                $out .= "<td>". $row['CodiceSessioneTutorial'] ."</td>";
                $tutorial = $conn -> prepare("SELECT Titolo, Abstract FROM TUTORIAL WHERE (CodicePresentazione = '$row[CodiceTutorial]') and (CodiceSessionePresentazione = '$row[CodiceSessioneTutorial]')");
                $tutorial -> execute();
                while($result = $tutorial->fetch(PDO::FETCH_ASSOC)){
                    $out .= "<td>". $result['Titolo'] ."</td>";
                    $out .= "<td>". $result['Abstract'] ."</td> </tr>";
                }
            }

            echo $out;
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }

        function backPHP(){
            header("Location:SpeakerPage.php");
        }
    ?>

</body>
</html>