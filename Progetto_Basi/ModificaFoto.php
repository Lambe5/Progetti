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
    <title>Modifica Foto</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Modifica Foto</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input type="submit" name="back" value="BACK">
        </form>
    </div>

    <div id="contenitore" class="m-3">
        <form method="POST" action="" enctype="multipart/form-data">
            <p>Immagine attuale:</p>
            <?php
                //mi prendo il valore della foto attuale e lo metto in un input tipo image
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
                                        $CV = $conn -> prepare("SELECT Foto FROM SPEAKER WHERE (UsernameUtente = '$_SESSION[username]')");
                                        $CV -> execute();
                                        if ($CV->rowCount() > 0) {
                                            try{
                                                
                                                while($result = $CV->fetch(PDO::FETCH_ASSOC)){
                                                    $modifica = $result['Foto'];
                                                }

                                                echo '<img class="rounded float-left w-25 m-3" src="data:image/*;base64,'
                                                 . base64_encode($modifica) . '"/>';
                                                    
                                            }catch(PDOException $e){
                                                echo $e;
                                            }
                                        }

                                        $conn = null;
                                        break;
                                    case 'Presenter':
                                        $tipo_str = "Presenter";
                                        //devo farlo in base al tipo di utente
                                        $CV = $conn -> prepare("SELECT Foto FROM PRESENTER WHERE (UsernameUtente = '$_SESSION[username]')");
                                        $CV -> execute();
                                        if ($CV->rowCount() > 0) {
                                            try{
                                                
                                                while($result = $CV->fetch(PDO::FETCH_ASSOC)){
                                                    $modifica = $result['Foto'];
                                                }

                                                echo '<img class="rounded float-left w-25" src="data:image/*;base64,'
                                                 . base64_encode($modifica) . '"/>';
                                                    
                                            }catch(PDOException $e){
                                                echo $e;
                                            }
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
            <p class="from2">Nuova immagine:</p>
            <input type="file" alt="Submit" id="foto" name="newFoto" class="altroUtente" placeholder="Inserisci foto" accept="image/*">
            <input id="modifica" type="submit" class="btn btn-success" name="modifica" value="MODIFICA">
        </form>
    </div>

<?php

    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['modifica'])){
        modificaFoto();
    }
   
    function modificaFoto(){
        $conn = ConnSQL::DBConnection();
        try{

            switch ($GLOBALS['tipo_str']) {
                case 'Speaker':
                    //devo chiamare la stored procedure giusta in base al tipo di utente
                    $isImage=true;
                    if ($_FILES['newFoto']['tmp_name'] != "") {
                        $image = file_get_contents($_FILES['newFoto']['tmp_name']);
                        $typeOfFile = mime_content_type($_FILES['newFoto']['tmp_name']);
                        strcmp(substr($typeOfFile, 0, 5),"image") ==0 ? $isImage=true : $isImage=false;
                    } else $image = "";
                    if (filesize($_FILES['newFoto']['tmp_name']) <= 1048576 && $isImage){
                        try{
                            $salvaInCrea_Conferenza = $conn->prepare("CALL ModificaFotoSpeaker(:username,:image)");
                            $salvaInCrea_Conferenza->execute([
                                'username'  => $_SESSION['username'],
                                'image' => $image,
                            ]);
                        }catch(PDOException $e){
                            echo $e;
                        }
                    }
                    break;
                case 'Presenter':
                    $isImage=true;
                    if ($_FILES['newFoto']['tmp_name'] != "") {
                        $image = file_get_contents($_FILES['newFoto']['tmp_name']);
                        $typeOfFile = mime_content_type($_FILES['newFoto']['tmp_name']);
                        strcmp(substr($typeOfFile, 0, 5),"image") ==0 ? $isImage=true : $isImage=false;
                    } else $image = "";
                    if (filesize($_FILES['newFoto']['tmp_name']) <= 1048576 && $isImage){
                        try{
                            $salvaInCrea_Conferenza = $conn->prepare("CALL ModificaFotoPresenter(:username,:image)");
                            $salvaInCrea_Conferenza->execute([
                                'username'  => $_SESSION['username'],
                                'image' => $image,
                            ]);
                        }catch(PDOException $e){
                            echo $e;
                        }
                    }
                    break;
            }
            
        }catch(PDOException $e){
            echo $e;
        }

       echo "<script type='text/javascript'>
       window.location.href = './ModificaFoto.php';
       </script>";
            
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
                                    echo "<script type='text/javascript'>
                                    window.location.href = './SpeakerPage.php';
                                    </script>";
                                    break;
                                case 'Presenter':
                                    $conn = null;
                                    echo "<script type='text/javascript'>
                                    window.location.href = './PresenterPage.php';
                                    </script>";
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