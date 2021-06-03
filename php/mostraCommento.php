<?php

require_once __DIR__.DIRECTORY_SEPARATOR."dbAccess.php";

class mostraCommento{
public function createNewComment() {
    $db = new DBAccess();
    $isFailed = $db->openDBConnection();

    if ($isFailed) {
      return null;
    }

    $commentContent = "";
    $comment = $db->getComments();

    $db->closeDBConnection();
    if ($comment) {
      
      foreach ($comment as $item) {
        $email = $item["email"];
        $testo = $item["testo"];
        $timestamp= $item["timestamp"];
        $voto = $item["voto"];

        $commentContent .= "<div class=\"casellacommento\">";
        $commentContent .= "<h3>$email</h3>" ;
        $commentContent .= "<p>$timestamp - $voto/5</p>";
        $commentContent .= "<blockquote><div>$testo</div></blockquote>";
        if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
          $commentContent .= "<form class=\"bottonicommento\" method=\"post\"  action=\"./diconodinoi.php\">";
          $commentContent .= "<fieldset class= \"noBorder\">";
          $commentContent .= "<input type=\"hidden\" name= \"email\" value=\"$email\" />";
          $commentContent .= "<input type=\"hidden\" name= \"timestamp\" value=\"$timestamp\" />";
          $commentContent .= "<button id=\"delete\" type=\"submit\" name=\"deleteComment\" class=\"bottonicommento\">Cancella</button>";
          $commentContent .= "</fieldset>";
          $commentContent .= "</form>";
        }
        $commentContent .= "</div>";
      }
      
    } else {
      $commentContent = "<h3>Nessun commento</h3>";
    }

    return $commentContent;
  }
}
?>