<?php
class InputCleaner {
  private const ALLOWED_NEWS_TAGS = "<em><strong><span><abbr>";
  private const ALLOWED_ROOM_TAGS = "<span>";

  public function cleanNews($input) {
    return htmlentities(strip_tags(trim($input), self::ALLOWED_NEWS_TAGS));
  }

  public function cleanRoomName($name) {
    return htmlentities(strip_tags(trim($name), self::ALLOWED_ROOM_TAGS));
  }
}
?>