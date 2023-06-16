<!DOCTYPE html>
<html>
<?php require_once "ConnSQL.php";
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>CreaConferenza Page</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body class="bg-info containter">

    <div id="title" class="bg-primary border-bottom border-dark">
        <h1 class="display-4 text-center ">Associa presenter alla presentazione di un articolo</h1>
    </div>
    <div>
        <form action="AmministratorePage.php" method="POST">
            <input type="submit" id="back" name="back" value="BACK">
        </form>
    </div>
    <div id='flexContainer' class="row">
        <div id='form' class="col">
            <h2 id="history">
                <?php
                    if(isset($_POST["checkedSessione"])){
                        echo "Sessione: " . $_POST["checkedSessione"][0];
                    } else if(isset($_POST["checkedArticolo"])){
                        echo "Sessione: " . $_SESSION["sessione"] . " Articolo: " . strtok($_POST["checkedArticolo"][0], "-");
                    }
                ?>
            </h2>
            <form id="regForm" action='' method="POST">
                <div class="form-group m-auto w-75">
                    <div class="tab">
                        <h3 class="w-25">
                            Sessione:
                        </h3>
                        <table class="table table-hover bg-light h-100">
                            <thead class="thead-dark">
                                <tr>
                                    <th></th>
                                    <th>Titolo</th>
                                    <th>Codice</th>
                                    <th>Acronimo Conferenza</th>
                                    <th>Edizione</th>
                                </tr>
                            </thead>
                            <tbody id="tbSessioni">
                                <?php
                                        stampaSessioni();
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab">
                        <h3 class="w-25">
                            Articolo:
                        </h3>
                        <table class="table table-hover bg-light h-100">
                            <thead class=" thead-dark">
                                <tr>
                                    <th></th>
                                    <th>Titolo</th>
                                    <th>Codice Articolo</th>
                                    <th>Codice Sessione</th>
                                    <th>N. pagine</th>
                                    <th>Autori</th>
                                    <th>Stato Svolgimento</th>
                                </tr>
                            </thead>
                            <tbody id="tbArticolo">
                                <?php
                                    if(isset($_POST["checkedSessione"])){
                                        stampaArticolo();
                                    }else if($_SESSION["sessione"] != null){
                                        stampaArticolo();
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab">
                        <h3 class="w-25">
                            Autori:
                        </h3>
                        <table class="table table-hover bg-light h-100">
                            <thead class=" thead-dark">
                                <tr>
                                    <th></th>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th>Cognome</th>
                                </tr>
                            </thead>
                            <tbody id="tbPresenter">
                                <?php
                                    if(isset($_POST["checkedArticolo"])){
                                        stampaAutori();
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab">
                        <div id="lastTab" class="alert alert-success" role="alert">
                            <label id="success">Associazione avvenuta con successo!</label>
                            <button type="button" id="exitBtn"
                                onclick="window.location.href = '<?php echo esco()?>'">Esci</button>
                            <?php
                                if(isset($_POST["checkedAutore"])){
                                    associaPresenter();
                                }
                            ?>
                        </div>
                    </div>
                    <div style="overflow:auto;">
                        <label id="error">Seleziona una sessione</label>
                        <div style="float:right;">
                            <button type="button" id="prevBtn" onclick="nextPrev(-1)">Indietro</button>
                            <button type="button" id="nextBtn" onclick="nextPrev(1)">Avanti</button>
                        </div>
                    </div>
                    <div style="text-align:center;margin-top:20px;">
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php

    function esco(){
        return './AmministratorePage.php';
    }

    function stampaSessioni()
    {
        $conn = ConnSQL::DBConnection();

        $sql = $conn->prepare("SELECT *
                                 FROM SESSIONE, PROGRAMMA_GIORNALIERO
                                WHERE Id = IdProgramma");
        $sql->execute();

        $out = "";

        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $out .= '<tr> <td><input type="radio" name="checkedSessione[]" value="' . $row['Codice'] . '"</td>';
                $out .= "<td>" . $row['Titolo'] . "</td>";
                $out .= "<td>" . $row['Codice'] . "</td>";
                $out .= "<td>" . $row['AcronimoConferenza'] . "</td> ";
                $out .= "<td>" . $row['AnnoEdizioneConferenza'] . "</td> </tr>";
            }
            echo $out;
        }
        $conn = null;
    }
    function stampaArticolo()
    {
        $conn = ConnSQL::DBConnection();

        if(isset($_POST["checkedSessione"])){
            $_SESSION["sessione"] = $_POST["checkedSessione"][0];
        }

        $sessione = $_SESSION["sessione"];

        $sql = $conn->prepare("SELECT *
                                 FROM ARTICOLO
                                WHERE CodiceSessionePresentazione = '$sessione'");
        $sql->execute();

        $out = "";

        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                //select degli autori
                $conn2 = ConnSQL::DBConnection();

                $tmp_sessione = $row["CodiceSessionePresentazione"];
                $tmp_articolo = $row["CodicePresentazione"];

                $listaAutori = "";

                $sql2 = $conn2->prepare("SELECT IdAutore
                                           FROM LISTA_AUTORI
                                          WHERE CodiceSessioneArticolo = '$tmp_sessione'
                                            and CodiceArticolo = '$tmp_articolo'");
                $sql2->execute();

                if ($sql2->rowCount() > 0) {
                    $row2 = $sql2->fetch(PDO::FETCH_ASSOC);
                    $listaAutori = $row2["IdAutore"];

                    while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                        $listaAutori .= ", " . $row2["IdAutore"];
                    }
                }

                // se manca il presenter aggiungo un radio button
                if($row['StatoSvolgimento'] == "NonCoperto"){
                    $out .= '<tr> <td><input type="radio" name="checkedArticolo[]" value="' . $row['CodicePresentazione'] . "-" . $row['CodiceSessionePresentazione'] . '"</td>';
                } else{
                    $out .= '<tr><td></td>';
                }
                    $out .= "<td>" . $row['Titolo'] . "</td>";
                    $out .= "<td>" . $row['CodicePresentazione'] . "</td>";
                    $out .= "<td>" . $row['CodiceSessionePresentazione'] . "</td>";
                    $out .= "<td>" . $row['Numpagine'] . "</td>";
                    $out .= "<td>" . $listaAutori . "</td>";
                    $out .= "<td>" . $row['StatoSvolgimento'] . "</td></tr>";
            }
            echo $out;
        }
        $conn = null;
    }

    function stampaAutori()
    {
        $conn = ConnSQL::DBConnection();

        if(isset($_POST["checkedArticolo"])){
            $_SESSION["articolo"] = strtok($_POST["checkedArticolo"][0], "-");
        }

        $sessione = $_SESSION["sessione"];
        $articolo = $_SESSION["articolo"];

        $sql = $conn->prepare("SELECT Id, Nome, Cognome
                                 FROM AUTORE
                                WHERE Id in (SELECT IdAutore
                                               FROM LISTA_AUTORI
                                              WHERE CodiceArticolo = '$articolo'
                                                and CodiceSessioneArticolo = '$sessione'
                                                and Id = IdAutore)");
        $sql->execute();

        $out = "";

        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $out .= "<tr> <td> <input type='radio' name='checkedAutore[]' value='" . $row['Id'] . "'</td>";
                $out .= "<td>" . $row['Id'] . "</td>";
                $out .= "<td>" . $row['Nome'] . "</td>";
                $out .= "<td>" . $row['Cognome'] . "</td></tr>";
            }
            echo $out;
        }
        $conn = null;
    }

    function associaPresenter()
    {
        $conn = ConnSQL::DBConnection();

        $sessione = $_SESSION["sessione"];
        $articolo = $_SESSION["articolo"];
        $presenter = $_POST["checkedAutore"][0];

        $sql = $conn->prepare("CALL CreaEAssociaPresenter(:UsernamePresenter, :CodiceArticolo, :CodiceSessioneArticolo)");
        
        $sql -> execute([
            'UsernamePresenter' => $presenter,
            'CodiceArticolo' => $articolo,
            'CodiceSessioneArticolo' => $sessione
        ]);

        $conn = null;
    }
    ?>
