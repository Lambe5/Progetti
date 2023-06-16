<?php
    require_once "./ConnSQL.php";
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
    <title>Login Page</title>
</head>

<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 id="loginTitle" class="display-4 text-center m-3">Login</h1>
    </div>

    <div>
        <form action="Homepage.php" method="GET" class="m-3">
            <input type="submit" name="back" value="Homepage">
        </form>
    </div>

    <div id="contenitore" class="m-3">
        <form method="POST" action="Login.php" class="login">
            <label for="username" required>Username</label>
            <input type="text" id="usernameLogin" name="usernameLog" placeholder="Inserisci username" maxlength='30'required>
            <label for="password" required>Password</label>
            <input type="password" id="passwordLogin" name="passwordLog" placeholder="Inserisci password" maxlength='30' required>
            <input id="btnLogin" class="btn btn-success mt-4" type="submit" name="login" value="LOGIN">
            <p id="accediReg">Non hai ancora un account? <a href="RegisterPage.php" class="badge badge-dark">
                    Registrati ora! </a></p>
        </form>
    </div>

    <?php


    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['login'])) {
        accediPHP();
    }

    function accediPHP()
    {

        //attivo la connessione
        $conn = ConnSQL::DBConnection();
        $esiste = false;

        //esegui una query per vedere se le credenziali per l'accesso sono giuste
        $sql = $conn->prepare("SELECT Username, Password FROM UTENTE");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            
            try {
                while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                    if ($_POST['usernameLog'] == $result['Username'] && $_POST['passwordLog'] == $result['Password']) {
                        echo "Ben venuto/a " . $_POST['usernameLog'] . "<br>";
                        $esiste = true;
                    }
                }
            } catch (PDOException $e) {
                echo $e;
            }
        } else {
            echo "0 results";
        }

        if ($esiste == true) {
            $_SESSION['username'] = $_POST['usernameLog'];

            if (isset($_SESSION['username'])) {

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
        } else   echo "<span id='errLogin' class='m-3 font-weight-bold badge-warning'> Username o Password Errati </span> ";

        $conn = null;
    }

    ?>

    <style>


    #usernameLogin {
        display: block;
    }

    #passwordLogin {
        display: block;
    }

    #btnLogin {
        margin-bottom: 1%;
    }
    </style>

</body>

</html>