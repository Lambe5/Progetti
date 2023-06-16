<!DOCTYPE html>
<?php require_once "./ConnDB.php"; session_start(); ?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GamesPage</title>
    <link rel="stylesheet" href="GamesPage.css" />
  </head>
  <body>
    <nav>
        <div class="opzioni">
            <a href="HomePage.php"><p>Back</p></a>
        </div>
    </nav>

    <div id="contenitore">
    <div id="liste">
    
    <?php

    if(isset($_SESSION['username']))//{
        $username = $_SESSION['username'];

    if(isset($_SESSION['username'])){
        echo " <div class='titolo'>
                <h1>Scores:</h1>
                </div>
                <style>
                h1{font-size: 300%;}
                </style>";
            stampaPunteggi();
    }

    function stampaPunteggi(){
        //faccio una select
        //stampl il risultato
        $conn = ConnDB::DBConnection();
        $sql = $conn -> prepare("SELECT GiocoMemory, GiocoPesi, GiocoQuiz FROM PUNTEGGIO WHERE (UsernameUtente = '$GLOBALS[username]')");
        $sql -> execute();

        echo "<ul>";

        if ($sql->rowCount() > 0) {
            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                //echo $row['Acronimo'] . " " . $row['Nome'] . " " .  $row['ImgLogo'] . " " .  $row['AnnoEdizione'] . " " . "<br>";
                echo "<li>Memory: ". $row['GiocoMemory'] . "</li> 
                    <li>Gioco dei Pesi: ". $row['GiocoPesi'] . "</li>
                    <li>Quiz: " . $row['GiocoQuiz'] . "</li>";
            }
        }
        
        echo "</ul>";
        echo "<div id='punteggi'>
        <a href='Punteggi.php'>Accedi alla Leaderboard</a>
    </div>";
        $conn = null;

    }

    ?>
  </div>
        <div id="giochi" class="titolo">
            <h1>Games</h1>
        </div>
        <div class="icone">
            <div id="game1">
                <a href="Memory.php">
                    <img src="images/bugs-solid.svg" class="icona">
                    <p class="testoIcona">Memory</p>
                </a>
            </div>
            <div id="game2">
                <a href="GiocoDeiPesi.php">
                    <img src="images/hippo-solid.svg" class="icona">
                    <p class="testoIcona">Gioco dei pesi</p>
                </a>
            </div>
            <div id="game3">
                <a href="Quiz.php">
                    <img src="images/frog-solid.svg" class="icona">
                    <p class="testoIcona">Quiz</p>
                </a>
            </div>
          <div id="game4">
          <a href="">
            <img src="images/otter-solid.svg" class="icona"/>
            <p class="testoIcona">Img/video divertenti</p>
          </a>
        </div>
      </div>
    </div>
  </body>
</html>
