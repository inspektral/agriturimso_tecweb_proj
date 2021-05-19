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
      $bools["$serviceName"] = in_array($serviceName, $services, true) ? true : false;
    }
    return $bools;
  }
}
?>