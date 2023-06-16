<?php require_once "./ConnDB.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GiocoDeiPesi</title>
    <link rel="stylesheet" href="GiocoDeiPesi.css" />
    <script src="GiocoDeiPesi.js"></script>
</head>
<body>
<nav>
        <div class="opzioni">
            <a href="GamesPage.php"><p>Back</p></a>
        </div>
    </nav>

    <div class="contenitore">
        <div id="finishGame">
            <form method="POST">
                <p id="gameOver"><b>Game Over</b></p>
                <input type="submit" name="again" value="Play again" id="reload">
                <input type="submit" name="noMore" value="Try another game!" id="exit">
                <input type="text" name="mosse" id="NumMosse" value="">
            </form>
        </div>

        <div id="caso">
            <div id="punteggio">
                <p><b>Score:</b></p>
                <p id='numeroMosse'></p>
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
                    $sql = $conn -> prepare("SELECT GiocoPesi FROM PUNTEGGIO WHERE (UsernameUtente = '$username')");
                    $sql -> execute();


                    if ($sql->rowCount() > 0) {
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            if($_POST['mosse'] > $row['GiocoPesi']){
                                $sostituisci = true;
                            }
                        }
                    }

                    if($sostituisci)
                        $conn -> query("CALL ModificaPesi('$username', '$_POST[mosse]')");
                }catch(PDOException $err){
           
                }
                $conn = null;

                if(isset($_POST['noMore'])){
                    header("Location: GamesPage.php");
                }
            }
        ?>


            <div class="immagini">
                <div>
                    <img src="" id="im1" alt="animal">
                    <h3 name="nomeAnimale"></h3>
                    <p name="peso"></p>
                </div>
                <div>
                    <img src="" id="im2" alt="animal">
                    <h3 name="nomeAnimale"></h3>
                    
                    <p name="peso"></p>
                </div>
            </div>
        </div>
        
        <div class="bottoni">
            <div><button onclick="max()">Higher</button></div>
            <div><button onclick="min()">Lower</button></div>
        </div>

  </body>
</html>
