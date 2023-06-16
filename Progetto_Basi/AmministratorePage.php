<!DOCTYPE html>
<html lang="en">

<?php
require_once "./ConnSQL.php";
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Welcome to Amministratore Page </title>

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
        <h1 class="display-4 text-center "> Amministratore Page</h1>
    </div>

    <?php
        $_SESSION['indietro'] = true;
    ?>

    <div id="container" class="p-4">
        <h4>Benvenuto/a <?php echo $username = $_SESSION['username'] ?>! Questa è la pagina dell'amministratore </h4>
    </div>
    <div id="operazioni">
        <h3 class="m-3">Scegli cosa fare!</h3>
        <br>
        <ul class="list-group w-50 m-3">
            <li class="list-group-item"><a href="./Homepage.php">Torna alla Homepage</a></li>

            <li class="list-group-item"><a href="./creaConferenza.php">Crea una nuova conferenza</a></li>
            <li class="list-group-item"><a href="./InserisciProgramma.php">Inserisci programma</a></li>
            <li class="list-group-item"><a href="./creaSessione.php">Crea una nuova sessione della conferenza</a></li>
            <li class="list-group-item"><a href="./CreaPresentazione.php">Crea una nuova presentazione</a></li>
            <li class="list-group-item"><a href="./AssociaSpeakerTutorial.php">Associa speaker ad un tutorial</a></li>
            <li class="list-group-item"><a href="./AssociaPresenterArticolo.php">Associa presenter ad un articolo</a>
            </li>
            <li class="list-group-item"><a href="./InserisciValutazione.php">Inserisci una valutazione</a></li>
            <li class="list-group-item"><a href="./InserisciSponsor.php">Inserisci uno sponsor/sponsorizzazione</a>
        </li>
            <li class="list-group-item"><a href="./InserisciParoleChiave.php">Inserisci parole chiave</a></li>
            <li class="list-group-item"><a href="./VisualizzaConferenze.php">Visualizza le conferenze disponibili</a>
        </li>

            <li class="list-group-item"><a href="./RegistrazioneConferenza.php">Registrati ad una conferenza</a></li>
            <li class="list-group-item"><a href="./PresentazioniSessioni.php">Visualizza le sessioni e le relative
                    presentazioni</a></li>
            <li class="list-group-item"><a href="./PresentazioniPreferite.php">Visualizza/inserisci nella tua lista
                    delle presentazioni preferite</a></li>
            <li class="list-group-item"><a href="./AllChat.php">Vai alle chat</a></li>
        </ul>
    </div>
</body>

</html>

<style>
a {
    color: black;
}
</style>