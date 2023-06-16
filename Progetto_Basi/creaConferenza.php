<!DOCTYPE html>
<html>
<?php require_once "ConnSQL.php";
require_once "ConnMongoDB.php";
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>CreaConferenza Page</title>

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
        <h1 class="display-4 text-center "> Crea conferenza</h1>
    </div>

    <div>
        <form action="AmministratorePage.php" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>
    <div id='flexContainer' class="row">
        <div id='formCreaConf' class="col">
            <form action='' method="POST" enctype="multipart/form-data">
                <div class="form-group m-3">
                    <label for="inputAcronimo">Acronimo Conferenza:</label>
                    <input type="text" name="acronimo" class="form-control form-control-sm" placeholder="Inserisci acronimo" required>
                    <label for="inputAcronimo" class="mt-1">Anno Edizione:</label>

                    <input type="text" name="annoedizione" class="form-control form-control-sm" placeholder="Inserisci anno edizione" />
                    <label class="form-label mt-1" for="imageFile">Immagine logo:</label>
                    <input type="file" name="image" class="form-control  " id='image' accept="image/*" />

                    <label for="inputNome" class="mt-1">Nome:</label>
                    <input type="text" name="nome" class="form-control form-control-sm" placeholder="Inserisci nome">
                    <input id="iscrizione" type="submit" name="iscriviti" class="btn btn-success mt-3 w-100" value="Crea conferenza">
                </div>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                creaConferenza();
            }
            ?>
        </div>

        <div id="tabellaConferenze" class="col">
            <h6 class="m-3">Tabella delle Conferenze</h6>
            <table id="tConferenze" class="table table-hover bg-light h-100 m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" id="acronimoCol">Acronimo</th>
                        <th scope="col" id="annoEdCol">Anno edizione</th>
                        <th scope="col" id="logoCol">Logo</th>
                        <th scope="col" id="nomeCol">Nome</th>
                        <th scope="col" id="nomeSvolgimento">Svolgimento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaConferenze() ?>
                </tbody>
            </table>
        </div>

        <div id="tabellaConferenzeReg" class="col">
            <h6 class="m-3">Tabella delle Conferenze a cui è registrato</h6>
            <table id="tConferenzeReg" class="table table-hover bg-light w-75  m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" id="nomeCol">Acronimo</th>
                        <th scope="col" id="logoCol">AnnoEdizione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaConferenzeRegistrate() ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php

    function stampaConferenze()
    {
        $conn = ConnSQL::DBConnection();

        $sql = $conn->prepare("SELECT * FROM conferenza");
        $sql->execute();

        $out = "";

        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $out .= "<tr> <td>" . $row['Acronimo'] . "</td>";
                $out .= "<td>" . $row['AnnoEdizione'] . "</td>";
                $out .= "<td>" . '<img class="rounded float-left w-100" 
                                    src="data:image/*;base64,' . base64_encode($row['ImgLogo']) . '"/>' . "</td>";
                $out .= "<td>" . $row['Nome'] . "</td> ";
                $out .= "<td>" . $row['Svolgimento'] . "</td> </tr>";
            }
            echo $out;
        }
        $conn = null;
    }
    function stampaConferenzeRegistrate()
    {
        $username = $_SESSION['username'];
        $conn = ConnSQL::DBConnection();

        $sql = $conn->prepare("SELECT *
                                 FROM registrazione
                                WHERE UsernameUtente='$username';");
        $sql->execute();

        $out = "";

        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $out .= "<tr> <td>" . $row['AcronimoConferenza'] . "</td>";
                $out .= "<td>" . $row['AnnoEdizioneConferenza'] . "</td>";
            }
            echo $out;
        }
        $conn = null;
    }
    function creaConferenza()
    {
        if (
            strcmp(preg_replace('/\s+/', '', $_POST['acronimo']), "") == 0 ||
            strcmp(preg_replace('/\s+/', '', $_POST['annoedizione']), "") == 0 ||
            intval($_POST['annoedizione']) < date('Y') ||  intval($_POST['annoedizione']) > 2155
        ) {

            echo "<p id='errCrea' class='m-3 text-center font-weight-bold badge-warning'>
                            Acronimo o Anno edizione non validi
                  </p> ";
        } else {

            $acronimo = $_POST['acronimo'];
            $annoEdizione = $_POST['annoedizione'];
            $nome = $_POST['nome'];
            $isImage=true;
            if ($_FILES['image']['tmp_name'] != "") {
                $image = file_get_contents($_FILES['image']['tmp_name']);
                $typeOfFile = mime_content_type($_FILES['image']['tmp_name']);
                strcmp(substr($typeOfFile, 0, 5),"image") ==0 ? $isImage=true : $isImage=false;
            } else $image = "";


            $conn = ConnSQL::DBConnection();
            $query = $conn->prepare("SELECT Acronimo,AnnoEdizione FROM CONFERENZA 
                                WHERE Acronimo='$acronimo' AND AnnoEdizione='$annoEdizione';");
            $query->execute();
            $conn = null;

            if ($query->rowCount() > 0) {
                echo "<p id='errCrea' class='m-3 text-center font-weight-bold badge-danger'>
                        ERRORE CREA: Esiste già una conferenza uguale!
                    </p> ";
            } else if (filesize($_FILES['image']['tmp_name']) <= 1048576 && $isImage) {

                //Richiamo la procedure per creare la conferenza
                $conn = ConnSQL::DBConnection();
                $script =  $conn->prepare("CALL CreaConferenza(:acronimo,:annoEdizione,:image,:nome)");
                $script->execute([
                    'acronimo'     => $acronimo,
                    'annoEdizione' => $annoEdizione,
                    'image'        => $image,
                    'nome'         => $nome,
                ]);

                
                //Aggiunto evento al logs di MongoDB
                ConnMongoDB::insertDocumentInLogs("Creata nuova conferenza");
                $conn = null;

                //Richiamo la procedure per registrare l'amministratore alla conferenza da lui creata
                $conn = ConnSQL::DBConnection();
                $usernameAmministratore = $_SESSION['username'];
                $script =  $conn->prepare("CALL AssociaAmministratore(:usernameAmm,:AcronimoConf,:annoEdizione)");
                $script->execute([
                    'usernameAmm'  => $usernameAmministratore,
                    'AcronimoConf' => $acronimo,
                    'annoEdizione' => $annoEdizione,
                ]);

                //serve per far si che l'utente inserià il programma nella conferenza corretta
                $_SESSION['acronimo'] = $acronimo;

                $salvaInCrea_Conferenza = $conn->prepare("CALL AssociaCreazioneConf(:usernameAmm,:AcronimoConf,:annoEdizione)");
                $salvaInCrea_Conferenza->execute([
                    'usernameAmm'  => $usernameAmministratore,
                    'AcronimoConf' => $acronimo,
                    'annoEdizione' => $annoEdizione,
                ]);
                $_SESSION['indietro'] = false;
                $conn = null;
                header("Location:InserisciProgramma.php");
            } else echo "<p id='errCrea' class='m-3 text-center font-weight-bold badge-danger'>
                            ERRORE:il logo deve essere un immagine(png,jpeg,jpg,gif) e minore di 1MB
                        </p> ";
        }
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