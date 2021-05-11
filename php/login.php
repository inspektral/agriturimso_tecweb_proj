<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";
// use DBAccess;

session_start();
$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "accedi.html");

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $dbAccess = new DBAccess();
    $success = $dbAccess->openDBConnection();

    if (! $success) {
        header("url=errors/500.html");
    }

    $user = $dbAccess->checkLogin($email, $password)["nome"];

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

    $html = str_replace("<LoginErrorPlaceholder />", $content);
    echo $html;
}
?>
