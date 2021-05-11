<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbAccess.php";

session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "registrati.html");

$userContent = "<div id=\"user\">";
if (isset($_SESSION['email'])) {        
  $email = $_SESSION["email"];
  $userContent .= "<p class='reg'>Benvenuto: " . $email . "</p>";
} else {
  $userContent .= "<span id=\"currentLink\" class=\"reg\">Registrati</span>";
  $userContent .= "<a href=\"../php/login.php\" class=\"reg\">Accedi</a>";
}
$userContent .= "</div>";

$errorContent = "<div><ul>";
if (isset($_POST["nome"]) && isset($_POST["cognome"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];
  $name = $_POST["nome"];
  $lastname = $_POST["cognome"];

  $dbAccess = new DBAccess();
  $isSuccess = $dbAccess->openDBConnection();

  if ($isSuccess) {
    $errorContent .= "<li><strong class=\"error\">Errore durante la connsessione al database</strong></li>";
  } else {
    $result = $dbAccess->signupUser($name, $lastname, $email, $password);
    $dbAccess->closeDBConnection(); 

    if ($result["isSuccessful"]) {        
      $_SESSION["username"] = $result["userEmail"];
      $_SESSION["isAdmin"] = $result["userEmail"] === "admin";
      header("Location: index.php");
    } else {
      $errorContent .= "<li><strong class=\"error\">Errore durante la registrazione</strong></li>";
    }
  }
}
$errorContent .= "</ul></div>";

$html = str_replace("<UserPlaceholder />", $userContent, $html);
$html = str_replace("<SignupErrorPlaceholder />", $errorContent, $html);
echo $html;
?>