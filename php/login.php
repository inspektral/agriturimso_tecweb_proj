<?php 
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";
use DBAccess;

session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "accedi.html");

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $dbAccess = new DbAccess();
    $success = $dbAccess->openDBConnection();

    if (!$success) {
        die("Errore nell'apertura del DB");
    }

    $user = $dbAccess->loginUser($email, $password);
    $dbAccess->closeConnection();
    echo $user;
    $content = "";
    if ($user) {        
        $_SESSION["user"] = $user;
        $_SESSION["isAdmin"] = $user["email"] === "admin";
        header("Location: index.php")
    }else{
        $content = "<strong class=\"error\">Credenziali errate</strong>";
    }

    $html = str_replace("<LoginErrorPlaceholder />", $content);
    echo $html;
}
?>