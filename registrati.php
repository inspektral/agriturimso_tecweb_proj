<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . "php/dbAccess.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "php/userNameMenu.php";

$htmlPage = file_get_contents("registrati.html");

$userAcc = new userNameMenu();

$strAccedi = $userAcc->getAccedi(true);

if (isset($_SESSION["user"])) {
    $strAccedi = $userAcc->loginSucc();
}

if (isset($_POST["nome"]) && isset($_POST["cognome"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    // mi sto registrando
    $dbAccess = new DBAccess();
    $conn = $dbAccess->openDBConnection();

    if (! $conn) {
        header("url=errors/500.html");
    }

    if (! $dbAccess->checkMail($_POST["email"])) {
        $dbAccess->insertUser($_POST["nome"], $_POST["cognome"], $_POST["email"], $_POST["password"]);
        $dbAccess->closeConnection();
        echo "<h1>Registrazione effettuata, verrai reindirizzato tra 2 secondi</h1>";
        header( "refresh:2;url= http://localhost/index.php" );
    } else {
        $dbAccess->closeConnection();
        $htmlPage = str_replace("<mailpwdErr/>", '<p class="errorLogin"><span xml:lang="en">Email</span> gi&agrave; presente</p>', $htmlPage);
    }
}

echo str_replace("<bottoniLogin/>", $strAccedi, $htmlPage);
?>