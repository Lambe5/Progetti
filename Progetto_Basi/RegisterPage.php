<?php
    require_once "./ConnSQL.php";
    require_once "./ConnMongoDB.php";
    ob_start();
    session_start(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Register Page</title>
</head>

<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 id="registratiTitle" class="display-4 text-center m-3">Registrati</h1>
    </div>

    <div id="contenitore" class="m-3">
        <form method="POST" action="RegisterPage.php" class="registrazione" enctype="multipart/form-data">
            <label class="registrazione" for="username" required>Username</label>
            <input class="registrazione" type="text" id="usernameReg" name="username" placeholder="Inserisci username"
            maxlength='30' required>
            <label class="registrazione" for="password" required>Password</label>
            <input class="registrazione" type="password" id="passwordReg" name="password"
                placeholder="Inserisci password" maxlength='30' required>
            <label class="registrazione" for="nome">Nome</label>
            <input type="text" id="nome" name="nome" class="registrazione" placeholder="Inserisci nome" maxlength='30'>
            <label class="registrazione" for="cognome">Cognome</label>
            <input type="text" id="cognome" name="cognome" class="registrazione" placeholder="Inserisci cognome" maxlength='30'>
            <label class="registrazione" for="dataN">Data nascita</label>
            <input type="date" id="dataN" name="dataN" class="registrazione" placeholder="Inserisci data Nascita">
            <label class="registrazione" for="luogo">Luogo nascita</label>
            <input type="text" id="luogo" name="luogo" class="registrazione" placeholder="Inserisci luogo" maxlength='30'>
            <br>
            <select id="tipoUtente" name="tipoUtente" onchange="visualizzaInput()">
                <option value="Generale" selected>Generale</option>
                <option value="Presenter">Presenter</option>
                <option value="Speaker">Speaker</option>
                <option value="Amministratore">Amministratore</option>
            </select>
            <br>
            <label class="altroUtente" for="CV">Curriculum Vitae</label>
            <input type="text" id="CV" name="CV" class="altroUtente" placeholder="Inserisci CV" maxlength='30'>
            <label class="altroUtente" for="image">Inserisci foto</label>
            <input type="file" alt="Submit" id="foto" name="image" class="altroUtente" placeholder="Inserisci foto" accept="image/*">
            <label class="altroUtente" for="nomeUni">Inserisci nomeUni</label>
            <input type="text" id="nomeUni" name="nomeUni" class="altroUtente" placeholder="Inserisci nomeUni" maxlength='30'>
            <label class="altroUtente" for="nomeDip">Inserisci nomeDip</label>
            <input type="text" id="nomeDip" name="nomeDip" class="altroUtente" placeholder="Inserisci nomeDip" maxlength='30'>
            <br>
            <input id="btnRegistrati" class="registrazione btn btn-success" type="submit" name="registrati" value="REGISTRATI">
           <span><a href="Login.php" id='linkLogin' class="badge badge-primary text-dark">Accedi al Login</a></span>
        </form>
        
    </div>

    <?php


    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['registrati'])) {
        registratiPHP();
    }
    function registratiPHP()
    {
        //attivo la connessione
        $conn = ConnSQL::DBConnection();
        $esiste = false;
        $cleanUsername = preg_replace("/\s+/", "", $_POST['username']);
        $cleanPassword = preg_replace("/\s+/", "", $_POST['password']);

        if (strcmp($cleanUsername, "") != 0 && strcmp($cleanPassword, "") != 0) {
            //esegui una query per vedere se esiste già un username uguale
            $sql = $conn->prepare("SELECT Username FROM UTENTE");
            $sql->execute();
            if ($sql->rowCount() > 0) {
                // output data of each row
                //$result = $sql->fetch(PDO::FETCH_ASSOC);
                $i = 0;
                try {
                    if (isset($_POST['username']) && isset($_POST['password'])) {

                        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {

                            if ($_POST['username'] == $result['Username']) {
                                echo "<span id='errLogin' class='m-3 font-weight-bold badge-warning'>
                                      Esiste già uno username uguale 
                                      </span> <br>";
                                $esiste = true;
                            }
                        }
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
            } else {
                echo "0 results";
            }

            //strcpm(str1,str2) confronta due stringhe se != 0 allora le stringhe sono diverse
            if ($esiste == false) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $nome = $_POST['nome'];
                $cognome = $_POST['cognome'];
                $dataN = $_POST['dataN'];
                $luogo = $_POST['luogo'];

                try {
                    $conn->query("CALL CreaUtente('$username', '$password', '$nome', '$cognome', '$luogo', '$dataN')");

                    //Dato che c'è stato un inserimento , vado ad inserirlo nella collezione 'logs' di mongoDB
                    ConnMongoDB::insertDocumentInLogs("Un utente si è registrato");
                } catch (PDOException $e) {
                    echo $e;
                }
            }

            //in base al tipo di utente, se diverso da generale, chiamo le funzioni per creare quel tipo di utente
            $_SESSION['username'] = $_POST['username'];

            if (isset($_SESSION['username'])) {

                if ($esiste == false) {
                    if ($_POST['tipoUtente'] != "Generale") {
                        echo "SISI";
                        try {
                            switch ($_POST['tipoUtente']) {
                                
                                case "Presenter":
                                    $CV = $_POST['CV'];
                                    //$image = $_POST['image'];
                                    $nomeUni = $_POST['nomeUni'];
                                    $nomeDip = $_POST['nomeDip'];
                                    //$conn->query("CALL CreaPresenter('$username', '$nomeUni', '$nomeDip', '$CV', '$image')");
                                    $isImage=true;
                                    if ($_FILES['image']['tmp_name'] != "") {
                                        $image = file_get_contents($_FILES['image']['tmp_name']);
                                        $typeOfFile = mime_content_type($_FILES['image']['tmp_name']);
                                        strcmp(substr($typeOfFile, 0, 5),"image") ==0 ? $isImage=true : $isImage=false;
                                    } else $image = "";
                                    if (filesize($_FILES['image']['tmp_name']) <= 1048576 && $isImage){
                                        echo "SI";
                                        try{
                                            $salvaInCrea_Conferenza = $conn->prepare("CALL CreaPresenter(:username,:nomeuni,:nomedip,:cv,:image)");
                                            $salvaInCrea_Conferenza->execute([
                                                'username'  => $username,
                                                'nomeuni' => $nomeUni,
                                                'nomedip' => $nomeDip,
                                                'cv' => $CV,
                                                'image' => $image,
                                            ]);
                                        }catch(PDOException $e){
                                            echo $e;
                                        }
                                    }
                                    $conn = null;
                                    header("Location:PresenterPage.php");
                                    break;
                                case "Amministratore":
                                    $conn->query("CALL InserisciAmministratore('$username')");
                                    $conn = null;
                                    header("Location:AmministratorePage.php");
                                    break;
                                case "Speaker":
                                    $CV = $_POST['CV'];
                                    //$image = $_POST['image'];
                                    $isImage=true;
                                    if ($_FILES['image']['tmp_name'] != "") {
                                        $image = file_get_contents($_FILES['image']['tmp_name']);
                                        $typeOfFile = mime_content_type($_FILES['image']['tmp_name']);
                                        strcmp(substr($typeOfFile, 0, 5),"image") ==0 ? $isImage=true : $isImage=false;
                                    } else $image = "";
                                    $nomeUni = $_POST['nomeUni'];
                                    $nomeDip = $_POST['nomeDip'];
                                    if (filesize($_FILES['image']['tmp_name']) <= 1048576 && $isImage){
                                        echo "SI";
                                        try{
                                            //$conn->query("CALL CreaSpeaker('$username', '$nomeUni', '$nomeDip', '$CV', '$image')");
                                            $salvaInCrea_Conferenza = $conn->prepare("CALL CreaSpeaker(:username,:nomeuni,:nomedip,:cv,:image)");
                                            $salvaInCrea_Conferenza->execute([
                                                'username'  => $username,
                                                'nomeuni' => $nomeUni,
                                                'nomedip' => $nomeDip,
                                                'cv' => $CV,
                                                'image' => $image,
                                            ]);
                                        }catch(PDOException $e){
                                            echo $e;
                                        }
                                    }
                                    $conn = null;
                                    header("Location:SpeakerPage.php");
                                    break;
                            }
                        } catch (PDOException $e) {
                            echo $e;
                        }
                    } else {
                        $conn = null;
                        header("Location:GenericoPage.php");
                    }
                }
            }
        }

        $conn = null;
    }

    ?>

    <style>
    .registrazione {
        display: block;
    }

    .altroUtente {
        display: none;
    }

    #usernameReg {
        display: block;
    }

    #passwordReg {
        display: block;
    }

    #btnLogin {
        margin-bottom: 1%;
    }

    #btnRegistrati {
        margin-bottom: 1%;
    }
    </style>


    <script type="text/javascript">
    function registrati() {
        //Pulisco l' eventuale msg di errore (se esiste) che era nella schermata di Login
        let errMsgLogin = document.getElementById('errLogin');
        if (errMsgLogin)
            errMsgLogin.textContent = '';
        //Nascondo il titolo della pagina 'Login' e faccio comparire il titolo 'Registrati'
        document.getElementById('loginTitle').style.display = 'none';
        document.getElementById('registratiTitle').style.display = 'block';
        //Nascondo il form del Login e mostro quello del Registrati
        document.getElementsByClassName('login')[0].style.display = 'none';
        //document.getElementById('accediReg').style.display = 'none'
        var tipoUtente = document.getElementById('tipoUtente');
        tipoUtente.style.display = 'block'
        var arr = document.getElementsByClassName('registrazione');

        for (var i = 0; i < arr.length; i++) {
            arr[i].style.display = 'block';
        }

    }

    function visualizzaInput() {
        //in base al tipo di valore presente rendo visibli i vari pannelli del form
        var tipoUtente = document.getElementById('tipoUtente').value;

        var altriUtenti = document.getElementsByClassName('altroUtente');

        if (tipoUtente != "Generale" && tipoUtente != "Amministratore") {
            for (var i = 0; i < altriUtenti.length; i++) {
                altriUtenti[i].style.display = 'block';
            }
        } else {
            for (var i = 0; i < altriUtenti.length; i++) {
                altriUtenti[i].style.display = 'none';
            }
        }
    }
    </script>
</body>

</html>