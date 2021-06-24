<?php
require_once __DIR__.DIRECTORY_SEPARATOR."dbAccess.php";

class RoomsListFactory {
  public function createRoomsList() {
    $db = new DBAccess();
    $isFailed = $db->openDBConnection();

    if ($isFailed) {
      return null;
    }

    $roomsContent = "";
    $rooms = $db->getRooms();

    $db->closeDBConnection();
    if ($rooms) {
      foreach ($rooms as $room) {
        $name = $room["name"];
        $people = $room["people"];
        $price = number_format($room["price"], 2);
        $meters = number_format($room["meters"]);
        $mainImg = $room["mainImg"];
        $mainImgLongdesc = $room["mainImgLongdesc"];
        $firstGallery = $room["firstGallery"];
        $secondGallery = $room["secondGallery"];
        $thirdGallery = $room["thirdGallery"];
        $fourthGallery = $room["fourthGallery"];

        $roomsContent .= "<div class=\"bedroom\">";
        $roomsContent .= "<h2>$name</h2>";
        $roomsContent .= "<h3><span>$people persone</span> ";
        $roomsContent .= "<span><abbr title=\"euro\">€</abbr></span>$price a notte";
        $roomsContent .= "</h3>";
        $roomsContent .= "<img src=\"$mainImg\" alt=\"\" longdesc=\"$mainImgLongdesc\" />";
        $roomsContent .= "<ul>";
        $roomsContent .= "<li>Buona colazione inclusa</li>";
        $roomsContent .= "<li>Cancellazione <strong>GRATUITA</strong></li>";
        $roomsContent .= "<li><strong>NESSUN PAGAMENTO ANTICIPATO</strong><div>Paga in struttura</div></li>";
        $roomsContent .= "</ul>";

        $roomsContent .= "<div class=\"services\">";
        $roomsContent .= "<dl>";
        $roomsContent .= "<dt>Dimensione stanza $meters <abbr title=\"metri quadrati\">mq.</abbr></dt>";
        $roomsContent .= $room["additionalServices"]["parking"] ? "<dt>Parcheggio privato disponibile senza prenotazione</dt>" : "";
        $roomsContent .= $room["additionalServices"]["wifi"] ? "<dt><abbr xml:lang=\"en\" title=\"Wireless Fidelity\">WI-FI</abbr> gratuito</dt>" : "";
        $roomsContent .= $room["additionalServices"]["privateBathRoom"] ? "<dt>Bagno privato</dt>" : "";
        $roomsContent .= "</dl>";

        if (array_reduce($room["services"], function($accomulator, $item) { return $accomulator + $item; }, 0)) {
          $roomsContent .= "<div><h4>Servizi in camera:</h4>";
          $roomsContent .= "<ul>";
          $roomsContent .= $room["services"]["tv"] ? "<li>TV a schermo piatto</li>" : "";
          $roomsContent .= $room["services"]["balcony"] ? "<li>Balcone</li>" : "";
          $roomsContent .= $room["services"]["gardenView"] ? "<li>Vista giardino</li>" : "";
          $roomsContent .= $room["services"]["airCondition"] ? "<li>Aria condizionata</li>" : "";
          $roomsContent .= $room["services"]["heat"] ? "<li>Riscaldamento</li>" : "";
          $roomsContent .= $room["services"]["parquet"] ? "<li><span xml:lang=\"fr\">Parquet</span> o pavimento in legno</li>" : "";
          $roomsContent .= $room["services"]["shower"] ? "<li>Doccia</li>" : "";
          $roomsContent .= $room["services"]["shampoo"] ? "<li>Prodotti da bagno in omaggio</li>" : "";
          $roomsContent .= $room["services"]["wc"] ? "<li><abbr xml:lang=\"en\" title=\"water closet\">WC</abbr></li>" : "";
          $roomsContent .= $room["services"]["bath"] ? "<li>Vasca</li>" : "";
          $roomsContent .= $room["services"]["bidet"] ? "<li xml:lang=\"fr\">Bidet</li>" : "";
          $roomsContent .= $room["services"]["paper"] ? "<li>Carta igienica</li>" : "";
          $roomsContent .= $room["services"]["towels"] ? "<li>Asciuga mani</li>" : "";
          $roomsContent .= $room["services"]["wardrobe"] ? "<li>Armadio o guardaroba</li>" : "";
          $roomsContent .= "</ul></div>";
        }
        $roomsContent .= "</div>";  
        $roomsContent .= "<div class=\"bookContainer\"><a class=\"button\" href=\"./prenota.php?nomeCamera=".$name."\">Verifica disponibilità</a></div>";
        $roomsContent .= "<div class=\"gallery\">";
        $roomsContent .= "<img src=\"$firstGallery\" alt=\"\" class=\"galleryElement\" />";
        $roomsContent .= "<img src=\"$secondGallery\" alt=\"\" class=\"galleryElement\" />";
        $roomsContent .= "<img src=\"$thirdGallery\" alt=\"\" class=\"galleryElement\" />";
        $roomsContent .= "<img src=\"$fourthGallery\" alt=\"\" class=\"galleryElement\" />";
        $roomsContent .= "</div>";
        $roomsContent .= "</div>";
      }
    } else {
      $roomsContent = "<h3>Nessuna camera trovata</h3>";
    }

    return $roomsContent;
  }
}
?>