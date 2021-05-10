<?php 
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";

session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "accedi.html");
$content = "<div><ul>";

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $dbAccess = new DBAccess();
    $isSuccess = $dbAccess->openDBConnection();
    
    if ($isSuccess) {
        $content .= "<li><strong class=\"error\">Errore durante la connsessione al database</strong></li>";
    } else {
        $user = $dbAccess->loginUser($username, $password);
        $dbAccess->closeDBConnection(); 

        if ($user) {        
            $_SESSION["username"] = $user["username"];
            $_SESSION["isAdmin"] = $user["username"] === "admin";
            header("Location: index.php");
        } else {
            $content .= "<li><strong class=\"error\">Credenziali errate</strong></li>";
        }
    }
}
$content .= "</ul></div>";

echo str_replace("<LoginErrorPlaceholder />", $content, $html);
?>