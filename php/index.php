<?php
session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "index.html");

$content = "<div id=\"user\">";
if (isset($_SESSION['username'])) {        
  $username = $_SESSION["username"];
  $content .= "<p class='reg'>Benvenuto: " . $username . "</p>";
} else {
  $content .= "<a href=\"../pages/registrati.html\" class=\"reg\">Registrati</a>";
  $content .= "<a href=\"../php/login.php\" class=\"reg\">Accedi</a>";
}
$content .= "</div>";

$html = str_replace("<UserPlaceholder />", $content, $html);
echo $html;
?>