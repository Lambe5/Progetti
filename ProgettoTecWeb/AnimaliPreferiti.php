<!DOCTYPE html>
<?php require_once "./ConnDB.php"; session_start(); ?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GamesPage</title>
    <link rel="stylesheet" href="AnimaliPreferiti.css" />
  </head>
  <body>
    <nav>
        <div class="opzioni">
            <a href="HomePage.php"><p>Back</p></a>
        </div>
    </nav>

    <div id="contenitore">
    <div id="liste">
        <div class='titolo'>
                <h1>Animali Preferiti</h1>
        </div>
        <div>
            <form action='AnimaliPreferiti.php' method='POST'>
                <input type='text' name='animale' placeholder='inserisci nome animale'>
                <input type='submit' id='inserisciAnimale' name='inserisci' value='INSERISCI'></button>
            </form>
        </div>
    <?php

    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        if(!isset($_POST['inserisci'])){
            stampaLista();
        }
    }

    function stampaLista(){
        //prendo e stampo la sua lista di animali preferiti
        $conn = ConnDB::DBConnection();
        $sql = $conn -> prepare("SELECT Animale FROM LISTA_ANIMALI WHERE (UsernameUtente = '$GLOBALS[username]')");
        $sql -> execute();

        echo "<ul>";

        if ($sql->rowCount() > 0) {
            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                //echo $row['Acronimo'] . " " . $row['Nome'] . " " .  $row['ImgLogo'] . " " .  $row['AnnoEdizione'] . " " . "<br>";
                echo "<li>". $row['Animale'] ."</li>";
            }
        }
        
        echo "</ul>";
        $conn = null;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['inserisci'])){
        inserisciAnimale();
    }

    function inserisciAnimale(){
        //chiamo la stored procedure per inserire nella tabella
        $conn = ConnDB::DBConnection();

        $animale = $_POST['animale'];

        try{
            $conn -> query("CALL InserisciListaAnimali('$GLOBALS[username]', '$animale')");
        }catch(PDOException $err){
        
        }
        $conn = null;
        stampaLista();
    }

    ?>
</div>
  </body>
</html>
