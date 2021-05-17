<?php
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."InputCleaner.php";

session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."newsForm.html");

if (!isset($_SESSION["isAdmin"]) && !$_SESSION["isAdmin"]) {
  // TODO: Error 400
  // header("Location: /errors/400.php");
}

$menu = new UserMenu();
$content = $menu->getWelcomeMessage($_SESSION['email']);

$userFeedbackContent = "<div><ul>";
$descriptionValue = "";
if (isset($_POST["description"]) && isset($_POST["submit"])) {
  $description = (new InputCleaner())->cleanNews($_POST["description"]);

  if (strlen($description) > 10) {
    $dbAccess = new DBAccess();
    $isFailed = $dbAccess->openDBConnection();

    if ($isFailed) {
      header("Location: /errors/500.php");
    } 
    
    $result = $dbAccess->addNews($description);
    $dbAccess->closeDBConnection(); 

    if (!$result) {
      header("Location: /errors/500.php");
    } 

    if ($result["isSuccessful"]) {
      $userFeedbackContent .= "<li><strong class=\"success\">Notizia aggiunta con successo</strong></li>";
    } else {
      $userFeedbackContent .= "<li><strong class=\"error\">Errore durante l'aggiunta della notizia</strong></li>";
    }
  } else {
    if (strlen($description) == 0) {
      $userFeedbackContent .= "<li><strong class=\"error\">La descrizione deve essere presente</strong></li>";
    } else {
      $userFeedbackContent .= "<li><strong class=\"error\">La descrizione deve avere lunghezza maggiore di dieci caratteri</strong></li>";
      $descriptionValue = $description;
    }
  }
}
$userFeedbackContent .= "</ul></div>";

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<InsertNewsErrorPlaceholder />", $userFeedbackContent, $html);
$html = str_replace("<DescriptionValuePlaceholder />", $descriptionValue, $html);
echo $html;
?>