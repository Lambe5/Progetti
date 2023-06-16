<!DOCTYPE html>
<?php 
require_once "./ConnSQL.php";
require_once "./ConnMongoDB.php";
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Chat Sessione</title>
</head>
<body class="bg-info">
    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Chat di Sessione</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <form action="" method="POST" id="messForm">
        <input type="text" placeholder="Messaggio" name="messaggio" maxlength='500' required>
        <input type="submit" name="inp" value="INVIA">
    </form>

    
<div id="contenitore" class="col-sm-12 my-auto">

    <?php

    if (isset($_POST["inp"])) {
        inserisciMessaggio();
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
        backPHP();
    }

    stampaChat();

    function cercaTimestamp(){
        $conn = ConnSQL::DBConnection();

        $codiceSessione = $_GET["codiceSessione"];
        $currentTimestamp = 0;
        //select del max timestamp corrispondente a questa sessione
        $timestampMax = $conn -> prepare("SELECT max(Timestamp) as MaxTimestamp FROM MESSAGGIO WHERE CodiceSessione='$codiceSessione'");
        $timestampMax -> execute();
        //se esiste faccio che quello attuale è +=1
        $row = $timestampMax->fetch();
        if($row['MaxTimestamp'] != null || $row['MaxTimestamp'] == 0){
            $currentTimestamp = $row['MaxTimestamp'] + 1;
        }
        $conn = null;

        return $currentTimestamp;

    }
    
    function stampaChat()
    {
        try {
            //mi prendo i messaggi relativi alla sessione corrente
            $conn = ConnSQL::DBConnection();
            $codiceSessione = $_GET["codiceSessione"];

            $messaggi = $conn -> prepare("SELECT UsernameUtente,Testo, DataInserimento, Timestamp
                                        FROM MESSAGGIO 
                                        WHERE CodiceSessione='$codiceSessione'
                                        ORDER BY Timestamp DESC");

            $messaggi -> execute();

            
            foreach ($messaggi as $row) {
                echo "<div id='mess'>
                <p>[" .  $row['DataInserimento'] . "&nbsp" . $row["UsernameUtente"] . "]" .
                "<p>" . $row["Testo"] ."</p>
                </div>";
           
            }
            
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function inserisciMessaggio()
    {


        $conn = ConnSQL::DBConnection();

        if(isTerminata())
        {

        try {
            $username = $_SESSION['username'];
            $messaggio = $_POST["messaggio"];
            $codSessione = $_GET["codiceSessione"];
            $data = date('Y-m-d H:i:s', time());
            $timestamp = cercaTimestamp();

            $conn->query("CALL inserisciMessaggio('$codSessione','$timestamp','$username','$messaggio','$data')");

            //Dato che c'è stato un inserimento , vado ad inserirlo nella collezione 'logs' di mongoDB
            ConnMongoDB::insertDocumentInLogs("Inserito un nuovo messaggio");

        } catch (PDOException $e) {
            echo $e;
        }
        
    }
    else{
        echo "<h3 id='errore'>La sessione non è ancora aperta. Non è possibili inviare nuovi messaggi.</h3>";
        echo "<style>
                #errore{color: red;}
            </style>";
    }

    }

    function isTerminata()
    {
        try {
            $conn = ConnSQL::DBConnection();
            $codiceSessione = $_GET["codiceSessione"];
            $dataSessione=$_GET["data"];

            $ora = $conn -> prepare("SELECT OraIni,OraFine FROM SESSIONE WHERE Codice ='$codiceSessione'");
            $ora -> execute();
            $row = $ora->fetch();

            $oraInizioSessione = $row["OraIni"];
            $oraFineSessione = $row["OraFine"];

            $oraNow = date("H:i:s");
            $dataNow = date("Y-m-d");

            if($oraNow > $oraInizioSessione && $oraNow < $oraFineSessione && $dataNow == $dataSessione)
            {
                return true;
            }

            else return false;

        } catch (PDOException $e) {
           echo $e;
        }
    }
    
        function backPHP(){
                header("Location:AllChat.php");
            }
    ?>

</div>

<style>
    #contenitore{
        margin: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    #mess{
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        margin: 5px;
        width: 30%;
    }

    #messForm{
        position: -webkit-sticky; /* Safari */
        position: sticky;
        top: 0;
        margin: 20px;
    }
</style>
</body>
</html>