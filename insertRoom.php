<?php
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."UserMenu.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."dbAccess.php";
require_once __DIR__.DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."InputCleaner.php";

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
$tvValue = "";
$balconeValue = "";
$gardenValue = "";
$airValue = "";
$heatValue = "";
$parquetValue = "";
$showerValue = "";
$shampooValue = "";
$wcValue = "";
$bathValue = "";
$bidetValue = "";
$paperValue = "";
$towelsValue = "";
if (isset($_POST["submit"])) {
  $userFeedbackContent = "<div><ul>";
  if (isset($_POST["name"]) && isset($_POST["people"]) && isset($_POST["price"]) && isset($_POST["services"]) && isset($_POST["mainImg"]) 
    && isset($_POST["mainLongdesc"]) && isset($_POST["firstImg"]) && isset($_POST["secondImg"]) && isset($_POST["thirdImg"]) && isset($_POST["fourthImg"])
  ) {
    $name = $description = (new InputCleaner())->cleanRoomName($_POST["name"]);
    $people = $_POST["people"];
    $price = $_POST["price"];
    $services = array();
    $mainImg = $_POST["mainImg"];
    $imgLongdesc = $_POST['imgLongdesc'];

    if (!empty($_POST["services"])) {
      $services = $_POST["services"];
    }

    if (strlen($name) > 5 && is_int($people) && $people > 0 && is_float($price) && $price > 0.0 && strlen($imgLongdesc) > 20) {

    } else {

    }
  } else {

  }

  $userFeedbackContent .= "</ul></div>";
}

$html = str_replace("<UserPlaceholder />", $content, $html);
$html = str_replace("<InsertRoomErrorPlaceholder />", $userFeedbackContent, $html);
$html = str_replace("<NameValuePlaceholder />", $nameValue, $html);
$html = str_replace("<PeopleValuePlaceholder />", $peopleValue, $html);
$html = str_replace("<PriceValuePlaceholder />", $priceValue, $html);
$html = str_replace("<TvValuePlaceholder />", $tvValue, $html);
$html = str_replace("<BalconeValuePlaceholder />", $balconeValue, $html);
$html = str_replace("<GardenValuePlaceholder />", $gardenValue, $html);
$html = str_replace("<AirValuePlaceholder />", $airValue, $html);
$html = str_replace("<HeatValuePlaceholder />", $heatValue, $html);
$html = str_replace("<ParquetValuePlaceholder />", $paperValue, $html);
$html = str_replace("<ShowerValuePlaceholder />", $shampooValue, $html);
$html = str_replace("<ShampooValuePlaceholder />", $shampooValue, $html);
$html = str_replace("<WcValuePlaceholder />", $wcValue, $html);
$html = str_replace("<BathValuePlaceholder />", $bathValue, $html);
$html = str_replace("<BidetValuePlaceholder />", $bidetValue, $html);
$html = str_replace("<PaperValuePlaceholder />", $paperValue, $html);
$html = str_replace("<TowelsValuePlaceholder />", $towelsValue, $html);
echo $html;
?>