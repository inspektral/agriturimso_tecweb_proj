<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR . "UserMenu.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR . "dbAccess.php";

session_start();

$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "prenota.html");

$menu = new UserMenu();
$content = "";
if (isset($_SESSION["email"])) {
    $content = $menu->getWelcomeMessage($_SESSION["email"]);
} else {
    $content = $menu->getAuthenticationButtons();
}

$html = str_replace("<UserPlaceholder />", $content, $html);

if (isset($_POST["prenotazioneDa"]) && $_POST["prenotazioneDa"] != '' && isset($_POST["prenotazioneA"]) && $_POST["prenotazioneA"] != '') {
    
    if($_POST["prenotazioneDa"] <= $_POST["prenotazioneA"]){
        
        $dbAccess = new DBAccess();
        $isFailed = $dbAccess->openDBConnection();
        
        if ($isFailed) {
            header("Location: /errors/500.php");
            exit();
        }
        
        if ($dbAccess->isFree($_POST["prenotazioneDa"], $_POST["prenotazioneA"])) {
            $dbAccess->prenotaCamera($_SESSION["email"],$_POST["prenotazioneDa"], $_POST["prenotazioneA"], $_POST["camera"]);
            header("Location: /pages/prenotazione_effettuata.html");
        } else {
            $html = str_replace("<resultPrenotazione/>", "Ciaone proprio, gia prenotato", $html);
        }
        
        $dbAccess->closeDBConnection();
    }else{
        $html = str_replace("<resultPrenotazione/>", "<strong class=\"error\">Data di prenotazione errata, data di partenza antecedente alla data di arrivo</strong>", $html);
        
    }
}

echo $html;
?>