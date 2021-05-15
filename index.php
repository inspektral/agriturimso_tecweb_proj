<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."NewsListFactory.php";

session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."index.html");

$menu = new UserMenu();
$content = "";
if (isset($_SESSION['email'])) {        
  $content = $menu->getWelcomeMessage($_SESSION['email']);
} else {
  $content = $menu->getAuthenticationButtons();
}

$contentAdminNews = "";
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
  $contentAdminNews = "<div id=\"adminSection\"><a id=\"buttonNews\" href=\"./insertNews.php\">Gestisci</a></div>";
}

$newsContent = (new NewsListFactory())->createNewsList();
if (!$newsContent) {
  header("Location: /errors/500.php");
}

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<AdminNewsManagementPlaceholder />", $contentAdminNews, $html);
$html = str_replace("<NewsListPlaceholder />", $newsContent, $html);
echo $html;
?>