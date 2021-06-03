<?php
require_once __DIR__.DIRECTORY_SEPARATOR."dbAccess.php";

class NewsListFactory {
  public function createNewsList() {
    $db = new DBAccess();
    $isFailed = $db->openDBConnection();

    if ($isFailed) {
      return null;
    }

    $newsContent = "";
    $news = $db->getNews();

    $db->closeDBConnection();
    if ($news) {
      $newsContent = "<ul>";
      foreach ($news as $item) {
        $date = explode(" ", $item["date"])[0];
        $description = html_entity_decode($item["description"]);
        $newsContent .= "<li><strong>$date</strong> - $description</li>";
      }
      $newsContent .= "</ul>";
    } else {
      $newsContent = "<h3>Nessuna nuova notizia trovata</h3>";
    }

    return $newsContent;
  }
}
?>