<!DOCTYPE html>
<?php
require_once "./ConnSQL.php";
require_once "ConnMongoDB.php";
session_start();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Crea Presentazione</title>
</head>

<body class="bg-info">
    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center">Aggiungi Presentazione</h1>
    </div>

    <div>
        <form action="" method="POST">
            <input id="btntest" type="button" value="BACK" 
                onclick="window.location.href = '<?php echo backPHP(); ?>'" /> 
        </form> 
    </div>

    <?php
        //ottengo le conferenze alle quali sono registrato e quindi che posso gestire
        $conn = ConnSQL::DBConnection();
        try {
            $username = $_SESSION["username"];

            $sessioni = $conn -> prepare("SELECT Codice, OraIni, OraFine, Titolo 
                                            FROM SESSIONE, PROGRAMMA_GIORNALIERO, REGISTRAZIONE
                                           WHERE IdProgramma = Id
                                             AND REGISTRAZIONE.AnnoEdizioneConferenza = PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza
                                             AND REGISTRAZIONE.AcronimoConferenza = PROGRAMMA_GIORNALIERO.AcronimoConferenza
                                             AND UsernameUtente = '$username'");
            $sessioni -> execute();

        } catch (PDOException $e) {
           echo $e;
        }

        echo ("<table class='table table-hover bg-light w-25 m-3'>"
        . "<thead class='thead-dark'><tr>"
        . "<th>Codice</th> "
        . "<th>Titolo</th>"
        . "<th>Ora Inizio</th>"
        . "<th>Ora Fine</th>"
        . "</tr>");

        foreach ($sessioni as $row) {
            echo ("<tr>"
                . "<td>" . $row["Codice"] . "</td>"
                . "<td>" . $row["Titolo"] . "</td>"
                . "<td>" . $row["OraIni"] . "</td>"
                . "<td>" . $row["OraFine"] . "</td>"
                . "</tr>");
        }
        echo ("</table>");

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }
    
        function backPHP(){
            return './AmministratorePage.php';
        }   
    ?>

    <form action="" method="POST" class="m-3" enctype=multipart/form-data>
        <input type="text" placeholder="Codice Presentazione" name="codice" maxlength="7" required>
        <span>Codice Sessione:</span>
        <select name="codiceSessione">
            <option>Seleziona:</option>
            <?php
            $sessioni -> execute();
            foreach ($sessioni as $row) {
                $codiceSessione = $row['Codice'];
                echo ("<option value='$codiceSessione'>$codiceSessione</option>");
            }
            ?>
        </select>
        <span>Ora Inizio:</span>
        <input type="time" name="oraInizio" required>
        <span>Ora Fine:</span>
        <input type="time" name="oraFine" required>
        <span>Numero di sequenza:</span>
        <input type="number" name="nSequenza" placeholder="Numero sequenza" required>
        <span>Tipo:</span>
        <select id="tipoPresentazione" name="tipoPresentazione" onchange="visualizzaInput()">
            <option value="scegli">Scegli:</option>
            <option value="Tutorial">Tutorial</option>
            <option value="Articolo">Articolo</option>
        </select>
        <br>
        <label class="tutorial" for="titolo">Titolo:</label>
        <input type="text" id="titoloT" name="titoloT" class="tutorial" placeholder="Inserisci Titolo">
        <label class="tutorial" for="Abstract">Inserisci Abstract:</label>
        <input type="text" alt="Submit" id="abstract" name="abstract" class="tutorial" placeholder="Inserisci Abstract">

        <label class="articolo" for="titolo">Titolo</label>
        <input type="text" id="titoloA" name="titoloA" class="articolo" placeholder="Inserisci Titolo">
        <label class="articolo" for="nPagine">Inserisci Numero Pagine</label>
        <input type="number" alt="Submit" id="nPagine" name="nPagine" class="articolo" placeholder="Inserisci nPagine">
        <label class="articolo" for="PDF">File PDF</label>
        <input type="file" id="pdf" name="pdf" class="articolo" placeholder="Inserisci pdf" accept="application/pdf">
        <label class="articolo">Inserisci Autori:</label>
        <div id="selAutori" class="articolo">
            <label>Numero autori:</label>
            <select name="numautori" id="numeroAutori" class="elFormAutore" onchange="mostraautori()">
                <option>Seleziona</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <select name="autori" id="listaAutoriH">
                <option>Seleziona:</option>
                <?php
                    stampaAutori();
                ?>
            </select>
            <div id="listaSelect"></div>
        </div>
        <br>
        <input id="btnCrea" type="submit" name="crea" value="CREA">
    </form>

    <?php

    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['crea'])){
        eseguiInsert();
    }

        function stampaAutori(){

            $conn = ConnSQL::DBConnection();

            $sql = $conn->prepare("SELECT ID
                                     FROM AUTORE");
            $sql->execute();

            $out = "";

            if ($sql->rowCount() > 0) {
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    $out .= '<option value = "' . $row["ID"] . '">' . $row["ID"] . '</option>';
                }
            }

            $out .= '<option value = "nuovo">+ Nuovo autore ...</option>';

            echo $out;
            
            $conn = null;
        }

        function eseguiInsert(){

            $conn = ConnSQL::DBConnection();

            // ottengo le variabili
            $codice = $_POST["codice"];
            $codiceSessione  = $_POST["codiceSessione"];
            $oraInizio = $_POST["oraInizio"];
            $oraFine = $_POST["oraFine"];
            $nSequenza = $_POST["nSequenza"];
            $tipo = $_POST["tipoPresentazione"];

            // controllo che non esista gia una presentazione
            $select = $conn -> prepare("SELECT Codice
                                          FROM PRESENTAZIONE
                                         WHERE Codice = '$codice'
                                           and CodiceSessione = '$codiceSessione'");
            $select-> execute();

            if($select->rowCount() == 0){

                // se e' un articolo
                if($tipo == "Articolo"){
                    $titolo = $_POST["titoloA"];
                    $nPagine = $_POST["nPagine"];
  
                    if ($_FILES['pdf']['tmp_name'] != "") {
                        
                        $pdf = file_get_contents($_FILES['pdf']['tmp_name']);
                    } else{
                        $pdf = null;
                    }

                    $sqlpresentazione = $conn->prepare("CALL CreaNuovoArticolo(:codice, :codiceSessione, :nSequenza, :oraInizio, :oraFine, :nPagine, :pdf, :titolo)");
                    $sqlpresentazione->execute([
                        ':codice' => $codice,
                        ':codiceSessione' => $codiceSessione,
                        ':nSequenza' => $nSequenza,
                        ':oraInizio' => $oraInizio,
                        ':oraFine' => $oraFine,
                        ':nPagine' => $nPagine,
                        ':pdf' => $pdf,
                        ':titolo' => $titolo
                    ]);

                    $numeroAutori = $_POST["numautori"];

                    for($i=0; $i < $numeroAutori; $i++){

                        // se l'autore non esiste lo creo
                        $autore = $_POST["autori_".$i];
                        if($autore == "nuovo"){
                            //autore non esiste
                            $id = $_POST["id_".$i];
                            $nome = $_POST["nome_".$i];
                            $cognome = $_POST["cognome_".$i];

                            $sqlnuovoAutore = $conn -> prepare("CALL InserisciAutore('$id', '$nome', '$cognome', '$codice', '$codiceSessione')");
                            $sqlnuovoAutore -> execute();
                        } else{
                            // autore gia esistente
                            $id = $autore;
                            // associa autori all'articolo
                            $sqlListaAutori = $conn->prepare("CALL InserisciListaAutori('$id', '$codice', '$codiceSessione')");
                            $sqlListaAutori->execute();
                        }
                    }
                    
                } else if($tipo == "Tutorial"){

                    // crea tutorial
                    $titolo = $_POST["titoloT"];
                    $abstract = $_POST["abstract"];

                    $sqlpresentazione = $conn->prepare("CALL CreaNuovoTutorial(:codice, :codiceSessione, :nSequenza, :oraInizio, :oraFine, :titolo, :abstract)");
                    $sqlpresentazione->execute([
                        ':codice' => $codice,
                        ':codiceSessione' => $codiceSessione,
                        ':nSequenza' => $nSequenza,
                        ':oraInizio' => $oraInizio,
                        ':oraFine' => $oraFine,
                        ':titolo' => $titolo,
                        ':abstract' => $abstract
                    ]);

                    // rimanda alla pagina di associazione speaker tutorial
                    $_SESSION["bool"] = true;
                    $_SESSION["preSessione"] = $codiceSessione;
                    $_SESSION["preTutorial"] = $codice;

                    echo '<script> window.location.href = "./AssociaSpeakerTutorial.php";</script>';

                }
                //Aggiunto evento al logs di MongoDB
                ConnMongoDB::insertDocumentInLogs("Creata una nuova presentazione");
            } // esiste gia una presentazione con quel codice
        }
    ?>

    <style>
    .tutorial {
        display: none;
        margin: 5px;
    }

    .articolo {
        display: none;
        margin: 5px;
    }

    #btnCrea,
    #listaAutoriH {
        display: none;
    }

    #newAutore {
        border: 1px solid grey;
        display: none;
    }

    .formAutore {
        display: none;
        flex-direction: column;
        width: 50%;
        border: 2px solid blue;
        padding: 10px;
    }
    </style>

    <script type="text/javascript">
    function visualizzaInput() {
        //in base al tipo di valore presente rendo visibli i vari pannelli del form
        var presentazione = document.getElementById('tipoPresentazione').value;

        var tutorial = document.getElementsByClassName('tutorial');
        var articolo = document.getElementsByClassName('articolo');
        var btn = document.getElementById('btnCrea');
        btn.style.display = 'block';

        if (presentazione == "Tutorial") {
            for (var i = 0; i < tutorial.length; i++) {
                tutorial[i].style.display = 'block';
            }
            for (var i = 0; i < articolo.length; i++) {
                articolo[i].style.display = 'none';
            }

            ////////////
            // var array = 
            // echo $array?>;
            // var sele = document.getElementById("listaSpeaker");

            // var option = document.createElement("option");
            // option.innerHTML = "seeeee";
            // sele.appendChild(option);



        } else if (presentazione == "Articolo") {
            for (var i = 0; i < tutorial.length; i++) {
                tutorial[i].style.display = 'none';
            }
            for (var i = 0; i < articolo.length; i++) {
                articolo[i].style.display = 'block';
            }

            var elementi = document.getElementsByClassName('elFormAutore');
            for (var j = 0; j < elementi.length; j++) {
                elementi[j].disabled = false;
            }

        } else {
            for (var i = 0; i < tutorial.length; i++) {
                tutorial[i].style.display = 'none';
            }
            for (var i = 0; i < articolo.length; i++) {
                articolo[i].style.display = 'none';
            }
            btn.style.display = 'none';
        }
    }

    var num;

    function mostraautori() {
        num = document.getElementById("numeroAutori").value;

        var div = document.getElementById("listaSelect");
        div.innerHTML = "";

        for (var i = 0; i < num; i++) {

            //creo la select per gli autori
            var newSelect = document.createElement("select");
            newSelect.setAttribute("name", "autori_" + i);
            newSelect.setAttribute("id", "autori_" + i);
            newSelect.setAttribute("class", "elFormAutore");
            newSelect.setAttribute("onchange", "formNewAutore(" + i + ")");
            var selectH = document.getElementById("listaAutoriH");
            newSelect.innerHTML = selectH.innerHTML;
            div.appendChild(newSelect);

            // creo div per nuovo autore
            var form = document.createElement("div");
            form.setAttribute("class", "formAutore");
            form.setAttribute("id", "formAutore_" + i);

            form.style.display = "none";

            // id
            var labelId = document.createElement("label");
            labelId.innerHTML = "Id:";
            var textId = document.createElement("input");
            textId.setAttribute("type", "text");
            textId.setAttribute("name", "id_" + i);
            console.log("id_" + i);
            if (document.getElementsByName("id_" + i) != null) {
                console.log("ok");
            }

            textId.setAttribute("id", "id_" + i);
            textId.setAttribute("class", "elFormAutore");
            textId.setAttribute("placeholder", "Id");

            form.appendChild(labelId);
            form.appendChild(textId);

            //nome
            var labelNome = document.createElement("label");
            labelNome.innerHTML = "Nome:";
            var textNome = document.createElement("input");
            textNome.setAttribute("type", "text");
            textNome.setAttribute("name", "nome_" + i);
            textNome.setAttribute("id", "nome_" + i);
            textNome.setAttribute("class", "elFormAutore");
            textNome.setAttribute("placeholder", "Inserisci nome");

            form.appendChild(labelNome);
            form.appendChild(textNome);

            // cognome
            var labelCognome = document.createElement("label");
            labelCognome.innerHTML = "Cognome:";
            var textCognome = document.createElement("input");
            textCognome.setAttribute("type", "text");
            textCognome.setAttribute("name", "cognome_" + i);
            textCognome.setAttribute("id", "cognome_" + i);
            textCognome.setAttribute("class", "elFormAutore");
            textCognome.setAttribute("placeholder", "Inserisci cognome");

            form.appendChild(labelCognome);
            form.appendChild(textCognome);

            div.appendChild(form);
        }
    }

    function formNewAutore(i) {

        // // mostro il form
        var valore = document.getElementById("autori_" + i).value;
        if (valore == "nuovo") {
            document.getElementById("formAutore_" + i).style.display = "flex";
        } else {
            document.getElementById("formAutore_" + i).style.display = "none";
        }
        // altrimenti non devo aggiungere un nuovo form per l'autore
    }
    </script>
</body>

</html>