<?php require_once "./ConnDB.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Memory Game</title>

    <link rel="stylesheet" href="Memory.css" />
  </head>
  <body>

    <nav>
        <div class="opzioni">
            <a href="GamesPage.php"><p>Back</p></a>
        </div>
    </nav>


    <div id="endGame">
      <form action="" method="POST">
        <h3 id="movesNumber"></h3>
        <input type="submit" value="Play again" name="again" id="reload"/>
        <input type="submit" value="Try another game!" name="noMore" id="exit" />
        <input type="text" name="mosse" id="NumMosse" value="">
      </form>

      
      <h3 id="title">
        <!-- <label>Choose the difficulty of the game:</label>
        <select name="Choose" id="choose">
          <option value="Easy">Esasy</option>
          <option value="Medium">Medium</option>
          <option value="Difficult">Difficult</option>
        </select>-->
        <button onclick="showCards()" name="play" id="goToPlay">Play</button>
      </h3>

    </div>

    <?php
        session_start();

        if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['again']) || isset($_POST['noMore'])) && isset($_SESSION['username'])){
            inserisciPunteggio();
        }
         else if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['noMore']))
         header("Location: GamesPage.php");


        function inserisciPunteggio(){
            //chiamo l'update su gioco dei pesi
            $conn = ConnDB::DBConnection();

            $sostituisci = false;

            $username = $_SESSION['username'];

            try{
                //controllo che il punteggio precedente fosse minore di quello attuale
                $sql = $conn -> prepare("SELECT GiocoMemory FROM PUNTEGGIO WHERE (UsernameUtente = '$username')");
                $sql -> execute();


                if ($sql->rowCount() > 0) {
                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                        if($_POST['mosse'] < $row['GiocoMemory'] || $row['GiocoMemory'] == null){
                            $sostituisci = true;
                        }
                    }
                }

                if($sostituisci){
                  $conn -> query("CALL ModificaMemory('$username', '$_POST[mosse]')");
                }
            }catch(PDOException $err){
       
            }
            $conn = null;

            if(isset($_POST['noMore'])){
                header("Location: GamesPage.php");
            }
        }
      ?>
    <section class="memory-game" id="memory">
      <div class="memory-card" data-framework="aurelia" id="card1">
        <img class="front-face" src="images/animal1.jpg" alt="Aurelia" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="aurelia" id="card2">
        <img class="front-face" src="images/animal1.jpg" alt="Aurelia" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="vue" id="card3">
        <img class="front-face" src="images/animal2.jpg" alt="Vue" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="vue" id="card4">
        <img class="front-face" src="images/animal2.jpg" alt="Vue" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="angular" id="card5">
        <img class="front-face" src="images/animal3.jpg" alt="Angular" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="angular" id="card6">
        <img class="front-face" src="images/animal3.jpg" alt="Angular" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="ember" id="card7">
        <img class="front-face" src="images/animal4.jpg" alt="Ember" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="ember" id="card8">
        <img class="front-face" src="images/animal4.jpg" alt="Ember" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="backbone" id="card9">
        <img class="front-face" src="images/animal5.jpg" alt="Backbone" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="backbone" id="card10">
        <img class="front-face" src="images/animal5.jpg" alt="Backbone" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="react" id="card11">
        <img class="front-face" src="images/animal8.jpg" alt="React" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="react" id="card12">
        <img class="front-face" src="images/animal8.jpg" alt="React" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="my1" name="my1" id="card13">
        <img class="front-face" src="images/animal9.jpg" alt="My1" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="my1" name="my1" id="card14">
        <img class="front-face" src="images/animal9.jpg" alt="My1" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="my2" name="my2" id="card15">
        <img class="front-face" src="images/animal10.jpeg" alt="My2" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="my2" name="my2" id="card16">
        <img class="front-face" src="images/animal10.jpeg" alt="My2" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <!--<div class="memory-card" data-framework="my3" name="my3" id="card17">
        <img class="front-face" src="images/animal11.jpg" alt="My3" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="my3" name="my3" id="card18">
        <img class="front-face" src="images/animal11.jpg" alt="My3" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>

      <div class="memory-card" data-framework="my4" name="my4" id="card19">
        <img class="front-face" src="images/animal12.jpg" alt="My4" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>
      <div class="memory-card" data-framework="my4" name="my4" id="card20">
        <img class="front-face" src="images/animal12.jpg" alt="My4" />
        <img class="back-face" src="images/paw-solid.svg" alt="JS Badge" />
      </div>-->
    </section>

    <script src="Memory.js"></script>
  </body>
</html>
