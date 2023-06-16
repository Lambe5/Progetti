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
    <title>Presentazioni Preferite</title>
</head>
<body class="bg-info">
    <?php
        session_start();
        $conn = ConnSQL::DBConnection();
        $username = $_SESSION["username"];

        $codiciAccessibili = $conn -> prepare("SELECT Codice, CodiceSessione
                                            FROM PRESENTAZIONE
                                            WHERE (CodiceSessione IN
                                                (SELECT Codice 
                                                FROM SESSIONE 
                                                WHERE IdProgramma IN (SELECT ID  
                                                                    FROM PROGRAMMA_GIORNALIERO,REGISTRAZIONE,CONFERENZA
                                                                    WHERE (REGISTRAZIONE.AcronimoConferenza=PROGRAMMA_GIORNALIERO.AcronimoConferenza 
                                                                    AND REGISTRAZIONE.AnnoEdizioneConferenza=PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza
                                                                    AND UsernameUtente='$username'
                                                                    AND CONFERENZA.AnnoEdizione=PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza 
                                                                    AND CONFERENZA.Acronimo=PROGRAMMA_GIORNALIERO.AcronimoConferenza
                                                                    AND CONFERENZA.Svolgimento='ATTIVA'))))
                                            AND Codice NOT IN (SELECT CodicePresentazione 
                                            FROM LISTA_PRESENTAZIONI_FAVORITE
                                            WHERE UsernameUtente='$username')");
                                            
        $codiciAccessibili -> execute();

    ?>


    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center">Lista presentazioni preferite</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <div id="tabella">
            <table class="table table-hover bg-light w-25 m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" id="codicePresCol">CodicePresentazione</th>
                        <th scope="col" id="codiceSesCol">CodiceSessionePresentazione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaCodici() ?>
                </tbody>
            </table>
        </div>


    <?php
        function stampaCodici(){
        $lista_preferite = $GLOBALS['conn'] -> prepare("SELECT * FROM LISTA_PRESENTAZIONI_FAVORITE WHERE UsernameUtente = '$GLOBALS[username]'");
        $lista_preferite -> execute();

        $out = "";

        // le rendo visibili
        while($row = $lista_preferite->fetch(PDO::FETCH_ASSOC)){
            $out .= "<tr> <td>". $row['CodicePresentazione'] ."</td>";
            $out .= "<td>". $row['CodiceSessionePresentazione'] ."</td> </tr>";
        }
        echo $out;
        $GLOBALS['conn'] = null;
        }
    ?>

    <form action="" method="POST" class="m-3">
        <select name="codiciSessioni">
            <option value="seleziona">Seleziona:</option>
            <?php
            foreach ($codiciAccessibili as $row) {
                $codice = "Presentazione: " . $row['Codice'] . ", Sessione: " . $row['CodiceSessione'];
                echo "<option value='$codice'>$codice</option>";
            }
            ?>
        </select>
        <input type="submit" name="aggiungi" class="btn btn-success m-3" value="AGGIUNGI">
    </form>

    <?php

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['codiciSessioni'])){
            inserisciPHP();
        }

        function inserisciPHP(){

            try{
                
            $conn = ConnSQL::DBConnection();
            if($_POST['codiciSessioni'] == "seleziona"){
                echo "<p>Seleziona una presentazione</p>";
            } else {
                $arr = explode(",", $_POST['codiciSessioni']);
           
                $arr_codice_p = explode(" ", $arr[0]);
    
                $arr_codice_s = explode(" ", $arr[1]);
                $inserisci_presentazione = $conn -> prepare("CALL InserisciPresentazionePreferitaInLista('$GLOBALS[username]', '$arr_codice_p[1]', '$arr_codice_s[2]')");
                
                $inserisci_presentazione -> execute();
    
    
                header("Refresh:0");
            }
            }catch(PDOException $e){
                echo $e;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }
    
        function backPHP(){
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