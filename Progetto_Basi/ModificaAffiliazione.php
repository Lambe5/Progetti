<?php 
        require_once "./ConnSQL.php"; 

        session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Modifica Affiliazione Universitaria</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Modifica Affiliazione</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <div id="contenitore" class="m-3">
        <form method="POST" action="">
            <?php
                $conn = ConnSQL::DBConnection();


                $sqlTipoUtente = $conn->prepare("SELECT * FROM username_tipoutente");
                $sqlTipoUtente->execute();
                $tipo_str = "";
                if ($sqlTipoUtente->rowCount() > 0) {
        
                    try {
                        while ($result = $sqlTipoUtente->fetch(PDO::FETCH_ASSOC)) {
        
                            if ($result['Username'] == $_SESSION['username']) {
        
                                switch ($result['Tipo']) {
                                    case 'Speaker':
                                        $tipo_str = "Speaker";
                                        //devo farlo in base al tipo di utente
                                        $CV = $conn -> prepare("SELECT NomeUni, NomeDip FROM SPEAKER WHERE (UsernameUtente = '$_SESSION[username]')");
                                        $CV -> execute();
                                        if ($CV->rowCount() > 0) {
                                            try{
                                                
                                                while($result = $CV->fetch(PDO::FETCH_ASSOC)){
                                                    $uni = $result['NomeUni'];
                                                    $dip = $result['NomeDip'];
                                                }

                                                echo "<input type='textarea' name='nomeUni' value='$uni' placeholder='Nome Uni' maxlength='30'>";
                                                echo "<input type='textarea' name='nomeDip' value='$dip' placeholder='Nome Dip' maxlength='30'>";

                                            }catch(PDOException $e){
                                                echo $e;
                                            }
                                        } else {
                                            echo "<input type='textarea' name='nomeUni' value='' placeholder='Nome Uni' maxlength='30'>";
                                            echo "<input type='textarea' name='nomeDip' value='' placeholder='Nome Dip' maxlength='30'>";
                                        }

                                        $conn = null;
                                        break;
                                    case 'Presenter':
                                        $tipo_str = "Presenter";
                                        //devo farlo in base al tipo di utente
                                        $CV = $conn -> prepare("SELECT NomeUni, NomeDip FROM PRESENTER WHERE (UsernameUtente = '$_SESSION[username]')");
                                        $CV -> execute();
                                        if ($CV->rowCount() > 0) {
                                            try{
                                                
                                                while($result = $CV->fetch(PDO::FETCH_ASSOC)){
                                                    $uni = $result['NomeUni'];
                                                    $dip = $result['NomeDip'];
                                                }

                                                echo "<input type='textarea' name='nomeUni' value='$uni' placeholder='Nome Uni' maxlength='30'>";
                                                echo "<input type='textarea' name='nomeDip' value='$dip' placeholder='Nome Dip' maxlength='30'>";

                                            }catch(PDOException $e){
                                                echo $e;
                                            }
                                        } else {
                                            echo "<input type='textarea' name='nomeUni' value='' placeholder='Nome Uni' maxlength='30'>";
                                            echo "<input type='textarea' name='nomeDip' value='' placeholder='Nome Dip' maxlength='30'>";
                                        }

                                        $conn = null;
                                        break;
                                }
                            }
                        }
                    } catch (PDOException $e) {
                        echo $e;
                    }
                }   
            ?>
            <input type="submit" class="btn btn-success" name="modifica" value="MODIFICA">
        </form>
    </div>

<?php

    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['modifica'])){
        modificaAffiliazione();
    }
   
    function modificaAffiliazione(){
        $conn = ConnSQL::DBConnection();
        try{

            switch ($GLOBALS['tipo_str']) {
                case 'Speaker':
                    //devo chiamare la stored procedure giusta in base al tipo di utente
                    $conn -> query("CALL ModificaAffiliazioneSpeaker('$_SESSION[username]', '$_POST[nomeUni]', '$_POST[nomeDip]')");
                    break;
                case 'Presenter':
                    //devo chiamare la stored procedure giusta in base al tipo di utente
                    $conn -> query("CALL ModificaAffiliazionePresenter('$_SESSION[username]', '$_POST[nomeUni]', '$_POST[nomeDip]')");
                    break;
            }

        }catch(PDOException $e){
            echo $e;
        }
        
        header("Refresh:0");
          
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
        backPHP();
    }

    function backPHP(){
        //carcio la pagina corretta in base al tipo di utente
        $conn = ConnSQL::DBConnection();

        $sqlTipoUtente = $conn->prepare("SELECT * FROM username_tipoutente");
            $sqlTipoUtente->execute();
            if ($sqlTipoUtente->rowCount() > 0) {

                try {
                    while ($result = $sqlTipoUtente->fetch(PDO::FETCH_ASSOC)) {

                        if ($result['Username'] == $_SESSION['username']) {

                            switch ($result['Tipo']) {
                                case 'Speaker':
                                    $conn = null;
                                    header("Location:SpeakerPage.php");
                                    break;
                                case 'Presenter':
                                    $conn = null;
                                    header("Location:PresenterPage.php");
                                    break;
                            }
                        }
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
            }
        }

?>

</body>
</html>