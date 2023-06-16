<!DOCTYPE html>
<?php 
require_once "./ConnSQL.php";
 ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>AllChat</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Tutte le Chat</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>
    
        
    <?php
        session_start();
        $username=$_SESSION["username"];
        $conn = ConnSQL::DBConnection();
        try{
            $sessioni = $conn -> prepare("SELECT Codice,Titolo,OraIni,OraFine,PROGRAMMA_GIORNALIERO.AcronimoConferenza,PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza,Data
                            FROM SESSIONE,PROGRAMMA_GIORNALIERO,REGISTRAZIONE
                            WHERE IdProgramma = Id AND PROGRAMMA_GIORNALIERO.AcronimoConferenza = REGISTRAZIONE.AcronimoConferenza
                            AND PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza = REGISTRAZIONE.AnnoEdizioneConferenza
                            AND UsernameUtente = '$username'");

        $sessioni -> execute();
        }catch(PDOException $e){
            echo $e;
        }

    ?>

    <?php   
   
    echo "<table class='table table-hover bg-light w-25 m-3'>"
    . "<tr class='thead-dark'>"
    . "<th> Codice </th> "
    . "<th> AcronimoConferenza </th>"
    . "<th> AnnoEdizioneConferenza </th>"
    . "<th> Titolo </th>"
    . "<th> OraInizio </th>"
    . "<th> OraFine </th>"
    . "<th> Data </th>"
    . "<th> Chat </th>"
    . "</tr>";

    foreach ($sessioni as $row) {
    echo "<tr>"
        . "<td>" . $row["Codice"] . "</td>"
        . "<td>" . $row["AcronimoConferenza"] . "</td>"
        . "<td>" . $row["AnnoEdizioneConferenza"] . "</td>"
        . "<td>" . $row["Titolo"] . "</td>"
        . "<td>" . $row["OraIni"] . "</td>"
        . "<td>" . $row["OraFine"] . "</td>"
        . "<td>" . $row["Data"] . "</td>"
        . "<td>  
                <form  action='ChatSessione.php' method='GET'>
                 <input type='hidden' name='data' value='$row[Data]'>
                 <input type='hidden' name='codiceSessione' value='$row[Codice]'>
                 <input type='submit' name='inviaChat' value='CHAT'>
                 </form> 
        </td>"
        . "</tr>";
    }
    echo ("</table>");


    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
        backPHP();
    }

    function backPHP(){
        //carcio la pagina corretta in base al tipo di utente
        $conn = ConnSQL::DBConnection();

        $sqlTipoUtente = $conn->prepare("SELECT * FROM username_tipoutente");
            $sqlTipoUtente->execute();
            $trovato = false;
            if ($sqlTipoUtente->rowCount() > 0) {

                try {
                    while ($result = $sqlTipoUtente->fetch(PDO::FETCH_ASSOC)) {

                        if ($result['Username'] == $_SESSION['username']) {

                            switch ($result['Tipo']) {
                                case 'Amministratore':
                                    $conn = null;
                                    echo "<script type='text/javascript'>
                                    window.location.href = './AmministratorePage.php';
                                    </script>";
                                    break;
                                case 'Speaker':
                                    $conn = null;
                                    echo "<script type='text/javascript'>
                                    window.location.href = './SpeakerPage.php';
                                    </script>";
                                    break;
                                case 'Presenter':
                                    $conn = null;
                                    echo "<script type='text/javascript'>
                                    window.location.href = './PresenterPage.php';
                                    </script>";
                                    break;
                            }
                            $trovato = true;
                        }
                    }
                    $conn = null;
                    if ($trovato == false){
                        header("Location:GenericoPage.php");
                        echo "<script type='text/javascript'>
                                    window.location.href = './GenericoPage.php';
                                    </script>";
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
            }
        }
 ?>

</body>
</html>