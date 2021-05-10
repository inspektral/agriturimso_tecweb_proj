<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";
// use DBAccess;

session_start();

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $dbAccess = new DBAccess();
    $success = $dbAccess->openDBConnection();

    if (! $success) {
        header("url=errors/500.html");
    }

    $user = $dbAccess->checkLogin($email, $password)["Username"];

    if ($user) {
        $dbAccess->closeConnection();
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $_SESSION["user"] = $user;
            $_SESSION["password"] = $_POST["password"];
            header("Location: http://localhost/index.php");
        }
    } else {
        $_SESSION["errLog"] = true;
        header("Location: http://localhost/accedi.php");
    }
}
//DELETE FROM `utenti` WHERE `email` = "asd"
?>