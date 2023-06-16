<?php require_once "./ConnDB.php"; ?>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Punteggi.css" />
  </head>
  <body>
    <nav>
        <div class="opzioni">
            <a href="GamesPage.php"><p>Back</p></a>
        </div>
    </nav> 
    <div id="contenitore">

    <div class="titolo">
        <h2>Leaderboard</h2>
    </div>
    <div id="punteggi">
      <div>
        <h3>Memory</h3>
        <ul>
          <?php
                 $conn = ConnDB::DBConnection();

                 //esegui una query per vedere se le credenziali per l'accesso sono giuste
                 $sql = $conn -> prepare("SELECT UsernameUtente, GiocoMemory FROM PUNTEGGIO");
                 $sql -> execute();
                 if ($sql->rowCount() > 0) {
                     $arr = array();
                     $nomi = array();
                     $index = 0;
                     try{
                         while($result = $sql->fetch(PDO::FETCH_ASSOC) and $index < 10){
                          if($result['GiocoMemory'] != null){
                                $arr += [$result['UsernameUtente'] => $result['GiocoMemory']];
                                $index++;
                          }
                         }
                         asort($arr);
                         
                         foreach($arr as $chiave => $valore){
                          echo "<li>" . $chiave . ": " . $valore . "</li>";
                         }
                     }catch(PDOException $e){
             
                     }
                   }
          ?>
        </ul>
      </div>
      <div>
        <h3>Gioco dei Pesi</h3>
        <ul>
        <?php
                 $conn = ConnDB::DBConnection();

                 //esegui una query per vedere se le credenziali per l'accesso sono giuste
                 $sql = $conn -> prepare("SELECT UsernameUtente, GiocoPesi FROM PUNTEGGIO");
                 $sql -> execute();
                 if ($sql->rowCount() > 0) {
                     $arr = array();
                     $nomi = array();
                     $index = 0;
                     try{
                         while($result = $sql->fetch(PDO::FETCH_ASSOC) and $index < 10){
                            if($result['GiocoPesi'] != null){
                                $arr += [$result['UsernameUtente'] => $result['GiocoPesi']];
                                $index++;
                            }   
                         }
                         arsort($arr);
                         
                         foreach($arr as $chiave => $valore){
                          echo "<li>" . $chiave . ": " . $valore . "</li>";
                         }
                     }catch(PDOException $e){
             
                     }
                   }
          ?>
        </ul>
      </div>
      <div>
        <h3>Quiz</h3>
        <ul>
        <?php
                 $conn = ConnDB::DBConnection();

                 //esegui una query per vedere se le credenziali per l'accesso sono giuste
                 $sql = $conn -> prepare("SELECT UsernameUtente, GiocoQuiz FROM PUNTEGGIO");
                 $sql -> execute();
                 if ($sql->rowCount() > 0) {
                     $arr = array();
                     $nomi = array();
                     $index = 0;
                     try{
                         while($result = $sql->fetch(PDO::FETCH_ASSOC) and $index < 10){
                            if($result['GiocoQuiz'] != null){
                                $arr += [$result['UsernameUtente'] => $result['GiocoQuiz']];
                                $index++;
                            }   
                         }
                         arsort($arr);
                         
                         foreach($arr as $chiave => $valore){
                          echo "<li>" . $chiave . ": " . $valore . "</li>";
                         }
                     }catch(PDOException $e){
             
                     }
                   }
          ?>
        </ul>
      </div>
    </div>
    </div>
  </body>
</html>
