<!DOCTYPE html>
<?php
require_once "./ConnSQL.php";
require_once "ConnMongoDB.php";
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Programma</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Programma Giornaliero</h1>
    </div>

    <?php 
        if($_SESSION['indietro'])
        {
            echo "<div>
            <form action='' method='POST' class='m-3'>
                <input type='submit' name='back' value='BACK'>
            </form>
            </div>";
        } else {
            echo "<div>
            <h3 class = 'm-3'>Inserisci almeno un programma nella nuova conferenza</h3>
            </div>";
        }
    ?>

    <?php
        $conn = ConnSQL::DBConnection();
        $username = $_SESSION['username'];

        //cerco codici delle conferenze attive a cui sono iscirtto

        $acronimiConferenze = $conn -> prepare("SELECT Acronimo FROM CONFERENZA, REGISTRAZIONE WHERE UsernameUtente = '$username'
                                                    AND Acronimo = AcronimoConferenza AND Svolgimento = 'ATTIVA'");
        $acronimiConferenze -> execute();

        $conn = null;
    ?>

    <div id="tabella">
            <table class="table table-hover bg-light w-25 m-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Acronimo</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaConferenze() ?>
                </tbody>
            </table>
    </div>

    <form action="" method="POST" class="m-3">
        <select name="acronimi">
            <option>Scegli Conferenza:</option>
            <?php
            foreach ($acronimiConferenze as $row) {
                $acronimo = $row['Acronimo'];
                echo ("<option value='$acronimo'>$acronimo</option>");
            }
            ?>
        </select>
        <input type="number" min="1900" max="2099" step="1" value="2022" name = 'anno' required/>
        <input type="date" placeholder="Data " name="data" required>
        <input type="submit" name="inserisci" value="INSERISCI">
    </form>

    <?php
        
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['inserisci'])) {
            inserisciProgramma();
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back'])) {
            backPHP();
        }

        function stampaConferenze(){
            $conn = ConnSQL::DBConnection();

            
            $sql = $conn -> prepare("SELECT Acronimo, Data FROM CONFERENZA, PROGRAMMA_GIORNALIERO, REGISTRAZIONE WHERE UsernameUtente = '$GLOBALS[username]'
                                    AND Acronimo = REGISTRAZIONE.AcronimoConferenza AND Acronimo = PROGRAMMA_GIORNALIERO.AcronimoConferenza
                                    AND Svolgimento = 'ATTIVA'");
            $sql -> execute();

            $out = "";

            if ($sql->rowCount() > 0) {
                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                    $out .= "<tr> <td>". $row['Acronimo'] ."</td>";
                    $out .= "<td>". $row['Data'] ."</td></tr>";
                }
                echo $out;
            }

            $conn = null;
        }


        function inserisciProgramma(){
            $conn = ConnSQL::DBConnection();
            $acronimo = $_POST['acronimi'];
            $data = $_POST['data'];
            $anno = $_POST['anno'];
            //faccio una select per vedere se esiste una conferenza con quell'acronimi in quell'anno

            $verificaConf = $conn -> prepare("SELECT Acronimo, AnnoEdizione FROM CONFERENZA WHERE Acronimo = '$acronimo' AND AnnoEdizione = '$anno'");
            $verificaConf -> execute();

            if($verificaConf->rowCount() > 0){
                try{
                    if(!$_SESSION['indietro'])
                    {
                        if($acronimo == "Scegli Conferenza:"){
                            echo "<p id='errore' class = 'm-3'>Seleziona una conferenza</p>";
                            echo "<style>
                            #errore{
                                color: red;
                            }
                            </style>";
                        }else if($acronimo != $_SESSION['acronimo']){      
                            echo "<p id='errore' class = 'm-3'>Seleziona la conferenza appena creata</p>";
                            echo "<style>
                            #errore{
                                color: red;
                            }
                            </style>";
                        }else{
                            $conn -> query("CALL CreaProgrammaGiornaliero('$acronimo', '$anno', '$data')");
                            $_SESSION['indietro'] = true;
                            //Aggiunto evento al logs di MongoDB
                            ConnMongoDB::insertDocumentInLogs("Creato un nuovo programma");
                            header("Location: AmministratorePage.php");
                        }
                    } else{
                        if($acronimo != "Scegli Conferenza:"){
                            $conn -> query("CALL CreaProgrammaGiornaliero('$acronimo', '$anno', '$data')");
                            //Aggiunto evento al logs di MongoDB
                            ConnMongoDB::insertDocumentInLogs("Creato un nuovo programma");
                            header("Refresh:0");
                        }else{
                            echo "<p id='errore' class = 'm-3'>Seleziona una conferenza</p>";
                            echo "<style>
                            #errore{
                                color: red;
                            }
                        </style>";
                        }
                    }
                }catch(PDOException $e){
                    echo $e;
                }
            } else {
                echo "<p id='errore' class = 'm-3'>Non esiste nessuna conferenza con queste caratteriestiche</p>";
                echo "<style>
                            #errore{
                                color: red;
                            }
                            </style>";
            }           
        }

        function backPHP(){
            header("Location:AmministratorePage.php");
        }

    ?>
    
</body>
</html>