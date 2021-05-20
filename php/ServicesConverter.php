<?php
class ServicesConverter {
  private const SERVICES_NAMES = [
    "tv",
    "balcony",
    "gardenView",
    "airCondition",
    "heat",
    "parquet",
    "shower",
    "shampoo",
    "wc",
    "bath",
    "bidet",
    "paper",
    "towels"
  ];

  public function convertToBoolean($services) {
    $bools = [];
    foreach (ServicesConverter::SERVICES_NAMES as $key => $serviceName) {
      $bools["$serviceName"] = in_array($serviceName, $services, true);
    }
    return $bools;
  }

  public function convertToHtmlAttribute($services) {
    $checked = [];
    foreach (ServicesConverter::SERVICES_NAMES as $key => $serviceName) {
      $checked["$serviceName"] = "";
      if (in_array($serviceName, $services, true)) {
        $checked["$serviceName"] = "checked=\"checked\"";
      }
    }
    return $checked;
  }
}
?>