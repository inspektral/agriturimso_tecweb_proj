<?php
class ImageUploader {
  public function upload($file,$path) {
    $imageFileType = strtolower(pathinfo($path,PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
      return false;
    }
    return move_uploaded_file($file,$path);
  }
}
?>