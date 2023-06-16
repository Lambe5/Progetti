<html lang="en">
<?php require_once "./ConnSQL.php"; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Welcome to the Homepage </title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

</head>

<body class="bg-info">

    <div id="title" class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center "> Benvenuto/a  nella Homepage </h1>
    </div>

    <div>
        <form action="Login.php" method="GET" class="m-3">
            <input type="submit" name="login" value="LOGIN">
        </form>
    </div>
    

    <div id="container" class="p-4">

        <div id="ListNumTot" class="w-25 mb-3">

            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Numero totale di conferenze registrate
                    <span class="badge badge-dark badge"> <?php stampaNumTotConfReg() ?> </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Numero totale di conferenze attive
                    <span class="badge badge-dark badge"><?php stampaNumTotConfAttive() ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Numero totale di utenti registrati
                    <span class="badge badge-dark badge"> <?php stampaNumTotUtenti() ?></span>
                </li>
            </ul>

        </div>

        <!-- Visualizzare la classifica dei presenter/speaker sulla base del voto medio -->
        <div id="tablevotomed">
            <table id="presenter_speaker_votomedio" class="table table-hover bg-light w-25 ">
                <caption class="text-dark"> Classifica sulla base del voto medio </caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" id="usernameCol">Username</th>
                        <th scope="col" id="votomedCol">Voto Medio</th>
                        <th scope="col" id="tipoCol">Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php stampaVotiMedi() ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>

<?php

function stampaNumTotConfReg(){
    //Connessione con il db stabilita
    $conn = ConnSQL::DBConnection();
    //chiamo la view che conta il numero TOT di conferenze registrate
    $sql= $conn->prepare("SELECT count(*) as TotConferenze FROM CONFERENZA; ");
    $sql->execute();

    if($sql->rowCount() > 0){

        $result = $sql->fetch(PDO::FETCH_ASSOC);

        echo $result['TotConferenze'];
        
    } else echo "Non c'è niente da stampare...";

    $conn=null;
}
function stampaNumTotConfAttive(){

    $conn=ConnSQL::DBConnection();
    $sql=$conn->prepare("SELECT count(*) as TotConferenzeAttive  FROM CONFERENZA WHERE Svolgimento='Attiva'; ");
    $sql->execute();

    if( $sql->rowCount() > 0){
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        echo $result['TotConferenzeAttive'];
    } else "Non c'è niente da stampare...";
}
function stampaNumTotUtenti(){

    $conn=ConnSQL::DBConnection();
    $sql=$conn->prepare("SELECT count(*) as TotUtenti FROM UTENTE;");
    $sql->execute();

    if($sql->rowCount() > 0){

        $result = $sql->fetch(PDO::FETCH_ASSOC);

        echo $result['TotUtenti'];

    } else "Non c'è niente da stampare...";

    $conn=null;
}
function stampaVotiMedi() {

    //Connessione con il db stabilita
    $conn = ConnSQL::DBConnection();
    //Chiamo la view che mostra i voti medi di speakers/presenters
    $sql = $conn->prepare("SELECT * FROM presenter_speaker_votomedio;");
    $sql->execute();
   

    if ($sql->rowCount() > 0) {

        $out="";
        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
           
            $out .= "<tr> <td>". $result['Username'] ."</td>";
            $out .= "<td>".number_format($result['VotoMed'], 1, ',', '') ."</td>";
            $out .= "<td>". $result['Tipo'] ."</td> </tr>";
        }

        echo $out ;
    } else echo "Non c'è niente da stampare...";

    $conn = null; //interrompo la connessione col db
}

?>