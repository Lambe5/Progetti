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
    <title>Sessioni</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Sessioni a cui sei Registrato</h1>
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
    $username = $_SESSION['username'];

    $listaSessioni = $conn -> prepare("SELECT Acronimo, AnnoEdizione, Codice, Titolo, OraIni, OraFine, NumPresentazioni, LinkTeams, Data
                                       FROM SESSIONE,PROGRAMMA_GIORNALIERO,REGISTRAZIONE,CONFERENZA
                                       WHERE UsernameUtente='$username'
                                        AND CONFERENZA.AnnoEdizione=PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza 
                                        AND CONFERENZA.Acronimo=PROGRAMMA_GIORNALIERO.AcronimoConferenza
                                        AND CONFERENZA.Svolgimento='ATTIVA'
                                        AND IdProgramma=Id
                                        AND REGISTRAZIONE.AcronimoConferenza=PROGRAMMA_GIORNALIERO.AcronimoConferenza
                                        AND REGISTRAZIONE.AnnoEdizioneConferenza=PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza 
                                       ");
    
    $listaSessioni -> execute();

    echo "<table class='table table-hover bg-light w-25 m-3'>"
        . "<thead class='thead-dark'>"
        . "<tr>"
        . "<th> Acronimo Conferenza </th>"
        . "<th> Anno Edizione Conferenza </th>"
        . "<th> Codice </th> "
        . "<th> Titolo </th>"
        . "<th> Data </th>"
        . "<th> Orario Inizio </th>"
        . "<th> Orario Fine </th>"
        . "<th> Numero Presentazioni </th>"
        . "<th> Link partecipazione </th>"
        . "<th> Visualizza Presentazioni </th>"
        . "</tr>"
        . "</thead>";

    foreach ($listaSessioni as $row) {
        echo "<tr>"
            . "<td>" . $row["Acronimo"] . "</td>"
            . "<td>" . $row["AnnoEdizione"] . "</td>"
            . "<td>" . $row["Codice"] . "</td>"
            . "<td>" . $row["Titolo"] . "</td>"
            . "<td>" . $row["Data"] . "</td>"
            . "<td>" . $row["OraIni"] . "</td>"
            . "<td>" . $row["OraFine"] . "</td>"
            . "<td>" . $row["NumPresentazioni"] . "</td>"
            . "<td><a href='$row[LinkTeams]'>" . $row["LinkTeams"] . "</a></td>"
            . "<td>  <form action='' method='POST'>
                        <input type='submit' name='codiceSessione' value='$row[Codice]'>
                    </form> </td>"
            . "</tr>";
    }
    echo ("</table>");


    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['codiceSessione'])) {
        mostraPresentazioni();
    }

    function mostraPresentazioni(){
        $_SESSION['codiceSessione'] = $_POST['codiceSessione'];
        header("Location: VisualizzaPresentazioni.php");
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
                                    header("Location:AmministratorePage.php");
                                    break;
                                case 'Speaker':
                                    $conn = null;
                                    header("Location:SpeakerPage.php");
                                    break;
                                case 'Presenter':
                                    $conn = null;
                                    header("Location:PresenterPage.php");
                                    break;
                            }
                            $trovato = true;
                        }
                    }
                    $conn = null;
                    if ($trovato == false)
                        header("Location:GenericoPage.php");
                } catch (PDOException $e) {
                    echo $e;
                }
            }
        }
    ?>
</body>
</html>