</body>

</html>

<style>
#flexContainer {
    margin: 0px;
}

#form {
    text-align: center;
    padding: 0px;
}

#back {
    margin: 10px;
}

#history {
    text-decoration: underline;
}

#success {
    margin-top: 30px;
}

table {
    width: 80%;
    margin: auto;
}

.tab {
    display: none;
}

#lastTab {
    display: flex;
    flex-direction: column;
}

#error {
    color: red;
    visibility: hidden;
}

#exitBtn {
    width: 20vw;
    margin: auto;
    margin-top: 50px;
}

button {
    background-color: #04AA6D;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 17px;
    font-family: Raleway;
    cursor: pointer;
}

button:hover {
    opacity: 0.8;
}

.step {
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
}

.step.active {
    opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
    background-color: #04AA6D;
}
</style>

<script>
var currentTab = <?php 
                    if(isset($_POST["checkedSessione"])){
                        echo 1;
                    } else if(isset($_POST["checkedArticolo"])){
                        echo 2;
                    } else if(isset($_POST["checkedAutore"])){                       
                        echo 3;
                    } else echo 0;
                ?>;

showTab(currentTab); // Display the current tab

function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    //... and fix the Previous/Next buttons:

    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 2)) {
        document.getElementById("nextBtn").innerHTML = "Conferma";
    } else if (n == (x.length - 1)) {
        document.getElementById("prevBtn").style.display = "none";
        document.getElementById("nextBtn").style.display = "none";
        document.getElementById("back").style.display = "none";

    } else {
        document.getElementById("nextBtn").innerHTML = "Avanti";
    }

    //... and run a function that will display the correct step indicator:
    fixStepIndicator(n);
}

