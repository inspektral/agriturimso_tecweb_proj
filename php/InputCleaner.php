<?php
class InputCleaner {
  private const ALLOWED_NEWS_TAGS = ['em', 'strong', 'span'];

  public function cleanNews($input) {
    return htmlentities(strip_tags(trim($input), self::ALLOWED_NEWS_TAGS));
  }
}
?>