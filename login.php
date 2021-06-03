<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR . "UserMenu.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR . "dbAccess.php";

session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "accedi.html");

$menu = new UserMenu();
$userContent = "";
if (isset($_SESSION['email'])) {
  $userContent = $menu->getWelcomeMessage($_SESSION['email']);
} else {
  $userContent = $menu->getAuthenticationButtons(false, false, true);
}

$errorContent = "<div><ul class=\"feedbackList\">";
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["submit"])) {
  $username = $_POST["email"];
  $password = $_POST["password"];

  $dbAccess = new DBAccess();
  $isFailed = $dbAccess->openDBConnection();

  if ($isFailed) {
    header("Location: ./errors/500.php");
    exit();
  }

  $user = $dbAccess->loginUser($username, $password);
  $dbAccess->closeDBConnection();

  if ($user) {
    $_SESSION["email"] = $user["email"];
    $_SESSION["isAdmin"] = $user["email"] === "admin@mail.com";
    header("Location: ./index.php");
  } else {
    $errorContent .= "<li><strong class=\"error\">Credenziali errate</strong></li>";
  }
}
$errorContent .= "</ul></div>";

$html = str_replace("<UserPlaceholder />", $userContent, $html);
$html = str_replace("<LoginErrorPlaceholder />", $errorContent, $html);
echo $html;
?>