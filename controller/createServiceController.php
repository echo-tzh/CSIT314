<?php
include '../entity/service.php';

class createServiceController {
    public function createService(string $serviceName, string $description, float $price, string $serviceDate, int $cleanerID, int $categoryID):bool {
        $service = new service();
        
        $result = $service->createService($serviceName, $description, $price, $serviceDate, $cleanerID, $categoryID);
        return $result; 
    }
}
?>