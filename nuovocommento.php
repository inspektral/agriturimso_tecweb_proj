<?php


require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";



session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR .'diconodinoi.html');

$menu = new UserMenu();
$content = "";

if (isset($_SESSION['email'])) {        
  $content = $menu->getWelcomeMessage($_SESSION['email']);
} else {
  $content = $menu->getAuthenticationButtons();
}

$html = str_replace("<UserPlaceholder />", $content, $html);

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
            $userFeedbackContent .= "<li><strong class=\"error\">Errore di collegamento al database. Riprova.</strong></li>";;
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

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<InsertCommentErrorPlaceholder />", $userFeedbackContent, $html);

echo $html;
?>