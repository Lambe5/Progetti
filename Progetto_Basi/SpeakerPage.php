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
    <title>Pagina Speaker</title>
</head>
<body class="bg-info">

    <div class="bg-primary border-bottom border-dark p-2">
        <h1 class="display-4 text-center m-3">Benvenuto <?php echo $_SESSION['username'] ?></h1>
    </div>

    <div id="operazioni">
        <h3>Scegli cosa fare!</h3>
        <br>
        <ul class="list-group w-25 m-3">
            <li class="list-group-item"><a href="./Homepage.php">Torna alla Homepage</a></li>
            <li class="list-group-item"><a href="./ModificaCV.php">Modifica CV</a></li>
            <li class="list-group-item"><a href="./ModificaFoto.php">Modifica foto</a></li>
            <li class="list-group-item"><a href="./ModificaAffiliazione.php">Modifica affiliazione universitaria</a></li>
            <li class="list-group-item"><a href="./AggiungiLinkDescrizione.php">Aggiungi un link e una descrizione</a></li>
            <li class="list-group-item"><a href="./ListaMieiTutorial.php">Visualizza i tutorial a cui appartengo</a></li>
            <li class="list-group-item"><a href="./VisualizzaConferenze.php">Visualizza le conferenze disponibili</a></li>
            <li class="list-group-item"><a href="./RegistrazioneConferenza.php">Registrati ad una conferenza</a></li>
            <li class="list-group-item"><a href="./PresentazioniSessioni.php">Visualizza le sessioni e le relative presentazioni</a></li>
            <li class="list-group-item"><a href="./PresentazioniPreferite.php">Visualizza/inserisci nella tua lista delle presentazioni preferite</a></li>
            <li class="list-group-item"><a href="./AllChat.php">Vai alle chat</a></li>
        </ul>
    </div>

    <style>
        a{
            color: black;
        }

        h3{
            margin: 1%;
        }

    </style>


</body>
</html>