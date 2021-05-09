<?php 
//require_once 'login.php';
session_start();

$htmlPage = file_get_contents("index.html");

$strAccedi = '<a href="registrati.html" class="reg">Registrati</a> <a href="accedi.html" class="reg">Accedi</a>';

if(isset($_SESSION["username"])) $strAccedi = "<a href='logout.php' class='logout'>Logout</a><p class='userName'>Benvenuto: " . $_SESSION["username"] . "</p>";

echo str_replace("<bottoniLogin/>", $strAccedi, $htmlPage);

?>