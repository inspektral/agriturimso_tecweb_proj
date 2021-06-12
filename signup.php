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

$email = "";
$lastname = "";
$name = "";
$userFeedbackContent = "";
$nameValue = "";
$lastnameValue  = "";
$emailValue = "";
if (isset($_POST["submit"])) {
  $userFeedbackContent = "<div><ul class=\"feedbackList\">";

  if (
    isset($_POST["nome"]) && strlen($_POST["nome"]) >= 2 && strlen($_POST["nome"]) <= 20 && 
    isset($_POST["cognome"]) && strlen($_POST["cognome"]) >= 2 && strlen($_POST["cognome"]) <= 20 && 
    isset($_POST["email"]) && strlen($_POST["email"]) > 5 && 
    isset($_POST["password"]) && strlen($_POST["password"]) >= 4
  ) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $name = $_POST["nome"];
    $lastname = $_POST["cognome"];

    $dbAccess = new DBAccess();
    $isFailed = $dbAccess->openDBConnection();

    if ($isFailed) {
      header("Location: ./errors/500.php");
    } 
    
    $result = $dbAccess->signupUser($name, $lastname, $email, $password);
    $dbAccess->closeDBConnection(); 

    if ($result["isSuccessful"]) {        
      $_SESSION["email"] = $result["userEmail"];
      $_SESSION["isAdmin"] = $result["userEmail"] === "admin@mail.com";
      $userFeedbackContent .= "<li><strong class=\"success\">Utente registrato correttamente, verrai reindirizzato alla <span xml:lang=\"en\">home</span> in 2 secondi</strong></li>";
      header("refresh:2;url= ./index.php");
    } else {
      $emailValue = $_POST["email"];
      $nameValue = $_POST["nome"];
      $lastnameValue = $_POST["cognome"];
      $userFeedbackContent .= "<li><strong class=\"error\">Errore durante la registrazione</strong></li>";
    }
  } else {
    if (!isset($_POST["nome"]) || strlen($_POST["nome"]) < 2 || strlen($_POST["nome"]) > 20) {
      $userFeedbackContent .= "<li><strong class=\"error\">Il nome deve avere lunghezza tra 2 e 20</strong></li>";
    }
    if (!isset($_POST["cognome"]) || strlen($_POST["cognome"]) < 2 || strlen($_POST["cognome"]) > 20) {
      $userFeedbackContent .= "<li><strong class=\"error\">Il cognome deve avere lunghezza tra 2 e 20</strong></li>";
    }
    if (!isset($_POST["email"]) || strlen($_POST["email"]) <= 5) {
      $userFeedbackContent .= "<li><strong class=\"error\">L'email non è valida</strong></li>";
    }
    if (!isset($_POST["password"]) || strlen($_POST["email"]) < 4) {
      $userFeedbackContent .= "<li><strong class=\"error\">La password deve avere almeno 4 caratteri</strong></li>";
    }
  }
  $userFeedbackContent .= "</ul></div>";
}

$html = str_replace("<UserPlaceholder />", $userContent, $html);
$html = str_replace("<SignupErrorPlaceholder />", $userFeedbackContent, $html);
$html = str_replace("<NameValuePlaceholder />", $nameValue, $html);
$html = str_replace("<LastNameValuePlaceholder />", $lastnameValue, $html);
$html = str_replace("<EmailValuePlaceholder />", $emailValue, $html);
echo $html;
?>