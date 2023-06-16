<!DOCTYPE html>
<?php 
require_once "./ConnSQL.php";
require_once "./ConnMongoDB.php";
 ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Aggiungi Risorse</title>
</head>
<body class="bg-info">
    <?php
        session_start();
        $usernameUtente = $_SESSION["username"];
        $conn = ConnSQL::DBConnection();

        //cerco tutte le presentazioni sostenute da questo utente, con questo metodo si suppone che uno possa inserire delle risorse anche dopo la scadenza della presentazione e sessione
        $lista_codici = $conn -> prepare("SELECT CodiceTutorial, CodiceSessioneTutorial FROM PRESENTAZIONE_TUTORIAL WHERE (UsernameSpeaker = '$usernameUtente')");        

        $lista_codici -> execute();
    ?>


    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center">Aggiungi un Link e una Descrizione a un Tutorial</h1>
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
                        <th scope="col" id="codicePresCol">CodiceTutorial</th>
                        <th scope="col" id="codiceSesCol">CodiceSessioneTutorial</th>
                        <th scope="col" id="link">LinkWeb</th>
                        <th scope="col" id="descrizione">Descrizione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaCodici() ?>
                </tbody>
            </table>
        </div>


    <?php

        function stampaCodici(){
              
            $lista_codici = $GLOBALS['conn'] -> prepare("SELECT INFO_AGGIUNTIVE.CodiceTutorial, INFO_AGGIUNTIVE.CodiceSessioneTutorial, LinkWeb, Descrizione
            FROM PRESENTAZIONE_TUTORIAL, INFO_AGGIUNTIVE
            WHERE (PRESENTAZIONE_TUTORIAL.UsernameSpeaker = '$GLOBALS[usernameUtente]')
            AND (INFO_AGGIUNTIVE.CodiceTutorial = PRESENTAZIONE_TUTORIAL.CodiceTutorial)");    

            $lista_codici -> execute();


        $out = "";

        while($row = $lista_codici->fetch(PDO::FETCH_ASSOC)){
            $out .= "<tr> <td>". $row['CodiceTutorial'] ."</td>";
            $out .= "<td>". $row['CodiceSessioneTutorial'] ."</td>";
            $out .= "<td><a href='$row[LinkWeb]'>". $row['LinkWeb'] ."</a></td>";
            $out .= "<td>". $row['Descrizione'] ."</td> </tr>";
        }
        echo $out;
        $GLOBALS['conn'] = null;
        }
    ?>

    <form action="" method="POST" class="m-3">
        <select name="codiciSessioni">
            <option>Seleziona:</option>
            <?php
            foreach ($lista_codici as $row) {
                $codice = "Tutorial: " . $row['CodiceTutorial'] . ", Sessione: " . $row['CodiceSessioneTutorial'];
                echo "<option value='$codice'>$codice</option>";
            }
            ?>
        </select>
        <input type="textarea" name="link" placeholder="Inserisci link" maxlength='100'>
        <input type="textarea" name="descrizione" placeholder="Inserisci descrizione" maxlength='500'>
        <input type="submit" name="aggiungi" class="btn btn-success m-3" value="AGGIUNGI">
    </form>


    <?php

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['codiciSessioni']) and $_POST['codiciSessioni'] != "Seleziona:"){
            inserisciPHP();
        }

        function inserisciPHP(){

            //devo controllare se il codice del tutorial è presente già in INFO_AGGIUNTIVE, se non esiste chiamo una insert per inserirlo
            //altrimenti chiamo la modifica

            $conn = ConnSQL::DBConnection();
            $arr = explode(",", $_POST['codiciSessioni']);
            
            $arr_codice_p = explode(" ", $arr[0]);

            $arr_codice_s = explode(" ", $arr[1]);

            $vediSeAppartine = $conn -> prepare("SELECT CodiceTutorial, CodiceSessioneTutorial FROM INFO_AGGIUNTIVE
             WHERE CodiceTutorial = '$arr_codice_p[1]' AND CodiceSessioneTutorial = '$arr_codice_s[2]' AND UsernameSpeaker = '$GLOBALS[usernameUtente]'");


            $vediSeAppartine -> execute();
            
            $appartine = false;

            if ($vediSeAppartine->rowCount() > 0) {
                 $appartine = true;
            }


            if($appartine){
                if($_POST['link'] != ""){
                    $inserisci_link = $conn -> prepare("CALL ModificaLinkInfoAggiuntive('$GLOBALS[usernameUtente]', '$arr_codice_p[1]', '$arr_codice_s[2]', '$_POST[link]')");
            
                    $inserisci_link -> execute();
                }
                
                if($_POST['descrizione'] != ""){
                    $inserisci_descrizione = $conn -> prepare("CALL ModificaDescrizioneInfoAggiuntive('$GLOBALS[usernameUtente]', '$arr_codice_p[1]', '$arr_codice_s[2]', '$_POST[descrizione]')");
            
                    $inserisci_descrizione -> execute();
                }
                
            } else {
                $inserisci_risorse = $conn -> prepare("CALL CreaInfoAggiuntive('$GLOBALS[usernameUtente]', '$arr_codice_p[1]', '$arr_codice_s[2]', '$_POST[link]', '$_POST[descrizione]')");
            
                $inserisci_risorse -> execute();
            }


            //Dato che c'è stato un inserimento , vado ad inserirlo nella collezione 'logs' di mongoDB
             ConnMongoDB::insertDocumentInLogs("Informazioni aggiuntive inserite");

            $conn = null;

            header("Refresh:0");
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