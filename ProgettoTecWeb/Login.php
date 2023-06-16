<?php require_once "./ConnDB.php"; 
        session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Login.css" />
    <title>Login</title>
</head>
<body>
    <nav>
        <div class="opzioni">
            <a href="HomePage.php"><p>Back</p></a>
        </div>
    </nav>
<div id="contenitore">
    <form action="" method="POST">
      <div class="signIn"><h1 id="titoloCambiaPw">Log in</h1></div>
      <div class="signIn"><input type="text" name="username" autofocus placeholder="Username"/></div>
      <div class="signIn"><input type="password" name="password" autofocus placeholder="Password"/></div>
      <div><button id="loginButton" name="login">Log in</button></div>
      <div class="signIn" id="linkReg"> <a href="Registrazione.php">Don't have an account? Create account</a></div>
    </form>

    <?php

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['login'])){
    loginPHP();
}

function loginPHP(){

    //controlla che l'account esiste e che la password associata sia quella giusta
    $conn = ConnDB::DBConnection();

    $esiste = false;

    //esegui una query per vedere se le credenziali per l'accesso sono giuste
    $sql = $conn -> prepare("SELECT Username, Password FROM UTENTE");
    $sql -> execute();
    if ($sql->rowCount() > 0) {
        
        try{
            while($result = $sql->fetch(PDO::FETCH_ASSOC)){
                if($_POST['username'] == $result['Username'] && $_POST['password'] == $result['Password']){
                    $esiste = true;
                }
            }
        }catch(PDOException $e){

        }

      }

      if($esiste == false)
        echo "<p id='errore'>Account non trovato</p>";
      else{
        $_SESSION['username'] = $_POST['username'];
        if(isset($_SESSION['username']))
            $conn = null;

        header("Location:HomePage.php");
      }
}
?>
</div>

</body>
</html>