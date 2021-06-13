<?php
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."InputCleaner.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."ServicesConverter.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."ImageUploader.php";

session_start();

$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."roomForm.html");

if (!isset($_SESSION["isAdmin"]) && !$_SESSION["isAdmin"]) {
  header("Location: ./errors/400.php");
}

$menu = new UserMenu();
$content = $menu->getWelcomeMessage($_SESSION['email']);

$userFeedbackContent = "";
$nameValue = "";
$peopleValue = "";
$priceValue = "";
$metersValue = "";
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
  "towels" => "",
  "wardrobe" => ""
];
$checkedAdditionalServices = [
  "parking" => "",
  "wifi" => "",
  "privateBathRoom" => ""
];

if (isset($_POST["submit"])) {
  $userFeedbackContent = "<div><ul class=\"feedbackList\">";
  if (
    isset($_POST["name"]) && strlen($_POST["name"]) > 0 && 
    isset($_POST["people"]) && strlen($_POST["people"]) > 0 && 
    isset($_POST["price"]) && strlen($_POST["price"]) > 0 && 
    isset($_POST["meters"]) && strlen($_POST["meters"]) > 0 && 
    isset($_FILES["mainImg"]) && strlen($_FILES["mainImg"]["name"]) > 0 &&
    isset($_POST["mainLongdesc"]) && strlen($_POST["mainLongdesc"]) > 0
  ) {
    $name = (new InputCleaner())->cleanRoomName($_POST["name"]);
    $people = intval($_POST["people"]);
    $price = doubleval(str_replace(",", ".", $_POST["price"]));
    $meters = intval($_POST["meters"]);
    $services = array();
    $additionalServices = array();
    $mainImg = ".".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["mainImg"]["name"]);
    $imgLongdesc = $_POST['mainLongdesc'];
    $imgLongdescPath = ".".DIRECTORY_SEPARATOR."rooms-longdescs".DIRECTORY_SEPARATOR.str_replace(" ", "_", strtolower($name)).".txt";
    $firstGallery = $_FILES["firstGallery"] === UPLOAD_ERR_OK ? ".".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["firstGallery"]["name"]) : null;
    $secondGallery = $_FILES["secondGallery"] === UPLOAD_ERR_OK ? ".".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["secondGallery"]["name"]) : null;
    $thirdGallery = $_FILES["thirdGallery"] === UPLOAD_ERR_OK ? ".".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["thirdGallery"]["name"]) : null;
    $fourthGallery = $_FILES["fourthGallery"] === UPLOAD_ERR_OK ? ".".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.basename($_FILES["fourthGallery"]["name"]) : null;

    if (!empty($_POST["services"])) {
      $services = $_POST["services"];
    }

    if (!empty($_POST["additionalServices"])) {
      $additionalServices = $_POST["additionalServices"];
    }

    if (strlen($name) > 5 && is_int($people) && $people > 0 && is_float($price) && $price > 0.0 && is_int($meters) && $meters > 5 && strlen($imgLongdesc) > 20) {
      $servicesBool = (new ServicesConverter())->convertToBoolean($services);
      $additionalServicesBool = (new ServicesConverter())->convertToBoolean($additionalServices, true);

      $dbAccess = new DBAccess();
      $isFailed = $dbAccess->openDBConnection();

      if ($isFailed) {
        header("Location: ./errors/500.php");
      }

      $result = $dbAccess->addRoom($name,$people,$price,$meters,$mainImg,$imgLongdescPath,$firstGallery,$secondGallery,$thirdGallery,$fourthGallery,$servicesBool,$additionalServicesBool);
      $dbAccess->closeDBConnection(); 

      if (!$result) {
        header("Location: ./errors/500.php");
      }

      if ($result["isSuccessful"] && file_put_contents($imgLongdescPath, $imgLongdesc)) {
        $uploader = new ImageUploader();
        $mainSuccess = $uploader->upload($_FILES["mainImg"],$mainImg);
        
        $firstSuccess = $_FILES["firstGallery"] === UPLOAD_ERR_OK ? $uploader->upload($_FILES["firstGallery"],$firstGallery) : true;
        $secondSuccess = $_FILES["secondGallery"] === UPLOAD_ERR_OK ? $uploader->upload($_FILES["secondGallery"],$secondGallery) : true;
        $thirdSuccess = $_FILES["thirdGallery"] === UPLOAD_ERR_OK ? $uploader->upload($_FILES["thirdGallery"],$thirdGallery) : true;
        $fourthSuccess = $_FILES["fourthGallery"] === UPLOAD_ERR_OK ? $uploader->upload($_FILES["fourthGallery"],$fourthGallery) : true;

        if ($mainSuccess && $firstSuccess && $secondSuccess && $thirdSuccess && $fourthSuccess) {
          $userFeedbackContent .= "<li><strong class=\"success\">Camera aggiunta con successo</strong></li>";
        } else {
          $userFeedbackContent .= "<li><strong class=\"error\">Errore durante durante il caricamento delle immagini</strong></li>";
          // Delete the room from database
          $dbAccess = new DBAccess();
          $isFailed = $dbAccess->openDBConnection();

          if ($isFailed) {
            header("Location: ./errors/500.php");
          }

          $result = $dbAccess->removeRoom($name);
          $dbAccess->closeDBConnection(); 

          if (!$result) {
            header("Location: ./errors/500.php");
          }

          $nameValue = $_POST["name"];
          $peopleValue = $_POST["people"];
          $priceValue = $_POST["price"];
          $metersValue = $_POST["meters"];
          $imgLongdescValue = $_POST['mainLongdesc'];
          $checkedServices = (new ServicesConverter())->convertToHtmlAttribute($services);
          $checkedAdditionalServices = (new ServicesConverter())->convertToHtmlAttribute($additionalServices, true);
        }
      } else {
        $userFeedbackContent .= "<li><strong class=\"error\">Errore durante l'aggiunta della camera</strong></li>";
        $nameValue = $_POST["name"];
        $peopleValue = $_POST["people"];
        $priceValue = $_POST["price"];
        $metersValue = $_POST["meters"];
        $imgLongdescValue = $_POST['mainLongdesc'];
        $checkedServices = (new ServicesConverter())->convertToHtmlAttribute($services);
        $checkedAdditionalServices = (new ServicesConverter())->convertToHtmlAttribute($additionalServices, true);
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
      if (!is_int($meters) || $meters <= 5) {
        $userFeedbackContent .= "<li><strong class=\"error\">La dimensione deve essere un numero intero maggiore di 5</strong></li>";
      } 
      if (strlen($imgLongdesc) <= 20) {
        $userFeedbackContent .= "<li><strong class=\"error\">La descrizione dell'immagine principale deve avere lunghezza maggiore di venti caratteri</strong></li>";
      }
      $nameValue = $_POST["name"];
      $peopleValue = $_POST["people"];
      $priceValue = $_POST["price"];
      $metersValue = $_POST["meters"];
      $imgLongdescValue = $_POST['mainLongdesc'];
      $checkedServices = (new ServicesConverter())->convertToHtmlAttribute($services);
      $checkedAdditionalServices = (new ServicesConverter())->convertToHtmlAttribute($additionalServices, true);
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
    if (!isset($_POST["meters"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">La dimensione è un campo obbligatorio</strong></li>";
    } 
    if (!isset($_FILES["mainImg"]) || $_FILES["mainImg"]["name"] === "") {
      $userFeedbackContent .= "<li><strong class=\"error\">L'immagine principale è un campo obbligatorio</strong></li>";
    }
    if (!isset($_POST["mainLongdesc"])) {
      $userFeedbackContent .= "<li><strong class=\"error\">La descrizione dell'immagine principale è un campo obbligatorio</strong></li>";
    }
    $nameValue = isset($_POST["name"]) ? $_POST["name"] : "";
    $peopleValue = isset($_POST["people"]) ? $_POST["people"] : "";
    $priceValue = isset($_POST["price"]) ? $_POST["price"] : "";
    $metersValue = isset($_POST["meters"]) ? $_POST["meters"] : "";
    $imgLongdescValue = isset($_POST["mainLongdesc"]) ? $_POST["mainLongdesc"] : "";
    $checkedServices = (new ServicesConverter())->convertToHtmlAttribute(isset($_POST["services"]) ? $_POST["services"] : []);
    $checkedAdditionalServices = (new ServicesConverter())->convertToHtmlAttribute(isset($_POST["additionalServices"]) ? $_POST["additionalServices"] : [], true);
  }
  $userFeedbackContent .= "</ul></div>";
}

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<InsertRoomErrorPlaceholder />", $userFeedbackContent, $html);
$html = str_replace("<NameValuePlaceholder />", $nameValue, $html);
$html = str_replace("<PeopleValuePlaceholder />", $peopleValue, $html);
$html = str_replace("<PriceValuePlaceholder />", $priceValue, $html);
$html = str_replace("<MetersValuePlaceholder />", $metersValue, $html);
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
$html = str_replace("<WardrobeCheckedPlaceholder />", $checkedServices["wardrobe"], $html);
$html = str_replace("<ParkingCheckedPlaceholder />", $checkedAdditionalServices["parking"], $html);
$html = str_replace("<WIFICheckedPlaceholder />", $checkedAdditionalServices["wifi"], $html);
$html = str_replace("<PrivateBathRoomCheckedPlaceholder />", $checkedAdditionalServices["privateBathRoom"], $html);
echo $html;
?>