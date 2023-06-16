<!DOCTYPE html>
<?php require_once "ConnSQL.php";
 require_once "ConnMongoDB.php";
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Valutazione</title>
</head>
<body class="bg-info">
    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Valutazione</h1>
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


        function backPHP(){
            header("Location:AmministratorePage.php");
        }


        $username=$_SESSION['username'];

        $conn = ConnSQL::DBConnection();

        $listaPresentazioni = $conn -> prepare("SELECT Codice FROM PRESENTAZIONE WHERE (CodiceSessione IN (SELECT Codice 
                                  FROM SESSIONE 
                                  WHERE IdProgramma IN (SELECT Id  
                                                        FROM PROGRAMMA_GIORNALIERO, REGISTRAZIONE
                                                        WHERE (PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza = REGISTRAZIONE.AnnoEdizioneConferenza
                                                         AND PROGRAMMA_GIORNALIERO.AcronimoConferenza = REGISTRAZIONE.AcronimoConferenza
                                                         AND UsernameUtente = '$username'))))
                                AND Codice NOT IN (SELECT CodicePresentazione 
                                                    FROM VALUTAZIONE
                                                    WHERE UsernameAmministratore = '$username')");


        $listaPresentazioni -> execute();
    ?>

    <form action="" method="post" class="m-3">
        <select name="codicePresentazione">
            <option>Scegli Presentazione:</option>
            <?php
            foreach ($listaPresentazioni as $row) {
                $codicePresentazione = $row['Codice'];
                echo ("<option value='$codicePresentazione'>$codicePresentazione</option>");
            }
            ?>
        </select>
        <input type="number" placeholder="Voto " name="Voto" max ="10" min="0" required>
        <input type="text" placeholder="Note " name="Note" maxlength="50" required>
        <input type="submit" name="inserisci" value="INSERISCI">
    </form>

    <?php
    $conn = ConnSQL::DBConnection();
    try {
        
        $voti = $conn -> prepare("SELECT UsernameAmministratore, CodicePresentazione, CodiceSessionePresentazione, Voto, Note
                                    FROM VALUTAZIONE
                                    WHERE ( UsernameAmministratore= '$username')");
        $voti -> execute();
    } catch (PDOException $e) {
        echo $e;
    }

    echo "<table class='table table-hover bg-light w-75 m-3'>"
    . "<thead class='thead-dark'>"
    . "<tr>"
    . "<th> Amministratore </th>"
    . "<th> Sessione </th>"
    . "<th> Presentazione </th>"
    . "<th> Voto </th>"
    . "<th> Note </th>"
    . "</tr> </thead>";

    foreach ($voti as $row) {
    echo "<tr>"
        . "<td>" . $row["UsernameAmministratore"] . "</td>"
        . "<td>" . $row["CodiceSessionePresentazione"] . "</td>"
        . "<td>" . $row["CodicePresentazione"] . "</td>"
        . "<td>" . $row["Voto"] . "</td>"
        . "<td>" . $row["Note"] . "</td>"
        . "</tr>";
    }
    echo ("</table>");


    if (isset($_POST["inserisci"])) 
    {
        if ($_POST["inserisci"] == "INSERISCI") {
            InserisciVoto();
        } 
    }


    function InserisciVoto(){
        $conn = ConnSQL::DBConnection();
        try {
            $codicePresentazione = $_POST["codicePresentazione"];
            $voto=$_POST["Voto"];
            $note=$_POST["Note"];
            $nome=$_SESSION['username'];

            $codiceSessione = $conn -> prepare("SELECT CodiceSessione FROM PRESENTAZIONE, SESSIONE WHERE CodiceSessione = SESSIONE.Codice AND PRESENTAZIONE.Codice = '$codicePresentazione'");
            $codiceSessione -> execute();

            $codiceSess = "";
            foreach ($codiceSessione as $row) {
                $codiceSess = $row['CodiceSessione'];
            }

            $conn -> query("CALL inserisciValutazione('$nome','$codicePresentazione', '$codiceSess', '$voto', '$note')"); 

            //Aggiunto evento al logs di MongoDB
            ConnMongoDB::insertDocumentInLogs("Inserita una nuova valutazione");
            header("Refresh:0");

        } catch (PDOException $e) {
            echo $e;
        }
    }
?>
</body>
</html>