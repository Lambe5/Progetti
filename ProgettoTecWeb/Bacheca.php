<?php require_once "./ConnDB.php"; ?>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Bacheca.css" />
  </head>
  <body>
    <nav>
        <div class="opzioni">
            <a href="HomePage.php"><p>Back</p></a>
        </div>
    </nav> 
    <div id="contenitore">
    <div id="title" class="titolo">
      <h2>Bacheca</h2>
    </div>
    <div id="bacheca">
      <?php
        //cerco tutti i messaggi presenti nella tabella Messaggio
        //creo un div e un input per ogni messaggio presente
        $conn = ConnDB::DBConnection();

        //esegui una query per vedere se le credenziali per l'accesso sono giuste
        $sql = $conn -> prepare("SELECT Messaggio, Immagine FROM MESSAGGIO");
        $sql -> execute();
        if ($sql->rowCount() > 0) {
            // output data of each row
            //$result = $sql->fetch(PDO::FETCH_ASSOC);
            try{
                while($result = $sql->fetch(PDO::FETCH_ASSOC)){
                        if($result['Messaggio'] != null){
                            /*$str = $result['Messaggio'];
                            $rimpiazza = str_replace("'", "&apos;",$str);
                            echo $rimpiazza;*/
                            echo "<div class='messaggio'>
                            <input type='image' name='foto' value='$result[Messaggio]'>
                            </div>";
                        }

                        if($result['Immagine'] != null){
                            echo "<div class='messaggio'>
                        <img src='./images/$result[Immagine]'>
                        </div>";
                        }
                }
            }catch(PDOException $e){
    
            }
    
          }

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['invia']) and strcmp($_POST['messaggio'], "") != 0 and $_POST['messaggio'] != null){
            inserisciMessaggio();
        }

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['inserisci']) and strcmp($_POST['immagine'], "") != 0 and $_POST['immagine'] != null){
            inserisciImmagine();
        }

        function inserisciMessaggio(){
            //chiamo la stored proceure e ricarico la pagina
            $messaggio = $_POST['messaggio'];
            $rimpiazza = str_replace("'", "&apos;",$messaggio);
            try{
                $GLOBALS['conn'] -> query("CALL InserisciMessaggio('$rimpiazza')");

                header("Location: Bacheca.php");
            }catch(PDOException $e){
    
            }
        }

        function inserisciImmagine(){
            //chiamo la stored proceure e ricarico la pagina
            $immagine = $_POST['immagine'];
            try{
                $GLOBALS['conn'] -> query("CALL InserisciImmagine('$immagine')");

                header("Location: Bacheca.php");
            }catch(PDOException $e){
    
            }
        }
      ?>
    </div>
     <div id="dashboards">
        <div>
            <form action="" method="POST">
                <input class="operazione" type="text" name="messaggio" value="" placeholder="Messaggio">
                <input class="operazione" type="submit" name="invia" value="INVIA">
            </form>
        </div>
        <div>
            <form action="" method="POST">
                <input class="operazione" type="file" name="immagine" value="">
                <input class="operazione" type="submit" name="inserisci" value="INSERISCI">
            </form>
        </div>
    </div>
    </div>
  </body>
</html>
