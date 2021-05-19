<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
}
?>