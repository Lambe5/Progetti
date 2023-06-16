<!DOCTYPE html>
<?php require_once "./ConnSQL.php";
session_start(); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Conferenze</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center">Lista conferenze disponibili</h1>
    </div>

    <div>
        <form action="" method="POST" class="m-3">
            <input id="btntest" type="button" value="BACK" 
                onclick="window.location.href = '<?php echo backPHP(); ?>'" />
                
        </form>
            
    </div>
    
    <div id="tabella">
            <table id="presenter_speaker_votomedio" class="table table-hover bg-light w-25 m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" id="acronimoCol">Acronimo</th>
                        <th scope="col" id="nomeCol">Nome</th>
                        <th scope="col" id="logoCol">ImgLogo</th>
                        <th scope="col" id="annoCol">AnnoEdizione</th>
                        <th scope="col" id="programma">Programma</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaConferenze() ?>
                </tbody>
            </table>
    </div>

    

    <?php

        function stampaConferenze(){
            $conn = ConnSQL::DBConnection();

            $sql = $conn -> prepare("SELECT Acronimo, Nome, ImgLogo, AnnoEdizione FROM CONFERENZA WHERE Svolgimento= 'Attiva';");
            $sql -> execute();

            $out = "";

            if ($sql->rowCount() > 0) {
                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                    $out .= "<tr> <td>". $row['Acronimo'] ."</td>";
                    $out .= "<td>". $row['Nome'] ."</td>";
                    $out .= "<td>". '<img class="rounded float-left w-100" 
                    src="data:image/png;base64,' . base64_encode($row['ImgLogo']) . '"/>' ."</td>";
                    $out .= "<td>". $row['AnnoEdizione'] ."</td>";
                    $out .= "<td>  <form  action='ProgrammaConferenza.php' method='GET'>
                                        <input type='submit' name='acronimo' value='$row[Acronimo]'>
                                    </form> </td> </tr>";
                }
                echo $out;
            }

            $conn = null;
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }

        function backPHP(){
            //carcio la pagina corretta in base al tipo di utente
            $conn = ConnSQL::DBConnection();

            $sqlTipoUtente = $conn->prepare("SELECT * FROM username_tipoutente");
                $sqlTipoUtente->execute();
                $trovato = false;
                $link = "";
                if ($sqlTipoUtente->rowCount() > 0) {

                    try {
                        while ($result = $sqlTipoUtente->fetch(PDO::FETCH_ASSOC)) {

                            if ($result['Username'] == $_SESSION['username']) {

                                switch ($result['Tipo']) {
                                    case 'Amministratore':
                                        $conn = null;
                                        return $link = './AmministratorePage.php';
                                        break;
                                    case 'Speaker':
                                        $conn = null;
                                        return $link = './SpeakerPage.php';
                                        break;
                                    case 'Presenter':
                                        $conn = null;
                                        return $link = './Presenteréage.php';
                                        break;
                                }
                                $trovato = true;
                            }
                        }
                        $conn = null;
                        if ($trovato == false)
                            return $link = './GenericoPage.php';
                    } catch (PDOException $e) {
                        echo $e;
                    }
                }
            }   
    ?>

</body>
</html>