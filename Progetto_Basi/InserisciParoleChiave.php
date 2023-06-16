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
    <title>Parole Chiave</title>
</head>
<body class="bg-info">
    <div id="title" class="bg-primary border-bottom border-dark">
        <h1 class="display-4 text-center ">Inserisci le Parole Chiavi</h1>
    </div>

    <div>
        <form action="AmministratorePage.php" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <?php

        $username = $_SESSION["username"];

        $conn = ConnSQL::DBConnection();

        try{

        $codiciArticoli = $conn -> prepare("SELECT Codice FROM PRESENTAZIONE,ARTICOLO WHERE (CodiceSessione IN (SELECT Codice 
                                FROM SESSIONE 
                                WHERE IdProgramma IN (SELECT ID  
                                                    FROM PROGRAMMA_GIORNALIERO,REGISTRAZIONE
                                                    WHERE (PROGRAMMA_GIORNALIERO.AcronimoConferenza = REGISTRAZIONE.AcronimoConferenza
                                                     AND PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza = REGISTRAZIONE.AnnoEdizioneConferenza
                                                     AND UsernameUtente = '$username'))))
                                            AND Codice=CodicePresentazione");
        $codiciArticoli -> execute();

        }catch (PDOException $e) {
            echo $e;
        }

    ?>

        <div id="tabella">
            <table id="presenter_speaker_votomedio" class="table table-hover bg-light w-25 m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">CodiceArticolo</th>
                        <th scope="col">CodiceSessione</th>
                        <th scope="col">Parola</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaParole() ?>
                </tbody>
            </table>
    </div>

    <form action="" method="POST" class="m-3">
        <span>Codice Articolo:</span>
        <select name="codiceArticolo">
            <option>Seleziona:</option>
            <?php
            foreach ($codiciArticoli as $row) {
                $codiceArticolo = $row['Codice'];
                echo ("<option value='$codiceArticolo'>$codiceArticolo</option>");
            }
            ?>
        </select>
        <input type="text" placeholder="Parola Chiave" name="parolaChiave" maxlength="20" required>
        <input type="submit" name="crea" value="CREA">
    </form>

    <?php

        function stampaParole(){
            $conn = ConnSQL::DBConnection();

            $paroleChiavi = $conn -> prepare("SELECT * FROM PAROLA_CHIAVE WHERE (CodiceSessioneArticolo IN (SELECT Codice 
                                    FROM SESSIONE 
                                    WHERE IdProgramma IN (SELECT ID  
                                                        FROM PROGRAMMA_GIORNALIERO,REGISTRAZIONE
                                                        WHERE (PROGRAMMA_GIORNALIERO.AcronimoConferenza = REGISTRAZIONE.AcronimoConferenza
                                                         AND PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza = REGISTRAZIONE.AnnoEdizioneConferenza
                                                         AND UsernameUtente = '$GLOBALS[username]'))))");

            $paroleChiavi -> execute();


            $out = "";

            if ($paroleChiavi->rowCount() > 0) {
                while($row = $paroleChiavi->fetch(PDO::FETCH_ASSOC)){
                    $out .= "<tr> <td>". $row['CodiceArticolo'] ."</td>";
                    $out .= "<td>". $row['CodiceSessioneArticolo'] ."</td>";
                    $out .= "<td>". $row['Parola'] ."</td></tr>";
                }
                echo $out;
            }

            $conn = null;
        }


        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['crea'])) {
            creaParolaChiave();
        }

        function creaParolaChiave(){
            $conn = ConnSQL::DBConnection();
            try {
                $parola = $_POST["parolaChiave"];
                $codiceArticolo = $_POST["codiceArticolo"];


                $codiciSessioni = $conn -> prepare("SELECT CodiceSessione FROM PRESENTAZIONE,ARTICOLO WHERE (CodiceSessione IN (SELECT Codice 
                                                        FROM SESSIONE 
                                                        WHERE IdProgramma IN (SELECT ID  
                                                                            FROM PROGRAMMA_GIORNALIERO,REGISTRAZIONE
                                                                            WHERE (PROGRAMMA_GIORNALIERO.AcronimoConferenza = REGISTRAZIONE.AcronimoConferenza
                                                                            AND PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza = REGISTRAZIONE.AnnoEdizioneConferenza
                                                                            AND UsernameUtente = '$GLOBALS[username]'))))
                                                        AND (Codice='$codiceArticolo')");

                $codiciSessioni -> execute();
                

                $codiceSessione = "";
                foreach ($codiciSessioni as $row) {
                    $codiceSessione = $row['CodiceSessione'];
                }
               
                $conn -> query("CALL InserisciParolaChiave('$codiceArticolo', '$codiceSessione', '$parola')");
                $conn = null;
                //Aggiunto evento al logs di MongoDB
                ConnMongoDB::insertDocumentInLogs("Creata una nuova parola chiave");
                header("Refresh:0");

            } catch (PDOException $e) {
                echo $e;
            }
        }
    ?>

</body>
</html>