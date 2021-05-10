<?php
session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "diconodinoi.html");

$content = "<div id=\"user\">";
if (isset($_SESSION['email'])) {        
  $email = $_SESSION["email"];
  $content .= "<p class='reg'>Benvenuto: " . $email . "</p>";
} else {
  $content .= "<a href=\"../php/signup.php\" class=\"reg\">Registrati</a>";
  $content .= "<a href=\"../php/login.php\" class=\"reg\">Accedi</a>";
}
$content .= "</div>";

$html = str_replace("<UserPlaceholder />", $content, $html);
echo $html;
?>