<?php 
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";

session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "accedi.html");

$userContent = "<div id=\"user\">";
if (isset($_SESSION['email'])) {        
  $email = $_SESSION["email"];
  $userContent .= "<p class='reg'>Benvenuto: " . $email . "</p>";
} else {
  $userContent .= "<a href=\"../php/signup.php\" class=\"reg\">Registrati</a>";
  $userContent .= "<span id=\"currentLink\" class=\"reg\">Accedi</span>";
}
$userContent .= "</div>";

$errorContent = "<div><ul>";
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
    $username = $_POST["email"];
    $password = $_POST["password"];

    $dbAccess = new DBAccess();
    $isSuccess = $dbAccess->openDBConnection();

    if ($isSuccess) {
        $errorContent .= "<li><strong class=\"error\">Errore durante la connsessione al database</strong></li>";
    } else {
        $user = $dbAccess->loginUser($username, $password);
        $dbAccess->closeDBConnection(); 

        if ($user) {        
            $_SESSION["email"] = $user["email"];
            $_SESSION["isAdmin"] = $user["email"] === "admin";
            header("Location: index.php");
        } else {
            $errorContent .= "<li><strong class=\"error\">Credenziali errate</strong></li>";
        }
    }
}
$errorContent .= "</ul></div>";

$html = str_replace("<UserPlaceholder />", $userContent, $html);
$html = str_replace("<LoginErrorPlaceholder />", $errorContent, $html);
echo $html;
?>