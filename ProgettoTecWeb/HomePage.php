<!DOCTYPE html>
<?php require_once "./ConnDB.php";
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="HomePage.css">
    <title>HomePage | Animal House</title>
</head>
<body>
    <nav id="navNoLog">
        <div class="opzioni">
            <a href="GamesPage.php"><p>Giochi</p></a>
        </div>
        <div class="opzioni">
            <a href=""> <p>E-commerce</p></a>
        </div>
        <div class="opzioni" id="logIn">
            <a href="Login.php"><p>Login</p></a>
        </div>
    </nav>

    <nav id="navLog">
        
        <?php
            if(isset($_SESSION['username']))
              echo "<div class='opzioniLog'>
              <a href='GamesPage.php'><p>Giochi</p></a>
              </div>";
          ?>

        <?php
            if(isset($_SESSION['username']))
              echo "<div class='opzioniLog'>
              <a href=''><p>E-commerce</p></a>
              </div>";
          ?>

        <?php
            if(isset($_SESSION['username'])){
              echo "<div class='opzioniLog'>
              <a href='Bacheca.php'><p>Bacheca</p></a>
              </div>";
            }
          ?>

        <?php
            if(isset($_SESSION['username']))
              echo "<div class='opzioniLog'>
              <a href='AnimaliPreferiti.php'><p>Animali Preferiti</p></a>
              </div>";
          ?>

          <?php
            if(isset($_SESSION['username']))
              echo "<div class='opzioniLog'>
              <a href='CambiaPw.php'><p>Cambia Password</p></a>
              </div>";
          ?>

          <?php
            if(isset($_SESSION['username'])){
              echo "<div class='opzioniLog'>
              <form action='HomePage.php' method='POST'>
              <p><input type='submit' id='eliminaAcc' class='bottone' value='Delete Account' name='elimina'></p>
              </form>
              </div>";
            }
          ?>
          
          <?php
            if(isset($_SESSION['username'])){
              echo "
              <div class='opzioniLog'>
              <form action='HomePage.php' method='POST'><p><input type='submit' id='logout' class='bottone' value='Logout' name='esci'></p>
              </form>
              </div>";
              echo '<style>
                    #navNoLog {display: none;}
                    #navLog {display: flex;}
              </style>';
            }
          ?>
    </nav>
    <div id="contenitore">
        <div id="info">
            <h1>Animal House</h1>
            <p>Animal House non è solo uno shop online. Animal House è una servizio che permette di coltivare il nostro legame con i nostri amici animali.</p>
        </div>
        <div id="sfondo">
            <img src="images/myAH1.jpg">
        </div>
    </div>

    <?php
        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['elimina'])){
          eliminaUtente();
        }

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['esci'])){
          logout();
        }

        function eliminaUtente(){
           //chiamo la stored procedure per inserire nella tabella
           $conn = ConnDB::DBConnection();

           $username = $_SESSION['username'];

           try{
               $conn -> query("CALL EliminaUtente('$username')");
               $_SESSION['username'] = null;
               
           }catch(PDOException $err){
           
           }
           $conn = null;
           header("Location: HomePage.php");
        }

        function logout(){
          session_start();
          session_destroy();
          echo '<style>
                    .opzioni { display:block;}
              </style>';
          header('Location: HomePage.php');
        }
      ?>
</body>
</html>