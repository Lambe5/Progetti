<?php require_once "./ConnDB.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CambiaPw.css" />
    <title>Login</title>
</head>
<body class="bg-info">
    <nav>
        <div class="opzioni">
            <a href="HomePage.php"><p>Back</p></a>
        </div>
    </nav>
    <div id="contenitore">
    <form action="" method="POST">
      <div class="signIn"><h1 id="titoloLogIn">Cambia Password</h1></div>
      <div class="signIn"><input type="password" name="old" autofocus placeholder="Old Password"/></div>
      <div class="signIn"><input type="password" name="new" autofocus placeholder="New Password"/></div>
      <div><button id="updateButton" name="update">Update</button></div>
      <div id="linkUpdate"><a href="ResetPw.php">Would you want to reset your password?</a></div>
    </form>

    <?php
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['update'])){
        updatePw();
      }

      function updatePw(){

        session_start();
        $conn = ConnDB::DBConnection();

        $esiste = false;

        //esegui una query per vedere se le credenziali per l'accesso sono giuste
        $sql = $conn -> prepare("SELECT Username, Password FROM UTENTE");
        $sql -> execute();
        if ($sql->rowCount() > 0) {
            // output data of each row
            //$result = $sql->fetch(PDO::FETCH_ASSOC);
            try{
                while($result = $sql->fetch(PDO::FETCH_ASSOC)){
                    if($_SESSION['username'] == $result['Username'] && $_POST['old'] == $result['Password']){
                        //echo "Ben venuto/a " . $_POST['username'] . "<br>";
                        $esiste = true;
                    }
                }
            }catch(PDOException $e){
    
            }
    
            }
    
            if($esiste == false)
                echo "<p id='errore'>Vecchia password errata</p>";
            else{
                try{
                    $conn -> query("CALL ModificaPw('$_SESSION[username]', '$_POST[new]')");                    
                }catch(PDOException $err){
                
                }
                $conn = null;
                header("Location:HomePage.php");
            }

      }
?>

</div>

</body>
</html>