<?php require_once "./ConnDB.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="Quiz.css">
</head>
<body>
    <nav>
        <div class="opzioni">
            <a href="GamesPage.php"><p>Back</p></a>
        </div>
    </nav>
        <div class="contenitore">
        <div id="finishGame">
            <form action="" method="POST">
                <p id="gameOver"><b>Game Over</b></p>
                <input type="submit" name="again" value="Play again" id="reload">
                <input type="submit" name="noMore" value="Try another game!" id="exit">
                <input type="text" name="mosse" id="NumMosse" value="">
            </form>
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
                    $sql = $conn -> prepare("SELECT GiocoQuiz FROM PUNTEGGIO WHERE (UsernameUtente = '$username')");
                    $sql -> execute();


                    if ($sql->rowCount() > 0) {
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            if($_POST['mosse'] > $row['GiocoQuiz']){
                                $sostituisci = true;
                            }
                        }
                    }

                    if($sostituisci)
                        $conn -> query("CALL ModificaQuiz('$username', '$_POST[mosse]')");
                }catch(PDOException $err){
           
                }
                $conn = null;

                if(isset($_POST['noMore'])){
                    header("Location: GamesPage.php");
                }
            }
        ?>

       <div id="core">
            <div id="punteggio">
                <p><b>Score:</b></p>
                <p id="numeroMosse">0</p>
            </div>
            <div class="domanda">
                <img src="">
            </div>
            <div>
            <p id="domandaCorrente"></p>

            </div>
            <div id="scelte">
                <p id="primo" onclick="rispostaSelezionata(id)"></p>
                <p id="secondo" onclick="rispostaSelezionata(id)"></p>
                <p id="terzo" onclick="rispostaSelezionata(id)"></p>
                <p id="quarto" onclick="rispostaSelezionata(id)"></p>
            </div>
       </div>
       <!-- 
       <div class="domanda">
            <button onclick="next()" id="continua">Continua</button>
       </div>
        -->
    </div>
</body>
<script src="Quiz.js"></script>
</html>