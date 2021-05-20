<?php
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";

session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."registrati.html");

$menu = new UserMenu();
$userContent = "";
if (isset($_SESSION['email'])) {        
  $userContent = $menu->getWelcomeMessage($_SESSION['email']);
} else {
    $userContent = $menu->getAuthenticationButtons(false, true);
}

$userFeedbackContent = "<div><ul>";
if (isset($_POST["nome"]) && isset($_POST["cognome"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];
  $name = $_POST["nome"];
  $lastname = $_POST["cognome"];

  $dbAccess = new DBAccess();
  $isFailed = $dbAccess->openDBConnection();

  if ($isFailed) {
    header("Location: /errors/500.php");
  } 
  
  $result = $dbAccess->signupUser($name, $lastname, $email, $password);
  $dbAccess->closeDBConnection(); 

  if ($result["isSuccessful"]) {        
    $_SESSION["email"] = $result["userEmail"];
    $_SESSION["isAdmin"] = $result["userEmail"] === "admin";
    $userFeedbackContent .= "<li><strong class=\"success\">Utente registrato corramente, verrai reindirizzato alla <span xml:lang=\"en\">home</span></strong></li>";
    header("refresh:2;url= /index.php");
  } else {
    $userFeedbackContent .= "<li><strong class=\"error\">Errore durante la registrazione</strong></li>";
  }
}
$userFeedbackContent .= "</ul></div>";

$html = str_replace("<UserPlaceholder />", $userContent, $html);
$html = str_replace("<SignupErrorPlaceholder />", $userFeedbackContent, $html);
echo $html;
?>