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

        // TODO: fix
        $commentContent .="<div class=\"casellacommento\">";
        $commentContent .= " <h3><strong> $email </strong></h3> " ;
        $commentContent .= "<strong>$timestamp</strong> - $voto/5</p>";
        $commentContent .= "<cite>$testo</cite>";
        $commentContent .="<div class= \"bottonicommento\">";
        $commentContent .= "<button type=\"button\">Cancella</button>";
        $commentContent .= "</div>";
        $commentContent .= "</div>";
      }
      
    } else {
      $commentContent = "<h3>Nessun commento</h3>";
    }

    return $commentContent;
  }
}








































?>