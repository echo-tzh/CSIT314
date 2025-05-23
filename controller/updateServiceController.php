<?php
require_once '../entity/service.php';

class updateServiceController {
    public function updateService(
        int $serviceID,
        string $newName,
        string $newDescription,
        float $newPrice,
        DateTime $newServiceDate,
        int $newCleanerID,
        int $newCategoryID,
        
    ):bool {
        $service = new service();
        $newServiceDateString = $newServiceDate->format('Y-m-d H:i:s');
        return $service->updateService(
            $serviceID,
            $newName,
            $newDescription,
            $newPrice,
            $newServiceDateString,
            $newCleanerID,
            $newCategoryID,          
        );
    }
}
?>