<?php
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."InputCleaner.php";

session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."roomForm.html");

if (!isset($_SESSION["isAdmin"]) && !$_SESSION["isAdmin"]) {
  // TODO: Error 400
  // header("Location: /errors/400.php");
}

$menu = new UserMenu();
$content = $menu->getWelcomeMessage($_SESSION['email']);

$userFeedbackContent = "<div><ul>";
$descriptionValue = "";

$userFeedbackContent .= "</ul></div>";

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<InsertRoomErrorPlaceholder />", $userFeedbackContent, $html);
// $html = str_replace("<DescriptionValuePlaceholder />", $descriptionValue, $html);
echo $html;
?>