function rebuildTables(n) {
    switch (n) {
        case 0:
            document.getElementById("history").innerHTML = "";
            break;
        case 1:
            document.getElementById("history").innerHTML = "Sessione: <?php echo $_SESSION["sessione"] ?>";
            break;
        default:
            break;
    }
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) {
        var error = document.getElementById("error");
        switch (currentTab) {
            case 0:
                error.innerHTML = "Seleziona una sessione";
                error.style.visibility = "visible";
                break;
            case 1:
                error.innerHTML = "Seleziona un articolo";
                error.style.visibility = "visible";
                break;
            case 2:
                error.innerHTML = "Seleziona un presenter";
                error.style.visibility = "visible";
                break;
            default:
                break;
        }
        return false;
    } else {
        var error = document.getElementById("error");
        error.style.visibility = "hidden";
    }
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;

    if (n == -1) {
        rebuildTables(currentTab);
    }
    // the form gets submitted:
    if (n == 1) {
        document.getElementById("regForm").submit();
    }
    // display the correct tab:
    showTab(currentTab);
}

function validateForm() {
    // This function deals with validation of the form fields
    var x, valid = true;
    var selected = false;
    x = document.getElementsByClassName("tab");

    switch (currentTab) {
        case 0:
            var radio = document.getElementsByName("checkedSessione[]");
            for (var i = 0; i < radio.length; i++) {
                if (radio[i].checked == true) {
                    selected = true;
                }
            }
            break;

        case 1:
            var radio = document.getElementsByName("checkedArticolo[]");
            for (var i = 0; i < radio.length; i++) {
                if (radio[i].checked == true) {
                    selected = true;
                }
            }
            break;
        case 2:
            var radio = document.getElementsByName("checkedAutore[]");
            for (var i = 0; i < radio.length; i++) {
                if (radio[i].checked == true) {
                    selected = true;
                }
            }
            break;
        default:
            break;
    }

    // If the valid status is true, mark the step as finished and valid:
    if (selected) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return selected; // return the valid status
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class on the current step:
    x[n].className += " active";
}
</script>