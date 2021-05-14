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
    print_r($news);
    $db->closeDBConnection();
    if ($news) {
      $newsContent = "<ul>";
      foreach ($news as $item) {
        $date = $item["date"];
        $description = $item["description"];
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