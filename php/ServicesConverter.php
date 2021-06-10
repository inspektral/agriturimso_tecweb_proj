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
    "towels",
    "wardrobe"
  ];

  private const ADDTITIONAL_SERVICES_NAMES = [
    "parking",
    "wifi",
    "privateBathRoom"
  ];

  public function convertToBoolean($services, $isAdditional = false) {
    $bools = [];
    $servicesNames = $isAdditional ? ServicesConverter::ADDTITIONAL_SERVICES_NAMES : ServicesConverter::SERVICES_NAMES;
    foreach ($servicesNames as $key => $serviceName) {
      $bools["$serviceName"] = in_array($serviceName, $services, true);
    }
    return $bools;
  }

  public function convertToHtmlAttribute($services, $isAdditional = false) {
    $checked = [];
    $servicesNames = $isAdditional ? ServicesConverter::ADDTITIONAL_SERVICES_NAMES : ServicesConverter::SERVICES_NAMES;
    foreach ($servicesNames as $key => $serviceName) {
      $checked["$serviceName"] = "";
      if (in_array($serviceName, $services, true)) {
        $checked["$serviceName"] = "checked=\"checked\"";
      }
    }
    var_dump($servicesNames,$checked);
    return $checked;
  }
}
?>