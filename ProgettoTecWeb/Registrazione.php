<?php require_once "./ConnDB.php"; 
        session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Registrazione.css" />
    <title>Registrazione</title>
</head>
<body>
    <nav>
        <div class="opzioni">
            <a href="HomePage.php"><p>Back</p></a>
        </div>
    </nav>
<div id="contenitore">
    <form action="" method="POST">
        <div class="createAcc"><h1>Registrati</h1></div>
        <div class="createAcc"><input type="text" name="username" autofocus placeholder="Username"/></div>
        <div class="createAcc"><input type="text" name="email" autofocus placeholder="Email"/></div>
        <div class="createAcc"><input type="password" name="password" id="pw" placeholder="Password"/></div>
        <div><button id="registratiButton" name="registrati">Registrati</button></div>
    </form>

    <?php

        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['registrati']) && strcmp($_POST['username'], "") != 0 && strcmp($_POST['password'], "") != 0){
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                registratiPHP();
            else echo "<p id='errore'>Email non valida</p>";
        }

        function registratiPHP(){

                //attivo la connessione
                $conn = ConnDB::DBConnection();
                $esiste = false;
        
                //esegui una query per vedere se esiste già un username uguale
                $sql = $conn -> prepare("SELECT Username FROM UTENTE");
                $sql -> execute();
                if ($sql->rowCount() > 0) {
                    // output data of each row
                    //$result = $sql->fetch(PDO::FETCH_ASSOC);
                    try{
                        if($_POST['username'] != "" && $_POST['password'] != ""){
                            while($result = $sql->fetch(PDO::FETCH_ASSOC)){
                                if($_POST['username'] == $result['Username']){
                                    echo "<p id='errore'>Esiste già uno username uguale</p>";
                                    $esiste = true;
                                }
                            }
                        }
                    }catch(PDOException $e){
            
                    }
            
                  } else {
                    echo "0 results";
                  }
            
            
                  if($esiste == false && $_POST['username'] != "" && $_POST['password'] != ""){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $email = $_POST['email'];
            
                    try{
                        $conn -> query("CALL CreaUtente('$username', '$password', '$email')");
                        $conn -> query("CALL InserisciInPunteggio('$username')");
                    }catch(PDOException $e){
            
                    }
                    $sql = $conn -> prepare("SELECT Username, Password FROM UTENTE");
                    $sql -> execute();
                    if ($sql->rowCount() > 0) {
                        $_SESSION['username'] = $_POST['username'];
                if(isset($_SESSION['username']))
                    header("Location:HomePage.php");
                $conn = null;
                    }
                }
        }
    ?>
</div>

</body>
</html>