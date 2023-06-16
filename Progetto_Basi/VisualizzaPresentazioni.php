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
    <title>Lista Presentazioni</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Lista Presentazioni</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <?php
     if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
        backPHP();
    }
    
        $conn = ConnSQL::DBConnection();

        $codiceSessione = $_SESSION['codiceSessione'];
        $username = $_SESSION['username'];

        stampaTabellaArticoli();
        stampaTabellaTutorial();

        function stampaTabellaArticoli()
        {
            
            $conn = ConnSQL::DBConnection();
            

            $listaPresentazioni = $conn -> prepare("SELECT * FROM PRESENTAZIONE WHERE (CodiceSessione IN (SELECT Codice 
                                                                                FROM SESSIONE 
                                                                                WHERE CodiceSessione = '$GLOBALS[codiceSessione]' AND IdProgramma IN (SELECT Id 
                                                                                        FROM REGISTRAZIONE,PROGRAMMA_GIORNALIERO
                                                                                        WHERE (UsernameUtente='$GLOBALS[username]'
                                                                                        AND REGISTRAZIONE.AcronimoConferenza = PROGRAMMA_GIORNALIERO.AcronimoConferenza
                                                                                        AND REGISTRAZIONE.AnnoEdizioneConferenza = PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza))))");
                                                            
            $listaPresentazioni -> execute();
                echo "<h3 class='m-3'>Articoli:</h3>";
                echo "<table class='table table-hover bg-light w-25 m-3'>"
                . "<thead class='thead-dark'>"
                . "<tr>"
                . "<th> Codice Sessione </th>"
                . "<th> Codice </th> "
                . "<th> Titolo </th> "
                . "<th> UsernamePresenter </th>"
                . "<th> Ora Inizio </th>"
                . "<th> Ora Fine </th>"
                . "<th> Numero Sequenza </th>"
                . "<th> NumeroPagine </th>"
                . "<th> StatoSvolgimento </th>"
                . "<th> Vedi Autori </th>"
                . "<th> Vedi lista parole chiave </th>"
                . "</tr>"
                . "</thead>";


            
            foreach ($listaPresentazioni as $row) {
                $codiceArticoloCorrente = $row["Codice"];
                $articoli = $conn -> prepare("SELECT * FROM ARTICOLO WHERE CodicePresentazione='$codiceArticoloCorrente' ");

                $articoli -> execute();
                foreach($articoli as $rowArticolo)
                {
                    echo "<tr>"
                        . "<td>" . $row["CodiceSessione"] . "</td>"
                        . "<td>" . $codiceArticoloCorrente . "</td>"
                        . "<td>" . $rowArticolo["Titolo"] . "</td>"
                        . "<td>" . $rowArticolo["UsernamePresenter"] . "</td>"
                        . "<td>" . $row["OraIni"] . "</td>"
                        . "<td>" . $row["OraFine"] . "</td>"
                        . "<td>" . $row["NumSequenza"] . "</td>"
                        . "<td>" . $rowArticolo["Numpagine"] . "</td>"
                        . "<td>" . $rowArticolo["StatoSvolgimento"] . "</td>"
                        . "<td>  <form  action='VisualizzaAutori.php' method='GET'>
                                    <input type='submit' name='codiceArticolo' value='$codiceArticoloCorrente'>
                                </form> </td>"
                        . "<td>  <form  action='VisualizzaParoleChiave.php' method='GET'>
                                    <input type='submit' name='codiceArticolo' value='$codiceArticoloCorrente'>
                                </form> </td>"
                        . "</tr>"
                        . "</thead>";
                }
            }
            
            echo ("</table>");
            $conn = null;

        }

        function stampaTabellaTutorial(){

            $conn = ConnSQL::DBConnection();
            

            $listaPresentazioni = $conn -> prepare("SELECT * FROM PRESENTAZIONE WHERE (CodiceSessione IN (SELECT Codice 
                                                                                FROM SESSIONE 
                                                                                WHERE CodiceSessione = '$GLOBALS[codiceSessione]' AND IdProgramma IN (SELECT Id 
                                                                                        FROM REGISTRAZIONE,PROGRAMMA_GIORNALIERO
                                                                                        WHERE (UsernameUtente='$GLOBALS[username]'
                                                                                        AND REGISTRAZIONE.AcronimoConferenza = PROGRAMMA_GIORNALIERO.AcronimoConferenza
                                                                                        AND REGISTRAZIONE.AnnoEdizioneConferenza = PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza))))");
                                                            
            $listaPresentazioni -> execute();
            echo "<h3 class='m-3'>Tutorial:</h3>";
            echo "<table class='table table-hover bg-light w-25 m-3'>"
            . "<thead class='thead-dark'>"
            . "<tr>"
            . "<th> Codice Sessione </th>"
            . "<th> Codice </th> "
            . "<th> Titolo </th> "
            . "<th> Orario Inizio </th>"
            . "<th> Orario fine </th>"
            . "<th> Abstract </th>"
            . "<th> Numero Sequenza </th>"
            . "<th> Vedi Speakers </th>"
            . "<th> Vedi Info Aggiuntive </th>"
            . "</tr>"
            . "</thead>";



            foreach ($listaPresentazioni as $row) {
                $codiceTutorialCorrente = $row["Codice"];
                $tutorial = $conn -> prepare("SELECT * FROM TUTORIAL WHERE CodicePresentazione='$codiceTutorialCorrente' AND CodiceSessionePresentazione = '$GLOBALS[codiceSessione]' ");
                $tutorial -> execute();

                foreach($tutorial as $rowTutorial)
                {
                    echo "<tr>"
                        . "<td>" . $row["CodiceSessione"] . "</td>"
                        . "<td>" . $codiceTutorialCorrente . "</td>"
                        . "<td>" . $rowTutorial["Titolo"] . "</td>"
                        . "<td>" . $row["OraIni"] . "</td>"
                        . "<td>" . $row["OraFine"] . "</td>"
                        . "<td>" . $rowTutorial["Abstract"] . "</td>"
                        . "<td>" . $row["NumSequenza"] . "</td>"
                        . "<td>  <form  action='VisualizzaSpeakers.php' method='GET'>
                                    <input type='submit' name='codiceTutorial' value='$rowTutorial[CodicePresentazione]'>
                                </form> </td>"
                        . "<td>  <form  action='VisualizzaInfoAggiuntive.php' method='GET'>
                                    <input type='submit' name='codiceTutorial' value='$rowTutorial[CodicePresentazione]'>
                                </form> </td>"

                        . "</tr>";
                }
            }
            echo ("</table>");

        }

        function backPHP(){
            header("Location:PresentazioniSessioni.php");
        }
    ?>
</body>
</html>