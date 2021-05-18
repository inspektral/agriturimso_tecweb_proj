<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."InputCleaner.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."ServicesConverter.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."ImageUploader.php";

session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."roomForm.html");

if (!isset($_SESSION["isAdmin"]) && !$_SESSION["isAdmin"]) {
  // TODO: Error 400
  // header("Location: /errors/400.php");
}

$menu = new UserMenu();
$content = $menu->getWelcomeMessage($_SESSION['email']);

$userFeedbackContent = "";
$nameValue = "";
$peopleValue = "";
$priceValue = "";
$imgLongdescValue = "";
$tvChecked = "";
$balconeChecked = "";
$gardenChecked = "";
$airChecked = "";
$heatChecked = "";
$parquetChecked = "";
$showerChecked = "";
$shampooChecked = "";
$wcChecked = "";
$bathChecked = "";
$bidetChecked = "";
$paperChecked = "";
$towelsChecked = "";
if (isset($_POST["submit"])) {
  $userFeedbackContent = "<div><ul class=\"feedbackList\">";
  if (isset($_POST["name"]) && isset($_POST["people"]) && isset($_POST["price"]) && isset($_POST["mainImg"]) && isset($_POST["mainLongdesc"])) {
    $nameValue = $name = $description = (new InputCleaner())->cleanRoomName($_POST["name"]);
    $peopleValue = $people = $_POST["people"];
    $priceValue = $price = $_POST["price"];
    $services = array();
    $mainImg = __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["mainImg"]["name"]);
    $imgLongdescValue = $imgLongdesc = $_POST['imgLongdesc'];
    $imgLongdescPath = __DIR__.DIRECTORY_SEPARATOR."rooms-longdescs".DIRECTORY_SEPARATOR.$name;
    $firstGallery = __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["firstGallery"]["name"]);
    $secondGallery = __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["secondGallery"]["name"]);
    $thirdGallery = __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["thirdGallery"]["name"]);
    $fourthGallery = __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["fourthGallery"]["name"]);

    if (!empty($_POST["services"])) {
      $services = $_POST["services"];
    }

    if (strlen($name) > 5 && is_int($people) && $people > 0 && is_float($price) && $price > 0.0 && strlen($imgLongdesc) > 20) {
      $servicesBool = (new ServicesConverter())->convertToBoolean($services);

      $dbAccess = new DBAccess();
      $isFailed = $dbAccess->openDBConnection();

      if ($isFailed) {
        header("Location: /errors/500.php");
      }

      $result = $dbAccess->addRoom($name,$people,$price,$mainImg,$imgLongdesc,$firstGallery,$secondGallery,$thirdGallery,$fourthGallery,$servicesBool);
      $dbAccess->closeDBConnection(); 

      if (!$result) {
        header("Location: /errors/500.php");
      }

      if ($result["isSuccessful"] && file_put_contents($imgLongdescPath, $imgLongdesc)) {
        $uploader = new ImageUploader();
        $mainSuccess = $uploader->upload($_FILES["mainImg"],$mainImg);
        $firstSuccess = $uploader->upload($_FILES["firstGallery"],$firstGallery);
        $secondSuccess = $uploader->upload($_FILES["secondgGllery"],$secondGallery);
        $thirdSuccess = $uploader->upload($_FILES["thirdGallery"],$thirdGallery);
        $fourthSuccess = $uploader->upload($_FILES["fourthGallery"],$fourthGallery);

        if ($mainSuccess && $firstSuccess && $secondSuccess && $thirdSuccess && $fourthSuccess) {
          $userFeedbackContent .= "<li><strong class=\"success\">Camera aggiunta con successo</strong></li>";
        } else {
          $userFeedbackContent .= "<li><strong class=\"error\">Errore durante durante il caricamento delle immagini</strong></li>";
        }
      } else {
        $userFeedbackContent .= "<li><strong class=\"error\">Errore durante l'aggiunta della camera</strong></li>";
      }
    } else {
      if (strlen($name) <= 5) {
        $userFeedbackContent .= "<li><strong class=\"error\">Il nome della camera deve avere lunghezza maggiore di cinque</strong></li>";
      } 
      if (!is_int($people) && $people <= 0) {
        $userFeedbackContent .= "<li><strong class=\"error\">Il numero di persone deve essere un intero maggiore di 1</strong></li>";
      }
      if (!is_float($price) && $price <= 0.0) {
        $userFeedbackContent .= "<li><strong class=\"error\">Il prezzo della stanza deve essere un numero reale maggiore di zero</strong></li>";
      } 
      if (strlen($imgLongdesc) <= 20) {
        $userFeedbackContent .= "<li><strong class=\"error\">La descrizione dell'immagine principale deve avere lunghezza maggiore di venti caratteri</strong></li>";
      }
    }
  } else {
    if (!isset($_POST["name"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">Il nome è un campo obbligatorio</strong></li>";
    } 
    if (!isset($_POST["people"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">Il numero di perone è un campo obbligatorio</strong></li>";
    } 
    if (!isset($_POST["price"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">Il prezzo è un campo obbligatorio</strong></li>";
    } 
    if (!isset($_POST["mainImg"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">L'immagine principale è un campo obbligatorio</strong></li>";
    }
    if (!isset($_POST["mainLongdesc"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">La descrizione dell'immagine principale è un campo obbligatorio</strong></li>";
    }
  }
  $userFeedbackContent .= "</ul></div>";
}

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<InsertRoomErrorPlaceholder />", $userFeedbackContent, $html);
$html = str_replace("<NameValuePlaceholder />", $nameValue, $html);
$html = str_replace("<PeopleValuePlaceholder />", $peopleValue, $html);
$html = str_replace("<PriceValuePlaceholder />", $priceValue, $html);
$html = str_replace("<ImgLongdescValuePlaceholder />", $imgLongdescValue, $html);
$html = str_replace("<TvCheckedPlaceholder />", $tvChecked, $html);
$html = str_replace("<BalconeCheckedPlaceholder />", $balconeChecked, $html);
$html = str_replace("<GardenCheckedPlaceholder />", $gardenChecked, $html);
$html = str_replace("<AirCheckedPlaceholder />", $airChecked, $html);
$html = str_replace("<HeatCheckedPlaceholder />", $heatChecked, $html);
$html = str_replace("<ParquetCheckedPlaceholder />", $paperChecked, $html);
$html = str_replace("<ShowerCheckedPlaceholder />", $shampooChecked, $html);
$html = str_replace("<ShampooCheckedPlaceholder />", $shampooChecked, $html);
$html = str_replace("<WcCheckedPlaceholder />", $wcChecked, $html);
$html = str_replace("<BathCheckedPlaceholder />", $bathChecked, $html);
$html = str_replace("<BidetCheckedPlaceholder />", $bidetChecked, $html);
$html = str_replace("<PaperCheckedPlaceholder />", $paperChecked, $html);
$html = str_replace("<TowelsCheckedPlaceholder />", $towelsChecked, $html);
echo $html;
?>