<?php
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."NewsListFactory.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."mostraCommento.php";

session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."diconodinoi.html");

$menu = new UserMenu();
$content = "";
if (isset($_SESSION['email'])) {        
  $content = $menu->getWelcomeMessage($_SESSION['email']);
} else {
  $content = $menu->getAuthenticationButtons();
}

$contentAdminNews = "";
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
  $contentAdminNews = "<div id=\"adminSection\"><a class=\"button\" href=\"./insertNews.php\">Aggiungi notizia</a></div>";
}

$newsContent = (new NewsListFactory())->createNewsList();
if (!$newsContent) {
  header("Location: ./errors/500.php");
}

$userFeedbackContent = "";
if(isset($_POST["submit"])) {
  $userFeedbackContent = "<div><ul class=\"feedbackList\">";
  if (isset($_SESSION["email"])) {        
      $email = $_SESSION["email"];
      $testo = $_POST["testo"];
      $voto = $_POST["voto"];
      
      $dbAccess = new DBAccess();
      $isFailed = $dbAccess->openDBConnection();

      if($isFailed) {
        header("Location: ./errors/500.php");
      }

      $result= $dbAccess->setComments($email, $testo, $voto);
      $dbAccess->closeDBConnection();

      if ($result["isSuccessful"]) {
          $userFeedbackContent .= "<li><strong class=\"success\">Commento aggiunto con successo</strong></li>";
      }else{				
          $userFeedbackContent .= "<li><strong class=\"error\">Errore durante l'aggiunta del commento</strong></li>";
      }
  } else { 
      $userFeedbackContent .= "<li><strong class=\"error\">Devi effettuare l'accesso per commentare</strong></li>";
  }
  $userFeedbackContent .= "</ul></div>";
}

$deleteUserFeedbackContent = "";
if(isset($_POST["deleteComment"]) && isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
  $deleteUserFeedbackContent = "<div><ul class=\"feedbackList\">";
  if (isset($_POST["email"]) && isset($_POST["timestamp"])) {        
    $email = $_POST["email"];
    $timestamp= $_POST["timestamp"];
    
    $dbAccess = new DBAccess();
    $isFailed = $dbAccess->openDBConnection();

    if($isFailed) {
      header("Location: ./errors/500.php");
    }

    $result= $dbAccess->deleteComment($email, $timestamp);
    $dbAccess->closeDBConnection();
      
    if ($result["isSuccessful"]) {
      $deleteUserFeedbackContent .= "<li><strong class=\"success\">Commento rimosso con successo</strong></li>";
    }else{				
      $deleteUserFeedbackContent .= "<li><strong class=\"error\">Errore durante la rimozione del commento</strong></li>";
    }
  }
  $deleteUserFeedbackContent .= "</ul></div>";
} else if(isset($_POST["deleteComment"]) && !$_SESSION["isAdmin"]){
  $deleteUserFeedbackContent = "<div><ul class=\"feedbackList\">";
  $deleteUserFeedbackContent .= "<li><strong class=\"error\">Devi essere un amministratore per poter rimuovere i commenti</strong></li>";
  $deleteUserFeedbackContent .= "</ul></div>";
}


$commentContent = (new mostraCommento())->createNewComment();
if (!$commentContent) {
  header("Location: ./errors/500.php");
}

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<AdminNewsManagementPlaceholder />", $contentAdminNews, $html);
$html = str_replace("<CommentPlaceholder />", $commentContent, $html);
$html = str_replace("<NewsListPlaceholder />", $newsContent, $html);
$html = str_replace("<DeleteCommentErrorPlaceholder />", $deleteUserFeedbackContent, $html);
$html = str_replace("<InsertCommentErrorPlaceholder />", $userFeedbackContent, $html);
echo $html;
?>