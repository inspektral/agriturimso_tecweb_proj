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
  $userContent = $menu->getAuthenticationButtons(true, false);
}

$email = "";
$lastname = "";
$name = "";
$userFeedbackContent = "";
if (isset($_POST["submit"])) {
  $userFeedbackContent = "<div><ul class=\"feedbackList\">";

  if (isset($_POST["nome"]) && isset($_POST["cognome"]) && isset($_POST["email"]) && isset($_POST["password"])) {
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
  } else {
    if (!isset($_POST["nome"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">Il nome è un campo obbligatorio</strong></li>";
    }
    if (!isset($_POST["cognome"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">Il cognome è un campo obbligatorio</strong></li>";
    }
    if (!isset($_POST["email"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">L'email è un campo obbligatorio</strong></li>";
    }
    if (!isset($_POST["password"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">La password è un campo obbligatorio</strong></li>";
    }
  }
  $userFeedbackContent .= "</ul></div>";
}

$html = str_replace("<UserPlaceholder />", $userContent, $html);
$html = str_replace("<SignupErrorPlaceholder />", $userFeedbackContent, $html);
$html = str_replace("<NameValuePlaceholder />", $name, $html);
$html = str_replace("<LastNameValuePlaceholder />", $lastname, $html);
$html = str_replace("<EmailValuePlaceholder />", $email, $html);
echo $html;
?>