<!DOCTYPE html>
<html>
<?php require_once "ConnSQL.php";
 require_once "ConnMongoDB.php";
session_start();
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Crea Sessione Page</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body class="bg-info containter">

    <div id="title" class="bg-primary border-bottom border-dark">
        <h1 class="display-4 text-center ">Crea Sessione</h1>
    </div>

    <div>
        <form action="AmministratorePage.php" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>
    <div id='flexContainer' class="row">
        <div id='firstColumn' class="col">
            <form action='creaSessione.php' method="POST">
                <div class="form-group m-3">
                    <label for="colFormLabel" class="col-form-label-lg m-3">Scegli conferenza</label>
                    <select class="custom-select m-3 text-center" name="conferenze">
                        <?php
                        stampaNomeConferenze();
                        ?>
                    </select>
                    <input id="iscrizione" type="submit" name="seleziona" class="btn btn-success m-3 w-100" value="Seleziona conferenza">
                </div>
            </form>
            <div id="tabellaConferenze" class="col">
                <h6 class="m-3">Tabella delle Sessioni</h6>
                <table id="tConferenze" class="table table-hover  bg-light m-3">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" id="codiceCol">Codice</th>
                            <th scope="col" id="idProgrammaCol">IdProgramma</th>
                            <th scope="col" id="colLinkTeams">LinkTeams</th>
                            <th scope="col" id="numPresentCol">NumPresentazioni</th>
                            <th scope="col" id="orainiCol">OraIni</th>
                            <th scope="col" id="orafineCol">OraFine</th>
                            <th scope="col" id="titoloCol">Titolo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        stampaSessioni();
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col mt-5">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['seleziona'])) {
                creaFormSessione();
            }
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['IdProgrammi'])) {
                creaSessione();
            }

            ?>
        </div>
    </div>
    <?php

    function stampaSessioni()
    {
        $conn = ConnSQL::DBConnection();

        $sql = $conn->prepare("SELECT * from sessione");
        $sql->execute();

        $out = "";

        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $out .= "<tr> <td>" . $row['Codice'] . "</td>";
                $out .= "<td>" . $row['IdProgramma'] . "</td>";
                $out .= "<td><p>" . $row['LinkTeams'] . "</p></td>";
                $out .= "<td>" . $row['NumPresentazioni'] . "</td> ";
                $out .= "<td>" . $row['OraIni'] . "</td>";
                $out .= "<td>" . $row['OraFine'] . "</td>";
                $out .= "<td>" . $row['Titolo'] . "</td>";
                $out .= "</tr>";
            }
            echo $out;
        }
        $conn = null;
    }
    function stampaNomeConferenze()
    {
        $conn = ConnSQL::DBConnection();

        $sql = $conn->prepare("SELECT Acronimo,AnnoEdizione FROM CONFERENZA WHERE Svolgimento= 'Attiva';");
        $sql->execute();

        $out = "";

        if ($sql->rowCount() > 0) {
            while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                $acronimo = $result['Acronimo'];
                $annoedizione = $result['AnnoEdizione'];
                $out .= "<option value='$acronimo $annoedizione'>Acronimo: " . $acronimo . " | AnnoEdizione: " . $annoedizione . "</option>";
            }
            echo $out;
        }
        $conn = null;
    }
    function creaFormSessione()
    {
        //ho un array con primo elemento 'Acronimo' e come secondo 'AnnoEdizione'
        $Acronimo_AnnoEdizione = explode(" ", $_POST['conferenze']);
        $result = "<h5 class='border-bottom border-dark m-3 w-75 text-center bg-secondary'>Conferenza scelta: 
                <span class='text-monospace text-center'>" . $Acronimo_AnnoEdizione[0] .
            " AnnoEdizione: " . $Acronimo_AnnoEdizione[1] . "</span><h5>";
        $result .= "<form action='creaSessione.php' class='m-3 w-75' method='POST'>";
        $result .= "<label class='col-form-label-lg'> Codice </label>";
        $result .= "<input type='text' name='codiceProg' class='form-control' placeholder='Inserisci Codice...' maxlength='10' required>";
        $result .= "<label class='col-form-label-lg'> IdProgramma </label>";
        $result .= "<select class='custom-select text-center' name='IdProgrammi' >";
        $result .=  stampaIdProgramma();
        $result .= "</select>";
        $result .= "<label class='mt-3' for='exampleFormControlTextarea1'>Link Teams</label>
                    <textarea class='form-control' rows='2' name='linkteams' placeholder='Inserisci link teams...' maxlength='100'></textarea>";
        $result .=  "<label class='mt-3' for='exampleFormControlTextarea1'>Numero Presentazioni</label>";
        $result .= "  <input type='number' id='numPresentazioni' class='w-100' name='numpresentazioni' min='1' placeholder='Inserisci un numero..' required>";
        $result .= "<label class='active mt-3' for='timeStandard'>Orario D'inizio</label>
                    <input class='form-control' id='timeStandar' name='orainizio' type='time'>";
        $result .= "<label class='active mt-3' for='timeStandard'>Orario Di Fine</label>
                    <input class='form-control' id='timeStandard' name='orafine' type='time'>";
        $result .= "<label class='active mt-3' for='titolo'>Titolo</label>
                    <input type='text' class='form-control' name='titoloProg' placeholder='Inserisci Titolo...' maxlength='100'>";
        $result .= "<input id='iscrizione'
                 type='submit' name='creaSessione' class='btn btn-success mt-5 w-100' value='Crea Sessione'>";
        $result .= "</form>";
        echo $result;
    }
    function stampaIdProgramma()
    {
        $conn = ConnSQL::DBConnection();
        $out = "";
        $sql = $conn->prepare("SELECT Id FROM programma_giornaliero;");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['Id'];
                $out .= "<option value='$id'> " . $id . "</option>";
            }
            return $out;
        }
        $conn = null;
    }
    function creaSessione()
    {
        $conn = ConnSQL::DBConnection();
        $errorMSG = "<p id='errorMSG' class='m-3 text-center font-weight-bold badge-danger'>
        ERRORE CREAZIONE: Codice già presente!!</p>";
        $codice = $_POST['codiceProg'];
        $idProgramma = $_POST['IdProgrammi'];
        $linkTeams = $_POST['linkteams'];
        $numPresentazioni = $_POST['numpresentazioni'];
        $oraIni = $_POST['orainizio'];
        $oraFine = $_POST['orafine'];
        $titolo = $_POST['titoloProg'];
        //Controllo se il codice sessione è gia presente
        $conn = ConnSQL::DBConnection();
        $sql = $conn->prepare("SELECT Codice FROM Sessione WHERE Codice='$codice';");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            echo $errorMSG;
            $conn = null;
        } else {
            $conn = ConnSQL::DBConnection();
            $sql = $conn->prepare("CALL CreaSessione(:Codice,:Id,:Link,:numpr,:OraIni,:OraFine,:Titolo)");
            try {
                $sql->execute([
                    'Codice'  => $codice,
                    'Id' => $idProgramma,
                    'Link' => $linkTeams,
                    'numpr' => $numPresentazioni,
                    'OraIni' => $oraIni,
                    'OraFine' => $oraFine,
                    'Titolo' => $titolo
                ]);

                //Aggiunto evento al logs di MongoDB
                ConnMongoDB::insertDocumentInLogs("Creata nuova sessione");

            } catch (PDOException $e) {
                echo $e;
            }
        }
        $conn = null;
    }
    ?>
</body>

</html>

<style>
    #flexContainer {
        margin: 0px;
    }

    #formCreaConf {
        padding: 0px;
    }
</style>