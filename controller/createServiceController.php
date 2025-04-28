<?php
include '../entity/service.php';

class CreateServiceController {
    public function createService(string $serviceName, string $description, float $price, string $serviceDate, int $cleanerID, int $categoryID) {
        $service = new Service();
        // Assuming your Service entity's createService method matches these parameters
        $result = $service->createService($serviceName, $description, $price, $serviceDate, $cleanerID, $categoryID);
        return $result === true; // Or however you determine success in your entity
    }
}
?>