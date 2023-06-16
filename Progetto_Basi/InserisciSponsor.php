<!DOCTYPE html>
<?php
require_once "./ConnSQL.php";
require_once "./ConnMongoDB.php";
session_start();

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>sponsor</title>
</head>

<body class="bg-info">
    <div id="title" class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center ">Sponsor</h1>
    </div>
    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <div class="m-3">
    <h3>Inserisci Sponsor:</h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" placeholder="Nome " name="nome" maxlength='30' required>
        <table>
            <tr>
                <td>Seleziona logo:</td>
                <td><input type='file' name="logo" required></td>
            </tr>
        </table>
        <input type="submit" name="creaSponsor" value="CREA">
    </form>

    </div>



    <?php
    $conn = ConnSQL::DBConnection();
    $username=$_SESSION["username"];

    try {

    $sponsor = $conn -> prepare("SELECT * FROM SPONSOR");
    $sponsor -> execute();

    $acronimi = $conn -> prepare("SELECT Acronimo FROM CONFERENZA, REGISTRAZIONE
                                    WHERE Acronimo = AcronimoConferenza AND AnnoEdizione = AnnoEdizioneConferenza
                                    AND Svolgimento='ATTIVA' AND UsernameUtente='$username'");
    $acronimi -> execute();

    }catch (PDOException $e) {
        echo $e;
    }

    ?>

    <div class="m-3">
    <h3>Inserisci Sponsorizzazione:</h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <span>Sponsor:</span>
        <select name="sponsor">
            <option>Seleziona:</option>
            <?php
                foreach ($sponsor as $row) {
                $nomeSponsor = $row['Nome'];
                echo ("<option value='$nomeSponsor'>$nomeSponsor</option>");
            }
            ?>
            </select>
            <input type="text" placeholder="Importo" name="importo" required>
            <span>Conferenza:</span>
            <select name="acronimiConferenze">
                <option>Seleziona:</option>
                <?php
            foreach ($acronimi as $row) {
                $acronimo = $row['Acronimo'];
                echo ("<option value='$acronimo'>$acronimo</option>");
            }
            ?>

        </select>
        <input type="number" min="1900" max="2099" step="1" value="2022" name = 'anno' required/>
        <input type="submit" name="creaSponsorizzazione" value="CREA">
    </form>

    </div>

    <?php

    function creaSponsor()
    {
        $conn = ConnSQL::DBConnection();
        try {
            $nome = $_POST["nome"];
            $uploadFile = $_FILES['logo']['tmp_name'];
            $foto = (file_get_contents($uploadFile));

            $creaSponsor = "CALL InserisciSponsor(:nome, :logo)";
            $statement = $conn->prepare($creaSponsor);
            $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
            $statement->bindParam(':logo', $foto, PDO::PARAM_LOB);
            $statement->execute();

            //Aggiunto evento al logs di MongoDB
            ConnMongoDB::insertDocumentInLogs("Inserito un nuovo Sponsor");

            header("Location:AmministratorePage.php");

            
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function creaSponsorizzazione()
    {
        $conn = ConnSQL::DBConnection();
        try {

            $nomeSponsor = $_POST["sponsor"];
            $acronimoConf = $_POST["acronimiConferenze"];
            $importo = floatval($_POST['importo']);
            $anno = $_POST['anno'];
            //faccio una select per vedere se esiste una conferenza con quell'acronimi in quell'anno

            $verificaConf = $conn -> prepare("SELECT Acronimo, AnnoEdizione FROM CONFERENZA WHERE Acronimo = '$acronimoConf' AND AnnoEdizione = '$anno'");
            $verificaConf -> execute();

            if($verificaConf->rowCount() > 0){
                if($importo > 0){
                    $conn -> query("CALL InserisciSponsorizzazione('$nomeSponsor','$acronimoConf', '$anno', '$importo')"); 
    
                    //Aggiunto evento al logs di MongoDB
                    ConnMongoDB::insertDocumentInLogs("Inserita una nuova Sponsorizzazione");
    
                    header("Location:AmministratorePage.php");
                } else {
                    echo "<p id='errore' class='m-3'>Bisogna inserire un importo</p>";
                    echo "<style>#errore{color:red;}</style>";
                }
            } else {
                echo "<p id='errore' class='m-3'>Non esiste nessuna conferenza con queste caratteriestiche</p>";
                echo "<style>#errore{color:red;}</style>";
            }

        } catch (PDOException $e) {
            echo $e;
        }
    }



    ?>

    <?php
         if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }
         if(isset($_POST['creaSponsor']))
        creaSponsor();

    if(isset($_POST['creaSponsorizzazione']))
        creaSponsorizzazione();

        function backPHP(){            
             header("Location:AmministratorePage.php");
        }

    ?>
</body>

</html>