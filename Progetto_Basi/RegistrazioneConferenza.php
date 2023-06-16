<!DOCTYPE html>
<?php require_once "./ConnSQL.php";  ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Registrazione Conferenza</title>
</head>
<body class="bg-info">

    <?php
        session_start();
        $usernameUtente = $_SESSION['username'];
        $conn = ConnSQL::DBConnection();
        
        //mi salvo tutti gli acronimi e anni delle conferenze non presenti nella tabella di iscrizione di quell'utente
        $lista_acronimi = $conn -> prepare("SELECT Acronimo, AnnoEdizione FROM CONFERENZA WHERE Acronimo <> ALL
                                                                                (SELECT AcronimoConferenza FROM REGISTRAZIONE WHERE '$usernameUtente' = UsernameUtente) AND (Svolgimento = 'Attiva')");

        $lista_acronimi -> execute();

        $conn = null;
    ?>

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center">Registrati ad una conferenza</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <div id="conferenzeIscritto" class="m-3">
        <h3>Conferenze alle quali sei iscritto</h3>
        <table id="conferenze_iscritto" class="table table-hover bg-light w-25 m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" id="acronimoCol">Acronimo</th>
                        <th scope="col" id="annoCol">AnnoEdizione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaConferenzeIscritto() ?>
                </tbody>
            </table>
    </div>

    <div id="selzionaConf">
        <h3 class="m-3">Seleziona la conferenza alla quale ti vuoi iscrivere:</h3>
        <form action="" method="POST">
            <select name="acronimiConferenze" class="m-3">
                <option>Seleziona:</option>
                <?php
                foreach ($lista_acronimi as $row) {
                    $acronimo = $row['Acronimo'];
                    echo "<option value='$acronimo'>$acronimo</option>";
                }
                ?>
            </select>
            <input id="iscrizione" type="submit" name="iscriviti" class="btn btn-success" value="ISCRIVITI">
        </form>
    </div>

    <?php

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['iscriviti']) && $_POST['acronimiConferenze'] != "Seleziona:"){
            iscrivitiPHP();
        }

        function iscrivitiPHP(){
            // chiamo il metodo per la registrazione
            $conn = ConnSQL::DBConnection();

            $cerca_conferenza = $conn -> prepare("SELECT Acronimo, AnnoEdizione FROM CONFERENZA WHERE Svolgimento = 'Attiva'");

            $cerca_conferenza -> execute();

            foreach ($cerca_conferenza as $row) {
                if($row['Acronimo'] = $_POST['acronimiConferenze'])
                    $anno = $row['AnnoEdizione'];
            }

            $iscriviti = $conn -> prepare("CALL RegistrazioneConferenza('$GLOBALS[usernameUtente]', '$_POST[acronimiConferenze]', '$anno')");

            $iscriviti -> execute();


            header("Refresh:0");
        }

        function stampaConferenzeIscritto(){
            $conn = ConnSQL::DBConnection();

            $sql = $conn -> prepare("SELECT AcronimoConferenza, AnnoEdizioneConferenza FROM REGISTRAZIONE WHERE (UsernameUtente = '$GLOBALS[usernameUtente]')");
            $sql -> execute();

            $out = "";

            if ($sql->rowCount() > 0) {
                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                    $out .= "<tr> <td>". $row['AcronimoConferenza'] ."</td>";
                    $out .= "<td>". $row['AnnoEdizioneConferenza'] ."</td> </tr>";
                }
                echo $out;
            }
        }

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