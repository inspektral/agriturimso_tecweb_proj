<?php 
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";

session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "accedi.html");

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $dbAccess = new DbAccess();
    $isSuccess = $dbAccess->openDBConnection();
    if ($isSuccess) {
        die("Errore durante la connsessione al database");
    }

    $user = $dbAccess->loginUser($username, $password);
    $dbAccess->closeDBConnection(); 

    $content = "";
    if ($user) {        
        $_SESSION["user"] = $user["username"];
        $_SESSION["isAdmin"] = $user["username"] === "admin";
        header("Location: index.php");
    } else {
        $content = "<strong class=\"error\">Credenziali errate</strong>";
    }

    $html = str_replace("<LoginErrorPlaceholder />", $content, $html);
    echo $html;
}
?>