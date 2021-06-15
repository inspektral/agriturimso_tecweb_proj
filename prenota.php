<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
require_once __DIR__ . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR . "UserMenu.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR . "NewsListFactory.php";
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

if (isset($_POST["prenotazioneDa"]) && $_POST["prenotazioneDa"] != '' && isset($_POST["prenotazioneA"]) && $_POST["prenotazioneA"] != '' && (isset($_GET["nomeCamera"]) || isset($_POST["nomeCamera"]))) {
    
    $nomeCamera = "";
    if(isset($_GET["nomeCamera"])){
        $nomeCamera = $_GET["nomeCamera"];
    }else if( isset($_POST["nomeCamera"])){
        $nomeCamera = $_POST["nomeCamera"];
    }

    $dateDa = DateTime::createFromFormat('d/m/Y',$_POST["prenotazioneDa"]);
    $dateA = DateTime::createFromFormat('d/m/Y',$_POST["prenotazioneA"]);

    if($dateDa < $dateA){
            
        $dbAccess = new DBAccess();
        $isFailed = $dbAccess->openDBConnection();
        
        if ($isFailed) {
            header("Location: ./errors/500.php");
            exit();
        }
        
        if ($dbAccess->isFree($_POST["prenotazioneDa"], $_POST["prenotazioneA"], $nomeCamera)) {
            $html = str_replace("<resultPrenotazione/>", "<p class=\"resultPrenotazione\">Che fortuna, la camera &egrave; libera, chiamaci subito per prenotare (0423/123456)!</p>", $html);
        } else {
            $html = str_replace("<resultPrenotazione/>", "<p class=\"resultPrenotazione resultPrenotazioneFalse\">Niente, mi dispiace, camera gi&agrave; prenotata, sar&agrave; per la prossima volta... <br/>O per la prossima settimana, prova a ricontrollare, altrimenti puoi chiamarci al numero 0423/123456</p>", $html);
        }
        
        $dbAccess->closeDBConnection();

    }else{
        $html = str_replace("<resultPrenotazione/>", "<strong class=\"resultPrenotazioneFalse\">Errore, data di partenza antecedente o coincidente alla data di arrivo</strong>", $html);
        
    }
}

$newsContent = (new NewsListFactory())->createNewsList();
if (!$newsContent) {
  header("Location: ./errors/500.php");
}

$contentAdminNews = "";
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
  $contentAdminNews = "<div id=\"adminSection\"><a class=\"button\" href=\"./insertNews.php\">Aggiungi notizia</a></div>";
}
$html = str_replace("<AdminNewsManagementPlaceholder />", $contentAdminNews, $html);

$html = str_replace("<NewsListPlaceholder />", $newsContent, $html);
$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<dateToday/>",date("d/m/Y"), $html);
$html = str_replace("<resultPrenotazione/>", "", $html);
if(isset($_GET["nomeCamera"])){
    $html = str_replace("<nameCamera/>", $_GET["nomeCamera"], $html);
}
if(isset($_POST["prenotazioneDa"])){
    $html = str_replace("<inputDaValue/>", $_POST["prenotazioneDa"], $html);
}else{
    $html = str_replace("<inputDaValue/>", "", $html);
}
if(isset($_POST["prenotazioneA"])){
    $html = str_replace("<inputAValue/>", $_POST["prenotazioneA"], $html);
}else{
    $html = str_replace("<inputAValue/>", "", $html);
}


echo $html;
?>