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
$checkedServices = [
  "tv" => "",
  "balcony" => "",
  "gardenView" => "",
  "airCondition" => "",
  "heat" => "",
  "parquet" => "",
  "shower" => "",
  "shampoo" => "",
  "wc" => "",
  "bath" => "",
  "bidet" => "",
  "paper" => "",
  "towels" => ""
];
if (isset($_POST["submit"])) {
  $userFeedbackContent = "<div><ul class=\"feedbackList\">";
  if (isset($_POST["name"]) && isset($_POST["people"]) && isset($_POST["price"]) && isset($_FILES["mainImg"]) && isset($_POST["mainLongdesc"])) {
    $name = (new InputCleaner())->cleanRoomName($_POST["name"]);
    $people = intval($_POST["people"]);
    $price = doubleval($_POST["price"]);
    $services = array();
    $mainImg = __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["mainImg"]["name"]);
    $imgLongdesc = $_POST['mainLongdesc'];
    $imgLongdescPath = __DIR__.DIRECTORY_SEPARATOR."rooms-longdescs".DIRECTORY_SEPARATOR.$name;
    $firstGallery = isset($_FILES["firstGallery"]) ? __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["firstGallery"]["name"]) : NULL;
    $secondGallery = isset($_FILES["secondGallery"]) ? __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["secondGallery"]["name"]) : NULL;
    $thirdGallery = isset($_FILES["thirdGallery"]) ? __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["thirdGallery"]["name"]) : NULL;
    $fourthGallery = isset($_FILES["fourthGallery"]) ? __DIR__.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["fourthGallery"]["name"]) : NULL;

    if (!empty($_POST["services"])) {
      $services = $_POST["services"];
    }

    if (strlen($name) > 5 && is_int($people) && $people > 0 && is_float($price) && $price > 0.0 && strlen($imgLongdesc) > 20) {
      $servicesBool = (new ServicesConverter())->convertToBoolean($services);

      var_dump($servicesBool);
      $dbAccess = new DBAccess();
      $isFailed = $dbAccess->openDBConnection();

      if ($isFailed) {
        header("Location: ./errors/500.php");
      }

      $result = $dbAccess->addRoom($name,$people,$price,$mainImg,$imgLongdesc,$firstGallery,$secondGallery,$thirdGallery,$fourthGallery,$servicesBool);
      $dbAccess->closeDBConnection(); 

      if (!$result) {
        // header("Location: ./errors/500.php");
        print_r("Errore");
      }

      if ($result["isSuccessful"] && file_put_contents($imgLongdescPath, $imgLongdesc)) {
        $uploader = new ImageUploader();
        $mainSuccess = $uploader->upload($_FILES["mainImg"],$mainImg);
        $firstSuccess = isset($_FILES["firstGallery"]) ? $uploader->upload($_FILES["firstGallery"],$firstGallery) : true;
        $secondSuccess = isset($_FILES["secondGallery"]) ? $uploader->upload($_FILES["secondgGllery"],$secondGallery) : true;
        $thirdSuccess = isset($_FILES["thirdGallery"]) ? $uploader->upload($_FILES["thirdGallery"],$thirdGallery) : true;
        $fourthSuccess = isset($_FILES["fourthGallery"]) ? $uploader->upload($_FILES["fourthGallery"],$fourthGallery) : true;

        if ($mainSuccess && $firstSuccess && $secondSuccess && $thirdSuccess && $fourthSuccess) {
          $userFeedbackContent .= "<li><strong class=\"success\">Camera aggiunta con successo</strong></li>";
        } else {
          $userFeedbackContent .= "<li><strong class=\"error\">Errore durante durante il caricamento delle immagini</strong></li>";
        }
      } else {
        $userFeedbackContent .= "<li><strong class=\"error\">Errore durante l'aggiunta della camera</strong></li>";
        $nameValue = $_POST["name"];
        $peopleValue = $_POST["people"];
        $priceValue = $_POST["price"];
        $imgLongdescValue = $_POST['mainLongdesc'];
        $checkedServices = (new ServicesConverter())->convertToHtmlAttribute($services);
      }
    } else {
      if (strlen($name) <= 5) {
        $userFeedbackContent .= "<li><strong class=\"error\">Il nome della camera deve avere lunghezza maggiore di cinque</strong></li>";
      } 
      if (!is_int($people) || $people <= 0) {
        $userFeedbackContent .= "<li><strong class=\"error\">Il numero di persone deve essere un intero maggiore di 1</strong></li>";
      }
      if (!is_float($price) || $price <= 0.0) {
        $userFeedbackContent .= "<li><strong class=\"error\">Il prezzo della stanza deve essere un numero reale maggiore di zero</strong></li>";
      } 
      if (strlen($imgLongdesc) <= 20) {
        $userFeedbackContent .= "<li><strong class=\"error\">La descrizione dell'immagine principale deve avere lunghezza maggiore di venti caratteri</strong></li>";
      }
      $nameValue = $_POST["name"];
      $peopleValue = $_POST["people"];
      $priceValue = $_POST["price"];
      $imgLongdescValue = $_POST['mainLongdesc'];
      $checkedServices = (new ServicesConverter())->convertToHtmlAttribute($services);
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
    if (!isset($_FILES["mainImg"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">L'immagine principale è un campo obbligatorio</strong></li>";
    }
    if (!isset($_POST["mainLongdesc"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">La descrizione dell'immagine principale è un campo obbligatorio</strong></li>";
    }
    $nameValue = isset($_POST["name"]) ? $_POST["name"] : "";
    $peopleValue = isset($_POST["people"]) ? $_POST["people"] : "";
    $priceValue = isset($_POST["price"]) ? $_POST["price"] : "";
    $imgLongdescValue = isset($_POST["mainLongdesc"]) ? $_POST['mainLongdesc'] : "";
    $checkedServices = (new ServicesConverter())->convertToHtmlAttribute($services);
  }
  $userFeedbackContent .= "</ul></div>";
}

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<InsertRoomErrorPlaceholder />", $userFeedbackContent, $html);
$html = str_replace("<NameValuePlaceholder />", $nameValue, $html);
$html = str_replace("<PeopleValuePlaceholder />", $peopleValue, $html);
$html = str_replace("<PriceValuePlaceholder />", $priceValue, $html);
$html = str_replace("<MainLongdescValuePlaceholder />", $imgLongdescValue, $html);
$html = str_replace("<TvCheckedPlaceholder />", $checkedServices["tv"], $html);
$html = str_replace("<BalconeCheckedPlaceholder />", $checkedServices["balcony"], $html);
$html = str_replace("<GardenCheckedPlaceholder />", $checkedServices["gardenView"], $html);
$html = str_replace("<AirCheckedPlaceholder />", $checkedServices["airCondition"], $html);
$html = str_replace("<HeatCheckedPlaceholder />", $checkedServices["heat"], $html);
$html = str_replace("<ParquetCheckedPlaceholder />", $checkedServices["parquet"], $html);
$html = str_replace("<ShowerCheckedPlaceholder />", $checkedServices["shower"], $html);
$html = str_replace("<ShampooCheckedPlaceholder />", $checkedServices["shampoo"], $html);
$html = str_replace("<WcCheckedPlaceholder />", $checkedServices["wc"], $html);
$html = str_replace("<BathCheckedPlaceholder />", $checkedServices["bath"], $html);
$html = str_replace("<BidetCheckedPlaceholder />", $checkedServices["bidet"], $html);
$html = str_replace("<PaperCheckedPlaceholder />", $checkedServices["paper"], $html);
$html = str_replace("<TowelsCheckedPlaceholder />", $checkedServices["towels"], $html);
echo $html;
?>